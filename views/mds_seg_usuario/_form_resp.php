<?php

use app\models\Mds_org_contacto;
use app\models\Mds_seg_rol;
use app\models\Mds_seg_usuario_rol;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_seg_usuario */
/* @var $form yii\widgets\ActiveForm */

$responsables = $model->responsable_todos ? Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_RESPONSABLE_ENTREGA) :
    Sds_com_configuracion::find()
    ->where('idconfiguracion in (select idresponsable from mds_seg_usuario_responsable where idusuario=' . $model->idusuario . ')')
    ->orderBy(['descripcion' => SORT_ASC])->all();
?>

<div class="mds-seg-usuario-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?php if (!empty($responsables)) : ?>
                <?= $form->field($model, 'responsable')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(
                        $responsables,
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
            <?php else : ?>
                <span class="text-danger"><b>Deben asignarse Responsables de Entrega al Usuario!<br>
                    Comuníquese con un administrador.</b></span>
            <?php endif; ?>
        </div>
    </div>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>
</div>