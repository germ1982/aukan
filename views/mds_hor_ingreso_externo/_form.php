<?php

use app\models\Mds_hor_ingreso_externo;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_configuracion;
use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo;
use app\models\Sds_com_persona;
use Da\QrCode\Label;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;



$this->registerJs(
    "

        $(document).ready(function(){

            if ($('#inputDniPersona').val()!='')
            {
                datos_persona();
            }

            $('#ajaxCrudModal').on('shown.bs.modal', function () {
                $('#inputDniPersona').focus();
            });
            
        }); 
            $('#btn_dni').on('click',function(){datos_persona()});
            $('#inputDniPersona').keyup(function(e){ValidaringresoDni()});
            "
);
?>

<div class="mds-hor-ingreso-externo-form" id="div_ingreso">
    <?php $form = ActiveForm::begin(); ?>
    <!-- BLOQUE 1 BUSQUEDA ##################################################################################################################################################### -->
    <div class="col-md-8">
        <!-- Linea de busqueda -->
        <div class="col-md-8">
            <!-- si es edicion y viene con un id persona lo setea en el campo de dni -->
            <?php
            /* if (isset($model->idpersona)) {
                $persona = Sds_com_persona::findOne($model->idpersona);
                $documento = $persona->documento;
                $model->dni = $documento;
            } */
            echo $form
                ->field($model_persona, 'documento')
                ->textInput([
                    //'type' => 'number',
                    'id' => 'inputDniPersona',
                    'oninput' => 'leer_datos();',
                    'autofocus' => true,
                ])
                ->label('Buscar persona por Dni');
            ?>
        </div>
        <div class="col-md-1" style="padding-top:25px;">
            <?php echo Html::a(
                '<i class="glyphicon glyphicon-search"></i>',
                null,
                [
                    'name' => 'btn_dni',
                    'id' => 'btn_dni',
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-primary',
                    'title' => Yii::t('app', 'Consultar DNI Llamante'),
                ]
            ); ?>
        </div>

        <div class="col-md-7" style="padding-top:5px; padding-bottom:10px" id="txt_mensaje">

        </div>

    </div>

    <!--Probando hora actual    -->
    <?php
    /*
            if ($model->fecha_hora != null) {
                        $ban = 1;
                        $fecha = $model->fecha_hora;
                        $model->fecha_hora = date(
                            'd/m/Y',
                            strtotime(str_replace('/', '-', $fecha))
                        );
                    } else {
                        $ban = 0;
                        //$model->fecha_hora = date('d/m/Y', strtotime(str_replace('/', '-', GetFechaActual())));
                        $model->fecha_hora = date('d/m/Y');
                    } */

    //$time = time();
    //$model->fecha_hora = date('d-m-Y',$time);
    ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form
                ->field($model, 'fecha_hora')
                ->textInput(['readonly' => true, 'id' => 'hora_llegada'])
                ->label('Fecha Hora de llegada');

            ?>
        </div>
    </div>
    <!-- Probando hora actual    -->
    <!-- BLOQUE 2 DATOS DE PERSONA ##################################################################################################################################################### -->
    Datos de Persona
    <div style='border: 1px solid #ccc; border-radius: 4px;'>
        <div class="row" style='padding:10px; '>
            <!-- Datos de persona -->
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Apellido -->
                        <?= $form
                            ->field($model_persona, 'apellido')
                            ->textInput(['id' => 'input_apellido'])
                            ->label('Apellido') ?>
                    </div>
                    <div class="col-md-6">
                        <!-- Nombres -->
                        <?= $form
                            ->field($model_persona, 'nombre')
                            ->textInput(['id' => 'input_nombre'])
                            ->label('Nombres') ?>
                    </div>

                    <div class="col-md-2">
                        <?= $form
                            ->field($model_persona, 'idpersona')
                            ->hiddenInput(['id' => 'VarHiddenIdPersona'])
                            ->label('')
                        // ->hiddenInput()
                        ?>

                    </div>

                </div>
                <div class="row">

                    <div class="col-md-4">
                        <!-- tipo documento -->
                        <?php
                        $idtipo = Sds_com_configuracion_tipo::TIPO_TIPO_DOC;
                        $consulta = "Select * From sds_com_configuracion Where idconfiguraciontipo = $idtipo and activo = 1";
                        $datos = ArrayHelper::map(
                            Sds_com_configuracion::findBySql($consulta)->all(),
                            'idconfiguracion',
                            'descripcion'
                        );
                        echo $form
                            ->field($model_persona, 'documento_tipo')
                            ->dropdownList($datos, [
                                'id' => 'input_combo_tipo_documento'
                            ])
                            ->label('Tipo Documento');
                        ?>

                    </div>
                    <div class="col-md-4">
                        <!-- documento -->
                        <?= $form
                            ->field($model_persona, 'documento')
                            ->textInput([
                                'id' => 'input_numero_documento',
                                'disabled' => true
                            ])
                            ->label('Numero Documento') ?>

                    </div>
                    <div class="col-md-4">
                        <!-- fecha de nacimiento -->

                        <?php
                        if ($model_persona->fecha_nacimiento != null) {
                            $model_persona->fecha_nacimiento = date('d/m/Y', strtotime(str_replace('-', '/', $model_persona->fecha_nacimiento)));
                        }
                        echo $form
                            ->field($model_persona, 'fecha_nacimiento')
                            ->widget(DatePicker::ClassName(), [
                                'name' => 'check_issue_date',
                                'language' => 'es',
                                'readonly' => false,
                                'layout' => '{picker}{input}{remove}',
                                'options' => [
                                    'id' => 'fecha_nacimiento',
                                    'class' => 'form-control input-md',
                                    'disabled' => false,
                                ],
                                'pluginOptions' => [
                                    'value' => null,
                                    'format' => 'dd/mm/yyyy',
                                    'endDate' => date('d/m/Y'),
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                ],
                            ])
                            ->label('Fecha de Nacimiento');
                        ?>

                    </div>
                </div>

                <div class="row">

                    <div class="col-md-4">
                        <!-- Nacionalidad -->
                        <?php
                        $idtipo = Sds_com_configuracion_tipo::TIPO_NACIONALIDAD;
                        $consulta = "Select * From sds_com_configuracion Where idconfiguraciontipo = $idtipo and activo = 1";
                        $datos = ArrayHelper::map(
                            Sds_com_configuracion::findBySql($consulta)->all(),
                            'idconfiguracion',
                            'descripcion'
                        );
                        echo $form
                            ->field($model_persona, 'nacionalidad')
                            ->dropdownList($datos, [
                                'id' => 'input_combo_nacionalidad',
                            ]);
                        ?>
                    </div>
                    <div class="col-md-4">
                        <!-- Genero -->
                        <?php
                        $idtipo = Sds_com_configuracion_tipo::TIPO_GENERO;
                        $consulta = "Select * From sds_com_configuracion Where idconfiguraciontipo = $idtipo and activo = 1";
                        $datos = ArrayHelper::map(
                            Sds_com_configuracion::findBySql($consulta)->all(),
                            'idconfiguracion',
                            'descripcion'
                        );
                        echo $form
                            ->field($model_persona, 'genero')
                            ->dropdownList($datos, [
                                'id' => 'input_combo_genero',
                            ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- contenedor de registro del destino de la persona externa -->
    <div style='border: 1px solid #ccc; border-radius: 4px; margin-top: 30px;'>
        <div style="padding: 10px;">
            <div class="row">
                <div class="col-md-4">
                    <?php
                    echo $form
                        ->field($model, 'idorganismo')
                        ->widget(Select2::class, [
                            // 'id' => 'cmb_contacto',
                            'data' => ArrayHelper::map(
                                Mds_org_organismo::findBySql(
                                    "SELECT * FROM mds_org_organismo o
                                     WHERE recepcion=1"
                                )->all(),
                                'idorganismo',
                                'descripcion'
                            ),
                            'options' => [
                                'placeholder' => 'Seleccionar Organismo ...',
                                'id' => 'cmb_contacto',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]); ?>
                </div>
                <div class="col-md-4">
                    <?php

                    echo $form
                    ->field($model, 'motivo')
                    ->widget(Select2::class, [
                    // 'id' => 'cmb_contacto',
                    'data' => ArrayHelper::map(
                    Sds_com_configuracion::findBySql(
                    "SELECT * FROM sds_com_configuracion o
                    WHERE idconfiguraciontipo=197"
                    )->all(),
                    'idconfiguracion',
                    'descripcion'
                    ),
                    'options' => [
                    'placeholder' => 'Seleccionar motivo ...',
                    'id' => 'motivo',
                    ],
                    'pluginOptions' => [
                    'allowClear' => true,
                    ],
                    ]); ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'sector')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form
                        ->field($model, 'observaciones')
                        ->textarea(['rows' => 3]) ?>
                </div>
            </div>


        </div>
    </div>

    <!-- BLOQUE 4 GRILLA DE MOVIMIENTOS ##################################################################################################################################################### -->
    <div class="row" style="border-radius: 5px; padding: 15px;">
        Ultimos 10 ingresos:
        <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;">
            <?php if ($model_persona->idpersona) {
                $dataProvider = new ActiveDataProvider([
                    'query' => Mds_hor_ingreso_externo::findBySql("SELECT * FROM mds_hor_ingreso_externo WHERE idpersona = $model->idpersona ORDER BY fecha_hora DESC LIMIT 10"),

                ]);
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'summary' => '',
                    'id' => 'grilla_movimientos',
                    'columns' => [

                        [
                            'attribute' => 'fecha_hora',
                            'label' => 'Fecha de Ingreso',
                            'value' => function ($model) {
                                if ($model->fecha_hora != null) {
                                    $fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hora)));
                                    return "$fecha";
                                }
                                return "";
                            },
                        ],
                        [
                            'attribute' => 'fecha_hora_ingreso',
                            'label' => 'Hora de Ingreso',
                            'value' => function ($model) {
                                if ($model->fecha_hora_ingreso != null) {
                                    $hora = date('H:i', strtotime(str_replace('/', '-', $model->fecha_hora_ingreso)));
                                    return "$hora";
                                }
                                return "";
                            },
                        ],
                        [
                            'attribute' => 'idcontacto',
                            'label' => 'Contacto Responsable',
                            'value' => function ($model) {
                                if ($model->idcontacto != null) {
                                    $contacto = Mds_org_contacto::findOne($model->idcontacto);
                                    $persona = Sds_com_persona::findOne($contacto->idpersona);
                                    return "$persona->apellido, $persona->nombre";
                                }
                                return "";
                            },
                        ],
                        'observaciones',
                    ],
                ]);
            } else {
                echo 'Sin Movimientos...';
            } ?>
        </div>
    </div>



    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'create' : 'update', [
                'class' => $model->isNewRecord
                    ? 'btn btn-success'
                    : 'btn btn-primary',
            ]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

<!------------------------- DATOS DE INGRESO ---------------------------->

<!------------------------- FIN DATOS DE INGRESO ---------------------------->
<?php $this->registerJsFile('@web/js/capturar_dni.js');?>
<script>
    function datos_persona(ristra = null) {
        $("#VarHiddenIdPersona").val('0');
        var dni_persona = $("#inputDniPersona").val();
        if (dni_persona == "") {
            alert("escriba un dni");
            return;
        }

        $('#input_numero_documento').val(dni_persona);
        $("#input_numero_documento").prop("readonly", true);

        $('#txt_mensaje').html("Buscando datos de Persona con dni " + dni_persona);

        if($('#hora_llegada').val()==''){
            let fh = new Date()
            let day = ((fh.getDate()) < 10) ? '0' + fh.getDate() : fh.getDate();
            let mes = fh.getMonth() + 1;
            let anio = fh.getFullYear();

            let hora = (fh.getHours() < 10) ? '0' + fh.getHours() : fh.getHours();
            let min = (fh.getMinutes() < 10) ? '0' + fh.getMinutes() : fh.getMinutes();
            let seg = (fh.getSeconds() < 10) ? '0' + fh.getSeconds() : fh.getSeconds();

            let fecha_hora = day + '/' + mes + '/' + anio + ' ' + hora + ':' + min + ':' + seg;
            $('#hora_llegada').val(fecha_hora); //al cargar un DNI se carga la hora exacta a la que llego la persona
        }
        $.post("index.php?r=mds_hor_ingreso_externo/validar_dni&dni_persona=" + dni_persona, function(data) {
            data = $.parseJSON(data);
            if (data.length === 0) {
                console.log('NOOOO se encontro en db');
                $("#div_grilla").html("Sin Movimientos...");
                // BloquearControlesPersona(false);
                LimpiarCamposAltaPersona(dni_persona);
                buscar_en_renaper(dni_persona,ristra);
            } else {
                console.log('Se encontro en db');
                console.log(data);
                $("#VarHiddenIdPersona").val(data[0]['idpersona']);
                $('#input_combo_nacionalidad').val(data[0]['nacionalidad']);
                $('#input_combo_genero').val(data[0]['genero']);
                $('#input_apellido').val(data[0]['apellido']);
                $('#input_nombre').val(data[0]['nombre']);
                $('#input_combo_tipo_documento').val(data[0]['documento_tipo']);

                $('#fecha_nacimiento').val(formatearFecha(data[0]['fecha_nacimiento'])); //llamamos a la funcionn formatear fecha para que cambie los '-' por '/'


                // BloquearControlesPersona(true);
                MostrarGrillaIngresos(data[0]['idpersona']);
                //buscar_foto_en_renaper(dni_persona);
                aux = "Ingresante: " + data[0]['apellido'] + ', ' + data[0]['nombre'];
                $('#txt_mensaje').html(aux);
            }

        });


    }

    function BloquearControlesPersona(option) {
        if (option === true) {
            $('#input_combo_nacionalidad').prop("readonly", true);
            $('#input_combo_genero').prop("readonly", true);
            $('#input_combo_tipo_documento').prop("readonly", true);
            $('#input_numero_documento').prop("readonly", true);
            $("#fecha_nacimiento").prop("readonly", true);
            $("#input_apellido").prop("readonly", true);
            $("#input_nombre").prop("readonly", true);

        } else {
            $('#input_combo_nacionalidad').prop("disabled", false);
            $('#input_combo_genero').prop("disabled", false);
            $('#input_combo_tipo_documento').prop("disabled", false);
            $('#input_numero_documento').prop("disabled", false);
            $("#fecha_nacimiento").prop("disabled", false);
            $("#input_apellido").prop("disabled", false);
            $("#input_nombre").prop("disabled", false);
        }
    }

    function MostrarGrillaIngresos(idpersona) {
        aux = "index.php?r=mds_hor_ingreso_externo/grilla_ingresos&idpersona=" + idpersona;
        $.post(aux, function(data) {
            $("#div_grilla").html(data);
        });
    }

    function ValidaringresoDni() {
        var aux = event.which;
        if (aux == 13) //pregunto si fue el enter
        {
            datos_persona();
        }
        /* else
            {
                aux = event.key;
                if (!/^([0-9])*$/.test(aux))
                    {
                        dni_campo = $('#inputDniPersona').val();
                        //alert("Solo Numeros");
                        dni_campo = dni_campo.substring(0,dni_campo.length-1);
                        $('#inputDniPersona').val(dni_campo);
                    }
            } */

    }

    function buscar_foto_en_renaper(dni_persona) {
        /*  gif = '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uvXr2LNr3869u/fv4MOL/x9Pvrz58+jTq1/Pvr379/Djy5+vvMACAvRrGlhgoOCAAvmZRACABC1gIEEGMMBAgCQlYIABBxR4oEAFKLgAgyQ9aIACAxl4oUALKDgAhiMV8GCEIE44gIUkknTAgwR6CAACCjJAYIshEaBhAgDIeICC/Q2EAAEE8IjjRAgMMACHA71owIgFVSiAQAkoQCQBCBw5kQJKKpnljAbgp5AAVxIwpZYTEdDlf0YuVKYCbaIpUZJrNjQklnJixOUAZzL0ZZ4YJSAmoDmVSSSchCpUpaGDNsQoAUwmepCVjELEKKKSHrSooZne9Genczp056eg+kkkqQhRCmmcpWqqaqQLkdh5ZZ+tFiQrkbQeNORAm+JZK0F3rjpQkQbdSSsCVrJaq6C+CiRrllcKdKWyvzLErJjRznhotRFR+mW2AHjLrajbSkskr+COqxClbaYrq7oM4UpQujNSCy9BCZBqJaz3UlSlvf0GLPDABBds8MEIJ6zwwgw37PDDEEcs8cQUV2zxxRhnrPHGHHfs8ccghyzyyCSXbPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9daSBQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn6+cgAEC9GseMHCg4AD8+ZWEgAIFGWAgQQcssECAJCUwwH8EGWjAQAQoOCGDIz04AAIDSdihggNgOJICGnZ4IAADWCgiSQU8SCAAHiag4AIADpTAih0hoOGNHqa4QH8DDcAAAwXgSFECBCjA4UAtQlhQAQsI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9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dY5BQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn688gYIE9GsqIKCgoAAE+ZmUAIAEEWBggQUUECBJCRgoQIEHCoRAggQsSJKBBOAnEIYDEZDggxaKhICB/W0YoQAJKhjiSPsRQCCHCaRI4EAarrhRgwbixyGKBVQ4kAIDDDCjjRE1qMCQAhg4pIQZShhkkCUSGdGIJGrY4JIFxfjkACpKKVGSGApQo0JbCullRfZh6ONCAkB5JkZUYolQk29iNGCdOxmg554HrInnQWmq6SdDexZqwJ8JtSgoRIYa0CeiCAWqJqQ3DTAmpRDJeVABCywwAKaZKtlQp50aMCioBkkapUIIHEDqAgeg4YoQmA4uNAADIBJgAKmfykoQlfzVSCdBBzDAwKEDDbDrqajiiKSoMBrLQJe+PoSjjxwCcCsDC1QbUYsvRgjAAsb26i1DVEaZLQAESHtuQy0KKy4ABhh76bsG1drhvNpSiy+gS+636r8U2XcvwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311lx37fXXYIctNmUBAQAh+QQJCgAAACwAAAAALAHlAAAI/wABCBxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHjyBDihxJsqTJkyhTqlzJsqXLlzBjypxJs6bNmzhz6tzJs6fPn0CDCh1KtKjRo0iTKl3K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bPLJKKes8sost+zyyzDHLPPMNNds880456zzzjz37PPPQAct9NBEF2300UgnrfTSTDft9NNQRy311FRXbfXVWGet9dbrBQQAIfkECQoAAAAsAAAAACwB5QAACP8AAQgcSLCgwYMIEypcyLChw4cQI0qcSLGixYsYM2rcyLGjx48gQ4ocSbKkyZMoU6pcybKly5cwY8qcSbOmzZs4c+rcybOnz59AgwodSrSo0aNIkypdyrSp06dQo0qdSrWq1atYs2rdyrWr169gw4odS7as2bNo06pdy7at27dw48qdS7eu3bt48+rdy7ev37+AAwseTLiw4cOIEytezLix48eQI0ueTLmy5cuYM2vezLmz58+gQ4seTbq06dOoU6tezbq169ewY8ueTbu27du4c+vezbu379/AgwsfTry48ePIkytfzry58+fQo0ufTr269evYs2vfzr279+/gw4v/H0++vPnz6NOrX8++vfv38OPLn688gYIE9GsqIKCgIAL8+ZVUwAAFEWAgQQIcGOBICTDAgAEEGUjAQAkY2N+CIznIwIQCSTjQfgQggOFIAzi4wEAeAoCAhSOStICDBALgYYUGAkhhix0VoKGIHq5IgAAE+WgjjhERsIABMQpkgIMHGFQhgPZJKCKREg2wwJUGcCgAA0kelECCEgJJ5UQHXHnlAVMuJCF/Q45ZpAFmnsiQj2m6WZGVCxTg0H92ZpRAl33etKaFbQZa4AEGJKooRIOyaChCikYK4UON3vcoQgQgKumlN/HJqUQV7mlgnZ8ypMAAA4i5EIhslroQAqjG39rQl2uq6mpBA8ZaQKFBEgAlq6TeeiqqBYj5pEE+2orAfryWCusAFwoEJo8H0ujrrbMWZC1+KfoYLbYPgThligCIC+5D3qKoIADWnusQiDaSCwCYzbob4Y/3cjiQp/YqlACp+33bb0X21TvwwQgnrPDCDDfs8MMQRyzxxBRXbPHFGGes8cYcd+zxxyCHLPLIJJds8skop6zyyiy37PLLMMcs88w012zzzTjnrPPOPPfs889ABy300EQXbfTRSCet9NJMN+3001BHLfXUVFdt9dVYZ6311lx37fXXYIfdXkAAOw==';
         $("#renaper_foto").attr("width", "160px");
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

    function buscar_en_renaper(dni_persona,ristra = null) {

         $('#loading').show();
         console.log('buscar_en_renaper');
         try
            {
                console.log('ingresa al try para hacer el post');
                    $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni_persona, function(data) {
                            try{

                                    console.log('hizo el post');
                                    if(data == 'error')
                                        {
                                            $('#loading').hide();
                                            if(ristra)
                                                {   
                                                    console.log(ristra,'cuando tira error');
                                                    
                                                    $('#txt_mensaje').html("No se encontro informacion del dni " + dni_persona + " , en la base de datos y hubo un error en renaper, proceder a la carga manual por escaneo de Dni");
                                                    mostrar_datos_ristra(ristra);
                                                }
                                            else
                                                {
                                                    $('#txt_mensaje').html("No se encontro informacion del dni " + dni_persona + " , complete el alta manualmente");
                                                }
                                            

                                        }
                                    else
                                        {
                                            //---------------------------------------------------------------------------
                                            $.each(data, function(ind, elem) {
                                            //respuesta = JSON.stringify(data);//esto lo use para ver todo lo que traia de renaper
                                            console.log(ind);
                                            if (ind == 'status') 
                                                {
                                                    elem_aux = elem;
                                                    console.log(elem_aux);
                                                    if (elem_aux == 'error') {

                                                        
                                                    
                                                                $('#txt_mensaje').html("No se encontro informacion del dni " + dni_persona + " , complete el alta manualmente");
                                                                $("#fecha_nacimiento").prop("readonly", false);
                                                                $("#input_apellido").prop("readonly", false);
                                                                $("#input_nombre").prop("readonly", false);
                                                                /* $("#renaper_foto").attr("width", "");
                                                                $("#renaper_foto").attr("src", ""); */
                                                                //PrepararCamposAltaPersonaAMano(dni_persona);
                                                                
                                                            
                                                            $('#loading').hide();    
                                                        return;
                                                    }
                                                }
                                            if (ind == 'records') 
                                                {
                                                    console.log(elem[0]);
                                                    $('#input_apellido').val(corregir_palabra(elem[0].result.apellido));
                                                    $('#input_nombre').val(corregir_palabra(elem[0].result.nombres));
                                                    $('#fecha_nacimiento').val(elem[0].result.fecha_nacimiento);
                                                    $('#input_combo_genero').val(elem[0].result.genero);
                                                    if (elem[0].result.genero == 'M') {
                                                        $('#input_combo_genero').val(2734);
                                                    }
                                                    if (elem[0].result.genero == 'F') {
                                                        $('#input_combo_genero').val(2733);
                                                    }
                                                    $('#input_combo_nacionalidad').val(elem[0].result.nacionalidad);
                                                    //Se comprueba resultado de RENAPER y se asigna ID Argentina en caso de corresponder
                                                    if (elem[0].result.nacionalidad == 'ARGENTINA') {
                                                        $('#input_combo_nacionalidad').val(70);
                                                    }
                                                    /* $("#renaper_foto").attr("src", elem[0].result.foto); */
                                                    $("#fecha_nacimiento").prop("readonly", true);
                                                    $("#input_apellido").prop("readonly", true);
                                                    $("#input_nombre").prop("readonly", true);
                                                    $('#txt_mensaje').html("Persona encontrada en RENAPER, completar datos faltantes para el alta...");
                                                }
                                                $('#loading').hide();       
                                            });
                                        }

                                    
                            }
                            catch (err)
                            {
                                $('#txt_mensaje').html("No se encontro informacion del dni " + dni_persona + " , en la base de datos y hubo un error en renaper, se porcede a la carga manual por escaneo de Dni");
                                mostrar_datos_ristra(ristra);
                                $('#loading').hide();
                            }
                });
            }
            catch(err)
            {
                $('#txt_mensaje').html("No se encontro informacion del dni " + dni_persona + " , en la base de datos y hubo un error en renaper, se porcede a la carga manual por escaneo de Dni");
                mostrar_datos_ristra(ristra);
                $('#loading').hide();    
            }
       
    }



    function LimpiarCamposAltaPersona(dni_persona) {
        /* $("#renaper_foto").attr("src", ""); */
        $('#txt_mensaje_alta_persona').html("");
        $('#input_combo_nacionalidad').val("");
        $('#input_combo_genero').val("");
        $('#input_apellido').val("");
        $('#input_nombre').val("");
        $('#input_combo_tipo_documento').val("");
        $('#input_numero_documento').val(dni_persona);
        $('#fecha_nacimiento').val("");

        //$("#fecha_nacimiento").prop("disabled",false);
        /*$("#fecha_nacimiento").prop("readonly",false);
            $("#input_apellido").prop("readonly",false);
            $("#input_nombre").prop("readonly",false); */


    }

    function PrepararCamposAltaPersonaAMano(dni_persona) {
        /*$("#renaper_foto").attr("width", "");
        $("#renaper_foto").attr("src", ""); */
        $('#txt_mensaje_alta_persona').html("");
        $('#input_combo_nacionalidad').val("");
        $('#input_combo_genero').val("");
        $('#input_apellido').val("");
        $('#input_nombre').val("");
        $('#input_combo_tipo_documento').val("");
        $('#input_numero_documento').val(dni_persona);
        $('#fecha_nacimiento').val("");

        /*             $('#input_combo_nacionalidad').prop("disabled",false);
                    $('#input_combo_genero').prop("disabled",false);
                    $('#input_combo_tipo_documento').prop("disabled",false);
                    $('#input_numero_documento').prop("disabled",true);
                    $("#fecha_nacimiento").prop("disabled",false);
                    $("#input_apellido").prop("disabled",false);
                    $("#input_nombre").prop("disabled",false); */


    }

    function corregir_palabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        palabra = palabra.toLowerCase();
        palabra = palabra.replace(/(^\w{1})|(\s+\w{1})/g, letter => letter.toUpperCase());
        return palabra;
    }

    function formatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }

   

   function leer_datos()
    {


        var input = document.getElementById("inputDniPersona");
        var timeoutId;

        input.oninput = function() {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(function() {
                var ristra = $('#inputDniPersona').val();
                var dni =  get_datos_ristro(ristra,'dni');
                if(dni.length>0)
                    {
                        $('#inputDniPersona').val(dni);
                        $('#input_numero_documento').val(dni);
                        datos_persona(ristra);
                        //verificar_existencia(ristra);
                        $('#inputDniPersona').focus();

                    }
                
                    
                }, 500);
        };
    }

/* function verificar_existencia(ristra)
{

    var timeoutId;
    clearTimeout(timeoutId);
            timeoutId = setTimeout(function() {
                if(dato.length>0)
            {
                
                
            }
                    
                }, 500);
    
} */

function mostrar_datos_ristra(ristra)
    {
        var dato =  get_datos_ristro(ristra,'dni');


        if(dato.length>0)
            {
                var dato =  get_datos_ristro(ristra,'apellido');
                $('#input_apellido').val(dato);
                var dato =  get_datos_ristro(ristra,'nombre');
                $('#input_nombre').val(dato);
                var dato =  get_datos_ristro(ristra,'nacimiento');
                $('#fecha_nacimiento').val(dato);
                var dato =  get_datos_ristro(ristra,'genero');
                set_genero(dato);
                var dato =  get_datos_ristro(ristra,'dni');
                $('#input_numero_documento').val(dato);
                $('#inputDniPersona').val(dato);
            }

    }

function set_genero(genero)
{
    console.log(genero,'genero');
    if (genero != 'M' && genero != 'F') {$('#input_combo_genero').val(688);}
    if (genero == 'M') {$('#input_combo_genero').val(82);}
    if (genero == 'F') { $('#input_combo_genero').val(81);}

}
</script>