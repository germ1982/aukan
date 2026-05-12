<?php

use app\controllers\SiteController;
use app\helpers\AppCheckboxListHelper;
use app\helpers\AppRadioButtomsListHelper;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\RegistroTecnicoAsistencia;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$solicitantes = Empleado::get_empleados();
$decreto_activo = $model->isNewRecord ? true:false;
$sectores = OrganismoDispositivo::get_dispositivos_con_decreto($decreto_activo);
$tipos_registros = Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_REGISTRO_TECNICO);

$tecnicos_asistencia = Empleado::get_asistentes_informaticos();

if ($model->fecha_solicitud) {
    $model->fecha_solicitud = date('d/m/Y', strtotime($model->fecha_solicitud));
    $model->hora_solicitud = substr($model->hora_solicitud, 0, 5);
} else {
    $model->fecha_solicitud = date('d/m/Y');
    $model->hora_solicitud = substr($model->hora_solicitud, 0, 5);
}

if ($model->fecha_solucion) {
    $model->fecha_solucion = date('d/m/Y', strtotime($model->fecha_solucion));
    $model->hora_solucion = substr($model->hora_solucion, 0, 5);
} else {
    $model->fecha_solucion = date('d/m/Y');
    $model->hora_solucion = substr($model->hora_solucion, 0, 5);
}

$asistentes_seleccionados = [];
if (!$model->isNewRecord) {
    $selectedIds = RegistroTecnicoAsistencia::find()
        ->select('idtecnico')
        ->where(['idregistro' => $model->idregistro])
        ->column();
    $asistentes_seleccionados = $selectedIds;
}
?>

<div class="registro-tecnico-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-5">
                    <?= SiteController::actionGet_input_fecha($form, $model, 'fecha_solicitud', 'input_fecha_solicitud') ?>
                </div>
                <div class="col-md-5">
                    <?= SiteController::actionGet_input_hora($form, $model, 'hora_solicitud', 'input_hora_solicitud') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= SiteController::actionGet_input_select2($form, $model, 'idsolicitante', 'input_idsolicitante', $solicitantes, 'idempleado', 'descripcion') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'input_iddispositivo', $sectores, 'iddispositivo', 'descripcion') ?>
                </div>
            </div>

        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'problema')->textarea(['rows' => 9]) ?>

        </div>
    </div>



    <div class="row">
        <div class="col-md-7">
            <div class="row">
                <div class="col-md-12">
                    <label>Asistentes</label>
                    <?= AppCheckboxListHelper::render($tecnicos_asistencia, 'idempleado', 'descripcion', 'asistentes', $asistentes_seleccionados ?? []) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>Tipo de Registro</label>
                    <?= AppRadioButtomsListHelper::renderRadio(
                        $tipos_registros,
                        'id_configuracion',
                        'descripcion',
                        'idtipo_registro',
                        $model->idtipo_registro ?? null
                    ) ?>
                </div>
            </div>
        </div>
        <div class="col-md-5">

            <div class="row">
                <div class="col-md-6">
                    <?= SiteController::actionGet_input_fecha($form, $model, 'fecha_solucion', 'input_fecha_solucion') ?>
                </div>
                <div class="col-md-6">
                    <?= SiteController::actionGet_input_hora($form, $model, 'hora_solucion', 'input_hora_solucion') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'solucion')->textarea(['rows' => 5]) ?>
                </div>
            </div>

        </div>
    </div>





    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS

var bloqueandoCambio = false; // Nuestra bandera de seguridad


// Escucha el evento de cambio en el selector del solicitante (empleado/persona)
$('#input_idsolicitante').on('change', function() {

    // Si la bandera 'bloqueandoCambio' es true, significa que la función de sectores
    // está trabajando. Cortamos acá para no entrar en un bucle infinito.
    if (bloqueandoCambio) return; 
    
    // Captura el ID del empleado que el usuario seleccionó
    var idempleado = $(this).val();

    // Verifica si el valor del empleado es nulo, vacío o indefinido (el usuario borró la selección)
    if (!idempleado) {
        
        // Obtiene el ID del dispositivo (sector) que está seleccionado actualmente en el otro combo
        var iddispositivoActual = $('#input_iddispositivo').val();
        
        // Si efectivamente hay un sector seleccionado, procedemos a recargar sus empleados
        if (iddispositivoActual) {
            
            // Activamos la bandera para evitar que los cambios en el DOM disparen eventos en cadena
            bloqueandoCambio = true;
            
            // Realiza una petición AJAX (Asynchronous JavaScript and XML) para traer los empleados del sector
            $.get('index.php?r=empleado/get_por_dispositivo&id=' + iddispositivoActual, function(data) {
                
                // Referenciamos el selector de solicitantes
                var select = $('#input_idsolicitante');
                
                // Limpiamos las opciones viejas y agregamos la opción neutra inicial
                select.empty().append(new Option('Seleccione...', ''));
                
                // Recorremos el listado de empleados que devolvió el servidor
                $.each(data, function(i, item) {
                    // Agregamos cada empleado como una nueva opción en el combo
                    select.append(new Option(item.descripcion, item.idempleado));
                });
                
                // Importante: No disparamos .trigger('change') aquí para no reactivar la búsqueda de sector
                // Simplemente bajamos la bandera para permitir futuras interacciones del usuario
                bloqueandoCambio = false;
            });
        }
        
        // Cortamos la ejecución de la función principal porque el campo quedó vacío y ya lanzamos el refresh
        return; 
    }

    // Activamos el bloqueo. A partir de acá, cualquier cambio programático en otros 
    // combos no disparará sus respectivas funciones de escucha.
    bloqueandoCambio = true; 

    // Realiza una petición AJAX (Asynchronous JavaScript and XML) tipo GET para 
    // obtener el ID del dispositivo asignado a este empleado.
    $.get('index.php?r=empleado/get_dispositivo&id=' + idempleado, function(data) {
        
        // Si el servidor devuelve un dato válido (el ID del dispositivo)...
        if (data) {
            // Setea ese valor en el combo de dispositivos y dispara el evento 'change'.
            // Gracias a la bandera 'bloqueandoCambio', la otra función ignorará este trigger.
            $('#input_iddispositivo').val(data).trigger('change');
        }

        // Una vez terminada la operación y actualizado el combo, liberamos la bandera.
        bloqueandoCambio = false; 

    // Si la petición al servidor falla (error 500, 404, etc.), liberamos la bandera
    // para que el formulario no quede "congelado" y el usuario pueda seguir intentando.
    }).fail(function() { bloqueandoCambio = false; }); 
});

// Al cambiar sector → filtra solicitantes

$('#input_iddispositivo').on('change', function() {
    // Si la bandera está activa, corta la ejecución para evitar bucles infinitos
    if (bloqueandoCambio) return;
    
    // Captura el ID del dispositivo seleccionado actualmente
    var iddispositivo = $(this).val();
    
    // Si el valor del dispositivo está vacío (el usuario borró el sector seleccionado)
    if (!iddispositivo) {
        
        // Activamos la bandera para evitar que el llenado del combo dispare otros eventos
        bloqueandoCambio = true;

        // Pedimos al servidor la lista completa (usando 'all' o el parámetro que definas)
        $.get('index.php?r=empleado/get_empleados', function(data) {
            var select = $('#input_idsolicitante');
            
            // Vaciamos y reseteamos el combo de personas
            select.empty().append(new Option('Seleccione un solicitante...', ''));
            
            // Llenamos con TODOS los empleados disponibles
            $.each(data, function(i, item) {
                select.append(new Option(item.descripcion, item.idempleado));
            });

            // IMPORTANTE: Como borramos el sector, también aseguramos que el solicitante quede en blanco
            select.val('').trigger('change'); 

            // Liberamos la bandera
            bloqueandoCambio = false;
        }).fail(function() {
            bloqueandoCambio = false;
        });

        // Cortamos la ejecución para que no intente seguir con la lógica de un ID inexistente
        return;
    }
    // Guarda el ID del solicitante que está seleccionado en este momento para no perderlo(ver)
    var seleccionadoAnteriormente = $('#input_idsolicitante').val();

    // Activa la bandera para avisar que estamos operando y bloquear otros eventos change
    bloqueandoCambio = true;

    // Realiza una petición GET al controlador de empleados filtrando por el ID del dispositivo
    $.get('index.php?r=empleado/get_por_dispositivo&id=' + iddispositivo, function(data) {
        
        // Referencia el selector de solicitantes (personas)
        var select = $('#input_idsolicitante');
        
        // Vacía todas las opciones actuales del selector de personas
        select.empty();
        
        // Recorre los datos recibidos del servidor (el listado de empleados)
        $.each(data, function(i, item) {
            
            // Verifica si este empleado de la lista es el mismo que estaba seleccionado antes
            var selected = (item.idempleado == seleccionadoAnteriormente) ? true : false;
            
            // Crea un nuevo objeto de opción con el nombre, el ID y define si debe estar seleccionado
            var newOption = new Option(item.descripcion, item.idempleado, selected, selected);
            
            // Agrega la nueva opción al selector de personas
            select.append(newOption);
        });
        
        // Dispara el evento change para que componentes como Select2 se enteren del cambio visual
        select.trigger('change'); 
        
        // Desactiva la bandera para permitir que el usuario vuelva a interactuar con los combos
        bloqueandoCambio = false;
    });
});

JS;
$this->registerJs($script);
?>