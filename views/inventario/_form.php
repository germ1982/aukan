<?php

use app\controllers\SiteController;
use app\models\Articulo;
use app\models\Configuracion;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use app\models\ConfiguracionTipo;
use app\models\ConstantesGlobales;
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
?>

<div class="inventario-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'es_cpu')->hiddenInput(['id' => 'input_es_cpu'])->label(false) ?>
    <?= $form->field($model, 'tiene_red')->hiddenInput(['id' => 'input_tiene_red'])->label(false) ?>

    <div class="row">
        <div class="col-md-5">
            <?= SiteController::actionGet_input_select2($form, $model, 'idarticulo', 'cmb_articulo', Articulo::get_articulos(), 'idarticulo', 'descripcion', 'Articulo', 'seleccione articulo...') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'cantidad')->textInput() ?>
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
        chorizo de red
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observacion')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
const articulosCpu = {$articulosCpuJson};
const articulosRed = {$articulosRedJson};

$('#cmb_articulo').on('change', function() {
    let idArticulo = $(this).val();
    let esInt = parseInt(idArticulo);

    // Evaluamos la propiedad CPU
    if (articulosCpu.includes(idArticulo) || articulosCpu.includes(esInt)) {
        $('#div_caracteristicas_cpu').slideDown();
        $('#input_es_cpu').val(1);
    } else {
        $('#div_caracteristicas_cpu').slideUp();
        $('#input_es_cpu').val(0);
        $('#cmb_micro, #cmb_ram, #cmb_disco, #cmb_so').val('').trigger('change');
    }

    // Evaluamos la propiedad RED
    if (articulosRed.includes(idArticulo) || articulosRed.includes(esInt)) {
        $('#div_caracteristicas_red').slideDown();
        $('#input_tiene_red').val(1);
    } else {
        $('#div_caracteristicas_red').slideUp();
        $('#input_tiene_red').val(0);
        // Acá podemos limpiar los combos de red cuando los agregues
    }
});
JS;
$this->registerJs($js);
?>