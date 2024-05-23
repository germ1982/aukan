<?php

use app\models\Mds_seg_item;
use app\models\Sds_com_menu;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-com-menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'padre')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            //Sds_com_menu::find()->where(['padre'=>null])->orderBy(['descripcion'=>SORT_ASC])->all(),
            Sds_com_menu::find()->orderBy(['descripcion'=>SORT_ASC])->all(),
            'idmenu',
            function($model){
                if($model->padre!=null){
                    $padre=Sds_com_menu::findOne($model->padre);
                    return $padre->descripcion.' > '.$model->descripcion;
                }
                return $model->descripcion;
            }            
        ),
        'options' => [
            'placeholder' => '- Seleccionar Padre -'
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'disabled' => false,
            ]
        ]);
        ?>
    <?= $form->field($model, 'ruta')->textInput() ?>

    <?= $form->field($model, 'iditem')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            Mds_seg_item::find()->all(),
            'iditem',
            function($model){
                return $model->iditem.' - '.$model->descripcion;
            }
        ),
        'options' => [
            'placeholder' => '- Seleccionar Item -'
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'disabled' => false,
            ]
        ]); ?>

    <?= $form->field($model, 'orden')->textInput() ?>

    <?= $form->field($model, 'icono')->textInput(['maxlength' => true]) ?>
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
<?php
$script = <<<  JS
$('#sds_com_menu-padre').change(function(){
    $.post("index.php?r=sds_com_menu/get_orden&idpadre="+$(this).val(), function(data){
        $('#sds_com_menu-orden').val(data);
    });
});
JS;
$this->registerJs($script);
?>