    //<![CDATA[
    
    // call this with: 
    // file:///Users/nmschenker/GoogleProstate/WC_D23_10x/results/index.html?zoom=6&center=23,42


    // ?zoom=8&center=-50.64597734071358 ,-104.34814453125
    var centreLat=76.72022329036133; //66.56440944430722 , -61.171875
    var centreLon=-88.76953125;
    var initialZoom=3;

    var str = window.location.search;
    if (str !== "") {
      var arguments = str.split('?')[1].trim();
      if (str.indexOf("&") > -1) {
        var arg1 = arguments.split('&')[0].trim();
        var arg2 = arguments.split('&')[1].trim();
        var var1 = arg1.split('=')[0].trim();
        var val1 = arg1.split('=')[1].trim();
    
        if (var1== 'zoom') {
        	initialZoom = parseInt(val1);
        } else {
      	  centreLat = parseFloat(val1.split(',')[0]);
    	  centreLon = parseFloat(val1.split(',')[1]);
        }
        var var2 = arg2.split('=')[0].trim();
        var val2 = arg2.split('=')[1].trim();
        if (var2== 'zoom') {
     	  initialZoom = parseInt(val2);
        } else {
      	  centreLat = parseFloat(val2.split(',')[0].trim());
    	  centreLon = parseFloat(val2.split(',')[1].trim());
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
        return histoPathLoc + "/Result/"+tmp+".jpg"
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
            if (height < 400)
               height = 400;
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
          return histoPathLoc + "/Result/"+tmp+".jpg"
        },
        isPng: false,
        maxZoom: 9,
        minZoom: 0,
        tileSize: new google.maps.Size(256,256),
        radius: 1738000,
        name: "Prostate",
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
     //$.get('/code/php/setKeyView.php?path=' + keyViewPath + '&lat=' + lat + '&lng=' + lng + '&zoom=' + zoom + '&filename=' + filename);
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
				  '<br>tilename: ' + tilename 
                  //+ '<input type="button" onClick="savePoint(\'' + lat + '\',\'' + lng + '\',\'' + map.getZoom() + '\',\'' + tilename + '\');" value="save this point"/>'
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

  keyViewPath = "";
  function fillListOfKeyViews( path ) {
      keyViewPath = path;
      var kv = document.getElementById('keyviews');
      if (!kv)
          return;
      values = new Array();
      $.ajax({ url: '/code/php/getKeyViews.php',
          data: { "path": path },
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
          value = values[i].filename;
          if (typeof value != 'undefined') {
              // remove three levels
              value2 = value.slice(0, -6) + ".jpg";
              text += "<div class=\"image\" style=\"width:64px; height:64px;\"> <p><img onError='imgError(this);' onClick=\"map.setZoom(" 
                  + values[i].zoom + "); map.setCenter(new google.maps.LatLng(" 
                  + values[i].lat + "," + values[i].lng + "));\" alt=\"" 
                  + value2 + "\" style=\"width:64px; height:64px;\" src=\"" 
                  + value2 + "\" lat=\"" + values[i].lat + "\" lng=\"" 
                  + values[i].lng + "\" zoom=\"" + values[i].zoom + "\"/></p></div>";
          }
      }
      //$('#keyviews').html(text);

      // fill in the second list of keyviews now
      text = "";
      for (i in values) {
          value = values[i].filename;
          if (typeof value != 'undefined') {
              // remove three levels
              value2 = value.slice(0, -6) + ".jpg";
              text += "<div class=\"keyview-shortcut\"> <img onError='imgError(this);' "
                  + "zoom=" + values[i].zoom + " "
                  + "lat=" + values[i].lat + " "
                  + "lng=" + values[i].lng + " "
                  + "notesstart=\"" + values[i].notesstart + "\" "
                  + "notesend=\"" + values[i].notesend + "\" "
                  + "vollocX=" + values[i].vollocX + " "
                  + "vollocY=" + values[i].vollocY + " "
                  + "vollocZ=" + values[i].vollocZ + " "
                  + "alt=\"" 
                  + value2 + "\" style=\"width:64px; height:64px;\" src=\"" 
                  + value2 + "\" lat=\"" + values[i].lat + "\" lng=\"" 
                  + values[i].lng + "\" zoom=\"" + values[i].zoom + "\"/> <div class=\"keyview-info\">"+ "KeyView Shortcut," +"</div> <div class=\"keyview-info\"> Date:</div> </div>";
          }          
      }
      jQuery('#keyviews2').html(text);
      return values;
  }
 
  function imgError(image) {
    image.onerror = "";
    image.src = "/images/noimage.png";
    return true;
  }  

  histoPathLoc = "";
  function loadMap( path ) {
        histoPathLoc = path;
        resizeMapDiv();
        fillListOfKeyViews( path );
        // customMapType.projection = CustomProjection(14, imageWraps);
        //alert(' loc is: ' + centreLat + ' ' + centreLon);
        var mycenter = new google.maps.LatLng(parseFloat(centreLat), parseFloat(centreLon));
        var myOptions = {
            center: mycenter,
            zoom: parseFloat(initialZoom),
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
                   
        var pixelCoords = [
            new google.maps.Point(-577.39,416.58),  
			new google.maps.Point(-548.36, -3183.42),  
			new google.maps.Point(109.22, -5106.09),  
			new google.maps.Point(865.36, -6149.50),  new google.maps.Point(-1380.03, -6886.53),  new google.maps.Point(-2983.89, -8408.65),  new google.maps.Point(-3177.81, -13256.76),  new google.maps.Point(-4388.72, -14690.76),  new google.maps.Point(-5721.30, -17363.97),  new google.maps.Point(-6451.05, -17828.62),  new google.maps.Point(-7629.88, -18141.05),  new google.maps.Point(-9702.57, -19803.41),  new google.maps.Point(-10785.17, -21229.40),  new google.maps.Point(-12896.02, -23553.04),  new google.maps.Point(-13850.31, -24706.65),  new google.maps.Point(-14410.78, -28000.99),  new google.maps.Point(-15774.05, -28553.76),  new google.maps.Point(-17121.29, -29250.73),  new google.maps.Point(-19459.31, -31266.91),  new google.maps.Point(-20333.41, -32484.61),  new google.maps.Point(-20753.50, -36373.67),  new google.maps.Point(-20280.37, -38664.86),  new google.maps.Point(-21294.10, -38709.11),  new google.maps.Point(-22168.20, -39558.29),  new google.maps.Point(-22432.83, -43027.13),  new google.maps.Point(-21959.70, -45614.73),  new google.maps.Point(-21173.34, -47254.73),  new google.maps.Point(-18903.89, -50066.65),  new google.maps.Point(-20225.08, -51713.00),  new google.maps.Point(-20489.71, -54853.38),  new google.maps.Point(-19936.39, -57192.63),  new google.maps.Point(-18631.11, -59004.93),  new google.maps.Point(-16754.60, -59437.53),  new google.maps.Point(-15004.05, -60290.09),  new google.maps.Point(-13745.03, -60602.52),  new google.maps.Point(-13069.28, -63794.80),  new google.maps.Point(-12195.18, -65012.50),  new google.maps.Point(-10743.70, -66278.26),  new google.maps.Point(-8768.21, -66819.10),  new google.maps.Point(-7573.34, -67011.38),  new google.maps.Point(-5831.57, -68170.11),  new google.maps.Point(-4492.35, -68690.84),  new google.maps.Point(-1218.39, -69777.81),  new google.maps.Point(436.02, -69918.60),  new google.maps.Point(1863.45, -70591.54),  new google.maps.Point(4157.56, -71275.11),  new google.maps.Point(5119.87, -71307.16),  new google.maps.Point(7422.08, -70973.88),  new google.maps.Point(8891.82, -71780.12),  new google.maps.Point(11185.33, -72356.92),  new google.maps.Point(13048.52, -72752.36),  new google.maps.Point(14259.43, -72688.27),  new google.maps.Point(15824.51, -72173.28),  new google.maps.Point(16908.12, -71592.53),  new google.maps.Point(18311.49, -71800.83),  new google.maps.Point(20364.42, -71656.63),  new google.maps.Point(21897.48, -71243.48),  new google.maps.Point(23292.83, -70378.27),  new google.maps.Point(24113.87, -69342.62),  new google.maps.Point(24338.41, -68333.21),  new google.maps.Point(24822.04, -67142.88),  new google.maps.Point(25776.33, -68040.13),  new google.maps.Point(26722.60, -68921.37),  new google.maps.Point(27380.18, -69522.20),  new google.maps.Point(29234.60, -70350.65),  new google.maps.Point(30742.22, -70767.23),  new google.maps.Point(32322.89, -70682.98),  new google.maps.Point(33838.54, -70282.42),  new google.maps.Point(35877.89, -68948.50),  new google.maps.Point(36771.41, -69625.16),  new google.maps.Point(37437.01, -70185.94),  new google.maps.Point(39155.47, -70892.14),  new google.maps.Point(40759.32, -70820.04),  new google.maps.Point(42837.84, -70316.40),  new google.maps.Point(43800.15, -69891.80),  new google.maps.Point(44949.20, -69255.97),  new google.maps.Point(45703.02, -68406.78),  new google.maps.Point(46881.84, -68318.66),  new google.maps.Point(48863.86, -67938.48),  new google.maps.Point(50098.82, -67521.89),  new google.maps.Point(52347.50, -66444.10),  new google.maps.Point(53269.71, -65803.21),  new google.maps.Point(55387.39, -63693.47),  new google.maps.Point(56221.39, -61570.51),  new google.maps.Point(57275.52, -60774.04),  new google.maps.Point(58582.66, -59940.88),  new google.maps.Point(59488.84, -59308.00),  new google.maps.Point(60061.32, -59562.15),  new google.maps.Point(61007.59, -59081.48),  new google.maps.Point(61825.55, -57975.94),  new google.maps.Point(62852.02, -57599.42),  new google.maps.Point(64786.30, -57011.98),  new google.maps.Point(65884.94, -55802.29),  new google.maps.Point(66796.33, -53570.75),  new google.maps.Point(67373.71, -52513.27),  new google.maps.Point(68087.42, -51624.03),  new google.maps.Point(69214.25, -50267.81),  new google.maps.Point(69382.66, -49154.26),  new google.maps.Point(69045.84, -48521.38),  new google.maps.Point(68436.38, -48080.77),  new google.maps.Point(70469.77, -48181.61),  new google.maps.Point(71432.08, -47644.86),  new google.maps.Point(72526.07, -46917.88),  new google.maps.Point(73520.46, -45940.52),  new google.maps.Point(74450.70, -44538.56),  new google.maps.Point(75610.73, -42069.51),  new google.maps.Point(76051.79, -40242.97),  new google.maps.Point(77098.35, -37057.92),  new google.maps.Point(77354.97, -36136.63),  new google.maps.Point(77502.49, -31882.37),  new google.maps.Point(78685.53, -30804.75),  new google.maps.Point(79896.45, -29074.34),  new google.maps.Point(80586.10, -27880.68),  new google.maps.Point(81736.40, -25488.67),  new google.maps.Point(82089.25, -23982.57),  new google.maps.Point(82265.67, -23533.95),  new google.maps.Point(82661.90, -20345.15),  new google.maps.Point(82597.74, -19343.76),  new google.maps.Point(81959.31, -17122.91),  new google.maps.Point(81253.62, -16297.76),  new google.maps.Point(81085.21, -15224.26),  new google.maps.Point(80899.86, -13530.89),  new google.maps.Point(80402.66, -12273.14),  new google.maps.Point(79897.45, -11015.39),  new google.maps.Point(78747.20, -9811.52),  new google.maps.Point(78033.48, -9354.89),  new google.maps.Point(76854.65, -9659.31),  new google.maps.Point(74590.63, -10002.68),  new google.maps.Point(73628.31, -10194.95),  new google.maps.Point(72045.20, -10100.37),  new google.maps.Point(71307.43, -9331.29),  new google.maps.Point(70914.48, -8666.37),  new google.maps.Point(70545.60, -8113.60),  new google.maps.Point(70694.32, -7012.97),  new google.maps.Point(71704.74, -6099.70),  new google.maps.Point(72771.30, -5915.44),  new google.maps.Point(73853.91, -5699.14),  new google.maps.Point(74962.55, -5403.24),  new google.maps.Point(76189.50, -4730.30),  new google.maps.Point(77023.51, -4249.63),  new google.maps.Point(77940.84, -3157.03),  new google.maps.Point(78662.58, -1851.21),  new google.maps.Point(79223.92, -673.57),  new google.maps.Point(79576.77, -24.66),  new google.maps.Point(80589.38, 2293.68),  new google.maps.Point(80805.90, 3455.30),  new google.maps.Point(80878.07, 4160.28),  new google.maps.Point(80517.38, 5911.44),  new google.maps.Point(80148.49, 6840.73),  new google.maps.Point(79883.86, 7465.60),  new google.maps.Point(79410.73, 8330.81),  new google.maps.Point(78078.00, 9369.34),  new google.maps.Point(77260.04, 9817.97),  new google.maps.Point(78013.85, 11596.45),  new google.maps.Point(78166.22, 12485.69),  new google.maps.Point(78086.61, 14182.01),  new google.maps.Point(77829.99, 15431.75),  new google.maps.Point(77717.73, 16625.41),  new google.maps.Point(77316.84, 19785.70),  new google.maps.Point(76899.84, 20723.01),  new google.maps.Point(75478.69, 22194.04),  new google.maps.Point(74756.95, 22570.57),  new google.maps.Point(73762.57, 22746.82),  new google.maps.Point(72919.98, 22922.60),  new google.maps.Point(73681.80, 24869.31),  new google.maps.Point(73826.16, 26391.43),  new google.maps.Point(73802.09, 27112.44),  new google.maps.Point(73510.80, 28972.24),  new google.maps.Point(73021.63, 30149.88),  new google.maps.Point(72460.28, 31287.47),  new google.maps.Point(71039.54, 33616.54),  new google.maps.Point(70454.13, 34409.65),  new google.maps.Point(69588.05, 35106.62),  new google.maps.Point(67814.34, 36167.13),  new google.maps.Point(66932.23, 36607.74),  new google.maps.Point(66619.47, 36655.81),  new google.maps.Point(64635.64, 36860.43),  new google.maps.Point(63649.27, 36964.58),  new google.maps.Point(63663.70, 38699.31),  new google.maps.Point(63743.89, 39884.96),  new google.maps.Point(63599.54, 41142.72),  new google.maps.Point(62925.63, 43395.20),  new google.maps.Point(62107.66, 43867.85),  new google.maps.Point(61562.36, 44420.63),  new google.maps.Point(60575.98, 44877.26),  new google.maps.Point(58994.40, 45474.78),  new google.maps.Point(57478.76, 45450.75),  new google.maps.Point(56706.55, 45391.97),  new google.maps.Point(55992.84, 46401.38),  new google.maps.Point(55279.12, 47226.52),  new google.maps.Point(54380.96, 48284.00),  new google.maps.Point(53607.90, 49402.45),  new google.maps.Point(52565.39, 50884.52),  new google.maps.Point(51498.83, 52046.14),  new google.maps.Point(49885.48, 53639.36),  new google.maps.Point(48442.01, 54496.55),  new google.maps.Point(46227.13, 56502.75),  new google.maps.Point(45345.01, 57079.55),  new google.maps.Point(43040.62, 58607.13),  new google.maps.Point(42238.70, 58663.20),  new google.maps.Point(39952.46, 59097.75),  new google.maps.Point(39078.36, 59233.94),  new google.maps.Point(35846.04, 59156.74),  new google.maps.Point(34161.55, 58192.31),  new google.maps.Point(32718.09, 58721.05),  new google.maps.Point(30878.74, 59413.32),  new google.maps.Point(29563.58, 59229.07),  new google.maps.Point(27002.35, 58526.37),  new google.maps.Point(26449.02, 58085.76),  new google.maps.Point(25652.64, 58681.74),  new google.maps.Point(24722.41, 59338.65),  new google.maps.Point(23527.54, 59619.04),  new google.maps.Point(21553.31, 59774.21),  new google.maps.Point(20478.73, 59581.95),  new google.maps.Point(18639.79, 59442.31),  new google.maps.Point(17589.27, 59314.13),  new google.maps.Point(16977.39, 60720.77),  new google.maps.Point(16327.83, 61057.24),  new google.maps.Point(15453.73, 61329.62),  new google.maps.Point(14643.78, 61537.91),  new google.maps.Point(13414.50, 61420.39),  new google.maps.Point(12861.17, 61164.03),  new google.maps.Point(11674.32, 61188.07),  new google.maps.Point(9970.02, 61330.55),  new google.maps.Point(8727.03, 60962.04),  new google.maps.Point(6390.64, 60910.29),  new google.maps.Point(5556.64, 61166.64),  new google.maps.Point(2528.34, 60898.93),  new google.maps.Point(-486.89, 59950.58),  new google.maps.Point(-3457.93, 58852.22),  new google.maps.Point(-5890.54, 56746.68),  new google.maps.Point(-6050.92, 55625.11),  new google.maps.Point(-6572.17, 56241.97),  new google.maps.Point(-7739.89, 56382.95),  new google.maps.Point(-8718.24, 56206.71),  new google.maps.Point(-9768.77, 55798.14),  new google.maps.Point(-12670.90, 53470.46),  new google.maps.Point(-14159.16, 51264.68),  new google.maps.Point(-14824.76, 49269.90),  new google.maps.Point(-15203.81, 48185.95),  new google.maps.Point(-15981.67, 48057.77),  new google.maps.Point(-17056.26, 47617.16),  new google.maps.Point(-17866.20, 46800.02),  new google.maps.Point(-19562.81, 44095.94),  new google.maps.Point(-19731.22, 42541.77),  new google.maps.Point(-19814.19, 39809.34),  new google.maps.Point(-19725.98, 38159.04),  new google.maps.Point(-20607.12, 37650.10),  new google.maps.Point(-21401.03, 36576.61),  new google.maps.Point(-22186.91, 34045.08),  new google.maps.Point(-22423.91, 31180.07),  new google.maps.Point(-22038.98, 29585.85),  new google.maps.Point(-20774.18, 27438.11),  new google.maps.Point(-20774.18, 25114.87),  new google.maps.Point(-18822.12, 22819.91),  new google.maps.Point(-17563.09, 21674.31),  new google.maps.Point(-15852.70, 20853.71),  new google.maps.Point(-15195.12, 20581.33),  new google.maps.Point(-14625.75, 18514.45),  new google.maps.Point(-14197.53, 15835.57),  new google.maps.Point(-13187.10, 14409.58),  new google.maps.Point(-11797.37, 12643.20),  new google.maps.Point(-10233.61, 11609.75),  new google.maps.Point(-9152.00, 10732.83),  new google.maps.Point(-10250.64, 8922.31),  new google.maps.Point(-9184.08, 7872.85),  new google.maps.Point(-9063.79, 7336.10),  new google.maps.Point(-8685.70, 5428.19),  new google.maps.Point(-7194.11, 4562.99),  new google.maps.Point(-6039.34, 4106.35),  new google.maps.Point(-4794.96, 4178.30),  new google.maps.Point(-3688.31, 5011.46),  new google.maps.Point(-2854.30, 5355.94),  new google.maps.Point(-1754.91, 3651.27),  new google.maps.Point(-1105.35, 1287.98)
        ];
        // map the contour and the marker to the map
        intercept = new google.maps.Point(96.5, 110);
        scale     = new google.maps.Point(1/750.0, 1/750.0);
        /* var triangleCoords = [];
        for (var i = 0; i < pixelCoords.length; i++) {
            blub = {};
            blub.x = intercept.x + pixelCoords[i].y * scale.x;
            blub.y = intercept.y -pixelCoords[i].x * scale.y;
            bla = map.getProjection().fromPointToLatLng(blub, map.getZoom());        
            triangleCoords.push(new google.maps.LatLng(bla.lat(),bla.lng()));
        }
        var outlineA = new google.maps.Polygon({
            paths: triangleCoords,
            strokeColor: "#550022",
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: "#222222",
            fillOpacity: 0,
            clickable: false
        });
        
        outlineA.setMap(map); */
        
        
        var image = 'marker.png';
        
        // add marker to the maps
        /*for (var i = 0; i < markerCoords.length; i++) {
            var markerName = "marker " + i;
            blub = {};
            blub.x = intercept.x + markerCoords[i].x * scale.x;
            blub.y = intercept.y - markerCoords[i].y * scale.y;
            bla = map.getProjection().fromPointToLatLng(blub, map.getZoom());
            addMarker(bla, image);
            //var mark = new google.maps.Marker({
            //    position: bla,
            //    map: map,
            //    title: markerName,
            //    icon: image
            //});
         } */ 
         //google.maps.event.addListener(map, 'click', function(event) {
         //   alert("Point.X.Y: " + event.latLng);
         //});

    }

    //]]>
