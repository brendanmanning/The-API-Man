<?php

// Make sure that the request is authed
include 'api-man-internal-api-auth.php';
  
  require_once '../api-man-Scripts.php';
    require_once '../api-man-utilities.php';
  
  $script_paths = array();
  $scripts = get_all_scripts();
  
  for($s = 0; $s < count($scripts); $s++) {
    $script_paths[] = str_replace('scripts/', '', make_relative($scripts[$s]));
    
    // Remove the routes/ prefix
    // api-man-edit-route.php only works without that prefix. For example: ?route=todos/add/ not /route/todos/add
    //$route_paths[$r] = str_replace("routes/", "", $route_paths[$r]);
  }
  
  echo json_encode($script_paths)

?>