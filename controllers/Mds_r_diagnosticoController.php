<?php

namespace app\controllers;

use Yii;
use app\models\Mds_r_diagnostico;
use app\models\Mds_r_diagnosticoSearch;
use app\models\Mds_r_variable_dimension;
use app\models\Sds_gis_capa_item;

use app\models\Mds_r_ejidos;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
//use yii\filters\AccessRule;
use app\models\Mds_seg_item;
use app\components\AccessRule;
/**
 * Mds_r_diagnosticoController implements the CRUD actions for Mds_r_diagnostico model.
 */
class Mds_r_diagnosticoController extends Controller
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
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index', 'create', 'update', 'delete', 'view', 'index_diagnostico','delete2'],
                'rules' => [
                    [
                        'actions' =>['index', 'create', 'update', 'delete', 'view', 'index_diagnostico','delete2'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                          
                            Mds_seg_item::MDS_R_PLANTILLA,
                            Mds_seg_item::MDS_R_PLANILLA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_r_diagnostico models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Mds_r_diagnosticoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        /*$dataProvider2=new ActiveDataProvider([
            'query' => Post::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);*/

        return  $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_r_diagnostico model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id,$idvardimension)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Ver Diagnóstico",
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                        'idvardimension' => $idvardimension
                       
                    ],
                    ),
                    'footer' =>
                    Html::a(
                        'Cerrar',
                        ['index_diagnostico', 'idvardimension' => $idvardimension],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                    )                           
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),

            ]);
        }




    }

    /**
     * Creates a new Mds_r_diagnostico model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idvardimension)
    {
        $request = Yii::$app->request;
        $model = new Mds_r_diagnostico();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title' => "Crear nuevo diagnóstico",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'idvardimension' => $idvardimension,
                    ]),
                    'footer' =>
                    Html::a(
                        'Cerrar',
                        ['index_diagnostico', 'idvardimension' => $idvardimension],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                    ) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];                      
            }else if($model->load($request->post()) )
            {

                $model->idvardimension=$idvardimension;
                $fecha = date('Y-m-d h:i:s', time());                
                $model->fecha=$fecha;
                // hay que ir a dimension y ver su origen:
                $una_dimension= Mds_r_variable_dimension::find()
                    ->where(['idvardimension' => $idvardimension])        
                    ->one();

                if ($una_dimension->origen==Mds_r_variable_dimension::ORIGEN_LOCALIDADES)
                {
                    $model->iddispositivo=null;
                }
                else
                {
                    if ($una_dimension->origen==Mds_r_variable_dimension::ORIGEN_DISPOSITIVO)
                    {
                        $model->idejido=null;

                    }
                }
                $model->valor=trim($model->valor);   
                
                $una_capa_item= Sds_gis_capa_item::find()
                    ->where(['idcapaitem' => $model->iddispositivo])        
                    ->one();
                //print_r($una_capa_item);print_r("<br> Ejido:::::");
                    //$una_capa_item->idubicacion; //foranea a idlocalidad de mdsyt.sds_com_localidad
                if (isset($una_capa_item))
                {
                    if ($una_capa_item->idubicacion!=null)
                    {
                        $un_ejido= Mds_r_ejidos::find()
                        ->where(['idlocalidad' => $una_capa_item->idubicacion])        
                        ->one();
                        //print_r($un_ejido);
                        if (isset($un_ejido))
                        {
                            $model->idejido=$un_ejido->idejido;
                        }
                        
                    }
                }
                
                $model->activo=1;

                if ($model->valor_dimension == null || empty(trim($model->valor_dimension))) {
                    $model->addError("valor_dimension", "Campo Obligatorio: Debe seleccionar una dimensión.");
                    return [
                        'title' => "Crear nuevo diagnóstico",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            'idvardimension' => $idvardimension,
                        ]),
                        'footer' =>
                        Html::a(
                            'Cerrar',
                            ['index_diagnostico', 'idvardimension' => $idvardimension],
                            ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                        ) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
    
                    ];      
                } else 
                {

                $model->save();
                return [
                    //'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Crear nuevo diagnóstico",
                    'content'=>'<span class="text-success">El nuevo diagnóstico ha sido creado exitosamente</span>',
                    'footer'=> 
                    Html::a(
                        'Cerrar',
                        ['index_diagnostico', 'idvardimension' => $idvardimension],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                    )
                           
        
                ];       

                }

                
                //var_dump($model->errors);

                  
            }else{           
                return [
                    'title'=> "Create new Mds_r_diagnostico",
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
                return $this->redirect(['view', 'id' => $model->iddiagnostico]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }
   
      
    public function actionIndex_diagnostico($idvardimension) // 
    {
        $searchModel = new Mds_r_diagnosticoSearch();
        $searchModel->idvardimension = $idvardimension;
        
        

        $titulo = "Diagnósticos" ;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [ //index_postulados
            
            'forceClose'=>false,
            'forceReload'=>null,
            'title' => $titulo,
            'content' => $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'idvardimension' => $idvardimension,
            ]),
            
            'footer' => Html::button('Cerrar',  
                                ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal",'id' => "botoncerrar" ]                                                  
                                
                    )
        ];
    }
    /**
     * Updates an existing Mds_r_diagnostico model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id,$idvardimension)  
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
                    'title'=> "Actualizar Diagnóstico",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'idvardimension' => $idvardimension,
                    ]),
                    'footer' =>
                    Html::a(
                        'Cerrar',
                        ['index_diagnostico', 'idvardimension' => $idvardimension],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                    )     .
                    Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])                    
                                
                ];         
            }else if($model->load($request->post()) ){

                $model->valor=trim($model->valor);   
                
                $una_capa_item= Sds_gis_capa_item::find()
                    ->where(['idcapaitem' => $model->iddispositivo])        
                    ->one();
                //print_r($una_capa_item);print_r("<br> Ejido:::::");
                    //$una_capa_item->idubicacion; //foranea a idlocalidad de mdsyt.sds_com_localidad
                if (isset($una_capa_item))
                {
                    if ($una_capa_item->idubicacion!=null)
                    {
                        $un_ejido= Mds_r_ejidos::find()
                        ->where(['idlocalidad' => $una_capa_item->idubicacion])        
                        ->one();
                        //print_r($un_ejido);
                        $model->idejido=$un_ejido->idejido;
                    }

                }
                

                $model->save();
                return [
                    //'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Ver Diagnóstico",
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                        'idvardimension' => $idvardimension,
                        
                    ]),

                    'footer' =>
                    Html::a(
                        'Cerrar',
                        ['index_diagnostico', 'idvardimension' => $idvardimension],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                    )   
                ];    
            }else{
                 return [
                    'title'=> "Update Mds_r_diagnostico #".$id,
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
                return $this->redirect(['view', 'id' => $model->iddiagnostico]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_r_diagnostico model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $un_diagnostico=$this->findModel($id);
        //->delete();
        $un_diagnostico->activo=0;
        $un_diagnostico->save();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#ajaxCrudDatatable13'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

    public function actionDelete2($id, $idvardimension)  
    //Eliminacion logica de un diagnostico. Se usa en /views/mds_r_diagnostco/_columns.php linea 133
    // Se elimina logicamente el diagnostico con id=$id
    //Luego se renderiza el modal index con todos los diagnosticos de variable dimension (idvardimension): $idvardimension
    {
        $request = Yii::$app->request;
        
        $un_diagnostico=$this->findModel($id);
        //->delete();
        $un_diagnostico->activo=0;
        $un_diagnostico->save();


        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;

            $titulo = "Eliminar Diagnóstico";
            return [
                
                'title' => $titulo,
                'content' => '<span class="text-success">Se eliminó el diagnóstico exitosamente</span>',

                'footer'=> 
                    Html::a(
                        'Cerrar',
                        ['index_diagnostico', 'idvardimension' => $idvardimension],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left',]
                    )

            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Mds_r_diagnostico model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_r_diagnostico the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_r_diagnostico::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}





