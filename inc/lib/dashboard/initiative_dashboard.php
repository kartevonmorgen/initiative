<?php

// Setup a customized Dashboard
add_action( 'wp_dashboard_setup', 'initiative_dashboard_widgets' );

function initiative_dashboard_widgets() 
{
  // Remove Welcome panel
  remove_action( 'welcome_panel', 'wp_welcome_panel' );

  // Remove the rest of the dashboard widgets
  remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
  remove_meta_box( 'health_check_status', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');

  add_meta_box( 'wpexplorer_dashboard_widget', 
                'Lippe von Morgen',
        	      'initiative_intro_content', 
                'dashboard',
                'normal');

  add_meta_box( 'dashboard_widget1', 
                'Initiative/Unternehmen',
                'initiative_explain_initiative_content',
                'dashboard',
                'side');

  add_meta_box( 'dashboard_widget2', 
                'Profil',
        	      'initiative_explain_profil_content',
                'dashboard',
                'side');
  add_meta_box( 'dashboard_widget3', 
                'Veranstaltungen',
        	      'initiative_explain_events_content',
                'dashboard',
                'side');
}


function initiative_intro_content() 
{
  echo '<p>Wilkommen im Verwaltungsbereich von Lippe von Morgen</p>';
  echo '<p><img src="/images/lippe-von-morgen-logo-244x120.png"/></p>';
}

function initiative_explain_initiative_content() 
{
  echo '<p>Unten <a href="'. get_site_url() . '/wp-admin/edit.php?post_type=initiative">Initiativen</a> findest du die Seite für deine Initiative oder Unternehmen</p>';
  echo '<p>Diese kannst du dort <em>Bearbeiten</em> und <em>Veröffentlichen</em> auf dem Platform</p>';
  echo '<p>Mehr Hilfe, schau einfach <a href="' .get_site_url() . '/hilfe#hilfe-initiative">hier</a>';
}

function initiative_explain_profil_content() 
{
  echo '<p>Unten <a href="'. get_site_url() . '/wp-admin/profile.php">Profil</a> kann man die Kontaktdaten für deine Initiative oder Unternehmen ändern.</p>';
  echo '<p>Auch kann man einstellen ob deine veröffentlichte Initiative oder Unternehmen auch auf der Wandelkarte erscheinen soll. Dann ein Häkchen setzen bei <em>Hochladen zu Karte von Morgen</em></p>';
  echo '<p>Auch ist es möglich Veranstaltungen von seine eigene Webseite automatisch zu importieren. Dafür sind die Felder <em>Feed URL</em> und <em>Feed URL Type</em>. Wenn du das möchtest dann Bitte kontakt aufnehmen mit support@lippevonmorgen.de.</p>';
  echo '<p>Mehr Hilfe, schau einfach <a href="' .get_site_url() . '/hilfe#hilfe-profil">hier</a>';
}

function initiative_explain_events_content() 
{
  echo '<p>Unten <a href="'. get_site_url() . '/wp-admin/edit.php?post_type=event">Veranstaltungen</a> findest du die Seite wo man Veranstaltungen erstellen oder bearbeiten kann.</p>';
  echo '<p>Nachdem man Veranstaltungen bearbeitet hat, kann man diese <em>Veröffentlichen</em>. Dann ist diese Veranstaltung sichtbar in der Wandelkalendar <a href="' . get_site_url() . '/eventscalendar">Wandelkalendar</a> und wird auf der <a href="' .get_site_url() . '/wandelkarte">Wandelkarte</a> angezeigt.</p><p><b>Pass auf:</b> der Veranstaltung wird nur auf der Wandelkarte angezeigt wenn das hochladen zu der Karte von Morgen aktiviert ist, das kann man im Profil einstellen.';
  echo '<p>Mehr Hilfe, schau einfach <a href="' .get_site_url() . '/hilfe#hilfe-events">hier</a>';
}

