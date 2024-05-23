<?php

use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

$archivos = new ArrayDataProvider([
    'allModels' => [
        ['nombre' => '¿Cómo pedir una carpeta compartida?', 'url' => '/instructivos/pedir_carpeta_compartida.pdf'],        
        ['nombre' => '¿Cómo pedir cambios de guarismo de gestdocu?', 'url' => '/instructivos/pedir_cambios_guarismo.pdf'],
        ['nombre' => '¿Cómo consultar guarismo de gestdocu?', 'url' => '/instructivos/consultar_guarismo_gestdocu.pdf'],
        //['nombre' => '¿Cómo consultar un código de Sa.Fi.Pro. de insumos informáticos?', 'url' => '/instructivos/consulta_codigo_safipro.pdf'],
        ['nombre' => '¿Cómo solicitar asistencia de sistemas?', 'url' => '/instructivos/solicitar_asistencia.pdf'],
        ['nombre' => '¿Cómo solicitar un celular corporativo?', 'url' => '/instructivos/solicitar_celular.pdf'],
        ['nombre' => '¿Cómo solicitar un celular corporativo - Subsecretaría de Familia?', 'url' => '/instructivos/solicitar_celular_familia.pdf'],
        ['nombre' => '¿Cómo solicitar cambios de sector de GDE?', 'url' => '/instructivos/cambio_sector_gde.pdf'],
        ['nombre' => '¿Cómo iniciar un expedientre por GDE?', 'url' => '/instructivos/images/inicio_expediente_gde.jpeg'],
        ['nombre' => 'Manual de Expedientes de GDE', 'url' => '/instructivos/manual_gde_2022.pdf'],
        ['nombre' => 'Manual de uso - Módulo RISNeu', 'url' => '/instructivos/instructivo_risneu.pdf'],
        ['nombre' => 'Manual de uso - Módulo Legales', 'url' => '/instructivos/instructivo_legales.pdf'],
        ['nombre' => 'Manual de uso - Módulo Odontología', 'url' => '/instructivos/instructivo_odontologia.pdf'],
        ['nombre' => 'Manual de uso - Módulo Acompañar', 'url' => '/instructivos/instructivo_acompanar.pdf'],
        ['nombre' => 'Manual de uso - Módulo Reproam', 'url' => '/instructivos/instructivo_reproam.pdf'],
        ['nombre' => 'Manual de uso - Módulo Certificaciones', 'url' => '/instructivos/instructivo_certificaciones.pdf'],
        ['nombre' => 'Manual de uso - Módulo Gerontología ', 'url' => '/instructivos/instructivo_gerontologia.pdf'],
        ['nombre' => 'Manual de uso - Módulo Relevamiento', 'url' => '/instructivos/instructivo_relevamiento.pdf'],
    ],
    'sort' => [
        'attributes' => ['id', 'name'],
    ],
]);
?>
<style>

</style>

<section class="panel-featured panel-featured-primary">
    <header class="panel-heading bg-default">
        <div class="panel-actions">

        </div>
        <h2 class="panel-title">Otros Instructivos</h2>
    </header>
    <div class="panel-body">
        <div>
            <?= GridView::widget([
                'id' => 'crud-datatable',
                'dataProvider' => $archivos,
                'pjax' => true,
                'columns' => [
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'nombre',
                        'filter' => false,
                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'url',
                        'hAlign'=>'center',
                        'vAlign'=>'middle',
                        'label' => 'Descargar',
                        'value' => function ($data) {
                            // return "<a download target='_blank' href='$data[url]'><span class='fas fa-download'></span></a>";
                            return Html::a('<span class="fas fa-download"></span>', Url::base().$data['url'], ['target'=>'_blank','data-pjax'=>0]);
                        },            
                        'format' => 'raw',
                        'filter' => false,
                    ]
                ],
                'toolbar' => ['content' => null],
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'panel' => [
                    'type' => 'default',
                    'heading' => false,
                    'after' => false,
                    'before' => false,
                    'footer' => false
                ]
            ]) ?>
        </div>
    </div>
</section>