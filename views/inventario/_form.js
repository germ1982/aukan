// Evento al cambiar de artículo
$('#cmb_articulo').on('change', function() {
    let idArticulo = $(this).val();
    let esInt = parseInt(idArticulo);

    // Evaluamos la propiedad CPU
    if (articulosCpu.includes(idArticulo) || articulosCpu.includes(esInt)) {
        $('#div_caracteristicas_cpu').slideDown();
        $('#input_es_cpu').val(1);
    } else {
        $('#div_caracteristicas_cpu').slideUp();
        $('#input_es_cpu').val(0);
        $('#cmb_micro, #cmb_ram_uno, #cmb_ram_dos, #cmb_disco, #cmb_so').val('').trigger('change');
    }

    // Evaluamos la propiedad RED
    if (articulosRed.includes(idArticulo) || articulosRed.includes(esInt)) {
        $('#div_caracteristicas_red').slideDown();
        $('#input_tiene_red').val(1);
    } else {
        $('#div_caracteristicas_red').slideUp();
        $('#input_tiene_red').val(0);
        
        // Si no tiene red, apagamos el switch, el hidden y ocultamos IP
        $('#chk_tiene_ip').prop('checked', false);
        $('#input_tiene_ip').val(0);
        $('#div_caracteristicas_ip').slideUp();
        $('#cmb_ip, #cmb_mascara_red, #cmb_puerta_enlace, #cmb_dns_red').val('').trigger('change');
    }
});

// Evento al cambiar el Interruptor de IP
$('#chk_tiene_ip').on('change', function() {
    if ($(this).is(':checked')) {
        $('#div_caracteristicas_ip').slideDown();
        $('#input_tiene_ip').val(1); // Seteamos el hidden en 1
    } else {
        $('#div_caracteristicas_ip').slideUp();
        $('#input_tiene_ip').val(0); // Seteamos el hidden en 0
        
        // Limpiamos los combos de IP si se apaga el interruptor
        $('#cmb_ip, #cmb_mascara_red, #cmb_puerta_enlace, #cmb_dns_red').val('').trigger('change');
    }
});

// Evento al cambiar de IP
$('#cmb_ip').on('change', function() {
    let idIp = $(this).val();

    if (idIp && ipsMap[idIp]) {
        let datosIp = ipsMap[idIp];
        
        $('#cmb_mascara_red').val(datosIp.mascara).trigger('change');
        $('#cmb_puerta_enlace').val(datosIp.puerta_enlace).trigger('change');
        $('#cmb_dns_red').val(datosIp.dns).trigger('change');
    } else {
        $('#cmb_mascara_red, #cmb_puerta_enlace, #cmb_dns_red').val('').trigger('change');
    }
});


// Agregar nuevo combo de RAM
$(document).on('click', '.btn-agregar-ram', function () {
    let primeraFila = $('#contenedor_rams .fila-componente-ram').first();
    let nuevaFila = primeraFila.clone();
    
    // Reseteamos el valor seleccionado en el nuevo combo
    nuevaFila.find('select').val('');
    $('#contenedor_rams').append(nuevaFila);
    actualizarNumeracionComponentes();
});

// Agregar nuevo combo de Disco
$(document).on('click', '.btn-agregar-disco', function () {
    let primeraFila = $('#contenedor_discos .fila-componente-disco').first();
    let nuevaFila = primeraFila.clone();
    
    // Reseteamos el valor seleccionado en el nuevo combo
    nuevaFila.find('select').val('');
    $('#contenedor_discos').append(nuevaFila);
    actualizarNumeracionComponentes();
});

// Eliminar combo (RAM o Disco)
$(document).on('click', '.btn-quitar-componente', function () {
    let contenedorPadre = $(this).closest('#contenedor_rams, #contenedor_discos');
    
    // Permitimos borrar solo si hay más de una fila
    if (contenedorPadre.children('.row').length > 1) {
        $(this).closest('.row').remove();
        actualizarNumeracionComponentes();
    } else {
        // Si es la única fila, solo limpiamos el valor seleccionado
        $(this).closest('.row').find('select').val('');
    }
});

// Función para enumerar automáticamente los labels de Memoria y Disco
function actualizarNumeracionComponentes() {
    $('#contenedor_rams .fila-componente-ram').each(function (index) {
        $(this).find('.label-ram').text('Memoria ' + (index + 1));
    });

    $('#contenedor_discos .fila-componente-disco').each(function (index) {
        $(this).find('.label-disco').text('Disco ' + (index + 1));
    });
}

$(document).ready(function () {
    var $cmbEdificio = $('#cmb_edificio');
    var $cmbOficina = $('#cmb_oficina');

    // Estado inicial: Si no hay edificio seleccionado, deshabilitar el combo de oficinas
    if (!$cmbEdificio.val()) {
        $cmbOficina.prop('disabled', true);
    }

    // Evento al cambiar el edificio
    $cmbEdificio.on('change', function () {
        var idEdificio = $(this).val();

        // Limpiar el combo de oficinas
        $cmbOficina.val(null).trigger('change');
        $cmbOficina.empty();

        if (idEdificio) {
            // Habilitar y cargar oficinas pertenecientes al edificio
            $cmbOficina.prop('disabled', false);

            $.ajax({
                url: 'index.php?r=inventario/get-oficinas-por-edificio',
                type: 'GET',
                data: { idedificio: idEdificio },
                dataType: 'json',
                success: function (data) {
                    $cmbOficina.html(data.options).trigger('change');
                }
            });
        } else {
            // Si vuelve a "seleccione edificio...", se deshabilita
            $cmbOficina.append('<option value="">seleccione oficina...</option>');
            $cmbOficina.prop('disabled', true).trigger('change');
        }
    });
});