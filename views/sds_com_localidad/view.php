<?php

use yii\widgets\DetailView;
use app\models\Sds_com_provincia;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_localidad */
?>
<div class="sds-com-localidad-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'class'=>'\kartik\grid\DataColumn',
                'attribute'=>'descripcion',
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'idprovincia',
                'label' => 'Provincia',
                'value' => function ($model) {
                    $idprovincia = $model->idprovincia;
                    if ($idprovincia != null) {
                        $provincia = Sds_com_provincia::findOne($idprovincia);
                        return $provincia->descripcion;
                    }
                    return "";
                },            
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'activo',
                'label' => 'Activo',
                'width' => '7%',
                'value' => function ($model) {
                    if ($model->activo)
                        return "Si";
                    else
                        return "No";
                },
               
            ],
        ],
    ]) ?>

</div>
