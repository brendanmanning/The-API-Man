<?php

// Make sure that the request is authed
include 'api-man-internal-api-auth.php';

  require_once '../api-man-config.php';
  
  $route = $_POST['route']; // todos/add/
  $fileName = $_POST['file']; // permissions.php
  $contents = $_POST['contents']; 
  
  $file = ROOT_DIR . "routes" . DIRECTORY_SEPARATOR . $route . DIRECTORY_SEPARATOR . $fileName . ".php";
  echo file_put_contents($file, $contents);

?>