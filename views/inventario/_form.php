<?php

use app\controllers\SiteController;
use app\models\Articulo;
use app\models\Configuracion;
use app\models\Empleado;
use app\models\OrganismoDispositivo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Inventario */
/* @var $form yii\widgets\ActiveForm */

$array_tipos = Configuracion::find()->where(['activo' => 1, 'id_configuracion_tipo' => 12])->orderBy('descripcion')->all();
?>

<div class="inventario-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'idInventario')->textInput() ?>
        </div>
        <div class="col-md-8">
            <?= SiteController::actionGet_input_select2($form, $model, 'idarticulo', 'cmb_articulo', Articulo::get_articulos(), 'idarticulo', 'descripcion', 'Articulo', 'seleccione articulo...') ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'cantidad')->textInput() ?>
        </div>



    </div>
    <div class="row">
        <div class="col-md-7">
            <?= SiteController::actionGet_input_select2($form, $model, 'iddispositivo', 'cmb_dispositivo', OrganismoDispositivo::get_dispositivos(), 'iddispositivo', 'descripcion', 'Dispositivo', 'seleccione dispositivo...') ?>
        </div>
        <div class="col-md-5">
            <?= SiteController::actionGet_input_select2($form, $model, 'idempleado', 'cmb_empleado', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Empleado', 'seleccione empleado...') ?>

        </div>


    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'idestado')->textInput() ?>
        </div>

        <div class=" col-md-4" style="padding-top:30px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
        </div>

    </div>

</div class="row">
<?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>
</div>


<?php if (!Yii::$app->request->isAjax) { ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php } ?>

<?php ActiveForm::end(); ?>

</div>