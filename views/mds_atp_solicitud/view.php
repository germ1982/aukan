<?php

use app\models\Mds_atp_solicitud;
use yii\widgets\DetailView;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_atp_solicitud */
//array de opciones del form...
//'disabled' => true
function CalculaEdad($fecha)
{
    list($Y, $m, $d) = explode("-", $fecha);
    return (date("md") < $m . $d ? date("Y") - $Y - 1 : date("Y") - $Y);
}
$fecha = $model->fecha_nacimiento;
$edad = CalculaEdad($fecha);
$anio = substr($fecha, 0, 4);
$mes  = substr($fecha, 5, 2);
$dia = substr($fecha, 8, 2);
$fecha = "$dia/$mes/$anio";
?>

<?php $form = ActiveForm::begin(); ?>
<div class="mds-atp-solicitud-view">
    DATOS BENEFICIARIOS
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'tipo_documento')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'documento')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'cuil')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'apellido')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <?php $sexo = $model->sexo;
                                if ($sexo == 'm') {
                                    $sexo = 'Masculino';
                                } else {
                                    $sexo = 'Femenino';
                                }   ?>
                                <?= $form->field($model, 'sexo')->textInput(['maxlength' => true, "readOnly" => true, 'value' => $sexo, 'style' => 'background-color:#ffffff']) ?>
                            </div>
                            <div class="col-md-6">
                                <?php $fn = $model->fecha_nacimiento;
                                $model->fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_nacimiento))); ?>
                                <?= $form->field($model, 'fecha_nacimiento')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">

                            <blockquote class="blockquote" id="blockedad">
                                <p><?php
                                    if ($fn != null) {
                                        $edad = CalculaEdad($fn);
                                        echo 'Edad: ';
                                        if ($edad == 1) {
                                            echo $edad . ' año';
                                        } else {
                                            echo $edad . ' años';
                                        }
                                    } else {
                                        $edad = 110;
                                    }
                                    ?></p>
                                <?php if ($edad < 18) {
                                    echo '<footer class="blockquote-footer">Se requiere un tutor</footer>';
                                } else {
                                    echo '<footer class="blockquote-footer">No se requiere tutor</footer>';
                                }
                                ?>
                            </blockquote>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'telefono')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'telefono_alternativo')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?php
                        switch ($model->estado) {
                            case Mds_atp_solicitud::INSCRIPTO:
                                $estado_atpcen = "Inscripto";
                                break;
                            case Mds_atp_solicitud::RECHAZADO:
                                $estado_atpcen = "Rechazado";
                                break;
                            case Mds_atp_solicitud::APROBADO:
                                $estado_atpcen = "Aprobado";
                                break;
                            case Mds_atp_solicitud::PENDIENTE_ALTA:
                                $estado_atpcen = "Pendiente Alta";
                                break;
                            default:
                                $estado_atpcen = "Estado erroneo - N°: " . $model->estado;
                                break;
                        }
                        ?>
                        <?= $form->field($model, 'estado')->textInput(['maxlength' => true, "readOnly" => true, 'value' => $estado_atpcen, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'localidad')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'direccion')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?php $cargafam = $model->carga_grupo_familiar;
                if ($cargafam == '6') {
                    $cargafam = '6 o mas';
                } ?>
                <?= $form->field($model, 'carga_grupo_familiar')->textInput(['value' => $cargafam, 'maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
            </div>
            <div class="col-md-6">
                <?php $ingresofam = $model->ingreso_grupo_familiar;
                if ($ingresofam == '1') {
                    $ingresofam = 'menos de $10000';
                } else if ($ingresofam == '2') {
                    $ingresofam = 'entre $10000 y $20000';
                } else if ($ingresofam == '3') {
                    $ingresofam = 'entre $20000 y $30000';
                } else if ($ingresofam == '4') {
                    $ingresofam = 'entre $30000 y $40000';
                } else if ($ingresofam == '5') {
                    $ingresofam = 'entre $40000 y $50000';
                } else if ($ingresofam == '6') {
                    $ingresofam = 'más de $50000';
                }
                ?>
                <?= $form->field($model, 'ingreso_grupo_familiar')->textInput(['value' => $ingresofam, 'maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'retirada')->textInput(['value' => $model->retirada == 1 ? 'Si' : 'No', 'maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
            </div>
        </div>
        <br>
        DATOS DE LA CUENTA
        <div class="row">
            <div class="col-md-12">
                <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'entidad')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'sucursal')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'numero_cuenta')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'digito_verificador')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>


        <div class="row">
            <div class='col-md-6' align="center" ;>
                DNI FRENTE
                <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:213px;height:265px;' id='base64image' src='<?php echo $model->foto_dni; ?>' />
            </div>
            <div class='col-md-6' align="center" ;>
                DNI DORSO
                <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:213px;height:265px;' id='base64image' src='<?php echo $model->foto_dnidorso; ?>' />
            </div>
        </div> <br>
    </div>
    <div class="row" style="height:500px">
        <div class='col-md-12' align="center" ;> <br>
            CERTIFICADO
            <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:70%;' id='base64image' src='<?php echo $model->foto_certificado; ?>' />
            <br>
        </div>
    </div>
    <br>
    <label id="label_tut" <?php if ($edad < 18) {
                                echo 'style="display:block"';
                            } else {
                                echo 'style="display:none"';
                            } ?>>DATOS TUTOR </label>
    <div <?php if ($edad < 18) {
                echo ' style="display:block ; border: ridge 1px; padding: 8px;border-color:#D8D8D8;" ';
            } else {
                echo 'style="display:none"';
            } ?> id="div_tutor">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'tutor_tipo_documento')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>

                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'tutor_documento')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'tutor_cuil')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'tutor_nombre')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'tutor_apellido')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <?php $tutor_sexo = $model->tutor_sexo;
                        if ($tutor_sexo == 'm') {
                            $tutor_sexo = 'Masculino';
                        } else {
                            $tutor_sexo = 'Femenino';
                        }   ?>
                        <?= $form->field($model, 'tutor_sexo')->textInput(['maxlength' => true, "readOnly" => true, 'value' => $tutor_sexo, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-7">
                        <?php $model->tutor_fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model->tutor_fecha_nacimiento))); ?>
                        <?= $form->field($model, 'tutor_fecha_nacimiento')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                </div>
                <div class="col-md-14">
                    <?= $form->field($model, 'tutor_parentesco')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class='col-md-6' align="center" ;>
                DNI TUTOR FRENTE
                <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:213px;height:265px;' id='base64image' src='<?php echo $model->tutor_foto_dni; ?>' />
            </div>
            <div class='col-md-6' align="center" ;>
                DNI TUTOR DORSO
                <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:213px;height:265px;' id='base64image' src='<?php echo $model->tutor_foto_dnidorso; ?>' />
            </div>
        </div>

        <br>
    </div>

    <!-- <div class="row">
           <div class="col-md-2">
            <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:100px;height:100px;' id='base64image' src='<?php echo $model->tutor_foto_dni; ?>' />    
           </div>
        </div>   -->

</div>
</div>