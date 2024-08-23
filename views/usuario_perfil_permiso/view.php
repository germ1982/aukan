<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioPerfilPermiso */
?>
<div class="usuario-perfil-permiso-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idpermiso',
            'idperfil',
            'idtipopermiso',
            'modulo',
            'item',
            'descripcion:ntext',
        ],
    ]) ?>

</div>
