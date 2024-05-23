<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_reg_contrasenia;
use app\models\Mds_reg_contraseniaSearch;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use app\models\Mds_sys_log;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\mpdf\Pdf;
/**
 * Mds_reg_contraseniaController implements the CRUD actions for Mds_reg_contrasenia model.
 */
class Mds_reg_contraseniaController extends Controller
{
    /**
     * @inheritdoc
     */
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
                'only' => ['index', 'create', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_REG_REGISTROS,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_reg_contrasenia models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Mds_reg_contraseniaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_reg_contrasenia model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_reg_contrasenia', $model->idcontrasenia, $model->getAttributes());
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> '',
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
     * Creates a new Mds_reg_contrasenia model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_reg_contrasenia();
        $model->fecha_carga=date('Y-m-d');
        $user=Mds_seg_usuario::findOne(Yii::$app->user->identity->idusuario);
        $model->idorganismo=$user->organismo_stock;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $mensaje_success='';
        $mensaje_error='';

        if ($user->organismo_stock==null){
            $mensaje_error='<span class="text-warning">Usted no posee permisos para crear una contraseña.</span>';
        }
        if($request->isAjax){
           if($model->load($request->post())){   
               // Se verifica si la IP ya existe      
                $ip=Mds_reg_contrasenia::find()->where(['like', 'ip', $model->ip])->all();
                if ($ip==null){
                    if($model->save()){
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_reg_contrasenia', $model->idcontrasenia, $model->getAttributes()); 
                        $model=new Mds_reg_contrasenia();
                        $mensaje_success='<h3 style="margin-top:0;">¡Excelente!</h3><b>Contraseña guardada</b>';
                    }else{
                        $mensaje_error='<span class="text-danger"><b>Error al guardar contraseña. Intente nuevamente</b></span>';
                    }
                }else{
                    $model->addError('ip', 'La IP ya se encuentra en uso');
                }
            }
            if($mensaje_success!=''){
                $force="#crud-datatable-pjax";
            }else{
                $force=null;
            }
            return [
                    'forceReload'=>$force,
                    'title'=> "Alta de Contraseña",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                        'mensaje_success' => $mensaje_success,
                        'mensaje_error' => $mensaje_error
                    ]),
                    'footer'=> Html::button('Cancelar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
            ];
        }
       
    }

    /**
     * Updates an existing Mds_reg_contrasenia model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $mensaje_success='';
        $mensaje_error='';       

        if($request->isAjax){
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load($request->post())){
                // Comprueba si la IP existe en la base de datos y de si es ella misma mediante el idcontrasenia
                $ip=Mds_reg_contrasenia::find()->where(['like', 'ip', $model->ip])->one();
                if($ip==null || ($ip!=null && $ip->idcontrasenia==$model->idcontrasenia)){
                    if($model->save()){
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_reg_contrasenia', $model->idcontrasenia, $model->getAttributes()); 
                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Contraseña #".$id,
                            'content'=>$this->renderAjax('view', [
                                'model' => $model,
                            ]),
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                        ];    
                    }
                }else{
                    $model->addError('ip', 'La IP ya se encuentra en uso');
                }
            } 
            return [
                    'title'=> "Cambiar Contraseña #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'mensaje_success'=> $mensaje_success,
                        'mensaje_error' => $mensaje_error
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Editar',['class'=>'btn btn-primary','type'=>"submit"])
            ];
        }
    }

    /**
     * Delete an existing Mds_reg_contrasenia model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model=$this->findModel($id);
        if ($model->delete()){
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_reg_contrasenia', $model->idcontrasenia, $model->getAttributes());
            return $this->redirect(['index']);
        }
    }

     /**
     * Delete multiple existing Mds_reg_contrasenia model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    

    /**
     * Finds the Mds_reg_contrasenia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_reg_contrasenia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_reg_contrasenia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionReporte_contrasenia(){
        //Edito la propiedad pcre.backtrack_limit del php.ini para que permita mayor cantidad de HTML en la pagina
        //ini_set("pcre.backtrack_limit", "10000000");
        $request = Yii::$app->request;
        $pks = (array) $request->post('selection');
        if (empty($pks)) {
            return $this->redirect('index.php?r=mds_reg_contrasenia');
        }
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_reg_contrasenia/reporte_contrasenia', null, array('idcontrasenias' => $pks));
        $content = $this->renderPartial('reporte_contrasenia', ['ids' => $pks]); // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'marginTop' => 10,
            'marginBottom' => 0,
            'marginLeft' => 1,
            'marginRight' => 0,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'Reporte Contraseñas',
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);
        return $pdf->render();
    }
}
