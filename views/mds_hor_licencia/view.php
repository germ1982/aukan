<?php

use yii\widgets\DetailView;
use app\models\Sds_com_persona;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_usuario;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_licencia */
?>
<div class="mds-hor-licencia-view">
 
    <?= DetailView::widget([

        'model' => $model,
        'attributes' => [
/*             [
                'attribute' => 'idlicencia',
                'label'=>'Número de licencia',
            ], */
/*             [
                'attribute' => 'idcontacto',
                'label'=>'Id del contacto',
            ], */

            [
                'attribute' => 'nombre',
                'label'=>'Nombre',
                'value' => function ($model) {
                    $idcontacto = $model->idcontacto;
                    if ($idcontacto != null) {
                        $contacto = Mds_org_contacto::findOne($idcontacto);
                        $idpersona = $contacto->idpersona;
                        if ($idpersona != null) {
                            $persona = Sds_com_persona::findOne($idpersona);
                            $aux = "$persona->apellido, $persona->nombre";
                            return $aux;
                        }
        
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'legajo',
                'label'=>'Legajo',
                'value' => function ($model) {
                    $idcontacto = $model->idcontacto;
                    if ($idcontacto != null) {
                        $contacto = Mds_org_contacto::findOne($idcontacto);
                        return $contacto->legajo;
        
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'desde',
                'label' => 'Desde',
                'value' => function ($model) {
                    $fecha = $model->desde;
                    $anio = substr($fecha, 0, 4);
                    $mes  = substr($fecha, 5, 2);
                    $dia = substr($fecha, 8, 2);
                    $fecha = "$dia/$mes/$anio";
                    return $fecha;
                },
            ],
            [
                'attribute' => 'hasta',
                'label' => 'Hasta',
                'value' => function ($model) {
                    $fecha = $model->hasta;
                    $anio = substr($fecha, 0, 4);
                    $mes  = substr($fecha, 5, 2);
                    $dia = substr($fecha, 8, 2);
                    $fecha = "$dia/$mes/$anio";
                    return $fecha;
                },
            ],
            'cantidad_dias',
            'detalle:ntext',

            [
                'class' => '\kartik\grid\DataColumn',
                'attribute' => 'idusuario',
                'label' => 'Usuario de carga',
                'value' => function ($model) {
                    $usuario = $model->idusuario;
                    if ($usuario != null) {
                        $user = Mds_seg_usuario::findOne($usuario);
                        return $user->user;
                    }
                    return "";
                },
            ],

        ],
    ]) ?>

</div>
