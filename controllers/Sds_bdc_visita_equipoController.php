<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Sds_bdc_equipo;
use app\models\Sds_bdc_visita;
use Yii;
use app\models\Sds_bdc_visita_equipo;
use app\models\Sds_bdc_visita_equipoSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Sds_bdc_visita_equipoController implements the CRUD actions for Sds_bdc_visita_equipo model.
 */
class Sds_bdc_visita_equipoController extends Controller
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

    public function actionIndex($idvisita=null)
    {
        $equipos = Sds_bdc_equipo::findBySql(
            "SELECT e.*, c.descripcion tipo_descripcion, c2.descripcion marca_descripcion
            FROM sds_bdc_equipo e
            JOIN sds_com_configuracion c ON e.tipo=c.idconfiguracion
            JOIN sds_com_configuracion c2 ON e.marca=c2.idconfiguracion 
            WHERE e.idequipo IN (SELECT ve.idequipo FROM sds_bdc_visita_equipo ve group by ve.idequipo)"
            )->all();

        $searchModel = new Sds_bdc_visita_equipoSearch();
        $searchModel->idvisita=$idvisita;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $visita = Sds_bdc_visita::findBySql("SELECT v.*, (c.descripcion) sector_descripcion FROM sds_bdc_visita v JOIN sds_com_configuracion c ON v.sector = c.idconfiguracion WHERE v.idvisita = $idvisita")->one();

        $responsables = Mds_org_contacto::findBySql(
            "SELECT c.*, CONCAT(p.apellido, ', ', p.nombre) nombre
            FROM  mds_org_contacto c
            JOIN sds_com_persona p ON c.idpersona = p.idpersona
            WHERE c.idcontacto IN(SELECT ve.idresponsable FROM sds_bdc_visita_equipo ve group by ve.idresponsable)"
        )->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'responsables' => $responsables,
            'equipos' => $equipos,
            'visita' => $visita,
        ]);
    }


    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = $this->findModel($id);
            $responsable = Mds_org_contacto::findBySql(
                "SELECT c.*, CONCAT(p.apellido, ', ', p.nombre) nombre FROM mds_org_contacto c
                JOIN sds_com_persona p ON c.idpersona=p.idpersona 
                WHERE idcontacto=$model->idresponsable")->one();
            
            $model->responsable= $responsable->nombre;
            return [
                    'title'=> "Equipo",
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-danger pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionCreate($idvisita=null)
    {
        $request = Yii::$app->request;
        $model = new Sds_bdc_visita_equipo();
        $model->idvisita = $idvisita;
        $equipos = Sds_bdc_equipo::findBySql(
            "SELECT e.*, c.descripcion tipo_descripcion, c2.descripcion marca_descripcion
            FROM sds_bdc_equipo e 
            JOIN sds_com_configuracion c ON e.tipo=c.idconfiguracion
            JOIN sds_com_configuracion c2 ON e.marca=c2.idconfiguracion"
            )->all();

        $responsables = Mds_org_contacto::findBySql(
            "SELECT c.*, CONCAT(p.apellido, ', ', p.nombre) nombre
            FROM  mds_org_contacto c
            JOIN sds_com_persona p ON c.idpersona = p.idpersona"
        )->all();

        if($request->isAjax){
            /*Process for ajax request*/
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Cargar Datos de Equipo",
                    'content'=>'<span class="text-success">El equipo ha sido cargado con exito!</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-danger pull-left','data-dismiss'=>"modal"]).
                            Html::a('Crear',['create', 'idvisita'=>$idvisita],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }
            return [
                'title'=> "Cargar Datos de Equipo",
                'content'=>$this->renderAjax('create', [
                    'model' => $model,
                    'equipos' => $equipos,
                    'responsables' => $responsables,
                ]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-danger pull-left','data-dismiss'=>"modal"]).
                            Html::button('Guardar',['class'=>'btn btn-success','type'=>"submit"])
            ];
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idvisitaequipo]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        $equipos = Sds_bdc_equipo::findBySql(
            "SELECT e.*, c.descripcion tipo_descripcion, c2.descripcion marca_descripcion
            FROM sds_bdc_equipo e 
            JOIN sds_com_configuracion c ON e.tipo=c.idconfiguracion
            JOIN sds_com_configuracion c2 ON e.marca=c2.idconfiguracion"
            )->all();

        $responsables = Mds_org_contacto::findBySql(
            "SELECT c.*, CONCAT(p.apellido, ', ', p.nombre) nombre
            FROM  mds_org_contacto c
            JOIN sds_com_persona p ON c.idpersona = p.idpersona"
        )->all();
        
        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Actualizar Datos de Equipos",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'equipos' => $equipos,
                        'responsables' => $responsables,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-danger pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-success','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                $responsable = Mds_org_contacto::findBySql(
                    "SELECT c.*, CONCAT(p.apellido, ', ', p.nombre) nombre FROM mds_org_contacto c
                    JOIN sds_com_persona p ON c.idpersona=p.idpersona 
                    WHERE idcontacto=$model->idresponsable")->one();
                
                $model->responsable= $responsable->nombre;
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Visitas de Equipo ".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-danger pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                return [
                    'title'=> "Actualizar Datos de Equipos",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'equipos' => $equipos,
                        'responsables' => $responsables,
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
                return $this->redirect(['view', 'id' => $model->idvisitaequipo]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'equipos' => $equipos,
                    'responsables' => $responsables,
                ]);
            }
        }
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

    protected function findModel($id)
    {
        if (($model = Sds_bdc_visita_equipo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La pagina requerida no existe');
        }
    }
}
