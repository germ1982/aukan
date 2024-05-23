<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_ris_persona */

?>
<div class="sds-ris-persona-create">
    <?= $this->render('_form', [
        'model' => $model,
        'model_persona' => $model_persona,
        'tipoParentezco' => $tipoParentezco,
        'tipoSitConyugal' => $tipoSitConyugal,
        'tipoEscolaridad' => $tipoEscolaridad,
        'tipoUltAnioAprobado' => $tipoUltAnioAprobado,
        'tipoEstEducativo' => $tipoEstEducativo,
        'tipoTrabajo' => $tipoTrabajo,
        'tipoVinculoContractual' => $tipoVinculoContractual,
        'tipoTipoTrabajo' => $tipoTipoTrabajo,
        'tipoDiscapacidad' => $tipoDiscapacidad,
        'tipoEnfermedad' => $tipoEnfermedad,
        'tipoCoberturaSalud' => $tipoCoberturaSalud,
        'configuracionParentezco' => $configuracionParentezco,
        'tipoDoc' => $tipoDoc,
        'tipoGenero' => $tipoGenero,
        'tipoGeneroAutopercibido' => $tipoGeneroAutopercibido,
        'tipoNacionalidad' => $tipoNacionalidad,
        'tipoCondicionHacinamiento' => $tipoCondicionHacinamiento,
        'tipoPueblosOriginarios' => $tipoPueblosOriginarios,
        'tipoSustancia' => $tipoSustancia,
        'esPrimeraPersona' => $esPrimeraPersona,
        'isCreate' => $isCreate,
        'oficial' => $oficial
    ]) ?>
</div>