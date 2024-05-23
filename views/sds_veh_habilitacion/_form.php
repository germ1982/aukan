<?php

use app\models\Sds_com_configuracion_tipo;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_veh_habilitacion */
/* @var $form yii\widgets\ActiveForm */

//Alerts Success y Error:
if(Yii::$app->session->hasFlash('success')) : ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
        <b><?= Yii::$app->session->getFlash('success') ?></b>
    </div>
<?php endif;
if (Yii::$app->session->hasFlash('faild')) : ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fas fa-times"></i> ¡UPS!</h4>
        <b><?= Yii::$app->session->getFlash('faild') ?></b>
    </div>
<?php endif;?>

<div class="sds-veh-habilitacion-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'tipo')->widget(Select2::class, [
                'data' => $filter['htipo'],
                'options' => [
                    'id' => 'config_' . Sds_com_configuracion_tipo::VEH_HABILITACION_TIPO,
                    'placeholder' => 'Seleccione...'
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ])->label('Tipo de Habilitación')?>
        </div>
        <div class="col-md-6">
            <?php $model->isNewRecord?'':$model->vencimiento = date('d/m/Y', strtotime($model->vencimiento));?>
            <?= $form->field($model, 'vencimiento')->widget(DatePicker::class, [
                'language' => 'es',
                'readonly' => true,
                'layout' => '{picker}{input}{remove}',
                'options' => [
                    'class' => 'form-control input-md',
                    'disabled' => false
                ],
                'pluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
                    ]
            ]);  ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'detalle')->textarea(['rows' => 18]) ?>
        </div>
        <div class="col-md-6" style="padding:0;">
            <?php if($model->adjunto==null):?>
                <?= $form->field($model, 'temp_file', ['enableClientValidation' => true,'enableAjaxValidation' => false])
                    ->widget(FileInput::class, [
                        'options' => [
                            'accept' => 'image/*,.pdf',
                        ],
                        'language' => 'es',
                        'pluginOptions' => [
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf'],
                            'showCaption' => false,
                            'showRemove' => true,
                            'showUpload' => false,
                            'showClose' => false,
                            'mainClass' => 'input-group-sm',
                            'maxFileSize' => 1000000000,
                            'previewFileType' => 'file',
                            'initialCaption' => false,
                            'fileActionSettings' => [
                                'showRemove' => true,
                                'showUpload' => false,
                            ]
                        ]
                    ]);?>
            <?php else:?>
                <?= $form->field($model, 'temp_file', ['enableClientValidation' => true,'enableAjaxValidation' => false])
                    ->widget(FileInput::class, [
                    'options' => [
                        'accept' => 'image/*,.pdf',
                    ],
                    'language' => 'es',
                    'pluginOptions' => [
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf'],
                        'showCaption' => false,
                        'showRemove' => true,
                        'showUpload' => false,
                        'showClose' => false,
                        'mainClass' => 'input-group-sm',
                        'maxFileSize' => 1000000000,
                        'previewFileType' => 'file',
                        'fileActionSettings' => [
                            'showRemove' => true,
                            'showUpload' => false,
                        ],
                        'initialPreview' => $model->adjunto!=null?[
                            Url::to("@web/uploads/veh_habilitacion/veh_$model->idvehiculo/$model->adjunto",true),
                            [
                                'class' =>'file-preview-image',
                                'style' =>'width:100%',
                            ],
                        ] : false,
                        'initialPreviewAsData' => true,
                        'initialPreviewFileType' => $model->adjunto!=null?explode('.',$model->adjunto)[1]:'',//Obtengo la extension del archivo
                        'overwriteInitial' => true,
                        'autoReplace' => true,
                        'initialCaption' =>$model->adjunto,
                        'fileActionSettings' => [
                            'showRemove' => true,
                            'showUpload' => false,
                        ],
                    ],
                    'pluginEvents' => [
                        'fileclear' => "function() { console.log('fileclear'); $('#delete_file').val(true);}",
                        'filereset' => 'function() {  }',
                    ],
                ])?>
                <?= $form->field($model, 'delete_file')->hiddenInput([
                        'id' => 'delete_file',
                        ])->label(false)
                ?>
      <?php endif; ?>
        </div>
    </div>
	    <?php if (!Yii::$app->request->isAjax){ ?>
	      	<div class="form-group">
	            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	        </div>
	    <?php } ?>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $(document).ready(function(){
        $('.input-group-sm').css('content-align', 'center');
    });
</script>