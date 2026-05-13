<?php
use app\models\OrganismoDispositivo;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$sectores = OrganismoDispositivo::get_dispositivos_con_decreto(true);
$sector_viejo = OrganismoDispositivo::findOne($iddispositivo_viejo)->descripcion;
$dataSelect = ArrayHelper::map($sectores, 'iddispositivo', 'descripcion');

// 1. El action debe incluir el id viejo para que actionMigrar_empleados($iddispositivo_viejo) lo reciba
$form = ActiveForm::begin([
    'action' => ['empleado/migrar_empleados', 'iddispositivo_viejo' => $iddispositivo_viejo],
    'method' => 'post',
    'id' => 'form-migrar-empleados', // Este ID es el que vinculamos con el botón del controller
]); ?>

    <div class="alert alert-warning">
        <i class="fa fa-info-circle"></i> 
        Los empleados del dispositivo <strong><?= Html::encode($sector_viejo) ?></strong> serán migrados al dispositivo seleccionado a continuación:
    </div>

    <?php
    echo Select2::widget([
        'name' => 'iddispositivo_nuevo', // Coincide con tu variable del POST en el controller
        'data' => $dataSelect,
        'options' => [
            'id' => 'input_cmb_dispositivos',
            'placeholder' => 'Buscar por Decreto, Edificio, Oficina o Nombre...',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'dropdownParent' => '#ajaxCrudModal',
        ],
    ]);
    ?>

<?php ActiveForm::end(); ?>

<?php
// Script para que el botón "Migrar" del footer (que está fuera del form) dispare el submit
$this->registerJs("
    $('#btnMigrar').on('click', function(e){
        e.preventDefault();
        $('#form-migrar-empleados').submit();
    });
");
?>