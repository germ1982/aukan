<?php

use yii\helpers\Html;

$textoPromptCategoria = empty($categoriaOptions) ? 'Seleccione primero un concurso' : 'Seleccione opción...';
?>

<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>

<div class="mds-conc-vacante-form">

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
                    <li><span><?= $this->title  ?></span></li>
                </ol>
                <div class="sidebar-right-toggle"></div>
            </div>
        </header>
    <?php endif ?>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'idconcurso')->dropdownList(
                                $concursoOptions,
                                [
                                    'id' => 'idconcurso',
                                    'prompt' => [
                                        'text' => 'Seleccione opción...',
                                        'options' => ['disabled' => true, 'selected' => true]
                                    ],
                                    'onchange' => 'cargarCategoriaOptions();',
                                    'disabled' => !$model->isNewRecord,
                                ]
                            )
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'categoria')->dropdownList(
                                $categoriaOptions,
                                [
                                    'id' => 'categoria',
                                    'prompt' => [
                                        'text' => $textoPromptCategoria,
                                        'options' => ['disabled' => true, 'selected' => true]
                                    ],
                                    'disabled' => empty($categoriaOptions) || !$model->isNewRecord,
                                ]
                            )
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'cantidad')->textInput(['type' => 'number', 'min' => 1, 'max' => 1000]) ?>
                        </div>
                        <div class="col-md-6">
                            <?php echo $form
                                ->field(
                                    $model,
                                    'requiere_titulo'
                                )
                                ->dropDownList(
                                    ['1' => 'Si', '0' => 'No'],
                                    [
                                        'id' => 'lugar',
                                        'prompt' => [
                                            'text' => 'Seleccione opción...',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                    ]
                                ); ?>
                        </div>
                    </div>
                    <?php if (!Yii::$app->request->isAjax) { ?>
                        <div class="row"><br />
                            <div class="col-md-12">
                                <a class="btn btn-info" href="index.php?r=mds_conc_vacante" title="Volver">Volver</a> |
                                <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => 'btn btn-success', 'id' => 'btnSave']) ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
    function cargarCategoriaOptions() {
        const idConcurso = $("#idconcurso").val();
        if (idConcurso) {
            $.post(`index.php?r=mds_conc_vacante/get_categoria_by_idconcurso&idconcurso=${idConcurso}`, function(data) {
                $("#categoria").html(data);
                $("#categoria").val(null).trigger('change');
                $("#categoria").prop("disabled", false);
            });
        }
    }
</script>