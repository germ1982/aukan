<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_org_padron;
use app\models\Sds_com_periodo;
use app\models\Sds_com_persona;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_hor_asistencia_reporteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reporte de Asistencias';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

//Verifico que el usuario tenga idusuario asignado, caso contrario redirecciono a Login
$user = Yii::$app->user->identity;
$idusuario = $user != null ? $user->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
?>

<style>
    .elect2-container {
        width: 60% !important;
    }

    .field-cmb_desde .select2-container {
        width: 73% !important;
    }

    .field-cmb_hasta .select2-container {
        width: 73% !important;
    }
</style>

<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-hor-asistencia-reporte-search">
                    <?php
                    $form = ActiveForm::begin([
                        'action' => ['index', 'inasistencias' => $searchModel->inasistencias],
                        'method' => 'get',
                    ]);
                    $searchModel->desde = $searchModel->desde == -1 ? date('n/Y', strtotime("-1 month")) : $searchModel->desde;
                    ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="col-md-12 text-center" style="border-bottom: 1px solid #ede; margin-bottom:5px;">Periodo</div>
                            <div class="col-md-6">
                                <?= $form->field($searchModel, 'desde')->widget(Select2::class, [
                                    'data' => ArrayHelper::map(
                                        Sds_com_periodo::find()->all(),
                                        'periodo',
                                        'periodo'
                                    ),
                                    'options' => [
                                        'id' => 'cmb_desde',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => false
                                    ],
                                ]); ?>
                            </div>
                            <div class="col-md-6">
                                <?php $searchModel->hasta = $searchModel->hasta == -1 ? date('n/Y', strtotime("-1 month")) : $searchModel->hasta; ?>
                                <?= $form->field($searchModel, 'hasta')->widget(Select2::class, [
                                    'data' => ArrayHelper::map(
                                        Sds_com_periodo::find()->all(),
                                        'periodo',
                                        'periodo'
                                    ),
                                    'options' => [
                                        'id' => 'cmb_hasta',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => false
                                    ],
                                ]); ?>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <?php
                            echo  $form->field($searchModel, 'codContacto')->widget(Select2::class, [
                                'data' => ArrayHelper::map(
                                    Mds_org_contacto::findBySql(
                                        "SELECT c.*, p.* 
                                        FROM mds_org_contacto c
                                            INNER JOIN sds_com_persona p ON c.idpersona=p.idpersona
                                            INNER JOIN mds_org_dispositivo d ON c.iddispositivo=d.iddispositivo
                                            WHERE 
                                                c.legajo IS NOT NULL AND c.activo AND
                                                d.idcapaitem IN 
                                                    (SELECT ic.idcapaitem FROM mds_seg_usuario_capa_item ic WHERE idusuario=" . $idusuario . ") 
                                                    OR IFNULL((SELECT COUNT(ic.idusuario) FROM mds_seg_usuario_capa_item ic WHERE ic.idusuario=" . $idusuario . "), 0) = 0
                                            ORDER BY p.apellido, p.nombre"
                                    )->all(),
                                    'idcontacto',
                                    function ($searchModel) {
                                        return ($searchModel->legajo != null ? $searchModel->legajo : "00000") . " - " . $searchModel->apellido . ", " . $searchModel->nombre;
                                    }
                                ),
                                'options' => [
                                    'id' => 'cmb_contacto',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'placeholder' => 'Seleccionar Empleado...'
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="col-md-2">
                            <?= $form->field($searchModel, 'estado')->widget(Select2::class, [
                                'data' => [
                                    'Asistencia' => 'Asistencia', 
                                    'Día de Profilaxis' => 'Día de Profilaxis',
                                    'Franco' => 'Franco',
                                    'Inasistencia' => 'Inasistencia', 'Licencia' => 'Licencia',
                                    'Paro' => 'Paro',
                                    'Quite de Colaboración' => 'Quite de Colaboración',
                                    'Retención de Tareas' => 'Retención de Tareas',
                                ],
                                'options' => ['placeholder' => 'Seleccionar Estado ...', 'id' => 'cmb_estado'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ]
                            ]); ?>
                        </div>
                        <div class="col-md-3" style="padding-top:26px">
                            <div class="form-group">
                                <?php
                                $url =  Url::to([
                                    '/mds_hor_asistencia_reporte/reporte_asistencia',
                                    'idcontacto' => $searchModel->codContacto,
                                    'desde' => $searchModel->desde,
                                    'hasta' => $searchModel->hasta,
                                    'estado' => $searchModel->estado
                                ]);
                                echo Html::submitButton('Buscar', ['class' => 'btn btn-primary']) . ' ' .
                                    Html::a('<span class= "fas fa-print"></span> Ver Reporte', $url, [
                                        'class' => 'btn btn-default',
                                        'title' => "Imprimir",
                                        'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                                        'data-toggle' => 'tooltip',
                                    ]) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-md-12" style="padding-top: 25px;padding-bottom: 10px;">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if($dataProvider->getModels()){
                                $padron=Mds_org_padron::findBySql(
                                    "SELECT * FROM view_padron_max_periodo 
                                    WHERE legajo = ".$dataProvider->getModels()[0]->legajo
                                )->one();
                            }?>
                            <h4 style="text-align: center;">
                                <b><?=(isset($padron) && $padron!=null? "PR: $padron->pr | ":"")?></b>
                                <b><?=($dataProvider->getModels() ? $dataProvider->getModels()[0]->pr_categoria : "") ?></b>
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="mds-hor-asistencia-reporte-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                ['content' =>
                                Html::a(
                                    '<i class="glyphicon glyphicon-repeat"></i>',
                                    [''],
                                    ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']
                                ) .
                                    '{toggleData}' .
                                    '{export}' . '</div>'],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,
                            'panel' => [
                                'type' => 'primary',
                                'heading' => false,
                                'after' => '<div class="clearfix"></div>',
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>