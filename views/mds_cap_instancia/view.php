<?php

use app\models\Mds_cap_campania;
use app\models\Mds_cap_capacitacion;
use app\models\Mds_cap_docente;
use app\models\Mds_cap_docente_instancia;
use app\models\Mds_cap_instancia;
use app\models\Sds_com_persona;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use app\models\Mds_cap_inscripcion;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_instancia */


?>


<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="/">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="mds-cap-instancia-form">

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">

                    <?php $form = ActiveForm::begin(); ?>
                    <div class="panel-group" id="accordion_detalle">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_detalle" href="#detalle">
                                        Datos Instancia
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle" class="accordion-body collapse in">
                                <div class="panel-body" id="detalle_content">

                                    <!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->
                                    <div class="row">
                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                        <div class="col-md-12">
                                            <?= $form->field($model, 'idcapacitacion')->widget(Select2::classname(), [
                                                'data' => ArrayHelper::map(
                                                    Mds_cap_capacitacion::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                                                    'idcapacitacion',
                                                    'descripcion'
                                                ),
                                                'options' => ['id' => 'cmb_item', 'placeholder' => 'Seleccionar capacitación ...', 'disabled' => true],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ])->label('Capacitación');
                                            ?>

                                        </div>
                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                        <div class="col-md-12">
                                            <?= $form->field($model, 'descripcion')->textInput(['id' => 'txt_descripcion', 'maxlength' => true, 'disabled' => true]); ?>
                                        </div>
                                    </div>
                                    <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'alias')->textInput(['id' => 'txt_alias', 'maxlength' => true, 'disabled' => true])->hint('Ej: El link queda: cumbre.neuquen.gov.ar/alias'); ?>
                                        </div>
                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->

                                        <div class="col-md-4">
                                            <?= $form->field($model, 'presencial')->dropDownList(
                                                [
                                                    Mds_cap_instancia::MODALIDAD_PRESENCIAL => "Presencial",
                                                    Mds_cap_instancia::MODALIDAD_VIRTUAL => "Virtual",
                                                    Mds_cap_instancia::MODALIDAD_DUAL => "Dual",
                                                ],
                                                [
                                                    'prompt' => '-- Seleccione una opción --',
                                                    'disabled' => true,
                                                ]
                                            ) ?>
                                        </div>
                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'lugar')->textInput(['disabled' => true]) ?>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'capacidad')->textInput(['id' => 'txt_capacidad', 'maxlength' => true, 'disabled' => true])->hint('Poner 0 (cero) si es ilimitado.'); ?>
                                        </div>
                                        <!--------------------------------------------------------------------------------------------------------------------------- -->

                                        <div class="col-md-4">
                                            <?= $form->field($model, 'lista_espera')->dropDownList(
                                                [
                                                    Mds_cap_instancia::LISTA_ESPERA_NO => "No",
                                                    Mds_cap_instancia::LISTA_ESPERA_SI => "Si",
                                                ],
                                                [
                                                    'prompt' => '-- Habilita Lista de Espera? --',
                                                    'id' => 'cmb_espera',
                                                    'disabled' => true
                                                ]
                                            ) ?>
                                        </div>

                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'capacidad_espera')->textInput(
                                                [
                                                    'id' => 'txt_capacidad_espera',
                                                    'type' => 'number',
                                                    'disabled' => true
                                                ]
                                            ); ?>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'cant_horas')->textInput(['id' => 'txt_cant_horas', 'maxlength' => true, 'disabled' => true]); ?>
                                        </div>
                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'privacidad')->dropDownList(
                                                [
                                                    Mds_cap_instancia::PRIVACIDAD_PRIVADA => "Privada",
                                                    Mds_cap_instancia::PRIVACIDAD_PUBLICA => "Pública",
                                                ],
                                                [
                                                    'prompt' => '-- Seleccione una opción --',
                                                    'disabled' => true,
                                                ]
                                            ) ?>
                                        </div>

                                        <div class="col-md-4">
                                            <!-- ?= $form->field($model, 'estado')->checkBox(['id' => 'check_estado', 'checked' => true, 'label' => 'Activa']) ?-->
                                            <?= $form->field($model, 'estado')->dropDownList(
                                                [
                                                    Mds_cap_instancia::ESTADO_ACTIVA => "Activa",
                                                    Mds_cap_instancia::ESTADO_NO_ACTIVA => "No Activa",
                                                ],
                                                [
                                                    'prompt' => '-- Seleccione una opción --',
                                                    'disabled' => true,
                                                ]
                                            ) ?>
                                        </div>
                                        <!--------------------------------------------------------------------------------------------------------------------------- -->


                                    </div>
                                    <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?php
                                            if ($model->desde != null) {
                                                $model->desde = date('d/m/Y', strtotime(str_replace('/', '-', $model->desde)));
                                            }
                                            echo $form->field($model, 'desde')->widget(DatePicker::ClassName(), [
                                                'name' => 'check_issue_date_desde',
                                                'language' => 'es',
                                                'readonly' => false,
                                                'layout' => '{picker}{input}{remove}',
                                                'options' => [
                                                    'id' => 'fecha_desde',
                                                    'class' => 'form-control input-md',
                                                    'disabled' => true
                                                ],
                                                'pluginOptions' => [
                                                    'value' => null,
                                                    'format' => 'dd/mm/yyyy',
                                                    //'endDate' => date('d/m/Y'), //esto es para que no me deje poner fechas mas alla de la actual
                                                    'todayHighlight' => true,
                                                    'autoclose' => true,
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                        <div class="col-md-4">
                                            <?php
                                            if ($model->hasta != null) {
                                                $model->hasta = date('d/m/Y', strtotime(str_replace('/', '-', $model->hasta)));
                                            }
                                            echo $form->field($model, 'hasta')->widget(DatePicker::ClassName(), [
                                                'name' => 'check_issue_date',
                                                'language' => 'es',
                                                'readonly' => false,
                                                'layout' => '{picker}{input}{remove}',
                                                'options' => [
                                                    'id' => 'fecha_hasta',
                                                    'class' => 'form-control input-md',
                                                    'disabled' => true,
                                                ],
                                                'pluginOptions' => [
                                                    'value' => null,
                                                    'format' => 'dd/mm/yyyy',
                                                    //'endDate' => date('d/m/Y'), //esto es para que no me deje poner fechas mas alla de la actual
                                                    'todayHighlight' => true,
                                                    'autoclose' => true,
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->

                                        <div class="col-md-4">
                                            <?= $form->field($model, 'idcampania')->widget(Select2::classname(), [
                                                'data' => ArrayHelper::map(
                                                    Mds_cap_campania::find()->all(),
                                                    'idcampania',
                                                    'descripcion'
                                                ),
                                                'options' => ['disabled' => true],
                                                'size' => Select2::MEDIUM,
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ]);
                                            ?>
                                        </div>

                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?php
                                            if ($model->fecha_inscripcion != null) {
                                                $model->fecha_inscripcion = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_inscripcion)));
                                            }
                                            echo $form->field($model, 'fecha_inscripcion')->widget(DatePicker::ClassName(), [
                                                'name' => 'check_issue_date',
                                                'language' => 'es',
                                                'readonly' => false,
                                                'layout' => '{picker}{input}{remove}',
                                                'options' => [
                                                    'id' => 'fecha_inscripcion',
                                                    'class' => 'form-control input-md',
                                                    'disabled' => true,

                                                ],
                                                'pluginOptions' => [
                                                    'value' => null,
                                                    'format' => 'dd/mm/yyyy',
                                                    //'endDate' => date('d/m/Y'), //esto es para que no me deje poner fechas mas alla de la actual
                                                    'todayHighlight' => true,
                                                    'autoclose' => true,
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->

                                        <div class="col-md-4">
                                            <?php
                                            if ($model->fecha_limite != null) {
                                                $model->fecha_limite = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_limite)));
                                            }
                                            echo $form->field($model, 'fecha_limite')->widget(DatePicker::ClassName(), [
                                                'name' => 'check_issue_date',
                                                'language' => 'es',
                                                'readonly' => false,
                                                'layout' => '{picker}{input}{remove}',
                                                'options' => [
                                                    'id' => 'fecha_limite',
                                                    'class' => 'form-control input-md',
                                                    'disabled' => true,

                                                ],
                                                'pluginOptions' => [
                                                    'value' => null,
                                                    'format' => 'dd/mm/yyyy',
                                                    //'endDate' => date('d/m/Y'), //esto es para que no me deje poner fechas mas alla de la actual
                                                    'todayHighlight' => true,
                                                    'autoclose' => true,
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                        
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'tipo')->dropDownList(
                                                [
                                                    Mds_cap_instancia::TIPO_CUMBRE => "Inscripcion por Cumbre",
                                                    Mds_cap_instancia::TIPO_EXTERNO => "Inscripción link externo",
                                                    Mds_cap_instancia::TIPO_VISIBLE => "Solo visible -sin inscripción",
                                                ],
                                                [
                                                    'prompt' => '-- Seleccione una opción --',
                                                    'disabled' => true,
                                                ]
                                            ) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6" style="width: 48%; float:left; margin: 1%; border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'inscripcion_externa')->checkBox(['selected' => $model->inscripcion_externa, 'id' => 'inscripcion_externa', 'disabled' => 'disabled']) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" id="div_enlace" <?php if ($model->inscripcion_externa == 0) {
                                                                                            echo 'style="display:none;"';
                                                                                        }; ?>>
                                                    <?= $form->field($model, 'enlace_ext')->textInput(['id' => 'enlace_ext', 'disabled' => true]); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="width: 48%; float:left; margin: 1%; border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'notificar_admin')->checkBox(['selected' => $model->notificar_admin, 'id' => 'notificar_admin', 'disabled' => 'disabled']) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" id="div_email_admin" <?php if ($model->notificar_admin != Mds_cap_instancia::CONST_NOT_ADMIN) {
                                                                                                echo 'style="display:none;"';
                                                                                            }; ?>>
                                                    <?= $form->field($model, 'email_administrador')->textInput(['id' => 'email_administrador', 'disabled' => true]); ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!--------------------------------------------------------------------------------------------------------------------------- -->
                                    <?= $form->field($model, 'titulo_dato_adicional')->textInput(['id' => 'titulo_dato_adicional', 'disabled' => true, 'maxlength' => true])->hint('Si desea que en la inscripción se solicite un dato adicional, ingrese el titulo del dato o la pregunta a mostrar.'); ?>
                                    <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                    <?= $form->field($model, 'detalle')->widget(CKEditor::className(), [
                                        'options' => [
                                            'rows' => 6,
                                            'disabled' => true
                                        ],
                                        'preset' => 'custom',
                                        'clientOptions' => [
                                            /* 'toolbarGroups' => [
                                      ['name' => 'document', 'groups' => ['mode', 'document', 'doctools' ]],
                                      ['name' => 'clipboard', 'groups' => ['clipboard', 'undo' ]],
                                      ['name' => 'editing', 'groups' => ['find', 'selection', 'spellchecker' ]],
                                      //'/',
                                      ['name' => 'basicstyles', 'groups' => ['basicstyles' ]],
                                      ['name' => 'paragraph', 'groups' => ['list', 'indent', 'blocks', 'align', 'bidi' ]],
                                      //['name' => 'links'],
                                      // ['name' => 'insert', 'groups' => ['table', 'horizontalrule', 'specialchar' ]],
                                      //'/',
                                      ['name' => 'styles'],
                                      ['name' => 'colors'],
                                      ['name' => 'tools'],
                                      ['name' => 'others']
                                      ], */
                                            'toolbar' => [
                                                [
                                                    'name' => 'row1',
                                                    'items' => [
                                                        //'Source', '-',
                                                        'Bold', 'Italic', 'Underline', 'Strike', '-',
                                                        'Subscript', 'Superscript', 'RemoveFormat', '-',
                                                        'TextColor', 'BGColor', '-',
                                                        'NumberedList', 'BulletedList', '-',
                                                        //'Outdent', 'Indent', '-', 'Blockquote', '-',
                                                        'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                                                        'Table', 'HorizontalRule', 'SpecialChar', '-',
                                                        'Undo', 'Redo', 'SelectAll', '-',
                                                        'NewPage', 'Print', 'Templates', '-',
                                                        'ShowBlocks', '-',
                                                        'Maximize',
                                                        'Link',
                                                        // 'Link', 'Unlink', 'Anchor', '-',
                                                    ],
                                                ],
                                                [
                                                    'name' => 'row2',
                                                    'items' => [
                                                        //'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-',

                                                        'Format', 'Font', 'FontSize', 'Styles',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ])
                                    ?>
                                    <?= $form->field($model, 'observacion')->widget(CKEditor::className(), [
                                        'options' => [
                                            'rows' => 3,
                                            'disabled' => true
                                        ],
                                        'preset' => 'custom',
                                        'clientOptions' => [
                                            /* 'toolbarGroups' => [
                                      ['name' => 'document', 'groups' => ['mode', 'document', 'doctools' ]],
                                      ['name' => 'clipboard', 'groups' => ['clipboard', 'undo' ]],
                                      ['name' => 'editing', 'groups' => ['find', 'selection', 'spellchecker' ]],
                                      //'/',
                                      ['name' => 'basicstyles', 'groups' => ['basicstyles' ]],
                                      ['name' => 'paragraph', 'groups' => ['list', 'indent', 'blocks', 'align', 'bidi' ]],
                                      //['name' => 'links'],
                                      // ['name' => 'insert', 'groups' => ['table', 'horizontalrule', 'specialchar' ]],
                                      //'/',
                                      ['name' => 'styles'],
                                      ['name' => 'colors'],
                                      ['name' => 'tools'],
                                      ['name' => 'others']
                                      ], */
                                            'toolbar' => [
                                                [
                                                    'name' => 'row1',
                                                    'items' => [
                                                        //'Source', '-',
                                                        'Bold', 'Italic', 'Underline', 'Strike', '-',
                                                        'Subscript', 'Superscript', 'RemoveFormat', '-',
                                                        'TextColor', 'BGColor', '-',
                                                        'NumberedList', 'BulletedList', '-',
                                                        //'Outdent', 'Indent', '-', 'Blockquote', '-',
                                                        'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                                                        'Table', 'HorizontalRule', 'SpecialChar', '-',
                                                        'Undo', 'Redo', 'SelectAll', '-',
                                                        'NewPage', 'Print', 'Templates', '-',
                                                        'ShowBlocks', '-',
                                                        'Maximize',
                                                        'Link',
                                                        // 'Link', 'Unlink', 'Anchor', '-',
                                                    ],
                                                ],
                                                [
                                                    'name' => 'row2',
                                                    'items' => [
                                                        //'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-',

                                                        'Format', 'Font', 'FontSize', 'Styles',
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ])
                                    ?>
                                    <!--------------------------------------------------------------------------------------------------------------------------- -->

                                    <?= $form->field($model, 'link_video')->textInput(['id' => 'link_video', 'disabled' => true, 'maxlength' => true])->hint('Ej. https://www.youtube.com/watch?v=L4ND34Vkrg8'); ?>

                                    <!--------------------------------------------------------------------------------------------------------------------------- -->
                                    <?php   
                                         $ver_panel_img=(($model->imagen_path!=null) || ($model->imagen!=null));                                      
                                    ?>    
                                    <div class="panel-group" id="accordion_salud" style="display:<?= $ver_panel_img ? "block" : "none" ?>">
                                        <div class="panel panel-accordion">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_salud" href="#imagen">
                                                        Archivo Adjunto
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="imagen" class="accordion-body collapse in">
                                                <div class="panel-body" id="salud_content" style="text-align: center">
                                                    <!-- Valida si es pdf -->
                                                    <?php if (stripos($model->imagen, 'application/pdf;base64,') != false) : ?>
                                                        <div class="row">
                                                            <object width="90%" height="500px" type="application/pdf" data="<?php echo $model->imagen; ?>">
                                                                <p>Archivo Adjunto no disponible.</p>
                                                            </object>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="row" style="max-height:500px">
                                                            <div class='col-md-12' align="center" ;> <br>
                                                                <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:70%;' id='base64image' src='
                                                                
                                                                <?php
                                                                    if ($model->imagen_path!=null)
                                                                    {
                                                                        echo Url::base() . '/uploads/instancias/'.$model->imagen_path ;
                                                                    }
                                                                    else
                                                                    {
                                                                        if ($model->imagen!=null)
                                                                        {
                                                                            echo $model->imagen;

                                                                        }
                                                                    }
                                                                ?>
                                                                ' />
                                                                <br>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel-group" id="accordion_detalle">
                                        <div class="panel panel-accordion">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_detalle" href="#detalle">
                                                        Datos para generar Certificado
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="detalle" class="accordion-body collapse in">
                                                <div class="panel-body" id="detalle_content">


                                                    <?= $form->field($model, 'area_certificado')->textInput(['id' => 'area_certificado', 'maxlength' => true, 'disabled' => true])->hint('Especificar todas las areas intervinientes, separadas por coma, tal como deben aparecer en el certificado.'); ?>
                                                    <!-------------------------------------------------------------------------------------------------------------------------- -->

                                                    <?= $form->field($model, 'resolucion_aval')->textInput(['id' => 'resolucion_aval', 'maxlength' => true, 'disabled' => true])->hint('Detallar, en caso que corresponda, bajo que norma, ley, resolucion y/o aval se enmarca la capacitación.'); ?>
                                                    <!-------------------------------------------------------------------------------------------------------------------------- -->
                                                    <!-------------------------------------------------------------------------------------------------------------------------- -->
                                                    <?php   
                                                        $ver_panel_img2=(($model->logo_principal_path!=null) || ($model->logo_principal!=null));                                      
                                                    ?> 
                                                    <div class="panel-group" id="accordion_salud" style="display:<?= $ver_panel_img2 ? "block" : "none" ?>">
                                                        <div class="panel panel-accordion">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_salud" href="#logo_principal">
                                                                        Archivo Adjunto Logo Principal
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="logo_principal" class="accordion-body collapse in">
                                                                <div class="panel-body" id="salud_content" style="text-align: center">
                                                                    <!-- Valida si es pdf -->
                                                                    <?php if (stripos($model->logo_principal, 'application/pdf;base64,') != false) : ?>
                                                                        <div class="row">
                                                                            <object width="90%" height="500px" type="application/pdf" data="<?php echo $model->logo_principal; ?>">
                                                                                <p>Archivo Adjunto no disponible.</p>
                                                                            </object>
                                                                        </div>
                                                                    <?php else : ?>
                                                                        <div class="row" style="max-height:500px">
                                                                            <div class='col-md-12' align="center" ;> <br>
                                                                                <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:70%;' id='base64image' src='
                                                                                
                                                                                <?php
                                                                                    if ($model->logo_principal_path!=null)
                                                                                    {
                                                                                        echo Url::base() . '/uploads/instancias/'.$model->logo_principal_path ;
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        if ($model->logo_principal!=null)
                                                                                        {
                                                                                            echo $model->logo_principal;

                                                                                        }
                                                                                    }
                                                                                ?>
                                                                                ' />
                                                                                <br>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-------------------------------------------------------------------------------------------------------------------------- -->
                                                    <?php   
                                                        $ver_panel_img3=(($model->logo_extra_path!=null) || ($model->logo_extra!=null));                                      
                                                    ?> 
                                                    <div class="panel-group" id="accordion_salud" style="display:<?= $ver_panel_img3 ? "block" : "none" ?>">
                                                        <div class="panel panel-accordion">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_salud" href="#logo_extra">
                                                                        Archivo Adjunto Logo Extra
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="logo_extra" class="accordion-body collapse in">
                                                                <div class="panel-body" id="salud_content" style="text-align: center">
                                                                    <!-- Valida si es pdf -->
                                                                    <?php if (stripos($model->logo_extra, 'application/pdf;base64,') != false) : ?>
                                                                        <div class="row">
                                                                            <object width="90%" height="500px" type="application/pdf" data="<?php echo $model->logo_extra; ?>">
                                                                                <p>Archivo Adjunto no disponible.</p>
                                                                            </object>
                                                                        </div>
                                                                    <?php else : ?>
                                                                        <div class="row" style="max-height:500px">
                                                                            <div class='col-md-12' align="center" ;> <br>
                                                                                <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:70%;' id='base64image' src='
                                                                                
                                                                                <?php
                                                                                    if ($model->logo_extra_path!=null)
                                                                                    {
                                                                                        echo Url::base() . '/uploads/instancias/'.$model->logo_extra_path ;
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        if ($model->logo_extra!=null)
                                                                                        {
                                                                                            echo $model->logo_extra;

                                                                                        }
                                                                                    }
                                                                                ?>
                                                                                ' />
                                                                                <br>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-------------------------------------------------------------------------------------------------------------------------- -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php
                                                            if (isset($model)) {
                                                                $roles = Mds_cap_docente_instancia::find()->where(['id_instancia' => $model->idinstancia, 'firmante' => 1])->all();
                                                                $roles_id = array();
                                                                foreach ($roles as $rol) {
                                                                    $roles_id[] = $rol['id_docente'];
                                                                }
                                                                $model->docentes = $roles_id;
                                                            }
                                                            ?>
                                                            <?=
                                                            $form->field($model, 'docentes')->widget(Select2::classname(), [
                                                                'data' => ArrayHelper::map(
                                                                    Mds_cap_docente::find()->all(),
                                                                    'idpersona',
                                                                    function ($model) {
                                                                        $cap = Mds_cap_docente::findOne($model['idpersona']);
                                                                        $per = Sds_com_persona::findOne($cap['idpersona']);
                                                                        return $per['nombre'] . " " . $per['apellido'] . " - DNI: " . $per['documento'];
                                                                    }
                                                                ),
                                                                'options' => ['id' => 'docentes', 'placeholder' => '', 'multiple' => true],
                                                                'size' => Select2::MEDIUM,
                                                                'pluginOptions' => [
                                                                    'tags' => true,
                                                                    'tokenSeparators' => [',', ' '],
                                                                    //'maximumInputLength' => 50,
                                                                    'allowClear' => true
                                                                ],
                                                            ])->label('Docentes Firmantes');
                                                            ?>
                                                        </div>
                                                    </div>

                                                    <!-------------------------------------------------------------------------------------------------------------------------- -->

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php
                                                            if (isset($model)) {
                                                                $roles2 = Mds_cap_docente_instancia::find()->where(['id_instancia' => $model->idinstancia, 'firmante' => 0])->all();
                                                                $roles_id2 = array();
                                                                foreach ($roles2 as $rol2) {
                                                                    $roles_id2[] = $rol2['id_docente'];
                                                                }
                                                                $model->docentes_no_firmantes = $roles_id2;
                                                            }
                                                            ?>
                                                            <?=
                                                            $form->field($model, 'docentes_no_firmantes')->widget(Select2::classname(), [
                                                                'data' => ArrayHelper::map(
                                                                    Mds_cap_docente::find()->all(),
                                                                    'idpersona',
                                                                    function ($model) {
                                                                        $cap2 = Mds_cap_docente::findOne($model['idpersona']);
                                                                        $per2 = Sds_com_persona::findOne($cap2['idpersona']);
                                                                        return $per2['nombre'] . " " . $per2['apellido'] . " - DNI: " . $per2['documento'];
                                                                    }
                                                                ),
                                                                'options' => ['id' => 'docentes_no_firmantes', 'placeholder' => '', 'multiple' => true],
                                                                'size' => Select2::MEDIUM,
                                                                'pluginOptions' => [
                                                                    'tags' => true,
                                                                    'tokenSeparators' => [',', ' '],
                                                                    //'maximumInputLength' => 50,
                                                                    'allowClear' => true
                                                                ],
                                                            ])->label('Docentes que Reciben Certificados');
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel-group" id="accordion_salud" style="display:<?= $model->imagen ? "block" : "none" ?>">
                                        <div class="panel panel-accordion">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_salud" href="#imagen">
                                                        Datos Estadisticos
                                                    </a>
                                                </h4>
                                            </div>
                                            <div class="accordion-body collapse in">
                                                <div class="panel-body" id="salud_content">
                                                    <?php
                                                    $total_cap_inscripciones = Mds_cap_inscripcion::find()
                                                        ->where(['idcapinstancia' => $model->idinstancia])
                                                        ->all();
                                                    $num_pers_inscrip = count($total_cap_inscripciones);

                                                    $unas_cap_inscripciones = Mds_cap_inscripcion::find()
                                                        ->where(['idcapinstancia' => $model->idinstancia, 'termino' => '2'])
                                                        ->all();
                                                    $num_pers_aprob = count($unas_cap_inscripciones);

                                                    $cert_no_generados = Mds_cap_inscripcion::find()
                                                        ->where(['idcapinstancia' => $model->idinstancia, 'estado_cert' => '0', 'termino' => '2'])
                                                        ->all();
                                                    $num_cert_no_generados = count($cert_no_generados);
                                                    $num_cert_generados = $num_pers_aprob - $num_cert_no_generados;
                                                    $cert_espera_firmas = Mds_cap_inscripcion::find()
                                                        ->where(['idcapinstancia' => $model->idinstancia, 'estado_cert' => '1', 'termino' => '2'])
                                                        ->all();
                                                    $num_cert_espera_firmas = count($cert_espera_firmas);

                                                    $cert_listo = Mds_cap_inscripcion::find()
                                                        ->where(['idcapinstancia' => $model->idinstancia, 'estado_cert' => '2', 'termino' => '2'])
                                                        ->all();
                                                    $num_cert_listo = count($cert_listo);
                                                    $no_culminaron =  $num_pers_inscrip - $num_pers_aprob;
                                                    ?>
                                                    <div class="row" style="margin-top:16px">
                                                        <div class="col-md-4">
                                                            Personas inscriptas en la instancia: <strong><?= $num_pers_inscrip; ?></strong>
                                                        </div>
                                                        <div class="col-md-4">
                                                            Personas que aprobaron la instancia: <strong><?= $num_pers_aprob; ?></strong>
                                                        </div>
                                                        <div class="col-md-4">
                                                            Personas que aun no han aprobado la instancia: <strong><?= $no_culminaron; ?></strong>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top:16px">
                                                        <div class="col-md-4">
                                                            Numero de Certificados generados: <strong><?= $num_cert_generados; ?></strong>
                                                        </div>
                                                        <div class="col-md-4">
                                                            Numero de Certificados generados en espera de firma: <strong><?= $num_cert_espera_firmas; ?></strong>
                                                        </div>
                                                        <div class="col-md-4">
                                                            Numero de Certificados generados completados: <strong><?= $num_cert_listo; ?></strong>
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
                    <?php ActiveForm::end(); ?>
                    <div class="row justify-content-between">
                        <div class="col-md-6">
                            <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    $("#cmb_item").change(function() {
        $("#txt_descripcion").val($("#cmb_item option:selected").text());
    });
</script>