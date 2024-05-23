<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_gis_capa_item */
?>
<div class="sds-gis-capa-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php
$this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyCCZFJd2nsxxLqz1w2hvwo5DcAyroXzdhg&libraries=places,drawing&callback');
$this->registerJsFile('@web/js/google_maps_zonas.js', ['depends' => 'yii\web\JqueryAsset']);
?>