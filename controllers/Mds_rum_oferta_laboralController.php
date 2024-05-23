<?php

namespace app\controllers;

use Yii;
use app\models\Mds_rum_oferta_laboral;
use app\models\Mds_rum_oferta_laboralSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_rum_postulacion;
use yii\web\UploadedFile;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_rum_empleador;
use app\models\Mds_seg_usuario;
use phpDocumentor\Reflection\Types\Expression;
use app\models\Mds_sys_log;

date_default_timezone_set('America/Argentina/Buenos_Aires');



/**
 * Mds_rum_oferta_laboralController implements the CRUD actions for Mds_rum_oferta_laboral model.
 */
class Mds_rum_oferta_laboralController extends Controller
{
    /**
     * @inheritdoc
     */

/*    public function behaviors()
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
    }*/
    
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_RUM_OFERTA_LABORAL,
                        ],
                    ],
                ],
            ],
        ];
    }
    /**
     * Lists all Mds_rum_oferta_laboral models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Mds_rum_oferta_laboralSearch();
        //id_empleador
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_oferta_laboral', null, array());
        $un_rol_usuario=Mds_seg_usuario_rol::find()                                                              
                           ->where(['idusuario' => $usuario->idusuario])
                           ->andWhere(["idrol"=> 38] )                           
                           ->one();  
        
        if ($un_rol_usuario == null){}
        else
        {
            if ($un_rol_usuario->idrol==38) //id de la tabla mds_seg_rol para el rol Rum Empleador
            {   //echo '['.$un_rol_usuario->idusuariorol.' , '.$un_rol_usuario->idusuario.' , '.$un_rol_usuario->idrol.']';
                //echo '   usuario.idusuario: '.$usuario->idusuario;

                //1. necesito buscar las empresas del usuario actual
                
                $empresas_usuario=Mds_rum_empleador::find()                                                              
                           ->where(['idpersona' => $usuario->idusuario])                           
                           ->all();  

                $post_final= array();
                $i=0;
                foreach ($empresas_usuario as $una_emp) 
                {   
                    $post_final[$i]=$una_emp->id;
                    $i++;                            
                }           
                //2. luego busco las ofertas laborales de todas esas empresas

                // el id_empleador de la oferta laboral, es el id de la empresa, no del usuario
                $dataProvider->query->where(['in','id_empleador',$post_final])
                ->orderBy([
                    'activo' => SORT_DESC ,
                    'fecha_publicacion'=>SORT_DESC
                    ]);
                
            }

        }
        
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_rum_oferta_laboral model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_oferta_laboral', $id, array());
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "RUMBO:: Ver Oferta Laboral",
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
     * Creates a new Mds_rum_oferta_laboral model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

     
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_rum_oferta_laboral();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "RUMBO:: Nueva Oferta Laboral",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) ){
                $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                if (isset($tmpfile)) {
                  
                    $extension= $tmpfile->extension;
                    $nuevo_nombre=$model->random_filename(30, '/uploads/ofertas',$extension);
                    $model->imagen = $nuevo_nombre ;                                 
                    $tmpfile->saveAs('uploads/ofertas/' . $nuevo_nombre );                    
                   
                } else 
                {
                };
                        $fechamodificacion=strftime( "%Y-%m-%d", time() );  
                        $horamodificacion=strftime( "%H:%M:%S", time() );
                        
                        $model->fechamodificacion=$fechamodificacion;
                        $model->horamodificacion=$horamodificacion;
                        $model->fechaalta=$fechamodificacion;
                        $model->horaalta=$horamodificacion;
                        

                        $fecha_pubini = ArmarDateParaMySql($model->fecha_publicacion);
                        $fecha_pubini = date_create($fecha_pubini);
                        $fecha_pubini = date_format($fecha_pubini, 'Y-m-d');
                        $model->fecha_publicacion = $fecha_pubini;

                        $fecha_pubfin = ArmarDateParaMySql($model->fecha_publicacionfin);
                        $fecha_pubfin = date_create($fecha_pubfin);
                        $fecha_pubfin = date_format($fecha_pubfin, 'Y-m-d');
                        $model->fecha_publicacionfin = $fecha_pubfin;

                        if (($model->ver_info_empresa == null) || ($model->ver_info_empresa == 0))
                        {
                            $model->info_empresa=null;
                        }
                        
                
                if($model->save())
                {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_oferta_laboral', $model->id, $model->getAttributes());
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "RUMBO:: Nueva Oferta Laboral",
                        'content'=>'<span class="text-success">Nueva Oferta Laboral Creada Exitosamente</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Crear más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
            
                    ];          

                }
                else{           
                    return [
                        'title'=> "RUMBO:: Nueva Oferta Laboral",
                        'content'=>$this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
            
                    ];         
                }
                
            }else{           
                return [
                    'title'=> "RUMBO:: Nueva Oferta Laboral",
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_oferta_laboral', $model->id, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Mds_rum_oferta_laboral model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);   
        //$postulados_borrar = Mds_rum_postulacion::find()->where(["id_oferta" => $model->id])->all();    

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "RUMBO:: Editar Oferta Laboral",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ]; 
                
                /*}else if($model->load($request->post()) ){
                $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                if (isset($tmpfile)) {
                  
                    $extension= $tmpfile->extension;
                    $nuevo_nombre=$model->random_filename(30, '/uploads/ofertas',$extension);
                    $model->imagen = $nuevo_nombre ;                                 
                    $tmpfile->saveAs('uploads/ofertas/' . $nuevo_nombre );                    
                   
                } else 
                {
                };*/

            }
            else if($model->load($request->post() ) )
            { 
                
                        $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                        if (isset($tmpfile)) 
                        {
                        
                            $extension= $tmpfile->extension;
                            $nuevo_nombre=$model->random_filename(30, '/uploads/ofertas',$extension);
                            $model->imagen = $nuevo_nombre ;                                 
                            $tmpfile->saveAs('uploads/ofertas/' . $nuevo_nombre );                    
                        
                        } 
                        else 
                        {
                        };               
                        $fechamodificacion=strftime( "%Y-%m-%d", time() );  
                        $horamodificacion=strftime( "%H:%M:%S", time() );
                        
                        $model->fechamodificacion=$fechamodificacion;
                        $model->horamodificacion=$horamodificacion;
                        

                        $fecha_pubini = ArmarDateParaMySql($model->fecha_publicacion);
                        $fecha_pubini = date_create($fecha_pubini);
                        $fecha_pubini = date_format($fecha_pubini, 'Y-m-d');
                        $model->fecha_publicacion = $fecha_pubini;

                        $fecha_pubfin = ArmarDateParaMySql($model->fecha_publicacionfin);
                        $fecha_pubfin = date_create($fecha_pubfin);
                        $fecha_pubfin = date_format($fecha_pubfin, 'Y-m-d');
                        $model->fecha_publicacionfin = $fecha_pubfin;


                        if (($model->ver_info_empresa == null) || ($model->ver_info_empresa == 0))
                        {
                            $model->info_empresa=null;
                        }

                        if ($model->save()) 
                        {   
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_oferta_laboral', $model->id, $model->getAttributes());                
                            return [
                                'forceReload'=>'#crud-datatable-pjax',
                                'title'=> "RUMBO:: Editar Oferta Laboral",
                                'content'=>$this->renderAjax('view', [
                                    'model' => $model,
                                ]),
                                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                            ];  
                        } 
                        else
                        {
                            return [
                            'title'=> "RUMBO:: Editar Oferta Laboral",
                            'content'=>$this->renderAjax('update', [
                                'model' => $model,
                            ]),
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                        ];        
                        }           
            }
            else
            {
                 return [
                    'title'=> "RUMBO:: Editar Oferta Laboral",
                    'content'=>$this->renderAjax('update', [
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_oferta_laboral', $model->id, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_rum_oferta_laboral model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_rum_oferta_laboral', $id, $model->getAttributes());
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
     * Finds the Mds_rum_oferta_laboral model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_rum_oferta_laboral the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_rum_oferta_laboral::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

 function ArmarDateParaMySql($Fecha)
{
    if ($Fecha == null) {
        return null;
    }
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}