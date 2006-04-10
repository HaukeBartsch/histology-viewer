
 var volparams = [];
 
 volparams["images"] = [""];
 volparams["showControls"] = false;

 // try to update the DICOM header information
 var updateOverlay = false;
 function updateMyOverlay() {
     console.log("updateOverlay now");
     if (papayaContainers[0].viewer.screenVolumes.length > 1) {
         papayaContainers[0].viewer.screenVolumes[1].findDisplayRange(0, { minPercent: -0.1, maxPercent: 1 } ); 
         papayaContainers[0].updateViewerSize();
         updateOverlay = false;
     } else {
         if (updateOverlay) {
            setTimeout(function() { updateOverlay(); }, 1000);
         }
     }
 }

 function updateInterface() {
     if (papayaContainers.length > 0 && 
         papayaContainers[0].viewer.volume.header.fileFormat != null &&
         papayaContainers[0].viewer.volume.header.fileFormat.series != null &&
         papayaContainers[0].viewer.volume.header.fileFormat.series.images[0] != null && 
         papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["0008103E"] != null) {
       if (typeof papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["0008103E"].value != 'undefined') {
         jQuery('#mr-series-description').text(papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["0008103E"].value);

         // now load the second volume
         setTimeout(function() {
           if (volimages.length > 1) {
              papayaContainers[0].viewer.screenVolumes[0].findDisplayRange(0, { minPercent: -0.3, maxPercent: 1 } )

              papaya.Container.addImage(0, volimages[1], { minPercent: -0.1, maxPercent: 1 } );
              updateOverlay = true;
              setTimeout(function() { updateMyOverlay(); }, 1000);
              volimages = [];
           }

         }, 200);
        }
       if (typeof papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["00080020"].value != 'undefined') {
        var date = papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["00080020"].value;
        jQuery('#mr-study-date').text( date );
       }
       if (typeof papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["00100010"].value != 'undefined') {
        var patientid = papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["00100010"].value;
        jQuery('#mr-study-patientid').text( patientid );
       }
       if (typeof papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["00080050"].value != 'undefined') {
        var accession = papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["00080050"].value;
        jQuery('#mr-study-accession-number').text( accession );
       }
       if (typeof papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["00101010"].value != 'undefined') {
        jQuery('#mr-patient-age').text(papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["00101010"].value);
       }
       if (typeof papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["00100040"].value != 'undefined') {
        jQuery('#mr-patient-sex').text(papayaContainers[0].viewer.volume.header.fileFormat.series.images[0].tags["00100040"].value);
       }
     } 
     setTimeout(updateInterface, 1000);
 }
 var volimages = [];
 function loadImages( images ) {
     jQuery('#papaya').hide();
     images = images.map(function(vol) {
         l = vol;
         jQuery.ajax({ url: '/code/php/getFiles.php',
           data: { "path": vol },
           dataType: 'json',
           success: function(data) {
             l = data;
           },
           async: false
         });
         return l;
     });
     volimages = images;

     volparams["images"] = images;
     volparams["showControls"] = false;
     volparams["radiological"] = true;
     // volparams["kioskMode"] = true;
     //setTimeout(function() { jQuery('#papaya').viewer.loadImage(images[0]); }, 500);
     if (images.length > 0) {
       papaya.Container.addImage(0, images[0], { minPercent: 0.1, maxPercent: 0.8} );
       // volparams[images[0]] = { min: 0, max: 2400 };
       // get the series description
       setTimeout(updateInterface, 500);
       //console.log(papayaContainers[0].viewer.volume);
     }
     jQuery('#papaya').fadeIn();
 }
