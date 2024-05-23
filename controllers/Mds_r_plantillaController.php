<?php

namespace app\controllers;

use Yii;
use app\models\Mds_r_plantilla;
use app\models\Mds_r_planilla;
use app\models\Sds_gis_capa;
use app\models\Mds_r_plantillaSearch;
use app\models\Mds_r_diagnostico;
use app\models\Mds_r_variable_dimension;
use app\models\Mds_seg_item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\filters\AccessControl;
//use yii\filters\AccessRule;
use app\components\AccessRule;

date_default_timezone_set('America/Argentina/Buenos_Aires');

/**
 * Mds_r_plantillaController implements the CRUD actions for Mds_r_plantilla model.
 */


 function write_to_console($data) {

    $console = 'console.log(' . json_encode($data) . ');';
    $console = sprintf('<script>%s</script>', $console);
    echo $console;
   }



class Mds_r_plantillaController extends Controller
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
                'only' => ['index', 'create', 'delete', 'update', 'view', 'obtener_origen','Obtener_origen2','Obtener_origen4','obtener_capa_plantilla','obtener_plantilla','create_plantilla','create_diagnostico','obtener_origencompleto','obtener_plantillasxdiagxdim'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'obtener_origen','Obtener_origen2','Obtener_origen4','obtener_capa_plantilla','obtener_plantilla','create_plantilla','create_diagnostico', 'obtener_origencompleto','obtener_plantillasxdiagxdim'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MDS_R_PLANTILLA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_r_plantilla models.
     * @return mixed
     */
    public function actionObtener_origen($id_plantilla)
    // Dado el id de una plantilla, busco el tipo de origen. 
    // Devuelvo un string
    {        
        if ($id_plantilla != '') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $plantilla = Mds_r_plantilla::find()
            ->where(['idtipoplantilla' => $id_plantilla])
            ->asArray()->one();
           
           $cad_return="";    
           //$cad_return="id_plantilla:".$id_plantilla." ORIGEN: ".$plantilla['origen']."id gis capa: ".$plantilla['id_gis_capa']."FINACA"; 
           if ($plantilla['origen']== Mds_r_planilla::ORIGEN_DISPOSITIVO)  
           {
                $gis_capa = Sds_gis_capa::find()->where(['idcapa' => $plantilla['id_gis_capa']])->asArray()->one();
                $cad_return=$cad_return."<b>Origen:</b> Dispositivo -> ".$gis_capa['descripcion'];
                
               
           }    
           else
           {  $cad_return=$cad_return."<b>Origen:</b> Localidades.";
           }                   
           
           
            return json_encode($cad_return);
        }
        return null;
    }
    public function actionObtener_origencompleto($id_plantilla,$variable_diag,$iddimension)
    // Dado el id de una plantilla, la variable de diagnostico y la dimension, busco el tipo de origen. 
    // Devuelvo un string
    {
        //Busco la persona, si existe traigo los datos para editar
        $cad_imp="id_plantilla:".$id_plantilla." variable_diag: ".$variable_diag."  iddimension: ".$iddimension; 
            //write_to_console($cad_imp);
        if ($id_plantilla != '') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            

            $plantilla = Mds_r_plantilla::find()
            ->where(['idtipoplantilla' => $id_plantilla])
            ->andWhere(['variable_diagnostico'=>$variable_diag])
            ->andWhere(['dimension'=>$iddimension])
            ->asArray()->one();
           
           $cad_return="";    
           //$cad_return="id_plantilla:".$id_plantilla." ORIGEN: ".$plantilla['origen']."id gis capa: ".$plantilla['id_gis_capa']."FINACA"; 
           //write_to_console(" ORIGEN: ".$plantilla['origen']);
           if ($plantilla['origen']== Mds_r_planilla::ORIGEN_DISPOSITIVO)  
           {
                $gis_capa = Sds_gis_capa::find()->where(['idcapa' => $plantilla['id_gis_capa']])->asArray()->one();
                $cad_return=$cad_return."<b>Origen:</b> Dispositivo -> ".$gis_capa['descripcion'];
                
               
           }    
           else
           {  $cad_return=$cad_return."<b>Origen:</b> Localidades.";
           }                   
           
           
            return json_encode($cad_return);
        }
        return null;
    }
    
    public function actionObtener_origen2($id_plantilla,$id_dimension)
     // Dado el id de una plantilla, y la dimension, busco el tipo de origen. 
    // Devuelvo un string
    {        
        if ($id_plantilla != '') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $plantilla = Mds_r_plantilla::find()->where(['idtipoplantilla' => $id_plantilla, 'dimension'=> $id_dimension])->asArray()->one();
           
           $cad_return="";    
           //$cad_return="iddimension: ".$id_dimension."   id_plantilla:".$id_plantilla." ORIGEN: ".$plantilla['origen']."id gis capa: ".$plantilla['id_gis_capa']."FINACA"; 
           if ($plantilla['origen']== Mds_r_planilla::ORIGEN_DISPOSITIVO)  
           {
                $gis_capa = Sds_gis_capa::find()->where(['idcapa' => $plantilla['id_gis_capa']])->asArray()->one();
                $cad_return.="<b>Origen:</b> Dispositivo -> ".$gis_capa['descripcion'];
                
               
           }    
           else
           {  $cad_return.="<b>Origen:</b> Localidades";
           }                   
           
           
            return json_encode($cad_return);
        }
        return null;
    }
    public function actionObtener_origen4($idvardim,$id_plantilla,$id_dimension,$idorigen)
    // Dado el id de la variable dimension, el id de una plantilla, la dimension  y el idorigen, busco el tipo de origen. 
    // Devuelvo un string
    {
        //Busco la persona, si existe traigo los datos para editar
        $vardimension = Mds_r_variable_dimension::find()->where(['idvardimension' => $idvardim])->one();        
            Yii::$app->response->format = Response::FORMAT_JSON;  
                               
           $cad_return="";                 
           if ($vardimension->origen== Mds_r_planilla::ORIGEN_DISPOSITIVO)  
           {
                if ($vardimension->id_giscapa!=null)
                {
                    $gis_capa = Sds_gis_capa::find()->where(['idcapa' => $vardimension->id_giscapa])->one();
                    $cad_return.="<b>Origen:</b> Dispositivo -> ".$gis_capa->descripcion;   

                }
                else
                {
                    $cad_return.="<b>Origen:</b> Dispositivo -> desconocido";   

                }                                                    
           }    
           else
           {  $cad_return.="<b>Origen:</b> Localidades";
           }                              
           //var_dump($plantilla->errors);           
            return json_encode($cad_return);                
    }
    public function actionObtener_capa_plantilla($idvardim,$id_plantilla)
    // Dada el di de una variable de dimension, y el id de una plantilla
    // obtenemos  el origen y la capa gis de dicha plantilla
    {
        if ($id_plantilla != '') {
            $vardimension = Mds_r_variable_dimension::find()->where(['idvardimension' => $idvardim])->one();                            
            Yii::$app->response->format = Response::FORMAT_JSON;                        
            $plantilla = Mds_r_plantilla::find()
            //->where(['idtipoplantilla' => $id_plantilla])
            ->where(['idtipoplantilla' => $id_plantilla, 'origen'=> $vardimension->origen])
            ->one();                   
            
            $result = array($vardimension->origen,$plantilla->id_gis_capa);           
            return json_encode($result);
        }
        return null;
    }
    public function actionObtener_plantillasxdiagxdim($id_plantilla,$variable_diag,$iddimension)
    //Dado el tipo de plantilla, la variable diagnostico y la dimension,
    // se obtienen las plantillas que cumplen esas tres catacteristicas
    {              
        if ($id_plantilla != '') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $plantillas = Mds_r_plantilla::find()
            ->where(['idtipoplantilla' => $id_plantilla])
            ->andWhere(['variable_diagnostico'=>$variable_diag])
            ->andWhere(['dimension'=>$iddimension])
            ->asArray()->one();
           // print_r($plantillas);                                         
            $result = array();            
            array_push($result, $plantillas);
            return json_encode($result);
        }
        return null;
    }
    
    public function actionObtener_plantilla($id_plantilla)
    {
        //Dado el id de una plantilla, se devuelve la plantilla
        if ($id_plantilla != '') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            $plantillas = Mds_r_plantilla::find()->where(['idtipoplantilla' => $id_plantilla])->asArray()->one();
           // print_r($plantillas);                                         
            $result = array();            
            array_push($result, $plantillas);
            return json_encode($result);
        }
        return null;
    }
    public function actionIndex()
    {    
        $searchModel = new Mds_r_plantillaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    

    /**
     * Creates a new Mds_r_plantilla model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
     public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_r_plantilla();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear Plantilla",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                
                $fechahora=strftime( "%Y-%m-%d %H:%M:%S ", time() );
                $model->fechahoracreate=$fechahora;

                if ($model-> origen != Mds_r_plantilla::CONST_DISP){
                    $model-> id_gis_capa = null;
                }

                $dimensiones = $model->dimensiones != null ? $model->dimensiones : array();
                $dim_count = count($model->dimensiones);
                $car = "";
                $car2="";
                for ($index_dim = 0; $index_dim < $dim_count; $index_dim ++){
                    $obj_plantilla = new Mds_r_plantilla();
                    $obj_plantilla->variable_diagnostico = $model->variable_diagnostico;
                    $obj_plantilla->idtipoplantilla = $model->idtipoplantilla;
                    $obj_plantilla->origen = $model->origen;
                    $obj_plantilla->fechahoracreate = $model->fechahoracreate;
                    $obj_plantilla->id_gis_capa = $model->id_gis_capa;
                    $obj_plantilla->activo = 1;


                    $obj_plantilla-> dimension = $model->dimensiones[$index_dim ];
                    $car = $car.$model-> dimension;
                    if($obj_plantilla->save())
                    {
                        
                    }
                    /* var_dump($model->errors); */
                }
                
                return [
                    'title'=> "Guardado de plantilla exitoso ",
                    'content'=>'<span class="text-success">Guardado exitoso</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
        
                ];

            }else{           
                return [
                    'title'=> "Create new Mds_r_plantilla",
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
                return $this->redirect(['view', 'id' => $model->idplantilla]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    } //end function actionCreate() 


    public function actionCreate_plantilla()
    {
        $request = Yii::$app->request;
        $model = new Mds_r_plantilla();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Ingresar plantilla",
                    'content'=>$this->renderAjax('create_plantilla', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::a(
                        'Cerrar',
                        ['create'],
                        ['role' => 'modal-remote', 'class' => 'btn btn-default pull-left']
                    ).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
            else if($model->load($request->post()))
            {
                $obj_new_plant = new Sds_com_configuracion();
                $obj_new_plant -> idconfiguraciontipo = Sds_com_configuracion_tipo :: R_TIPO_PLANTILLA;

                $obj_new_plant -> descripcion = $model -> nombre_plantilla;

                $obj_new_plant -> activo = 1;
                if ($obj_new_plant-> save()){
                    return [
                        'title'=> "Plantilla ingresada",
                        'content'=>'<span class="text-success">Guardado exitoso</span>',
                        'footer' =>
                    Html::a(
                        'Cerrar',
                        ['create'],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    )
                    ]; 
                }   
            }
            else{           
                     
            }
            
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idplantilla]);
            } else {
                return $this->render('create_plantilla', [
                    'model' => $model,
                ]);
            }
        }
       
    } //end function actionCreate_plantilla()

    public function actionCreate_diagnostico()
    {
        $request = Yii::$app->request;
        $model = new Mds_r_plantilla();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Ingresar variable diagnostico",
                    'content'=>$this->renderAjax('create_diagnostico', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::a(
                        'Cerrar',
                        ['create'],
                        ['role' => 'modal-remote', 'class' => 'btn btn-default pull-left']
                    ).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
            else if($model->load($request->post()))
            {
                $obj_new_variable = new Sds_com_configuracion();
                $obj_new_variable -> idconfiguraciontipo = Sds_com_configuracion_tipo :: DIAGNOSTICO_INDICADOR;

                $obj_new_variable -> descripcion = $model -> nombre_variable;

                $obj_new_variable -> activo = 1;
                if ($obj_new_variable-> save()){
                    return [
                        'title'=> "Variable diagnostico ingresada",
                        'content'=>'<span class="text-success">Guardado exitoso</span>',
                        'footer' =>
                    Html::a(
                        'Cerrar',
                        ['create'],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    )
                    ]; 
                }   
            }
            else{           
                     
            }
            
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->variable_diagnostico]);
            } else {
                return $this->render('create_diagnostico', [
                    'model' => $model,
                ]);
            }
        }
       
    } //end function actionCreate_diagnostico()


    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Plantilla n°".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Updates an existing Mds_r_plantilla model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
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
                    'title'=> "Modificar plantilla",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) ){
                if ($model-> origen != Mds_r_plantilla::CONST_DISP){
                    $model-> id_gis_capa = null;
                }

               
                    if($model->save())
                    {

                    }

                
                return [
                    'title'=> "Modificar plantilla ",
                    'content'=>'<span class="text-success">Modificación exitosa</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
        
                ];

            }else{
                 return [
                    'title'=> "Update Mds_r_plantilla #".$id,
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
                return $this->redirect(['view', 'id' => $model->idplantilla]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_r_plantilla model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $una_plantilla=$this->findModel($id);
        $una_plantilla->activo=0;
        $una_plantilla->save();
       

        $las_planillas=Mds_r_planilla::find()
            ->where(['idplantilla' => $una_plantilla->idplantilla])                      
            ->all();

        foreach ($las_planillas as $una_planilla) {
                $una_planilla->activo=0;
                $una_planilla->save();  
                
                
                $dimensiones=Mds_r_variable_dimension::find()
                ->where(['idplanilla' => $una_planilla->idplanilla])                      
                ->all();
                foreach ($dimensiones as $una_dimension) {
                    $una_dimension->activo=0;
                    $una_dimension->save(); 

                    $los_diagnosticos=Mds_r_diagnostico::find()
                    ->where(['idvardimension' => $una_dimension->idvardimension])                      
                    ->all();
                    foreach ($los_diagnosticos as $un_diagnostico) {
                        $un_diagnostico->activo=0;
                        $un_diagnostico->save();
                        
                    }       

                }     

        }               

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable14'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    } //fin function actionDelete()

    /**
     * Finds the Mds_r_plantilla model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_r_plantilla the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_r_plantilla::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
