<?php

use app\models\Mds_org_organismo;
use app\models\Mds_org_organismo_vinculacion;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_organismo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-org-organismo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'padre')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(
                    Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                    'idorganismo',
                    'descripcion'
                ),
                'options' => ['placeholder' => 'Seleccionar Organismo Padre ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            if (isset($model)) {
                $vinculaciones = Mds_org_organismo_vinculacion::find()->where(['idorganismo' => $model->idorganismo])->all();
                $vinculaciones_id = array();
                foreach ($vinculaciones as $vinculacion) {
                    $vinculaciones_id[] = $vinculacion['vinculacion'];
                }
                $model->vinculaciones = $vinculaciones_id;
                if (empty($model->vinculaciones)) {
                    array_push($model->vinculaciones, $model->padre);
                }
            }
            ?>
            <?=
                $form->field($model, 'vinculaciones')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Mds_org_organismo::find()->all(), 'idorganismo', 'descripcion'),
                    'options' => ['id' => 'vinculaciones', 'placeholder' => '', 'multiple' => true],
                    'size' => Select2::MEDIUM,
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [',', ' '],
                        //'maximumInputLength' => 50,
                        'allowClear' => true
                    ],
                ])->label('Organismos Vinculados');
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'abreviatura')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?php
            if ($model->isNewRecord) {
                $model->nivel = 1;
            }
            ?>
            <!--     [ 'template' =>   '<div data-plugin-spinner="" data-plugin-options="{ &quot;value&quot;:0, &quot;min&quot;: 0, &quot;max&quot;: 10 }">
                                    <div class="input-group" style="width:150px;">
                                        {input}
                                        <div class="spinner-buttons input-group-btn">
                                            <button type="button" class="btn btn-default spinner-up">
                                                <i class="fa fa-angle-up"></i>
                                            </button>
                                            <button type="button" class="btn btn-default spinner-down">
                                                <i class="fa fa-angle-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>'] -->
            <?= $form->field($model, 'nivel')->textInput([
                'maxlength' => true,
                'type' => 'number', 'class' => 'spinner-input form-control',
            ]) ?>
        </div>
        <div class="col-md-2" style="padding-top: 35px;">
            <?= $form->field($model, 'activo')->checkbox() ?>
        </div>
        <div class="col-md-2" style="padding-top: 35px;">
            <?= $form->field($model, 'recepcion')->checkbox() ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

</div>