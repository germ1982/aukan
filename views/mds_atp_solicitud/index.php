<?php

use app\models\Mds_atp_solicitud;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_atp_solicitudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tarjeta ATPCen';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
$solicitudes = Mds_atp_solicitud::find()->where("foto_dni is not null and foto_dni like '%base64%'")->limit(5)->all();
$mostrar_migrar = $idusuario == 1 && !empty($solicitudes);
?>
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
                <div class="mds-atp-solicitud-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                [
                                    'content' => ($mostrar_migrar ? Html::a(
                                        '<i class="fas fa-sync-alt"></i> Migrar DNIs',
                                        null,
                                        ['title' => 'Migrar imágenes de DNI', 'class' => 'btn btn-default', 'id' => 'btnMigrar']
                                    ) : "") .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-plus"></i>',
                                            ['create'],
                                            ['role' => 'modal-remote', 'title' => 'Crear nueva Solicitud Tarjeta ATPCen', 'class' => 'btn btn-default']
                                        ) .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
                                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Recargar Tabla']
                                        ) .
                                        '{toggleData}' .
                                        '{export}'
                                ],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,
                            'panel' => [
                                'type' => 'default',
                                'heading' =>  '',
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
<?php Modal::end();
$script = <<<  JS
$('#btnMigrar').click(function() {
        $("#btnMigrar").html("<i class=\"fas fa-spinner fa-pulse\"></i>");
        $("#btnMigrar").prop("disabled", true);
        $.post("index.php?r=mds_atp_solicitud/migrar_dni", function(data) {
            console.log(data);            
            if (data.length>0){
                $("#btnMigrar").html("<i class=\"fas fa-sync-alt\"></i> Migrar DNIs");
                $("#btnMigrar").prop("disabled", false);
                $("#btnMigrar").show();
            }
            else {
                $("#btnMigrar").hide();
            }
        });
    });
JS;

$this->registerJs($script);

?>