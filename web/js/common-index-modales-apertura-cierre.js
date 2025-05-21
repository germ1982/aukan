// Cuando se cierra el modal, recarga la página para actualizar la grilla
$('#ajaxCrudModal').on('hidden.bs.modal', function() {
    location.reload();
});

// Cuando se muestra el modal
$('#ajaxCrudModal').on('shown.bs.modal', function() {
    var modal = $(this); // referencia al modal
    var content = modal.find('.modal-content'); // obtiene el contenido principal del modal

    // Permite que elementos como Select2 funcionen correctamente
    modal.css('overflow', 'visible');

    // Define el alto máximo del modal y configura su layout como columna
    content.css({
        'max-height': 'calc(100vh - 60px)', // limita el alto al 100% de la ventana menos 60px
        'display': 'flex', // organiza hijos en columna
        'flex-direction': 'column' // fuerza el orden de arriba hacia abajo (header > body > footer)
    });

    var body = content.find('.modal-body'); // obtiene el cuerpo del modal
    var footer = content.find('.modal-footer'); // obtiene el pie del modal

    // Verifica que no se haya creado ya el wrapper (evita duplicación)
    if (content.find('.modal-body-footer-wrapper').length === 0 && body.length && footer.length) {
        var wrapper = $('<div class=\"modal-body-footer-wrapper\"></div>'); // crea un contenedor envolvente
        body.after(wrapper); // inserta el wrapper después del body
        wrapper.append(body).append(footer); // mete el body y el footer dentro del wrapper para que compartan el scroll
    }
});