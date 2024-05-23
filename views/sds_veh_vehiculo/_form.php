<?php

use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_veh_modelo;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_veh_vehiculo */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="sds-veh-vehiculo-form">
    <?php $form = ActiveForm::begin(); ?>
    <?php if (Yii::$app->session->hasFlash('success')) : ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif;
    if (Yii::$app->session->hasFlash('fail')) : ?>
        <div class="alert alert-danger alert-dismissable" id="alert-fail-save">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fas fa-times"></i> ¡UPS! Algo no esta bien...</h4>
            <?= Yii::$app->session->getFlash('fail') ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-4" style="padding:0;">
            <div class="col-md-2">
                Marca:
            </div>
            <div class="col-md-8" style="padding-right:0px">
                <?= $form->field($model, 'marca')->widget(Select2::class, [
                    'data' => $filter['marca'],
                    'options' => [
                        'id' => 'config_' . Sds_com_configuracion_tipo::VEH_MARCA,
                        'placeholder' => 'Seleccione...',
                        'tabIndex' => '1'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(false) ?>
            </div>
            <div class="col-md-1" style="padding:0px">
                <span class="input-group-btn" id="parent-new-marca">
                    <?=
                    Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                        'value' => Url::to(['//sds_com_configuracion/create_ext', 'tipo' => Sds_com_configuracion_tipo::VEH_MARCA]),
                        'class' => 'btn btn-success btn-flat',
                        'id' => 'btn_new_marca',
                        'tabIndex' => '-1',
                        'onclick' => '$("#modal_abm").modal("show")
                            .find("#content_abm")
                            .load($(this).attr("value"));
                        $("#header_abm").html("Cargar Marca");'
                    ]);
                    ?>
                </span>
            </div>
        </div>
        <div class="col-md-4" style="padding:0;">
            <div class="col-md-3" style="padding-right:0px">
                Modelo:
            </div>
            <div class="col-md-8" style="padding:0px">
                <?= $form->field($model, 'modelo')->widget(Select2::class, [
                    'options' => [
                        'id' => "modelo_marca",
                        'placeholder' => 'Seleccionar Modelo...',
                        'tabIndex' => '1',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(false) ?>
            </div>
            <div class="col-md-1" style="padding:0px">
                <span class="input-group-btn" id="parent-new-modelo">
                    <?=
                    Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                        'value' => Url::to(['//sds_veh_modelo/create']),
                        'class' => 'btn btn-success btn-flat',
                        'id' => 'btn_new_modelo',
                        'tabIndex' => '-1',
                        'onclick' => '$("#modal_abm").modal("show")
                                    .find("#content_abm")
                                    .load($(this).attr("value"));
                                $("#header_abm").html("Cargar modelo de vehículo");'
                    ]);
                    ?>
                </span>
            </div>
        </div>
        <div class="col-md-4" style="padding-left:30px;">
            <div class="col-md-3">
                Dominio:
            </div>
            <div class="col-md-9">
                <?= $form->field($model, 'dominio')->textInput(['maxlength' => true])->label(false) ?>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-4" style="padding:0;">
            <div class="col-md-2" style="padding-right:0px">
                Tipo:
            </div>
            <div class="col-md-8" style="margin-right: 0px; padding-right:0px">
                <?= $form->field($model, 'tipo')->widget(Select2::class, [
                    'data' => $filter['tipo'],
                    'options' => [
                        'id' => 'config_' . Sds_com_configuracion_tipo::VEH_TIPO,
                        'placeholder' => 'Seleccione Tipo de Vehículo...',
                        'tabIndex' => '1',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(false) ?>
            </div>
            <div class="col-md-1" style="padding:0px">
                    <span class="input-group-btn" id="parent-new-tipo">
                        <?= Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                            'value' => Url::to(['//sds_com_configuracion/create_ext', 'tipo' => Sds_com_configuracion_tipo::VEH_TIPO]),
                            'class' => 'btn btn-success btn-flat',
                            'id' => 'btn_new_tipo', 'tyle' => 'margin-top:27px',
                            'tabIndex' => '-1',
                            'onclick' => '
                    $("#modal_abm").modal("show")
                    .find("#content_abm")
                    .load($(this).attr("value"));
                    $("#header_abm").html("Cargar Tipo");'
                        ]);
                        ?>
                    </span>
                </div>
        </div>
        <div class="col-md-4" style="padding:0;">
            <div class="col-md-3" style="padding-right:0px">
                Estado:
            </div>
            <div class="col-md-9" style="padding:0px">
                <?= $form->field($model, 'estado')->widget(Select2::class, [
                    'data' => $filter['estado'],
                    'options' => [
                        'placeholder' =>
                        'Seleccione Estado',
                        'tabIndex' => '1',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ])->label(false) ?>
            </div>
        </div>

        <div class="col-md-4" style="padding-left:30px;">
            <div class="col-md-2">
                Año:
            </div>
            <div class="col-md-5">
                <?= $form->field($model, 'anio')->textInput([
                    'type' => 'number'
                ])->label(false) ?>
            </div>
            <div class="col-md-5" style="padding-top:5px;">
                <?= $form->field($model, 'alquilado')->checkBox(['selected' => false])->label(false) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'detalle')->textarea()->label("Detalles") ?>
        </div>
    </div>
</div>
<?php if (!Yii::$app->request->isAjax) { ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php } ?>
<?php ActiveForm::end(); ?>

</div>
<?php Modal::begin([
    'header' => '<h4 id="header_abm"></h4>',
    "id" => "modal_abm",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    "footer" => "", // always need it for jquery plugin
]);
echo "<div id='content_abm'></div>";
Modal::end();

$script = <<<JS
$(document).ready(function(){
    /* $('#config_158') select de marcas, se harcodea el idtipoconfiguracion asociado*/
    if($('#config_158').val()!=''){
        getModelos($('#config_158').val());
    }else{
        $("#btn_new_modelo").attr('disabled', true);
        $("#modelo_marca").attr('disabled', true);
    }

    $('#config_158').change(function(){
        if($(this).val()!=""){
            $("#btn_new_modelo").attr('value', 'index.php?r=sds_veh_modelo/create&marca='+$(this).val());
            $("#btn_new_modelo").attr('disabled', false);
            getModelos($(this).val());
        }else{
            $("#btn_new_modelo").val(null);
            $("#modelo_marca").html('');
            $("#modelo_marca").attr('disabled', true);
            $("#btn_new_modelo").attr('disabled', true);
        }
    });
    
    function getModelos(marca){
        $.post("index.php?r=sds_veh_modelo/cmb_modelo&marca="+marca, function(data) {
            var options = "";
            data.forEach(modelo => {
                selected=($("#modelo_marca").val()==modelo.idmodelo?'selected':'');
                options+="<option value='"+modelo.idmodelo+"' "+selected+">"+modelo.descripcion+"</option>";
            });
            $("#modelo_marca").attr('disabled', false);
            $("#modelo_marca").html(options);
            $("#modelo_marca").trigger('change');
        });
    }
});
JS;
$this->registerJs($script);
?>