<?php

use app\models\Mds_org_contacto;
use yii\widgets\DetailView;
use app\models\Sds_com_barrio;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_persona;
use app\models\Sds_com_localidad;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_provincia;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;


?>

<style>
    .campo {
        padding: 6px 12px;
        font-size: 12px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        height: 30px;
    }
</style>

<div class="sds-pen-pension-view">

    Datos de Persona
    <div style='border: 1px solid #ccc; border-radius: 4px;'>
        <div class="row" style='padding:10px; '>
            <!-- Datos de persona -->
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Apellido -->
                        <!-- Nombres -->
                        <h6><b>Pensionado</b></h6>
                        <p class="campo">
                            <?php
                            $persona = Sds_com_persona::findOne($model->idpersona);
                            echo "$persona->apellido, $persona->nombre";
                            ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <!-- tipo documento -->
                        <!-- Documento -->
                        <h6><b>Documento</b></h6>
                        <p class="campo">
                            <?php
                            $persona = $persona = Sds_com_persona::findOne($model->idpersona);
                            $tipodocumento = Sds_com_configuracion::findOne($persona->documento_tipo);
                            echo "$tipodocumento->descripcion: $persona->documento";
                            echo Html::input('hidden', 'hidden_dni', "$persona->documento", $options = ['id' => 'hidden_dni']);
                            ?>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <!-- legajo_rh  -->
                        <h6><b>Legajo RH</b></h6>
                        <p class="campo">
                            <?php
                            echo "$model->legajo_rh";
                            ?>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <!-- fecha de nacimiento -->
                        <h6><b>Fecha de Nacimiento</b></h6>
                        <p class="campo">
                            <?php
                            $persona = Sds_com_persona::findOne($model->idpersona);
                            $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $persona->fecha_nacimiento)));
                            echo "$fecha";
                            ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <!-- Nacionalidad -->
                        <h6><b>Nacionalidad</b></h6>
                        <p class="campo">
                            <?php
                            $persona = Sds_com_persona::findOne($model->idpersona);
                            $configuracion = Sds_com_configuracion::findOne($persona->nacionalidad);
                            echo "$configuracion->descripcion";
                            ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <!-- Genero -->
                        <h6><b>Genero</b></h6>
                        <p class="campo">
                            <?php
                            $persona = Sds_com_persona::findOne($model->idpersona);
                            $configuracion = Sds_com_configuracion::findOne($persona->genero);
                            echo "$configuracion->descripcion";
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-3">                
                <div class="col-md-3" style="text-align: center;">
                    <img id="renaper_foto" src="" alt="" height="140px" />
                </div>
            </div> -->
        </div>
        <div style='padding:10px; padding-top: 0px;'>
            <div class="row">
                <div class="col-md-5">
                    <!-- Localidad -->
                    <h6><b>Localidad</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->idlocalidad) {
                            $localidad = Sds_com_localidad::findOne($model->idlocalidad);
                            echo "$localidad->descripcion";
                        }
                        ?>
                    </p>
                </div>
                <div class="col-md-7">
                    <!-- Barrio -->
                    <h6><b>Barrio</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->idbarrio) {
                            $barrio = Sds_com_barrio::findOne($model->idbarrio);
                            echo "$model->idbarrio - ";
                        }
                        ?>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <!-- Calle -->
                    <h6><b>Calle</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->calle";
                        ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <!-- Numero -->
                    <h6><b>Numero</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->numero";
                        ?>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <!-- Manzana -->
                    <h6><b>Manzana</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->manzana";
                        ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <!-- casa -->
                    <h6><b>Casa</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->casa";
                        ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <!-- Lote -->
                    <h6><b>Lote</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->lote";
                        ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <!-- departamento -->
                    <h6><b>Departamento</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->departamento";
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <br>Datos de Pension
    <div style='border: 1px solid #ccc; border-radius: 4px;'>
        <div style='padding:10px; '>
            <div class="row">
                <div class="col-md-3">
                    <!-- programa -->
                    <h6><b>Programa</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->programa) {
                            $configuracion = Sds_com_configuracion::findOne($model->programa);
                            echo "$configuracion->descripcion";
                        }
                        ?>
                    </p>
                </div>
                <div class="col-md-2">
                    <!-- legajo -->
                    <h6><b>Legajo</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->legajo";
                        ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <!-- estado -->
                    <h6><b>Estado</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->estado) {
                            $configuracion = Sds_com_configuracion::findOne($model->estado);
                            echo "$configuracion->descripcion";
                        }
                        ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <!-- fecha_carga -->
                    <h6><b>Fecha carga</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->fecha_carga) {
                            $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_carga)));
                            echo "$fecha";
                        }
                        ?>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <!-- expediente -->
                    <h6><b>Expediente</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->expediente";
                        ?>
                    </p>
                </div>
                <div class="col-md-2">
                    <!-- resolucion -->
                    <h6><b>Resolucion</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->resolucion";
                        ?>
                    </p>
                </div>
                <div class="col-md-2">
                    <!-- tramite_nacion -->
                    <h6><b>Tramite Nacion</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->tramite_nacion == 1) {
                            echo "SI";
                        } else {
                            echo "NO";
                        }
                        ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <!-- lugar_pago -->
                    <h6><b>Lugar de Pago</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->lugar_pago) {
                            $configuracion = Sds_com_configuracion::findOne($model->lugar_pago);
                            echo "$configuracion->descripcion";
                        }
                        ?>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- notas -->
                    <h6><b>Notas</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->notas";
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <br>Datos De Otorgamiento
    <div style='border: 1px solid #ccc; border-radius: 4px;'>
        <div style='padding:10px; '>
            <div class="row">
                <div class="col-md-4">
                    <!-- fecha_otorgado -->
                    <h6><b>Fecha Otorgado</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->fecha_otorgado) {
                            $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_otorgado)));
                            echo "$fecha";
                        }
                        ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <!-- tipo_otorgado -->
                    <h6><b>Tipo Otorgado</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->tipo_otorgado) {
                            $configuracion = Sds_com_configuracion::findOne($model->tipo_otorgado);
                            echo "$configuracion->descripcion";
                        }
                        ?>
                    </p>
                </div>
                <div class="col-md-2">
                    <!-- anio_otorgado -->
                    <h6><b>Año Otorgado</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->anio_otorgado";
                        ?>
                    </p>
                </div>
                <div class="col-md-2">
                    <!-- numero_otorgado -->
                    <h6><b>Numero Otorgado</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->numero_otorgado";
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <br>Datos De Baja
    <div style='border: 1px solid #ccc; border-radius: 4px;'>
        <div style='padding:10px; '>
            <div class="row">
                <div class="col-md-4">
                    <!-- numero_baja -->
                    <h6><b>Numero Baja</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->numero_baja";
                        ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <!-- fecha_baja -->
                    <h6><b>Fecha Baja</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->fecha_baja) {
                            $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_baja)));
                            echo "$fecha";
                        }
                        ?>
                    </p>
                </div>
                <div class="col-md-2">
                    <!-- anio_baja -->
                    <h6><b>Año Baja</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->anio_baja";
                        ?>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- tipo_baja -->
                    <h6><b>Tipo Baja</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->tipo_baja) {
                            $configuracion = Sds_com_configuracion::findOne($model->tipo_baja);
                            echo "$configuracion->descripcion";
                        }
                        ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <!-- causa_baja -->
                    <h6><b>Causa Baja</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->causa_baja) {
                            $configuracion = Sds_com_configuracion::findOne($model->causa_baja);
                            echo "$configuracion->descripcion";
                        }
                        ?>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- observaciones_baja -->
                    <h6><b>Observacioneso Baja</b></h6>
                    <p class="campo">
                        <?php
                        echo "$model->observaciones_baja";
                        ?>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <!-- transferida -->
                    <h6><b>Transferida</b></h6>
                    <p class="campo">
                        <?php
                        if ($model->transferida == 1) {
                            echo "SI";
                        } else {
                            echo "NO";
                        }
                        ?>
                    </p>
                </div>
                <div class="col-md-5">
                    <!-- persona transferida-->
                    <h6><b>Persona Transferida</b></h6>
                    <p class="campo">
                        <?php
                        $aux = '';
                        if ($model->persona_transferida) {
                            $persona = Sds_com_persona::findOne($model->persona_transferida);
                            $aux =  "$persona->apellido, $persona->nombre";
                        }
                        echo $aux;
                        ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <!-- documento persona transferida-->
                    <h6><b>Documento</b></h6>
                    <p class="campo">
                        <?php
                        $aux = '';
                        if ($model->persona_transferida) {
                            $persona = $persona = Sds_com_persona::findOne($model->persona_transferida);
                            $tipodocumento = Sds_com_configuracion::findOne($persona->documento_tipo);
                            $aux =  "$tipodocumento->descripcion: $persona->documento";
                        }
                        echo $aux;
                        ?>
                    </p>
                </div>

            </div>
        </div>
    </div>


</div>


<script>
    aux = $("#hidden_dni").val();
    //alert(aux);
    //buscar_foto_en_renaper(aux);

    function buscar_foto_en_renaper(dni_persona) {
        //gif
        /* gif = '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uvXr2LNr3869u/fv4MOL/x9Pvrz58+jTq1/Pvr379/Djy5+vvMACAvRrGlhgoOCAAvmZRACABC1gIEEGMMBAgCQlYIABBxR4oEAFKLgAgyQ9aIACAxl4oUALKDgAhiMV8GCEIE44gIUkknTAgwR6CAACCjJAYIshEaBhAgDIeICC/Q2EAAEE8IjjRAgMMACHA71owIgFVSiAQAkoQCQBCBw5kQJKKpnljAbgp5AAVxIwpZYTEdDlf0YuVKYCbaIpUZJrNjQklnJixOUAZzL0ZZ4YJSAmoDmVSSSchCpUpaGDNsQoAUwmepCVjELEKKKSHrSooZne9Genczp056eg+kkkqQhRCmmcpWqqaqQLkdh5ZZ+tFiQrkbQeNORAm+JZK0F3rjpQkQbdSSsCVrJaq6C+CiRrllcKdKWyvzLErJjRznhotRFR+mW2AHjLrajbSkskr+COqxClbaYrq7oM4UpQujNSCy9BCZBqJaz3UlSlvf0GLPDABBds8MEIJ6zwwgw37PDDEEcs8cQUV2zxxRhnrPHGHHfs8ccghyzyyCSXbPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9daSBQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn6+cgAEC9GseMHCg4AD8+ZWEgAIFGWAgQQcssECAJCUwwH8EGWjAQAQoOCGDIz04AAIDSdihggNgOJICGnZ4IAADWCgiSQU8SCAAHiag4AIADpTAih0hoOGNHqa4QH8DDcAAAwXgSFECBCjA4UAtQlhQAQsI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9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dY5BQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn688gYIE9GsqIKCgoAAE+ZmUAIAEEWBggQUUECBJCRgoQIEHCoRAggQsSJKBBOAnEIYDEZDggxaKhICB/W0YoQAJKhjiSPsRQCCHCaRI4EAarrhRgwbixyGKBVQ4kAIDDDCjjRE1qMCQAhg4pIQZShhkkCUSGdGIJGrY4JIFxfjkACpKKVGSGApQo0JbCullRfZh6ONCAkB5JkZUYolQk29iNGCdOxmg554HrInnQWmq6SdDexZqwJ8JtSgoRIYa0CeiCAWqJqQ3DTAmpRDJeVABCywwAKaZKtlQp50aMCioBkkapUIIHEDqAgeg4YoQmA4uNAADIBJgAKmfykoQlfzVSCdBBzDAwKEDDbDrqajiiKSoMBrLQJe+PoSjjxwCcCsDC1QbUYsvRgjAAsb26i1DVEaZLQAESHtuQy0KKy4ABhh76bsG1drhvNpSiy+gS+636r8U2XcvwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311lx37fXXYIctNmUBAQAh+QQJCgAAACwAAAAALAHlAAAI/wABCBxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHjyBDihxJsqTJkyhTqlzJsqXLlzBjypxJs6bNmzhz6tzJs6fPn0CDCh1KtKjRo0iTKl3K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bPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dbrBQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn688gYIE9GsqIKCgIAL8+ZVUwAAFEWAgQQIcGOBICTDAgAEEGUjAQAkY2N+CIznIwIQCSTjQfgQggOFIAzi4wEAeAoCAhSOStICDBALgYYUGAkhhix0VoKGIHq5IgAAE+WgjjhERsIABMQpkgIMHGFQhgPZJKCKREg2wwJUGcCgAA0kelECCEgJJ5UQHXHnlAVMuJCF/Q45ZpAFmnsiQj2m6WZGVCxTg0H92ZpRAl33etKaFbQZa4AEGJKooRIOyaChCikYK4UON3vcoQgQgKumlN/HJqUQV7mlgnZ8ypMAAA4i5EIhslroQAqjG39rQl2uq6mpBA8ZaQKFBEgAlq6TeeiqqBYj5pEE+2orAfryWCusAFwoEJo8H0ujrrbMWZC1+KfoYLbYPgThligCIC+5D3qKoIADWnusQiDaSCwCYzbob4Y/3cjiQp/YqlACp+33bb0X21TvwwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311lx37fXXYIfdXkAAOw==';
        $("#renaper_foto").attr("width", "120px");
        $("#renaper_foto").attr("src", 'data:image/gif;base64,' + gif); */
        /* $.ajax({
            data: {
                'servicio': '*get_renaper',
                'auditoria': 'motu',
                'usuario_auditoria': 'motu',
                'filtro': 'documento=' + dni_persona,
                'tipo': 0
            },
            type: "POST",
            dataType: "json",
            url: "https://apisur.neuquen.gov.ar/index.php",
            success: function(data) {
                $.each(data, function(ind, elem) {
                    //respuesta = JSON.stringify(data);//esto lo use para ver todo lo que traia de renaper
                    console.log(ind);
                    if (ind == 'status') {
                        elem_aux = elem;
                        console.log(elem_aux);
                        if (elem_aux == 'error') {
                            $("#renaper_foto").attr("width", "");
                            $("#renaper_foto").attr("src", "");
                            return;
                        }
                    }

                    if (ind == 'records') {
                        console.log(elem[0]);
                        $("#renaper_foto").attr("src", elem[0].result.foto);
                    }
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(thrownError);
            },
        }); */
    }
</script>