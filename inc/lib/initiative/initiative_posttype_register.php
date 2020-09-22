<?php
// 
// Register out own custom post type for Initiative.
//
add_action( 'init', 'reg_posttype' );
function reg_posttype() {

$labels = array(
		'name' => _x( 'Initiativen', 'post type general name', 'initiative' ),
		'singular_name'      => _x( 'Initiative', 'post type singular name', 'initiative' ),
		'menu_name'          => _x( 'Initiativen', 'admin menu', 'initiative' ),
		'name_admin_bar'     => _x( 'Initiative', 'add new on admin bar', 'initiative' ),
		'add_new'            => _x( 'Erstellen', 'initiative', 'initiative' ),
		'add_new_item'       => __( 'Initiative erstellen', 'initiative' ),
		'new_item'           => __( 'Neue Initiative', 'initiative' ),
		'edit_item'          => __( 'Initiative bearbeiten', 'initiative' ),
		'view_item'          => __( 'Initiative Anschauen', 'initiative' ),
		'all_items'          => __( 'Alle Initiativen', 'initiative' ),
		'search_items'       => __( 'Initiativen Suche', 'initiative' ),
		'parent_item_colon'  => __( 'Parent Initiativen:', 'initiative' ),
		'not_found'          => __( 'Keine initiatives gefunden.', 'initiative' ),
		'not_found_in_trash' => __( 'Keine initiatives gefunden im Papierkorb.', 'initiative' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'initiative' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'initiative' ),
//		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'show_in_rest'       => true,
    'taxonomies'         => array( 'category', 'post_tag' ),
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
    'register_meta_box_cb' => 'initiative_meta_boxes',
    'capabilities'       => array('create_posts' => false),
    'map_meta_cap' => true
	);

	register_post_type( 'initiative', $args );
 
}

// 
// Make Tags and Categorie query's with custom-post-types
// So Custom Post Types as Posts are shown for the catebories.
add_filter('pre_get_posts', 'query_post_type');

function query_post_type($query) 
{
  if( is_category() || is_tag()) 
  {
    $post_type = get_query_var('post_type');
    if($post_type)
    {
      $post_type = $post_type;
    }
    else
    {
      // don't forget nav_menu_item to allow menus to work!
      $post_type = array('nav_menu_item', 'post', 'initiative'); 
    }
    $query->set('post_type',$post_type);

  }
  return $query;
}

// 
// Do not show Posts of other Authors in Admin
//
add_filter('pre_get_posts', 'posts_for_current_author');
function posts_for_current_author($query) 
{
  global $pagenow;
 
  if( 'edit.php' != $pagenow )
  {
    return $query;
  }

  // Wir sind nicht im Admin Bereich
  if( !$query->is_admin )
  {
    return $query;
  }

  // If the user can edit someone else its posts, 
  // we want to show them 
  if( current_user_can( 'edit_others_posts' ) ) 
  {
    return $query;
  }
  
  // If the user can not edit other posts,
  // we do not want to show them, so we 
  // only get the posts of the current user
  global $user_ID;
  $query->set('author', $user_ID );
  return $query;
}

add_action('pre_get_posts','users_own_attachments');
function users_own_attachments( $wp_query_obj ) 
{
  global $current_user, $pagenow;

  if( !is_a( $current_user, 'WP_User') )
  {
    return;
  }

  if( ( 'upload.php' != $pagenow ) &&
      (( 'admin-ajax.php' != $pagenow ) || 
      ( $_REQUEST['action'] != 'query-attachments' ) ) )
  {
    return;

  }
  
  if( !current_user_can('delete_pages') )
  {
    $wp_query_obj->set('author', $current_user->id );
  }
}



//
// Search for Initiative by Type (Company or Initiative)
//
add_filter('pre_get_posts', 'initiative_type');
function initiative_type($query) 
{
  // Wir sind nicht im Admin Bereich
  if( $query->is_admin )
  {
    return $query;
  }

  if(!$query->is_main_query())
  {
    return $query;
  }

  if( ! $query->is_post_type_archive(array('initiative')))
  {
    return $query;
  }

  $is_first = $_GET['first'];
  if(isset($is_first))
  {
    $_POST['search_company'] = 1;
    $_POST['search_initiative'] = 1;
  }

  $search_company = $_POST['search_company'];
  $search_initiative = $_POST['search_initiative'];
  $search_term = $_POST['search_term'];

  if( isset($search_term))
  {
    $query->set('s', $search_term);
  }

  if( isset( $search_company) &&
      isset( $search_initiative) )
  {
    return $query;
  }

  $meta_query =  array('relation' => 'OR');
  if( isset( $search_company))
  {
    array_push($meta_query,
       array(
         'key' => 'initiative_company',
         'compare'	=> '=',
         'value'	=> '1'));
  }
  else if( isset($search_initiative))
  {
    array_push($meta_query,
        array(
          'key' => 'initiative_company',
          'compare'	=> '=',
          'value'	=> ''));
  }
  else
  {
    array_push($meta_query,
       array(
         'key' => 'initiative_company',
         'compare'	=> '=',
         'value'	=> '2'));
  }

  $query->set('meta_query', $meta_query); 
  return $query;
}

/**
 * Register meta box(es).
 */
function initiative_meta_boxes() {
    add_meta_box( 'initiative_kvm_log', __( 'Karte von Morgen Log', 'initiative' ), 'initiative_display_callback' );
}
 
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function initiative_display_callback( $post ) {
    // Display code/markup goes here. Don't forget to include nonces!
    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'initiative_kvm_log_nonce', 'initiative_kvm_log_nonce' );

    $value = get_post_meta( $post->ID, 'initiative_kvm_log', true );

    echo '<textarea style="width:100%" id="initiative_kvm_log" name="initiative_kvm_log" disabled="true">' . esc_attr( $value ) . '</textarea>';
}



//add_action( 'pre_get_posts', 'textdomain_include_search' );
//function textdomain_include_search($query) 
//{
//  if ( !is_admin() && $query->is_main_query() ) 
//  {
//    if ($query->is_search) 
//    {
//      $query->set('post_type', array( 'initiative', 
//                                      EM_POST_TYPE_EVENT ) );
//    }
//  }
//}

?>
