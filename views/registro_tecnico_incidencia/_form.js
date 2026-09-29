// NOTA: todo con `var` (no `let`): este script se inyecta cada vez que se abre el modal
// y un `let` global redeclarado tira SyntaxError la segunda vez.

// -1 = alta de movimiento nuevo, >= 0 = editando ese índice de movimientosArray
var indiceEdicion = -1;
// true mientras cambiamos combos por código: los listeners de change se ignoran (evita cascadas)
var sincronizandoCombos = false;

// ---------------------------------------------------------------------------
// Inicialización
// ---------------------------------------------------------------------------
refrescar_grilla_movimientos(); // también sincroniza el hidden y evalúa visibilidad de retiro

var iddispositivoInicial = $("#input_iddispositivo_inc").val();
if (iddispositivoInicial) {
     cargarInventarioPorDispositivoSector(iddispositivoInicial);
}

// Alta (desde Registro Técnico o directa): se muestra el ABM del movimiento inicial ya cargado con los defaults.
// movimientosArray queda vacío hasta que el usuario lo agregue.
if (movimientoInicialDefaults !== null && movimientosArray.length === 0) {
     $("#mov_fecha").val(movimientoInicialDefaults.fecha);
     $("#mov_hora").val(movimientoInicialDefaults.hora);
     $("#mov_idmovimiento_nombre").val(
          movimientoInicialDefaults.idmovimiento_nombre,
     );
     $("#mov_descripcion").val(movimientoInicialDefaults.descripcion);

     $('input[name="asistentes_movimiento[]"]').prop("checked", false);
     if (Array.isArray(movimientoInicialDefaults.asistentes)) {
          movimientoInicialDefaults.asistentes.forEach(function (idTecnico) {
               $(
                    'input[name="asistentes_movimiento[]"][value="' +
                         idTecnico +
                         '"]',
               ).prop("checked", true);
          });
     }

     mostrar_abm_movimiento();
}

// ---------------------------------------------------------------------------
// Movimientos
// ---------------------------------------------------------------------------

/**
 * Evalúa si existe algún movimiento en movimientosArray con idmovimiento_nombre == 4 (Entregado).
 * Si existe, muestra #div_row_retiro; si no, lo oculta.
 */
function evaluarVisibilidadRetiro() {
     var tieneEntregado = movimientosArray.some(function (mov) {
          return parseInt(mov.idmovimiento_nombre, 10) === 4;
     });

     var divRetiro = $("#div_row_retiro");
     if (tieneEntregado) {
          divRetiro.slideDown();
     } else {
          divRetiro.slideUp();
          $("#input_idretira_inc").val("").trigger("change");
     }
}

/**
 * Deja el input hidden que viaja al controller siempre igual a movimientosArray.
 * Se llama desde refrescar_grilla_movimientos(), que ya se ejecuta en cada alta/edición/baja.
 */
function sincronizarHidden() {
     $("#movimientosArrayInput").val(JSON.stringify(movimientosArray));
     evaluarVisibilidadRetiro();
}

function refrescar_grilla_movimientos() {
     sincronizarHidden();
     $.post(
          "index.php?r=registro_tecnico_incidencia/grilla_movimientos",
          { movimientos: JSON.stringify(movimientosArray) },
          function (data) {
               $("#div_grilla_movimientos").html(data);
          },
     );
}

function ahoraFecha() {
     var d = new Date();
     return (
          ("0" + d.getDate()).slice(-2) +
          "/" +
          ("0" + (d.getMonth() + 1)).slice(-2) +
          "/" +
          d.getFullYear()
     );
}

function ahoraHora() {
     var d = new Date();
     return (
          ("0" + d.getHours()).slice(-2) +
          ":" +
          ("0" + d.getMinutes()).slice(-2)
     );
}

function mostrar_abm_movimiento() {
     $("#div_grilla").hide();
     // Botones del footer del modal (están fuera del form)
     $("#btnGuardar, #btnCerrar").hide();

     if (indiceEdicion >= 0) {
          $("#titulo_abm_movimiento").text("Editar Movimiento");
          $("#btn_agregar_mov, #btn_guardar_incidencia").hide();
          $("#btn_actualizar_mov").show();
     } else {
          var esInicial = movimientosArray.length === 0;
          $("#titulo_abm_movimiento").text(
               esInicial ? "Movimiento Inicial" : "Nuevo Movimiento",
          );

          // Movimientos posteriores al inicial: fecha/hora del momento en que se abre el ABM
          if (!esInicial) {
               $("#mov_fecha").val(ahoraFecha());
               $("#mov_hora").val(ahoraHora());
               $("#mov_idmovimiento_nombre").val(2).trigger("change");
          }

          $("#btn_agregar_mov").show();
          $("#btn_actualizar_mov").hide();
          // "Guardar Incidencia" solo existe en el alta y solo se ofrece con el movimiento inicial
          $("#btn_guardar_incidencia").toggle(esInicial);
     }

     $("#abm_movimiento").slideDown();
}

function ocultar_abm_movimiento() {
     limpiar_formulario_movimiento();
     indiceEdicion = -1;

     $("#abm_movimiento").hide();
     $("#div_grilla").show();
     $("#btnCerrar").show();
     // Sin movimientos no hay nada para guardar
     $("#btnGuardar").toggle(movimientosArray.length > 0);
}

/**
 * Cancelar: si todavía no hay ningún movimiento (se está cancelando el inicial) cierra el modal,
 * porque una incidencia no puede existir sin movimientos.
 */
function cancelar_movimiento() {
     if (movimientosArray.length === 0) {
          $("#btnCerrar").trigger("click");
          return;
     }
     ocultar_abm_movimiento();
}

function limpiar_formulario_movimiento() {
     $("#mov_descripcion").val("");
     $('input[name="asistentes_movimiento[]"]').prop("checked", false);
     $("#mov_idmovimiento_nombre").val(
          $("#mov_idmovimiento_nombre option:first").val(),
     );
}

function validarCabecera() {
     if (
          !$("#input_idingresante_inc").val() ||
          !$("#input_iddispositivo_inc").val()
     ) {
          alert("Debe seleccionar el ingresante y el dispositivo / sector.");
          return false;
     }
     return true;
}

/**
 * Agrega (o actualiza, si indiceEdicion >= 0) el movimiento del ABM en movimientosArray.
 * Si guardarIncidencia === true, además dispara el guardado del modal (botón #btnGuardar del footer).
 */
function agregarMovimiento(guardarIncidencia) {
     var fecha = $("#mov_fecha").val();
     var hora = $("#mov_hora").val();
     var idmovimiento_nombre = $("#mov_idmovimiento_nombre").val();
     var descripcion = $("#mov_descripcion").val();

     var asistentes = [];
     $('input[name="asistentes_movimiento[]"]:checked').each(function () {
          asistentes.push($(this).val());
     });

     if (!descripcion || asistentes.length === 0) {
          alert(
               "Debe ingresar una descripción y seleccionar al menos un técnico/asistente.",
          );
          return;
     }

     if (!validarCabecera()) return;

     if (indiceEdicion >= 0) {
          var mov = movimientosArray[indiceEdicion];
          mov.fecha = fecha;
          mov.hora = hora;
          mov.idmovimiento_nombre = idmovimiento_nombre;
          mov.descripcion = descripcion;
          mov.asistentes = asistentes;
     } else {
          movimientosArray.push({
               idtemp: Date.now(),
               fecha: fecha,
               hora: hora,
               idmovimiento_nombre: idmovimiento_nombre,
               descripcion: descripcion,
               asistentes: asistentes,
          });
     }

     refrescar_grilla_movimientos(); // sincroniza el hidden antes de cualquier submit
     ocultar_abm_movimiento();

     if (guardarIncidencia === true) {
          $("#btnGuardar").trigger("click");
     }
}

function editar_movimiento(index) {
     var mov = movimientosArray[index];
     if (!mov) return;

     indiceEdicion = index;

     if (mov.fecha) $("#mov_fecha").val(mov.fecha);
     if (mov.hora) $("#mov_hora").val(mov.hora);
     $("#mov_idmovimiento_nombre").val(mov.idmovimiento_nombre);
     $("#mov_descripcion").val(mov.descripcion);

     $('input[name="asistentes_movimiento[]"]').prop("checked", false);
     if (Array.isArray(mov.asistentes)) {
          mov.asistentes.forEach(function (idTecnico) {
               $(
                    'input[name="asistentes_movimiento[]"][value="' +
                         idTecnico +
                         '"]',
               ).prop("checked", true);
          });
     }

     mostrar_abm_movimiento();
}

function eliminar_movimiento(index) {
     if (confirm("¿Desea quitar este movimiento?")) {
          movimientosArray.splice(index, 1);
          refrescar_grilla_movimientos();
          $("#btnGuardar").toggle(movimientosArray.length > 0);
     }
}

// ---------------------------------------------------------------------------
// Inventario / Ingresante / Dispositivo
// ---------------------------------------------------------------------------

/** Ejecuta fn con los listeners de change de los combos desactivados (los triggers son síncronos). */
function sinCascada(fn) {
     sincronizandoCombos = true;
     try {
          fn();
     } finally {
          sincronizandoCombos = false;
     }
}

/**
 * Carga el inventario asignado al dispositivo/sector; oculta la fila si no hay ítems.
 */
function cargarInventarioPorDispositivoSector(iddispositivo) {
     var rowInventario = $("#div_row_inventario");
     var selectInventario = $("#input_idinventario_inc");

     selectInventario
          .empty()
          .append(new Option("Seleccione un ítem del inventario...", ""));

     if (!iddispositivo) {
          rowInventario.slideUp();
          return;
     }

     $.get(
          "index.php?r=registro_tecnico_incidencia/get_inventario_por_dispositivo_sector&id=" +
               iddispositivo,
          function (data) {
               if (Array.isArray(data) && data.length > 0) {
                    $.each(data, function (i, item) {
                         selectInventario.append(
                              new Option(item.descripcion, item.idinventario),
                         );
                    });
                    rowInventario.slideDown();
               } else {
                    rowInventario.slideUp();
               }
          },
     ).fail(function () {
          rowInventario.slideUp();
     });
}

/**
 * Ingresante elegido por el usuario => se completa su dispositivo/sector.
 */
$("#input_idingresante_inc").on("change", function () {
     if (sincronizandoCombos) return;

     var idempleado = $(this).val();
     if (!idempleado) return;

     $.get(
          "index.php?r=empleado/get_dispositivo&id=" + idempleado,
          function (iddispositivo) {
               if (!iddispositivo) return;

               sinCascada(function () {
                    $("#input_iddispositivo_inc")
                         .val(iddispositivo)
                         .trigger("change");
               });
               cargarInventarioPorDispositivoSector(iddispositivo);
          },
     );
});

/**
 * Dispositivo/sector elegido por el usuario => se cargan su inventario y se filtran los ingresantes
 * (todos, si se limpió el sector). Se conserva el ingresante actual si sigue estando en la lista.
 */
$("#input_iddispositivo_inc").on("change", function () {
     if (sincronizandoCombos) return;

     var iddispositivo = $(this).val();
     cargarInventarioPorDispositivoSector(iddispositivo);

     var url = iddispositivo
          ? "index.php?r=empleado/get_por_dispositivo&id=" + iddispositivo
          : "index.php?r=empleado/get_empleados";
     var idActual = $("#input_idingresante_inc").val();

     $.get(url, function (data) {
          var select = $("#input_idingresante_inc");
          select.empty().append(new Option("Seleccione...", ""));

          var sigueEnLista = false;
          $.each(data, function (i, item) {
               select.append(new Option(item.descripcion, item.idempleado));
               if (item.idempleado == idActual) sigueEnLista = true;
          });

          sinCascada(function () {
               select.val(sigueEnLista ? idActual : "").trigger("change");
          });
     });
});