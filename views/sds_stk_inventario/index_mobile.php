<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;

CrudAsset::register($this);

?>
<style>
    #ajaxCrudModal {
        left: 0px;
        top: -10px;
    }

    #ajaxCrudModal .modal-dialog {
        width: 100%;
    }

    @media (max-width: 425px) {
        #ajaxCrudModal {
            left: 0px;
            top: 0px;
            width: 100%;
        }

        #ajaxCrudModal .modal-dialog {
            width: 100%;
        }
    }
</style>

<div id="ajaxCrudDatatable">
    <?= Html::a('', ['create', 'celular' => true], ['role' => 'modal-remote', 'id' => 'botoncito']) ?>
</div>

<?php
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            window.location.href = 'index.php?r=site%2Findex';
        })"
);
?>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'class' => 'fade modal in',
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<?php
$script = <<<  JS
$(document).ready(function() {
    $("#botoncito").trigger("click");
});

JS;
$this->registerJs($script);

?>