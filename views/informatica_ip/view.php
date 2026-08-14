<?php

use app\models\Configuracion;
use app\models\InformaticaIp;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InformaticaIp */
?>
<div class="informatica-ip-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ip',
            [
                'label' => 'Detalle', // Nombre de la fila
                'value' => function ($model) {
                    $info = InformaticaIp::get_dispositivo_red($model->iddispositivo_red);
                    $obsDispositivo = $info['observacion'] ?? '';
                    $idDispositivo = $info['iddispositivo'] ?? null;
                    return $obsDispositivo;
                },
            ],
                        [
                'label' => 'Mac Adress', // Nombre de la fila
                'value' => function ($model) {
                    return $model->mac ?? '';
                },
            ],
            [
                'label' => 'Mascara', // Nombre de la fila
                'value' => function ($model) {
                    return Configuracion::findOne($model->mascara)->descripcion;
                },
            ],
            [
                'label' => 'Puerta de Enlace', // Nombre de la fila
                'value' => function ($model) {
                    return Configuracion::findOne($model->puerta_enlace)->descripcion;
                },
            ],
            [
                'label' => 'DNS', // Nombre de la fila
                'value' => function ($model) {
                    return Configuracion::findOne($model->dns)->descripcion;
                },
            ],
            [
                'label' => 'Observacion', // Nombre de la fila
                'value' => function ($model) {
                    return $model->observacion ?? '';
                },
            ],


        ],
    ]) ?>

</div>