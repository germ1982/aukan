// Carga inicial de la grilla parcial
    refrescar_grilla_movimientos();

    // Si es un alta directa desde Incidencias y no hay movimientos cargados,
    // desplegamos de una el ABM de movimiento con texto por defecto
    if (esAltaDirecta && movimientosArray.length === 0) {
        $('#mov_descripcion').val('Se ingresa equipo para revisión y diagnóstico.');
        mostrar_abm_movimiento();
    }

    function refrescar_grilla_movimientos() {
        $.post(
            "index.php?r=registro_tecnico_incidencia/grilla_movimientos", {
                movimientos: JSON.stringify(movimientosArray)
            },
            function(data) {
                $("#div_grilla_movimientos").html(data);
            }
        );
    }

    function mostrar_abm_movimiento() {
        $("#abm_movimiento").show();
        $('#formulario_principal_incidencia').hide();
        $("#btnGuardar").hide();
        $("#btnCerrar").hide();
    }

    function ocultar_abm_movimiento() {
        $("#abm_movimiento").hide();
        $('#formulario_principal_incidencia').show();
        $("#btnGuardar").show();
        $("#btnCerrar").show();
    }

    function agregarMovimiento() {
        let fecha = $('#mov_fecha').val();
        let hora = $('#mov_hora').val();
        let idmovimiento_nombre = $('#mov_idmovimiento_nombre').val();
        let descripcion = $('#mov_descripcion').val();

        // Asistentes tildados
        let asistentes = [];
        $('input[name="asistentes_movimiento[]"]:checked').each(function() {
            asistentes.push($(this).val());
        });

        if (!descripcion || asistentes.length === 0) {
            alert("Debe ingresar una descripción y seleccionar al menos un técnico/asistente.");
            return;
        }

        let nuevoMovimiento = {
            idtemp: Date.now(),
            fecha: fecha,
            hora: hora,
            idmovimiento_nombre: idmovimiento_nombre,
            descripcion: descripcion,
            asistentes: asistentes
        };

        movimientosArray.push(nuevoMovimiento);

        // Limpiar controles
        $('#mov_descripcion').val('');
        $('input[name="asistentes_movimiento[]"]').prop('checked', false);

        refrescar_grilla_movimientos();
        ocultar_abm_movimiento();
    }

    function eliminar_movimiento(index) {
        if (confirm("¿Desea quitar este movimiento?")) {
            movimientosArray.splice(index, 1);
            refrescar_grilla_movimientos();
        }
    }

    function guardarFormularioIncidencia() {
        if (movimientosArray.length === 0) {
            alert("Debe registrar al menos un movimiento inicial para la incidencia.");
            return false;
        }

        // Serializar array al input hidden
        $('#movimientosArrayInput').val(JSON.stringify(movimientosArray));
        $('#formulario_incidencia').submit();
    }

    