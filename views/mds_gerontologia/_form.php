<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use app\models\Sds_com_persona;
use app\models\Mds_gerontologia;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_gerontologia */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    div.required label:after {
        content: " *";
        color: red;
    }

    table {
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 5px;
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
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-gerontologia-form">
                    <div class="panel-group" id="accordion_beneficiario">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_beneficiario" href="#beneficiario">
                                        <b>Persona</b>
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="beneficiario" class="accordion-body collapse in">
                                <div class="panel-body" id="beneficiario_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 style="margin: 0"><b>Datos Personales</b></h5>
                                            <span id="txt_mensaje"></span>
                                            <div class="input-group">
                                                <?= $form->field($model, 'idpersona')->hiddenInput() ?>
                                                <input class="form-control" style="margin-top: -10px;" type="text" maxlength="10" id="txtDNI_search" value="<?= $model->persona
                                                                                                                                                                ? "{$model->persona->apellido} {$model->persona->nombre} ({$model->persona->documento})"
                                                                                                                                                                : '' ?>" name="txtDNI_search" autocomplete="off">
                                                <span class="input-group-btn">
                                                    <?php echo Html::button(
                                                        '<i class="glyphicon glyphicon-search"></i>',
                                                        [
                                                            'class' =>
                                                            'btn btn-primary btn-flat',
                                                            'name' => 'btn_dni',
                                                            'id' => 'btn_dni',
                                                            'style' => 'margin-top:26px',
                                                            'title' => Yii::t(
                                                                'app',
                                                                'Buscar DNI'
                                                            ),
                                                        ]
                                                    ); ?>
                                                    <?php echo Html::button(
                                                        '<i class="glyphicon glyphicon-plus"></i>',
                                                        [
                                                            'value' => Url::to([
                                                                'mds_cap_persona/create',
                                                            ]),
                                                            'class' =>
                                                            'btn btn-success btn-flat showModalButton',
                                                            'style' => 'margin-top:26px',
                                                            'id' => 'btnContacto',
                                                            'disabled' => 'disabled',
                                                            'onclick' =>
                                                            '$("#abm_persona").show();',
                                                            'title' => Yii::t(
                                                                'app',
                                                                'Crear Nueva Persona'
                                                            ),
                                                        ]
                                                    ); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-md-6" id="divDatepicker" name="divDatepicker" required style="<?= !($model->persona && $model->persona->idpersona) ? 'display:none' : '' ?>">
                                            <?php
                                            if ($model->fecha_atencion != null) {
                                                $model->fecha_atencion = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_atencion)));
                                            }
                                            echo $form->field($model, 'fecha_atencion')
                                                ->widget(DatePicker::class, [
                                                    'name' => 'check_issue_date',
                                                    'language' => 'es',
                                                    'readonly' => false,
                                                    'layout' => '{picker}{input}{remove}',
                                                    'options' => [
                                                        'id' => 'fechaAtencion',
                                                        'class' => 'form-control input-md',
                                                        'disabled' => false,
                                                        'autocomplete' => 'off',
                                                    ],
                                                    'pluginOptions' => [
                                                        'value' => null,
                                                        'format' => 'dd/mm/yyyy',
                                                        'todayHighlight' => true,
                                                        'autoclose' => true,
                                                    ],
                                                ]);
                                            ?>
                                        </div>
                                    </div>
                                    <div id="campos" style="<?= !($model->persona && $model->persona->idpersona) ? 'display:none' : '' ?>">
                                        <div class="row">
                                            <div class="col-md-6" required>
                                                <?= $form
                                                    ->field($model, 'idobrasocial')
                                                    ->widget(Select2::class, [
                                                        'data' => $obrasocial,
                                                        'options' => [
                                                            'placeholder' =>
                                                            'Seleccione opción...',
                                                            'tabIndex' => '1',
                                                        ],
                                                        'pluginOptions' => [
                                                            'allowClear' => true,
                                                        ],
                                                    ])
                                                    ->label('Obra social') ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?= $form->field($model, 'domicilio')->textInput(['maxlength' => true]) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <?= $form
                                                    ->field($model, 'idestadocivil')
                                                    ->widget(Select2::class, [
                                                        'data' => $estadocivil,
                                                        'options' => [
                                                            'placeholder' =>
                                                            'Seleccione opción...',
                                                            'tabIndex' => '1',
                                                        ],
                                                        'pluginOptions' => [
                                                            'allowClear' => true,
                                                        ],
                                                    ])
                                                    ->label('Estado Civil') ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?= $form->field($model, 'telefono')->textInput(['maxlength' => true])->label('Teléfono') ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?= $form
                                                    ->field($model, 'idvivienda')
                                                    ->widget(Select2::class, [
                                                        'data' => $vivienda,
                                                        'options' => [
                                                            'placeholder' =>
                                                            'Seleccione opción...',
                                                            'tabIndex' => '1',
                                                            'id' => 'idvivienda',
                                                        ],
                                                        'pluginOptions' => [
                                                            'allowClear' => true,
                                                        ],
                                                    ])
                                                    ->label('Vivienda') ?>
                                                <?= Html::input('hidden', 'hidden_idresidencia', Mds_gerontologia::ID_VIVIENDA_RESIDENCIA, $options = ['id' => 'hidden_idresidencia']) ?>
                                            </div>
                                            <div class="col-md-3" id="f_residencia">
                                                <?= $form->field($model, 'residencia')->textInput(['maxlength' => true])->label('Nombre de la Residencia') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="campos2" style="<?= !($model->persona && $model->persona->idpersona) ? 'display:none' : '' ?>">
                            <div class="panel-group" id="accordion_biografia">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_biografia" href="#biografia">
                                                <b>Biografía</b>
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="biografia" class="accordion-body collapse in">
                                        <div class="panel-body" id="biografia_content">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?= $form->field($model, 'lugar_nacimiento')->textInput(['maxlength' => true])->label('Lugar de nacimiento') ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <?= $form->field($model, 'idescolaridad')
                                                        ->widget(Select2::class, [
                                                            'data' => $escolaridad,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Escolaridad') ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'vivencias')->textarea(['rows' => 5])->label('Algunas vivencias que marcaron su vida') ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'tiempo_libre')->textarea(['rows' => 5])->label('Tiempo libre') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group" id="accordion_habitos">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_habitos" href="#habitos">
                                                <b>Hábitos</b>
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="habitos" class="accordion-body collapse in">
                                        <div class="panel-body" id="habitos_content">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'fuma')
                                                        ->dropDownList(
                                                            ['1' => 'Sí', '0' => 'No'],
                                                            [
                                                                'prompt' => [
                                                                    'text' =>
                                                                    'Seleccione opción...',
                                                                    'options' => [
                                                                        'disabled' => true,
                                                                        'selected' => true,
                                                                    ],
                                                                ],
                                                            ]
                                                        )
                                                        ->label('Fuma') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'suenio_adecuado')
                                                        ->dropDownList(
                                                            ['1' => 'Sí', '0' => 'No'],
                                                            [
                                                                'prompt' => [
                                                                    'text' =>
                                                                    'Seleccione opción...',
                                                                    'options' => [
                                                                        'disabled' => true,
                                                                        'selected' => true,
                                                                    ],
                                                                ],
                                                            ]
                                                        )
                                                        ->label('Sueño adecuado') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'ejercicio_fisico')
                                                        ->dropDownList(
                                                            ['1' => 'Sí', '0' => 'No'],
                                                            [
                                                                'prompt' => [
                                                                    'text' =>
                                                                    'Seleccione opción...',
                                                                    'options' => [
                                                                        'disabled' => true,
                                                                        'selected' => true,
                                                                    ],
                                                                ],
                                                            ]
                                                        )
                                                        ->label('Ejercicio físico cotidiano') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group" id="accordion_vacunas">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vacunas" href="#vacunas">
                                                <b>Vacunas</b>
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="vacunas" class="accordion-body collapse in">
                                        <div class="panel-body" id="vacunas_content">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'vacunas_obligatorias')
                                                        ->dropDownList(
                                                            ['1' => 'Sí', '0' => 'No'],
                                                            [
                                                                'prompt' => [
                                                                    'text' =>
                                                                    'Seleccione opción...',
                                                                    'options' => [
                                                                        'disabled' => true,
                                                                        'selected' => true,
                                                                    ],
                                                                ],
                                                            ]
                                                        )
                                                        ->label('Obligatorias') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'idvacunascovid')
                                                        ->widget(Select2::class, [
                                                            'data' => $vacunacovid19,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])->label('Vacunas COVID-19') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group" id="accordion_continencia">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_continencia" href="#continencia">
                                                <b>Continencia esfínteres</b>
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="continencia" class="accordion-body collapse in">
                                        <div class="panel-body" id="continencia_content">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'diuresis')
                                                        ->dropDownList(
                                                            ['1' => 'Sí', '0' => 'No'],
                                                            [
                                                                'prompt' => [
                                                                    'text' =>
                                                                    'Seleccione opción...',
                                                                    'options' => [
                                                                        'disabled' => true,
                                                                        'selected' => true,
                                                                    ],
                                                                ],
                                                            ]
                                                        )->label('Diuresis') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'catarsis')
                                                        ->dropDownList(
                                                            ['1' => 'Sí', '0' => 'No'],
                                                            [
                                                                'prompt' => [
                                                                    'text' =>
                                                                    'Seleccione opción...',
                                                                    'options' => [
                                                                        'disabled' => true,
                                                                        'selected' => true,
                                                                    ],
                                                                ],
                                                            ]
                                                        )->label('Catarsis') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group" id="accordion_patologicos">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_patologicos" href="#patologicos">
                                                <b>Antecedentes personales patológicos relevantes</b>
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="patologicos" class="accordion-body collapse in">
                                        <div class="panel-body" id="patologicos_content">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'antecedentes_hta')
                                                        ->dropDownList(
                                                            ['1' => 'Sí', '0' => 'No'],
                                                            [
                                                                'prompt' => [
                                                                    'text' =>
                                                                    'Seleccione opción...',
                                                                    'options' => [
                                                                        'disabled' => true,
                                                                        'selected' => true,
                                                                    ],
                                                                ],
                                                                'id' => 'cmb_hta',
                                                            ]
                                                        )->label('HTA') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'antecedentes_acv')
                                                        ->dropDownList(
                                                            ['1' => 'Sí', '0' => 'No'],
                                                            [
                                                                'prompt' => [
                                                                    'text' =>
                                                                    'Seleccione opción...',
                                                                    'options' => [
                                                                        'disabled' => true,
                                                                        'selected' => true,
                                                                    ],
                                                                ],
                                                                'id' => 'cmb_acv',
                                                            ]
                                                        )->label('ACV') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'antecedentes_cardiaca')
                                                        ->dropDownList(
                                                            ['1' => 'Sí', '0' => 'No'],
                                                            [
                                                                'prompt' => [
                                                                    'text' =>
                                                                    'Seleccione opción...',
                                                                    'options' => [
                                                                        'disabled' => true,
                                                                        'selected' => true,
                                                                    ],
                                                                ],
                                                                'id' => 'cmb_cardiaca',
                                                            ]
                                                        )->label('Enfermedades cardiovasculares (IAM, Trombosis, etc)') ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'antecedentes_diabetes')
                                                        ->dropDownList(
                                                            ['1' => 'Sí', '0' => 'No'],
                                                            [
                                                                'prompt' => [
                                                                    'text' =>
                                                                    'Seleccione opción...',
                                                                    'options' => [
                                                                        'disabled' => true,
                                                                        'selected' => true,
                                                                    ],
                                                                ],
                                                                'id' => 'cmb_diabetes',
                                                            ]
                                                        )->label('Diabetes') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'antecedentes_cancer')
                                                        ->dropDownList(
                                                            ['1' => 'Sí', '0' => 'No'],
                                                            [
                                                                'prompt' => [
                                                                    'text' =>
                                                                    'Seleccione opción...',
                                                                    'options' => [
                                                                        'disabled' => true,
                                                                        'selected' => true,
                                                                    ],
                                                                ],
                                                                'id' => 'cmb_cancer',
                                                            ]
                                                        )->label('Cáncer') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form->field($model, 'antecedentes_otras')->textInput(['maxlength' => true])->label('Otras') ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model, 'caidas')
                                                        ->dropDownList(
                                                            ['1' => 'Sí', '0' => 'No'],
                                                            [
                                                                'prompt' => [
                                                                    'text' =>
                                                                    'Seleccione opción...',
                                                                    'options' => [
                                                                        'disabled' => true,
                                                                        'selected' => true,
                                                                    ],
                                                                ],
                                                                'id' => 'cmb_caidas',
                                                            ]
                                                        )
                                                        ->label('Caídas en los últimos 6 meses') ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'medicacion_actual')->textarea(['rows' => 5])->label('Medicación actual') ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'estudios_complementarios')->textarea(['rows' => 5])->label('Laboratorios y estudios complementarios realizados el último año') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!------------------------------------------------  examen fisico  --------------------------------------------------------------->
                            <div class="panel-group" id="accordion_examenfisico">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_examenfisico" href="#examenfisico">
                                                <b>Examen físico</b>
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="examenfisico" class="accordion-body collapse in">
                                        <div class="panel-body" id="examenfisico_content">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form->field($model, 'examen_fis_ta')->textInput(['maxlength' => true])->label('TA') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form->field($model, 'examen_fis_sato2')->textInput(['maxlength' => true])->label('Sat O2') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form->field($model, 'examen_fis_fc')->textInput(['maxlength' => true])->label('FC lat/minuto') ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'examen_fis_abdomen')->textarea(['rows' => 3])->label('Abdomen') ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'examen_fis_aparato_respiratorio')->textarea(['rows' => 3])->label('Aparato respiratorio') ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'examen_fis_miembros_inferiores')->textarea(['rows' => 3])->label('Miembros inferiores') ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'examen_fis_observaciones')->textarea(['rows' => 3])->label('Observaciones') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!------------------------------------------------  ABVD  --------------------------------------------------------------->
                            <div class="panel-group" id="accordion_abvd">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_abvd" href="#abvd">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <b>Evaluación funcional ABVD : </b>Actividades Básicas de la Vida Diaria
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?= $form->field($model_evaluacion, 'abvd')->textInput(['maxlength' => true, 'readonly' => true])->label('') ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="abvd" class="accordion-body collapse in">
                                        <div class="panel-body" id="abvd_content">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model_evaluacion, 'abvd_lavado')
                                                        ->widget(Select2::class, [
                                                            'data' => $abvdLavadoSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $abvdLavadoSelectOptions,
                                                                'id' => 'abvdLavadoValor',
                                                                'onchange' =>
                                                                'cargarResultadoABVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Lavado') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model_evaluacion, 'abvd_vestido')
                                                        ->widget(Select2::class, [
                                                            'data' => $abvdVestidoSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $abvdVestidoSelectOptions,
                                                                'id' => 'abvdVestidoValor',
                                                                'onchange' =>
                                                                'cargarResultadoABVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Vestido') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model_evaluacion, 'abvd_banio')
                                                        ->widget(Select2::class, [
                                                            'data' => $abvdBanioSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $abvdBanioSelectOptions,
                                                                'id' => 'abvdBanioValor',
                                                                'onchange' =>
                                                                'cargarResultadoABVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Uso del baño') ?>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form->field($model_evaluacion, 'abvd_movilizacion')
                                                        ->widget(Select2::class, [
                                                            'data' => $abvdMovilizacionSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $abvdMovilizacionSelectOptions,
                                                                'id' => 'abvdMovilizacionValor',
                                                                'onchange' =>
                                                                'cargarResultadoABVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Movilización') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form->field($model_evaluacion, 'abvd_continencia')
                                                        ->widget(Select2::class, [
                                                            'data' => $abvdContinenciaSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $abvdContinenciaSelectOptions,
                                                                'id' => 'abvdContinenciaValor',
                                                                'onchange' =>
                                                                'cargarResultadoABVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Continencia') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model_evaluacion, 'abvd_alimentacion')
                                                        ->widget(Select2::class, [
                                                            'data' => $abvdAlimentacionSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $abvdAlimentacionSelectOptions,
                                                                'id' => 'abvdAlimentacionValor',
                                                                'onchange' =>
                                                                'cargarResultadoABVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Alimentación') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!------------------------------------------------  AIVD  --------------------------------------------------------------->
                            <div class="panel-group" id="accordion_aivd">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_aivd" href="#aivd">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <b>Evaluación funcional AIVD : </b>Actividades Instrumentales de la Vida Diaria
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?= $form->field($model_evaluacion, 'aivd')->textInput(['maxlength' => true, 'readonly' => true])->label('') ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="aivd" class="accordion-body collapse in">
                                        <div class="panel-body" id="aivd_content">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <?= $form
                                                        ->field($model_evaluacion, 'aivd_capacidad_telefono')
                                                        ->widget(Select2::class, [
                                                            'data' => $aivdCapacidadTelefonoSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $aivdCapacidadTelefonoSelectOptions,
                                                                'id' =>
                                                                'aivdCapacidadTelefonoValor',
                                                                'onchange' =>
                                                                'cargarResultadoAIVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Capacidad para usar el teléfono') ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?= $form
                                                        ->field($model_evaluacion, 'aivd_compras')
                                                        ->widget(Select2::class, [
                                                            'data' => $aivdComprasSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $aivdComprasSelectOptions,
                                                                'id' => 'aivdComprasValor',
                                                                'onchange' =>
                                                                'cargarResultadoAIVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Compras') ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?= $form
                                                        ->field(
                                                            $model_evaluacion,
                                                            'aivd_preparacion_comida'
                                                        )
                                                        ->widget(Select2::class, [
                                                            'data' => $aivdPreparacionComidaSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $aivdPreparacioncomidaSelectOptions,
                                                                'id' =>
                                                                'aivdPreparacionComidaValor',
                                                                'onchange' =>
                                                                'cargarResultadoAIVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Preparación de la comida') ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?= $form->field($model_evaluacion, 'aivd_cuidado_casa')
                                                        ->widget(Select2::class, [
                                                            'data' => $aivdCuidadoCasaSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $aivdCuidadoCasaSelectOptions,
                                                                'id' => 'aivdCuidadoCasaValor',
                                                                'onchange' =>
                                                                'cargarResultadoAIVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Cuidado de la casa') ?>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <?= $form->field($model_evaluacion, 'aivd_lavado_ropa')
                                                        ->widget(Select2::class, [
                                                            'data' => $aivdLavadoRopaSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $aivdLavadoRopaSelectOptions,
                                                                'id' => 'aivdLavadoRopaValor',
                                                                'onchange' =>
                                                                'cargarResultadoAIVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Lavado de ropa') ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?= $form->field($model_evaluacion, 'aivd_uso_transporte')
                                                        ->widget(Select2::class, [
                                                            'data' => $aivdUsoTransporteSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $aivdUsoTransporteSelectOptions,
                                                                'id' => 'aivdUsoTransporteValor',
                                                                'onchange' =>
                                                                'cargarResultadoAIVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Uso de medios de transporte') ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?= $form->field($model_evaluacion, 'aivd_responsabilidad_medicacion')
                                                        ->widget(Select2::class, [
                                                            'data' => $aivdResponsabilidadMedicacionSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $aivdResponsabilidadMedicacionSelectOptions,
                                                                'id' =>
                                                                'aivdResponsabilidadMedicacionValor',
                                                                'onchange' =>
                                                                'cargarResultadoAIVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Responsabilidad respecto a su medicación') ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <?= $form->field($model_evaluacion, 'aivd_manejo_asuntos_economicos')
                                                        ->widget(Select2::class, [
                                                            'data' => $aivdManejoAsuntosEconomicosSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $aivdManejoAsuntosEconomicosSelectOptions,
                                                                'id' =>
                                                                'aivdAsuntosEconomicosValor',
                                                                'onchange' =>
                                                                'cargarResultadoAIVD();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('Manejo de asuntos económicos') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!------------------------------------------------  social  --------------------------------------------------------------->
                            <div class="panel-group" id="accordion_evaluacionsocial">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_evaluacionsocial" href="#evaluacionsocial">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <b>Evaluación social: </b>Escala de valoración socio familiar de Gijón
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?= $form->field($model_evaluacion, 'ev_social_total')->textInput(['maxlength' => true, 'readonly' => true])->label('') ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="evaluacionsocial" class="accordion-body collapse in">
                                        <div class="panel-body" id="evaluacionsocial_content">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form
                                                        ->field($model_evaluacion, 'idsituacionfamiliar')
                                                        ->widget(Select2::class, [
                                                            'data' => $situacionFamiliarSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $situacionFamiliarSelectOptions,
                                                                'id' => 'situacionFamiliarValor',
                                                                'onchange' =>
                                                                'cargarResultadoSocial();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('a) Situación familiar') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form->field($model_evaluacion, 'idrelacionessociales')
                                                        ->widget(Select2::class, [
                                                            'data' => $relacionesSocialesSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $relacionesSocialesSelectOptions,
                                                                'id' => 'relacionesSocialesValor',
                                                                'onchange' =>
                                                                'cargarResultadoSocial();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('b) Relaciones sociales') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form->field($model_evaluacion, 'idredsocial')
                                                        ->widget(Select2::class, [
                                                            'data' => $redSocialSelect,
                                                            'options' => [
                                                                'placeholder' =>
                                                                'Seleccione opción...',
                                                                'tabIndex' => '1',
                                                                'options' => $redSocialSelectOptions,
                                                                'id' => 'redSocialValor',
                                                                'onchange' =>
                                                                'cargarResultadoSocial();',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ])
                                                        ->label('c) Apoyos de la red social') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!------------------------------------------------  ICOPE  --------------------------------------------------------------->
                            <div class="panel-group" id="accordion_icope">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_icope" href="#icope">
                                                <b>INSTRUMENTO ICOPE DE DETECCIÓN DE LA OMS</b>
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="icope" class="accordion-body collapse in">
                                        <div class="panel-body" id="icope_content">
                                            <div class="row">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Condiciones prioritarias asociadas con la disminución de la capacidad cognitiva</th>
                                                            <th>Pruebas</th>
                                                            <th>Evaluar a fondo todos los dominios que se seleccionen</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>DETERIORO COGNITIVO</td>
                                                            <td>1. Recordar tres palabras: flor, puerta, arroz (por ejemplo).
                                                                <br>2. Orientación en tiempo y espacio: ¿Cuál es la fecha completa de hoy?
                                                                ¿Dónde está usted ahora mismo (casa, consulta, etc.)?
                                                                <br>3. ¿Recuerda las tres palabras?
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <?= $form->field($model_evaluacion, 'icope_detcog_responde_incorrectamente')->checkbox() ?>
                                                                    <?= $form->field($model_evaluacion, 'icope_detcog_no_responde')->checkbox() ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>PÉRDIDA DE MOVILIDAD</td>
                                                            <td>Prueba de la silla: Debe levantarse de la silla cinco veces sin ayudarse con los brazos.
                                                                <br>¿Se levantó cinco veces de la silla en 14 segundos?
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <?= $form->field($model_evaluacion, 'icope_perdida_movilidad')->checkbox() ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>NUTRICIÓN DEFICIENTE</td>
                                                            <td> Pérdida de peso: <br>
                                                                1.¿Ha perdido más de 3 kg involuntariamente en los últimos tres meses?
                                                                <br>2. Pérdida del apetito: ¿Ha perdido el apetito?
                                                            </td>
                                                            <td>
                                                                <div><br>
                                                                    <?= $form->field($model_evaluacion, 'icope_nut_def_perdida_peso')->checkbox() ?>
                                                                    <?= $form->field($model_evaluacion, 'icope_nut_def_perdida_apetito')->checkbox() ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>DISCAPACIDAD VISUAL</td>
                                                            <td>¿Tiene algún problema de la vista?<br>
                                                                ¿Le cuesta ver de lejos o leer? ¿Tiene alguna enfermedad ocular o toma
                                                                medicación (p. ej., diabetes, hipertensión)?
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <?= $form->field($model_evaluacion, 'icope_discapacidad_visual')->checkbox() ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>PÉRDIDA AUDITIVA</td>
                                                            <td>Oye los susurros (prueba de susurros) <b>o bien</b>
                                                                <br>Audiometría ≤ 35 dB <b>o bien</b>
                                                                <br>Supera la prueba electrónica de dígitos sobre fondo de ruido.
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <?= $form->field($model_evaluacion, 'icope_perdida_auditiva')->checkbox() ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>SÍNTOMAS DEPRESIVOS</td>
                                                            <td>En las últimas dos semanas, ¿ha tenido alguno de los siguientes problemas?:<br>
                                                                1. ¿Sentimientos de tristeza, melancolía o desesperanza?<br>
                                                                2. ¿Falta de interés o de placer al hacer las cosas?
                                                            </td>
                                                            <td>
                                                                <div><br>
                                                                    <?= $form->field($model_evaluacion, 'icope_sin_dep_sentimientos')->checkbox() ?>
                                                                    <?= $form->field($model_evaluacion, 'icope_sin_dep_interes')->checkbox() ?>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group" id="accordion_recomendaciones">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_recomendaciones" href="#recomendaciones">
                                                <b>Observaciones</b>
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="recomendaciones" class="accordion-body collapse in">
                                        <div class="panel-body" id="recomendaciones_content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'problemas_actuales')->textarea(['rows' => 5])->label('Problemas actuales que impactan en su calidad de vida') ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'recomendaciones')->textarea(['rows' => 5])->label('Recomendaciones') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group" id="accordion_adjuntos">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_adjuntos" href="#adjuntos">
                                                <b>Documentación Adjunta</b>
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="adjuntos" class="accordion-body collapse in">
                                        <div class="panel-body" id="adjuntos_content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Archivos adjuntos</label>
                                                    <input type="hidden" id="otros_adjuntos" name="Mds_legales_oficio[otros_adjuntos]">
                                                    <input type="hidden" id="adjuntos_eliminados" name="Mds_legales_oficio[adjuntos_eliminados]">
                                                    <div class="dropzone needsclick dz-clickable" id="adjunto-otrosdocumentos" name="mainFileUploader">
                                                        <div class="fallback">
                                                            <input name="file" type="file" />
                                                        </div>
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
                        <div class="col-md-12">
                            <a class="btn btn-info" href="index.php?r=mds_gerontologia/index">Volver</a>
                            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success', 'id' => 'btnSave', 'style' => !($model->persona && $model->persona->idpersona) ? 'display:none' : '']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php Modal::begin([
    'header' => '<h4>Agregar persona</h4>',
    'id' => 'modal',
    'size' => 'modal-lg',
]); ?>
<div class="panel-body">
    <?php
    $model_persona = new Sds_com_persona();
    echo $this->render('./_form_new_persona', [
        'model' => $model_persona,
        'botones' => true,
        'nacionalidades' => $nacionalidades,
        'tiposDocumentos' => $tiposDocumentos,
        'username' => $username,
        'generos' => $generos,
        'token' => $token
    ]);
    ?>
</div>
<?php Modal::end(); ?>

<?php
$script = <<<JS
    $(function() {
        $(document).on('click', '.showModalButton', function(){
            $("#txtDNI").val($("#txtDNI_search").val());
            if ($('#modal').hasClass('in')) {
                $('#modal').find('#modalContent')
                        .load($(this).attr('value'));
            } else {
                $('#modal').modal('show')
                        .find('#modalContent')
                        .load($(this).attr('value'));
            }
        });
    });
    
JS;

$this->registerJs($script);
?>

<?php $this->registerJs(
    "$(document).ready(function() { 
        $('#f_residencia').hide();

        if($('#mds_gerontologia-idpersona').val()!=''){
            $('#campos').show();
            $('#campos2').show();
            $('#btnSave').show();
        }
        if($('#idvivienda').val()==$('#hidden_idresidencia').val()){
            $('#f_residencia').show();
        }
        $('#idvivienda').on('change', function(e) {
            if($('#idvivienda').val()==$('#hidden_idresidencia').val()){
                $('#f_residencia').show();
            }else{
                $('#mds_gerontologia-residencia').val('')
                $('#f_residencia').hide();
            }
        });

        // Deshabilitamos el comportamiento de la tecla enter para que no haga un submit del form
        $('#formGerontologia').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        
        $('#btnSave').click(function(e){
            const idpersona = $('#mds_gerontologia-idpersona').val();
            const idresidencia = $('#mds_gerontologia-residencia').val();
            
            if (!idpersona){
                alert('Debe completar el dato de la persona')
                e.preventDefault();
            }
            if (($('#idvivienda').val()==$('#hidden_idresidencia').val()) && ($('#mds_gerontologia-residencia').val()=='')){
                alert('Debe completar el nombre de la residencia')
                $('#mds_gerontologia-residencia').css('border-color', 'red');
                e.preventDefault();
            }
        });
        $('input[name=\"mds_gerontologia[idpersona]\"]').on('input', function() {
            $('#txt_mensajeLocalidad').html('');
         });

        $('#btn_dni').click(function(){
            const numeroDNI = $('#txtDNI_search').val();
            if (numeroDNI){
                getPersonaByDNI(numeroDNI);
            } else {
                $('#divDatepicker').hide();
                $('#campos').hide();
                $('#campos2').hide();
                alert('Debe ingresar un DNI');
            }           
        });
      
        $('#txtDNI_search').on('input', function () { 
            this.value = this.value.replace(/[^0-9]/g,'');
            $('#txtDNI_search').css('border-color', '#ccc');
        });

    });"
); ?>
<script>
    function cargarResultadoSocial() {
        let situacionfamiliar = $('#situacionFamiliarValor').find(':selected').data('valor');
        let relaciosocial = $('#relacionesSocialesValor').find(':selected').data('valor');
        let redsocial = $('#redSocialValor').find(':selected').data('valor');
        let suma = (situacionfamiliar && relaciosocial && redsocial) ? parseInt(situacionfamiliar) + parseInt(relaciosocial) + parseInt(redsocial) : '';
        $('#mds_gerontologia_respuesta-ev_social_total').val(suma);
    }

    function cargarResultadoABVD() {
        let lavado = $('#abvdLavadoValor').find(':selected').data('valor');
        let vestido = $('#abvdVestidoValor').find(':selected').data('valor');
        let banio = $('#abvdBanioValor').find(':selected').data('valor');
        let movilizacion = $('#abvdMovilizacionValor').find(':selected').data('valor');
        let continencia = $('#abvdContinenciaValor').find(':selected').data('valor');
        let alimentacion = $('#abvdAlimentacionValor').find(':selected').data('valor');

        if ($('#abvdLavadoValor').val() && $('#abvdVestidoValor').val() && $('#abvdBanioValor').val() && $('#abvdMovilizacionValor').val() && $('#abvdContinenciaValor').val() && $('#abvdAlimentacionValor').val()) {
            let sumaABVD = parseInt(lavado) + parseInt(vestido) + parseInt(banio) + parseInt(movilizacion) + parseInt(continencia) + parseInt(alimentacion);
            $('#mds_gerontologia_respuesta-abvd').val(sumaABVD);
        };
    }

    function cargarResultadoAIVD() {
        let capacidadTelefono = $('#aivdCapacidadTelefonoValor').find(':selected').data('valor');
        let compras = $('#aivdComprasValor').find(':selected').data('valor');
        let preparacionComida = $('#aivdPreparacionComidaValor').find(':selected').data('valor');
        let cuidadoCasa = $('#aivdCuidadoCasaValor').find(':selected').data('valor');
        let lavadoRopa = $('#aivdLavadoRopaValor').find(':selected').data('valor');
        let usoTransporte = $('#aivdUsoTransporteValor').find(':selected').data('valor');
        let responsabilidadMedicacion = $('#aivdResponsabilidadMedicacionValor').find(':selected').data('valor');
        let asuntosEconomicos = $('#aivdAsuntosEconomicosValor').find(':selected').data('valor');

        if ($('#aivdCapacidadTelefonoValor').val() && $('#aivdComprasValor').val() && $('#aivdPreparacionComidaValor').val() && $('#aivdCuidadoCasaValor').val() && $('#aivdLavadoRopaValor').val() && $('#aivdUsoTransporteValor').val() && $('#aivdResponsabilidadMedicacionValor').val() && $('#aivdAsuntosEconomicosValor').val()) {
            let sumaAIVD = parseInt(capacidadTelefono) + parseInt(compras) + parseInt(preparacionComida) + parseInt(cuidadoCasa) + parseInt(lavadoRopa) + parseInt(usoTransporte) + parseInt(responsabilidadMedicacion) + parseInt(asuntosEconomicos);
            $('#mds_gerontologia_respuesta-aivd').val(sumaAIVD);
        };
    }

    function getRenaper(dni) {
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni_campo, function(data) {
            if (data.status == "error") {
                $("#txt_mensaje").html("<b>Error!</b><i>" + data.message + "</i>");
                $("#txt_mensajeLocalidad").html(".");

                limpiarDatos();
            } else {
                let nombre = "";
                let apellido = "";
                let domicilio = "";
                let localidad = "";
                let fecha_nacimiento = "";
                //let sexo = "";
                let nacionalidad = "";
                $.each(data, function(ind, elem) {
                    //nacionalidad = JSON.stringify(data);//esto lo use para ver todo lo que traia de renaper
                    //$("#detalle").val(nacionalidad);//lo plante aca porque era un texto largo
                    if (ind == 'records') {
                        nombre = elem[0].nombres;
                        apellido = elem[0].apellido;
                        fecha_nacimiento = elem[0].fechaNacimiento;
                        foto = elem[0].foto;
                    }
                });
                $("#hidden_nueva_persona").val('0');
                nombre = corregir_palabra(nombre);
                $("#nombre_persona").val(nombre);
                apellido = corregir_palabra(apellido);
                $("#apellido_persona").val(apellido);
                $("#fecha_nacimiento").val(fecha_nacimiento);
                $("#nacionalidad_persona").val('');
                $("#sexo_persona").val('');
                $("#renaper_foto").attr("src", foto);
                habilitar_controles();
                $('#txt_mensaje').html("");
                $('#txt_mensajeLocalidad').html("");
                $("#btnContacto").prop("disabled", true);
            }
        });
    }

    function getPersonaByDNI(numeroDNI) {
        $("#div_duplicados").hide();
        $('#txt_mensaje').html("Buscando datos de la Persona...");
        $('#txt_mensajeLocalidad').html(".");
        const token = "<?= $token ?>";
        const headers = {
            Authorization: `Bearer ${token}`,
        };
        const url = "<?= env('ENDPOINT_API_SUR_NEST') ?>/<?= env('ENDPOINT_API_SUR_NEST_PERSONA_GET') ?>";
        const params = `/${numeroDNI}`;
        $.ajax({
            url: url + params,
            type: "GET",
            dataType: "json",
            async: true,
            headers,
            success: function(data) {
                if (data?.status === "success" && data?.records) {
                    const record = data.records;
                    $("#txtDNI_search").val(`${record.apellido} ${record.nombre} (${record.documento})`)
                    $("#idpersona").val(record.idpersona);
                    $('input[name="Mds_gerontologia[idpersona]"]').val(record.idpersona)
                    $("#txt_mensaje").html("");
                    $("#txt_mensajeLocalidad").html("");
                    $('#campos').show();
                    $('#campos2').show();
                    $('#divDatepicker').show();
                    $('#btnSave').show();
                } else {
                    $('#txt_mensaje').html(`No se encontró en el sistema una persona con DNI: ${numeroDNI}.<br>Por favor, debe registrarla haciendo click en el botón '+'`);
                    $('#txt_mensaje').css('color', 'red');
                    $('#txtDNI_search').css('border-color', 'red');
                    $('input[name="Mds_gerontologia[idpersona]"]').val('')
                    $("#btnContacto").prop("disabled", false);
                    $('#txt_mensajeLocalidad').html(".");
                    $('#campos').hide();
                    $('#campos2').hide();
                    $('#divDatepicker').hide();
                    $('#btnSave').hide();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                let errorMessage = "<span style='color: red'><b style='color: red'>Error!</b> <i>";
                const responseJSON = xhr.responseJSON;

                if (responseJSON && responseJSON.message) {
                    errorMessage += `${responseJSON.message}`;
                } else {
                    errorMessage += `Ocurrió un error al buscar a la persona`;
                }

                errorMessage += "</i></span>"
                $("#txt_mensaje").html(errorMessage);
                $("#txt_mensajeLocalidad").html(".");
            },
        });
    }
</script>