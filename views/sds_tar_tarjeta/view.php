<?php

use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_tar_tarjeta */
?>
<div class="sds-tar-tarjeta-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [            
            [
                'attribute' => 'fecha',
                //'header' => 'Organismo',
                'width' => '10%',
                'value' => function ($model) {
                    $fc = date_create($model->fecha);
                    $fc = date_format($fc, 'd/m/Y');
                    return $fc;
                },                
            ],
            'dni',
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'referente',
                'value' => function ($model) {
                    $idconfiguracion = $model->referente;
                    if ($idconfiguracion != null) {
                        $referente = Sds_com_configuracion::findOne($idconfiguracion);
                        return $referente->descripcion;
                    }
                    return "";
                },                
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'empresa',
                'value' => function ($model) {
                    $idconfiguracion = $model->empresa;
                    if ($idconfiguracion != null) {
                        $empresa = Sds_com_configuracion::findOne($idconfiguracion);
                        return $empresa->descripcion;
                    }
                    return "";
                },                
            ],
            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'idusuario',
                'value' => function ($model) {
                    $usuario = $model->idusuario;
                    if ($usuario != null) {
                        $user = Mds_seg_usuario::findOne($usuario);
                        return $user->user;
                    }
                    return "";
                },
            ],
            'numero',
            'observaciones:ntext',            
        ],
    ]) ?>

</div>
