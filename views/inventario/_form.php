<?php

use app\controllers\SiteController;
use app\models\Articulo;
use app\models\Configuracion;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\ConfiguracionTipo;
use app\models\ConstantesGlobales;
use app\models\InformaticaIp;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Inventario */
/* @var $form yii\widgets\ActiveForm */

$discos = Articulo::get_articulos_por_tipo(ConstantesGlobales::ARTICULO_TIPO_DISCO);
$procesadores = Articulo::get_articulos_por_tipo(ConstantesGlobales::ARTICULO_TIPO_PROCESADOR);
$ram = Articulo::get_articulos_por_tipo(ConstantesGlobales::ARTICULO_TIPO_RAM);
$sistemas_operativos = Configuracion::get_configuraciones(ConfiguracionTipo::SISTEMA_OPERATIVO);

$model->es_cpu = $model->es_cpu ?? 0;
$model->tiene_red = $model->tiene_red ?? 0;

// Traemos los IDs de ARTÍCULO cuyo `idtipo` esté registrado como CPU/DVR
$articulosCpuIds = (new \yii\db\Query())
    ->select(['a.idarticulo'])
    ->from(['a' => 'articulo'])
    ->innerJoin(['c' => 'configuracion'], 'c.descripcion = CAST(a.idtipo AS CHAR)')
    ->where(['c.id_configuracion_tipo' => ConfiguracionTipo::TIPO_ARTICULO_CPU])
    ->column();

// Traemos los IDs de ARTÍCULO cuyo `idtipo` esté registrado como RED
$articulosRedIds = (new \yii\db\Query())
    ->select(['a.idarticulo'])
    ->from(['a' => 'articulo'])
    ->innerJoin(['c' => 'configuracion'], 'c.descripcion = CAST(a.idtipo AS CHAR)')
    ->where(['c.id_configuracion_tipo' => ConfiguracionTipo::TIPO_ARTICULO_RED])
    ->column();

$articulosCpuJson = json_encode($articulosCpuIds);
$articulosRedJson = json_encode($articulosRedIds);

$señales = Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_SEÑAL);
$tecnologias_señal = Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_TECNOLOGIA_SEÑAL);
$mascaras_red = Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_MASCARA_RED);
$puertas_enlace = Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_PUERTA_ENLACE_RED);
$dns_red = Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_DNS_RED);
$ipsData = InformaticaIp::get_ips();
$ipsJson = json_encode($ipsData);
?>

<div class="inventario-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'es_cpu')->hiddenInput(['id' => 'input_es_cpu'])->label(false) ?>
    <?= $form->field($model, 'tiene_red')->hiddenInput(['id' => 'input_tiene_red'])->label(false) ?>
    <?= $form->field($model, 'tiene_ip')->hiddenInput(['id' => 'input_tiene_ip'])->label(false) ?>

    <div class="row">
        <div class="col-md-5">
            <?= SiteController::actionGet_input_select2($form, $model, 'idarticulo', 'cmb_articulo', Articulo::get_articulos(), 'idarticulo', 'descripcion', 'Articulo', 'seleccione articulo...') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'matricula')->textInput() ?>
        </div>

        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'idestado', 'cmb_estado', Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_ESTADO_ARTICULO), 'id_configuracion', 'descripcion', 'Estado', 'seleccione estado...') ?>
        </div>

        <div class="col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?php if ($model->origen_alta == 0): ?>
                <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'cmb_dispositivo', OrganismoDispositivo::get_dispositivos(), 'iddispositivo', 'descripcion', 'Dispositivo', 'seleccione dispositivo...') ?>
            <?php else: ?>
                <label class="control-label"><?= $model->getAttributeLabel('iddispositivo') ?></label>
                <p class="form-control-static" style="background: #eee; padding: 6px 12px; border-radius: 4px;">
                    <?= $model->iddispositivo ? OrganismoDispositivo::findOne($model->iddispositivo)->descripcion : '' ?>
                </p>
                <?= $form->field($model, 'iddispositivo')->hiddenInput()->label(false) ?>
            <?php endif; ?>
        </div>

        <div class="col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'idempleado', 'cmb_empleado', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Empleado', 'seleccione empleado...') ?>
        </div>
    </div>

    <!-- Div CPU -->
    <div class="row" id="div_caracteristicas_cpu" style="display: <?= $model->es_cpu ? 'block' : 'none' ?>; margin-top: 15px;">
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'idmicro', 'cmb_micro', $procesadores, 'idarticulo', 'descripcion', 'Micro', 'seleccione Micro...') ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'idram', 'cmb_ram', $ram, 'idarticulo', 'descripcion', 'RAM', 'seleccione RAM...') ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'iddisco', 'cmb_disco', $discos, 'idarticulo', 'descripcion', 'Disco', 'seleccione Disco...') ?>
        </div>
        <div class="col-md-3">
            <?= SiteController::actionGet_input_select2($form, $model, 'idso', 'cmb_so', $sistemas_operativos, 'id_configuracion', 'descripcion', 'Sistema Operativo', 'seleccione Sistema Operativo...') ?>
        </div>
    </div>

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

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observacion')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
use yii\web\View;

// 1. Inyectamos los datos del servidor a variables globales JS
$jsData = <<<JS
window.articulosCpu = {$articulosCpuJson};
window.articulosRed = {$articulosRedJson};
window.ipsMap = {$ipsJson};
JS;
$this->registerJs($jsData, View::POS_HEAD);

$cssUrl = Yii::$app->assetManager->publish(__DIR__ . '/_form.css')[1];
$jsUrl = Yii::$app->assetManager->publish(__DIR__ . '/_form.js')[1];

$this->registerCssFile($cssUrl);
$this->registerJsFile($jsUrl, ['depends' => [\yii\web\JqueryAsset::class]]);
?>