<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_orden_compra;
use app\models\Sds_stk_recepcion_item;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\Mds_seg_usuario;

function GetFechaActual()
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $mydate = getdate(date('U'));

    $dia = $mydate['mday'];
    if ($dia <= 9) {
        $dia = '0' . $dia;
    }

    $mes = $mydate['mon'];
    if ($mes <= 9) {
        $mes = '0' . $mes;
    }

    $hora = $mydate['hours'];
    if ($hora <= 9) {
        $hora = '0' . $hora;
    }

    $minuto = $mydate['minutes'];
    if ($minuto <= 9) {
        $minuto = '0' . $minuto;
    }

    $Fecha = "$dia/$mes/$mydate[year]";
    //echo "$mydate[mday]/$mydate[mon]/$mydate[year]";
    return $Fecha;
}

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
$usuario = Mds_seg_usuario::findOne($idusuario);
$organismo = $usuario->organismo_stock;
if ($organismo == null) {
    $organismo = 0;
}

echo Html::input(
    'hidden',
    'hidden_organismo_usurio_logueado',
    $organismo,
    $options = ['id' => 'hidden_organismo_usurio_logueado']
);

$form_principal = 'interv_form';

function botonAltaConfig($model, $tipo, $titulo, $form_principal)
{
    //Creo un botón reutilizable para todas las configuraciones. Se muestra el sector de ABM configuración y se llena
    //con lo que devuelve el método del controller 'actionCreate_ext'. Que sería como un create externo. Se usa también en risneu pero llenando un modal.
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to([
            '//sds_com_configuracion/create_ext',
            'tipo' => $tipo,
        ]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btn_config_' . $tipo,
        'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        //"disabled" => !$model->isNewRecord,
        'onclick' =>
            '
                    $("#abm_configuracion").show();
                    $("#abm_configuracion_content").load($(this).attr("value"));
                    $("#abm_configuracion_title").html("' .
            $titulo .
            '");
                    $("#btnGuardar").hide();$("#btnCerrar").hide();
                    $("#' .
            $form_principal .
            '").hide();',
    ]);
}
?>

<script>
    $('#nueva_configuracion').hide();
</script>

<div class="sds-stk-recepcion-form" id='interv_form'>

    <?php $form = ActiveForm::begin(); ?>
        <div id='div_campos' style = <?= $items == 1 ? 'display:none' : '' ?>>
            <?= $form
                ->field($model, 'idrecepcion')
                ->hiddenInput(['id' => 'hidden_input_id_recepcion'])
                ->label(false) ?>
            <div class="row">
                <!-- LINEA 1 XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX -->
                <div class="col-md-3">
                    <!-- FECHA  -->
                    <?php
                    if ($model->fecha != null) {
                        $ban = 1;
                        $fecha = $model->fecha;
                        $model->fecha = date(
                            'd/m/Y',
                            strtotime(str_replace('/', '-', $fecha))
                        );
                    } else {
                        $ban = 0;
                        $model->fecha = date(
                            'd/m/Y',
                            strtotime(str_replace('/', '-', GetFechaActual()))
                        );
                    }

                    echo $form
                        ->field($model, 'fecha')
                        ->widget(DatePicker::ClassName(), [
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
                            ],
                        ])
                        ->label('Fecha');
                    ?>
                </div>
                <!-- <div class="col-md-4"> -->
                <!-- PEDIDO -->
                <!-- $form->field($model, 'pedido')->hiddenInput(['maxlength' => true, 'id'=>'input_pedido'])->label(null) -->
                <!-- </div> -->
                <div class="col-md-6">
                    <!-- Orden de compra -->
                    <?php
                    $consulta = '';
                    //Consulta resumida
                    $consulta = "SELECT oc.idordencompra, oc.numero
                                FROM sds_stk_orden_compra oc
                                left JOIN sds_stk_recepcion r ON oc.idordencompra = r.idordencompra
                                where (SELECT SUM(ifnull(oci.cantidad,0)) - SUM(ifnull(ri.cantidad,0)) AS pendiente
                                        FROM sds_stk_orden_compra_item oci
                                        LEFT JOIN sds_stk_recepcion_item ri ON ri.idordencompraitem = oci.idordencompraitem
                                        WHERE (ri.idrecepcion=r.idrecepcion or ri.idordencompraitem is null)
                                        and oci.idordencompra=oc.idordencompra)>0
                                        and oc.idorganismo=$model->organismo
                                group by idordencompra";
                    echo $form
                        ->field($model, 'idordencompra')
                        ->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                Sds_stk_orden_compra::findBySql(
                                    $consulta
                                )->all(),
                                'idordencompra',
                                'numero'
                            ),
                            'options' => [
                                'placeholder' =>
                                    'Seleccionar Orden de Compra ...',
                                'id' => 'cmd_ordencompra',
                                'disabled' => false,
                                'onchange' => 'validar_orden_compra();',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ])
                        ->label('Orden de compra');
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <!-- EXPEDIENTE -->
                    <?= $form->field($model, 'expediente')->textInput([
                        'maxlength' => true,
                        'id' => 'input_expediente',
                    ]) ?>
                </div>
                <!-- LINEA 2 XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX -->
                <div class="col-md-6">
                    <!-- PROVEEDOR -->
                    <div class="input-group">
                        <div id="div_combo_proveedor">
                            <?= $form
                                ->field($model, 'proveedor')
                                ->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(
                                        Sds_com_configuracion::getConfiguraciones(
                                            Sds_com_configuracion_tipo::TIPO_PROVEEDOR
                                        ),
                                        'idconfiguracion',
                                        'descripcion'
                                    ),
                                    'options' => [
                                        'placeholder' =>
                                            'Seleccionar Proveedor ...',
                                        'id' => 'config_69',
                                        'disabled' => false,
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ]) ?>
                        </div>
                        <span class="input-group-btn">
                            <?php
                            $tipo = Sds_com_configuracion_tipo::TIPO_PROVEEDOR;
                            $titulo = 'Nuevo Proveedor';
                            echo botonAltaConfig(
                                $model,
                                $tipo,
                                $titulo,
                                $form_principal
                            );
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    <!-- LINEA GRILLA DE ITEMS ##################################################################################################################################################### -->
    <div class="row" style="border-radius: 5px; padding: 15px;<?= $model->isNewRecord
        ? 'display:none'
        : '' ?>">
        Items:
        <div id="div_grilla" class="col-md-12" style="border:1px solid #BEBEBE; border-radius: 5px; padding: 5px;"></div>
    </div>

    <?php if (isset($botones)) { ?>
        <br>
        <div class="form-group">
            <?= Html::submitButton(
                $model->isNewRecord ? 'Guardar' : 'Actualizar',
                [
                    'class' => $model->isNewRecord
                        ? 'btn btn-success'
                        : 'btn btn-primary',
                    'id' => 'btnGuardarInterno',
                ]
            ) ?>
            <?= Html::button('Cerrar', [
                'id' => 'btnCerrarInterno',
                'class' => 'btn btn-default',
                'onclick' => '$("#interv_form").hide();
                    //Vuelvo a mostrar los botones ocultos del padre
                    $("#btnGuardar").show();
                    $("#btnCerrar").show();',
            ]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>



</div>

<!-- DIV NUEVA CONFIGURACION ##################################################################################################################################################### -->
<div class="row" id="abm_configuracion" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 id="abm_configuracion_title" class="panel-title">
                </h3>
            </header>
            <div class="panel-body" id="abm_configuracion_content">
            </div>
        </section>
    </div>
</div>
<script>
    function mostrar_abm_recepcion_item() {
        $("#abm_items").show();
        $('#interv_form').hide();
        $("#btnGuardar").hide();
        $("#btnCerrar").hide();
        //$("#btnGuardarInterno").hide();//Ver que es
        //$("#btnCerrarInterno").hide();//Ver que es
    }

    function mostrar_abm_articulos() {
        $("#abm_articulos").show();
        $('#abm_items').hide();
        /* $("#btnGuardar").hide();
        $("#btnCerrar").hide();
        $("#btnGuardarInterno").hide();
        $("#btnCerrarInterno").hide(); */
    }

    function setear_proveedores() {
        $("#loading").show();
        var id_orden_compra = $('#cmd_ordencompra').val();
        $.post("index.php?r=sds_com_configuracion/cmb_proveedor&idordencompra=" + id_orden_compra, function(data) {
            $('#config_69').html(data);
            $('#btn_config_69').prop('disabled',id_orden_compra!='');
            $("#loading").hide();
        });
    }

    function setear_expediente() {
        $("#loading").show();
        var id_orden_compra = $('#cmd_ordencompra').val();
        $.post("index.php?r=sds_stk_orden_compra/get_expediente&idordencompra=" + id_orden_compra, function(data) {
            $('#input_expediente').val(data);
            $("#loading").hide();
        });
    }

    function validar_orden_compra()
        {
            let id_recepcion = $('#hidden_input_id_recepcion').val() ? $('#hidden_input_id_recepcion').val():-1;
            let id_orden_compra = $('#cmd_ordencompra').val();
            console.log(id_recepcion);
            if (id_recepcion && id_orden_compra) {
                aux = "index.php?r=sds_stk_recepcion/validar_orden_compra&idrecepcion=" + id_recepcion + '&idordencompra=' + id_orden_compra;
                $.post(aux, function(data) {
                    console.log(data);
                    //   alert(data);
                    switch(data)
                        {
                            //case 0:
                            case '0':
                                alert("Existen items de la Recepcion no estan en la Orden de Compra");
                                $('#cmd_ordencompra').val(0).trigger("change");
                                break;
                            //case 1:
                            case '1':
                                setear_proveedores();
                                setear_expediente();
                                break;
                            //case 2:
                            case '2':
                                alert("Las cantidades de los items de la Recepcion superan el restante a recepcionar de la orden de compra");
                                $('#cmd_ordencompra').val(0).trigger("change");
                                break;
                        }
                });
            }
            
        }
</script>

<script>
    // Scripts de la nueva configuracion

    refrescar_grilla();


    function eliminar_item(id_recepcion_item) {
        $.post("index.php?r=sds_stk_recepcion_item/delete&id=" + id_recepcion_item, function(data) {
            switch(data)
            {
                    case '0':
                        alert('No se ha eliminado')
                        break;
                    case '1':
                        refrescar_grilla();
                        break;
                    case '2':
                        alert("No se ah eliminado porque el item tiene vinculada una entrega");
                        break;
            }
        });
    }

    function refrescar_grilla() {
        id_recepcion = $('#hidden_input_id_recepcion').val();
        if (id_recepcion) {
            aux = "index.php?r=sds_stk_recepcion_item/grilla_items&idrecepcion=" + id_recepcion;
            $.post(aux, function(data) {
                $("#div_grilla").html(data);
            });
        }
    }

    function MostrarFormularioPrincipal() {
        $('#interv_form').show();
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
            url: 'consultas/sds_vio_intervencion_nueva_configuracion.php', //php que recibe la peticion
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
                    MostrarFormularioPrincipal(); //vuelvo al formulario principal
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

<!-- DIV ITEMS ##################################################################################################################################################### -->
<div class="row" id="abm_items" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 class="panel-title">
                    Agregar Item
                </h3>
            </header>
            <div class="panel-body">
                <?php
                $model_recepcion_item = new Sds_stk_recepcion_item();
                echo $this->render('/sds_stk_recepcion_item/_form', [
                    'model' => $model_recepcion_item,
                    'idrecepcion' => $model->idrecepcion, // <----- aca le paso como parametro el id de la recepcion
                    'botones' => true,
                ]);
                ?>
            </div>
        </section>
    </div>
</div>

<!-- DIV Articulos ##################################################################################################################################################### -->
<div class="row" id="abm_articulos" style="display:none;padding-top: 10px;">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-default">
            <header class="panel-heading">
                <h3 class="panel-title">
                    Agregar Articulo
                </h3>
            </header>
            <div class="panel-body">
                <?php
                $model_articulo = new Sds_stk_articulo();
                echo $this->render('/sds_stk_articulo/_form', [
                    'model' => $model_articulo,
                    //'idrecepcion' => $model->idrecepcion, // <----- aca le paso como parametro el id de la recepcion
                    'botones' => true,
                ]);
                ?>
            </div>
        </section>
    </div>
</div>