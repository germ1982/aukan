<?php

use app\models\Mds_seg_usuario;
use app\models\Sds_stk_articulo;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
?>
<div class="sds-stk-entrega-solicitud-form">
<?php
 $form = ActiveForm::begin(); ?>
 <div class="row">
    <div class="col-md-2">
    <?= $form->field($model, 'cantidad')->textInput(['type' => 'number'])?>
    </div>
    <div class="col-md-10">
    <?= $form->field($model, 'idarticulo')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            Sds_stk_articulo::find()->where(['organismo'=>Yii::$app->user->identity->organismo_stock])->all(),
            'idarticulo',
            function ($model) {
                return $model->idarticulo . ' - ' . $model->descripcion;
            }
            ),
        // 'data' => ArrayHelper::map(
        //     Sds_stk_articulo::find()->all(),
        //     'idarticulo',
        //     function ($model) {
        //         return $model->idarticulo . ' - ' . $model->descripcion;
        //     }
        
        'options' => [
            'placeholder' => '- Articulo -'
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'disabled' => false,
        ]
        ]);
    ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

</div>