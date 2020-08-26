<?php

function pending_gettext_with_context( $translated,  
                                       $text, 
                                       $context, 
                                       $domain )
{
  return pending_gettext($translated, $text, $domain);
}

add_filter( 'gettext_with_context', 'pending_gettext_with_context',10,4);

function pending_ngettext_with_context( $translated, 
                                        $single, 
                                        $plural, 
                                        $number, 
                                        $context, 
                                        $domain )
{
  if ( is_singular() ) 
  {
    return pending_gettext($translated, $single, $domain);
  }
  return pending_gettext($translated, $plural, $domain);
}

add_filter( 'ngettext_with_context', 'pending_ngettext_with_context',10,6);


function pending_ngettext( $translated, 
                           $single, 
                           $plural, 
                           $number, 
                           $domain) 
{
  if ( is_singular() ) 
  {
    return pending_gettext($translated, $single, $domain);
  }
  return pending_gettext($translated, $plural, $domain);
}

add_filter( 'ngettext', 'pending_ngettext', 20, 5 );

function pending_gettext( $translated, 
                          $text, 
                          $domain ) 
{
  if ( 'Pending' == $text ) 
  {
    $translated = 'Intern veröffentlicht';  
  }

  if ( 'Pending Review' == $text ) 
  {
    $translated = 'Intern veröffentlichen';  
  }

  if ( 'Pending <span class="count">(%s)</span>' == $text ) 
  {
    $translated = 'Intern veröffentlicht <span class="count">(%s)</span>';  
  }

  if ( '%s (Pending)' == $text ) 
  {
    $translated = '%s (Intern veröffentlicht)';  
  }

  if ( 'Save as Pending' == $text ) 
  {
    $translated = 'Intern veröffentlichen';  
  }
  return $translated;
}

add_filter( 'gettext', 'pending_gettext', 20, 3 );

// For Gutenberg we have an js way of doing.
function pending_in_gutenberg()
{
  // Make sure the `wp-i18n` has been "done".
  if ( wp_script_is( 'wp-i18n' ) ) :
  ?><script>
     // Note: Make sure that `wp.i18n` has already been defined by the time you call `wp.i18n.setLocaleData()`.
        wp.i18n.setLocaleData(
        {
          'Pending Review': ['nur Intern veröffentlicht'],
          'Save as Pending': ['Speichern']
        });
     </script><?php
  endif;
}

add_action( 'admin_print_footer_scripts', 'pending_in_gutenberg', 11);

/**
 * This always shows the current post status in the labels.
 *
 * @param array   $states current states.
 * @param WP_Post $post current post object.
 * @return array
 */
function display_all_post_states( $states, $post ) 
{
  /* Receive the post status object by post status name */
  $post_status_object = get_post_status_object( $post->post_status );
 
  /* Checks if the label exists */
  if ( in_array( $post_status_object->label, $states, true ) ) 
  {
    return $states;
  }
 
  /* Adds the label of the current post status */
  $states[ $post_status_object->name ] = $post_status_object->label;
 
  return $states;
}
 
add_filter( 'display_post_states', 'display_all_post_states', 10, 2 );

?>
