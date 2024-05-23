  <?php

    use app\models\Mds_org_organismo_externo;
    use app\models\Mds_seg_item;
    use app\models\Mds_seg_permiso;
    use app\models\Mds_seg_usuario;
    use app\models\Sds_ent_solicitud;
    use app\models\Sds_ent_tipo;
    use kartik\grid\GridView;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;

    function getPermisoSolicitudes($idusuario)
    {
        $permisos = Mds_seg_permiso::getPermisosByIdUsuario($idusuario)->all();
        $modulo_solicitudes = false;
        foreach ($permisos as $r) :
            switch ($r->iditem) {
                case Mds_seg_item::MODULO_ENT_SOLICITUD:
                    $modulo_solicitudes = true;
                    break;
            }
        endforeach;

        return $modulo_solicitudes;
    }

    return [
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'dni',
            'width' => '10%'
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'cantidad',
            'width' => '7%'
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'idtipo',
            'value' => function ($model) {
                $tipo = $model->idtipo;
                return Sds_ent_tipo::findOne($tipo)->descripcion;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Sds_ent_tipo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idtipo', 'descripcion'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'format' => 'raw',
            'filterInputOptions' => ['placeholder' => 'Tipo...'],
            'width' => '12%'
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'observaciones',
            'format' => 'html',
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
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Mds_seg_usuario::find()->where("idusuario in (select idusuario from sds_ent_solicitud)")->orderBy(['user' => SORT_ASC])->all(), 'idusuario', 'user'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Usuario...'],
            'format' => 'raw',
            'width' => '14%',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'entidad',
            'value' => function ($model) {
                $entidad = Mds_org_organismo_externo::findOne($model->entidad);
                return $entidad->descripcion;
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(Mds_org_organismo_externo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idorganismoexterno', 'descripcion'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Entidad...'],
            'format' => 'raw',
            'width' => '14%',
        ],
        [
            'class' => '\kartik\grid\DataColumn',
            'attribute' => 'estado',
            'value' => function ($model) {
                switch ($model->estado) {
                    case Sds_ent_solicitud::ESTADO_PENDIENTE:
                        return "Pendiente";
                    case Sds_ent_solicitud::ESTADO_APROBADO:
                        return "Aprobada";
                    case Sds_ent_solicitud::ESTADO_DESAPROBADO:
                        return "Desaprobada";
                    case Sds_ent_solicitud::ESTADO_ENTREGADO:
                        return "Entregada";
                }
                return "";
            },
            'width' => '7%',
            'filter' => ['0' => 'Pendientes', '1' => 'Aprobadas', '2' => 'Desaprobadas', '3' => 'Entregadas']
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => ' {view} {entregar} {update} {aprobar} {rechazar} ',
            'width' => '10%',
            'dropdown' => false,
            'vAlign' => 'middle',
            'hAlign' => 'left',
            'buttons' => [
                'view' => function ($url, $model) {
                    $url =  Url::to(['/sds_ent_solicitud/view', 'id' => $model->idsolicitud]);
                    return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 1,
                        'data-toggle' => 'tooltip',
                    ]);
                },
                'update' => function ($url, $model) {
                    $url =  Url::to(['/sds_ent_solicitud/update', 'id' => $model->idsolicitud]);
                    return $model->estado != Sds_ent_solicitud::ESTADO_PENDIENTE ? '' : Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, [
                        'role' => 'modal-remote', 'data-pjax' => 1,
                        'data-toggle' => 'tooltip',
                    ]);
                },
                'aprobar' => function ($url, $model) {
                    $url =  Url::to(['/sds_ent_solicitud/cambiar_estado', 'id' => $model->idsolicitud, 'estado' => Sds_ent_solicitud::ESTADO_APROBADO]);
                    return  $model->estado != Sds_ent_solicitud::ESTADO_PENDIENTE ? '' : (!getPermisoSolicitudes($model->idusuario) ? "" : Html::a('<span class= "far fa-thumbs-up"></span>', $url, [
                        'role' => 'modal-remote', 'title' => 'Aprobar solicitud',
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-confirm-title' => '',
                        'data-confirm-message' => 'La solicitud seleccionada con <b>DNI N°' . $model->dni . '</b> será <b>APROBADA</b> ¿Desea continuar?'
                    ]));
                },
                'rechazar' => function ($url, $model) {
                    $url =  Url::to(['/sds_ent_solicitud/cambiar_estado', 'id' => $model->idsolicitud, 'estado' => Sds_ent_solicitud::ESTADO_DESAPROBADO]);
                    return  $model->estado != Sds_ent_solicitud::ESTADO_PENDIENTE  ? '' : (!getPermisoSolicitudes($model->idusuario) ? "" : Html::a('<span class= "far fa-thumbs-down"></span>', $url, [
                        'role' => 'modal-remote', 'title' => 'Rechazar solicitud',
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-confirm-title' => '',
                        'data-confirm-message' => 'La solicitud seleccionada con <b>DNI N°' . $model->dni . '</b> será <b>RECHAZADA</b> ¿Desea continuar?'
                    ]));
                },
                'entregar' => function ($url, $model) {
                    $url =  Url::to(['/sds_ent_solicitud/cambiar_estado', 'id' => $model->idsolicitud, 'estado' => Sds_ent_solicitud::ESTADO_ENTREGADO]);
                    return  $model->estado != Sds_ent_solicitud::ESTADO_APROBADO ? '' : Html::a('<span class= "fas fa-people-carry"></span>', $url, [
                        'role' => 'modal-remote', 'title' => 'Entregar',
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-confirm-title' => '',
                        'data-confirm-message' => 'La solicitud seleccionada con <b>DNI N°' . $model->dni . '</b> será <b>ENTREGADA</b> ¿Desea continuar?'
                    ]);
                },
            ]
        ],
    ];
