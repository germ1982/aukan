<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Sds_gis_capa_item;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_interno */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-reg-interno-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'idinterno')->textInput(['type'=>'number'])->label('Nº Interno') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'grupo')->textInput(['type'=>'number'])->label('Grupo') ?>
        </div>
        <div class="col-md-3" style="padding-top: 30px;">
            <?= $form->field($model, 'recepcion')->checkbox(['checked' => ($model->recepcion ? true:false)]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'edificio')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Sds_gis_capa_item::find()->where(['like', 'descripcion', 'subsecretaria'])->orderBy(['descripcion' => SORT_ASC])->all(),
                    'idcapaitem',
                    'descripcion'
                ),
                'options' => [
                    'placeholder' => 'Seleccionar edificio ...',
                    'id'=>'edificio',
                ],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->label('Edificio');
            ?>
        </div>
    </div>
    <div class="row">
        <?php
        if(isset($model->idcapaitem) && isset($model->iddispositivo)){
            $dataOrg=ArrayHelper::map(
                Mds_org_dispositivo::find()
                    ->select('o.idorganismo, o.descripcion')
                    ->from('mds_org_dispositivo d')
                    ->innerJoin('mds_org_organismo o', 'd.idorganismo=o.idorganismo')
                    ->where('idcapaitem='.$model->idcapaitem)
                    ->groupBy('descripcion')
                    ->orderBy('descripcion ASC')->all(),
                'idorganismo',
                'descripcion'
            );
            $dataDispo=ArrayHelper::map(
                Mds_org_dispositivo::find()
                    ->where('idorganismo='.($model->organismo !=null ? $model->organismo:-1).' AND idcapaitem='.$model->idcapaitem)
                    ->orderBy(['descripcion' => SORT_ASC])->all(),
                'iddispositivo',
                'descripcion'
            );
        }else{
            $dataOrg='';
            $dataDispo='';
        }
        ?>
        <div class="col-md-6">
            <?= $form->field($model, 'organismo')->widget(Select2::class, [
                'data' => $dataOrg,
                'options' => [
                    'placeholder' => 'Seleccionar organismo...',
                    'id'=>'organismo',
                    'disabled'=>$model->isNewRecord,
                ],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->label('Organismo');
            ?>
        </div>


        <div class="col-md-6">
            <?= $form->field($model, 'iddispositivo')->widget(Select2::class, [
                'data' => $dataDispo,
                'options' => [
                    'placeholder' => 'Seleccionar dispositivo ...',
                    'id'=>'dispositivo',
                    'disabled' => $model->isNewRecord,
                ],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->label('Dispositivo');
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'idcontacto')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Mds_org_contacto::find()->select('idcontacto, p.nombre, p.apellido')->innerJoin('sds_com_persona p', 'mds_org_contacto.idpersona=p.idpersona')->all(),
                    function ($model) {
                        return $model['idcontacto'];
                    },
                    function ($model) {
                        return $model['nombre'] .' '. $model['apellido'];
                    },
                ),
                'options' => [
                    'placeholder' => 'Seleccionar contacto referente...',
                    'id'=>'idcontacto'
                ],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->label('Contacto');
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'responsable')->textInput(['maxlength' => true, 'id'=>'responsableInput'])->label('Contacto Referente') ?>
        </div>
    </div>


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<?php
$script = <<<  JS
$('#edificio').change(
    function(){
        var idcapaitem=$("#edificio option:selected").val();
        $.post("index.php?r=sds_reg_interno/cmb_organismo&idcapaitem="+idcapaitem, function(data) {
            $("#organismo").prop("disabled", false);
            $("#organismo").html(data);
            $("#organismo").val(null);
            $("#dispositivo").html('');
            $("#dispositivo").val('');
        });
    }
);

$('#organismo').change(
    function(){
        var idcapaitem=$("#edificio option:selected").val();
        var idorganismo=$("#organismo option:selected").val();
        $.post("index.php?r=sds_reg_interno/cmb_dispositivo&idcapaitem="+idcapaitem+'&idorganismo='+idorganismo, function(data) {
            $("#dispositivo").prop("disabled", false);
            $("#dispositivo").html(data);
        });
    }
);

$('#idcontacto').change(
    function(){
        var newValor=$("#idcontacto option:selected").text();
        $('#responsableInput').val(newValor);
        console.info(newValor);
    }
);

JS;

$this->registerJs($script);

?>