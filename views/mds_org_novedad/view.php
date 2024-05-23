<?php

use app\models\Mds_org_novedad;
use app\models\Sds_com_configuracion;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_novedad */
?>
<div class="mds-org-novedad-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'idnovedad',
                'label' => 'Id Novedad',
            ],
            [
                'attribute' => 'titulo',
                'label' => 'Título',
            ],
            [
                'attribute' => 'descripcion',
                'label' => 'Descripción',
            ],
            [
                'attribute' => 'fechahora',
                'label' => 'Fecha y Hora',
                'value' => function ($model) {
                    $fc = date_create($model->fechahora);
                    $fc = date_format($fc, 'd/m/Y H:i:s');
                    return $fc;
                }
            ],
            [
                'attribute' => 'estado',
                'label' => 'Estado',
                'value' => function ($model) {
                    switch ($model->estado) {
                        case Mds_org_novedad::NO_PUBLICADO:
                            return "No Publicado";
                        case Mds_org_novedad::PUBLICADO:
                            return "Publicado";
                        default:
                            return "";
                    }
                },
            ],
            [
                'attribute' => 'tipo',
                'label' => 'Tipo',
                'value' => function ($model) {
                    $idtipo = $model->tipo;
                    if ($idtipo != null) {
                        $tipo = Sds_com_configuracion::findOne($idtipo);
                        return $tipo->descripcion;
                    }
                    return "(no definido)";
                },
            ],
            [
                'attribute' => 'imagen',
                'label' => 'Imagen',
                'value' =>  $model->imagen ? 'uploads/novedades/'.$model->imagen : null,
                'format' => ['image', ['width' => '100', 'height' => '100']],
            ]
        ],
    ]) ?>

</div>