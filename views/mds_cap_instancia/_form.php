<?php

use app\models\Mds_cap_campania;
use app\models\Mds_cap_capacitacion;
use app\models\Mds_cap_docente;
use app\models\Mds_cap_docente_instancia;
use app\models\Mds_cap_instancia;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Sds_com_persona;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_instancia */
/* @var $form yii\widgets\ActiveForm */

$idcontacto  = Yii::$app->user->identity->idcontacto;
$idusuario = Yii::$app->user->identity->idusuario;
$permiso_global = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_CAP_GLOBAL . ")")->one();
$permiso_global = $permiso_global != null ? 1 : 0;

$this->title = 'Instancia';
$this->params['breadcrumbs'][] = $this->title;


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
                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);

                    $model->temp_imagen = $model->imagen;
                    $model->temp_logo1 = $model->logo_extra;
                    $model->temp_logo_princ = $model->logo_principal;
                    ?>

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
                                                'data' => $filterCapacitaciones,
                                                'options' => ['id' => 'cmb_item', 'placeholder' => 'Seleccionar capacitación ...'],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ])->label('Capacitación');
                                            ?>

                                        </div>

                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                        <div class="col-md-12">
                                            <?= $form->field($model, 'descripcion')->textInput(['id' => 'txt_descripcion', 'maxlength' => true]); ?>
                                        </div>
                                    </div>
                                    <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'alias')->textInput(['id' => 'txt_alias', 'maxlength' => true])->hint('Ej: El link queda: cumbre.neuquen.gov.ar/alias'); ?>
                                        </div>
                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'presencial')->dropDownList(
                                                [
                                                    Mds_cap_instancia::MODALIDAD_PRESENCIAL => "Presencial",
                                                    Mds_cap_instancia::MODALIDAD_VIRTUAL => "Virtual",
                                                    Mds_cap_instancia::MODALIDAD_DUAL => "Dual",
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            ) ?>
                                        </div>
                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'lugar')->textInput() ?>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'capacidad')->textInput(['id' => 'txt_capacidad', 'type' => 'number', 'min' => 0, 'pattern' => '/^[0-9]+$/u', 'maxlength' => true])->hint('Poner 0 (cero) si es ilimitado.'); ?>
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
                                                    'id' => 'cmb_espera'
                                                ]
                                            ) ?>
                                        </div>

                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'capacidad_espera')->textInput(
                                                [
                                                    'id' => 'txt_capacidad_espera',
                                                    'type' => 'number',
                                                    //       'disabled' => $model->lista_espera == 0
                                                ]
                                            ); ?>
                                        </div>
                                    </div>

                                    <!-- ------------------------------------------------------------------------------------------------------------------------ -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'cant_horas')->textInput(['id' => 'txt_cant_horas', 'type' => 'number', 'min' => 0, 'pattern' => '/^[0-9]+$/u', 'maxlength' => true]); ?>
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
                                                    'id' => 'cmb_privacidad',
                                                    'onchange' =>   'cargarPrivacidad();'
                                                ]
                                            ) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'estado')->dropDownList(
                                                [
                                                    Mds_cap_instancia::ESTADO_ACTIVA => "Activa",
                                                    Mds_cap_instancia::ESTADO_NO_ACTIVA => "No Activa",
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            ) ?>
                                        </div>

                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->

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
                                                    'disabled' => false
                                                ],
                                                'pluginOptions' => [
                                                    'value' => null,
                                                    'format' => 'dd/mm/yyyy',
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
                                                    'disabled' => false,
                                                ],
                                                'pluginOptions' => [
                                                    'value' => null,
                                                    'format' => 'dd/mm/yyyy',
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
                                                'options' => ['prompt' => '-- Seleccione una opción --'],
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
                                                    'disabled' => false,

                                                ],
                                                'pluginOptions' => [
                                                    'value' => null,
                                                    'format' => 'dd/mm/yyyy',
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
                                                    'disabled' => false,

                                                ],
                                                'pluginOptions' => [
                                                    'value' => null,
                                                    'format' => 'dd/mm/yyyy',
                                                    'todayHighlight' => true,
                                                    'autoclose' => true,
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                        <!-- ------------------------------------------------------------------------------------------------------------------------ -->

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

                                                ]
                                            ) ?>
                                        </div>

                                    </div>
                                    <!-- ------------------------------------------------------------------------------------------------------------------------ -->

                                    <div class="row">
                                        <div class="col-md-6" style="width: 48%; float:left; margin: 1%; border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'inscripcion_externa')->checkBox(['selected' => $model->inscripcion_externa, 'id' => 'inscripcion_externa']) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" id="div_enlace" <?php if ($model->inscripcion_externa == 0) {
                                                                                            echo 'style="display:none;"';
                                                                                        }; ?>>
                                                    <?= $form->field($model, 'enlace_ext')->textInput(['id' => 'enlace_ext']); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="width: 48%; float:left; margin: 1%; border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($model, 'notificar_admin')->checkBox(['selected' => $model->notificar_admin, 'id' => 'notificar_admin']) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" id="div_email_admin" <?php if ($model->notificar_admin != Mds_cap_instancia::CONST_NOT_ADMIN) {
                                                                                                echo 'style="display:none;"';
                                                                                            }; ?>>
                                                    <?= $form->field($model, 'email_administrador')->textInput(['id' => 'email_administrador']); ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>



                                    <!-- xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->

                                    <?= $form->field($model, 'titulo_dato_adicional')->textInput(['id' => 'titulo_dato_adicional', 'maxlength' => true])->hint('Si desea que en la inscripción se solicite un dato adicional, ingrese el titulo del dato o la pregunta a mostrar.'); ?>

                                    <!--------------------------------------------------------------------------------------------------------------------------- -->
                                    <?= $form->field($model, 'detalle')->widget(CKEditor::className(), [
                                        'options' => [
                                            'rows' => 6
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
                                            'rows' => 3
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

                                    <?= $form->field($model, 'link_video')->textInput(['id' => 'link_video', 'maxlength' => true])->hint('Ej. https://www.youtube.com/watch?v=L4ND34Vkrg8'); ?>

                                    <!--------------------------------------------------------------------------------------------------------------------------- -->

                                    <div class="row">
                                        <div class='col-md-12'>
                                            <?php
                                            if (($model->imagen == null) && ($model->imagen_path == null)) {
                                                echo $form->field($model, 'temp_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                    ->widget(FileInput::classname(), [
                                                        'options' => ['accept' => 'image/*'],
                                                        'language' => 'es',
                                                        'pluginOptions' => [
                                                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                                            'showCaption' => false,
                                                            'showRemove' => true,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'uploadUrl' => Url::to(['/mds_cap_instancia/update']),
                                                            'maxFileSize' => 100000,
                                                            'previewFileType' => 'file',
                                                            'initialCaption' => $model->imagen_path,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ]
                                                        ],
                                                    ]);
                                            } else {
                                                if ($model->imagen_path != null) {
                                                    //echo $form->field($model, 'temp_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                    echo $form->field($model, 'temp_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                        ->widget(FileInput::classname(), [
                                                            'options' => ['accept' => 'image/*'],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                                                'showCaption' => false,
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'mainClass' => 'input-group-sm',
                                                                'uploadUrl' => Url::to(['/mds_cap_instancia']),
                                                                'maxFileSize' => 100000,
                                                                'previewFileType' => 'file',
                                                                'initialPreview' => [
                                                                    //Html::img($model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%; text-align: center']),
                                                                    Html::img(Url::base() . "/uploads/instancias/" . $model->imagen_path, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                                                ],
                                                                'overwriteInitial' => true,
                                                                'autoReplace' => true,
                                                                'initialCaption' => $model->imagen_path,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                ]
                                                            ],
                                                            'pluginEvents' => [
                                                                "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ 
                                                                             $('#borrar_imagen').val(true);
                                                                         }",
                                                                "filereset" => "function() {  }",
                                                            ]
                                                        ]);
                                                } else // es el caso para if ($model->imagen != null)
                                                {
                                                    //echo $form->field($model, 'temp_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                    echo $form->field($model, 'temp_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                        ->widget(FileInput::classname(), [
                                                            'options' => ['accept' => 'image/*'],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                                                'showCaption' => false,
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'mainClass' => 'input-group-sm',
                                                                'uploadUrl' => Url::to(['/mds_cap_instancia']),
                                                                'maxFileSize' => 100000,
                                                                'previewFileType' => 'file',
                                                                'initialPreview' => [
                                                                    Html::img($model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%; text-align: center']),
                                                                    //Html::img(Url::base()."/uploads/instancias/".$model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                                                ],
                                                                'overwriteInitial' => true,
                                                                'autoReplace' => true,
                                                                'initialCaption' => $model->imagen,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                ]
                                                            ],
                                                            'pluginEvents' => [
                                                                "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ 
                                                                $('#borrar_imagen').val(true);
                                                            }",
                                                                "filereset" => "function() {  }",
                                                            ]
                                                        ]);
                                                }
                                            }
                                            ?>
                                            <?= $form->field($model, 'borrar_imagen')->hiddenInput(['id' => 'borrar_imagen'])->label(false) ?>
                                        </div>
                                    </div>
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
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?php
                                            if ($model->fecha_publicacion_cert != null) {
                                                $model->fecha_publicacion_cert = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_publicacion_cert)));
                                            }
                                            echo $form->field($model, 'fecha_publicacion_cert')->widget(DatePicker::ClassName(), [
                                                'name' => 'check_issue_date',
                                                'language' => 'es',
                                                'readonly' => false,
                                                'layout' => '{picker}{input}{remove}',
                                                'options' => [
                                                    'id' => 'fecha_publicacion_cert',
                                                    'class' => 'form-control input-md',
                                                    'disabled' => false,

                                                ],
                                                'pluginOptions' => [
                                                    'value' => null,
                                                    'format' => 'dd/mm/yyyy',
                                                    'todayHighlight' => true,
                                                    'autoclose' => true,
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class='col-md-12'>
                                            <?= $form->field($model, 'area_certificado')->textInput(['id' => 'area_certificado', 'maxlength' => true])->hint('Especificar todas las areas intervinientes, separadas por coma, tal como deben aparecer en el certificado.'); ?>
                                        </div>
                                    </div>
                                    <!-------------------------------------------------------------------------------------------------------------------------- -->
                                    <div class="row">
                                        <div class='col-md-12'>
                                            <?= $form->field($model, 'resolucion_aval')->textInput(['id' => 'resolucion_aval', 'maxlength' => true])->hint('Detallar, en caso que corresponda, bajo que norma, ley, resolución y/o aval se enmarca la capacitación.'); ?>
                                        </div>
                                    </div>
                                    <!-------------------------------------------------------------------------------------------------------------------------- -->
                                    <!-------------------------------------------------------------------------------------------------------------------------- -->
                                    <div class="row">
                                        <div class='col-md-12'>
                                            <?php
                                            if (($model->logo_principal == null) && ($model->logo_principal_path == null)) {
                                                //if ($model->logo_principal == null) {
                                                echo $form->field($model, 'temp_logo_princ', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                    ->widget(FileInput::classname(), [
                                                        'options' => ['accept' => 'image/*'],
                                                        'language' => 'es',
                                                        'pluginOptions' => [
                                                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                                            'showCaption' => false,
                                                            'showRemove' => true,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'uploadUrl' => Url::to(['/mds_cap_instancia/update']),
                                                            'maxFileSize' => 100000,
                                                            'previewFileType' => 'file',
                                                            'initialCaption' => $model->logo_principal,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ]
                                                        ],
                                                    ]);
                                            } else {
                                                if ($model->logo_principal_path != null) {
                                                    echo $form->field($model, 'temp_logo_princ', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                        ->widget(FileInput::classname(), [
                                                            'options' => ['accept' => 'image/*'],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                                                'showCaption' => false,
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'mainClass' => 'input-group-sm',
                                                                'uploadUrl' => Url::to(['/mds_cap_instancia/update']),
                                                                'maxFileSize' => 100000,
                                                                'previewFileType' => 'file',
                                                                'initialPreview' => [
                                                                    //Html::img($model->logo_principal, ['class' => 'file-preview-image', 'style' => 'width:100%; text-align: center']),
                                                                    Html::img(Url::base() . "/uploads/instancias/" . $model->logo_principal_path, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                                                ],
                                                                'overwriteInitial' => true,
                                                                'autoReplace' => true,
                                                                'initialCaption' => $model->logo_principal_path,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                ]
                                                            ],
                                                            'pluginEvents' => [
                                                                "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ 
                                                                $('#borrar_logo_princ').val(true);
                                                            }",
                                                                "filereset" => "function() {  }",
                                                            ]
                                                        ]);
                                                } else {
                                                    echo $form->field($model, 'temp_logo_princ', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                        ->widget(FileInput::classname(), [
                                                            'options' => ['accept' => 'image/*'],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                                                'showCaption' => false,
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'mainClass' => 'input-group-sm',
                                                                'uploadUrl' => Url::to(['/mds_cap_instancia/update']),
                                                                'maxFileSize' => 100000,
                                                                'previewFileType' => 'file',
                                                                'initialPreview' => [
                                                                    Html::img($model->logo_principal, ['class' => 'file-preview-image', 'style' => 'width:100%; text-align: center']),
                                                                    // Html::img(Url::base()."/uploads/instancias/".$model->logo_principal_path, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                                                ],
                                                                'overwriteInitial' => true,
                                                                'autoReplace' => true,
                                                                'initialCaption' => $model->logo_principal,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                ]
                                                            ],
                                                            'pluginEvents' => [
                                                                "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ 
                                                                $('#borrar_logo_princ').val(true);
                                                            }",
                                                                "filereset" => "function() {  }",
                                                            ]
                                                        ]);
                                                }
                                            }
                                            ?>
                                            <?= $form->field($model, 'borrar_logo_princ')->hiddenInput(['id' => 'borrar_logo_princ'])->label(false) ?>

                                        </div>
                                    </div>
                                    <!-------------------------------------------------------------------------------------------------------------------------- -->

                                    <div class="row">
                                        <div class='col-md-12'>
                                            <?php
                                            if (($model->logo_extra == null) && ($model->logo_extra_path == null)) {
                                                //if ($model->logo_extra == null) {
                                                echo $form->field($model, 'temp_logo1', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                    ->widget(FileInput::classname(), [
                                                        'options' => ['accept' => 'image/*'],
                                                        'language' => 'es',
                                                        'pluginOptions' => [
                                                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                                            'showCaption' => false,
                                                            'showRemove' => true,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'uploadUrl' => Url::to(['/mds_cap_instancia/update']),
                                                            'maxFileSize' => 100000,
                                                            'previewFileType' => 'file',
                                                            'initialCaption' => $model->logo_extra,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ]
                                                        ],
                                                    ]);
                                            } else {
                                                if ($model->logo_extra_path != null) {

                                                    echo $form->field($model, 'temp_logo1', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                        ->widget(FileInput::classname(), [
                                                            'options' => ['accept' => 'image/*'],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                                                'showCaption' => false,
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'mainClass' => 'input-group-sm',
                                                                'uploadUrl' => Url::to(['/mds_cap_instancia/update']),
                                                                'maxFileSize' => 100000,
                                                                'previewFileType' => 'file',
                                                                'initialPreview' => [
                                                                    //Html::img($model->logo_extra, ['class' => 'file-preview-image', 'style' => 'width:100%; text-align: center']),
                                                                    Html::img(Url::base() . "/uploads/instancias/" . $model->logo_extra_path, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                                                ],
                                                                'overwriteInitial' => true,
                                                                'autoReplace' => true,
                                                                'initialCaption' => $model->logo_extra_path,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                ]
                                                            ],
                                                            'pluginEvents' => [
                                                                "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ 
                                                                $('#borrar_logo1').val(true);
                                                            }",
                                                                "filereset" => "function() {  }",
                                                            ]
                                                        ]);
                                                } else // caso ($model->logo_extra != null)
                                                {
                                                    echo $form->field($model, 'temp_logo1', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                        ->widget(FileInput::classname(), [
                                                            'options' => ['accept' => 'image/*'],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                                                'showCaption' => false,
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'mainClass' => 'input-group-sm',
                                                                'uploadUrl' => Url::to(['/mds_cap_instancia/update']),
                                                                'maxFileSize' => 100000,
                                                                'previewFileType' => 'file',
                                                                'initialPreview' => [
                                                                    //Html::img($model->logo_extra, ['class' => 'file-preview-image', 'style' => 'width:100%; text-align: center']),                                                                
                                                                    Html::img($model->logo_extra, ['class' => 'file-preview-image', 'style' => 'width:100%; text-align: center']),
                                                                ],
                                                                'overwriteInitial' => true,
                                                                'autoReplace' => true,
                                                                'initialCaption' => $model->logo_extra,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                ]
                                                            ],
                                                            'pluginEvents' => [
                                                                "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ 
                                                                $('#borrar_logo1').val(true);
                                                            }",
                                                                "filereset" => "function() {  }",
                                                            ]
                                                        ]);
                                                }
                                            }
                                            ?>
                                            <?= $form->field($model, 'borrar_logo1')->hiddenInput(['id' => 'borrar_logo1'])->label(false) ?>

                                        </div>
                                    </div>
                                    <!-------------------------------------------------------------------------------------------------------------------------- -->

                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php
                                            if (isset($model)) {
                                                $roles = Mds_cap_docente_instancia::find()->where(['id_instancia' => $model->idinstancia, 'firmante' => 1])->all();
                                                $roles_id = array();
                                                $cad_roles = "";
                                                $indice = 0;
                                                $la_lista_docente = array();
                                                foreach ($roles as $rol) {
                                                    $roles_id[$indice] = $rol['id_docente'];
                                                    $la_lista_docente[$indice] = ['idpersona' => $rol['id_docente']];
                                                    if ($indice == 0) {
                                                        $cad_roles .= $rol['id_docente'];
                                                    } else {
                                                        $cad_roles = $cad_roles . "," . $rol['id_docente'];
                                                    }
                                                    $indice++;
                                                }
                                                $model->docentes = $roles_id;
                                            }
                                            $model->lista_firmas_aux = $cad_roles;
                                            $lista_docentes = Mds_cap_docente::find()->all();
                                            foreach ($lista_docentes as $un_obj_docente) {
                                                if (in_array($un_obj_docente['idpersona'], $roles_id)) {
                                                } else {
                                                    array_push($la_lista_docente, ['idpersona' => $un_obj_docente['idpersona']]);
                                                }
                                            }
                                            ?>
                                            <?=
                                            $form->field($model, 'docentes')->widget(Select2::classname(), [
                                                'data' => ArrayHelper::map(
                                                    $la_lista_docente,
                                                    'idpersona',
                                                    function ($model) {
                                                        $cap = Mds_cap_docente::findOne($model['idpersona']);
                                                        $per = Sds_com_persona::findOne($cap['idpersona']);
                                                        return $per['nombre'] . " " . $per['apellido'];
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
                                                'pluginEvents' => [
                                                    "change" => 'function(data) { 
                                                                                                                                                                    
                                                        var tamanio_sel = data.target.selectedOptions.length;                                                         
                                                        var cad=""; 
                                                        var array_seleccionados =  new Array();    
                                                        var array_id_seleccionados =  new Array();   
                                                        var array_seleccionados_final =  new Array(); 
                                                        var listado_final =  new Array();
                                                        for (var i=0; i<tamanio_sel; i++) 
                                                        {                                                            
                                                            array_seleccionados[i]={
                                                                id: data.target.selectedOptions.item(i).value,
                                                                text: data.target.selectedOptions.item(i).label
                                                            };
                                                            cad=cad+"   -"+array_seleccionados[i].id+" / "+array_seleccionados[i].text;
                                                            array_id_seleccionados[i]=array_seleccionados[i].id;
                                                            array_seleccionados_final[i]=array_seleccionados[i];
                                                            var newOption = new Option(array_seleccionados[i].text, array_seleccionados[i].id, false, true);
                                                            listado_final.push(newOption);
                                                        }                                                        
                                                        var tamanio_sel_all=data.target.length;
                                                        var cad_all="";                                                         
                                                        var array_seleccionados_all =  new Array(); 
                                                        var array_id_seleccionados_all =  new Array(); 
                                                        for (var i=0; i<tamanio_sel_all; i++) {
                                                            
                                                            array_seleccionados_all[i]={
                                                                id: data.target[i].value,
                                                                text: data.target[i].label
                                                            };
                                                            cad_all=cad_all+"   -"+array_seleccionados_all[i].id+" / "+array_seleccionados_all[i].text;
                                                            array_id_seleccionados_all[i]=array_seleccionados_all[i].id;
                                                        }
                                                        var cad_all_final=""; 
                                                        for (var i=0; i<tamanio_sel_all; i++) 
                                                        {
                                                            if (data.target.selectedOptions.length > 0)
                                                            {
                                                                if (array_id_seleccionados.indexOf(array_id_seleccionados_all[i]) == -1)
                                                                {  
                                                                    array_seleccionados_final.push(array_seleccionados_all[i]); 
                                                                    var newOption = new Option(array_seleccionados_all[i].text, array_seleccionados_all[i].id, false, false);
                                                                    listado_final.push(newOption);
                                                                }
                                                                else
                                                                {}
                                                            }
                                                            else
                                                            {                                                                
                                                                var newOption = new Option(array_seleccionados_all[i].text, array_seleccionados_all[i].id, false, false);
                                                                listado_final.push(newOption);
                                                            }
                                                            /*cad_all_final=cad_all_final+"   -"+array_seleccionados_final[i].id+" / "+array_seleccionados_final[i].text;      */                                                            
                                                        }                                                        
                                                                                                                                                                       
                                                        var cad_all_final="";
                                                        var array_finalisimo =  new Array();  
                                                        for (var i=0; i<tamanio_sel_all; i++) 
                                                        {                                                              
                                                            data.target[i]=listado_final[i];  
                                                            array_finalisimo[i]=listado_final[i].id;                                                                                                                                                                                                                                    
                                                        }
                                                        $("#lista_firmas_aux").val(array_finalisimo);                                                         
                                                    }',
                                                ]
                                            ])->label('Docentes Firmantes -  Ingrear en el orden que deben aparecer en el certificado');
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                    //echo $form->field($model, 'lista_firmas_aux')->textInput(['id' => 'lista_firmas_aux', 'maxlength' => true]); 
                                    ?>
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
                    <div class="row justify-content-between">
                        <div class="col-md-6">
                            <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php if (!Yii::$app->request->isAjax) { ?>
                                <div class="form-group">
                                    <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </section>
        </div>
    </div>
</div>

<?php
$this->registerJs(
    " $('#cmb_item').change(function() {
        $('#txt_descripcion').val($('#cmb_item option:selected').text());
    });

    $('#cmb_espera').change(function() {
        $('#txt_capacidad_espera').prop('disabled', $('#cmb_espera option:selected').val() == 0);
    });
                                
    $('#inscripcion_externa').on('click', function() {  
              
        /*var seleccion=$('#cmb_privacidad option:selected').text();*/
       if ($('#inscripcion_externa').prop('checked'))
        {
            $('#div_enlace').show();            
        }
        else
        {
            $('#div_enlace').hide();              
        }
    });

    $('#notificar_admin').on('click', function() {  
              
        /*var seleccion=$('#cmb_privacidad option:selected').text();*/
       if ($('#notificar_admin').prop('checked'))
        {
            $('#div_email_admin').show();            
        }
        else
        {
            $('#div_email_admin').hide();              
        }
    });
    "

);
?>