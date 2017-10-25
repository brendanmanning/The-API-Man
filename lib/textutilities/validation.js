function validateRestPath(str) {

  if(str.length < 1) {
    return false;
  }

  for (var i = 0, len = str.length; i < len; i++) {
    if( /[^a-zA-Z0-9]/.test( str[i] ) && str[i] != '/' && str[i] != '\\') {
     return false;
    }
  }
  
  return true;
}