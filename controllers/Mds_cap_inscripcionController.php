<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_cap_inscripcion;
use app\models\Mds_cap_inscripcionSearch;
use app\models\Mds_cap_instancia;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use app\models\Sds_com_persona;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\mpdf\Pdf;
use yii\bootstrap\Modal;
use app\models\Mds_cap_persona;
use app\models\Mds_cap_capacitacion;
use app\models\Mds_org_documento;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario;
use yii\helpers\ArrayHelper;
use app\models\Mds_seg_usuario_rol;
use yii\web\ForbiddenHttpException;

/**
 * Mds_cap_inscripcionController implements the CRUD actions for Mds_cap_inscripcion model.
 */
class Mds_cap_inscripcionController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout', 'certificado', 'desc_certificado', 'preview_certificado', 'create2'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout', 'certificado', 'desc_certificado', 'preview_certificado', 'create2'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_CAP_INSCRIPCION,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_cap_inscripcion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $modelForm = new \app\models\LoginForm();
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $modelForm,
            ]);
        }
        $idcontacto  = Yii::$app->user->identity->idcontacto;
        $idOrganismo = null;
        $idOrganismoExterno  = Yii::$app->user->identity->externo;

        // Verificamos que tenga un contacto asociado (usuario del MDSYT) o un idOrganismoExterno asociado
        if ((!$idcontacto && !$idOrganismoExterno)) {
            Yii::$app->session->setFlash('error_modulo', "El usuario debe tener un contacto asignado o participar de un organismo externo. <br>Comuníquese con un administrador.");
            return Yii::$app->getResponse()->redirect([
                'site',
            ]);
        }


        if ($idcontacto) {
            // Tiene contacto - Usuario MDSYT
            $idOrganismo = Mds_org_organismo::find()->where(
                "idorganismo in (select idorganismo
                    from mds_org_contacto contacto,mds_org_dispositivo disp
                    where disp.iddispositivo=contacto.iddispositivo and idcontacto = $idcontacto)"
            )->orderBy(['descripcion' => SORT_ASC])->one()->idorganismo;
        }


        $searchModel = new Mds_cap_inscripcionSearch();

        if ($idOrganismo || $idOrganismoExterno) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            if ($idOrganismo) {
                $filterInstancias = $this->getFilterInstancias("INTERNO", $idOrganismo, $idusuario, $idcontacto);
            } else {
                $filterInstancias = $this->getFilterInstancias("EXTERNO", $idOrganismoExterno, $idusuario, null);
            }
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cap_inscripcion', null, array());

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'filterInstancias' => $filterInstancias
            ]);
        } else {
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $modelForm,
            ]);
        }
    }


    /**
     * Displays a single Mds_cap_inscripcion model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $instancia = Mds_cap_instancia::findOne($model->idcapinstancia);
        $model->titulo_dato_adicional = $instancia->titulo_dato_adicional;
        $model->persona = Sds_com_persona::findOne($model->idpersonacap0->idpersona);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cap_inscripcion', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Inscripción #" . $id,
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
    public function actionCertificado($id, $nombres, $el_dni) /*agregado por Luis Garcia*/
    {
        $una_cap_inscripcion = Mds_cap_inscripcion::findOne($id);
        $una_cap_instancia = Mds_cap_instancia::find()
            ->where(['idinstancia' => $una_cap_inscripcion->idcapinstancia])
            ->one();
        $generar = true;
        $sugerir = false;
        $cad_resp = "";
        $cad_resp_sug = "";
        $culmino = (($una_cap_inscripcion->termino == 2) || ($una_cap_inscripcion->termino == 6));
        $res_aval = (($una_cap_instancia->resolucion_aval == null) || ($una_cap_instancia->resolucion_aval == ""));
        if ($res_aval) {
            $sugerir = true;
            $cad_resp_sug .= "<br>  - Resolución/Ley/Aval";
        }
        $res_area = (($una_cap_instancia->area_certificado == null) || ($una_cap_instancia->area_certificado == ""));
        if ($res_area) {
            $generar = false;
            $cad_resp .= "<br>  - Áreas intervinientes";
        }
        $res_presencial = ($una_cap_instancia->presencial == null);
        if ($res_presencial) {
            $sugerir = true;
            $cad_resp_sug .= "<br>  - Modalidad";
        }
        if (($una_cap_instancia->cant_horas == 0) || ($una_cap_instancia->cant_horas == null)) {
            $sugerir = true;
            $cad_resp_sug .= "<br>  - Cantidad de Horas";
        }
        $una_cap_persona = Mds_cap_persona::find()
            ->where(['idpersonacap' => $una_cap_inscripcion->idpersonacap])
            ->one();
        $una_com_persona = Sds_com_persona::find()
            ->where(['idpersona' => $una_cap_persona->idpersona])
            ->one();
        $un_cap_capacitacion = Mds_cap_capacitacion::find()
            ->where(['idcapacitacion' => $una_cap_instancia->idcapacitacion])
            ->one();
        if ($culmino) {
            if ($una_cap_inscripcion->estado_cert == 0) {
                if ($generar) {
                    $content = $this->renderPartial('certificado', ['id' => $id]); // setup kartik\mpdf\Pdf component 
                    define('_MPDF_TTFONTPATH', __DIR__ . '/fonts');
                    $nombres_save = $una_cap_instancia->idinstancia . '_' . $una_com_persona->documento;
                    $dir_valido = "../web/uploads/certificados/" . $una_cap_instancia->idinstancia;
                    $path_valido = "../web/uploads/certificados/" . $una_cap_instancia->idinstancia . "/" . $nombres_save . '.pdf';
                    $path_valido5 = "uploads/certificados/" . $una_cap_instancia->idinstancia . "/" . $nombres_save . '.pdf';
                    if (!file_exists($dir_valido)) {
                        mkdir($dir_valido, 0777, true);
                    }
                    $pdf = new Pdf([
                        'mode' => Pdf::MODE_UTF8,
                        'format' => Pdf::FORMAT_A4,

                        'orientation' => Pdf::ORIENT_LANDSCAPE,
                        'destination' => Pdf::DEST_BROWSER,
                        'content' => $content,

                        'defaultFontSize' => 12,
                        'filename' => $path_valido,
                        'destination' => "F",
                        'options' => ['margin-header' => '0', 'setAutoTopMargin' => false, 'margin-footer' => '0', 'setAutoBottomMargin' => false],
                        //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',                  
                        'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/style_cert.css',
                        //'cssFile' => 'estilo.css',
                        // any css to be embedded if required
                        'cssInline' => '.kv-heading-1{font-size:18px}',
                        'methods' => [
                            'SetTitle' => 'Certificado',
                            'SetHeader' => false,
                            'SetFooter' => false,
                            'SetMargins' => [0, 0, 0, 0]
                        ]
                    ]);
                    $model = $this->findModel($id);
                    //$model->estado_cert = 1;
                    $model->estado_cert = 2;


                    $model->path_cert = $path_valido5;
                    if ($model->save()) {
                        $documento = new Mds_org_documento();
                        $usuario = Yii::$app->user->identity;
                        $idusuario = $usuario != null ? $usuario->idusuario : null;
                        if (!isset($idusuario) || $idusuario == null) {
                            $model = new \app\models\LoginForm();
                            return Yii::$app->getResponse()->redirect([
                                'site/login',
                                'model' => $model,
                            ]);
                        }
                        if (isset($model->idcontacto)) {

                            $documento->idusuario =  Yii::$app->user->identity->idusuario;
                            $documento->tipo = 1744; //Tipo cert.Capac.
                            $documento->nombre = $nombres_save;
                            $documento->fecha = date('Y-m-d');
                            $documento->path = "uploads/certificados/" . $una_cap_instancia->idinstancia . "/" . $nombres_save . '.pdf';;
                            $documento->detalle = "Certificado Capacitación " . $un_cap_capacitacion->descripcion;
                            $documento->idcontacto = $model->idcontacto;
                            $documento->save(false);
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_documento', $documento->iddocumento, $documento->getAttributes());
                        }
                    }
                    $pdf->render();
                    $texto_exit = '<span class="text-success">Se generó el certificado de ' . $una_com_persona->nombre . ' ' . $una_com_persona->apellido . ' exitosamente</span>';
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'title' => "Certificados",
                        'size' => Modal::SIZE_SMALL,
                        'content' => $texto_exit,
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                } else {
                    $texto_exit = '<span class="text-success">No se puede generar el <b>certificado</b>, ya que falta cargar datos en la instancia de la capacitación:' . $cad_resp . '</span>';

                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'title' => "Certificados",
                        'size' => Modal::SIZE_SMALL,
                        'content' => $texto_exit,
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                $texto_exit = '<span class="text-success">El <b>certificado</b> de ' . $una_com_persona->nombre . ' ' . $una_com_persona->apellido . ' fue generado anteriormente </span>';

                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Certificados",
                    'size' => Modal::SIZE_SMALL,
                    'content' => $texto_exit,
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            }
        } else {


            $texto_exit = '<span class="text-success">No se puede generar el <strong>certificado</strong>, ya que ' . $una_com_persona->nombre . ' ' . $una_com_persona->apellido . ' no ha aprobado el curso ' . $un_cap_capacitacion->descripcion . '</span>';
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Certificados",
                'size' => Modal::SIZE_SMALL,
                'content' => $texto_exit,
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }
    }
    public function actionPreview_certificado($id, $nombres, $el_dni) /*agregado por Luis Garcia*/
    {
        $una_cap_inscripcion = Mds_cap_inscripcion::findOne($id);
        $una_cap_instancia = Mds_cap_instancia::find()
            ->where(['idinstancia' => $una_cap_inscripcion->idcapinstancia])
            ->one();

        $una_cap_persona = Mds_cap_persona::find()
            ->where(['idpersonacap' => $una_cap_inscripcion->idpersonacap])
            ->one();
        $una_com_persona = Sds_com_persona::find()
            ->where(['idpersona' => $una_cap_persona->idpersona])
            ->one();
        $un_cap_capacitacion = Mds_cap_capacitacion::find()
            ->where(['idcapacitacion' => $una_cap_instancia->idcapacitacion])
            ->one();

        $content = $this->renderPartial('certificado', ['id' => $id]); // setup kartik\mpdf\Pdf component 
        define('_MPDF_TTFONTPATH', __DIR__ . '/fonts');

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,

            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,

            'defaultFontSize' => 12,
            'filename' => 'preview.pdf',
            'destination' => "I",
            'options' => ['margin-header' => '0', 'setAutoTopMargin' => false, 'margin-footer' => '0', 'setAutoBottomMargin' => false],
            //'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',                  
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/style_cert.css',
            //'cssFile' => 'estilo.css',

            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'Certificado',
                'SetHeader' => false,
                'SetFooter' => false,
                'SetMargins' => [0, 0, 0, 0],
            ]
        ]);

        return  $pdf->render();
    }
    public function actionDesc_certificado($id) /* id es el idinscripcion, agregado por Luis Garcia*/
    {
        $una_cap_inscripcion = Mds_cap_inscripcion::findOne($id);

        $archivo = "../web/" . $una_cap_inscripcion->path_cert;
        if ($una_cap_inscripcion->path_cert != null || $una_cap_inscripcion->path_cert != '') {
            return \Yii::$app->response->sendFile($archivo);
        }
    }
    /**
     * Creates a new Mds_cap_inscripcion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate2($idpersonacap, $id_instancia, $termino, $fecha_desde, $dato_adicional, $dni_search)
    {
        $model = new Mds_cap_inscripcion();
        $model->idpersonacap = $idpersonacap;
        $model->idcapinstancia = $id_instancia;
        $model->fecha_inscripcion = $fecha_desde;
        $model->termino = $termino;
        $model->dato_adicional = $dato_adicional;
        $model->estado_cert = 0;
        $model->path_cert = null;
        $model->codigo_qr = null;

        $una_inscripcion = Mds_cap_inscripcion::find()->where(["idpersonacap" => $idpersonacap, "idcapinstancia" => $id_instancia])->one();
        if ($una_inscripcion != null) {
            Yii::$app->session->setFlash('error', "<b>Atención:</b> no se puede guardar el registro, ya que la persona ya fue inscripta a esta instancia anteriomente.");
            return "duplicado";
        } else {
            $fecha_ins = ArmarDateParaMySql($model->fecha_inscripcion);
            $fecha_ins = date_create($fecha_ins);
            $fecha_ins = date_format($fecha_ins, 'Y-m-d');
            $model->fecha_inscripcion = $fecha_ins;
            if ($model->save()) {
                return "exito";
            } else {
                echo "MODEL NOT SAVED";
                print_r($model->getAttributes());
                print_r($model->getErrors());

                return $model->idpersonacap . "-" . $model->idcapinstancia . "-" . $model->fecha_inscripcion . "-" . $model->termino . "-" . $model->dato_adicional;
            }
        }
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_cap_inscripcion();
        $model->fecha_inscripcion = date('d-m-Y');

        $idcontacto  = Yii::$app->user->identity->idcontacto;
        $idusuario  = Yii::$app->user->identity->idusuario;
        $idOrganismo = null;
        $idOrganismoExterno  = Yii::$app->user->identity->externo;

        // Verificamos que tenga un contacto asociado (usuario del MDSYT) o un idOrganismoExterno asociado
        if ((!$idcontacto && !$idOrganismoExterno)) {
            Yii::$app->session->setFlash('error_modulo', "El usuario debe tener un contacto asignado o participar de un organismo externo. <br>Comuníquese con un administrador.");
            return Yii::$app->getResponse()->redirect([
                'site',
            ]);
        }

        if ($idcontacto) {
            // Tiene contacto - Usuario MDSYT
            $idOrganismo = Mds_org_organismo::find()->where(
                "idorganismo in (select idorganismo
                    from mds_org_contacto contacto,mds_org_dispositivo disp
                    where disp.iddispositivo=contacto.iddispositivo and idcontacto = $idcontacto)"
            )->orderBy(['descripcion' => SORT_ASC])->one()->idorganismo;
        }

        if ($idOrganismo || $idOrganismoExterno) {
            if ($idOrganismo) {
                $filterInstancias = $this->getFilterInstancias("INTERNO", $idOrganismo, $idusuario, $idcontacto);
            } else {
                $filterInstancias = $this->getFilterInstancias("EXTERNO", $idOrganismoExterno, $idusuario, null);
            }
        } else {
            return Yii::$app->getResponse()->redirect([
                'site',
            ]);
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nueva Inscripción",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'filterInstancias' => $filterInstancias
                    ]),
                ];
            } else /*if ($model->load($request->post()))*/ {
                $una_inscripcion = Mds_cap_inscripcion::find()->where(["idpersonacap" => $model->idpersonacap, "idcapinstancia" => $model->idcapinstancia])->one();
                if ($una_inscripcion != null) {
                    Yii::$app->session->setFlash('error', "<b>Atención:</b> no se puede guardar el registro, ya que la persona ya fue inscripta a esta instancia anteriomente.");
                    return "duplicado";
                } else {
                    $transaction = Yii::$app->db->beginTransaction();
                    $fecha_ins = ArmarDateParaMySql($model->fecha_inscripcion);
                    $fecha_ins = date_create($fecha_ins);
                    $fecha_ins = date_format($fecha_ins, 'Y-m-d');
                    $model->fecha_inscripcion = $fecha_ins;
                    if ($model->save()) {
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_cap_inscripcion', $model->idinscripcion, $model->getAttributes());
                        return "exito";
                    } else {
                        return $model->idinscripcion;
                    }
                }
            }
            return [
                'title' => "Nueva Inscripción",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'filterInstancias' => $filterInstancias
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->Guardar()) {
                return $this->redirect(['view', 'id' => $model->idinscripcion]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'filterInstancias' => $filterInstancias
                ]);
            }
        }
    }
    public function actionBuscar_dni($dni)
    {
        $result = array();
        $com_persona = Sds_com_persona::find()->where(["documento" => $dni])->one();
        if ($com_persona != null) {
            //return $com_persona->nombre." ".$com_persona->apellido;
            array_push($result, $com_persona->getAttributes());
            $cap_persona = Mds_cap_persona::find()->where(["idpersona" => $com_persona->idpersona])->one();
            if ($cap_persona != null) {
                array_push($result, $cap_persona->getAttributes());
            }
        }
        return json_encode($result);
    }
    /**
     * Updates an existing Mds_cap_inscripcion model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $instancia = Mds_cap_instancia::findOne($model->idcapinstancia);
        //$instancia = Mds_cap_instancia::findBySql("select titulo_dato_adicional from mds_cap_instancia  where idinstancia = $model->idcapinstancia;")->one();
        $model->titulo_dato_adicional = $instancia->titulo_dato_adicional;

        $idcontacto  = Yii::$app->user->identity->idcontacto;
        $idusuario  = Yii::$app->user->identity->idusuario;
        $idOrganismo = null;
        $idOrganismoExterno  = Yii::$app->user->identity->externo;

        // Verificamos que tenga un contacto asociado (usuario del MDSYT) o un idOrganismoExterno asociado
        if ((!$idcontacto && !$idOrganismoExterno)) {
            Yii::$app->session->setFlash('error_modulo', "El usuario debe tener un contacto asignado o participar de un organismo externo. <br>Comuníquese con un administrador.");
            return Yii::$app->getResponse()->redirect([
                'site',
            ]);
        }

        if ($idcontacto) {
            // Tiene contacto - Usuario MDSYT
            $idOrganismo = Mds_org_organismo::find()->where(
                "idorganismo in (select idorganismo
                    from mds_org_contacto contacto,mds_org_dispositivo disp
                    where disp.iddispositivo=contacto.iddispositivo and idcontacto = $idcontacto)"
            )->orderBy(['descripcion' => SORT_ASC])->one()->idorganismo;
        }

        if ($idOrganismo || $idOrganismoExterno) {
            if ($idOrganismo) {
                $filterInstancias = $this->getFilterInstancias("INTERNO", $idOrganismo, $idusuario, $idcontacto);
            } else {
                $filterInstancias = $this->getFilterInstancias("EXTERNO", $idOrganismoExterno, $idusuario, null);
            }
        } else {
            return Yii::$app->getResponse()->redirect([
                'site',
            ]);
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Modificar Inscripción: " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'filterInstancias' => $filterInstancias
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $guardado = true;
                $transaction = Yii::$app->db->beginTransaction();
                if ($guardado && $model->save(false)) {
                    $transaction->commit();
                    $model->persona = Sds_com_persona::findOne($model->idpersonacap0->idpersona);
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_cap_inscripcion', $id, $model->getAttributes());
                    return [
                        'title' => "Inscripción #" . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                return [
                    'title' => "Modificar Inscripción: " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'filterInstancias' => $filterInstancias
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->Guardar()) {
                return $this->redirect(['view', 'id' => $model->idinscripcion]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'filterInstancias' => $filterInstancias
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_cap_inscripcion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_cap_inscripcion', $id, $model->getAttributes());
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
            return $this->redirect(['index']);
        }
    }

    public function actionGet_adicional($idinstancia)
    {
        if ($idinstancia) {
            $instancia = Mds_cap_instancia::findOne($idinstancia);
            return $instancia->titulo_dato_adicional;
        } else {
            return null;
        }
    }
    public function actionAcreditacion()
    {
        $hasRolAcreditacion = Mds_seg_usuario_rol::hasRol(Mds_cap_instancia::ID_ROL_ACREDITACION);
        if ($hasRolAcreditacion) {

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $token = isset($_SESSION["tokenNest"]) ? $_SESSION["tokenNest"] : '';

            return $this->render('form_acreditacion', [
                'username' => Yii::$app->user->identity->user,
                'token' => $token
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Finds the Mds_cap_inscripcion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Mds_cap_inscripcion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_cap_inscripcion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Arma el select para filtrar las instancias. Recibe un tipo (INTERNO/EXTERNO) y el ID del organismo
     */
    protected function getFilterInstancias($tipo, $id, $idusuario = null, $idcontacto = null)
    {

        $itemGlobal = Mds_seg_item::MODULO_CAP_GLOBAL;
        $permisos = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                        idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario AND iditem = $itemGlobal)")->all();
        $permiso_global = 0;
        if ($permisos && count($permisos) > 0) {
            $permiso_global = 1;
        }

        if ($tipo === "INTERNO") {

            $filterInstancias =  Mds_cap_instancia::find()->where("idcapacitacion in (SELECT cap.idcapacitacion FROM mds_cap_capacitacion cap
            where idorganismo in (select disp.idorganismo
            from mds_org_contacto contacto,
            mds_org_dispositivo disp
            where disp.iddispositivo=contacto.iddispositivo
            and idcontacto=$idcontacto
            union
            select vinc.vinculacion
            from mds_org_contacto contacto,
            mds_org_dispositivo disp
            join mds_org_organismo_vinculacion vinc on vinc.idorganismo=disp.idorganismo
            where disp.iddispositivo=contacto.iddispositivo
            and idcontacto=$idcontacto)) or 1=$permiso_global")->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
            $filterInstancias = ArrayHelper::map(
                $filterInstancias,
                'idinstancia',
                function ($model) {
                    return $model['idinstancia'] . ' - ' . $model['descripcion'];
                }
            );
        } else {

            // EXTERNO
            $filterInstancias =  Mds_cap_instancia::find()->where("idcapacitacion in (SELECT cap.idcapacitacion FROM mds_cap_capacitacion cap
            where idorganismoexterno = $id or 1=$permiso_global)")->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
            $filterInstancias = ArrayHelper::map(
                $filterInstancias,
                'idinstancia',
                function ($model) {
                    return $model['idinstancia'] . ' - ' . $model['descripcion'];
                }
            );
        }

        return $filterInstancias;
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
