<?php

namespace app\controllers;

use Yii;
use app\models\Mds_inv_entrega;
use app\models\Sds_com_persona;
use app\models\Mds_inv_entregaSearch;
use app\models\Mds_seg_item;
use app\components\AccessRule;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
/**
 * Mds_inv_entregaController implements the CRUD actions for Mds_inv_entrega model.
 */
class Mds_inv_entregaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],                   
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index', 'index_plantines','view','create', 'update', 'delete' ],
                'rules' => [
                    [
                        'actions' =>['index', 'index_plantines','view','create', 'update', 'delete'],
                        'allow' => true,
                        // Solo Admin Rumbo
                        'roles' => [
                            Mds_seg_item::MODULO_INV_PERSONA,                           
                        ],
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all Mds_inv_entrega models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Mds_inv_entregaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionIndex_plantines($idpersona ) // 
    {
        $searchModel = new Mds_inv_entregaSearch();
        $searchModel->idpersona = $idpersona;          

        $un_com = Sds_com_persona::findOne($idpersona);

        $titulo = "PLANTINES::Registro de " . $un_com->nombre ." ".$un_com->apellido ;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_postulacion', null, array('idoferta' => $id_oferta));
        Yii::$app->response->format = Response::FORMAT_JSON;

                return [ //index_postulados
            'title' => $titulo,
            'content' => $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'idpersona' =>$idpersona,
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
        ];
    }

    /**
     * Displays a single Mds_inv_entrega model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id,$idpersona)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            $un_com = Sds_com_persona::findOne($idpersona);

            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Ver Información de Plantin Entregado a ".$un_com->nombre." ".$un_com->apellido,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> //Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    Html::a(
                        'Cerrar',
                        ['index_plantines','idpersona'=>$idpersona],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                    )
                           // Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Mds_inv_entrega model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idpersona)
    {
        $request = Yii::$app->request;
        $model = new Mds_inv_entrega();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Registrar Nueva Entrega",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                        'idpersona'=>$idpersona,
                    ]),
                    'footer'=> //Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    Html::a(
                        'Cerrar',
                        ['index_plantines','idpersona'=>$idpersona],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                    ).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                                                 
                $fecha = date('Y-m-d h:i:s', time());
                $model->fecha = $fecha;
                $model->estado = 1;  
                $model->idpersona=$idpersona;              

                $unafecha = explode ("/",$model->fecha_entrega);
                $fecha_entrega= trim($unafecha[2])."-".trim($unafecha[1])."-".trim($unafecha[0]); 

                $model->fecha_entrega = $fecha_entrega;
                
                $model->save();

                

                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Registrar Nueva Entrega",
                    'content'=>'<span class="text-success">Se registro la entrega del plantin exitosamente</span>',
                    'footer'=> 
                    Html::a(
                        'Volver',
                        ['index_plantines','idpersona'=>$idpersona],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                    )
                                        
                    //Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                            //Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Registrar Nueva Entrega",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->identrega]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

   /* public function actionCreate2($idpersona)
    {
        $request = Yii::$app->request;
        $model = new Mds_inv_entrega();  

        if($request->isAjax){
           
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Registrar Nueva Entrega",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                        'idpersona'=>$idpersona,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()))
            {   $fecha = date('Y-m-d h:i:s', time());
                $model->fecha = $fecha;
                $model->estado = 1;  
                $model->idpersona=$idpersona;
                $model->save();
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Registrar Nueva Entrega".$idpersona,
                    'content'=>'<span class="text-success">Se registro la entrega del plantin exitosamente</span>',
                    'footer'=> Html::a(
                        'Volver al futuro',
                        ['index','idpersona'=>$idpersona],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                    )
                            //Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Registrar Nueva Entrega3",
                    'content'=>$this->renderAjax('index', [
                        'model' => $model,
                    ]),
                    'footer'=>  Html::a(
                        ' Volver',
                        ['index', 'idpersona' => $idpersona],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
           
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['index', 'idpersona' => $idpersona]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }*/


    /**
     * Updates an existing Mds_inv_entrega model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id,$idpersona)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            $un_com = Sds_com_persona::findOne($idpersona);
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Actualizar Información de Plantin Entregado a ".$un_com->nombre." ".$un_com->apellido,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'idpersona'=>$idpersona,
                    ]),
                    'footer'=> //Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    Html::a(
                        'Cancelar',
                        ['index_plantines','idpersona'=>$idpersona],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                    ).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                               
                $model->idpersona=$idpersona;              

                $unafecha = explode ("/",$model->fecha_entrega);
                $fecha_entrega= trim($unafecha[2])."-".trim($unafecha[1])."-".trim($unafecha[0]); 

                $model->fecha_entrega = $fecha_entrega;

                $model->save();

                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Ver Información de Plantin Entregado a ".$un_com->nombre." ".$un_com->apellido,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                        'idpersona' => $idpersona,
                    ]),
                    'footer'=> //Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    Html::a(
                        'Cerrar',
                        ['index_plantines','idpersona'=>$idpersona],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                    ).
                            Html::a('Editar',['update','id'=>$id,'idpersona'=>$idpersona],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    
                ];    
            }else{
                 return [
                    'title'=> "Update Mds_inv_entrega #".$id,
                    'content'=>$this->renderAjax('update', [
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
                return $this->redirect(['view', 'id' => $model->identrega]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_inv_entrega model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
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

    /**
     * Finds the Mds_inv_entrega model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_inv_entrega the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_inv_entrega::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
