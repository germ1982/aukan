$("#idperiodo").click(function() {
    render_graph_stock();
});

function render_graph_stock(idarticulo, anio, idordencompra, idorganizacionsocial) {
    idordencompra = idordencompra == null ? "" : idordencompra;
    idorganizacionsocial = idorganizacionsocial == null ? "" : idorganizacionsocial;
    aux = "index.php?r=view_stock_detalle_oc/stock_articulo_grafico&idarticulo=" + idarticulo +
        "&anio=" + anio + "&idordencompra=" + idordencompra + "&organizacion_social=" + idorganizacionsocial;
    $.post(aux, function(data) {
        //console.log(data);
        Highcharts.chart('graph_stock', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Variación anual de stock'
            },
            xAxis: {
                categories: data['categories']
            },
            yAxis: {
                /* min: data['minimo'], */
                title: {
                    text: 'Cantidad'
                }
            },
            credits: {
                enabled: false
            },
            series: data['graphic']
        });
    });
}