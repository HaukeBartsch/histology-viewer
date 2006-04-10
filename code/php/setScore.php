<?php

if (isset($_GET['num'])) {
  $num = $_GET['num'];
}
if (isset($_GET['score'])) {
  $score = $_GET['score'];
}
if (isset($_GET['path'])) {
   $path = $_GET['path'];
}

function loadDatabase ( $db_file ) {
  $d = json_decode( file_get_contents($db_file), true );

  return $d;
}

function saveDatabase( $data, $filename) {
  if (file_exists($filename)) {
     if (!is_writable($filename)) {
       echo("{ \"message\": \"Error: file is not writable\"}");
       return;
     }
  }
  file_put_contents($filename, json_encode($data));

}

$d = loadDatabase( $path."/scores.json" );

$d['num'.$num] = $score;

saveDatabase( $d, $path."/scores.json" );
echo ("Save to :" . $path."/scores.json" );

?>
