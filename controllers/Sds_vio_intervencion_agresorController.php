<?php

namespace app\controllers;

use app\models\Mds_sys_log;
use app\models\Sds_com_configuracion;
use app\models\Sds_vio_agresor;
use app\models\Sds_vio_intervencion;
use Yii;
use app\models\Sds_vio_intervencion_agresor;
use app\models\Sds_vio_intervencion_agresorSearch;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\ForbiddenHttpException;

/**
 * Sds_vio_intervencion_agresorController implements the CRUD actions for Sds_vio_intervencion_agresor model.
 */
class Sds_vio_intervencion_agresorController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sds_vio_intervencion_agresor models.
     * @return mixed
     */
    public function actionIndex($idintervencion = null)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new Sds_vio_intervencion_agresor();
            $model->idintervencion = $idintervencion;

            $model->agresores = $this->searchAgresores($idintervencion);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_vio_intervencion&id=' . $idintervencion, null, array());
            return [
                'title' => "Agresores",
                'content' => $this->renderAjax('index', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }
    public function searchAgresores($id)
    {
        $intervenciones = Sds_vio_intervencion_agresor::find()->where(["idintervencion" => $id, "activo" => 1])->all();
        $agresores = [];
        foreach ($intervenciones as $intervencion) {
            $agresor = Sds_vio_agresor::findOne(['idagresor' => $intervencion->idagresor]);
            $parentezco =  Sds_com_configuracion::findOne(['idconfiguracion' => $intervencion->parentezco]);
            if ($agresor) {
                array_push($agresores, [
                    'nombre' => $agresor->nombre,
                    'apellido' => $agresor->apellido,
                    'dni' => $agresor->dni,
                    'idagresor' => $agresor->idagresor,
                    'idintervencion' => $intervencion->idintervencion,
                    'parentezco' => $parentezco ? $parentezco->descripcion : "",
                ]);
            }
        }
        return new ArrayDataProvider([
            'allModels' => $agresores,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['nombre', 'dni'],
            ],
        ]);
    }

    /**
     * Displays a single Sds_vio_intervencion_agresor model.
     * @param integer $idintervencion
     * @param integer $idagresor
     * @return mixed
     */
    public function actionView($idintervencion, $idagresor)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            $request = Yii::$app->request;
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Sds_vio_intervencion_agresor #" . $idintervencion, $idagresor,
                    'content' => $this->renderAjax('view', [
                        'model' => $this->findModel($idintervencion, $idagresor),
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'idintervencion, $idagresor' => $idintervencion, $idagresor], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return $this->render('view', [
                    'model' => $this->findModel($idintervencion, $idagresor),
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Sds_vio_intervencion_agresor model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            $request = Yii::$app->request;
            $model = new Sds_vio_intervencion_agresor();

            if ($request->isAjax) {
                /*
                *   Process for ajax request
                */
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    return [
                        'title' => "Create neEEEEw Sds_vio_intervencion_agresor",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                } else if ($model->load($request->post())) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Create new Sds_vio_intervencion_agresor",
                        'content' => '<span class="text-success">Create Sds_vio_intervencion_agresor success</span>',
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                } else {
                    return [
                        'title' => "Create new Sds_vio_intervencion_agresor",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                }
            } else {
                /*
                *   Process for non-ajax request
                */
                if ($model->load($request->post()) && $model->save()) {
                    return $this->redirect(['view', 'idintervencion' => $model->idintervencion, 'idagresor' => $model->idagresor]);
                } else {
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Sds_vio_intervencion_agresor model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $idintervencion
     * @param integer $idagresor
     * @return mixed
     */
    public function actionUpdate($idintervencion, $idagresor)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            $request = Yii::$app->request;
            $model = $this->findModel($idintervencion, $idagresor);

            if ($request->isAjax) {
                /*
                *   Process for ajax request
                */
                Yii::$app->response->format = Response::FORMAT_JSON;
                if ($request->isGet) {
                    return [
                        'title' => "Update Sds_vio_intervencion_agresor #" . $idintervencion, $idagresor,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                } else if ($model->load($request->post()) && $model->save()) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Sds_vio_intervencion_agresor #" . $idintervencion, $idagresor,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Edit', ['update', 'idintervencion, $idagresor' => $idintervencion, $idagresor], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {
                    return [
                        'title' => "Update Sds_vio_intervencion_agresor #" . $idintervencion, $idagresor,
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            } else {
                /*
                *   Process for non-ajax request
                */
                if ($model->load($request->post()) && $model->save()) {
                    return $this->redirect(['view', 'idintervencion' => $model->idintervencion, 'idagresor' => $model->idagresor]);
                } else {
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Delete an existing Sds_vio_intervencion_agresor model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $idintervencion
     * @param integer $idagresor
     * @return mixed
     */
    public function actionDelete($idintervencion, $idagresor)
    {
        $hasRolViolencia = Sds_vio_intervencion::hasRolViolencia();
        if ($hasRolViolencia) {
            $request = Yii::$app->request;
            $model = $this->findModel($idintervencion, $idagresor);
            $model->activo = 0;
            $model->save();
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_vio_intervencion_agresor', $model->idintervencionagresor, $model->getAttributes());

            if ($request->isAjax) {
                /*
                *   Process for ajax request
                */
                // Carga index
                $model = Sds_vio_intervencion::findOne($idintervencion);
                $model->agresores = $this->searchAgresores($idintervencion);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Agresores",
                    'content' => $this->renderAjax('/sds_vio_intervencion_agresor/index', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                /*
                *   Process for non-ajax request
                */
                return $this->redirect(['index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Finds the Sds_vio_intervencion_agresor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $idintervencion
     * @param integer $idagresor
     * @return Sds_vio_intervencion_agresor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idintervencion, $idagresor)
    {
        if (($model = Sds_vio_intervencion_agresor::findOne(['idintervencion' => $idintervencion, 'idagresor' => $idagresor])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
