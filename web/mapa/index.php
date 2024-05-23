<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="ThemeStarz">

    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,700' rel='stylesheet' type='text/css'>
    <link href="assets/fonts/font-awesome.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="assets/css/bootstrap-select.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/jquery.slider.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    
    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/whhg.css">
    <link href="css/main.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="css/whhg.css">

    <!-- para modal-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link rel="shortcut icon" href="favicon.png">
    <title>GIS - Subsecretaría de Desarrollo Social</title>
    <style>

        #myInput {
        background-image: url('iconos/searchicon.png');
        background-position: 5px 12px;
        background-repeat: no-repeat;
        box-sizing: border-box;
        font-size: 14px;
        padding: 14px 1px 14px 30px;
        border: none;
        border-bottom: 1px solid #000;
        background-color: #293137;
        color: white;
        }

        #myInput:focus {outline: 3px solid #ddd;}

        .dropdown {
        position: relative;
        display: inline-block;
        }

        .dropdown-content {
        display: none;
        background-color: #293137;
        min-width: 105px;
        overflow: auto;
        border: 1px solid #293137;
        z-index: 1;
        max-width: 180px;
        }
        .dropdown-content a {
        color: white;
        padding: 10px;
        text-decoration: none;
        display: block;
        }

     
</style>
<style>
        .sidenav {
          height: 100%;
          width: 450px;
          position: fixed;
          z-index: 1;
          top: 40;
          left: 0;
          overflow-x: hidden;
          transition: 0.5s;
        }

        @media screen and (max-height: 450px) {
          .sidenav {padding-top: 15px;}
          .sidenav a {font-size: 18px;}
        }
</style>

<style>
        ul.acorh,
        ul.acorh * {
        margin: 0;
        padding: 0;
        border: 0;
        }
        ul.acorh {
        margin: 10px auto;
        padding: 0;
        list-style: none;
        width: 100%;
        font-size: 14px;
        z-index: 599;
        cursor: default
        }
        ul.acorh li {
        list-style: none;
        position: relative;
        }
        ul.acorh li a {
        display: block;
        padding: 5px 0px 5px 0px;
        background: white;
        color: #5d8eae;
        text-decoration: none;
        box-sizing: border-box;
        max-width: 170px;
        }
        ul.acorh li ul {
        position: absolute;
        display: block;
        max-height: 0;
        margin: 0;
        padding: 0;
        list-style: none;
        overflow: hidden;
        
        width: 100%;
        }
        ul.acorh li li a {
        padding: 5px 5px 5px 10px;
        background:white;
        color: #5d8eae;
        font-size: 12px;
        border: 0;
        box-sizing: border-box;
        left:105px;
        width: 100%;
        }
        ul.acorc li li:last-child a {
        border-bottom: 0;
        }
        ul.acorh li:hover ul {
        position: absolute;
        display: block;
        max-height: 150px;
        overflow-y: scroll;
        z-index: 599;
        cursor: default;
        left:150px;
        top:0px;
        width: 500px;
       
        }
        ul.acorh li a:hover {
        background: white;
        color: #5d8eae;
        width: 500px;
     
        }
     
</style>

    <link href="clases/select2/select2.min.css" rel="stylesheet"/>
    <script src="clases/select2/select2.min.js"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-144079497-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-144079497-1');

    jQuery(document).ready(function($){
    $(document).ready(function() {
        let capaPorUrl = null;
        $('.mi-selector').select2();
        });
    });


    </script>

</head>

<body onload="load()" class="page-homepage map-google horizontal-search-float" id="page-top" data-spy="scroll" data-target=".navigation" data-offset="90">

<div class="wrapper">
    <?php 
        include 'includes/header.php';
        include 'includes/menu_tematicas.php';
        include 'includes/datos_puntos.php';
        include 'includes/datos_capas.php';
        include 'includes/menu_tematicas_items.php';
        include 'includes/item_tematica.php';
    ?>
    <div id="mySidenav" style="width: 0;" class="sidenav">
            
    <!--map-->
    <div id="map" class="map"></div>
    <!--/map-->
    
    <div class="row site" id="rowsite">
        <div class="col-sm-3 col-md-3">
            <div id="myDropdown" class="dropdown-content">
                <input type="text" placeholder="Temática" id="myInput" onkeyup="filterFunction()">
                <?php echo $tematicas ?>
            </div>
        </div>
        
        <!--Content-->
        <div class="col-md-12 side-bar" id="cont">
            <br>
            <div class="row" >
                <!--Search-->
                        <div class="col-md-12">
                        <select class="mi-selector" style="width: 100%;" id="item_seleccionado">
                            <option value="">Seleccione</option>
                        </select>  
                        <input id="buscar"class="btn btn-primary btn-sm" style="width: 100%;margin-top: 10px"type="button" onclick="buscar();" value="Buscar"/><br/>
                        </div>
                        
                <!--/search-->
                <!--Category menu-->
                    <div class="col-md-12">
                        <div id="caja"></div>
                    </div>
                <!--/Category menu-->
            </div>
        </div>
        <!--/Content-->
    </div>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.js"></script>
     <!--Your map settings script-->
    <script type="text/javascript" src="js/map.js"></script>
    <!--jQuery-->
    <script type="text/javascript" src="js/jQueryv2.0.3.js"></script>
    <script type="text/javascript" src="js/pxgradient-1.0.2.jquery.js"></script>



</div>
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
    <!--jQuery-->
    <script type="text/javascript" src="js/pxgradient-1.0.2.jquery.js"></script>
<script>
    function modalReferencias(){
        document.getElementById('tituloModal').innerHTML='Referencias';
        document.getElementById('contenidoModal').innerHTML='<img style="width: 100%;" src="iconos/referencias.png">';
        $('#mostrarmodal').modal('show');
    }
</script>

<script>


    function cargaTodo(){
        const $select = document.querySelector("#item_seleccionado");
        var itembusqueda = <?php echo json_encode((array) $array_puntos)  ?>;
        for(k=0;k<itembusqueda.length;k++){    
                item_act = itembusqueda[k]['descripcion'];
                id_item_actual = itembusqueda[k]['idcapaitem'];
                const option = document.createElement('option');
                option.value = id_item_actual;
                option.text = item_act;
                $select.appendChild(option);          
            }

        var items = <?php echo json_encode((array) $array_puntos)  ?>;
            for(j=0;j<items.length;j++){
                        coordenadas = items[j]['coleccion_coordenadas'] ;
                        if(coordenadas==null){
                            dibuja_punto_item(j,false);
                        }else{
                            dibuja_poligono_item(items[j]['idcapaitem']);
                        }
            }
        var myLatlng = {lat: -38.44572560285308, lng: -69.4568638635059};
        map.setZoom(7);
        map.panTo(myLatlng);
    }

    function limpiarMapa(){
        infowindow.close(map);
        for(i=0;i<lstMarkers.length;i++){
                lstMarkers[i].marker.setMap(null); 
                if (mi_poligono != null) {
                    mi_poligono.setMap(null);
                }
            }
    }

    function cambiar_icono_item(checkbox){
        var value = checkbox.value;
        var array_puntos = <?php echo json_encode((array) $array_puntos)?>;

        //Si está marcada ejecuta la condición verdadera.
        if(checkbox.checked){
            openNav();
            for(j=0;j<array_puntos.length;j++){  
                    if( array_puntos[j]['idcapaitem'] == value && array_puntos[j]['idcapaitem']!=null  ){
                        coordenadas = array_puntos[j]['coleccion_coordenadas'] ;
                        if(coordenadas==null){
                            dibuja_punto_item(array_puntos[j]['idcapaitem'],true);//true para cambiar el centro
                        }else{
                            dibuja_poligono_item(array_puntos[j]['idcapaitem']);
                        }
                    }
            }
        }
    //Si se ha desmarcado se ejecuta lo siguiente
    else{
        if( document.getElementById('check0')){
            document.getElementById('check0').checked = false;
        }
        elimina();
        for(j=0;j<array_puntos.length;j++){  
                if( array_puntos[j]['idcapa'] == value && array_puntos[j]['idcapaitem']!=null){ 
                    var sub_check= 'check'+array_puntos[j]['idcapaitem'];
                    document.getElementById(sub_check).checked = false;
                    infowindow.close(map);
                }
                var check= 'capa'+array_puntos[j]['idcapa'];
                if( document.getElementById(check)){
                    document.getElementById(check).checked = false;
                }
                var checkit= 'check'+array_puntos[j]['idcapaitem'];
                if( document.getElementById(checkit)){
                    document.getElementById(checkit).checked = false;;
                }

        }
    }
}

</script>
<script>
    function cambiar_icono_capas(checkbox){
        var value = checkbox.value;
        var array_puntos = <?php echo json_encode((array) $array_items_tem)?>;
    //Si está marcada ejecuta la condición verdadera.
    if(checkbox.checked){
        for(j=0;j<array_puntos.length;j++){  
                if( array_puntos[j]['idcapa'] == value && array_puntos[j]['idcapaitem']!=null  ){
                    var sub_check= 'check'+array_puntos[j]['idcapaitem'];
                    document.getElementById(sub_check).checked = true;
                    coordenadas = array_puntos[j]['coleccion_coordenadas'] ;
                    if(coordenadas==null){
                        dibuja_punto_item(array_puntos[j]['idcapaitem'],false);
                    }else{
                        dibuja_poligono_item(array_puntos[j]['idcapaitem']);
                    }
                }
        }
    }
    //Si se ha desmarcado se ejecuta lo siguiente
    else{
        if( document.getElementById('check0')){
            document.getElementById('check0').checked = false;
        }
       
        for(j=0;j<array_puntos.length;j++){  
                if( array_puntos[j]['idcapa'] == value && array_puntos[j]['idcapaitem']!=null  ){ 
                    var sub_check= 'check'+array_puntos[j]['idcapaitem'];
                    
                    document.getElementById(sub_check).checked = false;
                    infowindow.close(map);
                    elimina();
                }
        }

    }
    }
</script>

<script>
    function cambiarCentro(lat,lon) {
        var myLatlng = {lat: lat, lng: lon - 0.005};
        map.setZoom(14);
        map.panTo(myLatlng);
    }
    /** Busca parametros get por nombre */
    function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
</script>

<script>
    var map;
    var lstMarkers= [];
    var mi_poligono = null;
    var infowindow = new google.maps.InfoWindow();
    function load() {
        document.getElementById("myDropdown").classList.toggle("show");
        menu_dispositivos();
        setMapHeight();
        map = new google.maps.Map(document.getElementById("map"), {
            center: new google.maps.LatLng(-38.891096, -69.773317),
            zoom: 7,
            mapTypeId: 'roadmap',
            scrollwheel: true,
            zoomControl:true,
            streetViewControl:false,
            mapTypeControl: false,
            mapTypeControlOptions: {
                position: google.maps.ControlPosition.RIGHT_CENTER
            }
        });
        google.maps.event.addDomListener(map, 'click', function() {
          openNav();
        });

    //poligono
    var mi_poligono = new google.maps.Polygon({
                  //  paths: points ,
                    map:map,
                    strokeColor: 'blue',
                    strokeOpacity: 0.8,
                    strokeWeight: 1,
                    fillColor: 'blue',
                    fillOpacity: 0.3
                    });
            mi_poligono.setMap(map);
            google.maps.event.addListener(mi_poligono, 'click', function (event) {
                this.setMap(null); 
            });  
            
	  
    var infoWindow = new google.maps.InfoWindow;

    var check=(document.getElementById("check0").value);
    capaPorUrl = getParameterByName('capa')
    // Verificamos si envia capa por GET
    if (capaPorUrl){
        const objCapa = {
            value: capaPorUrl,
            checked: true
        }
        cambiar_icono_capas_todos(objCapa);
        closeNav();
        $("#capa"+capaPorUrl).prop('checked',true)
    } else {
        openNav();
        todos_disp(check);
        $("input[type=checkbox]").each(function(){
                    $(this).prop("checked",true);
        });
    }   
}//end load
</script>

<script>
    function dibuja_punto_item(item,centrar){
        var item_pol = <?php echo json_encode((array) $array_puntos)  ?>;
        var item_tematicas = <?php echo json_encode((array) $array_item_tematicas)  ?>;
        var tematica_descripcion = '';

        for(u=0;u<item_pol.length;u++){
            if(item_pol[u]['idcapaitem']==item && item_pol[u]['coleccion_coordenadas']==null){
                for(i=0;i<item_tematicas.length;i++){
                    if(item_tematicas[i]['iditem']==item_pol[u]['idcapaitem'] ){
                         tematica_descripcion = tematica_descripcion+item_tematicas[i]['descripcion']+',' ;
                    }
                }
                var latitud = item_pol[u]['latitud'];
                var longitud = item_pol[u]['longitud'];

                var lat = parseFloat(latitud);
                var long = parseFloat(longitud);
                if(centrar==true){
                    cambiarCentro(lat,long);
                }

                var infoWindow = new google.maps.InfoWindow;
                var nombre = item_pol[u]['descripcion'];
                var descripcion = item_pol[u]['detalle'];
                var direccion = item_pol[u]['direccion'];
                var point = new google.maps.LatLng(latitud, longitud);

                var sitio = 'https://mindesarrolloytrabajo.neuquen.gob.ar/';
                
                pattern = /enter/ig;
                descripcion= descripcion.replace(pattern,'<br>');
                
                var html = '<font color="black"><b>'+ nombre +
                            '</b><br>'+ direccion + 
                            '</b><br>'+ descripcion + 
                            '</b><br> Temática: '+ tematica_descripcion + 
                            '<br/><br/></font>' + '<a href="'+sitio+'" target="_blank" style="color:#5d8eae;font-size: 14px;">+info</a>';
                var marker = new google.maps.Marker({
                    map: map,
                    position: point
                });
                if(item_pol[u]['idcapa']<12){
                    icon='iconos/'+item_pol[u]['idcapa']+'-'+item_pol[u]['estado']+'.png';
                }else{
                    icon='iconos/default.png';
                }
                marker.setIcon(icon);
                bindInfoWindow(marker, map, infoWindow, html);
                marker.setMap(map);
                marker.setAnimation(google.maps.Animation.DROP);

                var marker_item=new Object();
                marker_item.marker=marker;
                marker_item.tipo=item_pol[u]['tipo'];
                marker_item.capa= item_pol[u]['idcapa'];
                lstMarkers.push(marker_item);
            }
        }
    }
    </script>


<script>
    function dibuja_poligono_item(item){
        //dibujamos poligonos
        var items = <?php echo json_encode((array) $array_puntos)  ?>;
        for(i=0;i<items.length;i++){
            if(items[i]['idcapaitem']==item && items[i]['coleccion_coordenadas']!=null){
                var coleccion_coordenadas = items[i]['coleccion_coordenadas']; 
                var coordenadas =JSON.parse(coleccion_coordenadas);
                var array_coordenadas = coordenadas.coordinates;
                var type = coordenadas.type;
                var points=[];
                var infoWindow = new google.maps.InfoWindow;
                for(var j=0; j<array_coordenadas.length; j++) {
                    points.push(new google.maps.LatLng(coordenadas.coordinates[j][0],
                    coordenadas.coordinates[j][1]));
                }
                var mi_poligono = new google.maps.Polygon({
                    paths: points ,
                    map:map,
                    strokeColor: 'blue',
                    strokeOpacity: 0.8,
                    strokeWeight: 1,
                    fillColor: 'blue',
                    fillOpacity: 0.3,
                    name: 'poligono'
                    });
                mi_poligono.setMap(map);
                // informacion del poligono
                var info = items[i]['descripcion'] +' </br>'+items[i]['detalle'];

                mi_poligono.addListener('click', function (e) {
                    infowindow.setOptions({
                        content: info,
                        position: e.latLng  ,
                        maxWidth:300,
                    });
                    infowindow.open(map);
                });
                
                var marker_item=new Object();
                marker_item.marker=mi_poligono;
                marker_item.tipo=items[i]['tipo'];
                marker_item.capa= items[i]['idcapaitem'];
                lstMarkers.push(marker_item);
                }
            }
        
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


<script>
    function buscar(){
        for(i=0;i<lstMarkers.length;i++){
            lstMarkers[i].marker.setMap(null); 
        }
        $("input[type=checkbox]").each(function(){
            $(this).prop("checked",false);
        });
        var item=(document.getElementById("item_seleccionado").value); 
        var items = <?php echo json_encode((array) $array_puntos)  ?>;
        for(j=0;j<items.length;j++){
                if( items[j]['idcapaitem'] == item){
                    coordenadas = items[j]['coleccion_coordenadas'] ;
                    if(coordenadas==null){
                        dibuja_punto_item(item,true);
                    }else{
                        dibuja_poligono_item(item);
                    }
                }
        }
 
    }
</script>


<script>
        function menu_tematica(elemento){
            elimina();
            infowindow.close(map);
            const $select = document.querySelector("#item_seleccionado");
            for (let i = $select.options.length; i >= 0; i--) {
                $select.remove(i);
            }
            const option = document.createElement('option');
                option.value = '0';
                option.text = 'Seleccione';
                $select.appendChild(option); 

            for(i=0;i<lstMarkers.length;i++){
                lstMarkers[i].marker.setMap(null);   
            }

            var item_selecionado =$(elemento).data('value');
            
            var items = <?php echo json_encode((array) $array_items_tem)  ?>;
            var puntos = <?php echo json_encode((array) $array_puntos)  ?>;
            var capas = <?php echo json_encode((array) $array_capas)  ?>;

            var menu_nuevo = '';
            var idcapa_ant;
            var categoria = null;
            var item_id= null;
            var id_item_tematica = null;
            var menu_capa=[];
            if(item_selecionado!='todos'){
                //armamos el select de busqueda segun la tematica
                for(k=0;k<items.length;k++){
                        if( items[k]['idtematica'] == item_selecionado){
                            item_act = items[k]['nombre_item'];
                            id_item_actual = items[k]['idcapaitem'];
                            const option = document.createElement('option');
                            option.value = id_item_actual;
                            option.text = item_act;
                            $select.appendChild(option); 
                        }
                }
                
                    //listado
                    menu_nuevo = menu_nuevo +'<div>';
                    menu_nuevo = menu_nuevo + '<ul class="acorh" style="padding: 1px 10px 1px 5px;"><a style="color:#5d8eae;">Categorias:</a></ul>';

                    for(i=0;i<items.length;i++){
                        if( items[i]['idtematica'] == item_selecionado && id_item_tematica != items[i]['idcapa']){
                            if(menu_capa.includes(items[i]['idcapa'])){
                                    //items[i]['nombre_capa'];
                            }else{
                                nombre_capa = items[i]['nombre_capa'];
                                idcapa = items[i]['idcapa'];
                                menu_nuevo=menu_nuevo+'<ul class="acorh"><li><a><input name ="'+nombre_capa+'" id="capa'+idcapa+'" type="checkbox" value="'+idcapa+'" onclick="cambiar_icono_capas(this);" style="transform: scale(1.2);"> '+nombre_capa+' </a><ul>';
                                for(j=0;j<items.length;j++){
                                if(items[j]['idcapa']==idcapa && items[j]['idcapaitem']!= item_id  ){
                                    id_item = items[j]['idcapaitem'];
                                    nombre_item = items[j]['nombre_item'];
                                    lat = items[j]['latitud'] ;
                                    long = items[j]['longitud'] ;
                                    menu_nuevo=menu_nuevo+'<li><a><input id="check'+id_item+'" type="checkbox" value="'+id_item+'" onclick="cambiar_icono_item(this);" style="transform: scale(1.2);">    '+nombre_item +'</a></li>'; 
                                    item_id = items[j]['idcapaitem'];
                                    }
                                }
                                menu_nuevo =menu_nuevo +'</ul></li>';
                            }
                            id_item_tematica = items[i]['idcapa'];  
                            menu_capa.push(items[i]['idcapa']);
                        }
                    }
                
            
        }else{ //entra aca por que selecciono todos los dispositivos
        
            //busqueda  
            for(k=0;k<puntos.length;k++){  
                item_act = puntos[k]['descripcion'];
                id_item_actual = puntos[k]['idcapaitem'];
                const option = document.createElement('option');
                option.value = id_item_actual;
                option.text = item_act;
                $select.appendChild(option); 
            }
            //listado
            var menu_nuevo = '<div>';
            menu_nuevo = menu_nuevo + '<ul class="acorh" style=" padding: 1px 10px 1px 5px;"><a><input id="check0" type="checkbox" value="todos" onchange="todos_disp(this);" style="transform: scale(1.2);"> Marcar todos</a></ul>';
            menu_nuevo = menu_nuevo + '<hr style="border:1px solid #5d8eae;margin-bottom:2px;width:90%;margin: auto;">';
            menu_nuevo = menu_nuevo + '<ul class="acorh"><a>Categorias:</a></ul><ul class="acorh">';
            for(i=0;i<capas.length;i++){
                nombre_capa = capas[i]['nombre_capa'];
                idcapa = capas[i]['idcapa'];
                menu_nuevo=menu_nuevo+'<li><a><input name ="'+nombre_capa+'" id="capa'+idcapa+'" type="checkbox" value="'+idcapa+'" onclick="cambiar_icono_capas_todos(this);" style="transform: scale(1.2);"> '+nombre_capa+' </a><ul>';
                for(j=0;j<puntos.length;j++){
                    if(puntos[j]['idcapa']==idcapa){
                        id_item = puntos[j]['idcapaitem'];
                        nombre_item = puntos[j]['nombre_item'];
                        lat = puntos[j]['latitud'] ;
                        long = puntos[j]['longitud'] ;
                        menu_nuevo=menu_nuevo+'<li><a><input id="check'+id_item+'" type="checkbox" value="'+id_item+'" onclick="cambiar_icono_item(this);" style="transform: scale(1.2);">    '+nombre_item +'</a></li>'; 
                    }
                }
                menu_nuevo =menu_nuevo +'</ul></li>';
            }
        }
        menu_nuevo =menu_nuevo +'</ul><br></div>';
        var textodeldiv = document.getElementById("caja").innerHTML;
        document.getElementById("caja").innerHTML = menu_nuevo;
    }
</script>


<script>
function menu_dispositivos(){
    var items = <?php echo json_encode((array) $array_puntos)  ?>;
    var capas = <?php echo json_encode((array) $array_capas)  ?>;

    var menu_nuevo = '<div>';
    menu_nuevo = menu_nuevo + '<ul class="acorh" style=" padding: 1px 10px 1px 5px;"><a><input id="check0" type="checkbox" value="todos" onchange="todos_disp(this);" style="transform: scale(1.2);" checked> Marcar todos</a></ul>';
    menu_nuevo = menu_nuevo + '<hr style="border:1px solid #5d8eae;margin-bottom:2px;width:90%;margin: auto;">';
    menu_nuevo = menu_nuevo + '<ul class="acorh"><a> Categorias:</a></ul><ul class="acorh">';

    for(i=0;i<capas.length;i++){
        nombre_capa = capas[i]['nombre_capa'];
        idcapa = capas[i]['idcapa'];
        menu_nuevo=menu_nuevo+'<li><a><input name ="'+nombre_capa+'" id="capa'+idcapa+'" type="checkbox" value="'+idcapa+'" onclick="cambiar_icono_capas_todos(this);" style="transform: scale(1.2);"> '+nombre_capa+' </a><ul>';
        for(j=0;j<items.length;j++){
            if(items[j]['idcapa']==idcapa){
                id_item = items[j]['idcapaitem'];
                nombre_item = items[j]['nombre_item'];
                lat = items[j]['latitud'] ;
                long = items[j]['longitud'] ;
                menu_nuevo=menu_nuevo+'<li style="min-width:300px"><a><input id="check'+id_item+'" type="checkbox" value="'+id_item+'" onclick="cambiar_icono_item(this);" style="transform: scale(1.2);">    '+nombre_item +'</a></li>'; 
            }
        }
        menu_nuevo =menu_nuevo +'</ul></li>';
    }
    menu_nuevo =menu_nuevo +'</ul><br></div>';
    var textodeldiv = document.getElementById("caja").innerHTML;
    document.getElementById("caja").innerHTML = menu_nuevo;
}

</script>


<script>
    function cambiar_icono_capas_todos(checkbox){
        var value = checkbox.value;
        var array_puntos = <?php echo json_encode((array) $array_puntos)?>;
    //Si está marcada ejecuta la condición verdadera.
    if(checkbox.checked){
        for(j=0;j<array_puntos.length;j++){  
                if( array_puntos[j]['idcapa'] == value && array_puntos[j]['idcapaitem']!=null  ){
                    var sub_check= 'check'+array_puntos[j]['idcapaitem'];
                    document.getElementById(sub_check).checked = true;
                    coordenadas = array_puntos[j]['coleccion_coordenadas'] ;
                    if(coordenadas==null){
                        dibuja_punto_item(array_puntos[j]['idcapaitem'],false);
                    }else{
                        dibuja_poligono_item(array_puntos[j]['idcapaitem']);
                    }
                }
        }
    }
    //Si se ha desmarcado se ejecuta lo siguiente
    else{
        document.getElementById('check0').checked = false;
     
        for(j=0;j<array_puntos.length;j++){  
                if( array_puntos[j]['idcapa'] == value && array_puntos[j]['idcapaitem']!=null  ){ 
                    var sub_check= 'check'+array_puntos[j]['idcapaitem'];
                    document.getElementById(sub_check).checked = false;
                    infowindow.close(map);
                    elimina();
                    
                }
        }

    }
}
</script>

<script>
    function todos_disp(checkbox){
        
        var array_puntos = <?php echo json_encode((array) $array_puntos)?>;
        var capas = <?php echo json_encode((array) $array_capas)?>;
        const $select = document.querySelector("#item_seleccionado");
        var itembusqueda = <?php echo json_encode((array) $array_puntos)  ?>;
        for(k=0;k<itembusqueda.length;k++){    
                item_act = itembusqueda[k]['descripcion'];
                id_item_actual = itembusqueda[k]['idcapaitem'];
                const option = document.createElement('option');
                option.value = id_item_actual;
                option.text = item_act;
                $select.appendChild(option);          
            }
    //Si está marcada ejecuta la condición verdadera.
    if(checkbox.checked || checkbox=='todos'){
        
        elimina();
        for(u=0;u<capas.length;u++){  
            var check_capa= 'capa'+capas[u]['idcapa'];
            document.getElementById(check_capa).checked = true;
        }
        for(i=0;i<array_puntos.length;i++){  
                var sub_check= 'check'+array_puntos[i]['idcapaitem'];
                document.getElementById(sub_check).checked = true;
        }
    
        for(j=0;j<array_puntos.length;j++){  
                if( array_puntos[j]['idcapaitem']!=null  ){
                    coordenadas = array_puntos[j]['coleccion_coordenadas'] ;
                    if(coordenadas==null){
                        dibuja_punto_item(array_puntos[j]['idcapaitem'],false);
                    }else{
                        dibuja_poligono_item(array_puntos[j]['idcapaitem']);
                    }
                }
        }
        var myLatlng = {lat: -38.44572560285308, lng: -69.4568638635059};
        map.setZoom(7);
        map.panTo(myLatlng);
    }
    //Si se ha desmarcado se ejecuta lo siguiente
    else{
        for(u=0;u<capas.length;u++){  
            var check_capa= 'capa'+capas[u]['idcapa'];
            document.getElementById(check_capa).checked = false;
        }
        for(i=0;i<array_puntos.length;i++){  
                var sub_check= 'check'+array_puntos[i]['idcapaitem'];
                document.getElementById(sub_check).checked = false;
        }
        elimina();
    }
}
</script>
<script>
    function elimina(){
        for(u=0;u<lstMarkers.length;u++){  
                infowindow.close(map);
                lstMarkers[u].marker.setMap(null); 
                if (mi_poligono != null) {
                    mi_poligono.setMap(null);
                }
        }
    }
</script>

<script>
    function filterFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        div = document.getElementById("myDropdown");
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
            } else {
            a[i].style.display = "none";
            }
        }
    }
</script>
<script>
    function openNav() {
        if (!capaPorUrl){
            if(document.getElementById("mySidenav").style.width == "450px"){
                document.getElementById("mySidenav").style.width = "0";
            }else{
                document.getElementById("mySidenav").style.width = "450";
            };
        }
    }

    function closeNav() {
      document.getElementById("mySidenav").style.width = "0";
    }
</script>

</body>
</html>