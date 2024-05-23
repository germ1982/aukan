<?php
namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use Yii;
use app\models\Sds_reg_fotocopiadora;
use app\models\Sds_reg_fotocopiadoraSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Sds_reg_fotocopiadoraController implements the CRUD actions for Sds_reg_fotocopiadora model.
 */
class Sds_reg_fotocopiadoraController extends Controller
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
                            Mds_seg_item::BDC_EQUIPO
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_reg_fotocopiadora models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Sds_reg_fotocopiadoraSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_reg_fotocopiadora model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    { 
        $model = $this->findModel($id);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_reg_fotocopiadora', $model->idfotocopiadora, $model->getAttributes()); 
        $model->vencimiento = date('d/m/Y', strtotime($model->vencimiento));
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> '',
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cancelar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Sds_reg_fotocopiadora model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_reg_fotocopiadora();
        $model->vencimiento=date('d/m/Y');

        if($request->isAjax){
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;

            if($model->load($request->post()) && $request->post()) {
                $fecha = str_replace('/', '-', $request->post('Sds_reg_fotocopiadora')['vencimiento']);
                $model->vencimiento = date('Y-m-d', strtotime($fecha));
                if($model->save()){
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_reg_fotocopiadora', $model->idfotocopiadora, $model->getAttributes()); 
                    $model=new Sds_reg_fotocopiadora();
                    $model->vencimiento=date('d/m/Y');
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Nueva Fotocopiadora",
                        'content'=>'<span class="text-success">Guardada Correctamente!!</span>',
                        'footer'=> Html::button('Cancelar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Agregar Más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];  
                }else{
                    $model->vencimiento = date('d/m/Y', strtotime($fecha));
                }       
            }
            return [
                'title'=> "Nueva Fotocopiadora",
                'content'=>$this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer'=> Html::button('Cancelar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
            ];         
        } 
    }

    /**
     * Updates an existing Sds_reg_fotocopiadora model.
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
            $model->vencimiento = date('d/m/Y', strtotime($model->vencimiento));
            if($model->load($request->post())){
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_reg_fotocopiadora', $model->idfotocopiadora, $model->getAttributes()); 
                $fecha = str_replace('/', '-', $model->vencimiento);
                $model->vencimiento = date('Y-m-d', strtotime($fecha));
                if($model->save()){
                    $model->vencimiento = date('d/m/Y', strtotime($model->vencimiento));
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Fotocopiadora",
                        'content'=>$this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Cancelar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Editar Fotocopiadora',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];
                }else{
                    $model->vencimiento = date('d/m/Y', strtotime($fecha));
                }    
            }
            return [
                'title'=> "Editar Fotocopiadora",
                'content'=>$this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer'=> Html::button('Cancelar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
            ];
        }
        // else{
        //     /*
        //     *   Process for non-ajax request
        //     */
        //     if ($model->load($request->post()) && $model->save()) {
        //         return $this->redirect(['view', 'id' => $model->idfotocopiadora]);
        //     } else {
        //         return $this->render('update', [
        //             'model' => $model,
        //         ]);
        //     }
        // }
    }

    /**
     * Delete an existing Sds_reg_fotocopiadora model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model=$this->findModel($id);
        $model_aux=$model;
        if($model->delete()){
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_reg_fotocopiadora', $model_aux->idfotocopiadora, $model_aux->getAttributes());
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
     * Delete multiple existing Sds_reg_fotocopiadora model.
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
     * Finds the Sds_reg_fotocopiadora model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_reg_fotocopiadora the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_reg_fotocopiadora::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
