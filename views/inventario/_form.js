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
        $('#cmb_micro, #cmb_ram, #cmb_disco, #cmb_so').val('').trigger('change');
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