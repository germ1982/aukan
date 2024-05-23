<?php

use app\models\Mds_org_contacto;
use app\models\Sds_com_persona;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use app\models\Sds_reg_movimiento;
use yii\widgets\DetailView;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_registro_autosolicitud */
?>
<div class="sds-reg-registro-autosolicitud-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'idregistro',
                'label' => 'Registro numero',
            ],

            [
                'attribute' => 'fecha_hora',
                'label' => 'Fecha y Hora',
                'value' => function ($model) {
                    $fecha = $model->fecha_hora;
                    $anio = substr($fecha, 0, 4);
                    $mes  = substr($fecha, 5, 2);
                    $dia = substr($fecha, 8, 2);
                    $hora = substr($fecha, 11, 5);
                    $fecha = "$dia/$mes/$anio $hora";
                    return $fecha;
                },
            ],

            [
                'attribute' => 'usuario_solicitante',
                'label' => 'Solicitante',
                'value' => function ($model) {
                    $id = $model->usuario_solicitante;
                    if ($id != null) {
                        $contacto = Mds_org_contacto::findOne($id);
                        $persona=Sds_com_persona::findOne($contacto->idpersona);
                        $descripcion=$persona->apellido.', '.$persona->nombre;
                        return $descripcion;
                    }
                    return "";
                },
            ],

            [
                'attribute' => 'idorganismo',
                'label' => 'Organismo',
                'value' => function ($model) {
                    $id = $model->idorganismo;
                    if ($id != null) {
                        $configuracion = Mds_org_organismo::findOne($id);
                        return $configuracion->descripcion;
                    }
                    return "";
                },
            ],

            [
                'attribute' => 'iddispositivo',
                'label' => 'Dispositivo',
                'value' => function ($model) {
                    $id = $model->iddispositivo;
                    if ($id != null) {
                        $configuracion = Mds_org_dispositivo::findOne($id);
                        return $configuracion->descripcion;
                    }
                    return "";
                },
            ],

            [
                'attribute' => 'problema',
                'label' => 'Problema Reportado',
            ],
            [
                'attribute' => 'usuario_derivacion',
                'label' => 'Derivador, usuario de carga',
                'value' => function ($model) {
                    $id = $model->usuario_derivacion;
                    if ($id != null) {
                        $usuario = Mds_seg_usuario::findOne($id);
                        return $usuario->user;
                    }
                    return "";
                },
            ],

            [
                'attribute' => 'estado',
                'Label' => 'Estado',
                'value' => function($model){
                    $estado = "";
                    $idtipo = $model->idtipo;
                    $cerrado = $model->registro_abierto;
        
                    if ($cerrado == 0)
                        {$estado = "Finalizado";}
                    else
                        {
                            if ($idtipo==7 or $idtipo==10)//7:Informatica,10:Mantenimiento
                                {$estado = "Pendiente";}
                            else
                                {$estado = "Asignado";}
                        }
                    return $estado;
                },
            ],


            [
                'attribute' => 'movimiento',
                'label' => 'Movimientos',
                "format" => "ntext",
                'value' => function($model){
                    $model_movimientos = Sds_reg_movimiento::findBySql("select * from sds_reg_movimiento where idregistro = $model->idregistro order by idmovimiento")->all();
                    $listado = "";
                    foreach($model_movimientos as $movimiento) 
                        { 
                            $date = date_create($movimiento['fecha']);
                            $fecha=date_format($date, 'd/m/Y'); 
                            $model_tecnico = Mds_seg_usuario::findOne($movimiento['idtecnico']);
                            $tecnico = $model_tecnico->user;
                            $movimiento = $movimiento['descripcion'];

                            $listado  = "$listado $fecha - $tecnico - $movimiento \n ";
                        }

                    return $listado;
                }, 

            ],

            

        ],
    ]) ?>

</div>
