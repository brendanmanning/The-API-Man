<?php

  define("ROOT_DIR", "/home/brenuzrr/public_html/brendanmanningapps/wgwars/");
  define("URL_SUBDIRECTORY", "/wgwars/");
  
  define("DB_HOST", "localhost");
  define("DB_NAME", "brenuzrr_wgwars");
  define("DB_USER", "brenuzrr_wgwars");
  define("DB_PASS", "12265790");
  
  define("WEB_UI_PERMISSION_NAME", "backend"); // Do not touch this!!!
  
  
  /**
   * Utility function - do not touch!!!
   * Some internal scripts use this function to generate a connection to your database
   * @return A connection to the MySQL database using the credentials specified in api-man-config.php
   */
  function database_connection() {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    return $conn;
  }
?>