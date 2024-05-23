$(document).ready(function () {

    let tipo = $('#sds_gis_capa_item-tipo').val();
    if(tipo == 1 ){
        loadBasicMap();
    }
    else{
        ocultarInputsLatYLong();
        let coordinadas_hf = $("#sds_gis_capa_item-coleccion_coordenadas").val();
        //console.log(coordinadas_hf);
        let coordinadas = JSON.parse(coordinadas_hf);
        loadDrawingMap(coordinadas);
    }

    $('#txtLatitud').change(function () {
        loadBasicMap();

    });
    $('#txtLongitud').change(function () {
        loadBasicMap();

    });
    $('#sds_gis_capa_item-tipo').change(function () {
        let tipo = $(this).val();
        if(tipo == 2){
            ocultarInputsLatYLong();
            loadDrawingMap();

        }
        else{
            loadBasicMap();
            mostrarInputsLatYLong();
        }
    });

    function ocultarInputsLatYLong(){
        $("#divLatitud").hide();
        $("#divLongitud").hide();
    }
    function mostrarInputsLatYLong(){
        $("#divLatitud").show();
        $("#divLongitud").show();
    }
    function loadBasicMap(){
        const latLongDefault = {
            lat: (document.getElementById("txtLatitud").value) ? parseFloat( document.getElementById("txtLatitud").value) : -38.951678,
            lng : (document.getElementById("txtLongitud").value) ? parseFloat(document.getElementById("txtLongitud").value) :  -68.059188
        }
        //document.getElementById("txtLatitud").value = latLongDefault.lat;
        //document.getElementById("txtLongitud").value = latLongDefault.lng;

        /*const center = { lat: 50.064192, lng: -130.605469 };
        const defaultBounds = {
            north: center.lat + 0.1,
            south: center.lat - 0.1,
            east: center.lng + 0.1,
            west: center.lng - 0.1,
        };*/
        /*const options = {
            bounds: defaultBounds,
            componentRestrictions: { country: "us" },
            fields: ["address_components", "geometry", "icon", "name"],
            strictBounds: false,
            types: ["establishment"],
        };
        const input = document.getElementById("txtDireccion");
        const autocomplete = new google.maps.places.Autocomplete(input, options)*/
        const map = new google.maps.Map(document.getElementById("map"), {
            center: latLongDefault,
            zoom: 15,
            gestureHandling: "greedy",
        });
        const marker = new google.maps.Marker({
            map:map,
            draggable:true,
            position: latLongDefault
        });
        marker.setPosition(latLongDefault);
        const geocoder = new google.maps.Geocoder();
        google.maps.event.addListener(marker, 'dragend', function(event) {
            document.getElementById("txtLatitud").value = event.latLng.lat();
            document.getElementById("txtLongitud").value = event.latLng.lng();
            marker.setPosition(event.latLng);
            geocoder.geocode({
                'latLng': (event.latLng) ? event.latLng : latLongDefault
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        document.getElementById('txtDireccion').value = results[0].formatted_address;
                    }
                }
            });
        });


    }
    function loadDrawingMap(coordinadas = null){
        document.getElementById('txtDireccion').value = null;
        //document.getElementById("txtLongitud").value = 'null';
        const map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: -38.9584793463184, lng: -68.22248732448571 },
            zoom: 15,
            gestureHandling: "greedy",
        });
        if(coordinadas){
            let points=[];
            for(var i=0; i<coordinadas.coordinates.length; i++) {
                points.push(new google.maps.LatLng(coordinadas.coordinates[i][0],
                    coordinadas.coordinates[i][1]));
            }
            // Construct the polygon.
            const polygon = new google.maps.Polygon({
                paths: points,
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#FF0000",
                fillOpacity: 0.35,
            });
            polygon.setMap(map);
        }

        const drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode:google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [
                    //google.maps.drawing.OverlayType.MARKER,
                    //google.maps.drawing.OverlayType.CIRCLE,
                    google.maps.drawing.OverlayType.POLYGON,
                    //google.maps.drawing.OverlayType.POLYLINE,
                    //google.maps.drawing.OverlayType.RECTANGLE,
                ],
            },
            markerOptions: {
                icon: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
            },
            circleOptions: {
                fillColor: "#ffff00",
                fillOpacity: 1,
                strokeWeight: 5,
                clickable: false,
                editable: true,
                zIndex: 1,
            },
        });
        drawingManager.setMap(map);
        google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
            var path = polygon.getPath();
            console.log(path);

            let geoJson = {
                "type":"Polygon",
                "coordinates":[]

            };
            let coordinates = [];
            for (var i = 0 ; i < path.length ; i++) {
                coordinates.push([
                     path.getAt(i).lat(),
                     path.getAt(i).lng()
                ]);
            }
            geoJson.coordinates = coordinates;
            $("#sds_gis_capa_item-coleccion_coordenadas").val(JSON.stringify(geoJson));
        });
    }


});

