<?php

use app\models\Mds_legales_derivacion;
use app\models\Mds_legales_derivacion_area;
use app\models\Mds_legales_oficio;
use app\models\Mds_legales_respuesta_estado;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idlegalesoficio',
        'value' => function ($model) {
            return $model['idlegalesoficio'];
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idarea',
        'label' => 'Área',
        'value' => function ($model) {
            return ($model->areaOficio) ? $model->areaOficio->descripcion : '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $areaOficioFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'caso',
        'value' => function ($model) {
            $caso = $model->caso;
            if ($model->idlegalescaratula) {
                $caso = $model->caratulaModel->caso;
            }
            return $caso;
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'lugar_libramiento',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'caratula',
        'label' => 'Carátula',
        'width' => '250px',
        'value' => function ($model) {
            return ($model->caratulaModel) ? $model->caratulaModel->caratula : '';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $caratulasFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'supervisores',
        'label' => 'Supervisores/as',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $supervisoresFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'html',
        'value' => function ($model, $key, $index, $widget) {
            $supervisoresString = '<ul>';
            $supervisores = $model->getSupervisores();
            if (count($supervisores) > 0) {
                foreach ($supervisores as $index => $supervisor) {
                    $apellidoMuscula = mb_strtoupper($supervisor->usuario->apellido);
                    $nombreMayuscula = mb_strtoupper($supervisor->usuario->nombre);
                    $supervisoresString .= "<li>{$apellidoMuscula}, {$nombreMayuscula}</li>";
                }
                $supervisoresString .= '</ul>';
            } else {
                $supervisoresString = 'No tiene supervisores/as';
            }
            return $supervisoresString;
        },

    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'generadoresRespuesta',
        'label' => 'Generadores/as de respuesta',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $generadoresRespuestaFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'html',
        'value' => function ($model, $key, $index, $widget) {
            $generadoresRespuestaString = '<ul>';
            $generadoresRespuesta = $model->getReceptores();
            if (count($generadoresRespuesta) > 0) {
                foreach ($generadoresRespuesta as $index => $generadorRespuesta) {
                    $apellidoMuscula = mb_strtoupper($generadorRespuesta->usuario->apellido);
                    $nombreMayuscula = mb_strtoupper($generadorRespuesta->usuario->nombre);
                    $generadoresRespuestaString .= "<li>{$apellidoMuscula}, {$nombreMayuscula}</li>";
                }
                $generadoresRespuestaString .= '</ul>';
            } else {
                $generadoresRespuestaString = 'No tiene generadores/as de respuesta';
            }
            return $generadoresRespuestaString;
        },

    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo_oficio',
        'label' => 'Tipo requerimiento',
        'value' => function ($model) {
            return $model->tipoOficio->descripcion;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $tipoOficioFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'raw',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'numero_expediente',
        'value' => function ($model) {
            $numeroExpediente = $model->numero_expediente;
            if ($model->idlegalescaratula) {
                $numeroExpediente = $model->caratulaModel->numero_expediente;
            }
            return $numeroExpediente;
        }
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'anio_expediente',
        'value' => function ($model) {
            $anioExpediente = $model->anio_expediente;
            if ($model->idlegalescaratula) {
                $anioExpediente = $model->caratulaModel->anio_expediente;
            }
            return $anioExpediente;
        }
    ],
    [
        'attribute' => 'fecha_plazo',
        'value' => function ($model) {
            if ($model->fecha_plazo) {
                $fr = date_create($model->fecha_plazo);
                $fr = date_format($fr, 'd/m/Y');
            } else {
                $fr = '';
            }
            return $fr;
        },
        // 'format' => ['date', 'php:d-m-Y H:i:s'],
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fecha_plazo',
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'readonly' => true,
            'layout' => '{input} {remove}',
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true,
                'todayHighlight' => true,
            ]
        ])
    ],
    [
        'attribute' => 'fecha_recepcion',
        'value' => function ($model) {
            if ($model->fecha_recepcion) {
                $fr = date_create($model->fecha_recepcion);
                $fr = date_format($fr, 'd/m/Y');
            } else {
                $fr = '';
            }
            return $fr;
        },
        // 'format' => ['date', 'php:d-m-Y H:i:s'],
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fecha_recepcion',
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'readonly' => true,
            'layout' => '{input} {remove}',
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true,
                'todayHighlight' => true,
            ]
        ])
    ],
    [
        'attribute' => 'respuestasGeneradas',
        'label' => 'Rtas. Generadas',
        'filter' => true,
        'format' => 'html',
        'value' => function ($model, $key, $index, $widget) {
            $totalRespuestas = $model->getTotalRespuestasGeneradas();
            $color = ($totalRespuestas == 0) ? 'red' : 'green';

            return "<strong style='color: {$color}'>{$totalRespuestas}</strong>";
        },
    ],
    [
        'attribute' => 'respuestasEnviadas',
        'label' => 'Rtas. Enviadas',
        'filter' => true,
        'format' => 'html',
        'value' => function ($model, $key, $index, $widget) {
            $totalRespuestasEnviadas = count($model->getRespuestasAprobadas());
            $color = ($totalRespuestasEnviadas == 0) ? 'red' : 'green';

            return "<strong style='color: {$color}'>{$totalRespuestasEnviadas}</strong>";
        },
    ],
    [
        'attribute' => 'respuestaPendienteVistos',
        'label' => 'Vistos',
        'filter' => true,
        'format' => 'html',
        'value' => function ($model, $key, $index, $widget) {
            $totalRespuestaPendienteVistos = "-";
            $countTotalRespuestaPendienteVistos = count($model->getRespuestaPendienteVistos());
            if ($countTotalRespuestaPendienteVistos > 0){
                $totalRespuestaPendienteVistos = $countTotalRespuestaPendienteVistos;
            }
            return "<strong style='color: green'>{$totalRespuestaPendienteVistos}</strong>";
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'activo',
        'label' => 'Activo',
        'width' => '8%',
        'visible' => $hasRolAdminGeneral,
        'value' => function ($model) {
            if ($model->activo === 1)
                return "Si";
            else
                return "No";
        },
        'filter' => ['1' => 'Si', '0' => 'No'],
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => $string,
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'buttons' =>
        [
            'vincular' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_legales_oficio_vinculado/index', 'idlegalesoficio' => $model['idlegalesoficio'],
                ]);
                return Html::a('<i class="fas fa-child"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Vincular personas',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'rechazaroficio' => function ($url, $model, $key) use ($estadoAprobada, $estadoRechazada) {
                $usuarioAuth = Yii::$app->user->identity;
                $idUsuario = $usuarioAuth->idusuario;
                $idOficio = $model['idlegalesoficio'];
                // Busco a ver si tiene derivaciones activas. Ordeno por receptores primero ya que es la ultima derivación
                $derivacion = \app\models\Mds_legales_derivacion::find()->where(['idlegalesoficio' => $idOficio, 'idusuario' => $idUsuario, 'activo' => 1, 'fecha_usu_no_corresponde' => null])->orderBy(['supervisor' => SORT_ASC])->one();
                if ($derivacion && count($model->getLastRespuestasEstadoByEstado($estadoAprobada)) === 0 && count($model->getRespuestasPendientesMiSupervision()) === 0 && count($model->getLastRespuestasEstadoByEstado($estadoRechazada)) === 0) { // Existe una derivacion actual y no tiene ninguna respuesta aprobada por supervision
                    $url = Url::to(['mds_legales_oficio/rechazaroficio', 'idDerivacion' => $derivacion->idlegalesderivacion]);
                    $title = 'Devolver a Supervisión';
                    if ($derivacion->supervisor && ($derivacion->oficio->tipo_oficio == Mds_legales_oficio::ID_CONFIGURACION_NOTIFICACION_ELECTRONICA || $derivacion->oficio->tipo_oficio == Mds_legales_oficio::ID_CONFIGURACION_NOTIFICACION_ELECTRONICA_URGENTE)) {
                        $title = 'Devolver a Dir. Gral. de Asesoría Legal y Técnica';
                    } else if ($derivacion->supervisor) {
                        $title = 'Devolver a Dir. Gral. de Registro y Vinculación';
                    }
                    return Html::a('<span style="margin-left: 0.5rem;" class= "fas fa-ban "></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => $title
                    ]);
                }
            },
            'responder' => function ($url, $model, $key) use ($estadoObservada) {
                $usuarioAuth = Yii::$app->user->identity;
                $derivacion = Mds_legales_derivacion::find()
                    ->select('derivacion.idlegalesderivacion, respuesta.idlegalesrespuesta')
                    ->from('mds_legales_derivacion derivacion')
                    ->leftJoin('mds_legales_respuesta respuesta', 'respuesta.idlegalesoficio=derivacion.idlegalesoficio')
                    ->where("derivacion.idlegalesoficio = {$model['idlegalesoficio']} 
                        AND derivacion.idusuario = $usuarioAuth->idusuario 
                        AND derivacion.activo = 1 
                        AND derivacion.supervisor = 0 
                        AND derivacion.fecha_usu_no_corresponde IS NULL
                        ")
                    ->asArray()->one();
                if ($derivacion && (!$derivacion['idlegalesrespuesta'] || count($model->getLastRespuestasEstadoByEstado($estadoObservada)) == $model->getTotalRespuestasGeneradas())) {
                    $url = Url::to(['mds_legales_respuesta/create', 'idDerivacion' => $derivacion['idlegalesderivacion']]);
                    return Html::a('<span style="margin-left: 0.5rem;" class= "fas fa-reply"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Responder requerimiento'
                    ]);
                } else {
                    $respuestaObservada = Mds_legales_respuesta_estado::find()
                        ->select('derivacion.idlegalesderivacion, respuesta.idlegalesrespuesta')
                        ->from('mds_legales_respuesta_estado respuesta_estado')
                        ->innerJoin('mds_legales_respuesta respuesta', 'respuesta.idlegalesrespuesta=respuesta_estado.idlegalesrespuesta')
                        ->innerJoin('mds_legales_derivacion derivacion', 'derivacion.idlegalesoficio=respuesta.idlegalesoficio')
                        ->innerJoin('mds_legales_oficio oficio', 'oficio.idlegalesoficio=derivacion.idlegalesoficio')
                        ->where("derivacion.idlegalesoficio = {$model['idlegalesoficio']} 
                            AND derivacion.supervisor = 0 
                            AND derivacion.fecha_usu_no_corresponde IS NULL 
                            AND derivacion.idusuario = {$usuarioAuth->idusuario} 
                            AND derivacion.activo = 1 
                            AND respuesta_estado.estado = {$estadoObservada} 
                            AND respuesta.idusuario = {$usuarioAuth->idusuario} 
                            AND respuesta.idrespuestacorreccion IS NULL
                            AND oficio.activo = 1
                            ")
                        ->orderBy(['respuesta.idlegalesrespuesta' => SORT_DESC])
                        ->asArray()->one();

                    if ($respuestaObservada) {
                        $url = Url::to(['mds_legales_respuesta/create', 'idDerivacion' => $respuestaObservada['idlegalesderivacion'], 'idrespuesta' => $respuestaObservada['idlegalesrespuesta']]);
                        return Html::a('<span style="margin-left: 0.5rem;" class= "fas fa-reply"></span>', $url, [
                            'role' => 'post', 'data-pjax' => 0,
                            'data-toggle' => 'tooltip',
                            'title' => 'Modificar respuesta'
                        ]);
                    }
                }
            },
            'respuestas' => function ($url, $model, $key) use ($idDispositivo, $hasRolSupervisorGeneral, $hasRolSupervisorArea, $hasRolAdminGeneral, $hasRolReceptor) {
                $usuarioAuth = Yii::$app->user->identity;
                $derivacion = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $model['idlegalesoficio'], 'idusuario' => $usuarioAuth->idusuario, 'activo' => 1, 'supervisor' => 1, 'fecha_usu_no_corresponde' => null])->one();
                $consultaIdDispostivo = Mds_legales_derivacion_area::find()->where(['idoficio' => $model['idlegalesoficio'], 'iddispositivo' => $idDispositivo])->one();

                if ($hasRolAdminGeneral || $derivacion || $hasRolSupervisorGeneral || (!empty($consultaIdDispostivo) && $hasRolSupervisorArea) || $hasRolReceptor) {
                    $url = Url::to(['mds_legales_oficio/respuestas', 'idOficio' => $model['idlegalesoficio']]);
                    return Html::a('<span style="margin-left: 0.5rem;" class= "fas fa-comments"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Respuestas',
                        'target' => '_blank',
                    ]);
                }
            },
            // Derivar que utiliza el rol de supervisor
            'derivar' => function ($url, $model, $key) use ($hasRolSupervisor) {
                if ($hasRolSupervisor) {
                    $usuarioAuth = Yii::$app->user->identity;
                    $connection = Yii::$app->getDb();
                    //Buscamos las derivaciones que hayan sido para el usuario logueado, que sea un supervisor y que no tenga receptores
                    $derivaciones = $connection->createCommand(
                        "SELECT * 
                        FROM mds_legales_derivacion AS derivacion 
                        INNER JOIN mds_legales_oficio o ON derivacion.idlegalesoficio = o.idlegalesoficio  AND o.activo = 1 
                        WHERE derivacion.idusuario = {$usuarioAuth->idusuario} 
                        AND derivacion.supervisor = 1 
                        AND derivacion.activo = 1 
                        AND derivacion.fecha_usu_no_corresponde IS NULL 
                        AND derivacion.idlegalesoficio NOT IN (SELECT deri.idlegalesoficio 
                                                                FROM mds_legales_derivacion AS deri 
                                                                WHERE deri.supervisor = 0                                                          
                                                                ) 
                        ORDER BY derivacion.idlegalesderivacion DESC
                        "
                    )->queryAll();
                    foreach ($derivaciones as $derivacion) {
                        $url = Url::to(['mds_legales_oficio/rederivar', 'idDerivacion' => $derivacion['idlegalesderivacion']]);
                        if ($model['idlegalesoficio'] == $derivacion['idlegalesoficio']) {
                            return Html::a('<span style="margin-left:0.5rem" class="fas fa-arrow-right"></span>', $url, [
                                'role' => 'post', 'data-pjax' => 0,
                                'data-toggle' => 'tooltip',
                                'title' => 'Derivar'
                            ]);
                        }
                    }
                }
            },
            // Re-derivar que utiliza el rol de supervisor
            'reDerivar' => function ($url, $model, $key) use ($estadoAprobada, $hasRolSupervisor) {
                if ($hasRolSupervisor && count($model->getLastRespuestasEstadoByEstado($estadoAprobada)) === 0) {
                    $usuarioAuth = Yii::$app->user->identity;
                    $connection = Yii::$app->getDb();
                    //Buscamos las derivaciones que hayan sido rechazadas por un generador de respuestas y que ademas el supervisor no haya rechazado
                    $derivaciones = $connection->createCommand(
                        "SELECT * 
                    FROM mds_legales_derivacion AS derivacion
                    INNER JOIN mds_legales_oficio ON mds_legales_oficio.idlegalesoficio = derivacion.idlegalesoficio
                    WHERE mds_legales_oficio.activo = 1
                    AND derivacion.supervisor = 0 
                    AND derivacion.re_derivado = 0 
                    AND derivacion.activo = 0
                    AND derivacion.idlegalesoficio IN 
                    (
                        SELECT deri.idlegalesoficio 
                        FROM mds_legales_derivacion AS deri 
                        WHERE deri.idusuario = {$usuarioAuth->idusuario} 
                        AND deri.fecha_usu_no_corresponde IS NULL 
                        AND deri.supervisor = 1
                        AND deri.activo = 1
                    ) GROUP BY derivacion.idlegalesoficio"
                    )->queryAll();
                    foreach ($derivaciones as $derivacion) {
                        // Obtenemos la derivacion original 
                        $derivacionOriginal = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $derivacion['idlegalesoficio'], 'idusuario' => $usuarioAuth->idusuario, 'activo' => 1, 'supervisor' => 1, 'fecha_usu_no_corresponde' => null])->one();
                        if ($derivacionOriginal) {
                            $url = Url::to(['mds_legales_oficio/rederivar', 'idDerivacion' => $derivacionOriginal['idlegalesderivacion']]);
                            if ($model['idlegalesoficio'] == $derivacionOriginal['idlegalesoficio']) {
                                return Html::a('<span style="margin-left:0.5rem" class="fas fa-arrow-right"></span>', $url, [
                                    'role' => 'post', 'data-pjax' => 0,
                                    'data-toggle' => 'tooltip',
                                    'title' => 'Re-derivar'
                                ]);
                            }
                        }
                    }
                };
            },
            // Re-derivar que utiliza el rol de registro
            'derivarRegistro' => function ($url, $model, $key) use ($estadoAprobada, $hasRolRegistro) {
                if ($hasRolRegistro && count($model->getLastRespuestasEstadoByEstado($estadoAprobada)) === 0) {
                    $connection = Yii::$app->getDb();
                    $derivaciones = $connection->createCommand(
                        "SELECT * 
                    FROM mds_legales_derivacion AS derivacion 
                    INNER JOIN mds_legales_oficio AS oficio ON derivacion.idlegalesoficio = oficio.idlegalesoficio
                    WHERE derivacion.fecha_usu_no_corresponde IS NOT NULL 
                    AND derivacion.supervisor = 1 
                    AND derivacion.activo = 0 
                    AND derivacion.re_derivado = 0 
                    AND oficio.activo = 1
                    AND derivacion.observaciones IS NOT NULL
                    GROUP BY derivacion.idlegalesoficio
                    "
                    )->queryAll();
                    foreach ($derivaciones as $derivacion) {
                        $url = Url::to(['mds_legales_oficio/rederivar', 'idDerivacion' => $derivacion['idlegalesderivacion']]);

                        if ($model['idlegalesoficio'] == $derivacion['idlegalesoficio']) {
                            return Html::a('<span style="margin-left:0.5rem" class="fas fa-arrow-right"></span>', $url, [
                                'role' => 'post', 'data-pjax' => 0,
                                'data-toggle' => 'tooltip',
                                'title' => 'Re-derivar'
                            ]);
                        }
                    }
                }
            },
            'misrespuestas' => function ($url, $model, $key) use ($hasRolAdminGeneral) {
                $usuarioAuth = Yii::$app->user->identity;
                $derivacion = \app\models\Mds_legales_derivacion::find()->where(['idlegalesoficio' => $model['idlegalesoficio'], 'supervisor' => 0, 'idusuario' => $usuarioAuth->idusuario, 'activo' => 1])->one();
                if ($hasRolAdminGeneral || ($derivacion && $derivacion->fecha_usu_no_corresponde == null)) {
                    $url = Url::to(['/mds_legales_respuesta/mis-respuestas', 'idOficio' => $model['idlegalesoficio']]);
                    return Html::a('<span style="margin-left: 0.5rem;" class= "fas fa-book"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Mis Respuestas'
                    ]);
                }
            },
            'view' => function ($url, $model, $key) use ($permiso_ver_oficio_vinculacion, $permiso_ver_oficio_registro, $hasRolAdminGeneral) {
                $idlegalesoficio = $model['idlegalesoficio'];
                $url = Url::to(['mds_legales_oficio/view', 'idlegalesoficio' => $idlegalesoficio]);
                if ($permiso_ver_oficio_registro || $permiso_ver_oficio_vinculacion || $hasRolAdminGeneral) {
                    return Html::a('<span style="margin-left:0.5rem" class="fas fa-eye"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,
                        'data-toggle' => 'tooltip',
                        'title' => 'Ver',
                        'target' => '_blank',
                    ]);
                }
            },
            'delete' => function ($url, $model, $key) use ($hasRolAdminGeneral) {
                $dateNow = new DateTime('now');
                $fechaCarga = new DateTime($model->fecha_carga);
                $diffDate = $fechaCarga->diff($dateNow); //Aplicamos la diferencia entre fechas
                $usuarioAuth = Yii::$app->user->identity;
                if ($model['activo'] == 1 && (($diffDate->d < 1 && (count($model->receptores) === 0) && $model->getTotalRespuestasGeneradas() == 0 && ($model['idusuario'] === $usuarioAuth->idusuario)) || $hasRolAdminGeneral)) {
                    $url =  Url::to(['/mds_legales_oficio/delete', 'id' => $model->idlegalesoficio]);
                    return  Html::a(
                        '<span style="margin-left:0.5rem" class= "fas fa-trash"></span>',
                        $url,
                        [
                            'role' => 'modal-remote',
                            'data-toggle' => 'tooltip',
                            'title' => ('Borrar'),
                            'data' => [
                                'confirm' => '¿Está seguro que desea eliminar este elemento?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },
            'reactivate' => function ($url, $model, $key) use ($hasRolAdminGeneral) {
                if ($model['activo'] == 0 && $hasRolAdminGeneral) {
                    $url =  Url::to(['/mds_legales_oficio/reactivate', 'id' => $model->idlegalesoficio]);
                    return  Html::a(
                        '<span style="margin-left:0.5rem" class= "fas fa-check"></span>',
                        $url,
                        [
                            'role' => 'modal-remote',
                            'data-toggle' => 'tooltip',
                            'title' => ('Re-activar'),
                            'data' => [
                                'confirm' => '¿Está seguro que desea re-activar este elemento?',
                                'method' => 'post',
                            ],
                        ]
                    );
                }
            },
            'update' => function ($url, $model, $key) use ($permiso_editar_oficio, $hasRolRegistro, $hasRolAdminGeneral) {
                $usuarioAuth = Yii::$app->user->identity;
                $idlegalesoficio = $model['idlegalesoficio'];
                $dateNow = new DateTime('now');
                $fechaCarga = new DateTime($model->fecha_carga);
                $diffDate = $fechaCarga->diff($dateNow); //Aplicamos la diferencia entre fechas
                $url = Url::to(['mds_legales_oficio/update', 'id' => $idlegalesoficio]);
                if (($permiso_editar_oficio && $diffDate->d < 1 && (count($model->receptores) === 0) && $model->getTotalRespuestasGeneradas() == 0 && ($model['idusuario'] === $usuarioAuth->idusuario || $hasRolRegistro)) || $hasRolAdminGeneral) {
                    return Html::a('<span style="margin-left: 0.5rem" class="glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'post', 'data-pjax' => 0,  'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Actualizar'
                    ]);
                }
            },
            'agregarDerivaciones' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_legales_oficio/agregar_derivaciones', 'idlegalesoficio' => $model->idlegalesoficio
                ]);
                return Html::a('<i style="margin-left: 0.5rem" class="far fa-user"></i>', $url, [
                    'role' => 'modal-remote',
                    'title' => 'Agregar derivaciones',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'agregarArchivos' => function ($url, $model) {
                    return Html::button('<i style="margin-left: 0.5rem" class="far fa-folder"></i>', [
                        'type' => "button", 
                        'class' => 'button-like-link', 
                        'data-toggle' => "modal", 
                        'data-target' => "#modalAgregarArchivos",
                        'title' => 'Agregar archivos',
                        'data-idlegalesoficio' => $model->idlegalesoficio
                ]);
            },
        ],
    ]
];
