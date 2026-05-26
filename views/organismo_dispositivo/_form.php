<?php

use app\controllers\SiteController;
use app\models\Edificio;
use app\models\EdificioOficina;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrganismoDispositivo;
use app\models\Organismo;

$organismo = Organismo::findOne($model->idorganismo);

/* @var $this yii\web\View */
/* @var $model app\models\OrganismoDispositivo */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs("aplicarCorrector('input_descripcion');");
?>

<style>
    .file-drop-zone {
        min-height: 100px !important;
    }

    .file-preview-image {
        min-height: 100px !important;
        max-width: 100% !important;
        /* Ajusta la imagen al 100% del contenedor */
        max-height: 100% !important;
        /* Define la altura máxima de la vista previa */
        object-fit: cover !important;
        /* Cubre el contenedor sin distorsión */

    }

    .krajee-default {
        min-height: 100px !important;
        float: none !important;
    }

    .kv-file-content {
        min-height: 100px !important;
        width: 100% !important;
    }
</style>

<div class="organismo-dispositivo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class=" col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'descripcion')->textInput(['id' => 'input_descripcion']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php if ($model->origen_alta == 0): ?>
                        <?= SiteController::actionGet_input_select2($form, $model, 'idorganismo', 'cmb_organismo', Organismo::get_organismos(), 'idorganismo', 'descripcion', 'Organismo') ?>

                    <?php else: ?>


                        <label class="control-label"><?= $model->getAttributeLabel('idorganismo') ?></label>
                        <p class="form-control-static" style="background: #eee; padding: 6px 12px; border-radius: 4px;">
                            <?= $model->idorganismo ? Organismo::findOne($model->idorganismo)->descripcion : 'Raíz' ?>
                        </p>

                        <?= $form->field($model, 'idorganismo')->hiddenInput()->label(false) ?>

                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

        </div>
        <div class=" col-md-6">
            <div class="row">
                <div class=" col-md-3" style="padding-top:30px;">
                    <?= $form->field($model, 'es_oficial')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->es_oficial]) ?>
                </div>
                <div class=" col-md-3" style="padding-top:30px;">
                    <?= $form->field($model, 'es_organismo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->es_organismo]) ?>
                </div>
                <div class=" col-md-3" style="padding-top:30px;">
                    <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
                </div>
            </div>

            <div class="row">
                <div class=" col-md-12">
                    <?= SiteController::actionGet_input_select2($form, $model, 'idedificio', 'cmb_edificio', Edificio::get_edificios(), 'idedificio', 'descripcion_fija', 'Edificio') ?>
                </div>
            </div>
            <div class="row">
                <div class=" col-md-12">
                    <?= SiteController::actionGet_input_select2($form, $model, 'idoficina', 'cmb_oficina', EdificioOficina::find()->orderBy('descripcion')->all(), 'idoficina', 'descripcion', 'Oficina') ?>
                </div>
            </div>
        </div>
    </div>





</div>

<?php ActiveForm::end(); ?>

</div>


<?php
$script = <<<JS

var bloqueandoCambio = false; // Nuestra bandera de seguridad


// Escucha el evento de cambio en el selector del edificio (edificio/persona)
$('#cmb_edificio').on('change', function() {

    // Si la bandera 'bloqueandoCambio' es true, significa que la función de sectores
    // está trabajando. Cortamos acá para no entrar en un bucle infinito.
    if (bloqueandoCambio) return; 
    
    // Captura el ID del edificio que el usuario seleccionó
    var idedificio = $(this).val();

    // Verifica si el valor del edificio es nulo, vacío o indefinido (el usuario borró la selección)
    if (!idedificio) {
        console.log('Se borro edificio. Mostrando todas las oficinas disponibles.');
        bloqueandoCambio = true; // Activamos la bandera para evitar que el llenado del combo dispare otros eventos
        let url = 'index.php?r=edificio_oficina/get_oficinas'; // URL para obtener TODAS las oficinas (ajusta el controlador y acción según tu estructura)
        console.log('Haciendo petición a: ' + url);
        $.get(url, function(data) {
                    var select = $('#cmb_oficina');
                    
                    // Vaciamos y reseteamos el combo de personas
                    select.empty().append(new Option('Seleccione una oficina...', ''));
                    
                    // Llenamos con TODaS las oficinas disponibles
                    $.each(data, function(i, item) {
                        select.append(new Option(item.descripcion, item.idoficina));
                    });
                    // IMPORTANTE: Como borramos el sector, también aseguramos que el edificio quede en blanco
                    console.log(select[0]);
console.log(select.find('option').length);
                    select.val('').trigger('change.select2'); 

                    // Liberamos la bandera
                    bloqueandoCambio = false;
                }).fail(function() {
                    bloqueandoCambio = false;
                });
        bloqueandoCambio = false; // Liberamos la bandera para que el usuario pueda seguir interactuando
        return; 
    }

    // Activamos el bloqueo. A partir de acá, cualquier cambio programático en otros 
    // combos no disparará sus respectivas funciones de escucha.
    bloqueandoCambio = true; 
    console.log('Edificio seleccionado: ' + idedificio + '. Obteniendo oficinas relacionadas...');
    // Realiza una petición AJAX (Asynchronous JavaScript and XML) tipo GET para 
    // obtener el ID del oficina asignado a este edificio.
    let url = 'index.php?r=edificio_oficina/get_oficinas_edificio&idedificio=' + idedificio;
    console.log('Haciendo petición a: ' + url);
    $.get(url, function(data) {
        
        // Si el servidor devuelve un dato válido (el ID del oficina)...
        if (data) {
            // Referenciamos el selector de solicitantes
                var select = $('#cmb_oficina');
                
                // Limpiamos las opciones viejas y agregamos la opción neutra initial
                select.empty().append(new Option('Seleccione...', ''));
                
                // Recorremos el listado de oficinas que devolvió el servidor
                let primer_id = 0;
                $.each(data, function(i, item) {
                    console.log(select.html());
                    if(i == 0) primer_id = item.idoficina;
                    // Agregamos cada oficina como una nueva opción en el combo
                    select.append(new Option(item.descripcion, item.idoficina));
                });
            select.val(primer_id).trigger('change.select2');
           //select.val().trigger('change.select2');
        }

        // Una vez terminada la operación y actualizado el combo, liberamos la bandera.
        bloqueandoCambio = false; 

    // Si la petición al servidor falla (error 500, 404, etc.), liberamos la bandera
    // para que el formulario no quede "congelado" y el usuario pueda seguir intentando.
    }).fail(function() { bloqueandoCambio = false; }); 
});

// Al cambiar sector → filtra edificios

 $('#cmb_oficina').on('change', function() {
    console.log('Cambio en oficina detectado. ID seleccionado: ' + $(this).val());
    // Si la bandera está activa, corta la ejecución para evitar bucles infinitos
    if (bloqueandoCambio) return;
    console.log('en elk change de oficina, bloqueandoCambio no estaba bloqueado');
     
    // Captura el ID del oficina seleccionado actualmente
    var idoficina = $(this).val();
    
    // Guarda el ID del edificio que está seleccionado en este momento para no perderlo(ver)
    var idedificio = $('#cmb_edificio').val();

    /* if (idedificio) {
        console.log('hay un edificio seleccionado, no se hace nada porque el cambio de oficina no debería afectar al edificio');
        return;
    } */
    console.log('No hay edificio seleccionado, se procede a obtener el edificio relacionado a la oficina ' + idoficina);
    // Activa la bandera para avisar que estamos operando y bloquear otros eventos change
    bloqueandoCambio = true;

    // Realiza una petición GET al controlador de edificios filtrando por el ID del oficina
    $.get('index.php?r=edificio_oficina/get_edificio&idoficina=' + idoficina, function(data) {
        console.log('Respuesta del servidor para el edificio relacionado a la oficina ' + idoficina + ': ', data);
        // Referencia el selector de edificios (personas)
        var select = $('#cmb_edificio');
        
       // Dispara el evento change para que componentes como Select2 se enteren del cambio visual
        select.val(data).trigger('change'); 
        
        // Desactiva la bandera para permitir que el usuario vuelva a interactuar con los combos
        bloqueandoCambio = false;
    }); 
}); 

JS;
$this->registerJs($script);
?>