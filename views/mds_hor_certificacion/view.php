<?php

use yii\widgets\DetailView;
use app\models\Sds_com_persona;
use app\models\Mds_org_contacto;


function get_mes($mes)
        {
            switch ($mes)
            {
                case "1":
                    $mes = "Enero";
                    break;

                case "2":
                    $mes =  "Febrero";
                    break;

                case "3":
                    $mes =  "Marzo";
                    break;

                case "4":
                    $mes =  "Abril";
                    break;
                case "5":
                    $mes = "Mayo";
                    break;

                case "6":
                    $mes =  "Junio";
                    break;

                case "7":
                    $mes =  "Julio";
                    break;

                case "8":
                    $mes =  "Agosto";
                    break;
                case "9":
                    $mes = "Septiembre";
                    break;

                case "10":
                    $mes =  "Octubre";
                    break;

                case "11":
                    $mes =  "Noviembre";
                    break;

                case "12":
                    $mes =  "Diciembre";
                    break;
            } 
            return $mes;
    }   

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_certificacion */
?>
<div class="mds-hor-certificacion-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'idcertificacion',
                'label' => 'Id',
            ],
            [
                'attribute' => 'certificado',
                'label' => 'Empleado',
                'value' => function ($model) {
                    if ($model->certificado != null) {
                        $contacto = Mds_org_contacto::findOne($model->certificado);
                        $persona = Sds_com_persona::findOne($contacto->idpersona);
                        return "$persona->apellido, $persona->nombre";
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'certificante',
                'value' => function ($model) {
                    if ($model->certificante != null) {
                        $contacto = Mds_org_contacto::findOne($model->certificante);
                        $persona = Sds_com_persona::findOne($contacto->idpersona);
                        return "$persona->apellido, $persona->nombre";
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'periodo_mes',
                'value' => function ($model) {

                    return get_mes($model->periodo_mes);
                },
            ],
            'periodo_anio',
            [
                'attribute' => 'desde',
                'label' => 'Hora Ingreso',
                'value' => function ($model) {
                    $hora_desde = $model->desde;
                    if ($hora_desde != null) {
                        $hora_desde = date("H:i",strtotime($hora_desde));
                        return $hora_desde;
                    }
                    return "";
                },
            ],
            [
                'attribute' => 'hasta',
                'label' => 'Hora Egreso',
                'value' => function ($model) {
                    $hora_hasta = $model->hasta;
                    if ($hora_hasta != null) {
                        $hora_hasta = date("H:i",strtotime($hora_hasta));
                        return $hora_hasta;
                    }
                    return "";
                },
            ],
            'detalle:ntext',
            [
                'attribute' => 'estado',

                'value' => function ($model) {
            
                    $estado = $model->estado;
                    switch($estado)
                        {
                            case 0:
                                return 'Pendiente';
                            case 1:
                                return 'Generado';
                        }
                    return "";
                },
            ],

        ],
    ]) ?>

</div>
