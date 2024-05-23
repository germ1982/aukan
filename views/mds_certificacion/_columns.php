<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;

?>

<style>
    td>a>span {
        margin-left: 0.5rem
    }
</style>

<?php

$layoutDate = <<< HTML
    {input1}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
return
    [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'header' => '',
            'headerOptions' => ['style' => 'width:1%'],
            'checkboxOptions' =>
            function ($model) {
                return [
                    'value' => $model->idcertificacion,
                    'class' => 'checkbox-row', 'id' => 'checkbox_' . $model->idcertificacion
                ];
            }
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idcertificacion',
            'label' => '#',
            'width' => '5%',
            'value' => function ($model) {
                return $model['idcertificacion'];
            },
            'filterInputOptions' => ['class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idrisneu',
            'label' => '#RISNeu',
            'width' => '5%',
            'value' => function ($model) {
                $data = "";
                if ($model['idrisneu']) {
                    $data = $model['idrisneu'];
                }
                return $data;
            },
            'filterInputOptions' => ['class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'nro_expediente',
            'label' => 'N° Expediente',
            'width' => '5%',
            'value' => function ($model) {
                $data = "";
                if ($model['nro_expediente']) {
                    $data .= $model['nro_expediente'];
                }
                return $data;
            },
            'filterInputOptions' => ['class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'monto',
            'width' => '6%',
            'value' => function ($model) {
                $data = "$ {$model->monto}";
                return $data;
            },
            'filterInputOptions' => ['placeholder' => 'Monto', 'class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idbeneficiario',
            'label' => 'Beneficiario',
            'width' => '18%',
            'value' => function ($model) {
                $data = "";
                if ($model->beneficiario) {
                    $data = " {$model->beneficiario->apellido} {$model->beneficiario->nombre} ({$model->beneficiario->documento})";
                }
                return strtoupper($data);
            },
            'filterInputOptions' => ['placeholder' => 'Beneficiario', 'class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'responsable',
            'label' => 'Responsable de cobro/Tutor especial',
            'width' => '10%',
            'value' => function ($model) {
                $data = '';
                foreach ($model->responsables as $responsable) {
                    if (!$responsable->deleted_at) {
                        $data = "{$responsable->nombre_apellido} ({$responsable->dni})";
                    }
                }
                return strtoupper($data);
            },
            'filterInputOptions' => ['placeholder' => 'Responsable', 'class' => 'form-control']
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idprograma',
            'width' => '9%',
            'value' => function ($model) {
                return $model->programa->descripcion;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $programasFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true, 'multiple' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw'
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idestado',
            'label' => 'Estado actual',
            'width' => '11%',
            'value' => function ($model) {
                return strtoupper($model->estado->descripcion . "<br />" . $model->getDireccionPrevia() . "<br />" . $model->getUserUpdate());
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $estadosFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true, 'multiple' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw'
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'direccion_actual',
            'label' => 'Ubicación Actual',
            'width' => '12%',
            'value' => function ($model) {
                $direccion = $model->getDireccionActual();
                return $direccion['direccionActual'];
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $direccionesFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true, 'multiple' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Seleccione...'],
            'format' => 'raw'
        ],
        [
            'attribute' => 'periodo_desde',
            'width' => '8%',
            'value' => function ($model) {
                if ($model->periodo_desde) {
                    $date = date_create($model->periodo_desde);
                    $date = date_format($date, 'd-m-Y');
                } else {
                    $date = "";
                }
                return $date;
            },
            'options' => ['readonly' => true],
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'periodo_desde',
                'options' => ['placeholder' => 'Desde'],
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'readonly' => true,
                'layout' => '{input}{remove}',
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'autoclose' => true
                ]
            ])
        ],
        [
            'attribute' => 'periodo_hasta',
            'width' => '8%',
            'value' => function ($model) {
                if ($model->periodo_hasta) {
                    $date = date_create($model->periodo_hasta);
                    $date = date_format($date, 'd-m-Y');
                } else {
                    $date = "";
                }
                return $date;
            },
            'options' => ['readonly' => true],
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'periodo_hasta',
                'options' => ['placeholder' => 'Hasta'],
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'readonly' => true,
                'layout' => '{input}{remove}',
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'autoclose' => true
                ]
            ])
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idusuario_carga',
            'width' => '5%',
            'label' => 'Usuario de Carga',
            'visible' => $hasRolAdminGeneral,
            'value' => function ($model) {
                $username = "";
                if ($model && isset($model->usuarioCarga)) {
                    $username = mb_strtoupper($model->usuarioCarga->apellido) . ", " . mb_strtoupper($model->usuarioCarga->nombre);
                }
                return $username;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => $usuarioCargaFiltro,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Usuario...'],
            'format' => 'raw',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'deleted_at',
            'width' => '4%',
            'visible' => $hasRolAdminGeneral,
            'value' => function ($model) {
                return $model->deleted_at === NULL ? 'Si' : 'No';
            },
            'filter' => ['1' => 'Si', '0' => 'No'],
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'template' => $string,
            'vAlign' => 'middle',
            'hAlign' => 'center',
            'width' => '11%',
            'visibleButtons' => [
                'view' => function () use ($permissionRead) {
                    return ($permissionRead);
                },
                'update' => function ($model) use ($permissionUpdate, $area) {
                    return ($model->permissionUpdate($permissionUpdate, $area));
                },
                'aprobar' => function ($model) use ($permissionAutorizar) {
                    return ($model->permissionAprobar($permissionAutorizar));
                },
                'print' => function () use ($permissionImprimir) {
                    return ($permissionImprimir);
                },
                'print_history' => function () use ($permissionImprimir) {
                    return ($permissionImprimir);
                },
                'imprimirRisneu' => function ($model) use ($permissionRead) {
                    return ($permissionRead && $model->idrisneu);
                },
                'historial_responsables' => function () use ($permissionVerResponsables) {
                    return ($permissionVerResponsables);
                },
                'historial_estados' => function () use ($permissionVerEstados) {
                    return ($permissionVerEstados);
                },
                'historial_montos' => function () use ($permissionVerMontos) {
                    return ($permissionVerMontos);
                },
                'nota' => function ($model) use ($permissionNota) {
                    return ($permissionNota && is_null($model->deleted_at));
                },
                'delete' => function ($model) use ($permissionDelete) {
                    return ($model->permissionDelete($permissionDelete));
                },
                'reactivate' => function ($model) use ($permissionReactivate) {
                    return ($model->permissionReactivate($permissionReactivate));
                },
            ],
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    $url = Url::to(['mds_certificacion/view', 'id' => $model->idcertificacion]);
                    return Html::a('<span style="margin-left:0.5rem" class="fas fa-eye"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Ver',
                        'target' => '_blank',
                    ]);
                },
                'update' => function ($url, $model) {
                    $url = Url::to(['mds_certificacion/update', 'id' => $model->idcertificacion]);
                    return Html::a('<span style="margin-left: 0.5rem" class="glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Actualizar',
                        'id' => 'bnt-update'
                    ]);
                },
                'aprobar' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion/aprobarcolumn', 'idcertificacion' => $model->idcertificacion]);
                    return  Html::a('<span style="margin-left:0.5rem" class= "glyphicon glyphicon-check"></span>', $url, [
                        'role' => 'modal-remote',
                        'title' => ('Aprobar'),
                        'data-confirm' => false,
                        'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-confirm-title' => 'Aprobar certificación #' . $model->idcertificacion,
                        'data-confirm-message' => '¿Está seguro que desea aprobar la certificación #' . $model->idcertificacion . ', de 
                        ' . $model->beneficiario->apellido . ' ' . $model->beneficiario->nombre . '(' . $model->beneficiario->documento . ')'
                    ]);
                },
                'print' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion/certificacion_detalle', 'idcertificacion' => $model->idcertificacion]);
                    return  Html::a('<span class="fas fa-print"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => ('Exportar PDF'),
                        'target' => '_blank',
                    ]);
                },
                'print_history' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion/certificacion_historica', 'id' => $model->beneficiario->idpersona]);
                    return  Html::a('<span class="fas fa-download"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => ('Certificación Histórica'),
                        'target' => '_blank',
                    ]);
                },
                'imprimirRisneu' => function ($url, $model) {
                    $url =  Url::to(['/sds_ris_risneu/imprimir', 'id' => $model->idrisneu]);
                    return Html::a('<span  style="margin-left: 0.5rem;" class= "fas fa-users"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Imprimir RISNeu',
                        'target' => '_blank',
                    ]);
                },
                'historial_responsables' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion/historial_responsables', 'idcertificacion' => $model->idcertificacion]);
                    return  Html::a('<span style="margin-left: 0.5rem" class="fas fa-user-alt"></span>', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Historial responsables',
                        'data-toggle' => 'tooltip'
                    ]);
                },
                'historial_estados' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion/historial_estados', 'idcertificacion' => $model->idcertificacion]);
                    return  Html::a('<span style="margin-left: 0.5rem" class="fas fa-list"></span>', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Historial estados',
                        'data-toggle' => 'tooltip'
                    ]);
                },
                'historial_montos' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion/ver_montos', 'idcertificacion' => $model->idcertificacion, 'calledFrom' => 'index']);
                    return  Html::a('<span style="margin-left: 0.5rem" class="fas fa-usd"></span>', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Historial montos',
                        'data-toggle' => 'tooltip'
                    ]);
                },
                'nota' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion_nota/create', 'idcertificacion' => $model->idcertificacion]);
                    return  Html::a('<span style="margin-left: 0.5rem" class="fas fa-sticky-note"></span>', $url, [
                        'role' => 'modal-remote',
                        'title' => 'Agregar nota',
                        'data-toggle' => 'tooltip'
                    ]);
                },
                'delete' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion/delete', 'id' => $model->idcertificacion]);
                    return Html::a('<span class="fas fa-trash"></span>', $url, [
                        'role' => 'modal-remote',
                        'data-toggle' => 'tooltip',
                        'title' => ('Borrar'),
                        'data' => [
                            'confirm' => '¿Está seguro que desea eliminar este elemento?',
                            'method' => 'post'
                        ],
                    ]);
                },
                'reactivate' => function ($url, $model) {
                    $url =  Url::to(['/mds_certificacion/reactivate', 'id' => $model->idcertificacion]);
                    return  Html::a('<span style="margin-left:0.5rem" class= "fas fa-check"></span>', $url, [
                        'role' => 'modal-remote',
                        'data-toggle' => 'tooltip',
                        'title' => ('Re-activar'),
                        'data' => [
                            'confirm' => '¿Está seguro que desea re-activar este elemento?',
                            'method' => 'post',
                        ],
                    ]);
                },
            ]

        ]
    ];
?>