<?php

namespace app\helpers;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use Yii;

class AppBuscarPersonaHelper
{
    public static function widgetBuscarPersona(
        $model,
        $attributeIdPersona = 'idpersona',
        $label =  null,
        $col_input = 4,
        $col_texto = 8
    ) {
        $view = Yii::$app->view;
        $label = $label == null ? $model->getAttributeLabel($attributeIdPersona) : $label;
        $nombreDivMensaje = 'txt_mensaje_' . $attributeIdPersona; //es el id del div donde se muestra el nombre de la persona
        $inputIdPersona = 'input_' . $attributeIdPersona; //es el id del input hidden que tiene el idpersona
        $inputDocumento = 'input_documento_' . $attributeIdPersona; //es el id del input donde se escribe el dni
        $btnId = 'btn_dni_' . $attributeIdPersona; //es el id del boton de buscar dni
        $personaNombre = '';
        $funcionAsignarDatos = 'asignar_datos_' . $attributeIdPersona; //nombre de la funcion js que asigna los datos a los campos del formulario

        // Registrar JavaScript directamente desde el helper
        $js = <<<JS

                function get_datos_persona(inputDocumentoId, inputIdPersonaId, mensajeDivId, funcionAsignarDatos) {
                    console.log('ingreso a get_datos_persona');
                    console.log('funcion:' + funcionAsignarDatos);
                    $('#loading').show();
                    $('#' + inputIdPersonaId).val('0');//es el input hidden que tiene el idpersona

                    let dni_persona = $("#" + inputDocumentoId).val(); //input donde se escribe el dni  

                    if (dni_persona === "") {
                        texto = '<div style="text-align:center"><h2>AH!!! AH!!! AH!!!</h2></div><br><div style="text-align:center"><img src="https://c.tenor.com/0bn7ZRzdNpkAAAAd/nope-not-a-chance.gif" alt="gif de algo" style="width:150px; height:100px;"><br><h4>Debe escribir un numero de documento!!!</h4></div>';
                        $.alert({
                            title: '',
                            content: texto,
                            type: 'orange',
                        });
                        $('#loading').hide();
                        return;
                        
                    }

                    console.log('Buscando persona con dni: ' + dni_persona);
                    $('#' + mensajeDivId).html("Buscando datos de Persona con DNI " + dni_persona);
                    $.post("index.php?r=persona/get_persona&dni=" + dni_persona, function (data) {
                    //$.post("index.php?r=persona/get_persona_renaper&dni=" + dni_persona + "&genero=F", function (data) {
                        console.log('data de get_persona:');
                        console.log(data);

                        if (data) {
                            $('#' + inputIdPersonaId).val(data['idpersona']);
                            let nombreCompleto = data['apellido'] + ', ' + data['nombre'];
                            $('#' + mensajeDivId).html(nombreCompleto);
                            if (typeof window[funcionAsignarDatos] === 'function' && funcionAsignarDatos != '') {
                                window[funcionAsignarDatos](data); // Llama a la función pasada como parámetro
                            } else {
                                console.warn('La función ' + funcionAsignarDatos + ' no está definida.');
                            }
                        } else {
                            $('#' + mensajeDivId).html("No se encontraron datos de Persona con DNI " + dni_persona + " en SUR ni RENAPER");
                        }
                        $('#loading').hide();
                    });
                     
                }

                function ValidarIngresoDni(inputDocumentoId, inputIdPersonaId, mensajeDivId,funcionAsignarDatos) {
                    
                    if (event.which === 13) {
                        get_datos_persona(inputDocumentoId, inputIdPersonaId, mensajeDivId,funcionAsignarDatos);
                    }
                }

                function get_dni(idpersona){
                    console.log('ingreso a get_dni con: ' + idpersona);
                    var dni = '';
                    $.ajax({
                    url: "index.php?r=persona/get_dni&idpersona=" + idpersona, //php que recibe la peticion
                    type: 'post',
                    async: false,
                        success: function(data) {
                            console.log(data);
                            dni = data;
                        }
                    });
                    return dni;
                }
        JS;

        $view->registerJs($js);

        // Comenzar el HTML del widget
        $html = Html::activeHiddenInput($model, $attributeIdPersona, ['id' => $inputIdPersona]);

        $html .= '<div class="row">
                    <div class="col-md-' . $col_input . '">
                        <div class="input-group">';

        $html .= '<div class="form-group">'; // Contenedor del campo (similar al que crea field())
        $html .= Html::label($label, $inputDocumento); // Para que el label se asocie al input por 'id'
        $html .= Html::textInput(
            $inputDocumento, // Nombre del input (podrías usar null si solo quieres el id)
            null,            // Valor inicial (vacío)
            [
                'id' => $inputDocumento,
                'class' => 'form-control', // Clase de Bootstrap para estilos
                //'onkeyup' => 'ValidarIngresoDni(' . $inputDocumento . ', ' . $inputIdPersona . ', ' . $nombreDivMensaje . ');'
                'onkeyup' => "ValidarIngresoDni('$inputDocumento', '$inputIdPersona', '$nombreDivMensaje','$funcionAsignarDatos');",
            ]
        );
        $html .= '</div>'; // Cierre del form-group

        $html .= '<span class="input-group-btn" style="padding-top:27px;">';
        $html .= Html::a('<i class="glyphicon glyphicon-search"></i>', null, [
            'id' => $btnId,
            'class' => 'btn btn-primary',
            'title' => 'Buscar DNI',
            'onclick' => "get_datos_persona('$inputDocumento', '$inputIdPersona', '$nombreDivMensaje','$funcionAsignarDatos'); return false;",
            //'style' => 'padding: 6px 12px!important;',
        ]);
        $html .= '</span>
                        </div>
                    </div>';

        $html .= '<div class="col-md-' . $col_texto . '" style="padding-top:30px;" id="' . $nombreDivMensaje . '">' . $personaNombre . '</div>';
        $html .= '</div>';

        return $html;
    }
}
