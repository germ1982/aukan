<?php

use yii\helpers\Url;
use yii\helpers\Html;

function campo($titulo, $contenido, $esArchivo = false)
{
    // Si el campo es un archivo, se trata de una previsualización
    if ($esArchivo) {
        // Verificamos si existe un archivo adjunto
        if ($contenido) {
            $imagePath = Url::to('uploads/legajo_runneu/' . $contenido);

            // Determinamos el tipo de archivo (imagen o PDF)
            $fileExtension = pathinfo($contenido, PATHINFO_EXTENSION);

            // Si el archivo es una imagen, mostramos la imagen
            if (in_array($fileExtension, ['jpg', 'jpeg', 'gif', 'png'])) {
                $contenido = Html::img($imagePath, ['class' => 'file-preview-image', 'alt' => 'Imagen', 'title' => $contenido, 'width' => '100%', 'height' => '100%']);
            } elseif ($fileExtension === 'pdf') {
                // Mostrar el archivo PDF en un objeto
                $contenido = Html::tag('object', '', ['data' => $imagePath, 'type' => 'application/pdf', 'width' => '100%', 'height' => '470px']);
            } else {
                // Si es otro tipo de archivo, mostrar un enlace para descargar
                $contenido = Html::a('Descargar archivo', $imagePath, ['class' => 'btn btn-primary', 'target' => '_blank']);
            }
        } else {
            $contenido = 'No hay archivo adjunto.';
        }
    }

    echo "$contenido";
}

?>

<div class="runneu-legajo-view">

    <div class="row">
        <div class="col-md-12">
            <?= campo('archivo_adjunto', $model->archivo_adjunto, true) ?> <!-- Aquí pasamos 'true' para indicar que es un archivo -->
        </div>
    </div>
</div>
