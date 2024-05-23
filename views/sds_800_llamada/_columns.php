<?php

use app\models\Sds_800_atencion;
use app\models\Sds_800_atencion_interior;
use app\models\Sds_800_atencion_am;
use app\models\Sds_800_atencion_familia;
use app\models\Sds_800_llamada;
use app\models\Sds_vio_intervencion;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\helpers\Html;

$layoutDate = <<< HTML
        
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idllamada',
        'format' => 'raw',
        'width' => '5%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idrisneu',
        'format' => 'raw',
        'value' => function ($model) {
            $value = "";
            if ($model && isset($model->idrisneu)) {
                $value = $model->idrisneu;
            }
            return $value;
        },
        'width' => '5%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',
        'value' => function ($model) {
            $usuario = $model->usuarioAlta;
            $userName = "";
            if ($usuario) {
                $userName = $usuario->user;
            }
            return $userName;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $usuariosFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Usuario...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha_hora',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fdesde',
            'attribute2' => 'fhasta',
            'options' => ['placeholder' => 'Desde'],
            'options2' => ['placeholder' => 'Hasta'],
            'type' => DatePicker::TYPE_RANGE,
            'layout' => $layoutDate,
            'separator' => ' ',
            'readonly' => true,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ]),
        'format' => 'raw',
        'width' => '8%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
        'format' => 'raw',
        'width' => '5%',
    ],
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'telefono',
    ],
     [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nombre_completo',
    ],*/
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo',
        'value' => function ($model) {
            $idconfiguracion = $model->tipo;
            if ($idconfiguracion != null) {
                return $model->tipo0->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $tipoFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'genero',
        'value' => function ($model) {
            $idconfiguracion = $model->genero;
            if ($idconfiguracion != null) {
                return $model->genero0->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $generoFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Genero...'],
        'format' => 'raw',
        'width' => '2%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idderivacion',
        'value' => function ($model) {
            $idderivacion = $model->idderivacion;
            if ($idderivacion != null) {
                return $model->idderivacion0->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $derivacionFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Derivación...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'persona_afectada',
        // 'format' => 'html',
        'format' => 'raw',
        'width' => '13%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'label' => 'Estado',
        'value' => function ($model) {
            $estado = $model->estado0;
            return $estado;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array("0" => "Pendiente de evaluación", "1" => "No Corresponde", "2" => "Derivada", "3" => "Atendida", "4" => "Cerrada", "5" => "Situación Despejada"),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'format' => 'raw',
        'width' => '5%',
    ],
    // [
    //     'class' => '\kartik\grid\DataColumn',
    //     'attribute' => 'afectado_tratamiento',
    //     'label' => 'Tratamiento',
    //     'value' => function ($model) {
    //         $afectado_tratamiento = $model->afectado_tratamiento;
    //         if ($afectado_tratamiento === null) {
    //             $afectado_tratamiento = '';
    //         } else if ($afectado_tratamiento == Sds_800_llamada::PACIENTE_ADICCIONES) {
    //             $afectado_tratamiento = "Paciente Adicciones";
    //         } else {
    //             switch ($afectado_tratamiento) {
    //                 case Sds_800_llamada::PACIENTE_MENTAL:
    //                     $afectado_tratamiento = "Paciente Mental";
    //                     break;
    //                 case Sds_800_llamada::PACIENTE_DUALES:
    //                     $afectado_tratamiento = "Paciente Dual";
    //                     break;
    //             }
    //         }
    //         return $afectado_tratamiento;
    //     },
    //     'filterType' => GridView::FILTER_SELECT2,
    //     'filter' => array("0" => "Paciente Adicciones", "1" => "Paciente Mental", "2" => "Paciente Dual"),
    //     'filterWidgetOptions' => [
    //         'pluginOptions' => ['allowClear' => true],
    //     ],
    //     'filterInputOptions' => ['placeholder' => 'Tratamiento...'],
    //     'format' => 'raw',
    // ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'area_interviniente',
        'label' => 'Área',
        'hidden' => $searchModel->area != Sds_800_llamada::AREA_FAMILIA,
        'value' => function ($model) {
            return $model->area_interviniente0;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => Sds_800_llamada::ARRAY_AREAINTERVINIENTE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Área...'],
        'format' => 'raw',
        'width' => '5%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'profesional_interviniente',
        'hidden' => $searchModel->area != Sds_800_llamada::AREA_FAMILIA,
        'value' => function ($model) {
            $username = "";
            if ($model && isset($model->profesional_interviniente0)) {
                $username = $model->profesional_interviniente0->apellido . ", " . $model->profesional_interviniente0->nombre;
            }
            return $username;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $profesionalFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Profesional...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'deleted_at',
        'label' => 'Activo',
        'visible' => $hasRolAdminGeneral,
        'value' => function ($model) {
            return $model->deleted_at === NULL ? 'Si' : 'No';
        },
        'filter' => ['1' => 'Si', '0' => 'No'],
        'width' => '3%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $stringButtonsIndex,
        'width' => '10%',

        'visibleButtons' => [
            'update' => function ($model) {
                return !($model->estado != Sds_800_llamada::ESTADO_PENDIENTE
                    && $model->estado != Sds_800_llamada::ESTADO_ATENDIDA);
            },
            'delete' => function ($model) use ($hasRolAdminGeneral) {
                return (
                    (($model->idusuario === Yii::$app->user->identity->idusuario)
                        && ($model->estado == Sds_800_llamada::ESTADO_PENDIENTE)
                        && is_null($model->deleted_at))
                    ||
                    ($hasRolAdminGeneral && is_null($model->deleted_at))
                );
            },
            'reactivate' => function ($model) use ($hasRolAdminGeneral) {
                return ($hasRolAdminGeneral && !is_null($model->deleted_at));
            },
            'derivar' => function ($model) {
                return !($model->estado != Sds_800_llamada::ESTADO_PENDIENTE
                    && $model->estado != Sds_800_llamada::ESTADO_ATENDIDA);
            },
            'atender' => function ($model) {
                return ($model->estado == Sds_800_llamada::ESTADO_PENDIENTE
                    || $model->estado == Sds_800_llamada::ESTADO_ATENDIDA);
            },
            'despejar' => function ($model) {
                return ($model->estado == Sds_800_llamada::ESTADO_PENDIENTE);
            },
            'cerrar' => function ($model) {
                return ($model->estado == Sds_800_llamada::ESTADO_ATENDIDA);
            },
            'intervencion' => function ($model) {
                return ($model->estado == Sds_800_llamada::ESTADO_CERRADA);
            },
            'nc' => function ($model) {
                return ($model->estado == Sds_800_llamada::ESTADO_PENDIENTE);
            }
        ],

        'buttons' => [
            'view' => function ($url, $model) {
                $url =  Url::to(['/sds_800_llamada/view', 'id' => $model->idllamada]);
                return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Ver'
                ]);
            },
            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_800_llamada/update', 'id' => $model->idllamada]);
                return Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Editar'
                ]);
            },
            'delete' => function ($url, $model) {
                $url =  Url::to(['/sds_800_llamada/delete', 'id' => $model->idllamada]);
                return Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                    'title' => 'Eliminar',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-confirm-title' => 'Confirmar',
                    'data-confirm-message' => 'La llamada #' . $model->idllamada . ' con <b>DNI N°' . $model->dni . '</b> será <b>ELIMINADA</b><br> ¿Desea continuar?'
                ]);
            },
            'reactivate' => function ($url, $model) {
                $url =  Url::to(['/sds_800_llamada/reactivate', 'id' => $model->idllamada]);
                return  Html::a('<span style="margin-left:0.5rem" class= "fas fa-check"></span>', $url, [
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                    'title' => ('Re-activar'),
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-confirm-title' => 'Confirmar',
                    'data-confirm-message' => 'La llamada #' . $model->idllamada . ' con <b>DNI N°' . $model->dni . '</b> será <b>RE-ACTIVADA</b><br> ¿Desea continuar?'
                ]);
            },
            'derivar' => function ($url, $model) {
                $url =  Url::to(['/sds_800_llamada/derivar', 'id' => $model->idllamada]);
                return Html::a('<span class= "fas fa-arrow-alt-circle-right"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Derivar'
                ]);
            },
            'atender' => function ($url, $model) {
                //depende el area, es la atencion a la que llamo 
                $model_atencion = null;

                if ($model->idllamada != null) {
                    switch ($model->area) {
                        case Sds_800_llamada::AREA_SITUACIONDECALLE:
                            $model_atencion = Sds_800_atencion::findOne($model->idllamada);
                            $url =  Url::to(['sds_800_atencion/' . ($model_atencion == null ? 'create' : 'update'), 'id' => $model->idllamada]);
                            break;
                        case Sds_800_llamada::AREA_FAMILIA:
                            $model_atencion = Sds_800_atencion_familia::findOne($model->idllamada);
                            $url =  Url::to(['sds_800_atencion_familia/' . ($model_atencion == null ? 'create' : 'update'), 'id' => $model->idllamada]);
                            break;
                        case Sds_800_llamada::AREA_ADULTOSMAYORES:
                            $model_atencion = Sds_800_atencion_am::findOne($model->idllamada);
                            $url =  Url::to(['sds_800_atencion_am/' . ($model_atencion == null ? 'create' : 'update'), 'id' => $model->idllamada]);
                            break;
                        case Sds_800_llamada::AREA_INTERIOR:
                            $model_atencion = Sds_800_atencion_interior::findOne($model->idllamada);
                            $url =  Url::to(['sds_800_atencion_interior/' . ($model_atencion == null ? 'create' : 'update'), 'id' => $model->idllamada]);
                            break;
                        case Sds_800_llamada::AREA_VIOLENCIA:
                            $model_atencion = Sds_vio_intervencion::findOne(['idllamada' => $model->idllamada, 'deleted_at' => null]);
                            if ($model_atencion == null) {
                                $url = Url::to(['sds_vio_intervencion/' .  'create', 'idllamada' => $model->idllamada, 'origen' => Sds_800_llamada::AREA_VIOLENCIA]);
                            } else {
                                $url = Url::to(['sds_vio_intervencion/' . 'update',  'id' => $model_atencion->idintervencion, 'idllamada' => $model->idllamada, 'origen' => Sds_800_llamada::AREA_VIOLENCIA]);
                            }
                            break;
                    }
                }
                return  Html::a('<span class= "fas fa-phone"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => ($model_atencion == null ? 'Atender' : 'Editar Atención')
                ]);
            },
            'previa' => function ($url, $model) {
                $url =  Url::to(['/sds_800_llamada/view', 'id' => $model->idorigen]);
                return $model->idorigen == null ? '' : Html::a('<i class="fas fa-file-alt"></i></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Ver Atención Previa'
                ]);
            },
            'nc' => function ($url, $model) {
                $url =  Url::to(['/sds_800_llamada/nc', 'id' => $model->idllamada]);
                return Html::a('<span class= "fas fa-phone-slash"></span>', $url, [
                    'role' => 'modal-remote', 'title' => 'Marcar No Corresponde',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => '',
                    'data-confirm-message' => '<p>La llamada seleccionada con <b>DNI N°' . $model->dni . '</b> será <b>MARCADA COMO "NO CORRESPONDE"</b> ¿Desea continuar?</p>'
                ]);
            },
            'despejar' => function ($url, $model) {
                $url =  Url::to(['/sds_800_llamada/despejar', 'id' => $model->idllamada]);
                return  Html::a('<span class= "far fa-thumbs-up"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Despejar Situación'
                ]);
            },
            'cerrar' => function ($url, $model) {
                $url =  Url::to(['/sds_800_llamada/cerrar', 'id' => $model->idllamada]);
                return  Html::a('<span class= "fas fa-check-circle"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                    'title' => 'Cerrar Llamada'
                ]);
            },
            'pdf' => function ($url, $model) {
                //depende el area, es el PDF que se genera
                $url =  Url::to(['/sds_800_llamada/reporte_llamada', 'idllamada' => $model->idllamada, 'area' => $model->area]);
                return  Html::a('<span class= "fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                    'title' => ('Exportar PDF')
                ]);
            },
            'intervencion' => function ($url, $model) {
                //si la llamada esta cerrada, genero intervencion   //fa-hand-paper
                $url =  Url::to(['/mds_cor_intervencion/create', 'id' => $model->idllamada]);
                return  Html::a('<span class="fas fa-user"></span>', $url, [
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                    'title' => ('Crear Intervención')
                ]);
            },
            'imprimirRisneu' => function ($url, $model) {
                if ($model->area) {
                    if ($model->area == Sds_800_llamada::AREA_FAMILIA) {
                        $atencion =  Sds_800_atencion_familia::find()->where(['idllamada' => $model->idllamada])->one();
                        $documentoPersonaCom = ($atencion != null) ?  $atencion->idpersona0->idpersona0->documento : $model->afectado_dni;
                    }
                    if ($model->area == Sds_800_llamada::AREA_ADULTOSMAYORES) {
                        $atencion = Sds_800_atencion_am::find()->where(['idllamada' => $model->idllamada])->one();
                        $documentoPersonaCom =  ($atencion != null) ? $atencion->idpersona0->idpersona0->documento : $model->afectado_dni;
                    }
                    if ($model->area == Sds_800_llamada::AREA_INTERIOR) {
                        $atencion = Sds_800_atencion_interior::find()->where(['idllamada' => $model->idllamada])->one();
                        $documentoPersonaCom =  ($atencion != null) ?  $atencion->idpersona0->idpersona0->documento : $model->afectado_dni;
                    }
                    if ($model->area == Sds_800_llamada::AREA_VIOLENCIA) {
                        $atencion = Sds_vio_intervencion::find()->where(['idllamada' => $model->idllamada])->one();
                        $documentoPersonaCom = ($atencion != null) ?  $atencion->idpersona0->idpersona0->documento : $model->afectado_dni;
                    }
                    if ($documentoPersonaCom != null) {
                        $idRisneu = \app\models\Sds_ris_risneu::getIdRisneuByPersonaDni($documentoPersonaCom);
                        if ($idRisneu != null) {
                            $url =  Url::to(['/sds_ris_risneu/imprimir', 'id' => $idRisneu]);
                            return Html::a('<span class= "fas fa-users"></span>', $url, [
                                'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                                'data-toggle' => 'tooltip',
                                'title' => 'Imprimir RISNeu'
                            ]);
                        }
                    }
                }
            }
        ],
        'vAlign' => 'middle',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{hijos} {agresores} {movimiento}',
        'hidden' => $searchModel->area != Sds_800_llamada::AREA_VIOLENCIA,
        'width' => '4%',
        'buttons' => [
            'hijos' => function ($url, $model) {
                $model_atencion = Sds_vio_intervencion::findOne(['idllamada' => $model->idllamada]);
                if ($model->estado == Sds_800_llamada::ESTADO_ATENDIDA && !is_null($model_atencion)) {
                    $url =  Url::to(['/sds_com_persona/index_hijos', 'idpadre' => $model_atencion->idpersona, 'idllamada' => $model->idllamada]);
                    return Html::a('<i class="fas fa-child"></i>', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Asignar Hijos',
                        'data-toggle' => 'tooltip',
                    ]);
                }
            },
            'agresores' => function ($url, $model) {
                $model_atencion = Sds_vio_intervencion::findOne(['idllamada' => $model->idllamada]);
                if ($model->estado == Sds_800_llamada::ESTADO_ATENDIDA && !is_null($model_atencion)) {
                    $url =  Url::to(['/sds_vio_agresor/index_interv_agresor', 'idintervencion' => $model_atencion->idintervencion]);
                    return Html::a('<i class="far fa-angry"></i>', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Asignar Agresores',
                        'data-toggle' => 'tooltip',
                    ]);
                }
            },
            'movimiento' => function ($url, $model) {
                $model_atencion = Sds_vio_intervencion::findOne(['idllamada' => $model->idllamada]);
                if ($model->estado == Sds_800_llamada::ESTADO_ATENDIDA && !is_null($model_atencion)) {
                    $url =  Url::to(['/sds_vio_intervencion_movimiento/index', 'idintervencion' => $model_atencion->idintervencion]);
                    return Html::a('<i class="fas fa-exchange-alt"></i>', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Asignar Movimientos',
                        'data-toggle' => 'tooltip',
                    ]);
                }
            },
        ],
        'vAlign' => 'middle',
    ],
];
