<?php

use yii\helpers\Html;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_reproam_registro */
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
                <div class="mds-legales-supervisor-area-form">
                    <div class="row">
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'idarea')->widget(Select2::class, [
                                'data' => $areas,
                                'options' => [
                                    'placeholder' => 'Seleccione área...',
                                    'tabIndex' => '1',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'idusuario')->widget(Select2::class, [
                                'data' => $usuariosSupervisores,
                                'options' => [
                                    'placeholder' => 'Seleccione usuario...',
                                    'tabIndex' => '1',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
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
                            <a class="btn btn-info" href="index.php?r=mds_legales_supervisor_area/index">Volver </a>
                            <?= Html::submitButton("Guardar", ['class' => 'btn btn-success', 'id' => 'btnSave']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>