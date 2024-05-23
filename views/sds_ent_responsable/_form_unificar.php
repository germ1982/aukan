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
?>

<div class="mds-seg-usuario-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'idresponsable_reemp')->widget(Select2::classname(), [
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
            ])->label("Responsable a Reemplazar");
            ?>
        </div>
    </div>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>
</div>