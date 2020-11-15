<?php
/**
 * Plugin Name:       WP Initiative
 * Description:       Adding a Posttype Initiative and allow to Register
 * Plugin URI:        https://www.lippevonmorgen.de
 * Version:           1.0.0
 * Author:            Sjoerd Takken
 * Author URI:        https://www.sjoerdscomputerwelten.de/
 * Requires at least: 3.0.0
 * Tested up to:      5.5.2
 *
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$loaderClass = WP_PLUGIN_DIR . '/wp-libraries/inc/lib/plugin/class-wp-pluginloader.php';
if(!file_exists($loaderClass))
{
  echo "Das Plugin 'wp-libraries' muss erst installiert und aktiviert werden";
  exit;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
include_once( $loaderClass);

class WPInitiativePluginLoader extends WPPluginLoader
{
  public function init()
  {
    $this->add_dependency('wp-libraries/wp-libraries.php');

    $this->add_include('inc/lib/kvm/initiative2kvm.php');
    $this->add_include('inc/lib/kvm/class-initiative_load_kvm_entry.php');
    $this->add_include('inc/lib/kvm/class-initiative_save_kvm_entry.php');
    $this->add_include('inc/lib/user/initiative_userregister.php');
    $this->add_include('inc/lib/user/initiative_usertable_actions.php');
    $this->add_include('inc/lib/user/initiative_usertable_columns.php');
    $this->add_include('inc/lib/dashboard/initiative_dashboard.php');
    $this->add_include('inc/lib/translate/initiative_changependingstate.php');
    $this->add_include('inc/lib/initiative/initiative_posttype_register.php');

    $this->add_include('inc/views/class-in-userprofileview.php');
    $this->add_include('inc/views/class-in-userregisterview.php');
    $this->add_include('inc/models/class-in-usermodel.php');
    $this->add_include('inc/models/class-ui-usermeta_initiative_modeladapter.php');
    $this->add_include('inc/controllers/class-in-userprofilecontrol.php');
    $this->add_include('inc/controllers/class-in-userregistercontrol.php');
    $this->add_include('inc/controllers/class-in-controlholder.php');

    // 
    // Remove Menus for Authors, which they do not need
    // 
    add_action( 'admin_menu', 
                array($this, 'remove_menus'), 
                999 );
    add_filter( 'excerpt_more', 
                array($this, 'excerpt_more') );
  }

  public function start()
  {
    // We are already in the 'init' action,
    // so we call this directly.
    initiative_reg_posttype();
    $this->add_starter ( new InControlHolder());
  }

  public function remove_menus()
  {
    if( !current_user_can( 'edit_pages' ))
    {
      remove_menu_page('edit.php');
      remove_menu_page('edit-comments.php');
      remove_menu_page('tools.php');
    }
  }

  /**
   * Change the excerpt more string
   */
  function initiative_excerpt_more( $more ) 
  {
    return ' [..]';
  }
}

$loader = new WPInitiativePluginLoader();
$loader->register( __FILE__ , 20);

?>
