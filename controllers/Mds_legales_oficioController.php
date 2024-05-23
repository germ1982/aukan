<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_legales_archivo;
use app\models\Mds_legales_derivacion;
use app\models\Mds_legales_derivacion_area;
use app\models\Mds_legales_oficio;
use app\models\Mds_legales_oficioSearch;
use app\models\Mds_legales_respuesta;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_legales_respuesta_estado;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_legales_oficio_vinculado;
use app\models\Mds_legales_supervisor_area;
use app\models\Mds_legales_caratula;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\UploadedFile;
use app\models\Mds_sys_log;
use app\models\Sds_com_persona;
use DateTime;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\helpers\Html;
use kartik\mpdf\Pdf;



class Mds_legales_oficioController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => [
                    'index',
                    'create',
                    'store',
                    'update',
                    'delete',
                    'reactivate',
                    'vinculacion',
                    'vinculacionenviar',
                    'dashboard',
                    'dashboard_caratula',
                    'requerimientosvencidos',
                    'respuestasconobservaciones',
                    'respuestas',
                    'reporte',
                    'rechazaroficio',
                    'rechazaroficiostore',
                    'rederivar',
                    'rederivarstore',
                    'requerimientosdevueltos',
                    'view',
                    'agregar_derivaciones',
                    'store_agregar_derivaciones',
                    'store_agregar_archivos',
                    'guardararchivotemporal'
                ],
                'rules' => [
                    [
                        'actions' => ['create', 'store'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_CREAR_REQUERIMIENTO, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_EDITAR_REQUERIMIENTO, Mds_seg_item::MODULO_LEGALES_DERIVAR_A_USUARIOS, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_ELIMINAR_REQUERIMIENTO, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['reactivate', 'agregar_derivaciones', 'store_agregar_derivaciones', 'store_agregar_archivos'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['vinculacion', 'vinculacionenviar'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_ENVIAR_RESPUESTA, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['dashboard', 'dashboard_caratula'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_SEGUIMIENTO, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['index', 'requerimientosvencidos', 'view'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_VER_REQUERIMIENTO, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['respuestas'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_ACCIONAR_RESPUESTA, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL, Mds_seg_item::MODULO_LEGALES_RESPONDER_REQUERIMIENTO
                        ],
                    ],
                    [
                        'actions' => ['respuestasconobservaciones'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_RESPUESTAS_CON_OBSERVACION, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['reporte'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_IMPRIMIR_REPORTE, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['rechazaroficio', 'rechazaroficiostore'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_RECHAZAR_REQUERIMIENTO, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['rederivar', 'rederivarstore'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_DERIVAR_A_USUARIOS, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['requerimientosdevueltos'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_CREAR_REQUERIMIENTO, Mds_seg_item::MODULO_LEGALES_ACCIONAR_RESPUESTA, Mds_seg_item::MODULO_LEGALES_ADMIN_GENERAL
                        ],
                    ],
                    [
                        'actions' => ['guardararchivotemporal'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_LEGALES_TEMP_FILE
                        ],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(
        $idArea = null,
        $idUsuario = null,
        $fechaInicio = null,
        $fechaFin = null,
        $idEstado = null,
        $tipo = null,
        $idLegalesCaratula = null,
        $notificacion = null
    ) {
        if (Yii::$app->user && Yii::$app->user->identity && Yii::$app->user->identity->idcontacto) {
            $arrayIdRequerimientos = array();
            $valuesArrayIdRequerimientos = null; //Es importnate que se setee en null ya que si no viene el parametro notificacion en la url, en el search no filtro por valuesArrayIdRequerimientos
            $title = 'Requerimientos';
            $subtitle = $title;

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if ($notificacion) {
                $valuesArrayIdRequerimientos = array();
                $_SESSION["MdsLegalesOficioIndexParametroNotificacion"] = $notificacion;
                $calledFrom = 'index';

                switch ($notificacion) {
                    case 'oficiosConRespuestasVistas':
                        $arrayIdRequerimientos = Mds_legales_oficio::getOficiosConRespuestasVistas($calledFrom);
                        $title .= ' (Respuestas vistas)';
                        break;
                    case 'requerimientosConObservacionFinal':
                        $hasRolReceptor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_RECEPTOR);
                        $hasRolSupervisor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR);
                        $arrayIdRequerimientos = Mds_legales_oficio::getRequerimientosConObservacionFinal($hasRolReceptor, $hasRolSupervisor);
                        $title .= ' (Respuestas enviadas con observación final)';
                        break;
                    case 'vencimientoPlazoOficios':
                        $arrayIdRequerimientos = Mds_legales_oficio::getVencimientoPlazoOficios($calledFrom);
                        $title .= ' (Próximas a vencer / vencidas recientemente)';
                        break;
                    case 'oficiosSinRespuestas':
                        $arrayIdRequerimientos = Mds_legales_oficio::getOficiosSinRespuesta($calledFrom);
                        $title .= ' (Requieren respuesta)';
                        break;
                    case 'respuestasObservadas':
                        $arrayIdRequerimientos = Mds_legales_oficio::getRespuestasObservadas($calledFrom);
                        $title .= ' (Respuestas observadas)';
                        break;
                    case 'respuestasSinSupervisar':
                        $arrayIdRequerimientos = Mds_legales_oficio::getRespuestasSinSupervisar($calledFrom);
                        $title .= ' (Requieren supervisión)';
                        break;
                    case 'oficiosRespuestasAprobadasNoEnviadas':
                        $arrayIdRequerimientos = Mds_legales_oficio::getOficiosRespuestasAprobadasNoEnviadas($calledFrom);
                        $title .= ' (Respuestas no supervisadas)';
                        break;
                    case 'oficiosSinDerivarAUsuarios':
                        $arrayIdRequerimientos = Mds_legales_oficio::getOficiosSinDerivarAUsuarios($calledFrom);
                        $title .= ' (Requieren derivación)';
                        break;
                    case 'oficiosParaReDerivar':
                        $arrayIdRequerimientos = Mds_legales_oficio::getOficiosParaReDerivar($calledFrom);
                        $title .= ' (Requieren re-derivación)';
                        break;
                    case 'respuestasRechazadas':
                        $arrayIdRequerimientos = Mds_legales_oficio::getRespuestasRechazadas($calledFrom);
                        $title .= ' (Devueltas por Equipo de Supervisión Final)';
                        break;
                    case 'oficiosParaReDerivarASupervisor':
                        $arrayIdRequerimientos = Mds_legales_oficio::getOficiosParaReDerivarASupervisor($calledFrom);
                        $title .= ' (Requieren re-derivación a supervisión)';
                        break;
                    default:
                        break;
                }

                foreach ($arrayIdRequerimientos as $item) {
                    $valuesArrayIdRequerimientos[] = $item["idlegalesoficio"];
                }
            } else {
                $_SESSION["MdsLegalesOficioIndexParametroNotificacion"] = null;
            }

            $searchModel = new Mds_legales_oficioSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $idArea, $idUsuario, $fechaInicio, $fechaFin, $idEstado, $tipo, $idLegalesCaratula, $valuesArrayIdRequerimientos);

            $hasRolSupervisorGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_GENERAL);
            $hasRolSupervisorArea =  Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_AREA);
            $hasRolRegistro = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_REGISTRO);
            $hasRolAdminGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);
            $hasRolSupervisor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR);
            $hasRolReceptor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_RECEPTOR);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'tipoOficioFiltro' => $this->getFilterTipoOficio(),
                'areaOficioFiltro' => $this->getFilterAreaOficio(),
                'supervisoresFiltro' => $this->getSupervisoresFiltro(),
                'generadoresRespuestaFiltro' => $this->getGeneradoresRespuestaFiltro(),
                'caratulasFiltro' => $this->getCaratulasFiltro(),
                'title' => $title,
                'subtitle' => $subtitle,
                'hasRolSupervisorGeneral' => $hasRolSupervisorGeneral,
                'hasRolSupervisorArea' => $hasRolSupervisorArea,
                'hasRolRegistro' => $hasRolRegistro,
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
                'hasRolSupervisor' => $hasRolSupervisor,
                'hasRolReceptor' => $hasRolReceptor,
            ]);
        }
    }

    public function actionCreate($idlegalescaratula = null)
    {
        $model = new Mds_legales_oficio();
        $action = 'create';
        // $urlReturn =  isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "index.php?r=mds_legales_oficio/index";
        $listParentesco = $this->getListParentesco();
        $listTipoDocumento = $this->getListTiposDocumentos();
        $tipoGenero = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO), 'idconfiguracion', 'descripcion');
        $tipoNacionalidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD), 'idconfiguracion', 'descripcion');
        $modelSupervisorArea = new Mds_legales_supervisor_area();
        $supervisoresByArea = $modelSupervisorArea->getSupervisoresArea();

        $ultimoRequerimientoByCaratula = null;
        $requerimientosCaratulaSeleccionada = null;
        if ($idlegalescaratula) {
            $requerimientosCaratulaSeleccionada = Mds_legales_oficio::getRequerimientosByCaratula($idlegalescaratula);
            if (!empty($requerimientosCaratulaSeleccionada)) {
                $ultimoRequerimientoByCaratula = array_pop($requerimientosCaratulaSeleccionada);
                //Datos a precargar del ultimo requerimiento con la caratula seleccionada
                $model->idlegalescaratula = $idlegalescaratula;
                $model->idemisor = $ultimoRequerimientoByCaratula['idemisor'];
                $model->lugar_libramiento = $ultimoRequerimientoByCaratula['lugar_libramiento'];
                $model->donde_tramita = $ultimoRequerimientoByCaratula['donde_tramita'];
                $model->doctor_a_cargo = $ultimoRequerimientoByCaratula['doctor_a_cargo'];
                $model->caratula = $ultimoRequerimientoByCaratula['caratula'];
                $model->numero_expediente = $ultimoRequerimientoByCaratula['numero_expediente'];
                $model->anio_expediente = $ultimoRequerimientoByCaratula['anio_expediente'];
                $model->caso = $ultimoRequerimientoByCaratula['caso'];
                $model->idarea = $ultimoRequerimientoByCaratula['idarea'];

                $supervisoresUltimoRequerimiento = Mds_legales_derivacion::getSupervisoresByRequerimiento($ultimoRequerimientoByCaratula['idlegalesoficio']);
                $ultimoRequerimientoByCaratula['supervisores'] = $supervisoresUltimoRequerimiento ? json_encode($supervisoresUltimoRequerimiento) : json_encode(array());

                $personasVinculadasUltimoRequerimiento = Mds_legales_oficio_vinculado::getPersonasVinculadasByRequerimiento($ultimoRequerimientoByCaratula['idlegalesoficio']);
                $ultimoRequerimientoByCaratula['personasVinculadas'] = $personasVinculadasUltimoRequerimiento ? json_encode($personasVinculadasUltimoRequerimiento) : json_encode(array());
            } else {
                $caratula = Mds_legales_caratula::findOne($idlegalescaratula);
                if ($caratula) {
                    $model->idlegalescaratula = $idlegalescaratula;
                    $model->caratula = $caratula['caratula'];
                    $model->numero_expediente = $caratula['numero_expediente'];
                    $model->anio_expediente = $caratula['anio_expediente'];
                    $model->caso = $caratula['caso'];
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'action' => $action,
            'supervisoresByArea' => json_encode($supervisoresByArea),
            'listParentesco' => $listParentesco,
            'listTipoDocumento' => $listTipoDocumento,
            'tipoGenero' => $tipoGenero,
            'tipoNacionalidad' => $tipoNacionalidad,
            'ultimoRequerimientoByCaratula' => $ultimoRequerimientoByCaratula,
            'requerimientosCaratulaSeleccionada' => $requerimientosCaratulaSeleccionada,
        ]);
    }

    public function actionStore()
    {
        $usuario = Yii::$app->user->identity;
        $today = date('Y-m-d H:i:s');
        $model = new Mds_legales_oficio();
        $model->load(Yii::$app->request->post());
        $model->idusuario = $usuario->idusuario;
        $model->fecha_carga = $today;
        $model->activo = true;

        if ($model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            if ($model->fecha_libramiento) {
                $fecha_libramiento = ArmarDateParaMySql($model->fecha_libramiento);
                $fecha_libramiento = date_create($fecha_libramiento);
                $fecha_libramiento = date_format($fecha_libramiento, 'Y-m-d');
                $model->fecha_libramiento = $fecha_libramiento;
            }

            if ($model->fecha_recepcion) {
                $fecha_recepcion = ArmarDateParaMySql($model->fecha_recepcion);
                $fecha_recepcion = date_create($fecha_recepcion);
                $fecha_recepcion = date_format($fecha_recepcion, 'Y-m-d');
                $model->fecha_recepcion = $fecha_recepcion;
            }

            if ($model->fecha_oficio) {
                $fecha_oficio = ArmarDateParaMySql($model->fecha_oficio);
                $fecha_oficio = date_create($fecha_oficio);
                $fecha_oficio = date_format($fecha_oficio, 'Y-m-d');
                $model->fecha_oficio = $fecha_oficio;
            }

            if ($model->fecha_plazo) {
                $fecha_plazo = ArmarDateParaMySql($model->fecha_plazo);
                $fecha_plazo = date_create($fecha_plazo);
                $fecha_plazo = date_format($fecha_plazo, 'Y-m-d');
                $model->fecha_plazo = $fecha_plazo;
            }

            $accion = Mds_sys_log::ACCION_EDITAR;
            if ($model->idlegalescaratula) {
                $caratula = Mds_legales_caratula::findOne($model->idlegalescaratula);
                if ($caratula) {
                    $caratula->idusuario_modifica = Yii::$app->user->id;
                    $caratula->updated_at = $today;
                }
            } else {
                $accion = Mds_sys_log::ACCION_NUEVO;
                $caratula = new Mds_legales_caratula();
                $caratula->caratula = $model->caratula;
                $caratula->created_at = $today;
                $caratula->idusuario_alta = Yii::$app->user->id;
            }

            if ($caratula) {
                $caratula->numero_expediente = $model->numero_expediente;
                $caratula->anio_expediente = $model->anio_expediente;
                $caratula->caso = $model->caso;

                if ($caratula->save()) {
                    Mds_sys_log::guardarLog($accion, 'mds_legales_caratula', $caratula->idlegalescaratula, $caratula->getAttributes());
                    $model->idlegalescaratula = $caratula->idlegalescaratula;
                    $model->numero_expediente = null;
                    $model->anio_expediente = null;
                    $model->caso = null;
                }
            }


            if ($model->save()) {
                // Upload archivo adjunto
                if (Yii::$app->request->post()['Mds_legales_oficio']['archivo_oficio']) {
                    $tmpfile = (Yii::$app->request->post()['Mds_legales_oficio']['archivo_oficio']) ? json_decode(Yii::$app->request->post()['Mds_legales_oficio']['archivo_oficio'], true) : null;
                    $this->storeAdjuntoOficio($tmpfile, $model);
                }

                if (Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos']) {
                    $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
                    $this->storeAdjuntoOtros($adjuntos, $model, 'otros');
                }

                if (isset(Yii::$app->request->post()['users'])) {
                    $this->storeDerivacion($model, Yii::$app->request->post()['users']);
                }

                if (isset(Yii::$app->request->post()['Mds_legales_oficio']['personas_vinculadas'])) {
                    $personasVinculadas = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['personas_vinculadas'], true);
                    if ($personasVinculadas && count($personasVinculadas) > 0) {
                        $this->storePersonasVinculadas($model, $personasVinculadas);
                    }
                }

                if (isset(Yii::$app->request->post()['supervisores'])) {
                    $supervisorGuardado = $this->storeSupervisores($model, Yii::$app->request->post()['supervisores']);
                }

                if ($supervisorGuardado) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_oficio', $model->idlegalesoficio, $model->getAttributes());
                    Yii::$app->session->setFlash('success', "Se generó correctamente el requerimiento.");
                } else {
                    $transaction->rollBack();
                }
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', "Error al generar el requerimiento.");
            }
        } else {
            Yii::$app->session->setFlash('error', "Error al validar los datos del requerimiento.");
        }

        // $urlReturn =  Yii::$app->request->post()['urlReturn'];
        return $this->redirect(['mds_legales_oficio/index']);
    }

    public function storeAdjuntoOficio($tmpFile, $model)
    {
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $pathOficios = __DIR__ . '/../web/uploads/legales/oficios/';

        $date = date('Y-m-d_H_i_s', time());
        if ($tmpFile) {
            /*Se mueve el archivo de la carpeta temporal a la carpeta original*/
            $path_info = pathinfo($tmpFile["temp"]);
            $extension = $path_info['extension'];

            $nameFile = "requerimiento_{$model->idlegalesoficio}_{$date}.${extension}";
            if (rename($pathTemp . $tmpFile['temp'], $pathOficios  . $nameFile)) {
                Mds_legales_archivo::saveFile($tmpFile['nombre_original'], 'mds_legales_oficio', 'oficio', $model->idlegalesoficio, $nameFile);
            }
        }
    }

    // tipo = sugerencia / otros
    public function storeAdjuntoOtros($adjuntos, $model, $tipo)
    {
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $pathOficios = __DIR__ . '/../web/uploads/legales/oficios/';

        $date = date('Y-m-d_H_i_s', time());
        foreach ($adjuntos as $key => $adjunto) {
            $path_info = pathinfo($adjunto["temp"]);
            $extension = $path_info['extension'];
            $nameFile = "requerimiento_{$model->idlegalesoficio}_{$date}_{$key}.{$extension}";

            if (rename($pathTemp . $adjunto['temp'], $pathOficios  . $nameFile)) {
                Mds_legales_archivo::saveFile($adjunto['nombre_original'], 'mds_legales_oficio', $tipo, $model->idlegalesoficio, $nameFile);
            }
        }
    }

    public function storeAdjuntoByTipo($adjuntos, $idlegalesoficio, $idlegalesrespuesta, $tipoFolder, $tipoController, $tipoNameFile, $tipo)
    {
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $path = __DIR__ . "/../web/uploads/legales/$tipoFolder/";
        $date = date('Y-m-d_H_i_s', time());
        if (!$idlegalesrespuesta) {
            $idlegalesrespuesta = $idlegalesoficio;
        }
        foreach ($adjuntos as $key => $adjunto) {
            $tmpfile = $adjunto["temp"];
            if ($tmpfile) {
                $path_info = pathinfo($tmpfile);
                $extension = $path_info['extension'];
                $nameFile = "{$tipoNameFile}_{$idlegalesoficio}_{$date}_{$key}.{$extension}";
                if (rename($pathTemp . $tmpfile, $path  . $nameFile)) {
                    Mds_legales_archivo::saveFile($adjunto['nombre_original'], $tipoController, $tipo, $idlegalesrespuesta, $nameFile);
                }
            }
        }
    }

    public function storeAdjuntoRespuestaEstado($adjuntos, $idRespuesta, $tipoFolder, $nroNota, $nroNotaDependencia, $nroVinculacionJudicial)
    {
        $respuesta = Mds_legales_respuesta::find()->where(['idlegalesrespuesta' => $idRespuesta])->one();
        if ($respuesta) {
            $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
            $path = __DIR__ . "/../web/uploads/legales/$tipoFolder/";
            $date = date('Y-m-d_H_i_s', time());
            if (count($adjuntos) > 0) {
                $adjunto = $adjuntos[0];
                $tmpfile = $adjunto["temp"];
                if ($tmpfile) {
                    /*Se mueve el archivo de la carpeta temporal a la carpeta original*/
                    $path_info = pathinfo($tmpfile);
                    $extension = $path_info['extension'];
                    $nameFile = "requerimiento_{$respuesta->idlegalesoficio}_{$date}.{$extension}";
                    if (rename($pathTemp . $tmpfile, $path . $nameFile)) {
                        if ($tipoFolder === 'comprobantes') {
                            $respuesta->comprobante = $nameFile;
                            if ($nroNota) {
                                $respuesta->nro_nota = $nroNota;
                            }
                        } else {
                            $respuesta->nota = $nameFile;
                            if ($nroNotaDependencia) {
                                $respuesta->nro_nota_dependencia = $nroNotaDependencia;
                            }
                            if ($nroVinculacionJudicial) {
                                $respuesta->nro_vinculacion_judicial = $nroVinculacionJudicial;
                            }
                        }
                        $respuesta->update();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_respuesta', $respuesta->idlegalesrespuesta, $respuesta->getAttributes());
                    }
                }
            }
        }
    }

    /* Guarda los usuarios generadores de respuestas */
    public function storeDerivacion($oficio, $users)
    {
        $usuarioAuth = Yii::$app->user->identity;

        //Verificamos si hay que eliminar algun generador de respuesta
        $this->deleteGeneradoresRespuesta($oficio, $users);

        foreach ($users as $user_id) {
            $derivacion = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $oficio->idlegalesoficio, 'idusuario' => $user_id, 'activo' => 1, 'supervisor' => 0])->one();
            if ($derivacion == null) {
                $model  = new Mds_legales_derivacion();
                $model->idusuario = $user_id;
                $model->idusuario_deriva = $usuarioAuth->idusuario;
                $model->idlegalesoficio = $oficio->idlegalesoficio;
                $model->supervisor = 0;
                $model->re_derivado = 0;
                $model->activo = 1;
                $model->fecha_derivacion = date('Y-m-d H:i:s');
                $model->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_derivacion', $model->idlegalesderivacion, $model->getAttributes());
            }
        }
    }

    public function storePersonasVinculadas($oficio, $personasVinculadas)
    {
        $date = date('Y-m-d H:i:s');
        $idOficio = $oficio->idlegalesoficio;
        $arrayDocumentos = array();
        foreach ($personasVinculadas as $personaVinculada) {
            $model = new Mds_legales_oficio_vinculado();
            $model->created_at = $date;
            $model->idusuario_alta = Yii::$app->user->id;
            $model->idlegalesoficio = $idOficio;
            $model->idparentesco = $personaVinculada['parentesco'];
            $model->telefono = $personaVinculada['telefono'];
            $model->mail = $personaVinculada['mail'];
            $model->observaciones = $personaVinculada['observaciones'];
            
            $idPersona = $personaVinculada['idPersona'];
            $genero = $personaVinculada['genero'];
            $nacionalidad = $personaVinculada['nacionalidad'];
            $fechaNacimiento = $personaVinculada['fechaNacimiento'];
            
            if ($idPersona || ($genero && $nacionalidad && $fechaNacimiento)) {
                $model->idtipodocumento = null;
                $model->documento = null;
                $model->apellido = null;
                $model->nombre = null;
                $model->domicilio_calle = null;
                $model->domicilio_numero = null;
                
                if ($idPersona) {
                    $persona = Sds_com_persona::findOne($idPersona);
                    $accion = Mds_sys_log::ACCION_EDITAR;
                } else {
                    $persona = new Sds_com_persona();
                    $persona->documento = $personaVinculada['nroDocumento'];
                    $accion = Mds_sys_log::ACCION_NUEVO;
                }
                
                if ($persona) {
                    $persona->documento_tipo = $personaVinculada['tipoDocumento'];
                    $persona->apellido = $personaVinculada['apellido'];
                    $persona->nombre = $personaVinculada['nombre'];
                    $persona->domicilio_calle = $personaVinculada['domicilioCalle'];
                    $persona->domicilio_numero = $personaVinculada['domicilioNumero'];
                    $persona->genero = $genero;
                    $persona->nacionalidad = $nacionalidad;
                    $persona->fecha_nacimiento = date('Y-m-d', strtotime(str_replace('/', '-', $fechaNacimiento)));
                    if ($persona->save()) {
                        Mds_sys_log::guardarLog($accion, 'sds_com_persona', $persona->idpersona, $persona->getAttributes());
                        $model->idpersona = $persona->idpersona;
                    }
                }
            } else {
                $model->idpersona = null;
                $model->idtipodocumento = $personaVinculada['tipoDocumento'];
                $model->documento = $personaVinculada['nroDocumento'];
                $model->apellido = $personaVinculada['apellido'];
                $model->nombre = $personaVinculada['nombre'];
                $model->domicilio_calle = $personaVinculada['domicilioCalle'];
                $model->domicilio_numero = $personaVinculada['domicilioNumero'];
            }
            
            $model->save();
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_oficio_vinculado', $model->idlegalesoficiovinculado, $model->getAttributes());

            if (isset($personaVinculada['nroDocumento'])) {
                array_push($arrayDocumentos, $personaVinculada['nroDocumento']);
            }
        }

        if (count($arrayDocumentos)) {
            $externalApiRequest = new ExternalApiRequestController();
            $externalApiRequest->runneuIntervencionByModulo($arrayDocumentos, Mds_legales_oficio::RUNNEU_API_MODULO, $idOficio, 'create', Mds_legales_oficio::RUNNEU_API_TIPO_REQUERIMIENTO);
        }
    }

    /*Guarda los supervisores de respuestas*/
    public function storeSupervisores($oficio, $supervisores)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $supervisorGuardado = false;
        $usuariosSinContacto = array();

        //Verificamos si hay que eliminar algun supervisor que ya estaba cargado
        $this->deleteSupervisores($oficio, $supervisores);

        foreach ($supervisores as $user_id) {
            $derivacion = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $oficio->idlegalesoficio, 'idusuario' => $user_id, 'activo' => 1, 'supervisor' => 1])->one();
            if ($derivacion == null) {
                $usuario = Mds_seg_usuario::findOne($user_id);
                $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
                if ($contacto) {
                    $idDispositivo = $contacto->iddispositivo;
                    $model  = new Mds_legales_derivacion();
                    $model->idusuario = $user_id;
                    $model->idusuario_deriva = $usuarioAuth->idusuario;
                    $model->idlegalesoficio = $oficio->idlegalesoficio;
                    $model->fecha_derivacion = date('Y-m-d H:i:s');
                    $model->supervisor = 1;
                    $model->re_derivado = 0;
                    $model->activo = 1;
                    $model->save();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_derivacion', $model->idlegalesderivacion, $model->getAttributes());

                    $modelDerivacionArea = new Mds_legales_derivacion_area();
                    $modelDerivacionArea->idoficio = $oficio->idlegalesoficio;
                    $modelDerivacionArea->iddispositivo = $idDispositivo;
                    $modelDerivacionArea->save();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_derivacion_area', $modelDerivacionArea->idlegalesderivacionarea, $modelDerivacionArea->getAttributes());
                    $supervisorGuardado = true;
                } else {
                    array_push($usuariosSinContacto, $usuario);
                }
                if ($usuariosSinContacto) {
                    $textoUsuariosSinContacto = 'No pudo ser derivado a: <br>';
                    foreach ($usuariosSinContacto as $usuario) {
                        $textoUsuariosSinContacto .= mb_strtoupper($usuario->apellido) . ', ' . mb_strtoupper($usuario->nombre) . '<br>';
                    }
                    Yii::$app->session->setFlash('error', $textoUsuariosSinContacto);
                }
            }
        }
        return $supervisorGuardado;
    }

    public function deleteSupervisores($oficio, $supervisores)
    {
        $textoSupervisoresSinBorrar = 'No se pudo eliminar la derivacion a: <br>';

        //Busca las derivaciones a borrar
        $derivaciones = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $oficio->idlegalesoficio, 'activo' => 1, 'supervisor' => 1])->andWhere(['NOT IN', 'idusuario', $supervisores])->all();

        //A cada derivacion se cambia el atributo activo
        foreach ($derivaciones as $derivacion) {
            $model = Mds_legales_derivacion::findOne($derivacion->idlegalesderivacion);
            $model->activo = 0;
            if ($model->validate() && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_legales_derivacion', $model->idlegalesderivacion, $model->getAttributes());
            } else {
                $textoSupervisoresSinBorrar .= mb_strtoupper($derivacion->usuario->apellido) . ', ' . mb_strtoupper($derivacion->usuario->nombre) . '<br>';
                Yii::$app->session->setFlash('error', $textoSupervisoresSinBorrar);
            }

            //Desactivo las derivaciones que ese supervisor realizo
            $derivacionesDelSupervisor = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $oficio->idlegalesoficio, 'activo' => 1, 'fecha_usu_no_corresponde' => null, 'idusuario_deriva' => $derivacion->idusuario, 'supervisor' => 0])->all();
            foreach ($derivacionesDelSupervisor as $derivacion) {
                $derivacion->activo = 0;
                $derivacion->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_derivacion', $derivacion->idlegalesderivacion, $derivacion->getAttributes());
            }
        }
    }

    public function deleteGeneradoresRespuesta($oficio, $generadoresRespuesta)
    {
        $textoGeneradoresRespuestaSinBorrar = 'No se pudo eliminar la derivacion a: <br>';

        //Busca las derivaciones a borrar
        $derivaciones = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $oficio->idlegalesoficio, 'activo' => 1, 'supervisor' => 0])->andWhere(['NOT IN', 'idusuario', $generadoresRespuesta])->all();

        //A cada derivacion se cambia el atributo activo
        foreach ($derivaciones as $derivacion) {
            $model = Mds_legales_derivacion::findOne($derivacion->idlegalesderivacion);
            $model->activo = 0;
            if ($model->validate() && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_legales_derivacion', $model->idlegalesderivacion, $model->getAttributes());
            } else {
                $textoGeneradoresRespuestaSinBorrar .= mb_strtoupper($derivacion->usuario->apellido) . ', ' . mb_strtoupper($derivacion->usuario->nombre) . '<br>';
                Yii::$app->session->setFlash('error', $textoGeneradoresRespuestaSinBorrar);
            }
        }
    }

    public function actionRespuestas($idOficio)
    {
        // Lo deben ver supervisores, supervisores area y supervisores generales
        $respuestasOficio = Mds_legales_respuesta::find()->where(['idlegalesoficio' => $idOficio])->orderBy([
            'idlegalesrespuesta' => SORT_DESC
        ])->all();

        $usuarioAuth = Yii::$app->user->identity;

        $contacto = Mds_org_contacto::findOne($usuarioAuth->idcontacto);
        $idDispositivo = $contacto->iddispositivo; // Este es el id dispositivo de la persona logeada

        $consultaIdDispostivo = Mds_legales_derivacion_area::find()->where(['idoficio' => $idOficio, 'iddispositivo' => $idDispositivo])->one();
        $consultaSupervisor = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $idOficio, 'idusuario' => $usuarioAuth->idusuario, 'activo' => 1, 'fecha_usu_no_corresponde' => null, 'supervisor' => 1])->one();
        $consultaSupervisorGeneral = Mds_seg_usuario_rol::find()->where(['idrol' => Mds_legales_oficio::ID_ROL_SUPERVISOR_GENERAL, 'idusuario' => $usuarioAuth->idusuario])->one();
        $esSupervisorArea =  Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_AREA);
        $hasRolAdminGeneral =  Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);
        $hasRolSupervisor =  Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR);
        $hasRolReceptor =  Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_RECEPTOR);

        $devoluciones = Mds_legales_derivacion::find()->where("idlegalesoficio =$idOficio")->andWhere('fecha_usu_no_corresponde IS NOT NULL')->orderBy(['fecha_usu_no_corresponde' => SORT_ASC])->all();

        //Soy el supervisor al que le derivaron el oficio || Soy supervisor area de ese oficio || Soy supervisor general
        if ($hasRolAdminGeneral || !empty($consultaSupervisor) || (!empty($consultaIdDispostivo) && $esSupervisorArea) || !empty($consultaSupervisorGeneral) || $hasRolReceptor) {
            $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $idOficio])->one();
            return $this->render('answers', [
                'respuestasOficio' => $respuestasOficio,
                'oficio' => $oficio,
                'devoluciones' => $devoluciones,
                'hasRolSupervisor' => $hasRolSupervisor,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionVinculacion()
    {

        $searchModel = new Mds_legales_oficioSearch();
        $dataProvider = $searchModel->searchVinculacion(Yii::$app->request->queryParams);
        return $this->render('vinculacion/index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'tipoOficioFiltro' => $this->getFilterTipoOficioVinculacion($entregado = 1),
            'areaOficioFiltro' => $this->getFilterAreaOficioVinculacion($entregado = 1)
        ]);
    }

    public function actionVinculacionenviar()
    {
        $searchModel = new Mds_legales_oficioSearch();
        $dataProvider = $searchModel->searchRespuestasParaEnviar(Yii::$app->request->queryParams);
        return $this->render('vinculacion/respuestas_para_enviar/para_enviar', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'tipoOficioFiltro' => $this->getFilterTipoOficioVinculacion(),
            'areaOficioFiltro' => $this->getFilterAreaOficioVinculacion()
        ]);
    }

    public function actionRequerimientosvencidos($fechaInicio = null, $fechaFin = null)
    {
        $searchModel = new Mds_legales_oficioSearch();
        $dataProvider = $searchModel->searchRequerimientosVencidos(Yii::$app->request->queryParams, $fechaInicio, $fechaFin);

        $hasRolSupervisorGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_GENERAL);
        $hasRolSupervisorArea = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_AREA);
        $hasRolRegistro = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_REGISTRO);
        $hasRolAdminGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);
        $hasRolSupervisor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR);
        $hasRolReceptor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_RECEPTOR);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'tipoOficioFiltro' => $this->getFilterTipoOficio('VENCIDOS'),
            'areaOficioFiltro' => $this->getFilterAreaOficio('VENCIDOS'),
            'supervisoresFiltro' => $this->getSupervisoresFiltro('VENCIDOS'),
            'generadoresRespuestaFiltro' => $this->getGeneradoresRespuestaFiltro('VENCIDOS'),
            'caratulasFiltro' => $this->getCaratulasFiltro('VENCIDOS'),
            'title' => 'Requerimientos vencidos',
            'subtitle' => 'Requerimientos vencidos',
            'hasRolSupervisorGeneral' => $hasRolSupervisorGeneral,
            'hasRolSupervisorArea' => $hasRolSupervisorArea,
            'hasRolRegistro' => $hasRolRegistro,
            'hasRolAdminGeneral' => $hasRolAdminGeneral,
            'hasRolSupervisor' => $hasRolSupervisor,
            'hasRolReceptor' => $hasRolReceptor,
        ]);
    }

    public function actionRespuestasconobservaciones($fechaInicio = null, $fechaFin = null)
    {
        $searchModel = new Mds_legales_oficioSearch();
        $dataProvider = $searchModel->searchRespuestasconobservaciones(Yii::$app->request->queryParams, $fechaInicio, $fechaFin);

        $hasRolSupervisorGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_GENERAL);
        $hasRolSupervisorArea = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR_AREA);
        $hasRolRegistro = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_REGISTRO);
        $hasRolAdminGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);
        $hasRolSupervisor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR);
        $hasRolReceptor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_RECEPTOR);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'tipoOficioFiltro' => $this->getFilterTipoOficio('RESPUESTAS_CON_OBSERVACIONES'),
            'areaOficioFiltro' => $this->getFilterAreaOficio('RESPUESTAS_CON_OBSERVACIONES'),
            'supervisoresFiltro' => $this->getSupervisoresFiltro('RESPUESTAS_CON_OBSERVACIONES'),
            'generadoresRespuestaFiltro' => $this->getGeneradoresRespuestaFiltro('RESPUESTAS_CON_OBSERVACIONES'),
            'caratulasFiltro' => $this->getCaratulasFiltro('RESPUESTAS_CON_OBSERVACIONES'),
            'title' => 'Respuestas con observaciones',
            'subtitle' => 'Respuestas con observaciones',
            'hasRolSupervisorGeneral' => $hasRolSupervisorGeneral,
            'hasRolSupervisorArea' => $hasRolSupervisorArea,
            'hasRolRegistro' => $hasRolRegistro,
            'hasRolAdminGeneral' => $hasRolAdminGeneral,
            'hasRolSupervisor' => $hasRolSupervisor,
            'hasRolReceptor' => $hasRolReceptor,
        ]);
    }

    public function actionUpdate($id)
    {
        //Puede ser editado por cualquier usuario que tenga el rol de registro y siempre y cuando no hayan pasado +24hs
        $request = Yii::$app->request;
        $model = Mds_legales_oficio::findOne($id);

        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_LEGALES_EDITAR_REQUERIMIENTO, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "modifica");
        $dateNow = new DateTime('now');
        $fechaCarga = new DateTime($model->fecha_carga);
        $diffDate = $fechaCarga->diff($dateNow); //Aplicamos la diferencia entre fechas
        $hasRolAdminGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);

        //Si tengo permiso de modificar y soy el usuario que cargo el oficio o tengo el rol de registro
        if (($hasOnePermission && ($model->idusuario == $usuarioAuth->idusuario || Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_REGISTRO))) || $hasRolAdminGeneral) {
            if (count($model->respuestas) > 0 && !$hasRolAdminGeneral) {
                Yii::$app->session->setFlash('error', "No se puede editar un requerimiento con respuestas.");
                return $this->redirect(['mds_legales_oficio/index']);
            } elseif ($diffDate->d >= 1 && !$hasRolAdminGeneral) { // Verificamos que no hayan pasado +24hs
                Yii::$app->session->setFlash('error', "No se puede editar un requerimiento que se haya creado hace más de 24hs.");
                return $this->redirect(['mds_legales_oficio/index']);
            } elseif (count($model->receptores) > 0 && !$hasRolAdminGeneral) {
                Yii::$app->session->setFlash('error', "No se puede editar un requerimiento con usuarios generadores de respuestas asignados.");
                return $this->redirect(['mds_legales_oficio/index']);
            } else {
                if ($model->load($request->post())) {
                    $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_oficio');
                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;
                        $nuevo_nombre = $model->random_filename(30, '/uploads/legales/oficios', $extension);
                        $model->archivo_oficio = $nuevo_nombre;
                        $tmpfile->saveAs('uploads/legales/oficios/' . $nuevo_nombre);
                    }
                    if ($model->validate()) {

                        if ($model->fecha_libramiento) {
                            $fecha_libramiento = ArmarDateParaMySql($model->fecha_libramiento);
                            $fecha_libramiento = date_create($fecha_libramiento);
                            $fecha_libramiento = date_format($fecha_libramiento, 'Y-m-d');
                            $model->fecha_libramiento = $fecha_libramiento;
                        }

                        if ($model->fecha_recepcion) {
                            $fecha_recepcion = ArmarDateParaMySql($model->fecha_recepcion);
                            $fecha_recepcion = date_create($fecha_recepcion);
                            $fecha_recepcion = date_format($fecha_recepcion, 'Y-m-d');
                            $model->fecha_recepcion = $fecha_recepcion;
                        }

                        if ($model->fecha_oficio) {
                            $fecha_oficio = ArmarDateParaMySql($model->fecha_oficio);
                            $fecha_oficio = date_create($fecha_oficio);
                            $fecha_oficio = date_format($fecha_oficio, 'Y-m-d');
                            $model->fecha_oficio = $fecha_oficio;
                        }

                        if ($model->fecha_plazo) {
                            $fecha_plazo = ArmarDateParaMySql($model->fecha_plazo);
                            $fecha_plazo = date_create($fecha_plazo);
                            $fecha_plazo = date_format($fecha_plazo, 'Y-m-d');
                            $model->fecha_plazo = $fecha_plazo;
                        }

                        if ($model->idlegalescaratula) {
                            $caratula = Mds_legales_caratula::findOne($model->idlegalescaratula);
                            if ($caratula) {
                                $caratula->idusuario_modifica = Yii::$app->user->id;
                                $caratula->updated_at = date('Y-m-d H:i:s');
                                $caratula->numero_expediente = $model->numero_expediente;
                                $caratula->anio_expediente = $model->anio_expediente;
                                $caratula->caso = $model->caso;

                                if ($caratula->save()) {
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_caratula', $caratula->idlegalescaratula, $caratula->getAttributes());
                                    $model->idlegalescaratula = $caratula->idlegalescaratula;
                                    $model->numero_expediente = null;
                                    $model->anio_expediente = null;
                                    $model->caso = null;
                                }
                            }
                        }

                        if ($model->save()) {
                            if (Yii::$app->request->post()['Mds_legales_oficio']['archivo_oficio']) {
                                $tmpfile = (Yii::$app->request->post()['Mds_legales_oficio']['archivo_oficio']) ? json_decode(Yii::$app->request->post()['Mds_legales_oficio']['archivo_oficio'], true) : null;
                                $this->storeAdjuntoOficio($tmpfile, $model);
                            }
                            if (Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos']) {
                                $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
                                $this->storeAdjuntoOtros($adjuntos, $model, 'otros');
                            }
                            if (Yii::$app->request->post()['Mds_legales_oficio']['adjuntos_eliminados']) {
                                $adjuntosEliminados = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['adjuntos_eliminados'], true);
                                foreach ($adjuntosEliminados as $idAdjunto) {
                                    $modelArchivo = Mds_legales_archivo::findOne($idAdjunto);
                                    $modelArchivo->activo = 0;
                                    $modelArchivo->save();
                                }
                            }
                            if (isset(Yii::$app->request->post()['users'])) {
                                $this->storeDerivacion($model, Yii::$app->request->post()['users']);
                            }
                            if (isset(Yii::$app->request->post()['supervisores'])) {
                                $this->storeSupervisores($model, Yii::$app->request->post()['supervisores']);
                            }
                            Yii::$app->session->setFlash('success', "Se actualizó correctamente el requerimiento.");
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_oficio', $model->idlegalesoficio, $model->getAttributes());
                        } else {
                            Yii::$app->session->setFlash('error', "Error al actualizar el requerimiento.");
                        }
                    }

                    // $urlReturn =  Yii::$app->request->post()['urlReturn'];
                    return $this->redirect(['mds_legales_oficio/index']);
                } else {
                    if ($model->idlegalescaratula) {
                        $model->caratula = $model->caratulaModel->caratula;
                    }

                    // $urlReturn =  isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "index.php?r=mds_legales_oficio/index";
                    $listParentesco = $this->getListParentesco();
                    $listTipoDocumento = $this->getListTiposDocumentos();
                    $tipoGenero = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO), 'idconfiguracion', 'descripcion');
                    $tipoNacionalidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD), 'idconfiguracion', 'descripcion');
                    $action = 'update';
                    $modelSupervisorArea = new Mds_legales_supervisor_area();
                    $supervisoresByArea = $modelSupervisorArea->getSupervisoresArea();
                    return $this->render('update', [
                        'model' => $model,
                        'action' => $action,
                        'listParentesco' => $listParentesco,
                        'listTipoDocumento' => $listTipoDocumento,
                        'tipoGenero' => $tipoGenero,
                        'tipoNacionalidad' => $tipoNacionalidad,
                        'supervisoresByArea' => json_encode($supervisoresByArea),
                        'ultimoRequerimientoByCaratula' => null,
                        'requerimientosCaratulaSeleccionada' => null,
                        // 'urlReturn' => $urlReturn
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $oficio = Mds_legales_oficio::findOne($id);
        if ($oficio) {
            $dateNow = new DateTime('now');
            $fechaCarga = new DateTime($oficio->fecha_carga);
            $diffDate = $fechaCarga->diff($dateNow); //Aplicamos la diferencia entre fechas
            $hasRolAdminGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);
            if (count($oficio->receptores) >= 1 && !$hasRolAdminGeneral) {
                Yii::$app->session->setFlash('error', "No se puede eliminar un requerimiento con derivaciones a usuarios generadores de respuestas.");
                return $this->redirect(['mds_legales_oficio/index']);
            } else if ($diffDate->d >= 1 && !$hasRolAdminGeneral) {
                Yii::$app->session->setFlash('error', "No se puede eliminar un requerimiento que se haya creado hace más de 24hs.");
            } else {
                $usuario = Yii::$app->user->identity;
                $oficio->activo = 0;
                $oficio->deleted_at = date('Y-m-d H:i:s');
                $oficio->idusuario_borra = $usuario->idusuario;
                if ($oficio->update()) {
                    Yii::$app->session->setFlash('success', "Se borró correctamente el requerimiento.");
                } else {
                    Yii::$app->session->setFlash('error', "Error al borrar el requerimiento.");
                }
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_legales_oficio', $oficio->idlegalesoficio, $oficio->getAttributes());
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
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index']);
    }

    public function actionReactivate($id)
    {
        $hasRolAdminGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);
        if ($hasRolAdminGeneral) {
            $oficio = Mds_legales_oficio::findOne($id);
            if ($oficio) {
                $oficio->activo = 1;
                $oficio->deleted_at = null;
                $oficio->idusuario_borra = null;
                if ($oficio->update()) {
                    Yii::$app->session->setFlash('success', "Se reactivó correctamente el requerimiento.");
                } else {
                    Yii::$app->session->setFlash('error', "Error al reactivar el requerimiento.");
                }
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_oficio', $oficio->idlegalesoficio, $oficio->getAttributes());
            } else {
                Yii::$app->session->setFlash('error', "El requerimiento no existe.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index']);
    }

    public function actionDashboard()
    {

        /*
        Cantidad de oficios total (activos)
        Cantidad de oficios sin respuestas
        Cantidad de oficios vencidos (sin respuestas y con fecha de plazo menor al dia de hoy)

        Cantidad oficios que tengan por lo menos una respuesta aprobada
        Cantidad de oficios que tengan por lo menos una respuesta aprobada por supervision final
        Cantidad de requerimiento con respuestas pendientes de supervision final
        Cantidad de requerimientos por provincial/general
        */
        $estadoAprobado = Mds_legales_respuesta_estado::APROBADA;
        $estadoEnviada = Mds_legales_respuesta_estado::ENVIADA;
        $estadoPendiente = Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION;
        $idUsuarioProvincialFamilia = Mds_legales_oficio::ID_USUARIO_PROV_FAMILIA;
        $idUsuarioProvincialViolencia = Mds_legales_oficio::ID_USUARIO_PROV_VIOLENCIA;
        $idUsuarioProvincialNiniez = Mds_legales_oficio::ID_USUARIO_PROV_NINIEZ;
        $idUsuarioProvincialAdultosMayores = Mds_legales_oficio::ID_USUARIO_PROV_ADULTOS_MAYORES;
        $idUsuarioProvincialInterior = Mds_legales_oficio::ID_USUARIO_PROV_INTERIOR;
        $idUsuarioProvincialLegalTecnica = Mds_legales_oficio::ID_USUARIO_PROV_LEGAL_TECNICA;
        $idUsuarioGeneralAdmision = Mds_legales_oficio::ID_USUARIO_GRAL_ADMISION;
        $idUsuarioGeneralFamilia = Mds_legales_oficio::ID_USUARIO_GRAL_FAMILIA;
        $idUsuarioGeneralDiscapacidad = Mds_legales_oficio::ID_USUARIO_GRAL_DISCAPACIDAD;
        $idUsuarioGeneralAdultosMayores = Mds_legales_oficio::ID_USUARIO_GRAL_ADULTOS_MAYORES;
        $idAreaProvincialFamilia = Mds_legales_oficio::ID_AREA_PROV_FAMILIA;
        $idAreaProvincialViolencia = Mds_legales_oficio::ID_AREA_PROV_VIOLENCIA;
        $idAreaProvincialNiniez = Mds_legales_oficio::ID_AREA_PROV_NINIEZ;
        $idAreaProvincialAdultosMayores = Mds_legales_oficio::ID_AREA_PROV_ADULTOS_MAYORES;
        $idAreaProvincialInterior = Mds_legales_oficio::ID_AREA_PROV_INTERIOR;
        $idAreaProvincialLegalTecnica = Mds_legales_oficio::ID_AREA_PROV_LEGAL_TECNICA;
        // $fechaInicio = isset($_GET['fecha_inicio']) && $_GET['fecha_inicio'] ? $_GET['fecha_inicio'] : null;
        // $fechaFin = isset($_GET['fecha_fin']) && $_GET['fecha_fin'] ? $_GET['fecha_fin'] : null;
        $fechaInicio = isset(Yii::$app->request->post()['FECHA_INICIO']) ? Yii::$app->request->post()['FECHA_INICIO'] : null;
        $fechaFin = null;
        $fechaFinOriginal = isset(Yii::$app->request->post()['FECHA_FIN']) ? Yii::$app->request->post()['FECHA_FIN'] : null;
        if ($fechaFinOriginal) {
            $fechaFin = date_create($fechaFinOriginal);
            $fechaFin = $fechaFin->modify('+1 day');
            $fechaFin = date_format($fechaFin, 'Y-m-d');
        }
        $arrayIdUsuariosDashboard = [
            $idUsuarioProvincialFamilia,
            $idUsuarioProvincialViolencia,
            $idUsuarioProvincialNiniez,
            $idUsuarioProvincialAdultosMayores,
            $idUsuarioProvincialInterior,
            $idUsuarioProvincialLegalTecnica,
            // $idUsuarioGeneralAdmision,
            // $idUsuarioGeneralFamilia,
            // $idUsuarioGeneralDiscapacidad,
            // $idUsuarioGeneralAdultosMayores
        ];

        $model = new Mds_legales_oficio();
        $where = "activo = 1";
        $whereCaratulas = "deleted_at IS NULL";
        if ($fechaInicio && $fechaFin) {
            $where .= " AND fecha_carga >= '$fechaInicio' AND fecha_carga <= '$fechaFin'";
            $whereCaratulas .= " AND created_at >= '$fechaInicio' AND created_at <= '$fechaFin'";
        } else if ($fechaInicio) {
            $where .= " AND fecha_carga >= '$fechaInicio'";
            $whereCaratulas .= " AND created_at >= '$fechaInicio'";
        } else if ($fechaFin) {
            $where .= " AND fecha_carga <= '$fechaFin'";
            $whereCaratulas .= " AND created_at <= '$fechaFin'";
        }
        $totalOficios = $model->find()->where($where)->all();

        $totalOficiosSinResponder = $model->getOficiosSinResponder($fechaInicio, $fechaFin);
        $totalOficiosSinEnviar = $model->getOficiosSinEnviarPasadoLimiteTiempo($fechaInicio, $fechaFin);

        $oficiosFueraDeTermino = [];
        foreach ($totalOficiosSinEnviar as $oficio) {
            if ($oficio->fecha_plazo != null) {
                $now = new \DateTime('NOW');
                $date = \DateTime::createFromFormat('Y-m-d', $oficio->fecha_plazo);
                if ($date->format('Y-m-d') < $now->format('Y-m-d')) {
                    $oficiosFueraDeTermino[] = $oficio;
                }
            }
        }

        // $idOficios = array_column($oficiosFueraDeTermino, 'idlegalesoficio');
        // $searchModel = new Mds_legales_oficioSearch();
        // $dataProvider = $searchModel->searchOficiosSinResponderLimiteTiempo(Yii::$app->request->queryParams, $idOficios);

        $totalRequerimientosAprobados = $model->getTotalRequerimientosByIdEstado($estadoAprobado, $fechaInicio, $fechaFin);
        $totalRequerimientosEnviados = $model->getTotalRequerimientosByIdEstado($estadoEnviada, $fechaInicio, $fechaFin);
        $totalRequerimientosPendientesSupervisionFinal = $model->getTotalRequerimientosPendientesSupervisionFinal($fechaInicio, $fechaFin);
        $totalRequerimientosPendientesSupervision = $model->getTotalRequerimientosPendientesSupervision($fechaInicio, $fechaFin);
        $totalRequerimientosDevueltosSupervisionFinal = $model->getTotalRequerimientosDevueltosSupervisionFinal($fechaInicio, $fechaFin);

        // Inicio Pronviciales - Generales
        $totalRequerimientosConDerivacion = $model->getRequerimientosConDerivacionByFecha($fechaInicio, $fechaFin);

        $totalRequerimientosProvincialFamilia = 0;
        $totalRequerimientosProvincialViolencia = 0;
        $totalRequerimientosProvincialNiniez = 0;
        $totalRequerimientosProvincialAdultosMayores = 0;
        $totalRequerimientosProvincialLegalTecnica = 0;
        $totalRequerimientosProvincialInterior = 0;
        $totalRequerimientosUsuarioProvincialFamilia = 0;
        $totalRequerimientosUsuarioProvincialViolencia = 0;
        $totalRequerimientosUsuarioProvincialNiniez = 0;
        $totalRequerimientosUsuarioProvincialAdultosMayores = 0;
        $totalRequerimientosUsuarioProvincialLegalTecnica = 0;
        $totalRequerimientosUsuarioProvincialInterior = 0;
        $totalRequerimientosUsuarioGeneralAdmision = 0;
        $totalRequerimientosUsuarioGeneralFamilia = 0;
        $totalRequerimientosUsuarioGeneralDiscapacidad = 0;
        $totalRequerimientosUsuarioGeneralAdultosMayores = 0;
        $requerimientoAnterior = null;
        foreach ($totalRequerimientosConDerivacion as $requerimiento) {
            if ($requerimiento['supervisor'] == 1 && $requerimiento['activo'] == 1 && !$requerimiento['fecha_usu_no_corresponde'] && $requerimiento['re_derivado'] == 0) {
                switch ($requerimiento['idusuario']) {
                        // case $idUsuarioProvincialFamilia:
                        //     $totalRequerimientosUsuarioProvincialFamilia++;
                        //     break;
                        // case $idUsuarioProvincialViolencia:
                        //     $totalRequerimientosUsuarioProvincialViolencia++;
                        //     break;
                        // case $idUsuarioProvincialNiniez:
                        //     $totalRequerimientosUsuarioProvincialNiniez++;
                        //     break;
                        // case $idUsuarioProvincialAdultosMayores:
                        //     $totalRequerimientosUsuarioProvincialAdultosMayores++;
                        //     break;
                        // case $idUsuarioProvincialLegalTecnica:
                        //     $totalRequerimientosUsuarioProvincialLegalTecnica++;
                        //     break;
                        // case $idUsuarioProvincialInterior:
                        //     $totalRequerimientosUsuarioProvincialInterior++;
                        //     break;
                    case $idUsuarioGeneralAdmision:
                        $totalRequerimientosUsuarioGeneralAdmision++;
                        break;
                    case $idUsuarioGeneralFamilia:
                        $totalRequerimientosUsuarioGeneralFamilia++;
                        break;
                        // case $idUsuarioGeneralDiscapacidad:
                        //     $totalRequerimientosUsuarioGeneralDiscapacidad++;
                        //     break;
                    case $idUsuarioGeneralAdultosMayores:
                        $totalRequerimientosUsuarioGeneralAdultosMayores++;
                        break;
                    default:
                        break;
                }
            }
            if (!$requerimientoAnterior || ($requerimientoAnterior && $requerimientoAnterior['idlegalesoficio'] != $requerimiento['idlegalesoficio'])) {
                switch ($requerimiento['idarea']) {
                    case $idAreaProvincialFamilia:
                        $totalRequerimientosProvincialFamilia++;
                        break;
                    case $idAreaProvincialViolencia:
                        $totalRequerimientosProvincialViolencia++;
                        break;
                    case $idAreaProvincialNiniez:
                        $totalRequerimientosProvincialNiniez++;
                        break;
                    case $idAreaProvincialAdultosMayores:
                        $totalRequerimientosProvincialAdultosMayores++;
                        break;
                    case $idAreaProvincialLegalTecnica:
                        $totalRequerimientosProvincialLegalTecnica++;
                        break;
                    case $idAreaProvincialInterior:
                        $totalRequerimientosProvincialInterior++;
                        break;
                    default:
                        break;
                }
            }
            $requerimientoAnterior = $requerimiento;
        }


        $usuarioModel = new Mds_seg_usuario();
        $usuariosDashboard = $usuarioModel->find()->select(['idusuario', 'nombre', 'apellido'])->where(['in', 'idusuario', $arrayIdUsuariosDashboard])->all();

        $arrayUsuariosDashboard = array();
        foreach ($usuariosDashboard as $usuario) {
            $nombre =  mb_strtoupper($usuario['apellido']) . ", " . mb_strtoupper($usuario['nombre']);
            switch ($usuario['idusuario']) {
                case $idUsuarioProvincialFamilia:
                    $datosUsuario = [
                        'titulo' => "FAMILIA",
                        'nombre' => $nombre,
                        'totalRequerimientosUsuario' => $totalRequerimientosUsuarioProvincialFamilia,
                        'totalRequerimientosArea' => $totalRequerimientosProvincialFamilia,
                        'tipo' => 'PROVINCIAL',
                        'orden' => 0,
                        'idArea' => $idAreaProvincialFamilia,
                        'idUsuario' => $idUsuarioProvincialFamilia,
                    ];
                    array_push($arrayUsuariosDashboard, $datosUsuario);
                    break;
                case $idUsuarioProvincialViolencia:
                    $datosUsuario = [
                        'titulo' => "PREVENCIÓN Y ASISTENCIA DE LAS VIOLENCIAS",
                        'nombre' => $nombre,
                        'totalRequerimientosUsuario' => $totalRequerimientosUsuarioProvincialViolencia,
                        'totalRequerimientosArea' => $totalRequerimientosProvincialViolencia,
                        'tipo' => 'PROVINCIAL',
                        'orden' => 1,
                        'idArea' => $idAreaProvincialViolencia,
                        'idUsuario' => $idUsuarioProvincialViolencia,
                    ];
                    array_push($arrayUsuariosDashboard, $datosUsuario);
                    break;
                case $idUsuarioProvincialNiniez:
                    $datosUsuario = [
                        'titulo' => "NIÑEZ Y ADOLESCENCIA",
                        'nombre' => $nombre,
                        'totalRequerimientosUsuario' => $totalRequerimientosUsuarioProvincialNiniez,
                        'totalRequerimientosArea' => $totalRequerimientosProvincialNiniez,
                        'tipo' => 'PROVINCIAL',
                        'orden' => 2,
                        'idArea' => $idAreaProvincialNiniez,
                        'idUsuario' => $idUsuarioProvincialNiniez,
                    ];
                    array_push($arrayUsuariosDashboard, $datosUsuario);
                    break;
                case $idUsuarioProvincialAdultosMayores:
                    $datosUsuario = [
                        'titulo' => "POLÍTICAS PARA PERSONAS MAYORES",
                        'nombre' => $nombre,
                        'totalRequerimientosUsuario' => $totalRequerimientosUsuarioProvincialAdultosMayores,
                        'totalRequerimientosArea' => $totalRequerimientosProvincialAdultosMayores,
                        'tipo' => 'PROVINCIAL',
                        'orden' => 3,
                        'idArea' => $idAreaProvincialAdultosMayores,
                        'idUsuario' => $idUsuarioProvincialAdultosMayores,
                    ];
                    array_push($arrayUsuariosDashboard, $datosUsuario);
                    break;
                case $idUsuarioProvincialLegalTecnica:
                    $datosUsuario = [
                        'titulo' => "ASESORÍA LEGAL TÉCNICA Y ADMINISTRATIVA",
                        'nombre' => $nombre,
                        'totalRequerimientosUsuario' => $totalRequerimientosUsuarioProvincialLegalTecnica,
                        'totalRequerimientosArea' => $totalRequerimientosProvincialLegalTecnica,
                        'tipo' => 'PROVINCIAL',
                        'orden' => 4,
                        'idArea' => $idAreaProvincialLegalTecnica,
                        'idUsuario' => $idUsuarioProvincialLegalTecnica,
                    ];
                    array_push($arrayUsuariosDashboard, $datosUsuario);
                    break;
                case $idUsuarioProvincialInterior:
                    $datosUsuario = [
                        'titulo' => "COORDINACIÓN SUBSECRETARÍA DE FAMILIA",
                        'nombre' => $nombre,
                        'totalRequerimientosUsuario' => $totalRequerimientosUsuarioProvincialInterior,
                        'totalRequerimientosArea' => $totalRequerimientosProvincialInterior,
                        'tipo' => 'PROVINCIAL',
                        'orden' => 5,
                        'idArea' => $idAreaProvincialInterior,
                        'idUsuario' => $idUsuarioProvincialInterior,
                    ];
                    array_push($arrayUsuariosDashboard, $datosUsuario);
                    break;
                case $idUsuarioGeneralAdmision:
                    $datosUsuario = [
                        'titulo' => "ADMISIÓN",
                        'nombre' => $nombre,
                        'totalRequerimientosUsuario' => $totalRequerimientosUsuarioGeneralAdmision,
                        'tipo' => 'GENERAL',
                        'orden' => 6,
                        'idUsuario' => $idUsuarioGeneralAdmision,
                    ];
                    array_push($arrayUsuariosDashboard, $datosUsuario);
                    break;
                case $idUsuarioGeneralFamilia:
                    $datosUsuario = [
                        'titulo' => "FAMILIA",
                        'nombre' => $nombre,
                        'totalRequerimientosUsuario' => $totalRequerimientosUsuarioGeneralFamilia,
                        'tipo' => 'GENERAL',
                        'orden' => 7,
                        'idUsuario' => $idUsuarioGeneralFamilia,
                    ];
                    array_push($arrayUsuariosDashboard, $datosUsuario);
                    break;
                case $idUsuarioGeneralDiscapacidad:
                    $datosUsuario = [
                        'titulo' => "DISCAPACIDAD Y SISTEMA DE PROTECCIÓN DE DERECHOS",
                        'nombre' => $nombre,
                        'totalRequerimientosUsuario' => $totalRequerimientosUsuarioGeneralDiscapacidad,
                        'tipo' => 'GENERAL',
                        'orden' => 8,
                        'idUsuario' => $idUsuarioGeneralDiscapacidad,
                    ];
                    array_push($arrayUsuariosDashboard, $datosUsuario);
                    break;
                case $idUsuarioGeneralAdultosMayores:
                    $datosUsuario = [
                        'titulo' => "POLÍTICAS SOCIALES A PERSONAS MAYORES",
                        'nombre' => $nombre,
                        'totalRequerimientosUsuario' => $totalRequerimientosUsuarioGeneralAdultosMayores,
                        'tipo' => 'GENERAL',
                        'orden' => 9,
                        'idUsuario' => $idUsuarioGeneralAdultosMayores,
                    ];
                    array_push($arrayUsuariosDashboard, $datosUsuario);
                    break;
                default:
                    break;
            }
        }


        $arrayUsuariosDashboard = array_sort($arrayUsuariosDashboard, 'orden', SORT_ASC);
        // Fin Provinciales - Generales

        $cantidadCaratulas = count(Mds_legales_caratula::getAllCaratulasActivas($whereCaratulas));

        return $this->render('dashboard/index', [
            // 'searchModel' => $searchModel,
            // 'dataProvider' => $dataProvider,
            'totalOficios' => $totalOficios,
            'totalOficiosSinResponder' => $totalOficiosSinResponder,
            'oficiosFueraDeTermino' => $oficiosFueraDeTermino,
            'totalRequerimientosAprobados' => $totalRequerimientosAprobados,
            'totalRequerimientosEnviados' => $totalRequerimientosEnviados,
            'totalRequerimientosPendientesSupervision' => $totalRequerimientosPendientesSupervision,
            'totalRequerimientosPendientesSupervisionFinal' => $totalRequerimientosPendientesSupervisionFinal,
            'totalRequerimientosDevueltosSupervisionFinal' => $totalRequerimientosDevueltosSupervisionFinal,
            'arrayUsuariosDashboard' => $arrayUsuariosDashboard,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFinOriginal,
            'estadoAprobado' => $estadoAprobado,
            'estadoEnviada' => $estadoEnviada,
            'estadoPendiente' => $estadoPendiente,
            'cantidadCaratulas' => $cantidadCaratulas,
        ]);
    }

    public function actionDashboard_caratula()
    {

        /*
        Cantidad de caratulas total (activos)
        */

        $limiteCaratulasMayorRequerimientos = Mds_legales_caratula::DASHBOARD_LIMITE_CARATULAS_MAYOR_CANT_REQUERIMIENTOS;
        $fechaInicio = isset(Yii::$app->request->post()['FECHA_INICIO']) ? Yii::$app->request->post()['FECHA_INICIO'] : null;
        $fechaFin = null;
        $fechaFinOriginal = isset(Yii::$app->request->post()['FECHA_FIN']) ? Yii::$app->request->post()['FECHA_FIN'] : null;
        if ($fechaFinOriginal) {
            $fechaFin = date_create($fechaFinOriginal);
            $fechaFin = $fechaFin->modify('+1 day');
            $fechaFin = date_format($fechaFin, 'Y-m-d');
        }

        $where = "deleted_at IS NULL";
        $whereCaratulasConMasRequerimientos = "";
        if ($fechaInicio && $fechaFin) {
            $where .= " AND created_at >= '$fechaInicio' AND created_at <= '$fechaFin'";
            $whereCaratulasConMasRequerimientos = "oficio.fecha_carga >= '$fechaInicio' AND oficio.fecha_carga <= '$fechaFin'";
        } else if ($fechaInicio) {
            $where .= " AND created_at >= '$fechaInicio'";
            $whereCaratulasConMasRequerimientos = "oficio.fecha_carga >= '$fechaInicio'";
        } else if ($fechaFin) {
            $where .= " AND created_at <= '$fechaFin'";
            $whereCaratulasConMasRequerimientos = "oficio.fecha_carga <= '$fechaFin'";
        }

        $caratulasActivas = Mds_legales_caratula::getAllCaratulasActivas($where);
        $cantidadCaratulas = count($caratulasActivas);
        $cantidadRequerimientos = Mds_legales_caratula::getMayorCantidadRequerimientosPorCaratula($whereCaratulasConMasRequerimientos, $limiteCaratulasMayorRequerimientos);

        $caratulasConMasRequerimientos = Mds_legales_caratula::getCaratulasConMasRequerimientos($whereCaratulasConMasRequerimientos, $cantidadRequerimientos);
        return $this->render('dashboard/caratula', [
            'cantidadCaratulas' => $cantidadCaratulas,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFinOriginal,
            'caratulasConMasRequerimientos' => $caratulasConMasRequerimientos,
            'limiteCaratulasMayorRequerimientos' => $limiteCaratulasMayorRequerimientos
        ]);
    }

    /**
     * Generación de vista para rechazar oficio
     */
    public function actionRechazaroficio($idDerivacion)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $derivacion = Mds_legales_derivacion::find()->where(['idlegalesderivacion' => $idDerivacion, 'idusuario' => $usuarioAuth->idusuario, 'activo' => 1, 'fecha_usu_no_corresponde' => NULL])->one();
        if ($derivacion) {
            return $this->render('rechazar_oficio', [
                'derivacion' => $derivacion,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Funcion para eliminar
     */
    public function actionRechazaroficiostore()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $parametroIndex = isset($_SESSION["MdsLegalesOficioIndexParametroNotificacion"]) ? $_SESSION["MdsLegalesOficioIndexParametroNotificacion"] : null;
        $redirect = $parametroIndex ? ['index', 'notificacion' => $parametroIndex] : ['index'];

        $usuarioAuth = Yii::$app->user->identity;
        $idLegalesDerivacion = Yii::$app->request->post()['idlegalesderivacion'];
        $derivacion = Mds_legales_derivacion::find()->where(['idlegalesderivacion' => $idLegalesDerivacion, 'activo' => 1, 'fecha_usu_no_corresponde' => null])->one();

        if ($derivacion) {
            $idOficio = $derivacion->idlegalesoficio;
            $observaciones =
                Yii::$app->request->post()['Mds_legales_derivacion']['observaciones'];
            $dateNow = date('Y-m-d H:i:s');
            // Existe una derivacion
            if ($derivacion->idusuario == $usuarioAuth->idusuario) {
                //Desactivo la derivacion del usuario
                $derivacion->observaciones = $observaciones;
                $derivacion->fecha_usu_no_corresponde = $dateNow;
                $derivacion->activo = 0;
                $derivacion->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_derivacion', $derivacion->idlegalesderivacion, $derivacion->getAttributes());
            }

            if ($derivacion->supervisor) {
                $supervisores = array();
                $derivaciones = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $idOficio, 'activo' => 1, 'fecha_usu_no_corresponde' => null])->all();
                $existenUsuariosGeneradoresRespuesta = false;

                foreach ($derivaciones as $derivacion) {
                    // En el caso de que sea supervisor y devuelva un requerimiento, desactivo las derivaciones que ya realizó
                    if ($derivacion['idusuario_deriva'] == $usuarioAuth->idusuario &&  $derivacion['supervisor'] == 0) {
                        //Si soy el que derivo y la derivacion fue a un generador de respuesta
                        $derivacion->activo = 0;
                        $derivacion->save();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_derivacion', $derivacion->idlegalesderivacion, $derivacion->getAttributes());
                    }

                    if ($derivacion['supervisor'] == 0 && !$existenUsuariosGeneradoresRespuesta) {
                        //Si existen usuarios generadores de respuesta   
                        $existenUsuariosGeneradoresRespuesta = true;
                    } else if ($derivacion['supervisor'] == 1) {
                        array_push($supervisores, $derivacion);
                    }
                }

                //En caso de que no existan usuarios generadores de respuesta, quito las derivaciones a los demas supervisores
                if (!$existenUsuariosGeneradoresRespuesta && !empty($supervisores)) {
                    $nombreUsuario =  mb_strtoupper(Yii::$app->user->identity->apellido) . ', ' . mb_strtoupper(Yii::$app->user->identity->nombre);
                    $textoAdicionalObservaciones = "<p>Devuelto por el rechazo del usuario <b>$nombreUsuario</b> con el siguiente motivo: $observaciones</p>";
                    foreach ($supervisores as $supervisor) {
                        $supervisor->observaciones = $textoAdicionalObservaciones;
                        $supervisor->fecha_usu_no_corresponde = $dateNow;
                        $supervisor->activo = 0;
                        $supervisor->save();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_derivacion', $supervisor->idlegalesderivacion, $supervisor->getAttributes());
                    }
                }
            }

            if (Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos']) {
                $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
                $tipo = 'devolucion';
                $this->storeAdjuntoByTipo($adjuntos, $idLegalesDerivacion, null, $tipo, 'mds_legales_derivacion', $tipo, $tipo);
            }
        }

        return $this->redirect($redirect);
    }

    /**
     * Generación de vista para rederivar
     */
    public function actionRederivar($idDerivacion)
    {

        //Lo puede derivar el supervisor o el que cargo ese oficio

        $derivacionOriginal = Mds_legales_derivacion::find()->where(['idlegalesderivacion' => $idDerivacion, 're_derivado' => 0])->one();

        if ($derivacionOriginal) {
            $idOficio = $derivacionOriginal->idlegalesoficio;
            $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $idOficio, 'activo' => 1])->one();
            if ($oficio) {
                $derivaciones = Mds_legales_derivacion::find()->where("idlegalesoficio =$idOficio")->andWhere('fecha_usu_no_corresponde IS NOT NULL')->orderBy(['fecha_usu_no_corresponde' => SORT_DESC])->all();
                $arrayUsuariosReceptores =  ArrayHelper::map(Mds_legales_oficio::getUsuariosSegunRol(Mds_legales_oficio::ID_ROL_RECEPTOR), 'idusuario', 'nombre_apellido');
                $arraySupervisores = ArrayHelper::map(Mds_legales_oficio::getUsuariosSegunRol(Mds_legales_oficio::ID_ROL_SUPERVISOR), 'idusuario', 'nombre_apellido');
                $arrayUsuariosReceptoresDerivados =  ArrayHelper::map($oficio->getReceptores(), 'idusuario', function ($oficio) {
                    return $oficio->idusuario;
                });
                $arraySupervisoresDerivados =  ArrayHelper::map($oficio->getSupervisores(), 'idusuario', function ($oficio) {
                    return $oficio->idusuario;
                });

                $usuarioAuth = Yii::$app->user->identity;

                // Buscando derivaciones que tienen este id oficio, mi id usuario, activo=1, fecha_usu_no_corresponde IS NULL
                $consultaSupervisorQueDerivoReceptor = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $idOficio, 'activo' => 1, 'fecha_usu_no_corresponde' => null, 'idusuario' => $usuarioAuth->idusuario, 'supervisor' => 1])->one();
                $hasRolRegistro = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_REGISTRO);
                $hasRolSupervisor = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_SUPERVISOR);
                if ($consultaSupervisorQueDerivoReceptor || $hasRolRegistro) {
                    $existeUsuarioSupervisor = false;
                    $i = 0;
                    while ($i < count($derivaciones) && !$existeUsuarioSupervisor) {
                        $derivacion = $derivaciones[$i];
                        $existeUsuarioSupervisor = $derivacion->idusuario == $usuarioAuth->idusuario;
                        $i++;
                    }

                    //Soy el supervisor al que derivaron || soy el que cargo el oficio || tengo el rol de registro || soy el supervisor al que un receptor rechazo la derivacion
                    if (($existeUsuarioSupervisor && $hasRolSupervisor) || ($oficio->idusuario == $usuarioAuth->idusuario || $hasRolRegistro) || !empty($consultaSupervisorQueDerivoReceptor && $hasRolSupervisor)) {
                        $modelSupervisorArea = new Mds_legales_supervisor_area();
                        $supervisoresByArea = $modelSupervisorArea->getSupervisoresArea();
                        return $this->render('rederivar_oficio', [
                            'oficio' => $oficio,
                            'derivaciones' => $derivaciones,
                            'derivacionOriginal' => $derivacionOriginal,
                            'derivacionSupervision' => $consultaSupervisorQueDerivoReceptor,
                            'arraySupervisores' => $arraySupervisores,
                            'arraySupervisoresDerivados' => $arraySupervisoresDerivados,
                            'arrayUsuariosReceptores' => $arrayUsuariosReceptores,
                            'arrayUsuariosReceptoresDerivados' => $arrayUsuariosReceptoresDerivados,
                            'devoluciones' => $derivaciones,
                            'supervisoresByArea' => json_encode($supervisoresByArea),
                        ]);
                    } else {
                        throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                    }
                } else {
                    throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                }
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Almacenamiento (store) de la rederivación. La misma la utiliza registro y supervisión.
     */
    public function actionRederivarstore()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $parametroIndex = isset($_SESSION["MdsLegalesOficioIndexParametroNotificacion"]) ? $_SESSION["MdsLegalesOficioIndexParametroNotificacion"] : null;
        $redirect = $parametroIndex ? ['index', 'notificacion' => $parametroIndex] : ['index'];

        $idOficio = Yii::$app->request->post()['idlegalesoficio'];
        $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $idOficio])->one();
        $usuariosRechazoDerivacion = $oficio->getUsuariosDerivacionRechazo();
        $usuarioAuth = Yii::$app->user->identity;
        $tipoArchivosGuardar = null;
        //Si se re derivó a nuevos receptores
        if (isset(Yii::$app->request->post()['users'])) {
            $tipoArchivosGuardar = 'sugerencia';

            $usuariosReceptoresPorRechazoSupervisor = $oficio->getUsuariosReceptoresPorRechazoSupervisor();
            if (!empty($usuariosReceptoresPorRechazoSupervisor)) {
                $usuariosRechazoDerivacion = array_merge($usuariosRechazoDerivacion, $usuariosReceptoresPorRechazoSupervisor);
            }

            foreach ($usuariosRechazoDerivacion as $receptorDerivacion) {
                if ($receptorDerivacion->supervisor == 0) {
                    $receptorDerivacion->re_derivado = 1;
                    $receptorDerivacion->save();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_derivacion', $receptorDerivacion->idlegalesderivacion, $receptorDerivacion->getAttributes());
                }
            }

            //Receptores a los que quiero derivar el requerimiento
            $nuevosReceptores = Yii::$app->request->post()['users'];

            $this->storeDerivacion($oficio, $nuevosReceptores);

            $fechaDerivacion = date('Y-m-d H:i:s');

            if (isset(Yii::$app->request->post()['Mds_legales_oficio']['sugerencia'])) {
                $oficio->sugerencia = Yii::$app->request->post()['Mds_legales_oficio']['sugerencia'];
                $oficio->sugerencia_idusuario = $usuarioAuth->idusuario;
                $oficio->sugerencia_fecha = $fechaDerivacion;

                if ($oficio->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_oficio_sugerencia_log', $oficio->idlegalesoficio, $oficio->getAttributes());
                } else {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_oficio_error', $oficio->idlegalesoficio, $oficio->errors);
                }
            }
        }

        if (isset(Yii::$app->request->post()['supervisores'])) {
            $tipoArchivosGuardar = 'otros';

            //Si se re-derivo a nuevos supervisores
            foreach ($usuariosRechazoDerivacion as $supervisorDerivacion) {
                if ($supervisorDerivacion->supervisor == 1) {
                    $supervisorDerivacion->re_derivado = 1;
                    $supervisorDerivacion->save();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_derivacion', $supervisorDerivacion->idlegalesderivacion, $supervisorDerivacion->getAttributes());
                }
            }

            //Supervisores a los que quiero derivar el requerimiento
            $nuevosSupervisores = Yii::$app->request->post()['supervisores'];
            $this->storeSupervisores($oficio, $nuevosSupervisores);
        }

        if (isset(Yii::$app->request->post()['Mds_legales_oficio']['nuevaObservacion']) && !empty(Yii::$app->request->post()['Mds_legales_oficio']['nuevaObservacion'])) {
            $fechaToday = date('d-m-Y H:i');
            $observacionAnterior = $oficio->observaciones;
            $usuarioNuevaObservacion = mb_strtoupper($usuarioAuth->apellido) . ", " . mb_strtoupper($usuarioAuth->nombre);
            $preFixNuevaObservacion = "$fechaToday - $usuarioNuevaObservacion";
            $observacionNueva = "\n\n{$preFixNuevaObservacion}\n" . Yii::$app->request->post()['Mds_legales_oficio']['nuevaObservacion'];
            $oficio->observaciones = $observacionAnterior . $observacionNueva;
            if ($oficio->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_oficio', $oficio->idlegalesoficio, $oficio->getAttributes());
            } else {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_oficio_error', $oficio->idlegalesoficio, $oficio->errors);
            }
        }

        $idArea = (isset(Yii::$app->request->post()['idarea']) && !empty(Yii::$app->request->post()['idarea'])) ? Yii::$app->request->post()['idarea'] : null;
        if ($idArea && ($idArea != $oficio->idarea)) {
            $oficio->idarea = $idArea;
            if ($oficio->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_oficio', $oficio->idlegalesoficio, $oficio->getAttributes());
            } else {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_oficio_error', $oficio->idlegalesoficio, $oficio->errors);
            }
        }

        if (isset(Yii::$app->request->post()['Mds_legales_oficio']['adjuntos_eliminados']) && Yii::$app->request->post()['Mds_legales_oficio']['adjuntos_eliminados']) {
            $adjuntosEliminados = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['adjuntos_eliminados'], true);
            foreach ($adjuntosEliminados as $idAdjunto) {
                $modelArchivo = Mds_legales_archivo::findOne($idAdjunto);
                $modelArchivo->activo = 0;
                $modelArchivo->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_legales_archivo', $idAdjunto, $modelArchivo->getAttributes());
            }
        }

        if (Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'] && $tipoArchivosGuardar) {
            $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
            $this->storeAdjuntoOtros($adjuntos, $oficio, $tipoArchivosGuardar);
        }
        return $this->redirect($redirect);
    }

    public function actionGuardararchivotemporal()
    {
        $success = false;
        $file =  UploadedFile::getInstanceByName('file[0]');
        if (isset($file)) {
            $extension = $file->extension;
            $nuevo_nombre = Mds_legales_oficio::random_filename(30, '/uploads/legales/temp', $extension);
            if ($file->saveAs("uploads/legales/temp/{$nuevo_nombre}")) {
                $success = true;
            }
        }
        return json_encode([
            'subido' => $success,
            'temp' => $nuevo_nombre,
            'nombre_original' => $file->name
        ]);
    }
    /**
     * API para retornar los requerimientos devueltos
     */
    public function actionRequerimientosdevueltos()
    {
        $requerimientosDevueltos = Mds_legales_oficio::getOficiosParaReDerivarASupervisor('requerimientosDevueltos');
        if (!empty($requerimientosDevueltos)) {
            usort($requerimientosDevueltos, array($this, "ordenarByTipoRequerimiento"));
        }
        return json_encode(['data' => (array) $requerimientosDevueltos]);
    }

    /**
     * Displays a single mds_legales_oficio model.
     * @param integer $id
     * @return mixed
     */

    public function actionView($idlegalesoficio)
    {
        //Lo debe ver unicamente el que cargo el registro o si soy vinculacion

        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_LEGALES_VER_REQUERIMIENTO, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "ver");

        //Si soy Vinculacion
        $consultaVinculacion = Mds_seg_usuario_rol::find()->where(['idrol' => Mds_legales_oficio::ID_ROL_VINCULACION, 'idusuario' => $usuarioAuth->idusuario])->one();

        //Tengo permiso y soy el que lo cargo || Soy vinculacion
        if (($hasOnePermission) || !empty($consultaVinculacion)) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_legales_oficio', $idlegalesoficio, array());
            $respuestasOficio = Mds_legales_respuesta::find()->where(['idlegalesoficio' => $idlegalesoficio])->orderBy([
                'idlegalesrespuesta' => SORT_DESC
            ])->all();


            $devoluciones = Mds_legales_derivacion::find()->where("idlegalesoficio =$idlegalesoficio")->andWhere('fecha_usu_no_corresponde IS NOT NULL')->orderBy(['fecha_usu_no_corresponde' => SORT_ASC])->all();

            return $this->render('view', [
                'respuestasOficio' => $respuestasOficio,
                'oficio' => Mds_legales_oficio::find()->where(['idlegalesoficio' => $idlegalesoficio])->one(),
                'consultaVinculacion' => $consultaVinculacion,
                'devoluciones' => $devoluciones,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionAgregar_derivaciones($idlegalesoficio)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Agregar derivaciones - Requerimiento #$idlegalesoficio",
                'content' => $this->renderAjax('agregar_derivaciones', [
                    'idlegalesoficio' => $idlegalesoficio,
                ]),
                'footer' => [
                    Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                ]
            ];
        } else {
            return $this->redirect(['/mds_legales_oficio/index']);
        }
    }

    public function actionStore_agregar_derivaciones()
    {
        $usuarioAuth = Yii::$app->user->identity;
        $payload = Yii::$app->request->post();
        $idlegalesoficio = $payload['idlegalesoficio'];
        $message = 'Se agregaron las derivaciones correctamente.';

        if (isset($payload['generadoresRespuesta'])) {
            $generadoresRespuesta = $payload['generadoresRespuesta'];
            foreach ($generadoresRespuesta as $user_id) {
                $derivacion = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $idlegalesoficio, 'idusuario' => $user_id, 'activo' => 1, 'supervisor' => 0])->one();
                if ($derivacion == null) {
                    $model  = new Mds_legales_derivacion();
                    $model->idusuario = $user_id;
                    $model->idusuario_deriva = $usuarioAuth->idusuario;
                    $model->idlegalesoficio = $idlegalesoficio;
                    $model->supervisor = 0;
                    $model->re_derivado = 0;
                    $model->activo = 1;
                    $model->fecha_derivacion = date('Y-m-d H:i:s');
                    if (!$model->save()) {
                        $message = 'No se pudo crear la derivación del generador de respuesta.';
                    }
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_derivacion', $model->idlegalesderivacion, $model->getAttributes());
                }
            }
        }

        if (isset($payload['supervisores'])) {
            $supervisores = $payload['supervisores'];
            $usuariosSinContacto = array();

            foreach ($supervisores as $user_id) {
                $derivacion = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $idlegalesoficio, 'idusuario' => $user_id, 'activo' => 1, 'supervisor' => 1])->one();
                if ($derivacion == null) {
                    $usuario = Mds_seg_usuario::findOne($user_id);
                    $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
                    if ($contacto) {
                        $idDispositivo = $contacto->iddispositivo;
                        $model  = new Mds_legales_derivacion();
                        $model->idusuario = $user_id;
                        $model->idusuario_deriva = $usuarioAuth->idusuario;
                        $model->idlegalesoficio = $idlegalesoficio;
                        $model->fecha_derivacion = date('Y-m-d H:i:s');
                        $model->supervisor = 1;
                        $model->re_derivado = 0;
                        $model->activo = 1;
                        if (!$model->save()) {
                            $message = 'No se pudo crear la derivación del supervisor.';
                        }
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_derivacion', $model->idlegalesderivacion, $model->getAttributes());

                        $modelDerivacionArea = new Mds_legales_derivacion_area();
                        $modelDerivacionArea->idoficio = $idlegalesoficio;
                        $modelDerivacionArea->iddispositivo = $idDispositivo;
                        $modelDerivacionArea->save();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_derivacion_area', $modelDerivacionArea->idlegalesderivacionarea, $modelDerivacionArea->getAttributes());
                    } else {
                        array_push($usuariosSinContacto, $usuario);
                    }
                    if ($usuariosSinContacto) {
                        $textoUsuariosSinContacto = 'No pudo ser derivado a: <br>';
                        foreach ($usuariosSinContacto as $usuario) {
                            $textoUsuariosSinContacto .= mb_strtoupper($usuario->apellido) . ', ' . mb_strtoupper($usuario->nombre) . '<br>';
                        }
                        Yii::$app->session->setFlash('error', $textoUsuariosSinContacto);
                    }
                }
            }
        }

        return json_encode(['message' => $message]);
    }

    public function actionStore_agregar_archivos()
    {
        $idRespuesta = null;
        $crearEnMdsLegalesArchivo = true;
        $message = 'Se agregaron los archivos correctamente.';
        $payload = Yii::$app->request->post();

        if (isset($payload['otros_adjuntos']) && isset($payload['idlegalesoficio']) && isset($payload['archivo_tipo'])) {
            $idOficio = $payload['idlegalesoficio'];
            $archivoTipo = $payload['archivo_tipo'];
            $adjuntos = $payload['otros_adjuntos'];
            $adjuntos = json_decode($payload['otros_adjuntos'], true);
            switch ($archivoTipo) {
                case 'REQUERIMIENTO':
                    $tipoFolder = 'oficios';
                    $tipoController = 'mds_legales_oficio';
                    $tipoNameFile = 'requerimiento';
                    $tipo = 'oficio';
                    break;
                case 'REQUERIMIENTO_OTROS':
                    $tipoFolder = 'oficios';
                    $tipoController = 'mds_legales_oficio';
                    $tipoNameFile = 'requerimiento';
                    $tipo = 'otros';
                    break;
                case 'SUPERVISOR_SUGERENCIA':
                    $tipoFolder = 'oficios';
                    $tipoController = 'mds_legales_oficio';
                    $tipoNameFile = 'requerimiento';
                    $tipo = 'sugerencia';
                    break;
                case 'SUPERVISOR_APROBACION':
                    $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $idOficio])->one();
                    $idRespuesta = $oficio->lastRespuesta->idlegalesrespuesta;
                    $tipoFolder = 'respuestas_supervisor';
                    $tipoController = 'mds_legales_respuesta';
                    $tipoNameFile = 'respuesta_supervisor';
                    $tipo = 'respuesta_supervisor';
                    break;
                case 'RESPUESTA':
                    $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $idOficio])->one();
                    $idRespuesta = $oficio->lastRespuesta->idlegalesrespuesta;
                    $tipoFolder = 'respuestas';
                    $tipoController = 'mds_legales_respuesta';
                    $tipoNameFile = 'requerimiento';
                    $tipo = 'respuesta';
                    break;
                case 'VINCULACION_COMPROBANTE':
                    $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $idOficio])->one();
                    $idRespuesta = $oficio->lastRespuesta->idlegalesrespuesta;
                    $tipoFolder = 'comprobantes';
                    $nroNota = isset($payload['nro_nota']) ? $payload['nro_nota'] : null;
                    $nroNotaDependencia = null;
                    $nroVinculacionJudicial = null;
                    $crearEnMdsLegalesArchivo = false;
                    break;
                case 'VINCULACION_NOTA':
                    $oficio = Mds_legales_oficio::find()->where(['idlegalesoficio' => $idOficio])->one();
                    $idRespuesta = $oficio->lastRespuesta->idlegalesrespuesta;
                    $tipoFolder = 'notas';
                    $nroNota = null;
                    $nroNotaDependencia = isset($payload['nro_nota_dependencia']) ? $payload['nro_nota_dependencia'] : null;
                    $nroVinculacionJudicial = isset($payload['nro_vinculacion_judicial']) ? $payload['nro_vinculacion_judicial'] : null;
                    $crearEnMdsLegalesArchivo = false;
                    break;
                default:
                    break;
            }

            if ($crearEnMdsLegalesArchivo) {
                $this->storeAdjuntoByTipo($adjuntos, $idOficio, $idRespuesta, $tipoFolder, $tipoController, $tipoNameFile, $tipo);
            } else {
                $this->storeAdjuntoRespuestaEstado($adjuntos, $idRespuesta, $tipoFolder, $nroNota, $nroNotaDependencia, $nroVinculacionJudicial);
            }
        } else {
            $message = 'Ocurrió un error al agregar los archivos.';
        }

        return json_encode(['message' => $message]);
    }

    public function ordenarByTipoRequerimiento($a, $b)
    {
        return strcmp(strtoupper($b["descripcion"]), strtoupper($a["descripcion"]));
    }

    public function actionGuardarlogmanualusuario()
    {
        $success = false;
        if (Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_legales_oficio_manual', null, array())) {
            $success = true;
        };
        return json_encode(['success' => $success]);
    }

    protected function hasOnePermission($permissions, $action)
    {
        $hasOnePermission = false;
        $i = 0;
        while (!$hasOnePermission && $i < count($permissions)) {
            $permission = $permissions[$i];
            $hasOnePermission = $permission[$action];
            $i++;
        }

        return $hasOnePermission;
    }
    /*
    public function actionReporte($idlegalesoficio)
    {
        //Lo debe ver unicamente el que cargo el registro o si soy vinculacion
        $usuarioAuth = Yii::$app->user->identity;

        $dateToday = date('d/m/Y H:i:s');
        $oficio =  Mds_legales_oficio::find()->where(['idlegalesoficio' => $idlegalesoficio])->one();

        $apellidoMayuscula = mb_strtoupper($oficio->usuario->apellido);
        $nombreMayuscula = mb_strtoupper($oficio->usuario->nombre);
        $fechaCarga = armarDateParaReporte($oficio->fecha_carga);
        $listUsuarioCarga = "<span class='text-muted'>$fechaCarga</span> - $apellidoMayuscula, $nombreMayuscula";

        $supervisores = $oficio->getSupervisores();
        $listSupervisores = "";
        if (!empty($supervisores)) :
            foreach ($supervisores as $supervisor) :
                $fechaSupervisor = armarDateParaReporte($supervisor->fecha_derivacion);
                $fecha = "<span class='text-muted'> $fechaSupervisor</span>";
                $apellidoMayuscula = mb_strtoupper($supervisor->usuario->apellido);
                $nombreMayuscula = mb_strtoupper($supervisor->usuario->nombre);
                $listSupervisores .=  "<li>$fecha - $apellidoMayuscula, $nombreMayuscula </li>";
            endforeach;
        endif;

        $derivacionesReceptores = $oficio->getReceptores();
        $listReceptores = "";
        if (!empty($derivacionesReceptores)) :
            foreach ($derivacionesReceptores as $derivacion) :
                $fechaReceptor = armarDateParaReporte($derivacion->fecha_derivacion);
                $fecha = "<span class='text-muted'> $fechaReceptor</span>";
                $apellidoMayuscula = mb_strtoupper($derivacion->usuario->apellido);
                $nombreMayuscula = mb_strtoupper($derivacion->usuario->nombre);
                $listReceptores .=  "<li>$fecha - $apellidoMayuscula, $nombreMayuscula </li>";
            endforeach;
        endif;

        $countOficio = count($oficio->getAdjuntosByTipo('oficio'));
        $countOtros = count($oficio->getAdjuntosByTipo('otros'));
        $poseeAdjuntosOficio = $countOficio  > 0 ? 'Si' : 'No';
        $poseeAdjuntosOtros = $countOtros  > 0 ? 'Si (cantidad: ' . $countOtros . ')' : 'No';

        $content = $this->renderPartial('reporte', [
            'oficio' => $oficio,
            'listUsuarioCarga' => $listUsuarioCarga,
            'listSupervisores' => $listSupervisores,
            'listReceptores' => $listReceptores,
            'poseeAdjuntosOficio' => $poseeAdjuntosOficio,
            'poseeAdjuntosOtros' => $poseeAdjuntosOtros,
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' =>
            '.kv-heading-1{font-size:18px}
                table{border-collapse: collapse; width: 100%;}
                .titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}
                .parrafo, td{padding: 10px .5rem 5px .5rem}
                .parrafo{text-align:justify padding-left: 15px;}
                div.saltopagina{page-break-after:always}',
            'methods' => [
                'SetTitle' => 'DETALLE DE REQUERIMIENTO ' . $idlegalesoficio,
                'SetHeader' => null,
                'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
            ]
        ]);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_legales_oficio', $idlegalesoficio, array());
        return $pdf->render();
    }*/

    /*
    public function actionReporte_personas_vinculadas()
    {
        $usuarioAuth = Yii::$app->user->identity;

        $dateToday = date('d/m/Y H:i:s');
        $oficios =  Mds_legales_oficio::find()->where("caratula != ''")->orderBy(['caratula' => SORT_ASC])->limit(100)->all();

        $content = $this->renderPartial('reportePersonasVinculadas', [
            'oficios' => $oficios,
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' =>
            '.kv-heading-1{font-size:18px}
                table{border-collapse: collapse; width: 100%;}
                .titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}
                .parrafo, td{padding: 10px .5rem 5px .5rem}
                .parrafo{text-align:justify padding-left: 15px;}
                div.saltopagina{page-break-after:always}',
            'methods' => [
                'SetTitle' => 'Reporte Personas Vinculadas',
                'SetHeader' => null,
                'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
            ]
        ]);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_legales_oficio', null, array());
        return $pdf->render();
    }*/
    /*
    public function actionReporte_contestados_by_caratula()
    {

        $usuarioAuth = Yii::$app->user->identity;

        $dateToday = date('d/m/Y H:i:s');
        $caratulas =  Mds_legales_caratula::find()
            ->where("deleted_at is null")
            ->orderBy(['caratula' => SORT_ASC])
            ->limit(3000)
            ->all();

        $idProvFamilia = Mds_legales_oficio::ID_AREA_PROV_FAMILIA;
        $idTipoOficio = 2371;
        $caratulasCount = 0;
        foreach ($caratulas as $index => &$caratula) {
            //Aquellos requerimientos que son de familia y no son de tipo oficio
            $oficios = Mds_legales_oficio::find()
                ->where("activo = 1 
                AND idlegalescaratula = {$caratula['idlegalescaratula']} 
                AND idarea = $idProvFamilia 
                AND tipo_oficio != $idTipoOficio")
                ->orderBy(['idlegalesoficio' => SORT_DESC])
                ->one();

            if ($oficios && $oficios->getTotalRespuestasGeneradas() == 0) {
                $caratula['oficios'] = $oficios;
                $caratulasCount++;
            } else {
                unset($caratulas[$index]);
            }
        }

        usort($caratulas, array($this, 'compararFechasDescendente'));

        $content = $this->renderPartial('reporteContestadosByCaratula', [
            'caratulas' => $caratulas,
            'caratulasCount' => $caratulasCount
        ]);


        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 4,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'options' => ['shrink_tables_to_fit' => 0],
            'cssInline' =>
            '.kv-heading-1{font-size:12px}
                table{border-collapse: collapse; width: 100%;}
                .titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}
                .parrafo, td{padding: 10px .5rem 5px .5rem}
                .parrafo{text-align:justify padding-left: 15px;}
                div.saltopagina{page-break-after:always}',
            'methods' => [
                'SetTitle' => 'Reporte Respuestas - FAMILIA',
                'SetHeader' => null,
                'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
            ]
        ]);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_legales_oficio', null, array());
        return $pdf->render();
    }
    }*/
    /*

    public function actionCrearSupervisoresScript()
    {
        $message = "ok";
        $today = date('Y-m-d');
        $guardado = true;
        $idUsuariosSupervisores = [4992, 21083];
        $oficios = Mds_legales_oficio::buscarOficiosByUsuarioAndTipo(1119, 1);
        if (!empty($oficios)) {
            $indexOficios = 0;
            $oficiosLength = count($oficios);
            $transaction = Yii::$app->db->beginTransaction();
            while ($guardado && $indexOficios < $oficiosLength) {
                $oficio = $oficios[$indexOficios];
                $idOficio = $oficio->idlegalesoficio;

                foreach ($idUsuariosSupervisores as $idUsuario) {
                    $derivacion = Mds_legales_derivacion::find()->where(['idlegalesoficio' => $idOficio, 'idusuario' => $idUsuario, 'activo' => 1, 'supervisor' => 1])->one();
                    if (empty($derivacion)) {
                        $usuario = Mds_seg_usuario::findOne($idUsuario);
                        $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
                        if ($contacto) {
                            $idDispositivo = $contacto->iddispositivo;
                            $model  = new Mds_legales_derivacion();
                            $model->idusuario = $idUsuario;
                            $model->idusuario_deriva = 6703;
                            $model->idlegalesoficio = $idOficio;
                            $model->fecha_derivacion = $today;
                            $model->supervisor = 1;
                            $model->re_derivado = 0;
                            $model->activo = 1;

                            if ($model->save()) {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_derivacion', $model->idlegalesderivacion, $model->getAttributes());
                                $modelDerivacionArea = new Mds_legales_derivacion_area();
                                $modelDerivacionArea->idoficio = $idOficio;
                                $modelDerivacionArea->iddispositivo = $idDispositivo;
                                if ($modelDerivacionArea->save()) {
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_derivacion_area', $modelDerivacionArea->idlegalesderivacionarea, $modelDerivacionArea->getAttributes());
                                } else {
                                    $guardado = false;
                                }
                            } else {
                                $guardado = false;
                            }
                        }
                    }
                }

                $indexOficios++;
            }

            if ($guardado) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
                $message = "error al guardar derivacion";
            }
        }
        return $message;
    }
    }*/
    /*

    public function actionCrear_caratulas_nulas()
    {
        $today = date('2023-01-01');
        $oficiosConCaratulasNulas = Mds_legales_oficio::buscarOficiosConCaratulasNulas();
        if ($oficiosConCaratulasNulas) {
            foreach ($oficiosConCaratulasNulas as $oficio) {
                $modelCaratula = new Mds_legales_caratula();

                $modelCaratula->caratula = $oficio['caratula'];
                $modelCaratula->numero_expediente = $oficio['numero_expediente'];
                $modelCaratula->anio_expediente = $oficio['anio_expediente'];
                $modelCaratula->caso = $oficio['caso'];
                $modelCaratula->created_at = $today;
                $modelCaratula->idusuario_alta = Yii::$app->user->id;

                $transaction = Yii::$app->db->beginTransaction();

                if ($modelCaratula->validate() && $modelCaratula->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_caratula', $modelCaratula->idlegalescaratula, $modelCaratula->getAttributes());

                    $modelOficio = new Mds_legales_oficio();
                    $oficioCreado = $modelOficio->findOne($oficio['idlegalesoficio']);
                    if ($oficioCreado) {
                        $oficioCreado->idlegalescaratula = $modelCaratula->idlegalescaratula;
                        if ($oficioCreado->validate() && $oficioCreado->save()) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_oficio', $oficioCreado->idlegalesoficio, $oficioCreado->getAttributes());
                            $transaction->commit();
                        } else {
                            $transaction->rollBack();
                            echo ("Error al guardar el oficio: $oficioCreado->idlegalesoficio - ");
                        }
                    } else {
                        $transaction->rollBack();
                        echo ('Oficio no encontrado.');
                    }
                } else {
                    $transaction->rollBack();
                    echo ('Error al guardar la carátula.');
                }
            }
            echo ('finalizado');
        }
    }
    }*/

    protected function getFilterTipoOficio($llamadoDesde = null)
    {
        //Busqueda tipos de oficios existentes
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;

        switch ($llamadoDesde) {
            case 'VENCIDOS':
                $date = date('Y-m-d');
                $whereDate = " AND oficio.idlegalesoficio NOT IN 
            (
                SELECT mds_legales_respuesta.idlegalesoficio 
                FROM mds_legales_respuesta 
                INNER JOIN mds_legales_respuesta_estado 
                ON mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
                WHERE (mds_legales_respuesta_estado.estado = $idEstadoAprobado OR mds_legales_respuesta_estado.estado = $idEstadoEnviado) 
            ) 
            AND oficio.fecha_plazo < '$date' ";
                break;
            case 'RESPUESTAS_CON_OBSERVACIONES':
                $whereDate = " AND respuesta.observacion_final IS NOT NULL AND TRIM(respuesta.observacion_final) != '' ";
                break;
            default:
                $whereDate = "";
                break;
        }

        $tipoFiltro = Mds_legales_oficio::findBySql(
            "SELECT
            configuracion.idconfiguracion as idtipo,
            configuracion.descripcion as descripciontipo
            FROM mds_legales_oficio oficio
            INNER JOIN sds_com_configuracion configuracion
            ON oficio.tipo_oficio = configuracion.idconfiguracion
            LEFT JOIN mds_legales_respuesta respuesta
            ON oficio.idlegalesoficio = respuesta.idlegalesoficio
            WHERE oficio.activo = 1
            $whereDate
            AND (oficio.tipo_oficio IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1) )
            ORDER BY descripciontipo ASC"
        )->asArray()->all();
        $tiposFiltro = ArrayHelper::map($tipoFiltro, 'idtipo', 'descripciontipo');
        return $tiposFiltro;
    }

    protected function getFilterAreaOficio($llamadoDesde = null)
    {
        //Busqueda areas existentes
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;

        switch ($llamadoDesde) {
            case 'VENCIDOS':
                $date = date('Y-m-d');
                $whereDate = " AND oficio.idlegalesoficio NOT IN 
            (
                SELECT mds_legales_respuesta.idlegalesoficio 
                FROM mds_legales_respuesta 
                INNER JOIN mds_legales_respuesta_estado 
                ON mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
                WHERE (mds_legales_respuesta_estado.estado = $idEstadoAprobado OR mds_legales_respuesta_estado.estado = $idEstadoEnviado) 
            ) 
            AND oficio.fecha_plazo < '$date' ";
                break;
            case 'RESPUESTAS_CON_OBSERVACIONES':
                $whereDate = " AND respuesta.observacion_final IS NOT NULL AND TRIM(respuesta.observacion_final) != '' ";
                break;
            default:
                $whereDate = "";
                break;
        }

        $tipoFiltro = Mds_legales_oficio::findBySql(
            "SELECT
            configuracion.idconfiguracion as idarea,
            configuracion.descripcion as descripciontipo
            FROM mds_legales_oficio oficio
            INNER JOIN sds_com_configuracion configuracion
            ON oficio.idarea = configuracion.idconfiguracion
            LEFT JOIN mds_legales_respuesta respuesta
            ON oficio.idlegalesoficio = respuesta.idlegalesoficio
            WHERE oficio.activo = 1
            $whereDate
            AND (oficio.idarea IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1) )
            ORDER BY descripciontipo ASC
        "
        )->asArray()->all();

        $tiposFiltro = ArrayHelper::map($tipoFiltro, 'idarea', 'descripciontipo');
        return $tiposFiltro;
    }

    protected function getFilterTipoOficioVinculacion($entregado = null)
    {
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;
        $idEstadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;
        $idEstadoObservada = Mds_legales_respuesta_estado::OBSERVADA;
        //Busqueda tipos de oficios
        if ($entregado) {
            $whereEntregado = " AND respuesta.entregado = 1 ";
        } else { // respuestas para enviar
            $whereEntregado = " AND respuesta_estado.idlegalesrespuesta NOT IN (SELECT e.idlegalesrespuesta from mds_legales_respuesta_estado e where e.estado IN ('$idEstadoEnviado','$idEstadoObservada','$idEstadoRechazado')) ";
        }

        $tipoFiltro = Mds_legales_oficio::findBySql(
            "SELECT
            configuracion.idconfiguracion as idtipo,
            configuracion.descripcion as descripciontipo
            FROM mds_legales_oficio oficio
            INNER JOIN sds_com_configuracion configuracion
            ON oficio.tipo_oficio = configuracion.idconfiguracion
            INNER JOIN mds_legales_respuesta respuesta
            ON oficio.idlegalesoficio = respuesta.idlegalesoficio
            INNER JOIN mds_legales_respuesta_estado respuesta_estado
            ON respuesta.idlegalesrespuesta = respuesta_estado.idlegalesrespuesta
            WHERE oficio.activo = 1
            AND respuesta_estado.estado = '$idEstadoAprobado'
            $whereEntregado
            AND oficio.tipo_oficio IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1)
            ORDER BY descripciontipo ASC
            "
        )->asArray()->all();
        $tiposFiltro = ArrayHelper::map($tipoFiltro, 'idtipo', 'descripciontipo');
        return $tiposFiltro;
    }

    protected function getFilterAreaOficioVinculacion($entregado = null)
    {
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;
        $idEstadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;
        $idEstadoObservada = Mds_legales_respuesta_estado::OBSERVADA;
        //Busqueda tipos de oficios
        if ($entregado) {
            $whereEntregado = " AND respuesta.entregado = 1 ";
        } else { // respuestas para enviar
            $whereEntregado = " AND respuesta_estado.idlegalesrespuesta NOT IN (SELECT e.idlegalesrespuesta from mds_legales_respuesta_estado e where e.estado IN ('$idEstadoEnviado','$idEstadoObservada','$idEstadoRechazado')) ";
        }

        $tipoFiltro = Mds_legales_oficio::findBySql(
            "SELECT
            configuracion.idconfiguracion as idtipo,
            configuracion.descripcion as descripciontipo
            FROM mds_legales_oficio oficio
            INNER JOIN sds_com_configuracion configuracion
            ON oficio.idarea = configuracion.idconfiguracion
            INNER JOIN mds_legales_respuesta respuesta
            ON oficio.idlegalesoficio = respuesta.idlegalesoficio
            INNER JOIN mds_legales_respuesta_estado respuesta_estado
            ON respuesta.idlegalesrespuesta = respuesta_estado.idlegalesrespuesta
            WHERE oficio.activo = 1
            AND respuesta_estado.estado = '$idEstadoAprobado'
            $whereEntregado
            AND oficio.idarea IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1)
            ORDER BY descripciontipo ASC
            "
        )->asArray()->all();
        $tiposFiltro = ArrayHelper::map($tipoFiltro, 'idtipo', 'descripciontipo');
        return $tiposFiltro;
    }

    protected function getSupervisoresFiltro($llamadoDesde = null)
    {
        //Busqueda supervisores que tengan al menos una derivacion activa
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;

        switch ($llamadoDesde) {
            case 'VENCIDOS':
                $date = date('Y-m-d');
                $whereDate = " AND oficio.idlegalesoficio NOT IN 
            (
                SELECT mds_legales_respuesta.idlegalesoficio 
                FROM mds_legales_respuesta 
                INNER JOIN mds_legales_respuesta_estado 
                ON mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
                WHERE (mds_legales_respuesta_estado.estado = $idEstadoAprobado OR mds_legales_respuesta_estado.estado = $idEstadoEnviado) 
            ) 
            AND oficio.fecha_plazo < '$date' ";
                break;
            case 'RESPUESTAS_CON_OBSERVACIONES':
                $whereDate = " AND respuesta.observacion_final IS NOT NULL AND TRIM(respuesta.observacion_final) != '' ";
                break;
            default:
                $whereDate = "";
                break;
        }

        $tipoFiltro = Mds_legales_oficio::findBySql(
            "SELECT
            usuario.idusuario,
            UPPER(CONCAT(usuario.apellido, ', ', usuario.nombre)) as nombreUsuario
            FROM mds_legales_derivacion derivacion
            INNER JOIN mds_seg_usuario usuario
            ON derivacion.idusuario = usuario.idusuario
            INNER JOIN mds_legales_oficio oficio
            ON oficio.idlegalesoficio = derivacion.idlegalesoficio
            LEFT JOIN mds_legales_respuesta respuesta
            ON oficio.idlegalesoficio = respuesta.idlegalesoficio	
            WHERE oficio.activo = 1 
            $whereDate
            AND derivacion.activo = 1
            AND derivacion.supervisor = 1
            ORDER BY usuario.apellido ASC
        "
        )->asArray()->all();

        $tiposFiltro['SIN_VALOR'] = 'Sin supervisor/a';
        $tiposFiltro += ArrayHelper::map($tipoFiltro, 'idusuario', 'nombreUsuario');
        return $tiposFiltro;
    }

    protected function getGeneradoresRespuestaFiltro($llamadoDesde = null)
    {
        //Busqueda generadores de respuesta que tengan al menos una derivacion activa
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;

        switch ($llamadoDesde) {
            case 'VENCIDOS':
                $date = date('Y-m-d');
                $whereDate = " AND oficio.idlegalesoficio NOT IN 
            (
                SELECT mds_legales_respuesta.idlegalesoficio 
                FROM mds_legales_respuesta 
                INNER JOIN mds_legales_respuesta_estado 
                ON mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
                WHERE (mds_legales_respuesta_estado.estado = $idEstadoAprobado OR mds_legales_respuesta_estado.estado = $idEstadoEnviado) 
            ) 
            AND oficio.fecha_plazo < '$date' ";
                break;
            case 'RESPUESTAS_CON_OBSERVACIONES':
                $whereDate = " AND respuesta.observacion_final IS NOT NULL AND TRIM(respuesta.observacion_final) != '' ";
                break;
            default:
                $whereDate = "";
                break;
        }

        $tipoFiltro = Mds_legales_oficio::findBySql(
            "SELECT
            usuario.idusuario,
            UPPER(CONCAT(usuario.apellido, ', ', usuario.nombre)) as nombreUsuario
            FROM mds_legales_derivacion derivacion
            INNER JOIN mds_seg_usuario usuario
            ON derivacion.idusuario = usuario.idusuario
            INNER JOIN mds_legales_oficio oficio
            ON oficio.idlegalesoficio = derivacion.idlegalesoficio
            LEFT JOIN mds_legales_respuesta respuesta
            ON oficio.idlegalesoficio = respuesta.idlegalesoficio	
            WHERE oficio.activo = 1 
            $whereDate
            AND derivacion.activo = 1
            AND derivacion.supervisor = 0
            ORDER BY usuario.apellido ASC
        "
        )->asArray()->all();
        $tiposFiltro['SIN_VALOR'] = 'Sin generador/a de respuesta';
        $tiposFiltro += ArrayHelper::map($tipoFiltro, 'idusuario', 'nombreUsuario');
        return $tiposFiltro;
    }

    protected function getCaratulasFiltro($llamadoDesde = null)
    {
        //Busqueda tipos de oficios existentes
        $idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
        $idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;

        switch ($llamadoDesde) {
            case 'VENCIDOS':
                $date = date('Y-m-d');
                $whereDate = " AND oficio.idlegalesoficio NOT IN 
            (
                SELECT mds_legales_respuesta.idlegalesoficio 
                FROM mds_legales_respuesta 
                INNER JOIN mds_legales_respuesta_estado 
                ON mds_legales_respuesta_estado.idlegalesrespuesta = mds_legales_respuesta.idlegalesrespuesta
                WHERE (mds_legales_respuesta_estado.estado = $idEstadoAprobado OR mds_legales_respuesta_estado.estado = $idEstadoEnviado) 
            ) 
            AND oficio.fecha_plazo < '$date' ";
                break;
            case 'RESPUESTAS_CON_OBSERVACIONES':
                $whereDate = " AND respuesta.observacion_final IS NOT NULL AND TRIM(respuesta.observacion_final) != '' ";
                break;
            default:
                $whereDate = "";
                break;
        }

        $caratulas = Mds_legales_caratula::findBySql(
            "SELECT
            caratula.idlegalescaratula as idlegalescaratula,
            caratula.caratula as caratula
            FROM mds_legales_oficio oficio
            INNER JOIN mds_legales_caratula caratula
            ON oficio.idlegalescaratula = caratula.idlegalescaratula
            LEFT JOIN mds_legales_respuesta respuesta
            ON oficio.idlegalesoficio = respuesta.idlegalesoficio
            WHERE oficio.activo = 1
            $whereDate
            AND (oficio.idlegalescaratula IN (SELECT idlegalescaratula FROM mds_legales_caratula WHERE deleted_at IS NULL) )
            ORDER BY caratula ASC"
        )->asArray()->all();
        $caratulas = ArrayHelper::map($caratulas, 'idlegalescaratula', 'caratula');
        return $caratulas;
    }

    protected function getListParentesco()
    {
        //Busqueda relaciones vinculares
        $relacion = Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_PARENTEZCO, "activo" => 1])
            ->andWhere('idconfiguracion != 60') // traemos todos menos jefe
            ->asArray()
            ->all();
        $relaciones = ArrayHelper::map($relacion, 'idconfiguracion', 'descripcion');
        return $relaciones;
    }

    protected function getListTiposDocumentos()
    {
        //Busqueda Tipos de documentos
        $tipos = Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_TIPO_DOC, "activo" => 1])
            ->asArray()
            ->all();
        $arrayTipos = ArrayHelper::map($tipos, 'idconfiguracion', 'descripcion');
        return $arrayTipos;
    }

    public function compararFechasDescendente($a, $b)
    {
        if (isset($a->oficios->fecha_plazo) && isset($b->oficios->fecha_plazo)) {
            return strtotime($a->oficios->fecha_plazo) < strtotime($b->oficios->fecha_plazo);
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

function armarDateParaReporte($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $anio = substr($fecha, 2, 2);
    $mes = substr($fecha, 5, 2);
    $dia = substr($fecha, 8, 2);
    $hora = substr($fecha, 11, 5);
    $DT = "$dia/$mes/$anio $hora";
    return $DT;
}

function array_sort($array, $on, $order = SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}
