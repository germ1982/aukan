var anio = null;
var total_entregas_gral = 0;
var cant_entregas_gral = 0;
var alimentos_gral = 0;

$(document).ready(function () {
    cargarComboAnio();
    mostrarIndicadores();
});

function mostrarIndicadores() {
    anio = $("#cmbAnio").val() != null ? $("#cmbAnio").val() : (new Date).getFullYear();    
    //Mando 0 para tipo entrega, >0 para subsidios.
    $.getJSON('consultas/sds_indicadores.php', { 'anio': anio, 'tipo': 0 },
        function (data) {
            var indicadores_html = "";
            $.each(data, function (ind, elem) {
                var detalle = elem['detalle'];
                var total = elem['total'];
                var cantidad = elem['cantidad'];
                var alimentos = elem['alimentos'];
                var color = "primary";
                switch (ind) {
                    case 1:
                    case "1":
                        color = "warning";
                        break;
                    case 2:
                    case "2":
                        color = "tertiary";
                        break;
                    case 3:
                    case "3":
                        color = "quartenary";
                        break;
                    case 4:
                    case "4":
                        color = "success";
                        break;
                }
                indicadores_html = indicadores_html + '<div class="col-md-12 col-lg-12 col-xl-12">' +
                    '<section class="panel panel-featured-left panel-featured-' + color + '">' +
                    '<div class="panel-body">' +
                    '<div class="widget-summary">' + 
                    '<div class="widget-summary-col widget-summary-col-icon">' +
                    '<div class="summary-icon bg-' + color + '">' +
                    '<i class="fas fa-people-carry"></i>' +
                    '</div>' +
                    '</div>' +
                    '<div class="widget-summary-col">' +
                    '<div class="summary" style="padding-bottom:12px;">' +
                    '<h3 class="panel-title">' + detalle + '</h3>' +
                    '<h4 class="panel-subtitle"></h4>' +
                    '<div class="info">' +
                    '<strong class="amount" style="font-weight: 400;">Entregas Totales: ' + Number(total).toLocaleString('de-DE') + '</strong>' +
                    '</div>' +
                    '<div class="h4 text-weight-bold mb-none">Actas Realizadas: ' + Number(cantidad).toLocaleString('de-DE') + '</div>' +
                    '<div class="h4 text-weight-bold mb-none">Módulos de Alimento: ' + Number(alimentos).toLocaleString('de-DE') + '</div>' +
                    '</div>' +
                    '<div class="summary-footer" style="padding-top:12px;">' +
                    '<a class="text-uppercase label label-primary" onclick="mostrarEntregas();">Organismos</a> ' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</section>' +
                    '</div>';
                total_entregas_gral = total;
                cant_entregas_gral = cantidad;
                alimentos_gral = alimentos;
            });
            $("#filter_anio").show();
            $('#ind_tipos').html("");
            $('#ind_entregas').html("");
            $('#ind_general').html(indicadores_html);
        });
}

function cargarComboAnio() {
    var anioActual = (new Date).getFullYear();
    var html = '';
    for (var i = anioActual; i >= 2000; i--) {
        html = html + '<option value="' + i + '">' + i + '</option>';
    }
    $("#cmbAnio").html(html);

    $("#cmbAnio").change(function () {
        mostrarIndicadores();
    });
}

function mostrarEntregas() {
    $.getJSON('consultas/sds_ent_entregas_indic.php', { 'anio': anio },
        function (data) {
            var indicadores_html = "";
            $.each(data, function (ind, elem) {
                var cant_entregas = elem['entregas'];
                var total_entregas = elem['total'];
                var entidad_title = elem['descripcion'];
                var entidad_id = elem['cod_ext'];
                var alimentos = elem['alimentos'];
                var color = "primary";
                switch (entidad_id) {
                    case 1:
                    case "1":
                        color = "warning";
                        break;
                    case 2:
                    case "2":
                        color = "tertiary";
                        break;
                    case 3:
                    case "3":
                        color = "quartenary";
                        break;
                    case 4:
                    case "4":
                        color = "success";
                        break;
                    case 5:
                    case "5":
                        color = "info";
                        break;
                }
                indicadores_html = indicadores_html + '<div class="col-md-6 col-lg-6 col-xl-6">' +
                    '<section class="panel panel-featured-left panel-featured-' + color + '">' +
                    '<div class="panel-body">' +
                    '<div class="widget-summary">' +
                    '<div class="widget-summary-col widget-summary-col-icon">' +
                    '<div class="summary-icon bg-' + color + '">' +
                    '<i class="fas fa-people-carry"></i>' +
                    '</div>' +
                    '</div>' +
                    '<div class="widget-summary-col">' +
                    '<div class="summary" style="padding-bottom:12px;">' +
                    '<h3 class="panel-title">' + entidad_title + '</h3>' +
                    '<h4 class="panel-subtitle"></h4>' +
                    '<div class="info">' +
                    '<strong class="amount" style="font-weight: 400;">Entregas Totales: ' + Number(total_entregas).toLocaleString('de-DE') + '</strong>' +
                    '</div>' +
                    '<div class="h5 text-weight-bold mb-none">Actas Realizadas: ' + Number(cant_entregas).toLocaleString('de-DE') + '</div>' +
                    '<div class="h5 text-weight-bold mb-none">Módulos de Alimento: ' + Number(alimentos).toLocaleString('de-DE') + '</div>' +
                    '</div>' +
                    '<div class="summary-footer" style="padding-top:12px;">' +
                    '<a class="text-uppercase label label-primary" onclick="mostrarTipos(' + entidad_id + ',\'' + entidad_title + '\',' + cant_entregas + ',' + total_entregas + ');">Tipos de Entrega</a> ' +
                    '<a class="text-uppercase label label-success" href="index.php?r=sds_ent_entrega&entidad=' + entidad_id + '">Ver detalles</a>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</section>' +
                    '</div>';
            });
            $("#filter_anio").hide();
            $('#ind_general').html('<div class="col-md-12">' +
                '<section class="panel panel-featured-left">' +
                '<div class="panel-body">' +
                '<div class="widget-summary">' +
                '<div class="widget-summary-col">' +
                '<div class="summary" style="padding-bottom:12px;">' +
                '<h3 class="panel-title">Entregas por Organismo</h3>' +
                '<h4 class="panel-subtitle"></h4>' +
                '<div class="info">' +
                '<strong class="amount" style="font-weight: 400;">Entregas Totales: ' + Number(total_entregas_gral).toLocaleString('de-DE') + '</strong>' +
                '</div>' +
                '<div class="h5 text-weight-bold mb-none">Actas Realizadas: ' + Number(cant_entregas_gral).toLocaleString('de-DE') + '</div>' +
                '<div class="h5 text-weight-bold mb-none">Módulos de Alimento: ' + Number(alimentos_gral).toLocaleString('de-DE') + '</div>' +
                '</div>' +
                '<div class="summary-footer" style="padding-top:12px;">' +
                '<a class="btn btn-primary" onclick="mostrarIndicadores();mostrarIndicadoresAnsTarjetas();">Volver</a> ' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</section>' +
                '</div>');
            $('#ind_ans_tarjetas').html("");
            $('#ind_tipos').html("");
            $('#ind_entregas').html(indicadores_html);
        });
}

function mostrarTipos(entidad_id, entidad, cant_entregas, total) {
    $.getJSON('consultas/sds_ent_entregas_tipo_indic.php', { "entidad_id": entidad_id, "anio": anio },
        function (data) {
            var indicadores_html = "";
            $.each(data, function (ind, elem) {
                var cant = elem['entregas'];
                var total_entregas = elem['total'];
                var tipo_title = elem['descripcion'];
                var tipo_id = elem['cod_tipo'];
                var color = "primary";
                var icon = "fas fa-burn";
                switch (tipo_id) {
                    case 1:
                    case "1":
                        color = "warning";
                        icon = "fas fa-burn"
                        break;
                    case 2:
                    case "2":
                        color = "tertiary";
                        icon = "fas fa-bed"
                        break;
                    case 3:
                    case "3":
                        color = "quartenary";
                        icon = "far fa-clone"
                        break;
                    case 4:
                    case "4":
                        color = "success";
                        icon = "fas fa-glass-whiskey"
                        break;
                    case 5:
                    case "5":
                        color = "rainy";
                        icon = "el-icon-home"
                        break;
                    case 6:
                    case "6":
                        color = "yellow";
                        icon = "fas fa-box"
                        break;
                    case 7:
                    case "7":
                        color = "danger";
                        icon = "fas fa-utensils"
                        break;
                    case 8:
                    case "8":
                        color = "secondary";
                        icon = "fas fa-utensils"
                        break;
                    case 9:
                    case "9":
                        color = "dark";
                        icon = "far fa-credit-card"
                        break;
                    case 10:
                    case "10":
                        color = "pinky";
                        icon = "far fa-clone"
                        break;
                    case 11:
                    case "11":
                        color = "donna";
                        icon = "fas fa-baby"
                        break;
                    case 12:
                    case "12":
                        color = "brown";
                        icon = "fas fa-fire-alt"
                        break;
                    case 13:
                    case "13":
                        color = "primary";
                        icon = "fas fa-shoe-prints"
                        break;
                    case 14:
                    case "14":
                        color = "info";
                        icon = "fas fa-pencil-ruler"
                        break;
                }
                indicadores_html = indicadores_html + '<div class="col-md-6 col-lg-6 col-xl-6">' +
                    '<section class="panel panel-featured-left panel-featured-' + color + '">' +
                    '<div class="panel-body">' +
                    '<div class="widget-summary">' +
                    '<div class="widget-summary-col widget-summary-col-icon">' +
                    '<div class="summary-icon bg-' + color + '">' +
                    '<i class="' + icon + '"></i>' +
                    '</div>' +
                    '</div>' +
                    '<div class="widget-summary-col">' +
                    '<div class="summary" style="padding-bottom:12px;">' +
                    '<h3 class="panel-title">' + tipo_title + '</h3>' +
                    '<h4 class="panel-subtitle"></h4>' +
                    '<div class="info">' +
                    '<strong class="amount" style="font-weight: 400;">Entregas Totales: ' + Number(total_entregas).toLocaleString('de-DE') + '</strong>' +
                    '</div>' +
                    '<div class="h5 text-weight-bold mb-none">Actas Realizadas: ' + Number(cant).toLocaleString('de-DE') + '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</section>' +
                    '</div>';
            });
            $('#ind_ans_tarjetas').html("");
            $('#ind_general').html("");
            $("#filter_anio").hide();
            $('#ind_tipos').html(indicadores_html);
            $('#ind_entregas').html('<div class="col-md-12">' +
                '<section class="panel panel-featured-left">' +
                '<div class="panel-body">' +
                '<div class="widget-summary">' +
                '<div class="widget-summary-col">' +
                '<div class="summary" style="padding-bottom:12px;">' +
                '<h3 class="panel-title">' + entidad + '</h3>' +
                '<h4 class="panel-subtitle"></h4>' +
                '<div class="info">' +
                '<strong class="amount" style="font-weight: 400;">Entregas Totales: ' + Number(total).toLocaleString('de-DE') + '</strong>' +
                '</div>' +
                '<div class="h5 text-weight-bold mb-none">Actas Realizadas: ' + Number(cant_entregas).toLocaleString('de-DE') + '</div>' +
                '</div>' +
                '<div class="summary-footer" style="padding-top:12px;">' +
                '<a class="btn btn-primary" onclick="mostrarEntregas(' + anio + ');">Volver</a> ' +
                '<a class="btn btn-success" href="index.php?r=sds_ent_entrega&entidad=' + entidad_id + '">Ver detalles</a>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</section>' +
                '</div>');
        });
}