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
<script src="lib/ace/ace.js"></script>
<body>
  <div class="container">
  <ul id="api-man-routes" class="collection with-header">
    <li class="collection-header"><h4>API Routes</h4></li>
  </ul>
  </div>
</body>

<!-- Javascripts that should execute after the page loads -->
<script src="js/api-man-list-scripts.js"></script>

</html>