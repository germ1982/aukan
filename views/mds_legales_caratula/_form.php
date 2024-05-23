<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_legales_caratula */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>

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

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-legales-caratula-form">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'caratula')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'numero_expediente')->textInput(['type' => 'number']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'caso')->textInput() ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'anio_expediente')->textInput(['type' => 'number']) ?>
                        </div>
                    </div>
                    <?php if ($puedeEliminar) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?=
                                $form->field($model, 'deleted_at')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],
                                )
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row"><br />
                        <div class="col-md-12">
                            <a class="btn btn-info" href="index.php?r=mds_legales_caratula/index">Volver </a>
                            <?= Html::submitButton("Guardar", ['class' => 'btn btn-success', 'id' => 'btnSave']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>