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

  wp_add_dashboard_widget( 'wpexplorer_dashboard_widget', 
                           'Lippe von Morgen',
        	                 'initiative_intro_content');

  wp_add_dashboard_widget( 'dashboard_widget1', 
                           'Initiative/Unternehmen',
        	                 'initiative_explain_initiative_content');
  wp_add_dashboard_widget( 'dashboard_widget2', 
                           'Veranstaltungen',
        	                 'initiative_explain_events_content');
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
}

function initiative_explain_events_content() 
{
  echo '<p>Unten <a href="'. get_site_url() . '/wp-admin/edit.php?post_type=event">Veranstaltungen</a> findest du die Seite wo man Veranstaltungen erstellen oder bearbeiten kann.</p>';
  echo '<p>Nachdem man Veranstaltungen bearbeitet hat, kann man diese <em>Intern veröffentlichen</em>. Dann ist diese Veranstaltung nur sichtbar auf der Seite der Initiative und wird auch nicht zu der Karte von Morgen hochgeladen. Wird die Veranstaltung <em>Veröffentlicht</em>, dann ist diese sichtbar in der <a href="' . get_site_url() . '/wandelkalendar">Wandelkalendar</a> und wird auf der <a href="https://www.kartevonmorgen.org">Karte von Morgen</a> veröffentlicht.</p>';
}

