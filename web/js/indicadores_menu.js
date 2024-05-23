var anio = null;
var total_entregas_gral = 0;
var cant_entregas_gral = 0;
var alimentos_gral = 0;

$(document).ready(function () {    
    /* mostrarIndicadoresAnsTarjetas();
    setInterval('mostrarIndicadoresAnsTarjetas()', 60000);     */
});

function mostrarIndicadoresAnsTarjetas() {
    $.getJSON('consultas/mds_ans_tarjetas_indic.php', {},
        function (data) {
            var indicadores_html = "";
            $.each(data, function (ind, elem) {
                var fecha = elem['fecha'];
                var term_dni = elem['term_dni'];
                var pendientes = elem['pendientes'];
                var ingresaron = elem['ingresaron'];
                var ultima_hora = elem['ultima_hora'];
                indicadores_html = indicadores_html + "<tr>" +
                    "<td style='text-align:left;width: 25%'>" + fecha + "</td>" +
                    "<td style='text-align:center;width: 25%'>" + (!term_dni.toLowerCase().includes('remanentes') ? "Terminados en" : "") + " <b>" + term_dni + "</b></td>" +
                    "<td style='text-align:center;width: 25%'>" + pendientes + "</td>" +
                    "<td style='text-align:center;width: 25%'>" + ingresaron + (ultima_hora != 0 ? " (" + ultima_hora + ")" : "") + " </td>" +
                    "</tr>";
            });
            indicadores_html = '<div class="col-md-12 col-lg-12 col-xl-12">' +
                '<section class="panel"> ' +
                '<header class="panel-heading"> ' +
                '<div class="panel-actions"> ' +
                '<b><i class="fas fa-circle" style="color: #06d755;"></i> EN VIVO</b>' +
                '</div>' +
                '<h2 class="panel-title">Entrega Alimentar por Día</h2>' +
                '</header>' +
                '<div class="panel-body">' +
                '<div class="table-responsive"> ' +
                '<table class="table table-striped mb-none"> ' +
                '<thead> ' +
                '<tr> ' +
                '<th style="text-align:left;width: 25%"> Día</th> ' +
                '<th style="text-align:center;width: 25%"> Detalle</th> ' +
                '<th style="text-align:center;width: 25%"> Registrados</th> ' +
                '<th style="text-align:center;width: 25%"> Ingresaron (última hora)</th> ' +
                '</tr> ' +
                '</thead > ' +
                '<tbody> ' +
                indicadores_html
                + '</tbody>' +
                '</table>' +
                '</div>' +
                '</div>' +
                '</section></div>';
            $('#ind_ans_tarjetas').html(indicadores_html);
        }
    );
}