<?php

class InUserProfileView extends UIView
{
  public function init()
  {
    $this->add_va(new UIVATextfield('initiative_id'));
    $this->add_va(new UIVATextfield('initiative_name'));
    $this->add_va(new UIVACheckbox('initiative_company'));
    $this->add_va(new UIVATextfield('initiative_address'));
    $this->add_va(new UIVATextfield('initiative_zipcode'));
    $this->add_va(new UIVATextfield('initiative_city'));
    $this->add_va(new UIVATextfield('initiative_lat'));
    $this->add_va(new UIVATextfield('initiative_lng'));
    $this->add_va(new UIVATextfield('initiative_kvm_id'));
    $this->add_va(new UIVACheckbox('initiative_kvm_upload'));
    $this->add_va(new UIVATextarea('initiative_kvm_errorlog'));

    $this->add_va(new UIVATextfield('initiative_feed_url'));
    $this->add_va(new UIVACombobox('initiative_feed_type'));

    $va = $this->add_va(new UIVACheckbox('initiative_ds'));
    $va->set_disabled(true);
  }

  public function load()
  {
    $va_id = $this->get_viewadapter('initiative_id');
    $va_name = $this->get_viewadapter('initiative_name');
    
    if(empty($va_id))
    {
      return;
    }

    if(empty($va_name))
    {
      return;
    }
      
    if(!empty($va_id->get_value()))
    {
      $va_name->set_disabled(true);
    }
  }

  public function show()
  {
?>
  <h3>Extra Informationen über die Initiative</h3>
    <table class="form-table"><?php
    foreach($this->get_viewadapters() as $va)
    {
?><tr><th><?php
      $va->show_label();
?></th><td><?php
      $va->show_field();
      if ( $va->has_description())
      {
        $va->show_newline();
        $va->show_description();
      }
?></td</tr><?php
    }
?></table>
<?php
  }
}
