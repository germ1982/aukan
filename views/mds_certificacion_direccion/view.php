<?php

use yii\widgets\ActiveForm;
?>

<div class="sds-com-persona-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <label class="form-label"><b>Dirección/Área</b></label>
            <input type="text" class="form-control" value="<?= $model->direccion0->descripcion ?>" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label"><b>Dependiente de</b></label>
            <input type="text" class="form-control" value="<?= $model->direccionPadre ? $model->direccionPadre->descripcion : '' ?>" readonly>
        </div>
        <div class="col-md-4">
            <label class="form-label"><b>Nivel de autorización</b></label>
            <input type="text" class="form-control" value="<?= $model->nivelAutorizacion0 ? $model->nivelAutorizacion0->descripcion : '' ?>" readonly>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label"><b>Usuario</b></label>
            <?php $director = $model_director->usuario ? "{$model_director->usuario->apellido} {$model_director->usuario->nombre} ({$model_director->usuario->dni})" : '' ?>
            <input type="text" class="form-control" value="<?= $director ?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label"><b>Función que desempeña</b></label>
            <input type="text" class="form-control" value="<?= $model_director->idfuncion ? $model_director->funcion_usuario->descripcion : '' ?>" readonly>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label"><b>Fecha desde</b></label>
            <input type="text" class="form-control" value="<?= $model_director->fecha_desde ? date('d/m/Y', strtotime(str_replace('/', '-', $model_director->fecha_desde))) :  null ?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label"><b>Fecha hasta</b></label>
            <input type="text" class="form-control" value="<?= $model_director->fecha_hasta ? date('d/m/Y', strtotime(str_replace('/', '-', $model_director->fecha_hasta))) :  null ?>" readonly>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <label class="form-label"><b>Directores anteriores:</b></label><br>
            <?php foreach ($directores_list as $elemento) {
                $hasta = $elemento['fecha_hasta'] ? $elemento['fecha_hasta'] : 'actualidad';
            ?>
                <?= "{$elemento['apellido']} {$elemento['nombre']} {$elemento['fecha_desde']} - {$hasta}" ?>
                <br>
            <?php } ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>