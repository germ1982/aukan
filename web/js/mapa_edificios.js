var map;
var infoWindow = null;
var latitud, longitud, detalle = null;

$("#mds_org_dispositivo-idcapaitem").change(function() {
    cargarMapa();
});

function cargarMapa() {
    var idcapaitem = $("#mds_org_dispositivo-idcapaitem").val();
    if (idcapaitem != null) {
        $.getJSON("consultas/str_capa_item.php", {
                'idcapaitem': idcapaitem
            },
            function(data) {
                latitud = data['latitud'];
                longitud = data['longitud'];
                detalle = data['descripcion'];
                setMapProperties();
            }
        );
    } else {
        setMapProperties();
    }
}

function setMapProperties() {
    if (latitud == null) {
        latitud = -38.95167840000001;
    }
    if (longitud == null) {
        longitud = -68.05918880000002;
    }
    if (detalle == null) {
        detalle = "Prueba";
    }
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: new google.maps.LatLng(latitud, longitud),
        mapTypeId: 'roadmap'
    });
    if (infoWindow == null) {
        infoWindow = new google.maps.InfoWindow;
    }
    var latLng = new google.maps.LatLng(latitud, longitud);
    var marker = new google.maps.Marker({
        position: latLng,
        map: map,
        title: detalle
    });

    html = "<div>" + detalle + "</div>";

    bindInfoWindow(marker, map, infoWindow, html);

    var marker_item = new Object();
    marker_item.marker = marker;
}

function bindInfoWindow(marker, map, infoWindow, html) {
    google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
        /*if (marker.getAnimation() !== null) {
         marker.setAnimation(null);
         } else {
         marker.setAnimation(google.maps.Animation.BOUNCE);
         }*/
    });
}

function bindInfoWindow(marker, map, infoWindow, html) {
    google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
        /*if (marker.getAnimation() !== null) {
         marker.setAnimation(null);
         } else {
         marker.setAnimation(google.maps.Animation.BOUNCE);
         }*/
    });
}