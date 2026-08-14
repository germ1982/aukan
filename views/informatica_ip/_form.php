<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InformaticaIp */
/* @var $form yii\widgets\ActiveForm */

$mascaras = Configuracion::find()->where(['id_configuracion_tipo' => ConfiguracionTipo::TIPO_MASCARA_RED])->all();
$puertas = Configuracion::find()->where(['id_configuracion_tipo' => ConfiguracionTipo::TIPO_PUERTA_ENLACE_RED])->all();
$dnss = Configuracion::find()->where(['id_configuracion_tipo' => ConfiguracionTipo::TIPO_DNS_RED])->all();
?>

<div class="informatica-ip-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'mac')->textInput(['maxlength' => true]) ?>
            
        </div>
        <div class="col-md-4" style="padding-top: 25px;">
            <?= $form->field($model, 'usada')->checkbox() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= SiteController::actionGet_input_select($form,$model,'mascara','cmb_mascara',$mascaras,'id_configuracion','descripcion') ?>
        </div>
        <div class="col-md-4">
            <?= SiteController::actionGet_input_select($form,$model,'puerta_enlace','cmb_puerta_enlace',$puertas,'id_configuracion','descripcion') ?>
        </div>
        <div class="col-md-4">
            <?= SiteController::actionGet_input_select($form,$model,'dns','cmb_dns',$dnss,'id_configuracion','descripcion') ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
