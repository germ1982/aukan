<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Efemerides */

$this->title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Efemérides', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Consultar fotos asociadas a esta efeméride ordenadas
$fotos = (new \yii\db\Query())
    ->from('efemerides_fotos')
    ->where(['idefemeride' => $model->idefemeride])
    ->orderBy(['orden' => SORT_ASC])
    ->all();
?>

<div class="efemerides-view" style="background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">

    <!-- Cabecera: Fecha y Título -->
    <div class="row">
        <div class="col-md-12">
            <div style="margin-bottom: 10px;">
                <span class="text-muted" style="font-size: 14px;">
                    <i class="glyphicon glyphicon-calendar"></i> Fecha: <strong><?= Html::encode($model->fecha) ?></strong>
                </span>
                <span class="pull-right label <?= $model->activo ? 'label-success' : 'label-danger' ?>" style="font-size: 12px; padding: 5px 10px;">
                    <?= $model->activo ? 'Activa' : 'Inactiva' ?>
                </span>
            </div>
            <h2 style="margin-top: 0; font-weight: bold; color: #333;"><?= Html::encode($model->titulo) ?></h2>
            <hr style="margin-top: 10px; margin-bottom: 20px;">
        </div>
    </div>

    <!-- Descripción -->
    <div class="row" style="margin-bottom: 25px;">
        <div class="col-md-12">
            <h4 style="font-weight: bold; color: #555; margin-bottom: 10px;">Descripción</h4>
            <div class="well" style="background-color: #fcfcfc; padding: 15px; border-radius: 4px; border: 1px solid #e3e3e3; font-size: 14px; line-height: 1.6;">
                <?= nl2br(Html::encode($model->descripcion)) ?>
            </div>
        </div>
    </div>

    <!-- Galería de Fotos en 4 Columnas -->
    <div class="row">
        <div class="col-md-12">
            <h4 style="font-weight: bold; color: #555; margin-bottom: 15px;">Galería de Fotos</h4>
            <?php if (!empty($fotos)): ?>
                <div class="row">
                    <?php foreach ($fotos as $f): ?>
                        <div class="col-md-3 col-sm-6" style="margin-bottom: 20px;">
                            <div class="thumbnail" style="padding: 5px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                <a href="<?= Url::base(true) . '/img/efemerides/' . $f['foto'] ?>" class="img-thumbnail-click" title="Hacé clic para ampliar">
                                    <img src="<?= Url::base(true) . '/img/efemerides/' . $f['foto'] ?>" style="height: 140px; object-fit: cover; width: 100%; border-radius: 3px;">
                                </a>
                                <div class="caption text-center" style="padding: 5px 0 0 0;">
                                    <span style="font-size: 11px; color: #777;">Orden: <?= $f['orden'] ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">No hay fotos registradas para esta efeméride.</p>
            <?php endif; ?>
        </div>
    </div>

</div>
</div> <!-- Cierre del div principal .efemerides-view -->

<!-- Overlay simple para ver la foto en grande (sin modales anidados) -->
<div id="lightbox-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); z-index:99999; text-align:center; padding-top:5vh;">
    <span id="lightbox-close" style="position:absolute; top:20px; right:35px; color:#fff; font-size:40px; font-weight:bold; cursor:pointer;">&times;</span>
    <img id="img-enlarged" src="" style="max-width:90%; max-height:85vh; border-radius:4px; box-shadow:0 5px 25px rgba(0,0,0,0.8);">
</div>

<?php
$js = "
// Abrir imagen en grande con overlay limpio
$('.img-thumbnail-click').click(function(e) {
    e.preventDefault();
    var src = $(this).attr('href');
    $('#img-enlarged').attr('src', src);
    $('#lightbox-overlay').fadeIn(200);
});

// Cerrar al hacer clic en la cruz o fuera de la imagen
$('#lightbox-close, #lightbox-overlay').click(function(e) {
    if(e.target !== this && e.target.id !== 'lightbox-close') return;
    $('#lightbox-overlay').fadeOut(200);
});
";
$this->registerJs($js);
?>