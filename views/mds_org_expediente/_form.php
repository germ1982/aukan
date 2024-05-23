<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_expediente */
/* @var $form yii\widgets\ActiveForm */

function GetFechaActual()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $mydate=getdate(date("U"));
        
        $dia = $mydate['mday'];
        if($dia<=9)
            {$dia = '0'.$dia;}

        $mes = $mydate['mon'];
        if ($mes<=9)
            {$mes='0'.$mes;}

        $hora = $mydate['hours'];
            if($hora<=9)
                {$hora = '0'.$hora;}

        $minuto = $mydate['minutes'];
            if ($minuto<=9)
                {$minuto='0'.$minuto;}

        $Fecha = "$dia/$mes/$mydate[year]";
        //echo "$mydate[mday]/$mydate[mon]/$mydate[year]";
        return $Fecha;
    }

?>


<div class="mds-org-expediente-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">

            <?php
                    if ($model->fecha_ingreso != null) 
                        {
                            $ban = 1;
                            $fecha_ingreso = $model->fecha_ingreso;
                            $model->fecha_ingreso = date('d/m/Y', strtotime(str_replace('/', '-', $fecha_ingreso)));
                        }
                    else
                        {
                            $ban =0;
                            $model->fecha_ingreso = date('d/m/Y', strtotime(str_replace('/', '-', GetFechaActual())));
                        } 

                    echo $form->field($model, 'fecha_ingreso')->widget(DatePicker::ClassName(), [
                        'name' => 'check_issue_date',
                        'language' => 'es',
                        'readonly' => false,
                        'layout' => '{picker}{input}{remove}',
                        'disabled' => false,
                        'options' => [
                            'class' => 'form-control input-md',
                            'placeholder' => 'DD / MM / YYYY',
                            'label' => 'Fecha Ingreso',
                        ],
                        'pluginOptions' => [
                            'value' => null,
                            'format' => 'dd/mm/yyyy',
                            'endDate' => date('d/m/Y'),
                            'todayHighlight' => true,
                            'autoclose' => true,
                        ]
                    ])->label('Fecha Ingreso');
                ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'expediente')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'pedido_numero')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'gde')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-8">
            <?= $form->field($model, 'causante')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'extracto')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?php
                    if ($model->fecha_salida != null) 
                        {
                            $ban = 1;
                            $fecha_salida = $model->fecha_salida;
                            $model->fecha_salida = date('d/m/Y', strtotime(str_replace('/', '-', $fecha_salida)));
                        }
                    else
                        {
                            $ban =0;
                            $model->fecha_salida = date('d/m/Y', strtotime(str_replace('/', '-', GetFechaActual())));
                        } 

                    echo $form->field($model, 'fecha_salida')->widget(DatePicker::ClassName(), [
                        'name' => 'check_issue_date',
                        'language' => 'es',
                        'readonly' => false,
                        'layout' => '{picker}{input}{remove}',
                        'disabled' => false,
                        'options' => [
                            'class' => 'form-control input-md',
                            'placeholder' => 'DD / MM / YYYY',
                            'label' => 'Fecha Salida',
                        ],
                        'pluginOptions' => [
                            'value' => null,
                            'format' => 'dd/mm/yyyy',
                            'endDate' => date('d/m/Y'),
                            'todayHighlight' => true,
                            'autoclose' => true,
                        ]
                    ])->label('Fecha Salida');
                ?>
        </div>
        <div class="col-md-9">
            <?= $form->field($model, 'destino')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    

    

    


    

    

    

    


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
