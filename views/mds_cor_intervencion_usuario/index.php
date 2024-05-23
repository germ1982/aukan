<?php

use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use kartik\editable\Editable;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-12">
            <div style="display:<?= ($model->compartido_con)->getTotalCount() == 0 ? "block" : "none" ?>">
                <hr>
                <p>
                    <b>Hasta el momento no se seleccionó ningún usuario para compartir la intervención. Solo es visible para el usuario que la creó.</b>
                </p>
                <hr>
            </div>
            <div id="ajaxCrudDatatable">
                <?= GridView::widget([
                    'id' => 'crud-datatable',
                    'dataProvider' => $model->compartido_con,
                    'pjax' => true,
                    'columns' => require(__DIR__ . '/_columns.php'),
                    'toolbar' => [
                        ['content' => Html::a(
                            '<i class="glyphicon glyphicon-plus"></i> Agregar',
                            Url::to(['/mds_cor_intervencion_usuario/create', 'id' => $model->idintervencion]),
                            ['data-pjax' => 0, 'role' => 'modal-remote', 'class' => 'btn btn-success']
                        )],
                    ],
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,
                    'panel' => [
                        'type' => 'default',
                        'heading' => '',
                        'after' => '<div class="clearfix"></div>',
                        'heading' => false
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>