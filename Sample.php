<html lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
    <meta charset="utf-8">
    <title>MMIL Report Sample Page</title>
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
    <link href='/css/papaya.css' type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  </head>
  <body>


<div class="navbar navbar-default badge-primary navbar-fixed-top">
 <div class="navbar-header"><a class="navbar-brand" href="https://137.110.172.64/">Report</a>
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
        <li>       
          <div class="panel-tiny section" section="headneck" style="background: rgba(0,0,0,0) url('images/HEADNECK2.png') no-repeat scroll center top / cover;">
            <p>Head/Neck</p>
          </div>
        </li>
        <li>
          <div class="panel-tiny section" section="prostate" style="background: rgba(0,0,0,0) url('images/PROSTATE2.png') no-repeat scroll center top / cover;">
            <p><span title="Pelvis/Prostate">Pelvis</span> </p>
          </div>
        </li>
        <li>
          <div class="panel-tiny section" section="animal" style="background: rgba(0,0,0,0) url('images/MOUSE.png') no-repeat scroll center top / cover;">
            <p><span title="glioblastoma multiform">GBM</span> </p>
          </div>
        </li>
      </ul>
    </div>
</div>


<div class="container-fluid" id="main">
  <div class="row-fluid">
    <div class="col-md-12">
      &nbsp;
    </div>
  </div>

  <div class="row-fluid">
    <div class="col-md-5">

      <dl>
        <dt>Identifier</dt><dd><div id="identifier"></div></dd>
        <dt>Region</dt><dd><div id="region"></div></dd>
        <dt>Section</dt><dd><div id="section"></div></dd>
        <dt>HistoPath Block</dt><dd><div id="block"></div></dd>
        <dt>Path</dt><dd><div id="path"></div></dd>
      </dl>
    </div>
    <div class="col-lg-3 col-md-5 col-sm-12 col-xs-12">
      <button class="btn btn-primary pull-right" id="save-location-button">save</button>
      <!-- <button class="btn btn-default pull-right" id="delete-location-button">delete</button> -->
      <div id="keyviews2"></div>
    </div>
    <div class="col-lg-4 col-md-4 hidden-md hidden-xs hidden-sm">
          <div id="anatomic-headneck" section="headneck" class="anatomic-selection map">
            <img src="images/HeadNeckAnatomy.png" class="map maphilighted pull-right" border="0" style="max-height: 200px;" alt="" />
          </div>
          <div id="anatomic-prostate" section="prostate" class="anatomic-selection map">
            <img src="images/prostateAnatomy_small2.png" style="max-height: 200px;" class="map maphilighted pull-right" border="0" alt="" />
          </div>
          <div id="anatomic-animal" section="animal" class="anatomic-selection map">
            <img src="images/gbmAnatomy_small2.png" style="max-height: 200px;" class="map maphilighted pull-right" border="0" alt="" />
          </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row-fluid">
    <div class="col-lg-4 col-md-6">
      <div class="">
        <h1>Notes</h1>
        <textarea id="notes-short" style="width: 100%; height: 20em;"></textarea>&nbsp;
        <textarea id="notes" style="width: 100%; height: 40em;"></textarea>
      </div>
    </div>
    <div class="col-lg-4 col-md-6">
      <div class="">
        <h1>Scans</h1>
        <div style="margin-bottom: 20px;">
          <table class="table table-compact">
           <tr>
             <td>MRN:</td><td> <span id="mr-study-patientid"></span></td>
           </tr>
           <tr>
             <td>Accession:</td><td> <span id="mr-study-accession-number"></span></td>
           </tr>
           <tr>
             <td>StudyDate:</td><td> <span id="mr-study-date"></span></td>
           </tr>
           <tr>
             <td>Description:</td><td> <span id="mr-series-description"></span></td>
           </tr>
           <tr>
             <td>Age:</td><td> <span id="mr-patient-age"></span></td>
           </tr>
           <tr>
             <td>Sex:</td><td> <span id="mr-patient-sex"></span></td>
           </tr>
          </table>
        </div>
        <div class="papaya" data-params="volparams" style="padding-left: 10px; padding-right: 10px; background-color: black;"></div>
      </div>
    </div>
    <div class="col-lg-4 col-md-12">
      <div class="">
        <h1>Block <button style="margin-bottom: 5px;" class="btn glyphicon glyphicon-new-window"></button><button class="btn btn-primary btn-large pull-right scoring" style="margin-top: 5px;">Scoring</button></h1>
        <div id="page">
          <span id="keyviews" style="position: relative; float: right;"></span>
          <div id="map" style="border: 1px solid lightgray; min-height: 400px;"></div>
          <div class="scaleBar" style="position: relative; top: -30px; right: -100px; width: 100px; height: 50px;">
            <div id='scaleLine' style="position: absolute; background-color: #555555; color: #555555; height: 3px; width: 100px;"></div><br />
            <div id='scaleText' style="position: relative; top: -10px; right: -10px;"></div>
          </div>
        </div>
      </div>
    </div>
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
        </form>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="edit-param-save" title="Save the location of all image viewers">Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



    <script type="text/javascript" src="//maps.google.com/maps/api/js"></script>
    <script type="text/javascript" src="/js/Papaya/build/papaya.js"></script>
    <script type="text/javascript" src="/js/ace/ace.js"></script>

    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script type='text/javascript' src="js/map.js"></script>
    <script type='text/javascript' src="js/volumes.js"></script>
    <script>
      function getQueryParams(qs) {
        qs = qs.split('+').join(' ');
        
        var params = {},
            tokens,
            re = /[?&]?([^=]+)=([^&]*)/g;
        
        while (tokens = re.exec(qs)) {
            params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
        }
        
        return params;
      }

      function loadPath(path) {
          jQuery.getJSON('/code/php/getParam.php?action=list&path=' + encodeURIComponent(path), function(data) {
             //console.log("we have been asked to load the following: " + data[0].path);
             var section = data[0].section;
             // activate the correct anatomic selection map
             var secs = jQuery('.section');
             for (var i = 0; i < secs.length; i++) {
               if (jQuery(secs[i]).attr('section') == section) {
                  jQuery(secs[i]).css('display','');
               } else {
                  jQuery(secs[i]).css('display','none');
               }
             }
             var secs = jQuery('.anatomic-selection');
             for (var i = 0; i < secs.length; i++) {
               if (jQuery(secs[i]).attr('section') == section) {
                  jQuery(secs[i]).css('display','');
               } else {
                  jQuery(secs[i]).css('display','none');
               }
             }

             jQuery('#identifier').text(data[0].subject);
             jQuery('#region').text(data[0].region);
             jQuery('#section').text(data[0].section);
             jQuery('#block').text(data[0].block);
             jQuery('#path').text(data[0].path);
             // jQuery('#notes').val(data[0].notes);
             if (typeof data[0].notes != 'undefined') {
               editor.session.setValue(data[0].notes);
             } else {
               editor.session.setValue("");
             }
             editor2.session.setValue("");

             // load the map
             centreLat = data[0].centreLat;
             centreLon = data[0].centreLon;
             initialZoom = data[0].initalZoom;
             maxZoom = data[0].maxZoom;

             loadMap( data[0].path );
             // we need to find out if we have DICOM images available and create an array of DICOM file names
             
             if ( data.length > 0 && typeof data[0].volumes != 'undefined') {
               loadImages( data[0].volumes.split(",").map(function(a) { return data[0].path + "/" + a; }) );
             } else {
               jQuery('#papayaContainer0').fadeOut();
             }

          }).error(function(data) {
              console.log("Error");
          });
      }

function getInputSelection(el) {
    var start = 0, end = 0, normalizedValue, range,
    textInputRange, len, endRange;
    
    if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
        start = el.selectionStart;
        end = el.selectionEnd;
    } else {
        range = document.selection.createRange();
        
        if (range && range.parentElement() == el) {
            len = el.value.length;
            normalizedValue = el.value.replace(/\r\n/g, "\n");
            
            // Create a working TextRange that lives only in the input
            textInputRange = el.createTextRange();
            textInputRange.moveToBookmark(range.getBookmark());
            
            // Check if the start and end of the selection are at the very end
            // of the input, since moveStart/moveEnd doesn't return what we want
            // in those cases
            endRange = el.createTextRange();
            endRange.collapse(false);
            
            if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
                start = end = len;
            } else {
                start = -textInputRange.moveStart("character", -len);
                start += normalizedValue.slice(0, start).split("\n").length - 1;
                
                if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
                    end = len;
                } else {
                    end = -textInputRange.moveEnd("character", -len);
                    end += normalizedValue.slice(0, end).split("\n").length - 1;
                }
            }
        }
    }
    
    return {
        start: start,
        end: end
    };
}


       function saveLocation() {
          // store the current map, notes and MR location
          var loc = map.getCenter(); // lat, lng
          var zoom = map.getZoom();  // integer
          
          var numTiles = 1 << map.getZoom();
          var projection = map.getProjection(); // new CustomProjection();
          var worldCoordinate = projection.fromLatLngToPoint(loc);
          var pixelCoordinate = new google.maps.Point(
              worldCoordinate.x * numTiles,
              worldCoordinate.y * numTiles);
          var tileCoordinate = new google.maps.Point(
              Math.floor(pixelCoordinate.x / 256),
              Math.floor(pixelCoordinate.y / 256));
          var tilename = customMapOptions.getTileUrl(tileCoordinate, map.getZoom());

          // notes
          jQuery('#notes').focus();
          // var region = getInputSelection(document.getElementById('notes'));

          // we can have more than one selection, save them all as some kind of text
          var region = editor.getSelection();
          if (region.inMultiSelectMode) {
             region.start = region.ranges.map( function(a) { return a.start.column + " " + a.start.row; } ).join(",");
             region.end   = region.ranges.map( function(a) { return a.end.column + " " + a.end.row; } ).join(",");
          } else {
             region = editor.getSelectionRange();
             region.start = region.start.column + " " + region.start.row;
             region.end   = region.end.column + " " + region.end.row;
          }

          // volume index
          var volloc = [ 0, 0, 0];
          if (papayaContainers.length > 0 && typeof papayaContainers[0].viewer != 'undefine' && papayaContainers[0].viewer.axialSlice != null) {
              volloc = [ papayaContainers[0].viewer.sagittalSlice.currentSlice,
                         papayaContainers[0].viewer.coronalSlice.currentSlice,
                         papayaContainers[0].viewer.axialSlice.currentSlice ];
          }
          //console.log("got the location");

          jQuery.get('/code/php/setKeyView.php?path=' + keyViewPath + '&lat=' + loc.lat() 
                     + '&lng=' + loc.lng() + '&zoom=' + zoom + '&filename=' + tilename
                     + '&notesstart=' + region.start + '&notesend=' + region.end
                     + '&vollocX=' + volloc[0] + '&vollocY=' + volloc[1] + '&vollocZ=' + volloc[2], function(data) {
              // reload the keyviews again - we should have a new one
              var query = getQueryParams(document.location.search);
              if (typeof query.path != 'undefined') {
                fillListOfKeyViews(query.path);
              }
          });
      }

      var editor = null;
      var editor2 = null;

      jQuery(document).ready(function() {

        jQuery('#save-location-button').click(saveLocation);

        var query = getQueryParams(document.location.search);
        if (typeof query.path != 'undefined') {
            loadPath(query.path);
            jQuery('.scoring').click(function() {
               window.open(query.path + "/TableSlide.html", "TableSlide");
            });
            jQuery('.glyphicon-new-window').click(function() {
               //window.open(query.path + "/index.html", "FullScreen"); 
                window.open("histology.php?path="+query.path, "FullScreen");
            });
        }

      
        // load this keyview
        jQuery('#keyviews2').on('click', 'img', function() {
            // highlight this keyview but none of the others
           jQuery('#keyviews2').children().removeClass('selected');
           jQuery(this).parent().addClass('selected');

           //console.log('click on ' + jQuery(this).attr('zoom'));
           map.setZoom( parseInt(jQuery(this).attr('zoom')) );
           map.setCenter( new google.maps.LatLng( jQuery(this).attr('lat'), jQuery(this).attr('lng') ) );
           var coor = new papaya.core.Coordinate();
           papayaContainers[0].viewer.getWorldCoordinateAtIndex( parseInt(jQuery(this).attr('vollocX')), 
                              parseInt(jQuery(this).attr('vollocY')), 
                              parseInt(jQuery(this).attr('vollocZ')), coor);
           coor.x = parseInt(jQuery(this).attr('vollocX'));
           coor.y = parseInt(jQuery(this).attr('vollocY'));
           coor.z = parseInt(jQuery(this).attr('vollocZ'));
           papayaContainers[0].viewer.gotoCoordinate(coor);
                              
           var field = document.getElementById('notes');
           //field.focus();
           // field.setSelectionRange( parseInt(jQuery(this).attr('notesstart')), parseInt(jQuery(this).attr('notesend')) );
           // calculate a range for each comma separated entry
           var s = jQuery(this).attr('notesstart').split(',');
           var e = jQuery(this).attr('notesend').split(',');
           editor2.session.setValue(""); // clear the field first
           for (var i = 0; i < s.length; i++) {
                var ra = editor.selection.getRange();
                ra.start.column = parseInt(s[i].split(" ")[0]);
                ra.start.row    = parseInt(s[i].split(" ")[1]);
                ra.end.column   = parseInt(e[i].split(" ")[0]);
                ra.end.row      = parseInt(e[i].split(" ")[1]);
                if ( i == 0) {
                    editor.selection.setRange( ra );
                } else {
                    editor.selection.addRange( ra );
                }
               // copy this text also to notes-short
               var text = editor.session.getTextRange(ra) + "\n";
               editor2.session.setValue( text + editor2.session.getValue() );
           }          
        });

        if ( editor == null) {
           editor = ace.edit("notes");
           editor.container.style.height = 400;
           editor.resize();
        }
        if ( editor2 == null) {
           editor2 = ace.edit("notes-short");
           editor2.container.style.height = 200;
           editor2.resize();
        }
        editor.setTheme("ace/theme/chrome");
        editor.getSession().setUseWrapMode(true);
        editor.setHighlightActiveLine(true);

        editor2.setTheme("ace/theme/crimson_editor");
        editor2.getSession().setUseWrapMode(true);
        editor2.setHighlightActiveLine(true);
        //editor.getSession().setMode("ace/mode/text");
      });
    </script>

  </body>
</html>
