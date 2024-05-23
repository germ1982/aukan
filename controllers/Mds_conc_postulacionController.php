<?php

namespace app\controllers;

use Yii;
use app\models\Mds_conc_impugnacion;
use app\models\Mds_conc_postulacion;
use app\models\Mds_conc_postulacionSearch;
use app\models\Mds_conc_solicitud;

use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;

use yii\filters\AccessControl;
use app\components\AccessRule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use \yii\web\Response;

/**
 * Mds_conc_postulacionController implements the CRUD actions for Mds_conc_postulacion model.
 */
class Mds_conc_postulacionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index', 'create', 'update', 'delete', 'view', 'reactivate', 'impugnacion'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'reactivate', 'impugnacion'],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_CONCURSO
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_conc_postulacion models.
     * @return mixed
     */
    public function actionIndex($idsolicitud = null)
    {
        $permissionCrud = self::getPermissionsCrud();

        if ($permissionCrud) {
            $searchModel = new Mds_conc_postulacionSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $idsolicitud);
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL);

            $urlReferer = $_SERVER["HTTP_REFERER"] ?? null;
            $allowedRefererKeywords = ['mds_conc_postulacion', 'mds_conc_historial', 'site'];

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if ($urlReferer && !$this->anyRefererKeywordsPresent($urlReferer, $allowedRefererKeywords)) {
                $_SESSION["urlAnteriorMdsConcPostulacion"] = $urlReferer;
            } else if (!$urlReferer || strpos($urlReferer, 'site')) {
                $_SESSION["urlAnteriorMdsConcPostulacion"] = null;
            }

            $solicitud = $idsolicitud ? Mds_conc_solicitud::findOne($idsolicitud) : null;

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'permission' => $permissionCrud,
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
                'vacantesFiltro' => $this->getVacantesFiltro($idsolicitud),
                'estadosFiltro' => $this->getEstadosFiltro($idsolicitud),
                'concursosFiltro' => $this->getConcursosFiltro($idsolicitud),
                'urlAnterior' => $_SESSION["urlAnteriorMdsConcPostulacion"] ?? null,
                'solicitud' => $solicitud
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Delete an existing Mds_conc_postulacion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
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
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_conc_postulacion', $model->idpostulacion, $model->getAttributes());
                    Yii::$app->session->setFlash('success', " Se eliminó correctamente la postulación #" . $model->idpostulacion);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al borrar la postulación.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la postulación.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index', 'idsolicitud' => $model->idsolicitud]);
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
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_conc_postulacion', $model->idpostulacion, $model->getAttributes());
                    Yii::$app->session->setFlash('success', " Se reactivó correctamente el postulación #" . $model->idpostulacion);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', "Error al reactivar la postulación.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la postulación.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

        return $this->redirect(['index', 'idsolicitud' => $model->idsolicitud]);
    }

    public function actionImpugnacion($idpostulacion)
    {
        $model = Mds_conc_impugnacion::findOne($idpostulacion);

        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $content = $this->renderAjax('modal_impugnacion', [
                'model' => $model,
            ]);
        }

        return [
            'title' => 'Ver impugnación',
            'content' => $content,
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
        ];
    }

    public function actionDashboard()
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL);
        $hasRolDashboard = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_DASHBOARD);

        if ($hasRolAdminGeneral || $hasRolDashboard) {
            /*
            Cantidad de registros certificaciones (activas)
            */
            $fechaInicio = isset(Yii::$app->request->post()['FECHA_INICIO']) ? Yii::$app->request->post()['FECHA_INICIO'] : null;
            $fechaFin = null;
            $fechaFinOriginal = isset(Yii::$app->request->post()['FECHA_FIN']) ? Yii::$app->request->post()['FECHA_FIN'] : null;
            if ($fechaFinOriginal) {
                $fechaFin = date_create($fechaFinOriginal);
                $fechaFin = $fechaFin->modify('+1 day');
                $fechaFin = date_format($fechaFin, 'Y-m-d');
            }

            $where = "mds_conc_postulacion.deleted_at IS NULL";
            if ($fechaInicio && $fechaFin) {
                $where .= " AND mds_conc_postulacion.created_at >= '$fechaInicio' AND mds_conc_postulacion.created_at <= '$fechaFin'";
            } else if ($fechaInicio) {
                $where .= " AND mds_conc_postulacion.created_at >= '$fechaInicio'";
            } else if ($fechaFin) {
                $where .= " AND mds_conc_postulacion.created_at <= '$fechaFin'";
            }

            $concursos = Mds_conc_postulacion::getConcursosByPostulaciones($where);

            if (count($concursos) > 0) {
                foreach ($concursos as $index => $concurso) {
                    $idConcurso = $concurso['idconcurso'];
                    $postulaciones = Mds_conc_postulacion::getPostulacionByIdConcurso($idConcurso, $where);

                    $estados = array();
                    $categorias = array();

                    if (count($postulaciones) > 0) {
                        $estados = Mds_conc_postulacion::getEstadosByIdConcurso($idConcurso, $where);
                        $categorias = Mds_conc_postulacion::getCategoriasByIdConcurso($idConcurso, $where);

                        foreach ($postulaciones as $postulacion) {
                            $this->contarCantidadRegsitros($estados, $postulacion, 'estado', 'Por estados');
                            $this->contarCantidadEstadosPorCategoria($categorias, $estados, $postulacion);
                        }

                        $this->usortArrayByCantidadRegistros($estados);
                        $arrayIndicadores = array_merge(
                            $estados,
                        );
                    }

                    $concursos[$index]['postulaciones'] = $postulaciones ? $postulaciones : array();
                    $concursos[$index]['indicadores'] = $arrayIndicadores;
                    $concursos[$index]['categorias'] = $categorias;
                }
            }

            return $this->render('dashboard/index', [
                'concursos' => $concursos,
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFinOriginal,
                // 'urlIndex' => $urlIndex,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }
    /**
     * Finds the Mds_conc_postulacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_conc_postulacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_conc_postulacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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

    protected function getVacantesFiltro($idsolicitud)
    {
        $vacantes = Mds_conc_postulacion::getVacantesFiltro($idsolicitud);
        if ($vacantes) {
            return  ArrayHelper::map($vacantes, 'idvacante', 'detalleVacante');
        }
        return [];
    }

    protected function getEstadosFiltro($idsolicitud)
    {
        $estados = Mds_conc_postulacion::getEstadosFiltro($idsolicitud);
        if ($estados) {
            return  ArrayHelper::map($estados, 'idconfiguracion', 'estado');
        }
        return [];
    }

    protected function getConcursosFiltro($idsolicitud)
    {
        $concursos = Mds_conc_postulacion::getConcursosFiltro($idsolicitud);
        if ($concursos) {
            return  ArrayHelper::map($concursos, 'idconfiguracion', 'concurso');
        }
        return [];
    }

    protected function anyRefererKeywordsPresent($url, $keywords)
    {
        foreach ($keywords as $keyword) {
            if (strpos($url, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    protected function contarCantidadRegsitros(&$array, $postulacion, $idKey, $titulo)
    {
        $flag = true;
        $index = 0;

        while ($flag && $index < count($array)) {
            $array[$index]['titulo'] = $titulo;
            $array[$index]['cantidadRegistros'] = isset($array[$index]['cantidadRegistros']) ? $array[$index]['cantidadRegistros'] :  0;
            if ($postulacion[$idKey] == $array[$index][$idKey]) {
                $array[$index]['cantidadRegistros']++;
                if ($idKey === 'estado') {
                    $array[$index]['color'] = $this->definirColorEstado($postulacion->estado);
                }
                $flag = false;
            }
            $index++;
        }
    }

    protected function contarCantidadEstadosPorCategoria(&$categorias, $estados, $postulacion)
    {
        $flag = true;
        $indexCategoria = 0;
        $indexEstados = 0;

        while ($flag && $indexCategoria < count($categorias)) {
            if ($postulacion->vacante->categoria == $categorias[$indexCategoria]['categoria']) {
                while ($flag && $indexEstados < count($estados)) {
                    if ($postulacion->estado == $estados[$indexEstados]['estado']) {
                        $categorias[$indexCategoria]['estados'][$postulacion->estado]['cantidadRegistros'] = isset($categorias[$indexCategoria]['estados'][$postulacion->estado]['cantidadRegistros']) ? $categorias[$indexCategoria]['estados'][$postulacion->estado]['cantidadRegistros'] : 0;
                        $categorias[$indexCategoria]['estados'][$postulacion->estado]['cantidadRegistros']++;
                        $categorias[$indexCategoria]['estados'][$postulacion->estado]['descripcion'] = $postulacion->estado0->descripcion;
                        $categorias[$indexCategoria]['estados'][$postulacion->estado]['color'] = $this->definirColorEstado($postulacion->estado);
                        $flag = false;
                    }
                    $indexEstados++;
                }
            }
            $indexCategoria++;
        }
    }

    private function usortArrayByCantidadRegistros(&$array)
    {
        usort($array, function ($a, $b) {
            return $b['cantidadRegistros'] - $a['cantidadRegistros'];
        });
    }

    private function definirColorEstado($estado)
    {
        switch ($estado) {
            case Mds_conc_solicitud::ESTADO_ADMITIDO:
            case Mds_conc_solicitud::ESTADO_SELECCIONADO:
                $color = '#47a447';
                break;
            case Mds_conc_solicitud::ESTADO_NO_ADMITIDO:
            case Mds_conc_solicitud::ESTADO_RECHAZADO:
                $color = '#d2322d';
                break;
            case Mds_conc_solicitud::ESTADO_IMPUGNADO:
                $color = '#ed9c28';
                break;
            case Mds_conc_solicitud::ESTADO_INSCRIPTO:
                $color = '#5bc0de';
                break;
            default:
                $color = '#777';
                break;
        }
        return $color;
    }
}
