<?php

use app\models\Mds_org_contacto;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cor_intervencion */

$this->title = "Ver Intervención #{$model->idintervencion}";
$this->params['breadcrumbs'][] = $this->title;
$idusuario = Yii::$app->user->identity->idusuario;

?>
<style>
    .alert-detalle {
        color: #555555;
        background-color: #efefef;
        border-color: lightgray;
        margin-bottom: 15px;
    }
</style>
<header class="page-header">
    <h2> <?php echo $this->title; ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Ver intervención</span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-cor-intervencion-view">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="panel-group" id="accordion_persona">
                        <div id="guardia" class="accordion-body collapse in">
                            <label style="display: <?= $model->idllamada ? "block" : "none;" ?>" </label>
                                <div class="panel-body" id="persona_content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5><b>Nro. de Atención en Guardias Integradas: </b>
                                                <?php echo ($model->idllamada) ?></h5>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_persona">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_persona" href="#persona">
                                        Datos de la Persona
                                    </a>
                                </h4>
                            </div>
                            <div id="persona" class="accordion-body collapse in">
                                <div class="panel-body" id="persona_content">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'dni_beneficiario')->textInput(['readonly' => true, 'maxlength' => true]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'apellido')->textInput(['readonly' => true, 'maxlength' => true]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'nombre')->textInput(['readonly' => true, 'maxlength' => true]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'fecha_nacimiento')->textInput(['readonly' => true, 'maxlength' => true]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'edad')->textInput(['readonly' => true, 'maxlength' => true]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'genero')->textInput(['readonly' => true, 'maxlength' => true]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_intervencion">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_intervencion" href="#intervencion">
                                        Datos de la intervencion
                                    </a>
                                </h4>
                            </div>
                            <div id="intervencion" class="accordion-body collapse in">
                                <div class="panel-body" id="intervencion_content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?= $form->field($model, 'nombre_autopercibido')->textInput(['disabled' => true]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'profesional')->dropdownList(
                                                ArrayHelper::map(
                                                    Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                                                        join sds_com_persona p on p.idpersona=c.idpersona")->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])->all(),
                                                    'idcontacto',
                                                    function ($model) {
                                                        return mb_strtoupper($model->nombre) . " " . mb_strtoupper($model->apellido);
                                                    }
                                                ),
                                                [
                                                    'placeholder' => 'Seleccionar profesional ...',
                                                    'disabled' => true
                                                ]
                                            );
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form
                                                ->field($model, 'fecha_informe')
                                                ->widget(DatePicker::class, [
                                                    'name' => 'check_issue_date',
                                                    'language' => 'es',
                                                    'readonly' => false,
                                                    'layout' => '{picker}{input}{remove}',
                                                    'options' => [
                                                        'id' => 'fecha_informe',
                                                        'class' => 'form-control input-md',
                                                        'disabled' => true
                                                    ],
                                                    'pluginOptions' => [
                                                        'value' => null,
                                                        'format' => 'dd/mm/yyyy',
                                                        'endDate' => date('d/m/Y'),
                                                        'todayHighlight' => true,
                                                        'autoclose' => true,
                                                    ]
                                                ])->label('Fecha de Intervención'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="form-label">Tipo</label>
                                            <input class="form-control" type="text" value="<?= $model->tipo0 ? $model->tipo0->descripcion : '' ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Ley</label>
                                            <input class="form-control" type="text" value="<?= $model->ley0 ? $model->ley0->descripcion : '' ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="form-label">Origen Provincia</label>
                                            <input class="form-control" type="text" value="<?= $model->localidad ? $model->localidad->provincia->descripcion : '' ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Origen Localidad</label>
                                            <input class="form-control" type="text" value="<?= $model->localidad ? $model->localidad->descripcion : '' ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-6">
                                            <label class="form-label">Tiempo de residencia en Neuquén</label>
                                            <input class="form-control" type="text" value="<?= $model->tiemporesidencianqn ? $model->tiemporesidencianqn->descripcion : '' ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Denuncia</label>
                                            <input class="form-control" type="text" value="<?= $model->denuncia ? $model->denuncia->descripcion : '' ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <label>Consumos</label>
                                            <textarea class="form-control" readonly rows="5"><?= $consumos ? $consumos : '' ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <label>Problemas</label>
                                            <textarea class="form-control" readonly rows="5"><?= $problemas ? $problemas : '' ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <label>Articulación interinstitucional</label>
                                            <textarea class="form-control" readonly rows="5"><?= $articulaciones ? $articulaciones : '' ?></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Derivaciones previas:</label>
                                            <div class="alert alert-detalle" role="alert">
                                                <p><?= $model->derivaciones_previas;  ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Plan de acción:</label>
                                            <div class="alert alert-detalle" role="alert">
                                                <p><?= $model->plan_accion;  ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Detalle:</label>
                                            <div class="alert alert-detalle" role="alert">
                                                <p><?= $model->detalle;  ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Intervencion Realizada: </label>
                                            <div class="alert alert-detalle" role="alert">
                                                <p><?= $model->intervenciones;  ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Derivaciones Futuras:</label>
                                            <div class="alert alert-detalle" role="alert">
                                                <p><?= $model->derivaciones;  ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_tercero">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_tercero" href="#tercero">
                                    Datos de Tercero Referente
                                </a>
                            </h4>
                        </div>
                        <div id="tercero" class="accordion-body collapse in">
                            <div class="panel-body" id="tercero_content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'referente_dni')->textInput(['disabled' => true]) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'referente_nombre')->textInput(['disabled' => true, 'maxlength' => true]) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'referente_vinculo')->textInput(['disabled' => true, 'maxlength' => true]) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'referente_telefono')->textInput(['disabled' => true, 'maxlength' => true]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_salud" style="display:<?= $model->archivo_adjunto ? "block" : "none" ?>">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_salud" href="#salud">
                                    Archivo Adjunto
                                </a>
                            </h4>
                        </div>
                        <div id="salud" class="accordion-body collapse in">
                            <div class="panel-body" id="salud_content" style="text-align: center">
                                <!-- Valida si es pdf -->
                                <?php if (stripos($model->archivo_adjunto, 'application/pdf;base64,') != false) : ?>
                                    <div class="row">
                                        <object width="90%" height="500px" type="application/pdf" data="<?php echo $model->archivo_adjunto; ?>">
                                            <p>Archivo Adjunto no disponible.</p>
                                        </object>
                                    </div>
                                <?php else : ?>
                                    <div class="row" style="max-height:500px">
                                        <div class='col-md-12' align="center" ;> <br>
                                            <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:70%;' id='base64image' src='<?php echo $model->archivo_adjunto; ?>' />
                                            <br>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="row" style="margin-top:2%">
                                    <?= Html::a("Ampliar", $model->archivo_adjunto, ['target' => '_blank', 'data-pjax' => "0", 'class' => 'btn btn-success', 'style' => 'width:80%']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_compartir">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_compartir" href="#compartir">
                                    Compartido con:
                                </a>
                            </h4>
                        </div>
                        <div id="compartir" class="accordion-body collapse in">
                            <div class="panel-body" id="compartir_content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <p class="campo"><?= $model->compartido_con ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-info" href="index.php?r=mds_cor_intervencion/index">Volver </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php ActiveForm::end(); ?>