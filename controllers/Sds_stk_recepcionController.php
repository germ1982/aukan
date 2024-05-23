<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use app\models\Sds_stk_recepcion_item;

use Yii;
use app\models\Sds_stk_recepcion;
use app\models\Sds_stk_recepcionSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use app\models\Sds_stk_movimiento;
use app\models\Sds_stk_orden_compra;
use app\models\Sds_stk_orden_compra_item;
use yii\base\Model;
use app\models\Sds_ent_entrega;
use app\models\Sds_stk_articulo;


class Sds_stk_recepcionController extends Controller
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
                    'create',
                    'update',
                    'delete',
                    'view',
                    'validar_orden_compra',
                    'generar_ei',
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
                            'validar_orden_compra',
                            'generar_ei',
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

    public function actionIndex($celular = false)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        if ($celular) {
            return $this->render('index_mobil', [
                'searchModel' => null,
                'dataProvider' => null,
                'abrir_modal' => true,
            ]);
        }
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);

        $searchModel = new Sds_stk_recepcionSearch();

        if ($usuario->organismo_stock) {
            $id_organismo = $usuario->organismo_stock;
            $searchModel->organismo = $id_organismo;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA,'sds_stk_recepcion',null,[]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA,'sds_stk_recepcion',$id,[]);
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $boton_editar = $this->findModel($id)->generada ? '' : Html::a('Editar',['update', 'id' => $id],['class' => 'btn btn-primary', 'role' => 'modal-remote']);
            return [
                'title' => 'Recepcion Numero ' . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' =>
                    Html::button('Cerrar Recepcion', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .$boton_editar,
                    
            ];
        } else {
            return $this->redirect(['index']);
            /* return $this->render('view', [
                'model' => $this->findModel($id),
            ]); */
        }
    }

    public function actionCreate($celular = false)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $request = Yii::$app->request;
        $model = new Sds_stk_recepcion();
        $model->pedido = '0';
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        if ($usuario->organismo_stock) {
            $model->organismo = $usuario->organismo_stock;
        }
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Nueva Recepcion De Insumos',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                        Html::button(
                            $celular ? 'Crear Otra' : 'Cerrar Recepcion',
                            [
                                'id' => 'btnCerrar',
                                'class' => 'btn btn-default pull-left',
                                'data-dismiss' => 'modal',
                            ]
                        ) .
                        Html::button('Guardar Recepcion', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            } elseif ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $fecha = ArmarDateParaMySql($model->fecha, '00:00');
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->fecha = $fecha;
                if ($model->idordencompra == 0) {
                    $model->idordencompra = null;
                }
                if ($model->save()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(
                        Mds_sys_log::ACCION_NUEVO,
                        'sds_stk_recepcion',
                        $model->idrecepcion,
                        $model->getAttributes()
                    );
                    return [
                        'title' => "Se ha guardado la recepcion numero: $model->idrecepcion",
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' =>
                            Html::button(
                                $celular ? 'Crear Otra' : 'Cerrar Recepcion',
                                [
                                    'id' => 'btnCerrar',
                                    'class' => 'btn btn-default pull-left',
                                    'data-dismiss' => 'modal',
                                ]
                            ) .
                            Html::a(
                                'Añadir Articulos',
                                [
                                    'update',
                                    'id' => $model->idrecepcion,
                                    'celular' => $celular ? true : false,
                                ],
                                [
                                    'class' => 'btn btn-primary',
                                    'role' => 'modal-remote',
                                ]
                            ),
                    ];
                } else {
                    $aux =
                        "Organismo: $model->organismo <br>
                                Fecha:  $model->fecha<br>
                                Proveedor: $model->proveedor<br>
                                Pedido: $model->pedido<br>
                                Expediente: $model->expediente<br>
                                idordencompra: $model->idordencompra<br>
                                errores: " . print_r($model->getErrors(), true);
                    return [
                        'title' =>
                            '<span class="text-danger">Error, Revise los datos</span>',
                        'content' =>
                            '<span class="text-danger">' . $aux . '</span>',
                        'footer' => Html::button(
                            $celular ? 'Crear Otra' : 'Cerrar Recepcion',
                            [
                                'id' => 'btnCerrar',
                                'class' => 'btn btn-default pull-left',
                                'data-dismiss' => 'modal',
                            ]
                        ),
                    ];
                }
            } else {
                return [
                    'title' => 'Nueva Recepcion De Insumos',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                        Html::button(
                            $celular ? 'Crear Otra' : 'Cerrar Recepcion',
                            [
                                'id' => 'btnCerrar',
                                'class' => 'btn btn-default pull-left',
                                'data-dismiss' => 'modal',
                            ]
                        ) .
                        Html::button('Guardar Recepcion', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            }
        }else{
            return $this->redirect(['index']);
        }
    }

    public function actionUpdate($id, $celular = false, $items = 0)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Editando Recepcion Numero: ' . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'items' => $items,
                    ]),
                    'footer' =>
                        Html::button(
                            $celular ? 'Crear Otra' : 'Cerrar Recepcion',
                            [
                                'id' => 'btnCerrar',
                                'class' => 'btn btn-default pull-left',
                                'data-dismiss' => 'modal',
                            ]
                        ) .
                        Html::button('Guardar Recepcion', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            } elseif ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $fecha = ArmarDateParaMySql($model->fecha, '00:00');
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->fecha = $fecha;

                if ($model->idordencompra == 0) {
                    $model->idordencompra = null;
                }

                if ($guardado && $model->save()) {
                    
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR,'sds_stk_recepcion',$model->idrecepcion,$model->getAttributes());
                    $items_recepcion = Sds_stk_recepcion_item::find()->where("idrecepcion=$model->idrecepcion")->all();
                    foreach ($items_recepcion as $item_recepcion) {
                        $aux_where = "item_recepcion = $item_recepcion->idrecepcionitem and idarticulo = $item_recepcion->idarticulo and cantidad = $item_recepcion->cantidad and tipo = 1";
                        $item_recepcion_movimiento = Sds_stk_movimiento::find()->where($aux_where)->one();
                        $item_recepcion_movimiento->fecha_hora = $fecha;

                        if($item_recepcion_movimiento->save()){
                            if($model->idordencompra==null){
                                $item_recepcion->idordencompraitem = null;
                            }else{
                                $ioc = Sds_stk_orden_compra_item::find()->where("idordencompra=$model->idordencompra and idarticulo = $item_recepcion->idarticulo")->one();
                                $item_recepcion->idordencompraitem = $ioc->idordencompraitem;
                            }
                            $item_recepcion->save(false);
                        }else{
                            $guardado=false;
                        }
                    }
                    if($guardado){$transaction->commit();}
                    return [
                        'title' => 'Se editó la Recepción Número: ' . $id,
                        'content' => $this->renderAjax('view', ['model' => $model,]),
                        'footer' =>
                            Html::button(
                                $celular ? 'Crear Otra' : 'Cerrar Recepcion',
                                [
                                    'id' => 'btnCerrar',
                                    'class' => 'btn btn-default pull-left',
                                    'data-dismiss' => 'modal',
                                ]
                            ) .
                            Html::a(
                                'Añadir Articulos',
                                [
                                    'update',
                                    'id' => $model->idrecepcion,
                                    'celular' => $celular ? true : false,
                                ],
                                [
                                    'class' => 'btn btn-primary',
                                    'role' => 'modal-remote',
                                ]
                            ),
                    ];
                } else {
                    $aux = "Organismo: $model->organismo <br>
                            Fecha:  $model->fecha<br>
                            Proveedor: $model->proveedor<br>
                            Pedido: $model->pedido<br>
                            Expediente: $model->expediente<br>
                            idordencompra: $model->idordencompra";
                    return [
                        'title' =>
                            '<span class="text-danger">Error, revise los datos</span>',
                        'content' =>
                            '<span class="text-danger">' . $aux . '</span>',
                        'footer' => Html::button(
                            $celular ? 'Crear Otra' : 'Cerrar Recepcion',
                            [
                                'id' => 'btnCerrar',
                                'class' => 'btn btn-default pull-left',
                                'data-dismiss' => 'modal',
                            ]
                        ),
                    ];
                }
            } else {
                return [
                    'title' => 'Editando Recepcion Numero: ' . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' =>
                        Html::button(
                            $celular ? 'Crear Otra' : 'Cerrar Recepcion',
                            [
                                'id' => 'btnCerrar',
                                'class' => 'btn btn-default pull-left',
                                'data-dismiss' => 'modal',
                            ]
                        ) .
                        Html::button('Guardar Recepcion', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            }
        } else {
            return $this->redirect(['index']);
            /* if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR,'sds_stk_recepcion',$model->idrecepcion,$model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idrecepcion]);
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

        if ($request->isAjax) {
            if ($model->delete() > 0) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR,'sds_stk_recepcion',$id,$model->getAttributes());
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => 'Recepcion Eliminada',
                'content' =>
                    '<span class="text-danger">Se ha eliminado la recepcion numero ' .
                    $id .
                    '</span>',
                'footer' => Html::button('Cerrar', [
                    'class' => 'btn btn-default pull-left col-md-offset-5',
                    'data-dismiss' => 'modal',
                ]),
            ];
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionValidar_orden_compra($idrecepcion, $idordencompra)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        $ban = 1;
        $items_recepcion = Sds_stk_recepcion_item::find()
            ->where(['idrecepcion' => $idrecepcion])
            ->all();

        foreach ($items_recepcion as $item_recepcion) {
            $item_orden_compra = Sds_stk_orden_compra_item::find()
                ->where([
                    'idordencompra' => $idordencompra,
                    'idarticulo' => $item_recepcion->idarticulo,
                ])
                ->one();
            if ($item_orden_compra) {
                $recepcionado = Sds_stk_recepcion_item::find()
                    ->where([
                        'idordencompraitem' =>
                            $item_orden_compra->idordencompraitem,
                    ])
                    ->sum('cantidad');
                $restante = $item_orden_compra->cantidad - $recepcionado;
                if ($item_recepcion->cantidad > $restante) {
                    $ban = 2;
                }
            } else {
                $ban = 0;
            }
        }

        return $ban;
    }

    public function actionGenerar_ei($idrecepcion)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        $items_r = Sds_stk_recepcion_item::find()->where("idrecepcion=$idrecepcion")->all();
        $ban=1;
        $mensaje = "";
        foreach ($items_r as $item_r) {
            $articulo = Sds_stk_articulo::findOne($item_r->idarticulo);
            if($articulo->idtipo==null)
                {
                    $ban=0;
                    $mensaje = "<p style='color: red;'>No se realizo la Entrega Intermedia porque hay articulos sin tipo.</p>";
                }
        }
        
        if($ban==1)
        {
            $model = $this->findModel($idrecepcion);
            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : 0;

            $oc = Sds_stk_orden_compra::findOne($model->idordencompra);
            $fecha_oc = date_create("$oc->fecha_orden_compra 00:00");
            $fecha_oc_emision = date_create("$oc->fecha_emision 00:00");
            $fecha_ent_entrega = date_create("$model->fecha 00:00");
            
            $fecha_dif = $fecha_oc->diff($fecha_oc_emision);//esta funcion resta fechas
            $aux = $fecha_dif->format("%R%a");//el intervalo resultante es tipo date y para verlo nesesito formatearlo

            $fecha_ent_entrega = date_add($fecha_ent_entrega,date_interval_create_from_date_string("$aux days"));
            //para usmar uso el date add, y pasarle el intervalo en string, el cual convierte con ese chorizo

            $fecha_ent_entrega = date_format($fecha_ent_entrega,"Y-m-d H:i");
            $transaction = Yii::$app->db->beginTransaction();

            foreach ($items_r as $item_r) {
                $model_ent_entrega = new Sds_ent_entrega();

                $model_ent_entrega->fecha_hora = $fecha_ent_entrega;

                $model_ent_entrega->cantidad = $item_r->cantidad;

                $articulo = Sds_stk_articulo::findOne($item_r->idarticulo);

                $model_ent_entrega->idtipo = $articulo->idtipo ? $articulo->idtipo : 0;
                $model_ent_entrega->observaciones = $articulo->observaciones;
                $model_ent_entrega->idusuario = $idusuario;
                $model_ent_entrega->receptor = Sds_stk_recepcion::RECEPTOR;

                $oci=null;
                if($item_r->idordencompraitem)
                    {$oci = Sds_stk_orden_compra_item::findOne($item_r->idordencompraitem);}

                if($oci)
                    {$model_ent_entrega->emisor = $oci->identrega;}

                $model_ent_entrega->save(false);

                $item_r->identrega = $model_ent_entrega->identrega;
                $item_r->save();

            }

            $model->generada = 1;
            if($model->save())
                {
                    $transaction->commit();
                }
            $mensaje = "<p style='color: green;'>Entrega Intermedia Generada</p>";
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => "Entrega Intermedia ",
            'content' => $mensaje,
            'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) 
        ];
    }
    
    protected function findModel($id)
    {
        if (($model = Sds_stk_recepcion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(
                'The requested page does not exist.'
            );
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

            return [
                'forceClose' => true,
                'forceReload' => '#crud-datatable-pjax',
            ];
        } else {
            return $this->redirect(['index']);
        }
    } */
}
function ArmarDateParaMySql($Fecha, $Hora)
{
    $anio = substr($Fecha, 6, 4);
    $mes = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $H = substr($Hora, 0, 2);
    $m = substr($Hora, 3, 2);
    $DT = "$anio-$mes-$dia $H:$m:00";
    return $DT;
}
