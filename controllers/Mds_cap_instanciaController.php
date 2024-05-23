<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_cap_docente;
use app\models\Mds_cap_docente_instancia;
use app\models\Mds_cap_inscripcion;
use app\models\Mds_cap_persona;
use app\models\Sds_com_persona;
use Da\QrCode\QrCode;
use Yii;
use yii\helpers\Url;
use app\models\Mds_cap_instancia;
use app\models\Mds_cap_instanciaSearch;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use app\models\Mds_cap_capacitacion;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_permiso;
use yii\bootstrap\Modal;
use app\models\Sds_com_configuracion;
use phpDocumentor\Reflection\Types\Expression;
use yii\helpers\ArrayHelper;

use ZipArchive;

/**
 * Mds_cap_instanciaController implements the CRUD actions for Mds_cap_instancia model.
 */
class Mds_cap_instanciaController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout','certificados','cert_docentes','preview_certificado','descargar_certificados','migrar'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout','certificados','cert_docentes','preview_certificado','descargar_certificados','migrar'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_CAP_INSTANCIA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_cap_instancia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_cap_instanciaSearch();
        $modelForm = new \app\models\LoginForm();
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $idcontacto  = $usuario ? $usuario->idcontacto : null;
        $idOrganismo = null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $idcontacto  = Yii::$app->user->identity->idcontacto;
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
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            if ($idOrganismo) {
                $filterCapacitaciones = $this->getFilterCapacitaciones("INTERNO", $idOrganismo, $idusuario, $idcontacto);
            } else {
                $filterCapacitaciones = $this->getFilterCapacitaciones("EXTERNO", $idOrganismoExterno, $idusuario, null);
            }

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cap_instancia', null, array());

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'filterCapacitaciones' => $filterCapacitaciones
            ]);
        } else {
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $modelForm,
            ]);
        }
    }

    public function actionMigrar()
    {
        $cap_instancia = Mds_cap_instancia::find()
            ->all();
        $i = 0;
        foreach ($cap_instancia as $una_instancia) {
            if ($una_instancia->imagen != null) {
                $imagen = $una_instancia->imagen;
                $cad = date("Y-m-d H:i:s") . $una_instancia->idinstancia;
                $nombrefile = substr(md5($cad), 0, 12);
                $f = fopen("uploads/instancias/" . $nombrefile . ".png", "w") or die("Unable to open file!");
                fwrite($f, base64_decode(explode(",", $imagen, 2)[1]));
                $model_cap_instancia = $una_instancia;
                $model_cap_instancia->imagen_path = $nombrefile . ".png";
                $model_cap_instancia->save();
                $i++;
            }
            if ($una_instancia->logo_extra != null) {
                $logo_extra = $una_instancia->logo_extra;
                $cad = date("Y-m-d H:i:s") . $una_instancia->idinstancia;
                $nombrefile = substr(md5($cad), 0, 12);
                $f = fopen("uploads/instancias/" . $nombrefile . ".png", "w") or die("Unable to open file!");
                fwrite($f, base64_decode(explode(",", $logo_extra, 2)[1]));
                $model_cap_instancia = $una_instancia;
                $model_cap_instancia->logo_extra_path = $nombrefile . ".png";
                $model_cap_instancia->save();
                $i++;
            }
            if ($una_instancia->logo_principal != null) {
                $logo_principal = $una_instancia->logo_principal;
                $cad = date("Y-m-d H:i:s") . $una_instancia->idinstancia;
                $nombrefile = substr(md5($cad), 0, 12);
                $f = fopen("uploads/instancias/" . $nombrefile . ".png", "w") or die("Unable to open file!");
                fwrite($f, base64_decode(explode(",", $logo_principal, 2)[1]));
                $model_cap_instancia = $una_instancia;
                $model_cap_instancia->logo_principal_path = $nombrefile . ".png";
                $model_cap_instancia->save();
                $i++;
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => "Migrando imagenes",
            'size' => Modal::SIZE_SMALL,
            'content' => "terminamos de migrar las imagenes. Total: " . $i,
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
        ];
    }

    public function actionCertificados($id) // $id es el id de la instancia
    {
        $una_cap_instancia = Mds_cap_instancia::find()
            ->where(['idinstancia' => $id])
            ->one();
        $generar = true;
        $sugerir = false;
        $cad_resp = "";
        $cad_resp_sug = "";
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

        if ($generar) {
            /* optimizacion de codigo:*/

            $unas_cap_inscripciones = Mds_cap_inscripcion::find()
                ->where(['idcapinstancia' => $id])
                ->all();
            $i = 0;
            $num_pers_aprob = 0;
            $num_pers_inscrip = 0;
            $num_cert_esp_firm = 0;
            $array_aprobados=[];
            foreach ($unas_cap_inscripciones as $una_cap_inscripcion) {
                switch ($una_cap_inscripcion->termino) {
                    case 2:
                        $array_aprobados[$i] = $una_cap_inscripcion;
                        $i++;
                        $num_pers_aprob++;
                        $num_pers_inscrip++;
                        break;
                    case 6:
                        $array_aprobados[$i] = $una_cap_inscripcion;
                        $i++;
                        $num_pers_aprob++;
                        $num_pers_inscrip++;
                        break;
                    case 1:
                        $num_cert_esp_firm++;
                        $num_pers_inscrip++;
                        break;
                    default:
                        $num_pers_inscrip++;
                }
            }
            /*fin de optimizacion de codigo*/
          
            define('_MPDF_TTFONTPATH', __DIR__ . '/fonts');

            $path_valido = "../web/uploads/certificados/" . $una_cap_instancia->idinstancia;
            if (!file_exists($path_valido)) {
                mkdir($path_valido, 0777, true);
            }
            $cant_cert_imp = 0;
            //foreach($unas_cap_inscripciones as $una_cap_inscripcion)

            /* inicio obtencion de de firmas:*/

            $roles = Mds_cap_docente_instancia::find()->where(['id_instancia' => $una_cap_instancia->idinstancia, 'firmante' => 1])->all();
            $firmas_nombre = array();
            $firmas_cargo = array();
            $firmas_iddocente = array();
            foreach ($roles as $rol) {
                $el_docente = Mds_cap_docente::findOne($rol['id_docente']);
                $per_doc = Sds_com_persona::findOne($el_docente['idpersona']);
                if (($el_docente['profesion_corta'] != null) && ($el_docente['profesion_corta'] != '')) {
                    $id_profesion = $el_docente['profesion_corta'];
                    $la_profesion = Sds_com_configuracion::findOne($id_profesion);
                    $nombre_per = sanear_string(ucwords(strtolower($per_doc['nombre'])));
                    $apellido_per = sanear_string(ucwords(strtolower($per_doc['apellido'])));
                    $cad_nombres_apel = $nombre_per . ' ' . $apellido_per;

                    $firmas_nombre[] = $la_profesion['descripcion'] . " " . $cad_nombres_apel;
                } else {
                    $cad_nombres_apel = ucwords(strtolower($per_doc['nombre'])) . ' ' . ucwords(strtolower($per_doc['apellido']));
                    $firmas_nombre[] = $cad_nombres_apel;
                }
                $firmas_cargo[] = $el_docente['cargo_certificado'];
                $firmas_iddocente[] = $rol['id_docente'];
            }
            /*fin obtencion de firmas*/

            foreach ($array_aprobados as $una_cap_inscripcion) {
                if ($una_cap_inscripcion->estado_cert == 0) {
                    $una_cap_persona = Mds_cap_persona::find()
                        ->where(['idpersonacap' => $una_cap_inscripcion->idpersonacap])
                        ->one();
                    $una_com_persona = Sds_com_persona::find()
                        ->where(['idpersona' => $una_cap_persona->idpersona])
                        ->one();
                    $nombres = $una_cap_instancia->idinstancia . '_' . $una_com_persona->documento;

                    $el_pdf = generar_pdf($una_cap_inscripcion->idinscripcion, $nombres, $this, $path_valido, $firmas_nombre, $firmas_cargo, $firmas_iddocente, $roles);
                    $request = Yii::$app->request;
                    $model_cap_inscripcion = $una_cap_inscripcion;
                    //$model_cap_inscripcion->estado_cert = 1;
                    //cambio de estado nuevo:
                    $model_cap_inscripcion->estado_cert = 2;
                    $el_id_instancia = $model_cap_inscripcion->idcapinstancia;
                    $path_valido2 = "../web/uploads/certificados/" . $el_id_instancia . "/" . $nombres . '.pdf';
                    $path_valido3 = "uploads/certificados/" . $el_id_instancia . "/" . $nombres . '.pdf';
                    $model_cap_inscripcion->path_cert = $path_valido3;
                    $model_cap_inscripcion->save();
                    $cant_cert_imp++;
                }
            }
            $cant_ya_tenian = $num_pers_aprob - $cant_cert_imp;
            if ($sugerir) {
                $texto_exit = '<span class="text-success">Los certificados, de las personas aprobadas de este curso, se generaron exitosamente!<br>Se sugiere complete los siguientes datos en la instancia del curso:' . $cad_resp_sug . '</span>';
            } else {
                $texto_exit = '<span class="text-success">Los certificados, de las <strong>personas aprobadas</strong> de este curso, se generaron exitosamente!<br>  - Numero de Certificados generados anteriormente: ' . $cant_ya_tenian . '<br>  - Numero de Certificados generados ahora: ' . $cant_cert_imp . '</span>';
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Certificados",
                'size' => Modal::SIZE_SMALL,
                'content' => $texto_exit,
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Certificados",
                'size' => Modal::SIZE_SMALL,
                'content' => '<span class="text-success">No se pueden generar los <b>certificados</b>, ya que falta cargar datos en la instancia de la capacitación:' . $cad_resp . '</span>',
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }
    }

    public function actionCert_docentes($id) // $id es el id de la instancia
    {
        $id_de_la_instancia = $id;
        $una_cap_instancia = Mds_cap_instancia::find()
            ->where(['idinstancia' => $id])
            ->one();
        $generar = true;
        $sugerir = false;
        $cad_resp = "";
        $cad_resp_sug = "";
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

        if ($generar) {
            //necesitamos buscar todos los docentes que reciben certificados:
            $los_docentes = Mds_cap_docente_instancia::find()->where(['id_instancia' => $id_de_la_instancia, 'firmante' => 0])->all();

            define('_MPDF_TTFONTPATH', __DIR__ . '/fonts');

            $path_valido = "../web/uploads/certificados/" . $una_cap_instancia->idinstancia;
            if (!file_exists($path_valido)) {
                mkdir($path_valido, 0777, true);
            }
            /* inicio obtencion de de firmas:*/
            // buscamos los docentes que firman:
            $roles = Mds_cap_docente_instancia::find()->where(['id_instancia' => $una_cap_instancia->idinstancia, 'firmante' => 1])->all();
            $firmas_nombre = array();
            $firmas_cargo = array();
            $firmas_iddocente = array();
            foreach ($roles as $rol) {
                $el_docente = Mds_cap_docente::findOne($rol['id_docente']);
                $per_doc = Sds_com_persona::findOne($el_docente['idpersona']);
                if (($el_docente['profesion_corta'] != null) && ($el_docente['profesion_corta'] != '')) {
                    $id_profesion = $el_docente['profesion_corta'];
                    $la_profesion = Sds_com_configuracion::findOne($id_profesion);
                    $nombre_per = sanear_string(ucwords(strtolower($per_doc['nombre'])));
                    $apellido_per = sanear_string(ucwords(strtolower($per_doc['apellido'])));
                    $cad_nombres_apel = $nombre_per . ' ' . $apellido_per;
                    $firmas_nombre[] = $la_profesion['descripcion'] . " " . $cad_nombres_apel;
                } else {
                    $cad_nombres_apel = ucwords(strtolower($per_doc['nombre'])) . ' ' . ucwords(strtolower($per_doc['apellido']));
                    $firmas_nombre[] = $cad_nombres_apel;
                }

                $firmas_cargo[] = $el_docente['cargo_certificado'];
                $firmas_iddocente[] = $rol['id_docente'];
            }

            /*fin obtencion de firmas*/

            // vamos a generar los certificados
            //Obs. en el caso del certificado de un docente que ademas firma, su firma no deberia estar en el certificado
            foreach ($los_docentes as $un_docente) {

                if ($un_docente->estado_cert == 0) {
                    $una_cap_docente = Mds_cap_docente::find()
                        ->where(['idpersona' => $un_docente->id_docente])
                        ->one();

                    $una_com_persona = Sds_com_persona::find()
                        ->where(['idpersona' => $una_cap_docente->idpersona])
                        ->one();
                    $nombres = 'D' . $una_cap_instancia->idinstancia . '_' . $una_com_persona->documento;

                    //quitar la firma del docente al que se le entregara el certificado:
                    $firmas_nombre2 = array();
                    $firmas_cargo2 = array();
                    $firmas_iddocente2 = array();
                    $roles2 = array();
                    $iaux = 0;
                    $jaux = 0;
                    foreach ($roles as $un_rol) {

                        // roles son los los docentes que firman: $roles = Mds_cap_docente_instancia::find()->where(['id_instancia' => $una_cap_instancia->idinstancia, 'firmante' => 1])->all();
                        if ($un_rol['id_docente'] != $un_docente->id_docente) {
                            $firmas_nombre2[$jaux] = $firmas_nombre[$iaux];
                            $firmas_cargo2[$jaux] = $firmas_cargo[$iaux];
                            $firmas_iddocente2[$jaux] = $firmas_iddocente[$iaux];
                            $roles2[$jaux] = $un_rol;
                            $jaux++;
                        }
                        $iaux++;
                    }

                    $el_pdf = generar_pdfdocente($un_docente->iddocenteinstancia, $nombres, $this, $path_valido, $firmas_nombre2, $firmas_cargo2, $firmas_iddocente2, $roles2);
                    $request = Yii::$app->request;
                    $una_docente_ins = Mds_cap_docente_instancia::find()
                        ->where(['iddocenteinstancia' => $un_docente->iddocenteinstancia])
                        ->one();
                    //$una_docente_ins->estado_cert = 1;
                    $una_docente_ins->estado_cert = 2;

                    $el_id_instancia = $una_cap_instancia->idinstancia;
                    $path_valido2 = "../web/uploads/certificados/" . $el_id_instancia . "/" . $nombres . '.pdf';
                    $path_valido3 = "uploads/certificados/" . $el_id_instancia . "/" . $nombres . '.pdf';
                    $una_docente_ins->path_cert = $path_valido3;
                    $una_docente_ins->save();
                }
            }
            $texto_exit = '<span class="text-success">Los certificados de los docentes han sido generados exitosamente</span>';

            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Certificados",
                'size' => Modal::SIZE_SMALL,
                'content' => $texto_exit,
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Certificados",
                'size' => Modal::SIZE_SMALL,
                'content' => '<span class="text-success">No se pueden generar los <b>certificados</b> de los docentes, ya que falta cargar datos en la instancia de la capacitación:' . $cad_resp . '</span>',
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])

            ];
        }
    }

    public function actionPreview_certificado($id) /*$id es  el id de la instancia. agregado por Luis Garcia*/
    {

        $una_cap_inscripcion = Mds_cap_inscripcion::find()
            ->where(['idcapinstancia' => $id])
            ->one();
        $cert = generar_preview($una_cap_inscripcion->idinscripcion); // le envia una inscripcion

        return  $cert;
    }

    public function actionDescargar_certificados($id) /* id es el idinstancia, agregado por Luis Garcia*/
    {
        $unas_cap_inscripciones = Mds_cap_inscripcion::find()
            ->where(['idcapinstancia' => $id, 'termino' => '2'])
            ->andWhere(" estado_cert > 0 ")
            ->all();
        $num_inst = count($unas_cap_inscripciones);
        if ($num_inst > 0) {
            $zip = new ZipArchive();
            // Creamos y abrimos un archivo zip temporal
            $zip->open("../web/uploads/certificados/" . $id . "/certificados_" . $id . ".zip", \ZipArchive::CREATE);

            foreach ($unas_cap_inscripciones as $una_cap_inscripcion) {
                if ($una_cap_inscripcion->estado_cert > 0) {
                    $path_file = "../web/" . $una_cap_inscripcion->path_cert;

                    if (($una_cap_inscripcion->path_cert != null) ||  ($una_cap_inscripcion->path_cert != '')) {
                        $una_cap_persona = Mds_cap_persona::find()
                            ->where(['idpersonacap' => $una_cap_inscripcion->idpersonacap])
                            ->one();
                        $una_com_persona = Sds_com_persona::find()
                            ->where(['idpersona' => $una_cap_persona->idpersona])
                            ->one();
                        $nombres = $una_cap_inscripcion->idcapinstancia . '_' . $una_com_persona->documento . '.pdf';
                        $zip->addFile($path_file, $nombres);
                    }
                }
            }
            // Una vez añadido los archivos deseados cerramos el zip.
            $zip->close();
            // Creamos las cabezeras que forzaran la descarga del archivo como archivo zip.

            header("Content-type: application/octet-stream");
            header("Content-disposition: attachment; filename=certificados_" . $id . ".zip");
            // leemos el archivo creado
            readfile('../web/uploads/certificados/' . $id . '/certificados_' . $id . '.zip');
            // Por último eliminamos el archivo temporal creado
            //unlink('../web/uploads/certificados/'.$id.'/certificados_'.$id.'.zip');//Destruye el archivo temporal
            return \Yii::$app->response->sendFile('../web/uploads/certificados/' . $id . '/certificados_' . $id . '.zip');
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Certificados",
                'size' => Modal::SIZE_SMALL,
                'content' => '<span class="text-success">La instancia de la capacitación <b>no tiene</b> certificados para descargar</span>',
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])

            ];
        }
    }
    /**
     * Displays a single Mds_cap_instancia model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cap_instancia', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'title' => "Instancia numero: " . $id,
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
     * Creates a new Mds_cap_instancia model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_cap_instancia();
        $model->desde = date('d-m-Y');
        $model->hasta = date('d-m-Y');
        $model->fecha_limite = date('d-m-Y');
        $model->fecha_inscripcion = date('d-m-Y');
        $model->capacidad = 0;
        $user  = Yii::$app->user->identity;
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $model->idusuario = $user->idusuario;
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();

                $fecha_desde = ArmarDateParaMySql($model->desde);
                $fecha_desde = date_create($fecha_desde);
                $fecha_desde = date_format($fecha_desde, 'Y-m-d');
                $model->desde = $fecha_desde;

                $fecha_hasta = ArmarDateParaMySql($model->hasta);
                $fecha_hasta = date_create($fecha_hasta);
                $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');
                $model->hasta = $fecha_hasta;

                $limite = ArmarDateParaMySql($model->fecha_limite);
                $limite = date_create($limite);
                $limite = date_format($limite, 'Y-m-d');
                $model->fecha_limite = $limite;

                $limite = ArmarDateParaMySql($model->fecha_inscripcion);
                $limite = date_create($limite);
                $limite = date_format($limite, 'Y-m-d');
                $model->fecha_inscripcion = $limite;

                if($model->fecha_publicacion_cert) {
                    $publicacion = ArmarDateParaMySql($model->fecha_publicacion_cert);
                    $publicacion = date_create($publicacion);
                    $publicacion = date_format($publicacion, 'Y-m-d');
                    $model->fecha_publicacion_cert = $publicacion;
                }

                /*                
                // Upload imagen
                $tmpfile = UploadedFile::getInstance($model, 'temp_imagen');                
                /*if (isset($tmpfile)) {
                    $tmpfile_contents = file_get_contents($tmpfile->tempName);
                    $model->imagen = "data:image/png;base64," . base64_encode($tmpfile_contents);
                }*/
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;

                    $cad = date("Y-m-d H:i:s") . $model->idinstancia;
                    $nuevo_nombre = substr(md5($cad), 0, 15) . "." . $extension;

                    // $nuevo_nombre=$model->random_filename(30, '/uploads/instancias',$extension);
                    $model->imagen_path = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/instancias/' . $nuevo_nombre);
                } else {
                    if ($model->borrar_imagen) {
                        $model->imagen = null;
                        $model->imagen_path = null;
                        //faltaria eliminar la imagen fisica
                    }
                };

                // Upload logo
                $tmplogo = UploadedFile::getInstance($model, 'temp_logo1');
                if (isset($tmplogo)) {
                    /*$tmplogo_contents = file_get_contents($tmplogo->tempName);
                    $model->logo_extra = "data:image/png;base64," . base64_encode($tmplogo_contents);*/
                    $extension = $tmplogo->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/instancias', $extension);
                    $model->logo_extra_path = $nuevo_nombre;
                    $tmplogo->saveAs('uploads/instancias/' . $nuevo_nombre);
                } else {
                    if ($model->borrar_logo1) {
                        $model->logo_extra = null;
                        $model->logo_extra_path = null;
                        //faltaria eliminar la imagen fisica
                    }
                }

                // Upload logo principal
                $tmplogoprinc = UploadedFile::getInstance($model, 'temp_logo_princ');
                if (isset($tmplogoprinc)) {
                    /*$tmplogop_contents = file_get_contents($tmplogoprinc->tempName);
                    $model->logo_principal = "data:image/png;base64," . base64_encode($tmplogop_contents);*/
                    $extension = $tmplogoprinc->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/instancias', $extension);
                    $model->logo_principal_path = $nuevo_nombre;
                    $tmplogoprinc->saveAs('uploads/instancias/' . $nuevo_nombre);
                } else {
                    if ($model->borrar_logo_princ) {
                        $model->logo_principal = null;
                        $model->logo_principal_path = null;
                        //faltaria eliminar la imagen fisica
                    }
                }
                if ($model->inscripcion_externa == 0) {
                    $model->enlace_ext = '';
                }

                if ($model->notificar_admin == 0) {
                    $model->email_administrador = '';
                }

                $guardado = $model->save();

                if ($guardado) {
                    $roles = $model->docentes != null ? $model->docentes : array();
                    $roles_count = count($roles);
                    for ($index_rol = 0; $index_rol < $roles_count; $index_rol++) {
                        $usuario_rol = new Mds_cap_docente_instancia();
                        $usuario_rol->id_instancia = $model->idinstancia;
                        $usuario_rol->id_docente = $roles[$index_rol];
                        $usuario_rol->firmante = 1;

                        if (!$usuario_rol->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                            $model->addError("idinstancia", "No se pudo asociar los docentes FIRMANTES.");
                        }
                    }
                }

                if ($guardado) {
                    $roles2 = $model->docentes_no_firmantes != null ? $model->docentes_no_firmantes : array();
                    $roles_count2 = count($roles2);
                    for ($index_rol2 = 0; $index_rol2 < $roles_count2; $index_rol2++) {
                        $usuario_rol2 = new Mds_cap_docente_instancia();
                        $usuario_rol2->id_instancia = $model->idinstancia;
                        $usuario_rol2->id_docente = $roles2[$index_rol2];
                        $usuario_rol2->firmante = 0;

                        if (!$usuario_rol2->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                            $model->addError("idinstancia", "No se pudo asociar los docentes no firmantes.");
                        }
                    }
                }
                if ($guardado) {
                    $transaction->commit();
                    $model->imagen = "pngBase64";
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_cap_instancia', $model->idinstancia, $model->getAttributes());
                    return $this->redirect(['mds_cap_instancia/index', 'id' => $model->idinstancia]);
                } else {
                    $transaction->rollBack();
                }
            }

            $idcontacto  = Yii::$app->user->identity->idcontacto;
            $idOrganismoExterno  = Yii::$app->user->identity->externo;
            $idOrganismo = null;
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

            if ($idOrganismo) {
                $filterCapacitaciones = $this->getFilterCapacitaciones("INTERNO", $idOrganismo, $idusuario, $idcontacto);
            } else {
                $filterCapacitaciones = $this->getFilterCapacitaciones("EXTERNO", $idOrganismoExterno, $idusuario, null);
            }


            return $this->render('create', [
                'model' => $model,
                'filterCapacitaciones' => $filterCapacitaciones
            ]);
        }
    }

    /**
     * Updates an existing Mds_cap_instancia model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $nofirmante_borrar = Mds_cap_docente_instancia::find()->where(["id_instancia" => $model->idinstancia, 'firmante' => 0])->all();
        $firmante_borrar = Mds_cap_docente_instancia::find()->where(["id_instancia" => $model->idinstancia, 'firmante' => 1])->all();
        $model->borrar_imagen = false;
        $model->borrar_logo_princ = false;
        $model->borrar_logo1 = false;


        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
        } else {
            /*
            *   Process for non-ajax request
            */


            if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();

                $fecha_desde = ArmarDateParaMySql($model->desde);
                $fecha_desde = date_create($fecha_desde);
                $fecha_desde = date_format($fecha_desde, 'Y-m-d');
                $model->desde = $fecha_desde;

                $fecha_hasta = ArmarDateParaMySql($model->hasta);
                $fecha_hasta = date_create($fecha_hasta);
                $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');
                $model->hasta = $fecha_hasta;

                $limite = ArmarDateParaMySql($model->fecha_limite);
                $limite = date_create($limite);
                $limite = date_format($limite, 'Y-m-d');
                $model->fecha_limite = $limite;

                $limite = ArmarDateParaMySql($model->fecha_inscripcion);
                $limite = date_create($limite);
                $limite = date_format($limite, 'Y-m-d');
                $model->fecha_inscripcion = $limite;

                if($model->fecha_publicacion_cert) {
                    $publicacion = ArmarDateParaMySql($model->fecha_publicacion_cert);
                    $publicacion = date_create($publicacion);
                    $publicacion = date_format($publicacion, 'Y-m-d');
                    $model->fecha_publicacion_cert = $publicacion;
                }

                $tmpfile = UploadedFile::getInstance($model, 'temp_imagen');
                if (isset($tmpfile)) //siempre se guarda en archivo
                {
                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/instancias', $extension);
                    $model->imagen_path = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/instancias/' . $nuevo_nombre);
                } else {
                    if ($model->borrar_imagen) {
                        $model->imagen = null;
                        $model->imagen_path = null;
                        //faltaria eliminar la imagen fisica
                    }
                };
                $tmpfile = UploadedFile::getInstance($model, 'temp_logo1');
                if (isset($tmpfile)) //siempre se guarda en archivo
                {
                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/instancias', $extension);
                    $model->logo_extra_path = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/instancias/' . $nuevo_nombre);
                } else {
                    if ($model->borrar_logo1) {
                        $model->logo_extra = null;
                        $model->logo_extra_path = null;
                        //faltaria eliminar la imagen fisica
                    }
                };
                // Upload logo principal
                $tmpfile = UploadedFile::getInstance($model, 'temp_logo_princ');
                if (isset($tmpfile)) //siempre se guarda en archivo
                {
                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/instancias', $extension);
                    $model->logo_principal_path = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/instancias/' . $nuevo_nombre);
                } else {
                    if ($model->borrar_logo_princ) {
                        $model->logo_principal = null;
                        $model->logo_principal_path = null;
                        //faltaria eliminar la imagen fisica
                    }
                };
              
                if ($model->inscripcion_externa == 0) {
                    $model->enlace_ext = '';
                }

                if ($model->notificar_admin == 0) {
                    $model->email_administrador = '';
                }

                $guardado = $model->save();

                if ($guardado) {
                    $roles = $model->docentes != null ? $model->docentes : array();
                    if ($firmante_borrar != null) {
                        foreach ($firmante_borrar as $informe) {
                            $informe->delete();
                        }
                    }
                    $roles_count = count($roles);
                    for ($index_rol = 0; $index_rol < $roles_count; $index_rol++) {
                        $usuario_rol = new Mds_cap_docente_instancia();
                        $usuario_rol->id_instancia = $model->idinstancia;
                        $usuario_rol->id_docente = $roles[$index_rol];
                        $usuario_rol->firmante = 1;

                        if (!$usuario_rol->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                            $model->addError("idinstancia", "No se pudo asociar los docentes FIRMANTES.");
                        }
                    }
                }

                if ($guardado) {
                    $roles2 = $model->docentes_no_firmantes != null ? $model->docentes_no_firmantes : array();
                    if ($nofirmante_borrar != null) {
                        foreach ($nofirmante_borrar as $informe) {
                            $informe->delete();
                        }
                    }
                    $roles_count2 = count($roles2);
                    for ($index_rol2 = 0; $index_rol2 < $roles_count2; $index_rol2++) {
                        $usuario_rol2 = new Mds_cap_docente_instancia();
                        $usuario_rol2->id_instancia = $model->idinstancia;
                        $usuario_rol2->id_docente = $roles2[$index_rol2];
                        $usuario_rol2->firmante = 0;

                        if (!$usuario_rol2->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                            $model->addError("idinstancia", "No se pudo asociar los docentes no firmantes.");
                        }
                    }
                }
                if ($guardado) {
                    $transaction->commit();
                    $model->imagen = "pngBase64";
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_cap_instancia', $id, $model->getAttributes());
                    return $this->redirect(['mds_cap_instancia/index', 'id' => $model->idinstancia]);
                } else {
                    $transaction->rollBack();
                }
            }

            $usuario = Yii::$app->user->identity;
            $idusuario = $usuario != null ? $usuario->idusuario : null;
            $idcontacto  = Yii::$app->user->identity->idcontacto;
            $idOrganismoExterno  = Yii::$app->user->identity->externo;
            $idOrganismo = null;
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

            if ($idOrganismo) {
                $filterCapacitaciones = $this->getFilterCapacitaciones("INTERNO", $idOrganismo, $idusuario, $idcontacto);
            } else {
                $filterCapacitaciones = $this->getFilterCapacitaciones("EXTERNO", $idOrganismoExterno, $idusuario, null);
            }

            return $this->render('create', [
                'model' => $model,
                'filterCapacitaciones' => $filterCapacitaciones
            ]);
        }
    }

    /**
     * Delete an existing Mds_cap_instancia model.
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
            $model->imagen = "pngBase64";
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_cap_instancia', $id, $model->getAttributes());
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
     * Finds the Mds_cap_instancia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_cap_instancia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_cap_instancia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Arma el select para filtrar las capacitaciones. Recibe un tipo (INTERNO/EXTERNO) y el ID del organismo
     */
    protected function getFilterCapacitaciones($tipo, $id, $idusuario = null, $idcontacto = null)
    {

        $itemGlobal = Mds_seg_item::MODULO_CAP_GLOBAL;
        $permisos = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                        idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario AND iditem = $itemGlobal)")->all();
        $permiso_global = 0;
        if ($permisos && count($permisos) > 0) {
            $permiso_global = 1;
        }

        if ($tipo === "INTERNO") {

            $filterCapacitaciones =  Mds_cap_capacitacion::find()->where("idorganismo in (select disp.idorganismo
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
            and idcontacto=$idcontacto) or 1=$permiso_global")->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
            $filterCapacitaciones = ArrayHelper::map($filterCapacitaciones, 'idcapacitacion', 'descripcion');
        } else {
            // EXTERNO
            $filterCapacitaciones =  Mds_cap_capacitacion::find()->where(['idorganismoexterno' => $id])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
            $filterCapacitaciones = ArrayHelper::map($filterCapacitaciones, 'idcapacitacion', 'descripcion');
        }

        return $filterCapacitaciones;
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
    

    function  generar_pdf($idinscripcion, $nombres, $objeto, $path_valido, $firmas_nombre, $firmas_cargo, $firmas_iddocente, $roles)
    {
        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        $una_cap_inscripcion = Mds_cap_inscripcion::find()
            ->where(['idinscripcion' => $idinscripcion])
            ->one();

        $una_cap_persona = Mds_cap_persona::find()
            ->where(['idpersonacap' => $una_cap_inscripcion->idpersonacap])
            ->one();

        $una_com_persona = Sds_com_persona::find()
            ->where(['idpersona' => $una_cap_persona->idpersona])
            ->one();

        $una_cap_instancia = Mds_cap_instancia::find()
            ->where(['idinstancia' => $una_cap_inscripcion->idcapinstancia])
            ->one();

        $una_cap_capacitacion = Mds_cap_capacitacion::find()
            ->where(['idcapacitacion' => $una_cap_instancia->idcapacitacion])
            ->one();

        $nombre_per2 = sanear_string(ucwords(strtolower($una_com_persona->nombre)));
        $apellido_per2 = sanear_string(ucwords(strtolower($una_com_persona->apellido)));
        $cad_name = $nombre_per2 . ' ' . $apellido_per2;

        //$cad_name=ucwords(strtolower($una_com_persona->nombre)).' '.ucwords(strtolower($una_com_persona->apellido));    

        $dni = number_format($una_com_persona->documento, 0, '', '.');
        $titulo_cap = $una_cap_capacitacion->descripcion;
        $aval = $una_cap_instancia->resolucion_aval;
        $no_tiene_aval = (($aval == null) || ($aval == ""));
        $area_certificado = $una_cap_instancia->area_certificado;
        $modalidad = $una_cap_instancia->presencial;

        if ($modalidad == 0) {
            $modalidad = "presencial";
        } else {
            if ($modalidad == 1) {
                $modalidad = "virtual";
            } else {
                if ($modalidad == 2) {
                    $modalidad = "dual";
                }
            }
        }
        $cant_horas = $una_cap_instancia->cant_horas;

        $desde = $una_cap_instancia->desde;
        $unafecha = explode("-", $desde);
        $mes_desde = intval(trim($unafecha[1]));

        $anio_desde = trim($unafecha[0]);
        $dia_desde = trim($unafecha[2]);
        $el_mes_desde = $meses[$mes_desde - 1];

        $hasta = $una_cap_instancia->hasta;
        $unafecha = explode("-", $hasta);
        $hasta = trim($hasta[2]) . "/" . trim($hasta[1]) . "/" . trim($hasta[0]);
        $mes_hasta = intval(trim($unafecha[1]));

        $anio_hasta = trim($unafecha[0]);
        $dia_hasta = trim($unafecha[2]);
        $el_mes_hasta = $meses[$mes_hasta - 1];


        if($una_cap_instancia->desde==$una_cap_instancia->hasta)
        {
            $intervalo = " el día " . $dia_desde ." de " . $el_mes_hasta . " del año " . $anio_desde;

        }
        else
        {
            if ($anio_desde == $anio_hasta) {
                $intervalo = " desde " . $dia_desde . " de " . $el_mes_desde . " al " . $dia_hasta . " de " . $el_mes_hasta . " del año " . $anio_desde;
            } else {
                $intervalo = " desde " . $dia_desde . " de " . $el_mes_desde . " del año " . $anio_desde . " al " . $dia_hasta . " de " . $el_mes_hasta . " del año " . $anio_hasta;
            }
        }


        

        $logo_extra = $una_cap_instancia->logo_extra;
        if ($una_cap_instancia->logo_extra_path == null) {
            $logo_extra = $una_cap_instancia->logo_extra;
        } else {
            $logo_extra = '../web/uploads/instancias/' . $una_cap_instancia->logo_extra_path;
        }
        $logo_principal = $una_cap_instancia->logo_principal;
        if ($una_cap_instancia->logo_principal_path == null) {
            $logo_principal = $una_cap_instancia->logo_principal;
        } else {
            $logo_principal = '../web/uploads/instancias/' . $una_cap_instancia->logo_principal_path;
        }
        $codigo_qr = md5($una_cap_inscripcion->idinscripcion);
        $qrCode = (new QrCode("https://cumbre.neuquen.gov.ar/validator?codigo=" . $codigo_qr))
            ->setSize(100)
            ->setMargin(0)
            ->useForegroundColor(2, 2, 2);

        $el_model = Mds_cap_inscripcion::findOne($una_cap_inscripcion->idinscripcion);
        $el_model->codigo_qr = $codigo_qr;
        $el_model->save();


        $num_firmas = count($firmas_nombre);
        $html = '
        <html>    
        <body>';
        
        if (($logo_extra == null) || ($logo_extra == "")) {

            if (($logo_principal == null) || ($logo_principal == "")) {
                $html .= '
                    <div class="div_banner2">	                 
                       <img class="img_banner1" src="../web/img/banner2.png">
                    </div>';
            } else {
                $archivoImagen = $logo_principal;
                if ( file_exists ( $archivoImagen ) ) {
                list ( $a, $h, $t ) = getimagesize ( $archivoImagen );
                }
                if((isset($a)) && ($a==2175))
                {$html .= '
                    <div class="div_banner2aux" >
                        <img class="img_banneraux" src="' . $logo_principal . '">
                    </div>';

                }else
                if((isset($a)) && ($h>400))
                {
                    $html .= '
                    <div class="div_banner2_max" >
                        <img class="img_banner1_max" src="' . $logo_principal . '">
                    </div>';
                }
            
                else
                {
                    $html .= '
                    <div class="div_banner2" >	
                        <img class="img_banner1" src="' . $logo_principal . '">
                    </div>';
                }
                
            }
        } 
        else 
        {
            if (($logo_principal == null) || ($logo_principal == "")) {
                $html .= '
                    <div class="div_banner1" >	 
                        <img class="img_banner1" src="../web/img/banner1.png">
                    </div>
                    <div class="div_logo_extra" >
                        <img   class="img_logoextra" src="' . $logo_extra . '" >
                    </div>';
        } 
        else 
        {
                $html .= '
                    <div class="div_banner1" >	 
                        <img class="img_banner1" src="' . $logo_principal . '">
                    </div>
                    <div class="div_logo_extra" >
                        <img   class="img_logoextra" src="' . $logo_extra . '" >
                    </div>';
            }
        }




        $html .= '
        <p class="header1" >CERTIFICADO</p>
        <!--<p class="parrafo1">Por cuanto se Certifica que:</p> -->
        <p class="nombre">' . $cad_name . '</p>';

        $tit_br = strpos($titulo_cap, '<br>');
        $html .= '
        <p class="dni">D.N.I.: ' . $dni . '</p>';

        $html .= '<p class="';
        if ($tit_br == false) {
            $html .= 'cad_aprob';
        } else {
            $html .= 'cad_aprob_br';
        }
        $html .= '">';



        if ($una_cap_inscripcion->termino==0)
        {$html .=  "se ha inscripto";}
        else
        { 
            if($una_cap_inscripcion->termino==1)
            {$html .=  "esta cursando</p>";}
            else 
            {
                if($una_cap_inscripcion->termino==2)
                {
                    $html .=  "ha aprobado</p>";
                }
                else
                {
                    if($una_cap_inscripcion->termino==3)
                    {
                        $html .=  "ha desaprobado</p>";
                    }
                    else
                    {

                        if($una_cap_inscripcion->termino==6)
                        {
                            $html .=  "ha participado de</p>";
                        }
                    }
                }
            } 
        }



        $html .= '<p class="';

        if ($tit_br == false) {
            $html .= 'curso';
        } else {
            $html .= 'curso_br';
        }
        $html .= '">' . $titulo_cap . '</p>';

        $html .= '
        <div style="position:absolute;top: 665px; left: 47px;">
            <img src="' . $qrCode->writeDataUri() . '"  >
        </div>';

        if ($no_tiene_aval) {
        } else {
            $html .= '<p class="';
            if ($tit_br == false) {
                $html .= 'aval';
            } else {
                $html .= 'aval_br';
            }
            $html .= '">Avalado por ' . $aval . '</p>';
        }


        if ($tit_br == false) {
            $html .= '<p class="';
            if ($no_tiene_aval) {
                $html .= 'organizado_por_2';
            } else {
                $html .= 'organizado_por_1';
            }
            $html .= '" > Organizado por ' . $area_certificado . ', <br>';
        } else {
            $html .= '<p class="';
            if ($no_tiene_aval) {
                $html .= 'organizado_por_2_aval';
            } else {
                $html .= 'organizado_por_1_aval';
            }
            $html .= '"> Organizado por ' . $area_certificado . ', <br>';
        }


        $html .= 'bajo modalidad ' . $modalidad . ', ' . $intervalo;
        if (($cant_horas == 0) || ($cant_horas == null) || ($una_cap_inscripcion->termino == 6)) {
            $html .= '.-';
        } else {
            $html .= ',<br>con una duración de ' . $cant_horas . ' horas reloj con evaluación final.-';
        }
        $html .= '
        </p>';

        if ($num_firmas == 1) {
            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma11"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }
            $html .= '<div class="tabla_firmasx1">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }

        if ($num_firmas == 2) {
            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma21"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 2) {
                        $html .= '<div class="img_firma22"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }
            $html .= '<div class="tabla_firmasx2">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[1] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[1] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }
        if ($num_firmas == 3) {
            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma31"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 2) {
                        $html .= '<div class="img_firma32"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 3) {
                        $html .= '<div class="img_firma33"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }
            $html .= '<div class="tabla_firmasx3">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td valign="top">';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';
            $html .= '</td>';
            $html .= '<td style=" width:203px; " valign="top">';
            $tam2 = strlen($firmas_nombre[1]);
            /*if ($tam2>27)
                    {
                        $html.= '<img class="img_lineafirma2" src="../web/img/lineafirma.png">';
                    }
                    else
                    {
                        $html.= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
                    }*/
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[1] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[1] . '</p>';

            $html .= '</td>';

            $html .= '<td valign="top">';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[2] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[2] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }
        if ($num_firmas == 4) {

            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma51"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 2) {
                        $html .= '<div class="img_firma52"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 3) {
                        $html .= '<div class="img_firma53"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 4) {
                        $html .= '<div class="img_firma44"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }
            $html .= '<div class="tabla_firmasx4">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[1] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[1] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[2] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[2] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
            $html .= '<div class="tabla_firmasx4b">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[3] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[3] . '</p>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }

        if ($num_firmas == 5) {
            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma51"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 2) {
                        $html .= '<div class="img_firma52"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 3) {
                        $html .= '<div class="img_firma53"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 4) {
                        $html .= '<div class="img_firma54"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 5) {
                        $html .= '<div class="img_firma55"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }


            $html .= '<div class="tabla_firmasx5">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';


            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            //echo '<img  src="'.$firmas_imagen[0].'" >';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[1] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[1] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            //echo '<img  src="'.$firmas_imagen[2].'" >';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[2] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[2] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
            $html .= '<div class="tabla_firmasx5b">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            //echo '<img  src="'.$firmas_imagen[3].'" >';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[3] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[3] . '</p>';
            $html .= '<td>';
            $html .= '<td>';
            //echo '<img  src="'.$firmas_imagen[4].'" >';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[4] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[4] . '</p>';
            $html .= '<td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }




        $html .= ' 
        </body>    
        </html>';
        //$content = $objeto->renderPartial('/mds_cap_instancia/certificado', ['id' => $idinscripcion]); // setup kartik\mpdf\Pdf component 
        $content = $html;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'filename' => $path_valido . '/' . $nombres . '.pdf',
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
                'SetMargins' => [0, 0, 0, 0],
            ]
        ]);


        return $pdf->render();
    }
    function generar_preview($idinscripcion)
    {
        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        $una_cap_inscripcion = Mds_cap_inscripcion::find()
            ->where(['idinscripcion' => $idinscripcion])
            ->one();

        $una_cap_persona = Mds_cap_persona::find()
            ->where(['idpersonacap' => $una_cap_inscripcion->idpersonacap])
            ->one();

        $una_com_persona = Sds_com_persona::find()
            ->where(['idpersona' => $una_cap_persona->idpersona])
            ->one();

        $una_cap_instancia = Mds_cap_instancia::find()
            ->where(['idinstancia' => $una_cap_inscripcion->idcapinstancia])
            ->one();
        $un_id_instancia = $una_cap_instancia->idinstancia;

        $una_cap_capacitacion = Mds_cap_capacitacion::find()
            ->where(['idcapacitacion' => $una_cap_instancia->idcapacitacion])
            ->one();


        $nombre_per2 = sanear_string(ucwords(strtolower($una_com_persona->nombre)));
        $apellido_per2 = sanear_string(ucwords(strtolower($una_com_persona->apellido)));
        $cad_name = $nombre_per2 . ' ' . $apellido_per2;


        //$cad_name=ucwords(strtolower($una_com_persona->nombre)).' '.ucwords(strtolower($una_com_persona->apellido));    
        $dni = number_format($una_com_persona->documento, 0, '', '.');
        $titulo_cap = $una_cap_capacitacion->descripcion;
        $aval = $una_cap_instancia->resolucion_aval;

        $no_tiene_aval = (($aval == null) || ($aval == ""));
        $area_certificado = $una_cap_instancia->area_certificado;

        $res_area = (($una_cap_instancia->area_certificado == null) || ($una_cap_instancia->area_certificado == ""));
        if ($res_area) {
            $area_certificado .= " 'No se definió el área interviniente en la instancia' ";
        }

        $modalidad = $una_cap_instancia->presencial;

        if ($modalidad == 0) {
            $modalidad = "presencial";
        } else {
            if ($modalidad == 1) {
                $modalidad = "virtual";
            } else {
                if ($modalidad == 2) {
                    $modalidad = "dual";
                }
            }
        }
        $cant_horas = $una_cap_instancia->cant_horas;

        $desde = $una_cap_instancia->desde; 
        $unafecha = explode("-", $desde);
        $mes_desde = intval(trim($unafecha[1]));

        $anio_desde = trim($unafecha[0]);
        $dia_desde = trim($unafecha[2]);
        $el_mes_desde = $meses[$mes_desde - 1];

        $hasta = $una_cap_instancia->hasta;
        $unafecha = explode("-", $hasta);
        $hasta = trim($hasta[2]) . "/" . trim($hasta[1]) . "/" . trim($hasta[0]);
        $mes_hasta = intval(trim($unafecha[1]));

        $anio_hasta = trim($unafecha[0]);
        $dia_hasta = trim($unafecha[2]);
        $el_mes_hasta = $meses[$mes_hasta - 1];


        if($una_cap_instancia->desde==$una_cap_instancia->hasta)
        {
            $intervalo = " el día " . $dia_desde ." de " . $el_mes_hasta . " del año " . $anio_desde;

        }
        else
        {
            if ($anio_desde == $anio_hasta) {
                $intervalo = " desde " . $dia_desde . " de " . $el_mes_desde . " al " . $dia_hasta . " de " . $el_mes_hasta . " del año " . $anio_desde;
            } else {
                $intervalo = " desde " . $dia_desde . " de " . $el_mes_desde . " del año " . $anio_desde . " al " . $dia_hasta . " de " . $el_mes_hasta . " del año " . $anio_hasta;
            }

        }

        

        $logo_extra = $una_cap_instancia->logo_extra;
        if ($una_cap_instancia->logo_extra_path == null) {
            $logo_extra = $una_cap_instancia->logo_extra;
        } else {
            $logo_extra = '../web/uploads/instancias/' . $una_cap_instancia->logo_extra_path;
        }
        $logo_principal = $una_cap_instancia->logo_principal;
        if ($una_cap_instancia->logo_principal_path == null) {
            $logo_principal = $una_cap_instancia->logo_principal;
        } else {
            $logo_principal = '../web/uploads/instancias/' . $una_cap_instancia->logo_principal_path;
        }



        $codigo_qr = md5($una_cap_inscripcion->idinscripcion);
        $qrCode = (new QrCode("https://cumbre.neuquen.gov.ar/validator?codigo=" . $codigo_qr))
            ->setSize(100)
            ->setMargin(0)
            ->useForegroundColor(2, 2, 2);


        $roles = Mds_cap_docente_instancia::find()->where(['id_instancia' => $una_cap_inscripcion->idcapinstancia, 'firmante' => 1])->all();
        $firmas_nombre = array();
        $firmas_cargo = array();
        $firmas_iddocente = array();
        foreach ($roles as $rol) {
            $el_docente = Mds_cap_docente::findOne($rol['id_docente']);
            $per_doc = Sds_com_persona::findOne($el_docente['idpersona']);
            if (($el_docente['profesion_corta'] != null) && ($el_docente['profesion_corta'] != '')) {
                $id_profesion = $el_docente['profesion_corta'];
                $la_profesion = Sds_com_configuracion::findOne($id_profesion);

                $nombre_per2 = sanear_string(ucwords(strtolower($per_doc['nombre'])));
                $apellido_per2 = sanear_string(ucwords(strtolower($per_doc['apellido'])));
                $cad_nombres_apel = $nombre_per2 . ' ' . $apellido_per2;

                //$cad_nombres_apel=ucwords(strtolower($per_doc['nombre'])).' '.ucwords(strtolower($per_doc['apellido']));
                $firmas_nombre[] = $la_profesion['descripcion'] . " " . $cad_nombres_apel;
            } else {
                $cad_nombres_apel = ucwords(strtolower($per_doc['nombre'])) . ' ' . ucwords(strtolower($per_doc['apellido']));
                $firmas_nombre[] = $cad_nombres_apel;
            }

            $firmas_cargo[] = $el_docente['cargo_certificado'];
            $firmas_iddocente[] = $rol['id_docente'];
        }
        $num_firmas = count($firmas_nombre);
        $html = '
        <html>    
        <body>';
        if (($logo_extra == null) || ($logo_extra == "")) {

            if (($logo_principal == null) || ($logo_principal == "")) {
                $html .= '
                    <div class="div_banner2">	                 
                       <img class="img_banner1" src="../web/img/banner2.png">
                    </div>';
            } else {
                $archivoImagen = $logo_principal;
                if ( file_exists ( $archivoImagen ) ) {
                list ( $a, $h, $t ) = getimagesize ( $archivoImagen );
                }
                if((isset($a)) && ($a==2175))
                {$html .= '
                    <div class="div_banner2aux" >
                        <img class="img_banneraux" src="' . $logo_principal . '">
                    </div>';

                }else
                if((isset($a)) && ($h>400))
                {
                    $html .= '
                    <div class="div_banner2_max" >
                        <img class="img_banner1_max" src="' . $logo_principal . '">
                    </div>';
                }
            
                else
                {
                    $html .= '
                    <div class="div_banner2" >	
                        <img class="img_banner1" src="' . $logo_principal . '">
                    </div>';
                }
                
            }
        } 
        else 
        {
            if (($logo_principal == null) || ($logo_principal == "")) {
                $html .= '
                    <div class="div_banner1" >	 
                        <img class="img_banner1" src="../web/img/banner1.png">
                    </div>
                    <div class="div_logo_extra" >
                        <img   class="img_logoextra" src="' . $logo_extra . '" >
                    </div>';
        } 
        else 
        {
                $html .= '
                    <div class="div_banner1" >	 
                        <img class="img_banner1" src="' . $logo_principal . '">
                    </div>
                    <div class="div_logo_extra" >
                        <img   class="img_logoextra" src="' . $logo_extra . '" >
                    </div>';
            }
        }
        $html .= '
        <p class="header1" >CERTIFICADO</p>
        <!--<p class="parrafo1">Por cuanto se Certifica que:</p>-->
        <p class="nombre">' . $cad_name . '</p>
        <p class="dni">D.N.I.: ' . $dni . '</p> ';

        if ($una_cap_inscripcion->termino==0)
        {$html .= '<p class="cad_aprob">se ha inscripto</p>';}
        else
        { 
            if($una_cap_inscripcion->termino==1)
            {$html .= '<p class="cad_aprob">esta cursando</p>';}
            else 
            {
                if($una_cap_inscripcion->termino==2)
                {
                    $html .= '<p class="cad_aprob">ha aprobado</p>';
                }
                else
                {
                    if($una_cap_inscripcion->termino==3)
                    {
                        $html .= '<p class="cad_aprob">ha desaprobado</p>';
                    }
                    else
                    {

                        if($una_cap_inscripcion->termino==6)
                        {
                            $html .= '<p class="cad_aprob">ha participado de</p> ';
                        }
                    }
                }
            } 
        }

        $tit_br = strpos($titulo_cap, '<br>');
        $html .= '<p class="';

        if ($tit_br == false) {
            $html .= 'curso';
        } else {
            $html .= 'curso_br';
        }
        $html .= '">' . $titulo_cap . '</p>';

        $html .= '

        <div style="position:absolute;top: 665px; left: 47px;">
            <img src="' . $qrCode->writeDataUri() . '"  >
        </div>';

        if ($no_tiene_aval) {
        } else {
            $html .= '<p class="';
            if ($tit_br == false) {
                $html .= 'aval';
            } else {
                $html .= 'aval_br';
            }
            $html .= '">Avalado por ' . $aval . '</p>';
        }

        if ($tit_br == false) {
            $html .= '<p class="';
            if ($no_tiene_aval) {
                $html .= 'organizado_por_2';
            } else {
                $html .= 'organizado_por_1';
            }
            $html .= '" > Organizado por ' . $area_certificado . ', <br>';
        } else {
            $html .= '<p class="';
            if ($no_tiene_aval) {
                $html .= 'organizado_por_2_aval';
            } else {
                $html .= 'organizado_por_1_aval';
            }
            $html .= '"> Organizado por ' . $area_certificado . ', <br>';
        }
        $html .= 'bajo modalidad ' . $modalidad . ', ' . $intervalo;

        if (($cant_horas == 0) || ($cant_horas == null) || ($una_cap_inscripcion->termino == 6)) {
            $html .= '.-';
        } else {
            $html .= ',<br>con una duración de ' . $cant_horas . ' horas reloj con evaluación final.-';
        }
        $html .= '
        </p>';



        if ($num_firmas == 1) {
            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma11"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }
            $html .= '<div class="tabla_firmasx1">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }

        if ($num_firmas == 2) {
            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma21"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 2) {
                        $html .= '<div class="img_firma22"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }
            $html .= '<div class="tabla_firmasx2">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[1] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[1] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }
        if ($num_firmas == 3) {
            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma31"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 2) {
                        $html .= '<div class="img_firma32"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 3) {
                        $html .= '<div class="img_firma33"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }
            $html .= '<div class="tabla_firmasx3">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td valign="top">';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '<td style=" width:203px; " valign="top">';

            $tam2 = strlen($firmas_nombre[1]);
            /*if ($tam2>27)
                    {
                        $html.= '<img class="img_lineafirma2" src="../web/img/lineafirma.png">';
                    }
                    else
                    {
                        $html.= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
                    }*/
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[1] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[1] . '</p>';

            $html .= '</td>';

            $html .= '<td valign="top">';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[2] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[2] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }
        if ($num_firmas == 4) {

            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma51"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 2) {
                        $html .= '<div class="img_firma52"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 3) {
                        $html .= '<div class="img_firma53"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 4) {
                        $html .= '<div class="img_firma44"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }
            $html .= '<div class="tabla_firmasx4">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[1] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[1] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[2] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[2] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
            $html .= '<div class="tabla_firmasx4b">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[3] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[3] . '</p>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }

        if ($num_firmas == 5) {
            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma51"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 2) {
                        $html .= '<div class="img_firma52"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 3) {
                        $html .= '<div class="img_firma53"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 4) {
                        $html .= '<div class="img_firma54"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 5) {
                        $html .= '<div class="img_firma55"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }


            $html .= '<div class="tabla_firmasx5">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';


            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            //echo '<img  src="'.$firmas_imagen[0].'" >';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[1] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[1] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            //echo '<img  src="'.$firmas_imagen[2].'" >';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[2] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[2] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
            $html .= '<div class="tabla_firmasx5b">
                    <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            //echo '<img  src="'.$firmas_imagen[3].'" >';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[3] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[3] . '</p>';
            $html .= '<td>';
            $html .= '<td>';
            //echo '<img  src="'.$firmas_imagen[4].'" >';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[4] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[4] . '</p>';
            $html .= '<td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }




        $html .= ' 
        </body>    
        </html>';
        //$content = $objeto->renderPartial('/mds_cap_instancia/certificado', ['id' => $idinscripcion]); // setup kartik\mpdf\Pdf component 
        $content = $html;
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


        return $pdf->render();
    }


    function generar_pdfdocente($iddocenteinstancia, $nombres, $objeto, $path_valido, $firmas_nombre, $firmas_cargo, $firmas_iddocente, $roles)
    {
        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];


        $una_cap_docente_instancia = Mds_cap_docente_instancia::find()
            ->where(['iddocenteinstancia' => $iddocenteinstancia])
            ->one();

        $un_cap_docente = Mds_cap_docente::find()
            ->where(['idpersona' => $una_cap_docente_instancia->id_docente])
            ->one();

        $una_com_persona = Sds_com_persona::find()
            ->where(['idpersona' => $un_cap_docente->idpersona])
            ->one();

        $una_cap_instancia = Mds_cap_instancia::find()
            ->where(['idinstancia' => $una_cap_docente_instancia->id_instancia])
            ->one();



        $una_cap_capacitacion = Mds_cap_capacitacion::find()
            ->where(['idcapacitacion' => $una_cap_instancia->idcapacitacion])
            ->one();

        $nombre_per2 = sanear_string(ucwords(strtolower($una_com_persona->nombre)));
        $apellido_per2 = sanear_string(ucwords(strtolower($una_com_persona->apellido)));
        $cad_name = $nombre_per2 . ' ' . $apellido_per2;

        //$cad_name=ucwords(strtolower($una_com_persona->nombre)).' '.ucwords(strtolower($una_com_persona->apellido));    

        $dni = number_format($una_com_persona->documento, 0, '', '.');
        $titulo_cap = $una_cap_capacitacion->descripcion;
        $aval = $una_cap_instancia->resolucion_aval;
        $no_tiene_aval = (($aval == null) || ($aval == ""));
        $area_certificado = $una_cap_instancia->area_certificado;
        $modalidad = $una_cap_instancia->presencial;

        if ($modalidad == 0) {
            $modalidad = "presencial";
        } else {
            if ($modalidad == 1) {
                $modalidad = "virtual";
            } else {
                if ($modalidad == 2) {
                    $modalidad = "dual";
                }
            }
        }
        $cant_horas = $una_cap_instancia->cant_horas;

        $desde = $una_cap_instancia->desde;
        $unafecha = explode("-", $desde);
        $mes_desde = intval(trim($unafecha[1]));

        $anio_desde = trim($unafecha[0]);
        $dia_desde = trim($unafecha[2]);
        $el_mes_desde = $meses[$mes_desde - 1];

        $hasta = $una_cap_instancia->hasta;
        $unafecha = explode("-", $hasta);
        $hasta = trim($hasta[2]) . "/" . trim($hasta[1]) . "/" . trim($hasta[0]);
        $mes_hasta = intval(trim($unafecha[1]));

        $anio_hasta = trim($unafecha[0]);
        $dia_hasta = trim($unafecha[2]);
        $el_mes_hasta = $meses[$mes_hasta - 1];

        if($una_cap_instancia->desde==$una_cap_instancia->hasta)
        {
            $intervalo = " el día " . $dia_desde ." de " . $el_mes_hasta . " del año " . $anio_desde;

        }
        else
        {
            if ($anio_desde == $anio_hasta) {
                $intervalo = " desde " . $dia_desde . " de " . $el_mes_desde . " al " . $dia_hasta . " de " . $el_mes_hasta . " del año " . $anio_desde;
            } else {
                $intervalo = " desde " . $dia_desde . " de " . $el_mes_desde . " del año " . $anio_desde . " al " . $dia_hasta . " de " . $el_mes_hasta . " del año " . $anio_hasta;
            }
        
        }



        
        $logo_extra = $una_cap_instancia->logo_extra;
        if ($una_cap_instancia->logo_extra_path == null) {
            $logo_extra = $una_cap_instancia->logo_extra;
        } else {
            $logo_extra = '../web/uploads/instancias/' . $una_cap_instancia->logo_extra_path;
        }
        $logo_principal = $una_cap_instancia->logo_principal;
        if ($una_cap_instancia->logo_principal_path == null) {
            $logo_principal = $una_cap_instancia->logo_principal;
        } else {
            $logo_principal = '../web/uploads/instancias/' . $una_cap_instancia->logo_principal_path;
        }
        $codigo_qr = md5($una_cap_docente_instancia->iddocenteinstancia);
        $cad_qr = "https://cumbre.neuquen.gov.ar/validator?codigo=" . $codigo_qr . "&tipo=2";
        $qrCode = (new QrCode($cad_qr))
            ->setSize(100)
            ->setMargin(0)
            ->useForegroundColor(2, 2, 2);

        $el_model = Mds_cap_docente_instancia::findOne($una_cap_docente_instancia->iddocenteinstancia);
        $el_model->codigo_qr = $codigo_qr;
        $el_model->save();

        $num_firmas = count($firmas_nombre);
        $html = '
        <html>    
        <body>';
        if (($logo_extra == null) || ($logo_extra == "")) {

            if (($logo_principal == null) || ($logo_principal == "")) {
                $html .= '
                    <div class="div_banner2">	                 
                       <img class="img_banner1" src="../web/img/banner2.png">
                    </div>';
            } else {
                $archivoImagen = $logo_principal;
                if ( file_exists ( $archivoImagen ) ) {
                list ( $a, $h, $t ) = getimagesize ( $archivoImagen );
                }
                if((isset($a)) && ($a==2175))
                {$html .= '
                    <div class="div_banner2aux" >
                        <img class="img_banneraux" src="' . $logo_principal . '">
                    </div>';

                }else
                if((isset($a)) && ($h>400))
                {
                    $html .= '
                    <div class="div_banner2_max" >
                        <img class="img_banner1_max" src="' . $logo_principal . '">
                    </div>';
                }
            
                else
                {
                    $html .= '
                    <div class="div_banner2" >	
                        <img class="img_banner1" src="' . $logo_principal . '">
                    </div>';
                }
                
            }
        } 
        else 
        {
            if (($logo_principal == null) || ($logo_principal == "")) {
                $html .= '
                    <div class="div_banner1" >	 
                        <img class="img_banner1" src="../web/img/banner1.png">
                    </div>
                    <div class="div_logo_extra" >
                        <img   class="img_logoextra" src="' . $logo_extra . '" >
                    </div>';
        } 
        else 
        {
                $html .= '
                    <div class="div_banner1" >	 
                        <img class="img_banner1" src="' . $logo_principal . '">
                    </div>
                    <div class="div_logo_extra" >
                        <img   class="img_logoextra" src="' . $logo_extra . '" >
                    </div>';
            }
        }
        $html .= '
            <p class="header1" >CERTIFICADO</p>
            <!--<p class="parrafo1">Se Certifica que:</p> -->
            <p class="nombre">' . $cad_name . '</p>';

        $tit_br = strpos($titulo_cap, '<br>');
        $html .= '
            <p class="dni">D.N.I.: ' . $dni . '</p>';

        $html .= '<p class="';
        if ($tit_br == false) {
            $html .= 'cad_aprob';
        } else {
            $html .= 'cad_aprob_br';
        }
        $html .= '">';

        $html .= 'ha participado como <b>capacitador</b></p>';


        $html .= '<p class="';

        if ($tit_br == false) {
            $html .= 'curso';
        } else {
            $html .= 'curso_br';
        }
        $html .= '">' . $titulo_cap . '</p>';

        $html .= '
            <div style="position:absolute;top: 665px; left: 47px;">
                <img src="' . $qrCode->writeDataUri() . '"  >
            </div>';

        if ($no_tiene_aval) {
        } else {
            $html .= '<p class="';
            if ($tit_br == false) {
                $html .= 'aval';
            } else {
                $html .= 'aval_br';
            }
            $html .= '">Avalado por ' . $aval . '</p>';
        }


        if ($tit_br == false) {
            $html .= '<p class="';
            if ($no_tiene_aval) {
                $html .= 'organizado_por_2';
            } else {
                $html .= 'organizado_por_1';
            }
            $html .= '" > Organizado por ' . $area_certificado . ', <br>';
        } else {
            $html .= '<p class="';
            if ($no_tiene_aval) {
                $html .= 'organizado_por_2_aval';
            } else {
                $html .= 'organizado_por_1_aval';
            }
            $html .= '"> Organizado por ' . $area_certificado . ', <br>';
        }


        $html .= 'bajo modalidad ' . $modalidad . ', ' . $intervalo;

        if (($cant_horas == 0) || ($cant_horas == null)) {
            $html .= '.-';
        } else {
            $html .= ',<br>con una duración de ' . $cant_horas . ' horas reloj con evaluación final.-';
        }
        $html .= '
            </p>';

        if ($num_firmas == 1) {
            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma11"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }
            $html .= '<div class="tabla_firmasx1">
                        <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }

        if ($num_firmas == 2) {
            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma21"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 2) {
                        $html .= '<div class="img_firma22"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }
            $html .= '<div class="tabla_firmasx2">
                        <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[1] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[1] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }
        if ($num_firmas == 3) {
            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma31"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 2) {
                        $html .= '<div class="img_firma32"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 3) {
                        $html .= '<div class="img_firma33"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }
            $html .= '<div class="tabla_firmasx3">
                        <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td valign="top">';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';
            $html .= '</td>';
            $html .= '<td style=" width:203px; " valign="top">';
            $tam2 = strlen($firmas_nombre[1]);
            /*if ($tam2>27)
                        {
                            $html.= '<img class="img_lineafirma2" src="../web/img/lineafirma.png">';
                        }
                        else
                        {
                            $html.= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
                        }*/
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[1] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[1] . '</p>';

            $html .= '</td>';

            $html .= '<td valign="top">';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[2] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[2] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }
        if ($num_firmas == 4) {

            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma51"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 2) {
                        $html .= '<div class="img_firma52"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 3) {
                        $html .= '<div class="img_firma53"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 4) {
                        $html .= '<div class="img_firma44"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }
            $html .= '<div class="tabla_firmasx4">
                        <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[1] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[1] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[2] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[2] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
            $html .= '<div class="tabla_firmasx4b">
                        <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[3] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[3] . '</p>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }

        if ($num_firmas == 5) {
            $i = 1;
            foreach ($roles as $rol) {
                $el_docente2 = Mds_cap_docente::findOne($rol['id_docente']);
                if ($firma_docente = $el_docente2['firma'] != null) {
                    $firma_docente = $el_docente2['firma'];
                    if ($i == 1) {
                        $html .= '<div class="img_firma51"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 2) {
                        $html .= '<div class="img_firma52"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 3) {
                        $html .= '<div class="img_firma53"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 4) {
                        $html .= '<div class="img_firma54"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                    if ($i == 5) {
                        $html .= '<div class="img_firma55"><img  src="uploads/instancias/firmas/' . $firma_docente . '"></div>';
                    }
                }
                $i++;
            }


            $html .= '<div class="tabla_firmasx5">
                        <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';


            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[0] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[0] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            //echo '<img  src="'.$firmas_imagen[0].'" >';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[1] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[1] . '</p>';

            $html .= '</td>';
            $html .= '<td>';
            //echo '<img  src="'.$firmas_imagen[2].'" >';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm">' . $firmas_nombre[2] . '</p>';
            $html .= '<p class="cargo_firm">' . $firmas_cargo[2] . '</p>';

            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table></div>';
            $html .= '<div class="tabla_firmasx5b">
                        <table  style="text-align: center;  border-spacing:  21px;">';
            $html .= '<tr>';
            $html .= '<td>';
            //echo '<img  src="'.$firmas_imagen[3].'" >';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[3] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[3] . '</p>';
            $html .= '<td>';
            $html .= '<td>';
            //echo '<img  src="'.$firmas_imagen[4].'" >';
            $html .= '<img class="img_lineafirma" src="../web/img/lineafirma.png">';
            $html .= '<p class="nombre_firm" >' . $firmas_nombre[4] . '</p>';
            $html .= '<p class="cargo_firm" >' . $firmas_cargo[4] . '</p>';
            $html .= '<td>';
            $html .= '</tr>';
            $html .= '</table></div>';
        }




        $html .= ' 
            </body>    
            </html>';
        //$content = $objeto->renderPartial('/mds_cap_instancia/certificado', ['id' => $idinscripcion]); // setup kartik\mpdf\Pdf component 
        $content = $html;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'filename' => $path_valido . '/' . $nombres . '.pdf',
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
                'SetMargins' => [0, 0, 0, 0],
            ]
        ]);


        return $pdf->render();
    }

function sanear_string($string)
    {

        $string = trim($string);

        $string = str_replace(
            array('à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('ñ', 'ñ', 'c', 'C',),
            $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array(
                "¨", "º", "-", "~",
                "#", "@", "|", "!", '"', "'", "¡",
                "¿", "[", "^", "<code>", "]",
                "+", "}", "{", "¨", "´",
                ">", "< ", ";", ",", ":",
                "."
            ),
            '',
            $string
        );


        return $string;
    }
