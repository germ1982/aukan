<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_bdc_visita;
use app\models\Sds_bdc_visitaSearch;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Sds_bdc_visitaController implements the CRUD actions for Sds_bdc_visita model.
 */
class Sds_bdc_visitaController extends Controller
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
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
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

    public function actionIndex()
    {    
        $searchModel = new Sds_bdc_visitaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Visita",
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary pull-right','role'=>'modal-remote']).
                        Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_bdc_visita();
        $sectores = Sds_com_configuracion::find()->where(['idconfiguraciontipo'=>Sds_com_configuracion_tipo::BDC_VISITA, 'activo'=>1])->all();
        $forceReload=false;


        if($request->isAjax){
            /* Process for ajax request*/
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isPost){
                if($model->load($request->post())){
                    $model->fecha=date('Y-m-d', strtotime($model->fecha));
                    if($model->save()){
                        Yii::$app->session->setFlash('save', 'Los datos se guardaron de manera correcta.');
                        $model = new Sds_bdc_visita();
                    }else{
                        Yii::$app->session->setFlash('fail-save', 'Error al guardar los datos.');
                    }
                }
            }
            
            return [
                //'forceReload'=> $forceReload ? '#crud-datatable-pjax' : '',
                'title'=> "Cargar Visita",
                'content'=>$this->renderAjax('create', [
                    'model' => $model,
                    'sectores' => $sectores
                ]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::button('Guardar',['class'=>'btn btn-success','type'=>"submit"])
            ];
        }else{
            return $this->redirect(['index']);
        }
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        $sectores = Sds_com_configuracion::find()->where(['idconfiguraciontipo'=>Sds_com_configuracion_tipo::BDC_VISITA, 'activo'=>1])->all();
        
        if($request->isAjax){
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load($request->post())){
                $model->fecha=date('Y-m-d', strtotime($model->fecha));
                if($model->save()){
                    return [
                        'title'=> "Visita",
                        'content'=>$this->renderAjax('view', [ 
                            'model' => $model,
                        ]),
                        'footer'=> Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary pull-right','role'=>'modal-remote']).
                        Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    ];
                }
            }
            return [
                'title'=> "Actualizar Visita",
                'content'=>$this->renderAjax('update', [
                    'model' => $model,
                    'sectores' => $sectores,
                ]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-danger pull-left','data-dismiss'=>"modal"]).
                            Html::button('Guardar',['class'=>'btn btn-success pull-right','type'=>"submit"])
            ];
        }
        return $this->redirect(['index']);
    }
    
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $this->redirect(['index']);
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

    protected function findModel($id)
    {
        if (($model = Sds_bdc_visita::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La pagina requerida no existe');
        }
    }
}
