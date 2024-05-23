<?php

namespace app\controllers;

use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use app\models\Mds_seg_usuario_capa_item;
use app\models\Sds_gis_capa_item;
use app\models\Sds_reg_movimiento;
use Yii;
use app\models\Sds_reg_registro;
use app\models\Sds_reg_registroSearch;
use app\models\Sds_reg_registro_autosolicitud;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\mpdf\Pdf;
use yii\web\UploadedFile;
use app\models\Mds_sys_log;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Sds_com_persona;
use app\components\AccessRule;
/**
 * Sds_reg_registroController implements the CRUD actions for Sds_reg_registro model.
 */
class Sds_reg_registroController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_REG_REGISTROS,
                            Mds_seg_item::MODULO_REG_MANTENIMIENTO,
                            Mds_seg_item::MODULO_RUM_REG,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_reg_registro models.
     * @return mixed
     */
    public function actionIndex($entidad){   
        //Verifico permisos de usuario:
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        switch ($entidad){
            case Sds_reg_registro::ENT_INFORMATICA:
                $itemseguridad=Mds_seg_item::MODULO_REG_REGISTROS;
            break;
            case Sds_reg_registro::ENT_MANTENIMIENTO:
                $itemseguridad=Mds_seg_item::MODULO_REG_MANTENIMIENTO;
            break;
            case Sds_reg_registro::ENT_RUMBO:
                $itemseguridad=Mds_seg_item::MODULO_RUM_REG;
            break;
        }
        //$itemseguridad = $entidad==Sds_reg_registro::ENT_INFORMATICA ? Mds_seg_item::MODULO_REG_REGISTROS : Mds_seg_item::MODULO_REG_MANTENIMIENTO;
        if(isset($itemseguridad)){
            $permiso = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
            idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
            and ((iditem=" .$itemseguridad."))")->one();
        }else{
            $permiso = null;
        }
        $permiso=$permiso != null ? 1 : 0;
        //Si no tiene permisos retorno a Site mostrando un flash.
        if ($permiso == 0) {
            Yii::$app->session->setFlash('error_modulo', "Usted no posee permisos para ingresar al módulo. <br>Comuníquese con un administrador.");
            return Yii::$app->getResponse()->redirect([
                'site',
            ]);
        }

        $searchModel = new Sds_reg_registroSearch();
        $searchModel->entidad=$entidad;
        $searchModel->registro_abierto = 1;
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_reg_registro', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_reg_registro model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $entidad)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_reg_registro', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Registro numero " . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id, 'entidad' => $entidad], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Sds_reg_registro model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($entidad){
        $request = Yii::$app->request;
        $model = new Sds_reg_registro();
        $model->fecha_hora = date('d-m-Y H:i');
        $model->registro_abierto = 1;
        switch($entidad){
            case Sds_reg_registro::ENT_INFORMATICA;
                $model->idtipo=7;
            break;
            case Sds_reg_registro::ENT_MANTENIMIENTO;
                $model->idtipo=10;
            break;
            case Sds_reg_registro::ENT_RUMBO;
                $model->idtipo=11;
            break;
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
        $model->usuario_derivacion = $idusuario;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            switch($entidad){
                case Sds_reg_registro::ENT_INFORMATICA:
                    $titulo='Nuevo Registro Tecnico';
                    break;
                case Sds_reg_registro::ENT_MANTENIMIENTO:
                    $titulo='Nuevo Registro Mantenimiento';
                    break;
                case Sds_reg_registro::ENT_RUMBO:
                    $titulo='Nuevo Registro Rumbo';
                    break;
            }
            if ($request->isGet) {
                return [
                    'title' => $titulo,
                    'content' => $this->renderAjax('create', ['model' => $model, 'entidad'=>$entidad]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;
                $fecha_registro = ArmarDateParaMySql($model->fecha_hora, $model->hora);
                $fecha_registro = date_create($fecha_registro);
                $fecha_registro = date_format($fecha_registro, 'Y-m-d H:i');
                $model->fecha_hora = $fecha_registro;
                if ($model->fecha_ingreso != null) {
                    $fecha_ingreso = ArmarDateParaMySql($model->fecha_ingreso, '00:00');
                    $fecha_ingreso = date_create($fecha_ingreso);
                    $fecha_ingreso = date_format($fecha_ingreso, 'Y-m-d H:i');
                    $model->fecha_ingreso = $fecha_ingreso;
                }
                if ($model->registro_abierto == 0) {
                    if($model->fecha_solucion==''){
                        $model->addError('fecha_solucion','Si el registro ya no esta pendiente debe agregar una fecha de solucion');
                        $guardado = false;
                    }
                    else{
                        $fecha_solucion = ArmarDateParaMySql($model->fecha_solucion, '00:00');
                        $fecha_solucion = date_create($fecha_solucion);
                        $fecha_solucion = date_format($fecha_solucion, 'Y-m-d H:i');
                        $model->fecha_solucion = $fecha_solucion;
                    }
                    if($model->tecnicos_solucion==''){
                        $model->addError('tecnicos_solucion','Si el registro ya no esta pendiente debe agregar un tecnico en la solucion');
                        $guardado = false;
                    }                    
                    if($model->solucion==''){
                        $model->addError('solucion','Si el registro ya no esta pendiente debe agregar una solucion');
                        $guardado = false;
                    }
                }
                $model_dispositivo = Mds_org_dispositivo::findOne($model->iddispositivo);
                $model->idorganismo = $model_dispositivo->idorganismo;
                if ($guardado && $model->save()) {
                    $transaction->commit();
                    $aux = "derivador1 $model->usuario_derivacion derivador2 $model->id_usuario_derivador";
                    if ($model->registro_abierto == 0){
                        foreach($model->tecnicos_solucion as $t){
                            $model_movimiento = new Sds_reg_movimiento();  
                            $model_movimiento->idregistro = $model->idregistro;
                            $model_movimiento->idusuario = $model->usuario_derivacion;
                            $model_movimiento->fecha = $model->fecha_solucion;
                            $model_movimiento->descripcion = $model->solucion;
                            $model_movimiento->tipo = 2;
                            $model_movimiento->idtecnico = $t;
                            $model_movimiento->save();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_reg_movimiento', $model_movimiento->idmovimiento, $model_movimiento->getAttributes());
                        }
                    }

                    if($entidad == Sds_reg_registro::ENT_INFORMATICA){
                        $lugar = '';
                        $fechaForm = date_create($model->fecha_hora);
                        $fechaHoraSolicitud = date_format($fechaForm, 'd-m-Y') . ' ' . $model->hora;
                        $model_usuario_solicitante = Mds_seg_usuario::find()->where(['idcontacto'=>$model->usuario_solicitante])->one();
                        if ($model_dispositivo->idcapaitem) {
                            $edificio = Sds_gis_capa_item::findOne($model_dispositivo->idcapaitem);
                            $lugar = $edificio->descripcion;
                        }
                        $organismo = $model_dispositivo->organismo ? $model_dispositivo->organismo->descripcion : '';
                        $apiTokenTelegram = Sds_reg_registro_autosolicitud::API_TELEGRAM; //bot
                        $text = $fechaHoraSolicitud
                                ."\n".
                                $model_usuario_solicitante->apellido .' '.$model_usuario_solicitante->nombre.' ('.$model_usuario_solicitante->user.')'
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
                        //$msj_enviado = file_get_contents("https://api.telegram.org/bot$apiTokenTelegram/sendMessage?" . http_build_query($data) );
                    }
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_reg_registro', $model->idregistro, $model->getAttributes());
                    return [
                        //'forceReload'=>'#crud-datatable-pjax',
                        'title' => $titulo,
                        'content' => "<span class='text-success'>Registro Creado Correctamente</span>",
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        //Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];
                }
            }
            return [
                'title' => $titulo,
                'content' => $this->renderAjax('create', ['model' => $model, 'entidad' => $entidad]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else{
            if($model->load($request->post())) {
                return $this->redirect(['view', 'id' => $model->idregistro]);
            }else{
                return $this->render('create', ['model' => $model, 'entidad' => $entidad]);
            }
        }
    }

    /**
     * Updates an existing Sds_reg_registro model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $entidad)
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
                    'title' => "Editar Registro Numero: " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'entidad' => $entidad
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $fecha_registro = ArmarDateParaMySql($model->fecha_hora, $model->hora);
                $fecha_registro = date_create($fecha_registro);
                $fecha_registro = date_format($fecha_registro, 'Y-m-d H:i');
                $model->fecha_hora = $fecha_registro;

                if ($model->fecha_ingreso != null) {
                    $fecha_ingreso = ArmarDateParaMySql($model->fecha_ingreso, '00:00');
                    $fecha_ingreso = date_create($fecha_ingreso);
                    $fecha_ingreso = date_format($fecha_ingreso, 'Y-m-d H:i');
                    $model->fecha_ingreso = $fecha_ingreso;
                }

                if ($model->registro_abierto == 0) {

                    if($model->fecha_solucion=='')
                        {
                            $model->addError('fecha_solucion','Si el registro ya no esta pendiente debe agregar una fecha de solucion');
                            $guardado = false;
                        }
                    else
                        {
                            $fecha_solucion = ArmarDateParaMySql($model->fecha_solucion, '00:00');
                            $fecha_solucion = date_create($fecha_solucion);
                            $fecha_solucion = date_format($fecha_solucion, 'Y-m-d H:i');
                            $model->fecha_solucion = $fecha_solucion;
                        }

                    

                    if($model->tecnicos_solucion=='')
                        {
                            $model->addError('tecnicos_solucion','Si el registro ya no esta pendiente debe agregar un tecnico en la solucion');
                            $guardado = false;
                        }
                    
                    if($model->solucion=='')
                        {
                            $model->addError('solucion','Si el registro ya no esta pendiente debe agregar una solucion');
                            $guardado = false;
                        }
                    
                    
                }
                /* if ($model->fecha_solucion != null) {
                    $movimientos = Sds_reg_movimiento::findBySql('Select * from sds_reg_movimiento where idregistro = ' . $model->idregistro)->all();
                    if (empty($movimientos)) {
                        $model->addError("fecha_solucion", "No se puede cerrar el registro si no tiene movimientos");
                        $guardado = false;
                    }
                    $fecha_solucion = ArmarDateParaMySql($model->fecha_solucion, '00:00');
                    $fecha_solucion = date_create($fecha_solucion);
                    $fecha_solucion = date_format($fecha_solucion, 'Y-m-d H:i');
                    $model->fecha_solucion = $fecha_solucion;
                } else {
                    if ($model->registro_abierto == 0) {
                        $model->addError("fecha_solucion", "Se debe setear la fecha de solución del registro.");
                        $guardado = false;
                    }
                } */

                //---------------------------------------------------------------------------------------------------------------
                // Upload archivo adjunto
                $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto_recepcion');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nombre = "adjunto_recepcion_" . $model->idregistro . "." . $extension;
                    $model->adjunto_recepcion = $nombre;
                    $ruta = 'uploads/registros_tecnicos';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $tmpfile->saveAs($ruta . '/' . $nombre);
                }
                //---------------------------------------------------------------------------------------------------------------

                // Upload archivo adjunto
                $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto_entrega');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nombre = "adjunto_entrega_" . $model->idregistro . "." . $extension;
                    $model->adjunto_entrega = $nombre;
                    $ruta = 'uploads/registros_tecnicos';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $tmpfile->saveAs($ruta . '/' . $nombre);
                }
                //---------------------------------------------------------------------------------------------------------------

                $model_dispositivo = Mds_org_dispositivo::findOne($model->iddispositivo);
                $model->idorganismo = $model_dispositivo->idorganismo;

                if ($guardado && $model->save()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_reg_registro', $model->idregistro, $model->getAttributes());
                    if ($model->registro_abierto == 0) 
                    {
                        
                        foreach($model->tecnicos_solucion as $t)
                        {
                            $model_movimiento = new Sds_reg_movimiento();  
                            $model_movimiento->idregistro = $model->idregistro;
                            $model_movimiento->idusuario = $model->usuario_derivacion;
                            $model_movimiento->fecha = $model->fecha_solucion;
                            $model_movimiento->descripcion = $model->solucion;
                            $model_movimiento->tipo = 2;
                            $model_movimiento->idtecnico = $t;
                            $model_movimiento->save();
                            
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_reg_movimiento', $model_movimiento->idmovimiento, $model_movimiento->getAttributes());
                        }
                    }
                    return [
                        'title' => "Registro Tecnico Actualizado Numero: " . $id,
                        'content' => $this->renderAjax('view', ['model' => $model, 'entidad' => $entidad]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            }
            return [
                'title' => "Editar Registro Numero: " . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                    'entidad' => $entidad
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_reg_registro', $model->idregistro, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idregistro]);
            } else {
                //fijarse aca de como mantener los filtros
                $searchModel = new Sds_reg_registroSearch();
                $searchModel->registro_abierto = 1;
                $usuario = Yii::$app->user->identity;
                $idusuario = $usuario != null ? $usuario->idusuario : null;
                if (!isset($idusuario) || $idusuario == null) {
                    $model = new \app\models\LoginForm();
                    return Yii::$app->getResponse()->redirect([
                        'site/login',
                        'model' => $model,
                    ]);
                }
                $usuario_logueado = Mds_seg_usuario::findOne($usuario->idusuario);
                $contacto = Mds_org_contacto::findOne($usuario_logueado->idcontacto);
                $dispositivo = Mds_org_dispositivo::findOne($contacto->iddispositivo);
                $searchModel->idcapaitem = $dispositivo->idcapaitem;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_reg_registro model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $entidad)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_reg_registro', $id, $model->getAttributes());
        }
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index', 'entidad'=>$entidad]);
        }

    }

    /**
     * Delete multiple existing Sds_reg_registro model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    public function actionImprimir_registro($idregistro)
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_reg_registro/imprimir_registro', $idregistro, array());
        $content = $this->renderPartial('imprimir_registro', ['idregistro' => $idregistro]); // setup kartik\mpdf\Pdf component 
        //    print_r($content);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'Registro Tecnico',
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);

        return $pdf->render();
    }

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
     * Finds the Sds_reg_registro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_reg_registro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_reg_registro::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionGet_iddispositivo_contacto($id_contacto)
    {
        $contacto = Mds_org_contacto::findOne($id_contacto);
        $iddispositivo = $contacto->iddispositivo;
        echo $iddispositivo;
    }
    
    public function actionGrilla_movimientos($idregistro)
    {
        $user = Yii::$app->user->identity;
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $id_derivador = $user->idusuario;
        $dataProvider = new ActiveDataProvider([
            'query' => Sds_reg_movimiento::findBySql('Select * from sds_reg_movimiento where idregistro = ' . $idregistro),

        ]);

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => '',
            'id' => 'grilla_movimientos',
            'columns' => [

                //'idmovimiento',
                //'idregistro',
                [
                    'attribute' => 'fecha',
                    'value' => function ($model) {
                        $fc = date_create($model->fecha);
                        $fc = date_format($fc, 'd/m/Y');
                        return $fc;
                    },
                ],
                [
                    'attribute' => 'tipo',
                    'value' => function ($model) {
                        switch ($model->tipo) {
                            case 0: {
                                    $tipo = "Común";
                                    break;
                                }
                            case 1: {
                                    $tipo = "Ingreso Equipo";
                                    break;
                                }
                            case 2: {
                                    $tipo = "Solución";
                                    break;
                                }
                        }

                        return $tipo;
                    },
                ],
                'descripcion',
                [
                    'attribute' => 'idusuario',
                    'value' => function ($model) {
                        $idderivador = $model->idusuario;
                        $usuario = Mds_seg_usuario::findOne($idderivador);
                        $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
                        $persona = Sds_com_persona::findOne($contacto->idpersona);
                        return "$persona->nombre $persona->apellido";
                    },
                    'label' => 'Carga',
                ],
                [
                    'attribute' => 'idtecnico',
                    'value' => function ($model) {
                        $tecnico = $model->idtecnico;
                        $usuario = Mds_seg_usuario::findOne($tecnico);
                        $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
                        $persona = Sds_com_persona::findOne($contacto->idpersona);
                        return "$persona->nombre $persona->apellido";
                    },
                    'label' => 'Tecnico',
                ],
                [
                    'header' =>  Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                        'class' => 'btn btn-primary',
                        'title' => "Nuevo Movimiento",
                        'data-toggle' => 'tooltip',
                        'onclick' => "js:MostrarDivform_movimiento(0,$idregistro,$id_derivador,'',0,'',0);"
                    ]),
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{editar}',  // the default buttons + your custom button
                    'buttons' => [
                        'editar' => function ($url, $model) {
                            $id_movimiento = $model->idmovimiento;
                            $id_registro = $model->idregistro;
                            $usuario = Yii::$app->user->identity;
                            $idusuario = $usuario != null ? $usuario->idusuario : null;
                            if (!isset($idusuario) || $idusuario == null) {
                                $model = new \app\models\LoginForm();
                                return Yii::$app->getResponse()->redirect([
                                    'site/login',
                                    'model' => $model,
                                ]);
                            }
                            $id_derivador = $usuario->idusuario;
                            $fecha = $model->fecha;
                            $tipo = $model->tipo;
                            $descripcion = $model->descripcion;
                            $idtecnico = $model->idtecnico;

                            return Html::button('<i class="glyphicon glyphicon-pencil"></i>', [
                                'title' => "Editar Movimiento",
                                'data-toggle' => 'tooltip',
                                'class' => 'btn btn-link',
                                'onclick' => "js:MostrarDivform_movimiento($id_movimiento,$id_registro,$id_derivador,'$fecha',$tipo,'$descripcion',$idtecnico);",

                            ]);
                        }
                    ],
                ]

            ],
        ]);
    }
}

function ArmarDateParaMySql($Fecha, $Hora)
{
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $H = substr($Hora, 0, 2);
    $m = substr($Hora, 3, 2);
    $DT = "$anio-$mes-$dia $H:$m:00";
    return $DT;
}


