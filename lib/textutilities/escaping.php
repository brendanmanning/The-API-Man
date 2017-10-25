<?php
/**
 * Takes data and escapes in a certain format
 * @param data The string that should be escaped
 * @return The escaped string
 */
function escape($data) {

  // The following patterns correspond to their original form
  // CR; \r
  // LF; \n
  // Q1; '
  // Q2; "
  // S1; /
  // S2; \
  
  $data = str_replace( "\r", "CR;", $data );
  $data = str_replace("\n", 'LF;', $data);
  $data = str_replace("'", 'Q1;', $data);
  $data = str_replace('"', 'Q2;', $data);
  $data = str_replace('/', 'S1;', $data);
  $data = str_replace('\\', 'S2;', $data);
  
  return $data;
}
?>