<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RegistroTecnicoIncidencia */

?>
<?= $this->render('_form', [
    'model' => $model,
    'registroTecnico' => $registroTecnico ?? null,
]) ?>
