<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion_am */
?>
<div class="sds-800-atencion-am-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idllamada',
            'idpersona',
            'idusuario',
            'fecha_hora',
            'telefono_referente',
            'demanda:ntext',
            'atencion_previa',
            'institucion',
            'profesionales',
            'basura',
            'cable',
            'internet',
            'familiares',
            'sociales',
            'sociales_detalle',
            'emergente',
            'emergente_detalle',
            'psicologico',
            'psiquiatrico',
            'administra_dinero',
            'detalle_dinero',
            'plan',
            'detalle_plan',
            'recreacion',
            'centro',
            'orientado',
            'dependiente',
            'intoxicado',
            'delirios',
            'violentado',
            'expresion',
            'observaciones:ntext',
            'archivo_seguridad:ntext',
            'archivo_salud:ntext',
        ],
    ]) ?>

</div>
