<?php

  define("ROOT_DIR", "{ROOT_DIR}");
  define("URL_SUBDIRECTORY", "");
  
  define("DB_HOST", "{DB_HOST}");
  define("DB_NAME", "{DB_NAME}");
  define("DB_USER", "{DB_USER}");
  define("DB_PASS", "{DB_PASS}");
  
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
