<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_reproam_mandato */

$this->title = "Actualizar supervisor/a de área";

?>
<?php $form = ActiveForm::begin(['action' => ["mds_legales_supervisor_area/update", 'id' => $model->idlegalessupervisorarea], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-legales-oficio-update">
    <?= $this->render('_form', [
        'action' => $action,
        'form' => $form,
        'model' => $model,
        'areas' => $areas,
        'usuariosSupervisores' => $usuariosSupervisores,
        'puedeEliminar' => $puedeEliminar,
    ]) ?>
</div>
<?php ActiveForm::end(); ?>