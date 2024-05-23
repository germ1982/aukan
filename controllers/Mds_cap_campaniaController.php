<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_cap_campania;
use app\models\Mds_cap_campaniaSearch;
use app\models\Mds_seg_item;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Mds_cap_campaniaController implements the CRUD actions for Mds_cap_campania model.
 */
class Mds_cap_campaniaController extends Controller
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
                            Mds_seg_item::MODULO_CAP_INSTANCIA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_cap_campania models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_cap_campaniaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_cap_campania model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Campaña nro: " . $id,
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
     * Creates a new Mds_cap_campania model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_cap_campania();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear nueva campaña",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                // Upload archivo adjunto
                $tmpfile = UploadedFile::getInstance($model, 'temp_logo');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nombre = $model->random_filename(30, '/uploads/campanias', $extension);
                    $model->logo_path = $nombre;
                    if (!file_exists('uploads/campanias/' . $model->idcampania . '/archivo/')) {
                        mkdir('uploads/campanias/' . $model->idcampania . '/archivo/', 0777, true);
                    }
                    $tmpfile->saveAs('uploads/campanias/' . $model->idcampania . '/archivo/' . $nombre);
                    $model->save();
                }

                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Crear nueva campaña",
                    'content' => '<span class="text-success">Campaña creada con éxito</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Crear otra', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Crear nueva campaña",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idcampania]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_cap_campania model.
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
            if ($request->isGet) {
                return [
                    'title' => "Editar Campaña nro: " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();

                // Upload archivo adjunto
                $tmpfile = UploadedFile::getInstance($model, 'temp_logo');
                if (isset($tmpfile)) {
                    // elimino el actual si existe
                    if ($model->logo_path) {
                        $path = Yii::$app->basePath . '/web/uploads/campanias/' . $model->idcampania . '/archivo/' . $model->logo_path;
                        unlink($path);
                    }
                    // creo el nuevo
                    $extension = $tmpfile->extension;
                    $nombre = $model->random_filename(30, '/uploads/campanias', $extension);
                    $model->logo_path = $nombre;
                    if (!file_exists('uploads/campanias/' . $model->idcampania . '/archivo/')) {
                        mkdir('uploads/campanias/' . $model->idcampania . '/archivo/', 0777, true);
                    }
                    $tmpfile->saveAs('uploads/campanias/' . $model->idcampania . '/archivo/' . $nombre);
                } else {
                    // Valida si quitó el adjunto y en caso de que haya tenido uno, lo borra
                    if ($model->borrar_logo && $model->logo_path) {
                        $path = Yii::$app->basePath . '/web/uploads/campanias/' . $model->idcampania . '/archivo/' . $model->logo_path;
                        unlink($path);
                        $model->logo_path = null;
                    }
                }
                $guardado = $model->save(false);
                if ($guardado) {
                    $transaction->commit();
                    return [
                        'title' => "Campaña nro:" . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                return [
                    'title' => "Modificar campaña nro: " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idcampania]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_cap_campania model.
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
     * Finds the Mds_cap_campania model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_cap_campania the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_cap_campania::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
