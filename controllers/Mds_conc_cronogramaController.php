<?php

namespace app\controllers;

use Yii;
use app\models\Mds_conc_cronograma;
use app\models\Mds_conc_solicitud;
use app\models\Mds_conc_cronogramaSearch;
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

use yii\filters\AccessControl;
use app\components\AccessRule;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Mds_conc_cronogramaController implements the CRUD actions for Mds_conc_cronograma model.
 */
class Mds_conc_cronogramaController extends Controller
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
                    'guardarlogmanualusuario',
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
                            'guardarlogmanualusuario',
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
        $rolesConcursos = implode(',', [Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL]);
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
        if ($countPermisos) {
            $permissionCreate = true;
            $permissionRead = true;
            $permissionUpdate = true;
            $permissionDelete = true;
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
     * Lists all Mds_conc_cronograma models.
     * @return mixed
     */
    public function actionIndex()
    {
        $permissionCrud = self::getPermissionsCrud();

        if ($permissionCrud) {
            $searchModel = new Mds_conc_cronogramaSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'permission' => $permissionCrud,
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
                'usuarioCargaFiltro' => $this->getFilterUsuarioCarga(),
                'concursosFiltro' => $this->getConcursosFiltro(),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_conc_cronograma model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_conc_cronograma', $id, array());
            $fechaInicio = "";
            $fechaFin = "";

            if ($model->fecha_inicio) {
                $dateInput1 = explode('-', $model->fecha_inicio);
                $dateInput2 = $dateInput1[2];
                $dia_hora = explode(' ', $dateInput2);
                $dia = $dia_hora[0];
                $hora = $dia_hora[1];
                $hora = explode(':', $hora);
                $hora = "$hora[0]:$hora[1]";
                $fechaFormateada = $dia . '/' . $dateInput1[1] . '/' . $dateInput1[0];
                $fechaInicio = "$fechaFormateada $hora";
            }

            if ($model->fecha_fin) {
                $dateInput1 = explode('-', $model->fecha_fin);
                $dateInput2 = $dateInput1[2];
                $dia_hora = explode(' ', $dateInput2);
                $dia = $dia_hora[0];
                $hora = $dia_hora[1];
                $hora = explode(':', $hora);
                $hora = "$hora[0]:$hora[1]";
                $fechaFormateada = $dia . '/' . $dateInput1[1] . '/' . $dateInput1[0];
                $fechaFin = "$fechaFormateada $hora";
            }

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Ver Cronograma #" . $model->idetapa,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                        'fechaInicio' => $fechaInicio,
                        'fechaFin' => $fechaFin,
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
            $usuarioAuth = Yii::$app->user->identity;

            $model = new Mds_conc_cronograma();
            $model->idusuario = $usuarioAuth->idusuario;
            $today = date('Y-m-d H:i:s');
            $model->created_at = $today;

            $concursoOptions = ArrayHelper::map(
                Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::CONCURSO_TIPO, true),
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
                $title = "Crear nueva Etapa";
                $botonVolver = Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);
                $botonGuardar = Html::button('Guardar', ['class' => 'btn btn-success', 'type' => "submit"]);
                $buttons = $botonVolver;

                if ($request->isGet) {
                    $content = $this->renderAjax('create', [
                        'model' => $model,
                        'concursoOptions' => $concursoOptions,
                    ]);
                    $buttons .= " $botonGuardar";
                } else if ($model->load($request->post())) {

                    if ($model->fecha_inicio) {
                        $fechaInicioInput = $model->fecha_inicio;
                        $fechaInicioMySQL = date('Y-m-d H:i:s', strtotime($fechaInicioInput)); // Se parsea
                        $model->fecha_inicio = $fechaInicioMySQL;
                    }
                    if ($model->fecha_fin) {
                        $fechaFinInput = $model->fecha_fin;
                        $fechaFinMySQL = date('Y-m-d H:i:s', strtotime($fechaFinInput)); // Se parsea
                        $model->fecha_fin = $fechaFinMySQL;
                    }

                    if ($model->estado === '1') {
                        $model->deleted_at = null;
                        $model->idusuario_borra = null;
                    } else {
                        $model->deleted_at = $today;
                        $model->idusuario_borra = Yii::$app->user->id;
                    }

                    if ($model->validate()) {
                        $transaction = Yii::$app->db->beginTransaction();
                        if ($model->save()) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_conc_cronograma', $model->idetapa, $model->getAttributes());
                            $content = "<span class='text-success'>Se generó correctamente la etapa</span>";
                        } else {
                            $transaction->rollBack();
                            $content = "<span class='text-danger'>Error al guardar la etapa.</span>";
                        }
                    } else {
                        $buttons .= " $botonGuardar";
                        $content = $this->renderAjax('create', [
                            'model' => $model,
                            'concursoOptions' => $concursoOptions,
                        ]);
                    }
                }

                return [
                    'title' => $title,
                    'content' => $content,
                    'footer' => $buttons
                ];
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_conc_cronograma model.
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

            $concursoOptions = ArrayHelper::map(
                Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::CONCURSO_TIPO, true),
                'idconfiguracion',
                'descripcion'
            );

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $title = "Actualizar Etapa #$model->idetapa";
                $botonVolver = Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);
                $botonGuardar = Html::button('Guardar', ['class' => 'btn btn-success', 'type' => "submit"]);
                $buttons = $botonVolver;
                $content = "";

                if ($request->isGet) {
                    if ($model->fecha_inicio) {
                        $fechaInicio = Yii::$app->formatter->asDatetime($model->fecha_inicio, 'php:d-m-Y H:i');
                        $fechaInicioAjustada = date('d-m-Y H:i', strtotime('+3 hours', strtotime($fechaInicio)));
                        $model->fecha_inicio = $fechaInicioAjustada;
                    }

                    if ($model->fecha_inicio) {
                        $fechaInicioInput = $model->fecha_inicio;
                        $fechaInicioMySQL = date('Y-m-d H:i:s', strtotime($fechaInicioInput)); // Se parsea
                        $model->fecha_inicio = $fechaInicioMySQL;
                    }

                    if ($model->fecha_fin) {
                        $fechaFin = Yii::$app->formatter->asDatetime($model->fecha_fin, 'php:d-m-Y H:i');
                        $fechaFinAjustada = date('d-m-Y H:i', strtotime('+3 hours', strtotime($fechaFin)));
                        $model->fecha_fin = $fechaFinAjustada;
                    }

                    $content = $this->renderAjax('update', [
                        'model' => $model,
                        'concursoOptions' => $concursoOptions,
                    ]);
                    $buttons .= " $botonGuardar";
                } else if ($request->post() && $model->load($request->post())) {

                    if ($model->fecha_inicio) {
                        $fechaInicioInput = $model->fecha_inicio;
                        $fechaInicioMySQL = date('Y-m-d H:i:s', strtotime($fechaInicioInput)); // Se parsea
                        $model->fecha_inicio = $fechaInicioMySQL;
                    }
                    if ($model->fecha_fin) {
                        $fechaFinInput = $model->fecha_fin;
                        $fechaFinMySQL = date('Y-m-d H:i:s', strtotime($fechaFinInput)); // Se parsea
                        $model->fecha_fin = $fechaFinMySQL;
                    }

                    $today = date('Y-m-d H:i:s');
                    if ($model->estado === '1') {
                        $model->deleted_at = null;
                        $model->idusuario_borra = null;
                    } else {
                        $model->deleted_at = $today;
                        $model->idusuario_borra = Yii::$app->user->id;
                    }

                    $model->updated_at = $today;

                    if ($model->validate()) {
                        $transaction = Yii::$app->db->beginTransaction();
                        if ($model->save()) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_cronograma', $model->idetapa, $model->getAttributes());
                            $content = "<span class='text-success'>Se actualizó correctamente la etapa #$model->idetapa";
                        } else {
                            $transaction->rollBack();
                            $content = "<span class='text-danger'>Error al actualizar la etapa.</span>";
                        }
                    } else {
                        $buttons .= " $botonGuardar";
                        $content = $this->renderAjax('update', [
                            'model' => $model,
                            'concursoOptions' => $concursoOptions,
                        ]);
                    }
                }

                return [
                    'title' => $title,
                    'content' => $content,
                    'footer' => $buttons
                ];
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_conc_cronograma model.
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
            $model->estado = 0;
            $model->idusuario_borra = Yii::$app->user->id;
            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                if ($model->save()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_conc_cronograma', $model->idetapa, $model->getAttributes());
                    Yii::$app->session->setFlash('success', " Se eliminó correctamente la etapa #" . $model->idetapa);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al borrar la etapa.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la etapa.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['mds_conc_cronograma/index']);
    }

    public function actionReactivate($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionReactivate = $permissionCrud['permissionReactivate'];
        $model = $this->findModel($id);

        if (!is_null($model->deleted_at) && $permissionReactivate) {

            $model->deleted_at = null;
            $model->estado = 1;
            $model->idusuario_borra = null;
            $model->updated_at = date('Y-m-d H:i:s');
            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                if ($model->update()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_cronograma', $model->idetapa, $model->getAttributes());
                    Yii::$app->session->setFlash('success', " Se reactivó correctamente la etapa #" . $model->idetapa);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al reactivar la etapa.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la etapa.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

        return $this->redirect(['mds_conc_cronograma/index']);
    }

    public function actionGuardarlogmanualusuario()
    {
        $success = false;
        if (Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_conc_solicitud_manual', null, array())) {
            $success = true;
        };
        return json_encode(['success' => $success]);
    }

    /**
     * Finds the Mds_conc_cronograma model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_conc_cronograma the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_conc_cronograma::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getFilterUsuarioCarga()
    {
        $usuarioCarga = Mds_conc_cronograma::find()->select([
            'mds_conc_cronograma.idusuario',
            'CONCAT(UPPER(mds_seg_usuario.apellido),\', \', UPPER(mds_seg_usuario.nombre)) AS nombreUsuario'
        ])
        ->where(['deleted_at' => null])
        ->innerJoin('mds_seg_usuario', 'mds_conc_cronograma.idusuario=mds_seg_usuario.idusuario')
        ->asArray()
        ->all();
        $programasFiltro = ArrayHelper::map($usuarioCarga, 'idusuario', 'nombreUsuario');
        return $programasFiltro;
    }

    protected function getConcursosFiltro()
    {
        $concursos = Mds_conc_cronograma::getConcursosFiltro();
        if ($concursos) {
            return  ArrayHelper::map($concursos, 'idconfiguracion', 'concurso');
        }
        return [];
    }
}
