$(document).ready(function() {
    $("#tipo_documento").change(function() {
        var idtipo = $(this).val();
        // Leemos el idempleado directamente desde un atributo data del elemento
        var idempleado = $(this).data("idempleado") || 0;

        $.post("index.php?r=empleado/get_documentos&idempleado=" + idempleado + "&idtipo=" + idtipo, function(data) {
            $("#tabla_docs").html(data);
        });
    });

    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
        location.reload();
    });

    var $genReport = $('#generate-report');
    var $selectEdificio = $('#select-edificio');

    if ($genReport.length && $selectEdificio.length) {
        var href = $genReport.attr('href') + '&edificio=' + $selectEdificio.val();
        $genReport.attr('href', href);

        $selectEdificio.change(function() {
            var newHref = $genReport.attr('href') + '&edificio=' + $(this).val();
            $genReport.attr('href', newHref);
        });
    }
});