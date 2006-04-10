<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    
    <title>Histology Full Screen Viewer</title>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  </head>
  <body style="background: rgb(255, 255, 255);" onload="load();" onunload="" onresize="resizeMapDiv();">

  <div style="float: left; width: 100%; z-index: 1;" class="tinyText style_SkipStroke_7">

  <link type="text/css" rel="stylesheet" media="screen" href="styles.css" /> 
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">

  <script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>

  <!-- get the initial center location using javascript:void(prompt('',map.getCenter())); -->
  <script type="text/javascript">
    //<![CDATA[
    
    // call this with: 
    // file:///Users/nmschenker/GoogleProstate/WC_D23_10x/results/index.html?zoom=6&center=23,42


    // ?zoom=8&center=-50.64597734071358 ,-104.34814453125
    var centreLat=68.65229049197069; //66.56440944430722 , -61.171875
    var centreLon=-91.0078239440918;
    var initialZoom=2;
    var path = '';

    var str = window.location.search;
    if (str !== "") {
      var arguments = str.split('?')[1].trim();
      var args = arguments.split('&');
      for (var i = 0; i < args.length; i++) {
          var v   = args[i].split('=')[0].trim();
          var val = args[i].split('=')[1].trim();
    
          if (v == 'zoom') {
      	      initialZoom = parseInt(val1);
          } 

          if (v == 'center' ) {
    	      centreLat = parseFloat(val.split(',')[0]);
    	      centreLon = parseFloat(val.split(',')[1]);
          }
          if (v == 'path') {
              path = val;
          }
      }
    }
    
    //alert(' args: ' + centreLat + ' ' + centreLon + ' ' + initialZoom);
    
    var imageWraps=false; //SET THIS TO false TO PREVENT THE IMAGE WRAPPING AROUND
    var map; //the GMap2 itself

    /////////////////////
    //Custom projection
    /////////////////////
    function CustomProjection(a,b){
	this.imageDimension=65536;
	this.pixelsPerLonDegree=[];
	this.pixelOrigin=[];
	this.tileBounds=[];
	this.tileSize=256;
        this.isWrapped=b;
	var b=this.tileSize;
	var c=1;
	for(var d=0;d<a;d++){
          var e=b/2;
          this.pixelsPerLonDegree.push(b/360);
          this.pixelOrigin.push(new google.maps.Point(e,e));
          this.tileBounds.push(c);
          b*=2;
          c*=2
        }
    }

    CustomProjection.prototype.fromLatLngToPixel=function(latlng,zoom){
        var c=Math.round(this.pixelOrigin[zoom].x+latlng.lng()*this.pixelsPerLonDegree[zoom]);
        var d=Math.round(this.pixelOrigin[zoom].y+(-2*latlng.lat())*this.pixelsPerLonDegree[zoom]);
        return new google.maps.Point(c,d)
    };

    CustomProjection.prototype.fromPixelToLatLng=function(pixel,zoom,unbounded){
        var d=(pixel.x-this.pixelOrigin[zoom].x)/this.pixelsPerLonDegree[zoom];
        var e=-0.5*(pixel.y-this.pixelOrigin[zoom].y)/this.pixelsPerLonDegree[zoom];
        return new google.maps.LatLng(e,d,unbounded)
    };

    CustomProjection.prototype.tileCheckRange=function(tile,zoom,tilesize){
        var tileBounds=this.tileBounds[zoom];
	if (tile.y<0 || tile.y >= tileBounds) {return false;}
        if (this.isWrapped) {
		if (tile.x<0 || tile.x>=tileBounds) { 
			tile.x = tile.x%tileBounds; 
			if (tile.x < 0) {tile.x+=tileBounds} 
		}
	}
	else { 
        	if (tile.x<0 || tile.x>=tileBounds) {return false;}
	}  
  	return true;
    }
      
    CustomProjection.prototype.getWrapWidth=function(zoom) {
        return this.tileBounds[zoom]*this.tileSize;
    }
    
    /////////////////////////////////////////////////////////////////////////////////////////////////////

    function CoordMapType(tileSize) {
      this.tileSize = tileSize;
    }
 
    CoordMapType.prototype.getTile = function(coord, zoom, ownerDocument) {
      var div = ownerDocument.createElement('DIV');   

      a = coord;
      b = zoom;
      var c=Math.pow(2,b);
      var d=a.x;
      var e=a.y;
      var f="t";
      for(var g=0;g<b;g++){
          c=c/2;
          if(e<c){
              if(d<c){f+="q"}
              else{f+="r";d-=c}
          } else { 
              if(d<c){f+="t";e-=c}
              else{f+="s";d-=c;e-=c}
          }
      }
      val = f+".jpg";
             
      div.innerHTML = coord + val;
      div.style.width = this.tileSize.width + 'px';
      div.style.height = this.tileSize.height + 'px';
      div.style.fontSize = '10';
      div.style.borderStyle = 'solid';
      div.style.borderWidth = '1px';
      div.style.borderColor = '#AAAAAA';
      return div;
    };
    
    ////////////////////////////////////////////////////////////////////////////

    function customGetTileURL(a,b) {
      //converts tile x,y into keyhole string
console.log("USED!!!!");
      var c=Math.pow(2,b);

        var d=a.x;
        var e=a.y;
        var f="t";
        for(var g=0;g<b;g++){
            c=c/2;
            if(e<c){
                if(d<c){f+="q"}
                else{f+="r";d-=c}
            }
            else{
                if(d<c){f+="t";e-=c}
                else{f+="s";d-=c;e-=c}
            }
        }
        subdirs = 3;
        tmp = "";
        if (f.length >= subdirs) { // subdivide into sub-directories
            for (i = 0; i < subdirs; i++) {
                tmp += f.charAt(i) + "/";
            }
        }
        tmp += f;
        return path + "/Result/"+tmp+".jpg"
    }


    function getWindowHeight() {
        if (window.self&&self.innerHeight) {
            return self.innerHeight;
        }
        if (document.documentElement&&document.documentElement.clientHeight) {
            return document.documentElement.clientHeight;
        }
        return 0;
    }

    function resizeMapDiv() {
        //Resize the height of the div containing the map.
        //Do not call any map methods here as the resize is called before the map is created.
    	var d=document.getElementById("map");
        var offsetTop=0;
        for (var elem=d; elem!=null; elem=elem.offsetParent) {
            offsetTop+=elem.offsetTop;
        }
        var height=getWindowHeight()-offsetTop-50;
        if (height>=0) {
            d.style.height=height+"px";
        }
    }

    /////////////////////////////////////////////////////////////////

    var customMapOptions = {
        getTileUrl: function (a,b) {
          // pervent wrap around
          if (a.y < 0 || a.y >= (1 << b)) {
            return null;
          }
          if (a.x < 0 || a.x >= (1 << b)) {
            return null;
          }
        
          var c=Math.pow(2,b);
          var d=a.x;
          var e=a.y;
          var f="t";
          for(var g=0;g<b;g++){
              c=c/2;
              if(e<c){
                  if(d<c){f+="q"}
                  else{f+="r";d-=c}
              } else { 
                  if(d<c){f+="t";e-=c}
                  else{f+="s";d-=c;e-=c}
              }
          }
          subdirs = 3;
          tmp = "";
          if (f.length >= subdirs) { // subdivide into sub-directories
              for (i = 0; i < subdirs; i++) {
                  tmp += f.charAt(i) + "/";
              }
          }
          tmp += f;        
          return path + "/Result/"+tmp+".jpg"
        },
        isPng: false,
        maxZoom: 8,
        minZoom: 0,
        tileSize: new google.maps.Size(256,256),
        radius: 1738000,
        name: "Kidney",
        streetViewControl: false
    };
    var customMapType = new google.maps.ImageMapType(customMapOptions);    
    var copyrightNode;
    var manualMarkersArray = [];
  
    function addMarker(location, image) {
      if (image === undefined) {
        marker = new google.maps.Marker({
          position: location,
          map: map
        });
      } else {
        marker = new google.maps.Marker({
          position: location,
          map: map,
          icon: image
      });
    }
    manualMarkersArray.push(marker);
  }

  // Removes the overlays from the map, but keeps them in the array
  function clearOverlays() {
    if (manualMarkersArray) {
      for (i in manualMarkersArray) {
        manualMarkersArray[i].setMap(null);
      }
    }
  }

  // Shows any overlays currently in the array
  function showOverlays() {
    if (manualMarkersArray) {
      for (i in manualMarkersArray) {
        manualMarkersArray[i].setMap(map);
      }
    }
  }

  // Deletes all markers in the array by removing references to them
  function deleteOverlays() {
    if (manualMarkersArray) {
      for (i in manualMarkersArray) {
        manualMarkersArray[i].setMap(null);
      }
      manualMarkersArray.length = 0;
    }
  }

  function savePoint( lat, lng, zoom, filename ) {
     $.get('/code/php/setKeyView.php?path=' + path + '&lat=' + lat + '&lng=' + lng + '&zoom=' + zoom + '&filename=' + filename);
  }
  function saveScore( num, score ) {
      jQuery.get('/code/php/setScore.php?num='+num+'&score='+score+"&path="+path);
  }
  function createInfoWindowContent( lat, lng) {
        var chicago = new google.maps.LatLng(lat, lng);
        var numTiles = 1 << map.getZoom();
        var projection = map.getProjection(); // new CustomProjection();
        var worldCoordinate = projection.fromLatLngToPoint(chicago);
        var pixelCoordinate = new google.maps.Point(
            worldCoordinate.x * numTiles,
            worldCoordinate.y * numTiles);
        var tileCoordinate = new google.maps.Point(
            Math.floor(pixelCoordinate.x / 256),
            Math.floor(pixelCoordinate.y / 256));
        var tilename = customMapOptions.getTileUrl(tileCoordinate, map.getZoom());
			
        return ['location',
                'LatLng: ' + chicago.lat() + ' , ' + chicago.lng(),
                'World Coordinate: ' + worldCoordinate.x + ' , ' +
                  worldCoordinate.y,
                'Pixel Coordinate: ' + Math.floor(pixelCoordinate.x) + ' , ' +
                  Math.floor(pixelCoordinate.y),
                'Tile Coordinate: ' + tileCoordinate.x + ' , ' +
                  tileCoordinate.y + ' at Zoom Level: ' + map.getZoom() +
				  '<br>tilename: ' + tilename +
				  '<input type="button" onClick="savePoint(\'' + lat + '\',\'' + lng + '\',\'' + map.getZoom() + '\',\'' + tilename + '\');" value="save this point"/>'
               ].join('<br>');
  }

  function scaleOverlay(map) {
      this.map_ = map;
      this.div_ = null;
      this.setMap(map);
  }
  scaleOverlay.prototype = new google.maps.OverlayView();
  scaleOverlay.prototype.onAdd = function() {
      var div = document.createElement('DIV');
      div.className = 'scaleBar';
      div.innerHTML = 'Scale';
      this.div_ = div;
      var overlayProjection = this.getProjection();
      var bounds = map.getBounds();
      var ss = bounds.getSouthWest();
      var position = overlayProjection.fromLatLngToDivPixel(ss);
      div.style.left = position.x + 100 + 'px';
      div.style.top = position.y - 40 + 'px';
      div.style.width = '100px';
      div.style.height = '50px';
      var panes = this.getPanes();
      panes.floatPane.appendChild(div);
  }
  scaleOverlay.prototype.draw = function() {
      var overlayProjection = this.getProjection();
      if (overlayProjection) {
          var txt = 'Scale: ' + this.map_.getZoom();
          var bounds = map.getBounds();
          var ss = bounds.getSouthWest();
          var position = overlayProjection.fromLatLngToDivPixel(ss);
          var div = this.div_;
          if (div) {
              div.innerHTML = txt;
              div.style.left = position.x + 100 + 'px';
              div.style.top = position.y - 40 + 'px';
          }
      }
  }

  function fillListOfKeyViews() {
      var kv = document.getElementById('keyviews');
      if (!kv)
          return;
      values = new Array();
      $.ajax({ url: '/code/php/getKeyViews.php?path=' + path,
          dataType: 'json',
          success: function(data) {
              values = data;
          },
          error: function() {
              $(kv).html("server error");
          },
          async: false
      });
      text = "";
      for (i in values) {
//          if (i > 0) { // don't do the first one , its the header of the table
              value = values[i].filename;
              // remove three levels
              value2 = value.slice(0, -6) + ".jpg";
              text += "<div class=\"image\" style=\"width:64px; height:64px;\"> <p><img onClick=\"map.setZoom(" + values[i].zoom + "); map.setCenter(new google.maps.LatLng(" + values[i].lat + "," + values[i].lng + "));\" alt=\"" + value2 + "\" style=\"width:64px; height:64px;\" src=\"" + value2 + "\" lat=\"" + values[i].lat + "\" lng=\"" + values[i].lng + "\" zoom=\"" + values[i].zoom + "\"/></p></div>";
          }
//      }
      $('#keyviews').html(text);
      return values;
  }
  
  scores = {};
  function load() {
        resizeMapDiv();
        fillListOfKeyViews();
        // customMapType.projection = CustomProjection(14, imageWraps);
        //alert(' loc is: ' + centreLat + ' ' + centreLon);
        var mycenter = new google.maps.LatLng(centreLat, centreLon);
        var myOptions = {
            center: mycenter,
            zoom: initialZoom,
            mapTypeControlOptions: {
               mapTypeIds: ['map']
            },
            backgroundColor: "#FFFFFF",
            zoomControl: true,
            scaleControl: false,
            scaleControlOptions: {
               position: google.maps.ControlPosition.RIGHT_TOP
            },
            streetViewControl: false,
            overviewMapControl: true,
            overviewMapControlOptions: {
               position: google.maps.ControlPosition.BOTTOM_RIGHT
            },
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
            }
        };

        //Now create the custom map. Would normally be G_NORMAL_MAP,G_SATELLITE_MAP,G_HYBRID_MAP
        map = new google.maps.Map(document.getElementById("map"), myOptions );
        map.mapTypes.set('HM', customMapType);
        map.setMapTypeId('HM');
        jQuery.getJSON('/code/php/getScores.php?path=' + path, function(data) {
          scores = data;
          drawRects();
        });
        //map.overlayMapTypes.insertAt(0, new CoordMapType(new google.maps.Size(256, 256)));
        
        //copyrightNode = document.createElement('div');
        //copyrightNode.id = 'copyright-control';
        //copyrightNode.style.fontSize = '11px';
        //copyrightNode.style.fontFamily = 'Arial, sans-serif';
        //copyrightNode.style.margin = '0 2px 2px 0';
        //copyrightNode.style.whiteSpace = 'nowrap';
        //copyrightNode.index = 0;
        //copyrightNode.innerHTML = "<img src=\"TBOLogo.png\"></img>";
        //map.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(copyrightNode);

        google.maps.event.addListener(map, 'click', function(event) {
            addMarker(event.latLng);
            var coordInfoWindow = new google.maps.InfoWindow();
			coordInfoWindow.setContent(createInfoWindowContent(event.latLng.lat(), event.latLng.lng()));
			coordInfoWindow.setPosition( event.latLng );
			coordInfoWindow.open(map);
        });

        // var sO = new scaleOverlay(map);
        google.maps.event.addListener(map, "bounds_changed", function(event) {
            // sO.draw();
            // line drawn on screen is 100px == 200micron
            var mz = customMapOptions.maxZoom;
            var st = document.getElementById('scaleText');
            var zoomDif = (mz+1) - map.getZoom();
            var dif = 10 / zoomDif; // 10x is our max zoom
            st.innerHTML = (100 * 0.755 * Math.pow(2, zoomDif - 1)) + '&micro;m ' + (10 / Math.pow(2, zoomDif-1)).toFixed(1) + "x ";
        });
    }

var infowindow = new google.maps.InfoWindow();
var rectArr=[];
var cols=["white","#ffffb2","#fecc5c", "#fd8d3c", "#f03b20", "#bd0026"]

function drawRects () {
    var NW=new google.maps.LatLng(centreLat, centreLon);
    // how many rectangles do we need?
    // depends on the size of the map
    var overlayProjection = map.getProjection();
    var bounds = map.getBounds();
    var sizeInPixel = 10;
    var sw = bounds.getSouthWest();
    var ne = bounds.getNorthEast();
    var swP = overlayProjection.fromLatLngToPoint(sw);
    var neP = overlayProjection.fromLatLngToPoint(ne);
    var width = Math.floor((neP.x - swP.x) / sizeInPixel);
    var height = Math.floor((swP.y - neP.y) / sizeInPixel);

    //var width = 5; // grid size
    //var height = 3;
    // 100px == 200micron

    // add offset in x
    var offsetRect = function(i, j) {
        var overlayProjection = map.getProjection();
        var bounds = map.getBounds();
        var ss = bounds.getNorthEast();
        var position = overlayProjection.fromLatLngToPoint(ss);

        var newpos = new google.maps.Point( position.x, position.y );
        var newpos2 = new google.maps.Point( position.x, position.y );
        newpos.x  = newpos.x + i*sizeInPixel;
        newpos.y  = newpos.y + j*sizeInPixel;
        newpos2.x = newpos2.x + (i+1)*sizeInPixel;
        newpos2.y = newpos2.y + (j+1)*sizeInPixel;

        var NE = overlayProjection.fromPointToLatLng(newpos);
        var SW = overlayProjection.fromPointToLatLng(newpos2);
    
        return new google.maps.LatLngBounds(NE,SW);
    };

    for (var i = 0; i < height; i++) {
        for (var a = 0; a < width; a++) {
            var rectangle = new google.maps.Rectangle();
            // get the color for this field
            var centry = 'num'+(rectArr.length+1);
            var opa = 0;
            var col = cols[0];
            if (typeof scores[centry] !== 'undefined') {
                col = cols[scores[centry]];
                opa = .5;
            }

            var rectOptions = {
                strokeColor: "#3d3d3d",
                strokeOpacity: 0.2,
                strokeWeight: 2,
                fillColor: col,
                fillOpacity: opa,
                map: map,
                bounds: offsetRect(a,i) // new google.maps.LatLngBounds(SW,NE)
            };
            rectangle.setOptions(rectOptions);
            rectArr.push(rectangle);
            bindWindow(rectangle,rectArr.length);
        }
    }

    // store the click action
    jQuery(window).on('click', '.saveScore', function() {
        console.log('save this score');
        var score = jQuery('#scoreNum').val();
        var num = jQuery(this).attr('num');
        // update this rectangle
        rectArr[num-1].setOptions( { fillColor: cols[score], fillOpacity: 0.5  });
        saveScore(num, score);
    });
}

function bindWindow(rectangle,num){
    google.maps.event.addListener(rectangle, 'click', function(event) {
        infowindow.setContent("<h5>Square " + num + "</h5>" +
                             "<br/><div class=\"form-group\"><label for=\"scoreNum\">Score: </label><input placeholder=\"0 or 1 or 2 or 3 or 4 or 5\" id=\"scoreNum\" class=\"form-control\" type=\"text\"></div><button class=\"saveScore btn\" num=\""+num+"\">Save</button>");
        infowindow.setPosition(event.latLng);
        infowindow.open(map);
        jQuery('#scoreNum').focus().select();
        jQuery('#scoreNum').keyup(function(e) {
            if (e.keyCode == 13) {
                jQuery('.saveScore').trigger('click');
                infowindow.close();
            }
        });
    });
}

function disableSquares() {
    for (var i = 0; i < rectArr.length; i++) {
        rectArr[i].setOptions( { map: null } );
    }
}
function enableSquares() {
    for (var i = 0; i < rectArr.length; i++) {
        rectArr[i].setOptions( { map: map } );
    }
}

    //]]>
    </script>

  <div id="page">

     <span id="keyviews" style="position: relative; float: right;"></span>
     <div id="map"></div>
     <div class="scaleBar" style="position: relative; top: -30px; right: -100px; width: 100px; height: 50px;">
       <div id='scaleLine' style="position: absolute; background-color: #555555; color: #555555; height: 3px; width: 100px;"></div><br />
       <div id='scaleText' style="position: relative; top: -10px; right: -10px;"></div>
     </div>
     <div class="overlayScores" style="position: absolute; top: 20px; left: 30px;">
       <button class="btn\" onClick="disableSquares();">Disable Scores</button>
       <button class="btn\" onClick="enableSquares();">Enable Scores</button>
     </div>

     <!-- <div>
       <input onclick="clearOverlays();" type=button value="Hide Markers"/>
       <input onclick="showOverlays();" type=button value="Show Markers"/>
       <input onclick="deleteOverlays();" type=button value="Delete Markers"/> 
     </div> -->
  </div>


  <div id="bd_footer"></div>

 </body>
</html>

