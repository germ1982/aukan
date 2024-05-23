<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion_familia */
?>
<div class="sds-800-atencion-familia-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idllamada',
            'idpersona',
            'lugar_intervencion',
            'lugar_especificacion',
            'defensora',
            'edad',
            'idpersona_referente',
            'parentezco',
            'alojado',
            'hogar',
            'dia_hora',
            'operador',
            'equipo_tecnico',
            'sabe_leer',
            'nivel_estudio',
            'establecimiento',
            'trabaja',
            'tipo_trabajo',
            'atendido',
            'institucion',
            'nombre_profesionales',
            'beneficio_social',
            'area_beneficio',
            'centro_salud',
            'nombre_centro_salud',
            'obra_social',
            'nombre_obra_social',
            'tratamiento_medico',
            'tratamiento_institucion',
            'orientado',
            'intoxicado',
            'violentado',
            'plan_accion:ntext',
            'fecha_intervencion',
            'idusuario',
        ],
    ]) ?>

</div>
