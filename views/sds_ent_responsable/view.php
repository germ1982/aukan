<?php

use app\models\Mds_org_organismo_externo;
use app\models\Sds_ent_entrega;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_responsable */
?>
<div class="sds-ent-responsable-view">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idresponsable',
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'dni',
                'value' => function ($model) {
                    return $model->dni != null ? $model->dni : "";
                }
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'telefono',
                'value' => function ($model) {
                    return $model->telefono != null ? $model->telefono : "";
                }
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'mail',
                'value' => function ($model) {
                    return $model->mail != null ? $model->mail : "";
                }
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'idorganismoexterno',
                'value' => function ($model) {
                    $entidad = Mds_org_organismo_externo::findOne($model->idorganismoexterno);
                    return $entidad != null ? $entidad->descripcion : "";
                },                
            ],
            [
                'attribute' => 'dni_frente',
                'format' => 'html',
                'value' => function ($model) {
                    return '<img id="dni_frente" src="' . $model->dni_frente . '" alt="" height="200px" />';
                }
            ],
            [
                'attribute' => 'dni_dorso',
                'format' => 'html',
                'value' => function ($model) {
                    return '<img id="dni_dorso" src="' . $model->dni_dorso . '" alt="" height="200px" />';
                }
            ],
        ],
    ]);
    $entregas_pendientes = Sds_ent_entrega::getRendicionesPendientes($model->idresponsable, -1, date("Y-m-d"));
    $rendiciones_detalle = "";
    foreach ($entregas_pendientes as $rend) {
        $rendicion_detalle = "<div class='col-md-12'>" . date('d/m/Y', strtotime(str_replace('-', '/', $rend->fecha_hora)))
            . " - " . $rend->detalle_tipo . " - Cant.: " . $rend->cantidad . " | Saldo a rendir: " . $rend->saldo;
        $rendiciones_detalle = $rendiciones_detalle . $rendicion_detalle . "</div>";
    }
    ?>
    <section class="panel">
        <div class="panel-body">
            <?php
            if ($rendiciones_detalle != "") {
                echo "<div class=\"row\" style=\"padding-top: 5%;\">
        <div class=\"col-md-12\">
            <b>Rendiciones Pendientes:</b>
            </div>
        </div>
        <div class=\"row\">" . $rendiciones_detalle;
            } else {
                echo "<div class=\"row\" style=\"padding-top: 5%;\">
        <div class=\"col-md-12\">
            <b>Sin Rendiciones Pendientes</b>
            </div>
        </div>
        <div class=\"row\">";
            }
            ?>
        </div>
    </section>
</div>