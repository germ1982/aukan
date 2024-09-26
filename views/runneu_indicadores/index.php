<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use johnitvn\ajaxcrud\CrudAsset;

$this->title = 'Indicadores Runneu';
$this->params['breadcrumbs'][] = $this->title;
$clase = 'menu-index';

CrudAsset::register($this);
?>

<style>
    .custom-grid {
    font-size: 13px; /* Cambia el tamaño según tus necesidades */
}

.kv-grid-toolbar .btn {
    height: 30px;  /* Ajusta la altura de todos los botones */
    line-height: 1.42857143;  /* Esto centra el contenido verticalmente */
}


    body {
        background-color: #121212;
        color: #00bfff;
        font-family: Arial, sans-serif;
    }

    .caja_indicador {
        background: rgba(0, 0, 0, 0.8);
        border: 2px solid #00bfff;
        border-radius: 10px;
        padding: 10px;
        padding-top: 10px;
        box-shadow: 0 0 20px #00bfff;
        margin: 10px !important;
        margin-top: 20px !important;
    }
</style>





<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="neon fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>


<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6 ">
        <div class="caja_indicador">
            <h4>Indicador 1:</h4>
            <p>Descripción del Indicador 3. Este indicador analiza...</p>
            <?= Html::button('Ver Datos', [
                'class' => 'btn btn-primary',
                'onclick' => new JsExpression("cargarDatos('1')")
            ]) ?>
        </div>

    </div>

    <div class="col-md-6 col-lg-6 col-xl-6 ">
        <div class="caja_indicador">
            <h4>Indicador 2:</h4>
            <p>Descripción del Indicador 3. Este indicador analiza...</p>
            <?= Html::button('Ver Datos', [
                'class' => 'btn btn-primary',
                'onclick' => new JsExpression("cargarDatos('1')")
            ]) ?>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6 ">

        <div class="caja_indicador">
            <h4>Indicador 2:</h4>
            <p>Descripción del Indicador 3. Este indicador analiza...</p>
            <?= Html::button('Ver Datos', [
                'class' => 'btn btn-primary',
                'onclick' => new JsExpression("cargarDatos('1')")
            ]) ?>
        </div>

    </div>
    <div class="col-md-6 col-lg-6 col-xl-6 ">

        <div class="caja_indicador">
            <h4>Indicador 2:</h4>
            <p>Descripción del Indicador 3. Este indicador analiza...</p>
            <?= Html::button('Ver Datos', [
                'class' => 'btn btn-primary',
                'onclick' => new JsExpression("cargarDatos('1')")
            ]) ?>
        </div>

    </div>
</div>


<?php
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        });
        
    function cargarDatos(indicadorId) {
        $.ajax({
            url: '" . Url::to(['runneu-indicadores/cargar-datos']) . "',
            type: 'POST',
            data: { id: indicadorId },
            success: function (data) {
                $('#modalContent').html(data);
                $('#ajaxCrudModal').modal('show');
            }
        });
    }"
);
?>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<div id="modalContent"></div>
<?php Modal::end(); ?>