<?php 
use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;
use app\models\Sds_stk_articulo;


DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.container-items', // required: css class selector
                    'widgetItem' => '.item', // required: css class
                    'limit' => 10, // the maximum times, an element can be cloned (default 999)
                    'min' => 1, // 0 or 1 (default 1)
                    'insertButton' => '.add-item', // css class
                    'deleteButton' => '.remove-item', // css class
                    'model' => $model_inventario_items[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                        'idarticulo',
                        'cantidad'
                    ],
                ]); ?>
                <div class="container-items" style="height: 10px;">
                    <?php foreach ($model_inventario_items as $i => $model_inventario_item) : ?>
                        <div class="item panel panel-default">
                            <div class="row">
                                <?= html::activeHiddenInput($model_inventario_item, "[{$i}]idinventarioitem") ?>
                                <?= html::activeHiddenInput($model_inventario_item, "[{$i}]idinventario") ?>
                                <?= html::activeHiddenInput($model_inventario_item, "[{$i}]idarticulo") ?>
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                    <?= $model_inventario_item->idarticulo ? Html::textInput('articulo',Sds_stk_articulo::findOne($model_inventario_item->idarticulo)->descripcion,['disabled' => true, 'class' => 'form-control input-md text-right']) : '' ?>
                                </div>
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <?= Html::activeTextInput($model_inventario_item, "[{$i}]cantidad",['type' => 'number', 'class' => 'form-control input-md text-center'])?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
    
                <?php DynamicFormWidget::end(); ?>