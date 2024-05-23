<?php

use app\models\Mds_org_contacto;
use johnitvn\ajaxcrud\CrudAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_registro */
/* @var $form yii\widgets\ActiveForm */

CrudAsset::register($this);

$this->title = 'Cargar Registro';
?>
<style>
    .content-body {
        padding-top: 20px;
    }
</style>
<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li>
                <a href="index.php?r=mds_hor_registro">Registros Horarios</a>
            </li>
            <li><span style="color: #fff"><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 col-md-offset-8">
                        <?= Html::a(
                            '<i class="glyphicon glyphicon-import"></i> Importar Excel',
                            ['importar_horarios_excel_yii'],
                            ['role' => 'modal-remote', 'title' => 'Importar Excel', 'class' => 'btn btn-success', 'style'=>'margin: 0 10px;']
                        )?>
                        <?= Html::a(
                            '<i class="glyphicon glyphicon-import"></i> Importar Txt',
                            ['importacion'],
                            ['role' => 'modal-remote', 'title' => 'Importar Registros', 'class' => 'btn btn-primary col-md-5']
                        ) ?>
                    </div>
                </div>
                <hr style="margin: 3 0;">
                <div class="mds-hor-registro-form col-md-10 col-md-offset-1">
                    <?php if (isset($save) && !empty($save)) : ?>
                        <div class="alert alert-success text-center" id="msj-save" style="padding: 5px;">
                            <b>¡Excelente!</b>
                            <?php 
                                foreach($save as $msg){
                                    echo "<div>".$msg."</div>";
                                }
                            ?>
                        </div>
                    <?php endif ?>
                    <?php if (isset($errores) && !empty($errores)) : ?>
                        <div class="alert alert-danger text-center" id="msj-error" style="padding: 5px;">
                            <b>¡Ups! Algo no está bien...</b>
                            <?php 
                                foreach($errores as $error){
                                    echo "<div>".$error."</div>";
                                }
                            ?>
                        </div>
                    <?php endif ?>
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'idcontacto')->widget(Select2::class, [
                                'data' => ArrayHelper::map(
                                    Mds_org_contacto::findBySql(
                                        "select * from mds_org_contacto c 
                                            join sds_com_persona p on p.idpersona=c.idpersona 
                                            where legajo is not null and activo order by apellido,nombre"
                                    )->all(),
                                    'idcontacto',
                                    function ($model) {
                                        return $model->legajo . " - " . $model->nombre . " " . $model->apellido;
                                    }
                                ),
                                'options' => [
                                    'id' => 'cmb_contacto',
                                    'placeholder' => 'Seleccione Empleado...'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ]
                            ])->label("Empleado"); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="well border border-secondary rounded p-1" style="width:350px">
                                <?php
                                echo $form->field($model, 'fecha')->widget(DatePicker::class, [
                                    'name' => 'check_issue_date',
                                    'type' => $model->isNewRecord ? DatePicker::TYPE_INLINE : DatePicker::TYPE_INPUT,
                                    'language' => 'es',
                                    'readonly' => false,
                                    'layout' => '{picker}{input}{remove}',
                                    'options' => [
                                        'id' => 'fecha_registro',
                                        'class' => 'form-control input-md',
                                        'disabled' => false,
                                        'onchange' =>   'verificar_fecha_existente()',
                                    ],
                                    'pluginOptions' => [
                                        'value' => null,
                                        'defaultDate' => null,
                                        'format' => 'dd/mm/yyyy',
                                        'endDate' => date('d/m/Y'),
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                                        'multidate' => $model->isNewRecord ? true : false,
                                    ]
                                ])->label('Fecha (dd/mm/yyyy)'); ?>
                            </div>
                        </div>
                        <div class="col-md-7" tyle='background:red;'>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'ingreso')->widget(TimePicker::class, [
                                        'options' => [
                                            'id' => 'ingreso',
                                            'class' => 'form-control input-sm',
                                            'value' => '08:00'
                                        ],
                                        'pluginOptions' => [
                                            'showSeconds' => false,
                                            'showMeridian' => false,
                                            'minuteStep' => 15,
                                        ]
                                    ]);
                                    ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'egreso')->widget(TimePicker::class, [
                                        'options' => [
                                            'id' => 'egreso',
                                            'class' => 'form-control input-sm',
                                            'value' => '15:00'
                                        ],
                                        'pluginOptions' => [
                                            'showSeconds' => false,
                                            'showMeridian' => false,
                                            'minuteStep' => 15,
                                        ]
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <?= $form->field($model, 'activo')->checkbox()  ?>
                                </div>
                                <div class="col-md-10" id="txr_msj"></div>
                            </div>
                            <br><br><br>
                            <div class="row">
                                <?php if (!Yii::$app->request->isAjax) { ?>
                                    <div class="form-group">
                                        <?= Html::submitButton(
                                            $model->isNewRecord ? 'Guardar' : 'Actualizar',
                                            ['class' => [($model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'), 'col-md-12']]
                                        ); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </section>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<script>
    window.addEventListener("load", function() {
        setTimeout(function() {
            $('#msj-error').fadeOut("slow", function() {});
        }, 10000);
    });

    window.addEventListener("load", function() {
        setTimeout(function() {
            $('#msj-save').fadeOut("slow", function() {});
        }, 2500);
    });

    function verificar_fecha_existente() {
        $("#txr_msj").html("");
        $("#btnGuardarRegistroHorario").show();
        var id_contacto = $('#cmb_contacto').val();
        var fecha = $('#fecha_registro').val();
        $.post("index.php?r=mds_hor_registro/verificar_fecha_existente&id_contacto=" + id_contacto + "&fechas=" + fecha, function(data) {
            if (data.length > 0) {
                $("#btnGuardarRegistroHorario").hide();
                $("#txr_msj").html("<p style='color: red;'>El empleado registra licencia desde: <br>" + data + "</p>");
            }
        });
    }
</script>