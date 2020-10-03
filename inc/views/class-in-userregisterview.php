<?php

class InUserRegisterView extends UIView
{
  const DS_BGCOLOR = '#e3e892';
  public function init()
  {
    $v = $this->add_va(
           new UIVATextfield('initiative_name'));
    $v->set_backgroundcolor(self::DS_BGCOLOR);

    $v = $this->add_va(
           new UIVACheckbox('initiative_company'));
    $v->set_backgroundcolor(self::DS_BGCOLOR);

    $v = $this->add_va(
           new UIVATextfield('initiative_address'));
    $v->set_backgroundcolor(self::DS_BGCOLOR);
    
    $v = $this->add_va(
           new UIVATextfield('initiative_zipcode'));
    $v->set_backgroundcolor(self::DS_BGCOLOR);

    $v = $this->add_va(new UIVATextfield('initiative_city'));
    $v->set_backgroundcolor(self::DS_BGCOLOR);

    $this->add_va(new UIVATextfield('first_name'));
    $this->add_va(new UIVATextfield('last_name'));

    $v = $this->add_va(new UIVATextfield('dbem_phone'));
    $v->set_backgroundcolor(self::DS_BGCOLOR);

    $v = $this->add_va(new UIVATextfield('initiative_url'));
    $v->set_backgroundcolor(self::DS_BGCOLOR);

    $this->add_va(new UIVACheckbox('initiative_ds'));
  }

  public function show()
  {
?><p>&nbsp;</p>
<p><b>Kontaktdaten</b></p>
<hr>
<p>&nbsp;</p><?php
    foreach($this->get_viewadapters() as $va)
    {
?><p><?php
      $va->show_label();
      $va->show_newline();
      $va->show_field();
      if($va->has_description()) 
      { 
        $va->show_newline();
        $va->show_description();
        $va->show_newline();
        $va->show_newline();
      } 
?></p><?php
    }
?><p>&nbsp;</p>
<p><b>Datenschutz</b></p>
<hr>
<p>&nbsp;</p>
<p>Durch die Registrierung auf dieser Plattform werden die in Gelb eingegebenen Daten veröffentlicht.<br/>Die weißen Felder werden nur für interne Zwecke verwendet.<br/>Wenn Sie sich Anmelden können Sie eine Beschreibung von ihre Initiative und Veranstaltungen eingeben. Diese Eingaben werden automatisch auf dieser Webseite und auf der Webseite <a href="https://www.kartevonmorgen.org">www.kartevonmorgen.org</a> veröffentlicht.<br/>
Mehr Informationen über die Verarbeitung von personenbezogenen Daten sind in unserer <a href="<?php echo network_site_url('/ds/'); ?>">Datenschützerklärung</a> zu lesen</p>
<p>&nbsp;</p>
<hr>
<p>&nbsp;</p><?php
  }
}
