<?php

function createscript() {
  // Make sure that the request is authed
  include 'api-man-internal-api-auth.php';
  
  $script = $_POST['script'];
  
  // Add the php extension if none was provided
  if(!endsWith($script, '.php')) {
    $script .= ".php";
  }
  
  $success = file_put_contents('../scripts/' . $script, file_get_contents('../templates/script.php'));
  
  if($success) {
    echo json_encode(array("status" => 201, "message" => "Script created!"));
  } else {
    echo json_encode(array("status" => 500, "message" => "Script not created! Please verify your input and try again later!"));
  }
  
}
  function endsWith($haystack, $needle)
{
    $length = strlen($needle);

    return $length === 0 || 
    (substr($haystack, -$length) === $needle);
}

createscript();
  
?>