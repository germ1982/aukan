<?php

use app\models\Sds_gis_capa;
use app\models\Sds_gis_capa_item;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_gis_capa_item */
/* @var $form yii\widgets\ActiveForm */

$this->title = "Item de Capa";
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

<section class="panel">
    <div class="panel-body">
        <div class="sds-gis-capa-item-form">
            <?php $form = ActiveForm::begin(); ?>
            <?php
            /*
                    if($model->isNewRecord) ?>
                        <?php $coordenadas = json_decode($model->coleccion_coordenadas,true);  ?>
                        <input type="hidden" id="coleccion_coordenadas" value="<?php  echo htmlspecialchars(json_encode($coordenadas)) ?>">

                   <?php
                    */
            $array_coordenadas  = json_decode($model->coleccion_coordenadas, true);
            ?>
            <div class="row">
                <?= $form->field($model, 'coleccion_coordenadas', ['options' => ['id' => 'coleccion_coordenadas']])->hiddenInput(['value' => json_encode($array_coordenadas)])->label(false); ?>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-10">
                            <?= $form->field($model, 'idcapa')
                                ->dropDownList(
                                    ArrayHelper::map(
                                        Sds_gis_capa::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                                        'idcapa',
                                        'descripcion'
                                    ),
                                    ['prompt' => ""]
                                ) ?>
                        </div>
                        <div class="col-md-2" style="padding-top: 35px;">
                            <?= $form->field($model, 'activo')->checkbox() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
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
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'estado')->dropDownList([
                                null => '', '1' => 'Bien', '2' => 'Regular', '3' => 'Mal'
                            ]) ?>
                        </div>
                        <div class="col-md-4">
                            <label>Ubicación</label>
                            <?=
                            Select2::widget([
                                'name' => 'Sds_gis_capa_item[idubicacion]',
                                'value' => (!$model->isNewRecord) ? $model->idubicacion : null,
                                'data' => ArrayHelper::map(
                                    \app\models\Sds_com_localidad::find()->all(),
                                    'idlocalidad',
                                    function ($model) {
                                        return $model->descripcion;
                                    }
                                ),
                                'options' => ['multiple' => false, 'placeholder' => 'Select Ubicación  ...']
                            ]);

                            ?>
                        </div>
                        <div class="col-md-4">
                            <label>Temática</label>
                            <?=
                            Select2::widget([
                                'name' => 'Sds_gis_capa_item[tematicas]',
                                'value' => !$model->isNewRecord ? ArrayHelper::map($model->getTematicas(), 'idtematica', function ($model) {
                                    return $model->idtematica;
                                }) : '',
                                'data' => ArrayHelper::map(
                                    \app\models\Sds_com_configuracion::getConfiguraciones(\app\models\Sds_com_configuracion_tipo::SDS_GIS_CAPA_ITEM_TEMATICA, true),
                                    'idconfiguracion',
                                    function ($model) {
                                        return $model->descripcion;
                                    }
                                ),
                                'options' => ['multiple' => true, 'placeholder' => 'Seleccione Tematica  ...']
                            ]);

                            ?>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'contacto_telefono_1')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'contacto_telefono_2')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-4">
                            <?php echo $form->field($model, 'privacidad')->dropDownList(['0' => 'Publico', '1' => 'Privado'])
                                ->label("Privacidad");
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'contacto_web')->textInput(['maxlength' => true]) ?>
                        </div>

                    </div>


                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'tipo')->dropDownList([
                                '1' => 'Ubicación', '2' => 'Marcar Zona'
                            ]) ?>
                        </div>
                        <div class="col-md-4" id="divLatitud">
                            <?php
                            if ($model->isNewRecord) {
                                $model->latitud = -38.951678;
                            }
                            ?>
                            <?= $form->field($model, 'latitud')->textInput(['id' => "txtLatitud", 'maxlength' => true]) ?>
                        </div>
                        <div class="col-md-4" id="divLongitud">
                            <?php
                            if ($model->isNewRecord) {
                                $model->longitud = -68.059188;
                            }
                            ?>
                            <?= $form->field($model, 'longitud')->textInput(['id' => "txtLongitud", 'maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'direccion')->textInput(['id' => 'txtDireccion', 'maxlength' => true]) ?>
                        </div>

                    </div>
                    <div class="map" id="map" style="height: 500px; width:100%; border:1px solid #000;">

                    </div>
                    <?php
                    /*echo $form->field($model, 'coordenadas')->widget('\pigolab\locationpicker\CoordinatesPicker', [
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
                                'inputBinding' => [
                                    'latitudeInput' => new JsExpression("$('#txtLatitud')"),
                                    'longitudeInput' => new JsExpression("$('#txtLongitud')"),
                                    'locationNameInput' => new JsExpression("$('#txtDireccion')"),
                                ]
                            ]
                        ])->label('');*/
                    ?>
                </div>
            </div>
            <div class="row">

                <div class="col-md-4">
                    <?= $form->field($model, 'contacto_instagram')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'contacto_facebook')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'contacto_twitter')->textInput(['maxlength' => true]) ?>
                </div>

            </div>
            <div class="row">

                <div class="col-md-4">
                    <?= $form->field($model, 'contacto_email')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-2" style="padding-top: 35px;">
                    <?= $form->field($model, 'notificar_email')->checkbox() ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'referencia_externa')->textInput(['maxlength' => true]) ?>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'detalle_interno')->widget(CKEditor::className(), [
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
                    ])->hint('Este es un campo privado, no se visualizará en la web.');
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    if ($model->imagen == null) {
                        echo $form->field($model, 'archivo_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                            ->widget(FileInput::classname(), [
                                //'name' => 'i1',
                                'options' => ['accept' => 'image/*'],
                                'language' => 'es',
                                'pluginOptions' => [
                                    //'showPreview' => false,
                                    'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                    'showCaption' => false,
                                    'showRemove' => false,
                                    'showUpload' => false,
                                    'showClose' => false,
                                    'showCancel' => false,
                                    'mainClass' => 'input-group-sm',
                                    'uploadUrl' => Url::to(['/sds_gis_capa_item/update']),
                                    'maxFileSize' => 1000000,
                                    /* 'initialPreview'=>[
                                              Html::img($model->Foto,['class'=>'file-preview-image']),
                                              ], */
                                    'previewFileType' => 'image',
                                    'initialCaption' => $model->imagen,
                                    'fileActionSettings' => [
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'showZoom' => false,
                                        'showCaption' => false,
                                        'showCancel' => false
                                    ]
                                    //'minFileCount' => 1,
                                    // 'validateInitialCount' => true,
                                ],
                            ])->label('IMAGEN');
                    } else {
                        echo $form->field($model, 'archivo_imagen', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                            ->widget(FileInput::classname(), [
                                'options' => ['accept' => 'image/*'],
                                'language' => 'es',
                                'pluginOptions' => [
                                    'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
                                    'showCaption' => false,
                                    'showRemove' => false,
                                    'showUpload' => false,
                                    'showClose' => false,
                                    'showCancel' => false,
                                    'mainClass' => 'input-group-sm',
                                    'uploadUrl' => Url::to(['/sds_gis_capa_item']),
                                    'maxFileSize' => 1000000,
                                    'previewFileType' => 'image',
                                    'initialPreview' => [
                                        //Html::img($model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                        Html::img($model->imagen, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                        //CHtml::image(Yii::app()->baseUrl."/uploads/ofertas/".$model->imagen);
                                    ],
                                    'overwriteInitial' => true,
                                    'autoReplace' => true,
                                    'initialCaption' => $model->imagen,
                                    'fileActionSettings' => [
                                        'showRemove' => false,
                                        'showUpload' => false,
                                        'showZoom' => false,
                                        'showCaption' => false,
                                        'showCancel' => false
                                    ]
                                ],
                                'pluginEvents' => [
                                    "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                                    "filereset" => "function() {  }",
                                ]
                            ])->label('IMAGEN');
                    }



                    ?>

                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <?php if (!Yii::$app->request->isAjax) { ?>
                        <div class="form-group">
                            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                            <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>