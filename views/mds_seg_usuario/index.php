<?php

use app\models\Mds_seg_usuario;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_seg_usuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
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

$usuarios = Mds_seg_usuario::find()->where("pass not like '%pbkdf2_sha256%'")->orderBy(["idusuario" => SORT_DESC])->limit(1000)->all();
$mostrar_hash_pass = $idusuario == 1 && !empty($usuarios);

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
                <div class="mds-seg-usuario-index table-responsive">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable-usuarios',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => true,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                [
                                    'content' => ($mostrar_hash_pass ? Html::a(
                                        '<i class="fas fa-sync-alt"></i> Hash the Pass!',
                                        null,
                                        ['title' => 'a Hashear las Pass Mágicamente!', 'class' => 'btn btn-default', 'id' => 'btnHashPass']
                                    ) : "") .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-plus"></i>',
                                            ['create'],
                                            ['role' => 'modal-remote', 'title' => 'Nuevo Usuario', 'class' => 'btn btn-default']
                                        ) .
                                        Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            [''],
                                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                                        ) .
                                        '{toggleData}' .
                                        '{export}'
                                ],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => false,
                            'panel' => [
                                'type' => 'default',
                                'heading' => '',
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
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end();
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
    if(!$(\"#modal_abm\").hasClass('fade modal in'))
        location.reload();
})"
);
$script = <<<  JS
$('#btnHashPass').click(function() {
        $("#btnHashPass").html("<i class=\"fas fa-spinner fa-pulse\"></i>");
        $("#btnHashPass").prop("disabled", true);
        $.post("index.php?r=mds_seg_usuario/pass_hash", function(data) {
            console.log(data);
            $("#btnHashPass").html("<i class=\"fas fa-sync-alt\"></i> Hash the Pass");
            $("#btnHashPass").prop("disabled", false);
            if (data.length>0){
                $("#btnHashPass").show();
            }
            else {
                $("#btnHashPass").hide();
            }
        });
    });
JS;

$this->registerJs($script); ?>