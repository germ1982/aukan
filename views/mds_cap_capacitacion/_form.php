<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use dosamigos\ckeditor\CKEditor;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_capacitacion */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
$idcontacto = Yii::$app->user->identity->idcontacto;
$permiso_global = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_CAP_GLOBAL . ")")->one();
$permiso_global = $permiso_global != null ? 1 : 0;

?>

<script>
    $('#nueva_configuracion').hide();
</script>

<div class="mds-cap-capacitacion-form" id="id_formulario_capacitacion">

    <?php $form = ActiveForm::begin(); ?>
    <!-- ------------------------------------------------------------------------------------------------------------------------ -->
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true])->label("Nombre") ?>
        </div>
        <div class="col-md-6">
           <?= $form->field($model, 'nombre_corto')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <!-- ------------------------------------------------------------------------------------------------------------------------ -->
    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'tematica')->dropdownList(
                ArrayHelper::map(
                    Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_CAP_TEMATICA, false),
                    'idconfiguracion',
                    'descripcion'
                ),
                ['id' => 'tematica', 'placeholder' => 'Seleccionar Temática ...']
            )->label('Temática')
            ?>
        </div>
        <div class="col-md-1" style="padding-top:27px">
            <?= Html::Button('+', [
                'id' => 'boton_nueva_tematica',
                'class' => 'btn btn-default',
                'onclick' => 'js:MostrarDivNuevaConfiguracion("42","Nueva Temática","tematica","id_formulario_capacitacion");'
            ]);
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, $interno ? 'idorganismo' : 'idorganismoexterno')->widget(Select2::classname(), [
                'data' => $filterOrganismos
            ])->label('Organismo');
            ?>
        </div>
    </div>
    <!-- ------------------------------------------------------------------------------------------------------------------------ -->
    <?= $form->field($model, 'detalle')->widget(CKEditor::className(), [
        'options' => [
            'rows' => 6
        ],
        'preset' => 'custom',
        'clientOptions' => [
            /* 'toolbarGroups' => [
                                      ['name' => 'document', 'groups' => ['mode', 'document', 'doctools' ]],
                                      ['name' => 'clipboard', 'groups' => ['clipboard', 'undo' ]],
                                      ['name' => 'editing', 'groups' => ['find', 'selection', 'spellchecker' ]],
                                      //'/',
                                      ['name' => 'basicstyles', 'groups' => ['basicstyles' ]],
                                      ['name' => 'paragraph', 'groups' => ['list', 'indent', 'blocks', 'align', 'bidi' ]],
                                      //['name' => 'links'],
                                      // ['name' => 'insert', 'groups' => ['table', 'horizontalrule', 'specialchar' ]],
                                      //'/',
                                      ['name' => 'styles'],
                                      ['name' => 'colors'],
                                      ['name' => 'tools'],
                                      ['name' => 'others']
                                      ], */
            'toolbar' => [
                [
                    'name' => 'row1',
                    'items' => [
                        //'Source', '-',
                        'Bold', 'Italic', 'Underline', 'Strike', '-',
                        'Subscript', 'Superscript', 'RemoveFormat', '-',
                        'TextColor', 'BGColor', '-',
                        'NumberedList', 'BulletedList', '-',
                        //'Outdent', 'Indent', '-', 'Blockquote', '-',
                        'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                        'Table', 'HorizontalRule', 'SpecialChar', '-',
                        'Undo', 'Redo', 'SelectAll', '-',
                        'NewPage', 'Print', 'Templates', '-',
                        'ShowBlocks', '-',
                        'Maximize',
                        'Link',
                        // 'Link', 'Unlink', 'Anchor', '-',
                    ],
                ],
                [
                    'name' => 'row2',
                    'items' => [
                        //'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-',

                        'Format', 'Font', 'FontSize', 'Styles',
                    ],
                ],
            ],
        ],
    ])
    ?>
    <?= $form->field($model, 'perfil')->widget(CKEditor::className(), [
        'options' => [
            'rows' => 2,
        ],
        'preset' => 'custom',
        'clientOptions' => [
            /* 'toolbarGroups' => [
                                      ['name' => 'document', 'groups' => ['mode', 'document', 'doctools' ]],
                                      ['name' => 'clipboard', 'groups' => ['clipboard', 'undo' ]],
                                      ['name' => 'editing', 'groups' => ['find', 'selection', 'spellchecker' ]],
                                      //'/',
                                      ['name' => 'basicstyles', 'groups' => ['basicstyles' ]],
                                      ['name' => 'paragraph', 'groups' => ['list', 'indent', 'blocks', 'align', 'bidi' ]],
                                      //['name' => 'links'],
                                      // ['name' => 'insert', 'groups' => ['table', 'horizontalrule', 'specialchar' ]],
                                      //'/',
                                      ['name' => 'styles'],
                                      ['name' => 'colors'],
                                      ['name' => 'tools'],
                                      ['name' => 'others']
                                      ], */
            'toolbar' => [
                [
                    'name' => 'row1',
                    'items' => [
                        //'Source', '-',
                        'Bold', 'Italic', 'Underline', 'Strike', '-',
                        'Subscript', 'Superscript', 'RemoveFormat', '-',
                        'TextColor', 'BGColor', '-',
                        'NumberedList', 'BulletedList', '-',
                        //'Outdent', 'Indent', '-', 'Blockquote', '-',
                        'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                        'Table', 'HorizontalRule', 'SpecialChar', '-',
                        'Undo', 'Redo', 'SelectAll', '-',
                        'NewPage', 'Print', 'Templates', '-',
                        'ShowBlocks', '-',
                        'Maximize',
                        'Link',
                        // 'Link', 'Unlink', 'Anchor', '-',
                    ],
                ],
                [
                    'name' => 'row2',
                    'items' => [
                        //'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-',

                        'Format', 'Font', 'FontSize', 'Styles',
                    ],
                ],
            ],
        ],
    ])
    ?>
    <?= $form->field($model, 'objetivos')->widget(CKEditor::className(), [
        'options' => [
            'rows' => 4
        ],
        'preset' => 'custom',
        'clientOptions' => [
            /* 'toolbarGroups' => [
                                      ['name' => 'document', 'groups' => ['mode', 'document', 'doctools' ]],
                                      ['name' => 'clipboard', 'groups' => ['clipboard', 'undo' ]],
                                      ['name' => 'editing', 'groups' => ['find', 'selection', 'spellchecker' ]],
                                      //'/',
                                      ['name' => 'basicstyles', 'groups' => ['basicstyles' ]],
                                      ['name' => 'paragraph', 'groups' => ['list', 'indent', 'blocks', 'align', 'bidi' ]],
                                      //['name' => 'links'],
                                      // ['name' => 'insert', 'groups' => ['table', 'horizontalrule', 'specialchar' ]],
                                      //'/',
                                      ['name' => 'styles'],
                                      ['name' => 'colors'],
                                      ['name' => 'tools'],
                                      ['name' => 'others']
                                      ], */
            'toolbar' => [
                [
                    'name' => 'row1',
                    'items' => [
                        //'Source', '-',
                        'Bold', 'Italic', 'Underline', 'Strike', '-',
                        'Subscript', 'Superscript', 'RemoveFormat', '-',
                        'TextColor', 'BGColor', '-',
                        'NumberedList', 'BulletedList', '-',
                        //'Outdent', 'Indent', '-', 'Blockquote', '-',
                        'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'list', 'indent', 'blocks', 'align', 'bidi', '-',
                        'Table', 'HorizontalRule', 'SpecialChar', '-',
                        'Undo', 'Redo', 'SelectAll', '-',
                        'NewPage', 'Print', 'Templates', '-',
                        'ShowBlocks', '-',
                        'Maximize',
                        'Link',
                        // 'Link', 'Unlink', 'Anchor', '-',
                    ],
                ],
                [
                    'name' => 'row2',
                    'items' => [
                        //'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-',

                        'Format', 'Font', 'FontSize', 'Styles',
                    ],
                ],
            ],
        ],
    ])
    ?>
    <!-- ------------------------------------------------------------------------------------------------------------------------ -->

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

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
                'onclick' => 'js:MostrarFormularioPrincipal("id_formulario_capacitacion");'
            ]);
            ?>
        </div>
        <div class="col-md-2" style="padding-top:27px">
            <?= Html::Button('Guardar', [
                'id' => 'guardar_nueva_configuracion',
                'class' => 'btn btn-default',
                'onclick' => 'js:GuardarNuevaConfiguracion("id_formulario_capacitacion");'
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
    function MostrarFormularioPrincipal(id_form_principal) {
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