<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_stk_movimiento;
use app\models\Sds_stk_movimientoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Sds_stk_deposito;
use app\models\Sds_stk_recepcion_item;
use app\models\Model_multiple;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_movimiento_articulo;
use Exception;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Sds_com_configuracion;
use app\models\Sds_ent_entrega;
use yii\filters\AccessControl;

class Sds_stk_movimientoController extends Controller
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
                        'get_combo_deposito_destino',
                        'cmb_articulo',
                        'conversion_view',
                        'grilla_items_view',
                        'conversion',
                        'generar_ei',
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
                                'get_combo_deposito_destino',
                                'cmb_articulo',
                                'conversion_view',
                                'grilla_items_view',
                                'conversion',
                                'generar_ei',
                                'logout',
                            ],
                            'allow' => true,
                            // Allow users, moderators and admins to create
                            'roles' => [Mds_seg_item::STK_MOVIMIENTO],
                        ],
                    ],
                ],
            ];
        }


    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $searchModel = new Sds_stk_movimientoSearch();
        $searchModel->organismo = Yii::$app->user->identity->organismo_stock;
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
                    'title'=> "Movimiento Numero ".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_stk_movimiento();
        $model->scenario='create';
        $model->fecha_hora=date('d/m/Y');
        $model->tipo=Sds_stk_movimiento::TIPO_REUBICACION;
        if($request->isAjax){
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load($request->post()) && $model->validate()){
                $aux_date=$model->fecha_hora;//Para mantener el valor de la fecha en caso de error al guardar
                $model->fecha_hora=date('Y-m-d H:i:s', strtotime(str_replace('/','-',$model->fecha_hora)));
                if($model->save()){
                    Yii::$app->session->setFlash('save_model', 'Se realizó el cambio de deposito');
                    $model = new Sds_stk_movimiento();
                    $model->fecha_hora=date('d/m/Y');
                    $model->tipo=Sds_stk_movimiento::TIPO_REUBICACION;
                }else{
                    Yii::$app->session->setFlash('fail_save', 'Ocurrió un problema al realizar el cambio de deposito');
                    $model->fecha_hora=$aux_date;
                }
            }
            return [
                'title'=> "Cambio de Deposito",
                'content'=>$this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::button('Guardar',['class'=>'btn btn-primary','type' => 'submit'])
            ];
        }else{
            return $this->redirect(['/']);
        }
    }

    public function actionGet_combo_deposito_destino($id_deposito_origen)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $organismo_stock = Yii::$app->user->identity->organismo_stock;
        $depositos = Sds_stk_deposito::find()
            ->where(['idorganismo'=>($organismo_stock!=null?$organismo_stock:0)])
            ->andWhere(['<>', 'iddeposito', $id_deposito_origen])
            ->orderBy(['descripcion'=>SORT_ASC])
            ->all();
        $out_data=[];
        if(sizeof($depositos) > 0){
            foreach ($depositos as $deposito){
                if($deposito->activo){
                    array_push($out_data, $deposito);
                }
            }
        }
        return $out_data;
    }
    
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();
        if($request->isAjax){
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title'=> "Eliminado",
                'content'=>'<span class="text-success">Eliminado</span>',
                'footer'=> Html::button('cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
            ];
        }else{
            /* Process for non-ajax request */
            return $this->redirect(['index']);
        }
    }

    protected function findModel($id)
    {
        if(($model = Sds_stk_movimiento::findOne($id)) !== null) {
            return $model;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCmb_articulo($idarticulo)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $consulta = "SELECT a.idarticulo as idarticulo, concat(a.descripcion,' en ',c.descripcion) as descripcion 
                    FROM sds_stk_articulo a
                    JOIN sds_stk_articulo_conversion ac on a.idarticulo = ac.articulo_convertido
                    JOIN sds_com_configuracion c on a.unidad_medida = c.idconfiguracion
                    WHERE ac.articulo_base = $idarticulo";
        $articulos = Sds_stk_articulo::findBySql($consulta)->all();
        $cmbarticulo = "";
        if (sizeof($articulos) > 0) {
            foreach ($articulos as $articulo) {
                $cmbarticulo = $cmbarticulo ."<option value='" . $articulo->idarticulo . "'>" . $articulo->descripcion . "</option>";
            }
        } else {
            $cmbarticulo = "<option value=null></option>";
        }
        return $cmbarticulo;
    }
    
    public function actionConversion_view($id)
    {
        $model=$this->findModel($id);
        $m_articulos=Sds_stk_movimiento_articulo::find()->where(['idmovimiento'=>$model->idmovimiento])->all();
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Conversión Número ".$id,
                    'content'=>$this->renderAjax('conversion_view', [
                        'model' => $this->findModel($id),
                        'm_articulos' => $m_articulos,
                    ]),
                    'footer'=> Html::button('cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                ];    
        }else{
            return $this->render('conversion_view', [
                'model' => $this->findModel($id),
                'm_articulos' => $m_articulos,
            ]);
        }
    }

    public function actionGrilla_items_view($id)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Sds_stk_movimiento_articulo::find()->where(['idmovimiento'=>$id])->orderBy('idarticulo'),              
        ]);
        $dataProvider->pagination = false;
        return GridView::widget([
            'id' => 'grilla_items',
            'dataProvider' => $dataProvider,
            'summary' => '',
            'columns' => [
                [
                    'attribute' => 'idarticulo',
                    'headerOptions' => ['class' => 'titulo_grilla','style' => 'width:50%'],
                    'value' => function ($model) {
                        $articulo = Sds_stk_articulo::findOne($model->idarticulo);
                        $medida = Sds_com_configuracion::findOne($articulo->unidad_medida);
                        return "$articulo->descripcion (en $medida->descripcion)";
                    
                    },
                    'label'=>'Articulo',
                ],
                [
                    'attribute' => 'factor',
                    'headerOptions' => ['class' => 'titulo_grilla','style' => 'width:50%'],
                ],

            ],
        ]);
    }

    public function actionConversion($id=null)
    {
        if($id!=null){
            $model=$this->findModel($id);
            $m_articulos=Sds_stk_movimiento_articulo::find()->where(['idmovimiento'=>$model->idmovimiento])->all();
        }else{
            $model = new Sds_stk_movimiento;
            $m_articulos=[new Sds_stk_movimiento_articulo];
        }
        $request = Yii::$app->request;
        $model->scenario='conversion';
        $model->tipo=Sds_stk_movimiento::TIPO_CONVERSION;
        
        if($request->isAjax){
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load(Yii::$app->request->post())) {
                $model->deposito_ingreso=$model->deposito_egreso;
                $model->fecha_hora=date('Y-m-d H:i:s');
                $m_articulos = Model_multiple::createMultiple(Sds_stk_movimiento_articulo::class);
                Model_multiple::loadMultiple($m_articulos, Yii::$app->request->post());
                if($model->validate()){
                    $transaction = Yii::$app->db->beginTransaction();
                    try{
                        if($model->save()){
                            foreach ($m_articulos as $m_articulo){
                                $m_articulo->idmovimiento = $model->idmovimiento;
                                if(!$m_articulo->save()){
                                    throw new Exception('Hubo un error al guardar un articulo.');
                                }
                            }
                        }else{
                            throw new Exception('Hubo un error al guardar movimiento.');
                        }
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Los datos se guardaron de manera correcta.');
                        $model = new Sds_stk_movimiento;
                        $model->scenario='conversion';
                        $model->tipo=Sds_stk_movimiento::TIPO_CONVERSION;
                        $m_articulos=[new Sds_stk_movimiento_articulo];
                    }catch(Exception $e){
                        Yii::$app->session->setFlash('fail', $e->getMessage());
                        $transaction->rollBack();
                    }
                }
            }
        }else{
            return $this->redirect(['/']);
        }
        return [
            'title'=> "Conversión de Articulo",
            'content'=>$this->renderAjax('conversion', [
                'model' => $model,
                'm_articulos' => $m_articulos
            ]),
            'footer' => Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    ($model->isNewRecord?
                        Html::button('Guardar',['class'=>'btn btn-primary', 'type'=>"submit"]):
                        ' ')
        ];
    }
    
    public function actionGenerar_ei($idmovimiento)
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $model = $this->findModel($idmovimiento);
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : 0;
        $ri = Sds_stk_recepcion_item::findOne($model->item_recepcion);
        $articulo = Sds_stk_articulo::findOne($ri->idarticulo);
        $deposito_destino = Sds_stk_deposito::findOne($model->deposito_ingreso);
        $transaction = Yii::$app->db->beginTransaction();
        $model_ent_entrega = new Sds_ent_entrega();
        $model_ent_entrega->fecha_hora = $model->fecha_hora;
        $model_ent_entrega->cantidad = $model->cantidad;
        $model_ent_entrega->idtipo = $articulo->idtipo ? $articulo->idtipo : 0;
        $model_ent_entrega->observaciones = "Asignación a Depósito $deposito_destino->descripcion";
        $model_ent_entrega->idusuario = $idusuario;
        $model_ent_entrega->emisor = $ri->identrega;
        $model_ent_entrega->receptor = $deposito_destino->idresponsable;
        $model_ent_entrega->save(false);
        $model->generado = 1;
        if ($model->save(false)) {
            $transaction->commit();
        }
        $mensaje = "<p style='color: green;'>Primer entrega intermedia creada</p>";
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => "Entrega Intermedia",
            'content' => $mensaje,
            'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
        ];
    }

     /**
     * Delete multiple existing Sds_stk_movimiento model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
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