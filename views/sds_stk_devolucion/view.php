<?php

use app\models\Mds_org_contacto;
use app\models\Mds_seg_usuario;
use yii\widgets\DetailView;
use app\models\Sds_com_configuracion;
use app\models\Sds_stk_articulo;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_devolucion */
?>
<div class="sds-stk-devolucion-view">

 entrega:
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'idarticulo',
                'value' => function ($model) {return Sds_stk_articulo::findOne($model->idarticulo)->descripcion;},
            ],
            [
                'attribute' => 'fecha_hora_entrega',
                'value' => function ($model) {
                    if ($model->fecha_hora_entrega != null) {
                        $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hora_entrega)));
                        return "$fecha";
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'hora_entrega',
                'value' => function ($model) {
                    if ($model->fecha_hora_entrega != null) {
                        $fecha = date('H:m', strtotime(str_replace('/', '-', $model->fecha_hora_entrega)));
                        return "$fecha";
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'responsable_entrega',
                'value' => function ($model) {
                    $usuario = Mds_seg_usuario::findOne($model->responsable_entrega);
                    return "$usuario->apellido $usuario->nombre";
                },
            ],
            [
                'attribute' => 'destinatario',
                'value' => function ($model) {return Mds_org_contacto::getAyN($model->destinatario);},
            ],
            'observaciones_entrega:ntext',
        ],
    ]) ?>
<br>
Devolucion:
<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            [
                'attribute' => 'fecha_hora_devolucion',
                'value' => function ($model) {
                    if($model->responsable_devolucion==null){return '';}
                    if ($model->fecha_hora_devolucion != null) {
                        $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hora_devolucion)));
                        return "$fecha";
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'hora_devolucion',
                'value' => function ($model) {
                    if($model->responsable_devolucion==null){return '';}
                    if ($model->fecha_hora_devolucion != null) {
                        $fecha = date('H:m', strtotime(str_replace('/', '-', $model->fecha_hora_devolucion)));
                        return "$fecha";
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'responsable_devolucion',
                'value' => function ($model) {
                    if($model->responsable_devolucion==null){return '';}
                    $usuario = Mds_seg_usuario::findOne($model->responsable_devolucion);
                    return "$usuario->apellido $usuario->nombre";
                },
            ],

            'observaciones_devolucion:ntext',
            [
                'attribute' => 'estado',
                'value' => function ($model) {return $model->estado ? Sds_com_configuracion::findOne($model->estado)->descripcion : '';},
            ],
        ],
    ]) ?>

</div>
