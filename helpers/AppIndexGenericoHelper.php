<?php
// app/helpers/AppIndexGenericoHelper.php

namespace app\helpers;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;

class AppIndexGenericoHelper
{
    /**
     * Renderiza la vista de índice genérica completa (Header, Grid, Modal).
     *
     * @param \yii\web\View $view El objeto View ($this) de la vista.
     * @param string $title El título de la página.
     * @param string $customButtonsA HTML para botones adicionales en la barra de herramientas.
     * @param array $gridColumns El array de columnas de Kartic/Yii GridView.
     * @param object $dataProvider El DataProvider para la grilla.
     * @param object $searchModel El SearchModel para el filtro.
     * @return string El HTML completo renderizado.
     */
    public static function renderIndex($view, $title, $gridColumns, $dataProvider, $searchModel, $customButtonsA = '',$customButtonsB = '', $modalWidth = '600px',$tamañoLetra = '12px')
    {
        // 1. Configuración de Assets y Título (Indispensable)
        $view->title = $title;
        $view->params['breadcrumbs'][] = $title;

        $buttonsB = Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['role' => 'modal-remote', 'title' => 'Nuevo', 'class' => 'btn btn-default']) .
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']) .
                        '{toggleData}'.
                        '{export}' ;

        $buttonsB = $customButtonsB == '' ? $buttonsB : $customButtonsB;
        
        \johnitvn\ajaxcrud\CrudAsset::register($view);
        \app\assets\CommonIndexAsset::register($view);
        $view->registerCssFile('@web/css/css_index_views.css', ['depends' => [\app\assets\AppAsset::class]]); 

        //Inyección de CSS para Ancho y Centrado Fijo del modal(PX)
        // Usamos la clase 'modal-xl' como gancho (hook) para asegurar que el estilo personalizado se aplique.
        $view->registerCss("

            .custom-grid {
                font-size: {$tamañoLetra} !important;
            }

            .modal-lg { 
                /* Forzamos el ancho en PX y el centrado */
                max-width: {$modalWidth} !important; 
                width: {$modalWidth} !important; 
                margin: 1.75rem auto; 
            }
            
            .panel-body{
                padding-top: 5x!important;
            }
        ");

        // 2. Construcción de la Salida HTML (Todo el Index)
        $html = '<header class="page-header">';
        $html .= '<h2>' . $title . '</h2>';
        $html .= '<div class="right-wrapper pull-right">';
        $html .= '<ol class="breadcrumbs">';
        $html .= '<li><a href="' . Url::to(['/']) . '"><i class="neon fa fa-home"></i></a></li>';
        $html .= '<li><span>' . $title . '</span></li>';
        $html .= '</ol>';
        $html .= '<div class="sidebar-right-toggle"></div>';
        $html .= '</div></header>';

        $html .= '<div class="row"><div class="col-md-12 col-lg-12 col-xl-12"><section class="panel"><div class="panel-body">';

        // 3. Renderizado del GridView
        $html .= '<div id="ajaxCrudDatatable">';
        
        // Renderizar el GridView (usa la función de renderizado de Yii para interpretar el widget)
        $html .= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'custom-grid'],
            'filterModel' => $searchModel,
            'pjax' => false,
            'columns' => $gridColumns,
            'toolbar' => [
                [
                    'content' =>
                    '<div class="row">' .
                        '<div class="col-md-9"><div class="botones_a">' .
                            $customButtonsA . // Botones personalizados
                        '</div></div>' .
                        '<div class="col-md-3"><div class="botones_b">' .
                        $buttonsB.
                        '</div></div>' .
                    '</div>'
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => false,
            'panel' => ['type' => 'primary', 'heading' => false, 'after' => '<div class="clearfix"></div>'],
            
            // Lógica de rowOptions para la columna 'activo'
            'rowOptions' => function ($model) {
                // Verificamos si la propiedad 'activo' existe para evitar errores
                if (property_exists($model, 'activo') && $model->activo == 0) { 
                    return ['style' => 'background-color:#f8d7da; color:#721c24;'];
                }
                return [];
            },
        ]);
        
        $html .= '</div>'; // #ajaxCrudDatatable
        $html .= '</div></section></div></div>'; // .panel-body, .panel, .row

        // 4. Renderizado del Modal (fuera del cuerpo principal)

        // --- Lógica simplificada para ancho en porcentaje ---
        $modalOptions = [
            'tabindex' => false,
            // Aplicamos el ancho como estilo directo, asumiendo que es un porcentaje válido
            'style' => "width: {$modalWidth}; max-width: {$modalWidth}; margin-left: auto !important; margin-right: auto !important;"
        ];

        $html .= Modal::widget([
            "id" => "ajaxCrudModal",
            'options' => $modalOptions, // <-- Aquí aplicamos el ancho
            // Ya no usamos 'size' ya que estamos sobrescribiendo el ancho con 'style'
            //'size' => Modal::SIZE_LARGE,
            'size' => Modal::SIZE_LARGE, // <-- ¡CAMBIAR AQUÍ! Usamos SIZE_LARGE
            'clientOptions' => ['backdrop' => 'static'],
            "footer" => "",
        ]);

        return $html;
    }
}