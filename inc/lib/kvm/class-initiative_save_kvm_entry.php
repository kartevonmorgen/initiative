<?php

class InitiativeSaveKVMEntry
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

  public function save()
  {
    if (!class_exists('KVMInterface')) 
    { 
      //echo 'Plugin Events KVM Interface not found';
      return;
    }

    $user_id = $this->get_user_id();
    $user_meta = get_userdata($user_id);
    if(empty($user_meta))
    {
      //echo 'user_meta is empty for user_id:' . $user_id;
      return;
    }

    if(!$user_meta->initiative_kvm_upload)
    {
      //echo 'No upload allowed for user_id:' . $user_id;
      return;
    }


    $wpInitiative = new WPInitiative();
    $this->fill_initiative_user($wpInitiative, $user_meta);

    if( $user_meta->initiative_id > 0 )
    {
      $initiative_post = get_post($user_meta->initiative_id);
      if(!empty($initiative_post))
      {
        $this->fill_initiative_post($wpInitiative, 
                                    $initiative_post);
      }

    }
    $instance = KVMInterface::get_instance();
    $kvm_id = $instance->save_entry($wpInitiative);

    update_user_meta($user_id, 'initiative_kvm_id', $kvm_id);

  }

  private function fill_initiative_user($wpInitiative, 
                                        $user_meta)
  {
    if( ! empty( $user_meta->initiative_kvm_id))
    {
      $wpInitiative->set_kvm_id($user_meta->initiative_kvm_id);
    }

    $wpInitiative->set_company($user_meta->initiative_company);

    if( ! empty( $user_meta->first_name))
    {
      $wpInitiative->set_contact_firstname(
        $user_meta->first_name);
    }

    if( ! empty( $user_meta->last_name))
    {
      $wpInitiative->set_contact_lastname(
        $user_meta->last_name);
    }

    if( ! empty( $user_meta->dbem_phone))
    {
      $wpInitiative->set_contact_phone(
        $user_meta->dbem_phone);
    }

    if( ! empty( $user_meta->user_url))
    {
      $wpInitiative->set_contact_website(
        $user_meta->user_url);
    }

    if( ! empty( $user_meta->user_email))
    {
      $wpInitiative->set_contact_email(
        $user_meta->user_email);
    }

    $wpInitiative->set_location(
      $this->create_location($user_meta));
  }

  private function create_location($user_meta)
  {
    $wpLocation = new WPLocation();
    if( ! empty( $user_meta->initiative_name))
    {
      $wpLocation->set_name($user_meta->initiative_name);
    }

    if( ! empty( $user_meta->initiative_address))
    {
      $wpLocHelper = new WPLocationHelper();
      $wpLocHelper->set_address(
        $wpLocation, $user_meta->initiative_address);
    }

    if( ! empty( $user_meta->initiative_zipcode))
    {
      $wpLocation->set_zip($user_meta->initiative_zipcode);
    }

    if( ! empty( $user_meta->initiative_city))
    {
      $wpLocation->set_city($user_meta->initiative_city);
    }

    if( ! empty( $user_meta->initiative_lat))
    {
      $wpLocation->set_lat($user_meta->initiative_lat);
    }

    if( ! empty( $user_meta->initiative_lng))
    {
      $wpLocation->set_lon($user_meta->initiative_lng);
    }

    return $wpLocation;
  }

  private function fill_initiative_post($wpInitiative, 
                                        $initiative_post)
  {
    $wpInitiative->set_id($initiative_post->ID);
    $wpInitiative->set_user_id($initiative_post->post_author);

    if(!empty($initiative_post->post_title))
    {
      $wpInitiative->set_name(
        $initiative_post->post_title);
    }

    if(!empty($initiative_post->post_excerpt))
    {
      $wpInitiative->set_description(
        $initiative_post->post_excerpt);
    }
    else if( !empty($initiative_post->post_content))
    {
      $wpInitiative->set_description(
        wp_trim_excerpt('', $initiative_post));
    }
    else
    {
      $wpInitiative->set_description('');
    }

    $posttags = get_the_tags($initiative_post->ID );
    if (!empty($posttags)) 
    {
       foreach($posttags as $tag) 
       {
         $wpInitiative->add_tag(new WPTag($tag->name, 
                                          $tag->slug));
       }
    }
    $postcats = get_the_category($initiative_post->ID );
    if (!empty($postcats)) 
    {
       foreach($postcats as $cat) 
       {
         $wpInitiative->add_category(
           new WPCategory($cat->name, $cat->slug));
       }
    }
  }
}
