<?php

use kartik\grid\GridView;
use kartik\date\DatePicker;
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
return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcertificacionestado',
        'label' => '#',
        'width' => '5%',
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idbeneficiario',
        'label' => 'Beneficiario',
        'value' => function ($model) {
            $data = "";
            if ($model->certificacion && $model->certificacion->beneficiario) {
                $data = strtoupper("{$model->certificacion->beneficiario->apellido} {$model->certificacion->beneficiario->nombre} ({$model->certificacion->beneficiario->documento})");
            }
            return $data;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $beneficiariosFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Beneficiario...'],
        'format' => 'raw',
        'width' => '20%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcertificacion',
        'label' => 'N° Certificación',
        'value' => function ($model) {
            $data = "";
            if ($model->certificacion && $model->certificacion) {
                $data = $model->certificacion->idcertificacion;
            }
            return $data;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $certificacionesFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Certificación...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idestado',
        'label' => 'Estado',
        'value' => function ($model) {
            $data = "";
            if ($model->estado) {
                $data = $model->estado->descripcion;
            }
            return $data;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $estadosFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idusuario',
        'label' => 'Usuario',
        'value' => function ($model) {
            $data = "";
            if ($model->usuario) {
                $data = strtoupper("{$model->usuario->apellido}, {$model->usuario->nombre}");
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
        'width' => '12%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddireccion',
        'label' => 'Dirección',
        'value' => function ($model) {
            $data = "";
            $data = $model->getDirecciones();
            return $data;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $direccionesFiltro,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Dirección...'],
        'format' => 'raw',
        'width' => '15%',
    ],
    [
        'attribute' => 'fecha_inicio',
        'value' => function ($model) {
            if ($model->fecha_inicio) {
                $date = date_create($model->fecha_inicio);
                $date = date_format($date, 'd-m-Y H:i');
            } else {
                $date = "";
            }
            return $date;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fecha_inicio',
            'options' => ['placeholder' => 'Inicio'],
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'readonly' => true,
            'layout' => '{input}{remove}',
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ]),
        'width' => '14%',
    ],
    [
        'attribute' => 'fecha_fin',
        'value' => function ($model) {
            if ($model->fecha_fin) {
                $date = date_create($model->fecha_fin);
                $date = date_format($date, 'd-m-Y H:i');
            } else {
                $date = "";
            }
            return $date;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fecha_fin',
            'options' => ['placeholder' => 'Fin'],
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'readonly' => true,
            'layout' => '{input}{remove}',
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ]),
        'width' => '14%',
    ],
];
