<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Sds_bdc_equipo;
use app\models\Sds_bdc_movimiento;
use app\models\Sds_cel_linea;
use app\models\Sds_cel_plan;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_cel_linea */
/* @var $form yii\widgets\ActiveForm */

function botonAltaMarca()
{
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to(['//sds_com_configuracion/create_ext', 'tipo' => Sds_com_configuracion_tipo::BDC_MARCA_EQUIPO]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btnMarca', 'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        'onclick' => '
          $("#abm_configuracion").modal("show")
          .find("#content_abm")
          .load($(this).attr("value"));
          $("#header_abm").html("Nueva Marca");
          $("#btnGuardar").hide();$("#btnCerrar").hide();
          $("#main_form").hide();'
    ]);
}

?>
<?php 
if(isset($mensaje_success)):?>
    <div class="alert alert-success">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i> ¡Excelente!</h4>
        <?=$mensaje_success?>
    </div>
<?php endif;

if(isset($mensaje_error)):?>
    <div class="alert alert-danger">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-times"></i> ¡Hubo fallas al guardar la línea!</h4> 
        Por favor intente nuevamente <br>
        <i>*Error: </i> <?=$mensaje_error?>
    </div>
<?php endif;?>

<div class="sds-cel-linea-form" id="main_form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-<?=$model->isNewRecord?'3':'12'?>">
            <?= $form->field($model, 'numero')->textInput(['maxlength' => true]) ?>
        </div>
        <?php if(!$model->isNewRecord && ($model->idequipo==null)):?>
            <div class="col-md-<?=$model->isNewRecord?'3':'12'?>">
                <?= $form->field($model, 'idequipo')->widget(Select2::class, [
                    'data' => ArrayHelper::map(
                        Sds_bdc_equipo::findBySql(
                            "SELECT * FROM sds_bdc_equipo e WHERE idequipo IN
                            (SELECT um.idequipo FROM sds_bdc_movimiento m2 JOIN
                                (SELECT mov.idequipo, max(mov.idmovimiento) ultimo_movimiento FROM
                                    (SELECT me.*,m.fecha_hora FROM sds_bdc_movimiento m
                                    JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
                                GROUP BY mov.idequipo
                                ) um ON m2.idmovimiento=um.ultimo_movimiento
                            WHERE m2.tipo<>.".Sds_bdc_movimiento::MOV_BAJA.") AND e.tipo=".Sds_bdc_equipo::CELULAR.
                            " AND e.idequipo NOT IN(SELECT idequipo FROM sds_cel_linea WHERE NOT ISNULL(idequipo))"
                            )->all(),
                        'idequipo',
                        function ($model) {
                            $marca=Sds_com_configuracion::findOne($model->marca);
                            return "#".str_pad($model->idequipo,6,"0", STR_PAD_LEFT) ." - ".$marca->descripcion." ".$model->modelo;
                        }
                    ),
                    'options' => ['placeholder' => 'Seleccionar Equipo...', 'id' => 'cmb_contacto'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);?>
            </div>
        <?php endif; ?>
        <?php if($model->isNewRecord):?>
            <div class="col-md-3">
                <?= $form->field($model, 'idequipo')->widget(Select2::class, [
                    'data' => ArrayHelper::map(
                        Sds_bdc_equipo::findBySql(
                            "SELECT * FROM sds_bdc_equipo e WHERE idequipo IN
                            (SELECT um.idequipo FROM sds_bdc_movimiento m2 JOIN
                                (SELECT mov.idequipo, max(mov.idmovimiento) ultimo_movimiento FROM
                                    (SELECT me.*,m.fecha_hora FROM sds_bdc_movimiento m
                                    JOIN sds_bdc_movimiento_equipo me ON me.idmovimiento=m.idmovimiento) mov
                                GROUP BY mov.idequipo
                                ) um ON m2.idmovimiento=um.ultimo_movimiento
                            WHERE m2.tipo<>.".Sds_bdc_movimiento::MOV_BAJA.") AND e.tipo=".Sds_bdc_equipo::CELULAR.
                            " AND e.idequipo NOT IN(SELECT idequipo FROM sds_cel_linea WHERE NOT ISNULL(idequipo))"
                            )->all(),
                        'idequipo',
                        function ($model) {
                            $marca=Sds_com_configuracion::findOne($model->marca);
                            return "#".str_pad($model->idequipo,6,"0", STR_PAD_LEFT) ." - ".$marca->descripcion." ".$model->modelo;
                        }
                    ),
                    'options' => ['placeholder' => 'Seleccionar Equipo...', 'id' => 'cmb_contacto'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);?>
            </div>
            
            <div class="col-md-3">
                <?= $form->field($model, 'idplan')->widget(Select2::class, [
                    'data' => ArrayHelper::map(
                        Sds_cel_plan::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                        'idplan',
                        'descripcion'
                    ),
                    'options' => ['placeholder' => 'Seleccionar Plan ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'organismo_padre')->widget(Select2::class, [
                    'data' => ArrayHelper::map(
                        Mds_org_organismo::find()->where('nivel in (1,2)')->orderBy(['descripcion' => SORT_ASC])->all(),
                        'idorganismo',
                        'descripcion'
                    ),
                    'options' => ['placeholder' => 'Seleccionar Organismo Padre ...'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                ?>
            </div>
        <?php endif;?>
        <!--
        <div class="col-md-3">
             idplan 
            <div class="input-group">
                <?= $form->field($model, 'equipo_marca')->widget(Select2::class, [
                    'data' => ArrayHelper::map(
                        Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::BDC_MARCA_EQUIPO, false),
                        'idconfiguracion',
                        'descripcion'
                    ),
                    'options' => [
                        'placeholder' => 'Seleccionar Marca ...',
                        'id' => 'config_' . Sds_com_configuracion_tipo::BDC_MARCA_EQUIPO,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
                <span class="input-group-btn">
                    <?= botonAltaMarca(); ?>
                </span>
            </div>
        </div>
        
        <div class="col-md-3">
            <?= $form->field($model, 'equipo_modelo')->textInput() ?>
        </div>
        -->
    </div>

    <!--
    <div class="row">
        <div class="col-md-3">
            <!-- equipo_tipo --
            <?= $form->field($model, 'equipo_tipo')->dropDownList([
                null => "",
                Sds_cel_linea::TIPO_DESCONOCIDO => "Desconocido",
                Sds_cel_linea::TIPO_OBSOLETO => "Obsoleto",
                Sds_cel_linea::TIPO_GAMA_BAJA => "Gama Baja",
                Sds_cel_linea::TIPO_GAMA_MEDIA => "Gama Media",
                Sds_cel_linea::TIPO_GAMA_ALTA => "Gama Alta",
                Sds_cel_linea::TIPO_SIN_EQUIPO => "Sin Equipo",
            ]) ?>
        </div>
        <div class="col-md-3">
            <!-- estado --
            <?= $form->field($model, 'estado')->dropDownList([
                null => "",
                Sds_cel_linea::ESTADO_NO_RELEVADO => "No Relevado",
                Sds_cel_linea::ESTADO_RELEVADO => "Relevado",
                Sds_cel_linea::ESTADO_ENTREGADO => "Entregado"
            ]) ?>
        </div>
        <div class="col-md-3">
            <!-- activo --
            <?= $form->field($model, 'activo')->dropDownList([
                null => "",
                Sds_cel_linea::ACTIVO_ACTIVO => "Activo",
                Sds_cel_linea::ACTIVO_BAJA => "Baja",
                Sds_cel_linea::ACTIVO_SUSPENSION_POR_ROBO => "Susp.Robo",
                Sds_cel_linea::ACTIVO_SUSPENSION_DESCONOCIDO => "Susp.Desc.",
                Sds_cel_linea::ACTIVO_LINEA_DISPONIBLE => "Línea Disponible"
            ]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'equipo_detalle')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?php /*
            if ($model->fecha_entrega != null) {
                $model->fecha_entrega = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_entrega)));
            }
            echo $form->field($model, 'fecha_entrega')->widget(DatePicker::class, [
                'name' => 'check_issue_date',
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}{remove}',
                'options' => [
                    'id' => 'fecha_entrega',
                    'class' => 'form-control input-md',
                    'disabled' => false
                ],
                'pluginOptions' => [
                    'value' => null,
                    'format' => 'dd/mm/yyyy',
                    'endDate' => date('d/m/Y'),
                    'todayHighlight' => true,
                    'autoclose' => true,
                ]
            ])->label('Fecha Entrega (dd/mm/yyyy)'); ?>
        </div>
        <!--
        <div class="col-md-6">
            <div class="input-group">
                <?php /*$form->field($model, 'idcontacto')->widget(Select2::class, [
                    'data' => ArrayHelper::map(
                        Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                            join sds_com_persona p on p.idpersona=c.idpersona")->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])->all(),
                        'idcontacto',
                        function ($model) {
                            return $model->nombre . " " . $model->apellido;
                        }
                    ),
                    'options' => ['placeholder' => 'Seleccionar Contacto ...', 'id' => 'cmb_contacto'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
                <span class="input-group-btn">
                    <?= Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                        'value' => Url::to(['mds_org_contacto/create']),
                        'class' => 'btn btn-success btn-flat',
                        'id' => 'btnContacto', 'style' => 'margin-top:27px',
                        'onclick' => '$("#abm_contacto").show();$("#btnGuardar").hide();$("#btnCerrar").hide();$("#main_form").hide();'
                    ]);
                    */?>
                </span>
            </div>
        </div>
       
    </div>
    --
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'idorganismo')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                    'idorganismo',
                    'descripcion'
                ),
                'options' => ['placeholder' => 'Seleccionar Organismo ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>
    -->
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
</div>

<div class="row" id="abm_configuracion" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 id="header_abm" class="panel-title">
                </h3>
            </header>
            <div class="panel-body" id="content_abm">
            </div>
        </section>
    </div>
</div>

<div class="row" id="abm_contacto" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 class="panel-title">
                    Agregar Contacto
                </h3>
            </header>
            <div class="panel-body">
                <?php
                $model_contacto = new Mds_org_contacto();
                echo $this->render('/mds_org_contacto/_form', [
                    'model' => $model_contacto,
                    'botones' => true
                ]);
                ?>
            </div>
        </section>
    </div>
</div>