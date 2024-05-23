<?php

namespace app\controllers;

use Yii;
use app\models\Mds_seg_usuario_status;
use app\models\Mds_seg_usuario_statusSearch;

use app\models\Mds_seg_permiso;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

use yii\filters\AccessControl;
use app\components\AccessRule;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Mds_seg_usuario_statusController implements the CRUD actions for Mds_seg_usuario_status model.
 */
class Mds_seg_usuario_statusController extends Controller
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
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'allow' => true,
                        'roles' => [
                            Mds_seg_item::MODULO_SEG_USUARIO_STATUS
                        ],
                    ],
                ],
            ],
        ];
    }

    private function getPermissionsCrud()
    {
        $permission = false;

        $idusuario = Yii::$app->user->identity->idusuario;
        $iditem = Mds_seg_item::MODULO_SEG_USUARIO_STATUS;

        $permisos = Mds_seg_permiso::findBySql(
            "select *
                from mds_seg_permiso
                where idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                AND iditem = {$iditem}"
        )->all();

        if (count($permisos) > 0) {
            $permission = true;
        }

        return $permission;
    }

    /**
     * Lists all Mds_seg_usuario_status models.
     * @return mixed
     */
    public function actionIndex()
    {
        $permissionCrud = self::getPermissionsCrud();

        if ($permissionCrud) {
            $searchModel = new Mds_seg_usuario_statusSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $usuarioCargaFiltro = [];
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'usuarioCargaFiltro' => $this->getUsuarioFiltro("idusuario_carga"),
                'usuarioFiltro' => $this->getUsuarioFiltro("idusuario"),
                'statusFiltro' => $this->getStatusFiltro(),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }


    protected function getUsuarioFiltro($typeUser)
    {
        $data = Mds_seg_usuario_status::getUsuarioFiltro($typeUser);
        if ($data) {
            return  ArrayHelper::map($data, 'idusuario', 'nombre_usuario');
        }
        return [];
    }

    protected function getStatusFiltro()
    {
        $data = Mds_seg_usuario_status::getStatusFiltro();
        if ($data) {
            return  ArrayHelper::map($data, 'idconfiguracion', 'descripcion');
        }
        return [];
    }

    /**
     * Displays a single Mds_seg_usuario_status model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $permissionCrud = self::getPermissionsCrud();

        if ($permissionCrud) {
            $request = Yii::$app->request;
            $model = $this->findModel($id);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_seg_usuario_status', $id, array());

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Ver Estado #" . $model->idseg_usuario_status,
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
    /**
     * Finds the Mds_conc_solicitud model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_conc_solicitud the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_seg_usuario_status::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
