<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_atpcen_encuesta;
use app\models\Mds_atpcen_encuestaSearch;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Sds_ris_persona;
use app\models\Sds_com_persona;
use app\models\Sds_ris_risneu;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

date_default_timezone_set('America/Argentina/Buenos_Aires');
/**
 * Mds_atpcen_encuestaController implements the CRUD actions for Mds_atpcen_encuesta model.
 */
class Mds_atpcen_encuestaController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout','Get_id_risneu','Validar_dni'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout','Get_id_risneu','Validar_dni'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ATPCEN_ENCUESTA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_atpcen_encuesta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_atpcen_encuestaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        
        $usuarioAuth = Yii::$app->user->identity;
        $permissionsImprimirRisneu = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RIS_RISNEU_IMPRIMIR, $usuarioAuth->idusuario);
        $stringButtonsIndex = '{view} {update} {delete}';
        if (!empty($permissionsImprimirRisneu)) {
            $stringButtonsIndex .= " {imprimirRisneu}";
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'stringButtonsIndex' => $stringButtonsIndex,
        ]);
    }


    /**
     * Displays a single Mds_atpcen_encuesta model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "ATPCen:: Ver encuesta #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }
    public function actionGet_id_risneu($dni)
    {
        $risneu = Sds_ris_persona::findBySql("
        select r.*, p.* 
        from sds_ris_persona r 
        inner join sds_com_persona p on r.idpersona  = p.idpersona 
        inner join sds_ris_risneu risneu on risneu.idrisneu = r.idrisneu
        where p.documento = $dni and r.activo = 1
        order by risneu.updated_at DESC, risneu.idrisneu DESC;")
        ->all();
        return $risneu[0]->idrisneu;
    }
    public function actionValidar_dni($dni)
    {
        //Busco la persona en risneu, si existe traigo los datos para editar
        if ($dni != '') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = Sds_ris_persona::findBySql("SELECT risper.*
                                                FROM sds_ris_persona risper
                                                JOIN sds_com_persona persona ON persona.idpersona=risper.idpersona
                                                JOIN sds_ris_risneu risneu ON risneu.idrisneu=risper.idrisneu
                                                WHERE documento=$dni and risper.activo = 1
                                                ORDER BY risneu.updated_at DESC, risneu.idrisneu DESC")->one();
            $model_persona = null;
            //aca queria verificar si era create o update. Pero vi que siempre es create porque en el editar el botón de buscar dni esta deshabilitado.
            //$createUpdate = $editar ? "update&id=".$idllamada : "create&id=".$idllamada;
            if ($model == null) {
                $model = Sds_ris_risneu::findBySql("SELECT risneu.idrisneu
                FROM sds_ris_risneu risneu
                WHERE dni=$dni and activo = 1")->one();
                if ($model) {
                    return $this->redirect([
                        'sds_ris_risneu/update',
                        'finalizar' => false,
                        'id' => $model->idrisneu,
                        'dni' => $dni,
                        'origen' => 'index.php?r=mds_atpcen_encuesta/create'
                    ]);
                } else {
                    return $this->redirect([
                        'sds_ris_risneu/create',
                        'finalizar' => false,
                        'dni' => $dni,
                        'origen' => 'index.php?r=mds_atpcen_encuesta/create'
                    ]);
                }
            } else { // las persona tiene risneu
                $model_persona = Sds_com_persona::findOne($model->idpersona);
            }
            $result = array();
            array_push($result, $model->getAttributes());
            array_push($result, $model_persona->getAttributes());
            //$model->id_risneu=$model->idrisneu;
            return json_encode($result);
        }
        return null;
    }
    /**
     * Creates a new Mds_atpcen_encuesta model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_atpcen_encuesta();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "ATPCen:: Nueva Encuesta",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {

                $fechamodificacion = strftime("%Y-%m-%d", time());
                $model->fecha_hora_alta = $fechamodificacion;

                $model->fecha_hora_entrevista = ArmarDateParaMySql($model->fecha_hora_entrevista);
                $model->fecha_nac_tutor = ArmarDateParaMySql($model->fecha_nac_tutor);
                $model->fecha_diagnostico = ArmarDateParaMySql($model->fecha_diagnostico);


                if ($model->save()) {
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Create new Mds_atpcen_encuesta",
                        'content' => '<span class="text-success">Create Mds_atpcen_encuesta success</span>',
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                } else {
                    return [
                        'title' => "Create new Mds_atpcen_encuesta",
                        'content' => $this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                    ];
                }
            } else {
                return [
                    'title' => "Create new Mds_atpcen_encuesta",
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
            if ($model->load($request->post())) {

                $tmpfile = UploadedFile::getInstance($model, 'archivo_biopsia');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/atpcen', $extension);
                    $model->estudio_biopsia = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/atpcen/' . $nuevo_nombre);
                } else {
                };
                $tmpfile = UploadedFile::getInstance($model, 'archivo_foto_dni');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/atpcen', $extension);
                    $model->frente_dni = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/atpcen/' . $nuevo_nombre);
                } else {
                };
                $tmpfile = UploadedFile::getInstance($model, 'archivo_foto_dnidorso');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/atpcen', $extension);
                    $model->dorso_dni = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/atpcen/' . $nuevo_nombre);
                } else {
                };
                $fechaalta = strftime("%Y-%m-%d", time());
                $model->fecha_alta = $fechaalta;

                $horaalta = strftime("%H:%M:%S", time());
                $model->hora_alta = $horaalta;

                $model->fecha_hora_entrevista = ArmarDateParaMySql($model->fecha_hora_entrevista);
                $model->fecha_nac_tutor = ArmarDateParaMySql($model->fecha_nac_tutor);
                $model->fecha_diagnostico = ArmarDateParaMySql($model->fecha_diagnostico);

                $usuario = Yii::$app->user->identity;
                $idusuario = $usuario != null ? $usuario->idusuario : null;
                if (!isset($idusuario) || $idusuario == null) {
                    $model = new \app\models\LoginForm();
                    return Yii::$app->getResponse()->redirect([
                        'site/login',
                        'model' => $model,
                    ]);
                }
                $model->id_persona_carga = $usuario->idusuario;


                $model->save();


                return $this->redirect(['view', 'id' => $model->id_atpcen]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_atpcen_encuesta model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update Mds_atpcen_encuesta #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Mds_atpcen_encuesta #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Mds_atpcen_encuesta #" . $id,
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
            if ($model->load($request->post())) {

                $tmpfile = UploadedFile::getInstance($model, 'archivo_biopsia');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/atpcen', $extension);
                    $model->estudio_biopsia = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/atpcen/' . $nuevo_nombre);
                } else {
                };
                $tmpfile = UploadedFile::getInstance($model, 'archivo_foto_dni');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/atpcen', $extension);
                    $model->frente_dni = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/atpcen/' . $nuevo_nombre);
                } else {
                };
                $tmpfile = UploadedFile::getInstance($model, 'archivo_foto_dnidorso');
                if (isset($tmpfile)) {

                    $extension = $tmpfile->extension;
                    $nuevo_nombre = $model->random_filename(30, '/uploads/atpcen', $extension);
                    $model->dorso_dni = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/atpcen/' . $nuevo_nombre);
                } else {
                };
                $model->fecha_hora_entrevista = ArmarDateParaMySql($model->fecha_hora_entrevista);
                $model->fecha_nac_tutor = ArmarDateParaMySql($model->fecha_nac_tutor);
                $model->fecha_diagnostico = ArmarDateParaMySql($model->fecha_diagnostico);

                /*$user  = Yii::$app->user->identity;
                $model->id_persona_carga = $user->idusuario;*/


                $model->save();
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_atpcen_encuesta model.
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
     * Finds the Mds_atpcen_encuesta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_atpcen_encuesta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_atpcen_encuesta::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
function ArmarDateParaMySql($Fecha)
{
    if ($Fecha == null) {
        return null;
    }
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}
