<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Mds_org_organismo_externo;
use app\models\Mds_seg_rol;
use app\models\Mds_seg_usuario_entrega_tipo;
use app\models\Mds_seg_usuario_responsable;
use app\models\Mds_seg_usuario_rol;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_tipo;
use app\models\Sds_stk_deposito;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_seg_usuario */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .select2-search__field {
        width: 100% !important;
    }
</style>

<div class="mds-seg-usuario-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="input-group">
                <?= $form->field($model, 'idcontacto')->widget(Select2::class, [
                    'id' => 'cmb_contacto',
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
                        'onclick' => '$("#abm_contacto").show();
                                        $("#crear_usuario").hide();
                                        $("#btnGuardar").hide();
                                        $("#btnCerrar").hide();'
                    ]);
                    ?>
                </span>
            </div>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'user')->textInput(['maxlength' => true])->label('Usuario') ?>
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
        <div class="col-md-6">
            <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'celular_cuenta')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 col-md-offset-5" style="text-align: right; padding-right:0;">
            <?= $form->field($model, 'activo')->checkbox() ?>
        </div>
        <div class="col-md-2" style="text-align: right;">
            <?= $form->field($model, 'is_externo')->checkbox(['id' => 'is_externo']); ?>
        </div>
        <div class="col-md-3" style="text-align: right;">
            <?= $form->field($model, 'responsable_todos')->checkbox(['id' => 'resp_todos']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'responsable')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_RESPONSABLE_ENTREGA),
                    'idconfiguracion',
                    'descripcion'
                ),
                'options' => [
                    'placeholder' => 'Seleccionar Responsable ...',
                    'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_RESPONSABLE_ENTREGA,
                    'disabled' => false,
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <div id="externo_content" class="input-group" style="<?= $model->is_externo ? 'display: inline;' : 'display: none;' ?>">
                <?= $form->field($model, 'externo')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Mds_org_organismo_externo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismoexterno', 'descripcion'),
                    'options' => [
                        'placeholder' => 'Seleccionar Organismo Externo ...',
                        'id' => 'cmb_externo',
                        'tabIndex' => '1',
                        'disabled' => false
                    ],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($model)) {
                $roles = Mds_seg_usuario_rol::find()->where(['idusuario' => $model->idusuario])->all();
                $roles_id = array();
                foreach ($roles as $rol) {
                    $roles_id[] = $rol['idrol'];
                }
                $model->roles = $roles_id;
            }
            ?>
            <?= $form->field($model, 'roles')->widget(Select2::class, [
                'data' => ArrayHelper::map(Mds_seg_rol::find()->where(['mds_seg_rol.deleted_at' => null])->orderBy(['descripcion' => SORT_ASC])->all(), 'idrol', 'descripcion'),
                'options' => ['id' => 'roles', 'placeholder' => 'Seleccione Roles...', 'multiple' => true],
                'pluginOptions' => [
                    'tags' => true,
                    // 'tokenSeparators' => [',', ' '],
                    'allowClear' => true
                ],
                'showToggleAll' => false,
            ])->label('Roles Asignados');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($model)) {
                $tipos_entrega = Mds_seg_usuario_entrega_tipo::find()->where(['idusuario' => $model->idusuario])->all();
                $tipos_entrega_id = array();
                foreach ($tipos_entrega as $tipo_ent) {
                    $tipos_entrega_id[] = $tipo_ent['idtipo'];
                }
                $model->tipos_entrega = $tipos_entrega_id;
            }
            ?>
            <?= $form->field($model, 'tipos_entrega')->widget(Select2::class, [
                'data' => ArrayHelper::map(Sds_ent_tipo::find()->all(), 'idtipo', 'descripcion'),
                'options' => ['id' => 'tipos_entrega', 'placeholder' => 'Seleccione Tipos Entregas...', 'multiple' => true],
                'pluginOptions' => [
                    'tags' => true,
                    // 'tokenSeparators' => [',', ' '],
                    'allowClear' => true
                ],
                'showToggleAll' => false,
            ])->label('Tipos de Entrega Asignados');
            ?>
        </div>
    </div>
    <div class="row">
        <div id="responsables_content" class="col-md-12" style="<?= !$model->responsable_todos ? 'display: block;' : 'display: none;' ?>">
            <?php
            if (isset($model)) {
                $responsables = Mds_seg_usuario_responsable::find()->where(['idusuario' => $model->idusuario])->all();
                $responsables_id = array();
                foreach ($responsables as $resp) {
                    $responsables_id[] = $resp['idresponsable'];
                }
                $model->responsables = $responsables_id;
            }
            ?>
            <?=
            $form->field($model, 'responsables')->widget(Select2::class, [
                'data' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_RESPONSABLE_ENTREGA), 'idconfiguracion', 'descripcion'),
                'options' => ['id' => 'responsables', 'placeholder' => 'Seleccione Responsables...', 'multiple' => true],
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', '\n'],
                    'allowClear' => true
                ],
            ])->label('Responsables Entrega Asignados');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="input-group">
                <?= $form->field($model, 'organismo_stock')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismo', 'descripcion'),
                    'options' => [
                        'placeholder' => 'Seleccionar Organismo ...',
                        'id' => 'cmb_organismo_stock',
                        'tabIndex' => '1',
                        'disabled' => false
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group">
                <?= $form->field($model, 'iddeposito')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Sds_stk_deposito::find()
                        ->where(['idorganismo' => $model->organismo_stock])
                        ->orderBy(['descripcion' => SORT_ASC])->all(), 'iddeposito', 'descripcion'),
                    'options' => [
                        'placeholder' => 'Seleccionar Depósito ...',
                        'id' => 'cmb_deposito',
                        'tabIndex' => '1',
                        'disabled' => $model->organismo_stock == null
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'direccionesCertificaciones')->widget(Select2::class, [
                'data' => $listDireccionesCertificaciones,
                'options' => [
                    'id' => 'direccionesCertificaciones',
                    'placeholder' => 'Seleccione direcciones',
                    'multiple' => true
                ],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true
                ],
                'showToggleAll' => false,
            ])->label('Direcciones certificaciones');
            ?>
        </div>
    </div>

    <?php /* $form->field($model, 'imagen')->textarea(['rows' => 6])  */ ?>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

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

<script>
    $("#cmb_contacto").change(function() {
        var idcontacto = $("#cmb_contacto").val();
        $.post("index.php?r=mds_org_contacto/get_contacto&id=" + idcontacto, function(data) {
            data = JSON.parse(data);
            $("#mds_seg_usuario-user").val(data['user']);
            $("#mds_seg_usuario-pass").val(data['pass']);
            $("#mds_seg_usuario-nombre").val(data['nombre']);
            $("#mds_seg_usuario-apellido").val(data['apellido']);
            $("#mds_seg_usuario-mail").val(data['mail']);
        });
    });

    $("#cmb_organismo_stock").change(function() {
        var idorganismo = $("#cmb_organismo_stock").val();
        if (idorganismo) {
            $("select#cmb_deposito").prop('disabled', false);
            $.post("index.php?r=sds_stk_deposito/cmb_deposito&idorganismo=" + idorganismo, function(data) {
                $("select#cmb_deposito").html(data);
            });
        } else {
            $("select#cmb_deposito").html("");
            $("select#cmb_deposito").prop('disabled', true);
        }
    });

    $("#is_externo").change(function() {
        $("#is_externo").prop('checked') ? $("#externo_content").show() : $("#externo_content").hide();
    });

    $("#resp_todos").change(function() {
        !$("#resp_todos").prop('checked') ? $("#responsables_content").show() : $("#responsables_content").hide();
    });
</script>