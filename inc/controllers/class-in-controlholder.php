<?php

class InControlHolder 
{
  private static $instance = null;

  private $userRegister;
  private $userProfile;
 
  private function __construct()
  {
  }

  /** 
   * The object is created from within the class itself
   * only if the class has no instance.
   */
  public static function get_instance()
  {
    if (self::$instance == null)
    {
      self::$instance = new InControlHolder();
    }
    return self::$instance;
  }

  public function init()
  {
    $userRegister = new InUserRegisterControl();
    $userRegister->init();

    $userProfile = new InUserProfileControl();
    $userProfile->init();
  }



}
