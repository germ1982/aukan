<?php

use Mpdf\Tag\Select;
use yii\helpers\Html;
use kartik\select2\Select2;
use PHPUnit\Framework\MockObject\Rule\MethodName;
use yii\base\Model;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_visita_equipo */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="sds-bdc-visita-equipo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idvisita')->hiddenInput()->label(false);?>
    <?= $form->field($model, 'idequipo')->widget(Select2::class,[
        'data' => ArrayHelper::map(
            $equipos,
            'idequipo',
            function ($model){
                return "#".str_pad($model->idequipo,6,"0", STR_PAD_LEFT)." - $model->tipo_descripcion $model->marca_descripcion".($model->matricula!=null ? " | Mat.: $model->matricula":"");
            }
        ),
        'options' => [
            'placeholder' => '- Seleccionar Equipo -',
        ],
        'pluginOptions' => [
            'allowClear' => false,
        ]
    ]);?>
    
    <?= $form->field($model, 'ip')->textInput(['maxlength' => true, 'pattern'=>'^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$', 'placeholder'=>'111.111.111.111']) ?>
    <?= $form->field($model, 'idresponsable')->widget(Select2::class,[
        'data' => ArrayHelper::map(
            $responsables,
            'idcontacto',
            function($model){
                return "$model->legajo - $model->nombre";
            }
        ),
        'options' => [
            'placeholder' => '- Seleccionar Responsable -'
        ],
        'pluginOptions' => [
            'allowClear' => false
            ]
    ]);?>

    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>

    

	<?php if (!Yii::$app->request->isAjax){ ?>
	<div class="form-group">
	<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	<?php } ?>

    <?php ActiveForm::end();?>
    
</div>
