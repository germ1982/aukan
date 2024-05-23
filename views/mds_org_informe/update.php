<?php


/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_informe */
?>
<div class="mds-org-informe-update">

    <?= $this->render('_form', [
        'model' => $model,
        'usuarios' => $usuarios,
        'tiposInforme' => $tiposInforme,
        'organismos' => $organismos,
        'compartidos' => $compartidos,
        'cantMaxCompartidos' => $cantMaxCompartidos,
        'urlAnterior' => $urlAnterior
    ]) ?>

</div>
