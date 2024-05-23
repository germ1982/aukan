<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use app\models\Sds_gis_capa_item;
use Yii;
use app\models\Sds_reg_registro_autosolicitud;
use app\models\Sds_reg_registro_autosolicitudSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use app\models\Sds_reg_registro;

/**
 * Sds_reg_registro_autosolicitudController implements the CRUD actions for Sds_reg_registro_autosolicitud model.
 */
class Sds_reg_registro_autosolicitudController extends Controller
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
        ];
    }

    /**
     * Lists all Sds_reg_registro_autosolicitud models.
     * @return mixed
     */
    public function actionIndex($entidad)
    {
        $searchModel = new Sds_reg_registro_autosolicitudSearch();
        $searchModel->entidad=$entidad;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_reg_registro_autosolicitud', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_reg_registro_autosolicitud model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_reg_registro_autosolicitud', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Registro numero: " . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) //.
                //Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Sds_reg_registro_autosolicitud model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($entidad)
    {
        $request = Yii::$app->request;
        $model = new Sds_reg_registro_autosolicitud();
        $model->fecha_hora = date('d-m-Y');

        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $usuario = Mds_seg_usuario::findOne($usuario->idusuario);
        $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
        $BtnGuardar = Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => 'submit']);
        if ($contacto->activo == 0) {
            $BtnGuardar = "";
        }
        if ($request->isAjax) {
            /*
                *   Process for ajax request
                */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {

                return [
                    'title' => "Nueva Solicitud",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        $BtnGuardar
                ];
            } else if ($model->load($request->post())) { {
                    //cuando hago algo raro como formatear una fecha, tengo que modificar aca:
                    //Crear la variable guardado en true, e iniciar una transaccion,
                    //Despues agrego el if mas abajo y el save me lo llevo para alla
                    // en el if le mando commmit a la transaccion
                    //hagoi un return agregando title content y footer para que cierre bien el modal, el resto se comenta no sirve pa nada
                    $guardado = true;
                    $transaction = Yii::$app->db->beginTransaction();

                    $fecha = ArmarDateTimeActualParaMySql();
                    $fecha = date_create($fecha);
                    $fecha = date_format($fecha, 'Y-m-d H:i:s');
                    $model->fecha_hora = $fecha;

                    //echo'<script type="text/javascript">alert("$model->desde: '.$model->fecha_hora.'");</script>';

                    $model_usuario = Mds_seg_usuario::findOne($usuario->idusuario);
                    $model->usuario_solicitante = $model_usuario->idcontacto;

                    $model_contacto = Mds_org_contacto::findOne($model_usuario->idcontacto);
                    $model->iddispositivo = $model_contacto->iddispositivo;

                    $model_dispositivo = Mds_org_dispositivo::findOne($model_contacto->iddispositivo);
                    $model->idorganismo = $model_dispositivo->idorganismo;

                    $model->usuario_derivacion = $usuario->idusuario;
                    $model->registro_abierto = 1;
                    switch($entidad){
                        case Sds_reg_registro::ENT_INFORMATICA:
                            $model->idtipo=7;
                            break;
                        case Sds_reg_registro::ENT_MANTENIMIENTO:
                            $model->idtipo=10;
                            break;
                        case Sds_reg_registro::ENT_RUMBO:
                            $model->idtipo=11;
                            break;
                    }

                    /*
                    Usado para mostrar la IP desde donde se realiza la solicitud
                    $aux =  getHostByName(getHostName());
                    $aux = "Registro cargado desde la ip $aux $model->problema";
                    $model->problema = $aux;
                    */
                    
                    if ($guardado && $model->save(false)) {
                        $transaction->commit();
                        if($entidad == Sds_reg_registro::ENT_INFORMATICA){
                            $lugar = '';
                            if ($model_dispositivo->idcapaitem) {
                                $edificio = Sds_gis_capa_item::findOne($model_dispositivo->idcapaitem);
                                $lugar = $edificio->descripcion;
                            }
                            $organismo = $model_dispositivo->organismo ? $model_dispositivo->organismo->descripcion : '';
                            $apiTokenTelegram = Sds_reg_registro_autosolicitud::API_TELEGRAM; //bot
                            $text = date("d-m-Y H:i")
                                    ."\n".
                                    $model_usuario->apellido.' '.$model_usuario->nombre.' ('.$model_usuario->user.')'
                                    ."\n".
                                    $organismo
                                    ."\n".
                                    $lugar
                                    ."\n".
                                    'Problema: '
                                    .$model->problema;
                            $data = [ 
                                'chat_id' => Sds_reg_registro_autosolicitud::CHAT_ID,
                                'text' => $text
                            ]; 

                            $curl_handle=curl_init();
                            curl_setopt($curl_handle, CURLOPT_URL,"https://api.telegram.org/bot$apiTokenTelegram/sendMessage?" . http_build_query($data));
                            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
                            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($curl_handle, CURLOPT_USERAGENT, 'SUR');
                            $query = curl_exec($curl_handle);
                            curl_close($curl_handle);
                          //  $msj_enviado = file_get_contents("https://api.telegram.org/bot$apiTokenTelegram/sendMessage?" . http_build_query($data) );
                        }
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_reg_registro_autosolicitud', $model->idregistro, $model->getAttributes());
                        return [
                            'title' => "Nueva Solicitud",
                            'content' => '<span class="text-success">Registrado exitosamente</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                }
            } else {
                return [
                    'title' => "Nueva Solicitud",
                    'content' => $this->renderAjax('create', [
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_reg_registro_autosolicitud', $model->idregistro, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idregistro]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Sds_reg_registro_autosolicitud model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Editar Registro numero: ".$id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_reg_registro_autosolicitud', $model->idregistro, $model->getAttributes());
                return [
                    'title' => "Edición de Registro",
                    'content' => '<span class="text-success">Editado exitosamente</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    //para que salga bien cuando guarda se comenta lo de abajo y se agrega lo de arriba
                    /*                             'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Nueva Instancia",
                    'content'=>'<span class="text-success">Create Mds_cap_instancia success</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote']) */

                ];
                /*                 return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Registro numero: ".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];   */
            } else {
                return [
                    'title' => "Editar Registro numero: " . $id,
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_reg_registro_autosolicitud', $model->idregistro, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idregistro]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }



    public function actionCerrar_registro($id, $entidad)
    {
        $registro = $this->findModel($id);
        if ($registro->updateAttributes(['registro_abierto' => 0])) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_reg_registro_autosolicitud/cerrar_registro', $id, $registro->getAttributes());
            return $this->redirect(['index', 'entidad'=>$entidad]);
        }
    }
    /**
     * Delete an existing Sds_reg_registro_autosolicitud model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_reg_registro_autosolicitud', $id, $model->getAttributes());
            return $this->redirect(['index']);
        }

/*
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
/*            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /*
            *   Process for non-ajax request
            */
/*            return $this->redirect(['index']);
        }
*/
    }

    /**
     * Delete multiple existing Sds_reg_registro_autosolicitud model.
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
     * Finds the Sds_reg_registro_autosolicitud model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_reg_registro_autosolicitud the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_reg_registro_autosolicitud::findOne($id)) !== null) {
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

function ArmarDateTimeActualParaMySql()
{
    $fecha  = date("Y-m-d H:i");

    return $fecha;
}
