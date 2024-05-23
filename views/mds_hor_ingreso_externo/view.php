<?php

use yii\widgets\DetailView;
use app\models\Sds_com_persona;
use app\models\Mds_org_contacto;
use Da\QrCode\Label;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_ingreso_externo */


?>
<?php $form = ActiveForm::begin(); ?>


<div class="row">
    <div class="col-md-4">
        <?= $form
            ->field($persona, 'nombre')
            ->label('Nombre')
            ->textInput(['readonly' => true])
        ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($persona, 'apellido')
            ->label('Apellido')
            ->textInput(['readonly' => true])
        ?>
    </div>
    <div class="col-md-4">
        <?= $form
            ->field($persona, 'documento')
            ->label('Documento')
            ->textInput(['readonly' => true])
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?php
        if ($model->fecha_hora != null) {
            $prueba_esta1 = date('d/m/Y H:i', strtotime(str_replace('-', '/', $model->fecha_hora)));
        }

        echo $form
            ->field($model, 'fecha_hora')
            ->textInput(['readonly' => true, 'endDate' => date('d/m/Y H:i:s'), 'format' => 'dd/mm/yyyy', 'value'=>isset($prueba_esta1)?$prueba_esta1:'',])
            ->label('Fecha de llegada')
        ?>
    </div>
    <div class="col-md-4">
        <?php
        if ($model->fecha_hora_ingreso != null) {
            $prueba_esta2 = date('d/m/Y H:i', strtotime(str_replace('-', '/', $model->fecha_hora_ingreso)));
        }
        echo $form
            ->field($model, 'fecha_hora_ingreso')
            ->label('Fecha de ingreso')
            ->textInput(['readonly' => true, 'endDate' => date('d/m/Y H:i'), 'format' => 'dd/mm/yyyy H:i:s', 'value'=>isset($prueba_esta2)?$prueba_esta2:''])
        ?>
    </div>
    
    <div class="col-md-4">
        <?php
        $tiempo_espera='';
        if ($model->fecha_hora_ingreso != null) {
            $llegada = new DateTime($model->fecha_hora);
            $ingreso = new DateTime($model->fecha_hora_ingreso);

            $band = date_diff($llegada, $ingreso);
            if($band->d > 0){
                $tiempo_espera .= $band->d.' Días ';
            }
            if($band->h > 0){
                $tiempo_espera .= ($band->h<10?'0'.$band->h:$band->h).':';
            }else{
                $tiempo_espera .= '00:';
            }

            if($band->i > 0){
                $tiempo_espera .= ($band->i<10?'0'.$band->i:$band->i).':';
            }else{
                $tiempo_espera .= '00:';
            }

            if($band->s > 0){
                $tiempo_espera .= ($band->s<10?'0'.$band->s:$band->s);
            }else{
                $tiempo_espera .= '00';
            }

        }
        
        echo $form
            ->field($model, 'fecha_hora_ingreso')
            ->label('Tiempo de espera')
            ->textInput(['readonly' => true, 'endDate' => date('d/m/Y H:i:s'), 'format' => 'dd/mm/yyyy', 'value'=>$tiempo_espera]);
        ?>
    </div>
</div>
<div class="row">

    <div class="col-md-4" style="margin-top: 10px;">
        <?=
        $form
            ->field($model, 'observaciones')
            ->label('Observaciones')
            ->textInput(['readonly' => true])
        ?>
    </div>

    <div class="col-md-4" style="margin-top: 10px;">
        <?php
        echo $form
            ->field($model, 'idcontacto')
            ->label('Contacto')
            ->textInput([
                'attrubute' => 'idcontacto',
                'readonly' => true,
                'value' =>   $model->idcontacto != null ? Mds_org_contacto::getAyN($model->idcontacto) : '-Sin asignar-',
            ])
        ?>
    </div>
    <div class="col-md-4" style="margin-top: 10px;">
        <?php
        echo $form
            ->field($model, 'motivo')
            ->label('Contacto')
            ->textInput([
                'attrubute' => 'idcontacto',
                'readonly' => true,
                'value' =>   $model->idcontacto != null ? Mds_org_contacto::getAyN($model->idcontacto) : '-Sin asignar-',
            ])
        ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
