 <?php

    use yii\helpers\Html;
    use kartik\grid\GridView;
    use johnitvn\ajaxcrud\CrudAsset;
    use yii\widgets\ActiveForm;
    use yii\helpers\Url;

    /* @var $this yii\web\View */
    /* @var $searchModel app\models\Mds_cap_personaSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = 'Supervisión - Respuestas supervisadas';
    $this->params['breadcrumbs'][] = $this->title;

    $string = "";
    $permiso_view = 0;
    if ($permiso_view == 1) {
        $string .= "{view}";
    }
    // Precargamos view
    $string .= "{view}";
    CrudAsset::register($this);

    $botonManual = Html::button('Manual de Usuario', ['id' => 'boton-manual', 'type' => "button", 'class' => 'btn btn-primary pull-left btnManual']);
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
 </style>
 <header class="page-header">
     <h2><?= $this->title ?></h2>

     <div class="right-wrapper pull-right">
         <ol class="breadcrumbs">
             <li>
                 <a href="index.php">
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
                 <?= $this->render('../components/flash_messages') ?>
                 <div class="sds-vio-intervencion-index table-responsive">
                     <div id="ajaxCrudDatatable">
                         <?= GridView::widget([
                                'id' => 'crud-datatable',
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'pjax' => false,
                                'columns' => require(__DIR__ . '/_columns.php'),
                                'toolbar' => [
                                    ['content' =>
                                    Html::a(
                                        '<i class="glyphicon glyphicon-repeat"></i>',
                                        [''],
                                        ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                                    ) .
                                        '{toggleData}' .
                                        '{export}'],
                                ],
                                'striped' => true,
                                'condensed' => true,
                                'responsive' => false,
                                'panel' => [
                                    'type' => 'default',
                                    'heading' => '',
                                    'after' => '<div class="clearfix"></div>',
                                    'before' => $botonManual
                                ]
                            ]) ?>
                     </div>
                 </div>
             </div>
         </section>
     </div>
 </div>

 <?php
    require(Yii::$app->basePath . '/views/mds_legales_oficio/vinculacion/modal_nota.php');
    require(Yii::$app->basePath . '/views/mds_legales_oficio/vinculacion/modal_comprobante.php');

    $this->registerJs(
        "$('#crud-datatable-filters').children('td').children().css('z-index', '0');

        $('#boton-manual').click(function() {
            $.ajax({
                    type: 'POST',
                    url: '" . Url::to(['/mds_legales_oficio/guardarlogmanualusuario']) . "', 
                    data: { },
    
                    success: function (success) {
                        if (success) {
                            window.open('" . Url::base() . "/instructivos/instructivo_legales.pdf', '_blank');
                        } else {
                            console.log(errormessage);
                            alert('Ocurrió un error');
                        }
                    },
                    error: function (errormessage) {
                        console.log(errormessage);
                        alert('Ocurrió un error');
                    }
                });
        })
      "
    );
    ?>