<?php
    require_once "clases/xajax_core/xajax.inc.php";
    $xajax = new xajax();
    $xajax->registerFunction('guardar');
    
    function guardar($idCapa,$nombre,$descripcion,$lat,$lon,$padre){
        include 'includes/bd.php';
        if ($padre==0){
            $sql = "insert into CDD_GIS_Capa_Item(idCapa,Nombre,Descripcion,Activo) values($idCapa,'$nombre','$descripcion','True')";
        }else{
            $sql = "insert into CDD_GIS_Capa_Item(idCapa,Nombre,Descripcion,Activo,Padre) values($idCapa,'$nombre','$descripcion','True',$padre)";
        }
        $conexion->query($sql);
        $sql="select max(i.idCapaItem) idCapaItem
                from CDD_GIS_Capa_Item i
                where i.idCapa=$idCapa and 
                i.Nombre='$nombre';";
        foreach ($conexion->query($sql) as $fila) {
            $idCapaItem = $fila['idCapaItem'];
        }
        $sql = "insert into CDD_GIS_Punto values($idCapaItem,'$lat','$lon')";
        $conexion->query($sql);
    }
    
    $xajax->processRequest();
    $xajax->configure('javascript URI', 'js/');
?>
<html lang="en-US">
    <?php $xajax->printJavascript(); ?>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="ThemeStarz">

    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,700' rel='stylesheet' type='text/css'>
    <link href="assets/fonts/font-awesome.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="assets/css/bootstrap-select.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="assets/css/jquery.slider.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/fileinput.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">

    <title>GIS | Ministerio de Ciudadanía</title>

</head>

<body class="page-sub-page page-create-account page-account" id="page-top" onload="load();">
<!-- Wrapper -->
<div class="wrapper">
    <!-- Navigation -->
    <?php include 'includes/header.php'; ?>
    <div id="page-content">
        <!-- Breadcrumb -->
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Agregar Ubicación</li>
            </ol>
        </div>
        <!-- end Breadcrumb -->

        <div class="container">
            <header><h1>Nueva Ubicación</h1></header>
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <h3>Tipo de Ubicación</h3>
                        <div class="radio" id="create-account-user">
                            <label>
                                <input type="radio" id="radio-localidad" name="account-type" required checked="true">Localidad
                            </label>
                        </div>
                        <div class="radio" id="agent-switch" data-agent-state="">
                            <label>
                                <input type="radio" id="radio-barrio" name="account-type" required>Barrio
                            </label>
                        </div>
                        <div id="agency" class="disabled">
                            <div class="form-group">
                                <label for="account-agency">Selecciona la localidad:</label>
                                <select name="account-agency" id="combo-localidad">
                                    <?php include 'includes/combo_localidad.php'; ?>
                                </select>
                            </div><!-- /.form-group -->
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="form-create-account-full-name">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" required>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label for="form-create-account-full-name">Descripción:</label>
                            <input type="text" class="form-control" id="description" required>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label for="form-create-account-full-name">Latitud:</label>
                            <input type="text" value="-38.941080" class="form-control" id="lat" required readonly>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label for="form-create-account-full-name">Longitud:</label>
                            <input type="text" value="-68.185441" class="form-control" id="lon" required readonly>
                        </div><!-- /.form-group -->
                        
                        <div class="form-group clearfix">
                            <button type="submit" class="btn pull-right btn-default" id="account-submit" onclick="guardar();">Crear Ubicación</button>
                        </div>
                    <hr>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div id="submit-map"></div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>
    <!-- end Page Content -->
    <!-- Page Footer -->
    <?php include 'includes/footer.php'; ?>
    <!-- end Page Footer -->
</div>
<script type="text/javascript" src="assets/js/jquery-2.1.0.min.js"></script>
<script type="text/javascript" src="assets/js/jquery-migrate-1.2.1.min.js"></script>
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg&callback=load">
</script>
<script type="text/javascript" src="assets/js/markerwithlabel_packed.js"></script>
<script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/smoothscroll.js"></script>
<script type="text/javascript" src="assets/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="assets/js/icheck.min.js"></script>
<script type="text/javascript" src="assets/js/retina-1.1.0.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.magnific-popup.min.js"></script>
<script type="text/javascript" src="assets/js/fileinput.min.js"></script>
<script type="text/javascript" src="assets/js/custom-map.js"></script>
<script type="text/javascript" src="assets/js/custom.js"></script>
<script>
    var map;
    function load() {
          map = new google.maps.Map(document.getElementById("submit-map"), {
            center: new google.maps.LatLng(-38.941080, -68.185441),
            zoom: 7,
            mapTypeId: 'roadmap'
          });

        map.addListener('dragend', function(e) {
                alert(e.latLng);
                });
	  
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      
      var name = "Arrastre";
      var address = "Para ubicar el punto";
          //var type = markers[i].getAttribute("type");
          var point = new google.maps.LatLng(
              parseFloat(-38.941080),
              parseFloat(-68.185441));
          var html = '<font color="black"><b>'+ name + '</b>'+ address + '<br/></font>';
          //var html = "Hola";
          //var icon = customIcons[type] || {};
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            //icon: icon.icon,
            draggable:true,
            //title:"Drag me!"
          });
		  
		  marker.addListener('dragend', function() {
                      document.getElementById('lat').value = marker.position.lat();
                      document.getElementById('lon').value = marker.position.lng();
		  });
                  
           //marker.setIcon(markers[i].getAttribute("icon"));
		  
          bindInfoWindow(marker, map, infoWindow, html);
      }
        
         function bindInfoWindow(marker, map, infoWindow, html) {
          google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent(html);
            infoWindow.open(map, marker);
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
        
        function guardar(){
            //alert(document.getElementById("combo-localidad").value);
            var idCapa;
            var padre=0;
            if(document.getElementById("radio-localidad").checked){
                idCapa=7;
            }else{
                idCapa=8;
                padre=document.getElementById("combo-localidad").value;
            };
            nombre=document.getElementById("nombre").value;
            descripcion=document.getElementById("description").value;
            lat=document.getElementById("lat").value;
            lon=document.getElementById("lon").value;
            //alert("idCapa: "+idCapa+" nombre: "+nombre+" descripcion: "+descripcion+" lat: "+lat+" lon: "+lon)
            if (idCapa==8 && padre==""){
                alert('Debe seleccionar una localidad');
            }else{
                //alert("idCapa: "+idCapa+" nombre: "+nombre+" descripcion: "+descripcion+" lat: "+lat+" lon: "+lon)
                xajax_guardar(idCapa,nombre,descripcion,lat,lon,padre);
                document.location.href='ubicacion_listado.php';
            }
          }
</script>

<!--[if gt IE 8]>
<script type="text/javascript" src="assets/js/ie.js"></script>
<![endif]-->

</body>
</html>