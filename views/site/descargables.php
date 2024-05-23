<?php

use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

$archivos = new ArrayDataProvider([
    'allModels' => [
        ['nombre' => 'Hoja de Nota - Ministerio de Desarrollo Social y Trabajo', 'url' => Url::base().'/descargables/nota_ministerio.docx'],
        ['nombre' => 'Hoja de Nota - Subsecretaría de Desarrollo Social', 'url' => Url::base().'/descargables/nota_subse_desarrollo.docx'],
        ['nombre' => 'Hoja de Nota - Subsecretaría de Familia', 'url' => Url::base().'/descargables/nota_subse_familia.docx'],
        ['nombre' => 'Internos', 'url' => Url::base().'/descargables/internos.pdf'],
        //['nombre' => 'Solicitud de Licencia', 'url' => Url::base().'/descargables/licencia.pdf'],
        ['nombre' => 'Membrete - Ministerio de Desarrollo Social y Trabajo', 'url' => Url::base().'/descargables/ministerio_dsyt.jpg'],
        ['nombre' => 'Membrete - Subsecretaría de Desarrollo Social', 'url' => Url::base().'/descargables/subse_desarrollo.jpg'],
        ['nombre' => 'Membrete - Subsecretaría de Familia', 'url' => Url::base().'/descargables/subse_familia.jpg'],
        ['nombre' => 'Membrete - Subsecretaría de Trabajo', 'url' => Url::base().'/descargables/subse_trabajo.jpg'],
        ['nombre' => 'Actualizar Sicopro', 'url' => 'https://www.dropbox.com/s/2msu5j7db6w6mn3/Actualizar_Sicopro2008.bat?dl=0']
    ],
    'pagination' => [
        'pageSize' => 10,
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
        <h2 class="panel-title">Archivos Descargables</h2>
    </header>
    <div class="panel-body">
        <div>
            <?= GridView::widget([
                'id' => 'crud-datatable',
                'dataProvider' => $archivos,
                'pjax' => false,
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
                            return Html::a('<span class="fas fa-download"></span>', $data['url'], ['target'=>'_blank']);
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

<?php
    $archivosRH= new ArrayDataProvider([
        'allModels' => [
            ['nombre' => 'Solicitud de Licencia', 'url' => Url::base().'/descargables/licencia.pdf'],
            ['nombre' => 'Solicitud de Licencia CCT', 'url' => Url::base().'/descargables/licencias_cct.pdf'],
            ['nombre' => 'DDJJ Carga Familiar', 'url' => Url::base().'/descargables/ddjj_carga_familiar.pdf'],
            //['nombre' => 'DDJJ Domicilio/Email', 'url' => Url::base().'/descargables/ddjj_domicilio_email.pdf'],
            ['nombre' => 'DDJJ Domicilio/Email ART', 'url' => Url::base().'/descargables/ddjj_domicilio_art.pdf'],
        ],
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'attributes' => ['id', 'name'],
        ],
    ]);
?>
<section class="panel-featured panel-featured-primary">
    <header class="panel-heading bg-default">
        <div class="panel-actions">
            
        </div>
        <h2 class="panel-title">Formularios RRHH</h2>
    </header>
    <div class="panel-body">
        <div>
            <?= GridView::widget([
                'id' => 'crud-datatable',
                'dataProvider' => $archivosRH,
                'pjax' => false,
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
                            return Html::a('<span class="fas fa-download"></span>', $data['url'], ['target'=>'_blank']);
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