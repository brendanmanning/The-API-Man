<?php
// Make sure that the request is authed
  include 'api-man-internal-api-auth.php';
  
  $script = $_GET['script'];
  
  echo file_get_contents('../scripts/' . $script);
  
?>
  