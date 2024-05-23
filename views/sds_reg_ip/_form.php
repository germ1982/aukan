<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Sds_com_persona;
use app\models\Mds_org_contacto;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\select2\Select2;

$const_Tipo_asignacion = Sds_com_configuracion_tipo::TIPO_ASIGNACION_IP;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_reg_ip */
/* @var $form yii\widgets\ActiveForm */
?>
<script>
    $('#nueva_configuracion').hide();
</script>

<div id="form_principal" class="sds-reg-ip-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- ##################################################################################################################################################### -->
        <div class="row">
            <!-- ------------------------------------------------------------------------------------------------------------------------- -->
            <div class="col-md-7">
                <?= $form->field($model, 'idcontacto')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(
                            Mds_org_contacto::find()->all(), //el order debe ser asi'apellido' => SORT_ASC, 'nombre' => SORT_ASC etc
                            'idcontacto', //ca siempre tiene que ir el id a buscar en el search
                            function ($model) 
                                {
                                    $idcontacto = $model->idcontacto;
                                    if ($idcontacto != null) {
                                        $contacto = Mds_org_contacto::findOne($idcontacto);
                                        $idpersona = $contacto->idpersona;
                                    }
                        
                                    if ($idpersona != null) {
                                        $persona = Sds_com_persona::findOne($idpersona);
                                        $aux = "$persona->apellido, $persona->nombre";
                                        return $aux;
                                    }
                                    return "";
                
                                }
                        ),
                        'options' => ['placeholder' => 'Seleccionar Usuario ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Usuario');
                ?>
            </div>
            <!-- ------------------------------------------------------------------------------------------------------------------------- -->

            <div class="col-md-5">

                <div class="input-group">
                    <?= $form->field($model, 'asignacion')->dropdownList(
                            ArrayHelper::map(Sds_com_configuracion::findBySql(
                                "select idconfiguracion, descripcion from sds_com_configuracion 
                                where idconfiguracion = 1 
                                or idconfiguraciontipo = " . Sds_com_configuracion_tipo::BDC_TIPO_EQUIPO . " and activo = 1 
                                order by descripcion"
                            )->all(), 'idconfiguracion', 'descripcion'),
                            ['id' => 'asignacion', 'prompt' => '', 'placeholder' => 'Seleccionar asignacion ...'],
                        )->label('Asignación')
                    ?>
                    <span class="input-group-btn" style="padding-top:27px;">
                            <?= Html::Button('+', [
                                'id' => 'boton_nueva_asignacion',
                                'class' => 'btn btn-primary',
                                'onclick' => 'js:MostrarDivNuevaConfiguracion("'.Sds_com_configuracion_tipo::BDC_TIPO_EQUIPO.'","Nueva Asignacion","asignacion","form_principal");'
                            ]);
                        ?>
                    </span>
                </div>
            </div>

        </div>
    <!-- ##################################################################################################################################################### -->
        <div class="row">
            <!-- ------------------------------------------------------------------------------------------------------------------------- -->
            <div class="col-md-4">
                <div class="input-group">
                
                    <?= $form->field($model, 'sistema_operativo')->dropdownList(
                            ArrayHelper::map(
                                Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_SISTEMA_OPERATIVO, false),
                                'idconfiguracion',
                                'descripcion'
                            ),
                            ['id' => 'sistema_operativo', 'prompt' => '', 'placeholder' => 'Seleccionar sistema operativo ...'],
                        )->label('Sistema Operativo')
                    ?>
                    <span class="input-group-btn" style="padding-top:27px;">
                        <?= Html::Button('+', [
                                'id' => 'boton_nuevo_sistema_operativo',
                                'class' => 'btn btn-primary',
                                'onclick' => 'js:MostrarDivNuevaConfiguracion("'.Sds_com_configuracion_tipo::TIPO_SISTEMA_OPERATIVO.'","Nuevo sistema operativo","sistema_operativo","form_principal");'
                            ]);
                        ?>
                    </span>
                </div>
            </div>
            <!-- ------------------------------------------------------------------------------------------------------------------------- -->
            <div class="col-md-4">
                <div class="input-group">
                    <?= $form->field($model, 'procesador')->dropdownList(
                            ArrayHelper::map(
                                Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_PROCESADOR, false),
                                'idconfiguracion',
                                'descripcion'
                            ),
                            ['id' => 'procesador', 'prompt' => '', 'placeholder' => 'Seleccionar procesador...'],
                        )->label('Procesador')
                    ?>
                
                    <span class="input-group-btn" style="padding-top:27px;">
                        <?= Html::Button('+', [
                                'id' => 'boton_nuevo_procesador',
                                'class' => 'btn btn-primary',
                                'onclick' => 'js:MostrarDivNuevaConfiguracion("'.Sds_com_configuracion_tipo::TIPO_PROCESADOR.'","Nuevo procesador","procesador","form_principal");'
                            ]);
                        ?>
                    </span>
                </div>
            </div>
            <!-- ------------------------------------------------------------------------------------------------------------------------- -->
            <div class="col-md-4">
                <div class="input-group">
                    <?= $form->field($model, 'memoria')->dropdownList(
                            ArrayHelper::map(
                                Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_MEMORIA, false),
                                'idconfiguracion',
                                'descripcion'
                            ),
                            ['id' => 'memoria', 'prompt' => '','placeholder' => 'Seleccionar memoria...'],
                        )->label('Memoria')
                    ?>
                    <span class="input-group-btn" style="padding-top:27px;">
                        <?= Html::Button('+', [
                                'id' => 'boton_nueva_memoria',
                                'class' => 'btn btn-primary',
                                'onclick' => 'js:MostrarDivNuevaConfiguracion("'.Sds_com_configuracion_tipo::TIPO_MEMORIA.'","Nueva memoria","memoria","form_principal");'
                            ]);
                        ?>
                    </span>
                </div>
            </div>
            <!-- ------------------------------------------------------------------------------------------------------------------------- -->
        </div>
    <!-- ##################################################################################################################################################### -->
    <div class="row">
            <!-- ------------------------------------------------------------------------------------------------------------------------- -->
            <div class="col-md-4">
                <div class="input-group">
                    <?= $form->field($model, 'disco')->dropdownList(
                            ArrayHelper::map(
                                Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_DISCO, false),
                                'idconfiguracion',
                                'descripcion'
                            ),
                            ['id' => 'disco', 'prompt' => '', 'placeholder' => 'Seleccionar disco...'],
                        )->label('Disco')
                    ?>
                    <span class="input-group-btn" style="padding-top:27px;">
                        <?= Html::Button('+', [
                                'id' => 'boton_nuevo_disco',
                                'class' => 'btn btn-primary',
                                'onclick' => 'js:MostrarDivNuevaConfiguracion("'.Sds_com_configuracion_tipo::TIPO_DISCO.'","Nuevo disco","disco","form_principal");'
                            ]);
                        ?>
                    </span>
                </div>
            </div>
            <!-- ------------------------------------------------------------------------------------------------------------------------- -->
            <div class="col-md-4">
                <div class="input-group">
                    <?= $form->field($model, 'conectividad')->dropdownList(
                            ArrayHelper::map(
                                Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_CONECTIVIDAD, false),
                                'idconfiguracion',
                                'descripcion'
                            ),
                            ['id' => 'conectividad', 'prompt' => '', 'placeholder' => 'Seleccionar conectividad...'],
                        )->label('Conectividad')
                    ?>
                    <span class="input-group-btn" style="padding-top:27px;">
                        <?= Html::Button('+', [
                                'id' => 'boton_nueva_conectividad',
                                'class' => 'btn btn-primary',
                                'onclick' => 'js:MostrarDivNuevaConfiguracion("'.Sds_com_configuracion_tipo::TIPO_CONECTIVIDAD.'","Nueva Conectividad","conectividad","form_principal");'
                            ]);
                        ?>
                    </span>
                </div>
            </div>

            <!-- ------------------------------------------------------------------------------------------------------------------------- -->
        </div>
    <!-- ##################################################################################################################################################### -->
    <!-- ------------------------------------------------------------------------------------------------------------------------- -->
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>
        </div>
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
    </div>
    <!-- ##################################################################################################################################################### -->

   

    

    

    


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<!-- El siguiente div inicia oculto y sirve para cargar nuevas configuraciones, 
es generico para cualquier tipo de configuracion -->
<div class="sds-reg-ip-form" id="nueva_configuracion">

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
                'onclick' => 'js:MostrarFormularioPrincipal("form_principal");'
            ]);
            ?>
        </div>
        <div class="col-md-2" style="padding-top:27px">
            <?= Html::Button('Guardar', [
                'id' => 'guardar_nueva_configuracion',
                'class' => 'btn btn-default',
                'onclick' => 'js:GuardarNuevaConfiguracion("form_principal");'
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

<script>

function MostrarFormularioPrincipal(id_form_principal) 
    {
        //esta funcion se llama al cancelar o al realizarse el guardado de una nueva configuracion
        //Oculta el div de carga de la nueva configuracion y muestra el principal del modelo
        $('#'+id_form_principal).show();
        $('#nueva_configuracion').hide();
        $("#btnGuardar").show();
        $("#btnCerrar").show();
    }

function MostrarDivNuevaConfiguracion(id_tipo, titulo, id_combobox,id_form_principal) 
    {
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
        $('#'+id_form_principal).hide();
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