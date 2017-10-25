<?php

  // Make sure that the request is authed
  include 'api-man-internal-api-auth.php';

  require_once '../api-man-Routes.php';
  
  $route = $_POST['route'];
  
  $success = create_route($route);
  
  if($success) {
    echo json_encode(array("status" => 201, "message" => "Route created!"));
  } else {
    echo json_encode(array("status" => 500, "message" => "Route not created! Please try again later!"));
  }
  
  
?>