<?php
use yii\widgets\ActiveForm;
?>
<div class="mds-certificacion-direccion-usuario-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label"><b>Dirección/Área</b></label>
            <input type="text" class="form-control" value="<?= $model->idcertificaciondireccion0->direccion0->descripcion ?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label"><b>Usuario</b></label>
            <?php $director = "{$model->usuario->apellido} {$model->usuario->nombre} ({$model->usuario->dni})" ?>
            <input type="text" class="form-control" value="<?= $director ?>" readonly>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3">
            <label class="form-label">Creado:</label>
            <input type="text" class="form-control" value="<?= $model->created_at ? date('d/m/Y', strtotime(str_replace('/', '-', $model->created_at))) :  null ?>" readonly>
        </div>
        <div class="col-md-3">
            <label class="form-label">Eliminado:</label>
            <input type="text" class="form-control" value="<?= $model->deleted_at ? date('d/m/Y', strtotime(str_replace('/', '-', $model->deleted_at))) :  null ?>" readonly>
        </div>
    </div>
    <?php if (!Yii::$app->request->isAjax) : ?>
        <br>
        <div class="card-footer">
            <a class="btn btn-info" href="index.php?r=mds_certificacion_direccion_usuario/index">Volver </a>
        </div>
    <?php endif ?>
    <?php ActiveForm::end(); ?>
</div>