<?php

use app\models\Mds_org_contacto;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_franco */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-hor-franco-form">

    <?php $form = ActiveForm::begin(['action' => ['mds_hor_franco/clonar_francos', 'idcontacto' => $model->idcontacto, 'mes' => $model->mes_clonar, 'anio' => $model->anio_clonar], 'id' => $model->formName()]); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'idcontacto_clonar')->widget(Select2::classname(), [

                'data' => ArrayHelper::map(
                    Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                                join sds_com_persona p on p.idpersona=c.idpersona 
                                where legajo is not null and activo and rotativo order by apellido,nombre")->all(),
                    'idcontacto',
                    function ($model) {
                        return ($model->legajo != null ? $model->legajo : "00000") . " - " . $model->nombre . " " . $model->apellido;
                    }
                ),
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->label("Empleado"); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <!-- periodo mes -->
            <?php
            $meses = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');
            echo $form->field($model, 'mes_clonar')->dropDownList($meses);
            ?>
        </div>
        <div class="col-md-4">
            <!-- periodo año -->
            <?php
            $anios = array();
            $anios[] = '';
            $anio_actual = date('Y');
            for ($i = ($anio_actual - 1); $i <= ($anio_actual + 1); $i++) {
                $anios[$i] = $i;
            }
            echo $form->field($model, 'anio_clonar')->dropDownList($anios);
            ?>
        </div>
    </div>

    <?php /* if (!Yii::$app->request->isAjax){  */ ?>
    <div class="form-group">
        <div class="row" style="padding-top: 2%;text-align: right;">
            <div class="col-md-4 col-md-offset-8">
                <div class="form-group">
                    <?= Html::submitButton('Generar Francos', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php /* } */ ?>

    <?php ActiveForm::end(); ?>

</div>
<?php

$ajax = Yii::$app->request->isAjax;
$script = <<<  JS

    $('form#{$model->formName()}').on('beforeSubmit',function(e){        
        var \$form = $(this);
        $.post(
            \$form.attr("action"),
            \$form.serialize()
        )
        .done(function(result){   
            console.log(result);
            if(result >= 1){
                $(\$form).trigger("reset");    
                $('#modal_abm').modal('hide'); 
                e.preventDefault();                
            }else{
                $("#message").html(result);
            }
        }).fail(function(){
            console.log("server error");
        });
       
        return false;
    });
        

JS;

$this->registerJs($script);

?>