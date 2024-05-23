<?php

namespace app\controllers;

use Yii;
use app\models\Mds_r_planilla;
use app\models\Mds_r_variable_dimension;
use app\models\Mds_r_variable_dimensionSearch;
use app\models\Mds_r_planillaSearch;
use app\models\Mds_r_plantilla;
use app\models\Mds_r_diagnostico;
use app\models\Mds_seg_item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
//use yii\filters\AccessRule;
use app\components\AccessRule;

date_default_timezone_set('America/Argentina/Buenos_Aires');
/**
 * Mds_r_planillaController implements the CRUD actions for Mds_r_planilla model.
 */
class Mds_r_planillaController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view','exportarplanilla'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view','exportarplanilla'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MDS_R_PLANILLA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_r_planilla models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Mds_r_planillaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);                                       

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        
        ]);
    }
    public function actionExportarplanilla($idplanilla)
    {
        //Genera un PDF con todo el detalle de la planilla

        $usuarioAuth = Yii::$app->user->identity;

        $model = $this->findModel($idplanilla);
        $content = $this->renderPartial('reporte_planilla', [
            'model' => $model,
        ]);
        $dateToday = date('d/m/Y H:i:s');
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}',
            'methods' => [
                'SetTitle' => 'PLANILLA DE DIAGNOSTICOS ' . $idplanilla,
                'SetHeader' => null,
                'SetFooter' => ["<p style='text-align:left'>Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
            ]
        ]);
        

        return $pdf->render();
    }


    /**
     * Displays a single Mds_r_planilla model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $elmodelo=$this->findModel($id);            
            return [
                    'title'=> "Ver Planilla Diagnostico",
                    'content'=>$this->renderAjax('view', [
                        'model' => $elmodelo,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])                            
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Mds_r_planilla model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_r_planilla();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear Nueva Planilla",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                $usuarioAuth = Yii::$app->user->identity;
                
                $model->activo=1;
                $fecha = date('Y-m-d h:i:s', time());                
                $model->fechacarga=$fecha;                
                if($model->save())
                { 
                    $tupla_plantilla=Mds_r_plantilla::find()
                  ->where(['idtipoplantilla' => $model->idplantilla])                        
                  ->all();
                  
                  $fechahora=strftime( "%Y-%m-%d %H:%M:%S ", time() );
                  $guardado = 1;
                  
                  foreach($tupla_plantilla as $una_tupla){
                    $obj_var_diag = new Mds_r_variable_dimension();
                    $obj_var_diag -> idplanilla = $model -> idplanilla;
                    $obj_var_diag -> idvariable = $una_tupla -> variable_diagnostico;
                    $obj_var_diag -> iddimension = $una_tupla -> dimension;
                    $obj_var_diag -> origen = $una_tupla -> origen;
                    $obj_var_diag -> fecha_actualizacion = $fechahora;
                    $obj_var_diag -> fecha_carga = $fechahora;
                    $obj_var_diag -> mapear = 0;
                    $obj_var_diag -> tipomapa = null;
                    $obj_var_diag -> detalle = null;
                    $obj_var_diag -> observacion = null;
                    $obj_var_diag -> id_giscapa = $una_tupla->id_gis_capa;
                    $obj_var_diag -> activo =1;
                    if(!$obj_var_diag->save()){
                        $guardado = 0;
                    }
                  }

                  if($guardado == 1){
                    return [
                        //'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Crear Nueva Planilla",
                        'content'=>'<span class="text-success">La planilla se ha guardado exitosamente</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])                                            
                    ];
                  }            
                }
                else
                {   //print_r($model->getErrors());
                    return [
                        'title'=> "ERROR AL GUARDAR",
                        'content'=>$this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
            
                    ];   
                }

                      
            }else{           
                return [
                    'title'=> "Create new Mds_r_planilla",
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
                return $this->redirect(['view', 'id' => $model->idplanilla]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Mds_r_planilla model.
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
                    'title'=> "Actualizar Planilla",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){

                if($model->save())
                {
                    return [
                        
                        'title'=> "Actualizar Planilla",
                        'content'=>'<span class="text-success">La planilla se ha actualizado exitosamente</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])                                            
                    ];       
                }                
            }else{
                 return [
                    'title'=> "Update Mds_r_planilla #".$id,
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
                return $this->redirect(['view', 'id' => $model->idplanilla]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_r_planilla model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $una_planilla=$this->findModel($id);
        $una_planilla->activo=0;

        //->delete();
        $dimensiones=Mds_r_variable_dimension::find()
        ->where(['idplanilla' => $una_planilla->idplanilla])                      
        ->all();
        //hay que eliminar los disgnosticos asociados a cada variable dimension:
        foreach ($dimensiones as $una_dimension) {

            $los_diagnosticos=Mds_r_diagnostico::find()
            ->where(['idvardimension' => $una_dimension->idvardimension])                      
            ->all();
            foreach ($los_diagnosticos as $un_diagnostico) {
                $un_diagnostico->activo=0;
                $un_diagnostico->save();
                //$un_diagnostico->delete();
            }
            $una_dimension->activo=0;
            $una_dimension->save();
        }
                    
        $una_planilla->activo=0;
        $una_planilla->save();


        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#ajaxCrudDatatable10'];
            //return $this->redirect(['index']);
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }

    }
    /**
     * Finds the Mds_r_planilla model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_r_planilla the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_r_planilla::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
