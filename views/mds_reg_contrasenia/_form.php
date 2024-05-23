<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_reg_contrasenia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-reg-contrasenia-form">
    <?php if($mensaje_success!=''):?>
        <div class="alert alert-success">
            <?=$mensaje_success?>
        </div>
        <?php elseif($mensaje_error!=''):?>
            <div class="alert alert-danger">
            <?=$mensaje_error?>
        </div>
    <?php endif; ?>
<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
        <?= $form->field($model, 'tipo')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_ASIGNACION_IP),
            'idconfiguracion',
            'descripcion'
        ),
        'options' => [
            'placeholder' => '- Seleccionar Tipo Contraseña -'
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'disabled' => false,
            ]
        ]); ?>

        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
        <?= $form->field($model, 'ip')->textInput(['maxlength' => true, 'pattern'=>'^((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])$', 'placeholder'=>'111.111.111.111']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'usuario')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'contrasenia')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'ubicacion')->textInput(['maxlength' => true]) ?>
        </div>
    </div>


    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
