<?php

use yii\widgets\ActiveForm;

$this->title = "Ver Registro #{$model->idcertificacionprogramamonto}";
?>

<?php $form = ActiveForm::begin(); ?>
<?php if (!Yii::$app->request->isAjax) : ?>
    <header class="page-header">
        <h2><?= $this->title ?></h2>
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="index.php">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span><?= $this->title ?></span></li>
            </ol>
            <div class="sidebar-right-toggle"></div>
        </div>
    </header>
<?php endif ?>
<div class="mds-certificacion-programa-monto-view">
    <div class="row">
        <div class="col-md-6">
            <label class="form-label"><b>Dirección</b></label>
            <input type="text" class="form-control" value="<?= $model->certificacionPrograma->programa0->descripcion ?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label"><b>Programa</b></label>
            <input type="text" class="form-control" value="<?= $model->certificacionPrograma->direccion0->direccion0->descripcion ?>" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label"><b>Monto</b></label>
            <input type="number" class="form-control" value="<?= $model->monto ?>" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label class="form-label"><b>Fecha Inicio</b></label>
            <input type="text" class="form-control" value="<?php echo $model->fecha_inicio ? date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_inicio))) :  null ?>" readonly>
        </div>
        <div class="col-md-6">
            <label class="form-label"><b>Fecha Fin</b></label>
            <input type="text" class="form-control" value="<?php echo $model->fecha_fin ? date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_fin))) :  null ?>" readonly>
        </div>
    </div>
    <?php if (!Yii::$app->request->isAjax) : ?>
        <br>
        <div class="card-footer">
            <a class="btn btn-info" href="index.php?r=mds_certificacion_programa_monto/index">Volver </a>
        </div>
    <?php endif ?>
</div>
<?php ActiveForm::end(); ?>