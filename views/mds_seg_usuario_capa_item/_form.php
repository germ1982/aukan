<?php

use app\models\Sds_gis_capa_item;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_seg_usuario_capa_item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-seg-usuario-capa-item-form">

	<?php $form = ActiveForm::begin(); ?>

	<div class="row">
		<div class="col-md-12">
			<?= $form->field($model, 'idcapaitem')
				->dropDownList(
					ArrayHelper::map(
						Sds_gis_capa_item::find()->orderBy(['descripcion' => SORT_ASC])->all(),
						'idcapaitem',
						'descripcion'
					),
					['prompt' => ""]
				) ?>
		</div>
	</div>

	<?php if (!Yii::$app->request->isAjax) { ?>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	<?php } ?>

	<?php ActiveForm::end(); ?>

</div>