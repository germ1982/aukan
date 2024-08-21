<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\Persona;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$persona_nombre = '';
// Suponiendo que el modelo tiene un atributo 'avatar' que guarda el nombre del archivo de la imagen
$initialPreview = [];
$initialPreviewConfig = [];

if (isset($model->idpersona)) {
    $persona = Persona::findOne($model->idpersona);
    $model->documento = $persona->documento;
    $persona_nombre = "$persona->apellido, $persona->nombre";
}

if (isset($model->avatar)) {
    $imagePath = Url::to('img/empleado-foto/' . $model->foto);

    // Agrega la imagen a la vista previa inicial
    $initialPreview = [
        Html::img($imagePath, ['class' => 'file-preview-image', 'alt' => 'Avatar', 'title' => $model->foto, 'width' => '100%', 'height' => 'auto']),
    ];
}



/* @var $this yii\web\View */
/* @var $model app\models\Empleado */
/* @var $form yii\widgets\ActiveForm */
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
<div class="empleado-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-8">

            <div class="row">
                <!-- Linea de busqueda -->
                <div class="col-md-4">
                    <div class="input-group">
                        <?= $form->field($model, 'documento')->textInput([
                            'id' => 'input_dni_persona',
                            'onkeyup' => 'ValidarIngresoDni();',
                            //'disabled' => $generada
                        ])
                            ->label($model->isNewRecord ? 'Buscar Persona' : 'DNI Persona') ?>
                        <span class="input-group-btn" style="padding-top:27px;">
                            <?= SiteController::actionGet_boton_buscar_x_documento(
                                'btn_dni',
                                'Buscar Dni',
                                'datos_persona(0);'
                            ) ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-8" style="padding-top:30px;" id="txt_mensaje"><?= $persona_nombre ?></div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= SiteController::actionGet_input_select2($form,$model, 'iddispositivo', 'cmb_dispositivos',OrganismoDispositivo::get_dispositivos(),'iddispositivo','descripcion', 'Sector','Seleccione Sector...')?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'email')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'legajo')->textInput() ?>
                </div>
            </div>

            <div class="row">

                <div class="col-md-4">
                    <?= $form->field($model, 'cuil')->textInput() ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'telefono')->textInput() ?>
                </div>

                <div class="col-md-2" style="padding-top:30px;">
                    <?= $form->field($model,'fichado')->checkbox(['checked' => true]) ?>
                </div>

            </div>

            <div class="row">

                <div class="col-md-4">
                    <?= SiteController::actionGet_input_select2($form,$model, 'categoria', 'cmb_categorias',Configuracion::get_configuraciones(ConfiguracionTipo::CATEGORIA_LABORAL),'id_configuracion','descripcion', 'Categoria','Seleccione Categoria...')?>
                </div>

                <div class="col-md-8">
                <?= SiteController::actionGet_input_select2($form,$model, 'funcion', 'cmb_funcion',Configuracion::get_configuraciones(ConfiguracionTipo::FUNCION_LABORAL),'id_configuracion','descripcion', 'Funcion','Seleccione Funcion...')?>
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
                    //'uploadUrl' => Url::to(['/mds_atp_solicitud/update']),
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
    </div>


    <div class="row">
        <div class="col-md-3">
            <?= SiteController::actionGet_input_fecha($form, $model, 'ingreso_real', 'fecha_ingreso_real', 'Ingreso Real') ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_fecha($form, $model, 'ingreso_administrativo', 'fecha_ingreso_administrativo', 'Ingreso Administrativo') ?>
        </div>
        <div class="col-md-6">
            <?= SiteController::actionGet_input_select2($form,$model, 'contratacion', 'cmb_contratacion',Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_DE_CONTRATACION),'id_configuracion','descripcion', 'Contratacion','Seleccione Contratacion...')?>
        </div>

    </div>

    <div class="row">


        <div class="col-md-4">
            <?= SiteController::actionGet_input_select2($form,$model, 'afiliacion', 'cmb_afiliacion',Configuracion::get_configuraciones(ConfiguracionTipo::AFILIACION_GREMIAL),'id_configuracion','descripcion', 'Afiliacion Gremial','Seleccione Afiliacion...')?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'antiguedad_legal')->textInput() ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'antiguedad_total')->textInput() ?>
        </div>



        <div class="col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
        </div>
    </div>


</div>

<?php if (!Yii::$app->request->isAjax) { ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php } ?>

<?php ActiveForm::end(); ?>

</div>