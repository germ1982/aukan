<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_vio_agresor;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_vio_agresor */
/* @var $form yii\widgets\ActiveForm */

$this->title = $model->isNewRecord ? 'Crear Agresor' : "Actualizar Agresor #" . $model->idagresor;
?>


<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>

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

<h1><?= Html::encode($this->title) ?></h1>

<div class="sds-vio-agresor-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-11">
                            <?= $form->field($model, 'dni')->textInput(['id' => "dni", 'maxlength' => true]) ?>
                        </div>
                        <div class="col-md-1" style="padding-top:25px;">
                            <?php
                            echo  Html::a('<img src="img/PUI_logo_tiny.png" height="34px" alt="Consulta PUI">', null, [
                                'name' => 'btn_pui_agresor',
                                'id' => 'btn_pui_agresor',
                                'data-request-method' => 'post',
                                'data-toggle' => 'tooltip',
                                'style' => 'padding:0px;padding-left:2px;',
                                'class' => 'btn',
                                'title' => Yii::t('app', 'Consulta a Portal Unificado'),
                            ]);
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 required">
                            <?= $form->field($model, 'nombre')->textInput(['id' => 'nombre', 'maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6 required">
                            <?= $form->field($model, 'apellido')->textInput(['id' => 'apellido', 'maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'genero')->dropdownList(
                                ArrayHelper::map(
                                    Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO_AUTOPERCIBIDO, false),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                ['id' => 'genero', 'placeholder' => 'Seleccionar Genero ...', 'tabindex' => '1']
                            )
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'agresor_dato_denuncia')->textarea(['rows' => 2, "id" => "agresor_dato_denuncia"]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?=
                            $form->field($model, 'agresor_dav')->dropdownList(
                                [
                                    1 => "Si",
                                    0 => "No",
                                ],
                                [
                                    'onchange' => 'ocultar_agresor_dav()',
                                    'id' => 'check_agresor_dav',
                                ]
                            )
                            ?>
                        </div>
                        <div class="col-md-9" id="agresor_dav">
                            <?= $form->field($model, 'agresor_dav_datos')->textInput(['id' => 'agresor_dav_datos', 'maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?=
                            $form->field($model, 'agresor_problematico')->dropdownList(
                                [
                                    1 => "Si",
                                    0 => "No",
                                ],
                                [
                                    'onchange' => 'ocultar_agresor_problematico()',
                                    'id' => 'check_agresor_consumo',
                                ]
                            )
                            ?>
                        </div>
                        <div class="col-md-9" id="agresor_con">
                            <?= $form->field($model, 'agresor_consumo')->textarea(['rows' => 1, "id" => "agresor_consumo"]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?=
                            $form->field($model, 'activo')->dropdownList(
                                [
                                    1 => "Si",
                                    0 => "No",
                                ],

                            )
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <a class="btn btn-info" href="index.php?r=sds_vio_agresor/index">Volver </a>
                        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(
    "$(document).ready(function() {
        if ($('#check_agresor_consumo').val() == '1') {
            $('#agresor_con').show();
        } else {
            $('#agresor_con').hide();
        }

        if ($('#check_agresor_dav').val() == '1') {
            $('#agresor_dav').show();
        } else {
            $('#agresor_dav').hide();
        }
    });

        $('#btn_pui_agresor').click(function(){        
        var dni_campo_agresor = $('#dni').val();        
        window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo_agresor, '_blank');
    });"
);
?>

<script>
    function ocultar_agresor_dav() {
        if ($('#check_agresor_dav').val() == '1') {
            $('#agresor_dav').show();
        } else {
            $('#agresor_dav').hide();
            $('#agresor_dav_datos').val('');
        }
    }

    function ocultar_agresor_problematico() {
        if ($('#check_agresor_consumo').val() == '1') {
            $('#agresor_con').show();
        } else {
            $('#agresor_con').hide();
            $('#agresor_consumo').val('');
        }
    }
</script>