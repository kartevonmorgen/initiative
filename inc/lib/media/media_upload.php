<?php

add_filter('wp_handle_upload_prefilter', 
           'upload_error_message');

function upload_error_message($file) 
{
  $limit = 500;
  $limit_output = '500 kB';

  $size = $file['size'];
  $size = $size / 1024;

  if ( ( $size > $limit ) ) 
  {
    $file['error'] = 'Bilder, Video, '.
                     'Audio sollten kleiner sein dann ' . 
                     $limit_output . ' ( ' . round($size) . 
                     ' > ' . $limit . ' )';
  }
  return $file;
}

add_action('admin_head', 'upload_load_styles');

function upload_load_styles() 
{
  $limit = '500';
	$limit_output = '500 kB';
  ?>
  <!-- .Custom Max Upload Size -->
	<style type="text/css">
		.after-file-upload {
			display: none;
		}
		.upload-flash-bypass:after {
			content: 'Maximale große für Bilder, Video und Audio: <?php echo $limit_output ?>.';
			display: block;
			margin: 15px 0;
		}

  </style>
  <!-- END Custom Max Upload Size -->
  <?php
}
