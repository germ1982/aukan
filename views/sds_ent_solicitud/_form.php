<?php

use app\models\Sds_ent_solicitud;
use app\models\Sds_ent_tipo;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_solicitud */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-ent-solicitud-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'dni')->textInput(["id" => "txtDNI", "disabled" => $model->estado == Sds_ent_solicitud::ESTADO_DESAPROBADO]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordion_renaper">
                <div class="panel panel-accordion">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#renaper">
                                Registro Renaper
                            </a>
                        </h4>
                    </div>
                    <div id="renaper" class="accordion-body collapse in">
                        <div class="panel-body" id="renaper_content">
                            <div class="row">
                                <!-- <div class="col-md-3" style="text-align: center;">
                                    <img id="renaper_foto" src="" alt="" height="100px" />
                                </div> -->
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12" id="renaper_nombre"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="renaper_apellido"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="renaper_domicilio"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="renaper_localidad"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'idtipo')->dropDownList(
                ArrayHelper::map(
                    Sds_ent_tipo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                    'idtipo',
                    'descripcion'
                ),
                [
                    'prompt' => 'Seleccionar Tipo Entrega ...',
                    'id' => 'cmb_dispositivo',
                    "disabled" => $model->estado == Sds_ent_solicitud::ESTADO_DESAPROBADO
                ]
            );
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'cantidad')->textInput(['type' => 'number', "disabled" => $model->estado == Sds_ent_solicitud::ESTADO_DESAPROBADO]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 6, "disabled" => $model->estado == Sds_ent_solicitud::ESTADO_DESAPROBADO]) ?>
        </div>
    </div>
    <div class="row" style="display:<?= $model->estado == Sds_ent_solicitud::ESTADO_DESAPROBADO ? "block" : "none" ?>">
        <div class="col-md-12">
            <?= $form->field($model, 'motivo_rechazo')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs(
    "$(document).ready(function() {        
        datos_renaper(true);
    });

    $('#txtDNI').focusout(function(){        
        datos_renaper(false);
    });
    "
);
?>
<script>
    var dni = <?php echo isset($model->dni) ? $model->dni : 0 ?>;

    function datos_renaper(primera_vez = false) {
        var dni_campo = $('#txtDNI').val();
        if (dni != dni_campo || primera_vez) {
            dni = dni_campo;
            if (dni_campo > 0) {
                $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni_campo, function(data) {
                    if (data.status == "error") {
                        $("#txt_mensaje").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");
                        limpiarDatos();
                    } else {
                        var nombre = "";
                        var apellido = "";
                        var domicilio = "";
                        var localidad = "";
                        var foto = "";
                        $.each(data, function(ind, elem) {
                            console.log(ind);
                            if (ind == 'records') {
                                console.log(elem[0]);
                                nombre = elem[0].result.nombres;
                                apellido = elem[0].result.apellido;
                                domicilio = elem[0].result.calle + " " + elem[0].result.numero;
                                localidad = elem[0].result.ciudad;
                                //foto = elem[0].result.foto;
                            }
                        });

                        $("#renaper_nombre").html("<b>Nombre: </b>" + nombre);
                        $("#renaper_apellido").html("<b>Apellido: </b>" + apellido);
                        $("#renaper_domicilio").html("<b>Domicilio: </b>" + domicilio);
                        $("#renaper_localidad").html("<b>Localidad: </b>" + localidad.replace("ï¿½", "É").replace(/_/g, " "));
                       /*  $("#renaper_foto").attr("src", foto); */
                    }
                });
            }
        }
    }

    function limpiarDatos() {
        $("#renaper_nombre").val('');
        $("#renaper_apellido").val('');
        $("#renaper_domicilio").val("");
        $("#renaper_localidad").val("");
        /* $("#renaper_foto").attr("src", ''); */
    }
</script>