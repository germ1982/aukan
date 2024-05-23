<?php

namespace app\controllers;

use Yii;
use app\components\AccessRule;
use app\models\Sds_com_persona;
use app\models\Mds_cap_docente;
use app\models\Mds_cap_docenteSearch;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;

/**
 * Mds_cap_docenteController implements the CRUD actions for Mds_cap_docente model.
 */
class Mds_cap_docenteController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout','migrarfirma'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout','migrarfirma'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_CAP_DOCENTE,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_cap_docente models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_cap_docenteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cap_docente', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_cap_docente model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $request = Yii::$app->request;

        $model = $this->findModel($id);
        $model_com_persona = Sds_com_persona::findOne($model->idpersona);
        $model->dni = $model_com_persona->documento;
        $model->nombre = $model_com_persona->nombre;
        $model->apellido = $model_com_persona->apellido;
        $model->fecha_nacimiento = $model_com_persona->fecha_nacimiento;
        $model->nacionalidad = $model_com_persona->nacionalidad;
        $model->sexo = $model_com_persona->genero;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cap_docente', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Docentes registrados" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
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


    public function actionMigrarfirma() 
    //Pasamos las imagenes de firmas docentes que estan en base 64 a un archivo jpg
    {
        $request = Yii::$app->request;

        $cap_docente=Mds_cap_docente::find()->all();            
           $i=0;
           foreach ($cap_docente as $una_instancia) {
            if ($una_instancia->firma !=null)
            {  
                $imagen=$una_instancia->firma;
                $cad= date("Y-m-d H:i:s").$una_instancia->idpersona;        
                $nombrefile=substr(md5($cad),0,12);  
                $f = fopen("uploads/instancias/firmas/".$nombrefile.".jpg", "w") or die("Unable to open file!");        
                fwrite($f, base64_decode(explode(",", $imagen, 2)[1]));   
                $model_cap_docente=new Mds_cap_docente;
                $model_cap_docente = Mds_cap_docente::findOne($una_instancia->idpersona);
                $model_cap_docente->firma=$nombrefile.".jpg";
                   
                
                if ($model_cap_docente->update(false)) {

                } else {
                  echo "MODEL NOT SAVED";
                  print_r($model_cap_docente->getAttributes());
                  print_r($model_cap_docente->getErrors());
                  
                }
                //var_dump($model_cap_docente->errors);                    
                $i++;
            } 
            
           }
       
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
            'title' => "Migrando firmas",
            'size' => Modal::SIZE_SMALL,
            'content' => "terminamos de migrar las firmas. Total: ".$i,
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        
                                      
    }    

    /**
     * Creates a new Mds_cap_docente model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_cap_docente();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nuevo Docente",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {
                /* ------------------------------------------------------------------------------------------------ */
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;
                $alta_persona = true;
                $model_com_persona = new Sds_com_persona;
                if ($model->idpersona > 0) {
                    $model_com_persona = Sds_com_persona::findOne($model->idpersona);
                    $alta_persona = false;
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
                $model_com_persona->conviviente = 0;
                if (!$model_com_persona->save()) {
                    $guardado = false;
                    $transaction->rollBack();
                    $model->addError("dni", "No se pudo guardar la persona con los datos ingresados.");
                } else {
                    Mds_sys_log::guardarLog($alta_persona ? Mds_sys_log::ACCION_NUEVO : Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model_com_persona->idpersona, $model_com_persona->getAttributes());
                    $tmpfile = UploadedFile::getInstance($model, 'temp_imagen');
                    $model->firma =   $tmpfile = UploadedFile::getInstance($model, 'temp_imagen');
                    if (isset($tmpfile)) {                       
                        $extension= $tmpfile->extension;
                        $nuevo_nombre=$model->random_filename(30, '/uploads/instancias/firmas',$extension);
                        $model->firma = $nuevo_nombre ;     
                        $tmpfile->saveAs('uploads/instancias/firmas/' . $nuevo_nombre );   
                    }

                    $model->idpersona = $model_com_persona->idpersona;

                    if ($guardado && $model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_cap_docente', $model->idpersona, $model_com_persona->getAttributes());
                        $transaction->commit();
                        return [
                            'title' => "Docentes",
                            'content' => '<span class="text-success">Docente creado exitosamente</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    } else {
                        $transaction->rollBack();
                        $model->addError("dni", "No se pudo guardar el docente con los datos ingresados.");
                    }
                }
                //}                
                return [
                    'title' => "Docentes",
                    'content' => $this->renderAjax('create', ['model' => $model,]),
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
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_cap_docente model.
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
        $model->borrar_firma = false;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Modificar Docente",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                /* ------------------------------------------------------------------------------------------------ */
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;
                $model_com_persona = new Sds_com_persona;
                $alta_persona = true;
                if ($model->idpersona > 0) {
                    $alta_persona = false;
                    $model_com_persona = Sds_com_persona::findOne($model->idpersona);
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
                $model_com_persona->conviviente = 0;
                if (!$model_com_persona->save()) {
                    $guardado = false;
                    $transaction->rollBack();
                    $model->addError("dni", "No se pudo guardar la persona con los datos ingresados.");                    
                } else {
                    Mds_sys_log::guardarLog($alta_persona ? Mds_sys_log::ACCION_NUEVO : Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model_com_persona->idpersona, $model_com_persona->getAttributes());
                    $tmpfile = UploadedFile::getInstance($model, 'temp_imagen');
                    //$model->firma =   $tmpfile = UploadedFile::getInstance($model, 'temp_imagen');
                    if (isset($tmpfile)) {

                        $extension= $tmpfile->extension;
                        $nuevo_nombre=$model->random_filename(30, '/uploads/instancias/firmas',$extension);
                        $model->firma = $nuevo_nombre ;     
                        $tmpfile->saveAs('uploads/instancias/firmas/' . $nuevo_nombre );   
                    }
                    else 
                    {
                        if($model->borrar_firma ) 
                        {                    
                             $model->firma = null;                            
                         }
                    };

                    $model->idpersona = $model_com_persona->idpersona;

                    if ($guardado && $model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_cap_docente', $model->idpersona, $model_com_persona->getAttributes());
                        $transaction->commit();
                        return [
                            'title' => "Docentes",
                            'content' => '<span class="text-success">Docente editado exitosamente</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    } else {
                        $transaction->rollBack();
                        $model->addError("dni", "No se pudo guardar el docente con los datos ingresados.");
                    }
                }
                //}                
                return [
                    'title' => "Docentes",
                    'content' => $this->renderAjax('create', ['model' => $model,]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->iddocente]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_cap_docente model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_cap_docente', $id, $model->getAttributes());
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
     * Finds the Mds_cap_docente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_cap_docente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_cap_docente::findOne($id)) !== null) {
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
