<?php
/**
 * Plugin Name:       Initiative Customizations 
 * Description:       Adding a Posttype Initiative and allow to Register
 * Plugin URI:        https://www.lippevonmorgen.de
 * Version:           1.0.0
 * Author:            Sjoerd Takken
 * Author URI:        https://www.sjoerdscomputerwelten.de/
 * Requires at least: 3.0.0
 * Tested up to:      5.5.2
 *
 */

if ( ! defined( 'ABSPATH' ) ) 
{
  exit; // Exit if accessed directly.
}

include('inc/lib/kvm/initiative2kvm.php');
include('inc/lib/kvm/class-initiative_load_kvm_entry.php');
include('inc/lib/kvm/class-initiative_save_kvm_entry.php');
include('inc/lib/user/initiative_userregister.php');
include('inc/lib/user/initiative_usertable_actions.php');
include('inc/lib/user/initiative_usertable_columns.php');
include('inc/lib/dashboard/initiative_dashboard.php');
include('inc/lib/translate/initiative_changependingstate.php');
include('inc/lib/initiative/initiative_posttype_register.php');

add_action( 'init', 'init_mvc' );

function init_mvc()
{
  include_once('inc/views/class-in-userprofileview.php');
  include_once('inc/views/class-in-userregisterview.php');
  include_once('inc/models/class-in-usermodel.php');
  include_once('inc/models/class-ui-usermeta_initiative_modeladapter.php');
  include_once('inc/controllers/class-in-userprofilecontrol.php');
  include_once('inc/controllers/class-in-userregistercontrol.php');
  include_once('inc/controllers/class-in-controlholder.php');

  $uiControl = InControlHolder::get_instance();
  $uiControl->init();
}


// 
// Remove Menus for Authors, which they do not need
// 
add_action( 'admin_menu', 'remove_menus', 999 );

function remove_menus()
{
  if( !current_user_can( 'edit_pages' ))
  {
//    remove_menu_page('index.php');
    remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
    remove_menu_page('tools.php');
//    remove_submenu_page('edit.php?post_type=initiative',
//                        'post-new.php?post_type=initiative');
  }
}

/**
 * Change the excerpt more string
 */
 function initiative_excerpt_more( $more ) {
     return ' [..]';
 }
 add_filter( 'excerpt_more', 'initiative_excerpt_more' );

?>
