<?php

namespace app\helpers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfiguracionTipo;
use yii\web\View; // Importa la clase View



class AppConfiguracionHtmlHelper
{

    
    /**
     * Genera un botón para el alta externa de una configuración.
     * También registra las funciones JavaScript necesarias para mostrar/ocultar el ABM.
     *
     * @param \yii\db\ActiveRecord $model El modelo actual del formulario (para la propiedad isNewRecord).
     * @param string $tipo El nombre de la constante en ConfiguracionTipo (ej. 'TIPO_DOCUMENTO').
     * @return string El HTML del botón.
     */
    public static function botonAltaConfiguracion($model, $tipo, $titulo, $idSelect2 = null)
    {

        // --- CSS personalizado para el botón ---
            Yii::$app->view->registerCss("
            .custon-bottom-add {
                margin-top: 26px;
                height: 34px;
                margin-left:-2px!important; 
                padding-top: 6px;
                padding-bottom: 6px;
        
            }

            .glyphicon{
                position:static!important;
            }
        ");
        // --- JavaScript Global para mostrar/ocultar el ABM de Configuración ---
        // Se registra una sola vez para toda la aplicación
        $js = <<<JS
            // Función para mostrar el ABM de configuración
            window.showConfigAbm = function(buttonElement, title) {
                $("#abm_configuracion").show();
                $("#abm_configuracion_content").load($(buttonElement).attr("value"), function() {
                    // Callback después de que el contenido se ha cargado.
                    // Aquí podrías reinicializar Select2 o DatePicker si el formulario cargado los usa.
                });
                $("#abm_configuracion_title").html(title);
                $("#btnGuardar").hide(); // Oculta el botón Guardar si no es del modal principal
                $("#btnCerrar").hide(); // Oculta el botón Cerrar si no es del modal principal
                $("#form_principal").hide(); // Oculta el formulario principal
                    // Guarda el idSelect2 en una variable global
                window.idSelect2ParaActualizar = $(buttonElement).data('idselect2');
            };

            // Función para ocultar el ABM de configuración
            window.hideConfigAbm = function() {
                $("#abm_configuracion").hide();
                $("#abm_configuracion_content").empty(); // Limpia el contenido del modal
                $("#abm_configuracion_title").html(""); // Limpia el título
                $("#btnGuardar").show(); // Muestra el botón Guardar del formulario principal
                $("#btnCerrar").show(); // Muestra el botón Cerrar del formulario principal
                $("#form_principal").show(); // Muestra el formulario principal
            };

            // Asignar evento al botón "Cerrar" del bloque de configuración
            $(document).on('click', '#btnCerrarConfiguracion', function() {
                    hideConfigAbm();
                });


                $('#abm_configuracion').on('click', '#btnGuardarConfiguracion', function () {
                    
                var form = $('#form_configuracion');
                var tipo = form.find('#configuracion-tipo').val(); // solo si necesitás enviar el tipo
                var combo_txt = '#' + window.idSelect2ParaActualizar;
                var combo_obj = $('#' + window.idSelect2ParaActualizar);
                $.ajax({
                    url: form.attr('action'),  // Usa el action del form
                    type: 'POST',
                    data: form.serialize(),
                    success: function (data) {
                        if (data.success==true) {
                            console.log('data: ',data);
                            console.log('data.id: ',data.id);
                            $.alert('Se guardó con éxito');
                            hideConfigAbm();

                            if (window.idSelect2ParaActualizar) {
                                console.log('id-combo: ' + window.idSelect2ParaActualizar);
                                console.log(combo_obj.length);
                                var newOption = new Option(data.text, data.id, true, true);
                                combo_obj.append(newOption).trigger('change');
                                
                            }
                            
                                                // Si se pasó el ID del select2, hacer trigger
                            
                            // hacer el triguer al combo, buscar pasarpor parametro el id por parametro al crear el boton.
                        } else {
                            $('#abm_configuracion_content').html(data); // muestra los errores
                        }
                    },
                    error: function () {
                        $.alert('Ocurrió un error al guardar');
                    }
                });
            });


            
            //$("#abm_configuracion_content").load('/configuracion/create_ext');
            // Asegura que las funciones están disponibles globalmente en el scope de la ventana.
            // Esto es importante para poder llamarlas desde el 'onclick' o desde otros scripts.
        JS;

        // Registra el JavaScript una sola vez. Usa un ID único para evitar duplicados.
        // POS_HEAD o POS_BEGIN son buenos lugares para funciones globales.
        Yii::$app->view->registerJs($js, View::POS_HEAD, 'configAbmFunctions');

        // Retorna el HTML del botón con la llamada a la nueva función
        return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
            'value' => Url::to(['/configuracion/create_ext', 'tipo' => $tipo]),
            'class' => 'btn btn-success btn-flat custon-bottom-add',
            'id' => 'btn_config_' . strtolower($tipo),
            //'style' => 'margin-top:27px',
            'tabIndex' => '-1',
            "disabled" => !$model->isNewRecord,
            'data-idselect2' => $idSelect2, // 👈 acá lo pasás
            // Llama a la función global 'showConfigAbm'
            'onclick' => 'showConfigAbm(this, ' . json_encode($titulo) . ');'
        ]);
    }

    

    public static function renderAbmContenedor($guardarId = 'btnGuardarConfiguracion', $cerrarId = 'btnCerrarConfiguracion')
    {
        return Html::tag(
            'div',
            Html::tag('h3', '', ['id' => 'abm_configuracion_title', 'style' => 'margin-top:8px;']) .
                Html::tag('div', '', ['id' => 'abm_configuracion_content']) .
                Html::tag(
                    'div',
                    Html::tag(
                        'div',
                        Html::button('Guardar', ['id' => $guardarId, 'class' => 'btn btn-primary', 'style' => 'margin: 5px;']),
                        ['style' => 'flex: 1; text-align: left;']
                    ) .
                        Html::tag(
                            'div',
                            Html::button('Cerrar', [
                                'id' => $cerrarId,
                                'class' => 'btn btn-default',
                                'onclick' => 'hideConfigAbm();',
                                'style' => 'margin: 5px;'
                            ]),
                            ['style' => 'flex: 1; text-align: right;']
                        ),
                    [
                        'class' => 'form-group',
                        'style' => 'display: flex; justify-content: space-between; margin-bottom: 10px;'
                    ]
                ),
            [
                'id' => 'abm_configuracion',
                'style' => 'display:none; border: 1px solid #ccc; padding: 0px 15px;'
            ]
        );
    }
}
