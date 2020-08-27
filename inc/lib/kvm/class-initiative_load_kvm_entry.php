<?php

class InitiativeLoadKVMEntry
{
  private $_user_id;
  
  public function __construct($user_id) 
  {
    $this->_user_id = $user_id;
  }

  public function get_user_id()
  {
    return $this->_user_id;
  }

  public function load()
  {
    if (!class_exists('KVMInterface')) 
    { 
      echo 'Plugin KVM Interface not found';
      return;
    }

    $user_id = $this->get_user_id();
    $user_meta = get_userdata($user_id);
    if(empty($user_meta))
    {
      echo 'user_meta is empty for user_id:' . $user_id;
      return;
    }

    $kvm_id = $user_meta->initiative_kvm_id;
    if(empty($kvm_id))
    {
      echo 'Karte von Morgen Entry Id not setted '.
        'in the User Profile settings for user_id:' . 
        $user_id;
      return;
    }

    $instance = KVMInterface::get_instance();
    $wpInitiativen = $instance->get_entries_by_ids(
      array($kvm_id));

    $wpInitiative = reset($wpInitiativen);
    $this->fill_usermeta($user_id, $user_meta, $wpInitiative);

    if( $user_meta->initiative_id > 0 )
    {
      $initiative_post = get_post($user_meta->initiative_id);
      if(!empty($initiative_post))
      {
        $this->fill_initiative_post($initiative_post,
                                    $wpInitiative );
      }
    }

    echo 'Load Initiative from KVM for kvm_id ' . 
      $kvm_id . ' sucessfully';
  }

  private function fill_usermeta($user_id,
                                 $user_meta,
                                 $wpInitiative)
  {
    $args = array('ID' => $user_id);


    /*
    $args['initiative_company'] = 
               $wpInitiative->is_company();

    if( ! empty( $wpInitiative->get_contact_firstname()))
    {
      $args['first_name'] = 
        $wpInitiative->get_contact_firstname(); 
    }

    if( ! empty( $wpInitiative->get_contact_lastname()))
    {
      $args['last_name'] = 
        $wpInitiative->get_contact_lastname(); 
    }

    if( ! empty( $wpInitiative->get_contact_phone()))
    {
      $args['dbem_phone'] = 
        $wpInitiative->get_contact_phone();
    }

    if( ! empty( $wpInitiative->get_contact_website()))
    {
      $args['user_url'] = 
        $wpInitiative->get_contact_website();
    }

    if( ! empty( $wpInitiative->get_contact_email()))
    {
      $args['user_email'] = 
        $wpInitiative->get_contact_email();
    }
    */

    $this->fill_location($wpInitiative->get_location(), 
                         $args);
    wp_update_user( $args );
  }

  private function fill_location($wpLocation, $args)
  {
    $wpLocHelper = new WPLocationHelper();
    if( ! empty( $wpLocHelper->get_address($wpLocation)))
    {
      $args['initiative_address'] = 
        $wpLocHelper->get_address($wpLocation);
    }

    if( ! empty( $wpLocation->get_zip()))
    {
      $args['initiative_zipcode'] = 
        $wpLocation->get_zip();
    }

    if( ! empty( $wpLocation->get_city()))
    {
      $args['initiative_city'] = 
        $wpLocation->get_city();
    }

    if( ! empty( $wpLocation->get_lat()))
    {
      $args['initiative_lat'] = 
        $wpLocation->get_lat();
    }

    if( ! empty( $wpLocation->get_lon()))
    {
      $args['initiative_lng'] = 
        $wpLocation->get_lon();
    }

    return $wpLocation;
  }

  private function fill_initiative_post($initiative_post,
                                        $wpInitiative )
  {
    $ipost = array();
    $ipost['ID'] = $initiative_post->ID;

    if(!empty($wpInitiative->get_name()))
    {
      $ipost['post_title'] = 
        $wpInitiative->get_name();
    }

    if(!empty($wpInitiative->get_description()))
    {
      $ipost['post_content'] = 
        '<!-- wp:paragraph -->'.
        $wpInitiative->get_description() .
        '<!-- /wp:paragraph -->';
    }
    wp_update_post( $ipost );

    $wpTagsStr = array();
    foreach($wpInitiative->get_tags() as $wpTag)
    {
      array_push( $wpTagsStr, $wpTag->get_slug());
    }

    wp_add_post_tags($initiative_post->ID, $wpTagsStr);
  }

}
