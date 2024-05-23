<?php

use app\models\Mds_fs_persona;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;

use yii\widgets\DetailView;

use app\models\Sds_com_provincia;
use app\models\Sds_com_localidad;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_fs_persona */
?>
<div class="mds-fs-persona-view">
    <div class="alert alert-info">
        <h5 style="margin: 0"><b>ESTADO ACTUAL: </b>
            <?php echo Mds_fs_persona::getEstado($model->estado) ?>
        </h5>
    </div>
    <?php $form = ActiveForm::begin(); ?>
    DATOS PERSONALES
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'dni')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'apellido')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        if ($model->fecha_nacimiento != null) {
                            $fn = $model->fecha_nacimiento;
                            $model->fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                        } else {
                            $fn = null;
                        }
                        echo $form->field($model, 'fecha_nacimiento')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]);
                        ?>

                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'lugar_nacimiento')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'nacionalidad')->dropdownList(
                            ArrayHelper::map(
                                Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, false),
                                'idconfiguracion',
                                'descripcion'
                            ),
                            ['id' => 'nacionalidad_persona', 'tabindex' => '1', 'disabled' => true]
                        );
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'genero')->dropdownList(
                            ArrayHelper::map(
                                Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_GENERO, false),
                                'idconfiguracion',
                                'descripcion'
                            ),
                            ['id' => 'sexo_persona', 'tabindex' => '1', 'disabled' => true]
                        ); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'estado_civil')->dropdownList(
                            ArrayHelper::map(
                                Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_SIT_CONYUGAL, false),
                                'idconfiguracion',
                                'descripcion'
                            ),
                            ['id' => 'estado_persona', 'disabled' => true]
                        ); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'domicilio')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-6">

                        <?php

                        if ($model->idlocalidad == '') {
                        } else {

                            //echo 'el id localidad es: '.$model->id_localidad;
                            $una_prov = Sds_com_localidad::find()
                                ->select(['idprovincia', 'descripcion'])
                                ->where(['idlocalidad' => $model->idlocalidad])
                                ->one();
                        }
                        $una_provincia = Sds_com_provincia::find()->where(['idprovincia' => $una_prov->idprovincia])->one();
                        $model->la_provincia = $una_provincia->descripcion;
                        $model->idlocalidad = $una_prov->descripcion;
                        echo $form->field($model, 'la_provincia')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true])->label("Provincia");
                        ?>

                    </div>
                    <div class="col-md-6">

                        <?php
                        echo $form->field($model, 'idlocalidad')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true])->label("Localidad");

                        ?>

                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'tiempo_provincia')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'nivel_escolaridad')->dropdownList(
                            ArrayHelper::map(
                                Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_ULTIMO_ANIO_APROBADO, false),
                                'idconfiguracion',
                                'descripcion'
                            ),
                            ['id' => 'estado_persona', 'disabled' => true]
                        ); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'profesion')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'telefono')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'telefono_alternativo')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'mail')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'grupo_familiar')->textArea(['rows' => 6, 'maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <br>
    INFORMACION GENERAL
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">
                <div class="row h-100">
                    <div class="col-md-12">
                        <?= $form->field($model, 'inscripto_rua_check')->widget(SwitchInput::classname(), [
                            'pluginOptions' => [
                                'onText' => 'SI',
                                'offText' => 'NO'
                            ],
                            'disabled' => true
                        ]); ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'inscripto_rua')->textArea(['rows' => 6, 'maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'motivo_fs')->textArea(['rows' => 6, 'maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'acuerdo_familia')->textArea(['rows' => 6, 'maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'conocimiento_programa')->textArea(['rows' => 6, 'maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'disponibilidad_horaria')->textArea(['rows' => 6, 'maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'franja_etaria')->textArea(['rows' => 6, 'maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'consulta')->textArea(['rows' => 6, 'maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff', 'disabled' => true]) ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <br>
    <div style="display:<?= $model->informe_adjunto_path ? "block" : "none" ?>">
        INFORME ADJUNTO
        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
            <div class="row">
                <div class="col-md-12">
                    <div id="salud_content" style="text-align: center">
                        <!-- Valida si es pdf -->
                        <?php if (Mds_fs_persona::getExtension($model->informe_adjunto_path) != 'image') : ?>
                            <div class="row" style="max-height:300px">
                                <div class='col-md-12' style="padding: 1rem" align="center" ;>
                                    <img id='base64image' alt="Vista Previa no Disponible" src='' />
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="row" style="max-height:300px">
                                <div class='col-md-12' style="padding: 1rem" align="center" ;>
                                    <img id='base64image' alt="Archivo sin Vista Previa" src='<?php echo Url::to('uploads/familiassolidarias/' . $model->idfspersona . '/informe/' . $model->informe_adjunto_path, true) ?>' />
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="row" style="margin-top:2%">
                            <?= Html::a("Abrir Archivo Adjunto", 'uploads/familiassolidarias/' . $model->idfspersona . '/informe/' . $model->informe_adjunto_path, ['target' => '_blank', 'data-pjax' => "0", 'class' => 'btn btn-success', 'style' => 'width:80%']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>