<?php

use app\models\Mds_atp_sucursal;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_atp_alta */

?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>
<div class="mds-atp-alta-create">
    <div class="mds-atp-alta-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-12 required" required id="idsucursal-required">
                <?= $form->field($model, 'idsucursal')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(
                        Mds_atp_sucursal::find()->all(),
                        'idsucursal',
                        function ($model) {
                            return 'Código: ' . $model['codigo'] . ' - Dirección: ' . $model['direccion'];
                        }
                    ),
                    'options' => [
                        'prompt' => '-- Seleccione una opción --',
                        'placeholder' => 'Sucursal',
                    ],
                    'size' => Select2::MEDIUM,
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Sucursal')
                ?>
            </div>
            <div class="col-md-12">
                <p>Cantidad total de altas a procesar: <?php echo $count_altas ?></p>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>