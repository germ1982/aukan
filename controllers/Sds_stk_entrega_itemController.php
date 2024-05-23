<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_stk_deposito;
use app\models\Sds_stk_entrega_item;
use app\models\Sds_stk_entrega;
use app\models\Sds_stk_entrega_itemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_seg_usuario;
use app\models\Sds_stk_movimiento;
use app\models\Mds_sys_log;
use app\models\Sds_stk_recepcion;
use app\models\Sds_com_configuracion;
use app\models\Sds_stk_recepcion_item;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_orden_compra;
use app\models\Sds_stk_orden_compra_item;
use app\models\Sds_view_stock_detalle;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\grid\GridView;

class Sds_stk_entrega_itemController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => [
                    'index', 'create', 'update', 'delete', 'view',
                    'validar_item_existente', 'create_ajax', 'create_ext',
                    'get_combo_articulo', 'get_combo_deposito',
                    'get_combo_expediente',
                    'get_disponibilidad_item', 'grilla_items',
                    'grilla_items_view',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index', 'create', 'update', 'delete', 'view',
                            'create_ajax', 'create_ext', 'get_combo_articulo',
                            'grilla_items', 'grilla_items_view'
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [Mds_seg_item::STK_ENTREGA],
                    ],
                    [
                        'actions' => [
                            'get_combo_deposito',
                            'get_combo_expediente',
                            'get_disponibilidad_item'
                        ],
                        'allow' => true,
                        'roles' => [Mds_seg_item::STK_ENTREGA, Mds_seg_item::STK_MOVIMIENTO],
                    ],
                    [
                        'actions' => [
                            'validar_item_existente',
                        ],
                        'allow' => true,
                        'roles' => [Mds_seg_item::STK_ENTREGA, Mds_seg_item::STK_ARTICULO],
                    ],
                ],

            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new Sds_stk_entrega_itemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => 'Sds_stk_entrega_item #' . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' =>
                Html::button('Close', [
                    'class' => 'btn btn-default pull-left',
                    'data-dismiss' => 'modal',
                ]) .
                    Html::a(
                        'Edit',
                        ['update', 'id' => $id],
                        ['class' => 'btn btn-primary', 'role' => 'modal-remote']
                    ),
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_stk_entrega_item();

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Create new Sds_stk_entrega_item',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Close', [
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Save', [
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            } elseif ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => 'Create new Sds_stk_entrega_item',
                    'content' =>
                    '<span class="text-success">Create Sds_stk_entrega_item success</span>',
                    'footer' =>
                    Html::button('Close', [
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::a(
                            'Create More',
                            ['create'],
                            [
                                'class' => 'btn btn-primary',
                                'role' => 'modal-remote',
                            ]
                        ),
                ];
            } else {
                return [
                    'title' => 'Create new Sds_stk_entrega_item',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Close', [
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Save', [
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->identregaitem]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionValidar_item_existente(
        $id_entrega,
        $id_articulo,
        $id_recepcion_expediente
    ) {
        $aux = 0;
        $sql = "SELECT * 
                        FROM sds_stk_entrega_item I 
                        INNER JOIN sds_stk_recepcion_item RI on RI.idrecepcionitem = I.recepcion_item
                        WHERE I.identrega = $id_entrega and RI.idarticulo = $id_articulo and RI.idrecepcion = $id_recepcion_expediente";

        $model_item_entrega = Sds_stk_entrega_item::findBySql($sql)->one();

        if ($model_item_entrega) {
            $aux = $model_item_entrega->identregaitem;
        }
        return $aux;
    }

    public function actionCreate_ajax(
        $id_entrega,
        $id_articulo,
        $cantidad,
        $id_recepcion_item,
        $id_deposito,
        $disponible
    ) {
        $model = new Sds_stk_entrega_item();
        $model->recepcion_item = $id_recepcion_item;
        $model->cantidad = $cantidad;
        $model->identrega = $id_entrega;
        $model->idarticulo = $id_articulo;

        $model_movimiento = new Sds_stk_movimiento();
        //Para verificar que cantiodad no sea mayor a disponible
        $model_movimiento->disponible = $disponible;
        $model_movimiento->tipo = Sds_stk_movimiento::TIPO_EGRESO;
        $model_movimiento->cantidad = $cantidad;
        $model_movimiento->idarticulo = $id_articulo;
        $model_movimiento->deposito_egreso = $id_deposito;
        //Se aplica fecha/hora de la entrega
        $model_entrega = Sds_stk_entrega::findOne($model->identrega);
        $model_movimiento->fecha_hora = $model_entrega->fecha_hora;

        Yii::$app->response->format = Response::FORMAT_JSON;

        $transaction = Yii::$app->db->beginTransaction();
        if ($model->save()) {
            $model_movimiento->item_entrega = $model->identregaitem;
            if ($model_movimiento->save()) {
                Mds_sys_log::guardarLog(
                    Mds_sys_log::ACCION_NUEVO,
                    'sds_stk_entrega_item',
                    $model->identregaitem,
                    $model->getAttributes()
                );
                $transaction->commit();
                return $model->identregaitem;
            } else {
                return $model_movimiento->getErrors();
            }
        } else {
            $transaction->rollBack();
            return $model->getErrors();
            return $this->renderAjax('//sds_stk_entrega_item/create', [
                'model' => $model,
                'botones' => true,
            ]);
        }
    }

    public function actionCreate_ext()
    {
        $request = Yii::$app->request;
        $model = new Sds_stk_entrega_item();
        $request = Yii::$app->request;

        if ($model->load($request->post())) {
            $guardar = true;
            $consulta = "SELECT * from sds_stk_recepcion_item RI INNER JOIN sds_stk_recepcion R on R.idrecepcion = RI.idrecepcion Where RI.idarticulo = $model->articulo and R.idrecepcion = $model->expediente";
            $model_recepcion_item = Sds_stk_recepcion_item::findBySql(
                $consulta
            )->one();
            $model->recepcion_item = $model_recepcion_item->idrecepcionitem;
            $disponible = $this->actionGet_disponibilidad_item(
                $model->articulo,
                $model->deposito,
                $model->expediente
            );

            if ($model->cantidad > $disponible) {
                $guardar = false;
                $model->cantidad = 0;
                //echo "<script>alert('la cantidad no debe superar el disponible');</script>";
                //$model->addError('cantidad', 'la cantidad no debe superar el disponible');
            }

            if ($guardar && $model->save()) {
                $model_movimiento = new Sds_stk_movimiento();
                $model_movimiento->tipo = 3;
                $model_movimiento->cantidad = $model->cantidad;
                $model_movimiento->deposito_egreso = $model->deposito;
                $model_movimiento->idarticulo = $model->articulo;

                $model_entrega = Sds_stk_entrega::findOne($model->identrega);
                $model_movimiento->fecha_hora = $model_entrega->fecha_hora;

                $model_movimiento->item_entrega = $model->identregaitem;
                $model_movimiento->save();
                Mds_sys_log::guardarLog(
                    Mds_sys_log::ACCION_NUEVO,
                    'sds_stk_entrega_item',
                    $model->identregaitem,
                    $model->getAttributes()
                );
                return $model->identregaitem;
            } else {
                return $this->renderAjax('//sds_stk_entrega_item/create', [
                    'model' => $model,
                    'botones' => true,
                ]);
            }
        }
        /*else 
                {
                    return $this->renderAjax('//sds_stk_entrega_item/create', [
                        'model' => $model,
                        'botones' => true,
                    ]);
                }  */
    }
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Update Sds_stk_entrega_item #' . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Close', [
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Save', [
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            } elseif ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => 'Sds_stk_entrega_item #' . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Close', [
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::a(
                            'Edit',
                            ['update', 'id' => $id],
                            [
                                'class' => 'btn btn-primary',
                                'role' => 'modal-remote',
                            ]
                        ),
                ];
            } else {
                return [
                    'title' => 'Update Sds_stk_entrega_item #' . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Close', [
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Save', [
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            }
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->identregaitem]);
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
        $model = $this->findModel($id);
        $ban = 0;
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(
                Mds_sys_log::ACCION_ELIMINAR,
                'sds_stk_entrega_item',
                $id,
                $model->getAttributes()
            );
            $ban = 1;
        }
        return $ban;
    }

    /* public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) { */
    /*
             *   Process for ajax request
             */
    /* Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceClose' => true,
                'forceReload' => '#crud-datatable-pjax',
            ];
        } else { */
    /*
             *   Process for non-ajax request
             */
    /*  return $this->redirect(['index']);
        }
    } */

    public function actionGet_combo_articulo()
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        $organismo = $usuario->organismo_stock;
        if ($organismo == null) {
            $organismo = 0;
        }

        $consulta_cantidad_tipo1 = "SELECT ifnull(SUM(Mo.cantidad),0)
                                                            FROM sds_stk_movimiento Mo
                                                            WHERE Mo.tipo = 1 and Mo.idarticulo = A.idarticulo";
        $consulta_cantidad_tipo3 = "SELECT ifnull(SUM(Mo.cantidad),0)
                                                            FROM sds_stk_movimiento Mo
                                                            WHERE Mo.tipo = 3 and Mo.idarticulo = A.idarticulo";

        $consulta_disponible = "($consulta_cantidad_tipo1)-($consulta_cantidad_tipo3)";

        $consulta = "SELECT 
                                A.idarticulo as idarticulo, 
                                A.descripcion as descripcion,
                                $consulta_disponible as disponible
                                FROM sds_stk_movimiento M
                                INNER JOIN sds_stk_recepcion_item RI on RI.idrecepcionitem = M.item_recepcion
                                INNER JOIN sds_stk_recepcion R on R.idrecepcion = RI.idrecepcion
                                INNER JOIN sds_stk_articulo A on A.idarticulo = RI.idarticulo
                                WHERE R.organismo = $organismo and $consulta_disponible > 0
                                group by A.idarticulo, A.descripcion";
        $articulos = Sds_stk_articulo::findBySql($consulta)->all();
        $data = "<option value='0'></option>";
        if (sizeof($articulos) > 0) {
            foreach ($articulos as $articulo) {
                $data =
                    $data .
                    "<option value='$articulo->idarticulo'>$articulo->descripcion</option>";
            }
        }
        return $data;
    }

    public function actionGet_combo_deposito($id_articulo)
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        $organismo = $usuario->organismo_stock;
        if ($organismo == null) {
            $organismo = 0;
        }
        $consulta_cantidad_tipo1 = "SELECT ifnull(SUM(Mo.cantidad),0) FROM sds_stk_movimiento Mo 
                                    WHERE Mo.tipo = 1 and Mo.idarticulo = M.idarticulo and Mo.deposito_ingreso = d.iddeposito";
        $consulta_cantidad_tipo2_ingreso = "SELECT ifnull(SUM(Mo.cantidad),0) FROM sds_stk_movimiento Mo 
                                            WHERE Mo.tipo = 2 and Mo.idarticulo = M.idarticulo and Mo.deposito_ingreso = d.iddeposito";
        $consulta_cantidad_tipo2_egreso = "SELECT ifnull(SUM(Mo.cantidad),0) FROM sds_stk_movimiento Mo 
                                            WHERE Mo.tipo = 2 and Mo.idarticulo = M.idarticulo and Mo.deposito_egreso = d.iddeposito";
        $consulta_cantidad_tipo3 = "SELECT ifnull(SUM(Mo.cantidad),0) FROM sds_stk_movimiento Mo 
                                    WHERE Mo.tipo = 3 and Mo.idarticulo = M.idarticulo and Mo.deposito_egreso = d.iddeposito";
        $consulta_disponible = "($consulta_cantidad_tipo1)+($consulta_cantidad_tipo2_ingreso)-($consulta_cantidad_tipo2_egreso)-($consulta_cantidad_tipo3)";
        $consulta = "SELECT M.deposito_ingreso as iddeposito, concat(d.descripcion,' (Cantidad ',$consulta_disponible,')') as descripcion,
                $consulta_disponible AS disponible
                FROM sds_stk_movimiento M
                INNER JOIN sds_stk_deposito d ON d.iddeposito = M.deposito_ingreso
                WHERE M.idarticulo = $id_articulo AND $consulta_disponible > 0
                GROUP BY M.deposito_ingreso,d.descripcion";

        if ($usuario->iddeposito) {
            //$model->deposito = $usuario->iddeposito;
            $consulta = "SELECT * FROM sds_stk_deposito WHERE iddeposito = $usuario->iddeposito";
        }

        //$consulta = "select * from sds_stk_deposito";
        $depositos = Sds_stk_deposito::findBySql($consulta)->all();
        $data = '';
        if (sizeof($depositos) > 0) {
            foreach ($depositos as $deposito) {
                $data =
                    $data .
                    "<option value='$deposito->iddeposito'>$deposito->descripcion-- </option>";
            }
        }
        //return $consulta;
        return $data;
    }

    public function actionGet_combo_expediente($id_articulo, $id_deposito)
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        $organismo = $usuario->organismo_stock;
        if ($organismo == null) {
            $organismo = 0;
        }
        $consulta_cantidad_tipo1 = "SELECT ifnull(SUM(Mo.cantidad),0) 
                                    FROM sds_stk_movimiento Mo 
                                    INNER JOIN sds_stk_recepcion_item RI ON Mo.item_recepcion = RI.idrecepcionitem
                                    INNER JOIN sds_stk_recepcion RE ON RI.idrecepcion = RE.idrecepcion
                                    WHERE Mo.tipo = 1 
                                    AND Mo.idarticulo = M.idarticulo 
                                    AND Mo.deposito_ingreso = D.iddeposito 
                                    AND RE.expediente = R.expediente";
        $consulta_cantidad_tipo2_ingreso = "SELECT ifnull(SUM(Mo.cantidad),0) 
                                        FROM sds_stk_movimiento Mo 
                                        INNER JOIN sds_stk_recepcion_item RI ON Mo.item_recepcion = RI.idrecepcionitem
                                        INNER JOIN sds_stk_recepcion RE ON RI.idrecepcion = RE.idrecepcion
                                        WHERE Mo.tipo = 2 
                                        AND Mo.idarticulo = M.idarticulo 
                                        AND Mo.deposito_ingreso = D.iddeposito
                                        AND RE.expediente = R.expediente";
        $consulta_cantidad_tipo2_egreso = "SELECT ifnull(SUM(Mo.cantidad),0)
                                            FROM sds_stk_movimiento Mo 
                                            INNER JOIN sds_stk_recepcion_item RI ON Mo.item_recepcion = RI.idrecepcionitem
                                            INNER JOIN sds_stk_recepcion RE ON RI.idrecepcion = RE.idrecepcion
                                            WHERE Mo.tipo = 2 
                                            AND Mo.idarticulo = M.idarticulo 
                                            AND Mo.deposito_egreso = D.iddeposito 
                                            AND RE.expediente = R.expediente";
        $consulta_cantidad_tipo3 = "SELECT IFNULL(SUM(Mo.cantidad),0) 
                                    FROM sds_stk_movimiento Mo 
                                    INNER JOIN sds_stk_entrega_item EI ON Mo.item_entrega = EI.identregaitem
                                    INNER JOIN sds_stk_recepcion_item RI ON EI.recepcion_item = RI.idrecepcionitem
                                    INNER JOIN sds_stk_recepcion RE ON RI.idrecepcion = RE.idrecepcion
                                    WHERE Mo.tipo = 3 
                                    AND Mo.idarticulo = M.idarticulo 
                                    AND Mo.deposito_egreso = D.iddeposito
                                    AND RE.expediente = R.expediente";
        $consulta_disponible = "($consulta_cantidad_tipo1)+($consulta_cantidad_tipo2_ingreso)-($consulta_cantidad_tipo2_egreso)-($consulta_cantidad_tipo3)";
        $consulta = "SELECT R.idrecepcion AS idrecepcion, R.expediente AS expediente, $consulta_disponible AS disponible
                    FROM sds_stk_movimiento M
                    INNER JOIN sds_stk_recepcion_item REI ON REI.idrecepcionitem = M.item_recepcion
                    INNER JOIN sds_stk_recepcion R ON REI.idrecepcion = R.idrecepcion
                    INNER JOIN sds_stk_deposito D ON D.iddeposito = M.deposito_ingreso
                    WHERE M.idarticulo = $id_articulo 
                    AND M.deposito_ingreso = $id_deposito 
                    AND $consulta_disponible > 0 
                    group by R.idrecepcion, R.expediente";
        $expedientes = Sds_stk_recepcion::findBySql($consulta)->all();
        $data = '';
        if (sizeof($expedientes) > 0) {
            foreach ($expedientes as $expediente) {
                $data .= "<option value='$expediente->idrecepcion'>$expediente->expediente (cantidad $expediente->disponible)</option>";
            }
        }
        return $data;
    }

    public function actionGet_disponibilidad_item(
        $id_articulo,
        $id_deposito,
        $id_recepcion_expediente
    ) {
        /* El expediente es unico por cada recepcion, 
            por lo que en el combos se muestra el mismo como descripcion y su id es el idrecepcion
            por lo que en las consultas, se usa el idrecepcion para referirse al expediente */
        $consulta_total_ingreso = "SELECT ifnull(SUM(Mo.cantidad),0) as cantidad
                                            FROM sds_stk_movimiento Mo
                                            INNER JOIN sds_stk_recepcion_item RI on Mo.item_recepcion = RI.idrecepcionitem
                                            INNER JOIN sds_stk_recepcion RE ON RI.idrecepcion = RE.idrecepcion
                                            WHERE Mo.tipo = 1 
                                                    and Mo.idarticulo = $id_articulo
                                                    and Mo.deposito_ingreso = $id_deposito
                                                    and RE.idrecepcion = $id_recepcion_expediente";

        $total_ingreso = Sds_stk_movimiento::findBySql(
            $consulta_total_ingreso
        )->one()->cantidad;

        $consulta_total_ingreso_interno = "SELECT ifnull(SUM(Mo.cantidad),0) as cantidad
                                            FROM sds_stk_movimiento Mo
                                            INNER JOIN sds_stk_recepcion_item RI on Mo.item_recepcion = RI.idrecepcionitem
                                            INNER JOIN sds_stk_recepcion RE ON RI.idrecepcion = RE.idrecepcion
                                            WHERE Mo.tipo = 2
                                                    and Mo.idarticulo = $id_articulo
                                                    and Mo.deposito_ingreso = $id_deposito
                                                    and RE.idrecepcion = $id_recepcion_expediente";

        $total_ingreso_interno = Sds_stk_movimiento::findBySql(
            $consulta_total_ingreso_interno
        )->one()->cantidad;

        $consulta_total_egreso_interno = "SELECT ifnull(SUM(Mo.cantidad),0) as cantidad
                                            FROM sds_stk_movimiento Mo 
                                            INNER JOIN sds_stk_recepcion_item RI on Mo.item_recepcion = RI.idrecepcionitem
                                            INNER JOIN sds_stk_recepcion RE ON RI.idrecepcion = RE.idrecepcion
                                            WHERE Mo.tipo = 2 
                                                and Mo.idarticulo = $id_articulo
                                                and Mo.deposito_egreso = $id_deposito 
                                                and RE.idrecepcion = $id_recepcion_expediente";

        $total_egreso_interno = Sds_stk_movimiento::findBySql(
            $consulta_total_egreso_interno
        )->one()->cantidad;

        $consulta_total_egreso = "SELECT ifnull(SUM(Mo.cantidad),0) as cantidad
                                        FROM sds_stk_movimiento Mo
                                        INNER JOIN sds_stk_entrega_item EI on Mo.item_entrega = EI.identregaitem
                                        INNER JOIN sds_stk_recepcion_item RI on EI.recepcion_item = RI.idrecepcionitem
                                        INNER JOIN sds_stk_recepcion RE ON RI.idrecepcion = RE.idrecepcion
                                        WHERE Mo.tipo = 3 
                                                and Mo.idarticulo = $id_articulo
                                                and Mo.deposito_egreso = $id_deposito
                                                and RE.idrecepcion = $id_recepcion_expediente";

        $total_egreso = Sds_stk_movimiento::findBySql(
            $consulta_total_egreso
        )->one()->cantidad;

        $disponible =
            $total_ingreso +
            $total_ingreso_interno -
            $total_egreso_interno -
            $total_egreso;

        //return "total_ingreso: $total_ingreso <br> total_ingreso_interno:$total_ingreso_interno <br> total_egreso_interno:$total_egreso_interno <br> total_egreso:$total_egreso <br> disponible: $disponible";
        //return $consulta_total_ingreso;
        return $disponible;
        //return "$id_articulo,$id_deposito,$id_recepcion_expediente";
        //return $disponible;
    }

    public function actionGrilla_items($identrega)
    {
        $columna1 = '35%';
        $columna2 = '20%';
        $columna3 = '30%';
        $columna5 = '10%';
        $columna6 = '5%';

        $dataProvider = new ActiveDataProvider([
            'query' => Sds_stk_entrega_item::findBySql(
                'Select * from sds_stk_entrega_item where identrega = ' .
                    $identrega
            ),
            'sort' => [
                'attributes' => [
                    'articulo',
                    'deposito',
                    'expediente',
                    'cantidad',
                ],
                'defaultOrder' => ['articulo' => SORT_DESC],
            ],
        ]);
        return GridView::widget([
            'id' => 'grilla_items',
            'dataProvider' => $dataProvider,
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'articulo',
                    'headerOptions' => ['style' => 'width:' . $columna1],
                    'value' => function ($model) {
                        /* $recepcion_item = Sds_stk_recepcion_item::findOne(
                            $model->recepcion_item
                        ); */
                        $articulo = Sds_stk_articulo::findOne(
                            $model->idarticulo
                        );
                        $medida = Sds_com_configuracion::findOne(
                            $articulo->unidad_medida
                        );
                        return "$articulo->descripcion (en $medida->descripcion)";
                    },
                    'label' => 'Articulo',
                ],
                [
                    'attribute' => 'deposito',
                    'headerOptions' => ['style' => 'width:' . $columna2],
                    'value' => function ($model) {
                        $movimiento = Sds_view_stock_detalle::find()
                            ->where("item_entrega = $model->identregaitem")
                            ->one();
                        $deposito = Sds_stk_deposito::findOne(
                            $movimiento->deposito
                        );
                        return $deposito->descripcion;
                    },
                    'label' => 'Deposito',
                ],
                [
                    'attribute' => 'expediente',
                    'headerOptions' => ['style' => 'width:' . $columna3],
                    'value' => function ($model) {
                        $recepcion_item = Sds_stk_recepcion_item::findOne(
                            $model->recepcion_item
                        );
                        $recepcion = Sds_stk_recepcion::findOne(
                            $recepcion_item->idrecepcion
                        );
                        $expediente = $recepcion->expediente;
                        if ($recepcion_item->idordencompraitem != null) {
                            $oc_item = Sds_stk_orden_compra_item::findOne(
                                $recepcion_item->idordencompraitem
                            );
                            $oc = Sds_stk_orden_compra::findOne(
                                $oc_item->idordencompra
                            );
                            $expediente =
                                'OC: ' .
                                $oc->numero .
                                '<br> Fecha Rec.: ' .
                                date('d/m/Y', strtotime($recepcion->fecha)) .
                                '<br> Expediente: ' .
                                $expediente;
                        }
                        return $expediente;
                    },
                    'format' => 'html',
                    'label' => 'Expediente',
                ],
                [
                    'attribute' => 'cantidad',
                    'headerOptions' => ['style' => 'width:' . $columna5],
                    'label' => 'Cantidad',
                ],
                [
                    'header' => Html::button(
                        '<i class="glyphicon glyphicon-plus"></i>',
                        [
                            'class' => 'btn btn-primary',
                            'id' => 'btnEntregaItem',
                            'title' => 'Nueva Entrega',
                            'data-toggle' => 'tooltip',
                            'onclick' => 'js:mostrar_abm_entrega_item();',
                        ]
                    ),
                    'template' => '',
                    'class' => 'yii\grid\ActionColumn',
                    'headerOptions' => ['style' => 'width:' . $columna6],
                    'template' => ' {eliminar}', // the default buttons + your custom button
                    'buttons' => [
                        'eliminar' => function ($url, $model) {
                            $id_entrega_item = $model->identregaitem;
                            return Html::button(
                                '<i class="glyphicon glyphicon-trash"></i>',
                                [
                                    'title' => 'Eliminar Item',
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-link',
                                    'onclick' => "js:eliminar_item($id_entrega_item);",
                                ]
                            );
                        },
                    ],
                ],
            ],
        ]);
    }

    public function actionGrilla_items_view($identrega)
    {
        $columna1 = '35%';
        $columna2 = '25%';
        $columna3 = '15%';
        $columna5 = '10%';

        $dataProvider = new ActiveDataProvider([
            'query' => Sds_stk_entrega_item::findBySql(
                'Select * from sds_stk_entrega_item where identrega = ' .
                    $identrega
            ),
            'sort' => [
                'attributes' => [
                    'articulo',
                    'deposito',
                    'expediente',
                    'cantidad',
                ],
                'defaultOrder' => ['articulo' => SORT_DESC],
            ],
        ]);
        return GridView::widget([
            'id' => 'grilla_items',
            'dataProvider' => $dataProvider,
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'articulo',
                    'headerOptions' => ['style' => 'width:' . $columna1],
                    'value' => function ($model) {
                        /* $recepcion_item = Sds_stk_recepcion_item::findOne(
                            $model->recepcion_item
                        ); */
                        $articulo = Sds_stk_articulo::findOne(
                            $model->idarticulo
                        );
                        $medida = Sds_com_configuracion::findOne(
                            $articulo->unidad_medida
                        );
                        return "$articulo->descripcion (en $medida->descripcion)";
                    },
                    'label' => 'Articulo',
                ],
                [
                    'attribute' => 'deposito',
                    'headerOptions' => ['style' => 'width:' . $columna2],
                    'value' => function ($model) {
                        $movimiento = Sds_stk_movimiento::find()
                            ->where("item_recepcion = $model->recepcion_item")
                            ->one();
                        $deposito = Sds_stk_deposito::findOne(
                            $movimiento->deposito_ingreso
                        );
                        return $deposito->descripcion;
                    },
                    'label' => 'Deposito',
                ],
                [
                    'attribute' => 'expediente',
                    'headerOptions' => ['style' => 'width:' . $columna3],
                    'value' => function ($model) {
                        $recepcion_item = Sds_stk_recepcion_item::findOne(
                            $model->recepcion_item
                        );
                        $recepcion = Sds_stk_recepcion::findOne(
                            $recepcion_item->idrecepcion
                        );
                        return $recepcion->expediente;
                    },
                    'label' => 'Expediente',
                ],
                [
                    'attribute' => 'cantidad',
                    'headerOptions' => ['style' => 'width:' . $columna5],
                    'label' => 'Cantidad',
                ],
            ],
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Sds_stk_entrega_item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(
                'The requested page does not exist.'
            );
        }
    }
}
