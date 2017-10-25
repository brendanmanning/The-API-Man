function validate() {
  var routeNameField = document.getElementById("route_name");
  var explanationField = document.getElementById("explanation");
  var submitButton = document.getElementById("submit");
  
  var routeName = routeNameField.value;
  
  if(validateRestPath(routeName)) {
    routeNameField.classList.remove('invalid');
    routeNameField.classList.add('valid');
    
    explanationField.style.display = 'none';
    submitButton.style.display = '';
  } else {
    routeNameField.classList.remove('valid');
    routeNameField.classList.add('invalid');
    
    explanationField.style.display = '';
    submitButton.style.display = 'none';
  }
}

function submit() {
  var routeNameField = document.getElementById("route_name");
  var routeName = routeNameField.value;
  
  $.post(ROOT_URL + "internal-api/createroute.php", {
    route: routeName,
    username: USER_NAME,
    token: ACCESS_TOKEN
  }, function(data, status) {
    if(status == "success") {
      var json = JSON.parse(data);
      if(json['status'] == 201) {
        window.location.href = 'api-man-edit-route.php?route=' + routeName
      } else {
        Materialize.toast(data['message'], 3000);
      }
    } else {
        Materialize.toast('Route not created due to an unknown error. Please try again.', 3000);
    }
  });
}