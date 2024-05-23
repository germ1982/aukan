<?php

namespace app\controllers;

use Yii;
use app\models\Mds_relevamiento_respuesta;
use app\models\Mds_relevamiento_respuestaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Mds_seg_permiso;
use yii\web\ForbiddenHttpException;
use app\models\Mds_seg_item;

/**
 * Mds_relevamiento_respuestaController implements the CRUD actions for Mds_relevamiento_respuesta model.
 */
class Mds_relevamiento_respuestaController extends Controller
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
        ];
    }

    /**
     * Lists all Mds_relevamiento_respuesta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RELEVAMIENTO_EDILICIO, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "ver");
        if ($hasOnePermission) {
            $searchModel = new Mds_relevamiento_respuestaSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_relevamiento_respuesta model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RELEVAMIENTO_EDILICIO, $usuarioAuth->idusuario);
        $hasOnePermission = $this->hasOnePermission($permissions, "ver");
        if ($hasOnePermission) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Mds_relevamiento_respuesta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RELEVAMIENTO_EDILICIO, $usuarioAuth->idusuario);
        $hasOnePermissionAlta = $this->hasOnePermission($permissions, "alta");
        $hasOnePermissionEdit = $this->hasOnePermission($permissions, "modificar");
        if ($hasOnePermissionAlta || $hasOnePermissionEdit) {
            $model = new Mds_relevamiento_respuesta();
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idrelevamientorespuesta]);
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_relevamiento_respuesta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        $permissions = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RELEVAMIENTO_EDILICIO, $usuarioAuth->idusuario);
        $hasOnePermissionAlta = $this->hasOnePermission($permissions, "alta");
        $hasOnePermissionEdit = $this->hasOnePermission($permissions, "modificar");
        if ($hasOnePermissionAlta || $hasOnePermissionEdit) {
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idrelevamientorespuesta]);
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }


    /**
     * Finds the Mds_relevamiento_respuesta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_relevamiento_respuesta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_relevamiento_respuesta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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
}
