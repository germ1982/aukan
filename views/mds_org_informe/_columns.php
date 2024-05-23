<?php

use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\helpers\Url;
use app\models\Mds_org_informe;

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
        'attribute' => 'idinforme',
        'label' => "#",
        'width' => '5%',
    ],
    [
        'attribute' => 'fecha',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha);
            $fc = date_format($fc, 'd/m/Y');
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
        ])
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',
        'value' => function ($model) {
            $data = "";
            if ($model->idusuario && $model->usuario0) {
                $data = strtoupper($model->usuario0->apellido) . ", " . strtoupper($model->usuario0->nombre);
            }
            return $data;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $usuariosFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Usuario...'],
        'format' => 'raw',
        'width' => '15%',
        'visible' => $title === 'Informes Recibidos' ? true : false
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'asunto',
        'width' => '20%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'compartidos',
        'label' => 'Compartido con',
        'filterType' => GridView::FILTER_SELECT2,
        'width' => '15%',
        'filter' => $compartidosFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'html',
        'value' => function ($model, $key, $index, $widget) {
            $compartidosString = '<ul>';
            $compartidos = $model->getCompartidos(null, null);
            if (count($compartidos) > 0) {
                foreach ($compartidos as $compartido) {
                    if ($compartido->idusuario0) {
                        $apellidoMuscula = mb_strtoupper($compartido->idusuario0->apellido);
                        $nombreMayuscula = mb_strtoupper($compartido->idusuario0->nombre);
                        $compartidosString .= "<li>{$apellidoMuscula}, {$nombreMayuscula}</li>";
                    }
                }
                $compartidosString .= '</ul>';
            } else {
                $compartidosString = 'No tiene usuarios';
            }
            return $compartidosString;
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'vistos',
        'label' => 'Visto por',
        'filterType' => GridView::FILTER_SELECT2,
        'width' => '15%',
        'filter' => $vistosFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
        'format' => 'html',
        'value' => function ($model, $key, $index, $widget) {
            $vistosString = '<ul>';
            $vistos = $model->getCompartidos(Mds_org_informe::VISTO_VALUE, 'VISTO_FECHA');
            if (count($vistos) > 0) {
                foreach ($vistos as $visto) {
                    if ($visto->idusuario0) {
                        $apellidoMuscula = mb_strtoupper($visto->idusuario0->apellido);
                        $nombreMayuscula = mb_strtoupper($visto->idusuario0->nombre);
                        $vistoFecha = $visto->visto_fecha ?  "(". date('d/m/Y H:i', strtotime($visto->visto_fecha)) . ")" : null;
                        $vistosString .= "<li>{$apellidoMuscula}, {$nombreMayuscula} {$vistoFecha}</li>";
                    }
                }
                $vistosString .= '</ul>';
            } else {
                $vistosString = 'No tiene vistos';
            }
            return $vistosString;
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo',
        'value' => function ($model) {
            $data = "";
            if ($model->tipo && $model->tipo0) {
                $data = $model->tipo0->descripcion;
            }
            return $data;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $tiposFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Temática...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'value' => function ($model) {
            $iddispositivo = $model->iddispositivo;
            if ($iddispositivo != null) {
                return $model->iddispositivo0->descripcion . " - " . $model->iddispositivo0->organismo->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  $dispositivosFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Dispositivo...'],
        'format' => 'raw',
        'width' => '25%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'visto',
        'label' => 'Visto',
        'value' => function ($model) {
            // Si el informe es "propio", se toma como visto, sino, tengo que validar que está registrado en el campo visto de informe_usuario
            $idusuario = Yii::$app->user->identity->idusuario;
            $model->visto = 2;
            if (!($idusuario == $model->idusuario)) {
                if ($model->informeUsuario) {
                    $model->visto = $model->informeUsuario->visto;
                }
            }
            return $model->visto == 2 ? 'Si' : 'No';
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array(1 => "No", 2 => "Si"),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ''],
        'format' => 'raw',
        'width' => '5%',
        'visible' => $title === 'Informes Recibidos' ? true : false
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} {imprimir}',
        'vAlign' => 'middle',
        'width' => '10%',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'buttons' => [
            'view' => function ($url, $model) {
                $url =  Url::to(['/mds_org_informe/view', 'id' => $model->idinforme]);
                return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, ['data-pjax' => 0, 'role' => 'post', 'title' => 'Ver', 'data-toggle' => 'tooltip']);
            },
            'update' => function ($url, $model) {
                $url =  Url::to(['/mds_org_informe/update', 'id' => $model->idinforme]);
                return  Yii::$app->user->identity->idusuario != $model->idusuario ? '' : Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, ['data-pjax' => 0, 'role' => 'post', 'title' => 'Editar', 'data-toggle' => 'tooltip']);
            },
            'imprimir' => function ($url, $model) {
                $url =  Url::to(['/mds_org_informe/reporte_informe', 'idinforme' => $model->idinforme]);
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip', 'title' => 'Exportar PDF'
                ]);
            },
        ],
    ],

];
