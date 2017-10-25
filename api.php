<?php

  require_once 'api-man-Request.php';
  require_once 'api-man-config.php';
  
  $base_url = ROOT_DIR;
  $request_url = $_SERVER['REQUEST_URI'];
  
  // Remove the get parameters because that will mess up filesystem access
  $request_url = explode('?', $request_url)[0];
  
  // If the user has installed API Man in a subdirectory, strip that off the beginning of the request URI
  //   Ex. api.server.com/v3/ @ /var/www/html/api/v3/
  //       String the /v3/ off the end of api.server.com
  $request_url = substr($request_url, strlen(URL_SUBDIRECTORY));
  
 

  $request = new Request($base_url . "routes" . DIRECTORY_SEPARATOR . $request_url);
  $request->execute();
?>