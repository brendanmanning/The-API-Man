<?php
  // Make sure that the request is authed
  include 'api-man-internal-api-auth.php';
  
  $script = $_POST['script'];
  $contents = $_POST['contents'];
  
  $success = file_put_contents('../scripts/' . $script, $contents);
  
  if($success) {
    echo json_encode(array("status" => 201, "message" => "Script saved!"));
  } else {
    echo json_encode(array("status" => 500, "message" => "Script not saved! Please try again later!"));
  }
  
?>