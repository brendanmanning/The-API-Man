<?php

// Make sure that the request is authed
include 'api-man-internal-api-auth.php';
 
 function main() {
  require_once '../api-man-config.php';
  require_once '../api-man-Files.php';

  $path = $_GET['path'];
 
  
  
  if($path{strlen($path) - 1} != DIRECTORY_SEPARATOR) {
    $path .= DIRECTORY_SEPARATOR;
  }
  
  if(startsWith($path, ROOT_DIR) && (strlen($path) >= strlen(ROOT_DIR))) {
    $path = substr($path, strlen(ROOT_DIR));
  }

  
  if($path == -1 || $path == "-1" || $path == "") {
    $path = ROOT_DIR;
  } else {
 
    $path = ROOT_DIR . $path;
    
    }
  
  
  echo json_encode(get_files($path));
  
  }
  
  function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

main();
?>