<?php

namespace app\controllers;

use Yii;
use app\models\Mds_rum_empleador;
use app\models\Mds_rum_empleadorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use app\models\Mds_rum_domicilio;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_seg_usuario;

use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\Mds_seg_item;
use yii\db\Query;
use app\models\Mds_sys_log;
date_default_timezone_set('America/Argentina/Buenos_Aires');

/**
 * Mds_rum_empleadorController implements the CRUD actions for Mds_rum_empleador model.
 */
class Mds_rum_empleadorController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'enviar_datos'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'enviar_datos'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_RUM_EMPLEADOR,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_rum_empleador models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Mds_rum_empleadorSearch();
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
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_empleador', null, array());

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
                
                /*$empresas_usuario=Mds_rum_empleador::find()                                                              
                           ->where(['idpersona' => $usuario->idusuario])                           
                           ->all();  */

                $dataProvider->query->where(['idpersona' => $usuario->idusuario]);
                
            }

        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_rum_empleador model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_empleador', $id, array());
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "RUMBO:: Ver Empresa",
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
     * Creates a new Mds_rum_empleador model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_rum_empleador();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "RUMBO:: Nuevo Empresa",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else 
                if($model->load($request->post()) )
                {
                    $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                    if (isset($tmpfile)) {
                      
                        $extension= $tmpfile->extension;
                        $nuevo_nombre=$model->random_filename(30, '/uploads/empleador',$extension);
                        $model->imagen = $nuevo_nombre ;                                 
                        $tmpfile->saveAs('uploads/empleador/' . $nuevo_nombre );                    
                       
                    } else 
                    {
                    };

                    $transaction = Yii::$app->db->beginTransaction();
                    $model_dom = new Mds_rum_domicilio;
                    $model_dom->calle = $model->calle;
                    $model_dom->numero = $model->numero;
                    $model_dom->barrio = $model->barrio;
                    if ($model->idlocalidad==null)
                    {
                        $model_dom->idlocalidad =1; 
                    }else
                    {
                        $model_dom->idlocalidad = $model->idlocalidad;
                    }
                    
                    $model_dom->manzana = $model->manzana;
                    $model_dom->monoblock = $model->monoblock;
                    $model_dom->piso = $model->piso;
                    $model_dom->dpto = $model->dpto;
                    $model_dom->lote = $model->lote;

                    if ($model_dom->save()){} else{ }
                    
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_empleador', $model->id, $model->getAttributes());
                    //$model->nombre=$model->id_categoria;   
                    $model->iddomicilio=$model_dom->id;                    
                    //$model->id_categoria=262;
                    $guardo=false;

                    $fechaalta=strftime( "%Y-%m-%d", time() );  
                    $horaalta=strftime( "%H:%M:%S", time() );
                    $fechamodificacion=$fechaalta;  
                    $horamodificacion=$horaalta;  	                   	
                    $model->fechaalta=$fechaalta;
                    $model->horaalta=$horaalta;
                    $model->fechamodificacion=$fechamodificacion;
                    $model->horamodificacion=$horamodificacion;                     

                    $guardo=$model->save();
                    if ($guardo) 
                    { 
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_empleador', $model->id, $model->getAttributes());

                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "RUMBO:: Crear nuevo Empleador ",
                            'content'=>'<span class="text-success">Nuevo Empleador Creado Exitosamente</span>',
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a('Crear más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                
                        ];
                    }     
                    else
                    {

                        (new Query)
                        ->createCommand()
                        ->delete('mds_rum_domicilio', ['id' => $model_dom->id])
                        ->execute();  

                        return [
                            'title'=> "RUMBO:: Nuevo Empleador",
                            'content'=>$this->renderAjax('create', [
                                'model' => $model,
                            ]),
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                
                        ];   

                    } 
                    
                }
                else{           
                return [
                    'title'=> "RUMBO:: Nuevo Empleador",
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
              Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_empleador', $model->id, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }
    public function actionEnviar_datos($id) // Permite enviar un email, con los datos de la cuenta, al usuario asociado. El codigo se ejecuta cuando se presiona el boton "Envia datos" 
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id); 
        $cad_estado="asdas";
        $id_persona=$model->idpersona;
        $un_usuario=Mds_seg_usuario::findOne($id_persona); 
        $fechaenvio=strftime( "%Y-%m-%d", time() ); 
        $unafecha = explode ("-",$fechaenvio);
        $fechaenvio= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]);  
        $horaenvio=strftime( "%H:%M:%S", time() );	
        $mail_user=$model->email;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_empleador', $id, array());
        $cad_cuerpo1='<!doctype html>
        <html>
        
        <head>
          <meta name="viewport" content="width=device-width">
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <title>Simple Transactional Email</title>
          <style>
            /* -------------------------------------
                INLINED WITH htmlemail.io/inline
            ------------------------------------- */
            /* -------------------------------------
                RESPONSIVE AND MOBILE FRIENDLY STYLES
            ------------------------------------- */
            @media only screen and (max-width: 620px) {
              table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
              }
        
              table[class=body] p,
              table[class=body] ul,
              table[class=body] ol,
              table[class=body] td,
              table[class=body] span,
              table[class=body] a {
                font-size: 16px !important;
              }
        
              table[class=body] .wrapper,
              table[class=body] .article {
                padding: 10px !important;
              }
        
              table[class=body] .content {
                padding: 0 !important;
              }
        
              table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
              }
        
              table[class=body] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
              }
        
              table[class=body] .btn table {
                width: 100% !important;
              }
        
              table[class=body] .btn a {
                width: 100% !important;
              }
        
              table[class=body] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
              }
            }
        
            /* -------------------------------------
                PRESERVE THESE STYLES IN THE HEAD
            ------------------------------------- */
            @media all {
              .ExternalClass {
                width: 100%;
              }
        
              .ExternalClass,
              .ExternalClass p,
              .ExternalClass span,
              .ExternalClass font,
              .ExternalClass td,
              .ExternalClass div {
                line-height: 100%;
              }
        
              .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
              }
        
              #MessageViewBody a {
                color: inherit;
                text-decoration: none;
                font-size: inherit;
                font-family: inherit;
                font-weight: inherit;
                line-height: inherit;
              }
        
              .btn-primary table td:hover {
                background-color: #34495e !important;
              }
        
              .btn-primary a:hover {
                background-color: #34495e !important;
                border-color: #34495e !important;
              }
            }
          </style>
        </head>
        
        <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
          <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;"></span>
          <table border="0" cellpadding="0" cellspacing="0" class="body"
            style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
            <tr>
              <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
              <td class="container"
                style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
                <div class="content"
                  style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">
        
                  <!-- START CENTERED WHITE CONTAINER -->
                  <table class="main"
                    style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">
        
                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                      <td>
                        <img src="https://sur.neuquen.gov.ar/img/headermail.png" width="580px">
                      </td>
                    </tr>
                    <tr>
                      <td class="wrapper"
                        style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                        <table border="0" cellpadding="0" cellspacing="0"
                          style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                          <tr>
                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">                      
                              <p
                                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">';

                                $cad_cuerpo2admin='
                                Estimado/a administrador/a de <b>Rumbo</b>
                               
                                </p>
                              <p
                                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
                                Se ha enviado, desde SUR, información sobre la cuenta de un Usuario Asociado a una empresa </p>
                              <h3>Datos Enviados:</h3>
                              <table>
                                <tr>
                                  <td>Empresa:</td>
                                  <td style="font-weight: bold; font-style: italic;">'.$model->nombre.'</td>
                                </tr>';
                              $cad_cuerpo2user='
                                Estimado/a usuario/a de <b>Sur-Rumbo</b>
                               
                                </p>
                              <p
                                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
                                Se ha enviado, desde SUR, información sobre la cuenta de Rumbo de su "Usuario Asociado" a la empresa '.$model->nombre.' </p>
                                Para comenzar a operar con Rumbo ingrese al siguiente link: <a href="sur.neuquen.gov.ar" target="_blank">Acceso a Sur-Rumbo</a><br>
                              <h3>Datos Enviados:</h3>
                              <table>';
                              $cad_cuerpo3='                              
                                <tr>
                                  <td>Nombre de Usuario:</td>
                                  <td style="font-weight: bold; font-style: italic;">'.$un_usuario->user.'</td>
                                </tr>
                                <tr>
                                  <td>Contraseña (si es la primera vez que ingresás):</td>
                                  <td>'.$un_usuario->user.'</td>
                                </tr>
                                <tr>
                                  <td colspan="2">                                  
                                    Si ya tenés usuario en SUR-Rumbo, ingresá tu contraseña habitual
                                  </td>                                  
                                </tr>
                                <tr>
                                  <td colspan="2">                                  
                                  Si olvidastes tu contraseña comunicate con nosotros enviándonos un email a rumbo@neuquen.gov.ar
                                  </td>                                  
                                </tr>
                                <tr>
                                  <td>
                                      <br>
                                      <a href="https://www.youtube.com/watch?v=Y9DrhK_gWbE" target="_blank"> Video Instructivo</a><br>
                                  </td>
                                  
                                </tr>
                                <tr>
                                <td>
                                    <br>                                    
                                    Descargar la <a href="https://sur.neuquen.gov.ar/guiasrumbo/guia_del_empresario.pdf" download >GUIA DE USO PARA EL EMPRESARIO (versión pdf)</a><br>
                                </td>
                                
                              </tr>
                                ';
                                $cad_cuerpo4admin='
                                <tr>
                                  <td>Nombre/s y Apellido/s del usuario asociado:</td>
                                  <td style="font-weight: bold; font-style: italic;">'.$un_usuario->nombre.' '.$un_usuario->apellido.'</td>
                                </tr>';
                                $cad_cuerpo5='
                                <tr>
                                  <td>Datos enviados el '.$fechaenvio.' a las '.$horaenvio.'</td>                                 
                                </tr>
                              </table>             
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>            
                    <tr>
                      <td>
                        <br>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <img src="https://sur.neuquen.gov.ar/img/footermail.png" width="580px">
                      </td>
                    </tr>
        
                    <!-- END MAIN CONTENT AREA -->
                  </table>
        
                  <!-- START FOOTER -->
                  <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                    <table border="0" cellpadding="0" cellspacing="0"
                      style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                      <tr>
                        <td class="content-block"
                          style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                          <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Ministerio de
                            Desarrollo Social y Trabajo
                          </span>
                        </td>
                      </tr>
                      <tr>
                        <td class="content-block powered-by"
                          style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                          Enviado por "Rumbo" un Producto de "SUR - Sistema Unico de Registro"
                        </td>
                      </tr>
                    </table>
                  </div>
                  <!-- END FOOTER -->
        
                  <!-- END CENTERED WHITE CONTAINER -->
                </div>
              </td>
              <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
            </tr>
          </table>
        </body>
        
        </html>';
        //$cad_cuerpo = str_replace('$fullname','Luis Eduardo Garcia', $cad_cuerpo);
        $cad_cuerpo_admin=$cad_cuerpo1.$cad_cuerpo2admin.$cad_cuerpo3.$cad_cuerpo4admin.$cad_cuerpo5;
        $cad_cuerpo_user=$cad_cuerpo1.$cad_cuerpo2user.$cad_cuerpo3.$cad_cuerpo5;
        Yii::$app->mailer->compose()        
        ->setFrom('rumbo@neuquen.gov.ar')
        ->setTo('rumbo@neuquen.gov.ar')
        ->setSubject('Sur-Rumbo. Notificación sobre los datos de Usuario asociado a la Empresa "'.$model->nombre.'"')
        ->setTextBody($cad_estado)
        ->setHtmlBody( $cad_cuerpo_admin)
        ->send();

        
        Yii::$app->mailer->compose()
        ->setFrom('rumbo@neuquen.gov.ar')
        ->setTo($mail_user)
        ->setSubject('Rumbo: datos de Usuario asociado a la Empresa "'.$model->nombre.'"')
        ->setTextBody($cad_estado)
        ->setHtmlBody( $cad_cuerpo_user)
        ->send();

        return $cad_estado;
    }
    /**
     * Updates an existing Mds_rum_empleador model.
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
                    'title'=> "RUMBO:: Editar Empleador",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){

                    $tmpfile = UploadedFile::getInstance($model, 'archivo_imagen');
                    if (isset($tmpfile)) {
                      
                        $extension= $tmpfile->extension;
                        $nuevo_nombre=$model->random_filename(30, '/uploads/empleador',$extension);
                        $model->imagen = $nuevo_nombre ;                                 
                        $tmpfile->saveAs('uploads/empleador/' . $nuevo_nombre );                    
                       
                    } else 
                    {
                    };
                    $transaction = Yii::$app->db->beginTransaction();
                    $model_dom = new Mds_rum_domicilio;
                    $model_dom->calle = $model->calle;
                    $model_dom->numero = $model->numero;
                    $model_dom->barrio = $model->barrio;
                    $model_dom->idlocalidad = $model->idlocalidad;
                    $model_dom->manzana = $model->manzana;
                    $model_dom->monoblock = $model->monoblock;
                    $model_dom->piso = $model->piso;
                    $model_dom->dpto = $model->dpto;
                    $model_dom->lote = $model->lote;
                    $model_dom->duplex = $model->duplex;

                    if ($model_dom->save()){}
                    else{}
                    
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_empleador', $model->id, $model->getAttributes());
                    //$model->nombre=$model->id_categoria;   
                    $model->iddomicilio=$model_dom->id;                    
                    //$model->id_categoria=262;

                    $fechamodificacion=strftime( "%Y-%m-%d", time() );  
                    $horamodificacion=strftime( "%H:%M:%S", time() );                                     	                   
                    $model->fechamodificacion=$fechamodificacion;
                    $model->horamodificacion=$horamodificacion;  

                    if ($model->save()) 
                    {
                      Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_empleador', $model->id, $model->getAttributes());
                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "RUMBO:: Editar Cualificación",
                            'content'=>$this->renderAjax('view', [
                                'model' => $model,
                            ]),
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                        ];  
                    }else
                    {
                        return [
                            'title'=> "RUMBO:: Editar Cualificación",
                            'content'=>$this->renderAjax('update', [
                                'model' => $model,
                            ]),
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                        ];    
                    }                
            }else{
                 return [
                    'title'=> "RUMBO:: Editar Cualificación",
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
              Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_empleador', $model->id, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_rum_empleador model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_rum_empleador', $id, $model->getAttributes());
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
     * Finds the Mds_rum_empleador model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_rum_empleador the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_rum_empleador::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
