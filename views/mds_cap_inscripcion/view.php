<?php

use app\models\Mds_cap_inscripcion;
use app\models\Mds_cap_instancia;
use app\models\Mds_cap_persona;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use app\models\Sds_com_persona;
use app\models\Sds_com_provincia;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_inscripcion */
?>
<div class="mds-cap-inscripcion-view">

    <?php $form = ActiveForm::begin(); ?>
    <div class="panel-group" id="accordion_persona">
        <div id="persona" class="accordion-body collapse in">
            <div class="panel-heading">
                <h5 style="margin: 0"><b>Datos Personales: </b></h5>
            </div>
            <div class="panel-body" id="persona_content">
                <div class="row">
                    <div class="col-md-6">
                        <h5><b>Nombre y Apellido: </b>
                            <?php echo $model->persona->nombre . ' ' . $model->persona->apellido ?></h5>
                    </div>
                    <div class="col-md-6">
                        <h5><b><?php echo (Sds_com_configuracion::findOne($model->persona->documento_tipo))->descripcion ?>: </b>
                            <?php echo $model->persona->documento ?></h5>
                    </div>
                    <div class="col-md-6">
                        <h5><b>Email: </b>
                            <?php echo $model->idpersonacap0->mail ?></h5>
                    </div>
                    <div class="col-md-6">
                        <h5><b>Fecha de Nacimiento: </b>
                            <?php echo date_format(date_create($model->persona->fecha_nacimiento), 'd-m-Y') ?></h5>
                    </div>
                    <div class="col-md-6">
                        <h5><b>Género: </b>
                            <?php echo (Sds_com_configuracion::findOne($model->persona->genero))->descripcion ?></h5>
                    </div>
                    <div class="col-md-6">
                        <h5><b>Teléfono: </b>
                            <?php echo $model->idpersonacap0->telefono ?></h5>
                    </div>
                    <div class="col-md-6">
                        <h5><b>Nacionalidad: </b>
                            <?php echo (Sds_com_configuracion::findOne($model->persona->nacionalidad))->descripcion ?></h5>
                    </div>
                    <div class="col-md-6">
                        <h5><b>Provincia: </b>
                            <?php 
                                $localidad = (Sds_com_localidad::findOne($model->idpersonacap0->localidad));
                                echo (Sds_com_provincia::findOne($localidad->idprovincia))->descripcion 
                            ?></h5>
                    </div>
                    <div class="col-md-6">
                        <h5><b>Localidad: </b>
                            <?php echo (Sds_com_localidad::findOne($model->idpersonacap0->localidad))->descripcion ?></h5>
                    </div>
                    <div class="col-md-6">
                        <h5><b>Últimos Estudios: </b>
                            <?php echo (Sds_com_configuracion::findOne($model->idpersonacap0->ultimo_año))->descripcion ?></h5>
                    </div>
                    <div class="col-md-12" style="display:<?= $model->titulo_dato_adicional ? "block" : "none" ?>">
                        <h5><b><?php echo $model->titulo_dato_adicional ?>:</b>
                            <?php echo $model->dato_adicional ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-group" id="accordion_persona">
        <div id="persona" class="accordion-body collapse in">
            <div class="panel-body" id="persona_content">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'idcapinstancia')->widget(
                            Select2::classname(),
                            [
                                'data' => ArrayHelper::map(
                                    Mds_cap_instancia::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                                    'idinstancia',
                                    'descripcion'
                                ),
                                'options' => ['placeholder' => 'Seleccionar instancia ...'],
                                'disabled' => true,
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]
                        )->label('Instancia');
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">

                        <?= $form->field($model, 'termino')->dropDownList(
                            [
                                Mds_cap_inscripcion::ESTADO_INSCRIPTO => "Inscripto",
                                Mds_cap_inscripcion::ESTADO_ENCURSO => "En curso",
                                Mds_cap_inscripcion::ESTADO_APROBADO => "Aprobado",
                                Mds_cap_inscripcion::ESTADO_DESAPROBADO => "Desaprobado",
                                Mds_cap_inscripcion::ESTADO_ABANDONADO => "Abandonado",
                                Mds_cap_inscripcion::ESTADO_PARTICIPO => "Participa",
                                Mds_cap_inscripcion::ESTADO_NO_CORRESPONDE => "No Corresponde"
                            ],
                            [
                                'prompt' => '-- Seleccione una opción --',
                                'disabled' => true,
                            ]

                        ) ?>

                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'fecha_inscripcion')->widget(DatePicker::ClassName(), [
                            'name' => 'check_issue_date',
                            'language' => 'es',
                            'readonly' => false,
                            'layout' => '{picker}{input}{remove}',
                            'disabled' => true,
                            'options' => [
                                'id' => 'fecha_inscripcion',
                                'class' => 'form-control input-md',
                            ],
                            'pluginOptions' => [
                                'value' => null,
                                'format' => 'dd-mm-yyyy',
                                'endDate' => date('d/m/Y'),
                                'todayHighlight' => true,
                                'autoclose' => true,

                            ]
                        ])->label('Fecha de Inscripción'); ?>
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