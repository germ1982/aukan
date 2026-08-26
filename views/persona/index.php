<?php

use app\helpers\AppIndexGenericoHelper;

/** @var mixed $dataProvider */
/** @var mixed $searchModel */
$gridColumns = require(__DIR__ . '/_columns.php');
$customButtonsA = ""; // o define aquí tus botones HTML::a(...) para la izquierda si es necesario

$customButtonsB = ''; // o define aquí tus botones HTML::a(...) para la derecha si es necesario

$anchoModal = '1200px'; // Ancho del modal en PX
$tamañoLetra = '11px'; // Tamaño de letra para la grilla

// 2. Renderizar la vista completa
echo AppIndexGenericoHelper::renderIndex(
    $this,                  // Objeto View ($this)
    'Personas',      // Título
    $gridColumns,           // Columnas
    $dataProvider,          // DataProvider (viene del controlador)
    $searchModel,           // SearchModel (viene del controlador)
    $customButtonsA,
    $customButtonsB,
    $anchoModal,
    $tamañoLetra,
);

?>

<script>
    function actualizarRenaperConSpinner(idpersona) {
        // 1. Muestra tu loading al toque
        $('#loading').show();

        // 2. Llamada AJAX al controlador
        $.ajax({
            url: 'index.php?r=persona/actualizar_persona_renaper',
            type: 'GET', // o POST según cómo lo tengas en el controlador
            data: {
                idpersona: idpersona
            },
            dataType: 'json',
            success: function(response) {
                // 3. Oculta el loading
                $('#loading').hide();

                let gif = 'https://media4.giphy.com/media/v1.Y2lkPTc5MGI3NjExb3dmb3JhMG1rMG5jc28yN25lbWlodHpkejI4Z3pjNHgxemFiOHBzaSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/dlMIwDQAxXn1K/giphy.gif';

                if (response.respuesta == 2){gif ='https://media4.giphy.com/media/v1.Y2lkPTc5MGI3NjExY3c5a3gwdTMzbzk5amU3azJqM2ZyM29xenJjY3FuYXR3anFnNGRwdiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/kUFlw7XaGE36w/giphy.gif';}                

                if (response.respuesta == 3){gif ='https://media1.giphy.com/media/v1.Y2lkPTc5MGI3NjExYXk3eDZuanAycWwzdG1mZGhsZmFoNjAwa2d2Zjg5YW1pdmw0MDM5cCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/m12EDnP8xGLy8/giphy.gif';}

                // 4. Muestra tu alerta global con el título y contenido que devolvió el controlador
                
                mostrarAlerta(response.content, response.title, 'blue',function() {
                     if (response.respuesta == 2){location.reload();}
                },gif);

                
            },
            error: function() {
                $('#loading').hide();
                let gif = 'https://media3.giphy.com/media/v1.Y2lkPTc5MGI3NjExM21qNjNuejk3cGpyaHBicGU4Y3Y2MmUybTBrMnppb2xxNjQ4ZHpkaiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/YTzh3zw4mj1XpjjiIb/giphy.gif';
                mostrarAlerta('Ocurrió un error al conectar con el servidor.', 'Error', 'red',null, gif);
            }
        });
    }
</script>