<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Sds_stk_recepcion;
use Yii;
use app\models\Sds_stk_recepcion_item;
use app\models\Sds_stk_movimiento;
use app\models\Sds_stk_recepcion_itemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Sds_stk_articulo;
use yii\data\ActiveDataProvider;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_stk_deposito;
use app\models\Sds_stk_entrega_item;
use app\models\Sds_stk_orden_compra_item;
use yii\filters\AccessControl;

/**
 * Sds_stk_recepcion_itemController implements the CRUD actions for Sds_stk_recepcion_item model.
 */
class Sds_stk_recepcion_itemController extends Controller
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
                    'create',
                    'update',
                    'delete',
                    'view',
                    'validar_item_existente',
                    'create_ajax',
                    'validar_cantidad',
                    'create_ext',
                    'grilla_items',
                    'grilla_items_view',
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
                            'validar_cantidad',
                            'create_ext',
                            'grilla_items',
                            'grilla_items_view',
                            'logout',
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [Mds_seg_item::MODULO_STK_RECEPCION],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        /*Se agrega este redirecionamiento ya que se estuvo realizando verifiación
        y todo indica que esta action no se utiliza*/
        return $this->redirect(['/sds_stk_recepcion']);
        $searchModel = new Sds_stk_recepcion_itemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_stk_recepcion_item', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_stk_recepcion_item', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Sds_stk_recepcion_item #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->redirect(['/sds_stk_recepcion']);
            /* return $this->render('view', [
                'model' => $this->findModel($id),
            ]); */
        }
    }

    public function actionCreate()
    {
        /*Se agrega este redirecionamiento ya que se estuvo realizando verifiación
        y todo indica que esta action no se utiliza*/
        return $this->redirect(['/sds_stk_recepcion']);
        $request = Yii::$app->request;
        $model = new Sds_stk_recepcion_item();
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Sds_stk_recepcion_item",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_stk_recepcion_item', $model->idrecepcionitem, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Sds_stk_recepcion_item",
                    'content' => '<span class="text-success">Create Sds_stk_recepcion_item success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Create new Sds_stk_recepcion_item",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_stk_recepcion_item', $model->idrecepcionitem, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idrecepcionitem]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionUpdate($id)
    {
        /*Se agrega este redirecionamiento ya que se estuvo realizando verifiación
        y todo indica que esta action no se utiliza*/
        return $this->redirect(['/sds_stk_recepcion']);
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update Sds_stk_recepcion_item #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_stk_recepcion_item', $model->idrecepcionitem, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Sds_stk_recepcion_item #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Sds_stk_recepcion_item #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_stk_recepcion_item', $model->idrecepcionitem, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idrecepcionitem]);
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
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        $model = $this->findModel($id);
        $entrega = Sds_stk_entrega_item::find()->where("recepcion_item = $id")->one();
        $ban = $entrega ? 2 : 0;
        if($ban == 0)
        {
            if ($model->delete() > 0) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_stk_recepcion_item', $id, $model->getAttributes());
                $ban = 1;
            }
        }
        return $ban;
    }

    public function actionValidar_item_existente($id_recepcion, $id_articulo)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        $aux = 0;
        $model_item_recepcion = Sds_stk_recepcion_item::find()->where("idrecepcion = $id_recepcion and idarticulo = $id_articulo")->one();
        if ($model_item_recepcion) {
            $aux  = $model_item_recepcion->idrecepcionitem;
        }
        return $aux;
    }

    public function actionCreate_ajax($id_recepcion, $id_articulo, $descripcion, $cantidad, $id_deposito, $id_orden_compra)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        $model = new Sds_stk_recepcion_item();
        $model->idrecepcion = $id_recepcion;
        $model->idarticulo = $id_articulo;
        $model->descripcion = $descripcion;
        $model->cantidad = $cantidad;
        if ($id_orden_compra) {
            $model->idordencompraitem = Sds_stk_orden_compra_item::find()->where("idordencompra = $id_orden_compra and idarticulo = $id_articulo")->one()->idordencompraitem;
        }

        $transaction = Yii::$app->db->beginTransaction();
        if ($model->save()) {
            $model_movimiento = new Sds_stk_movimiento();
            $model_movimiento->tipo = 1;
            $model_movimiento->cantidad = $model->cantidad;
            $model_movimiento->deposito_ingreso = $id_deposito;
            $model_movimiento->idarticulo = $model->idarticulo;

            $model_recepcion = Sds_stk_recepcion::findOne($model->idrecepcion);
            $model_movimiento->fecha_hora = $model_recepcion->fecha;

            $model_movimiento->item_recepcion = $model->idrecepcionitem;
            $model_movimiento->save(false);
            $transaction->commit();
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_stk_recepcion_item', $model->idrecepcionitem, $model->getAttributes());
            return 1;
        } else {
            return 0;
        }
    }

    public function actionValidar_cantidad($id_recepcion, $id_articulo, $id_orden_compra, $cantidad)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        $sql_solicitado = "SELECT SUM(soci.cantidad) 
                            FROM sds_stk_orden_compra_item soci
                            WHERE soci.idarticulo = A.idarticulo and soci.idordencompra = $id_orden_compra";

        $sql_recepcionado = "SELECT CASE WHEN rri.cantidad IS NULL THEN 0 ELSE SUM(rri.cantidad) END recepcionado 
                            FROM sds_stk_recepcion_item rri
                            WHERE rri.idarticulo = A.idarticulo and rri.idrecepcion = $id_recepcion";

        $sql_pendiente = "($sql_solicitado) -($sql_recepcionado)";

        $consulta = "SELECT $sql_pendiente as disponible
                    FROM sds_stk_articulo A
                    INNER JOIN sds_stk_orden_compra_item oci on oci.idarticulo = A.idarticulo
                    
                    WHERE A.activo = 1 
                        and A.idarticulo = $id_articulo
                        and oci.idordencompra = $id_orden_compra";

        $pendiente = Sds_stk_articulo::findBySql($consulta)->one()->disponible;

        if ($cantidad > $pendiente)
            return 1;
        else
            return 0;
    }
    
    public function actionCreate_ext()
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        $request = Yii::$app->request;
        $model = new Sds_stk_recepcion_item();
        $request = Yii::$app->request;
        if ($model->load($request->post())) {
            if ($model->save(false)) {
                $model_movimiento = new Sds_stk_movimiento;
                $model_movimiento->tipo = 1;
                $model_movimiento->cantidad = $model->cantidad;
                $model_movimiento->deposito_ingreso = $model->deposito;
                $model_movimiento->idarticulo = $model->idarticulo;

                $model_recepcion = Sds_stk_recepcion::findOne($model->idrecepcion);
                $model_movimiento->fecha_hora = $model_recepcion->fecha;

                $model_movimiento->item_recepcion = $model->idrecepcionitem;
                $model_movimiento->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_stk_recepcion_item', $model->idrecepcionitem, $model->getAttributes());
                return $model->idrecepcionitem;
            } else {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Error",
                    'content' => '<span class="text-success">El articulo ya esta en esta recepcion</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            }
        }
    }
    
    public function actionGrilla_items($idrecepcion)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Sds_stk_recepcion_item::findBySql('Select * from sds_stk_recepcion_item where idrecepcion = ' . $idrecepcion),
            'sort' => [
                'attributes' => ['idarticulo', 'deposito', 'cantidad', 'descripcion'],
            ]
        ]);
        $dataProvider->pagination = false;

        $aux_alta = Html::button('<i class="glyphicon glyphicon-plus"></i>', [
            'class' => 'btn btn-primary',
            'id' => 'btnRecepcionItem',
            'title' => "Nuevo Item",
            'data-toggle' => 'tooltip',
            'onclick' => "js:mostrar_abm_recepcion_item();"
        ]);


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
                    'label' => 'Articulo',
                ],
                [
                    'attribute' => 'deposito',
                    'value' => function ($model) {
                        $movimiento = Sds_stk_movimiento::find()->where("idarticulo = $model->idarticulo and item_recepcion = $model->idrecepcionitem")->one();
                        if ($movimiento != null && $movimiento->deposito_ingreso!=null) {
                            $deposito = Sds_stk_deposito::findOne($movimiento->deposito_ingreso);
                            return "$deposito->descripcion";
                        }
                        return "";
                    },
                    'label' => 'Deposito',
                ],
                [
                    'attribute' => 'cantidad',
                    'headerOptions' => ['style' => 'width:5%'],
                ],
                'descripcion',
                [
                    'header' =>  $aux_alta,
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width:5%'],
                    'template' => ' {eliminar}',  // the default buttons + your custom button
                    'buttons' => [
                        'eliminar' => function ($url, $model) {
                            $id_recepcion_item = $model->idrecepcionitem;
                            return Html::button('<i class="glyphicon glyphicon-trash"></i>', [
                                'title' => "Eliminar Item",
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-link',
                                'onclick' => "js:eliminar_item($id_recepcion_item);"
                            ]);
                        },
                    ]
                ]

            ],
        ]);
    }
    
    public function actionGrilla_items_view($idrecepcion)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Sds_stk_recepcion_item::findBySql('Select * from sds_stk_recepcion_item where idrecepcion = ' . $idrecepcion),
            'sort' => [
                'attributes' => ['idarticulo', 'deposito', 'cantidad', 'descripcion'],
            ]
        ]);
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
                    'label' => 'Articulo',
                ],
                [
                    'attribute' => 'deposito',
                    'value' => function ($model) {
                        $movimiento = Sds_stk_movimiento::find()->where("idarticulo = $model->idarticulo and item_recepcion = $model->idrecepcionitem")->one();
                        $deposito = Sds_stk_deposito::findOne($movimiento->deposito_ingreso);
                        return "$deposito->descripcion";
                    },
                    'label' => 'Articulo',
                ],
                [
                    'attribute' => 'cantidad',
                    'headerOptions' => ['style' => 'width:5%'],
                ],
                'descripcion',

            ],
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Sds_stk_recepcion_item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /*
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            return $this->redirect(['index']);
        }
    }
    */
}
function ArmarDateParaMySql($Fecha, $Hora)
{
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $H = substr($Hora, 0, 2);
    $m = substr($Hora, 3, 2);
    $DT = "$anio-$mes-$dia $H:$m:00";
    return $DT;
}
