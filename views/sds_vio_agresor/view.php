<?php

use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_vio_agresor */

$this->title = "Ver Agresor DNI: {$model->dni}";
$this->params['breadcrumbs'][] = $this->title;
$idusuario = Yii::$app->user->identity->idusuario;

CrudAsset::register($this);

?>

<style>
    .table>thead>tr>td.info,
    .table>tbody>tr>td.info,
    .table>tfoot>tr>td.info,
    .table>thead>tr>th.info,
    .table>tbody>tr>th.info,
    .table>tfoot>tr>th.info,
    .table>thead>tr.info>td,
    .table>tbody>tr.info>td,
    .table>tfoot>tr.info>td,
    .table>thead>tr.info>th,
    .table>tbody>tr.info>th,
    .table>tfoot>tr.info>th {
        color: #777;
        background-color: #fafafa !important;
    }

    .panel-primary .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
    }

    .btnPrint {
        background: grey !important;
    }

    .btnPrint a {
        color: white !important;
        text-decoration: none !important;
    }

    .alert-detalle {
        color: black;
        background-color: #efefef;
        border-color: lightgray;
        max-height: 300px;
        overflow-y: auto;
    }
</style>

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

    <h1 style="margin-top: 0"><?= Html::encode($this->title) ?></h1>
<?php endif ?>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="row form-group">
                    <div class="col-md-12">
                        <label class="form-label">DNI</label>
                        <input type="text" class="form-control" value="<?php echo $model->dni ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" value="<?php echo $model->nombre ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido</label>
                        <input type="text" class="form-control" value="<?php echo $model->apellido ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="form-label">Género</label>
                        <input type="text" class="form-control" value="<?php echo $model->genero0 ? $model->genero0->descripcion : "" ?>" readonly>
                    </div>
                    <?php if ($model['parentezco']) : ?>
                        <div class="col-md-6">
                            <label class="form-label">Parentesco</label>
                            <input type="text" class="form-control" value="<?php echo $model->parentesco0 ? $model->parentesco0->descripcion : "" ?>" readonly>
                        </div>
                    <?php endif ?>

                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <label class="control-label">Agresor Dato Denuncia</label>
                        <div class="alert alert-detalle" role="alert">
                            <p><?php echo $model->agresor_dato_denuncia;  ?></p>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <?php if ($model->agresor_dav == 1) : ?>
                        <div class="col-md-6">
                        <?php else : ?>
                            <div class="col-md-12">
                            <?php endif ?>
                            <label class="form-label">Agresor Dav</label>
                            <input type="text" class="form-control" value="<?php echo ($model->agresor_dav === null ? "" : ($model->agresor_dav == 1 ? "Si" : "No")) ?>" readonly>
                            </div>

                            <?php if ($model->agresor_dav == 1) : ?>
                                <div class="col-md-6">
                                    <label class="form-label">Agresor Dav Datos</label>
                                    <input type="text" class="form-control" value="<?php echo $model->agresor_dav_datos ?>" readonly>
                                </div>
                            <?php endif ?>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label class="form-label">Escolaridad alcanzada</label>
                                <input type="text" class="form-control" value="<?php echo $model->escolaridadAlcanzada ? $model->escolaridadAlcanzada->descripcion : "" ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Es o fue funcionario/a público</label>
                                <input type="text" class="form-control" value="<?php echo ($model->funcionario === null ? "" : ($model->funcionario == 1 ? "Si" : "No")) ?>" readonly>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label class="form-label">Realiza alguna actividad por la que le descuentan dinero</label>
                                <input type="text" class="form-control" value="<?php echo ($model->desc_actividad === null ? "" : ($model->desc_actividad == 1 ? "Si" : "No")) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Por esa actividad le descuentan para la jubilación</label>
                                <input type="text" class="form-control" value="<?php echo ($model->desc_jubilacion === null ? "" : ($model->desc_jubilacion == 1 ? "Si" : "No")) ?>" readonly>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label class="form-label">Acceso a armas de fuego</label>
                                <input type="text" class="form-control" value="<?php echo ($model->acceso_armas === null ? "" : ($model->acceso_armas == 1 ? "Si" : "No")) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Antecedentes penales</label>
                                <input type="text" class="form-control" value="<?php echo ($model->antecedente_penales === null ? "" : ($model->antecedente_penales == 1 ? "Si" : "No")) ?>" readonly>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label class="form-label">Antecedentes de violencia con parejas o ex parejas</label>
                                <input type="text" class="form-control" value="<?php echo ($model->antecedente_violencia === null ? "" : ($model->antecedente_violencia == 1 ? "Si" : "No")) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Antecendentes de violación de medidas de restrición</label>
                                <input type="text" class="form-control" value="<?php echo ($model->antecedente_restricciones === null ? "" : ($model->antecedente_restricciones == 1 ? "Si" : "No")) ?>" readonly>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label class="form-label">Vínculo con actividades ilícitas</label>
                                <input type="text" class="form-control" value="<?php echo ($model->vinculo_ilicito === null ? "" : ($model->vinculo_ilicito == 1 ? "Si" : "No")) ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Vínculo con personal de seguridad</label>
                                <input type="text" class="form-control" value="<?php echo $model->vinculoPersonalSeg ? $model->vinculoPersonalSeg->descripcion : "" ?>" readonly>
                            </div>
                        </div>
                        <div class="row form-group">
                            <?php if ($model->consumo_problematico == 1) : ?>
                                <div class="col-md-6">
                                <?php else : ?>
                                    <div class="col-md-12">
                                    <?php endif ?>
                                    <label class="form-label">Consumo problemático</label>
                                    <input type="text" class="form-control" value="<?php echo ($model->consumo_problematico === null ? "" : ($model->consumo_problematico == 1 ? "Si" : "No")) ?>" readonly>
                                    </div>

                                    <?php if ($model->consumo_problematico == 1) { ?>
                                        <div class="col-md-6" id="consumo">
                                            <label class="form-label">Tipos de consumo problemático</label>
                                            <?=
                                            Select2::widget([
                                                'name' => 'consumos',
                                                'id' => 'consumos',
                                                'value' => ArrayHelper::map($model->getConsumos(), 'idconsumo', function ($model) {
                                                    return $model->idconsumo;
                                                }),
                                                'data' => $vioConsumoSelectOptions,
                                                'options' => [
                                                    'id' => 'check_consumos',
                                                    'placeholder' => 'Seleccione ploblematicas',
                                                    'multiple' => true,
                                                    'required' => false,
                                                ],
                                                'pluginOptions' => [
                                                    'tags' => true,
                                                    'tokenSeparators' => [','],
                                                    'allowClear' => true,
                                                    'disabled' => true,
                                                ],
                                            ]);
                                            ?>
                                        </div>
                                    <?php } ?>
                                </div>

                                <?php if (!Yii::$app->request->isAjax) : ?>
                                    <br>
                                    <div class="card-footer" id="botones">
                                        <a class="btn btn-info" href="index.php?r=sds_vio_agresor/index">Volver </a>
                                    </div>
                                <?php endif ?>
                        </div>
        </section>
    </div>
</div>