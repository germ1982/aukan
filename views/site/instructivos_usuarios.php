<?php

use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\widgets\Pjax;

$archivos = new ArrayDataProvider([
    'allModels' => [
        ['nombre' => '¿Cómo pedir un usuario para PC/usuario-dominio-SUR?', 'url' => '/instructivos/pedir_usuario_sur.pdf'],        
        ['nombre' => '¿Cómo desbloquear un usuario/blanquear una contraseña de usuario PC/dominio/gde/sur?', 'url' => '/instructivos/blanquear_usuario_pc_dominio_gde_sur.pdf'],
        ['nombre' => '¿Cómo pedir un usuario para GestDocu?', 'url' => '/instructivos/pedir_usuario_gestdocu.pdf'],
        ['nombre' => '¿Cómo desbloquear un usuario/blanquear una contraseña de GestDocu?', 'url' => '/instructivos/blanquear_usuario_gestdocu.pdf'],        
        ['nombre' => '¿Cómo pedir un usuario para Sa.Fi.Pro?', 'url' => '/instructivos/pedir_usuario_safipro.pdf'],
        ['nombre' => '¿Cómo pedir un usuario para RH ProNeu?', 'url' => '/instructivos/pedir_usuario_proneu.pdf'],
        ['nombre' => '¿Cómo pedir un usuario para un Email Institucional?', 'url' => '/instructivos/pedir_usuario_email.pdf'],
        ['nombre' => '¿Cómo desbloquear un usuario/blanquear una contraseña de Email Institucional?', 'url' => '/instructivos/blanquear_usuario_email.pdf'],
        ['nombre' => '¿Cómo pedir un usuario para GDE?', 'url' => '/instructivos/pedir_usuario_gde.pdf'],
        //['nombre' => '¿Cómo pedir un usuario para Sistemas Propios (Ministerio De Desarrollo Social y Trabajo) / S.U.R.?', 'url' => '/instructivos/pedir_usuario_sur.pdf'],
        // ['nombre' => 'Manual introducción GDE', 'url' => '/instructivos/introduccion_gde.html'],        
    ],
    'pagination' => [
        'pageSize' => 20,
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
        <h2 class="panel-title">Instructivos para Cuentas de Usuarios</h2>
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