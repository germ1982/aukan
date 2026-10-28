<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Efemerides */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="efemerides-form">

    <?php $form = ActiveForm::begin([
        'id' => 'efemerides-form',
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'fecha')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-md-9">
            <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'activo')->checkbox() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'regla')->checkbox([
                'id' => 'check-regla',
                'uncheck' => 0,
                'value' => 1
            ]) ?>
        </div>

        <div class="col-md-3 div-regla-condicional" style="display: none;">
            <?= $form->field($model, 'num_semana')->dropDownList([
                1 => '1era Semana',
                2 => '2da Semana',
                3 => '3era Semana',
                4 => '4ta Semana',
                5 => '5ta Semana (Última)'
            ], ['prompt' => 'Seleccione semana...']) ?>
        </div>

        <div class="col-md-3 div-regla-condicional" style="display: none;">
            <?= $form->field($model, 'dia_semana')->dropDownList([
                0 => 'Domingo',
                1 => 'Lunes',
                2 => 'Martes',
                3 => 'Miércoles',
                4 => 'Jueves',
                5 => 'Viernes',
                6 => 'Sábado'
            ], ['prompt' => 'Seleccione día...']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'descripcion')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <!-- SECCIÓN DE CARGA DE FOTOS Y MINIATURAS -->
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'fotos[]')->fileInput([
                'multiple' => true, 
                'accept' => 'image/*',
                'id' => 'input-fotos-multiples'
            ]) ?>
            <small class="text-muted">Podés seleccionar varias imágenes a la vez. Se previsualizarán abajo.</small>
            
            <!-- Contenedor para previsualizar nuevas fotos seleccionadas -->
            <div id="preview-nuevas-fotos" class="row" style="margin-top: 10px;"></div>

            <!-- Contenedor para fotos existentes (si es update) -->
            <?php if (!$model->isNewRecord): ?>
                <?php 
                    $fotosActuales = (new \yii\db\Query())
                        ->from('efemerides_fotos')
                        ->where(['idefemeride' => $model->idefemeride])
                        ->orderBy(['orden' => SORT_ASC])
                        ->all();
                ?>
                <div style="margin-top: 15px;">
                    <label>Fotos actuales en galería:</label>
                    <div class="row" id="contenedor-fotos-actuales">
                        <?php if (!empty($fotosActuales)): ?>
                            <?php foreach($fotosActuales as $f): ?>
                                <div class="col-md-2 text-center foto-item-<?= $f['idfoto'] ?>" style="margin-bottom: 15px;">
                                    <div class="thumbnail" style="padding: 5px; position: relative;">
                                        <img src="<?= Url::base(true) . '/img/efemerides/' . $f['foto'] ?>" style="height: 80px; object-fit: cover; width: 100%;">
                                        <span style="font-size: 10px; display: block; margin: 5px 0;">Orden: <?= $f['orden'] ?></span>
                                        <button type="button" class="btn btn-danger btn-xs btn-eliminar-foto" data-id="<?= $f['idfoto'] ?>" style="width: 100%;">Eliminar</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-md-12"><p class="text-muted">No hay fotos cargadas.</p></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group" style="margin-top: 15px;">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

<?php
$urlDeleteFoto = Url::to(['efemerides/delete-foto']);
$js = "
function toggleRegla() {
    if($('#check-regla').is(':checked')) {
        $('.div-regla-condicional').show();
    } else {
        $('.div-regla-condicional').hide();
    }
}
toggleRegla();
$('#check-regla').change(function() {
    toggleRegla();
});

// Previsualización en miniatura de las nuevas fotos seleccionadas mediante FileReader
$('#input-fotos-multiples').change(function(e) {
    $('#preview-nuevas-fotos').html('');
    var files = e.target.files;
    $.each(files, function(i, file) {
        var reader = new FileReader();
        reader.onload = function(to) {
            var html = '<div class=\"col-md-2 text-center\" style=\"margin-bottom: 15px;\">' +
                       '<div class=\"thumbnail\" style=\"padding: 5px;\">' +
                       '<img src=\"' + to.target.result + '\" style=\"height: 80px; object-fit: cover; width: 100%;\">' +
                       '<span style=\"font-size: 10px; display: block; margin-top: 5px; color: green;\">Nueva</span>' +
                       '</div></div>';
            $('#preview-nuevas-fotos').append(html);
        };
        reader.readAsDataURL(file);
    });
});

// Eliminación instantánea de foto existente vía AJAX sin esperar el form
$(document).on('click', '.btn-eliminar-foto', function() {
    var idfoto = $(this).data('id');
    if(confirm('¿Estás seguro de eliminar esta foto permanentemente?')) {
        $.ajax({
            url: '" . $urlDeleteFoto . "',
            type: 'POST',
            data: {idfoto: idfoto},
            success: function(response) {
                if(response.success) {
                    $('.foto-item-' + idfoto).fadeOut(300, function() { $(this).remove(); });
                } else {
                    alert('Error al eliminar la foto.');
                }
            }
        });
    }
});
";
$this->registerJs($js);
?>