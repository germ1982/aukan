<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_stk_inventario;
use app\models\Sds_stk_inventarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_seg_usuario;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_inventario_item;
use app\models\Model_multiple;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use Exception;
use yii\filters\AccessControl;

class Sds_stk_inventarioController extends Controller
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
                    'index',
                    'create',
                    'update',
                    'delete',
                    'view',
                    'get_grilla',
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
                            'get_grilla',
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

    public function actionIndex($celular = false)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site']);
        }
        
        if ($celular) {
            return $this->render('index_mobile', [
                'searchModel' => null,
                'dataProvider' => null,
                'abrir_modal' => true,
            ]);
        }
        $searchModel = new Sds_stk_inventarioSearch();
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        $searchModel->idorganismo  = $usuario->organismo_stock;
        $disparar_flash = false;
        $descripcion_flash = "";
        if ($usuario->organismo_stock == null) {
            $disparar_flash = true;
            $descripcion_flash = 'Usted debe tener un organismo de stock asignado para acceder. <br>Comuníquese con un administrador.';
        }
        /*         if (!$disparar_flash) {
            $organismo = Mds_org_organismo::findOne($usuario->organismo_stock);
            if ($organismo->idrubro == null) {
                $disparar_flash = true;
                $descripcion_flash = 'Usted debe tener un rubro asignado para acceder. <br>Comuníquese con un administrador.';
            }
        } */
        if ($disparar_flash) {
            Yii::$app->session->setFlash(
                'error_modulo',
                $descripcion_flash
            );
            return Yii::$app->getResponse()->redirect(['site']);
        }
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
                'title' => "Editar Inventario Numero $id",
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['Editar', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionGet_grilla($idrubro)
    {
        $consulta = "SELECT a.idarticulo as idarticulo, 0 as cantidad
                        FROM sds_stk_articulo a
                        WHERE a.rubro = $idrubro ORDER BY a.descripcion";

        $model_inventario_items = Sds_stk_inventario_item::findBySql($consulta)->all();

        //return $consulta;


        return Yii::$app->controller->renderPartial('_form_grilla', ['model_inventario_items' => $model_inventario_items]);
    }

    public function actionCreate($celular = false)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site']);
        }
        $request = Yii::$app->request;
        $model = new Sds_stk_inventario();

        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        $idrubro = null;
        if ($usuario->organismo_stock) {
            $model->idorganismo = $usuario->organismo_stock;
            $idrubro = Mds_org_organismo::findOne($model->idorganismo)->idrubro;
        }
        $idrubro = $idrubro != null ? $idrubro : Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_RUBRO])->orderBy(['idconfiguracion' => SORT_ASC])->limit(1)->one()->idconfiguracion;

        $consulta = "SELECT a.idarticulo as idarticulo, 0 as cantidad
                        FROM sds_stk_articulo a
                        WHERE a.rubro = $idrubro ORDER BY a.descripcion";

        $model_inventario_items = Sds_stk_inventario_item::findBySql($consulta)->all();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post())) {
                $fecha = ArmarDateParaMySql($model->fecha_hora, $model->hora);
                $model->fecha_hora = date('Y-m-d H:i', strtotime($fecha));
                //$organismo = sds_

                $model_inventario_items = Model_multiple::createMultiple(Sds_stk_inventario_item::class);
                Model_multiple::loadMultiple($model_inventario_items, Yii::$app->request->post());

                if ($model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        if ($model->save()) {
                            foreach ($model_inventario_items as $model_inventario_item) {
                                $model_inventario_item->idinventario = $model->idinventario;
                                if (!$model_inventario_item->save(false)) {
                                    throw new Exception('Hubo un error al guardar un articulo.');
                                }
                            }
                            //$consulta = "SELECT * FROM sds_stk_inventario_item ii WHERE ii.idinventario = $model->idinventario";

                        } else {
                            throw new Exception('Hubo un error al guardar el inventario.');
                        }
                        $transaction->commit();
                        //Yii::$app->session->setFlash('success', 'Los datos se guardaron de manera correcta.');
                        return [
                            'title' => '<span class="text-success">Nuevo Inventario</span>',
                            'content' => '<span class="text-success">Inventariado exitosamente! </span>',
                            'footer' => Html::button($celular ? 'Finalizar Carga' : 'Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Crear Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                        ];
                    } catch (Exception $e) {
                        Yii::$app->session->setFlash('fail', $e->getMessage());
                        $transaction->rollBack();
                    }
                }
            }
        }
        return [
            'title' => "Nuevo inventario",
            'content' => $this->renderAjax('create', [
                'model' => $model,
                'model_inventario_items' => $model_inventario_items
            ]),
            'footer' => Html::button($celular ? 'Finalizar Carga' : 'Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
        ];
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        //$idrubro = Mds_org_organismo::findOne($model->idorganismo)->idrubro;
        $idarticulo = Sds_stk_inventario_item::find()->where("idinventario = $id")->orderBy(['idinventario' => SORT_ASC])->limit(1)->one()->idarticulo;
        $idrubro = Sds_stk_articulo::findOne($idarticulo)->rubro;
        $consulta = "SELECT ifnull(ii.idinventarioitem,0) as idinventarioitem, $id as idinventario, a.idarticulo as idarticulo, ifnull(ii.cantidad,0) as cantidad
                            FROM sds_stk_inventario_item ii
                            RIGHT JOIN sds_stk_articulo a on ii.idarticulo = a.idarticulo
                            WHERE a.rubro = $idrubro and ii.idinventario  = $id  ORDER BY a.descripcion";


        $model_inventario_items = Sds_stk_inventario_item::findBySql($consulta)->all();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post())) {
                $fecha = ArmarDateParaMySql($model->fecha_hora, $model->hora);
                $model->fecha_hora = date('Y-m-d H:i', strtotime($fecha));

                //$model_inventario_items = Model_multiple::createMultiple(Sds_stk_inventario_item::class);
                Model_multiple::loadMultiple($model_inventario_items, Yii::$app->request->post());

                if ($model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        if ($model->save()) {
                            foreach ($model_inventario_items as $model_inventario_item) {
                                //$maux = Sds_stk_inventario_item::findOne()

                                //print_r($model_inventario_item->attributes);
                                if (!$model_inventario_item->save(false)) {
                                    throw new Exception('Hubo un error al guardar un articulo.');
                                }
                            }
                        } else {
                            throw new Exception('Hubo un error al guardar el inventario.');
                        }
                        $transaction->commit();
                        return [
                            'title' => '<span class="text-success">Editado correctamente</span>',
                            'content' => '<span class="text-success">Se ha editado correctamente</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                Html::a('Crear otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                        ];
                    } catch (Exception $e) {
                        Yii::$app->session->setFlash('fail', $e->getMessage());
                        $transaction->rollBack();
                    }
                }
            }
        }
        return [
            'title' => "Nuevo inventario",
            'content' => $this->renderAjax('create', [
                'model' => $model,
                'model_inventario_items' => $model_inventario_items
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
        ];
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            return $this->redirect(['index']);
        }
    }

    protected function findModel($id)
    {
        if (($model = Sds_stk_inventario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Delete multiple existing Sds_stk_inventario model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
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
    }
    public function actionCreate_old()
    {
        $request = Yii::$app->request;
        $model = new Sds_stk_inventario();
        $usuario = Yii::$app->user->identity;
        //$idrubro = Mds_org_organismo::findOne($usuario->organismo_stock)->idrubro;

        $model_inventario_items = [new Sds_stk_inventario_item];

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) //renderiza form
            {
                return [
                    'title' => "Nuevooooooooooooo inventario",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'model_inventario_items' => $model_inventario_items
                    ]),
                    'footer' => Html::button('Cancelar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                /* $model_inventario_items = Model_multiple::createMultiple(Sds_stk_inventario_item::class);
                    Model_multiple::loadMultiple($model_inventario_items, Yii::$app->request->post()); *

                $fecha = ArmarDateParaMySql($model->fecha_hora, $model->hora);
                $model->fecha_hora = date('Y-m-d H:i', strtotime($fecha));

                if ($guardado && $model->save()) {
                    $transaction->commit();
                    return [
                        'title' => '<span class="text-success">Creado correctamente</span>',
                        'content' => '<span class="text-success">Se ha creado </span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Crear otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {
                    return [
                        'title' => '<span class="text-danger">Error</span>',
                        'content' => '<span class="text-danger">Ocurrio un error de datos, no se guardo.</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Intentar otra vez', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'title' => "Nuevooooooooooooo inventario",
            'content' => $this->renderAjax('create', [
                'model' => $model,
                'model_inventario_items' => $model_inventario_items
            ]),
            'footer' => Html::button('Cancelar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

        ];
    } 
    public function actionUpdate_old($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Editar Inventario $id",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('cancelar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;


                $fecha = ArmarDateParaMySql($model->fecha_hora, $model->hora);
                $model->fecha_hora = date('Y-m-d H:i', strtotime($fecha));

                if ($guardado && $model->save()) {
                    $transaction->commit();
                    return [
                        'title' => '<span class="text-success">Editado correctamente</span>',
                        'content' => '<span class="text-success">Se ha editado correctamente</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Crear otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                } else {
                    return [
                        'title' => '<span class="text-danger">Error</span>',
                        'content' => '<span class="text-danger">Ocurrio un error de datos, no se guardo.</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Intentar otra vez', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                }
            }
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
