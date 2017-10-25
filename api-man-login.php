<?php
  require_once 'api-man-config.php';
  require_once 'imports/api-man-imports.php';
  require_once 'html/api-man-navbar.php';
  require_once 'S5.php';

function main() {

  session_start();
  
  }
  
  function login($user, $password) {
  
    $security = new S5();
  
    // This is not a simple boolean.
    // On successful login, S5 returns the database id of logged in user.
    // It returns false on error
    $auth_status = $security->login($user,$password);
    
    // If we are logged in, write to $_SESSION because this is for PHP, not the API
    if($auth_status != false) {
      $_SESSION['username'] = $user;
      $_SESSION['userid'] = $auth_status;
      $_SESSION['authed'] = true;
      $_SESSION['token'] = $security->create_token($user)['token'];
      header("Location: api-man-list-routes.php");
    } else {
      header("Location: api-man-login.php?status=incorrect");
    }
  }
  
  function logout() {
    $_SESSION['username'] = null;
    $_SESSION['userid'] = null;
    $_SESSION['authed'] = false;
    $_SESSION['token'] = null;
    header("Location: api-man-login.php?status=reauth");
  }
  
  main();
  
  if(isset($_POST['LOGIN'])) {
  
    login($_POST['LOGIN_USERNAME'], $_POST['LOGIN_PASSWORD']);
  }
  
  if(isset($_GET['logout'])) {
  
    logout();
  }
?>
<body>
    <div class="container">
      <div class="col s12 m5">
        <div class="card-panel">
          <div class="row">
           <form class="col s12" method="POST">
             <div class="row">
               <div class="input-field col s12">
                 <input placeholder="Ex. johnsmith123" id="LOGIN_USERNAME" name="LOGIN_USERNAME" type="text" value="" class="validate">
                 <label for="LOGIN_USERNAME">Username</label>
               </div>
               <div class="input-field col s12">
                 <input placeholder="mypassword123" id="LOGIN_PASSWORD" name="LOGIN_PASSWORD" type="password" class="validate">
                 <label for="LOGIN_PASSWORD">Password</label>
               </div>
             </div>
             <button type="submit" class="waves-effect waves-light btn"><i class="material-icons right"> chevron_right </i>login</button>
             <input type="hidden" name="LOGIN" value="true">
           </form>
         </div>
       </div>
     </div>
   </div>
 </div>
</body>