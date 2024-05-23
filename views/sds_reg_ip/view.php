<?php

use app\controllers\Sds_reg_ipController;
use yii\widgets\DetailView;
use app\models\Sds_com_configuracion;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_org_contacto;
use app\models\Sds_com_persona;
use app\models\Sds_reg_ip;
use app\models\Sds_bdc_equipo;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_ip */
?>
<div class="sds-reg-ip-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'idip',
                'label' => 'Id',

            ],
            [
                'attribute' => 'idequipo',
                'label' => 'Id Equipo',
                'value' => function ($model) 
                {
                    if($model->idequipo)
                        {return $model->idequipo;}

                    return "No esta asignada a ningun equipo";
                },
            ],
    
            [
                'attribute' => 'ip',
                'label' => 'Ip',
                'value' => function ($model) 
                                {
                                    return "$model->subred.$model->ip";
                                },
            ],

            [
                'attribute' => 'asignacion',
                'label' => 'Asignación',
                'value' => function ($model) 
                                {
                                
                                    $idasignacion = $model->asignacion;
                                    if($model->idequipo)
                                    {
                                        $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                                        if ($equipo->tipo != null) {
                                            $idasignacion = $equipo->tipo;
                                        }
                                    }

                                    if ($idasignacion != null) {
                                        $asignacion = Sds_com_configuracion::findOne($idasignacion);
                                        return $asignacion->descripcion;
                                    }
                                    return "";
                                },
            ],
            [
                'attribute' => 'idcontacto',
                'label' => 'Usuario',
                'value' => function ($model) {
                    if ($model->idequipo != null) 
                        {
                            $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                            if ($equipo->usuario != null) {
                                $contacto=Mds_org_contacto::findOne($equipo->usuario);
                                if($contacto!=null){
                                    $usuario=Sds_com_persona::findOne($contacto->idpersona);
                                    return $usuario->apellido.' '.$usuario->nombre;
                                }
                            }
                        }
                    else
                        {
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
                        }
                    return "";
                },
            ],
            [
                'attribute' => 'iddispositivo',
                'label' => 'Sector',
                'value' => function ($model) 
                                {
                                    if ($model->idequipo != null) 
                                    {
                                        $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                    
                                        if ($equipo->idorganismo != null) {
                                            $organismo = Mds_org_organismo::findOne($equipo->idorganismo);
                                            return $organismo->descripcion;
                                        }
                                    }
                                    else
                                        {
                                            $idcontacto = $model->idcontacto;
                                            if ($idcontacto != null) {
                                                $contacto = Mds_org_contacto::findOne($idcontacto);
                                                $iddispositivo = $contacto->iddispositivo;
                                
                                                if ($iddispositivo != null) {
                                                    $dispositivo = Mds_org_dispositivo::findOne($iddispositivo);
                                                    $organismo = Mds_org_organismo::findOne($dispositivo->idorganismo);
                                                    return $organismo->descripcion;
                                                }
                                            }
                                        }
                                    return "";
                                },
            ],
            [
                'attribute' => 'sistema_operativo',
                'label' => 'Sistema Operativo',
                'value' => function ($model) 
                                {
                                    $id = $model->sistema_operativo;
                                    if($model->idequipo)
                                    {
                                        $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                                        if ($equipo->sistema_operativo != null) {
                                            $id = $equipo->sistema_operativo;
                                        }
                                    }
                                    if ($id != null) {
                                        $item = Sds_com_configuracion::findOne($id);
                                        return $item->descripcion;
                                    }
                                    return "";
                                },
            ],
            [
                'attribute' => 'procesador',
                'label' => 'Procesador',
                'value' => function ($model) 
                                {
                                    $id = $model->procesador;
                                    if($model->idequipo)
                                    {
                                        $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                                        if ($equipo->procesador != null) {
                                            $id = $equipo->procesador;
                                        }
                                    }
                                    if ($id != null) {
                                        $item = Sds_com_configuracion::findOne($id);
                                        return $item->descripcion;
                                    }
                                    return "";
                                },
            ],
            [
                'attribute' => 'memoria',
                'label' => 'Memoria',
                'value' => function ($model) 
                                {
                                    $id = $model->memoria;
                                    if($model->idequipo)
                                    {
                                        $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                                        if ($equipo->memoria != null) {
                                            $id = $equipo->memoria;
                                        }
                                    }
                                    if ($id != null) {
                                        $item = Sds_com_configuracion::findOne($id);
                                        return $item->descripcion;
                                    }
                                    return "";
                                },
            ],
            [
                'attribute' => 'disco',
                'label' => 'Disco',
                'value' => function ($model) 
                                {
                                    $id = $model->disco;
                                    if($model->idequipo)
                                    {
                                        $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                                        if ($equipo->disco != null) {
                                            $id = $equipo->disco;
                                        }
                                    }
                                    if ($id != null) {
                                        $item = Sds_com_configuracion::findOne($id);
                                        return $item->descripcion;
                                    }
                                    return "";
                                },
            ],
            [
                'attribute' => 'conectividad',
                'label' => 'Conectividad',
                'value' => function ($model) 
                                {
                                    $id = $model->conectividad;
                                    if($model->idequipo)
                                    {
                                        $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                                        if ($equipo->conectividad != null) {
                                            $id = $equipo->conectividad;
                                        }
                                    }
                                    if ($id != null) {
                                        $item = Sds_com_configuracion::findOne($id);
                                        return $item->descripcion;
                                    }
                                    return "";
                                },
            ],
            [
                'attribute' => 'observaciones',
                'label' => 'Observaciones',
            ],

            [
                'label' => 'Camara',
                'value' => function ($model) 
                                {
                                    if($model->asignacion==261)//Camara
                                        {
                                            $ban = Sds_reg_ipController::actionVerificar_gravacion_de_camara("$model->subred.$model->ip");
                                            switch($ban)
                                                {
                                                    case 0:{
                                                        $ban = "No esta grabando... "; 
                                                        break;
                                                    }
                                                    case 1:{
                                                        $ban = "Grabando normalmente... "; 
                                                        break;
                                                    }
                                                    case 2:{
                                                        $ban = "No existe ruta de Grabacion, probablemente la ip $model->subred.$model->ip no sea una camara, si lo es, no esta grabando"; 
                                                        break;
                                                    }
                                                }
                                        }
                                    else
                                        {
                                            $ban = 'No es Camara...';
                                        }
                                    

                                    return "$ban";
                                },
            ],
            
        ],
    ]) ?>

</div>

