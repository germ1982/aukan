<?php

use app\models\Sds_com_configuracion_tipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_configuracion */
?>
<div class="sds-com-configuracion-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'idconfiguraciontipo',
                'value' => function ($model) {
                    $idtipo = $model->idconfiguraciontipo;
                    if ($idtipo != null) {
                        $conf_tipo = Sds_com_configuracion_tipo::findOne($idtipo);
                        return $conf_tipo->descripcion;
                    }
                    return "";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Sds_com_configuracion_tipo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idconfiguraciontipo', 'descripcion'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => false],
                ],
            ],
            'descripcion',
            [
                'attribute' => 'activo',
                'value' => function ($model) {
                    return $model->activo == 1 ? 'Si' : 'No';
                },
            ],
        ],
    ]) ?>

</div>