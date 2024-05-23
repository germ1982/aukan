<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Mds_seg_usuario;



$this->title = 'Entrega de Insumos';
$this->params['breadcrumbs'][] = $this->title;
$clase = 'sds_stk_entrega-index';
$title_for_new = 'Nueva Entrega';

CrudAsset::register($this);

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
$usuario = Mds_seg_usuario::findOne($idusuario);

$boton_reporte = Html::a(
    'REPORTE <span class= "fas fa-print"></span>',
    Url::to([
        '/sds_stk_entrega/imprimir_reporte_entregas',
        'responsable' => getResponsables($searchModel),
        'fecha_desde' => $searchModel->fdesde,
        'fecha_hasta' => $searchModel->fhasta,
        'observaciones' => $searchModel->observaciones,
        'destinatario' => $searchModel->idpersona,
        'detalle_items' => $searchModel->detalle_items,
        'organizacion_social' => $searchModel->organizacion_social,

    ]),
    [
        'title' => "Imprimir ",
        'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
        'data-toggle' => 'tooltip',
        'class' => 'btn btn-default',
    ]
) ;

if($usuario->iddeposito)
{
    $boton_reporte='';
}

function getResponsables($searchModel)
{
    $responsable_filter = "";
    if (is_array($searchModel->idcontacto)) 
        {
            $responsables = array();
            foreach ((array)$searchModel->idcontacto as $responsable) 
                {
                    array_push($responsables, $responsable);
                }
            $responsable_filter = implode(",", $responsables);
        } 
    else 
        {
            $responsable_filter = $searchModel->idcontacto;
        }
    return "$responsable_filter";
}
?>
<style>
    .table>thead>tr>td.info,
    .table>tbody>tr>td.info,
    .table>tfoot>tr>td.info,
    .table>thead>tr>th.info,
    .table>tbody>tr>th.info,
    .table>tfoot>tr>th.info,
    .table>thead>tr.info>td,
    .table>tbody>tr.info>td,
    .table>tfoot>tr.info>td,
    .table>thead>tr.info>th,
    .table>tbody>tr.info>th,
    .table>tfoot>tr.info>th {
        color: #777;
        background-color: #fafafa !important;
    }

    .panel-primary .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
    }

    .select2-search{
        z-index:1;
    }

    /*     #ajaxCrudModal{
        left: 0px;
        top: -26px;


    }
    #ajaxCrudModal .modal-dialog {
        width: 1340px;
    } */
    @media (max-width: 425px) {
        #ajaxCrudModal {
            left: -10px;
            top: -10px;
            width: 102%;
        }

        #ajaxCrudModal .modal-dialog {
            width: 98%;
        }
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
                <div class="<?= $clase ?>">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'rowOptions' => function ($model, $key, $index, $column) {
                                //tiene acta pero pero no esta generada// blanco
                                $aux = null;
                                
                                if ($model->acta_original == 1) 
                                    {
                                        if ($model->generada) {
                                            //tiene acta pero y esta generada
                                            $aux =  ['style' => 'background-color: #C7FF7B;'];//verde
                                        }
                                        
                                    }
                                    else
                                    {
                                        //no tiene acta y no esta generada
                                        $aux =  ['myurl' => $model->identrega, 'style' => 'background-color: #FD4040; color: #000'];//Rojo
                                        if ($model->generada) {
                                            //no tiene acta pero esta generada
                                            $aux = ['myurl' => $model->identrega, 'style' => 'background-color: #FCAEAE; color: #000'];//Naranja
                                        }
                                    }

                                return $aux;
                            },


                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' =>
                            [
                                [
                                    'content' =>
                                    $boton_reporte.
                                        Html::a(
                                            '<i class="glyphicon glyphicon-plus"></i>',
                                            ['create'],
                                            ['role' => 'modal-remote', 'title' => $title_for_new, 'class' => 'btn btn-default']
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
                                'type' => 'primary',
                                'heading' => false,
                                /* 'heading' => '<i class="glyphicon glyphicon-list"></i> Sds Vio Intervencions listing',
                                'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>', */
                                /* 'after'=>BulkButtonWidget::widget([
                                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Delete All',
                                                ["bulk-delete"] ,
                                                [
                                                    "class"=>"btn btn-danger btn-xs",
                                                    'role'=>'modal-remote-bulk',
                                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                                    'data-request-method'=>'post',
                                                    'data-confirm-title'=>'Are you sure?',
                                                    'data-confirm-message'=>'Are you sure want to delete this item'
                                                ]),
                                        ]).   */
                                'after' => '<div class="clearfix"></div>',
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        })"
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
<?php Modal::end(); ?>

<?php
$script = <<<  JS
$(document).ready(function() {
    //$("#ajaxCrudModal").css('display', 'block');
});
JS;
$this->registerJs($script);
$this->registerJs(
    "$('#crud-datatable-filters').children('td').children().css('z-index', '0')"
);
?>
