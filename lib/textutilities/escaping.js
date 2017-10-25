/**
 * Takes data escaped in a certain format and returns it to its original state
 * @param data The string that should be unescaped
 * @return The unescaped string
 */
function unescape(data) {

  // The following patterns correspond to their original form
  // CR; \r
  // LF; \n
  // Q1; '
  // Q2; "
  // S1; /
  // S2; \
  
  data = data.replace(/CR;/g, '\r');
  data = data.replace(new RegExp('LF;', 'g'), '\n');
  data = data.replace(/Q1;/g, "'");
  data = data.replace(/Q2;/g, '"');
  data = data.replace(/S1;/g, '/');
  data = data.replace(/S2;/g, '\\');
  
  return data;
}