<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use app\models\Mds_sys_log;
use Yii;
use app\models\Sds_ent_cierre;
use app\models\Sds_ent_cierreSearch;
use app\models\Sds_ent_entrega;
use app\models\Model_multiple;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_tipo;
use Exception;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * Sds_ent_cierreController implements the CRUD actions for Sds_ent_cierre model.
 */
class Sds_ent_cierreController extends Controller
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
                            Mds_seg_item::MODULO_ENT_PRIMER_INGRESO,
                        ],
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all Sds_ent_cierre models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Sds_ent_cierreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Sds_ent_cierre model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Sds_ent_cierre #" . $id,
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
     * Creates a new Sds_ent_cierre model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCerrar($identrega, $estado)
    {
        ini_set('max_input_vars',30000);
        $request = Yii::$app->request;
        $model_entrega = Sds_ent_entrega::findOne($identrega);
        $model_entrega->estado_cierre = $estado;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model_entrega->tiene_numero = Sds_ent_tipo::findOne($model_entrega->idtipo)->tiene_numero;
        if (
            !$model_entrega->tiene_numero ||
            ($model_entrega->numero_desde != null
                && $model_entrega->numero_hasta != null)
        ) {
            $model_entrega->saldo = Sds_ent_entrega::getSaldo($identrega);
            if ($model_entrega->fecha_cierre == null) {
                $model_entrega->fecha_cierre = date("Y-m-d");
            }
            $models_cierres = Sds_ent_cierre::find()->where(["identrega" => $model_entrega->identrega])->all();
            //1. Si el tipo es sin número, listar todos los motivos y cargarle cantidad a cada uno.
            //Predeterminar 0. (campos: motivo, cantidad)
            if (empty($models_cierres) && $model_entrega->estado_cierre!=2) {
                if (!$model_entrega->tiene_numero) {
                    $motivos = Sds_com_configuracion::find()->where("idconfiguraciontipo=" .
                            Sds_com_configuracion_tipo::TIPO_ENT_MOTIVO_CIERRE)
                            ->orderBy(['descripcion' => SORT_ASC])->all();
                    foreach ($motivos as $motivo) {
                        $cierre = new Sds_ent_cierre();
                        $cierre->identrega = $model_entrega->identrega;
                        $cierre->motivo = $motivo->idconfiguracion;
                        $cierre->cantidad = 0;
                        array_push($models_cierres, $cierre);
                    }
                } else {
                    $numero_desde = $model_entrega->numero_desde;
                    $numero_hasta = $model_entrega->numero_hasta;
                    //Reviso que los numeros correspondientes a entregas finales
                    //no se encuentre en otra entrega ni esten rendidos al parecer.
                    //Ah claro revisa si ese numero fue entregado a otro responsable si el dni es nulo
                    for ($numero = $numero_desde; $numero <= $numero_hasta; $numero++) {
                        $entrega_existente = Sds_ent_entrega::find()->where("((dni is null 
                                                and numero_desde is not null and numero_hasta is not null
                                            and (numero_desde<=$numero and numero_hasta>=$numero))
                                            or (dni is not null and numero=$numero)) and emisor=$identrega")->one();
                        //algo tengo que corregir aca con el tema de los numeros. O sea algunas de las que toma cuando hace el saldo
                        //para el icono no las esta tomando aca.
                        if ($entrega_existente == null) {
                            $cierre = new Sds_ent_cierre();
                            $cierre->numero = 0 + $numero;
                            $cierre->identrega = $model_entrega->identrega;
                            $cierre->cantidad = 1;
                            array_push($models_cierres, $cierre);
                        }
                    }
                }
                if (empty($models_cierres)) {
                    return [
                            'title' => "Rendición Final de Entrega #" . $identrega,
                            'content' => '<span class="text-success">La entrega ya se encuentra cerrada correctamente.</span>',
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                }
            } else {
                if ($model_entrega->load($request->post())) {
                    if ($model_entrega->fecha_cierre != null) {
                        $fecha = date('Y-m-d', strtotime(str_replace('/', '-', $model_entrega->fecha_cierre)));
                        $model_entrega->updateAttributes(['fecha_cierre' => $fecha]);
                        $tmpfile = UploadedFile::getInstance($model_entrega, 'archivo_adjunto_cierre');
                        if (isset($tmpfile)) {
                            $extension = $tmpfile->extension;
                            $nombre =  'rendicion_' . $model_entrega->identrega . '_' . $fecha . '.' . $extension;
                            //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                            $ruta = 'uploads/entregas/actas/';
                            if (!file_exists($ruta)) {
                                mkdir($ruta, 0777, true);
                            }
                            $model_entrega->adjunto_cierre = $ruta . $nombre;
                            $tmpfile->saveAs($model_entrega->adjunto_cierre);
                            $model_entrega->updateAttributes(['adjunto_cierre' => $model_entrega->adjunto_cierre]);
                        }
                        return [
                                'title' => "Rendición Final de Entrega #" . $identrega,
                                'content' => '<span class="text-success">Fecha de cierre y adjuntos actualizados!</span>',
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                            ];
                    } else {
                        $model_entrega->addError('fecha_cierre', 'La fecha de cierre es obligatoria');
                    }
                }
                $url =  Url::to(['/sds_ent_cierre/reporte_cierre', 'identrega' => $model_entrega->identrega,'estado_cierre'=>$model_entrega->estado_cierre]);
                return [
                        'title' => "Datos de Rendición de Entrega #" . $identrega,
                        'content' => $this->renderAjax('view', [
                            'model_entrega' => $model_entrega,
                            'models_cierres' => $models_cierres
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::button('Editar', ['class' => 'btn btn-primary', 'type' => "submit"]) .
                            Html::a('<span class= "fas fa-print"></span> Ver Reporte', $url, [
                                'class' => 'btn btn-primary',
                                'title' => "Imprimir",
                                'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                                'data-toggle' => 'tooltip',
                            ])
                    ];
            }
            //2. Si el tipo es con número, listar todos los números incluidos en el desde/hasta
            //de la entrega que no tienen entrega final ni entrega intermeda, y permitir asignar el motivo.
            //(campos: label numero, motivo, cantidad en 1 no se muestra).
            if ($model_entrega->load($request->post())) {
                if ($model_entrega->fecha_cierre != null) {
                    $model_entrega->fecha_cierre = date('Y-m-d', strtotime(str_replace('/', '-', $model_entrega->fecha_cierre)));
                    $models_cierres = Model_multiple::createMultiple(Sds_ent_cierre::classname());
                    Model_multiple::loadMultiple($models_cierres, Yii::$app->request->post());
                    //validación de modelos
                    //$valid = $model_entrega->validate();
                    //si se quiere implementar la edición, al guardar hay que borrar primero los items anteriores, buscando
                    //por el id, para que luego se agreguen nuevos con los datos del form ingresados. O sea, no edita, asi que
                    //hay que borrar los viejos y meter nuevos.
                    //$deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsAddress, 'id', 'id')));
                    $valido = Model_multiple::validateMultiple($models_cierres);
                    $transaction = \Yii::$app->db->beginTransaction();
                    $errores = "";
                    $cant_asignada = 0;
                    if ($valido) {
                        $tmpfile = UploadedFile::getInstance($model_entrega, 'archivo_adjunto_cierre');
                        if (isset($tmpfile)) {
                            $extension = $tmpfile->extension;
                            $nombre =  'rendicion_' . $model_entrega->identrega . '_' . $model_entrega->fecha_cierre . '.' . $extension;
                            //uploads/contactos/<idempleado>_<apellido>_<nombre>/<tipoDocumento><nombre_documento><Y-m-d>
                            $ruta = 'uploads/entregas/actas/';
                            if (!file_exists($ruta)) {
                                mkdir($ruta, 0777, true);
                            }
                            $model_entrega->adjunto_cierre = $ruta . $nombre;
                            $tmpfile->saveAs($model_entrega->adjunto_cierre);
                            $model_entrega->updateAttributes(['adjunto_cierre' => $model_entrega->adjunto_cierre]);
                        }
                        if ($flag = $model_entrega->save(false)) {
                            foreach ($models_cierres as $model_cierre) {
                                $model_cierre->identrega = $identrega;
                                $cant_asignada += $model_cierre->cantidad;
                                if (!($flag = $model_cierre->save(false))) {
                                    $errores = $errores + print_r($model_cierre->getErrors(), true) + "<br>";
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($cant_asignada != $model_entrega->saldo && !$model_entrega->tiene_numero) {
                            $model_entrega->addError("fecha_cierre", "ERROR! La cantidad asignada debe coincidir con la cantidad pendiente de rendición!");
                            $flag = false;
                        }
                        if ($flag) {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_ent_entrega/cerrar', $identrega, array());
                            $transaction->commit();
                            $url =  Url::to(['/sds_ent_cierre/reporte_cierre', 'identrega' => $model_entrega->identrega]);                            
                            return [
                                    'title' => "Rendición Final de Entrega #" . $identrega,
                                    'content' => '<span class="text-success">Entrega cerrada exitosamente</span>',
                                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                        Html::a('<span class= "fas fa-print"></span> Ver Reporte', $url, [
                                            'class' => 'btn btn-primary',
                                            'title' => "Imprimir",
                                            'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                                            'data-toggle' => 'tooltip',
                                        ])
                                ];
                        }
                    }
                    $transaction->rollBack();
                    if ($errores != "") {
                        $model_entrega->addError("fecha_cierre", $errores);
                    }
                } else {
                    $model_entrega->addError('fecha_cierre', 'La fecha de cierre es obligatoria');
                }
            }
            return [
                'title' => "Rendición Final de Entregas #" . $identrega,
                'content' => $this->renderAjax('cerrar', [
                    'model_entrega' => $model_entrega,
                    'models_cierres' => $models_cierres
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                $model_entrega->estado_cierre!=2 ? Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]):""

            ];
        } else {
            return [
                'title' => "Entrega #" . $identrega,
                'content' => '<h5 class="text-danger" style="text-align:center"><b>Debe ingresar los números desde y hasta para poder realizar la rendición</b></h5>',
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        }
    }

    /**
     * Updates an existing Sds_ent_cierre model.
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
                    'title' => "Update Sds_ent_cierre #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } elseif ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Sds_ent_cierre #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Sds_ent_cierre #" . $id,
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
                return $this->redirect(['view', 'id' => $model->idcierre]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionReporte_cierre($identrega, $estado_cierre=1)
    {
        if (!Sds_ent_entrega::getCerradas($identrega) && $estado_cierre!=2) {
            return $this->redirect($_SERVER['HTTP_REFERER']);
        } else {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_ent_entrega/reporte_cierre', $identrega, array());
            $content = $this->renderPartial('reporte_cierre', ['identrega' => $identrega,'estado_cierre'=>$estado_cierre]);
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'content' => $content,
                'defaultFontSize' => 12,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                // any css to be embedded if required
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'methods' => [
                    'SetTitle' => 'RENDICIÓN DE ENTREGA',
                    'SetHeader' => null,
                    'SetFooter' => null,
                ]
            ]);

            return $pdf->render();
        }
    }

    /**
     * Delete an existing Sds_ent_cierre model.
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
     * Delete multiple existing Sds_ent_cierre model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $model->delete();
        }

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
     * Finds the Sds_ent_cierre model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_ent_cierre the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_ent_cierre::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
