<?php

// Add an possibility to approve a user after registration
// approve means we change the role to 'author'
// so the user can create events and edit its initiative-page
add_filter('user_row_actions','initiative_user_action_links',10,2);
function initiative_user_action_links( $actions, $user )
{
  $user_meta = get_userdata($user->ID);
  $user_roles = $user_meta->roles;
  if ( in_array( 'subscriber', $user_roles, true ) ) 
  {
    $approve_link = admin_url( "users.php?page=user_approve&amp;action=user_approve&amp;user=$user->ID");
    $actions['user_approve'] = "<a href='$approve_link'><b>" . __( 'Bestätigen','initiative') . "</b></a>";
  }
  else
  {
    $update_link = admin_url( "users.php?page=user_updatekvm&amp;action=user_updatekvm&amp;user=$user->ID");
    $actions['user_updatekvm'] = "<a href='$update_link'><b>" . __( 'Add/Update KVM','initiative') . "</b></a>";

    $update_link = admin_url( "users.php?page=user_loadkvm&amp;action=user_loadkvm&amp;user=$user->ID");
    $actions['user_loadkvm'] = "<a href='$update_link'><b>" . __( 'Load KVM','initiative') . "</b></a>";
  }

	return $actions;
}

add_action('admin_menu', 'initiative_admin_submenu_userapprove');
function initiative_admin_submenu_userapprove() 
{         
  add_users_page('Benutzer bestätigen', 'Benutzer bestätigen', 
    'manage_options', 'user_approve' , 
    'initiative_user_approve_functions');
  
  add_users_page('Benutzer Update KVM', 'Benutzer Update KVM', 
    'manage_options', 'user_updatekvm' , 
    'initiative_user_updatekvm_functions');

  add_users_page('Benutzer Load KVM', 'Benutzer Load KVM', 
    'manage_options', 'user_loadkvm' , 
    'initiative_user_loadkvm_functions');
}

// 
// Change user role to 'author'
//
function initiative_user_approve_functions() 
{
  if(!isset($_GET['action']) && $_GET['action']== 'user_approve')
  {
    return;
  }
  
  $user_id = $_GET['user'];

  $result = wp_update_user(array('ID'=>$user_id, 'role'=>'author'));

  if ( is_wp_error( $result ) ) 
  {
    // There was an error, probably that user doesn't exist.
    echo 'Benutzer id ' . $user_id;
    echo $result->get_error_message();
    return;
  }
  $user_meta = get_userdata($user_id);
    
  // Success!
  echo 'Benutzer ' . $user_meta->display_name . ' (id=' . $user_id . '), Initiative: ' . $user_meta->initiative_name;
  echo ' ist bestätigt</br>';
  if( $user_meta->initiative_id > 0 )
  {
    $ipost = get_post($user_meta->initiative_id);
    if(!empty($ipost))
    {
      if(get_post_status($ipost) !== 'trash')
      {
        echo 'Initiative ist bereits erstellt ' . 
          $user_meta->initiative_name . 
          ' (id=' .$user_meta->initiative_id;
        echo '</br>';
        return;
      }
    }
  }

  echo 'Initiative erstellen ' . $user_meta->initiative_name;
  echo '</br>';
  
  $post_name = sanitize_title( $user_meta->initiative_name     );
  $ipost = array(
    'comment_status' => 'closed',
    'post_author' => $user_id,
    'post_category' => array(1),
    'post_content' => '<!-- wp:paragraph -->'.
                        '<p>Schreibe hier etwas über deine Initiative</p>'.
                        '<!-- /wp:paragraph -->',
    'post_title' => $user_meta->initiative_name,
    'post_name' => $post_name,
    'post_status' => 'draft',
    'post_type' => 'initiative');

  // Insert the post into the database
  $ipostid = wp_insert_post( $ipost, true );
  update_user_meta($user_id, 'initiative_id', $ipostid);

  echo 'Webseite: ' . $user_meta->user_url;
  echo '</br>';

  if(!empty($user_meta->user_url))
  {
    return;
  }


  $website = home_url('initiative/' . $post_name); 
  echo 'UUpdate Webseite: ' . $website;
  echo '</br>';
  $args = array(
    'ID' => $user_id,
    'user_url' => $website);
  wp_update_user( $args );
}

function initiative_user_updatekvm_functions() 
{
  if(!isset($_GET['action']) && $_GET['action']== 'user_updatekvm')
  {
    return;
  }

  $user_id = $_GET['user'];
  $save_entry = new InitiativeSaveKVMEntry($user_id);
  $save_entry->save();
}

// 
// Update Get KVM
//
function initiative_user_loadkvm_functions() 
{
  if(!isset($_GET['action']) && $_GET['action']== 'user_loadkvm')
  {
    return;
  }

  $user_id = $_GET['user'];
  $load_entry = new InitiativeLoadKVMEntry($user_id);
  $load_entry->load();
}
