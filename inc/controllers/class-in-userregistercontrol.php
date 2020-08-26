<?php
/**
  * Controller InRegisterControl
  *
  * @author  	Sjoerd Takken
  * @copyright 	No Copyright.
  * @license   	GNU/GPLv2, see https://www.gnu.org/licenses/gpl-2.0.html
  */
class InUserRegisterControl extends UIControl
{
  public function init() 
  {
    $model = new InUserModel();
    $model->init();
    $this->set_model($model);

    $view = new InUserRegisterView($this);
    $view->init();
    $this->set_view($view);


    // Registrierung
    add_action( 'register_form', 
      array($this, 'start_register') );

    add_filter( 'registration_errors',
                array($this,'validate_register'), 10, 3 );
    add_action( 'user_register', 
                array($this,'save_register'));

  }

  public function start_register()
  {
    $this->load();
    $this->get_view()->show();
  }

  public function validate_register( $errors,
                            $user_login, 
                            $user_email )
  {
    return $this->validate( $errors );
  }

  public function save_register($user_id)
  {
    $this->load();
    $this->set_property(UIModel::USER_ID, $user_id);
    $this->save();

    // Doing extra updates
    $name = $this->get_value('first_name') . 
      ' ' . $this->get_value('last_name');

    $args = array(
      'ID' => $user_id,
      'display_name' => $name,
      'user_url' => $this->get_value('initiative_url'));
    wp_update_user( $args );
  }

}
