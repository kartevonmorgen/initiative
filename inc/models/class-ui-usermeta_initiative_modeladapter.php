<?php

class UIUserMetaInitiativeModelAdapter 
  extends UIUserMetaModelAdapter
{
  public function save_value()
  {
    $user_id = $this->get_property(UIModel::USER_ID);
    if(empty($user_id))
    {
      return;
    }
    $value = $this->get_value();
    if(empty($value))
    {
      $value = '';
    }
    update_user_meta( $user_id, 
                      $this->get_id(),
                      $value);

    $ma = $this->get_model()->get_modeladapter(
                                'initiative_id');
    // Because the field is disabled, it is not filled
    // after an update, so we reload it, to make sure
    // that a value exist.
    $ma->load_value();

    $initiative_id = $ma->get_value();
    if(empty($initiative_id))
    {
      return;
    }

    // Also save in the Initiative Post the value.
    update_post_meta($initiative_id, 
                     $this->get_id(),
                     $value);
  }
}
