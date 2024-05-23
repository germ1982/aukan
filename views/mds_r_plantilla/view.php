<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_gis_capa;
use app\models\Mds_r_plantilla;

use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_r_plantilla */
?>

<?php $form = ActiveForm::begin(); ?>
<div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">

<div class="row"> <!-- input-tipo-plantilla -->

<div class="col-md-12">

<?php
                  $una_conf=Sds_com_configuracion::find()
                  ->where(['idconfiguracion' => $model->idtipoplantilla])  
                  ->orderBy(['descripcion' => SORT_ASC])                        
                  ->one();

                  $model->idtipoplantilla=   $una_conf->  descripcion; 

  ?>
   <?= $form->field($model, 'idtipoplantilla')->textInput([
                          'maxlength' => true,'readOnly' => true,
                          'style' => 'background-color:#ffffff',])->label("Tipo plantilla") ?>
</div>

</div> <!-- fin input-tipo-plantilla -->

   <div class="row"> <!-- input-variable-diagnostico -->
        <div class="col-md-12">
            
        <?php
                        $una_conf=Sds_com_configuracion::find()
                        ->where(['idconfiguracion' => $model->variable_diagnostico])  
                        ->orderBy(['descripcion' => SORT_ASC])                        
                        ->one();

                        $model->variable_diagnostico=   $una_conf->  descripcion;
        ?>

          <?= $form->field($model, 'variable_diagnostico')->textInput([
                                'maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Variable diagnóstico") ?>
        </div>
   </div> <!-- fin input-variable-diagnostico --> 

   <div class="row"> <!-- input-dimension -->
      <div class="col-md-12">

      <?php
                        $una_conf=Sds_com_configuracion_tipo::find()
                        ->where(['idconfiguraciontipo' => $model->dimension])  
                        ->orderBy(['descripcion' => SORT_ASC])                        
                        ->one();

                        $model->dimension=   $una_conf->  descripcion; 

        ?>
         <?= $form->field($model, 'dimension')->textInput([
                                'maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Dimension") ?>
      </div>
   </div> <!-- fin input-dimension -->

   <div class="row"> 
      <div class="col-md-4"> <!-- input-origen -->

      <?php
                        $una_conf=Sds_com_configuracion::find()
                        ->where(['idconfiguracion' => $model->origen])  
                        ->orderBy(['descripcion' => SORT_ASC])                        
                        ->one();
                        $idorigen=$model->origen;
                        $model->origen=   $una_conf->  descripcion; 

        ?>
         <?= $form->field($model, 'origen')->textInput([
                                'maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Origen") ?>
      </div>

      <div class="col-md-8"> <!-- input-gis_capa -->

      <?php
                        $una_conf=Sds_gis_capa::find()
                        ->where(['idcapa' => $model->id_gis_capa])  
                        ->orderBy(['descripcion' => SORT_ASC])                        
                        ->one();
                        if($una_conf != null){
                           $model->id_gis_capa=   $una_conf->  descripcion; 
                        }        

        ?>
         <?= $form->field($model, 'id_gis_capa')->textInput([
                                'maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Capa") ?>
      </div>
   </div> <!-- fin row-input-origen -->

   <!-- <div class="row" style="display:<?= $idorigen == Mds_r_plantilla :: CONST_DISP?"block":"none"?>"> -->

   <div class="row"> <!-- input-fecha-hora-creacion -->
      <div class="col-md-5">
         <?php
                      $fecha=$model->fechahoracreate;   
                      $sep = explode(' ',$model->fechahoracreate);  
                      $fecha = explode('-',$sep[0]);  
                      $hora = $sep[1];  
                      $fecha_final = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
                      $model->fechahoracreate=$fecha_final.' '.$hora;
         ?>
         <?= $form->field($model, 'fechahoracreate')->textInput([
                                'maxlength' => true,'readOnly' => true,
                                'style' => 'background-color:#ffffff',])->label("Fecha y hora de creación") 
                                ?>
      </div>
   </div> <!-- fin input-fecha-hora-creacion -->


</div> 
<?php ActiveForm::end(); ?>
