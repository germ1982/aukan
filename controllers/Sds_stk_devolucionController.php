<?php

namespace app\controllers;

use Yii;
use app\models\Sds_stk_devolucion;
use app\models\Sds_stk_devolucionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\mpdf\Pdf;

class Sds_stk_devolucionController extends Controller
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
        ];
    }

    public function actionIndex()
    {    
        $searchModel = new Sds_stk_devolucionSearch();
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
                    'title'=> "Movimiento De Devolucion Numero $id",
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar', ['id' => 'btnCerrar', 'class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Sds_stk_devolucion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_stk_devolucion();  

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Nuevo Movimiento Devolucion",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar', ['id' => 'btnCerrar', 'class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar', ['id' => 'btnGuardar','class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                $transaction = Yii::$app->db->beginTransaction();
                $fecha = ArmarDateParaMySql($model->fecha_hora_entrega, $model->hora_entrega);
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d H:m');
                $model->fecha_hora_entrega = $fecha;

                if ($model->save(false)) {
                    $transaction->commit();
                    return [
                        
                        'title'=> "Nuevo Movimiento Devolucion",
                        'content' => '<span class="text-success">Movimiento de Devolucion Creado Correctamente</span>',
                        'footer'=> Html::button('Cerrar', ['id' => 'btnCerrar', 'class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Crear Otro',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
            
                    ];
                    
                }
                else{
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Nuevo Movimiento Devolucion",
                        'content' => '<span class="text-danger">Error, No Se Guardo</span>',
                        'footer'=> Html::button('Cerrar', ['id' => 'btnCerrar', 'class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    ];
                }

                         
            }else{           
                return [
                    'title'=> "Nuevo Movimiento Devolucion",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar', ['id' => 'btnCerrar', 'class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar', ['id' => 'btnGuardar','class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{

            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->iddevolucion]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    public function actionImprimir_acta_entrega($identrega)
    {
        $content = $this->renderPartial('imprimir_acta_entrega', [
            'identrega' => $identrega,
        ]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'ACTA DE ENTREGA',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }


    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Editar Movimiento de Devolucion Numero $id",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar', ['id' => 'btnCerrar', 'class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar', ['id' => 'btnGuardar','class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                $transaction = Yii::$app->db->beginTransaction();
                $fecha = ArmarDateParaMySql($model->fecha_hora_entrega, $model->hora_entrega);
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d H:m');
                $model->fecha_hora_entrega = $fecha;

                $fecha = ArmarDateParaMySql($model->fecha_hora_devolucion, $model->hora_devolucion);
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d H:m');
                $model->fecha_hora_devolucion = $fecha;

                if ($model->save(false)) {
                    $transaction->commit();
                    return [
                        
                        'title'=> "Editar Movimiento Devolucion Numero $id",
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Cerrar', ['id' => 'btnCerrar', 'class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
            
                    ];
                    
                }
                else{
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Nuevo Movimiento Devolucion",
                        'content' => '<span class="text-danger">Error, No Se Guardo</span>',
                        'footer'=> Html::button('Cerrar', ['id' => 'btnCerrar', 'class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    ];
                }
   
            }else{
                 return [
                    'title'=> "Editar Movimiento de Devolucion Numero $id",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar', ['id' => 'btnCerrar', 'class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar', ['id' => 'btnGuardar','class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->iddevolucion]);
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
        if($this->findModel($id)->delete()){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title'=> "Eliminado",
                'content' => '<span class="text-danger">Eliminado</span>',
                'footer'=> Html::button('Cerrar', ['id' => 'btnCerrar', 'class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
            ];
        }else{

            return $this->redirect(['index']);
        }


    }


    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

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

    /**
     * Finds the Sds_stk_devolucion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_stk_devolucion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_stk_devolucion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
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