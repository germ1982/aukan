<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_documento;
use app\models\Mds_seg_usuario;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_persona;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_documento */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Cargar Documento'.($model->medicina==1 ? ' de Medicina Laboral':'');
?>
<style>
    .content-body{
        padding-top: 20px;
    }
</style>
<?php if(!Yii::$app->request->isAjax):?>
    <header class="page-header">
        <h2><?= $this->title ?></h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.html">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span><?= $this->title ?></span></li>
            </ol>

            <div class="sidebar-right-toggle"></div>
        </div>
    </header>
<?php endif; 

//Alerts Success y Error:
if (Yii::$app->session->hasFlash('save_documento')) : ?>
    <div class="alert alert-success alert-dismissable" id="alert-save">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
        <b><?= Yii::$app->session->getFlash('save_documento') ?></b>
    </div>
<?php endif;

if (Yii::$app->session->hasFlash('fail_save_documento')) : ?>
    <div class="alert alert-danger alert-dismissable" id="alert-fail-save">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fas fa-times"></i> ¡Por favor intente nuevamente!</h4>
        <b><?= Yii::$app->session->getFlash('fail_save_documento') ?></b>
    </div>
<?php endif;?>


<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div id="form_principal" class="mds-org-documento-form">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <?php if (!$model->isNewRecord) : ?>
                            <div class="col-md-6">
                                <h5><b>Usuario: </b>
                                    <?php 
                                    $usuario = Mds_seg_usuario::findOne($model->idusuario);
                                    if($usuario != null){
                                        echo $usuario->apellido.' '.$usuario->nombre;
                                    }
                                    ?>
                                </h5>
                            </div>
                        <?php endif; ?>
                    </div>
                                
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            $fecha_desde = date_format(date_create(), 'd-m-Y');
                            if ($model->fecha != null) {
                                $model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha)));
                            }
                            ?>
                            <?= $form->field($model, 'fecha')->widget(DatePicker::class, [
                                'name' => 'check_issue_date',
                                'language' => 'es',
                                'readonly' => false,
                                'layout' => '{picker}{input}{remove}',
                                'options' => [
                                    'id' => 'fecha',
                                    'class' => 'form-control input-md',
                                    'disabled' => false
                                ],
                                'pluginOptions' => [
                                    'value' => null,
                                    'format' => 'dd-mm-yyyy',
                                    'startDate' => $fecha_desde,
                                    'endDate' => date('d-m-Y'),
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                ]
                            ]);
                            ?>

                            <?= $form->field($model, 'idcontacto')->widget(Select2::class, [
                                'data' => ArrayHelper::map(
                                    Mds_org_contacto::find()->all(), //el order debe ser asi'apellido' => SORT_ASC, 'nombre' => SORT_ASC etc
                                    'idcontacto', //Aca siempre tiene que ir el id a buscar en el search
                                    function ($model) {
                                        if ($model->idcontacto != null) {
                                            $contacto = Mds_org_contacto::findOne($model->idcontacto);
                                            if ($contacto->idpersona != null) {
                                                $persona = Sds_com_persona::findOne($contacto->idpersona);
                                                if($persona!=null){
                                                    return $persona->apellido.", ".$persona->nombre." - Legajo: ".$contacto->legajo;
                                                }
                                                return '-Error-';
                                            }
                                        }
                                        return "";
                                    }
                                ),
                                'options' => ['placeholder' => 'Seleccionar Contacto ...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Contacto');
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'tipo')->dropdownList(
                                        ArrayHelper::map(
                                            Sds_com_configuracion::getConfiguraciones(
                                                ($model->medicina==0 ? Sds_com_configuracion_tipo::TIPO_CONTACTO_DOCUMENTO_TIPO : Sds_com_configuracion_tipo::DOC_MEDICINA_LABORAL)),
                                            'idconfiguracion',
                                            'descripcion'
                                        ),
                                        [
                                            'prompt' => '- Seleccionar Tipo -',
                                            'id' => 'tipo_documento'
                                        ]
                                    );
                                    ?>
                                </div>
                                
                                <!-- ------------------------------------------------------------------------------------------------------------------------- -->
                                <div class="col-md-1" style="padding-top:27px">
                                    <?php $aux = Sds_com_configuracion_tipo::TIPO_CONTACTO_DOCUMENTO_TIPO; ?>
                                    <?php
                                    /* Html::Button('+', [
                                        'id' => 'boton_nueva_asignacion',
                                        'class' => 'btn btn-default',
                                        'onclick' => 'js:MostrarDivNuevaConfiguracion("' . $aux . '","Nuevo tipo de documentación","tipo_documento","form_principal");'
                                    ]); */
                                    ?>
                                </div>
                                <!-- ------------------------------------------------------------------------------------------------------------------------- -->
                            </div>
                                
                            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
                                
                            <?php if($model->medicina==1): ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'estado')->dropdownList(
                                            ArrayHelper::map(
                                                Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_ESTADO_DOCUMENTO),
                                                'idconfiguracion',
                                                'descripcion'
                                            ),
                                            [
                                                'prompt' => '- Seleccionar Estado -',
                                                'id' => 'tipo_documento'
                                            ]
                                        );
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if(Yii::$app->request->isAjax):?>
                                <!-- Creado para recargar estilos que generan conflicto al abrir modal -->
                                <span id='refresh-modal' class="btn btn-info pull-right"><i class="fas fa-sync"></i></span><br>
                            <?php endif; ?>
                            <div>
                                <?php if ($model->path == null) : ?>
                                    <?= $form->field($model, 'temp_archivo_adjunto', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                        ->widget(FileInput::class, [
                                            'options' => ['accept' => 'image/*,.pdf, .odt, .ods, .doc, .docx, .xls, .xlsx'],
                                            'language' => 'es',
                                            'pluginOptions' => [
                                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf', 'odt', 'ods', 'doc', 'docx', 'xls', 'xlsx'],
                                                'showCaption' => false,
                                                'showRemove' => true,
                                                'showUpload' => false,
                                                'showClose' => false,
                                                'mainClass' => 'input-group-sm',
                                                'uploadUrl' => Url::to(['/mds_org_documento/update']),
                                                'maxFileSize' => 1000000000,
                                                'previewFileType' => 'file',
                                                'initialCaption' => false,
                                                'fileActionSettings' => [
                                                    'showRemove' => true,
                                                    'showUpload' => false,
                                                ]
                                            ],
                                        ]);                                    
                                    ?>
                                <?php else : ?>
                                    <?php
                                    $contacto  = Mds_org_contacto::findOne($model->idcontacto);
                                    $persona = Sds_com_persona::findOne($contacto->idpersona);
                                    echo $form->field($model, 'temp_archivo_adjunto', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                        ->widget(FileInput::class, [
                                            'options' => ['accept' => 'image/*,.pdf, .odt, .ods, .doc, .docx, .xls, .xlsx'],
                                            'language' => 'es',
                                            'pluginOptions' => [
                                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp', 'pdf', 'odt', 'ods', 'doc', 'docx', 'xls', 'xlsx'],
                                                'showCaption' => false,
                                                'showRemove' => true,
                                                'showUpload' => false,
                                                'showClose' => false,
                                                'mainClass' => 'input-group-sm',
                                                'uploadUrl' => Url::to(['/mds_org_documento/update']),
                                                'maxFileSize' => 1000000000,
                                                'previewFileType' => 'file',
                                                'initialPreview' => [
                                                    Url::to('@web/' . $model->path, true), ['class' => 'file-preview-image', 'style' => 'width:100%']
                                                ],
                                                'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                                'initialPreviewFileType' => Mds_org_documento::getExtension($model->path), // image is the default and can be overridden in config below
                                                'overwriteInitial' => true,
                                                'autoReplace' => true,
                                                'fileActionSettings' => [
                                                    'showRemove' => false,
                                                    'showUpload' => false,
                                                ]
                                            ],
                                            'pluginEvents' => [
                                                "fileclear" => "function() { console.log('fileclear'); $('#borrar').val(true);}",
                                                "filereset" => "function() {  }",
                                            ]
                                        ]);
                                    
                                    ?>
                                <?php endif; ?>
                                <?= $form->field($model, 'borrar_adjunto')->hiddenInput(['id' => 'borrar'])->label(false) ?>
                            </div>
                        </div>
                                    
                    </div>
                                    
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'detalle')->textarea(['rows' => 10]) ?>
                        </div>
                    </div>
                    <?php if (!Yii::$app->request->isAjax) { ?>
                        <br>
                        <div class="form-group">
                            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => 'col-md-6 col-md-offset-3 '.($model->isNewRecord ? 'btn btn-success' : 'btn btn-primary')]) ?>
                        </div>
                    <?php } ?>
                    
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    function ordenarSelect(id_componente) {
        //alta burbuja que encontre en la internet
        var selectToSort = jQuery('#' + id_componente);
        var optionActual = selectToSort.val();
        selectToSort.html(selectToSort.children('option').sort(function(a, b) {
            return a.text === b.text ? 0 : a.text < b.text ? -1 : 1;
        })).val(optionActual);
    }
</script>

<?php
$script = <<<  JS
$(document).ready(function(){
    //Para que al abrir la imagen en modal no genere conflicto de scroll con el modal de formulario update
    $("#ajaxCrudModal").css({'overflow-x': 'hidden', 'overflow-y': 'auto'});
    //Remplazo las class de los botones del FileInput para cargar bien los iconos
    $('.bi-zoom-in').addClass('fas fa-search-plus');
    $('.bi-trash').addClass('fas fa-trash');
    $('.bi-x-lg').addClass('fas fa-window-close');
    $("#refresh-modal").click(function(){
        $("#ajaxCrudModal").css({'overflow-x': 'hidden', 'overflow-y': 'auto'});
    });
    
    //Ocultar automaticamente los alert de error o success:
    if($('#alert-save').html()!=undefined){
        setTimeout(() => {
            $('#alert-save').css('display', 'none');
        }, 1500);
    }
    if($('#alert-fail-save').html()!=undefined){
        setTimeout(() => {
            $('#alert-fail-save').css('display', 'none');
        }, 1500);
    }
});
JS;
$this->registerJs($script);
?>