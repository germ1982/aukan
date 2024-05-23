<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_hor_remanente;
use app\models\Mds_hor_remanenteSearch;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use DateTime;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

class Mds_hor_remanenteController extends Controller
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
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'only' => ['index', 'create', 'update', 'delete', 'bulk-delete', 'view', 'logout', 'importar'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'bulk-delete', 'update', 'view', 'logout', 'importar'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_HOR_REMANENTE,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_hor_remanente models.
     * @return mixed
     */
    public function actionIndex($idcontacto = null)
    {
        $searchModel = new Mds_hor_remanenteSearch(['idcontacto' => $idcontacto]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (is_null($idcontacto)) {
            return $this->redirect(['/mds_org_contacto']);
        } else {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single Mds_hor_remanente model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Mds_hor_remanente #" . $id,
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

    /**
     * Creates a new Mds_hor_remanente model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionImportar()
    {
        $model = new Mds_hor_remanente();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $registros = Yii::$app->request->post('registros');
        if ($registros != null) {
            $cant_guardados = 0;
            $errores = array();
            $warning = array();
            $exceptions = Sds_com_configuracion::findBySql(
                "SELECT * FROM sds_com_configuracion 
                WHERE idconfiguraciontipo=" . Sds_com_configuracion_tipo::RRHH_EXC_REMANENTE
            )->all();
            $transaction = Yii::$app->db->beginTransaction();
            $sql = Yii::$app->db->createCommand('DELETE FROM mds_hor_remanente');
            $resultado = $sql->execute() >= 0;
            if ($resultado) {
                $fechaaltaremanente = Sds_com_configuracion::findOne($model::ID_FECHA_ALTA);
                $fechaaltaremanente->descripcion = date('d/m/Y');
                $fechaaltaremanente->save();
                $registros = json_decode($registros);
                $fila = 2;
                foreach ($registros as $registro) {
                    $contacto = Mds_org_contacto::find()->where('legajo=' . $registro->legajo)->one();
                    if ($contacto == null) {
                        array_push($errores, 'El legajo ' . $registro->legajo . ' no tiene Contacto asignado');
                    } else {
                        for ($i = 0; $i < 3; $i++) {
                            $remanente = new Mds_hor_remanente();
                            $remanente->idcontacto = $contacto->idcontacto;
                            //Verifico que el valor del remanente no sea vacio o negativo
                            if (!isset($registro->anio0_value) || $registro->anio0_value < 0) {
                                $registro->anio0_value = 0;
                            }
                            if (!isset($registro->anio1_value) || $registro->anio1_value < 0) {
                                $registro->anio1_value = 0;
                            }
                            if (!isset($registro->anio2_value) || $registro->anio2_value < 0) {
                                $registro->anio2_value = 0;
                            }

                            if ($i == 0 && ($contacto->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA
                                || $contacto->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_PLANTA_PERMANENTE)) {
                                $remanente->anio = $registro->anio0_header;
                                $remanente->dias = (isset($registro->anio0_value) ? intval($registro->anio0_value) : 0);
                            } elseif ($i == 1 && ($contacto->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA
                                || $contacto->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_PLANTA_PERMANENTE
                                || $contacto->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA_PURA)) {
                                $remanente->anio = $registro->anio1_header;
                                $remanente->dias = (isset($registro->anio1_value) ? intval($registro->anio1_value) : 0);
                            } elseif ($i == 2 && $contacto->tipo_contratacion != Mds_org_contacto::TIPO_CONTRATACION_EVENTUALES) {
                                $remanente->anio = $registro->anio2_header;
                                $remanente->dias = (isset($registro->anio2_value) ? intval($registro->anio2_value) : 0);
                            }
                            if ($remanente->dias < 0) {
                                array_push($errores, 'El legajo ' . $registro->legajo . ' registra datos erroneos en el año ' . $remanente->anio);
                            } else {
                                if (
                                    $contacto->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA
                                    || $contacto->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_PLANTA_PERMANENTE
                                    || $contacto->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA_PURA
                                ) {

                                    if (
                                        $contacto->fecha_ingreso != null
                                        || $contacto->fecha_ingreso_planta != null
                                    ) {
                                        $ingreso = new DateTime($contacto->fecha_ingreso_planta != null ? $contacto->fecha_ingreso_planta : $contacto->fecha_ingreso);
                                        $fecha = new DateTime("now");
                                        $antiguedad = $ingreso->diff($fecha);
                                    }

                                    if (isset($ingreso)) {
                                        $valid = true;
                                        //Se verifica si el año de ingreso a planta está exceptuado de remanentes por días de profilaxis
                                        foreach ($exceptions as $exception) {
                                            //A la fecha de ingreso le resto un año para descartarlo en caso de que esté exeptuado
                                            $anioExcept = strtotime('-1 year', strtotime($ingreso->format('Y-m-d')));
                                            if ($exception->descripcion == $ingreso->format('Y-m-d') && $remanente->anio == date('Y', $anioExcept)) {
                                                $valid = false;
                                            }
                                        }
                                        if ($valid) {
                                            if ($antiguedad->y >= 1 || $antiguedad->m > 5) {
                                                if ($remanente->save()) {
                                                    $cant_guardados++;
                                                } else {
                                                    $errores_registro = $remanente->getErrors();
                                                    if (!in_array($errores_registro, $errores)) {
                                                        array_push($errores, $remanente->getErrors());
                                                    }
                                                }
                                            } else {
                                                $msg = 'El legajo ' . $contacto->legajo . ' no tiene antigüedad mayor a 6 meses (fila ' . $fila . ' omitida)';
                                                if (!in_array($msg, $warning)) {
                                                    array_push($warning, $msg);
                                                }
                                            }
                                        }
                                    } else {
                                        $msg = 'El legajo ' . $contacto->legajo . ' no tiene fecha de ingreso a planta registrada (fila ' . $fila . ' omitida)';
                                        if (!in_array($msg, $warning)) {
                                            array_push($warning, $msg);
                                        }
                                    }
                                    unset($ingreso_planta);
                                }
                            }
                        }
                    }
                    $fila++;
                }
                if ($cant_guardados > 0) {
                    $transaction->commit();
                } else {
                    $transaction->rollBack();
                }
                return array('guardados' => $cant_guardados, 'errores' => $errores, 'bulkDelete' => true, 'warning' => $warning);
            } else {
                array_push($errores, 'Hubo fallas al procesar los datos. Por favor intente nuevamente. En caso de persistir el error 
                    contacte con administración de sistemas. [Error: BulkDelete]');
                return array('guardados' => 0, 'errores' => $errores, 'bulkDelete' => false, 'warning' => $warning);
            }
        } else {
            return [
                'title' => "Importar Remanente",
                'content' => $this->renderAjax('importar', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Importar', ['id' => 'btn_importar', 'class' => 'btn btn-primary'])
            ];
        }
    }


    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_remanente();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Mds_hor_remanente",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Mds_hor_remanente",
                    'content' => '<span class="text-success">Create Mds_hor_remanente success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Create new Mds_hor_remanente",
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
                return $this->redirect(['view', 'id' => $model->idremanente]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_hor_remanente model.
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
                    'title' => "Update Mds_hor_remanente #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Mds_hor_remanente #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Mds_hor_remanente #" . $id,
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
                return $this->redirect(['view', 'id' => $model->idremanente]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_hor_remanente model.
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
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            /* Process for non-ajax request */
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing Mds_hor_remanente model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $allRemanentes = Mds_hor_remanente::find()->where('idremanente>0')->all();
        if (count($allRemanentes) <= 0) {
            return true;
        }
        foreach ($allRemanentes as $remanente) {
            $remanente->delete();
        }
        $allRemanentes = Mds_hor_remanente::find()->where('idremanente>0')->all();
        if (count($allRemanentes) <= 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Finds the Mds_hor_remanente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_hor_remanente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_hor_remanente::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
