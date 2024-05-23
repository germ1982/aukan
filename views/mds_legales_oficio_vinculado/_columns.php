<?php

use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'apellido',
        'value' => function ($model) {
            if ($model->idpersona) {
                $apellido = $model->persona->apellido;
            } else {
                $apellido = $model->apellido;
            }

            if (strlen($apellido) > 100) {
                return substr($apellido, 0, 100) . "...";
            }
            return mb_strtoupper($apellido);
        },
        'filter' => false,
        'enableSorting' => false,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre',
        'value' => function ($model) {
            if ($model->idpersona) {
                $nombre = $model->persona->nombre;
            } else {
                $nombre = $model->nombre;
            }

            if (strlen($nombre) > 100) {
                return substr($nombre, 0, 100) . "...";
            }
            return mb_strtoupper($nombre);
        },
        'filter' => false,
        'enableSorting' => false,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idtipodocumento',
        'value' => function ($model) {
            if ($model->idpersona || $model->tipoDocumento) {
                if ($model->idpersona) {
                    $tipoDocumento = $model->persona->documentoTipo->descripcion;
                } else {
                    $tipoDocumento = $model->tipoDocumento->descripcion;
                }
                $tipoDocumentoPointStart = strpos($tipoDocumento, ".") ? strpos($tipoDocumento, ".") + 1 : 0;
                $tipoDocumento = substr($tipoDocumento, $tipoDocumentoPointStart);
            } else {
                $tipoDocumento = "";
            }
            return $tipoDocumento;
        },
        'filter' => false,
        'enableSorting' => false,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documento',
        'value' => function ($model) {
            if ($model->idpersona) {
                $documento = $model->persona->documento;
            } else {
                $documento = $model->documento;
            }

            if (strlen($documento) > 100) {
                return substr($documento, 0, 100) . "...";
            }
            return $documento;
        },
        'filter' => false,
        'enableSorting' => false,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'parentesco',
        'value' => function ($model) {
            if ($model->parentesco) {
                $parentesco = $model->parentesco->descripcion;
                $parentescoPointStart = strpos($parentesco, ".") ? strpos($parentesco, ".") + 1 : 0;
                $parentesco = substr($parentesco, $parentescoPointStart);
            } else {
                $parentesco = "";
            }
            return $parentesco;
        },
        'filter' => false,
        'enableSorting' => false,
    ],
    // [
    //     'class' => '\kartik\grid\DataColumn',
    //     'attribute' => 'telefono',
    //     'value' => function ($model) {
    //         if ($model->telefono) {
    //             $telefono = $model->telefono;
    //         } else {
    //             $telefono = "";
    //         }
    //         return $telefono;
    //     },
    //     'filter' => false,
    //     'enableSorting' => false,
    // ],
    // [
    //     'class' => '\kartik\grid\DataColumn',
    //     'attribute' => 'domicilio_calle',
    //     'value' => function ($model) {
    //         if ($model->idpersona) {
    //             $domicilioCalle = $model->persona->domicilio_calle ? $model->persona->domicilio_calle : '';
    //         } else {
    //             $domicilioCalle = $model->domicilio_calle ? $model->domicilio_calle : '';
    //         }
    //         return $domicilioCalle;
    //     },
    //     'filter' => false,
    //     'enableSorting' => false,
    // ],
    // [
    //     'class' => '\kartik\grid\DataColumn',
    //     'attribute' => 'domicilio_numero',
    //     'value' => function ($model) {
    //         if ($model->idpersona) {
    //             $domicilioNumero = $model->persona->domicilio_numero ? $model->persona->domicilio_numero : '';
    //         } else {
    //             $domicilioNumero = $model->domicilio_numero ? $model->domicilio_numero : '';
    //         }
    //         return $domicilioNumero;
    //     },
    //     'filter' => false,
    //     'enableSorting' => false,
    // ],
    // [
    //     'class' => '\kartik\grid\DataColumn',
    //     'attribute' => 'mail',
    //     'value' => function ($model) {
    //         if ($model->mail) {
    //             $mail = $model->mail;
    //         } else {
    //             $mail = "";
    //         }
    //         return $mail;
    //     },
    //     'filter' => false,
    //     'enableSorting' => false,
    // ],
    // [
    //     'class' => '\kartik\grid\DataColumn',
    //     'attribute' => 'observaciones',
    //     'value' => function ($model) {
    //         if ($model->observaciones) {
    //             $observaciones = $model->observaciones;
    //         } else {
    //             $observaciones = "";
    //         }
    //         return $observaciones;
    //     },
    //     'filter' => false,
    //     'enableSorting' => false,
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'visible' => $permissions['hasRolAdminGeneral'],
        'value' => function ($model) {
            if ($model->deleted_at)
                return "No";
            else
                return "Si";
        },
        'filter' => false,
        'enableSorting' => false,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => $stringButtonsIndex,
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'buttons' => [
            'view' => function ($url, $model) {
                $url =  Url::to(['/mds_legales_oficio_vinculado/view', 'id' => $model->idlegalesoficiovinculado]);
                return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 1,
                    'title' => 'Consultar Datos',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'delete' => function ($url, $model) {
                if (!$model->deleted_at) {
                    $url =  Url::to(['/mds_legales_oficio_vinculado/delete', 'id' => $model->idlegalesoficiovinculado]);
                    return  Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                        'role' => 'modal-remote',
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-pjax' => 1,
                        'title' => ('Borrar'),
                        'data' => [
                            'confirm' => '¿Está seguro que desea eliminar este elemento?',
                            'method' => 'post',
                        ],
                    ]);
                }
            },
            'reactivate' => function ($url, $model, $key) {
                if ($model->deleted_at) {
                    $url =  Url::to(['/mds_legales_oficio_vinculado/reactivate', 'id' => $model->idlegalesoficiovinculado]);
                    return  Html::a(
                        '<span class= "fas fa-check"></span>',
                        $url,
                        [
                            'role' => 'modal-remote',
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-pjax' => 1,
                            'title' => ('Re-activar'),
                            'data' => [
                                'confirm' => '¿Está seguro que desea re-activar este elemento?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },
        ]
    ],

];
