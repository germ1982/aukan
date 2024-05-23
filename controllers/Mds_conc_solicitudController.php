<?php

namespace app\controllers;

use Yii;
use app\models\Mds_conc_solicitud;
use app\models\Mds_conc_solicitudSearch;

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

use kartik\mpdf\Pdf;
use setasign\Fpdi\Fpdi;

use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\Mds_conc_postulacion;
use app\models\Mds_conc_vacante;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Mds_conc_solicitudController implements the CRUD actions for Mds_conc_solicitud model.
 */
class Mds_conc_solicitudController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                    'view',
                    'create',
                    'store',
                    'update',
                    'delete',
                    'reactivate',
                    'reporte',
                    'guardarlogmanualusuario',
                    'add_vacante'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'store',
                            'update',
                            'delete',
                            'reactivate',
                            'reporte',
                            'guardarlogmanualusuario',
                            'add_vacante',
                        ],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_CONCURSO
                        ],
                    ],
                ],
            ],
        ];
    }

    private function getPermissionsCrud()
    {
        $response = null;

        $permissionCreate = false;
        $permissionRead = false;
        $permissionUpdate = false;
        $permissionDelete = false;
        $permissionReactivate = false;

        $idusuario = Yii::$app->user->identity->idusuario;
        $rolesConcursos = implode(',', Mds_conc_solicitud::ID_ROLES_CONCURSOS);
        $iditem = Mds_seg_item::MODULO_CONCURSO;

        $permisos = Mds_seg_permiso::findBySql(
            "select *
                from mds_seg_permiso
                where idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                AND idrol IN ({$rolesConcursos})
                AND iditem = {$iditem}"
        )->all();

        $countPermisos = count($permisos);
        $i = 0;

        while ((!$permissionCreate || !$permissionRead || !$permissionUpdate || !$permissionDelete) && $i < $countPermisos) {
            $permiso = $permisos[$i];
            if (!$permissionCreate) {
                $permissionCreate = $permiso->alta;
            }
            if (!$permissionRead) {
                $permissionRead = $permiso->ver;
            }
            if (!$permissionUpdate) {
                $permissionUpdate = $permiso->modifica;
            }
            if (!$permissionDelete) {
                $permissionDelete = $permiso->baja;
            }
            $i++;
        }

        if (Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL)) {
            $permissionReactivate = true;
        }

        if ($countPermisos) {
            $response = [
                'permissionCreate' => $permissionCreate,
                'permissionRead' => $permissionRead,
                'permissionUpdate' => $permissionUpdate,
                'permissionDelete' => $permissionDelete,
                'permissionReactivate' => $permissionReactivate,
            ];
        }
        return $response;
    }

    /**
     * Lists all Mds_conc_solicitud models.
     * @return mixed
     */
    public function actionIndex()
    {
        $permissionCrud = self::getPermissionsCrud();

        if ($permissionCrud) {
            $searchModel = new Mds_conc_solicitudSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'permission' => $permissionCrud,
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
                'listaEstados' => $this->getListaEstados(),
                'usuarioCargaFiltro' => $this->getFilterUsuarioCarga(),
                'concursosFiltro' => $this->getConcursosFiltro(),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_conc_solicitud model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionRead = $permissionCrud['permissionRead'];

        if ($permissionRead) {
            $request = Yii::$app->request;
            $model = $this->findModel($id);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_conc_solicitud', $id, array());

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Ver Solicitud #" . $model->idsolicitud,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return $this->render('view', [
                    'model' => $model,
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionCreate()
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionCreate = $permissionCrud['permissionCreate'];

        if ($permissionCreate) {
            $request = Yii::$app->request;
            $initialPreview = [];
            $usuarioAuth = Yii::$app->user->identity;

            $model = new Mds_conc_solicitud();
            $model->idusuario = $usuarioAuth->idusuario;
            $model->created_at = date('Y-m-d H:i:s');

            $concursoOptions = ArrayHelper::map(
                Sds_com_configuracion::getConfiguracionesSinOrden(Sds_com_configuracion_tipo::CONCURSO_TIPO, true),
                'idconfiguracion',
                'descripcion'
            );

            if (count($concursoOptions) === 1) {
                //Si solo tenemos una opcion, precargamos el valor en el select
                $keys = array_keys($concursoOptions);
                $model->idconcurso = reset($keys);
            }

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $title = "Crear nueva Solicitud";
                $botonVolver = Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);
                $botonGuardar = Html::button('Guardar', ['class' => 'btn btn-success', 'type' => "submit"]);
                $buttons = $botonVolver;

                if ($request->isGet) {
                    $content = $this->renderAjax('create', [
                        'model' => $model,
                        'initialPreview' => $initialPreview,
                        'tituloRequerido' => false,
                        'concursoOptions' => $concursoOptions,
                    ]);
                    $buttons .= " $botonGuardar";
                } else if ($model->load($request->post())) {
                    // Upload archivo adjunto
                    // $this->storeDocumentacion($model, null);
                    if ($model->validate()) {
                        $transaction = Yii::$app->db->beginTransaction();
                        if ($model->save()) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_conc_solicitud', $model->idsolicitud, $model->getAttributes());
                            $content = "<span class='text-success'>Se generó correctamente  la solicitud #$model->idsolicitud, de $model->apellido $model->nombre</span>";
                        } else {
                            $transaction->rollBack();
                            $content = "<span class='text-danger'>Error al guardar la solicitud.</span>";
                        }
                    } else {
                        $content = "<span class='text-danger'>Error al validar los datos de la solicitud.</span>";
                    }
                }

                return [
                    'title' => $title,
                    'content' => $content,
                    'footer' => $buttons
                ];
            } else {
                if ($model->load($request->post())) {
                    // Upload archivo adjunto
                    // $this->storeDocumentacion($model, null);
                    if ($model->validate()) {
                        $transaction = Yii::$app->db->beginTransaction();
                        if ($model->save()) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_conc_solicitud', $model->idsolicitud, $model->getAttributes());
                            Yii::$app->session->setFlash('success', "Se generó correctamente  la solicitud #" . $model->idsolicitud . ", de " . $model->apellido . " " . $model->nombre);
                        } else {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', "Error al guardar la solicitud.");
                        }
                    } else {
                        Yii::$app->session->setFlash('error', "Error al validar los datos de la solicitud.");
                    }
                    return $this->redirect(['mds_conc_solicitud/index']);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                        'initialPreview' => $initialPreview,
                        'tituloRequerido' => false,
                        'concursoOptions' => $concursoOptions,
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_conc_solicitud model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionUpdate = $permissionCrud['permissionUpdate'];

        if ($permissionUpdate) {

            $request = Yii::$app->request;
            $model = $this->findModel($id);

            $tituloRequerido = false;
            $postulaciones = $model->getPostulaciones();
            foreach ($postulaciones as $key => $postulacion) {
                if ($postulacion->vacante->requiere_titulo) {
                    $model->titulo_isRequired = true;
                    $tituloRequerido = true;
                    break;
                }
            }

            $concursoOptions = ArrayHelper::map(
                Sds_com_configuracion::getConfiguracionesSinOrden(Sds_com_configuracion_tipo::CONCURSO_TIPO, true),
                'idconfiguracion',
                'descripcion'
            );

            $adjuntoAlmacenados = [$model->deudores_morosos, $model->registro_violencia, $model->antecedente_nacional, $model->titulo];
            $initialPreview = [
                'deudores_morosos' => Url::to(Mds_conc_solicitud::CARPETA_CONCURSO . $model->deudores_morosos, true),
                'extension_deudor' => Mds_conc_solicitud::getExtension($model->deudores_morosos),
                'registro_violencia' => Url::to(Mds_conc_solicitud::CARPETA_CONCURSO . $model->registro_violencia, true),
                'extension_violencia' => Mds_conc_solicitud::getExtension($model->registro_violencia),
                'antecedente_nacional' => Url::to(Mds_conc_solicitud::CARPETA_CONCURSO . $model->antecedente_nacional, true),
                'extension_antecedente' => Mds_conc_solicitud::getExtension($model->antecedente_nacional),
                'titulo' => $model->titulo ? Url::to(Mds_conc_solicitud::CARPETA_CONCURSO . $model->titulo, true) : '',
                'extension_titulo' => $model->titulo ? Mds_conc_solicitud::getExtension($model->titulo) : '',
            ];

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $title = "Actualizar Solicitud #$model->idsolicitud";
                $botonVolver = Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);
                $botonGuardar = Html::button('Guardar', ['class' => 'btn btn-success', 'type' => "submit"]);
                $buttons = $botonVolver;
                $content = "";

                if ($request->isGet) {
                    $content = $this->renderAjax('update', [
                        'model' => $model,
                        'initialPreview' => $initialPreview,
                        'tituloRequerido' => $tituloRequerido,
                        'concursoOptions' => $concursoOptions,
                    ]);
                    $buttons .= " $botonGuardar";
                } else if ($request->post()) {
                    // Upload archivo adjunto
                    // $this->storeDocumentacion($model, $adjuntoAlmacenados);
                    $model->titulo_isRequired = $model->titulo_isRequired ? ($model->titulo ? false : true) : false;
                    if ($model->validate()) {
                        $transaction = Yii::$app->db->beginTransaction();
                        if ($model->save()) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_solicitud', $model->idsolicitud, $model->getAttributes());
                            $content = "<span class='text-success'>Se actualizó correctamente la solicitud #$model->idsolicitud, de $model->apellido $model->nombre</span>";
                        } else {
                            $transaction->rollBack();
                            $content = "<span class='text-danger'>Error al actualizar la solicitud.</span>";
                        }
                    } else {
                        $content = "<span class='text-danger'>Error al validar los datos de la solicitud.</span>";
                    }
                }

                return [
                    'title' => $title,
                    'content' => $content,
                    'footer' => $buttons
                ];
            } else {
                if ($request->post()) {
                    // Upload archivo adjunto
                    // $this->storeDocumentacion($model, $adjuntoAlmacenados);
                    $model->titulo_isRequired = $model->titulo_isRequired ? ($model->titulo ? false : true) : false;
                    if ($model->validate()) {
                        $transaction = Yii::$app->db->beginTransaction();
                        if ($model->save()) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_solicitud', $model->idsolicitud, $model->getAttributes());
                            Yii::$app->session->setFlash('success', " Se actualizó correctamente la solicitud #" . $model->idsolicitud . ", de " . $model->apellido . ' ' . $model->nombre);
                        } else {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', "Error al actualizar la solicitud.");
                        }
                    } else {
                        Yii::$app->session->setFlash('error', "Error al validar los datos de la solicitud.");
                    }
                    return $this->redirect(['mds_conc_solicitud/index']);
                } else {
                    return $this->render('update', [
                        'model' => $model,
                        'initialPreview' => $initialPreview,
                        'tituloRequerido' => $tituloRequerido,
                        'concursoOptions' => $concursoOptions,
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_conc_solicitud model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionDelete = $permissionCrud['permissionDelete'];
        $model = $this->findModel($id);

        if (!$model->deleted_at && $permissionDelete) {
            $model->deleted_at = date('Y-m-d H:i:s');
            $model->idusuario_borra = Yii::$app->user->id;
            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                if ($model->save()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_conc_solicitud', $model->idsolicitud, $model->getAttributes());
                    Yii::$app->session->setFlash('success', " Se eliminó correctamente la solicitud #" . $model->idsolicitud);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al borrar la solicitud.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la solicitud.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['mds_conc_solicitud/index']);
    }

    public function actionReactivate($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionReactivate = $permissionCrud['permissionReactivate'];
        $model = $this->findModel($id);

        if (!is_null($model->deleted_at) && $permissionReactivate) {

            $model->deleted_at = null;
            $model->idusuario_borra = null;
            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                if ($model->update()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_solicitud', $model->idsolicitud, $model->getAttributes());
                    Yii::$app->session->setFlash('success', " Se reactivó correctamente la solicitud #" . $model->idsolicitud);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al reactivar la solicitud.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la solicitud.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

        return $this->redirect(['mds_conc_solicitud/index']);
    }

    public function actionReporte($ids)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionRead = $permissionCrud['permissionRead'];

        if ($permissionRead) {

            $array_idsolicitudes = explode(",", $ids);
            $idNombre = str_replace(',', ' #', $ids);
            $solicitudes = [];

            foreach ($array_idsolicitudes as $id) {
                $model =  $this->findModel($id);
                $datos = [];
                if ($model) {
                    $datos['solicitud'] = $model;
                    $datos['postulaciones'] = $model->getPostulaciones();
                    $datos['renaper'] = $model->getConcRenaper();
                    $datos['proneu'] = $model->getConcProneu();
                    $datos['rhsur'] = $model->getConcRhSur();
                    array_push($solicitudes, $datos);
                }
            }

            $usuarioAuth = Yii::$app->user->identity;
            $dateToday = date('d/m/Y H:i:s');

            $content = $this->renderPartial('reporte', [
                'solicitudes' => $solicitudes,
            ]);

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'filename' => 'Solicitud_' . $ids . '_' . $dateToday . '.pdf',
                'content' => $content,
                'defaultFontSize' => 12,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem;}.parrafo,td{padding: 10px .5rem 5px .5rem}div.saltopagina{page-break-after:always}',
                'methods' => [
                    'SetTitle' => 'DETALLE DE LA SOLICITUD #' . $idNombre,
                    'SetHeader' => null,
                    'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
                ]
            ]);

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_conc_solicitud', $ids, array());
            return $pdf->render();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionGuardarlogmanualusuario()
    {
        $success = false;
        if (Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_conc_solicitud_manual', null, array())) {
            $success = true;
        };
        return json_encode(['success' => $success]);
    }

    public function actionAdd_vacante($id)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            $permissionCrud = self::getPermissionsCrud();
            $permissionCreate = $permissionCrud['permissionCreate'];

            if ($permissionCreate) {
                $arrayVacantes = $this->getVacantesDisponibles($id);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Agregar vacante - Solicitud #$id",
                    'content' => $this->renderAjax('agregar_vacante', [
                        'id' => $id,
                        'arrayVacantes' => $arrayVacantes,
                    ]),
                    'footer' => [
                        Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                    ]
                ];
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            return $this->redirect(['/mds_conc_solicitud/index']);
        }
    }
    protected function getVacantesDisponibles($idsolicitud)
    {

        $data = [];
        $model = $this->findModel($idsolicitud);
        if ($model && !$model->deleted_at) {
            $idconcurso = $model->idconcurso;
            if ($idconcurso) {
                $vacantesPostuladas =
                    Mds_conc_postulacion::find()->select('vacante.categoria')->where(
                        "postulacion.idsolicitud = $idsolicitud AND vacante.deleted_at IS NULL AND postulacion.deleted_at IS NULL AND configuracion.activo = 1"
                    )
                    ->from("mds_conc_postulacion as postulacion")
                    ->innerJoin('mds_conc_vacante as vacante', 'postulacion.idvacante = vacante.idvacante')
                    ->innerJoin('sds_com_configuracion as configuracion', 'vacante.categoria = configuracion.idconfiguracion')
                    ->asArray()
                    ->all();

                $data = ArrayHelper::map(
                    Sds_com_configuracion::find('idconfiguracion', 'descripcion')
                        ->from("sds_com_configuracion as configuracion")
                        ->innerJoin('mds_conc_vacante as vacante', 'vacante.categoria = configuracion.idconfiguracion')
                        ->where(
                            "vacante.deleted_at IS NULL AND configuracion.activo = 1"
                        )
                        ->andWhere(['NOT IN', 'categoria', $vacantesPostuladas])
                        ->orderBy(['descripcion' => SORT_ASC])
                        ->all(),
                    'idconfiguracion',
                    'descripcion'
                );
            }
        }
        return $data;
    }

    public function actionStore_add_vacante()
    {

        $usuarioAuth = Yii::$app->user->identity;
        $payload = Yii::$app->request->post();
        $idsolicitud = $payload['idsolicitud'];
        $message = 'No tiene permisos para realizar esta acción.';
        if ($idsolicitud) {
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL);
            $hasRolAdmin = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMINISTRADOR);

            if ($hasRolAdminGeneral || $hasRolAdmin) {
                if (isset($payload['nuevas_vacantes'])) {
                    $transaction = Yii::$app->db->beginTransaction();
                    $model = $this->findModel($idsolicitud);
                    $idconcurso = $model->idconcurso;
                    foreach ($payload['nuevas_vacantes'] as $idconfiguracion) {

                        $vacante = Mds_conc_vacante::find()
                            ->select("idvacante")
                            ->where("deleted_at IS NULL AND categoria = $idconfiguracion AND idconcurso = $idconcurso")
                            ->orderBy(["idvacante" => SORT_DESC])
                            ->one();
                        if ($vacante) {
                            $model  = new Mds_conc_postulacion();
                            $model->idusuario = $usuarioAuth->idusuario;
                            $model->idsolicitud = $idsolicitud;
                            $model->estado = Mds_conc_solicitud::ESTADO_INSCRIPTO;
                            $model->idvacante = $vacante->idvacante;
                            $model->puntaje = null;
                            $model->created_at = date('Y-m-d H:i:s');

                            if (!$model->save()) {
                                $message = 'Error al agregar las vacantes.';
                                $transaction->rollBack();
                            }
                        }
                    }
                    $transaction->commit();
                    $message = 'Se agregaron las vacantes correctamente.';
                }
            }
        }
        return json_encode(['message' => $message]);
    }

    /**
     * Finds the Mds_conc_solicitud model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_conc_solicitud the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_conc_solicitud::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getListaEstados()
    {
        //Busqueda tipos de documentos
        $tipos = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::CONCURSO_SOLICITUD_ESTADO, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $tipos = ArrayHelper::map($tipos, 'idconfiguracion', 'descripcion');
        return $tipos;
    }

    protected function getFilterUsuarioCarga()
    {
        $usuarioCarga = Mds_conc_solicitud::find()->select([
            'mds_conc_solicitud.idusuario',
            'CONCAT(UPPER(mds_seg_usuario.apellido),\', \', UPPER(mds_seg_usuario.nombre)) AS nombreUsuario'
        ])->innerJoin('mds_seg_usuario', 'mds_conc_solicitud.idusuario=mds_seg_usuario.idusuario')->asArray()->all();
        $programasFiltro = ArrayHelper::map($usuarioCarga, 'idusuario', 'nombreUsuario');
        return $programasFiltro;
    }

    protected function getConcursosFiltro()
    {
        $concursos = Mds_conc_solicitud::getConcursosFiltro();
        if ($concursos) {
            return  ArrayHelper::map($concursos, 'idconfiguracion', 'concurso');
        }
        return [];
    }

    private function storeAdjunto($model, $tipoAdjunto)
    {
        // Upload archivo
        $tmpFile = UploadedFile::getInstance($model, $tipoAdjunto);
        $nameFile = null;

        if (isset($tmpFile)) {
            $date = $this->generarCodigo();
            $extension = $tmpFile->extension;
            $path_info = pathinfo($tmpFile);
            $extension = $path_info['extension'];
            $nameFile = "conc_solicitud_{$model->idsolicitud}_{$date}.{$extension}";

            $ruta = Mds_conc_solicitud::PATH;
            if (!file_exists($ruta . $model->idsolicitud . '/')) {
                mkdir($ruta . $model->idsolicitud . '/', 0777, true);
            }
            $tmpFile->saveAs($ruta . $nameFile);
        }
        return $nameFile;
    }

    //Esto
    private function storeDocumentacion($model, $adjuntosAlmacenados)
    {
        // Upload archivo adjunto
        $nameFileDeudor = $this->storeAdjunto($model, 'deudores_morosos');
        if (!is_null($nameFileDeudor)) {
            $model->deudores_morosos = $nameFileDeudor;
        } else {
            if ($adjuntosAlmacenados) : $model->deudores_morosos = $adjuntosAlmacenados[0];
            endif;
        }
        $nameFileViolencia = $this->storeAdjunto($model, 'registro_violencia');
        if (!is_null($nameFileViolencia)) {
            $model->registro_violencia = $nameFileViolencia;
        } else {
            if ($adjuntosAlmacenados) : $model->registro_violencia = $adjuntosAlmacenados[1];
            endif;
        }
        $nameFileAntecedente = $this->storeAdjunto($model, 'antecedente_nacional');
        if (!is_null($nameFileAntecedente)) {
            $model->antecedente_nacional = $nameFileAntecedente;
        } else {
            if ($adjuntosAlmacenados) : $model->antecedente_nacional = $adjuntosAlmacenados[2];
            endif;
        }
        $nameFileTitulo = $this->storeAdjunto($model, 'titulo');
        if (!is_null($nameFileTitulo)) {
            $model->titulo = $nameFileTitulo;
        } else {
            if ($adjuntosAlmacenados) : $model->titulo = $adjuntosAlmacenados[3];
            endif;
        }
    }

    private function generarCodigo()
    {
        $codigo = '';
        $length = 10;
        $keys = array_merge(range('A', 'Z'));
        $codigo = date('Y-m-d_H_i_s', time());

        for ($i = 0; $i < $length; $i++) {
            $codigo .= $keys[array_rand($keys)];
        }

        return $codigo;
    }
}
