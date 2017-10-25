<?php
  
  /**
   * Include this page at the top of every page.
   * If the user is not authenticated, it will redirect the user to the login page
   */
   
  require_once 'S5.php';
  require_once 'api-man-config.php';
  
  // We will use S5 to validate the token that should be saved in session
  $security = new S5();
  
  session_start();
  
  if($_SESSION['authed'] == false || $_SESSION['username'] == null || $_SESSION['userid'] == null || $_SESSION['token'] == null) {
    header("Location: api-man-login.php");
    exit();
  }
  
  if($security->verify_token($_SESSION['username'], $_SESSION['token']) && $security->has_permission($_SESSION['userid'], WEB_UI_PERMISSION_NAME)) {
    header("Location: api-man-login.php");
    exit();
  }
?>
  