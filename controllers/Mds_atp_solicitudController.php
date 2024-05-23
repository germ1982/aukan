<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_atp_solicitud;
use app\models\Mds_atp_solicitudSearch;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;
use app\models\Sds_com_calle;
use app\models\Mds_atp_historial;
use app\models\Sds_com_localidad;
use yii\helpers\ArrayHelper;

date_default_timezone_set('America/Argentina/Buenos_Aires');

/**
 * Mds_atp_solicitudController implements the CRUD actions for Mds_atp_solicitud model.
 */
class Mds_atp_solicitudController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'cambiar_estado','migrar_dni', 'get_localidades','reporte_solicitud'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'cambiar_estado','migrar_dni','get_localidades','reporte_solicitud'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ATP_SOLICITUD,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_atp_solicitud models.
     * @return mixed
     */
    public function actionIndex()
    {
        //$query = Mds_atp_solicitud::find()->select(['id', 'documento', 'nombre', 'apellido', 'fecha_nacimiento', 'telefono', 'email']);
        $searchModel = new Mds_atp_solicitudSearch();
        //$dataProvider = new ActiveDataProvider(['query' => $query,]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //Mds_atp_solicitudSearch;


        //$searchModel = new Mds_cor_intervencionSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_atp_solicitud', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Displays a single Mds_atp_solicitud model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_atp_solicitud', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Tarjeta ATPCen Solicitada",
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

    public function actionCambiar_estado($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            $model->estado_anterior = $model->estado;
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {

                return [
                    'title' => "Cambiar estado del registro de " . $model->nombre . ' ' . $model->apellido,
                    'content' => $this->renderAjax('crear_historial', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else 
            if ($model->load($request->post())) {

                $model_historial = new Mds_atp_historial();

                $model_historial->estado_nuevo = $model->estado;
                $model_historial->estado_anterior = $model->estado_anterior;
                $model_historial->descripcion = $model->desc_historial;
                $model_historial->id_atp_solicitud = $model->id;
                $model_historial->fecha_hora = date('Y-m-d h:i:s', time());
                $model_historial->save();


                if ($model->save()) {
                    // Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_atp_solicitud', $model->id, $model->getAttributes());
                    return [
                        'title' => "Cambio estado del registro de " . $model->nombre . ' ' . $model->apellido,
                        'forceReload' => '#crud-datatable-pjax',
                        'content' => '<span class="text-success">Se ha registrado exitosamente el cambio de estado del registro ATPCen de ' . $model->nombre . ' ' . $model->apellido . '</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])

                    ];
                } else {
                    var_dump($model->errors);
                    return [
                        'title' => "PEPECambio estado del registro de " . $model->nombre . ' ' . $model->apellido,
                        'content' => $this->renderAjax('crear_historial', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                }
            } else {
                return [
                    'title' => "Crear nueva Solitud de Tarjeta ATPCen 333",
                    'content' => $this->renderAjax('crear_historial', [
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
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Creates a new Mds_atp_solicitud model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_atp_solicitud();
        /** Trabaja solo las localidades de la Provincia de Neuquén */
        $localidades = Sds_com_localidad::find()->where(['idprovincia' => 58, 'activo' => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $localidades = ArrayHelper::map($localidades, 'idlocalidad', 'descripcion');

        if ($request->isAjax) {

            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear nueva Solitud de Tarjeta ATPCen",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'localidades' => $this->getLocalidades(),
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {
                $guardar = true;
                $tmpfile = UploadedFile::getInstance($model, 'archivo_foto_dni');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nombre =  $model->created_at . "_" . $model->documento . '_frente.' . $extension;
                    $ruta = 'uploads/atpcen/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->foto_dni = $ruta . $nombre;
                    $tmpfile->saveAs($model->foto_dni);
                } else {
                    $model->addError('archivo_foto_dni', 'La foto del dni es obligatoria');
                    $guardar = false;
                }
                $tmpfile2 = UploadedFile::getInstance($model, 'archivo_foto_certificado');
                if (isset($tmpfile2)) {
                    $extension = $tmpfile2->extension;
                    $nombre =  $model->created_at . "_" . $model->documento . '_certificado.' . $extension;
                    $ruta = 'uploads/atpcen/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->foto_certificado = $ruta . $nombre;
                    $tmpfile2->saveAs($model->foto_certificado);
                } else {
                    $model->addError('archivo_foto_certificado', 'La foto del certificado es obligatoria');
                    $guardar = false;
                }
                $ahora = date("Y-m-d H:i:s");
                $valormili = strtotime($ahora) * 1000;
                $model->created_at = $valormili;
                $model->updated_at = $valormili;
                $tmpfile3 = UploadedFile::getInstance($model, 'archivo_tutor_foto_dni');
                if (isset($tmpfile3)) {
                    $extension = $tmpfile3->extension;
                    $nombre =  $model->created_at . "_" . $model->tutor_documento . '_frente.' . $extension;
                    $ruta = 'uploads/atpcen/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->tutor_foto_dni = $ruta . $nombre;
                    $tmpfile3->saveAs($model->tutor_foto_dni);
                } else {
                }
                $tmpfile4 = UploadedFile::getInstance($model, 'archivo_foto_dnidorso');
                if (isset($tmpfile4)) {
                    $extension = $tmpfile4->extension;
                    $nombre =  $model->created_at . "_" . $model->documento . '_dorso.' . $extension;
                    $ruta = 'uploads/atpcen/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->foto_dnidorso = $ruta . $nombre;
                    $tmpfile4->saveAs($model->foto_dnidorso);
                } else {
                }
                $tmpfile5 = UploadedFile::getInstance($model, 'archivo_tutor_foto_dnidorso');
                if (isset($tmpfile5)) {
                    $extension = $tmpfile5->extension;
                    $nombre =  $model->created_at . "_" . $model->tutor_documento . '_dorso.' . $extension;
                    $ruta = 'uploads/atpcen/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->tutor_foto_dnidorso = $ruta . $nombre;
                    $tmpfile5->saveAs($model->tutor_foto_dnidorso);
                } else {
                }
                $fecha_nac = FormatDateSql($model->fecha_nacimiento);
                $fecha_nac = date_create($fecha_nac);
                $fecha_nac = date_format($fecha_nac, 'Y-m-d');
                $model->fecha_nacimiento = $fecha_nac;
                $fecha_nac_tut = FormatDateSql($model->tutor_fecha_nacimiento);
                $fecha_nac_tut = date_create($fecha_nac_tut);
                $fecha_nac_tut = date_format($fecha_nac_tut, 'Y-m-d');
                $model->tutor_fecha_nacimiento = $fecha_nac_tut;
                if ($guardar && $model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_atp_solicitud', $model->id, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Crear nueva Solitud de Tarjeta ATPCe",
                        'content' => '<span class="text-success">Solicitud de Tarjeta ATPCen creada exitosamente</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Crear otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                }
            }
            return [
                'title' => "Crear nueva Solitud de Tarjeta ATPCen",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'localidades' => $localidades,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_atp_solicitud model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        /** Trabaja solo las localidades de la Provincia de Neuquén */
        $localidades = Sds_com_localidad::find()->where(['idprovincia' => 58, 'activo' => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $localidades = ArrayHelper::map($localidades, 'idlocalidad', 'descripcion');

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {

                return [
                    'title' => "Actualizar Solitud de Tarjeta ATPCen",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'localidades' => $localidades,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $guardar = true;
                $tmpfile = UploadedFile::getInstance($model, 'archivo_foto_dni');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nombre =  $model->created_at . "_" . $model->documento . '_frente.' . $extension;
                    $ruta = 'uploads/atpcen/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->foto_dni = $ruta . $nombre;
                    $tmpfile->saveAs($model->foto_dni);
                } else {
                }
                $tmpfile2 = UploadedFile::getInstance($model, 'archivo_foto_certificado');
                if (isset($tmpfile2)) {
                    $extension = $tmpfile2->extension;
                    $nombre =  $model->created_at . "_" . $model->documento . '_certificado.' . $extension;
                    $ruta = 'uploads/atpcen/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->foto_certificado = $ruta . $nombre;
                    $tmpfile2->saveAs($model->foto_certificado);
                } else {
                }
                $ahora = date("Y-m-d H:i:s");
                $valormili = strtotime($ahora) * 1000;
                $model->updated_at = $valormili;
                $tmpfile3 = UploadedFile::getInstance($model, 'archivo_tutor_foto_dni');
                if (isset($tmpfile3)) {
                    $extension = $tmpfile3->extension;
                    $nombre =  $model->created_at . "_" . $model->tutor_documento . '_frente.' . $extension;
                    $ruta = 'uploads/atpcen/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->tutor_foto_dni = $ruta . $nombre;
                    $tmpfile3->saveAs($model->tutor_foto_dni);
                } else {
                }
                $tmpfile4 = UploadedFile::getInstance($model, 'archivo_foto_dnidorso');
                if (isset($tmpfile4)) {
                    $extension = $tmpfile4->extension;
                    $nombre =  $model->created_at . "_" . $model->documento . '_dorso.' . $extension;
                    $ruta = 'uploads/atpcen/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->foto_dnidorso = $ruta . $nombre;
                    $tmpfile4->saveAs($model->foto_dnidorso);
                } else {
                }
                $tmpfile5 = UploadedFile::getInstance($model, 'archivo_tutor_foto_dnidorso');
                if (isset($tmpfile5)) {
                    $extension = $tmpfile5->extension;
                    $nombre =  $model->created_at . "_" . $model->tutor_documento . '_dorso.' . $extension;
                    $ruta = 'uploads/atpcen/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->tutor_foto_dnidorso = $ruta . $nombre;
                    $tmpfile5->saveAs($model->tutor_foto_dnidorso);
                } else {
                }
                $fecha_nac = FormatDateSql($model->fecha_nacimiento);
                $fecha_nac = date_create($fecha_nac);
                $fecha_nac = date_format($fecha_nac, 'Y-m-d');
                $model->fecha_nacimiento = $fecha_nac;
                $fecha_nac_tut = FormatDateSql($model->tutor_fecha_nacimiento);
                $fecha_nac_tut = date_create($fecha_nac_tut);
                $fecha_nac_tut = date_format($fecha_nac_tut, 'Y-m-d');
                $model->tutor_fecha_nacimiento = $fecha_nac_tut;
                if ($guardar && $model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_atp_solicitud', $model->id, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Tarjeta ATPCen Solicitada",
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {

                    return [
                        'title' => "Actualizar Solitud de Tarjeta ATPCen",
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                            'localidades' => $localidades,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            } else {
                return [
                    'title' => "Actualizar Solitud de Tarjeta ATPCen",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'localidades' => $localidades,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {

                $tmpfile = UploadedFile::getInstance($model, 'archivo_foto_dni');
                if (isset($tmpfile)) {
                    $tmpfile_contents = file_get_contents($tmpfile->tempName);
                    $model->foto_dni = "data:image/png;base64," . base64_encode($tmpfile_contents);
                }

                $tmpfile2 = UploadedFile::getInstance($model, 'archivo_foto_certificado');
                if (isset($tmpfile2)) {
                    $tmpfile_contents2 = file_get_contents($tmpfile2->tempName);
                    $model->foto_certificado = "data:image/png;base64," . base64_encode($tmpfile_contents2);
                }
                $tmpfile3 = UploadedFile::getInstance($model, 'archivo_tutor_foto_dni');
                if (isset($tmpfile3)) {
                    $tmpfile_contents3 = file_get_contents($tmpfile3->tempName);
                    $model->tutor_foto_dni = "data:image/png;base64," . base64_encode($tmpfile_contents3);
                }
                $tmpfile4 = UploadedFile::getInstance($model, 'archivo_foto_dnidorso');
                if (isset($tmpfile4)) {
                    $tmpfile_contents4 = file_get_contents($tmpfile4->tempName);
                    $model->foto_dnidorso = "data:image/png;base64," . base64_encode($tmpfile_contents4);
                }
                $tmpfile5 = UploadedFile::getInstance($model, 'archivo_tutor_foto_dnidorso');
                if (isset($tmpfile5)) {
                    $tmpfile_contents5 = file_get_contents($tmpfile5->tempName);
                    $model->tutor_foto_dnidorso = "data:image/png;base64," . base64_encode($tmpfile_contents5);
                }
                if ($model->save()) {
                    //return $this->redirect(['view', 'id' => $model->id]);
                    return $this->redirect(['index']);
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionMigrar_dni()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_atp_solicitud/migrar_dni', null, array());
        $solicitudes = Mds_atp_solicitud::find()->where("foto_dni is not null and foto_dni like '%base64%'")->limit(10)->all();
        // 'foto_dni','foto_dnidorso', 'foto_certificado' required
        // 'tutor_foto_dni','tutor_foto_dnidorso' not required        
        $solicitudes_migradas = array();
        foreach ($solicitudes as $solicitud) {
            //Foto DNI
            $image_parts = explode(";base64,", $solicitud->foto_dni);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $nombre =  $solicitud->created_at . "_" . $solicitud->documento . '_frente.' . $image_type;
            $ruta = 'uploads/atpcen';
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $solicitud->foto_dni = $ruta . '/' . $nombre;
            file_put_contents($solicitud->foto_dni, $image_base64);
            //Foto DNI Dorso
            if ($solicitud->foto_dnidorso != null) {
                $image_parts = explode(";base64,", $solicitud->foto_dnidorso);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $nombre =  $solicitud->created_at . "_" . $solicitud->documento . '_dorso.' . $image_type;
                $ruta = 'uploads/atpcen';
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0777, true);
                }
                $solicitud->foto_dnidorso = $ruta . '/' . $nombre;
                file_put_contents($solicitud->foto_dnidorso, $image_base64);
            }
            //Foto Certificado
            if ($solicitud->foto_certificado != null) {
                $image_parts = explode(";base64,", $solicitud->foto_certificado);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = isset($image_type_aux[1]) ? $image_type_aux[1] : "png";
                $image_base64 = base64_decode($image_parts[1]);
                $nombre =  $solicitud->created_at . "_" . $solicitud->documento . '_certificado.' . $image_type;
                $ruta = 'uploads/atpcen';
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0777, true);
                }
                $solicitud->foto_certificado = $ruta . '/' . $nombre;
                file_put_contents($solicitud->foto_certificado, $image_base64);
            }
            //DNI Tutor
            if ($solicitud->tutor_foto_dni != null) {
                $image_parts = explode(";base64,", $solicitud->tutor_foto_dni);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $nombre =  $solicitud->created_at . "_" . $solicitud->tutor_documento . '_frente.' . $image_type;
                $ruta = 'uploads/atpcen';
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0777, true);
                }
                $solicitud->tutor_foto_dni = $ruta . '/' . $nombre;
                file_put_contents($solicitud->tutor_foto_dni, $image_base64);
            }
            //DNI Tutor Dorso
            if ($solicitud->tutor_foto_dnidorso != null) {
                $image_parts = explode(";base64,", $solicitud->tutor_foto_dnidorso);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $nombre =  $solicitud->created_at . "_" . $solicitud->tutor_documento . '_dorso.' . $image_type;
                $ruta = 'uploads/atpcen';
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0777, true);
                }
                $solicitud->tutor_foto_dnidorso = $ruta . '/' . $nombre;
                file_put_contents($solicitud->tutor_foto_dnidorso, $image_base64);
            }
            if ($solicitud->save(false)) {
                array_push($solicitudes_migradas, $solicitud);
            } else {
                return "Fallo la solicitud " . $solicitud->id;
            }
        }

        return $solicitudes_migradas;
    }

    /**
     * Delete an existing Mds_atp_solicitud model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_atp_solicitud', $id, $model->getAttributes());
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
     * Finds the Mds_atp_solicitud model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_atp_solicitud the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_atp_solicitud::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionReporte_solicitud($id)
    {
        $content = $this->renderPartial('reporte_solicitud', ['id' => $id]); // setup kartik\mpdf\Pdf component 
        //        print_r($content);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',                  
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/style.css',

            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'ACTA DE SOLICITUD TARJETA ATPCen',
                'SetHeader' => null,
                'SetFooter' => ['|Ministerio de Desarrollo Social y Trabajo | Página {PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    protected function getLocalidades()
    {
        //Busqueda localidades
        $localidades = Sds_com_localidad::find()->where(['idprovincia' => 58, 'activo' => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $localidades = ArrayHelper::map($localidades, 'idlocalidad', 'descripcion');

        return $localidades;
    }
}
function FormatDateSql($Fecha)
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
