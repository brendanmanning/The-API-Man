<html>
<?php

  // Ensure that the user is authenticated
  include 'api-man-web-ui-auth.php';
  
  // Ensure that JS requests are authenticated
  include 'html/api-man-client-side-auth.php';
  
  require_once 'api-man-Routes.php';
  require_once 'imports/api-man-imports.php';
  require_once 'html/api-man-navbar.php';

?>
<script src="lib/textutilities/validation.js"></script>
<script src="js/api-man-add-route.js"></script>
<body>
  <div class="container">
    <div class="card-panel">
      <h3>Add API Route</h3>
      <p>Use RESTful notation, for example: todos/add or todos/list</p>
      <div class="input-field col s12">
        <input placeholder="Route Name" id="route_name" type="text" class="" onkeyup="validate()">
        <span id="explanation" style="display: none;">Input should be alphanumeric and the slash character only. No spaces or symbols.</span>
      </div>
      <a id="submit" onclick="submit()" class="waves-effect waves-light btn" style="display: none;"><i class="material-icons right">add</i>create route</a>
    </div>
  </div>
</body>

<!-- Javascripts that should execute after the page loads -->
<script src="js/api-man-list-routes.js"></script>

</html>