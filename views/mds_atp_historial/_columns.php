<?php
use yii\helpers\Url;

return [       
        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_atp_historial',
        'label'=>'Id',
        'width' => '2%',
    ],   
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
        'label'=>'Motivo de cambio de Estado',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha_hora',
        'label'=> 'Fecha Hora Registro',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado_anterior',
        'label'=>'Estado Anterior',
        'width' => '10%',
        'value' => function ($model) {
            if ($model->estado_anterior==$model->estado_nuevo)
            {
                return '';
            }
            else
            {
                if ($model->estado_anterior==1)
                {
                    return 'Inscripto';
                }
                else
                {
                    if ($model->estado_anterior==2)
                    {
                        return 'Rechazado';
                    }
                    else
                    {
                        if ($model->estado_anterior==3)
                        {
                            return 'Pendiente Evaluación';
                        }
                        else
                        {
                            if ($model->estado_anterior==4)
                            {
                                return 'Aprobado';
                            }
                            else
                            {
                                return "Estado erroneo - N°: " . $model->estado_anterior;
                            }
                        }
                    }
                }
                

            }
                       
        },        
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado_nuevo',
        'label'=>'Estado Nuevo',
        'width' => '10%',
        'value' => function ($model) {
            if ($model->estado_anterior==$model->estado_nuevo)
            {
                return '';
            }
            {

                if ($model->estado_nuevo==1)
                {
                    return 'Inscripto';
                }
                else
                {
                    if ($model->estado_nuevo==2)
                    {
                        return 'Rechazado';
                    }
                    else
                    {
                        if ($model->estado_nuevo==3)
                        {
                            return 'Pendiente Alta';
                        }
                        else
                        {
                            if ($model->estado_nuevo==4)
                            {
                                return 'Aprobado';
                            }
                            else
                            {
                                return "Estado erroneo - N°: " . $model->estado_nuevo;
                            }
                        }
                    }
                }
            }
            
                       
        },        
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Esta usted seguro?',
                          'data-confirm-message'=>'Esta seguro que desea eliminar este registro?'], 
    ],

];   