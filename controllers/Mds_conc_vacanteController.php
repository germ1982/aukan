<?php

namespace app\controllers;

use Yii;
use app\models\Mds_conc_postulacion;
use app\models\Mds_conc_vacante;
use app\models\Mds_conc_vacanteSearch;
use app\models\Mds_conc_solicitud;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

use kartik\mpdf\Pdf;

use yii\filters\AccessControl;
use app\components\AccessRule;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Mds_conc_vacanteController implements the CRUD actions for Mds_conc_vacante model.
 */
class Mds_conc_vacanteController extends Controller
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
                    'postulantes',
                    'guardarlogmanualusuario',
                    'get_categoria_by_idconcurso',
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
                            'postulantes',
                            'guardarlogmanualusuario',
                            'get_categoria_by_idconcurso',
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
     * Lists all Mds_conc_vacante models.
     * @return mixed
     */
    public function actionIndex()
    {
        $permissionCrud = self::getPermissionsCrud();

        if ($permissionCrud) {
            $searchModel = new Mds_conc_vacanteSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'permission' => $permissionCrud,
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
                'categoriasFiltro' => $this->getCategoriasFiltro(),
                'concursosFiltro' => $this->getConcursosFiltro(),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_conc_vacante model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_conc_vacante', $id, array());

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Ver vacante #" . $model->idvacante,
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

            $model = new Mds_conc_vacante();
            $model->idusuario = Yii::$app->user->identity->idusuario;
            $model->created_at = date('Y-m-d H:i:s');

            $categoriaOptions = [];

            $concursoOptions = ArrayHelper::map(
                Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::CONCURSO_TIPO, true),
                'idconfiguracion',
                'descripcion'
            );

            if (count($concursoOptions) === 1) {
                //Si solo tenemos una opcion, precargamos el valor en el select
                $keys = array_keys($concursoOptions);
                $model->idconcurso = reset($keys);

                $categoriaOptions = ArrayHelper::map(
                    Mds_conc_vacante::getCategoriasDisponiblesByConcurso($model->idconcurso, null),
                    'idconfiguracion',
                    'descripcion'
                );
            }

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $title = "Crear nueva vacante";
                $botonVolver = Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);
                $botonGuardar = Html::button('Guardar', ['class' => 'btn btn-success', 'type' => "submit"]);
                $buttons = $botonVolver;

                if ($request->isGet) {
                    $content = $this->renderAjax('create', [
                        'model' => $model,
                        'categoriaOptions' => $categoriaOptions,
                        'concursoOptions' => $concursoOptions,
                    ]);

                    $buttons .= " $botonGuardar";
                } else if ($model->load($request->post()) && $model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    if ($model->save()) {
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_conc_vacante', $model->idvacante, $model->getAttributes());
                        $content = "<span class='text-success'> Se generó correctamente  la vacante #$model->idvacante</span>";
                    } else {
                        $transaction->rollBack();
                        $content = '<span class="text-danger">Error al guardar la vacante</span>';
                    }
                } else {
                    $idConcurso = isset($request->post()['Mds_conc_vacante']['idconcurso']) ? $request->post()['Mds_conc_vacante']['idconcurso'] : null;
                    $idCategoria = isset($request->post()['Mds_conc_vacante']['categoria']) ? $request->post()['Mds_conc_vacante']['categoria'] : null;

                    if ($idConcurso) {
                        $categoriaOptions = ArrayHelper::map(
                            Mds_conc_vacante::getCategoriasDisponiblesByConcurso($idConcurso, null),
                            'idconfiguracion',
                            'descripcion'
                        );
                    }

                    if ($idCategoria) {
                        $model->categoria = $idCategoria;
                    }

                    $buttons .= " $botonGuardar";
                    $content = $this->renderAjax('create', [
                        'model' => $model,
                        'categoriaOptions' => $categoriaOptions,
                        'concursoOptions' => $concursoOptions,
                    ]);
                }

                return [
                    'title' => $title,
                    'content' => $content,
                    'footer' => $buttons
                ];
            } else {
                if ($model->load($request->post())) {
                    if ($model->validate()) {
                        $transaction = Yii::$app->db->beginTransaction();
                        if ($model->save()) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_conc_vacante', $model->idvacante, $model->getAttributes());
                            Yii::$app->session->setFlash('success', " Se generó correctamente  la vacante #" . $model->idvacante);
                        } else {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', "Error al guardar la vacante.");
                        }
                    } else {
                        Yii::$app->session->setFlash('error', "Error al validar los datos de la vacante.");
                    }
                    return $this->redirect(['mds_conc_vacante/index']);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                        'categoriaOptions' => $categoriaOptions,
                        'concursoOptions' => $concursoOptions,
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_conc_vacante model.
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

            if ($model) {
                $model->updated_at = date('Y-m-d H:i:s');

                $categoriaOptions = ArrayHelper::map(
                    Mds_conc_vacante::getCategoriasDisponiblesByConcurso($model->idconcurso, $id),
                    'idconfiguracion',
                    'descripcion'
                );

                $concursoOptions = ArrayHelper::map(
                    Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::CONCURSO_TIPO, true),
                    'idconfiguracion',
                    'descripcion'
                );

                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;

                    $title = "Actualizar Vacante #" . $model->idvacante;
                    $botonVolver = Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);
                    $botonEditar = Html::button('Guardar', ['class' => 'btn btn-success', 'type' => "submit"]);
                    $buttons = $botonVolver;

                    if ($request->isGet) {
                        $content = $this->renderAjax('update', [
                            'model' => $model,
                            'categoriaOptions' => $categoriaOptions,
                            'concursoOptions' => $concursoOptions,
                        ]);
                        $buttons .= " $botonEditar";
                    } else {
                        $model->cantidad = isset($request->post()['Mds_conc_vacante']['cantidad']) ? $request->post()['Mds_conc_vacante']['cantidad'] : null;
                        $model->requiere_titulo = isset($request->post()['Mds_conc_vacante']['requiere_titulo']) ? $request->post()['Mds_conc_vacante']['requiere_titulo'] : null;
                        if ($model->validate()) {
                            $transaction = Yii::$app->db->beginTransaction();
                            if ($model->update()) {
                                $transaction->commit();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_vacante', $model->idvacante, $model->getAttributes());
                                $content = "<span class='text-success'>Se actualizó correctamente la vacante #$model->idvacante </span>";
                            } else {
                                $transaction->rollBack();
                                $content = "<span class='text-danger'>Error al generar la vacante.</span>";
                            }
                        } else {
                            $content = $this->renderAjax('update', [
                                'model' => $model,
                                'categoriaOptions' => $categoriaOptions,
                                'concursoOptions' => $concursoOptions,
                            ]);
                            $buttons .= " $botonEditar";
                        }
                    }

                    return [
                        'title' => $title,
                        'content' => $content,
                        'footer' => $buttons
                    ];
                } else {
                    if ($model->load($request->post())) {
                        if ($model->validate()) {
                            $transaction = Yii::$app->db->beginTransaction();
                            if ($model->update()) {
                                $transaction->commit();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_vacante', $model->idvacante, $model->getAttributes());
                                Yii::$app->session->setFlash('success', " Se actualizó correctamente la vacante #" . $model->idvacante);
                            } else {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', "Error al generar la vacante.");
                            }
                        } else {
                            Yii::$app->session->setFlash('error', "Error al validar los datos de la vacante.");
                        }
                        return $this->redirect(['mds_conc_vacante/index']);
                    } else {
                        return $this->render('update', [
                            'model' => $model,
                            'categoriaOptions' => $categoriaOptions,
                            'concursoOptions' => $concursoOptions,
                        ]);
                    }
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_conc_vacante model.
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
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_conc_vacante', $model->idvacante, $model->getAttributes());
                    Yii::$app->session->setFlash('success', " Se eliminó correctamente la vacante #" . $model->idvacante);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al borrar la vacante.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la vacante.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['mds_conc_vacante/index']);
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
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_vacante', $model->idvacante, $model->getAttributes());
                    Yii::$app->session->setFlash('success', " Se reactivó correctamente la vacante #" . $model->idvacante);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al reactivar la vacante.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la vacante.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

        return $this->redirect(['mds_conc_vacante/index']);
    }

    public function actionReporte($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionRead = $permissionCrud['permissionRead'];

        if ($permissionRead) {
            $model =  $this->findModel($id);

            if ($model) {
                $usuarioAuth = Yii::$app->user->identity;
                $dateToday = date('d/m/Y H:i:s');
                $idVacante = $model->idvacante;

                $content = $this->renderPartial('reporte', [
                    'vacante' => $model,
                ]);

                $pdf = new Pdf([
                    'mode' => Pdf::MODE_UTF8,
                    'format' => Pdf::FORMAT_A4,
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    'destination' => Pdf::DEST_BROWSER,
                    'filename' => 'Reporte_vacante_' . $idVacante . '.pdf',
                    'content' => $content,
                    'defaultFontSize' => 12,
                    'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                    'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}div.saltopagina{page-break-after:always}',
                    'methods' => [
                        'SetTitle' => 'DETALLE DE LA VACANTE #' . $idVacante,
                        'SetHeader' => null,
                        'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
                    ]
                ]);

                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_conc_historial', $id, array());
                return $pdf->render();
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'No existe la solicitud concurso.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionPostulantes()
    {
        $id = Yii::$app->request->queryParams['id'];
        $postulantes =  Mds_conc_postulacion::find()
            ->select(['postulacion.idpostulacion', 'postulacion.created_at', 'solicitud.nombre', 'solicitud.apellido', 'solicitud.documento'])
            ->from(['mds_conc_postulacion postulacion'])
            ->innerJoin('mds_conc_solicitud solicitud', 'postulacion.idsolicitud = solicitud.idsolicitud')
            ->where("idvacante = $id AND postulacion.deleted_at IS null")
            ->orderBy(['apellido' => SORT_ASC, 'nombre' => SORT_ASC])
            ->asArray()
            ->all();

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_conc_vacante_postulaciones', $id, array());
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'title' => "Listado de postulantes",
            'content' => $this->renderAjax('modal_postulantes', [
                'model' => $postulantes,
                'id' => $id,
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default', 'data-dismiss' => "modal"])
        ];
    }

    public function actionGuardarlogmanualusuario()
    {
        $success = false;
        if (Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_conc_vacante_manual', null, array())) {
            $success = true;
        };
        return json_encode(['success' => $success]);
    }

    public function actionGet_categoria_by_idconcurso($idconcurso, $idvacante = null)
    {
        $categorias = Mds_conc_vacante::getCategoriasDisponiblesByConcurso($idconcurso, $idvacante);
        $categoriasOptions = "<option value='' selected disabled>Seleccione opción...</option>";
        if (sizeof($categorias) > 0) {
            foreach ($categorias as $categoria) {
                $categoriasOptions .= "<option value='" . $categoria->idconfiguracion . "'>" .
                    $categoria->descripcion . "</option>";
            }
        }
        return $categoriasOptions;
    }

    /**
     * Finds the Mds_conc_vacante model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_conc_vacante the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_conc_vacante::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getCategoriasFiltro()
    {
        $categorias = Mds_conc_vacante::getCategoriasFiltro();
        if ($categorias) {
            return  ArrayHelper::map($categorias, 'idconfiguracion', 'categoria');
        }
        return [];
    }

    protected function getConcursosFiltro()
    {
        $concursos = Mds_conc_vacante::getConcursosFiltro();
        if ($concursos) {
            return  ArrayHelper::map($concursos, 'idconfiguracion', 'concurso');
        }
        return [];
    }
}
