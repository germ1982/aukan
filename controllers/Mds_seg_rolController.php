<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Mds_seg_rol;
use app\models\Mds_seg_rolSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use kartik\mpdf\Pdf;

/**
 * Mds_seg_rolController implements the CRUD actions for Mds_seg_rol model.
 */
class Mds_seg_rolController extends Controller
{
    private $itemsSeguridad = array();

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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'reporte_usuarios', 'usuarios', 'reactivate', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'reporte_usuarios', 'usuarios', 'reactivate', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_SEG_SEGURIDAD,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_seg_rol models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->insertarItemsSeguridad();
        $searchModel = new Mds_seg_rolSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_seg_rol', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    private function insertarItemsSeguridad()
    {
        $this->generarListaItems();
        $items = Mds_seg_item::find()->all();
        foreach ($this->itemsSeguridad as $it) {
            $encontrado = false;
            foreach ($items as $it_db) {
                if ($it_db->iditem == $it->iditem) {
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                $it->save();
            }
        }
    }

    //Carga la lista de los items de seguridad existentes
    private function generarListaItems()
    {
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_REG_REGISTROS, "Sistema de Registros Técnicos");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_REG_TIPOS, "Configuración de Tipos de Registros Técnicos");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_NOT_NOTAS, "Gestión de Notas");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ORG_ORGANISMOS, "Configuración de Organismos");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ORG_DISPOSITIVOS, "Configuración de Dispositivos");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_SEG_SEGURIDAD, "Configuración de Usuarios y Permisos");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_GIS_MAPA, "Visualización de Mapa");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_GIS_CAPAS, "Configuración de capas de GIS");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_CEL_CORPORATIVOS, "Administración de lineas corporativas");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_RIS_RISNEU, "Administración de RisNeu");
        $this->agregarItemSeguridad(Mds_seg_item::ACCESO_PORTAL_UNIF, "Acceso a Portal Unificado");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_RIS_ENCUESTADOR, "Alta de Encuestador");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_VIO_VIOLENCIA, "Violencia Interno");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_POR_FAMILIA, "Subsidios Familia");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_POR_DESEMPLEO, "Subsidios Desempleo");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_POR_SST, "Subsidios Social Transitorio");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ENT_ENTREGAS, "Entregas - Indicadores");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_TAR_TARJETA, "Tarjetas Subse");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ANS_ALIMENTAR, "Tarjeta Alimentar ANSES");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ENT_SOLICITUD, "Aprobar/Rechazar Solicitudes de Entregas");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_GUARDIAS_INTEGRADAS_LLAMADA, "Ingreso a módulo de llamadas 0800");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ANS_NEGATIVA, "Certificaciones Negativas ANSES");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_VIO_EXTERNO, "Violencia Externo");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_REG_TECNICO, "Técnico Activo");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ORG_CONTACTOS, "Configuración de Contactos");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ATP_SOLICITUD, "Tarjeta ATPCen");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_COR_INTERVENCION, "Intervenciones Coordinación");
        $this->agregarItemSeguridad(Mds_seg_item::WS_JUBILACIONES_Y_PENSIONES, "WS Jubilaciones y Pensiones");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_CAP_CAPACITACION, "Capacitaciones");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_REG_AUTOSOLICITUD, "Solicitudes de Soportes Técnicos");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_TELEFONIA_VISTAS, "Telefonía Vistas Consulta");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_RRHH, "Recursos Humanos");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ENT_PRIMER_INGRESO, "Entregas - Primeros Ingresos");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ENT_VER_TODAS, "Entregas - Ver Todas");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ENT_CAMBIO_RESPONSABLE, "Entregas - Cambiar Responsable");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ORG_INFORMES, "Estructura - Informe de Dispositivos");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ENT_ENTREGAS, "Gestión de Entregas");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_CAP_GLOBAL, "Capacitaciones Global");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_ENT_ARBOL, "Entregas - Árbol");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_GUARDIAS_INTEGRADAS_LLAMADA_FAMILIA, "Llamadas 0800 - Familia");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_GUARDIAS_INTEGRADAS_LLAMADA_ADULTOS, "Llamadas 0800 - Adultos Mayores");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_TES_ADJUNTOS, "Tesorería - Adjuntos");
        $this->agregarItemSeguridad(Mds_seg_item::MODULO_SST_INDICADORES, "Subsidios - Indicadores");
    }

    private function agregarItemSeguridad($codigoItemSeguridad, $descripcionItemSeguridad)
    {
        $item = new Mds_seg_item();
        $item->iditem = $codigoItemSeguridad;
        $item->descripcion = $descripcionItemSeguridad;
        array_push($this->itemsSeguridad, $item);
    }

    /**
     * Displays a single Mds_seg_rol model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_seg_rol', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Rol <b>#" . $id . ' - ' . $model->descripcion . '</b>',
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-right', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Mds_seg_rol model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_seg_rol();
        $model->created_at = date('Y-m-d H:i:s');
        $usuarioAuth = Yii::$app->user->identity;
        $model->idusuario_carga = $usuarioAuth->idusuario;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nuevo Rol",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_rol', $model->idrol, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Nuevo Rol",
                    'content' => '<span class="text-success">Creado Exitosamente!</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Agregar Otro', ['create'], ['class' => 'btn btn-success', 'role' => 'modal-remote']) .
                        Html::a('Asignar Permisos', ['mds_seg_permiso/index', 'idrol' => $model->idrol], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "Nuevo Rol",
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_seg_rol', $model->idrol, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idrol]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_seg_rol model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->updated_at = date('Y-m-d H:i:s');
        $model->idusuario_modifica = Yii::$app->user->id;

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Rol <b>#" . $id . ' - ' . $model->descripcion . '</b>',
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_seg_rol', $model->idrol, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Rol #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Actualizar Rol #" . $id,
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_seg_rol', $model->idrol, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idrol]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_seg_rol model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->idusuario_borra = Yii::$app->user->id;

        if ($model->save()) {
            //Yii::$app->session->setFlash('success', "Se eliminó correctamente la solicitud.");
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_seg_rol', $id, $model->getAttributes());
        }
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Delete multiple existing Mds_seg_rol model.
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

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
    /*  Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else { */
    /*
            *   Process for non-ajax request
            */
    /*      return $this->redirect(['index']);
        }
    } */

    /**
     * Finds the Mds_seg_rol model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_seg_rol the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_seg_rol::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUsuarios()
    {
        $idrol = Yii::$app->request->queryParams['idrol'];
        $model_rol = Mds_seg_rol::findOne($idrol);
        $usuarios = Mds_seg_rol::getUsuariosActivosByRol($idrol);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_seg_rol', null, array());
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'title' => "Listado de usuarios con el rol <b>#" . $idrol . " - " . $model_rol->descripcion . '</b>',
            'content' => $this->renderAjax('modal_usuarios', [
                'model' => $usuarios,
                'idrol' => $idrol
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default', 'data-dismiss' => "modal"])
        ];
    }

    public function actionReporte_usuarios($idrol)
    {
        $usuarios = Mds_seg_rol::getUsuariosActivosByRol($idrol);
        $model_rol = Mds_seg_rol::findOne($idrol);

        $usuarioAuth = Yii::$app->user->identity;
        $dateToday = date('d/m/Y H:i:s');
        $content = $this->renderPartial('reporte_usuarios', [
            'model' => $usuarios,
            'rol' => $model_rol,
        ]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}div.saltopagina{page-break-after:always}',
            'methods' => [
                'SetTitle' => 'Listado usuarios rol ' . $model_rol->descripcion,
                'SetHeader' => null,
                'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
            ]
        ]);

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_seg_usuario_rol', $idrol, array());
        return $pdf->render();
    }

    public function actionReactivate($idrol)
    {
        $model = $this->findModel($idrol);

        if ($model) {
            $model->deleted_at = null;
            $model->idusuario_borra = null;
            if ($model->update()) {
                Yii::$app->session->setFlash('success', "Se reactivó correctamente el rol.");
            } else {
                Yii::$app->session->setFlash('error', "Error al reactivar el rol.");
            }
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_legales_oficio', $model->idrol, $model->getAttributes());
        } else {
            Yii::$app->session->setFlash('error', "El rol no existe.");
        }

        return $this->redirect(['index']);
    }
}
