<?php

use app\helpers\AppIndexGenericoHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\data\ArrayDataProvider $dataProvider */

$boton_volver = Html::a(
    '<i class="fas fa-arrow-left"></i> Volver a Inventario',
    ['inventario/index'],
    ['title' => 'Volver a Inventario', 'class' => 'btn btn-primary boton_menu neon']
);

// Filtros apilados
$filtros_html = '<div style="display: inline-block; vertical-align: middle; margin-left: 15px;">'
    . '<label class="active" style="display: block; margin-bottom: 2px; font-weight: normal; font-size: 11px;">'
        . Html::checkbox('filtro_con_red', true, ['id' => 'chk-con-propiedad']) 
        . ' Con propiedades'
    . '</label>'
    . '<label style="display: block; margin-bottom: 0; font-weight: normal; font-size: 11px;">'
        . Html::checkbox('filtro_sin_red', false, ['id' => 'chk-sin-propiedad']) 
        . ' Sin propiedades'
    . '</label>'
. '</div>';

$customButtonsA = $boton_volver . $filtros_html;
$customButtonsB = '';

$gridColumns = [
    [
        'attribute' => 'descripcion',
        'label' => 'Tipo de Artículo',
    ],
    [
        'label' => 'Admite Red',
        'format' => 'raw',
        'contentOptions' => ['class' => 'text-center', 'style' => 'width: 150px;'],
        'headerOptions' => ['class' => 'text-center'],
        'value' => function ($model) {
            return Html::checkbox('check_red', (bool)$model->tiene_red, [
                'class' => 'check-articulo-propiedad',
                'data-id' => $model->id_configuracion,
                'data-tipo' => 'red',
            ]);
        },
    ],
    [
        'label' => 'Es CPU',
        'format' => 'raw',
        'contentOptions' => ['class' => 'text-center', 'style' => 'width: 150px;'],
        'headerOptions' => ['class' => 'text-center'],
        'value' => function ($model) {
            return Html::checkbox('check_cpu', (bool)$model->tiene_cpu, [
                'class' => 'check-articulo-propiedad',
                'data-id' => $model->id_configuracion,
                'data-tipo' => 'cpu',
            ]);
        },
    ],
];

echo AppIndexGenericoHelper::renderIndex(
    $this,
    'Tipos de Artículo Con Propiedades Especiales',
    $gridColumns,
    $dataProvider,
    null,
    $customButtonsA,
    $customButtonsB,
    '1200px',
    '10px'
);

$urlToggleRed = Url::to(['toggle_articulo_red']);
$urlToggleCpu = Url::to(['toggle_articulo_cpu']);

$js = <<<JS

// Filtro visual de la tabla
function filtrarTabla() {
    let verConPropiedad = $('#chk-con-propiedad').is(':checked');
    let verSinPropiedad = $('#chk-sin-propiedad').is(':checked');

    $('#crud-datatable tbody tr').each(function() {
        let tieneRed = $(this).find('input[data-tipo="red"]').is(':checked');
        let tieneCpu = $(this).find('input[data-tipo="cpu"]').is(':checked');
        let tieneAlgunaPropiedad = tieneRed || tieneCpu;

        if ((tieneAlgunaPropiedad && verConPropiedad) || (!tieneAlgunaPropiedad && verSinPropiedad)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

filtrarTabla();

$('#chk-con-propiedad, #chk-sin-propiedad').on('change', function() {
    filtrarTabla();
});

// Guardado AJAX dinámico según la propiedad (red o cpu)
$('.check-articulo-propiedad').on('change', function() {
    let chk = $(this);
    let idConfig = chk.data('id');
    let tipoPropiedad = chk.data('tipo'); // 'red' o 'cpu'
    let estado = chk.is(':checked') ? 1 : 0;
    
    let urlDestino = (tipoPropiedad === 'red') ? '{$urlToggleRed}' : '{$urlToggleCpu}';

    $.ajax({
        url: urlDestino,
        type: 'POST',
        data: {
            id_configuracion: idConfig,
            estado: estado,
            _csrf: yii.getCsrfToken()
        },
        success: function(res) {
            if (!res.success) {
                alert('Error al actualizar.');
                chk.prop('checked', !estado);
            } else {
                let titulo = 'Exelente!!!';
                let mensaje = '';
                let gifUrl = '';

                // Clave combinada para el switch (ej: 'red_1', 'cpu_0')
                switch (tipoPropiedad + '_' + estado) {
                    case 'red_1':
                        mensaje = 'Ahora el tipo de artículo tiene caracteristicas de red.';
                        gifUrl = 'https://c.tenor.com/hARuxJoPe3kAAAAd/tenor.gif'; // Matrix / I'm in
                        break;

                    case 'red_0':
                        titulo = 'ATENCION!!!';
                        mensaje = 'Se quitaron caracteristicas de red al tipo de artículo.';
                        gifUrl = 'https://media1.tenor.com/m/0vwMYlZPj3sAAAAd/internet.gif'; // Travolta sin red
                        break;

                    case 'cpu_1':
                        mensaje = 'Ahora el tipo de artículo tiene caracteristicas de CPU / Micro / Ram / disco.';
                        gifUrl = 'https://media1.tenor.com/m/2tCoZJfgj8UAAAAd/hello-old-people.gif'; // GIF de PC / Gamer / Hardware
                        break;

                    case 'cpu_0':
                        titulo = 'ATENCION!!!';
                        mensaje = 'Se quitaron caracteristicas de CPU al tipo de artículo.';
                        gifUrl = 'https://media1.tenor.com/m/IMbftEksSEsAAAAd/trash.gif'; // GIF rompiendo/desconectando la PC
                        break;
                }
                
                let cartel = '<div style="text-align:center"><h2>' + titulo + '</h2></div><br><div style="text-align:center"><img src="' + gifUrl + '" alt="gif" style="width:150px; height:100px;"><br><h4>' + mensaje + '</h4></div>';
                
                $.alert({
                    title: '',
                    content: cartel,
                    type: 'blue',
                });
                
                filtrarTabla();
            }
        },
        error: function() {
            alert('Error de comunicación con el servidor.');
            chk.prop('checked', !estado);
        }
    });
});
JS;
$this->registerJs($js);
?>