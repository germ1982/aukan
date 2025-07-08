// Esto se ejecuta cada vez que finaliza una petición AJAX (por ejemplo, al hacer "Crear Otro")
$(document).on('ajaxComplete', function() {
    let dni_persona = $("#input_dni_persona").val();
    if (dni_persona) {
        datos_persona();
    }

    // Asociar evento enter al input
    $("#input_dni_persona").off('keyup').on('keyup', function(event) {
        if (event.which === 13) {
            datos_persona();
        }
    });

    // Botón buscar
    $("#btn_dni").off('click').on('click', function() {
        datos_persona();
    });
});

// Funciones auxiliares
function datos_persona() {
    let dni_persona = $("#input_dni_persona").val();

    if (dni_persona == "") {
        alert("escriba un dni");
        return;
    }

    $('#txt_mensaje').html("Buscando datos de Persona con dni " + dni_persona);
    $.post("index.php?r=persona/validar_dni&dni=" + dni_persona, function(data) {
        data = $.parseJSON(data);
        if (data.length === 0) {
            $('#txt_mensaje').html("No se encontraron datos en Personas con dni " + dni_persona);
            datos_persona_no_homo();
        } else {
            rellenar_campos(data, 1);
        }
    });
}

function datos_persona_no_homo() {
    let dni_persona = $("#input_dni_persona").val();

    $('#txt_mensaje').html("Buscando datos de Persona con dni " + dni_persona);
    $.post("index.php?r=personas_no_homologadas/validar_dni&dni=" + dni_persona, function(data) {
        data = $.parseJSON(data);
        if (data.length === 0) {
            $('#txt_mensaje').html("No se encontraron datos en Persona no Homologadas con dni " + dni_persona);
            rellenar_campos([], 0);
        } else {
            rellenar_campos(data, 2);
        }
    });
}

function rellenar_campos(data, tabla) {
    if (tabla === 0) {
        $('#txt_mensaje').html('No se encontraron datos, rellene los campos para guardar en personas no homologadas');
        return;
    }

    let aux = data[0]['apellido'] + ', ' + data[0]['nombre'];
    if (tabla === 1) {
        $('#txt_mensaje').html(aux + ' datos encontrados en personas');
    } else if (tabla === 2) {
        $('#txt_mensaje').html(aux + ' datos encontrados en personas no homologadas');
    }

    $('#registrorecepcion-nombre').val(data[0]['nombre']);
    $('#registrorecepcion-apellido').val(data[0]['apellido']);
    $('#cmb_documento_tipo').val(data[0]['documento_tipo']).trigger('change');
    $('#cmb_nacionalidad').val(data[0]['nacionalidad']).trigger('change');
    $('#cmb_genero').val(data[0]['genero']).trigger('change');
    $('#input_fecha_nacimiento').val(data[0]['fecha_nacimiento']);
}

function formatearFecha(fecha) {
    let day = fecha.substring(8, 10);
    let month = fecha.substring(5, 7);
    let year = fecha.substring(0, 4);
    return day + "/" + month + "/" + year;
}
