<?php



add_action( 'save_post_initiative', 'update_initiative2kvm', 10, 3 );

function update_initiative2kvm($post_id, $post, $update) 
{
  if( empty( $post ))
  {
    return;
  }

  // unhook this function so it doesn't loop infinitely
  remove_action( 'save_post_initiative', 
                 'update_initiative2kvm' );


  $user_id = $post->post_author;
 
  $save_entry = new InitiativeSaveKVMEntry($user_id);
  $save_entry->save();
  
  // re-hook this function
  add_action( 'save_post_initiative', 
              'update_initiative2kvm', 10, 3 );
}
