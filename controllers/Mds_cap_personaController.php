<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_cap_persona;
use app\models\Sds_com_persona;
use app\models\Mds_cap_personaSearch;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Mds_cap_personaController implements the CRUD actions for Mds_cap_persona model.
 */
class Mds_cap_personaController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout','create_ext'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout','create_ext'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_CAP_PERSONA,
                        ],
                    ],
                ],
            ],
        ];
    }
    /**
     * Lists all Mds_cap_persona models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_cap_personaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cap_persona', null, "");
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_cap_persona model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cap_persona', $id, "");
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Personas Inscriptas",
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Mds_cap_persona model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_cap_persona();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nueva Persona",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        //Html::a('Guardar y Nueva Inscripcion',['create'],['role' => 'modal-remote', 'title' => 'Nueva intervencion', 'class' => 'btn btn-primary','type'=>"submit"]).
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {
                /* ------------------------------------------------------------------------------------------------ */
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;
                $model_com_persona = new Sds_com_persona;
                $model_cap_persona = null;
                if ($model->idpersona > 0) {
                    $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                    $model_cap_persona = Mds_cap_persona::findOne($model->idpersona);
                }
                $model_com_persona->documento_tipo = '83';
                $model_com_persona->documento = $model->dni;
                $model_com_persona->nacionalidad = $model->nacionalidad;
                $model_com_persona->genero = $model->sexo;
                $fecha_nac = ArmarDateParaMySql($model->fecha_nacimiento);
                $fecha_nac = date_create($fecha_nac);
                $fecha_nac = date_format($fecha_nac, 'Y-m-d');
                $model_com_persona->fecha_nacimiento = $fecha_nac;
                $model_com_persona->nombre = $model->nombre;
                $model_com_persona->apellido = $model->apellido;
           
                if (!$model_com_persona->save(false)) {
                    $guardado = false;
                    $transaction->rollBack();
                    $model->addError("dni", "No se pudo guardar la persona con los datos ingresados.");
                } else {                    
                    if ($model_cap_persona != null) {
                        $model_cap_persona->telefono = $model->telefono;
                        $model_cap_persona->mail = $model->mail;
                        $model_cap_persona->ultimo_año = $model->ultimo_año;
                        $model_cap_persona->localidad = $model->localidad;
                        $model = $model_cap_persona;                                                
                    }   
                    $model->idpersona = $model_com_persona->idpersona;                 
                    if ($guardado && $model->save()) {
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_cap_persona', $model->idpersonacap, $model->getAttributes());
                        return [
                            'title' => "Inscriptos",
                            'content' => '<span class="text-success">Alumno creado exitosamente</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                    else {
                        $transaction->rollBack();
                        $model->addError("dni", "No se pudo guardar el inscripto con los datos ingresados.");
                    }
                }
            }
            return
                [
                    'title' => "Inscriptos",
                    'content' => $this->renderAjax('create', ['model' => $model,]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
        } else {
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

    //para crear una persona desde el form de inscripciones
    public function actionCreate_ext()
    {
        $request = Yii::$app->request;
        $model = new Mds_cap_persona();
        $model_com_persona = new Sds_com_persona;
        $guardado = true;

        if ($model->load($request->post())) {
            $model_com_persona->documento_tipo = '83';

            if ($model->idpersona > 0) {
                $model_com_persona = Sds_com_persona::findOne($model->idpersona);
            }
            $model_com_persona->documento = $model->dni;
            $model_com_persona->nacionalidad = $model->nacionalidad;
            $model_com_persona->genero = $model->sexo;
            $fecha_nac = ArmarDateParaMySql($model->fecha_nacimiento);
            $fecha_nac = date_create($fecha_nac);
            $fecha_nac = date_format($fecha_nac, 'Y-m-d');
            $model_com_persona->fecha_nacimiento = $fecha_nac;
            $model_com_persona->nombre = $model->nombre;
            $model_com_persona->apellido = $model->apellido;
            $model_com_persona->conviviente = 0;
            if (!$model_com_persona->save()) {
                $guardado = false;
            } else {
                $model->idpersona = $model_com_persona->idpersona;
                if ($guardado && $model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_cap_persona', $model->idpersonacap, $model->getAttributes());
                    return $model->idpersonacap;
                } else {
                    return $this->renderAjax('//mds_cap_persona/create', [
                        'model' => $model,
                        'botones' => true,
                    ]);
                }
            }
        } else {
            return $this->renderAjax('//mds_cap_persona/create', [
                'model' => $model,
                'botones' => true,
            ]);
        }
    }


    /**
     * Updates an existing Mds_cap_persona model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model_com_persona = Sds_com_persona::findOne($model->idpersona);
        $model->dni = $model_com_persona->documento;
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Editar Inscripto",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $model_com_persona->documento = $model->dni;
                $model_com_persona->nacionalidad = $model->nacionalidad;
                $model_com_persona->genero = $model->sexo;
                $fecha_nac = ArmarDateParaMySql($model->fecha_nacimiento);
                $fecha_nac = date_create($fecha_nac);
                $fecha_nac = date_format($fecha_nac, 'Y-m-d');
                $model_com_persona->fecha_nacimiento = $fecha_nac;
                $model_com_persona->nombre = $model->nombre;
                $model_com_persona->apellido = $model->apellido;
                $model_com_persona->conviviente = 0;

                if (!$model_com_persona->save()) {
                    $guardado = false;
                    $transaction->rollBack();
                } else {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model_com_persona->idpersona, $model_com_persona->getAttributes());
                    
                    if($guardado && $model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_cap_persona', $id, $model->getAttributes());
                        $transaction->commit();
                        
                        return [
                            'title' => "Incripción guardada",
                            'content' => $this->renderAjax('view', [
                                'model' => $model,
                            ]),
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }else {
                        $transaction->rollBack();
                        $model->addError("dni", "No se pudo guardar la inscripción con los datos ingresados.");
                    }
                }

            } else {
                return [
                    'title' => "Editar Inscripto",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
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
     * Delete an existing Mds_cap_persona model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_cap_persona', $id, $model->getAttributes());
        }
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

     /**
     * Finds the Mds_cap_persona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_cap_persona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_cap_persona::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCmb_contacto()
    {
        $contactos =   Mds_cap_persona::find()
            ->select('mds_cap_persona.idpersonacap, sds_com_persona.nombre as nombre, sds_com_persona.apellido as apellido,sds_com_persona.documento as dni')
            ->innerJoin('sds_com_persona', 'sds_com_persona.idpersona = mds_cap_persona.idpersona')
            ->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])
            ->all();

        $cmbContactos = "";
        if (sizeof($contactos) > 0) {
            foreach ($contactos as $contacto) {
                $cmbContactos = $cmbContactos .
                    "<option value='" . $contacto->idpersonacap . "'>" . $contacto->nombre . " " . $contacto->apellido . " - " . $contacto->dni .  "</option>";
            }
        } else {
            $cmbContactos = "<option value=null></option>";
        }
        return $cmbContactos;
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
