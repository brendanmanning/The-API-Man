

<?php

// Make sure that the request is authed
include 'api-man-internal-api-auth.php';
  require_once '../api-man-config.php';
  
  $route = $_GET['route']; // todos/add/
  $fileName = $_GET['file']; // permissions.php
  $file = ROOT_DIR . "routes" . DIRECTORY_SEPARATOR . $route . $fileName . ".php";
  echo file_get_contents($file, TRUE);
?>