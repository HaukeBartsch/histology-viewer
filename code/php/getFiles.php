<?php

if (isset($_GET['path'])) {
   $path = $_GET['path'];
} else {
   echo ("{ \"message\": \"Error: could not find path to look at\"}");
   return;
}

$tmp = scandir($path);
$files = array();
foreach($tmp as $f) {
   if ($f != "." && $f != "..") {
     $files[] = $path."/".$f;
   }
}
echo(json_encode($files,TRUE));

?>