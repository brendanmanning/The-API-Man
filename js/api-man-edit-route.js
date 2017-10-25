/*--- HANDLE UI BINDINGS BELOW ---*/
function bindButtons() {
  document.getElementById("api-man-route-handler-save-button").addEventListener("click", function() {
    saveEditorText("index", function(success) {
      HANDLER_INITIAL_DATA = routeHandlerEditor.getValue();
    });
  });
  document.getElementById("api-man-parameters-handler-save-button").addEventListener("click", function() {
    saveEditorText("parameters", function(success) {
      PARAMETERS_INITIAL_VALUE = parametersHandlerEditor.getValue();
    });
  });
  document.getElementById("api-man-permissions-handler-save-button").addEventListener("click", function() {
    saveEditorText("permissions", function(success) {
      PERMISSIONS_INITIAL_VALUE = permissionsHandlerEditor.getValue();
    });
  });
  document.getElementById("api-man-test-request-button").addEventListener("click", function() {
    makeRequest();
  });
  document.getElementById("api-man-test-form-parameter-add").addEventListener("click", function() {
    addNewParameter();
  });
}

/*--- HANDLE EDITORS BELOW ---*/

var routeHandlerEditor = null;
var parametersHandlerEditor = null;
var permissionsHandlerEditor = null;

var routeHandlerEditorLength = -1;
var parametersHandlerEditor = -1;
var permissionsHandlerEditor = -1;

function prepareEditors() {
  routeHandlerEditor = ace.edit("api-man-route-handler");
  parametersHandlerEditor = ace.edit("api-man-parameters-handler");
  permissionsHandlerEditor = ace.edit("api-man-permissions-handler");
  
  var PHPMode = ace.require("ace/mode/php").Mode;
  
  routeHandlerEditor.setTheme("ace/theme/sqlserver");
  routeHandlerEditor.session.setMode(new PHPMode());
  
  parametersHandlerEditor.setTheme("ace/theme/sqlserver");
  parametersHandlerEditor.session.setMode(new PHPMode());
  
  permissionsHandlerEditor.setTheme("ace/theme/sqlserver");
  permissionsHandlerEditor.session.setMode(new PHPMode());
}

function downloadEditorText() {


    routeHandlerEditor.setValue(unescape(HANDLER_INITIAL_DATA));

    parametersHandlerEditor.setValue(unescape(PARAMETERS_INITIAL_DATA));
    
    permissionsHandlerEditor.setValue(unescape(PERMISSIONS_INITIAL_DATA));
  
}

/*function observeEditorChanges() {
  routeHandlerEditor.on('change', function(delta) {
    routeHandlerEditorLength = routeHandlerEditor.getValue().length;
    alert("Changed: " + routeHandlerEditorLength + "/" + routeHandlerEditorOriginalLength);
    if(routeHandlerEditorOriginalLength == routeHandlerEditorLength) {
    alert("Same length");
      if(routeHandlerEditor.getValue() != unescape(HANDLER_INITIAL_DATA)) {
        document.getElementById("api-man-route-handler-save-button").classList.remove('disabled');
      } else {
        document.getElementById("api-man-route-handler-save-button").classList.add('disabled');
      }
    } else {
        document.getElementById("api-man-route-handler-save-button").classList.remove('disabled');
    }
  });
  parametersHandlerEditor.on('change', function(delta) {
    parametersHandlerEditorLength.getValue().length;
  });
  permissionsHandlerEditor.on('change', function(delta) {
    permissionsHandlerEditorLength.getValue().length;
  });
}

function refreshOriginal() {
  var routeHandlerEditorOriginalLength = unescape(HANDLER_INITIAL_DATA).length;
  var parametersHandlerEditorOriginalLength = unescape(PARAMETERS_INITIAL_DATA).length;
  var permissionsHandlerEditorOriginalLength = unescape(PERMISSIONS_INITIAL_DATA).length;
}*/

/**
 * Save the current code in one of the editors back to the server
 * @param editor The "editor name" for the file being edited. Either "index", "permissions", or "parameters"
 * @param callback A callback that recieves true/false on success/failure
 */
function saveEditorText(editor, callback) {
  $.post(ROOT_URL + "internal-api/savefile.php",
    {
        route: ROUTE,
        file: editor,
        contents: getEditorText(editor),
        username: USER_NAME,
        token: ACCESS_TOKEN
    },
    function(data, status){
      var success = status == "success";
      
      if(success) {
        Materialize.toast('File saved!', 1500)
      } else {
        Materialize.toast('Error saving file! Please try again', 3000)
      }
      
      callback(success);
      //refreshOriginal();
    });
}

function getEditorText(editor) {
  switch(editor) {
    case "index":
      return routeHandlerEditor.getValue();
    case "parameters":
      return parametersHandlerEditor.getValue();
    case "permissions":
      return permissionsHandlerEditor.getValue();
    default:
      alert("ERROR");
      break;
  }
}

/*--- STATEFUL SETUP ---*/
/*const rowTemplate = '<td><input placeholder="count" id="sf-prefix-parameter" type="text" class="validate" stateful-track></td><td><input placeholder="100" id="sf-prefix-value" type="text" class="validate"></td><td><a class="btn-floating" onclick="stateful.removeRow(\'sf-prefix-\')"><i class="material-icons">delete</i></a></td>';
stateful.rowTemplate = rowTemplate;
stateful.rowProperties = [
  {
    name: 'parameter'
  },
  {
    name: 'value'
  },
]
stateful.addRow({});
*/
var currentParameterValueIndex = 0;

function addNewParameter() {
  var cell = document.getElementById("api-man-test-form-parameters").insertRow(currentParameterValueIndex);
  cell.innerHTML = '<td id="row/' + currentParameterValueIndex + '"><input placeholder="count" id="parameter/' + currentParameterValueIndex + '" type="text" class="validate" onkeyup="parameterChanged(' + currentParameterValueIndex + ')"></td><td><input placeholder="100" id="value/' + currentParameterValueIndex + '" type="text" class="validate" onkeyup="valueChanged(' +  currentParameterValueIndex + ')"></td><td><a class="btn-floating" onclick="stateful.removeRow(' + currentParameterValueIndex + ')"><i class="material-icons">delete</i></a></td>';
   testparameters[currentParameterValueIndex] = {"parameter":"", "value":""};
  currentParameterValueIndex++;
 
}

function parameterChanged(index) {
  testparameters[index]['parameter'] = document.getElementById('parameter/' + index).value;
  //alert(document.getElementById('parameter/' + index).value);
  console.log(JSON.stringify(testparameters));
}
function valueChanged(index) {
  testparameters[index]['value'] = document.getElementById('value/' + index).value;
  //alert(document.getElementById('value/' + index).value);
  
  console.log(JSON.stringify(testparameters));
}

/*--- API REQUEST INFORMATION & TESTER ---*/

var testoutput = document.getElementById("api-man-test-request-output");

var testmethod = document.getElementById("api-man-test-form-method");

var testparameters = [];

function makeRequest() {
  if(testmethod.value == "GET") {
    makeGetRequest(ROOT_URL + ROUTE, testparameters);
  } else if(testmethod.value == "POST") {
    makePostRequest(ROOT_URL + ROUTE, testparameters);
  }
}

function makeGetRequest(request, params) {
  
  for(var p = 0; p < params.length; p++) {
    if(p == 0) {
      request += "?" + params[p]['parameter'] + "=" + params[p]['value'];
    } else {
      request += "&" + params[p]['parameter'] + "=" + params[p]['value'];
    }
  }
  
  //alert(request);
  
  $.get(request, function(data, success) {
    if(success == "success") {
      testoutput.innerHTML = data;
    } else {
      testoutput.innerHTML = "Error making request";
    }
  });
}

function parametersArrayToObject(arr) {
var obj = {};
  for(var i = 0; i < arr.length; i++) {
    obj[arr[i]['parameter']] = arr[i]['value'];
  }
  return obj;
}

function makePostRequest(request, params) {
$.post(request,parametersArrayToObject(params), function(data, success) {
    if(success == "success") {
      testoutput.innerHTML = data;
    } else {
      testoutput.innerHTML = "Error making request";
    }
  });
}

/*--- HELPERS FOR THE CODE EDITOR ---*/
function addScript(editorid) {
  promptForScript(function(fileobject) {
    var text = getEditorText(editorid);
    var newtext = "";
    var components = text.split("<?php");
    for(var i = 0; i < components.length; i++) {
      if(i != (components.length - 1)) {
       newtext += "<?php";
      }
      if(i == 0) {
        newtext += "\n\trequire 'scripts/" + fileobject["name"] + "';";
      }
      newtext += components[i];
    }
    
    if(editorid == 'index') {
      routeHandlerEditor.setValue(newtext);
    } else if(editorid == 'parameters') {
      parametersHandlerEditor.setValue(newtext);
    } else if(editorid == 'permissions') {
      permissionsHandlerEditor.setValue(newtext);
    }
    
  });
}

/*--- EXECUTE FUNCTIONS ON PAGE LOAD ---*/

bindButtons();
prepareEditors();
downloadEditorText();
//refreshOriginal();
//observeEditorChanges();