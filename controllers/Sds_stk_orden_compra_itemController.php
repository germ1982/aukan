<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_stk_orden_compra_item;
use app\models\Sds_stk_orden_compra_itemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Sds_stk_articulo;
use yii\data\ActiveDataProvider;
use app\models\Sds_com_configuracion;
use app\models\Mds_sys_log;
use app\models\Sds_stk_orden_compra;
use yii\filters\AccessControl;

/**
 * Sds_stk_orden_compra_itemController implements the CRUD actions for Sds_stk_orden_compra_item model.
 */
class Sds_stk_orden_compra_itemController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => [
                    'index',
                    'view',
                    'create',
                    'update',
                    'delete',
                    'validar_item_existente',
                    'create_ajax',
                    'grilla_items',
                    'grilla_items_views',
                    'recalcular_importe_total',
                    'logout',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'delete',
                            'update',
                            'view',
                            'validar_item_existente',
                            'create_ajax',
                            'grilla_items',
                            'grilla_items_views',
                            'recalcular_importe_total',
                            'logout',
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [Mds_seg_item::STK_OC],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        /*Se agrega este redirecionamiento ya que se estuvo realizando verifiación
        y todo indica que esta action no se utiliza*/
        return $this->redirect(['/sds_stk_orden_compra/index']);

        $searchModel = new Sds_stk_orden_compra_itemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        /*Se agrega este redirecionamiento ya que se estuvo realizando verifiación
        y todo indica que esta action no se utiliza*/
        return $this->redirect(['/sds_stk_orden_compra/index']); 
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Sds_stk_orden_compra_item #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCreate()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $request = Yii::$app->request;
        $model = new Sds_stk_orden_compra_item();  
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Sds_stk_orden_compra_item",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Sds_stk_orden_compra_item",
                    'content'=>'<span class="text-success">Create Sds_stk_orden_compra_item success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Sds_stk_orden_compra_item",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            return $this->redirect(['/sds_stk_orden_compra/index']);
            /* if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idordencompraitem]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            } */
        }
       
    }

    public function actionUpdate($id)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Sds_stk_orden_compra_item #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Sds_stk_orden_compra_item #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Sds_stk_orden_compra_item #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            return $this->redirect(['/sds_stk_orden_compra/index']);
            /* if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idordencompraitem]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            } */
        }
    }

    public function actionValidar_item_existente($idordencompra, $id_articulo)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $model_item = Sds_stk_orden_compra_item::find()->where("idordencompra = $idordencompra and idarticulo = $id_articulo")->one();
        if($model_item)
        {
            $model_oc = Sds_stk_orden_compra::findOne($idordencompra);
            return $model_oc->numero;
        }
        return 0;
    }
    
    public function actionCreate_ajax($idordencompra, $id_articulo, $importe_unitario, $cantidad)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $model = new Sds_stk_orden_compra_item();
        $model->idordencompra = $idordencompra;
        $model->idarticulo = $id_articulo;
        $model->importe_unitario = $importe_unitario;
        $model->cantidad = $cantidad;
        if ($model->save())
        {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_stk_orden_compra_item', $model->idordencompraitem , $model->getAttributes());
            return 1;
        }else{
            return 0;
        }
    }

    public function actionGrilla_items($id)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => Sds_stk_orden_compra_item::findBySql('Select * from sds_stk_orden_compra_item where idordencompra = '.$id),
            'sort' => [
                'attributes' => ['idarticulo','cantidad', 'importe_unitario'],
            ]                
        ]);

        $dataProvider->pagination = false;

        $aux_alta = Html::button('<i class="glyphicon glyphicon-plus"></i>', [
            'class' => 'btn btn-primary',
            'id' => 'btnItem',
            'title' => "Nuevo Item",
            'data-toggle' => 'tooltip',
            'onclick' => "js:mostrar_abm_item();"]);
        
            
        return GridView::widget([
            'id' => 'grilla_items',
            'dataProvider' => $dataProvider,
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'idarticulo',
                    'headerOptions' => ['style' => 'width:65%'],
                    'value' => function ($model) {
                        $articulo = Sds_stk_articulo::findOne($model->idarticulo);
                        $medida = Sds_com_configuracion::findOne($articulo->unidad_medida);
                        return "$articulo->descripcion (en $medida->descripcion)";
                    
                    },
                    'label'=>'Articulo',
                ],
                [
                    'attribute' => 'cantidad',
                    'headerOptions' => ['style' => 'width:15%'],
                    'value' => function ($model) {
                        $aux = truncate($model->cantidad,2);
                        return "$aux";
                    
                    },
                ],
                [
                    'attribute' => 'importe_unitario',
                    'headerOptions' => ['style' => 'width:15%'],
                    'value' => function ($model) {
                        $aux = truncate($model->importe_unitario,2);
                        return "$$aux";
                    
                    },
                ],
                [
                    'header'=>  $aux_alta,
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width:5%'],
                    'template' => ' {eliminar}',  // the default buttons + your custom button
                    'buttons' => [
                        'eliminar' => function ($url,$model) {
                            $id_item = $model->idordencompraitem;
                            return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'title' => "Eliminar Item",
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-link',
                                'onclick' => "js:eliminar_item($id_item);"
                            ]);
                        },
                    ]
                ]
            ],
        ]);
    }

    public function actionGrilla_items_view($id)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Sds_stk_orden_compra_item::findBySql('Select * from sds_stk_orden_compra_item where idordencompra = '.$id),
            'sort' => [
                'attributes' => ['idarticulo','cantidad', 'importe_unitario'],
            ]                
        ]);
        $dataProvider->pagination = false;
        return GridView::widget([
            'id' => 'grilla_items',
            'dataProvider' => $dataProvider,
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'idarticulo',
                    'headerOptions' => ['style' => 'width:45%'],
                    'value' => function ($model) {
                        $articulo = Sds_stk_articulo::findOne($model->idarticulo);
                        $medida = Sds_com_configuracion::findOne($articulo->unidad_medida);
                        return "$articulo->descripcion (en $medida->descripcion)";
                    
                    },
                    'label'=>'Articulo',
                ],
                [
                    'attribute' => 'cantidad',
                    'headerOptions' => ['style' => 'width:10%'],
                    'value' => function ($model) {
                        $aux = truncate($model->cantidad,2);
                        return "$aux";
                    
                    },
                ],
                [
                    'attribute' => 'importe_unitario',
                    'headerOptions' => ['style' => 'width:10%'],
                    'value' => function ($model) {
                        $aux = truncate($model->importe_unitario,2);
                        return "$$aux";
                    
                    },
                ],

            ],
        ]);
    }

    public function actionRecalcular_importe_total($idordencompra)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $consulta= "SELECT ifnull(sum(oci.cantidad*oci.importe_unitario),0) as importe_total
                    FROM sds_stk_orden_compra_item oci 
                    WHERE oci.idordencompra = $idordencompra";
        $orden_compra = Sds_stk_orden_compra::findBySql($consulta)->one();

        $model_oc = Sds_stk_orden_compra::findOne($idordencompra); 
        $model_oc->importe_total = $orden_compra->importe_total;
        return $model_oc->save() ? $model_oc->importe_total : false;
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        if ($model->delete()) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_stk_orden_compra_item', $id, $model->getAttributes());
            if($this->actionRecalcular_importe_total($model->idordencompra)){
                $transaction->commit();
                return 1;
            }
        }
        $transaction->rollBack();
        return 0;
    }

    protected function findModel($id)
    {
        if (($model = Sds_stk_orden_compra_item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
       
    } 
    */
}

function truncate($number, $precision = 0) {
    // warning: precision is limited by the size of the int type
    $shift = pow(10, $precision);
    return intval($number * $shift)/$shift;
 }