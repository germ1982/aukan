<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_inv_persona;
use app\models\Mds_inv_personaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Sds_com_persona;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_inv_entrega;
use app\models\Mds_inv_asistencia;
use app\models\Mds_seg_item;
use yii\filters\AccessControl;

date_default_timezone_set('America/Argentina/Buenos_Aires');
/**
 * Mds_inv_personaController implements the CRUD actions for Mds_inv_persona model.
 */
function write_to_console($data) {
    $console = $data;
    if (is_array($console))
    $console = implode(',', $console);
   
    echo "<script>console.log('Console: " . $console . "' );</script>";
   }
class Mds_inv_personaController extends Controller
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
                'only' => ['index', 'entrega','createplantin','borrarplantin','verplantin','updateplantin','guardareditplantin','guardarnuevoplantin','createpersona','updatepersona','create', 'update', 'delete', 'view', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'entrega','createplantin','borrarplantin','verplantin','updateplantin','guardareditplantin','guardarnuevoplantin','createpersona','updatepersona','create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_INV_PERSONA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_inv_persona models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Mds_inv_personaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionEntrega($dni)
    {     
        $result = [];  
        $entrega_persona = Mds_inv_persona::findBySql("select * from mds_inv_persona r inner join sds_com_persona p on r.idpersona  = p.idpersona where p.documento = $dni;")->one();              
        if ($entrega_persona != null) {            
            array_push($result, "si");
            return json_encode($result);
        }
        else
        {            
            array_push($result, "no");
            return json_encode($result);
        }       
    }
    public function actionCreateplantin($idpersona)
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
                    'content'=>$this->renderAjax('_form2', [
                        'model' => $model,
                        'idpersona'=>$idpersona,
                        'accion'=>'crear',
                        
                    ]),
                    /*'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])*/
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Registrar Nueva Entrega",
                    'content'=>'<span class="text-success">Create Mds_inv_entrega success</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
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
    public function actionBorrarplantin($id_plantin)
    {
        
        $request = Yii::$app->request;
        if ($this->findModel2($id_plantin)->delete())
        {
            return "exito";
        }
        else
        {
            return "fallido";
        } 
    }
    /*public function actionDeleteplantin($id)
    {
       
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Seguro desea eliminar la entrega",
                    'content'=>$this->renderAjax('_formeliminar', [                        
                        'id'=>$id,                        
                        
                    ]),                    
        
                ];         
            }
        }
          
    }*/
    public function actionVerplantin($id)
    {
        $request = Yii::$app->request;
        $model = new Mds_inv_entrega();  
        $un_plantin = Mds_inv_entrega::find()->where(["identrega" => $id])->one();  
        $model->identrega=$id;
        $model->idespecie=$un_plantin->idespecie;
        $model->cantidad=$un_plantin->cantidad;

        $model->fecha=$un_plantin->fecha;        
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Ver Entrega Plantin",
                    'content'=>$this->renderAjax('ver', [
                        'model' => $model,
                        'identrega'=>$id,
                        'accion'=>'editar',
                    ]),
                    /*'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])*/
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Registrar Nueva Entrega",
                    'content'=>'<span class="text-success">Create Mds_inv_entrega success</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
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

    public function actionUpdateplantin($id)
    {
        $request = Yii::$app->request;
        $model = new Mds_inv_entrega();  
        $un_plantin = Mds_inv_entrega::find()->where(["identrega" => $id])->one();  
        $model->identrega=$id;
        $model->idespecie=$un_plantin->idespecie;
        $model->cantidad=$un_plantin->cantidad;
        
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Editar Entrega Plantin",
                    'content'=>$this->renderAjax('_form2', [
                        'model' => $model,
                        'identrega'=>$id,
                        'accion'=>'editar',
                    ]),
                    /*'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])*/
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Registrar Nueva Entrega",
                    'content'=>'<span class="text-success">Create Mds_inv_entrega success</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
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

    public function actionGuardareditplantin($identrega,$especie,$cantidad)
    {
        
        $model_inv_entrega = $this->findModel2($identrega); 

        //$un_plantin = Mds_inv_entrega::find()->where(["identrega" => $identrega])->one();
        //$model_inv_entrega = new Mds_inv_entrega();
        //$model_inv_entrega->identrega = $identrega;
        $model_inv_entrega->idespecie = $especie;
        $model_inv_entrega->cantidad = $cantidad;        
                     
        if ($model_inv_entrega->save()) {  
                return "exito";
        }
        else
        {
            return "fallido";
        }
    }
    public function actionGuardarnuevoplantin($idpersona,$especie,$cantidad)
    {
        $model_inv_entrega = new Mds_inv_entrega();
        $model_inv_entrega->idespecie = $especie;
        $model_inv_entrega->cantidad = $cantidad;        
        $fecha = date('Y-m-d h:i:s', time());
        $model_inv_entrega->fecha = $fecha;
        $model_inv_entrega->estado = 1;
        $model_inv_entrega->idpersona = $idpersona;        
        if ($model_inv_entrega->save()) {  
                return "exito";
        }
        else
        {
            return "fallido";
        }
    }
    public function actionCreatepersona($whatsapp,$arr_seg2,$arr_seg,$apellido,$nombre,$fecha_nac,$genero,$nacionalidad,$dni,$grupo_familiar,$telefono,$email,$domicilio,$seguimiento,$cant_nnya,$recibe_plantines,$cosecha_plantines)
    //Se crea una nueva persona a la cual se le entrega plantines. 
    {
        
        $un_com_persona = Sds_com_persona::find()->where(["documento" => $dni])->one();          
                   
        if ($un_com_persona != null) // es decir que esta cargado
        {    
            $check_persona = Mds_inv_persona::find()->where(["idpersona" => $un_com_persona->idpersona])->one();  
            if ($check_persona !=null)
            {
                return "yaexiste";
            }
            else
            {
                    $el_id_persona=$un_com_persona->idpersona;          
                    $model_inv_persona = new Mds_inv_persona();
                    $model_inv_persona->grupo_familiar = $grupo_familiar;
                    $model_inv_persona->telefono = $telefono;
                    $model_inv_persona->email = $email;
                    $model_inv_persona->domicilio = $domicilio;
                    $model_inv_persona->seguimiento = $seguimiento;
                    $model_inv_persona->whatsapp = $whatsapp;            
                    $model_inv_persona->idpersona = $un_com_persona->idpersona;


                    $model_inv_persona->cant_nnya = $cant_nnya;
                    $model_inv_persona->recibe_plantines = $recibe_plantines;            
                    $model_inv_persona->cosecha_plantines = $cosecha_plantines;

                    if ($model_inv_persona->save()) { 
                        
                        $arr_asistencia=explode(",", $arr_seg);  
                        $arr_asist2=explode(",", $arr_seg2); 
                            foreach ($arr_asistencia as $dato_asist) {                        
                                $model_inv_asistencia = new Mds_inv_asistencia();
                                if ($dato_asist==2187) 
                                {  
                                    $model_inv_asistencia->descripcion =$arr_asist2[0];
                                }
                                else
                                {
                                    if ($dato_asist==2188) 
                                    {  
                                        $model_inv_asistencia->descripcion =$arr_asist2[1];
                                    }
                                    else
                                    {
                                        $model_inv_asistencia->descripcion =null;
                                    }
                                }
                                
                                $model_inv_asistencia->idconfiguracion =$dato_asist ;
                                $model_inv_asistencia->idpersona = $el_id_persona;
                                
                            
                                if ($model_inv_asistencia->save()) 
                                {  
                                
                                } 
                                else
                                {
                                    /*print_r($model_inv_asistencia->getAttributes());
                                    print_r($model_inv_asistencia->getErrors());*/
                                }

                            }
                        return  $model_inv_persona->idpersona ;                                               
                    }
                    else
                    {   
                    /*print_r($model_inv_persona->getAttributes());
                        print_r($model_inv_persona->getErrors());*/
                        
                        //return $model->idpersonacap."-".$model->idcapinstancia."-".$model->fecha_inscripcion."-".$model->termino."-".$model->dato_adicional;
                        return "fallido";
                    }
            }

        }
        else
        {   // no esta en sds_com_persona, hay que agregarla
            $un_sds_com_persona = new Sds_com_persona();

            $un_sds_com_persona->documento = $dni;//
            $un_sds_com_persona->documento_tipo = 83;//
            $un_sds_com_persona->nacionalidad = $nacionalidad;//
            $un_sds_com_persona->genero = $genero;//

            $unafecha = explode ("/",$fecha_nac);
            $fecha_nacimiento= trim($unafecha[2])."-".trim($unafecha[1])."-".trim($unafecha[0]);    
           
                            
            $un_sds_com_persona->fecha_nacimiento = $fecha_nacimiento;//
            $un_sds_com_persona->nombre = $nombre;
            $un_sds_com_persona->apellido = $apellido;            
            $un_sds_com_persona->padre = null;
            $un_sds_com_persona->conviviente = 0;
            if ($un_sds_com_persona->save()) {      
                
                $un_com_persona2 = Sds_com_persona::find()->where(["documento" => $dni])->one(); 
                $el_id_persona=$un_com_persona2->idpersona;     
                $model_inv_persona2 = new Mds_inv_persona();
                $model_inv_persona2->grupo_familiar = $grupo_familiar;
                $model_inv_persona2->telefono = $telefono;
                $model_inv_persona2->email = $email;
                $model_inv_persona2->domicilio = $domicilio;
                $model_inv_persona2->seguimiento = $seguimiento;
                $model_inv_persona2->whatsapp = $whatsapp;  
                $model_inv_persona2->idpersona = $un_com_persona2->idpersona;

                             

                if ($model_inv_persona2->save()) {                                                   
                    
                    $arr_asistencia=explode(",", $arr_seg);  
                    $arr_asist2=explode(",", $arr_seg2); 
                        foreach ($arr_asistencia as $dato_asist) {                        
                            $model_inv_asistencia = new Mds_inv_asistencia();
                            if ($dato_asist==2187) 
                            {  
                                $model_inv_asistencia->descripcion =$arr_asist2[0];
                            }
                            else
                            {
                                if ($dato_asist==2188) 
                                {  
                                    $model_inv_asistencia->descripcion =$arr_asist2[1];
                                }
                                else
                                {
                                    $model_inv_asistencia->descripcion =null;
                                }
                            }
                            
                            $model_inv_asistencia->idconfiguracion =$dato_asist ;
                            $model_inv_asistencia->idpersona = $el_id_persona;
                            
                           
                            if ($model_inv_asistencia->save()) 
                            {  
                               
                            } 
                            else
                            {
                                /*print_r($model_inv_asistencia->getAttributes());
                                print_r($model_inv_asistencia->getErrors());*/
                            }
    
                        }
                    return $model_inv_persona2->idpersona ;
                                    
                }
                else
                {  
                    return "fallido";
                }
            }
            else
            {   return "fallido";
            }            
        }            
    }
              
    public function actionUpdatepersona($whatsapp,$arr_seg2,$arr_seg,$idpersona,$apellido,$nombre,$fecha_nac,$genero,
    $nacionalidad,$dni,$grupo_familiar,$telefono,$email,$domicilio,$seguimiento,$cant_nnya,$recibe_plantines,$cosecha_plantines)
    {
        
            
            $model_inv_persona = $this->findModel($idpersona);   
            
            $model_inv_persona->grupo_familiar = $grupo_familiar;
            $model_inv_persona->telefono = $telefono;
            $model_inv_persona->email = $email;
            $model_inv_persona->domicilio = $domicilio;
            $model_inv_persona->seguimiento = $seguimiento;
            $model_inv_persona->whatsapp = $whatsapp;
            $model_inv_persona->idpersona = $idpersona;
            $model_inv_persona->cant_nnya = $cant_nnya;
            $model_inv_persona->recibe_plantines = $recibe_plantines;
            $model_inv_persona->cosecha_plantines = $cosecha_plantines;
   
            if ($model_inv_persona->save()) {    
                
                $model_com_persona = $this->findModel3($idpersona);               
                $model_com_persona->documento = $dni;
                $model_com_persona->documento_tipo = $model_com_persona->documento_tipo;
                $model_com_persona->nacionalidad = $nacionalidad;
                $model_com_persona->genero = $genero;

                $unafecha = explode ("/",$fecha_nac);
                $fecha_nacimiento= trim($unafecha[2])."-".trim($unafecha[1])."-".trim($unafecha[0]); 

                $model_com_persona->fecha_nacimiento = $fecha_nacimiento;
                $model_com_persona->nombre = $nombre;                
                $model_com_persona->apellido = $apellido;
                //$model_com_persona->padre = $model_com_persona->padre;                
                //$model_com_persona->conviviente = $model_com_persona->conviviente;
                if ($model_com_persona->save()) {   
                    
                    //eliminar todas las asistencias cargadas:
                    $all_asistencias = Mds_inv_asistencia::find()->where(['idpersona' => $idpersona])->all(); 
                    foreach ($all_asistencias as $una_asistencia) 
                    { 
                        //$obj_asistencia=new Mds_inv_asistencia();
                        $una_asistencia->delete();
                        //$this->findModel($id)->delete();
                    }
        
                    $arr_asistencia=explode(",", $arr_seg);  
                    $arr_asist2=explode(",", $arr_seg2); 
                        foreach ($arr_asistencia as $dato_asist) {                        
                            $model_inv_asistencia = new Mds_inv_asistencia();
                            if ($dato_asist==2187) 
                            {  
                                $model_inv_asistencia->descripcion =$arr_asist2[0];
                            }
                            else
                            {
                                if ($dato_asist==2188) 
                                {  
                                    $model_inv_asistencia->descripcion =$arr_asist2[1];
                                }
                                else
                                {
                                    $model_inv_asistencia->descripcion =null;
                                }
                            }
                            
                            $model_inv_asistencia->idconfiguracion =$dato_asist ;
                            $model_inv_asistencia->idpersona = $idpersona;
                            
                           
                            if ($model_inv_asistencia->save()) 
                            {  
                               
                            } 
                            else
                            {
                                /*print_r($model_inv_asistencia->getAttributes());
                                print_r($model_inv_asistencia->getErrors());*/
                            }
    
                        }

                    
                    return  "exito" ;      
                } else
                {
                    return "fallido";
                }                                                                         
            }
            else
            {                 
                return "fallido";
            }                  
    }


    /*public function actionCreate2()
    {
        $request = Yii::$app->request;
        $model = new Mds_inv_entrega();  

        if($request->isAjax){           
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Mds_inv_entrega",
                    'content'=>$this->renderAjax('create2', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Mds_inv_entrega",
                    'content'=>'<span class="text-success">Create Mds_inv_entrega success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Mds_inv_entrega",
                    'content'=>$this->renderAjax('create2', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
         
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->identrega]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }*/
    
    /**
     * Displays a single Mds_inv_persona model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Ver Registro Entrega Plantines",
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                   /* 'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])*/
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Mds_inv_persona model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function beforeAction($action) {     $this->enableCsrfValidation = false;     return parent::beforeAction($action); }
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_inv_persona();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear Nuevo Registro",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    /*'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])*/
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Crear Nuevo Registro2",
                    'content'=>'<span class="text-success">Create Mds_inv_persona success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Crear Nuevo Registro3",
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
                return $this->redirect(['view', 'id' => $model->idpersona]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Mds_inv_persona model.
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
                    'title'=> "Actualizar Registro",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    /*'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])*/
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Mds_inv_persona #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Mds_inv_persona #".$id,
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
                return $this->redirect(['view', 'id' => $model->idpersona]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_inv_persona model.
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
     * Delete multiple existing Mds_inv_persona model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
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
     * Finds the Mds_inv_persona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_inv_persona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_inv_persona::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findModel2($id)
    {
        if (($model = Mds_inv_entrega::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    protected function findModel3($id)
    {
        if (($model = Sds_com_persona::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel9($id)
    {
        if (($model = Mds_inv_asistencia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
