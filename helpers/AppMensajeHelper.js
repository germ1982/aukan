// Helper global de Alertas y Confirmaciones JS (JavaScript)

window.mostrarAlerta = function(mensaje, titulo = 'Información', type = 'blue', onClose = null, gifUrl = null) {
    let content = '';

    if(gifUrl == null) {
        gifUrl = 'https://i.giphy.com/RhvdJp3UPoDfi.webp';
    }
    if (gifUrl) {
        content += '<div style="text-align:center; margin-bottom:10px;"><img src="' + gifUrl + '" alt="gif" style="width:150px; height:100px;"></div>';
    }
    content += '<div style="text-align:center"><h4>' + mensaje + '</h4></div>';

    $.alert({
        title: titulo,
        content: content,
        type: type,
        animation: 'none',       // Desactiva animación de entrada
        closeAnimation: 'none',  // Desactiva animación de salida
        onDestroy: function() {
            if (typeof onClose === 'function') onClose();
        },
        onOpen: function() {
            this.$title.css({ 'text-align': 'center', 'width': '100%' });
            this.$btnc.css({ 'display': 'flex', 'justify-content': 'center', 'width': '100%', 'float': 'none' });
        }
    });
};

window.mostrarConfirmacion = function( titulo = 'Confirmación', mensaje, funcion_confirmar, funcion_cancelar = null, type = 'blue', gifUrl = null, btnConfirmText = 'Sí, Obio', btnCancelText = 'No, Mejor No') {
    let content = '';
    if(!gifUrl) {
        gifUrl = 'https://media3.giphy.com/media/v1.Y2lkPTc5MGI3NjExeGFzNDlkZGpmanlhbGF4ZWhvOWsyMGo2bnd5aW1qcTViM2ZjaWRnaCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/Lijb8d8IWZVuw/giphy.gif';
    }
    if (gifUrl) {
        content += '<div style="text-align:center; margin-bottom:10px;"><img src="' + gifUrl + '" alt="gif" style="width:150px; height:100px;"></div>';
    }
    content += '<div style="text-align:center">' + mensaje + '</div>';

    $.confirm({
        title: titulo,
        content: content,
        type: type,
        animation: 'none',       // Desactiva animación de entrada
        closeAnimation: 'none',  // Desactiva animación de salida
        buttons: {
            confirmar: {
                text: btnConfirmText,
                btnClass: 'btn-blue',
                action: function() {
                    if (typeof funcion_confirmar === 'function') funcion_confirmar();
                }
            },
            cancelar: {
                text: btnCancelText,
                action: function() {
                    if (typeof funcion_cancelar === 'function') funcion_cancelar();
                }
            }
        },
        onOpen: function() {
            this.$title.css({ 'text-align': 'center', 'width': '100%' });
            this.$btnc.css({ 'display': 'flex', 'justify-content': 'space-between', 'width': '100%', 'float': 'none' });
        }
    });
};