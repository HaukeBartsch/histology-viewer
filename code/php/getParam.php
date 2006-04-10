<?php

$action = "list";
$path = "";

if (isset($_GET['action'])) {
  $action = $_GET['action'];
}
if (isset($_GET['path'])) {
  $path = $_GET['path'];
}

function readParams() {
   $dir= '../../GoogleMaps2/';
   $dh = scandir($dir,1);
   $ds = [];
   foreach ( $dh as $d ) {
      if ($d != "." && $d != "..") {
         // if we find this we want to add this directory
         $dd = '../../GoogleMaps2/'.$d.'/Result';
         if (is_dir($dd)) {
            //echo ("check: ".$dd."\n");
            // we found a directory that works for this project
            $ds[] = '../../GoogleMaps2/'.$d;
         } elseif (is_dir('../../GoogleMaps2/'.$d)) {
            $dh2 = scandir('../../GoogleMaps2/'.$d,1);
            // echo ("check: ../../GoogleMaps2/".$d."\n");
            foreach ( $dh2 as $d2 ) {
               if ( $d2 != "." && $d2 != "..") {
                  $dd2 = '../../GoogleMaps2/'.$d.'/'.$d2.'/Result';
                  if (is_dir($dd2)) {
                    //echo ('check: ../../GoogleMaps2/'.$d.'/'.$d2."\n");
                    // we found a directory that works for this project
                    $ds[] = '../../GoogleMaps2/'.$d.'/'.$d2;
                  }
               }
            }
         }
      }
   }
   $params = [];
   foreach ($ds as $d) {
      // The name is the directory
      $parfile = $d.'/param.json';
      if (is_readable($d)) {
         $ps = json_decode(file_get_contents($parfile), true);
         if (!isset($ps['subject'])) {
           $ps['subject'] = "unknown";
         }
         if (!isset($ps['block'])) {
           $ps['block'] = "unknown";
         }
         $ps['path'] = $d;
         $params[] = $ps;
      } else {
         // lets create one so we can add information to it
         $ps = array( "block" => "unknown", "subject" => "unknown", "path" => $d );
         $params[] = $ps;
      }
   }

   return $params;
}

if ($action == "list") {
   $parms = readParams();
   $d = array();
   if ( $path != "" ) {
      // search for this path and only return its entry
      // TODO: rewrite this so that given a path only that directory needs to be queried to improve speed
      $found = 0;
      foreach( $parms as $par ) {
         if ($par['path'] == $path) {
            $d[] = $par;
            $found = 1;
         }
      }
      if ($found == 0) {
        echo("{ \"message\": \"Error: this path ". $path . " could not be found\" }");
      }
   } else {
     $d = $parms;
   }
   echo(json_encode($d, JSON_PRETTY_PRINT));
} elseif ($action == "change") {
   if ($path == "") {
      echo ("{\"message\": \"Error: unknown path\"}");
      return;
   }  

   $parms = readParams();
   // find the one we want to change based on the path
   foreach ($parms as $par) {
      if ($par['path'] == $path) { // update all entries in this params
         // save a merge of the values we had the values we got new
         foreach ( array_keys($_GET) as $key ) {
            if ($key == 'action') {
               continue;
            } 
            $par[$key] = $_GET[$key];
         }
         $f = $path.'/param.json';
         if (is_writable($f)) {
            file_put_contents($f, json_encode($par, JSON_PRETTY_PRINT));
            echo ("{\"message\": \"Ok\"}");
         } else {
            // maybe the file does not exist?
            if (!file_exists($f)) {
               file_put_contents($f, json_encode($par, JSON_PRETTY_PRINT));
            } else {
               echo ("{\"message\": \"Error: file is not writable: " . $f . "\"}");
            }
         }
         return;
      }
   }
} else {
  echo ("{ \"message\": \"Error: unknown action\" }");
}

?>
