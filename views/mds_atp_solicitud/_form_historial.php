<?php

use app\models\Mds_atp_solicitud;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use yii\web\JsExpression;


use yii\helpers\ArrayHelper;
use app\models\Sds_ent_tipo;
use yii\bootstrap\Collapse;
use yii\bootstrap\Modal;

?>

<div class="mds-atp-solicitud-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">                                 
                <?php echo $form->field($model, 'estado')->        
                dropDownList([
                    Mds_atp_solicitud::INSCRIPTO => 'Inscripto', 
                    Mds_atp_solicitud::RECHAZADO => 'Rechazado', 
                    Mds_atp_solicitud::PENDIENTE_ALTA => 'Pendiente Alta', 
                    Mds_atp_solicitud::APROBADO => 'Aprobado',
                ]); ?>      
        </div>                  
    </div>              
    <div class="row">
            <div class="col-md-12">
                <?php echo $form->field($model, 'desc_historial')->textarea(['rows' => 6]) ->label('Detalle Cambio de Estado'); ?>
            </div>                                       
    </div>
            
  
<?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar Datos', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            
        </div>
    <?php } ?> 
   
    <?php ActiveForm::end(); ?>
</div>
