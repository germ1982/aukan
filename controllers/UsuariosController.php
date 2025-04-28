<?php

namespace app\controllers;

use app\models\LogPlataforma;
use app\models\Persona;
use app\models\UsuarioAsignacionPerfil;
use app\models\Usuarios;
use app\models\UsuariosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class UsuariosController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Usuarios models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UsuariosSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination->pageSize = 50;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Usuarios model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Usuario Id " . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Usuarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Usuarios();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Nuevo Usuario',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Guardar', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                if ($model->idpersona == null) $guardado = false;

                //Aca comienza el proceso de guardado de la imagen:
                //primero rescata los datos de la imagen cargados en el widget fileInput
                $tmpfile = UploadedFile::getInstance($model, 'imageFile');

                //
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;

                    $nuevo_nombre = "avatar-$model->idpersona.$extension";
                    $model->avatar = $nuevo_nombre;
                    $tmpfile->saveAs('img/usuarios-avatares/' . $nuevo_nombre);
                } else {
                    $model->avatar = "avatar-0.jpg";
                }
                //$model->password = Yii::$app->getSecurity()->generatePasswordHash($model->documento);
                $model->password = hash('sha256', $model->documento);
                $model->status = "1";


                if ($guardado && $model->save()) {
                    $transaction->commit();
                    LogPlataforma::registrar(7, 1, $model->id);

                    if ($model->perfil) {
                        foreach ($model->perfil as $p) {
                            $model_perfil = new UsuarioAsignacionPerfil();
                            $model_perfil->idusuario = $model->id;
                            $model_perfil->idperfil = $p;
                            $model_perfil->activo = 1;
                            $model_perfil->save();
                        }
                    }

                    return [
                        'title' => "Nuevo Usuario",
                        'content' => '<span class="text-success">Usuario Creado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                    ];
                }
            }
            return [
                'title' => "Nuevo Usuario, Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }


    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Editar Usuario',
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Guardar', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                if ($model->idpersona == null) $guardado = false;

                //Aca comienza el proceso de guardado de la imagen
                $tmpfile = UploadedFile::getInstance($model, 'imageFile');

                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;

                    $nuevo_nombre = "avatar-$model->idpersona.$extension";
                    $model->avatar = $nuevo_nombre;
                    $tmpfile->saveAs('img/usuarios-avatares/' . $nuevo_nombre);
                } /* else {
                    $model->avatar = "avatar-0.jpg";
                } */
                //$model->password = Yii::$app->getSecurity()->generatePasswordHash($model->documento);
                $model->status = "1";


                if ($guardado && $model->save()) {
                    $transaction->commit();
                    LogPlataforma::registrar(7, 2, $model->id);
                    UsuarioAsignacionPerfil::deleteAll(['idusuario' => $model->id]);
                    if ($model->perfil) {
                        foreach ($model->perfil as $p) {
                            $model_perfil = new UsuarioAsignacionPerfil();
                            $model_perfil->idusuario = $model->id;
                            $model_perfil->idperfil = $p;
                            $model_perfil->activo = 1;
                            $model_perfil->save();
                        }
                    }

                    return [
                        'title' => "Editar Usuario",
                        'content' => '<span class="text-success">Usuario Editado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                    ];
                }
            }
            return [
                'title' => "Editar Usuario, Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

            ];
        }
    }

    public function actionReset_password($id)
    {
        $model = $this->findModel($id);
        $model_persona = Persona::findOne($model->idpersona);
        $model->documento = $model_persona->documento;
        //$model->password = Yii::$app->getSecurity()->generatePasswordHash($model_persona->documento);
        $model->password = hash('sha256', $model_persona->documento);
        $contenido = $model->save() ? "Se ah reseteado la contraseña" : "Hubo un error al resetear la contraseña";
        LogPlataforma::registrar(7, 6, $id);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Resetear Contraseña',
            'content' => "$contenido", // . json_encode($model->getErrors()),
            'footer' =>
            Html::button('Cerrar', [
                'id' => 'btnCerrar',
                'class' => 'btn btn-default pull-left',
                'data-dismiss' => 'modal',
            ])
        ];
    }

    public function actionDelete($id)
    {

        if ($this->findModel($id)->delete()) {
            LogPlataforma::registrar(7, 3, $id);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Eliminado",
                'content' => '<span class="text-success">Usuario Eliminado Correctamente</span>',
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'center btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }
    }

    /**
     * Finds the Usuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Usuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUpdate_password()
    {
        $request = Yii::$app->request;
        $model = $this->findModel(Yii::$app->user->identity->id);
        $passwordOriginal = $model->password;
        $model->scenario = 'cambiar_clave'; // 🔑 Acá se activa el escenario correcto
        $error = '';
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Cambiar Contraseña',
                    'content' => $this->renderAjax('update_password', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) /* .
                        Html::button('Guardareeeeeeeee', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]), */
                ];
            } else if ($model->load($request->post())) {

                $guardado = true;

                // Validar la contraseña actual manualmente
                if (hash('sha256', $model->password_actual) !== $passwordOriginal) {
                    $guardado = false;
                    $error = "<div style='color: red; font-weight: bold; animation: parpadeo 1s infinite;'><i class='fas fa-exclamation-triangle'></i> La Contraseña Actual No Es Correcta</div><br>";
                    $model->addError('password_actual', 'La contraseña actual no es correcta');
                }

                // Validar el resto con las reglas del modelo
                if (!$model->validate()) {
                    $guardado = false;
                }
                


                if ($guardado) {
                    $model->password = hash('sha256', $model->password_nueva);
                    if ($model->save()) {

                    LogPlataforma::registrar(7, 7, $model->id);
                    return [
                        'title' => 'Cambiar Contraseña',
                        'content' => '<span class="text-success">Contraseña cambiada correctamente.</span>',
                        'footer' => Html::a('Aceptar', ['/site/logout'], ['role' => "menuitem",'class' => 'btn btn-default pull-left']),
                    ];
                    }
                    
                }
            }

            return [
                'title' => "Cambiar Contraseña ",
                'content' => $error.$this->renderAjax('update_password', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])

            ];
        }
    }
}
