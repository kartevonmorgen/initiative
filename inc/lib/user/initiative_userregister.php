<?php

//
// Change Logo on Register and Login Page
add_action( 'login_enqueue_scripts', 
            'initiative_login_logo_one' );

function initiative_login_logo_one() 
{ 
?><style type="text/css"> 
body.login div#login h1 a 
{
  background-image: url(/images/lippe-von-morgen-logo.png);   
  background-size: 310px;
  width: 510px;
  height: 130px;
  padding-bottom: 30px; 
} 
div#login
{
  width: 510px;
}
input#user_email
{
  background-color:<?php echo InUserRegisterView::DS_BGCOLOR ?>;
}
</style><?php 
} 

add_filter( 'wp_new_user_notification_email', 'initiative_wp_new_user_notification_email', 10, 3 );
function initiative_wp_new_user_notification_email( 
  $wp_new_user_notification_email, $user, $blogname ) 
{
  $key = get_password_reset_key( $user );
  $user_login = stripslashes( $user->user_login );
  $user_email = stripslashes( $user->user_email );
  $login_url  = wp_login_url();
  $link = network_site_url( 'wp-login.php?action=rp&key=' . $key . '&login=' . rawurlencode( $user->user_login)); 
  $message = '<p>Hallo ' . $user->user_login. '</p>';
  $message .= '<p>Wilkommen auf der ' . $blogname . ' Plattform</p>';

  $message .= '<p>Benutzername: ' . $user_login . '</p>';
  $message .= '<p>Email: ' . $user_email . '</p>';
  $message .= '<p>Die Registrierung wird manuell bestätigt durch die Redaktion von Lippe von Morgen. Sobald die Registrierung freigegeben ist werden Sie eine Rückmeldung bekommen</p>';
  $message .= '<p>Bitte bestätigen Sie die Registrierung jetzt schon über den folgenden Link und stellen Sie ein Passwort ein: ';
  $message .= '<a href="' . $link . '">' . $link . '</a>';
 
  $message .= '<p>Wenn Sie Probleme haben, kontaktieren Sie Bitte die folgende Email-Adresse: ' . get_option('admin_email') . '</p>';
  $message .= '<p>Viel Erfolg auf dem Plattform!</p>';
 
//  $wp_new_user_notification_email['subject'] = sprintf( '[%s] Your credentials.', $blogname );
  $wp_new_user_notification_email['headers'] = array('Content-Type: text/html; charset=UTF-8');
  $wp_new_user_notification_email['message'] = $message;
 
  return $wp_new_user_notification_email;
}
