<?php

use app\controllers\SiteController;
use app\models\Articulo;
use app\models\Configuracion;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\ConfiguracionTipo;
use app\models\ConstantesGlobales;
use app\models\InformaticaIp;
use app\models\Inventario;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Inventario */
/* @var $form yii\widgets\ActiveForm */

$discos = Articulo::get_articulos_por_tipo(ConstantesGlobales::ARTICULO_TIPO_DISCO);
$procesadores = Articulo::get_articulos_por_tipo(ConstantesGlobales::ARTICULO_TIPO_PROCESADOR);
$ram = Articulo::get_articulos_por_tipo(ConstantesGlobales::ARTICULO_TIPO_RAM);
$sistemas_operativos = Configuracion::get_configuraciones(ConfiguracionTipo::SISTEMA_OPERATIVO);
$mothers = Articulo::get_articulos_por_tipo(ConstantesGlobales::ARTICULO_TIPO_MOTHERBOARD);
$fuentes = Articulo::get_articulos_por_tipo(ConstantesGlobales::ARTICULO_TIPO_FUENTE);
$placas_video = Articulo::get_articulos_por_tipo(ConstantesGlobales::ARTICULO_TIPO_PLACA_VIDEO);



// Traemos los IDs de ARTÍCULO cuyo `idtipo` esté registrado como CPU/DVR
$articulosCpuIds = Inventario::getArticulosCpuIds();

// Traemos los IDs de ARTÍCULO cuyo `idtipo` esté registrado como RED
$articulosRedIds = Inventario::getArticulosRedIds();

// Evaluamos si el artículo actual pertenece a la categoría CPU o RED
$model->es_cpu = !empty($model->idarticulo) && in_array($model->idarticulo, $articulosCpuIds) ? 1 : 0;
$model->tiene_red = !empty($model->idarticulo) && in_array($model->idarticulo, $articulosRedIds) ? 1 : 0;

// Consultamos si existe relación de IP a través de inventario_dispositivo_red
if (!$model->isNewRecord) {
    $ipAsignada = Inventario::obtenerIpAsignada($model->idinventario);

    if ($ipAsignada) {
        $model->tiene_ip = 1;
        $model->idip = $ipAsignada; // Seteamos el atributo para que el combo lo seleccione
    } else {
        $model->tiene_ip = 0;
    }
} else {
    $model->tiene_ip = $model->tiene_ip ?? 0;
}

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


    <!-- NUEVA FILA: Edificio y Oficina -->
<!-- FILA: Edificio y Oficina -->
    <div class="row">
        <div class="col-md-6">
            <?= SiteController::actionGet_input_select2(
                $form, 
                $model, 
                'idedificio', 
                'cmb_edificio', 
                \app\models\Edificio::get_edificios_fijo_direccion(), 
                'idedificio', 
                'descripcion_fija', 
                'Edificio', 
                'seleccione edificio...'
            ) ?>
        </div>

        <div class="col-md-6">
            <?php 
            // Si ya tenemos edificio (por el afterFind), cargamos las oficinas correspondientes
            $oficinasData = !empty($model->idedificio) 
                ? \app\models\EdificioOficina::get_oficinas_por_edificio($model->idedificio) 
                : [];
            ?>
            <?= SiteController::actionGet_input_select2(
                $form, 
                $model, 
                'idoficina', 
                'cmb_oficina', 
                $oficinasData, 
                'idoficina', 
                'descripcion', 
                'Oficina', 
                'seleccione oficina...'
            ) ?>
        </div>
    </div>

    <?php include '_form_caracteristicas_cpu.php'; ?>
    <?php include '_form_caracteristicas_red.php'; ?>

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