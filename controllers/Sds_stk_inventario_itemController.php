<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_stk_inventario_item;
use app\models\Sds_stk_inventario_itemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use app\models\Sds_com_configuracion;
use app\models\Sds_stk_articulo;
use yii\filters\AccessControl;

class Sds_stk_inventario_itemController extends Controller
{
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
                        'grilla_items',
                        'validar_item_existente',
                        'create_ajax',
                        'logout',
                    ],
                    'rules' => [
                        [
                            'actions' => [
                                /* 'index',
                                'create',
                                'delete',
                                'update',
                                'view', */
                                'grilla_items',
                                'validar_item_existente',
                                'create_ajax',
                                'logout',
                            ],
                            'allow' => true,
                            // Allow users, moderators and admins to create
                            'roles' => [Mds_seg_item::STK_INVENTARIO],
                        ],
                    ],
                ],
            ];
        }

    public function actionIndex()
    {    
        $searchModel = new Sds_stk_inventario_itemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Sds_stk_inventario_item #".$id,
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
            return $this->redirect(['site']);
        }

        $request = Yii::$app->request;
        $model = new Sds_stk_inventario_item();  
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Sds_stk_inventario_item",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Sds_stk_inventario_item",
                    'content'=>'<span class="text-success">Create Sds_stk_inventario_item success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Sds_stk_inventario_item",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idinventarioitem]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    
    }

    public function actionUpdate($id)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site']);
        }
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Sds_stk_inventario_item #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Sds_stk_inventario_item #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                return [
                    'title'=> "Update Sds_stk_inventario_item #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idinventarioitem]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    public function actionGrilla_items($idinventario)
    {
        $columna1 = '80%';
        $columna2 = '15%';
        $columna3 = '5%';
        //$consulta = "SELECT ii.idarticulo as idarticulo, a.descripcion as descripcion, ii.cantidad as cantidad 
        $consulta = "SELECT * from sds_stk_inventario_item where idinventario = $idinventario";
        $dataProvider = new ActiveDataProvider(['query' => Sds_stk_inventario_item::findBySql($consulta), 'sort' => [
            'attributes' => ['idinventarioitem','idarticulo','cantidad']
        ]]);
        return GridView::widget([
            'id' => 'grilla_items',
            'dataProvider' => $dataProvider,
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'idarticulo',
                    'headerOptions' => ['style' => 'width:' . $columna1],
                    'value' => function ($model) {
                        $articulo = Sds_stk_articulo::findOne($model->idarticulo);
                        $medida = Sds_com_configuracion::findOne($articulo->unidad_medida);
                        return "$articulo->descripcion (en $medida->descripcion)";
                    },
                    'label' => 'Articulo',
                ],
                [
                    'attribute' => 'cantidad',
                    'headerOptions' => ['style' => 'width:' . $columna2],
                    'label' => 'Cantidad',
                ],
                [
                    'header' =>  Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                        'class' => 'btn btn-primary',
                        'id' => 'btnEntregaItem',
                        'title' => "Nueva Entrega",
                        'data-toggle' => 'tooltip',
                        'onclick' => "js:mostrar_abm_entrega_item();"
                    ]),
                    'template' => '',
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width:' . $columna3],
                    'template' => ' {eliminar}',  // the default buttons + your custom button
                    'buttons' => [
                        'eliminar' => function ($url, $model) {
                            $idinventarioitem = $model->idinventarioitem;
                            return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'title' => "Eliminar Item",
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-link',
                                'onclick' => "js:eliminar_item($idinventarioitem);"
                            ]);
                        },
                    ]
                ],
            ],
        ]);
    }

    public function actionValidar_item_existente($idinventario, $idarticulo)
    {
        $aux = 0;
        $model_item = Sds_stk_inventario_item::find()->where("idinventario = $idinventario and idarticulo = $idarticulo")->one();
        if($model_item)
            {   
                $aux  = $idinventario;
            }
        return $aux;
    }

    public function actionCreate_ajax($idinventario, $idarticulo, $cantidad)
    {
        $model = new Sds_stk_inventario_item();
        $model->idinventario = $idinventario;
        $model->idarticulo = $idarticulo;
        $model->cantidad = $cantidad;
        return $model->save() ? 1 : 0;
    }

    protected function findModel($id)
    {
        if (($model = Sds_stk_inventario_item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /* public function actionBulkDelete()
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
    
    } */
}
