<?php
  
  // Make sure that the request is authed
  include 'api-man-internal-api-auth.php';
  
  $route = $_POST['route'];
  
  if($route{0} == DIRECTORY_SEPARATOR) {
    $route = substr($route,1);
  }
  
  if($route{strlen($route) - 1} != DIRECTORY_SEPARATOR) {
    $route .= DIRECTORY_SEPARATOR;
  }
  
  $file = '../routes/' . $route;
  
  if(!is_dir($file)) {
    die(json_encode(array("status" => 500, "message" => "Route could not be deleted! Please try again later! (#1)")));
  }
  
  $indexfile = $file . "index.php";
  $permissionsfile = $file . "permissions.php";
  $parametersfile = $file . "parameters.php";
  
  if(!is_file($indexfile) || !is_file($permissionsfile) || !is_file($parametersfile)) {
    die(json_encode(array("status" => 500, "message" => "Route could not be deleted! Please try again later! (#2)")));
  }
  
  $indexOK = unlink($indexfile);
  $permsOK = unlink($permissionsfile);
  $paramsOK = unlink($parametersfile);
  
  $dirOK = rmdir($file);
  
  if($indexOK && $permsOK && $paramsOK && $dirOK) {
    echo json_encode(array("status" => 200, "message" => "Route deleted!"));
  } else {
    echo json_encode(array("status" => 500, "message" => "Route could not be deleted! Please try again later! (#3)"));
  }
  
?>
  