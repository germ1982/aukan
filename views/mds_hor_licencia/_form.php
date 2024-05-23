<?php
use app\models\Mds_org_contacto;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_licencia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-hor-licencia-form">
    <?php
        foreach(Yii::$app->session->getAllFlashes() as $key => $message):?>
            <div class="alert alert-<?=$key?> alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-check"></i> <?=$key=='success' ? '¡Excelente!':'¡Ups!'?></h4>
                <b><?= $message ?></b>
            </div>
<?php   endforeach;?>
    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
        <?= $form->field($model, 'idcontacto')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Mds_org_contacto::findBySql("SELECT * FROM mds_org_contacto c 
                        join sds_com_persona p on p.idpersona=c.idpersona")
                        ->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])->all(),
                    'idcontacto',
                    function ($model) {
                        return $model->apellido . " " .$model->nombre.' - Leg.: '.$model->legajo;
                    }
                ),
                'options' => ['placeholder' => 'Seleccionar Persona...', 'disabled' => false],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Persona');?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'desde')->widget(DatePicker::class, [
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}{remove}',
                'options' => [
                    'id' => 'fecha_desde',
                    'class' => 'form-control input-md',
                    
                ],
                'pluginOptions' => [
                    'value' => null,
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
                    
                ]
            ])->label('Fecha desde');
            ?>
        </div>
        <div class="col-md-5">
        <?= $form->field($model, 'hasta')->widget(DatePicker::class, [
                'language' => 'es',
                'layout' => '{picker}{input}{remove}',
                'options' => [
                    'id' => 'fecha_hasta',
                    'class' => 'form-control input-md',
                    
                ],
                'pluginOptions' => [
                    'value' => null,
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
                    
                ]
            ])->label('Fecha hasta');
            ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'cantidad_dias')->textInput(['id'=>'cant_dias','readonly'=>true])->label('Cant. Días') ?>
        </div>
    </div>


    <?= $form->field($model, 'detalle')->textarea(['rows' => 6]) ?>
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<<  JS
$(document).ready(function(){
    $('#fecha_desde').change(function(){
        var desde=$(this).val();
        var hasta=$('#fecha_hasta').val();
        var diff=calcular_dias(desde, hasta);
        if(desde!='' && hasta!=''){
            var diff=calcular_dias(desde, hasta);
        }
    });

    $('#fecha_hasta').change(function(){
        var desde=$('#fecha_desde').val();
        var hasta=$(this).val();
        if(desde!='' && hasta!=''){
            var diff=calcular_dias(desde, hasta);
        }
    });
});

function calcular_dias(desde, hasta){
    var d=desde.split('/');
    var fd=d[2]+'-'+d[1]+'-'+d[0];
    var fechaDesde = new Date(fd).getTime();

    var h=hasta.split('/');
    var fh=h[2]+'-'+h[1]+'-'+h[0];
    var fechaHasta = new Date(fh).getTime();
    
    if(fechaHasta>=fechaDesde){
        var diff = parseInt((fechaHasta - fechaDesde)/(1000 * 60 * 60 * 24))+1;//A la resta de las fechas la divido con los milisegundos para convertir en días y le sumo uno, porque la fecha desde es inclusive
    }else{
        var diff='Revisar Fechas';
    }
    $('#cant_dias').css('padding-left', '6px');
    $('#cant_dias').val(diff);
    return diff;
}
JS;
$this->registerJs($script);
?>