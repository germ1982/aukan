<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_hor_licencia;
use app\models\Mds_hor_licenciaSearch;
use app\models\Mds_hor_motivo_inasistencia;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;

/**
 * Mds_hor_licenciaController implements the CRUD actions for Mds_hor_licencia model.
 */
class Mds_hor_licenciaController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'bulk-delete', 'view', 'logout', 'create_lsgh', 'procesar_licencias'],
                'rules' => [
                    [
                        'actions' => ['index', 'delete', 'bulk-delete', 'update', 'view', 'logout', 'create', 'procesar_licencias'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_HOR_LICENCIA,
                        ],
                    ],
                    [
                        'actions' => ['create_lsgh'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::HOR_LIC_SGH,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_hor_licencia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_hor_licenciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_licencia', null, array());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mds_hor_licencia model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_licencia', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Mds_hor_licencia #" . $id,
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

    public function actionCreate_lsgh()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_licencia();
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post())) {
                $model->desde = date('Y-m-d', strtotime(str_replace('/', '-', $model->desde)));
                $model->hasta = date('Y-m-d', strtotime(str_replace('/', '-', $model->hasta)));
                $model->idusuario = Yii::$app->user->identity->idusuario;
                $model->idmotivoinasistencia = Mds_hor_motivo_inasistencia::SIN_GOCE_HABERES;
                if ($model->save()) {
                    $model = new Mds_hor_licencia();
                    Yii::$app->session->setFlash('success', 'La licencia fue generada de manera correcta.');
                } else {
                    Yii::$app->session->setFlash('danger', 'La licencia <b>NO</b> fue generada.
                    Por favor intente nuevamente.');
                }
            }
            return [
                'title' => "Nueva licencia sin goce de haberes",
                'content' => $this->renderAjax('_form', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-success', 'type' => "submit"])
            ];
        }
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_hor_licencia();

        if ($request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'title' => "Importar Excel con licencias",
                'content' => $this->renderAjax('importar_licencias_yii', [
                    'model' => $model,
                ]),


            ];
        }
    }

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
                    'title' => "Update Mds_hor_licencia #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_hor_licencia', $model->idlicencia, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Mds_hor_licencia #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Mds_hor_licencia #" . $id,
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_hor_licencia', $model->idlicencia, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idlicencia]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_hor_licencia', $id, $model->getAttributes());
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

    protected function findModel($id)
    {
        if (($model = Mds_hor_licencia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /** ACA ESTA LA PAPA! Ezecomment */
    public function actionProcesar_licencias()
    {
        $datos = Yii::$app->request->post('datos');
        /* var licencia_elto = {
            'legajo': legajo,
            'apenom': apenom,
            't_lic': t_lic,
            'descripcion': descripcion,
            'desde': desde,
            'hasta': hasta,
            'cant': cant,
            'observ': observ,
            'liq': liq,
        }; */
        $datos_licencias = json_decode($datos);
        $anuncio_log = "";
        $guardados = 0;
        $no_guardados_x_fecha = 0;
        $no_guardados_x_save = 0;
        $inexistentes = 0;
        $linea = 1;
        /* return json_encode($datos_licencias); */
        foreach ($datos_licencias as $licencia) {
            $linea++;
            if (isset($licencia->legajo)) {
                $legajo = $licencia->legajo;
                $idcontacto = get_id_contacto($legajo);
                $Apellido_y_Nombre = trim($licencia->apenom);
                if ($idcontacto > 0) {
                    if (isset($licencia->desde) && $licencia->desde && isset($licencia->hasta) && $licencia->hasta) {
                        $desde = substr($licencia->desde, 0, 10);
                        $hasta = substr($licencia->hasta, 0, 10);
                        $c_t_lic = trim($licencia->t_lic);
                        /* 2252p Licencia anual paga // 2266 // 2269 Reducción de Horario// 2263b // 2261c .- */
                        if ($c_t_lic != '2252p' && $c_t_lic != '2266' && $c_t_lic != '2269' && $c_t_lic != '2263b' && $c_t_lic != '2261c') {
                            $c_t_lic_descripcion = trim($licencia->descripcion);
                            $idmotivoinasistencia = get_id_motivo_inasistencia($c_t_lic, $c_t_lic_descripcion);
                            $detalle = trim($licencia->descripcion) . " - " . trim($licencia->observ) . " - " . trim($licencia->observ);
                            $cantidad_dias = $licencia->cant;
                            $idusuario = Yii::$app->user->identity->idusuario;
                            $fecha_repetida = validar_fecha($desde, $hasta, $idcontacto);
                            if ($fecha_repetida == 0) {
                                //$anuncio_log.= "INSERT INTO mds_hor_licencia(desde,hasta,detalle,idcontacto,cantidad_dias,idusuario,idmotivoinasistencia) VALUES ('$desde', '$hasta', '$detalle', $idcontacto, $cantidad_dias, $idusuario, $idmotivoinasistencia);\n";
                                //$msql_insert = "INSERT INTO mds_hor_licencia(desde,hasta,detalle,idcontacto,cantidad_dias,idusuario,idmotivoinasistencia) VALUES ('$desde', '$hasta', '$detalle', $idcontacto, $cantidad_dias, $idusuario, $idmotivoinasistencia);";
                                $aux = guardar_licencia($desde, $hasta, $detalle, $idcontacto, $cantidad_dias, $idusuario, $idmotivoinasistencia);
                                $aux_log = "$anuncio_log <p style='color:red'> Linea: $linea No se ha guardado la licencia de: $Apellido_y_Nombre Legajo: $legajo Razón: falla en insert</p>";
                                $no_guardados_x_save++;
                                if ($aux > 0) {
                                    $guardados++;
                                    $no_guardados_x_save--;
                                    //$aux_log = "$anuncio_log <p style='color:#017E25'> Linea: $linea Se ha guardado la licencia de: $Apellido_y_Nombre legajo: $legajo Guardado con Id: $aux\n $msql_insert</p>";
                                    $aux_log = $anuncio_log;
                                }
                                $anuncio_log = $aux_log;
                            } else {
                                $no_guardados_x_fecha++;
                                $anuncio_log = "$anuncio_log <p style='color:red'> Linea: $linea No se ha guardado la licencia de: $Apellido_y_Nombre Legajo: $legajo Razón: ya existe la licencia</p>";
                            }
                        }
                    } else {
                        $aux_log = "$anuncio_log <p style='color:red'> Linea: $linea No se ha guardado la licencia de: $Apellido_y_Nombre Legajo: $legajo Razón: falla Periodos desde y hasta</p>";
                    }
                    //$anuncio_log = "$anuncio_log Apto para guardado\n idcontacto: $idcontacto \n desde: $desde \n hasta: $hasta \n c_t_lic: $c_t_lic \n c_t_lic_descripcion: $c_t_lic_descripcion \n idmotivoinasistencia: $idmotivoinasistencia \n detalle: $detalle \n cantidad_dias: $cantidad_dias \n idusuario: $idusuario";
                } else {
                    $inexistentes++;
                    $anuncio_log = "$anuncio_log <p style='color:red'> Linea: $linea No se ha guardado la licencia de: $Apellido_y_Nombre Legajo: $legajo Razón: No existe en mds_org_contacto</p>";
                }
                //$anuncio_log = "$anuncio_log </div>";
            }
        }

        $estadisticas = " Lineas recorridas: $linea \n Encabezados: 1\n Licencias no Guardadas por contacto inexistente: $inexistentes \n Licencias guardadas: $guardados \n Licencias no guardadas por períodos ya existentes: $no_guardados_x_fecha \n Licencias no guardadas por error en save: $no_guardados_x_save \n";
        //return $datos;
        return $estadisticas . $anuncio_log;
    }
}

function guardar_licencia($desde, $hasta, $detalle, $idcontacto, $cantidad_dias, $idusuario, $idmotivoinasistencia)
{
    $aux = 0;
    $model = new Mds_hor_licencia();
    $model->desde = $desde;
    $model->hasta = $hasta;
    $model->detalle = $detalle;
    $model->idcontacto = $idcontacto;
    $model->cantidad_dias = $cantidad_dias;
    $model->idusuario = $idusuario;
    $model->idmotivoinasistencia = $idmotivoinasistencia;
    if ($model->validate()) {
        $model->save();
        $aux = $model->idlicencia;
    }
    return $aux;
    //return $model->getErrors();
}

function get_id_motivo_inasistencia($idrh, $descripcion)
{
    $consulta = "SELECT * FROM mds_hor_motivo_inasistencia WHERE idrh = '$idrh'";
    $motivo_inasistencia = Mds_hor_motivo_inasistencia::findBySql($consulta)->one();
    if ($motivo_inasistencia == null) {
        $motivo_inasistencia = new Mds_hor_motivo_inasistencia();
        $motivo_inasistencia->descripcion = $descripcion;
        $motivo_inasistencia->idrh = $idrh;
        $motivo_inasistencia->activo = 1;
        $motivo_inasistencia->save();
    }
    return $motivo_inasistencia->idmotivoinasistencia;
}

function get_id_contacto($legajo)
{
    $contacto = Mds_org_contacto::findBySql("SELECT * FROM mds_org_contacto WHERE legajo = $legajo")->one();
    $idcontacto = 0;

    if ($contacto != null) {
        $idcontacto = $contacto->idcontacto;
    }
    return $idcontacto;
}

function validar_fecha($desde, $hasta, $idcontacto)
{

    $licencia = Mds_hor_licencia::findBySql("SELECT * FROM mds_hor_licencia WHERE idcontacto = $idcontacto and desde = '$desde' and hasta = '$hasta'")->one();
    $ban = 0;
    if ($licencia != null) {
        $ban = 1;
    }
    return $ban;
}
