<?php
  require '../S5.php';
  $security = new S5();
  
  $user = null;
  $token = null;
  
  if(isset($_GET['username']) && isset($_GET['token'])) {
    $user = $_GET['username'];
    $token = $_GET['token'];

  }
  
  if(isset($_POST['username']) && isset($_POST['token'])) {
    $user = $_POST['username'];
    $token = $_POST['token'];
  }
  
  if($user == null || $token == null) {
    die(json_encode(array("error" => 403, "message" => "Your request did not supply proper authentication credentials.")));
  }
  
  if(!$security->verify_token($user, $token)) {
    die(json_encode(array("error" => 403, "message" => "Your request did not supply a valid user/token pair")));
  }
  
?>