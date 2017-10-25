<?php

// Make sure that the request is authed
include 'api-man-internal-api-auth.php';

// Add the script authentication

  require_once '../api-man-Routes.php';
  require_once '../api-man-utilities.php';
  
  $route_paths = array();
  $routes = get_all_routes();
  
  for($r = 0; $r < count($routes); $r++) {
    $route_paths[] = make_relative($routes[$r]->route_path());
    
    // Remove the routes/ prefix
    // api-man-edit-route.php only works without that prefix. For example: ?route=todos/add/ not /route/todos/add
    $route_paths[$r] = str_replace("routes/", "", $route_paths[$r]);
  }
  
  echo json_encode($route_paths)

?>