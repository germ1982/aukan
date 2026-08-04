<?php

use app\controllers\SiteController;
use yii\helpers\Html;

/** @var array $form */
/** @var array $señales */
/** @var array $tecnologias_señal */
/** @var array $ipsData */
/** @var array $señales */
/** @var array $tecnologias_señal */
/** @var array $mascaras_red */
/** @var array $puertas_enlace */
/** @var array $dns_red */


?>


<!-- Div Red -->
    <div class="row" id="div_caracteristicas_red" style="display: <?= $model->tiene_red ? 'block' : 'none' ?>; margin-top: 15px;">
        <div class="col-md-2">
            <?= SiteController::actionGet_input_select2($form, $model, 'idseñal', 'cmb_señal', $señales, 'id_configuracion', 'descripcion', 'Señal', 'seleccione Señal...') ?>
        </div>
        <div class="col-md-2">
            <?= SiteController::actionGet_input_select2($form, $model, 'idtecnologia_señal', 'cmb_tecnologia_señal', $tecnologias_señal, 'id_configuracion', 'descripcion', 'Tecnología de Señal', 'seleccione Tecnología de Señal...') ?>
        </div>

        <!-- Interruptor para habilitar IP -->
        <div class="col-md-3" style="padding-top: 30px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <label for="chk_tiene_ip" style="margin-bottom: 0; cursor: pointer;">¿Tiene IP?</label>
                <label class="switch" style="margin-bottom: 0;">
                    <?= Html::activeCheckbox($model, 'tiene_ip', [
                        'id' => 'chk_tiene_ip',
                        'label' => false,
                        'checked' => (bool)$model->tiene_ip
                    ]) ?>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="row" id="div_caracteristicas_ip" style="display: <?= $model->tiene_ip ? 'block' : 'none' ?>; margin-top: 15px;">
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'idip', 'cmb_ip', $ipsData, 'idip', 'ip', 'IP', 'seleccione IP...') ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'idmascara_red', 'cmb_mascara_red', $mascaras_red, 'id_configuracion', 'descripcion', 'Máscara de Red', 'seleccione Máscara de Red...') ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'idpuerta_enlace', 'cmb_puerta_enlace', $puertas_enlace, 'id_configuracion', 'descripcion', 'Puerta de Enlace', 'seleccione Puerta de Enlace...') ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'iddns_red', 'cmb_dns_red', $dns_red, 'id_configuracion', 'descripcion', 'DNS de Red', 'seleccione DNS de Red...') ?>
        </div>
    </div>