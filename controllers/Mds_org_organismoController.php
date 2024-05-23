<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_org_organismo;
use app\models\Mds_org_organismoSearch;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_contacto;
use app\models\Mds_org_organismo_vinculacion;
use app\models\Mds_seg_item;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;



/**
 * Mds_org_organismoController implements the CRUD actions for Mds_org_organismo model.
 */
class Mds_org_organismoController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout', 'organigrama', 'reload_organigrama', 'buscar_dispositivos', 'buscar_personas'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout', 'organigrama', 'reload_organigrama', 'buscar_dispositivos', 'buscar_personas'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ORG_ORGANISMOS,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_org_organismo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_org_organismoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_organismo', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOrganigrama()
    {
        return $this->render('organigrama');
    }

    public function actionReload_organigrama($padre = null, $descripcion = "", $usuario = 0)
    {
        if ($padre == null) {
            $padre = Mds_org_organismo::getOrganismoRaiz();
        }
        $organismos = Mds_org_organismo::getOrganismosHijos($padre, $usuario);
        $tiene_hijos = !empty($organismos);
        $icon_text = '{ "icon" : "fa fa-folder text-success" }';
        switch ($padre->nivel) {
            case 1:
                $icon_text = '{"icon" : "fas fa-landmark  text-primary","opened":true}';
                break;
            case 2:
                $icon_text = '{ "icon" : "fas fa-university text-success" }';
                break;
            case 3:
                $icon_text = '{ "icon" : "fas fa-university text-warning" }';
                break;
            case 4:
                $icon_text = '{ "icon" : "fas fa-university text-info"}';
                break;
        };
        $idorg = $padre->idorganismo;
        $contiene_descr = str_contains(strtolower($padre->descripcion), strtolower($descripcion));
        $dispositivos = Mds_org_dispositivo::find()
            ->where(["activo" => 1])->andWhere("idorganismo = " . $idorg)
            ->orderBy(['descripcion' => SORT_ASC])->all();
        if (sizeof($dispositivos) > 0) {
            foreach ($dispositivos as $dispositivo) {
                $contiene_descr = str_contains(strtolower($dispositivo->descripcion), strtolower($descripcion));
                if ($contiene_descr) {
                    break;
                }
            }
        }
        $html_arbol = "<li id='" . $idorg . "'data-jstree='" . $icon_text . "'>" .
            $padre->descripcion;
        $html_hijos = "";
        if ($tiene_hijos) {
            foreach ($organismos as $organismo) {
                $html_hijos_content = $this->actionReload_organigrama($organismo, $contiene_descr ? "" : $descripcion, $usuario);
                if ($html_hijos_content != "") {
                    $html_hijos =  $html_hijos . "<ul>" .
                        $html_hijos_content .
                        "</ul>";
                }
            }
        }
        if (!$contiene_descr && $html_hijos == "") {
            return "";
        }
        $html_arbol =  $html_arbol . $html_hijos . "</li>";

        return $html_arbol;
    }


    /**
     * Displays a single Mds_org_organismo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_organismo', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Organismo #" . $id,
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
     * Creates a new Mds_org_organismo model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_org_organismo();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nuevo Organismo",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $vinculaciones = $model->vinculaciones != null ? $model->vinculaciones : array();
                $vinculaciones_count = count($vinculaciones);
                $guardado = $model->save();
                if ($guardado) {
                    for ($index_vinc = 0; $index_vinc < $vinculaciones_count; $index_vinc++) {
                        $vinculacion = new Mds_org_organismo_vinculacion();
                        $vinculacion->idorganismo = $model->idorganismo;
                        $vinculacion->vinculacion = $vinculaciones[$index_vinc];
                        if (!$vinculacion->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                            $model->addError('vinculaciones', 'No se pudieron guardar las vinculaciones del organismo');
                        }
                    }
                }
                if ($guardado) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_organismo', $model->idorganismo, $model->getAttributes());
                    if (isset($vinculacion)) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_organismo_vinculacion', $vinculacion->idorganismovinculacion, $model->getAttributes());
                    }
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Nuevo Organismo",
                        'content' => '<span class="text-success">Organismo creado exitosamente</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                } else {
                    $transaction->rollBack();
                }
            }
            return [
                'title' => "Nuevo Organismo",
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_organismo', $model->idorganismo, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idorganismo]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_org_organismo model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $vinc_borrar = Mds_org_organismo_vinculacion::find()->where(["idorganismo" => $model->idorganismo])->all();
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Organismo #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $vinculaciones = $model->vinculaciones != null ? $model->vinculaciones : array();
                $vinculaciones_count = count($vinculaciones);
                if ($vinc_borrar != null) {
                    foreach ($vinc_borrar as $vinc) {
                        $vinc->delete();
                    }
                }
                $guardado = $model->save();
                if ($guardado) {
                    for ($index_vinc = 0; $index_vinc < $vinculaciones_count; $index_vinc++) {
                        $vinculacion = new Mds_org_organismo_vinculacion();
                        $vinculacion->idorganismo = $model->idorganismo;
                        $vinculacion->vinculacion = $vinculaciones[$index_vinc];
                        if (!$vinculacion->save()) {
                            $transaction->rollBack();
                            $guardado = false;
                            $model->addError('vinculaciones', 'No se pudieron guardar las vinculaciones del organismo');
                        }
                    }
                }
                if ($guardado) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_org_organismo', $model->idorganismo, $model->getAttributes());
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_organismo_vinculacion', $vinculacion->idorganismovinculacion, $model->getAttributes());
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Organismo #" . $id,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {
                    $transaction->rollBack();
                }
            }
            return [
                'title' => "Actualizar Organismo #" . $id,
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_org_organismo', $model->idorganismo, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idorganismo]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_org_organismo model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_org_organismo', $id, $model->getAttributes());
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
     * Delete multiple existing Mds_org_organismo model.
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
    /*  Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else { */
    /*
            *   Process for non-ajax request
            */
    /* return $this->redirect(['index']);
        }
    } */

    /**
     * Finds the Mds_org_organismo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_org_organismo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_org_organismo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBuscar_dispositivos($idorganismo)
    {
        $result = array();
        $resultP = array();
        $resultD = array();
        $los_dispositivos = Mds_org_dispositivo::getTodoslosDisp($idorganismo);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_organismo/buscar_dispositivos', $idorganismo, array());
        foreach ($los_dispositivos as $un_dispositivo) {
            $iddispositivo = $un_dispositivo["iddispositivo"];
            $descripcion = $un_dispositivo["descripcion"];

            array_push($resultP, [$iddispositivo, $descripcion]);
        };
        $total = count($resultP);

        array_push($resultD, $total);

        array_push($result, $resultP);
        array_push($result, $resultD);
        return json_encode($result);
    }

    public function actionBuscar_personas($iddispositivo)
    {
        $result = array();
        $resultP = array();
        $resultD = array();

        $los_contactos = Mds_org_contacto::getTodoslosCont($iddispositivo);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_organismo/buscar_personas', $iddispositivo, array());
        foreach ($los_contactos as $un_contacto) {
            $idcontacto = $un_contacto["idcontacto"];
            $nombre = $un_contacto["nombre"];
            $apellido = $un_contacto["apellido"];
            $dni = $un_contacto["documento"];
            $legajo = $un_contacto["legajo"];
            $actividad = $un_contacto["descripcion_actividad"];
            if ($legajo == null) {
                $legajo = 's/n';
            }
            $cad = "<b>" . $nombre . " " . $apellido . "</b><br> <b>DNI: </b>" . $dni . "<br><b>LEG: </b>" . $legajo . "<br>" . "<b>ACTIVIDAD: </b>" . $actividad;

            array_push($resultP, [$idcontacto, $cad]);
        };
        $total = count($resultP);
        array_push($resultD, $total);
        array_push($result, $resultP);
        array_push($result, $resultD);
        return json_encode($result);
    }
}
