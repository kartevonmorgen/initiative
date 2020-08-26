<?php

// Add a Column 'Bestätigt' to the user table
add_filter( 'manage_users_columns', 'initiative_modify_user_table' );
function initiative_modify_user_table( $column ) 
{
  unset($column['posts']);
  $column['initiative'] = 'Initiative';
  $column['approved'] = 'Bestätigt';
  $column['kvm_upload'] = 'KVM Upload';
  return $column;
}

add_filter( 'manage_users_custom_column', 'initiative_modify_user_table_row', 10, 3 );
function initiative_modify_user_table_row( $val, $column_name, $user_id ) 
{
  if ('initiative' == $column_name) 
  {
    $user_meta = get_userdata($user_id);
    
    $initiative_id = get_user_meta($user_id, 'initiative_id', true);
    if(empty($user_meta->initiative_id))
    {
      return null;
    }
    
    $initiative_post = get_post($user_meta->initiative_id);
    return '<a href="'.admin_url('post.php?post='.$initiative_post->ID.'&action=edit').'">'.$initiative_post->post_title.'</a>';
  }

  if ('approved' == $column_name) 
  {
    $user_meta = get_userdata($user_id);
    $user_roles = $user_meta->roles;
    if ( in_array( 'subscriber', $user_roles, true ) ) 
    {
      return '<b>NEIN</b>';
    }
    else
    {
      return 'JA';
    }
  }

  if ('kvm_upload' == $column_name) 
  {
    $user_meta = get_userdata($user_id);
    $kvm_upload = $user_meta->initiative_kvm_upload;
    if ( $kvm_upload ) 
    {
      return 'JA';
    }
    else
    {
      return '<b>NEIN</b>';
    }
  }
  return $val;
}

//
// make the KVM-Upload column sortable
//
add_filter( 'manage_users_sortable_columns', 
            'initiative_user_sortable_columns' );
function initiative_user_sortable_columns( $columns ) 
{
  $columns['kvm_upload'] = 'kvm_upload';
  return $columns;
}
 
//set instructions on how to sort the kvm_upload column
if( is_admin()) 
{
   add_action('pre_user_query', 'initiative_user_query');
}

function initiative_user_query($userquery)
{
  if('kvm_upload' == $userquery->query_vars['orderby']) 
  {
    global $wpdb;
    $userquery->query_from .= " LEFT OUTER JOIN $wpdb->usermeta AS alias ON ($wpdb->users.ID = alias.user_id) ";
    //note use of alias
    $userquery->query_where .= " AND alias.meta_key = 'initiative_kvm_upload' ";
    //which meta are we sorting with?
    $userquery->query_orderby = " ORDER BY alias.meta_value ".

    ($userquery->query_vars["order"] == "ASC" ? "asc " : "desc ");
    //set sort order
  }
}
