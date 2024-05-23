<?php

namespace app\controllers;

use Yii;
use app\models\Mds_org_contacto_persona;
use app\models\Mds_org_contacto_personaSearch;
use app\models\Mds_seg_item;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Mds_org_contacto_personaController implements the CRUD actions for Mds_org_contacto_persona model.
 */
class Mds_org_contacto_personaController extends Controller
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
                    // 'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index', 'create', 'update', 'delete', 'bulk-delete', 'view', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ORG_CONTACTOS,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_org_contacto_persona models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_org_contacto_personaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_org_contacto_persona model.
     * @param integer $legajo
     * @param integer $dni
     * @return mixed
     */
    public function actionView($legajo, $dni)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Mds_org_contacto_persona #" . $legajo, $dni,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($legajo, $dni),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'legajo, $dni' => $legajo, $dni], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($legajo, $dni),
            ]);
        }
    }

    /**
     * Creates a new Mds_org_contacto_persona model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_org_contacto_persona();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Mds_org_contacto_persona",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Mds_org_contacto_persona",
                    'content' => '<span class="text-success">Create Mds_org_contacto_persona success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Create new Mds_org_contacto_persona",
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
                return $this->redirect(['view', 'legajo' => $model->legajo, 'dni' => $model->dni]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_org_contacto_persona model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $legajo
     * @param integer $dni
     * @return mixed
     */
    public function actionUpdate($legajo, $dni)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($legajo, $dni);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update Mds_org_contacto_persona #" . $legajo, $dni,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Mds_org_contacto_persona #" . $legajo, $dni,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'legajo, $dni' => $legajo, $dni], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Mds_org_contacto_persona #" . $legajo, $dni,
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
                return $this->redirect(['view', 'legajo' => $model->legajo, 'dni' => $model->dni]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_org_contacto_persona model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $legajo
     * @param integer $dni
     * @return mixed
     */
    public function actionDelete($legajo, $dni)
    {
        $request = Yii::$app->request;
        $this->findModel($legajo, $dni)->delete();

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
     * Delete multiple existing Mds_org_contacto_persona model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $legajo
     * @param integer $dni
     * @return mixed
     */
    /*  public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if ($request->isAjax) { */
    /*
            *   Process for ajax request
            */
    /* Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else { */
    /*
            *   Process for non-ajax request
            */
    /*  return $this->redirect(['index']);
        }
    } */

    /**
     * Finds the Mds_org_contacto_persona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $legajo
     * @param integer $dni
     * @return Mds_org_contacto_persona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($legajo, $dni)
    {
        if (($model = Mds_org_contacto_persona::findOne(['legajo' => $legajo, 'dni' => $dni])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
