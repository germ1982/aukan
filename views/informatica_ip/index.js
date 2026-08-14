// Procesa la petición AJAX al confirmar la acción en una IP individual
function ejecutarCambioIp($chk, url, vaAOcupar, accionTxt) {
    $('#loading').show();

    $.post(url, {_csrf: yii.getCsrfToken()})
        .done(function(res) {
            if (res.success) {
                let gifUrl = accionTxt == 'liberar' ? 'https://media2.giphy.com/media/v1.Y2lkPTc5MGI3NjExM2xpY3VnY3lrZTFmYThjdWw4N3VodDhtdGR2bW5zNHM5Z2p2d3d4cSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/R0H0Y9ulnZXK8/giphy.gif' : null;
                mostrarAlerta(res.message, 'Información', 'blue', function() {
                    $('#loading').hide();
                }, gifUrl);
                $.pjax.reload({container: res.forceReload, timeout: false});
            } else {
                mostrarAlerta('Error: ' + res.message, 'Error', 'red', function() {
                    $chk.prop('checked', !vaAOcupar);
                    $('#loading').hide();
                });
            }
        })
        .fail(function() {
            mostrarAlerta('Error de comunicación con el servidor.', 'Error', 'red', function() {
                $chk.prop('checked', !vaAOcupar);
                $('#loading').hide();
            });
        });
}

// Revierte el check de la fila si el usuario cancela la acción
function cancelarCambioIp($chk, vaAOcupar) {
    $chk.prop('checked', !vaAOcupar);
}

// Maneja el checkbox individual de cada fila (Usada / Libre)
function handleCambioEstadoIp() {
    let $chk = $(this);
    let vaAOcupar = $chk.is(':checked'); 
    let url = $chk.data('url');
    let accionTxt = vaAOcupar ? 'ocupar' : 'liberar';
    let advTxt = vaAOcupar ? '' : '<br><small class="text-danger">Atención: Liberar la IP borrará la MAC, Dispositivo de Red, Observaciones y DNS asociados.</small>';

    let titulo = 'Confirmación';
    let mensaje = '¿Está seguro de que desea ' + accionTxt + ' esta IP?' + advTxt;
    let tipo = vaAOcupar ? 'blue' : 'red';

    mostrarConfirmacion(
        titulo,
        mensaje,
        function() { ejecutarCambioIp($chk, url, vaAOcupar, accionTxt); },
        function() { cancelarCambioIp($chk, vaAOcupar); },
        tipo
    );
}

// Evento para los checkboxes de la grilla
$(document)
    .off('change', '.chk-usada')
    .on('change', '.chk-usada', handleCambioEstadoIp);