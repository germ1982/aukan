<?php

use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use app\models\Sds_ent_entrega;
use app\models\Sds_ent_solicitud_intermedia;
use app\models\Sds_ent_tipo;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_solicitud_intermedia */
?>
<div class="sds-ent-solicitud-intermedia-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idsolicitudintermedia',
            [
                'attribute' => 'emisor',
                'value' => function ($model) {
                    $entrega = Sds_ent_entrega::findOne($model->emisor);
                    $fc = date_create($entrega->fecha_hora);
                    $fc = date_format($fc, 'd/m/Y H:i');
                    $receptor = Sds_com_configuracion::findOne($entrega->receptor);
                    $emisor = $fc . ' - ' . $receptor->descripcion;
                    return $emisor;
                },
            ],
            [
                'attribute' => 'receptor',
                'value' => function ($model) {
                    return Sds_com_configuracion::findOne($model->receptor)->descripcion;
                },
            ],
            [
                'attribute' => 'fecha_hora',
                'value' => function ($model) {
                    $fc = date_create($model->fecha_hora);
                    $fc = date_format($fc, 'd/m/Y H:i');
                    return $fc;
                }
            ],
            [
                'attribute' => 'irregular',
                'value' => $model->irregular ? "Si" : "No"
            ],
            [
                'attribute' => 'usuario_carga',
                'value' => function ($model) {
                    $usuario = Mds_seg_usuario::findOne($model->usuario_carga);
                    return $usuario->user;
                },
            ],
            [
                'attribute' => 'usuario_aprobacion',
                'value' => function ($model) {
                    $usuario = $model->usuario_aprobacion != null ?  Mds_seg_usuario::findOne($model->usuario_aprobacion)->user : "";
                    return $usuario;
                },
            ],
            [
                'attribute' => 'estado',
                'value' => function ($model) {
                    switch ($model->estado) {
                        case Sds_ent_solicitud_intermedia::ESTADO_PENDIENTE:
                            return "Pendiente";
                        case Sds_ent_solicitud_intermedia::ESTADO_APROBADA:
                            return "Aprobada";
                        case Sds_ent_solicitud_intermedia::ESTADO_RECHAZADA:
                            return "Rechazada";
                        case Sds_ent_solicitud_intermedia::ESTADO_ENTREGADA:
                            return "Entregada";
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'idtipo',
                'value' => function ($model) {
                    return Sds_ent_tipo::findOne($model->idtipo)->descripcion;
                },
            ],
            'cantidad',
            [
                'attribute' => 'rendiciones_pendientes',
                'format' => 'html',
            ],
            'observaciones:ntext',
        ],
    ]) ?>

</div>