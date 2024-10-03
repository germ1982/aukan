<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Articulo */
/* @var $form yii\widgets\ActiveForm */

if (isset($model->imagen)) {
    $imagePath = Url::to('img/articulos/' . $model->imagen);

    // Agrega la imagen a la vista previa inicial
    $initialPreview = [
        Html::img($imagePath, ['class' => 'file-preview-image', 'alt' => 'Imagen', 'title' => $model->imagen, 'width' => '100%', 'height' => 'auto']),
    ];
}
$array_tipos = Configuracion::find()->where(['activo' => 1, 'id_configuracion_tipo' => 12])->orderBy('descripcion')->all();
$array_marca = Configuracion::find()->where(['activo' => 1, 'id_configuracion_tipo' => 14])->orderBy('descripcion')->all();
$array_rubro = Configuracion::find()->where(['activo' => 1, 'id_configuracion_tipo' => 15])->orderBy('descripcion')->all();
$array_unidad_de_medida = Configuracion::find()->where(['activo' => 1, 'id_configuracion_tipo' => 13])->orderBy('descripcion')->all();
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


<div class="articulo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class=" col-md-8">
            <div class="row">
                <div class=" col-md-6">
                    <?= SiteController::actionGet_input_select2($form, $model, 'idtipo', 'cmb_tipo_articulo', $array_tipos, 'id_configuracion', 'descripcion', 'Tipo', 'seleccione articulo...') ?>
                </div>
                <div class=" col-md-6">
                    <?= SiteController::actionGet_input_select2($form, $model, 'idmarca', 'cmb_tipo_marca', $array_marca, 'id_configuracion', 'descripcion', 'Marca', 'seleccione marca...') ?>
                </div>
            </div>
            <div class="row">
                <div class=" col-md-6">
                    <?= $form->field($model, 'modelo')->textInput() ?>
                </div>
                <div class=" col-md-6">
                    <?= SiteController::actionGet_input_select2($form, $model, 'idrubro', 'cmb_tipo_rubro', $array_rubro, 'id_configuracion', 'descripcion', 'Rubro', 'seleccione rubro...') ?>
                </div>
            </div>
            <div class="row">
                <div class=" col-md-6">
                    <?= SiteController::actionGet_input_select2($form, $model, 'id_unidad_medida', 'cmb_tipo_unidad_de_medida', $array_unidad_de_medida, 'id_configuracion', 'descripcion', 'Unidad_de_Medida', 'seleccione unidad...') ?>
                </div>
                <div class=" col-md-4" style="padding-top:30px;">
                    <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
                </div>

            </div>

            <div class="row">


                <div class=" col-md-12">
                    <?= $form->field($model, 'descripcion')->textInput() ?>
                </div>


            </div>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'imageFile')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'initialPreview' => $model->imagen ? [Yii::$app->request->baseUrl . '/img/articulos/' . $model->imagen] : [],
                    'initialPreviewAsData' => true,
                    'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png'],
                    'showPreview' => true,
                    'showCaption' => false,
                    'showRemove' => true,
                    'showUpload' => false,
                    'maxFileSize' => 2000,
                ],
            ]); ?>
        </div>

    </div>




    <?php ActiveForm::end(); ?>

</div>