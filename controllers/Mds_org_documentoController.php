<?php
namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_org_contacto;
use Yii;
use app\models\Mds_org_documento;
use app\models\Mds_org_documentoSearch;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_persona;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\filters\AccessControl;

/* Mds_org_documentoController implements the CRUD actions for Mds_org_documento model. */
class Mds_org_documentoController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete','bulk-delete', 'view', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ORG_DOCUMENTO
                        ],
                    ],

                ],
            ],
        ];
    }

    /**
     * Lists all Mds_org_documento models.
     * @return mixed
     */
    public function actionIndex($medicina=0){
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $permiso_entrega = Mds_seg_permiso::findBySql("SELECT * FROM mds_seg_permiso WHERE 
        idrol IN (SELECT idrol FROM mds_seg_usuario_rol WHERE idusuario=$idusuario)
        AND iditem=".($medicina==1 ? Mds_seg_item::MODULO_ORG_DOC_MEDICINA : Mds_seg_item::MODULO_ORG_DOCUMENTO))->one();
        $permiso_entrega = $permiso_entrega != null ? 1 : 0;
        if ($permiso_entrega == 0) {
            Yii::$app->session->setFlash('error_modulo', "Usted no posee permisos para ingresar al módulo. <br>Comuníquese con un administrador.");
            return Yii::$app->getResponse()->redirect([
                'site',
            ]);
        }


        $searchModel = new Mds_org_documentoSearch();
        $searchModel->medicina=$medicina;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_documento', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }


    /**
     * Displays a single Mds_org_documento model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $medicina)
    {
        $model = $this->findModel($id);
        $model->medicina = $medicina;
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_documento', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Documento Numero: " . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Mds_org_documento model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($medicina=0){
        $request = Yii::$app->request;
        $model = new Mds_org_documento();
        $model->medicina=$medicina;
        $model->fecha = date('d-m-Y');
        if($model->medicina==0){
            $model->estado = Mds_org_documento::DOC_PROCESADO;
        }
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $user = Yii::$app->user->identity;
        $model->idusuario = $user->idusuario;

        if($request->isAjax) {
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Guardar nuevo documento",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),

                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
                ];
            } else if ($model->load($request->post())) {
                //cuando hago algo raro como formatear una fecha, tengo que modificar aca:
                //Crear la variable guardado en true, e iniciar una transaccion,
                //Despues agrego el if mas abajo y el save me lo llevo para alla
                // en el if le mando commmit a la transaccion
                //hago un return agregando title content y footer para que cierre bien el modal, el resto se comenta no sirve pa nada
                
                /*
                $transaction = Yii::$app->db->beginTransaction();
                $fecha = ArmarDateParaMySql($model->fecha);
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->fecha = $fecha;
                //---------------------------------------------------------------------------------------------------------------
                // Upload archivo adjunto
                $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nombre = $model->tipo . $model->nombre . $model->fecha . '.' . $extension;                    
                    $contacto  = Mds_org_contacto::findOne($model->idcontacto);
                    $persona = Sds_com_persona::findOne($contacto->idpersona);
                    //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                    $ruta = 'uploads/contactos/' . $model->idcontacto . '_' . $persona->apellido . '_' . $persona->nombre;
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->path = $ruta . '/' . $nombre;
                    $tmpfile->saveAs($model->path);
                }
                //---------------------------------------------------------------------------------------------------------------
                if ($model->save()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_documento', $model->iddocumento, $model->getAttributes());
                */

                $save= $this->saveModelandFile($model);
                if($save){
                    return [
                        'title' => "Nuevo Documento",
                        'content' => '<span class="text-success">El documento se guardo de manera correcta</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }else{
                    return [
                        'title' => "El documento no se pudo guardar de manera correcta",
                        'content' => '<span class="text-danger">Por favor intente nuevamente</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            }
            return [
                'title' => "Guardar nuevo documento",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])

            ];
        }else{
            /*  Process for non-ajax request */
            if($model->load($request->post())){
                $save= $this->saveModelandFile($model);
                $ismedicina=$model->medicina;
                if($save){
                    Yii::$app->session->setFlash('save_documento', "¡El documento se guardó de manera exitosa!");
                    $model = new Mds_org_documento();
                }else{
                    Yii::$app->session->setFlash('fail_save_documento', "El documento no pudo ser guardada.");
                }
                return $this->redirect(array('create', 'medicina'=>$ismedicina,
                        'model' => $model
            ));
            }else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }



    /*
     * Updates an existing Mds_org_documento model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id){
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $tipo = Sds_com_configuracion::findOne($model->tipo);
        $tipo->idconfiguraciontipo == Sds_com_configuracion_tipo::DOC_MEDICINA_LABORAL ? $model->medicina=1 : $model->medicina=0;
        if ($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post())) {
                $guardado = true;
                $transaction = Yii::$app->db->beginTransaction();
                $fecha = ArmarDateParaMySql($model->fecha);
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->fecha = $fecha;
                //----------------------------------------------------------------------------------------------------------
                // Upload archivo adjunto
                $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto');
                $contacto  = Mds_org_contacto::findOne($model->idcontacto);
                $persona = Sds_com_persona::findOne($contacto->idpersona);
                $ruta = 'uploads/contactos/' . $model->idcontacto . '_' . $persona->apellido . '_' . $persona->nombre;
                $path = Yii::$app->basePath . '/web/' . $model->path;

                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nombre = $model->tipo . $model->nombre . $model->fecha . '.' . $extension;
                    // elimino el actual si existe
                    if ($model->path) {
                        if ($model->path == $ruta . '/' .$nombre) {
                            unlink($path);
                        }
                    }

                    // creo el nuevo
                    $model->path = $ruta . '/' . $nombre;
                    $tmpfile->saveAs($model->path);
                } else {
                    // Valida si quitó el adjunto y en caso de que haya tenido uno, lo borra
                    if ($model->borrar_adjunto && $model->path) {
                        unlink($path);
                        $model->path = null;
                    }
                }
                //----------------------------------------------------------------------------------------------------------

                if ($guardado && $model->save(false)) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_org_documento', $model->iddocumento, $model->getAttributes());
                    return
                        [
                            'title' => "Documento Numero " . $id,
                            'content' => $this->renderAjax('view', [
                                'model' => $model,
                            ]),
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                        ];
                }
            } else {
                return [
                    'title' => "Editar Documento Numero " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
                ];
            }
        }
    }

    /**
     * Delete an existing Mds_org_documento model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_org_documento', $id, $model->getAttributes());
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
     * Delete multiple existing Mds_org_documento model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
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
     * Finds the Mds_org_documento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_org_documento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_org_documento::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function saveModelandFile($model){
        $transaction = Yii::$app->db->beginTransaction();
        $fecha = ArmarDateParaMySql($model->fecha);
        $fecha = date_create($fecha);
        $fecha = date_format($fecha, 'Y-m-d');
        $model->fecha = $fecha;
        //---------------------------------------------------------------------------------------------------------------
        // Upload archivo adjunto
        $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto');
        if (isset($tmpfile)) {
            $extension = $tmpfile->extension;
            $nombre = $model->tipo . $model->nombre . $model->fecha . '.' . $extension;                    
            $contacto  = Mds_org_contacto::findOne($model->idcontacto);
            $persona = Sds_com_persona::findOne($contacto->idpersona);
            //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
            $ruta = 'uploads/contactos/' . $model->idcontacto . '_' . $persona->apellido . '_' . $persona->nombre;
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $model->path = $ruta . '/' . $nombre;
            $tmpfile->saveAs($model->path);
        }
        //---------------------------------------------------------------------------------------------------------------
        if ($model->save()) {
            $transaction->commit();
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_documento', $model->iddocumento, $model->getAttributes());
            return true;
        }else{
            return false;
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
