<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_stk_orden_compra;
use app\models\Sds_stk_orden_compraSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_seg_usuario;
use app\models\Mds_sys_log;
use app\models\Sds_ent_entrega;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_orden_compra_item;
use yii\filters\AccessControl;

/**
 * Sds_stk_orden_compraController implements the CRUD actions for Sds_stk_orden_compra model.
 */
class Sds_stk_orden_compraController extends Controller
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
                    'generar_pi',
                    'get_expediente',
                    'get_cmb_ordenes_compra',
                    'get_cmb_ordenes_compra_graph',
                    'logout',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            /* 'delete', */
                            'update',
                            'view',
                            'generar_pi',
                            'get_expediente',
                            'get_cmb_ordenes_compra',
                            'get_cmb_ordenes_compra_graph',
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

    /**
     * Lists all Sds_stk_orden_compra models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $searchModel = new Sds_stk_orden_compraSearch();
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        if ($usuario->organismo_stock) {
            $searchModel->idorganismo = $usuario->organismo_stock;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_stk_orden_compra', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_stk_orden_compra', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $boton_editar = $this->findModel($id)->generada ? '' : Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote']);
            return [
                'title' => "Orden de compra Numero 
                    " . $this->findModel($id)->numero,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) . $boton_editar
            ];
        } else {
            return $this->redirect(['index']);
            /* return $this->render('view', [
                'model' => $this->findModel($id),
            ]); */
        }
    }

    public function actionCreate()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $request = Yii::$app->request;
        $model = new Sds_stk_orden_compra();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nueva Orden De Compra",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {

                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $usuario = Yii::$app->user->identity;
                $idusuario = $usuario != null ? $usuario->idusuario : null;
                $usuario = Mds_seg_usuario::findOne($idusuario);
                if ($usuario->organismo_stock) {
                    $model->idorganismo = $usuario->organismo_stock;
                }

                $fecha = ArmarDateParaMySql($model->fecha_emision, '00:00');
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->fecha_emision = $fecha;

                $fecha = ArmarDateParaMySql($model->vencimiento, '00:00');
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->vencimiento = $fecha;
                $model->importe_total = 0;

                if ($model->fecha_orden_compra) {
                    $fecha = ArmarDateParaMySql($model->fecha_orden_compra, '00:00');
                    $fecha = date_create($fecha);
                    $fecha = date_format($fecha, 'Y-m-d');
                    $model->fecha_orden_compra = $fecha;
                }



                if ($guardado && $model->save()) {
                    $transaction->commit();
                    return [
                        'title' => "Se ha guardado la orden de compra numero: $model->numero",
                        'content' => $this->renderAjax('view', ['model' => $model,]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Añadir Articulos', ['update', 'id' => $model->idordencompra], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {
                    $aux = "Organismo: $model->idorganismo <br>
                                Fecha Emision:  $model->fecha_emision<br>
                                Fecha Vencimiento:  $model->vencimiento<br>
                                Proveedor: $model->proveedor<br>
                                Numero: $model->numero<br>
                                Tipo norma legal: $model->tipo_norma_legal<br>
                                Norma Legal: $model->norma_legal<br>
                                Importe Total: $model->importe_total<br>
                                Expediente: $model->expediente";

                    return [
                        'title' => "Error",
                        'content' => '<span class="text-success">' . $aux . '</span>',
                        'footer' => Html::button('Cerrar Recepcion', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                return [
                    'title' => "Nueva Orden De Compra",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }
        } else {
            return $this->redirect(['index']);
            /* if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idordencompra]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            } */
        }
    }

    public function actionUpdate($id, $items = 0)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Editar Orden de Compra Numero " . $this->findModel($id)->numero,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'items' => $items,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {

                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $usuario = Yii::$app->user->identity;
                $idusuario = $usuario != null ? $usuario->idusuario : null;
                $usuario = Mds_seg_usuario::findOne($idusuario);
                if ($usuario->organismo_stock) {
                    $model->idorganismo = $usuario->organismo_stock;
                }

                if ($model->fecha_orden_compra) {
                    $fecha = ArmarDateParaMySql($model->fecha_orden_compra, '00:00');
                    $fecha = date_create($fecha);
                    $fecha = date_format($fecha, 'Y-m-d');
                    $model->fecha_orden_compra = $fecha;
                }

                $fecha = ArmarDateParaMySql($model->fecha_emision, '00:00');
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->fecha_emision = $fecha;

                $fecha = ArmarDateParaMySql($model->vencimiento, '00:00');
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->vencimiento = $fecha;
                $model->importe_total = str_replace("$", "", $model->importe_total);
                if ($guardado && $model->save(false)) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_stk_orden_compra', $model->idordencompra, $model->getAttributes());
                    return [
                        'title' => "Se ha guardado la orden de compra numero: $model->numero",
                        'content' => $this->renderAjax('view', ['model' => $model,]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Añadir Articulos', ['update', 'id' => $model->idordencompra], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {
                    $aux = "Organismo: $model->idorganismo <br>
                                Fecha Emision:  $model->fecha_emision<br>
                                Fecha Vencimiento:  $model->vencimiento<br>
                                Proveedor: $model->proveedor<br>
                                Numero: $model->numero<br>
                                Tipo norma legal: $model->tipo_norma_legal<br>
                                Norma Legal: $model->norma_legal<br>
                                Importe Total: $model->importe_total<br>
                                Expediente: $model->expediente";

                    return [
                        'title' => "Error",
                        'content' => '<span class="text-success">' . $aux . '</span>',
                        'footer' => Html::button('Cerrar Recepcion', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                return [
                    'title' => "Editar Orden de Compra Numero " . $this->findModel($id)->numero,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            return $this->redirect(['index']);
            /* if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_stk_orden_compra', $model->idordencompra, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idordencompra]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            } */
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete()) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_stk_orden_compra', $id, $model->getAttributes());
        }
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Eliminado",
                'content' => '<span class="text-success">Se a eliminado la orden de compra</span>',
                'footer' => Html::button('Cerrar ', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionGenerar_pi($id_oc)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $items_oc = Sds_stk_orden_compra_item::find()->where("idordencompra=$id_oc")->all();
        $ban = 1;
        $mensaje = "";
        foreach ($items_oc as $item_oc) {
            $articulo = Sds_stk_articulo::findOne($item_oc->idarticulo);
            if ($articulo->idtipo == null) {
                $ban = 0;
                $mensaje = "<p style='color: red;'>No se realizo la primer entrega porque hay articulos sin tipo.</p>";
            }
        }

        if ($ban == 1) {
            $model = $this->findModel($id_oc);

            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : 0;
            foreach ($items_oc as $item_oc) {
                $model_ent_entrega = new Sds_ent_entrega();

                $fecha = "$model->fecha_orden_compra 00:00";
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d H:i');
                $model_ent_entrega->fecha_hora = $fecha;

                $model_ent_entrega->cantidad = $item_oc->cantidad;

                $articulo = Sds_stk_articulo::findOne($item_oc->idarticulo);

                $model_ent_entrega->idtipo = $articulo->idtipo ? $articulo->idtipo : 0;
                $model_ent_entrega->observaciones = $articulo->observaciones;
                $model_ent_entrega->idusuario = $idusuario;
                $model_ent_entrega->receptor = Sds_stk_orden_compra::RECEPTOR;
                $model_ent_entrega->oc = $model->numero;
                $model_ent_entrega->proveedor = $model->proveedor;

                $model_ent_entrega->save(false);
                $item_oc->identrega = $model_ent_entrega->identrega;
                $item_oc->save();
            }

            $model->generada = 1;
            $model->save();
            $mensaje = "<p style='color: green;'>Primer Ingreso Generado</p>";
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => "Primer Ingreso ",
            'content' => $mensaje,
            'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
        ];
    }

    public function actionGet_expediente($idordencompra)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        return $idordencompra != null ? Sds_stk_orden_compra::findOne($idordencompra)->expediente : "";
    }

    public function actionGet_cmb_ordenes_compra($id_proveedor)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $organismo = Yii::$app->user->identity->organismo_stock;
        if ($organismo == null) {
            $organismo = 0;
        }
        $sql_cantidad_oci = "SELECT ifnull(SUM(sri.cantidad),0)
                                FROM sds_stk_orden_compra_item soci
                                INNER JOIN sds_stk_recepcion_item sri ON sri.idordencompraitem = soci.idordencompraitem
                                WHERE soci.idordencompra=oc.idordencompra";

        $sql_pendiente = "SELECT (ifnull(SUM(oci.cantidad),0) - ($sql_cantidad_oci))
                            FROM sds_stk_orden_compra_item oci
                            WHERE oci.idordencompra=oc.idordencompra";

        $sql_completo  = "SELECT oc.idordencompra, oc.numero,($sql_pendiente) as Pendiente
                            FROM sds_stk_orden_compra oc
                            WHERE oc.proveedor = $id_proveedor
                            AND oc.idorganismo = $organismo
                            AND ($sql_pendiente)>0";

        $ordenes_compra = Sds_stk_orden_compra::findBySql($sql_completo)->all();
        $data = "<option value='0'></option>";
        if (sizeof($ordenes_compra) > 0) {
            foreach ($ordenes_compra as $orden_compra) {
                $data = $data . "<option value='$orden_compra->idordencompra'>$orden_compra->numero</option>";
            }
        }
        return $data;
    }

    public function actionGet_cmb_ordenes_compra_graph($idarticulo, $idorganismo)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $ordenes_compra = Sds_stk_orden_compra::find()->where(
            "idordencompra in (select idordencompra from view_stock_detalle_oc) and idorganismo=$idorganismo 
            and idordencompra in (select idordencompra from sds_stk_orden_compra_item it where it.idarticulo=$idarticulo)"
        )->all();
        $data = "";
        if (sizeof($ordenes_compra) > 0) {
            foreach ($ordenes_compra as $orden_compra) {
                $data = $data . "<option value='$orden_compra->idordencompra'>$orden_compra->numero</option>";
            }
        }
        return $data;
    }

    protected function findModel($id)
    {
        if (($model = Sds_stk_orden_compra::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /* public function actionBulkDelete()
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
    } */
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
