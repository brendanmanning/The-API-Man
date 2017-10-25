<?php

  require_once 'api-man-config.php';

  /**
   * Make an absolute file path relative to the ROOT_DIR variable
   * @param path The path to make relative
   * @return The path parameter now relative to the root directory specified in api-man-config.php or null on error
   */
  function make_relative($path) {
  
    // Is the parameter shorter than the ROOT_DIR?
    if(strlen($path) < strlen(ROOT_DIR)) {
      return null;
    }
    
    // Is the parameter not a subdirectory of the ROOT_DIR?
    for($c = 0; $c < strlen(ROOT_DIR); $c++) {
      if($path{$c} != (ROOT_DIR . ""){$c}) {
      die($path . ',' . ROOT_DIR . '@' . $c);
        return null;
      }
    }
    
    return substr($path, strlen(ROOT_DIR));
  }
?>