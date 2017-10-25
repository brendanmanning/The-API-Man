<html>
<?php
  
  // Ensure that the user is authenticated
  include 'api-man-web-ui-auth.php';
  
    // Ensure that JS requests are authenticated
  include 'html/api-man-client-side-auth.php';
  
  require_once 'api-man-config.php';
  require_once 'imports/api-man-imports.php';
  require_once 'html/api-man-navbar.php';
  require_once 'lib/textutilities/escaping.php';
  
  

?>
<!-- Define the state of the editors after the last save or on page load-->
<script>
  var EDITOR_INITIAL_DATA = '<?php /*echo escape($route->route_handler());*/ ?>';
</script>
<script src="lib/ace/ace.js"></script>
<script src="lib/ace/theme-sql-server.js" type="text/javascript" charset="utf-8"></script>
<script src="lib/ace/mode-php.js" type="text/javascript" charset="utf-8"></script>
<script src="lib/textutilities/escaping.js"></script>
<body>
  <ul id="slide-out" class="side-nav">
    <div id="script-list">
      <li><a class="waves-effect waves-light btn modal-trigger" href="#modal1">Add new script</a></li>
    </div>
  </ul>
  <div class="fixed-action-btn horizontal">
    <a href="#" data-activates="slide-out" id="side-bar-trigger" class="button-collapse btn-floating btn-large red">
      <i class="large material-icons">dehaze</i>
    </a>
    <ul>
      <li><a onclick="saveScript()" class="btn-floating yellow darken-1"><i class="material-icons">save</i></a></li>
    </ul>
  </div>
  <!-- Modal Structure -->
  <div id="modal1" class="modal">
    <div class="modal-content">
      <h4>New Script</h4>
      <input placeholder="MyNewScript.php" id="NEW_SCRIPT_NAME" type="text" class="validate">
    </div>
    <div class="modal-footer">
      <a  class="modal-action modal-close waves-effect waves-green btn-flat">Close</a>
      <a  onclick="createScript()" class="modal-action modal-close waves-effect waves-green btn-flat teal white-text">Create</a>
    </div>
  </div>
  <div id="editor" style="height: 100%; width: 100%;">
  
  </div>
</body>

<script>
    $("#side-bar-trigger").sideNav();
     $(document).ready(function(){
    // the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
    $('.modal').modal();
  });
</script>
<script src="js/api-man-edit-script.js"></script>