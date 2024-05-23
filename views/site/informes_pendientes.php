<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_informeSearch;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$searchModel_inf = new Mds_org_informeSearch();
$searchModel_inf->visto = 1;
$dataProvider_inf = $searchModel_inf->search(Yii::$app->request->queryParams);
$dataProvider_inf->pagination->pageSize = 5;
$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
?>
<?php if ($dataProvider_inf->getCount() > 0) :?>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel-featured panel-featured-primary">
            <header class="panel-heading bg-default">
                <div class="panel-actions">

                </div>
                <h2 class="panel-title">Informes Sin Leer</h2>
            </header>
            <div class="panel-body">
                <div id="tblcapacitaciones">
                    <?= GridView::widget([
                        'id' => 'crud-datatable',
                        'dataProvider' => $dataProvider_inf,
                        'pjax' => false,
                        'columns' => [
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'idinforme',
                                'width' => '100px',
                                'filter' => false
                            ],
                            [
                                'attribute' => 'fecha',
                                'width' => '10%',
                                'value' => function ($model) {
                                    $fc = date_create($model->fecha);
                                    $fc = date_format($fc, 'd/m/Y');
                                    return $fc;
                                },
                                'options' => ['readonly' => true],
                                'filter' => false
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'idusuario',
                                'value' => function ($model) {
                                    $idusuario = $model->idusuario;
                                    if ($idusuario != null) {
                                        $user = Mds_seg_usuario::findOne($idusuario);
                                        $contacto = Mds_org_contacto::findOne($user->idcontacto);
                                        if($contacto) {
                                            $persona = Sds_com_persona::findOne($contacto->idpersona);
                                            if($persona){
                                                return $persona->nombre . ' ' . $persona->apellido;
                                            }
                                        }
                                    }
                                    return "";
                                },
                                'format' => 'raw',
                                'width' => '12%',
                                'filter' => false
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'tipo',
                                'value' => function ($model) {
                                    $idtipo = $model->tipo;
                                    if ($idtipo != null) {
                                        $tipo = Sds_com_configuracion::findOne($idtipo);
                                        return $tipo->descripcion;
                                    }
                                    return "";
                                },
                                'format' => 'raw',
                                'width' => '10%',
                                'filter' => false
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'asunto',
                                'width' => '20%',
                                'filter' => false
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'iddispositivo',
                                'value' => function ($model) {
                                    $iddispositivo = $model->iddispositivo;
                                    if ($iddispositivo != null) {
                                        $dispositivo = Mds_org_dispositivo::findOne($iddispositivo);
                                        $organismo = $dispositivo ? Mds_org_organismo::findOne($dispositivo->idorganismo):null;
                                        return $organismo ? $dispositivo->descripcion . " - " . $organismo->descripcion:'';
                                    }
                                    return "";
                                },
                                'format' => 'raw',
                                'width' => '32%',
                                'filter' => false
                            ],
                            [
                                'class' => 'kartik\grid\ActionColumn',
                                'dropdown' => false,
                                'header' => '',
                                'template' => '{view}',
                                'vAlign' => 'middle',
                                'width' => '3%',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        $url =  Url::to(['/mds_org_informe/view', 'id' => $model->idinforme]);
                                        return Html::a('<span class= "glyphicon glyphicon-eye-open"></span>', $url, ['data-pjax' => 0, 'role' => 'post', 'title' => 'Ver', 'data-toggle' => 'tooltip']);
                                    },
                                ],
                            ],
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
            </div>
        </section>
    </div>
<?php endif;?>