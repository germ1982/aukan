<?php

use app\models\Mds_hor_registro;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

$usuario = Yii::$app->user->identity;
$query = Mds_hor_registro::find()->where("idcontacto=".$usuario->idcontacto)->limit(20)->orderBy(['fecha' => SORT_DESC]);
$dataProvider_rh = new ActiveDataProvider([
    'query' => $query,
    'pagination' => false
]);

?>
<?php if ($dataProvider_rh->getCount() > 0) : ?>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel-featured panel-featured-primary">
            <header class="panel-heading bg-default">
                <div class="panel-actions">

                </div>
                <h2 class="panel-title">Últimas fichadas</h2>
            </header>
            <div class="panel-body">
                <?= GridView::widget([
                    'id' => 'crud-datatable',
                    'dataProvider' => $dataProvider_rh,
                    'pjax' => false,
                    'columns' => [
                        [
                            'attribute' => 'fecha',
                            'width' => '100%',
                            'label' => 'Fecha y Hora',
                            'hAlign' => 'center',
                            'format' => 'html',
                            'value' => function ($model) {
                                $fc = date_create($model->fecha);
                                $fc_fecha = date_format($fc, 'd/m/Y');
                                $fc_hora = date_format($fc, 'H:i');
                                return '<b>' . $fc_fecha . '</b> - ' . $fc_hora;
                            },

                        ]
                    ],
                    'toolbar' => ['content' => null],
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,
                    'panel' => [
                        'before' => false,
                        'type' => 'default',
                        'heading' => false,
                        'after' => false,
                        'footer' => false
                    ]
                ]) ?>
            </div>
        </section>
    </div>
<?php endif; ?>