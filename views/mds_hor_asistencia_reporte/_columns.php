<?php

use app\models\Mds_hor_registro;
use yii\helpers\Html;
use yii\helpers\Url;

return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'fecha',
        'filter' => false,
        'value' => function($model){
            return date('d/m/Y', strtotime(str_replace('-','/',$model->fecha)));
        },
        'width' => '6%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dia',
        'value' => function($model){
            $dia='';
            switch (date('N', strtotime($model->fecha))){
                case 1: 
                    $dia = 'Lunes';
                    break;
                case 2: 
                    $dia = 'Martes';
                    break;
                case 3: 
                    $dia = 'Miércoles';
                    break;
                case 4: 
                    $dia = 'Jueves';
                    break;
                case 5: 
                    $dia = 'Viernes';
                    break;
                case 6: 
                    $dia = 'Sábado';
                    break;
                case 7: 
                    $dia = 'Domingo';
                    break;
            }
            return $dia;
        },
        'filter' => false,
        'width' => '6%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'width' => '8%',
        'format' => 'html',
        'value' => function ($model) {
            $color = 'default';

            if($model->estado == 'Inasistencia'){
                $color = 'danger';
            }
            if($model->estado == 'Asistencia'){
                $color = 'success';
            }

            return  "<div class='text-$color text-center'>" . $model->estado . "</div>";
        },
        'filter' => false,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'width' => '55%',
        'template' => '{detalle}',
        'vAlign' => 'middle',
        'header' => 'Detalle',
        'headerOptions' => [
            'style' => 'color:#0088cc',
        ],
        'buttons' => [
            'detalle' => function ($url, $model) {
                $resultado = "";
                if (!empty($model->detalle) || !empty($model->detalle_fichada)) {
                    $fichadas = explode(',', $model->detalle_fichada);
                    $carga_nocturna = false;
                    $nocturno_detalle = "";
                    foreach ($fichadas as $idregistro) {
                        $registro = Mds_hor_registro::findOne($idregistro);
                        if ($registro != null) {
                            $origen = "";
                            switch ($registro->origen) {
                                case (Mds_hor_registro::ORIGEN_CICLO):
                                    $origen = "Ciclo";
                                    break;
                                case (Mds_hor_registro::ORIGEN_GUARDIA):
                                    $origen = "Guardia";
                                    break;
                                case (Mds_hor_registro::ORIGEN_IMPORTACION):
                                    $origen = "Importación";
                                    break;
                                case (Mds_hor_registro::ORIGEN_MANUAL):
                                    $origen = "Manual";
                                    break;
                            }
                            if ($registro->origen == 2) {
                                $url = Url::to(['/mds_hor_asistencia_reporte/view', 'idregistro' => $registro->idregistrohorario]);
                                $resultado = $resultado . ($resultado != "" ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : "") . Html::a(date_format(date_create($registro->fecha), "H:i"). " ($origen)", $url, [
                                    'role' => 'modal-remote', 'data-pjax' => 1,
                                    'title' => 'Ver Foto y Ubicación de Fichada',
                                    'data-toggle' => 'tooltip',
                                ]);
                            } else {
                                $resultado = $resultado . ($resultado != "" ? "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : "") . date_format(date_create($registro->fecha), "H:i") . " ($origen)";
                            }                            
                            if($registro->horario_nocturno && !$carga_nocturna){
                                $carga_nocturna = true;
                                $nocturno_detalle=date_format(date_create($registro->fecha), "H:i");
                            }elseif($registro->horario_nocturno && $carga_nocturna){
                                $resultado = date_format(date_create($registro->fecha), "H:i")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $nocturno_detalle &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ($origen)";
                                $carga_nocturna = false;
                            }                            
                        }
                    }
                    if ($model->detalle != "") {
                        $resultado = ($resultado != "" ? $resultado . " | " : "") . $model->detalle;
                    }
                    $resultado = ($model->turno_rotativo == 1 ? "TURNO ROTATIVO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" : "") . $resultado;
                }
                return $resultado;
            },
        ],
    ],
];
