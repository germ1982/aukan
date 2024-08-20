<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioAsignacionPerfil */
?>
<div class="usuario-asignacion-perfil-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idusuario',
            'idperfil',
            'activo',
        ],
    ]) ?>

</div>
