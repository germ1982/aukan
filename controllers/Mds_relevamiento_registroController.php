<?php

namespace app\controllers;

use Yii;
use app\models\Mds_relevamiento_registro;
use app\models\Mds_relevamiento_registroSearch;
use app\models\Mds_relevamiento_respuesta;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_gis_capa_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\Mds_sys_log;
use kartik\mpdf\Pdf;
use app\models\Mds_seg_usuario_rol;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\Mds_legales_archivo;

/**
 * Mds_relevamiento_registroController implements the CRUD actions for Mds_relevamiento_registro model.
 */
class Mds_relevamiento_registroController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index', 'create', 'update', 'delete', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'storeRespuesta', 'actualizarRespuesta', 'duplicate', 'reactivate'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_RELEVAMIENTO_EDILICIO,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_relevamiento_registro models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RELEVAMIENTO_EDILICIO, $usuarioAuth->idusuario);
        $permissionView = $this->hasOnePermission($permissions, "ver");
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_relevamiento_registro::ID_ROL_RELEVAMIENTO_ADMINISTRADOR_GENERAL);

        if ($permissionView) {
            $searchModel = new Mds_relevamiento_registroSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $permissionCreated = $this->hasOnePermission($permissions, "alta");
            $permissionUpdated = $this->hasOnePermission($permissions, "modifica");

            $stringButtonsIndex = '{view} {print} {reactivate} {delete}';
            if (!empty($permissionUpdated)) {
                $stringButtonsIndex .= ' {update}';
            }
            if (!empty($permissionCreated)) {
                $stringButtonsIndex .= ' {duplicate}';
            }

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'edificiosFilter' => $this->getEdificiosFiltro(),
                'stringButtonsIndex' => $stringButtonsIndex,
                'permissionCreated' => $permissionCreated,
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
                'usuariosFiltro' => $this->getUsuariosFiltro()
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_relevamiento_registro model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RELEVAMIENTO_EDILICIO, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "ver");
        $model = $this->findModel($id);

        if ($hasOnePermission) {
            $model_respuesta = Mds_relevamiento_respuesta::find()
                ->select('mds_relevamiento_respuesta.iditem,mds_relevamiento_respuesta.posee,mds_relevamiento_respuesta.detalle,sds_com_configuracion.descripcion,sds_com_configuracion.idconfiguraciontipo')
                ->where(['mds_relevamiento_respuesta.idrelevamientoregistro' => $id, 'mds_relevamiento_respuesta.deleted_at' => null])
                ->innerJoin('sds_com_configuracion', 'sds_com_configuracion.idconfiguracion=mds_relevamiento_respuesta.iditem')
                ->innerJoin('mds_relevamiento_registro', 'mds_relevamiento_registro.idrelevamientoregistro=mds_relevamiento_respuesta.idrelevamientoregistro')
                ->innerJoin('sds_com_configuracion_tipo', 'sds_com_configuracion_tipo.idconfiguraciontipo=sds_com_configuracion.idconfiguraciontipo')
                ->asArray()
                ->all();
            $agrupadores = $this->getAgrupadores();
            return $this->render('view', [
                'model' => $this->findModel($id),
                'model_respuesta' => $model_respuesta,
                'agrupadores' => $agrupadores,
                'edificiosFilter' => $this->getCapaitem(),
                'adjuntos' => $model->getOtrosAdjuntos(),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Mds_relevamiento_registro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RELEVAMIENTO_EDILICIO, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "alta");

        if ($hasOnePermission) {
            $model = new Mds_relevamiento_registro();
            $model_respuesta = new Mds_relevamiento_respuesta();
            $agrupadores = $this->getAgrupadores();
            $transaction = Yii::$app->db->beginTransaction();

            if ($model->load(Yii::$app->request->post())) {
                $model->created_at = date('Y-m-d H:i:s');
                $model->idusuario_carga = Yii::$app->user->id;
                $model->fecha = $this->armarDateParaMySql($model->fecha);

                if ($model->save()) {
                    // Upload archivo adjunto
                    if (Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos']) {
                        $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
                        $this->storeAdjuntoOtros($adjuntos, $model);
                    }
                    $guardarRespuesta = $this->storeRespuesta($model, Yii::$app->request->post());
                    if ($guardarRespuesta) {
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_relevamiento_registro', $model->idrelevamientoregistro, $model->getAttributes());
                        Yii::$app->session->setFlash('success', ' Respuesta #' . $model->idrelevamientoregistro . ' guardada correctamente.');
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', "Error al guardar la respuesta.");
                    }
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al guardar los datos.");
                }
            }

            return $this->render('create', [
                'model' => $model,
                'model_respuesta' => $model_respuesta,
                'agrupadores' => $agrupadores,
                'edificiosFilter' => $this->getEdificiosFiltro(),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_relevamiento_registro model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RELEVAMIENTO_EDILICIO, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "modifica");
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_relevamiento_registro::ID_ROL_RELEVAMIENTO_ADMINISTRADOR_GENERAL);
        $model = $this->findModel($id);

        if ($hasOnePermission && ($model->deleted_at == null) && ($model->idusuario_carga === $usuarioAuth->idusuario) || ($hasRolAdminGeneral)) {
            $model_respuesta = Mds_relevamiento_respuesta::find()
                ->select('mds_relevamiento_respuesta.idrelevamientorespuesta,mds_relevamiento_respuesta.iditem,mds_relevamiento_respuesta.posee,mds_relevamiento_respuesta.detalle,sds_com_configuracion.descripcion,sds_com_configuracion.idconfiguraciontipo')
                ->where(['mds_relevamiento_respuesta.idrelevamientoregistro' => $id, 'mds_relevamiento_respuesta.deleted_at' => null])
                ->innerJoin('sds_com_configuracion', 'sds_com_configuracion.idconfiguracion=mds_relevamiento_respuesta.iditem')
                ->innerJoin('mds_relevamiento_registro', 'mds_relevamiento_registro.idrelevamientoregistro=mds_relevamiento_respuesta.idrelevamientoregistro')
                ->innerJoin('sds_com_configuracion_tipo', 'sds_com_configuracion_tipo.idconfiguraciontipo=sds_com_configuracion.idconfiguraciontipo')
                ->asArray()
                ->all();
            $agrupadores = $this->getAgrupadores();
            $transaction = Yii::$app->db->beginTransaction();

            if ($model->load(Yii::$app->request->post())) {
                $model->updated_at = date('Y-m-d H:i:s');
                $model->fecha = $this->armarDateParaMySql($model->fecha);
                if ($model->update()) {
                    $guardarRespuesta = $this->actualizarRespuesta($model, Yii::$app->request->post());
                    if ($guardarRespuesta) {

                        if (isset(Yii::$app->request->post()['Mds_legales_oficio']['adjuntos_eliminados']) && Yii::$app->request->post()['Mds_legales_oficio']['adjuntos_eliminados']) {
                            $adjuntosEliminados = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['adjuntos_eliminados'], true);
                            foreach ($adjuntosEliminados as $idAdjunto) {
                                $modelArchivo = Mds_legales_archivo::findOne($idAdjunto);
                                $modelArchivo->activo = 0;
                                $modelArchivo->save();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_relevamiento_registro', $idAdjunto, $modelArchivo->getAttributes());
                            }
                        }

                        if (Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos']) {
                            $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
                            $this->storeAdjuntoOtros($adjuntos, $model);
                        }
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_relevamiento_registro', $model->idrelevamientoregistro, $model->getAttributes());
                        Yii::$app->session->setFlash('success', ' Respuesta #' . $model->idrelevamientoregistro . ' actualizada correctamente.');
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', "Error al actualizar la respuesta.");
                    }
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al actualizar los datos.");
                }
            }

            return $this->render('update', [
                'model' => $model,
                'model_respuesta' => $model_respuesta,
                'agrupadores' => $agrupadores,
                'edificiosFilter' => $this->getEdificiosFiltro()
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_relevamiento_registro model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RELEVAMIENTO_EDILICIO, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "baja");
        if ($hasOnePermission) {
            $model = $this->findModel($id);
            if ($model) {
                $model->idusuario_borra = Yii::$app->user->id;
                $model->deleted_at = date('Y-m-d H:i:s');

                if ($model->update()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_relevamiento_registro', $id, $model->getAttributes());
                    Yii::$app->session->setFlash('success', ' Se <b>ELIMINÓ</b> correctamente el registro #' . $id . ' correspondiente a ' . $model->capaitem->descripcion);
                }
            }
            return $this->redirect(['index']);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Finds the Mds_relevamiento_registro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_relevamiento_registro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_relevamiento_registro::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getAgrupadores()
    {
        $arrayAgrupadores = Mds_relevamiento_registro::ARRAY_AGRUPADORES;
        $agrupadores = [];
        foreach ($arrayAgrupadores as $idconfiguraciontipo => $titulo) {
            $agrupador = Sds_com_configuracion_tipo::find()
                ->where(['idconfiguraciontipo' => $idconfiguraciontipo, 'activo' => 1])
                ->asArray()
                ->one();
            if ($agrupador) {
                $agrupador['titulo'] = $titulo;
                array_push($agrupadores, $agrupador);
            }
        }
        return $agrupadores;
    }
    protected function getCapaitem()
    {
        //Busqueda dispositivos cargados en capaitem
        $capaFilter = Sds_gis_capa_item::find()
            ->where(['activo' => 1])
            ->asArray()
            ->orderBy('descripcion')
            ->all();
        $capaFilter = ArrayHelper::map($capaFilter, 'idcapaitem', 'descripcion');
        return $capaFilter;
    }

    public function storeRespuesta($model, $data)
    {
        $guardaRespuesta = false;
        $respuestas = $data['respuesta'];
        foreach ($respuestas as $item => $valor) {
            $model_respuesta = new Mds_relevamiento_respuesta();
            $model_respuesta->idrelevamientoregistro = $model['idrelevamientoregistro'];
            $model_respuesta->iditem = $item;
            $model_respuesta->posee = $valor === null ? null : $valor;
            $model_respuesta->detalle = $data['detalle'][$item];
            $model_respuesta->created_at = date('Y-m-d H:i:s');
            $model_respuesta->idusuario_carga = Yii::$app->user->id;
            if ($model_respuesta->save()) {
                $guardaRespuesta = true;
            };
        }
        return $guardaRespuesta;
    }

    public function actualizarRespuesta($model, $data)
    {
        $guardaRespuesta = false;
        $respuestas = $data['respuesta'];
        foreach ($respuestas as $item => $valor) {
            $model_respuesta = Mds_relevamiento_respuesta::find()
                ->where([
                    'mds_relevamiento_respuesta.idrelevamientoregistro' => $model['idrelevamientoregistro'],
                    'mds_relevamiento_respuesta.iditem' => $item
                ])
                ->one();
            $model_respuesta->posee = $valor === null ? null : $valor;
            $model_respuesta->detalle = $data['detalle'][$item];
            $model_respuesta->updated_at = date('Y-m-d H:i:s');
            if ($model_respuesta->update()) {
                $guardaRespuesta = true;
            };
        }
        return $guardaRespuesta;
    }

    function armarDateParaMySql($fecha)
    {
        if ($fecha == null) {
            return null;
        }
        $anio = substr($fecha, 6, 4);
        $mes  = substr($fecha, 3, 2);
        $dia = substr($fecha, 0, 2);
        $DT = "$anio-$mes-$dia";
        return $DT;
    }
    public function actionDuplicate($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RELEVAMIENTO_EDILICIO, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "alta");

        if ($hasOnePermission) {
            $transaction = Yii::$app->db->beginTransaction();
            $guardaRespuesta = false;
            $fecha_actual = date('Y-m-d H:i:s');
            $model = $this->findModel($id);
            $model_registro = new Mds_relevamiento_registro;
            $model_registro->idcapaitem = $model->idcapaitem;
            $model_registro->observaciones = $model->observaciones;
            $model_registro->fecha = $model->fecha;
            $model_registro->created_at = $fecha_actual;
            $model_registro->idusuario_carga = Yii::$app->user->id;

            $model_respuestas = Mds_relevamiento_respuesta::find()
                ->select('mds_relevamiento_respuesta.iditem,mds_relevamiento_respuesta.posee,mds_relevamiento_respuesta.detalle,mds_relevamiento_respuesta.detalle')
                ->where(['mds_relevamiento_respuesta.idrelevamientoregistro' => $id, 'mds_relevamiento_respuesta.deleted_at' => null])
                ->innerJoin('sds_com_configuracion', 'sds_com_configuracion.idconfiguracion=mds_relevamiento_respuesta.iditem')
                ->innerJoin('mds_relevamiento_registro', 'mds_relevamiento_registro.idrelevamientoregistro=mds_relevamiento_respuesta.idrelevamientoregistro')
                ->innerJoin('sds_com_configuracion_tipo', 'sds_com_configuracion_tipo.idconfiguraciontipo=sds_com_configuracion.idconfiguraciontipo')
                ->asArray()
                ->all();
            if ($model_registro->save()) {
                foreach ($model_respuestas as $item => $valor) {
                    $model_respuesta = new Mds_relevamiento_respuesta();
                    $model_respuesta->idrelevamientoregistro = $model_registro->idrelevamientoregistro;
                    $model_respuesta->iditem = $valor['iditem'];
                    $model_respuesta->posee = $valor['posee'];
                    $model_respuesta->detalle = $valor['detalle'];
                    $model_respuesta->created_at = $fecha_actual;
                    $model_respuesta->idusuario_carga = Yii::$app->user->id;

                    if ($model_respuesta->save()) {
                        $guardaRespuesta = true;
                    };
                }
                if ($guardaRespuesta) {
                    $adjuntos = $this->getAdjuntosById($id);
                    foreach ($adjuntos as $key => $adjunto) {
                        $path = $adjunto["path"];
                        Mds_legales_archivo::saveFile($adjunto['nombre'], 'mds_relevamiento_registro', 'relevamiento', $model_registro->idrelevamientoregistro, $path);
                    }
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_relevamiento_registro', $model_registro->idrelevamientoregistro, $model_registro->getAttributes());
                    Yii::$app->session->setFlash('success', ' Registro #' . $model->idrelevamientoregistro . 'del edificio <b>' . $model->capaitem->descripcion . '</b> duplicado correctamente.');
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al duplicar el registro.");
                }
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', "Error al duplicar el registro.");
            }
            return $this->redirect(['index']);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionDetalle_registro($idrelevamientoregistro)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RELEVAMIENTO_EDILICIO, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "ver");
        if ($hasOnePermission) {
            $model = Mds_relevamiento_registro::find()
                ->where(['idrelevamientoregistro' => $idrelevamientoregistro])
                ->one();
            if ($model) {
                $model_respuesta = Mds_relevamiento_respuesta::find()
                    ->select('mds_relevamiento_respuesta.iditem,mds_relevamiento_respuesta.posee,mds_relevamiento_respuesta.detalle,sds_com_configuracion.descripcion,sds_com_configuracion.idconfiguraciontipo')
                    ->where(['mds_relevamiento_respuesta.idrelevamientoregistro' => $idrelevamientoregistro, 'mds_relevamiento_respuesta.deleted_at' => null])
                    ->innerJoin('sds_com_configuracion', 'sds_com_configuracion.idconfiguracion=mds_relevamiento_respuesta.iditem')
                    ->innerJoin('mds_relevamiento_registro', 'mds_relevamiento_registro.idrelevamientoregistro=mds_relevamiento_respuesta.idrelevamientoregistro')
                    ->innerJoin('sds_com_configuracion_tipo', 'sds_com_configuracion_tipo.idconfiguraciontipo=sds_com_configuracion.idconfiguraciontipo')
                    ->asArray()
                    ->all();
                $agrupadores = $this->getAgrupadores();
                $capaItem = $this->getCapaitem();
                $usuarioAuth = Yii::$app->user->identity;
                $adjuntos = $this->getAdjuntosById($idrelevamientoregistro);

                $content = $this->renderPartial('detalle_registro', [
                    'model' => $model,
                    'model_respuesta' => $model_respuesta,
                    'agrupadores' => $agrupadores,
                    'edificiosFilter' => $capaItem,
                    'adjuntos' => $adjuntos
                ]);
                $dateToday = date('d/m/Y H:i:s');
                $pdf = new Pdf([
                    'mode' => Pdf::MODE_UTF8,
                    'format' => Pdf::FORMAT_A4,
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    'destination' => Pdf::DEST_BROWSER,
                    'filename' => 'Relevamiento_edilicio_' . $model->idrelevamientoregistro . '.pdf',
                    'content' => $content,
                    'defaultFontSize' => 12,
                    'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                    // any css to be embedded if required
                    'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}',
                    'methods' => [
                        'SetTitle' => 'RELEVAMIENTO EDILICIO #' . $model->idrelevamientoregistro,
                        'SetHeader' => null,
                        'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
                    ]

                ]);
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_relevamiento_registro', $idrelevamientoregistro, array());
                return $pdf->render();
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
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

    public function actionReactivate($id)
    {
        parse_str(Yii::$app->request->headers['referer'], $params);
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_relevamiento_registro::ID_ROL_RELEVAMIENTO_ADMINISTRADOR_GENERAL);
        $model = Mds_relevamiento_registro::findOne($id);

        if ($model) {
            if ($model->deleted_at && $hasRolAdminGeneral) {
                $model->deleted_at = null;
                $model->idusuario_borra = null;
                if ($model->update()) {
                    Yii::$app->session->setFlash('success', ' Se reactivó correctamente el registro #' . $model->idrelevamientoregistro);
                } else {
                    Yii::$app->session->setFlash('error', ' Error al reactivar el registro #' . $model->idrelevamientoregistro);
                }
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_relevamiento_registro', $model->idrelevamientoregistro, $model->getAttributes());
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            Yii::$app->session->setFlash('error', " El registro no existe.");
        }
        return $this->redirect(['mds_relevamiento_registro/index']);
    }
    protected function getUsuariosFiltro()
    {
        //Busqueda de usuarios profesionales intervinientes que se encuentran cargados en intervenciones
        $usuariosFiltro = Mds_relevamiento_registro::findBySql(
            "SELECT
                usuario.idusuario as idusuario,
                UPPER (CONCAT (usuario.apellido, ' ', usuario.nombre)) as usuario_nombre
                FROM mds_relevamiento_registro
                INNER JOIN mds_seg_usuario usuario
                ON mds_relevamiento_registro.idusuario_carga = usuario.idusuario
                WHERE usuario.activo = 1
                ORDER BY usuario_nombre ASC
            "
        )->asArray()->all();

        $usuariosFiltro = ArrayHelper::map($usuariosFiltro, 'idusuario', 'usuario_nombre');
        return $usuariosFiltro;
    }

    protected function getEdificiosFiltro()
    {
        $arrayCapas = Mds_relevamiento_registro::ARRAY_CAPAS;
        $edificios = Sds_gis_capa_item::find()
            ->select('idcapaitem,descripcion')
            ->where(['idcapa' => $arrayCapas, 'activo' => 1])
            ->orderBy(['descripcion' => SORT_ASC])
            ->asArray()
            ->all();

        $result = ArrayHelper::map($edificios, 'idcapaitem', 'descripcion');
        return $result;
    }

    private function storeAdjuntoOtros($adjuntos, $model)
    {
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $pathCertificaciones = __DIR__ . '/../web/uploads/relevamiento/';
        $date = date('Y-m-d_H_i_s', time());
        foreach ($adjuntos as $key => $adjunto) {
            $path_info = pathinfo($adjunto["temp"]);
            $extension = $path_info['extension'];
            $nameFile = "relevamiento_{$model->idrelevamientoregistro}_{$date}_{$key}.{$extension}";
            if (rename($pathTemp . $adjunto['temp'], $pathCertificaciones  . $nameFile)) {
                Mds_legales_archivo::saveFile($adjunto['nombre_original'], 'mds_relevamiento_registro', 'relevamiento', $model->idrelevamientoregistro, $nameFile);
            }
        }
    }
    protected function getAdjuntosById($idrelevamientoregistro)
    {
        $adjuntos = Mds_legales_archivo::find()
            ->where([
                'id_objeto' => $idrelevamientoregistro,
                'objeto' => 'mds_relevamiento_registro',
                'tipo' => 'relevamiento',
                'mds_legales_archivo.activo' => 1
            ])
            ->asArray()
            ->all();
        return $adjuntos;
    }
    public function actionGuardarlogmanualusuario()
    {
        $success = false;
        if (Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_relevamiento_manual', null, array())) {
            $success = true;
        };
        return json_encode(['success' => $success]);
    }
}
