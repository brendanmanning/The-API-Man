<html>
<?php
  
  // Ensure that the user is authenticated
  include 'api-man-web-ui-auth.php';
  
    // Ensure that JS requests are authenticated
  include 'html/api-man-client-side-auth.php';
  
  require_once 'api-man-config.php';
  require_once 'api-man-Route.php';
  require_once 'imports/api-man-imports.php';
  require_once 'html/api-man-navbar.php';
  require_once 'html/api-man-edit-attatch.php';
  require_once 'lib/textutilities/escaping.php';

  $route = new Route(ROOT_DIR . 'routes' . DIRECTORY_SEPARATOR . $_GET['route']);
?>
<!-- Define the state of the editors after the last save or on page load-->
<script>
  var HANDLER_INITIAL_DATA = '<?php echo escape($route->route_handler()); ?>';
  var PARAMETERS_INITIAL_DATA = '<?php echo escape($route->parameters_handler()); ?>';
  var PERMISSIONS_INITIAL_DATA = '<?php echo escape($route->permissions_handler()); ?>';
</script>
<script src="lib/ace/ace.js"></script>
<script src="lib/ace/theme-sql-server.js" type="text/javascript" charset="utf-8"></script>
<script src="lib/ace/mode-php.js" type="text/javascript" charset="utf-8"></script>
<script src="lib/textutilities/escaping.js"></script>
<body>
  <div class="row">
    <div class="col s6">
      <div class="card-panel">
        <h5>Editing Route: <strong><?php echo $_GET['route']; ?></strong></h5>
      </div>
      <div class="card-panel">
        <div class="row">
            <label for="api-man-test-form-method">Request Method</label>
            <select id="api-man-test-form-method" class="browser-default">
              <option value="GET">GET</option>
              <option value="POST">POST</option>
            </select>
            
            <table>
              <thead>
                <tr>
                  <th>Parameter</th>
                  <th>Value</th>
                  <td></td>
                </tr>
              </thead>
              <tbody id="api-man-test-form-parameters">
              </tbody>
            </table>
            
            <a id="api-man-test-form-parameter-add" class="btn-floating"><i class="material-icons">add</i></a>
        </div>
      </div>
      <div class="card-panel">
        <span class="black-text">
        <p id="api-man-test-request-output">Press "Request" to test your response</p>
        <p><a id="api-man-test-request-button">Make Request</a></p>
        </span>
      </div>
    </div>
    <div class="col s6">
    <div class="card-panel">
          <ul class="tabs">
            <li class="tab col s3"><a href="#api-man-editor-1" class="active">Handler</a></li>
            <li class="tab col s3"><a href="#api-man-editor-2">Parameters</a></li>
            <li class="tab col s3"><a href="#api-man-editor-3">Permissions</a></li>
          </ul>
       
      <div id="api-man-editor-1">
        <div id="api-man-route-handler" style="height: 400px; width: 500px;"></div>
        <a id="api-man-route-handler-save-button" class="waves-effect waves-light btn"><i class="material-icons right">save</i>Save</a>
        <?php echo fab('fab1', 'index'); ?>
      </div>
      <div id="api-man-editor-2">
        <div id="api-man-parameters-handler" style="height: 400px; width: 500px;"></div>
        <a id="api-man-parameters-handler-save-button" class="waves-effect waves-light btn"><i class="material-icons right">save</i>Save</a>
        <?php echo fab('fab2', 'parameters'); ?>
      </div>
      <div id="api-man-editor-3">
        <div id="api-man-permissions-handler" style="height: 400px; width: 500px;"></div>
        <a id="api-man-permissions-handler-save-button" class="waves-effect waves-light btn large"><i class="material-icons right">save</i>Save</a>
        <?php echo fab('fab3', 'permissions'); ?>
      </div>
    </div>
    </div>
  </div>
  
  
  <?php include 'html/api-man-file-chooser.php'; ?>

</body>

<!-- Javascripts that should execute after the page loads -->
<script>
  //var stateful = new Stateful();
  //stateful.trackAllTaggedElements();
</script>
<script src="js/api-man-file-chooser.js"></script>
<script src="js/api-man-edit-route.js"></script>

<!-- Include the footer -->
<?php include 'html/api-man-footer.php'; ?>
</html>