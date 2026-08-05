<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Articulo;
use app\models\Configuracion;
use app\models\Edificio;
use app\models\EdificioOficina;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\InventarioCpu;
use app\models\InventarioCpuComponente;
use app\models\InventarioDispositivoRed;
use app\models\InformaticaIp;
use app\models\Inventario;

/* @var $this yii\web\View */
/* @var $model app\models\Inventario */

$this->title = 'Detalle de Inventario #' . $model->idinventario;

// Evaluación directa usando los nuevos métodos del modelo
$esCpu = Inventario::esCpu($model->idarticulo);
$tieneRed = Inventario::tieneRed($model->idarticulo);

// 1. Datos de Artículo y Empleado desde sus métodos estáticos
$articuloModel = $model->idarticulo ? Articulo::get_articulo($model->idarticulo) : null;
$empleadoModel = $model->idempleado ? Empleado::get_empleado($model->idempleado) : null;

// 2. Dispositivo / Sector
$dispositivo = $model->iddispositivo ? OrganismoDispositivo::findOne($model->iddispositivo) : null;

// 3. CPU y Componentes
$cpu = InventarioCpu::findOne(['idinventario' => $model->idinventario]);
$componentesCpu = [];
if ($cpu) {
    $componentesCpu = InventarioCpuComponente::find()
        ->where(['idcpu' => $cpu->idcpu])
        ->all();
}

// 4. Red e IP
$red = InventarioDispositivoRed::findOne(['idinventario' => $model->idinventario]);
$ipModel = null;
if ($red) {
    $ipModel = InformaticaIp::findOne(['iddispositivo_red' => $red->iddispositivo_red]);
}
?>
<style>
    <?= include("view.css"); ?>
</style>
<div class="inventario-view-container">

    <div class="inventario-card">
        <div class="inventario-card-header">
            <i class="fas fa-boxes"></i> Datos Generales
        </div>
        <div class="inventario-card-body">
            <div class="row">
                <div class="col-md-8">
                    <?= DetailView::widget([
                        'model' => $model,

                        'attributes' => [
                            [
                                'label' => 'Artículo',

                                'value' => function ($model) {
                                    return Articulo::get_articulo_descripcion($model->idarticulo);
                                }
                            ],
                            'matricula',
                            [
                                'label' => 'Sector',
                                'value' => function ($model) {
                                    return OrganismoDispositivo::get_organismo_dispositivo($model->iddispositivo);
                                }
                            ],

                            [
                                'label' => 'Ubicacion',
                                'value' => function ($model) {
                                    $oficina = EdificioOficina::findOne(['idoficina' => $model->iddispositivo]);
                                    if ($oficina) {
                                        $edificioModel = Edificio::findOne($oficina->idedificio);
                                        $edificio = $edificioModel ? $edificioModel->descripcion_fija : null;
                                        
                                        if ($edificio) {
                                            $direccion = Edificio::get_direccion($oficina->idedificio);
                                            return $direccion . " - $edificio - " . $oficina->descripcion;
                                        }
                                    }
                                    return '(Sin ubicación asignada)';
                                }
                            ],

                            [
                                'label' => 'Empleado',
                                'value' => $empleadoModel ? $empleadoModel->descripcion : '(Sin asignar)',
                            ],

                            [
                                'attribute' => 'idestado',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    // Control null-safe para evitar que explote si no encuentra la configuración
                                    $confEstado = $model->idestado ? Configuracion::findOne($model->idestado) : null;
                                    $estado = $confEstado ? $confEstado->descripcion : '(Sin estado)';

                                    // Punto/LED estilo semáforo
                                    $color = $model->activo ? '#00ff66' : '#ff0055';
                                    $textoEstado = $model->activo ? 'Activo' : 'Inactivo';

                                    $dot = "<span style='
                                    display: inline-block;
                                    width: 9px;
                                    height: 9px;
                                    border-radius: 50%;
                                    background-color: {$color};
                                    box-shadow: 0 0 8px {$color};
                                    margin-right: 6px;
                                    vertical-align: middle;
                                '></span>";

                                    $estadoHtml = "<span style='font-size: 11px; opacity: 0.9;'> //  {$dot}{$textoEstado}</span>";

                                    return $estado . ' ' . $estadoHtml;
                                },
                            ],

                            'observacion:ntext',
                        ],
                    ]) ?>
                </div>
                <div class="col-md-4 text-center d-flex align-items-center justify-content-center">
                    <?php
                    $articulo = $model->idarticulo ? Articulo::findOne($model->idarticulo) : null;
                    if ($articulo && $articulo->imagen):
                    ?>
                        <div class="cyber-img-frame">
                            <span class="cyber-corner top-left"></span>
                            <span class="cyber-corner top-right"></span>
                            <span class="cyber-corner bottom-left"></span>
                            <span class="cyber-corner bottom-right"></span>

                            <?= Html::img('@web/img/articulos/' . $articulo->imagen, [
                                'class' => 'img-fluid cyber-img',
                                'alt' => 'Artículo'
                            ]) ?>
                        </div>
                    <?php else: ?>
                        <div class="cyber-img-frame no-img">
                            <span class="cyber-corner top-left"></span>
                            <span class="cyber-corner bottom-right"></span>
                            <span style="color: rgba(0, 255, 255, 0.4); font-size: 10px; letter-spacing: 1px;">[ NO_IMAGE_DATA ]</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <?php if ($esCpu): ?>
        <div class="inventario-card">
            <div class="inventario-card-header">
                <i class="fas fa-microchip"></i>
                <?php if ($cpu): ?>
                    Especificaciones
                <?php else: ?>
                    Especificaciones:
                    <span style="padding: 20px; color: rgba(0, 255, 255, 0.5);">
                        <em>Sin datos</em>
                    </span>
                <?php endif; ?>
            </div>
            <div class="inventario-card-body">
                <?php if ($cpu): ?>
                    <?= DetailView::widget([
                        'model' => $cpu,
                        'attributes' => [
                            [
                                'label' => 'Capacidad',
                                'value' => function ($model) {
                                    return 'RAM: ' . $model->total_ram_gb . ' GB // Espacio en Disco: ' . $model->total_disco_gb . ' GB';
                                },
                            ],
                            [
                                'label' => 'Sistema Operativo',
                                'value' => function ($cpu) {
                                    if (!$cpu->idso) return '(Sin SO)';
                                    $so = \app\models\Configuracion::findOne($cpu->idso);
                                    return $so ? $so->descripcion : '(Sin SO)';
                                },
                            ],
                            [
                                'label' => 'Componentes',
                                'format' => 'raw',
                                'value' => function ($model) use ($componentesCpu, $cpu) {
                                    if (empty($componentesCpu) && !$cpu->idmother && !$cpu->idmicro && !$cpu->idfuente) {
                                        return '<em>Sin componentes</em>';
                                    }

                                    $items = '';
                                    $items .= $cpu->idmother ? '<li>' . Articulo::get_articulo_descripcion($cpu->idmother) . '</li>' : '';
                                    $items .= $cpu->idmicro ? '<li>' . Articulo::get_articulo_descripcion($cpu->idmicro) . '</li>' : '';


                                    foreach ($componentesCpu as $comp) {
                                        $compArt = \app\models\Articulo::get_articulo($comp->idarticulo);
                                        $nombre = $compArt ? \yii\helpers\Html::encode($compArt->descripcion) : 'Componente #' . $comp->idarticulo;
                                        $items .= '<li>' . $nombre . '</li>';
                                    }

                                    $items .= $cpu->idfuente ? '<li>' . Articulo::get_articulo_descripcion($cpu->idfuente) . '</li>' : '';

                                    return '<ul style="margin: 0; padding-left: 18px;">' . $items . '</ul>';
                                },
                            ],
                        ],
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($tieneRed): ?>
        <div class="inventario-card">
            <div class="inventario-card-header">
                <i class="fas fa-microchip"></i>
                <?php if ($red): ?>
                    Especificaciones De Red
                <?php else: ?>
                    Especificaciones De Red:
                    <span style="padding: 20px; color: rgba(0, 255, 255, 0.5);">
                        <em>Sin datos</em>
                    </span>
                <?php endif; ?>
            </div>
            <div class="inventario-card-body">
                <?php if ($red): ?>
                    <div class="row">
                        <!-- Columna 1: IP y Configuración Básica -->
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $ipModel ?? $red,
                                'attributes' => [
                                    [
                                        'label' => 'IP Asignada',
                                        'value' => $ipModel && $ipModel->ip ? $ipModel->ip : '(Sin IP)',
                                    ],
                                    [
                                        'label' => 'Máscara de Subred',
                                        'value' => $ipModel && $ipModel->mascara ? $ipModel->mascara : '-',
                                    ],
                                    [
                                        'label' => 'Puerta de Enlace',
                                        'value' => $ipModel && $ipModel->puerta_enlace ? $ipModel->puerta_enlace : '-',
                                    ],
                                    [
                                        'label' => 'Tipo de Señal',
                                        'value' => function () use ($red) {
                                            if (!$red || !$red->tipo_senal) return '-';
                                            $conf = Configuracion::findOne($red->tipo_senal);
                                            return $conf ? $conf->descripcion : $red->tipo_senal;
                                        },
                                    ],
                                ],
                            ]) ?>
                        </div>

                        <!-- Columna 2: Físicos, DNS y Tecnología -->
                        <div class="col-md-6">
                            <?= DetailView::widget([
                                'model' => $ipModel ?? $red,
                                'attributes' => [
                                    [
                                        'label' => 'Dirección MAC',
                                        'value' => $ipModel && $ipModel->mac ? $ipModel->mac : '-',
                                    ],
                                    [
                                        'label' => 'Servidor DNS',
                                        'value' => $ipModel && $ipModel->dns ? $ipModel->dns : '-',
                                    ],
                                    [
                                        'label' => 'Tipo de Tecnología',
                                        'value' => function () use ($red) {
                                            if (!$red || !$red->tipo_tecnologia) return '-';
                                            $conf = Configuracion::findOne($red->tipo_tecnologia);
                                            return $conf ? $conf->descripcion : $red->tipo_tecnologia;
                                        },
                                    ],
                                ],
                            ]) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>