<?php

use app\models\Mds_org_contacto;
use app\models\Sds_com_persona;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_registro */
/* @var $form yii\widgets\ActiveForm */

?>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_DEFAULT,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "",
]);
echo "<div id='content_abm'></div>";
?>
<?php Modal::end(); ?>
<div class="mds-hor-registro-form">
    <?php if (isset($save) && $save) :
        //ACA PONER DATOS DE GUARDADO: Fichó Exitosamente! 
        //nombre del contacto / foto o legajo no encontrado (si no se encontró el legajo, mensaje
        //de error sobre el campo de legajo mismo)        
        $contacto = Mds_org_contacto::findOne($model->idcontacto);
        $persona = Sds_com_persona::findOne($contacto->idpersona);
    ?>
        <div class="col-md-12 alert alert-success" id="msj-save" style="padding: 10px;">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h5 class="text-center">
                <b>¡Fichó Exitosamente!</b>
            </h5>
            <div class="col-md-6">
                <?php
                $query = new yii\db\Query;
                $query->select(["path"])
                    ->from(["mds_org_documento foto_dni"])
                    ->where("tipo=1472 and foto_dni.idcontacto=" . $model->idcontacto);
                $command = $query->createCommand();
                $foto_dni = $command->queryOne();
                if ($foto_dni != null) :
                    $foto_dni = $foto_dni['path'];
                ?>
                    <img id="foto" src="<?= $foto_dni ?>" height="130px" alt="Sistema Único de Registro">
                <?php endif;  ?>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <h5 class="col-md-12">
                        <b>Legajo: </b><?= $contacto->legajo ?>
                    </h5>
                </div>
                <div class="row">
                    <h5 class="col-md-12">
                        <b>Apellido: </b><?= $persona->apellido ?>
                    </h5>
                </div>
                <div class="row">
                    <h5 class="col-md-12">
                        <b>Nombre: </b><?= $persona->nombre ?>
                    </h5>
                </div>
            </div>
        </div>
    <?php endif ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'legajo')->textInput() ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function verificar_fecha_existente() {
        $("#txr_msj").html("");
        $("#btnGuardarRegistroHorario").show();

        var id_contacto = $('#cmb_contacto').val();
        var fecha = $('#fecha_registro').val();
        $.post("index.php?r=mds_hor_registro/verificar_fecha_existente&id_contacto=" + id_contacto + "&fecha=" + fecha, function(data) {
            if (data.length > 0) {
                $("#btnGuardarRegistroHorario").hide();
                $("#txr_msj").html("<p style='color: red;'>El empleado registra licencia " + data + "</p>");
            }
        });
    }
</script>
