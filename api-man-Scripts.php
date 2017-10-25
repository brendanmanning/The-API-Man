<?php
  
  require_once 'api-man-config.php';
  
  /**
   * Gets all the scripts in the scripts/ folder
   * @return An array of script names
   */
  function get_all_scripts() {
    $scripts = array();
    $files = scandir(ROOT_DIR . "scripts");
    foreach($files as $key => $value){
      $path = realpath(ROOT_DIR . "scripts".DIRECTORY_SEPARATOR.$value);
      if(!is_dir($path)) {
        $scripts[] = $path;
      }
    }
    return $scripts;
  }
?>