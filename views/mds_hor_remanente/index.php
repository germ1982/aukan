<?php

use app\models\Mds_org_contacto;
use app\models\Sds_com_persona;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_hor_remanenteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Remanentes';
$this->params['breadcrumbs'][] = $this->title;
CrudAsset::register($this);
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
                <div class="mds-hor-remanente-index">
                    <?php 
                        $contacto = Mds_org_contacto::findOne($searchModel->idcontacto);
                        $persona = Sds_com_persona::findOne($contacto->idpersona);
                        if(!is_null($persona)){
                            $nya=$persona->apellido.', '.$persona->nombre;
                        }
                    ?>
                    <h3><?=$nya?></h3>
                    <div id="ajaxCrudDatatable">
                        <?=GridView::widget([
                            'id'=>'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'pjax'=>false,
                            'columns' => require(__DIR__.'/_columns.php'),
                            'toolbar'=> [
                                ['content' => Html::a('<i class="far fa-address-card"></i> &nbsp;Volver a Contactos',
                                    ['/mds_org_contacto'],
                                    ['role' => 'post', 'data-pjax' => 0, 'title' => 'Ir a Contactos', 'class' => 'btn btn-info']
                                    )
                                ]],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,          
                            'panel' => [
                                'type' => 'default',
                                'heading' => false,
                                'after' => false
                            ]
                        ])?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
