<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use Yii;
use app\models\Sds_ent_entrega;
use app\models\Sds_ent_entregaSearch;
use app\models\Sds_ent_saldo;
use app\models\Sds_ent_solicitud;
use app\models\Sds_ent_tipo;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use app\models\Mds_sys_log;
use app\models\Sds_ent_responsable;
use app\models\Sds_ent_solicitud_intermedia;

/**
 * Sds_ent_entregaController implements the CRUD actions for Sds_ent_entrega model.
 */
class Sds_ent_entregaController extends Controller
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
                'only' => [
                    'index',
                    'create',
                    'update',
                    'delete',
                    'view',
                    'logout',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'delete',
                            'update',
                            'view',
                            'logout',
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ENT_ENTREGAS,
                            Mds_seg_item::MODULO_ENT_ENTREGA_INTERMEDIA,
                            Mds_seg_item::MODULO_ENT_PRIMER_INGRESO,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_ent_entrega models.
     * @return mixed
     *
     * Paso el parámetro 0 si es entrega inicial
     * Paso el parámetro 1 si es entrega intermedia
     * Paso el parámetro 2 si es entrega final
     */
    public function actionIndex(
        $estado = Sds_ent_entrega::ESTADO_FINAL,
        $idemisor = null
    ) {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app
                ->getResponse()
                ->redirect(['site/login', 'model' => $model]);
        }
        $permiso_entrega = Mds_seg_permiso::findBySql(
            "select * from mds_seg_permiso where 
        idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
        and ((iditem=" .
                Mds_seg_item::MODULO_ENT_ENTREGAS .
                ' and ' .
                $estado .
                '=' .
                Sds_ent_entrega::ESTADO_FINAL .
                ")
        or (iditem=" .
                Mds_seg_item::MODULO_ENT_ENTREGA_INTERMEDIA .
                ' and ' .
                $estado .
                '=' .
                Sds_ent_entrega::ESTADO_INTERMEDIA .
                ")
        or (iditem=" .
                Mds_seg_item::MODULO_ENT_PRIMER_INGRESO .
                ' and ' .
                $estado .
                '=' .
                Sds_ent_entrega::ESTADO_INICIAL .
                ")
        or (iditem=" .
                Mds_seg_item::MODULO_ENT_DEUDOR .
                ' and ' .
                $estado .
                '=' .
                Sds_ent_entrega::ESTADO_DEUDOR .
                '))'
        )->one();
        $permiso_entrega = $permiso_entrega != null ? 1 : 0;
        if ($permiso_entrega == 0) {
            Yii::$app->session->setFlash(
                'error_modulo',
                'Usted no posee permisos para ingresar al módulo. <br>Comuníquese con un administrador.'
            );
            return Yii::$app->getResponse()->redirect(['site']);
        }
        $searchModel = new Sds_ent_entregaSearch();
        $searchModel->estado = $estado;
        $searchModel->nombre_emisor = $idemisor != null ? $idemisor : '';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_ent_entrega',
            null,
            []
        );
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEnviar_deudor($id)
    {
        $model = $this->findModel($id);
        $receptor = Sds_com_configuracion::findOne($model->receptor);
        $datos_responsable = Sds_ent_responsable::findOne($model->receptor);
        $fecha = date_create($model->fecha_hora);
        $fecha = date_format($fecha, 'd/m/Y');
        $tipo = Sds_ent_tipo::findOne($model->idtipo)->descripcion;
        $mail_responsable = $datos_responsable->mail;
        $nombre_responsable = $receptor->descripcion;
        $dni_responsable =
            $datos_responsable != null ? $datos_responsable->dni : '';
        $contenido =
            '<!doctype html>
        <html>
        
        <head>
          <meta name="viewport" content="width=device-width">
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <title>Simple Transactional Email</title>
          <style>
            /* -------------------------------------
                INLINED WITH htmlemail.io/inline
            ------------------------------------- */
            /* -------------------------------------
                RESPONSIVE AND MOBILE FRIENDLY STYLES
            ------------------------------------- */
            @media only screen and (max-width: 620px) {
              table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
              }
        
              table[class=body] p,
              table[class=body] ul,
              table[class=body] ol,
              table[class=body] td,
              table[class=body] span,
              table[class=body] a {
                font-size: 16px !important;
              }
        
              table[class=body] .wrapper,
              table[class=body] .article {
                padding: 10px !important;
              }
        
              table[class=body] .content {
                padding: 0 !important;
              }
        
              table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
              }
        
              table[class=body] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
              }
        
              table[class=body] .btn table {
                width: 100% !important;
              }
        
              table[class=body] .btn a {
                width: 100% !important;
              }
        
              table[class=body] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
              }
            }
        
            /* -------------------------------------
                PRESERVE THESE STYLES IN THE HEAD
            ------------------------------------- */
            @media all {
              .ExternalClass {
                width: 100%;
              }
        
              .ExternalClass,
              .ExternalClass p,
              .ExternalClass span,
              .ExternalClass font,
              .ExternalClass td,
              .ExternalClass div {
                line-height: 100%;
              }
        
              .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
              }
        
              #MessageViewBody a {
                color: inherit;
                text-decoration: none;
                font-size: inherit;
                font-family: inherit;
                font-weight: inherit;
                line-height: inherit;
              }
        
              .btn-primary table td:hover {
                background-color: #34495e !important;
              }
        
              .btn-primary a:hover {
                background-color: #34495e !important;
                border-color: #34495e !important;
              }
            }
          </style>
        </head>
        
        <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
          <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;"></span>
          <table border="0" cellpadding="0" cellspacing="0" class="body"
            style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
            <tr>
              <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
              <td class="container"
                style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
                <div class="content"
                  style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">
        
                  <!-- START CENTERED WHITE CONTAINER -->
                  <table class="main"
                    style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">        
                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                      <td class="wrapper"
                        style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                        <table border="0" cellpadding="0" cellspacing="0"
                          style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                          <tr>
                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">                      
                              <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">' .
            $nombre_responsable .
            ' - DNI: ' .
            $dni_responsable .
            ': <br><br>
                                Está pendiente de rendición la entrega con fecha ' .
            $fecha .
            ', 
                                tipo ' .
            $tipo .
            ', cantidad ' .
            $model->cantidad .
            '. 
                                Por favor comunicarse a la brevedad.                               
                                </p>      
                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
                                    Subsecretaría de Desarrollo Social, Gobierno de la Provincia del Neuquén
                                </p>                      
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>            
                    <tr>
                      <td>
                        <br>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <img src="https://sur.neuquen.gov.ar/img/footermail.png" width="580px">
                      </td>
                    </tr>
        
                    <!-- END MAIN CONTENT AREA -->
                  </table>
        
                  <!-- START FOOTER -->
                  <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                    <table border="0" cellpadding="0" cellspacing="0"
                      style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                      <tr>
                        <td class="content-block"
                          style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                          <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Ministerio de
                            Desarrollo Social y Trabajo
                          </span>
                        </td>
                      </tr>
                      <tr>
                        <td class="content-block powered-by"
                          style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                          Enviado por "SUR - Sistema Unico de Registro"
                        </td>
                      </tr>
                    </table>
                  </div>
                  <!-- END FOOTER -->
        
                  <!-- END CENTERED WHITE CONTAINER -->
                </div>
              </td>
              <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
            </tr>
          </table>
        </body>
        
        </html>';

        Yii::$app->mailer
            ->compose()
            ->setFrom('ezeramsoft@gmail.com')
            ->setTo($mail_responsable)
            ->setSubject('Notificación Rendiciones de entregas pendientes')
            ->setHtmlBody($contenido)
            ->send();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Notificación de Rendiciones Pendientes',
            'content' =>
            '<div class="alert alert-success alert-dismissable">       
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>             
                            <h4><i class="icon fa fa-check"></i> Mail enviado correctamente!
                                <br> Se muestra la vista previa del mismo. Puede cerrar esta pantalla.
                            </h4>
                        </div>' . $contenido,
            'footer' => Html::button('Cerrar', [
                'class' => 'btn btn-default pull-left',
                'data-dismiss' => 'modal',
            ]),
        ];
    }

    public function actionIndicadores()
    {
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_ent_entrega/indicadores',
            null,
            []
        );
        return $this->render('indicadores');
    }

    public function actionArbol_entregas($externo = 1)
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app
                ->getResponse()
                ->redirect(['site/login', 'model' => $model]);
        }
        $permiso_arbol = Mds_seg_permiso::findBySql(
            "select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" .
                Mds_seg_item::MODULO_ENT_ARBOL .
                ')'
        )->one();
        $permiso_arbol = $permiso_arbol != null ? 1 : 0;
        if ($permiso_arbol == 0) {
            Yii::$app->session->setFlash(
                'error_modulo',
                'Usted no posee permisos para ingresar al módulo. <br>Comuníquese con un administrador.'
            );
            return Yii::$app->getResponse()->redirect(['site']);
        }
        return $this->render('arbol_entregas', ['externo' => $externo]);
    }

    public function actionReload_arbol_entregas(
        $entrega_madre = null,
        $idtipo,
        $receptor,
        $anio,
        $externo
    ) {
        $identrega = $entrega_madre != null ? $entrega_madre->identrega : -1;
        if ($identrega < 0) {
            Mds_sys_log::guardarLog(
                Mds_sys_log::ACCION_CONSULTA,
                'sds_ent_entrega/arbol_entregas',
                null,
                ['idtipo' => $idtipo, 'receptor' => $receptor, 'anio' => $anio]
            );
            //$cantidad_buscar = Sds_ent_entrega::getTotal($idtipo, $receptor, $anio);
        }
        $html_hijas = '';
        $codigos = $identrega;
        $contiene_filtros = false;
        $saldo = $entrega_madre != null ? $entrega_madre->cantidad : 0;
        if ($entrega_madre != null) {
            $contiene_filtros = ($entrega_madre->receptor == $receptor || $receptor == -1) &&
                $anio == date('Y', strtotime($entrega_madre->fecha_hora));
        }
        $entregas_hijas = Sds_ent_entrega::getEntregasHijas(
            $identrega,
            $idtipo,
            $externo
        );
        $tiene_hijas = !empty($entregas_hijas);
        $icon_text =
            '{ "icon" : "fa fa-folder ' .
            ($tiene_hijas ? 'text-primary' : 'text-black') .
            '"' .
            ($identrega == -1 ? ',"opened":true ' : '') .
            '}';
        if ($tiene_hijas) {
            foreach ($entregas_hijas as $entrega) {
                $html_hijas_content = $this->actionReload_arbol_entregas(
                    $entrega,
                    $idtipo,
                    $contiene_filtros ? -1 : $receptor,
                    $anio,
                    $externo
                );
                $saldo -= $entrega->cantidad;
                if ($html_hijas_content != '') {
                    if ($codigos != -1) {
                        $codigos = $codigos . ',' . $entrega->identrega;
                    }
                    $html_hijas =
                        $html_hijas . '<ul>' . $html_hijas_content . '</ul>';
                }
            }
        }
        if (!$contiene_filtros && $html_hijas == '') {
            return '';
        }
        $saldo =
            $entrega_madre != null
            ? $saldo - Sds_ent_entrega::getEntregasFinalesTotal($identrega)
            : 0;
        $saldo =
            $entrega_madre != null
            ? $saldo - Sds_ent_entrega::getDevueltas($identrega)
            : 0;
        //Faltaria que no me agregue las entregas que no tienen hijas, pero no son del responsable filtrado, que no se en que momento me las esta tomando
        //y las agrega. A lo mejor toma como que el nodo ese, si contiene los filtros. Eso hay que revisar.
        $html_arbol =
            "<li id='" .
            $codigos .
            "' data-jstree='" .
            $icon_text .
            "'>" .
            ($identrega > 0
                ? ($entrega_madre->numero_desde != null
                    ? $entrega_madre->numero_desde
                    : '') .
                ($entrega_madre->numero_hasta != null
                    ? ' al ' . $entrega_madre->numero_hasta . '|'
                    : '') .
                ($entrega_madre->nombre_receptor .
                    ($entrega_madre->receptor == $receptor ||
                        $receptor == -1
                        ? ($entrega_madre->oc ? "|OC: " . $entrega_madre->oc . "|" : " ") .
                        $entrega_madre->cantidad .
                        (' (Saldo: ' . $saldo . ')')
                        : '') .
                    ($entrega_madre->emisor == null
                        ? ' ' . $entrega_madre->detalle_tipo
                        : '') .
                    '|' .
                    date(
                        'd/m/Y',
                        strtotime(
                            str_replace(
                                '/',
                                '-',
                                $entrega_madre->fecha_hora
                            )
                        )
                    ))
                : 'Primer Ingreso') .
            $html_hijas .
            '</li>';
        return $html_arbol;
    }

    public function actionEntregas_finales($codsEntregas)
    {
        //Devolver JSON con lista de entregas finales y despues vemos
        $entregas_finales = Sds_ent_entrega::getEntregasFinales($codsEntregas);
        $comapos = strpos($codsEntregas, ',');
        $codMadre =
            $comapos > 0 ? substr($codsEntregas, 0, $comapos) : $codsEntregas;
        $cantSel = 0;
        if ($codMadre > 0) {
            $cantSel = Sds_ent_entrega::findOne($codMadre)->cantidad;
            Mds_sys_log::guardarLog(
                Mds_sys_log::ACCION_CONSULTA,
                'sds_ent_entrega/entregas_finales',
                $codMadre,
                ['identregas' => explode(',', $codsEntregas)]
            );
        }
        $result = [];
        $resultEntregas = [];
        $total = 0;
        foreach ($entregas_finales as $entrega) {
            $total = $total + $entrega->cantidad;
            $entrega_agregar = [];
            //identrega,fecha_hora,dni,cantidad,observaciones
            $entrega_agregar['identrega'] = $entrega->identrega;
            $entrega_agregar['numero'] = $entrega->numero;
            $entrega_agregar['tipo'] = $entrega->detalle_tipo;
            $entrega_agregar['tipo'] = $entrega->detalle_tipo;
            $entrega_agregar['emisor'] = $entrega->nombre_emisor;
            $entrega_agregar['fecha_hora'] = $entrega->fecha_hora;
            $entrega_agregar['dni'] = $entrega->dni;
            $entrega_agregar['nombre'] = $entrega->nombre;
            $entrega_agregar['apellido'] = $entrega->apellido;
            $entrega_agregar['cantidad'] = $entrega->cantidad;
            $entrega_agregar['observaciones'] = $entrega->observaciones;
            array_push($resultEntregas, $entrega_agregar);
        }
        array_push($result, $resultEntregas);
        array_push($result, $total);
        array_push($result, $cantSel);
        return json_encode($result);
    }

    /**
     * Displays a single Sds_ent_entrega model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_ent_entrega',
            $id,
            []
        );
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => 'Sds_ent_entrega #' . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' =>
                Html::button('Close', [
                    'class' => 'btn btn-default pull-left',
                    'data-dismiss' => 'modal',
                ]) .
                    Html::a(
                        'Edit',
                        ['update', 'id' => $id],
                        ['class' => 'btn btn-primary', 'role' => 'modal-remote']
                    ),
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionView_interm($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_ent_entrega',
            $id,
            []
        );
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => 'Entrega #' . $id,
                'content' => $this->renderAjax('view_interm', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', [
                    'class' => 'btn btn-default pull-left',
                    'data-dismiss' => 'modal',
                ]),
            ];
        } else {
            return $this->render('view_interm', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Sds_ent_entrega model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate(
        $idsolicitud = null,
        $dni = null,
        $dni_frente = null,
        $dni_dorso = null,
        $exito = false,
        $fecha_hora = null
    ) {
        $request = Yii::$app->request;
        $model = new Sds_ent_entrega();
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app
                ->getResponse()
                ->redirect(['site/login', 'model' => $model]);
        }
        $ultima_entrega = Sds_ent_entrega::findBySql(
            "select * from sds_ent_entrega ent
                        where ent.emisor in (select identrega from sds_ent_entrega ent
                        where ent.receptor = (select responsable
                        from mds_seg_usuario where idusuario=" .
                $usuario->idusuario .
                "))
                        order by identrega desc limit 1"
        )->one();
        if ($fecha_hora == null) {
            $fecha_hora =
                $ultima_entrega != null
                ? $ultima_entrega->fecha_hora
                : date('Y-m-d H:i');
        }
        $model->fecha_hora = $fecha_hora;
        $model->idtipo =
            $ultima_entrega != null ? $ultima_entrega->idtipo : null;
        if ($ultima_entrega != null) {
            $numero = $ultima_entrega->numero;
            if ($numero != null) {
                $numero = $numero + 1;
                $model->numero = $numero;
            }
        }
        if ($dni != null) {
            $model->dni = $dni;
            $model->dni_frente = $dni_frente;
            $model->dni_dorso = $dni_dorso;
            $persona_existente = Sds_com_persona::find()
                ->where(['documento' => $dni])
                ->one();
            if ($persona_existente != null) {
                $model->fecha_nacimiento = $persona_existente->fecha_nacimiento;
            }
        }
        $model->idusuario = $idusuario;
        if ($idsolicitud != null) {
            $model_solicitud = Sds_ent_solicitud::findOne($idsolicitud);
            $model->dni = $model_solicitud->dni;
            $model->cantidad = $model_solicitud->cantidad;
            $model->idtipo = $model_solicitud->idtipo;
            $model->observaciones = $model_solicitud->observaciones;
            $model->idsolicitud = $idsolicitud;
        }
        if ($request->isAjax) {
            /*
                *   Process for ajax request
                */
            /*  Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    return [
                        'title' => "Nueva Entrega",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                            
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                } else if ($model->load($request->post()) && $model->save()) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Create new Sds_ent_entrega",
                        'content' => '<span class="text-success">Create Sds_ent_entrega success</span>',
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                } 
                return [
                    'title' => "Create new Sds_ent_entrega",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];             */
        } else {
            /*
             *   Process for non-ajax request
             */
            if ($model->load($request->post())) {
                $guardar = true;
                if ($model->dni == null) {
                    $model->addError(
                        'dni',
                        'Debe ingresar el dni del receptor'
                    );
                    $guardar = false;
                }
                if ($model->sexo == null) {
                    $model->addError('sexo', 'Debe ingresar el sexo');
                    $guardar = false;
                }
                if ($model->nacionalidad == null) {
                    $model->addError(
                        'nacionalidad',
                        'Debe ingresar la nacionalidad'
                    );
                    $guardar = false;
                }
                if ($model->nombre == null) {
                    $model->addError('nombre', 'Debe ingresar el nombre');
                    $guardar = false;
                }
                if ($model->apellido == null) {
                    $model->addError('apellido', 'Debe ingresar el apellido');
                    $guardar = false;
                }
                if ($model->fecha_nacimiento == null) {
                    $model->addError(
                        'fecha_nacimiento',
                        'Debe ingresar la fecha de nac.'
                    );
                    $guardar = false;
                }
                if ($model->emisor == null) {
                    $model->addError('emisor', 'Debe asignar un emisor');
                    $guardar = false;
                }
                if ($model->saldo < $model->cantidad) {
                    $model->addError(
                        'cantidad',
                        'La cantidad debe ser menor o igual al saldo informado.'
                    );
                    $guardar = false;
                }
                if ($guardar) {
                    $fecha = date(
                        'Y-m-d',
                        strtotime(str_replace('/', '-', $model->fecha_hora))
                    );
                    $model->fecha_hora = date(
                        'Y-m-d H:i',
                        strtotime(
                            str_replace(
                                '/',
                                '-',
                                $model->fecha_hora . ' ' . $model->hora
                            )
                        )
                    );
                    $tiene_numero = $this->actionHabilitar_numero(
                        $model->idtipo
                    );
                    if (
                        $tiene_numero == 1 &&
                        ($model->numero == null || $model->numero == '')
                    ) {
                        $model->addError('numero', 'Debe asignar un número');
                        $guardar = false;
                    } elseif ($tiene_numero == 1) {
                        $entrega_existente = Sds_ent_entrega::find()
                            ->where(
                                'idtipo=' .
                                    $model->idtipo .
                                    ' and numero=' .
                                    $model->numero .
                                    " and YEAR(fecha_hora)=YEAR('" .
                                    $model->fecha_hora .
                                    "')"
                            )
                            ->one();
                        if ($entrega_existente != null) {
                            $model->addError(
                                'numero',
                                'Ya existe una entrega guardada que coincide con el número ingresado'
                            );
                            $guardar = false;
                        }
                        $entrega_emisor = Sds_ent_entrega::findOne(
                            $model->emisor
                        );
                        if (
                            $entrega_emisor->numero_desde != null &&
                            $entrega_emisor->numero_hasta != null &&
                            !($model->numero >=
                                $entrega_emisor->numero_desde &&
                                $model->numero <= $entrega_emisor->numero_hasta
                            )
                        ) {
                            $entrega_correspondiente = Sds_ent_entrega::find()
                                ->where(
                                    "dni is null 
                                                                                and numero_desde is not null 
                                                                                and numero_hasta is not null
                                                                                and (numero_desde<=$model->numero and numero_hasta>=$model->numero)"
                                )
                                ->one();
                            $error_entr_corr = '';
                            if ($entrega_correspondiente != null) {
                                $error_entr_corr =
                                    ' La entrega que corresponde a dicho número es la nº ' .
                                    $entrega_correspondiente->identrega .
                                    ' - ' .
                                    date_format(
                                        date_create(
                                            $entrega_correspondiente->fecha_hora
                                        ),
                                        'd/m/Y'
                                    ) .
                                    ' - ' .
                                    Sds_com_configuracion::findOne(
                                        $entrega_correspondiente->receptor
                                    )->descripcion;
                            }
                            $model->addError(
                                'emisor',
                                'El número ingresado debe ser mayor igual a ' .
                                    $entrega_emisor->numero_desde .
                                    ' y menor igual a ' .
                                    $entrega_emisor->numero_hasta .
                                    ' (acorde a la numeración asignada al emisor seleccionado)' .
                                    $error_entr_corr
                            );

                            $guardar = false;
                        }
                    }
                    $model_com_persona = new Sds_com_persona();
                    $transaction = Yii::$app->db->beginTransaction();
                    $ban_persona_existe = 0;
                    if ($model->idpersona > 0) {
                        $ban_persona_existe = 0;
                        $model_com_persona = Sds_com_persona::findOne(
                            $model->idpersona
                        );
                    }
                    $model_com_persona->documento_tipo = '83';
                    $model_com_persona->fecha_nacimiento = date(
                        'Y-m-d',
                        strtotime(
                            str_replace('/', '-', $model->fecha_nacimiento)
                        )
                    );
                    $model_com_persona->documento = $model->dni;
                    $model_com_persona->nacionalidad = $model->nacionalidad;
                    $model_com_persona->genero = $model->sexo;
                    $model_com_persona->nombre = $model->nombre;
                    $model_com_persona->apellido = $model->apellido;
                    $model_com_persona->conviviente = 0;
                    $tmpfile = UploadedFile::getInstance(
                        $model,
                        'archivo_dni_frente'
                    );
                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;
                        $nombre = $model->dni . '_frente.' . $extension;
                        //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                        $ruta = 'uploads/entregas/';
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0777, true);
                        }
                        $model->dni_frente = $ruta . $nombre;
                        $tmpfile->saveAs($model->dni_frente);
                    }
                    $tmpfile = UploadedFile::getInstance(
                        $model,
                        'archivo_dni_dorso'
                    );
                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;
                        $nombre = $model->dni . '_dorso.' . $extension;
                        //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                        $ruta = 'uploads/entregas/';
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0777, true);
                        }
                        $model->dni_dorso = $ruta . $nombre;
                        $tmpfile->saveAs($model->dni_dorso);
                    }
                    $tmpfile = UploadedFile::getInstance(
                        $model,
                        'archivo_acta'
                    );
                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;
                        $nombre =
                            $fecha .
                            '_' .
                            $model->idtipo .
                            '_' .
                            $model->dni .
                            '.' .
                            $extension;
                        //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                        $ruta = 'uploads/entregas/actas/';
                        if (!file_exists($ruta)) {
                            mkdir($ruta, 0777, true);
                        }
                        $model->acta = $ruta . $nombre;
                        $tmpfile->saveAs($model->acta);
                    }
                    if ($guardar) {
                        if (!$model_com_persona->save()) {
                            $model->addError(
                                'dni',
                                'No se ha podido guardar la persona. Lo siento.'
                            );
                            $transaction->rollBack();
                        } else {
                            $model->idpersona = $model_com_persona->idpersona;
                            if ($model->save()) {
                                if ($model->idsolicitud != null) {
                                    $model_solicitud = Sds_ent_solicitud::findOne(
                                        $model->idsolicitud
                                    );
                                    $model_solicitud->estado =
                                        Sds_ent_solicitud::ESTADO_ENTREGADO;
                                    $model_solicitud->save();
                                }
                                $transaction->commit();
                                Mds_sys_log::guardarLog(
                                    Mds_sys_log::ACCION_NUEVO,
                                    'sds_ent_entrega',
                                    $model->identrega,
                                    $model->getAttributes()
                                );
                                if ($ban_persona_existe == 1) {
                                    Mds_sys_log::guardarLog(
                                        Mds_sys_log::ACCION_EDITAR,
                                        'sds_com_persona',
                                        $model_com_persona->idpersona,
                                        $model->getAttributes()
                                    );
                                } else {
                                    Mds_sys_log::guardarLog(
                                        Mds_sys_log::ACCION_NUEVO,
                                        'sds_com_persona',
                                        $model_com_persona->idpersona,
                                        $model->getAttributes()
                                    );
                                }
                                return $this->redirect([
                                    'create',
                                    'exito' => true,
                                    'fecha_hora' => $model->fecha_hora,
                                ]);
                            }
                        }
                    }
                }
            }
            return $this->render('create', [
                'model' => $model,
                'exito' => $exito,
            ]);
        }
    }

    public function actionMigrar_dni()
    {
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_EDITAR,
            'sds_ent_entrega/migrar_dni',
            null,
            []
        );
        $entregas = Sds_ent_entrega::find()
            ->where(
                "dni_frente is not null
                                        and dni_frente like '%base64%'"
            )
            ->limit(50)
            ->all();
        $entregas_migradas = [];
        foreach ($entregas as $entrega) {
            $image_parts = explode(';base64,', $entrega->dni_frente);
            $image_type_aux = explode('image/', $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $nombre = $entrega->dni . '_frente.' . $image_type;
            //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
            $ruta = 'uploads/entregas';
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $entrega->dni_frente = $ruta . '/' . $nombre;
            file_put_contents($entrega->dni_frente, $image_base64);
            $image_parts = explode(';base64,', $entrega->dni_dorso);
            $image_type_aux = explode('image/', $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $nombre = $entrega->dni . '_dorso.' . $image_type;
            //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
            $ruta = 'uploads/entregas';
            if (!file_exists($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $entrega->dni_dorso = $ruta . '/' . $nombre;
            file_put_contents($entrega->dni_dorso, $image_base64);
            if ($entrega->save()) {
                array_push($entregas_migradas, $entrega);
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $entregas_migradas;
    }

    public function actionCreate_interm(
        $estado,
        $exito = false,
        $fecha_hora = null
    ) {
        $request = Yii::$app->request;
        $model = new Sds_ent_entrega();
        $model->estado = $estado;
        $model->interior = 0;
        if ($fecha_hora == null) {
            $fecha_hora = date('Y-m-d H:i');
        }
        $model->fecha_hora = $fecha_hora;
        $user = Yii::$app->user->identity;
        $idusuario = $user != null ? $user->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app
                ->getResponse()
                ->redirect(['site/login', 'model' => $model]);
        }
        $model->idusuario = $user->idusuario;
        /*
         *   Process for non-ajax request
         */
        if ($model->load($request->post())) {
            $fecha = date(
                'Y-m-d',
                strtotime(str_replace('/', '-', $model->fecha_hora))
            );
            $model->fecha_hora = date(
                'Y-m-d H:i',
                strtotime(
                    str_replace(
                        '/',
                        '-',
                        $model->fecha_hora . ' ' . $model->hora
                    )
                )
            );
            $guardar = true;
            if ($model->receptor == null) {
                $model->addError('receptor', 'Debe asignar un receptor');
                $guardar = false;
            }
            if ($model->emisor == null) {
                $model->addError('emisor', 'Debe asignar un emisor');
                $guardar = false;
            }
            if ($model->emisor <= 0) {
                if ($model->proveedor == null) {
                    $model->addError('proveedor', 'Debe asignar un proveedor');
                    $guardar = false;
                }
                if ($model->oc == null || strlen(trim($model->oc)) == 0) {
                    $model->addError('oc', 'Debe asignar un número de OC');
                    $guardar = false;
                }
                $model->emisor = null;
            } else {
                if ($model->saldo < $model->cantidad) {
                    $model->addError(
                        'cantidad',
                        'La cantidad debe ser menor o igual al saldo informado.'
                    );
                    $guardar = false;
                }
            }
            $tmpfile = UploadedFile::getInstance($model, 'archivo_acta');
            if (isset($tmpfile)) {
                $extension = $tmpfile->extension;
                $nombre =
                    $fecha .
                    '_' .
                    $model->idtipo .
                    '_' .
                    $model->receptor .
                    '.' .
                    $extension;
                //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                $ruta = 'uploads/entregas/actas/';
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0777, true);
                }
                $model->acta = $ruta . $nombre;
                $tmpfile->saveAs($model->acta);
            }
            if ($guardar) {
                $entrega_existente = null;
                if ($model->tiene_numero) {
                    $entrega_existente = Sds_ent_entrega::find()
                        ->where(
                            'idtipo=' .
                                $model->idtipo .
                                " and 
                                    ((numero_desde>=" .
                                $model->numero_desde .
                                ' and numero_desde<=' .
                                $model->numero_hasta .
                                ")
                                    or (numero_hasta<=" .
                                $model->numero_hasta .
                                ' and numero_hasta>=' .
                                $model->numero_desde .
                                ")
                                    or (" .
                                $model->numero_desde .
                                '>=numero_desde and ' .
                                $model->numero_desde .
                                "<=numero_hasta)
                                    or (" .
                                $model->numero_hasta .
                                '<=numero_hasta and ' .
                                $model->numero_hasta .
                                ">=numero_desde))
                                    and YEAR(fecha_hora)=YEAR('" .
                                $model->fecha_hora .
                                "')"
                        )
                        ->one();
                }
                if ($entrega_existente != null) {
                    $model->addError(
                        'numero_desde',
                        'Ya existe una entrega guardada que coincide con el rango de números ingresados.'
                    );
                    $model->addError(
                        'numero_hasta',
                        'Entrega Nº ' .
                            $entrega_existente->identrega .
                            ' - Desde: ' .
                            $entrega_existente->numero_desde .
                            ' / Hasta: ' .
                            $entrega_existente->numero_hasta
                    );
                    $guardar = false;
                }
                if ($model->save()) {
                    Mds_sys_log::guardarLog(
                        Mds_sys_log::ACCION_NUEVO,
                        'sds_ent_entrega',
                        $model->identrega,
                        $model->getAttributes()
                    );
                    return $this->redirect([
                        'create_interm',
                        'estado' => $estado,
                        'exito' => true,
                        'fecha_hora' => $model->fecha_hora,
                    ]);
                }
            }
        }
        return $this->render('create', [
            'model' => $model,
            'interm' => true,
            'exito' => $exito,
        ]);
    }

    /**
     * Updates an existing Sds_ent_entrega model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->dni_frente == null && $model->dni_dorso == null) {
            $model->dni_frente = Sds_ent_entrega::find()
                ->where('dni_frente is not null')
                ->limit(1)
                ->one()->dni_frente;
            $model->dni_dorso = $model->dni_frente;
        }
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            /* Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update Sds_ent_entrega #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $model->fecha_hora = date('Y-m-d H:i', strtotime(str_replace('/', '-', $model->fecha_entrega . ' ' . $model->hora)));
                $model->archivo_dni_frente = UploadedFile::getInstance($model, 'archivo_dni_frente');
                $model->detalle = $model->archivo_dni_frente;
                $tmpfile_contents = file_get_contents($model->archivo_dni_frente);
                $model->dni_frente = base64_encode($tmpfile_contents);                
                if ($model->save()) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Sds_ent_entrega #" . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            }
            return [
                'title' => "Actualizar Entrega #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ]; */
        } else {
            if ($model->load($request->post())) {
                $fecha = date(
                    'Y-m-d',
                    strtotime(str_replace('/', '-', $model->fecha_hora))
                );
                $model->fecha_hora = date(
                    'Y-m-d H:i',
                    strtotime(
                        str_replace(
                            '/',
                            '-',
                            $model->fecha_hora . ' ' . $model->hora
                        )
                    )
                );
                $guardar = true;
                if ($model->dni == null) {
                    $model->addError(
                        'dni',
                        'Debe ingresar el dni del receptor'
                    );
                    $guardar = false;
                }
                if ($model->sexo == null) {
                    $model->addError('sexo', 'Debe ingresar el sexo');
                    $guardar = false;
                }
                if ($model->nacionalidad == null) {
                    $model->addError(
                        'nacionalidad',
                        'Debe ingresar la nacionalidad'
                    );
                    $guardar = false;
                }
                if ($model->nombre == null) {
                    $model->addError('nombre', 'Debe ingresar el nombre');
                    $guardar = false;
                }
                if ($model->apellido == null) {
                    $model->addError('apellido', 'Debe ingresar el apellido');
                    $guardar = false;
                }
                if ($model->fecha_nacimiento == null) {
                    $model->addError(
                        'fecha_nacimiento',
                        'Debe ingresar la fecha de nac.'
                    );
                    $guardar = false;
                }
                if ($model->emisor == null) {
                    $model->addError('emisor', 'Debe asignar un emisor');
                    $guardar = false;
                }
                $tiene_numero = $this->actionHabilitar_numero($model->idtipo);
                if (
                    $tiene_numero == 1 &&
                    ($model->numero == null || $model->numero == '')
                ) {
                    $model->addError('numero', 'Debe asignar un número');
                    $guardar = false;
                } elseif ($tiene_numero == 1) {
                    $entrega_existente = Sds_ent_entrega::find()
                        ->where(
                            'identrega!=' .
                                $model->identrega .
                                ' and idtipo=' .
                                $model->idtipo .
                                ' and numero=' .
                                $model->numero .
                                " and YEAR(fecha_hora)=YEAR('" .
                                $model->fecha_hora .
                                "')"
                        )
                        ->one();
                    if ($entrega_existente != null) {
                        $model->addError(
                            'numero',
                            'Ya existe una entrega guardada que coincide con el número ingresado'
                        );
                        $guardar = false;
                    }
                    $entrega_emisor = Sds_ent_entrega::findOne($model->emisor);
                    if (
                        $entrega_emisor->numero_desde != null &&
                        $entrega_emisor->numero_hasta != null &&
                        !($model->numero >= $entrega_emisor->numero_desde &&
                            $model->numero <= $entrega_emisor->numero_hasta
                        )
                    ) {
                        $entrega_correspondiente = Sds_ent_entrega::find()
                            ->where(
                                "dni is null 
                                                                            and numero_desde is not null 
                                                                            and numero_hasta is not null
                                                                            and (numero_desde<=$model->numero and numero_hasta>=$model->numero)"
                            )
                            ->one();
                        $error_entr_corr = '';
                        if ($entrega_correspondiente != null) {
                            $error_entr_corr =
                                ' La entrega que corresponde a dicho número es la nº ' .
                                $entrega_correspondiente->identrega .
                                ' - ' .
                                date_format(
                                    date_create(
                                        $entrega_correspondiente->fecha_hora
                                    ),
                                    'd/m/Y'
                                ) .
                                ' - ' .
                                Sds_com_configuracion::findOne(
                                    $entrega_correspondiente->receptor
                                )->descripcion;
                        }
                        $model->addError(
                            'emisor',
                            'El número ingresado debe ser mayor igual a ' .
                                $entrega_emisor->numero_desde .
                                ' y menor igual a ' .
                                $entrega_emisor->numero_hasta .
                                ' (acorde a la numeración asignada al emisor seleccionado)' .
                                $error_entr_corr
                        );
                        $guardar = false;
                    }
                }
                if ($model->saldo < $model->cantidad) {
                    $model->addError(
                        'cantidad',
                        'La cantidad debe ser menor o igual al saldo informado.'
                    );
                    $guardar = false;
                }
                $model_com_persona = new Sds_com_persona();
                $transaction = Yii::$app->db->beginTransaction();
                $ban_persona_existe = 0;
                if ($model->idpersona > 0) {
                    $ban_persona_existe = 0;
                    $model_com_persona = Sds_com_persona::findOne(
                        $model->idpersona
                    );
                }
                $model_com_persona->documento_tipo = '83';
                $model_com_persona->fecha_nacimiento = date(
                    'Y-m-d',
                    strtotime(str_replace('/', '-', $model->fecha_nacimiento))
                );
                $model_com_persona->documento = $model->dni;
                $model_com_persona->nacionalidad = $model->nacionalidad;
                $model_com_persona->genero = $model->sexo;
                $model_com_persona->nombre = $model->nombre;
                $model_com_persona->apellido = $model->apellido;
                $model_com_persona->conviviente = 0;
                $tmpfile = UploadedFile::getInstance(
                    $model,
                    'archivo_dni_frente'
                );
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nombre = $model->dni . '_frente.' . $extension;
                    //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                    $ruta = 'uploads/entregas/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->dni_frente = $ruta . $nombre;
                    $tmpfile->saveAs($model->dni_frente);
                }
                $tmpfile = UploadedFile::getInstance(
                    $model,
                    'archivo_dni_dorso'
                );
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nombre = $model->dni . '_dorso.' . $extension;
                    //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                    $ruta = 'uploads/entregas/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->dni_dorso = $ruta . $nombre;
                    $tmpfile->saveAs($model->dni_dorso);
                }
                $tmpfile = UploadedFile::getInstance($model, 'archivo_acta');
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;
                    $nombre =
                        $fecha .
                        '_' .
                        $model->idtipo .
                        '_' .
                        $model->dni .
                        '.' .
                        $extension;
                    //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                    $ruta = 'uploads/entregas/actas/';
                    if (!file_exists($ruta)) {
                        mkdir($ruta, 0777, true);
                    }
                    $model->acta = $ruta . $nombre;
                    $tmpfile->saveAs($model->acta);
                }
                if ($guardar) {
                    if (!$model_com_persona->save()) {
                        $model->addError(
                            'dni',
                            'No se ha podido guardar la persona. Lo siento.'
                        );
                        $transaction->rollBack();
                        //return print_r($model_com_persona, true);
                    } else {
                        $model->idpersona = $model_com_persona->idpersona;
                        if ($model->save(false)) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(
                                Mds_sys_log::ACCION_EDITAR,
                                'sds_ent_entrega',
                                $model->identrega,
                                $model->getAttributes()
                            );
                            if ($ban_persona_existe == 1) {
                                Mds_sys_log::guardarLog(
                                    Mds_sys_log::ACCION_EDITAR,
                                    'sds_com_persona',
                                    $model_com_persona->idpersona,
                                    $model->getAttributes()
                                );
                            } else {
                                Mds_sys_log::guardarLog(
                                    Mds_sys_log::ACCION_NUEVO,
                                    'sds_com_persona',
                                    $model_com_persona->idpersona,
                                    $model->getAttributes()
                                );
                            }
                            return $this->redirect(['index']);
                        }
                    }
                }
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate_interm($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->estado = Sds_ent_entrega::ESTADO_INTERMEDIA;
        if ($model->emisor == null) {
            $model->emisor = 0;
            $model->estado = Sds_ent_entrega::ESTADO_INICIAL;
        }
        if ($model->load($request->post())) {
            $fecha = date(
                'Y-m-d',
                strtotime(str_replace('/', '-', $model->fecha_hora))
            );
            $model->fecha_hora = date(
                'Y-m-d H:i',
                strtotime(
                    str_replace(
                        '/',
                        '-',
                        $model->fecha_hora . ' ' . $model->hora
                    )
                )
            );
            $guardar = true;
            $idtipo = $model->idtipo;
            $tiene_numero =
                $idtipo > 0 ? Sds_ent_tipo::findOne($idtipo)->tiene_numero : 0;
            if ($tiene_numero == 1) {
                if (
                    $model->numero_desde == null ||
                    $model->numero_desde == ''
                ) {
                    //$model->addError("numero_desde", "Debe asignar el número desde");
                    //$guardar = false;
                } elseif (
                    $model->numero_hasta == null ||
                    $model->numero_hasta == ''
                ) {
                    //$model->addError("numero_hasta", "Debe asignar el número hasta");
                    //$guardar = false;
                } elseif (is_int($model->numero_desde)) {
                    $model->addError(
                        'numero_desde',
                        'El número desde debe ser numérico y sin decimales.'
                    );
                    $guardar = false;
                } elseif (is_int($model->numero_hasta)) {
                    $model->addError(
                        'numero_hasta',
                        'El número hasta debe ser numérico y sin decimales.'
                    );
                    $guardar = false;
                } elseif ($model->numero_desde > $model->numero_hasta) {
                    $model->addError(
                        'numero_hasta',
                        'El número hasta debe ser mayor que el desde.'
                    );
                    $guardar = false;
                } else {
                    $entrega_existente = Sds_ent_entrega::find()
                        ->where(
                            "idtipo=$idtipo and 
                            ((numero_desde>=" .
                                $model->numero_desde .
                                ' and numero_desde<=' .
                                $model->numero_hasta .
                                ")
                            or (numero_hasta<=" .
                                $model->numero_hasta .
                                ' and numero_hasta>=' .
                                $model->numero_desde .
                                ")
                            or (" .
                                $model->numero_desde .
                                '>=numero_desde and ' .
                                $model->numero_desde .
                                "<=numero_hasta)
                            or (" .
                                $model->numero_hasta .
                                '<=numero_hasta and ' .
                                $model->numero_hasta .
                                ">=numero_desde))
                            and YEAR(fecha_hora)=YEAR('" .
                                $model->fecha_hora .
                                "') and 
                            (identrega!=" .
                                $model->identrega .
                                ' && identrega!=' .
                                $model->emisor .
                                ')'
                        )
                        ->one();
                    if ($entrega_existente != null) {
                        $model->addError(
                            'numero_desde',
                            'Ya existe una entrega guardada que coincide con el rango de números ingresados.'
                        );
                        $model->addError(
                            'numero_hasta',
                            'Entrega Nº ' .
                                $entrega_existente->identrega .
                                ' - Desde: ' .
                                $entrega_existente->numero_desde .
                                ' / Hasta: ' .
                                $entrega_existente->numero_hasta
                        );
                        $guardar = false;
                    }
                }
            }
            if ($model->receptor == null) {
                $model->addError('receptor', 'Debe asignar un receptor');
                $guardar = false;
            }
            if ($model->emisor == null) {
                $model->addError('emisor', 'Debe asignar un emisor');
                $guardar = false;
            }
            if ($model->saldo < $model->cantidad) {
                $model->addError(
                    'cantidad',
                    'La cantidad debe ser menor o igual al saldo informado.'
                );
                $guardar = false;
            }
            if ($model->emisor <= 0) {
                $model->emisor = null;
                if ($model->proveedor == null) {
                    $model->addError('proveedor', 'Debe asignar un proveedor');
                    $guardar = false;
                }
                if ($model->oc == null || strlen(trim($model->oc)) == 0) {
                    $model->addError('oc', 'Debe asignar un número de OC');
                    $guardar = false;
                }
            } else {
                if ($model->saldo < $model->cantidad) {
                    $model->addError(
                        'cantidad',
                        'La cantidad debe ser menor o igual al saldo informado.'
                    );
                    $guardar = false;
                }
            }
            $tmpfile = UploadedFile::getInstance($model, 'archivo_acta');
            if (isset($tmpfile)) {
                $extension = $tmpfile->extension;
                $nombre =
                    $fecha .
                    '_' .
                    $model->idtipo .
                    '_' .
                    $model->receptor .
                    '.' .
                    $extension;
                //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                $ruta = 'uploads/entregas/actas/';
                if (!file_exists($ruta)) {
                    mkdir($ruta, 0777, true);
                }
                $model->acta = $ruta . $nombre;
                $tmpfile->saveAs($model->acta);
            }
            if ($guardar) {
                if ($model->save(false)) {
                    Mds_sys_log::guardarLog(
                        Mds_sys_log::ACCION_EDITAR,
                        'sds_ent_entrega',
                        $model->identrega,
                        $model->getAttributes()
                    );
                    return $this->redirect([
                        'index',
                        'estado' =>
                        $model->emisor == null
                            ? Sds_ent_entrega::ESTADO_INICIAL
                            : Sds_ent_entrega::ESTADO_INTERMEDIA,
                    ]);
                }
            }
            $model->addError('idtipo', print_r($model->getErrors(), true));
        }
        return $this->render('update', [
            'model' => $model,
            'interm' => true,
        ]);
    }

    /**
     * Delete an existing Sds_ent_entrega model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $transaction = Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            $model_solicitud = Sds_ent_solicitud_intermedia::findOne(
                $model->idsolicitudintermedia
            );
            if ($model_solicitud != null && $model_solicitud->delete()) {
                Mds_sys_log::guardarLog(
                    Mds_sys_log::ACCION_ELIMINAR,
                    'sds_ent_solicitud_intermedia',
                    $id,
                    $model_solicitud->getAttributes()
                );
            }
            Mds_sys_log::guardarLog(
                Mds_sys_log::ACCION_ELIMINAR,
                'sds_ent_entrega',
                $id,
                $model->getAttributes()
            );
            $transaction->commit();
        }
        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceClose' => true,
                'forceReload' => '#crud-datatable-pjax',
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect([
                'index',
                'estado' =>
                $model->emisor == null
                    ? Sds_ent_entrega::ESTADO_INICIAL
                    : ($model->dni == null
                        ? Sds_ent_entrega::ESTADO_INTERMEDIA
                        : Sds_ent_entrega::ESTADO_FINAL),
            ]);
        }
    }

    /**
     * Delete multiple existing Sds_ent_entrega model.
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
            return [
                'forceClose' => true,
                'forceReload' => '#crud-datatable-pjax',
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Sds_ent_entrega model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_ent_entrega the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_ent_entrega::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(
                'The requested page does not exist.'
            );
        }
    }

    public function actionReporte_entrega($identrega)
    {
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_ent_entrega/reporte_entrega',
            $identrega,
            []
        );
        $content = $this->renderPartial('reporte_entrega', [
            'identrega' => $identrega,
        ]); // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',

            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'ACTA DE ENTREGA',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }

    public function actionReporte_entregas($identregas, $titulo)
    {
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_ent_entrega/reporte_entregas',
            $identregas,
            []
        );
        $content = $this->renderPartial('reporte_entregas', [
            'identregas' => $identregas,
            'titulo' => $titulo,
        ]); // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',

            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'RESUMEN DE ENTREGAS FINALES',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }

    public function actionReporte_rendicion($identregas, $externo, $detalle)
    {
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_ent_entrega/reporte_rendicion',
            $identregas,
            []
        );
        $content = $this->renderPartial('reporte_rendicion', [
            'identregas' => $identregas,
            'detalle' => $detalle,
            'externo' => $externo,
        ]); // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',

            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'RENDICIÓN DE ENTREGAS',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }

    public function actionReporte_rendicion_tc(
        $identregas,
        $externo,
        $intermedias
    ) {
        $identregas = Sds_ent_entrega::getArbolIds($identregas, -1, $externo);
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_ent_entrega/reporte_rendicion_tc',
            $identregas,
            []
        );
        $content = $this->renderPartial('reporte_rendicion_tc', [
            'identregas' => $identregas,
            'intermedias' => $intermedias,
        ]); // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',

            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'TRIBUNAL DE CUENTAS',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }

    public function actionReporte_entrega_interm($identrega)
    {
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_ent_entrega/reporte_entrega_interm',
            $identrega,
            []
        );
        $content = $this->renderPartial('reporte_entrega_interm', [
            'identrega' => $identrega,
        ]); // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'ACTA DE ENTREGA',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }

    public function actionReporte_entrega_remito()
    {
        $request = Yii::$app->request;
        $pks = (array) $request->post('selection');
        if (empty($pks)) {
            $searchModel = new Sds_ent_entregaSearch();
            $searchModel->estado = Sds_ent_entrega::ESTADO_INTERMEDIA;
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams
            );
            Mds_sys_log::guardarLog(
                Mds_sys_log::ACCION_CONSULTA,
                'sds_ent_entrega',
                null,
                []
            );
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'error' => '',
            ]);
        }
        $identregas_str = implode(',', $pks);
        $entregas_validar = Sds_ent_entrega::find()
            ->where("identrega in ($identregas_str)")
            ->all();
        $fecha_entregas = null;
        $responsable = null;
        $valido = true;
        foreach ($entregas_validar as $entrega) {
            //valido que la fecha y responsable sean iguales.
            $fecha_entrega = date_format(
                date_create($entrega->fecha_hora),
                'd/m/Y'
            );
            if ($fecha_entregas == null) {
                $fecha_entregas = $fecha_entrega;
            }
            if ($responsable == null) {
                $responsable = $entrega->receptor;
            }
            if (
                $fecha_entrega != $fecha_entregas ||
                $responsable != $entrega->receptor
            ) {
                $valido = false;
            }
        }
        if (!$valido) {
            return $this->redirect([
                'index',
                'estado' => Sds_ent_entrega::ESTADO_INTERMEDIA,
                'error' =>
                'Las entregas seleccionadas deben ser de la misma fecha y del mismo receptor.',
            ]);
        }
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_ent_entrega/reporte_entrega_remito',
            null,
            ['entregas' => $pks]
        );
        $content = $this->renderPartial('reporte_entrega_remito', [
            'ids' => $identregas_str,
        ]); // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'ACTA DE ENTREGA',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }

    public function actionHabilitar_numero($idtipo = -1)
    {
        return $idtipo > 0 ? Sds_ent_tipo::findOne($idtipo)->tiene_numero : 0;
    }

    public function actionCmb_emisor(
        $idtipo = -1,
        $fecha_entrega = null
    ) {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app
                ->getResponse()
                ->redirect(['site/login', 'model' => $model]);
        }
        $entregas_emisor = Sds_ent_entrega::findBySql(
            "select * from sds_ent_entrega ent
                                                    join (select if(haber>0,(select emisor
                                                    from sds_ent_entrega
                                                    entemisor where entemisor.identrega=ctacte.identrega),ctacte.identrega) codEnt,sum(debe-haber) saldo
                                                                            from view_sds_ent_cta_cte ctacte
                                                                            group by codEnt having saldo>0) temp on temp.codEnt=ent.identrega
                                                            where idtipo=$idtipo
                                                            and ent.fecha_cierre is null
                                                            and ent.receptor = (select responsable 
                                                            from mds_seg_usuario where idusuario=" .
                $usuario->idusuario .
                ")
                                                            and DATEDIFF(fecha_hora,'$fecha_entrega')<=0"
        )->all();
        $cmbEntregas = '';
        if (sizeof($entregas_emisor) > 0) {
            /* $fecha_mas_cercana = null;
            if ($fecha_entrega != null) {
                foreach ($entregas_emisor as $entrega) {
                    $fecha_entrega_aux = date("d-m-Y ", strtotime($fecha_entrega));
                    $fecha_entrega_emisor_aux = date("d-m-Y ", strtotime($entrega->fecha_hora));
                    $fecha_mas_cercana = $fecha_mas_cercana != null ? date("d-m-Y ", strtotime($fecha_mas_cercana)) : null;
                    if ($fecha_mas_cercana == null || ($fecha_entrega_emisor_aux <= $fecha_entrega_aux
                        && $fecha_entrega_emisor_aux > $fecha_mas_cercana)) {
                        $fecha_mas_cercana = $entrega->fecha_hora;
                    }
                } */
            $marcar_fecha = false;
            $dia_menor = 31;
            foreach ($entregas_emisor as $entrega) {
                $fc = date_create($entrega->fecha_hora);
                $fc =
                    date_format($fc, 'd/m/Y') .
                    ' - Cant: ' .
                    $entrega->cantidad;
                $marcar_fecha =
                    date('m-Y ', strtotime($entrega->fecha_hora)) ==
                    date('m-Y ', strtotime($fecha_entrega)) &&
                    date('d', strtotime($entrega->fecha_hora)) < $dia_menor;
                if ($marcar_fecha) {
                    $dia_menor = date('d', strtotime($entrega->fecha_hora));
                }
                $receptor = Sds_com_configuracion::findOne($entrega->receptor);
                $cmbEntregas =
                    $cmbEntregas .
                    "<option value='" .
                    $entrega->identrega .
                    "' " .
                    ($marcar_fecha ? "selected='selected'" : '') .
                    '>' .
                    $entrega->toString() .
                    '</option>';
            }
        }
        return $cmbEntregas;
    }

    public function actionSet_fecha_numero($idtipo = -1, $idemisor = -1)
    {
        if ($idtipo > 0 && $idemisor > 0) {
            $ultima_entrega = Sds_ent_entrega::findBySql(
                "select * from sds_ent_entrega ent
                        where ent.idtipo=$idtipo and 
                        ent.emisor = $idemisor
                        order by identrega desc limit 1"
            )->one();
            $fecha_hora =
                $ultima_entrega != null
                ? $ultima_entrega->fecha_hora
                : date('Y-m-d H:i');
            $numero = null;
            if ($ultima_entrega != null) {
                $numero = $ultima_entrega->numero;
                if ($numero != null) {
                    $numero = $numero + 1;
                }
            }
            return json_encode([
                'fecha_hora' => $fecha_hora,
                'numero' => $numero,
            ]);
        }
    }

    public function actionGet_saldo($idtipo = -1, $identrega = -1)
    {
        return Sds_ent_saldo::getSaldo($idtipo, $identrega);
    }

    public function actionDni_frente_dorso($dni, $identrega = 0)
    {
        //print_r($ultima_entrega_fotos);
        if ($identrega == 0) {
            $ultima_entrega_fotos = Sds_ent_entrega::findBySql(
                "select * from sds_ent_entrega 
            where dni=$dni and dni_frente like '%uploads%' 
            order by fecha_hora desc limit 1"
            )->one();
            if ($ultima_entrega_fotos == null) {
                $dni_frente = 'img/dni_sin_foto.png';
                $dni_dorso = 'img/dni_sin_foto.png';
            } else {
                $dni_frente = $ultima_entrega_fotos->dni_frente;
                $dni_dorso = $ultima_entrega_fotos->dni_dorso;
            }
            return $this->redirect([
                'create',
                'idsolicitud' => null,
                'dni' => $dni,
                'dni_frente' => $dni_frente,
                'dni_dorso' => $dni_dorso,
            ]);
        }
    }
}
