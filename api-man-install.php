<html>
<?php
  function install() {
    
    // Get variables from POST
    $dbhost = $_POST['DB_HOST'];
    $dbname = $_POST['DB_NAME'];
    $dbuser = $_POST['DB_USER'];
    $dbpass = $_POST['DB_PASS'];
    $rootdir = $_POST['ROOT_DIR'];
    $rooturl = $_POST['ROOT_URL'];
    $adminuser = $_POST['ADMIN_USER'];
    $adminpass = $_POST['ADMIN_PASS'];
    
    // Update the database credentials in the config file
    $config = file_get_contents("./api-man-config.php");
    $config = str_replace("{DB_HOST}", $dbhost, $config);
    $config = str_replace("{DB_NAME}", $dbname, $config);
    $config = str_replace("{DB_USER}", $dbuser, $config);
    $config = str_replace("{DB_PASS}", $dbpass, $config);
    $config = str_replace("{ROOT_DIR}", $rootdir, $config);
    file_put_contents('./api-man-config.php', $config);
    
    // Update the root url in the JS config file
    $jsconfig = file_get_contents('./api-man-config.js');
    $jsconfig = str_replace("{ROOT_URL}", $rooturl, $jsconfig);
    file_put_contents('./api-man-config.js', $jsconfig);
    
    // Update the database credentials in the S5 file
    $s5 = file_get_contents('./S5.php');
    $s5 = str_replace("{DB_HOST}", $dbhost, $s5);
    $s5 = str_replace("{DB_NAME}", $dbname, $s5);
    $s5 = str_replace("{DB_USER}", $dbuser, $s5);
    $s5 = str_replace("{DB_PASS}", $dbpass, $s5);
    file_put_contents('./S5.php', $s5);
    
    require 'S5.php';
    
    // Setup the S5 database
    $security = new S5();
    $security->prepare_database();
    
    // Create the administrative user
    $adminuserid = $security->register($adminuser, $adminpass);
    
    // Create the permission that allows access to the backend
    $webconsolepermissionid = $security->create_permission("backend", "Users with this permission may access and make changes on the API Manager Web Console");
    
    // Grant that permission to the administrative user
    $security->grant_permission($adminuserid, $webconsolepermissionid);
    
    header("Location: api-man-post-install.php?success=" . $s5OK);
    
  }
  
  if(isset($_POST['install'])) {
    install();
  }
  
  require_once 'api-man-config.php';
  require_once 'imports/api-man-imports.php';
  require_once 'html/api-man-navbar.php';
?>
<body>
    <div class="container">
      <div class="col s12 m5">
        <div class="card-panel">
          <h5>Database Connection</h5>
          <div class="row">
           <form class="col s12" method="POST">
             <div class="row">
               <div class="input-field col s6">
                 <input placeholder="Usually localhost" id="DB_HOST" name="DB_HOST" type="text" value="localhost" class="validate">
                 <label for="DB_HOST">Database Host</label>
               </div>
              <div class="input-field col s6">
                <input placeholder="MySQL database where all tables will be" id="DB_NAME" name="DB_NAME" type="text" class="validate">
                <label for="DB_NAME">Database Name</label>
              </div>
            </div>
            <div class="row">
               <div class="input-field col s6">
                 <input placeholder="A MySQL user granted all permissions on the above database" id="DB_USER" name="DB_USER" type="text"" class="validate">
                 <label for="DB_USER">MySQL User</label>
               </div>
              <div class="input-field col s6">
                <input placeholder="The password for that user" id="DB_PASS" name="DB_PASS" type="text" class="validate">
                <label for="DB_PASS">User's Password</label>
              </div>
            </div>
          <h5>File System Setup</h5>
           <div class="row">
               <div class="input-field col s12">
                 <input placeholder="Where is API Man installed? Ex: /var/www/html/api/" id="ROOT_DIR" name="ROOT_DIR" type="text" value="localhost" class="validate">
                 <label for="ROOT_DIR">Root Directory (trailing slash required)</label>
               </div>
              </div> 
              <div class="row">
               <div class="input-field col s12">
                 <input placeholder="Your webserver's url. Ex: http://myserver.com" id="ROOT_URL" name="ROOT_URL" type="text" value="" class="validate">
                 <label for="ROOT_URL">Website URL (no need for www)</label>
               </div>
              </div> 
           <h5>Admin User</h5>
           <p>This is the account you will use to login to the API Manager</p>
           <div class="row">
               <div class="input-field col s6">
                 <input placeholder="Ex. johnsmith123" id="ADMIN_USER" name="ADMIN_USER" type="text"" class="validate">
                 <label for="DB_USER">Your Username</label>
               </div>
              <div class="input-field col s6">
                <input placeholder="r3A77ysECUREpa55werd" id="ADMIN_PASS" name="ADMIN_PASS" type="text" class="validate">
                <label for="DB_PASS">Your Password</label>
              </div>
            </div>
              <button type="submit" class="waves-effect waves-light btn"><i class="material-icons right">sentiment_very_satisfied </i>SETUP</button>
              <input type="hidden" name="install" value="true">
              </form>   
        </div>
      </div>
    </div>