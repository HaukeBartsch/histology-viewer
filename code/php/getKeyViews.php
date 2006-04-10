<?php

function loadDatabase ( $db_file ) {
  $d = json_decode(file_get_contents($db_file), true);

  return $d;
}

$path = "";
if (isset($_GET['path'])) {
  $path = $_GET['path'];
}

$d = loadDatabase( $path."/dbfile.txt" );

echo(json_encode($d, true));

?>