<?php

use app\controllers\SiteController;
use app\models\Edificio;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$mysql_edificios = "SELECT idedificio, concat(descripcion_fija,' - ',descripcion_gestion) as descripcion_fija from edificio
                        order by descripcion_fija, descripcion_gestion";


$initialPreview = [];
$initialPreviewConfig = [];

if (isset($model->plano_ubicacion)) {
    $imagePath = Url::to('img/oficinas-planos/' . $model->plano_ubicacion);

    // Agrega la imagen a la vista previa inicial
    $initialPreview = [
        Html::img($imagePath, ['class' => 'file-preview-image', 'alt' => 'Foto', 'title' => $model->plano_ubicacion, 'width' => '100%', 'height' => 'auto']),
    ];
}
?>

<div class="row">

    <?php $form = ActiveForm::begin(); ?>


    <div class="col-md-8">
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= SiteController::actionGet_input_select2($form, $model, 'idedificio', 'cmb_edificios', Edificio::findBySql($mysql_edificios)->all(), 'idedificio', 'descripcion_fija', 'Edificio', 'Seleccione Edificio...') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
            </div>
        </div>

    </div>
    <div class="col-md-4">
    <?= $form->field($model, 'imageFile')->widget(FileInput::className(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'initialPreview' => $initialPreview,
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                    'showPreview' => true,
                    'showCaption' => false,
                    'showRemove' => true,
                    'showUpload' => false,
                    'showClose' => false,
                    'showCancel' => false,
                    'mainClass' => 'input-group-sm',
                    'maxFileSize' => 100000,
                    'fileActionSettings' => [
                        'showRemove' => false,
                        'showUpload' => false,
                        'showZoom' => false,
                        'showCaption' => false,
                        'showCancel' => false
                    ],
                    'previewFileType' => 'image',
                    'layoutTemplates' => [
                        'footer' => '',  // Remueve el footer en la vista previa si es necesario
                    ],
                    'initialPreviewConfig' => [
                        ['width' => '50px'] // Define el ancho de la vista previa
                    ]
                ]
            ]); ?>
    </div>




    <?php ActiveForm::end(); ?>
</div>