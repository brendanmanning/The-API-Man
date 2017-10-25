<?php
  
  require_once 'api-man-config.php';
  require_once 'api-man-Route.php';

  /**
   * Create a new route on the disk given a route path
   * @param route The route name as a string
   * @return A boolean indicating whether the creation worked or not
   */
  function create_route($route) {
    
    // If the path does not start with a /, add one
    if($route{0} != DIRECTORY_SEPARATOR && $route{0} != "/") {
      $route = DIRECTORY_SEPARATOR . $route;
    }
    
    // If the path does not end with a /, add one
    if($route{strlen($route) - 1} != DIRECTORY_SEPARATOR && $route{strlen($route) - 1} != "/") {
      $route .= DIRECTORY_SEPARATOR;
    }
    
    // Create a route object from this path
    $route = new Route(ROOT_DIR . "routes" . $route);
    
    // Recursively create the folder that the route will be saved in
    $dirsOK = mkdir($route->route_path(), 0777, true);
    
    // Get the template content that we will insert into each handler file
    $indexTemplate = file_get_contents(ROOT_DIR . "templates/index.php");
    $parametersTemplate = file_get_contents(ROOT_DIR . "templates/parameters.php");
    $permissionsTemplate = file_get_contents(ROOT_DIR . "templates/permissions.php");
    
    // Save these templates to the new route
    
    $routePath = $route->route_handler_path();
    $paramPath = $route->parameters_handler_path();
    $permsPath = $route->permissions_handler_path();

    
    $indexOK = file_put_contents($routePath, $indexTemplate);
    $paramsOK = file_put_contents($paramPath, $parametersTemplate);
    $permsOK = file_put_contents($permsPath, $permissionsTemplate);

    return $dirsOK && ($indexOK != FALSE) && ($paramsOK != FALSE) && ($permsOK != FALSE);
  }

  /**
   * Get all the APi routes in the project
   * @return An array of Route objects
   */
   function get_all_routes() {
   
     $routes = array();
     
     // Get the subfolders that contain routes
     $route_folders = get_route_subfolders();
     
     for($r = 0; $r < count($route_folders); $r++) {
       $routes[] = new Route($route_folders[$r]);
     }
     
     return $routes;
   }
   
   /**
    * Utility function - Get all the subfolders containing route handlers in a directory
    * @return An array of subfolders' absolute paths
    */
   function get_route_subfolders( $dir = null ) {
   
     if($dir == null) {
       $dir = ROOT_DIR . "routes";
     }
   
     // Recursively list the contents of this directory
     $files = scandir($dir);
     $routefolders = array();
     
      foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        
        if(!is_dir($path)) {
          
          // If we've found the index.php file of an action
          if(endsWith($value, "index.php")) {
          
            // Add the root folder one level up to the array of folders containing routes
            $routefolders[] = dirname($path);
            
            continue;
          }
        } else if($value != "." && $value != "..") {
        
            // Recurse into this folder
            $foundroutes = get_route_subfolders($path);
            for($f = 0; $f < count($foundroutes); $f++) {
              $routefolders[] = $foundroutes[$f];
            }
            
            continue;
        }
     }
    
     return $routefolders;
    
   }
   
   /**
    * Utility function - Does a string end with a substring?
    * @param haystack The string to search inside
    * @param needle The string to search for
    */
   function endsWith($haystack, $needle) {
     // search forward starting from end minus needle length characters
     return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
   }