<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = $model->isNewRecord ? "Crear Movimiento" : "Actualizar Movimiento #{$model->idmovimiento}";

$this->params['breadcrumbs'][] = $this->title;
?>


<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>


<?php $form = ActiveForm::begin(); ?>

<?php if (!Yii::$app->request->isAjax) : ?>

    <header class="page-header">
        <h2><?= $this->title ?></h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.php">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span><?= $this->title ?></span></li>
            </ol>

            <div class="sidebar-right-toggle"></div>
        </div>
    </header>
<?php endif ?>


<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-6">
                        <!-- <div class="input-group"> -->

                        <?= $form->field($model, 'tipo_movimiento')->dropdownList(
                            $tipo_movimiento,
                            [
                                'prompt' => [
                                    'text' => 'Seleccione Tipo Movimiento',
                                    'options' => ['disabled' => true, 'selected' => true]
                                ],
                                'id' => 'tipo_movimiento',
                            ],
                        );
                        ?>
                        <!-- <span class="input-group-btn">
                                <= botonAltaConfiguracion($model,  Sds_com_configuracion_tipo::VIO_MOVIMIENTO_TIPO_MOVIMIENTO) ?>
                            </span> -->
                        <!-- </div> -->
                    </div>
                    <div class="col-md-6">
                        <?php
                        if ($model->fecha != null) {
                            $model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha)));
                        }
                        echo $form->field($model, 'fecha')->widget(DatePicker::class, [
                            'name' => 'check_issue_date',
                            'language' => 'es',
                            'readonly' => false,
                            'layout' => '{picker}{input}{remove}',
                            'options' => [
                                'id' => 'fecha',
                                'class' => 'form-control input-md',
                                'disabled' => false,
                            ],
                            'pluginOptions' => [
                                'value' => null,
                                'format' => 'dd/mm/yyyy',
                                'endDate' => date('d/m/Y'),
                                'todayHighlight' => true,
                                'autoclose' => true,
                            ]
                        ]);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'profesionales_intervinientes')->textInput(['id' => 'profesionales', 'maxlength' => true, 'autocomplete' => 'off']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'detalle')->textarea(['id' => 'detalle', 'rows' => 6]) ?>
                    </div>
                </div>

                <?php if (!Yii::$app->request->isAjax) { ?>
                    <div class="form-group">
                        <a class="btn btn-info" href="index.php?r=sds_vio_intervencion_movimiento/index">Volver </a>
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                    </div>
                <?php } ?>

            </div>
        </section>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php

$this->registerJs(
    "$(document).ready(function() {
            $('#boton-guardar-movimiento').prop('disabled', true);
            $( '#tipo_movimiento').change(function() {
                validarCamposObligatorios();
            });
            $( '#fecha').change(function() {
                validarCamposObligatorios();
            });
            $( '#profesionales').keypress(function() {
                validarCamposObligatorios();
            });
            $( '#detalle').keypress(function() {
                validarCamposObligatorios();
            });
        });"
);

function botonAltaConfiguracion($model, $tipo)
{
    //Creo un botón reutilizable para todas las configuraciones. Se muestra el sector de ABM configuración y se llena
    //con lo que devuelve el método del controller 'actionCreate_ext'. Que sería como un create externo. Se usa también en risneu pero llenando un modal.
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to(['//sds_com_configuracion/create_ext', 'tipo' => $tipo]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btn_config_' . $tipo, 'style' => 'margin-top:25px',
        'tabIndex' => '-1',
        // "disabled" => !$model->isNewRecord,
        'onclick' => '
                        $("#modal_abm").modal("show")
                        .find("#content_abm")
                        .load($(this).attr("value"));
                        $("#header_abm").html("Agregar Tipo de Movimiento");
                    '
    ]);
}


Modal::begin([
    'header' => '<h4 id="header_abm"></h4>',
    'id' => 'modal_abm',
    'size' => 'modal-md',
]);

echo "<div id='content_abm'></div>";

Modal::end();


?>


<script>
    function validarCamposObligatorios() {
        let movimiento = $('#tipo_movimiento').val();
        let fecha = $('#fecha').val();
        let profesionales = $('#profesionales').val();
        let detalle = $('#detalle').val();

        if (movimiento && fecha && profesionales && detalle) {
            $('#boton-guardar-movimiento').prop('disabled', false);
        } else {
            $('#boton-guardar-movimiento').prop('disabled', true);
        }
    }
</script>