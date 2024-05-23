<?php

use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
date_default_timezone_set('America/Argentina/Buenos_Aires');
/* @var $this yii\web\View */
/* @var $model app\models\Mds_atp_historial */
?>

<div class="mds-atp-historial-view">
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-2">                                 
            <?php 
                $fecha_hora=$model->fecha_hora;
                $dateInput1 = explode('-',$fecha_hora);
                $dateInput2 = $dateInput1[2]; 

                $dia_hora=explode(' ',$dateInput2);
                $dia= $dia_hora[0];
                $hora=$dia_hora[1];
                $fecha_hora2 = $dia.'/'.$dateInput1[1].'/'.$dateInput1[0]; 

                $model->fecha_reg=$fecha_hora2;      
                $model->hora_reg=$hora;
                echo $form->field($model, 'fecha_reg')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ;
            ?>                    
    </div>   
    <div class="col-md-2">                                 
            <?php 
                         
                echo $form->field($model, 'hora_reg')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ;
            ?>                    
    </div>   
    <div class="col-md-3">                                 
            <?php 
                $su_estado=$model->estado_anterior;
                $model->estado_anterior = ($su_estado==1)? 'Inscripto' : (($su_estado==2)?'Rechazado' : (($su_estado==3)?'Pendiente Alta' : (($su_estado==4)?'Aprobado' : "Estado erroneo - N°: " . $model->estado_anterior)));                
                echo $form->field($model, 'estado_anterior')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ;
            ?>                    
    </div>  
    <div class="col-md-3">                                 
            <?php 
                $su_estado=$model->estado_nuevo;
                $model->estado_nuevo = ($su_estado==1)? 'Inscripto' : (($su_estado==2)?'Rechazado' : (($su_estado==3)?'Pendiente Alta' : (($su_estado==4)?'Aprobado' : "Estado erroneo - N°: " . $model->estado_nuevo)));                
                echo $form->field($model, 'estado_nuevo')->textInput(['maxlength' => true,"readOnly"=>true,'style'=>'background-color:#ffffff']) ;
            ?>                    
    </div>                 
</div>    

<?php          
   echo $form->field($model, 'descripcion')->textarea(['rows' => 6,'​readonly' => true])->label('Motivo de cambio de Estado') ;       
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

