<?php

namespace app\controllers;

use app\models\Configuracion;
use app\models\ConstantesGlobales;
use app\models\LogPlataforma;
use app\models\Persona;
use Yii;
use app\models\UsuarioAsignacionPerfil;
use app\models\UsuarioAsignacionPerfilSearch;
use app\models\Usuarios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * Usuario_asignacion_perfilController implements the CRUD actions for UsuarioAsignacionPerfil model.
 */
class Usuario_asignacion_perfilController extends Controller
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
        ];
    }

    /**
     * Lists all UsuarioAsignacionPerfil models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsuarioAsignacionPerfilSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single UsuarioAsignacionPerfil model.
     * @param integer $idusuario
     * @param integer $idperfil
     * @return mixed
     */
    public function actionView($idusuario, $idperfil)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "UsuarioAsignacionPerfil #" . $idusuario,
                $idperfil,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($idusuario, $idperfil),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'idusuario, $idperfil' => $idusuario, $idperfil], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($idusuario, $idperfil),
            ]);
        }
    }

    /**
     * Creates a new UsuarioAsignacionPerfil model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new UsuarioAsignacionPerfil();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new UsuarioAsignacionPerfil",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                LogPlataforma::registrar(16, 1, $model->idingreso);
                $usuario = Usuarios::findOne($model->idusuario);
                $persona = Persona::get_persona_ayn($usuario->idpersona);
                $perfil = Configuracion::findOne($model->idperfil)->descripcion;
                LogPlataforma::registrar(ConstantesGlobales::ASIGNACION_DE_PERFIL_A_USUARIO, ConstantesGlobales::CREACION, 0, "Se asigno $perfil a $persona");
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new UsuarioAsignacionPerfil",
                    'content' => '<span class="text-success">Create UsuarioAsignacionPerfil success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Create new UsuarioAsignacionPerfil",
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
                $usuario = Usuarios::findOne($model->idusuario);
                $persona = Persona::get_persona_ayn($usuario->idpersona);
                $perfil = Configuracion::findOne($model->idperfil)->descripcion;
                LogPlataforma::registrar(ConstantesGlobales::ASIGNACION_DE_PERFIL_A_USUARIO, ConstantesGlobales::CREACION, 0, "Se asigno $perfil a $persona");
                return $this->redirect(['view', 'idusuario' => $model->idusuario, 'idperfil' => $model->idperfil]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing UsuarioAsignacionPerfil model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $idusuario
     * @param integer $idperfil
     * @return mixed
     */
    public function actionUpdate($idusuario, $idperfil)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($idusuario, $idperfil);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update UsuarioAsignacionPerfil #" . $idusuario,
                    $idperfil,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {

                $usuario = Usuarios::findOne($model->idusuario);
                $persona = Persona::get_persona_ayn($usuario->idpersona);
                $perfil = Configuracion::findOne($model->idperfil)->descripcion;
                LogPlataforma::registrar(ConstantesGlobales::ASIGNACION_DE_PERFIL_A_USUARIO, ConstantesGlobales::MODIFICACION, 0, "Se asigno $perfil a $persona");
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "UsuarioAsignacionPerfil #" . $idusuario,
                    $idperfil,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'idusuario, $idperfil' => $idusuario, $idperfil], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update UsuarioAsignacionPerfil #" . $idusuario,
                    $idperfil,
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
                $usuario = Usuarios::findOne($model->idusuario);
                $persona = Persona::get_persona_ayn($usuario->idpersona);
                $perfil = Configuracion::findOne($model->idperfil)->descripcion;
                LogPlataforma::registrar(ConstantesGlobales::ASIGNACION_DE_PERFIL_A_USUARIO, ConstantesGlobales::MODIFICACION, 0, "Se asigno $perfil a $persona");
                return $this->redirect(['view', 'idusuario' => $model->idusuario, 'idperfil' => $model->idperfil]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing UsuarioAsignacionPerfil model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $idusuario
     * @param integer $idperfil
     * @return mixed
     */
    public function actionDelete($idusuario, $idperfil)
    {
        $request = Yii::$app->request;
        $this->findModel($idusuario, $idperfil)->delete();
        $usuario = Usuarios::findOne($idusuario);
        $persona = Persona::get_persona_ayn($usuario->idpersona);
        $perfil = Configuracion::findOne($idperfil)->descripcion;
        LogPlataforma::registrar(ConstantesGlobales::ASIGNACION_DE_PERFIL_A_USUARIO, ConstantesGlobales::ELIMINACION, 0, "Se quito $perfil a $persona");
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
     * Delete multiple existing UsuarioAsignacionPerfil model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $idusuario
     * @param integer $idperfil
     * @return mixed
     */


    /**
     * Finds the UsuarioAsignacionPerfil model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $idusuario
     * @param integer $idperfil
     * @return UsuarioAsignacionPerfil the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idusuario, $idperfil)
    {
        if (($model = UsuarioAsignacionPerfil::findOne(['idusuario' => $idusuario, 'idperfil' => $idperfil])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
