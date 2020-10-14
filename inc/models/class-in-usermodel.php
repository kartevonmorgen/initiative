<?php

class InUserModel extends UIModel
{
  public function __construct()
  {
  }

  public function init()
  {
    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_id'));
    $ma->set_title('Initiative Id');
    $ma->set_disabled(true);

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_name'));
    $ma->set_title('Name der Initiative');
    $ma->set_description('Solange der Benutzer noch nicht bestätigt ist und die Initaitive noch nicht erstellt ist, kann man hier den Namen noch ändern');
    $ma->set_validate(true);

    $ma = $this->add_ma(
      new UIUserMetaInitiativeModelAdapter(
                 'initiative_company'));
    $ma->set_title('Unternehmen');
    $ma->set_description('Sind Sie ein Unternehmen?');

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_address'));
    $ma->set_title('Strasse und Nr.');
    $ma->set_validate(true);

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_zipcode'));
    $ma->set_title('Postleitzahl');
    $ma->set_validate(true);

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_city'));
    $ma->set_title('Ort');
    $ma->set_validate(true);

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_lat'));
    $ma->set_title('Latitude');
    $ma->set_disabled(true);

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_lng'));
    $ma->set_title('Longitude');
    $ma->set_disabled(true);

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_kvm_id'));
    $ma->set_title('Karte von Morgen Id');
    if( !current_user_can('administrator')) 
    {
      $ma->set_disabled(true);
    }

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_kvm_log'));
    $ma->set_title('Karte von Morgen Statusmeldungen');
    $ma->set_disabled(true);

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_ds'));
    $ma->set_title('Datenschutzerklärung akzeptiert');
    $ma->set_description('Sie sind mit der Datenschutzerklärung einverstanden');
    $ma->set_validate(true);

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_feed_url'));
    $ma->set_title('Feed URL');
    $ma->set_description('Diese wird benutzt um Veranstaltungen automatisch hochzuladen');

    $ma = $this->add_ma(
      new class('initiative_feed_type') 
          extends UIUserMetaModelAdapter
      {
        private $_feedchoices;

        public function get_choices()
        {
          if(!empty($this->_feedchoices))
          {
            return $this->_feedchoices;
          }

          if (!class_exists('SSImporterFactory')) 
          { 
            return array();
          }
          
          $this->_feedchoices = array();
          $factory = SSImporterFactory::get_instance();
          foreach($factory->get_importtypes() 
                  as $importtype)
          {
            array_push($this->_feedchoices, 
              new UIChoice($importtype->get_id(), 
                           $importtype->get_name()));
          }
          return $this->_feedchoices;
        }
      });
    $ma->set_title('Feed URL Type');

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_feed_update_log'));
    $ma->set_title('Feed updates Statusmeldungen');
    $ma->set_disabled(true);


    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('first_name'));
    $ma->set_title('Kontaktperson Vorname ');

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('last_name'));
    $ma->set_title('Kontaktperson Nachname ');
    $ma->set_validate(true);

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('dbem_phone'));
    $ma->set_title('Phone');

    $ma = $this->add_ma(
      new UIUserMetaModelAdapter('initiative_url'));
    $ma->set_title('Webseite');


  }

  protected function before_save_model()
  {
    if($this->is_address_changed())
    {
      $wpLocation = $this->create_wplocation();
      $wpLocHelper = new WPLocationHelper();
      $wpLocation = $wpLocHelper->fill_by_osm_nominatim(
                                    $wpLocation);
      $this->set_value('initiative_lng', $wpLocation->get_lon());
      $this->set_value('initiative_lat', $wpLocation->get_lat());
    }
  }

  protected function save_model()
  {
    if($this->is_user_changed())
    {
      $save_entry = new InitiativeSaveKVMEntry(
                          $this->get_property(UIModel::USER_ID));
      $save_entry->save();
    }
  }

  private function is_address_changed()
  {
    return 
      $this->is_value_changed('initiative_address') ||
      $this->is_value_changed('initiative_zipcode') ||
      $this->is_value_changed('initiative_city'); 
  }

  private function is_user_changed()
  {
    foreach($this->get_modeladapters() as $ma)
    { 
      // TODO: Only Update for changed fields for KVM
      // Not for feed_url
      if($ma->is_value_changed())
      {
        return true;
      }
    }
    return false;
  }

  private function create_wplocation()
  {
    $wpLocHelper = new WPLocationHelper();

    $wpLocation = new WPLocation();
    $wpLocHelper->set_address($wpLocation, 
                              $this->get_value('initiative_address'));
    $wpLocation->set_zip($this->get_value('initiative_zipcode'));
    $wpLocation->set_city($this->get_value('initiative_city'));
    $wpLocation->set_name($this->get_value('initiative_name'));
    return $wpLocation;
  }
}
