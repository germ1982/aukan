<?php

namespace app\controllers;

use Yii;
use app\models\Mds_r_variable_dimension;
use app\models\Mds_r_variable_dimensionSearch;
use app\models\Mds_r_diagnostico;
use app\models\Mds_r_planilla;
use app\models\Mds_r_plantilla;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_seg_item;
use yii\filters\AccessControl;
//use yii\filters\AccessRule;
use app\components\AccessRule;

/**
 * Mds_r_variable_dimensionController implements the CRUD actions for Mds_r_variable_dimension model.
 */
class Mds_r_variable_dimensionController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' =>['index', 'create', 'update', 'delete', 'view'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MDS_R_PLANILLA,
                            Mds_seg_item::MDS_R_PLANTILLA,
                        ],
                    ],
                ],
            ],
        ];
    }
    /**
     * Lists all Mds_r_variable_dimension models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Mds_r_variable_dimensionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['activo' =>'1'])  
                /*->orderBy([
                    'activo' => SORT_DESC ,
                    'fecha_publicacion'=>SORT_DESC
                    ])*/;

        return $this->render('index', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_r_variable_dimension model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Ver Variable Dimensión",
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
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
     * Creates a new Mds_r_variable_dimension model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idplanilla)
    {
        $request = Yii::$app->request;
        $model = new Mds_r_variable_dimension();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear nueva Variable de Dimensión",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                        'idplanilla' => $idplanilla,
                       
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) ){

                $fecha = date('Y-m-d h:i:s', time());
                $model->fecha_carga = $fecha;
                $model->fecha_actualizacion = $fecha;
                $model->idplanilla = $idplanilla;
                $model->activo = 1;
                if (!$model->mapear)
                {
                    $model->tipomapa=null;
                }
                //recuperar datos de la plantilla:                       
                if ($model-> origen != Mds_r_plantilla::CONST_DISP){
                    $model-> id_gis_capa = null;                    
                }
                else
                {   //hay que ir a la plantilla a buscarlo:
                    $una_planilla=Mds_r_planilla::find()
                    ->where(['idplanilla' => $model->idplanilla])                      
                    ->one();
                    $plantilla = Mds_r_plantilla::find()
                    ->where(['idtipoplantilla' => $una_planilla->idplantilla])
                    ->andWhere(['variable_diagnostico'=>$model->idvariable])
                    ->andWhere(['dimension'=>$model->iddimension])
                    ->one();
                    $model->id_giscapa=$plantilla->id_gis_capa;                   
                }

                $model->save();
               $namegrid='crud-datatable'.$model->idplanilla;                
                return [
                    //'forceReload'=>'#crud-datatable- pjax',                  
                    'forceReload'=>'#'.$namegrid.'-pjax',
                    'title'=> "Crear Variable Dimensión",
                    'content'=>'<span class="text-success">Variable Dimensión creada exitosamente</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Mds_r_variable_dimension",
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
                return $this->redirect(['view', 'id' => $model->idvardimension]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Mds_r_variable_dimension model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id,$idplanilla)
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
                    'title'=> "Actualizar Variable Dimensión ",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'idplanilla' => $idplanilla,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) ){

                $fecha = date('Y-m-d h:i:s', time());            
                $model->fecha_actualizacion = $fecha;
                
                $model->idvariable =$model->id_variable_dim;
                $model->iddimension =$model->id_iddimension_var;

                if (!$model->mapear)
                {
                    $model->tipomapa=null;
                }
                //hay que evaluar si la dimension cambio.
                //En caso afirmativo. hay que actualizar el origen
                //y probablemente el id_gis_capa:
                $una_var_dim=Mds_r_variable_dimension::find()
                ->where(['idvardimension' => $model->idvardimension])                      
                ->one();

                if ($model->iddimension != $una_var_dim->iddimension)
                {
                        //recuperar datos de la plantilla:                       
                        if ($model-> origen != Mds_r_plantilla::CONST_DISP){
                            $model-> id_gis_capa = null;                    
                        }
                        else
                        {   //hay que ir a la plantilla a buscarlo:
                            $una_planilla=Mds_r_planilla::find()
                            ->where(['idplanilla' => $model->idplanilla])                      
                            ->one();
                            $plantilla = Mds_r_plantilla::find()
                            ->where(['idtipoplantilla' => $una_planilla->idplantilla])
                            ->andWhere(['variable_diagnostico'=>$model->idvariable])
                            ->andWhere(['dimension'=>$model->iddimension])
                            ->one();
                            $model->id_giscapa=$plantilla->id_gis_capa;                   
                        }
                }

                
                $model->save();
                $namegrid='crud-datatable'.$model->idplanilla;                                                 
                return [
                    //'forceReload'=>'#crud-datatable-pjax','forceReload'=>'#crud-datatable-pjax',
                    //'forceClose'=>true,
                    'forceReload'=>'#'.$namegrid.'-pjax',
                    'title'=> "Actualizar Variable Dimensión",
                    'content'=>'<span class="text-success">La Variable Dimensión ha sido actualizada exitosamente</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                ];    
            }else{
                 return [
                    'forceReload'=>'#crud-datatable'.$model->idplanilla.'-pjax',
                    'title'=> "Update Mds_r_variable_dimension #".$id,
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
                return $this->redirect(['view', 'id' => $model->idvardimension]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_r_variable_dimension model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $unadimension=$this->findModel($id);
        $unadimension->activo=0;
        $unadimension->save();
        $los_diagnosticos=Mds_r_diagnostico::find()
            ->where(['idvardimension' => $unadimension->idvardimension])                      
            ->all();
        foreach ($los_diagnosticos as $un_diagnostico) {
            $un_diagnostico->activo=0;
            $un_diagnostico->save();                
        }            

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
           
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable'.$unadimension->idplanilla.'-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }
    /**
     * Finds the Mds_r_variable_dimension model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_r_variable_dimension the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_r_variable_dimension::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
