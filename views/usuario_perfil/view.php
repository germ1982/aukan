<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioPerfil */
?>
<div class="usuario-perfil-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_configuracion',
            //'id_configuracion_tipo',
            'descripcion',
            [
                  'attribute' => 'activo',
                  'value' => function ($model) {
                      return $model->activo == 1 ? 'Si' : 'No';
                  },
              ],
        ],
    ]) ?>

</div>
