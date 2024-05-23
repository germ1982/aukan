<?php

use app\controllers\Sds_cel_movimientoController;
use app\models\Sds_cel_movimiento;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\select2\Select2;
use kartik\date\DatePicker;

$lineanro = $_GET['lineanro'];

function GetFechaActual()
    {
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $mydate=getdate(date("U"));
        
        $dia = $mydate['mday'];
        if($dia<=9)
            {$dia = '0'.$dia;}

        $mes = $mydate['mon'];
        if ($mes<=9)
            {$mes='0'.$mes;}

        $hora = $mydate['hours'];
            if($hora<=9)
                {$hora = '0'.$hora;}

        $minuto = $mydate['minutes'];
            if ($minuto<=9)
                {$minuto='0'.$minuto;}

        $Fecha = "$dia/$mes/$mydate[year] $hora:$minuto";
        //echo "$mydate[mday]/$mydate[mon]/$mydate[year]";
        return $Fecha;
    }


?>

<script>
        $('#nueva_configuracion').hide();
        var FormularioPrincipal = "id_div_sds-cel-movimiento";
</script>


<div class="sds-cel-movimiento-form" id="id_div_sds-cel-movimiento">

    <?php $form = ActiveForm::begin(); ?>

        <div class="row" style="display:none">
            <?php 
                if(isset($lineanro)) 
                    {$model->linea = $lineanro;}
                echo $form->field($model, 'linea')->textInput(['id'=>'input_lineanro'])->label('Linea');
            ?>  
        </div>

        <div class="row">
            <div class="col-md-3">
                    <?php
                        if ($model->fecha != null) 
                            {
                                $fecha = $model->fecha;
                                $model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', $fecha)));
                            }
                        else
                            {
                                $model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', GetFechaActual())));
                            } 

                        echo $form->field($model, 'fecha')->widget(DatePicker::ClassName(), [
                            'name' => 'check_issue_date',
                            'language' => 'es',
                            'readonly' => false,
                            'layout' => '{picker}{input}{remove}',
                            'disabled' => false,
                            'options' => [
                                'class' => 'form-control input-md',
                                'placeholder' => 'DD / MM / YYYY',
                                'label' => 'Fecha',
                            ],
                            'pluginOptions' => [
                                'value' => null,
                                'format' => 'dd/mm/yyyy',
                                'endDate' => date('d/m/Y'),
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'label' => 'Fecha',
                            ]
                        ])->label('Fecha');
                    ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <?php 
                    $aux = $this->context->actionGet_dato_actual($model->linea,"numero");
                    if(!($aux==''))
                        {$model->numero = $aux;}
                    echo $form->field($model, 'numero')->textInput(); 
                ?>
            </div>
            <div class="col-md-7">
                <div class="input-group">

                    <?php 
                        $aux = $this->context->actionGet_dato_actual($model->linea,"organismo");
                        if(!($aux==''))
                            {$model->organismo = $aux;}
                    
                        echo $form->field($model, 'organismo')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                        Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_ORGANISMO_LINEA, false),
                                        'idconfiguracion',
                                        'descripcion'
                                    ),
                            'options' => ['placeholder' => 'Seleccionar Organismo ...',
                                            'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_ORGANISMO_LINEA,
                                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Orgnismo');
                    ?>
                    <span class="input-group-btn" style="padding-top:27px">
                        <?= Html::Button('<i class="glyphicon glyphicon-plus"></i>', [
                            'id' => 'boton_nueva_configuracion',
                            'class' => 'btn btn-primary',
                            'title' => "Nuevo Organismo",
                            'data-toggle' => 'tooltip',
                            'onclick' => 'js:MostrarDivNuevaConfiguracion('.Sds_com_configuracion_tipo::TIPO_ORGANISMO_LINEA.',"Nuevo Organismo","config_'. Sds_com_configuracion_tipo::TIPO_ORGANISMO_LINEA.'","id_div_sds-cel-movimiento");'
                            ]);
                        ?>
                    </span>
                </div>

            </div>
            <div class="col-md-2">
                    <?php 
                        $aux = $this->context->actionGet_dato_actual($model->linea,"baja");
                        
                        if($aux==0)
                            {$model->baja =0;}
                            
                        echo $form->field($model, 'baja')->dropDownList(
                            [
                                '1'=>"si",
                                '0'=>"no", 
                            ],
                            [
                                'id' => 'input_baja',
                            ]
                        )->label('Baja') 
                    ?>  
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>
            </div>
        </div>

    
        <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

    <!-- DIV NUEVA CONFIGURACION ##################################################################################################################################################### -->
        <!-- El siguiente div inicia oculto y sirve para cargar nuevas configuraciones, 
        es generico para cualquier tipo de configuracion -->
        <div class="sds-vio-intervencion-form" id="nueva_configuracion">

            <div class="row">
                <div class="col-md-4">
                    <?=
                        Html::label('nueva', 'label_configuracion', ['id' => 'label_nueva_configuracion'])
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?=
                        Html::input('text', 'Configuracion', '', $options = [
                            'maxlength' => 100,
                            'id' => 'texinput_nueva_configuracion',
                            'style' => 'width:350px',
                            'label' => 'algo'
                        ])
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?=
                        Html::label('...', 'label_estado', ['id' => 'label_estado'])
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2" style="padding-top:27px">
                    <?= Html::Button('Cancelar', [
                        'id' => 'cancelar_nueva_configuracion',
                        'class' => 'btn btn-default',
                        'onclick' => 'js:MostrarFormularioPrincipal(FormularioPrincipal);'
                    ]);
                    ?>
                </div>
                <div class="col-md-2" style="padding-top:27px">
                    <?= Html::Button('Guardar', [
                        'id' => 'guardar_nueva_configuracion',
                        'class' => 'btn btn-default',
                        'onclick' => 'js:GuardarNuevaConfiguracion(FormularioPrincipal);'
                    ]);
                    ?>
                </div>
            </div>
            <?=
                Html::input('hidden', 'hidden_tipo_configuracion', '', $options = ['id' => 'hidden_tipo_configuracion']);
            ?>
            <?=
                Html::input('hidden', 'name_id_combobox', '', $options = ['id' => 'name_id_combobox']);
            ?>
        </div>

<script> // Scripts de la nueva configuracion

    function MostrarFormularioPrincipal(id_form_principal) 
        {
            //esta funcion se llama al cancelar o al realizarse el guardado de una nueva configuracion
            //Oculta el div de carga de la nueva configuracion y muestra el principal del modelo
            $('#' + id_form_principal).show();
            $('#nueva_configuracion').hide();
            $("#btnGuardar").show();
            $("#btnCerrar").show();
        }

    function MostrarDivNuevaConfiguracion(id_tipo, titulo, id_combobox, id_form_principal) {
        //esta funcion se llama en los clic de los botones que son para dar altas a nuevas configuraciones.
        //oculta el div principal y muestra el de edicion de la nueva configuracion
        //como la carga de configuraciones es generica se le pasan cuatro parametros que definen que estoy editando:
        //el id_tipo que me dice el id del tipo de configuracion que voy a guardar, lo guardo en un hidden...
        //un titulo para orientar al usuario acerca de lo que esta editando
        //el id del combo que se esta editando, para que al terminar el guardado lo refresque y lo ordene. tambien lo gurado en un hidden, trucazo!!!
        //El id del div principal del formulario
        $('#texinput_nueva_configuracion').val('');
        $('#name_id_combobox').val(id_combobox);
        $('#label_nueva_configuracion').text(titulo);
        $("#label_estado").text('');
        $('#hidden_tipo_configuracion').val(id_tipo);
        $('#' + id_form_principal).hide();
        $('#nueva_configuracion').show();
        $("#btnGuardar").hide();
        $("#btnCerrar").hide();
    }

    function GuardarNuevaConfiguracion(id_form_principal) {
        //encapsulo los parametros a guardar.
        var parametros = {
            "id_tipo_configuracion": $('#hidden_tipo_configuracion').val(), //este lo tenia de comodin en un hidden..trucazo...               
            "descripcion_configuracion": $('#texinput_nueva_configuracion').val()
        };

        $.ajax({
            data: parametros, //datos que se envian a traves de ajax
            url: 'consultas/sds_com_configuracion_alta.php', //php que recibe la peticion
            type: 'post', //método de envio
            beforeSend: function() {
                $("#label_estado").text("Procesando, espere por favor..."); //alto cartel de estado del tramite
            },
            success: function(response) { //aca recibe el json del php que guarda o dice si ya existia

                var obj = jQuery.parseJSON(response) //pareo el json

                if (obj.anuncio == 'Guardado') {
                    //si lo guardo en el anuncio recibe guardado y procede a agregar el dato al combo y ordenarlo
                    var combo = $('#name_id_combobox').val(); //rescata el id con el que identifico al combo, lo tenia en un hidden, trucazo...
                    $('#' + combo).append(new Option(obj.descripcion, obj.id, false, true)); //agrego el dato al combo el true,false ese me deja el nuevo dato como seleccionado
                    ordenarSelect(combo); //ordeno el combo con esa funcion que encontre en la internet
                    MostrarFormularioPrincipal(id_form_principal); //vuelvo al formulario principal
                }
                $("#label_estado").text(obj.anuncio); //aca imprime el estado, aunque solo es practico cuando dice que ya existe, si guardo sale y ni se ve.
            }
        });

    }

    function ordenarSelect(id_componente) {
        //alta burbuja que encontre en la internet
        var selectToSort = jQuery('#' + id_componente);
        var optionActual = selectToSort.val();
        selectToSort.html(selectToSort.children('option').sort(function(a, b) {
            return a.text === b.text ? 0 : a.text < b.text ? -1 : 1;
        })).val(optionActual);
    }
</script>