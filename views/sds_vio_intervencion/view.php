<?php

use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_vio_intervencion */

$this->title = "Ver Intervención #{$model->idintervencion}";
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

<header class="page-header">
    <h2>Ver intervención <?php echo $data_persona ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>ver intervención</span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="panel-group">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse">
                                    Usuario de carga
                                </a>
                            </h4>
                        </div>
                        <div class="accordion-body collapse in">
                            <div class="panel-body" id="llamante_content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" value="<?php
                                                                                        $nombre = $usuario_carga->nombre;
                                                                                        $apellido = $usuario_carga->apellido;
                                                                                        $user = $usuario_carga->user;
                                                                                        echo "$apellido $nombre ($user)" ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_persona">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_persona" href="#persona">
                                    Persona en situación de violencia
                                </a>
                            </h4>
                        </div>
                        <div id="persona" class="accordion-body collapse in">
                            <div class="panel-body" id="llamante_content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Persona Agredida</label>
                                        <input type="text" class="form-control" value="<?php echo $data_persona ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Número de Teléfono</label>
                                        <input type="text" class="form-control" value="<?php echo $persona_violencia->telefono ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Domicilio</label>
                                        <input type="text" class="form-control" value="<?php echo $persona_violencia->domicilio ?>" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Provincia</label>
                                        <input type="text" class="form-control" value="<?php echo $provincia ?>" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Localidad</label>
                                        <input type="text" class="form-control" value="<?php echo $localidad ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Sexo</label>
                                        <input type="text" class="form-control" value="<?php echo $sexo ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Género Autopercibido</label>
                                        <input type="text" class="form-control" value="<?php echo ($persona_violencia->generoautopercibido0) ? $persona_violencia->generoautopercibido0->descripcion : 'Sin Asignar' ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Provincia Oriunda</label>
                                        <input type="text" class="form-control" value="<?php echo ($persona_violencia->localidadoriunda0) ? $provincia_oriunda : '' ?>" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Localidad Oriunda</label>
                                        <input type="text" class="form-control" value="<?php echo ($persona_violencia->localidadoriunda0) ? $persona_violencia->localidadoriunda0->descripcion : '' ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nacionalidad Origen</label>
                                        <input type="text" class="form-control" value="<?php echo ($persona_violencia->nacionalidad0) ? $persona_violencia->nacionalidad0->descripcion : '' ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_referente">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_persona" href="#referente">
                                    Referente Terciario
                                </a>
                            </h4>
                        </div>
                        <div id="referente" class="accordion-body collapse in">
                            <div class="panel-body" id="llamante_content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" value="<?php echo $model->referente_telefono ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nombre y Apellido</label>
                                        <input type="text" class="form-control" value="<?php echo $model->referente_nombre ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Vínculo</label>
                                        <input type="text" class="form-control" value="<?php echo $model->referente_vinculo ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_abordaje">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_persona" href="#abordaje">
                                    Abordaje
                                </a>
                            </h4>
                        </div>
                        <div id="abordaje" class="accordion-body collapse in">
                            <div class="panel-body" id="llamante_content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Fecha</label>
                                        <input type="text" class="form-control" value="<?php echo $fecha ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nuevo Ingreso</label>
                                        <input type="text" class="form-control" value="<?php echo $ingreso ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Tipo de Situación</label>
                                        <input type="text" class="form-control" value="<?php echo $tipo_situacion ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tipo de Intervención</label>
                                        <input type="text" class="form-control" value="<?php echo $tipo_intervencion ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Proveniente de</label>
                                        <input type="text" class="form-control" value="<?php echo $derivacion ?>" readonly>
                                    </div>
                                    <?php if ($denuncia == "Si") : ?>
                                        <div class="col-md-4">
                                        <?php else : ?>
                                            <div class="col-md-8">
                                            <?php endif; ?>
                                            <label class="form-label">Denuncia</label>
                                            <input type="text" class="form-control" value="<?php echo $denuncia ?>" readonly>
                                            </div>
                                            <?php if ($denuncia == "Si") : ?>
                                                <div class="col-md-4">
                                                    <label class="form-label">Juzgado</label>
                                                    <input type="text" class="form-control" value="<?php echo $model->juzgado ?>" readonly>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Provincia Del Hecho</label>
                                                <input type="text" class="form-control" value="<?php echo $provincia_hecho ?>" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Localidad Del Hecho</label>
                                                <input type="text" class="form-control" value="<?php echo $localidad_hecho ?>" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Tipo de modalidad</label>
                                                <input type="text" class="form-control" value="<?php echo $tipo_modalidad ?>" readonly>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Consumo Problemático</label>
                                                <input type="text" class="form-control" value="<?php echo $consumo_problematico ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label">Detalle</label>
                                                <div class="alert alert-detalle" role="alert">
                                                    <p><?php echo $model->detalle;  ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Detalle para plataforma vulnerabilidad</label>
                                                <textarea class="form-control" style="min-height: 20vh" readonly><?php echo $model->detalle_plataforma ?></textarea>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Profesionales intervinientes</label>
                                                <textarea class="form-control" style="min-height: 20vh" readonly><?php echo $model->profesionales_intervinientes ?></textarea>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---------------------------------------------------  tipos de violencia ------------------------------------------------------------------------------ -->
                    <div class="panel-group">
                        <div class="panel panel-accordion" id="accordion_vio_fisica">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_fisica" href="#detalle_vio_fisica">
                                        Violencia física
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_fisica" class="accordion-body collapse <?= $model->tipo_violencia_fisica ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioFisicaSelectOptions as $tipoFisica) {
                                                $checked = "";
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $fisica) {
                                                        if ($fisica->idviolenciatipo == $tipoFisica->idconfiguracion) {
                                                            $checked = "checked";
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "
                                                <div class='form-group'>
                                                    <input type='checkbox' tabindex='1'name='Sds_violencia[item][]' value='{$tipoFisica->idconfiguracion}' $checked disabled > 
                                                    <label> {$tipoFisica->descripcion} </label>
                                                </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Frecuencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoFisica['frecuenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Ocurrencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoFisica['ocurrenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Vigencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoFisica['vigencia'] === null ? '' : ($model_frecuencia->tipoFisica['vigencia'] == 1 ? 'si' : 'no') ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel-group" id="accordion_vio_psicologica">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_psicologica" href="#detalle_vio_psicologica">
                                        Violencia psicológica
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_psicologica" class="accordion-body collapse <?= $model->tipo_violencia_psicologica ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioPsicologicaSelectOptions as $tipoPsicologica) {
                                                $checked = "";
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $psicologica) {
                                                        if ($psicologica->idviolenciatipo == $tipoPsicologica->idconfiguracion) {
                                                            $checked = "checked";
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "<div class='form-group'>
                                                    <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoPsicologica->idconfiguracion}' $checked ' disabled > 
                                                    <label> {$tipoPsicologica->descripcion}</label>
                                                </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Frecuencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoPsicologica['frecuenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Ocurrencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoPsicologica['ocurrenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Vigencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoPsicologica['vigencia'] === null ? '' : ($model_frecuencia->tipoPsicologica['vigencia'] == 1 ? 'si' : 'no') ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_vio_sexual">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_sexual" href="#detalle_vio_sexual">
                                        Violencia sexual
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_sexual" class="accordion-body collapse <?= $model->tipo_violencia_sexual ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioSexualSelectOptions as $tipoSexual) {
                                                $checked = "";
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $sexual) {
                                                        if ($sexual->idviolenciatipo == $tipoSexual->idconfiguracion) {
                                                            $checked = "checked";
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "<div class='form-group'>
                                                        <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoSexual->idconfiguracion}' $checked disabled > 
                                                        <label>{$tipoSexual->descripcion}</label>
                                                    </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Frecuencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoSexual['frecuenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Ocurrencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoSexual['ocurrenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Vigencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoSexual['vigencia'] === null ? '' : ($model_frecuencia->tipoSexual['vigencia'] == 1 ? 'si' : 'no') ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_vio_economicapatrimonial">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_economicapatrimonial" href="#detalle_vio_economicapatrimonial">
                                        Violencia económica - patrimonial
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_economicapatrimonial" class="accordion-body collapse <?= $model->tipo_violencia_economica_patrimonial ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioEconomicapatrimonialSelectOptions as $tipoEconomicaPatrimonial) {
                                                $checked = "";
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $economica) {
                                                        if ($economica->idviolenciatipo == $tipoEconomicaPatrimonial->idconfiguracion) {
                                                            $checked = "checked";
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "
                                                <div class='form-group'>
                                                    <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoEconomicaPatrimonial->idconfiguracion}' $checked disabled > 
                                                    <label>{$tipoEconomicaPatrimonial->descripcion}</label>
                                                </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Frecuencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoEconomicaPatrimonial['frecuenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Ocurrencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoEconomicaPatrimonial['ocurrenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Vigencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoEconomicaPatrimonial['vigencia'] === null ? '' : ($model_frecuencia->tipoEconomicaPatrimonial['vigencia'] == 1 ? 'si' : 'no') ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-accordion" id="accordion_vio_simbolica">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_simbolica" href="#detalle_vio_simbolica">
                                        Violencia simbolica
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_simbolica" class="accordion-body collapse <?= $model->tipo_violencia_simbolica ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioSimbolicaSelectOptions as $tipoSimbolica) {
                                                $checked = '';
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $simbolica) {
                                                        if ($simbolica->idviolenciatipo == $tipoSimbolica->idconfiguracion) {
                                                            $checked = 'checked';
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "
                                                <div class='form-group'>
                                                    <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoSimbolica->idconfiguracion}' $checked disabled > 
                                                    <label>{$tipoSimbolica->descripcion}</label>
                                                </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Frecuencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoSimbolica['frecuenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Ocurrencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoSimbolica['ocurrenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Vigencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoSimbolica['vigencia'] === null ? '' : ($model_frecuencia->tipoSimbolica['vigencia'] == 1 ? 'si' : 'no') ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_vio_negligenciaabandono">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_negligenciaabandono" href="#detalle_vio_negligenciaabandono">
                                        Violencia negligencia - abandono
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_negligenciaabandono" class="accordion-body collapse <?= $model->tipo_violencia_negligencia_abandono ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioNegligenciaAbandonoSelectOptions as $tipoNegligenciaAbandono) {
                                                $checked = "";
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $negligencia) {
                                                        if ($negligencia->idviolenciatipo == $tipoNegligenciaAbandono->idconfiguracion) {
                                                            $checked = "checked";
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "
                                                <div class='form-group'>
                                                    <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoNegligenciaAbandono->idconfiguracion}' $checked disabled > 
                                                    <label>{$tipoNegligenciaAbandono->descripcion}</label>
                                                </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Frecuencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoNegligenciaAbandono['frecuenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Ocurrencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoNegligenciaAbandono['ocurrenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Vigencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoNegligenciaAbandono['vigencia'] === null ? '' : ($model_frecuencia->tipoNegligenciaAbandono['vigencia'] == 1 ? 'si' : 'no') ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_vio_ambiental">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_ambiental" href="#detalle_vio_ambiental">
                                        Violencia ambiental
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_ambiental" class="accordion-body collapse <?= $model->tipo_violencia_ambiental ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioAmbientalSelectOptions as $tipoAmbiental) {
                                                $checked = '';
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $ambiental) {
                                                        if ($ambiental->idviolenciatipo == $tipoAmbiental->idconfiguracion) {
                                                            $checked = 'checked';
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "
                                                <div class='form-group'>
                                                    <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoAmbiental->idconfiguracion}' $checked disabled > 
                                                    <label>{$tipoAmbiental->descripcion}</label>
                                                </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3 ">
                                            <label class="form-label">Frecuencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoAmbiental['frecuenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Ocurrencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoAmbiental['ocurrenciaDetalle'] ?>" readonly>
                                            <label class="form-label">Vigencia</label>
                                            <input type="text" class="form-control" value="<?php echo $model_frecuencia->tipoAmbiental['vigencia'] === null ? '' : ($model_frecuencia->tipoAmbiental['vigencia'] == 1 ? 'si' : 'no') ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="acordion_abordaje_complementario">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#acordion_abordaje_complementario" href="#detalle_abordaje_complementario">
                                        Abordajes Complementarios
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_abordaje_complementario" class="accordion-body collapse in">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <textarea class="form-control" style="min-height: 20vh" readonly><?php echo $model->abordaje_complementario ?></textarea>
                                        </div>
                                    </div>

                                    <?php
                                    $adjuntos1 = $model->archivo_adjunto1;
                                    $adjuntos2 = $model->archivo_adjunto2;
                                    ?>
                                    <?php
                                    if ($adjuntos1 || $adjuntos2) : ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <br />
                                                <label>Archivos adjuntos</label>
                                                <ul style="list-style: none">
                                                    <?php
                                                    if ($adjuntos1) : ?>
                                                        <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjuntos1, Url::base() . '/uploads/violencia/' . $adjuntos1, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                                    <?php endif; ?>
                                                    <?php
                                                    if ($adjuntos2) : ?>
                                                        <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjuntos2, Url::base() . '/uploads/violencia/' . $adjuntos2, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                                    <?php endif; ?>
                                                </ul>
                                                <br>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer" id="botones">
                    <a class="btn btn-info" href="index.php?r=sds_vio_intervencion/index">Volver</a> |
                    <a class="btn btn-primary" href="index.php?r=sds_vio_intervencion_movimiento/index&idintervencion=<?= $model->idintervencion ?>" role="modal-remote" title="Asignar Movimientos" data-toggle="tooltip">Movimientos</a>
                </div>
            </div>
        </section>
    </div>


    <?php Modal::begin([
        "id" => "ajaxCrudModal",
        'options' => [
            'tabindex' => false // important for Select2 to work properly
        ],
        'size' => Modal::SIZE_LARGE,
        'clientOptions' => [
            'backdrop' => 'static'
        ],
        "footer" => "", // always need it for jquery plugin
    ]) ?>
    <?php Modal::end(); ?>