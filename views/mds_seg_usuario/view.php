<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo_externo;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_seg_usuario */
?>
<div class="mds-seg-usuario-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'user',
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'nombre',
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'apellido',
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'dni',
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'mail',
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'idcontacto',
                'value' => function ($model) {
                    $contacto = $model->idcontacto;
                    if ($contacto != null) {
                        $contacto = Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                        join sds_com_persona p on p.idpersona=c.idpersona
                        where idcontacto=" . $contacto)->one();
                        return $contacto->nombre . " " . $contacto->apellido;
                    }
                    return "";
                }
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'externo',
                'value' => function ($model) {
                    $entidad = Mds_org_organismo_externo::findOne($model->externo);
                    return $entidad != null ? $entidad->descripcion : "";
                }               
            ],
        ],
    ]) ?>

</div>