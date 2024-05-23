<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="ThemeStarz">

    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,700' rel='stylesheet' type='text/css'>
    <link href="assets/fonts/font-awesome.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="assets/css/bootstrap-select.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/jquery.slider.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
	<link rel="shortcut icon" href="favicon.png">
<!-- para modal-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <title>GIS - Ministerio de Ciudadanía</title>
</head>

<body onload="load()" class="page-homepage map-google horizontal-search-float" id="page-top" data-spy="scroll" data-target=".navigation" data-offset="90">
    <!-- inicio modal-->
    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 id="tituloModal">Resumen</h3>
                </div>
                <div class="modal-body">
                    <p id="contenidoModal" style="line-height: 32px;"></p>
                </div>
                <div class="modal-footer">
                    <a href="#" data-dismiss="modal" class="btn btn-danger">Cerrar</a>
                </div>
            </div>
        </div>
    </div>
    <!-- fin modal-->
<div class="wrapper">
    <?php //include 'includes/header.php'; ?>
    <div id="map"></div>
</div>
<?php //include 'includes/menu_capas_juvenil.php'; ?>
<script type="text/javascript" src="assets/js/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg"></script>
<script type="text/javascript" src="assets/js/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/smoothscroll.js"></script>
<script type="text/javascript" src="assets/js/markerwithlabel_packed.js"></script>
<script type="text/javascript" src="assets/js/infobox.js"></script>
<script type="text/javascript" src="assets/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.placeholder.js"></script>
<script type="text/javascript" src="assets/js/icheck.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.vanillabox-0.1.5.min.js"></script>
<script type="text/javascript" src="assets/js/retina-1.1.0.min.js"></script>
<script type="text/javascript" src="assets/js/jshashtable-2.1_src.js"></script>
<script type="text/javascript" src="assets/js/jquery.numberformatter-1.2.3.js"></script>
<script type="text/javascript" src="assets/js/tmpl.js"></script>
<script type="text/javascript" src="assets/js/jquery.dependClass-0.1.js"></script>
<script type="text/javascript" src="assets/js/draggable-0.1.js"></script>
<script type="text/javascript" src="assets/js/jquery.slider.js"></script>
<script type="text/javascript" src="assets/js/markerclusterer_packed.js"></script>
<script type="text/javascript" src="assets/js/custom-map.js"></script>
<script type="text/javascript" src="assets/js/custom.js"></script>

<script>
    function modalReferencias(){
        document.getElementById('tituloModal').innerHTML='Referencias';
        document.getElementById('contenidoModal').innerHTML='<img style="width: 100%;" src="iconos/referencias.png">';
        $('#mostrarmodal').modal('show');
    }
</script>

<script>
    function cambiar_icono(imagen, check, icono,tipo){
        extension=icono.slice(-4);
        icono_sin_ext=icono.replace(extension,'');
        if (document.getElementById(check).checked){
            document.getElementById(imagen).src = icono_sin_ext+'-off'+extension;
            document.getElementById(check).checked=false;
            for(i=0;i<lstMarkers.length;i++){
                if (lstMarkers[i].tipo==tipo){
                   lstMarkers[i].marker.setMap(null); 
                }
            }
        }else{
            document.getElementById(imagen).src = icono_sin_ext+'-on'+extension;
            document.getElementById(check).checked=true;
            for(i=0;i<lstMarkers.length;i++){
                if (lstMarkers[i].tipo==tipo){
                   lstMarkers[i].marker.setMap(map);
                   lstMarkers[i].marker.setAnimation(google.maps.Animation.DROP);
                }
            }
        };
    }
</script>

<script>
    function cambiarCentro(lat,lon) {
        var myLatlng = {lat: lat, lng: lon};
        map.setZoom(14);
        map.panTo(myLatlng);
        RecorrerForm();
    }
</script>

<script>
    var map;
    var lstMarkers= [];
    function load() {
          setMapHeight();
          var mapStylesNow = [
                {
                    "featureType": "all",
                    "elementType": "all",
                    "stylers": [
                        {
                            "hue": "#e7ecf0"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "administrative.land_parcel",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "landscape.man_made",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "hue": "#2500ff"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#aab9db"
                        }
                    ]
                },
                {
                    "featureType": "poi.attraction",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "poi.business",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "poi.government",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "poi.medical",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#9fdfa6"
                        }
                    ]
                },
                {
                    "featureType": "poi.place_of_worship",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "poi.school",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "poi.sports_complex",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "all",
                    "stylers": [
                        {
                            "saturation": -70
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#cfcfcf"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#9d9d9d"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "simplified"
                        },
                        {
                            "saturation": -60
                        },
                        {
                            "color": "#91bbf9"
                        }
                    ]
                }
            ];
          map = new google.maps.Map(document.getElementById("map"), {
            center: new google.maps.LatLng(-38.955365, -68.078496),
            zoom: 14,
            mapTypeId: 'roadmap',
            styles: mapStylesNow,
            zoomControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM
            },
            streetViewControlOptions: {
                position: google.maps.ControlPosition.RIGHT_BOTTOM
            },
            mapTypeControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            }
          });
	  
      var infoWindow = new google.maps.InfoWindow;
      
      downloadUrl("cargar_puntos_comision.php", function(data) {
        var xml = data.responseXML;
        //alert (xml);
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var nombre = markers[i].getAttribute("titulo");
          var descripcion = markers[i].getAttribute("detalle");
          var tipo = markers[i].getAttribute("padre");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
              
          pattern = /enter/ig;
          descripcion= descripcion.replace(pattern,'<br>');
          
          var html = '<font color="black"><b>'+ nombre + '</b><br>'+ descripcion + '<br/></font>';
          var marker = new google.maps.Marker({
              map: map,
              animation: google.maps.Animation.DROP,
              position: point
          });
          marker.setIcon(markers[i].getAttribute("icon"));
          bindInfoWindow(marker, map, infoWindow, html);
          marker.setMap(map);
          var marker_item=new Object();
          marker_item.marker=marker;
          marker_item.tipo=tipo;
          marker_item.capa=markers[i].getAttribute("tipo");
          lstMarkers.push(marker_item);
        }
       });
       downloadUrl("cargar_areas.php", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        var id_ant=0;
        var defaultColor = '#ff0000';
        
        var polygon = new google.maps.Polygon({
                 path: new google.maps.MVCArray()
                 , map: map
                 , strokeColor: defaultColor
                 , strokeWeight: 3
                 , strokeOpacity: 0.5
                 , fillColor: defaultColor
                 , fillOpacity: 0.3
                 , clickable: false
             });
        
        for (var i = 0; i < markers.length; i++) {
          id_new=markers[i].getAttribute("id");
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          if (id_ant!==id_new){
              polygon = new google.maps.Polygon({
                   path: new google.maps.MVCArray()
                   , map: map
                   , strokeColor: markers[i].getAttribute("icon")
                   , strokeWeight: 3
                   , strokeOpacity: 0.5
                   , fillColor: markers[i].getAttribute("icon")
                   , fillOpacity: 0.3
                   , clickable: true
               });

               polygon.currentColor = markers[i].getAttribute("icon");
          }
          polygon.getPath().push(point);
          id_ant=id_new;
          if (i==(markers.length-1) || id_ant!==markers[i+1].getAttribute("id")){
              //alert('Id Distinto');
              var html = markers[i].getAttribute("name");
              polygonWindow(polygon, map, infoWindow, html);}
              
          }
        });
      }
        
        function bindInfoWindow(marker, map, infoWindow, html) {
          google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
            if (marker.getAnimation() !== null) {
                marker.setAnimation(null);
              } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
              }
          });
        }
        
        function polygonWindow(polygon, map, infoWindow, html) {  
          //var myLatlngPrueba = new google.maps.LatLng(-38.956311,-68.067277);
          google.maps.event.addListener(polygon, 'click', function(event) {
            var myMap = new Map();
            var myMapOut = new Map();
            var adentro=0;
            var afuera=0;
            for(i=0;i<lstMarkers.length;i++){
                clave='<br><img src="'+lstMarkers[i].marker.getIcon()+'">  '+lstMarkers[i].capa;
                if (myMap.get(clave)==null){
                    adentro=0;
                    afuera=0;
                }else{
                    adentro=myMap.get(clave);
                    afuera=myMapOut.get(clave);
                }
                myLatLngMarker=lstMarkers[i].marker.getPosition();
                if (google.maps.geometry.poly.containsLocation(myLatLngMarker, polygon)){
                    adentro=adentro+1;
                }else{
                    afuera=afuera+1;
                }
                myMap.set(clave,adentro);
                myMapOut.set(clave,afuera);
            }
            var contenido = '<b>Lugares</b>';
            for (var [key, value] of myMap) {
                //alert(key + " = " + value);
                adentro=value;
                afuera=myMapOut.get(key);
                valor=100*adentro/(adentro+afuera);
                contenido=contenido+key + ': <b>' + valor.toFixed(1)+ '%</b>';
                document.getElementById('contenidoModal').innerHTML=contenido;
                document.getElementById('tituloModal').innerHTML='Resumen - '+html;
            };
            $('#mostrarmodal').modal('show');
          });
        }

        function downloadUrl(url, callback) {
          var request = window.ActiveXObject ?
              new ActiveXObject('Microsoft.XMLHTTP') :
              new XMLHttpRequest;

          request.onreadystatechange = function() {
            if (request.readyState === 4) {
              request.onreadystatechange = doNothing;
              callback(request, request.status);
            }
          };

          request.open('GET', url, true);
          request.send(null);
        }

        function doNothing() {}
</script>
</body>
</html>