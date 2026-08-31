var bloqueandoCambio = false;

// 1. EVENTO: Cambio en Solicitante
$('#input_idsolicitante').on('change', function() {
    if (bloqueandoCambio) return;
    
    var idempleado = $(this).val();

    // Si el usuario borra la selección del solicitante
    if (!idempleado) {
        var iddispositivoActual = $('#input_idsector_solicitante').val();
        if (iddispositivoActual) {
            bloqueandoCambio = true;
            $.get('index.php?r=empleado/get_por_dispositivo&id=' + iddispositivoActual, function(data) {
                var $select = $('#input_idsolicitante');
                $select.empty().append(new Option('Seleccione...', ''));
                $.each(data, function(i, item) {
                    $select.append(new Option(item.descripcion, item.idempleado));
                });
                // Actualiza la interfaz visual de Select2 sin disparar nuestro evento
                $select.val('').trigger('change.select2');
            }).always(function() {
                bloqueandoCambio = false;
            });
        }
        return;
    }

    // Si seleccionó un empleado, busca su sector
    bloqueandoCambio = true;
    $.get('index.php?r=empleado/get_dispositivo&id=' + idempleado, function(data) {
        if (data) {
            // Setea el sector y fuerza refresco de Select2
            $('#input_idsector_solicitante').val(data).trigger('change.select2');
        }
    }).always(function() {
        bloqueandoCambio = false;
    });
});

// 2. EVENTO: Cambio en Sector
$('#input_idsector_solicitante').on('change', function() {
    if (bloqueandoCambio) return;
    
    var iddispositivo = $(this).val();
    
    // Si borró la selección del sector
    if (!iddispositivo) {
        bloqueandoCambio = true;
        $.get('index.php?r=empleado/get_empleados', function(data) {
            var $select = $('#input_idsolicitante');
            $select.empty().append(new Option('Seleccione un solicitante...', ''));
            $.each(data, function(i, item) {
                $select.append(new Option(item.descripcion, item.idempleado));
            });
            $select.val('').trigger('change.select2');
        }).always(function() {
            bloqueandoCambio = false;
        });
        return;
    }

    // Filtra los solicitantes por el sector elegido
    var seleccionadoAnteriormente = $('#input_idsolicitante').val();
    bloqueandoCambio = true;

    $.get('index.php?r=empleado/get_por_dispositivo&id=' + iddispositivo, function(data) {
        var $select = $('#input_idsolicitante');
        $select.empty().append(new Option('Seleccione...', ''));
        
        $.each(data, function(i, item) {
            var isSelected = (item.idempleado == seleccionadoAnteriormente);
            $select.append(new Option(item.descripcion, item.idempleado, isSelected, isSelected));
        });
        
        // Notifica únicamente a Select2 para repintar la vista
        $select.trigger('change.select2');
    }).always(function() {
        bloqueandoCambio = false;
    });
});