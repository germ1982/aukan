<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_org_organismo_externo;
use app\models\Mds_org_organismo_externoSearch;
use app\models\Mds_seg_item;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * Mds_org_organismo_externoController implements the CRUD actions for Mds_org_organismo_externo model.
 */
class Mds_org_organismo_externoController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ORG_ORGANISMO_EXTERNO,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_org_organismo_externo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_org_organismo_externoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_org_organismo_externo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Mds_org_organismo_externo #" . $id,
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
     * Creates a new Mds_org_organismo_externo model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_org_organismo_externo();
        // $model->activo = true;
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!$request->isGet && $model->load($request->post())) {
                $model->idorganismoexterno = sizeof(Mds_org_organismo_externo::find()->all());
                // Upload archivo adjunto
                $tmpfile = UploadedFile::getInstance($model, 'temp_logo');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nombre = $model->random_filename(30, '/uploads/organismos', $extension);
                    $model->logo = $nombre;
                    if (!file_exists('uploads/organismos/' . $model->idorganismoexterno . '/archivo/')) {
                        mkdir('uploads/organismos/' . $model->idorganismoexterno . '/archivo/', 0777, true);
                    }
                    $tmpfile->saveAs('uploads/organismos/' . $model->idorganismoexterno . '/archivo/' . $nombre);
                    $model->save();
                }

                if ($model->save()) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Nuevo Organismo Externo",
                        'content' => '<span class="text-success">Organismo Externo creado exitosamente!</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                }
            }
            return [
                'title' => "Nuevo Organismo Externo",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idorganismoexterno]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_org_organismo_externo model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->borrar_logo = false;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!$request->isGet && $model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();

                // Upload archivo adjunto
                $tmpfile = UploadedFile::getInstance($model, 'temp_logo');
                if (isset($tmpfile)) {
                    // elimino el actual si existe
                    if ($model->logo) {
                        $path = Yii::$app->basePath . '/web/uploads/organismos/' . $model->idorganismoexterno . '/archivo/' . $model->logo;
                        unlink($path);
                    }
                    // creo el nuevo
                    $extension = $tmpfile->extension;
                    $nombre = $model->random_filename(30, '/uploads/organismos', $extension);
                    $model->logo = $nombre;
                    if (!file_exists('uploads/organismos/' . $model->idorganismoexterno . '/archivo/')) {
                        mkdir('uploads/organismos/' . $model->idorganismoexterno . '/archivo/', 0777, true);
                    }
                    $tmpfile->saveAs('uploads/organismos/' . $model->idorganismoexterno . '/archivo/' . $nombre);
                } else {
                    // Valida si quitó el adjunto y en caso de que haya tenido uno, lo borra
                    if ($model->borrar_logo && $model->logo) {
                        $path = Yii::$app->basePath . '/web/uploads/organismos/' . $model->idorganismoexterno . '/archivo/' . $model->logo;
                        unlink($path);
                        $model->logo = null;
                    }
                }
                $guardado = $model->save();
                if ($guardado) {
                    $transaction->commit();
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Mds_org_organismo_externo #" . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
            }
            return [
                'title' => "Actualizar Organismo Externo #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idorganismoexterno]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_org_organismo_externo model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

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
     * Delete multiple existing Mds_org_organismo_externo model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    /* public function actionBulkDelete()
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
    /* return $this->redirect(['index']);
        }
    } */

    /**
     * Finds the Mds_org_organismo_externo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_org_organismo_externo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_org_organismo_externo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
