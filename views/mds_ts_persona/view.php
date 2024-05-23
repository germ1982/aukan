<?php

use app\models\Mds_ts_persona;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\date\DatePicker;
use kartik\widgets\FileInput;
use yii\helpers\Url;

use app\models\Sds_com_provincia;
use app\models\Sds_com_localidad;
use app\models\Sds_com_configuracion;
use app\models\Mds_ts_checklist;
use app\models\Sds_com_configuracion_tipo;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_ts_persona */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-ts-persona-form">

    <?php $form = ActiveForm::begin(); ?>
    <b>CAMPAÑA Y ESTADO DE LA SOLICITUD</b>
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'campania')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff'])->label('') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'estado')->dropDownList(
                    [
                        Mds_ts_persona::SOLICITUD => "Pendiente de Evaluacion",
                        Mds_ts_persona::ACEPTADA => "Aceptada",
                        Mds_ts_persona::RECHAZADA => "Rechazada",
                    ],
                    ['prompt' => '-- Seleccione una opción --', 'disabled' => true]
                ) ?>
            </div>
        </div>
    </div>
    <?php if ($model->tipo_beneficiario == 1) { ?>
        <br>
        <b>DATOS INSTITUCIÓN</b>
        <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'nombre_institucion')->textInput(['maxlength' => true, "readOnly" => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'domicilio_institucion')->textInput(['maxlength' => true, "readOnly" => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'tipo_institucion')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_TS_INSTITUCION, true),
                            'idconfiguracion',
                            'descripcion'
                        ),
                        'options' => [
                            'placeholder' => 'Seleccionar una opción',
                            'tabIndex' => '1',
                            'id' => 'tipo_institucion',
                            'disabled' => true
                        ],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ])->label('Seleccione un Tipo de Institución');
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <br>
    <b>DATOS BENEFICIARIOS</b>
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'dni')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'apellido')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        if ($model->fecha_nacimiento != null) {
                            $fn = $model->fecha_nacimiento;
                            $model->fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                        } else {
                            $fn = null;
                        }
                        echo $form->field($model, 'fecha_nacimiento')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']);
                        ?>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <?= $form->field($model, 'domicilio')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'telefono')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'mail')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff']) ?>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php

                        if ($model->idlocalidad == '') {
                        } else {

                            //echo 'el id localidad es: '.$model->id_localidad;
                            $una_prov = Sds_com_localidad::find()
                                ->select(['idprovincia', 'descripcion'])
                                ->where(['idlocalidad' => $model->idlocalidad])
                                ->one();
                        }
                        $una_provincia = Sds_com_provincia::find()->where(['idprovincia' => $una_prov->idprovincia])->one();
                        $model->la_provincia = $una_provincia->descripcion;
                        $model->idlocalidad = $una_prov->descripcion;
                        echo $form->field($model, 'la_provincia')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff'])->label("Provincia");
                        ?>

                    </div>
                    <div class="col-md-6">
                        <?php
                        echo $form->field($model, 'idlocalidad')->textInput(['maxlength' => true, "readOnly" => true, 'style' => 'background-color:#ffffff'])->label("Localidad");
                        ?>
                    </div>
                    <?php if ($model->tipo_beneficiario == 1) { ?>
                        <div class="col-md-6">
                            <?= $form->field($model, 'relacion_institucion')->textInput(['maxlength' => true]) ?>
                        </div>
                    <?php } ?>
                    <div class="col-md-6">
                        <?= $form->field($model, 'nro_persona')->textInput(['maxlength' => true])->label('N° Persona (Boleta CALF)') ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <br>
    <b>DATOS BENEFICIARIOS</b>
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <div class="row">
            <div class='col-md-3' align="center" ;>
            </div>
            <div class='col-md-6' align="center" ;>
                DNI FRENTE
                <a href="<?php echo $model->foto_dni_frente; ?>" target="_blank">
                    <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width: 100%;' src='<?php echo $model->foto_dni_frente; ?>' />
                </a>
            </div>
            <div class='col-md-3' align="center" ;>
            </div>

        </div><br>
        <div class="row">
            <div class='col-md-3' align="center" ;>
            </div>
            <div class='col-md-6' align="center" ;>
                DNI DORSO
                <a href="<?php echo $model->foto_dni_dorso; ?>" target="_blank">
                    <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width: 100%;' src='<?php echo $model->foto_dni_dorso; ?>' />
                </a>
            </div>
            <div class='col-md-3' align="center" ;>
            </div>
        </div><br>
        <div class="row">
            <div class='col-md-3' align="center" ;>
            </div>
            <div class="col-md-6" align="center" ;>
                FACTURA LUZ <br>
                <?php
                if ($model->factura_luz == null) {
                    echo "No registra factura de luz";
                } else {

                    if ((str_contains($model->factura_luz, '.jpg'))
                        || (str_contains($model->factura_luz, '.jpeg'))
                        || (str_contains($model->factura_luz, '.gif'))
                        || (str_contains($model->factura_luz, '.svg'))
                        || (str_contains($model->factura_luz, '.png'))
                        || (str_contains($model->factura_luz, '.bmp'))
                    ) {
                        echo "                                          
                                <a href='$model->factura_luz' target='_blank'><img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6;  width: 100%;'  src='$model->factura_luz' /></a>";
                    } else {
                        if (str_contains($model->factura_luz, '.pdf')) {

                            echo '<object data=' . $model->factura_luz . '  type="application/pdf" style="height:400px"></object>';
                        }
                    }
                }
                ?>
            </div>
            <div class='col-md-3' align="center" ;>
            </div>
        </div><br>

        <?php if ($model->tipo_beneficiario == 1) { ?>
            <!-- PERSONERIA JURIDICA -->
            <div class="row">
                <div class='col-md-3' align="center" ;>
                </div>
                <div class="col-md-6" align="center" ;>

                    PERSONERIA JURÍDICA <br>
                    <?php
                    if ($model->personeria_juridica == null) {
                        echo "No registra personeria juridica";
                    } else {

                        if ((str_contains($model->personeria_juridica, '.jpg'))
                            || (str_contains($model->personeria_juridica, '.jpeg'))
                            || (str_contains($model->personeria_juridica, '.gif'))
                            || (str_contains($model->personeria_juridica, '.svg'))
                            || (str_contains($model->personeria_juridica, '.png'))
                            || (str_contains($model->personeria_juridica, '.bmp'))
                        ) {
                            echo "                                            
                                            <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width: 100%;'  src='" . $model->personeria_juridica . "' />                            
                                            ";
                        } else {
                            if (str_contains($model->personeria_juridica, '.pdf')) {

                                echo '<object data=' . $model->personeria_juridica . '  type="application/pdf" style="height:400px"></object>';
                            }
                        }
                    }
                    ?>
                </div>
                <div class='col-md-3' align="center" ;>
                </div>
            </div>
        <?php } else { ?>
            <!-- RECIBO DE SUELDO -->
            <div class="row">
                <div class='col-md-3' align="center" ;>
                </div>
                <div class="col-md-6" align="center" ;>

                    RECIBO DE SUELDO <br>
                    <?php
                    if ($model->recibo_sueldo == null) {
                        echo "No registra recibo de sueldo";
                    } else {

                        if ((str_contains($model->recibo_sueldo, '.jpg'))
                            || (str_contains($model->recibo_sueldo, '.jpeg'))
                            || (str_contains($model->recibo_sueldo, '.gif'))
                            || (str_contains($model->recibo_sueldo, '.svg'))
                            || (str_contains($model->recibo_sueldo, '.png'))
                            || (str_contains($model->recibo_sueldo, '.bmp'))
                        ) {
                            echo "                                            
                                            <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width: 100%;'  src='" . $model->recibo_sueldo . "' />                            
                                            ";
                        } else {
                            if (str_contains($model->recibo_sueldo, '.pdf')) {

                                echo '<object data=' . $model->recibo_sueldo . '  type="application/pdf" style="height:400px"></object>';
                            }
                        }
                    }
                    ?>
                </div>
                <div class='col-md-3' align="center" ;>
                </div>
            </div>
        <?php } ?>
    </div>
    <br>
</div>
<?php if ($model->tipo_beneficiario == 0) { ?>
    OPCIONES SELECCIONADAS
    <div style="border: ridge 1px; padding: 8px; border-color:#D8D8D8;">
        <?php

        $chequeos = Mds_ts_checklist::findBySql("SELECT idconfiguracion
        FROM mds_ts_checklist                                                
        WHERE idtspersona=" . $model->idtspersona)->all();
        $array_asist = [];
        $m = 0;
        foreach ($chequeos as $una_asistencia) {
            $array_asist[$m] = $una_asistencia->idconfiguracion;
            $m++;
        }

        $cadcheckasist = "";
        foreach ($chequeos as $una_asistencia) {
            $cadcheckasist = $cadcheckasist . "-" . $una_asistencia->idconfiguracion;
        }
        $model->cad_check = $cadcheckasist;
        ?>
        <?= $form->field($model, 'cad_check')->hiddenInput()->label(false) ?>

        <?php
        $tipos_asistencias = Sds_com_configuracion::getConfiguraciones(94);
        $cont = 0;
        $cad_asistencias1 = '';


        foreach ($tipos_asistencias as $tipo_asist) {
            $checked = "";
            if (in_array($tipo_asist->idconfiguracion, $array_asist)) {
                $checked = "checked";
            }

            $cad_asistencias1 = $cad_asistencias1 . '
                                <div class="col-md-6"> ' .
                '<input type="checkbox" tabindex="1"  name="tschek" id="asistencia' . $cont . '"' .
                ' value=' . $tipo_asist->idconfiguracion . ' ' . $checked . ' readonly="readonly"  onclick="javascript: return false;" > ' . $tipo_asist->descripcion;
            $cad_asistencias1 = $cad_asistencias1 . '</div>';
            $cont++;
        }

        if ($cad_asistencias1 != '') {
            echo '<div class="row">';
            echo $cad_asistencias1;
            echo '</div>';
        }

        $model->num_opciones_asistencia = $cont;
        echo $form->field($model, 'num_opciones_asistencia')->hiddenInput(['id' => 'num_opciones_asistencia'])->label(false);
        ?>
    </div>
<?php } ?>

<?php if (!Yii::$app->request->isAjax) { ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php } ?>

<?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<  JS

function cargarLocalidades() {
    $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia").val(), function(data) {             
            $("select#cmb_localidad").html(data);
    });
}
$("body").on("click","[name='tschek']",function(event)
    { 
        var cadena="";
        $("input:checkbox:checked").each(   
        function() {
            cadena=cadena+"-"+$(this).val();            
            //alert("El checkbox con valor " + $(this).val() + " está seleccionado");
        }
        );       
        $("#mds_ts_persona-cad_check").attr('value',cadena);       
    });

 $(document).ready(function(){
    $(':checkbox[readonly=readonly]').click(function(){
      return false;        
     });
  });  

JS;

$this->registerJs($script);

?>