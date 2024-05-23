<?php

use yii\widgets\DetailView;
use app\models\Sds_com_configuracion;
use app\models\Mds_seg_usuario;
use app\models\Mds_org_organismo;
use app\models\Mds_org_organismo_externo;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_capacitacion */
?>
<div class="mds-cap-capacitacion-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' =>
        [
            [
                'attribute' => 'idcapacitacion',
                'label' => 'Número de capacitación',
            ],
            [
                'attribute' => 'descripcion',
                'label' => 'Nombre',
            ],

            [
                'attribute' => 'Temática',
                'value' => function ($model) {
                    $id = $model->tematica;
                    if ($id != null) {
                        $configuracion = Sds_com_configuracion::findOne($id);
                        return $configuracion->descripcion;
                    }
                    return "";
                },
            ],

            [
                'attribute' => 'idusuario',
                'label' => 'Usuario de carga',
                'value' => function ($model) {
                    $idusuario = $model->idusuario;
                    if ($idusuario != null) {
                        $usuario = Mds_seg_usuario::findOne($idusuario);
                        return $usuario->user;
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'idorganismo',
                'label' => 'Organismo',
                'value' => function ($model) {
                    $idorganismo = $model->idorganismo;
                    $idorganismoexterno = $model->idorganismoexterno;

                    if ($idorganismo != null) {
                        $organismo = Mds_org_organismo::findOne($idorganismo);
                        return $organismo ? $organismo->descripcion : "";
                    } else if ($idorganismoexterno != null){
                        $organismo = Mds_org_organismo_externo::findOne($idorganismoexterno);
                        return $organismo ? $organismo->descripcion : "";
                    }
                    return "";
                },
            ],
            [
                'format' => 'html',
                'attribute' => 'detalle'
            ],
            [
                'format' => 'html',
                'attribute' => 'objetivos'
            ],
            [
                'format' => 'html',
                'attribute' => 'perfil'
            ],
            'nombre_corto:ntext',
        ],
    ]) ?>

</div>