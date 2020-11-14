<?php

class InControlHolder extends WPPluginStarter
{
  private $userRegister;
  private $userProfile;
 
  public function start()
  {
    $userRegister = new InUserRegisterControl();
    $userRegister->init();

    $userProfile = new InUserProfileControl();
    $userProfile->init();
  }
}
