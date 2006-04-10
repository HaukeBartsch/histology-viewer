<html lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
    <meta charset="utf-8">
    <title>MMIL Multimodal Integration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="/bootstrap/img/favicon.ico">
    <link rel="apple-touch-icon" href="/bootstrap/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/bootstrap/img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/bootstrap/img/apple-touch-icon-114x114.png">
    <link href="css/style.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
  </head>
  <body>


<div class="navbar navbar-default badge-primary navbar-fixed-top">
 <div class="navbar-header"><a class="navbar-brand" href="#" data-toggle="modal" data-target="#about">Multimodal Integration</a>
      <a class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <!-- <li><a href="#">Link</a></li>
        <li><a href="#">More</a></li>-->
        <li><a href="#">Histopathology + Surgery + Radiology</a></li>
      </ul>
    </div>
</div>


<div class="container-fluid" id="main">
  <div class="row-fluid">
    <div class="col-md-12">
      <h1>Sections</h1>
      <p>This resource exists since October 2015 and has been developed with support from a Cancer Imaging Grant at UC San Diego. Do not share these images, all right belong to the collaborators working on this project.</p>
    </div>
    <div class="col-md-4">
       <div class="panel-large" style="background: rgba(0,0,0,0) url('images/HEADNECK2.png') no-repeat scroll center top / cover;">
          <p>Head/Neck <button class="pull-right sec-button" part="headneck"><span class="glyphicon glyphicon-ok-sign sign"></span></button></p>
       </div>
    </div>
    <div class="col-md-4">
       <div class="panel-large" style="background: rgba(0,0,0,0) url('images/PROSTATE2.png') no-repeat scroll center top / cover;">
          <p><span title="Pelvis/Prostate">Pelvis</span> <button class="pull-right sec-button" part="prostate"><span class="glyphicon glyphicon-ok-sign sign"></span></button></p>
       </div>
    </div>
    <div class="col-md-4">
       <div class="panel-large" style="background: rgba(0,0,0,0) url('images/MOUSE.png') no-repeat scroll center top / cover;">
          <p><span title="glioblastoma multiform">GBM</span> <button class="sec-button pull-right" part="animal"><span class="glyphicon glyphicon-ok-sign sign"></span></button></p>
       </div>
    </div>
  </div>

  <div class="row-fluid">
    <div class="col-xs-12">
       <h1>Samples</h1>
       <p>The following list of <span class="num-samples"></span> samples is provided for educational purposes only.</p>
    </div>
    <div class="col-xs-12">&nbsp;</div>
    <div class="col-xs-12 subj" id="c">
    
      <!-- item list -->

<?php
    $dir = '/var/www/html/GoogleMaps2/';
    if (!is_readable($dir)) {
      // print('cannot read GoogleMaps directory');
    } else {
      $dh = scandir($dir,1);
      $ds = [];
      foreach ( $dh as $d ) {
         if ($d != "." && $d != "..") {
            // if we find this we want to add this directory
            $dd = 'GoogleMaps2/'.$d.'/Result';
            if (is_dir($dd)) {
               // we found a directory that works for this project
               $ds[] = 'GoogleMaps2/'.$d;
            } elseif (is_dir('GoogleMaps2/'.$d)) {
               $dh2 = scandir('GoogleMaps2/'.$d,1);
               foreach ( $dh2 as $d2 ) {
                  if ( $d2 != "." && $d2 != "..") {
                     $dd2 = 'GoogleMaps2/'.$d.'/'.$d2.'/Result';
                     if (is_dir($dd2)) {
                       // we found a directory that works for this project
                       $ds[] = 'GoogleMaps2/'.$d.'/'.$d2;
                     }
                  }
               }
            }
         }
      }

      foreach ($ds as $d) {
          // The name is the directory
          $name = split('/', $d);
          $block = "";
          if (count($name) > 1) {
             $block = $name[2];
          }
          $name = implode('/', array_slice($name, 1, count($name)));

          // do we have a params file?
          $parfile = $d.'/param.json';
          if (is_readable($d)) {
             $params = json_decode(file_get_contents($parfile), true);
             if (isset($params['subject'])) {
               $name = $params['subject'];
             }
             if (isset($params['block'])) {
               $block = $params['block'];
             }
          }

          // how many keyviews?
          $kv = 0;
          $kvf = $d.'/dbfile.txt';
          if (is_readable($kvf)) {
            $kv = count(file($kvf));
          }
          $str = "";
          $str = $str.'<div class="panel panel-default" style="background-repeat: no-repeat; ';
          $str = $str.'background-position: right 30%; background-size: 40%; ';
          $str = $str.'background-image: url('.$d.'/Result/t.jpg);">';
          $str = $str.'<div onclick="location.href=\''.$d.'/index.html\'" class="panel-heading">';
          $str = $str.'<a href="'.$d.'/index.html">'.$name.' '.$block;
          if ($kv > 0) {
            $str = $str.'&nbsp;&nbsp; #keyviews: '.$kv;
          }
          $str = $str.'</a>';
          //$str = $str.'<span style="font-size: 6pt;text-color: gray;">BLOCK</span>';
          $str = $str.'</div></div>';
          //print($str);
      }
    }
?>

    </div>
    <div class="col-xs-12">
      <hr>
      <small><font color="lightgray";>This is a service of the Multi-Modal Imaging Laboratory at UC San Diego.</font></small>
      <p>&nbsp;</p>
    </div>
  </div>


<div class="modal fade" id="edit-param" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Parameter <span class="tiny" id="edit-param-title-sub"></span></h4>
      </div>
      <div class="modal-body">

        <form>
          <div class="form-group">
            <label for="edit-param-section">Section</label>
            <select class="form-control" id="edit-param-section">
              <option value="headneck">HEAD/NECK</option>
              <option value="prostate">PROSTATE</option>
              <option value="animal">ANIMAL</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="form-group">
             <label for="edit-param-region">Region</label>
             <input type="text" class="form-control" id="edit-param-region" placeholder="anatomical region">
          </div>

          <center>
          <div id="anatomic-headneck" section="headneck" class="anatomic-selection map">
            <img src="images/HeadNeckAnatomy.png" class="map maphilighted" border="0" width="575" height="625" orgWidth="575" orgHeight="625" usemap="#map-anatomic-headneck" alt="" />
            <map name="map-anatomic-headneck" id="map-anatomic-headneck">
              <area shape="rect" coords="573,623,575,625" alt="Image Map" style="outline:none;" title="Image Map" href="#" />
              <area id="h1" alt="" title="1" href="#" shape="poly" coords="316,180,306,167,280,200,254,211,154,227,91,243,83,263,101,279,125,299,163,325,242,299" target="_self"     />
              <area id="h2" alt="" title="2" href="#" shape="poly" coords="317,181,243,297,372,279,388,176,389,163,385,154,379,150,363,161,346,174,336,180" target="_self"  />
              <area id="h3" alt="" title="3" href="#" shape="poly" coords="373,279,242,299,203,310,260,427,310,428,364,430" style="outline:none;" target="_self"     />
              <area id="h4" alt="" title="4" href="#" shape="poly" coords="362,431,313,427,262,430,301,537,297,575,285,605,279,614,335,601,356,567" style="outline:none;" target="_self"     />
              <area id="h5" alt="" title="5" href="#" shape="poly" coords="398,171,397,250,419,349,474,469,505,514,495,537,440,564,336,604,352,573,370,333,387,187" style="outline:none;" target="_self"     />
              <area id="h6" alt="" title="6" href="#" shape="poly" coords="200,312,163,324,173,360,188,442,230,565,253,607,272,615,288,595,301,537,261,428" style="outline:none;" target="_self"     />
            </map>
          </div>
          </center>

          <center>
          <div id="anatomic-prostate" section="prostate" class="anatomic-selection map">
            <img src="images/prostateAnatomy_small2.png" class="map maphilighted" border="0" width="600" height="285" orgWidth="600" orgHeight="285" usemap="#map-anatomic-prostate" alt="" />
            <map name="map-anatomic-prostate" id="map-anatomic-prostate">
              <area shape="rect" coords="598,283,600,285" alt="Image Map" style="outline:none;" title="Image Map" href="#" />
              <area id="p1" alt="" title="Central Zone" href="#" shape="poly" coords="143,88,123,78,99,74,74,86,70,93,71,111,86,135,123,164,146,176,154,176,154,173,145,169,139,152,142,137,152,126" style="outline:none;" target="_self"     />
              <area id="p2" alt="" title="Central Zone" href="#" shape="poly" coords="181,86,191,79,213,76,237,84,242,106,234,126,213,150,184,170,171,177,161,176,163,173,173,168,179,152,178,138,173,129,168,125" style="outline:none;" target="_self" />
              <area id="p3" alt="" title="Transition Zone" href="#" shape="poly" coords="153,128,146,133,139,142,141,157,145,167,154,173,156,157" style="outline:none;" target="_self"     />
              <area id="p4" alt="" title="Transition Zone" href="#" shape="poly" coords="169,126,163,145,164,175,168,172,178,158,177,141,175,131" style="outline:none;" target="_self"     />
              <area id="p5" alt="" title="Peripheral Zone" href="#" shape="poly" coords="242,102,260,127,263,174,233,224,193,257,174,267,164,262,160,254,158,224,161,201,178,182,178,179,173,176,183,170,201,159,222,141,238,123" style="outline:none;" target="_self"/>
              <area id="p6" alt="" title="Peripheral Zone" href="#" shape="poly" coords="70,99,50,119,47,156,71,211,101,245,130,265,142,266,146,258,150,231,152,200,137,179,137,175,142,174,114,160,90,139,74,119" style="outline:none;" target="_self" />
              <area id="p7" alt="" title="vera montanium" href="#" shape="poly" coords="162,177,179,178,176,185,162,201,160,201" style="outline:none;" target="_self"/>
              <area id="p8" alt="" title="vera montanium" href="#" shape="poly" coords="155,177,143,176,135,176,140,185,154,202,155,189" style="outline:none;" target="_self"/>
              <area id="p9" alt="" title="Peripheral Zone" href="#" shape="poly" coords="433,46,407,63,386,107,388,166,407,212,453,254,476,261,493,258,497,247,490,225,490,171,484,160,479,143,456,125,440,101" style="outline:none;" target="_self"/>
              <area id="p10" alt="" title="Anterior fibromuscular stoma" href="#" shape="poly" coords="580,79,545,99,544,122,529,143,514,149,506,151,497,176,498,220,512,248,521,246,555,222,583,180,596,124,590,97" style="outline:none;" target="_self"/>
              <area id="p11" alt="" title="Central Zone" href="#" shape="poly" coords="441,41,449,33,468,31,499,34,547,54,523,86,506,92,491,107,488,124,492,139,485,140,480,139,479,142,458,127,444,104,434,67,433,46" style="outline:none;" target="_self"/>
              <area id="p12" alt="" title="Transition Zone" href="#" shape="poly" coords="523,85,504,96,492,110,489,127,492,140,497,138" style="outline:none;" target="_self"     />
              <area id="p13" alt="" title="Transition Zone" href="#" shape="poly" coords="545,100,522,123,504,150,519,148,535,136,545,118" style="outline:none;" target="_self"     />
              <area id="p14" alt="" title="vera montanium" href="#" shape="poly" coords="494,145,488,141,480,140,479,144,480,154,490,168" style="outline:none;" target="_self"     />
            </map>
          </div>
          </center>

          <center>
          <div id="anatomic-gbm" section="animal" class="anatomic-selection map">
            <img src="images/gbmAnatomy_small2.png" class="map maphilighted" border="0" width="600" height="285" orgWidth="600" orgHeight="285" usemap="#map-anatomic-animal" alt="" />
          </div>
          </center>
          
          <div class="form-group">
             <label for="edit-param-notes">Notes</label>
             <textarea rows="10" class="form-control" id="edit-param-notes" placeholder="Notes"></textarea>
          </div>
          <div class="form-group">
             <label for="edit-param-subject">Patient</label>
             <input type="text" class="form-control" id="edit-param-subject" placeholder="Name">
          </div>
          <div class="form-group">
             <label for="edit-param-block">Block</label>
             <input type="text" class="form-control" id="edit-param-block" placeholder="Block">
          </div>
          <div class="form-group">
             <label for="edit-param-max-zoom">Max Zoom Level</label>
             <input type="number" class="form-control" id="edit-param-max-zoom" placeholder="8">
          </div>
          <div class="form-group">
             <label for="edit-param-inital-zoom">Initial Zoom Level</label>
             <input type="number" class="form-control" id="edit-param-inital-zoom" placeholder="2">
          </div>
          <div class="form-group">
             <label for="edit-param-centre-lon">Center Longitude</label>
             <input type="text" class="form-control" id="edit-param-centre-lon" placeholder="-88.76953125">
          </div>
          <div class="form-group">
             <label for="edit-param-centre-lat">Center Latitude</label>
             <input type="text" class="form-control" id="edit-param-centre-lat" placeholder="76.72022329036133">
          </div>
          <div class="form-group">
             <label for="edit-param-volumes">Volumes</label>
             <input type="text" class="form-control" id="edit-param-volumes" placeholder="../T2, ../T1">
          </div>
        </form>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="edit-param-save">Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<div class="modal fade" id="about" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">HistoPath</h4>
      </div>
      <div class="modal-body">
        <div>
          <div class="about-group row">
            <div class="col-md-3">
              <img width="100%" src="/images/Histopathology_small.png">
            </div>
            <div class="col-md-9">
              <div class="about-group-title">Histopathology</div>
              <p>the microscopic examination of tissue in order to study the manifestations of disease</p>
            </div>
          </div>
          <div class="about-group row">
            <div class="col-md-3">
              <img width="100%" src="/images/Surgery.png">
            </div>
            <div class="col-md-9">
              <div class="about-group-title">Surgery</div>
              <p>investigates and/or treats a pathological condition such as disease or injury</p>
            </div>
          </div>
          <div class="about-group row">
            <div class="col-md-3">
              <img width="100%" src="/images/Radiology.png">
            </div>
            <div class="col-md-9">
              <div class="about-group-title">Radiology</div>
              <p>imaging to diagnose and treat diseases seen within the body</p>
            </div>
          </div>
        </div>
        <p>This resource as been created with generous support from the<center><H3 style="color: salmon;">Cancer Imaging Program (CIP)</H3></center> at UC San Diego.</p>
        <div class="well"><p><i>"An integrative approach to histological sections &dash; accelerating the integration of multi-modality imaging into clinical practice."</i><br/><span class="pull-right">Hauke Bartsch, PhD, and David Hall, PhD</span></p></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


        <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <script type='text/javascript' src="js/jquery.maphilight.js"></script>
    <script>
        jQuery(document).ready(function() {

           jQuery('#map-anatomic-headneck').maphilight({
              fillColor: '008800'
           });
 /*          jQuery('#map-anatomic-prostate').maphilight({
              fillColor: '008800'
           }); */

           jQuery.getJSON('/code/php/getParam.php?action=list', function(data) {
             str = "";
             jQuery('.num-samples').text(data.length);
             for (var i = 0; i < data.length; i++) {
                str = str + "<div class=\"panel panel-default\" style=\"background-repeat: no-repeat; ";
                str = str + "  background-position: right 30%; background-size: 40%; ";
                str = str + "  background-image: url(" + data[i].path + "/Result/t.jpg);\" section=\""+ data[i].section +"\">";
                str = str + "  <div class=\"panel-heading\">";
                str = str + "    <a href=\"/Sample.php?path=" + data[i].path + "\">" + data[i].subject + " " + data[i].block;
                //if (kv > 0) {
                //  str = str + "&nbsp;&nbsp; #keyviews: " + kv;
                //}
                str = str + '    </a>';
                str = str + '<button class="pull-right im-button" path="' + data[i].path + '"><span class="glyphicon glyphicon-edit"></span></button>';
                str = str + '</div></div>';
             }
             jQuery('.subj').append(str);
           });

           jQuery('.sec-button').click(function() {
              // toggle to on/off
              var icon = jQuery(this).find('.sign');
              if (icon.hasClass('glyphicon-ok-sign')) {
                icon.removeClass('glyphicon-ok-sign').addClass('glyphicon-ban-circle');
              } else {
                icon.removeClass('glyphicon-ban-circle').addClass('glyphicon-ok-sign');
              }
              changeVisibility();
           });

           jQuery('.subj').on('click', '.im-button', function(event) {
              var p = jQuery(this).attr('path');
              //console.log("edit this section:" + p);
              
              jQuery.getJSON('/code/php/getParam.php?action=list&path='+p, function(data) {
                 data = data[0];
                 if (typeof data['section'] !== 'undefined') {
                    jQuery('#edit-param-section').val(data['section']).change();
                 } else {
                    jQuery('#edit-param-section').val("").change();
                 }
                 if (typeof data['region'] !== 'undefined') {
                    jQuery('#edit-param-region').val(data['region']);
                 } else {
                    jQuery('#edit-param-region').val("");
                 }
                 if (typeof data['subject'] !== 'undefined') {
                    jQuery('#edit-param-subject').val(data['subject']);
                 } else {
                    jQuery('#edit-param-subject').val("");
                 }
                 if (typeof data['notes'] !== 'undefined') {
                    jQuery('#edit-param-notes').val(data['notes']);
                 } else {
                    jQuery('#edit-param-notes').val("");
                 }
                 if (typeof data['block'] !== 'undefined') {
                    jQuery('#edit-param-block').val(data['block']);
                 } else {
                    jQuery('#edit-param-block').val("");
                 }
                 if (typeof data['maxZoom'] !== 'undefined') {
                    jQuery('#edit-param-max-zoom').val(data['maxZoom']);
                 } else {
                    jQuery('#edit-param-max-zoom').val(""); 
                 }
                 if (typeof data['initalZoom'] !== 'undefined') {
                    jQuery('#edit-param-inital-zoom').val(data['initalZoom']);
                 } else {
                    jQuery('#edit-param-inital-zoom').val("");
                 }
                 if (typeof data['centreLon'] !== 'undefined') {
                    jQuery('#edit-param-centre-lon').val(data['centreLon']);
                 } else {
                    jQuery('#edit-param-centre-lon').val("");
                 }
                 if (typeof data['centreLat'] !== 'undefined') {
                    jQuery('#edit-param-centre-lat').val(data['centreLat']);
                 } else {
                    jQuery('#edit-param-centre-lat').val("");
                 }
                 if (typeof data['volumes'] !== 'undefined') {
                    jQuery('#edit-param-volumes').val(data['volumes']);
                 } else {
                    jQuery('#edit-param-volumes').val("");
                 }
                 jQuery('#edit-param-inital-zoom').parent().parent().attr('path', data['path']);
                 jQuery('#edit-param-title-sub').text(data['path']);

                 jQuery('#edit-param').modal();
              });

              event.preventDefault();
              return false;
           });

           jQuery('#edit-param-save').click(function() {
              var data = {
                action: "change",
                section: jQuery('#edit-param-section').val(),
                region:  jQuery('#edit-param-region').val(),
                notes:   jQuery('#edit-param-notes').val(),
                subject: jQuery('#edit-param-subject').val(),
                block  : jQuery('#edit-param-block').val(),
                maxZoom: jQuery('#edit-param-max-zoom').val(),
                initalZoom: jQuery('#edit-param-inital-zoom').val(),
                centreLon:  jQuery('#edit-param-centre-lon').val(),
                centreLat:  jQuery('#edit-param-centre-lat').val(),
                volumes:    jQuery('#edit-param-volumes').val(),
                path:       jQuery('#edit-param-inital-zoom').parent().parent().attr('path')
              };
              jQuery.getJSON('/code/php/getParam.php', data, function(dat) {

              });
           });

           jQuery('#edit-param-section').change(function() {
              // only display the anatomy section for this type
              var section = jQuery('#edit-param-section').val();
              var ana = jQuery('#edit-param').find('.anatomic-selection');
              for (var i = 0; i < ana.length; i++) {
                 if (jQuery(ana[i]).attr('section') == section) {
                   jQuery(ana[i]).css('display', '');
                 } else {
                   jQuery(ana[i]).css('display', 'none');
                 }
              }
           });

           jQuery('.anatomic-selection').click(function(event) {
             jQuery('#edit-param-region').val(event.target.title);
           });

        });

        function changeVisibility() {
           var sections = jQuery.find('.sec-button');
           var numvis = 0;
           for (var i = 0; i < sections.length; i++) {
              console.log('change visibility for: ' + jQuery(sections[i]).attr('part'));
              var sectionname = jQuery(sections[i]).attr('part');
              var vis = jQuery(sections[i]).find('span').hasClass('glyphicon-ok-sign');
              // enable or disable all entries in c list
              var entries = jQuery('#c').children();
              for(var j = 0; j < entries.length; j++) {
                 var p = jQuery(entries[j]).attr('section');
                 if (p == sectionname) {
                    if (vis) {
                       jQuery(entries[j]).fadeIn(); // css('display', '');
                       numvis = numvis + 1;
                    } else {
                       jQuery(entries[j]).fadeOut(); // css('display', 'none');
                    }
                 }
              }
             jQuery('.num-samples').text(numvis);
           }
        }
    </script>

  </body>
</html>
