<?php

use app\models\Mds_org_organismo;
use app\models\Sds_bdc_equipo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_fotocopiadora */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-reg-fotocopiadora-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'idproveedor')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Sds_com_configuracion::find()->where([
                        'idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_PROVEEDOR
                    ])->all(),
                    'idconfiguracion',
                    'descripcion'
                ),
                'options' => [
                    'placeholder' => 'Seleccione Proveedor...',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'disabled' => false,
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'safipro')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'expediente_fisico')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'idorganismo')->widget(Select2::class, [
            'data' => ArrayHelper::map(
                Mds_org_organismo::find()->all(),
                'idorganismo',
                'descripcion'
            ),
            'options' => [
                'placeholder' => 'Seleccione Organismo...',
            ],
            'pluginOptions' => [
                'allowClear' => true,
                'disabled' => false,
            ]
        ]); ?>
    </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'expediente_gde')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'lugar')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'idequipo')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Sds_bdc_equipo::find()->where([
                        'tipo' => Sds_bdc_equipo::FOTOCOPIADORA
                    ])->all(),
                    'idequipo',
                    function ($equipo) {
                        $marca = Sds_com_configuracion::findOne($equipo->marca);
                        return '#' . str_pad($equipo->idequipo, 6, "0", STR_PAD_LEFT) . ' | ' . $marca->descripcion . ($equipo->modelo != '' ? ' - ' . $equipo->modelo : '');
                    }
                ),
                'options' => [
                    'placeholder' => 'Seleccione Equipo...',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'disabled' => false,
                ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'copias')->textInput(['type' => 'number']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <?= $form->field($model, 'vencimiento')->widget(DatePicker::class, [
                'language' => 'es',
                'layout' => '{picker}{input}',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => '',
                ],
                'pluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'startDate' => date('d/M/Y'),
                    //'endDate' => date('d/m/Y'),
                    'todayHighlight' => true,
                    'autoclose' => true,
                    'required' => true
                ]
            ])->label('Vencimiento:'); ?></div>
    </div>
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