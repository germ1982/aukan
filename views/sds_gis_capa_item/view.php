<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_gis_capa;
use app\models\Sds_gis_capa_item;
use app\models\Sds_gis_item_tematica;
use pigolab\locationpicker\CoordinatesPicker;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;
/* @var $this yii\web\View */
/* @var $model app\models\Sds_gis_capa_item */
?>
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
                <div class="sds-gis-capa-item-view">
                    <div class="row">
                        <div class="col-md-12">



                            <div class="row">


                                <div class="col-md-6">
                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [
                                            [
                                                'attribute' => 'idcapa',
                                                'value' => function ($model) {
                                                    $idcapa = $model->idcapa;
                                                    if ($idcapa != null) {
                                                        return Sds_gis_capa::findOne($idcapa)->descripcion;
                                                    }
                                                    return "";
                                                }
                                            ],
                                            'descripcion',
                                            'latitud',
                                            'longitud',
                                            [
                                                'attribute' => 'estado',
                                                'format' => 'html',
                                                'value' => function ($model) {
                                                    $icon_class = 'fas fa-circle ';
                                                    switch ($model->estado) {
                                                        case Sds_gis_capa_item::ESTADO_VERDE:
                                                            $icon_class = $icon_class . 'text-success';
                                                            break;
                                                        case Sds_gis_capa_item::ESTADO_AMARILLO:
                                                            $icon_class = $icon_class . 'text-warning';
                                                            break;
                                                        case Sds_gis_capa_item::ESTADO_ROJO:
                                                            $icon_class = $icon_class . 'text-danger';
                                                            break;
                                                    }
                                                    return '<span class="' . $icon_class . '"></span>';
                                                }
                                            ],
                                            ['attribute' => 'activo', 'value' => function ($model) {
                                                return $model->activo ? "Si" : "No";
                                            }],
                                            'direccion',
                                            'contacto_telefono_1',
                                            'contacto_telefono_2',
                                            [
                                                'attribute' => 'referencia_externa',
                                                'value' => function ($model) {
                                                    $idcapaitem = $model->referencia_externa;
                                                    if ($idcapaitem != null) {
                                                        return Sds_gis_capa_item::findOne($idcapaitem)->descripcion;
                                                    }
                                                    return "";
                                                }
                                            ],

                                        ],
                                    ]) ?>
                                </div>



                                <div class="col-md-6">

                                    <?php


                                    $items_tematica = Sds_gis_item_tematica::find()
                                        ->where(['iditem' => $model->idcapaitem])
                                        ->all();
                                    $cad_item = '';
                                    foreach ($items_tematica as $un_item) {
                                        $una_conf = Sds_com_configuracion::find()
                                            ->where(['idconfiguracion' => $un_item->idtematica])
                                            ->one();
                                        $cad_item = $cad_item . $una_conf->descripcion . ' - ';
                                    };
                                    $model->tematicas = $cad_item;
                                    ?>

                                    <?= DetailView::widget([
                                        'model' => $model,
                                        'attributes' => [

                                            ['attribute' => 'privacidad', 'value' => function ($model) {
                                                return $model->activo ? "Privado" : "Publico";
                                            }],
                                            'contacto_web',
                                            'contacto_instagram',
                                            'contacto_facebook',
                                            'contacto_twitter',
                                            'contacto_email',
                                            ['attribute' => 'notificar_email', 'value' => function ($model) {
                                                return $model->activo ? "Si" : "No";
                                            }],
                                            'tematicas',
                                            ['attribute' => 'tipo', 'value' => function ($model) {
                                                if ($model->tipo = 1) {
                                                    return 'Ubicación';
                                                } else {
                                                    if ($model->tipo = 2) {
                                                        return 'Marcar Zona';
                                                    } else { {
                                                            return '';
                                                        }
                                                    }
                                                }
                                                return $model->activo ? "Si" : "No";
                                            }],

                                        ],
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class='col-md-6'>
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
                            ]);
                            ?>
                        </div>
                        <div class='col-md-6'>
                            <?= $form->field($model, 'detalle_interno')->widget(CKEditor::className(), [
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
                            ])->hint('Este es un campo privado, no se visualizará en la web.');
                            ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <br>
                    <br>
                    <div class="row">
                        <div class='col-md-12'>
                            <?php
                            if ($model->imagen == null) {
                                echo ' No hay una imagen guardada';
                            } else {
                                echo '
                                <figcaption class="text-left">Imagen</figcaption>
                                    <img  src="';
                                echo Url::base() . '/' . $model->imagen;
                                echo  '">
                                    
                                ';
                            }
                            ?>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            echo CoordinatesPicker::widget([
                                'model' => $model,
                                'attribute' => 'coordenadas',
                                'key' => 'AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg', // require , Put your google map api key
                                'valueTemplate' => '{latitude},{longitude}', // Optional , this is default result format
                                'options' => [
                                    'style' => 'width: 100%; height: 405px', // map canvas width and height
                                ],
                                'enableSearchBox' => false, // Optional , default is true
                                'searchBoxOptions' => [ // searchBox html attributes
                                    'style' => 'width: 300px;display:none;', // Optional , default width and height defined in css coordinates-picker.css
                                ],
                                'searchBoxPosition' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'), // optional , default is TOP_LEFT
                                'mapOptions' => [
                                    // google map options
                                    // visit https://developers.google.com/maps/documentation/javascript/controls for other options
                                    'mapTypeControl' => false, // Enable Map Type Control
                                    'mapTypeControlOptions' => [
                                        'style' => new JsExpression('google.maps.MapTypeControlStyle.HORIZONTAL_BAR'),
                                        'position' => new JsExpression('google.maps.ControlPosition.TOP_LEFT'),
                                    ],
                                    'streetViewControl' => true, // Enable Street View Control
                                ],
                                'clientOptions' => [
                                    // jquery-location-picker options
                                    'location' => [
                                        'latitude' => $model->latitud,
                                        'longitude' => $model->longitud,
                                    ],
                                    'radius' => 0,
                                    'addressFormat' => 'street_number',
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (!Yii::$app->request->isAjax) { ?>
                                <div class="form-group">
                                    <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>