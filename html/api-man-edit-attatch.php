<?php
/**
 * Creates a FAB that is costumized for each editor
 * The FAB must be customized so that the <> code button 
 * knows which editor div to insert it's requrie statement into
 */
function fab($id, $codeEditorDiv) {
   
  $html = ''; 
   
  $html .= '<div id="' . $id . '" class="fixed-action-btn horizontal">';
  $html .= '  <a class="btn-floating btn-large red">';
  $html .= '    <i class="large material-icons">mode_edit</i>';
  $html .= '  </a>';
  $html .= '  <ul>';
  $html .= '    <li><a onclick="addScript(\'' . $codeEditorDiv . '\')" class="btn-floating red"><i class="material-icons">code</i></a></li>';
  $html .= '    <li><a href="api-man-add-route.php" class="btn-floating blue"><i class="material-icons">add</i>Add route</a></li>';
  $html .= '  </ul>';
  $html .= '</div>';
  
  return $html;
}
?>