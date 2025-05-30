<?php

namespace app\helpers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\ConfiguracionTipo;
use yii\web\View; // Importa la clase View

class AppHtmlHelper
{
    /**
     * Genera un botón para el alta externa de una configuración.
     * También registra las funciones JavaScript necesarias para mostrar/ocultar el ABM.
     *
     * @param \yii\db\ActiveRecord $model El modelo actual del formulario (para la propiedad isNewRecord).
     * @param string $tipo El nombre de la constante en ConfiguracionTipo (ej. 'TIPO_DOCUMENTO').
     * @return string El HTML del botón.
     */
    public static function botonAltaConfiguracion($model, $tipo,$titulo)
    {
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

            // Asegura que las funciones están disponibles globalmente en el scope de la ventana.
            // Esto es importante para poder llamarlas desde el 'onclick' o desde otros scripts.
        JS;

        // Registra el JavaScript una sola vez. Usa un ID único para evitar duplicados.
        // POS_HEAD o POS_BEGIN son buenos lugares para funciones globales.
        Yii::$app->view->registerJs($js, View::POS_HEAD, 'configAbmFunctions');

        // Retorna el HTML del botón con la llamada a la nueva función
        return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
            'value' => Url::to(['/sds_com_configuracion/create_ext', 'tipo' => $tipo]),
            'class' => 'btn btn-success btn-flat',
            'id' => 'btn_config_' . strtolower($tipo),
            'style' => 'margin-top:27px',
            'tabIndex' => '-1',
            "disabled" => !$model->isNewRecord,
            // Llama a la función global 'showConfigAbm'
            'onclick' => 'showConfigAbm(this,' . $titulo . ');'
        ]);
    }
}