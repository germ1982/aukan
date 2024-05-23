<?php

use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_tar_tarjeta */
/* @var $form yii\widgets\ActiveForm */

function botonAltaConfiguracion($tipo)
{
    //Creo un botón reutilizable para todas las configuraciones. Se muestra el sector de ABM configuración y se llena
    //con lo que devuelve el método del controller 'actionCreate_ext'. Que sería como un create externo. Se usa también en risneu pero llenando un modal.
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to(['//sds_com_configuracion/create_ext', 'tipo' => $tipo]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btn_config_' . $tipo, 'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        'onclick' => '
            $("#abm_configuracion").show();
            $("#abm_configuracion_content").load($(this).attr("value"));
            $("#abm_configuracion_title").html("Agregar ' . ($tipo == Sds_com_configuracion_tipo::TIPO_REFERENTE_TARJETA ? 'Referente' : 'Empresa') . '");'
    ]);
}

?>

<div class="sds-tar-tarjeta-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?php
            $model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha)));

            echo $form->field($model, 'fecha')->widget(DatePicker::ClassName(), [
                'name' => 'check_issue_date',
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}{remove}',
                'options' => [
                    'id' => 'fecha_entrega',
                    'class' => 'form-control input-md',
                    'disabled' => false
                ],
                'pluginOptions' => [
                    'value' => null,
                    'format' => 'dd/mm/yyyy',
                    'endDate' => date('d/m/Y'),
                    'todayHighlight' => true,
                    'autoclose' => true,
                ]
            ])->label('Fecha (dd/mm/yyyy)'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="input-group">
                <?= $form->field($model, 'referente')->dropdownList(
                    ArrayHelper::map(
                        Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_REFERENTE_TARJETA, true),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    [
                        'prompt' => 'Seleccionar Referente ...', 'tabindex' => '1',
                        'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_REFERENTE_TARJETA,
                    ]
                ); ?>
                <span class="input-group-btn">
                    <?= botonAltaConfiguracion(Sds_com_configuracion_tipo::TIPO_REFERENTE_TARJETA) ?>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="input-group">
                <?= $form->field($model, 'empresa')->dropdownList(
                    ArrayHelper::map(
                        Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_EMPRESA_TARJETA, true),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    [
                        'prompt' => 'Seleccionar Empresa ...', 'tabindex' => '1',
                        //El id lo estandarizo de esta forma para que se pueda llenar cualquier combo que use el abm configuración. Siendo como necesario solo el tipo de configuración.
                        'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_EMPRESA_TARJETA,
                    ]
                ); ?>
                <span class="input-group-btn">
                    <?= botonAltaConfiguracion(Sds_com_configuracion_tipo::TIPO_EMPRESA_TARJETA) ?>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'numero')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'dni')->textInput(["id" => "txtDNI"]) ?>
        </div>
        <div class="col-md-8">
            <div class="row" style="padding-top: 30px;">
                <div class="col-md-12" id="renaper_nombre"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
</div>
<div class="row" id="abm_configuracion" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 id="abm_configuracion_title" class="panel-title">
                </h3>
            </header>
            <div class="panel-body" id="abm_configuracion_content">
            </div>
        </section>
    </div>
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
            if (dni_campo != '') {
                dni = dni_campo;
                $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni, function(data) {
                    if (data.status != "error") {
                        var nombre = "";
                        var apellido = "";
                        /* var domicilio = "";
                           var localidad = "";
                           var foto = ""; */
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

                        $("#renaper_nombre").html("<b>Apellido y Nombre: </b>" + apellido + ", " + nombre);
                        /* 
                                            $("#renaper_domicilio").html("<b>Domicilio: </b>" + domicilio);
                                            $("#renaper_localidad").html("<b>Localidad: </b>" + localidad.replace("ï¿½", "É").replace(/_/g, " "));
                                            $("#renaper_foto").attr("src", foto); */
                    }
                });
            }
        }
    }
</script>