var anio = null;

$(document).ready(function () {
    cargarComboAnio();
    mostrarIndicadores();
});

function mostrarIndicadores() {
    anio = $("#cmbAnio").val() != null ? $("#cmbAnio").val() : (new Date).getFullYear();
    var idusuario = $("#idusuario").val();
    //Mando 0 para tipo entrega, >0 para subsidios.
    $.getJSON('consultas/sds_indicadores.php', { 'anio': anio, 'tipo': 1 },
        function (data) {
            var indicadores_html = "";
            $.each(data, function (ind, elem) {
                var detalle = elem['detalle'];
                var total = elem['total'];
                var cantidad = elem['cantidad'];
                var color = "primary";
                var id_ind = "";
                switch (ind) {
                    case 0:
                    case "0":
                        color = "warning";
                        if ($("#permiso_des").val() == 1)
                            id_ind = "ind_desempleo";
                        break;
                    case 1:
                    case "1":
                        color = "tertiary";
                        if ($("#permiso_fam").val() == 1)
                            id_ind = "ind_familia";
                        break;
                    case 2:
                    case "2":
                        color = "quartenary";
                        if ($("#permiso_sst").val() == 1)
                            id_ind = "ind_sst";
                        break;
                }
                if (id_ind != "") {
                    indicadores_html = indicadores_html + '<div id="' + id_ind + '" class="col-md-6 col-lg-6 col-xl-6">' +
                        '<section class="panel panel-featured-left panel-featured-' + color + '">' +
                        '<div class="panel-body">' +
                        '<div class="widget-summary">' +
                        '<div class="widget-summary-col widget-summary-col-icon">' +
                        '<div class="summary-icon bg-' + color + '">' +
                        '<i class="fas fa-users"></i>' +
                        '</div>' +
                        '</div>' +
                        '<div class="widget-summary-col">' +
                        '<div class="summary" style="padding-bottom:12px;">' +
                        '<h3 class="panel-title">' + detalle + '</h3>' +
                        '<h4 class="panel-subtitle"></h4>' +
                        '<div class="info">' +
                        '<strong class="amount" style="font-weight: 400;">Monto: $' + Number(total).toLocaleString('de-DE') + '</strong>' +
                        '</div>' +
                        '<div class="h5 text-weight-bold mb-none">Cantidad: ' + Number(cantidad).toLocaleString('de-DE') + '</div>' +
                        '<div class="h5 text-weight-bold mb-none"> &nbsp;' +
                        '</div>' +
                        '<div class="summary-footer" style="padding-top:12px;">' +
                        '<a class="text-uppercase label label-primary" onclick="mostrarSubsidioMeses(' + ind + ',' + Number(total) + ',' + Number(cantidad) + ');">Por mes</a>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</section>' +
                        '</div>';
                }
            });
            $("#filter_anio").show();
            $('#ind_tipos').html("");
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

function mostrarSubsidioMeses(indice, monto_total, cantidad_total) {
    //El indice sería 0: desempleo; 1: familia; 2: SST
    $.getJSON('consultas/mds_por_subsidios_indic.php', { 'anio': anio, 'indice_sub': indice },
        function (data) {
            var indicadores_html = "";
            $.each(data, function (ind, elem) {
                var mes = elem['mes'];
                var total = elem['total'];
                var cantidad = elem['cantidad'];
                var color = "primary";
                var titulo = "";
                switch (mes) {
                    case 1:
                    case "1":
                        titulo = "Enero";
                        color = "warning";
                        icon = "far fa-calendar"
                        break;
                    case 2:
                    case "2":
                        titulo = "Febrero";
                        color = "tertiary";
                        icon = "far fa-calendar"
                        break;
                    case 3:
                    case "3":
                        titulo = "Marzo";
                        color = "quartenary";
                        icon = "far fa-calendar"
                        break;
                    case 4:
                    case "4":
                        titulo = "Abril";
                        color = "success";
                        icon = "far fa-calendar"
                        break;
                    case 5:
                    case "5":
                        titulo = "Mayo";
                        color = "rainy";
                        icon = "far fa-calendar"
                        break;
                    case 6:
                    case "6":
                        titulo = "Junio";
                        color = "yellow";
                        icon = "far fa-calendar"
                        break;
                    case 7:
                    case "7":
                        titulo = "Julio";
                        color = "danger";
                        icon = "far fa-calendar"
                        break;
                    case 8:
                    case "8":
                        titulo = "Agosto";
                        color = "secondary";
                        icon = "far fa-calendar"
                        break;
                    case 9:
                    case "9":
                        titulo = "Septiembre";
                        color = "info";
                        icon = "far fa-calendar"
                        break;
                    case 10:
                    case "10":
                        titulo = "Octubre";
                        color = "pinky";
                        icon = "far fa-calendar"
                        break;
                    case 11:
                    case "11":
                        titulo = "Noviembre";
                        color = "donna";
                        icon = "far fa-calendar"
                        break;
                    case 12:
                    case "12":
                        titulo = "Diciembre";
                        color = "brown";
                        icon = "far fa-calendar"
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
                    '<h3 class="panel-title">' + titulo + '</h3>' +
                    '<h4 class="panel-subtitle"></h4>' +
                    '<div class="info">' +
                    '<strong class="amount" style="font-weight: 400;">Monto: $ ' + Number(total).toLocaleString('de-DE') + '</strong>' +
                    '</div>' +
                    '<div class="h5 text-weight-bold mb-none">Cantidad: ' + Number(cantidad).toLocaleString('de-DE') + '</div>' +
                    '</div>' +
                    '<div class="summary-footer" style="padding-top:12px;">' +
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
                '<h3 class="panel-title">Subsidios por mes</h3>' +
                '<h4 class="panel-subtitle"></h4>' +
                '<div class="info">' +
                '<strong class="amount" style="font-weight: 400;">Monto Total: ' + Number(monto_total).toLocaleString('de-DE') + '</strong>' +
                '</div>' +
                '<div class="h5 text-weight-bold mb-none">Cantidad Total: ' + Number(cantidad_total).toLocaleString('de-DE') + '</div>' +
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
            $('#ind_tipos').html(indicadores_html);
        });
}