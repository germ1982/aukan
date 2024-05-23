<?php
    require_once "clases/xajax_core/xajax.inc.php";
    $xajax = new xajax();
    $xajax->registerFunction('guardar');
    $idCapaItem=$_GET['idCapaItem'];
    $idCapa=$_GET['idCapa'];
    $capa="'".$_GET['capa']."'";
    $descripcion="'".$_GET['descripcion']."'";
    $lat="'".$_GET['lat']."'";
    $lon="'".$_GET['lon']."'";
    $icono="'".$_GET['icono']."'";
    $nombre="'".$_GET['nombre']."'";
    function guardar($idCapaItem,$nombre,$descripcion,$lat,$lon,$padre){
        include 'includes/bd.php';
        if ($padre==0){
            $sql = "update CDD_Gis_Capa_Item set nombre='$nombre',descripcion='$descripcion' where idCapaItem=$idCapaItem";
        }else{
            $sql = "update CDD_GIS_Capa_Item set nombre='$nombre',Descripcion='$descripcion',Padre='$padre' where idCapaItem=$idCapaItem";
        }
        $conexion->query($sql);
        $sql = "update CDD_GIS_Punto set latitud='$lat', longitud='$lon' where idCapaItem=$idCapaItem;";
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

<body class="page-sub-page page-create-account page-account" id="page-top" onload="<?php echo 'load('.$idCapaItem.','.$idCapa.','.$capa.','.$descripcion.','.$lat.','.$lon.','.$icono.');'; ?>">
<!-- Wrapper -->
<div class="wrapper">
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
                                <input type="radio" id="radio-localidad" name="account-type" disabled required <?php if($idCapa!=8){echo 'checked';}; ?>>Otro
                            </label>
                        </div>
                        <div class="radio" id="agent-switch" data-agent-state="">
                            <label>
                                <input type="radio" id="radio-barrio" name="account-type" disabled required <?php if($idCapa==8){echo 'checked';}; ?>>Barrio
                            </label>
                        </div>
                        <div>
                            <div class="form-group">
                                <label for="account-agency">Selecciona la localidad:</label>
                                <select name="account-agency" id="combo-localidad" <?php if($idCapa!=8){echo 'disabled';}; ?>>
                                    <?php 
                                        $_SESSION['padre']=$_GET['padre'];
                                        include 'includes/combo_localidad.php'; 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="form-create-account-full-name">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" value="<?php echo $_GET['nombre'];?>" required>
                        </div>
                        <div class="form-group">
                            <label for="form-create-account-full-name">Descripción:</label>
                            <input type="text" class="form-control" id="description" value="<?php echo $_GET['descripcion'];?>" required>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label for="form-create-account-full-name">Latitud:</label>
                            <input type="text" value="<?php echo $_GET['lat'];?>" class="form-control" id="lat" required readonly>
                        </div><!-- /.form-group -->
                        <div class="form-group">
                            <label for="form-create-account-full-name">Longitud:</label>
                            <input type="text" value="<?php echo $_GET['lon'];?>" class="form-control" id="lon" required readonly>
                        </div><!-- /.form-group -->
                        
                        <div class="form-group clearfix">
                            <button type="submit" class="btn pull-right btn-default" id="account-submit" onclick="guardar(<?php echo $_GET['idCapaItem'];?>);">Actualizar Ubicación</button>
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
    function load(idCapaItem,idCapa,capa,descripcion,lat,lon,icono) {
          map = new google.maps.Map(document.getElementById("submit-map"), {
            center: new google.maps.LatLng(lat, lon),
            zoom: 7,
            mapTypeId: 'roadmap'
          });

            map.addListener('dragend', function(e) {
                //alert(e.latLng);
                });
	  
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      
      var name = nombre;
      var address = descripcion;
          //var type = markers[i].getAttribute("type");
          var point = new google.maps.LatLng(
              parseFloat(lat),
              parseFloat(lon));
          var html = '<font color="black"><b>'+ name + '</b><br>'+ address + '<br/></font>';
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
                  
            marker.setIcon(icono);

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
        
        function guardar(idCapaItem){
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
                //alert("idCapaItem: "+idCapaItem+" nombre: "+nombre+" descripcion: "+descripcion+" lat: "+lat+" lon: "+lon+" padre: "+padre)
                xajax_guardar(idCapaItem,nombre,descripcion,lat,lon,padre);
                document.location.href='ubicacion_listado.php';
            }
          }
</script>

<!--[if gt IE 8]>
<script type="text/javascript" src="assets/js/ie.js"></script>
<![endif]-->

</body>
</html>