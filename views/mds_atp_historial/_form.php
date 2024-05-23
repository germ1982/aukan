<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Mds_atp_solicitud;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_atp_historial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-atp-historial-form">

    <?php $form = ActiveForm::begin(); ?>    
    
    <?php          
       echo $form->field($model, 'descripcion')->textarea(['rows' => 6])->label('Motivo de cambio de Estado') ;       
    ?>
  
	<?php //if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
            <?php
                //echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
            ?>
	    </div>
    <?php //} ?>
    

    <?php ActiveForm::end(); ?>
    
</div>
