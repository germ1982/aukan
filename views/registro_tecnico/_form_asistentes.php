<?php

use app\controllers\SiteController;
use app\models\ConfiguracionTipo;
use app\models\Empleado;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;


    $subQuery = (new Query())
    ->select(['c.descripcion'])
    ->from('configuracion c')
    ->where(['c.id_configuracion_tipo' => ConfiguracionTipo::TIPO_ASISTENCIA_INFORMATICA]);

// 2. Si es edición, quitamos el registro actual de la exclusión
if (!$model->isNewRecord) {
    // IMPORTANTE: id_configuracion es la PK de la tabla configuracion
    $subQuery->andWhere(['not', ['c.id_configuracion' => $model->id_configuracion]]);
}
// 2. Definimos la consulta principal
$query = (new Query())

    ->select([
        'e.idempleado', 
        'descripcion' => "CONCAT(p.apellido, ' ', p.nombre)"
    ])
    ->from('empleado e')
    ->innerJoin('personas p', 'e.idpersona = p.idpersona')
    ->where(['not in', 'e.idempleado', $subQuery])
    ->orderBy(['p.apellido' => SORT_ASC, 'p.nombre' => SORT_ASC]);


$empleados = $query->all();

?>

<div class="configuracion-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form_configuracion',
    ]); ?>

    <?= Html::activeHiddenInput($model, 'id_configuracion_tipo') ?>

    <div class="row">
        <div class="col-md-10">
            <?= SiteController::actionGet_input_select2($form,$model,'descripcion','imput_descripcion',$empleados,'idempleado',
                    'descripcion') ?>
            
        </div>
        <div class="col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => $model->isNewRecord ? true : (bool)$model->activo]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>