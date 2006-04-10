<?php

if (!empty($_GET['path'])) {
  $path = $_GET['path'];
} else {
  print('no path given');
  return 0;
}
if (!empty($_GET['lat'])) {
  $lat = $_GET['lat'];
} else {
  print('no lat given');
  return 0;
}
if (!empty($_GET['lng'])) {
  $lng = $_GET['lng'];
} else {
  print('no lng given');
  return 0;
}
if (!empty($_GET['zoom'])) {
  $zoom = $_GET['zoom'];
} else {
  print('no zoom given');
  return 0;
}
if (!empty($_GET['filename'])) {
  $filename = $_GET['filename'];
} else {
  print('no filename given');
  return 0;
}
if (!empty($_GET['notesstart'])) {
  $notesstart = $_GET['notesstart'];
} 
if (!empty($_GET['notesend'])) {
  $notesend = $_GET['notesend'];
}
if (isset($_GET['vollocX'])) {
  $vollocX = $_GET['vollocX'];
}
if (isset($_GET['vollocY'])) {
  $vollocY = $_GET['vollocY'];
}
if (isset($_GET['vollocZ'])) {
  $vollocZ = $_GET['vollocZ'];
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

$d = loadDatabase( $path."/dbfile.txt" );
$ar = array("lat" => $lat, "lng" => $lng, "zoom" => $zoom, "filename" => $filename);
if (isset($notesstart)) {
  $ar['notesstart'] = $notesstart;
}
if (isset($notesend)) {
   $ar['notesend'] = $notesend;
}
if (isset($vollocX)) {
   $ar['vollocX'] = $vollocX;
}
if (isset($vollocY)) {
   $ar['vollocY'] = $vollocY;
}
if (isset($vollocZ)) {
   $ar['vollocZ'] = $vollocZ;
}

$d[] = $ar;
saveDatabase( $d, $path."/dbfile.txt" );
echo ("Save to :" . $path."/dbfile.txt" );
return;
?>
