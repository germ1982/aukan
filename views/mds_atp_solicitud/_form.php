<?php

use app\models\Mds_atp_solicitud;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use yii\web\JsExpression;


use yii\helpers\ArrayHelper;
use app\models\Sds_ent_tipo;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_atp_solicitud */
/* @var $form yii\widgets\ActiveForm */

function CalculaEdad($fecha)
{
    list($Y, $m, $d) = explode("-", $fecha);
    return (date("md") < $m . $d ? date("Y") - $Y - 1 : date("Y") - $Y);
}
$fecha = $model->fecha_nacimiento;

if ($model->isNewRecord) {
    $edad = 100;
} else {
    $edad = CalculaEdad($fecha);
}
$anio = substr($fecha, 0, 4);
$mes  = substr($fecha, 5, 2);
$dia = substr($fecha, 8, 2);
$fecha = "$dia/$mes/$anio";
echo '<script>

function calculate_age(birth_month,birth_day,birth_year)
{
       today_date = new Date();
       today_year = today_date.getFullYear();
       today_month = today_date.getMonth();
       today_day = today_date.getDate();
       age = today_year - birth_year;

       if ( today_month < (birth_month - 1))
       {
           age--;
       }
       if (((birth_month - 1) == today_month) && (today_day < birth_day))
       {
           age--;
       }
       return age;
}

    function actualizardatos() {             
        fechanac=document.getElementById("fecha_nacimiento").value;
        cadfecha=fechanac.split("/");
        edad=calculate_age(cadfecha[1],cadfecha[0],cadfecha[2])
        
        if (edad < 18) 
        {   
            if (edad ==1){ cad="<p>Edad: "+ edad+" año </p> <footer class=\"blockquote-footer\">Se requiere un tutor</footer>";}
            else
            {
                cad="<p>Edad: "+ edad+" años </p> <footer class=\"blockquote-footer\">Se requiere un tutor</footer>";
                document.getElementById("label_tut").style.display = \'block\';
                document.getElementById("div_tutor").style.display = \'block\';
                document.getElementById("div_tutor").style = "border: ridge 1px; padding: 8px;border-color:#D8D8D8;";
            }    
            
        }
        else { 
            cad="<p>Edad: "+ edad+" años </p> <footer class=\"blockquote-footer\">No se requiere tutor</footer>";
            document.getElementById("div_tutor").style.display = \'none\';
            document.getElementById("label_tut").style.display = \'none\';
            
        }              
        document.getElementById("blockedad").innerHTML=cad;
        
        
        
    }
</script> ';
function botonAltaHistorial($interseccion)
{
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to(['create_historial']),
        'class' => 'btn btn-success btn-flat',
        'id' => $interseccion ? 'btnCalleInt' : 'btnCalle', 'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        'onclick' => '
          $("#modal_abm").modal("show")
          .find("#content_abm")
          .load($(this).attr("value"));
          $("#header_abm").html("Crear Nota del Cambio de Estado");'
    ]);
}
?>

<div class="mds-atp-solicitud-form">
    <?php $form = ActiveForm::begin(); ?>

    DATOS BENEFICIARIOS
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <?php echo $form->field($model, 'tipo_documento')->dropDownList([
                            'DNI' => 'DNI', 'LC' => 'LC', 'LE' => 'LE', 'CI' => 'CI',
                            'PASAPORTE EXTRANJERO' => 'PASAPORTE EXTRANJERO', 'CEDULA DE IDENTIDAD EXTRANJERA' => 'CEDULA DE IDENTIDAD EXTRANJERA',
                            'NO TIENE' => 'NO TIENE', 'OTRO' => 'OTRO'
                        ]); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'documento')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'cuil')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">

                            <div class="col-md-6">
                                <?php echo $form->field($model, 'sexo')->dropDownList(['F' => 'Femenino', 'M' => 'Masculino']); ?>
                            </div>

                            <div class="col-md-6">
                                <?php
                                if ($model->fecha_nacimiento != null) {
                                    $fn = $model->fecha_nacimiento;
                                    $model->fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                                } else {
                                    $fn = null;
                                }
                                echo $form->field($model, 'fecha_nacimiento')->widget(DatePicker::ClassName(), [
                                    'name' => 'check_issue_date',
                                    'language' => 'es',
                                    'readonly' => false,
                                    'removeButton' => false,
                                    'layout' => '{picker}{input}{remove}',
                                    'options' => [
                                        'id' => 'fecha_nacimiento',
                                        'class' => 'form-control input-md',
                                        'disabled' => false,
                                        'onchange' =>   'actualizardatos()',
                                    ],
                                    'pluginOptions' => [
                                        'value' => null,
                                        'format' => 'dd/mm/yyyy',
                                        'endDate' => date('d/m/Y'),
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                                    ]
                                ])->label('Fecha Nacimiento'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">

                            <blockquote class="blockquote" id="blockedad">
                                <p><?php
                                    if ($fn != null) {
                                        $edad = CalculaEdad($fn);
                                        echo 'Edad: ';
                                        if ($edad == 1) {
                                            echo $edad . ' año';
                                        } else {
                                            echo $edad . ' años';
                                        }
                                    } else {
                                        $edad = 110;
                                    }
                                    ?></p>
                                <?php if ($edad < 18) {
                                    echo '<footer class="blockquote-footer">Se requiere un tutor</footer>';
                                } else {
                                    echo '<footer class="blockquote-footer">No se requiere tutor</footer>';
                                }
                                ?>
                            </blockquote>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'telefono_alternativo')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        echo $form->field($model, 'estado')->dropDownList([Mds_atp_solicitud::INSCRIPTO => 'Inscripto']); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form
                            ->field($model, 'idlocalidad')
                            ->widget(Select2::classname(), [
                                'data' => $localidades,
                                'options' => [
                                    'placeholder' => 'Seleccionar Localidad...',
                                    'id' => 'cmb_idlocalidad'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?php echo $form->field($model, 'carga_grupo_familiar')->dropDownList(['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6 o mas']); ?>
            </div>
            <div class="col-md-6">
                <?php echo $form->field($model, 'ingreso_grupo_familiar')->dropDownList(['1' => 'menos de $10000', '2' => 'entre $10000 y $20000', '3' => 'entre $20000 y $30000', '4' => 'entre $30000 y $40000', '5' => 'entre $40000 y $50000', '6' => 'más de $50000']); ?>
            </div>
            <div class="col-md-6">
                <?php echo $form
                    ->field(
                        $model,
                        'retirada'
                    )
                    ->dropDownList(
                        ['1' => 'Si', '0' => 'No'],
                        [
                            'prompt' =>
                            'Seleccione una opción...',
                        ]
                    )
                    ->label(
                        '¿La Tarjeta fue Retirada?'
                    ); ?>
            </div>
        </div>
        DATOS DE LA CUENTA
        <div class="row">
            <div class="col-md-12">
                <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'entidad')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'sucursal')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'numero_cuenta')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'digito_verificador')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <div class="row" align="center" ;>
            <div class='col-md-6' align="center" ;>
                <?php
                if ($model->foto_dni == null) {
                    echo $form->field($model, 'archivo_foto_dni', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                'uploadUrl' => Url::to(['/mds_atp_solicitud/update']),
                                'maxFileSize' => 5000,
                                /* 'initialPreview'=>[
                                              Html::img($model->Foto,['class'=>'file-preview-image']),
                                              ], */
                                'previewFileType' => 'image',
                                'initialCaption' => $model->foto_dni,
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
                        ])->label('DNI FRENTE');
                } else {
                    echo $form->field($model, 'archivo_foto_dni', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                'uploadUrl' => Url::to(['/mds_atp_solicitud/update']),
                                'maxFileSize' => 5000,
                                'previewFileType' => 'image',
                                'initialPreview' => [
                                    Html::img($model->foto_dni, ['class' => 'file-preview-image', 'style' => 'height:100%;']),
                                ],
                                'overwriteInitial' => true,
                                'autoReplace' => true,
                                'initialCaption' => $model->foto_dni,
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
                        ])->label('DNI FRENTE');
                }
                ?>
            </div>
            <div class='col-md-6'>
                <?php
                if ($model->foto_dnidorso == null) {
                    echo $form->field($model, 'archivo_foto_dnidorso', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                'uploadUrl' => Url::to(['/sds_ent_entrega/update']),
                                'previewFileType' => 'image',
                                'maxFileSize' => 5000,
                                /* 'initialPreview'=>[
                                              Html::img($model->Foto,['class'=>'file-preview-image']),
                                              ], */
                                'initialCaption' => $model->foto_dnidorso,
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
                        ])->label('DNI DORSO');
                } else {
                    echo $form->field($model, 'archivo_foto_dnidorso', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                'previewFileType' => 'image',
                                'resizeImages' => true,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/sds_ent_entrega/update']),
                                'maxFileSize' => 5000,
                                'initialPreview' => [
                                    Html::img($model->foto_dnidorso, ['class' => 'file-preview-image', 'style' => 'height:100%']),
                                ],
                                'overwriteInitial' => true,
                                'autoReplace' => true,
                                'initialCaption' => $model->foto_dnidorso,
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
                        ])->label('DNI DORSO');
                }
                ?>
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class='col-md-12'>
            <?php
            if ($model->foto_certificado == null) {
                echo $form->field($model, 'archivo_foto_certificado', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                            'uploadUrl' => Url::to(['/mds_atp_solicitud/update']),
                            'maxFileSize' => 1000,
                            'previewFileType' => 'image',

                            'initialCaption' => $model->foto_certificado,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                                'showZoom' => false,
                                'showCaption' => false,
                                'showCancel' => false
                            ]
                        ],
                    ])->label('Foto Certificado');
            } else {
                echo $form->field($model, 'archivo_foto_certificado', ['enableClientValidation' => true, 'enableAjaxValidation' => true])
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
                            'previewFileType' => 'image',
                            'resizeImages' => true,

                            'mainClass' => 'input-group-sm',
                            'uploadUrl' => Url::to(['/mds_atp_solicitud/update']),
                            'maxFileSize' => 5000,
                            'previewFileType' => 'image',
                            'initialPreview' => [
                                Html::img($model->foto_certificado, ['class' => 'file-preview-image', 'style' => 'height:100%']),
                            ],
                            'overwriteInitial' => true,
                            'autoReplace' => true,
                            'initialCaption' => $model->foto_certificado,
                            'fileActionSettings' => [
                                'showRemove' => false,
                                'showUpload' => false,
                                'showZoom' => false,
                                'showCancel' => false
                            ]
                        ],
                        'pluginEvents' => [
                            "fileclear" => "function() { /*contempla evento de botón 'quitar' que se agrega al file browser*/ }",
                            "filereset" => "function() {  }",
                        ]
                    ])->label('Foto Certificado');
            }
            ?>
        </div>
    </div>


    <br>
    <label id="label_tut" <?php if ($edad < 18) {
                                echo 'style="display:block"';
                            } else {
                                echo 'style="display:none"';
                            } ?>>DATOS TUTOR </label>
    <div <?php if ($edad < 18) {
                echo ' style="display:block ; border: ridge 1px; padding: 8px;border-color:#D8D8D8;" ';
            } else {
                echo 'style="display:none"';
            } ?> id="div_tutor">
        <div class="row">
            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-4">
                        <?php echo $form->field($model, 'tutor_tipo_documento')->dropDownList([
                            'DNI' => 'DNI', 'LC' => 'LC', 'LE' => 'LE', 'CI' => 'CI',
                            'PASAPORTE EXTRANJERO' => 'PASAPORTE EXTRANJERO', 'CEDULA DE IDENTIDAD EXTRANJERA' => 'CEDULA DE IDENTIDAD EXTRANJERA',
                            ' NO TIENE' => ' NO TIENE', 'OTRO' => 'OTRO'
                        ]); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'tutor_documento')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'tutor_cuil')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'tutor_nombre')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'tutor_apellido')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <?php echo $form->field($model, 'tutor_sexo')->dropDownList(['F' => 'Femenino', 'M' => 'Masculino']); ?>
                    </div>
                    <div class="col-md-7">
                        <?php
                        if ($model->tutor_fecha_nacimiento != null) {
                            $model->tutor_fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model->tutor_fecha_nacimiento)));
                        }
                        echo $form->field($model, 'tutor_fecha_nacimiento')->widget(DatePicker::ClassName(), [
                            'name' => 'check_issue_date',
                            'language' => 'es',
                            'readonly' => false,
                            'layout' => '{picker}{input}{remove}',
                            'options' => [
                                'id' => 'tutor_fecha_nacimiento',
                                'class' => 'form-control input-md',
                                'disabled' => false,

                            ],
                            'pluginOptions' => [
                                'value' => null,
                                'format' => 'dd/mm/yyyy',
                                'endDate' => date('d/m/Y'),
                                'todayHighlight' => true,
                                'autoclose' => true,
                            ],

                        ])->label('Fecha Nacimiento Tutor'); ?>
                    </div>
                </div>
                <div class="col-md-14">
                    <?= $form->field($model, 'tutor_parentesco')->textInput(['maxlength' => true]) ?>
                </div>
            </div>


        </div>

        <!-- <div class="row">
           <div class="col-md-2">
            <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:100px;height:100px;' id='base64image' src='<?php echo $model->tutor_foto_dni; ?>' />    
           </div>
        </div>   -->


        <div class="row">
            <div class='col-md-6'>
                <?php
                if ($model->tutor_foto_dni == null) {
                    echo $form->field($model, 'archivo_tutor_foto_dni', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                'uploadUrl' => Url::to(['/mds_atp_solicitud/update']),
                                'maxFileSize' => 1000,
                                /* 'initialPreview'=>[
                                              Html::img($model->Foto,['class'=>'file-preview-image']),
                                              ], */
                                'previewFileType' => 'image',
                                'initialCaption' => $model->tutor_foto_dni,
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
                        ])->label('DNI TUTOR FRENTE');
                } else {
                    echo $form->field($model, 'archivo_tutor_foto_dni', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                'uploadUrl' => Url::to(['/mds_atp_solicitud/update']),
                                'maxFileSize' => 1000,
                                'previewFileType' => 'image',
                                'initialPreview' => [
                                    Html::img($model->tutor_foto_dni, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                ],
                                'overwriteInitial' => true,
                                'autoReplace' => true,
                                'initialCaption' => $model->tutor_foto_dni,
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
                        ])->label('DNI TUTOR FRENTE');
                }
                ?>
            </div>
            <br>
            <div class='col-md-6'>
                <?php
                if ($model->tutor_foto_dnidorso == null) {
                    echo $form->field($model, 'archivo_tutor_foto_dnidorso', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                'uploadUrl' => Url::to(['/sds_ent_entrega/update']),
                                'previewFileType' => 'image',
                                'maxFileSize' => 1000,
                                /* 'initialPreview'=>[
                                              Html::img($model->Foto,['class'=>'file-preview-image']),
                                              ], */
                                'initialCaption' => $model->tutor_foto_dnidorso,
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
                        ])->label('DNI TUTOR DORSO');
                } else {
                    echo $form->field($model, 'archivo_tutor_foto_dnidorso', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                'previewFileType' => 'image',
                                'resizeImages' => true,
                                'mainClass' => 'input-group-sm',
                                'uploadUrl' => Url::to(['/sds_ent_entrega/update']),
                                'maxFileSize' => 1000,
                                'initialPreview' => [
                                    Html::img($model->tutor_foto_dnidorso, ['class' => 'file-preview-image', 'style' => 'width:100%']),
                                ],
                                'overwriteInitial' => true,
                                'autoReplace' => true,
                                'initialCaption' => $model->tutor_foto_dnidorso,
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
                        ])->label('DNI TUTOR DORSO');
                }
                ?>
            </div>

        </div>


        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar Datos', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>
    </div>
    <?php

    Modal::begin([
        'header' => '<h4 id="header_abm"></h4>',
        'id' => 'modal_abm',
        'options' => [
            'tabindex' => false // important for Select2 to work properly
        ],
        'size' => 'modal-md',
    ]);

    echo "<div id='content_abm'></div>";

    Modal::end();
