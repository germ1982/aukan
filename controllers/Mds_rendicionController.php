<?php

namespace app\controllers;

use Yii;
use app\models\Mds_legales_archivo;
use app\models\Mds_rendicion;
use app\models\Mds_rendicion_comprobante;
use app\models\Mds_rendicionSearch;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario;
use app\models\Mds_seg_usuario_rol;
use app\models\Mds_sys_log;

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_persona;

use app\models\Sds_gis_capa;
use app\models\Sds_gis_capa_item;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

use kartik\mpdf\Pdf;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use DateTime;

use yii\helpers\Url;



/**
 * Mds_rendicionController implements the CRUD actions for Mds_rendicion model.
 */
class Mds_rendicionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'reactivate' => ['POST'],
                    'storecomprobante' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'view',
                    'create',
                    'update',
                    'delete',
                    'reactivate',
                    'reporte',
                    'storecomprobante',
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'reactivate',
                            'reporte',
                            'storecomprobante',
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    private function getPermissionsCrud()
    {

        $response = null;
        $permissionCreate = false;
        $permissionRead = false;
        $permissionUpdate = false;
        $permissionDelete = false;
        $permissionReactivate = false;
        $permissionPrint = false;

        $idusuario = Yii::$app->user->identity->idusuario;
        $rolesRendicion = implode(',', Mds_rendicion::ID_ROLES_RENDICION);
        $iditem = Mds_seg_item::MODULO_RENDICION;

        $permisos = Mds_seg_permiso::findBySql(
            "SELECT *
                FROM mds_seg_permiso
                WHERE idrol IN (SELECT idrol FROM mds_seg_usuario_rol WHERE idusuario=$idusuario)
                AND idrol IN ({$rolesRendicion})
                AND iditem = {$iditem}"
        )->all();


        $countPermisos = count($permisos);
        $i = 0;

        while ((!$permissionCreate || !$permissionRead || !$permissionUpdate || !$permissionDelete) && $i < $countPermisos) {
            $permiso = $permisos[$i];
            if (!$permissionCreate) {
                $permissionCreate = $permiso->alta;
            }
            if (!$permissionRead) {
                $permissionRead = $permiso->ver;
                $permissionPrint = $permiso->ver;
            }
            if (!$permissionUpdate) {
                $permissionUpdate = $permiso->modifica;
            }
            if (!$permissionDelete) {
                $permissionDelete = $permiso->baja;
            }
            $i++;
        }

        if (Mds_seg_usuario_rol::hasRol(Mds_rendicion::ID_ROL_ADMIN_GENERAL)) {
            $permissionReactivate = true;
        }

        if ($countPermisos) {
            $response = [
                'permissionCreate' => $permissionCreate,
                'permissionRead' => $permissionRead,
                'permissionUpdate' => $permissionUpdate,
                'permissionDelete' => $permissionDelete,
                'permissionReactivate' => $permissionReactivate,
                'permissionPrint' => $permissionPrint,
            ];
        }
        return $response;
    }

    /**
     * Lists all Mds_rendicion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $permissionCrud = self::getPermissionsCrud();

        if ($permissionCrud) {

            $searchModel = new Mds_rendicionSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_rendicion::ID_ROL_ADMIN_GENERAL);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'permission' => $permissionCrud,
                'hasRolAdminGeneral' => $hasRolAdminGeneral,
                'listTipos' => $this->getListTipos(),
                'filtroUsuarioCarga' => $this->getFilterUsuarioCarga(),
                'filtroLugar' => $this->getFilterLugar(),
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Displays a single Mds_rendicion model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionRead = $permissionCrud['permissionRead'];

        if ($permissionRead) {
            $model = $this->findModel($id);
            $comprobantes = count($model->comprobantes) > 0 ?  true : null;
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rendicion', $id, array());
            $this->idcapaByidlugar($model);

            // $url =  Url::to(['/mds_rendicion/comprobante', 'id' => $model->idrendicion]);
            // $txt = $model->comprobante ? 'Actualizar Comprobante' : 'Presentar Comprobante';

            return $this->render('view', [
                'model' => $model,
                'adjuntos' => $model->getOtrosAdjuntos(),
                'comprobantes' => $comprobantes,
                'TIPO_COMBUSTIBLE' => Mds_rendicion::TIPO_COMBUSTIBLE,
                // 'url' => $url,
                // 'txt' => $txt,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionCreate()
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionCreate = $permissionCrud['permissionCreate'];

        if ($permissionCreate) {
            $model = new Mds_rendicion();
            $request = Yii::$app->request;
            $idusuario =  Yii::$app->user->identity->idusuario;
            $username =  Yii::$app->user->identity->user;

            if ($model->load($request->post())) {
                $model->idusuario_carga = $idusuario;
                $model->created_at = date('Y-m-d H:i:s');

                $this->armarDateParaMySql($model);

                if ($model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    if ($model->save()) {

                        // Upload archivo adjunto
                        if (Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos']) {
                            $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
                            $this->storeAdjuntoOtros($adjuntos, $model);
                        }

                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rendicion', $model->idrendicion, $model->getAttributes());
                        Yii::$app->session->setFlash('success', " Se generó correctamente la rendición #" . $model->idrendicion);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', " Error al guardar la rendición.");
                    }
                } else {
                    Yii::$app->session->setFlash('error', " Error al validar los datos de la rendición.");
                }
                return $this->redirect(['mds_rendicion/index']);
            } else {

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $token = isset($_SESSION["tokenNest"]) ? $_SESSION["tokenNest"] : '';

                return $this->render('create', [
                    'model' => $model,
                    'username' => $username,
                    'listUsuario' =>  $this->getListUsuarios(),
                    'listTipoRendicion' => $this->getListTipos(),
                    'listCapa' => $this->getListCapas(),
                    'listLugar' => [],
                    'TIPO_COMBUSTIBLE' => Mds_rendicion::TIPO_COMBUSTIBLE,
                    'TIPO_AUH' => Mds_rendicion::TIPO_AUH,
                    'TIPO_ALIMENTAR' => Mds_rendicion::TIPO_ALIMENTAR,
                    // Modal New Persona
                    'model_persona' => new Sds_com_persona(),
                    'tiposDocumentos' => $this->getListTiposDocumentos(),
                    'generos' => $this->getListGeneros(),
                    'nacionalidades' => $this->getListNacionalidades(),
                    'token' => $token
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_rendicion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionUpdate = $permissionCrud['permissionUpdate'];

        if ($permissionUpdate) {
            $idusuario =  Yii::$app->user->identity->idusuario;
            $username =  Yii::$app->user->identity->user;
            $request = Yii::$app->request;
            $model = $this->findModel($id);
            $this->idcapaByidlugar($model);

            if ($model->load($request->post())) {
                $model->idusuario_modifica = $idusuario;
                $model->updated_at = date('Y-m-d h:i:s');
                $this->armarDateParaMySql($model);

                if ($model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    if ($model->update()) {
                        if (isset($request->post()['Mds_legales_oficio']['adjuntos_eliminados']) && $request->post()['Mds_legales_oficio']['adjuntos_eliminados']) {
                            $adjuntosEliminados = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['adjuntos_eliminados'], true);
                            $this->eliminarAdjuntoOtros($adjuntosEliminados);
                        }

                        if ($request->post()['Mds_legales_oficio']['otros_adjuntos']) {
                            $adjuntos = json_decode(Yii::$app->request->post()['Mds_legales_oficio']['otros_adjuntos'], true);
                            $this->storeAdjuntoOtros($adjuntos, $model);
                        }

                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rendicion', $model->idrendicion, $model->getAttributes());
                        Yii::$app->session->setFlash('success', " Se actualizó correctamente la rendición #" . $model->idrendicion);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', " Error al generar la rendición.");
                    }
                } else {
                    Yii::$app->session->setFlash('error', " Error al validar los datos de la rendción.");
                }
                return $this->redirect(['mds_rendicion/index']);
            } else {
                $this->armarDateParaVista($model);

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $token = isset($_SESSION["tokenNest"]) ? $_SESSION["tokenNest"] : '';

                return $this->render('update', [
                    'model' => $model,
                    'username' => $username,
                    'listTipoDocumento' => $this->getListTiposDocumentos(),
                    'listUsuario' =>  $this->getListUsuarios(),
                    'listTipoRendicion' => $this->getListTipos(),
                    'listCapa' => $this->getListCapas(),
                    'listLugar' => $this->getListLugar($model->idcapa),
                    'adjuntos' => $model->getOtrosAdjuntos(),
                    'TIPO_COMBUSTIBLE' => Mds_rendicion::TIPO_COMBUSTIBLE,
                    'TIPO_AUH' => Mds_rendicion::TIPO_AUH,
                    'TIPO_ALIMENTAR' => Mds_rendicion::TIPO_ALIMENTAR,
                    // Modal New Persona
                    'model_persona' => new Sds_com_persona(),
                    'tiposDocumentos' => $this->getListTiposDocumentos(),
                    'generos' => $this->getListGeneros(),
                    'nacionalidades' => $this->getListNacionalidades(),
                    'token' => $token
                ]);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_rendicion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionDelete = $permissionCrud['permissionDelete'];
        $model = $this->findModel($id);

        if (is_null($model->deleted_at) && $permissionDelete) {
            $model->deleted_at = date('Y-m-d H:i:s');
            $model->idusuario_borra = Yii::$app->user->id;
            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                if ($model->save()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_rendicion', $model->idrendicion, $model->getAttributes());
                    Yii::$app->session->setFlash('success', " Se eliminó correctamente la rendición #" . $model->idrendicion);
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', " Error al borrar la rendición.");
                }
            } else {
                Yii::$app->session->setFlash('error', " Error al validar los datos de la rendición.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['mds_rendicion/index']);
    }

    public function actionReactivate($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionReactivate = $permissionCrud['permissionReactivate'];
        $model = $this->findModel($id);

        if ($model) {
            if (!is_null($model->deleted_at) && $permissionReactivate) {
                $model->deleted_at = null;
                $model->idusuario_borra = null;
                if ($model->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    if ($model->update()) {
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rendicion', $model->idrendicion, $model->getAttributes());
                        Yii::$app->session->setFlash('success', " Se reactivó correctamente la rendición #" . $model->idrendicion);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', " Error al reactivar la rendición #" . $model->idrendicion);
                    }
                } else {
                    Yii::$app->session->setFlash('error', " Error al validar los datos de la rendición.");
                }
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            }
        } else {
            Yii::$app->session->setFlash('error', " La rendición no existe.");
        }
        return $this->redirect(['mds_rendicion/index']);
    }

    public function actionReporte($id)
    {
        $permissionCrud = self::getPermissionsCrud();
        $permissionPrint = $permissionCrud['permissionPrint'];

        if ($permissionPrint) {

            $usuarioAuth = Yii::$app->user->identity;
            $dateToday = date('d/m/Y H:i:s');
            $array_idrendicion = explode(",", $id);
            $arrayRendiciones = [];

            foreach ($array_idrendicion as $id) {
                $model =  $this->findModel($id);
                if ($model) {
                    $this->idcapaByidlugar($model);
                    array_push($arrayRendiciones, $model);
                }
            }

            $content = $this->renderPartial('reporte', [
                'arrayRendiciones' => $arrayRendiciones,
            ]);

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'filename' => 'Rendición_' . $id . '.pdf',
                'content' => $content,
                'defaultFontSize' => 12,
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => '.kv-heading-1{font-size:18px}table{border-collapse: collapse; width: 100%;}.titulo{text-transform: uppercase; padding: 10px 0 10px .5rem}.parrafo,td{padding: 10px .5rem 5px .5rem}div.saltopagina{page-break-after:always}',
                'methods' => [
                    'SetTitle' => 'DETALLE DE RENDICIÓN ' . $id,
                    'SetHeader' => null,
                    'SetFooter' => ["<p style='text-align:left'>Imprime {$usuarioAuth->apellido} {$usuarioAuth->nombre} - {$dateToday} <br> Subsecretaria de Familia - Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb}</p>"],
                ]
            ]);

            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rendicion', $id, array());
            return $pdf->render();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionStorecomprobante()
    {
        $data = Yii::$app->request->post();
        $idrendicion = $data['idrendicion'];
        $model =  $this->findModel($idrendicion);
        $permissionCrud = self::getPermissionsCrud();
        $permissionUpdate = $permissionCrud['permissionUpdate'];

        if ($permissionUpdate && $model->idtipo == Mds_rendicion::TIPO_COMBUSTIBLE) {
            if ($data) {

                $fecha_desde = $data['fecha_desde'];
                $fecha_hasta = $data['fecha_hasta'];
                $observaciones = $data['observaciones'];

                $model_comprobante =  new Mds_rendicion_comprobante();

                $idusuario =  Yii::$app->user->identity->idusuario;
                $model_comprobante->idrendicion = $idrendicion;
                $model_comprobante->idusuario_carga = $idusuario;
                $model_comprobante->created_at = date('Y-m-d H:i:s');
                $model_comprobante->fecha_desde =  $fecha_desde;
                $model_comprobante->fecha_hasta =  $fecha_hasta;
                $model_comprobante->observaciones = $observaciones;

                $guardado = $model_comprobante->save();

                if ($guardado) {
                    // Upload archivo adjunto
                    $adjuntos = json_decode($data['otros_adjuntos'], true);
                    $this->storeAdjuntoComprobantesOtros($adjuntos, $model_comprobante);
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rendicion_comprobante', $model_comprobante->idrendicion_comprobante, $model_comprobante->getAttributes());
                }

                $message = 'exito';
            } else {
                $message = 'error';
            }
            return json_encode(['message' => $message]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionGuardarlogmanualusuario()
    {
        $success = false;
        if (Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rendicion_manual', null, array())) {
            $success = true;
        };
        return json_encode(['success' => $success]);
    }

    /**
     * Finds the Mds_rendicion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_rendicion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_rendicion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getListTipos()
    {
        //Busqueda tipos de rendición
        $tipos = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::RENDICION_TIPOS_RENDICION, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $tipos = ArrayHelper::map($tipos, 'idconfiguracion', 'descripcion');
        return $tipos;
    }

    protected function getListTiposDocumentos()
    {
        //Busqueda tipos de documentos
        $tipos = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_TIPO_DOC, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $tipos = ArrayHelper::map($tipos, 'idconfiguracion', 'descripcion');
        return $tipos;
    }

    protected function getListCapas()
    {
        $capas = Sds_gis_capa::getAllCapa();
        $capas = ArrayHelper::map($capas, 'idcapa', 'descripcion');
        return $capas;
    }

    protected function getListLugar($idcapa)
    {
        $lugares = Sds_gis_capa_item::getCapaItemByIdCapa($idcapa);
        $lugares = ArrayHelper::map($lugares, 'idcapaitem', 'descripcion');
        return $lugares;
    }

    protected function getListGeneros()
    {
        //Busqueda generos
        $generos = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_GENERO, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $generos = ArrayHelper::map($generos, 'idconfiguracion', 'descripcion');
        return $generos;
    }

    protected function getListNacionalidades()
    {
        //Busqueda nacionalidades
        $nacionalidades = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, "activo" => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        $nacionalidades = ArrayHelper::map($nacionalidades, 'idconfiguracion', 'descripcion');
        return $nacionalidades;
    }


    protected function getListUsuarios()
    {
        //Busqueda de usuarios cargadas en sds usuario
        $usuarios = Mds_seg_usuario::findBySql(
            "SELECT u.idusuario, UCASE(CONCAT(u.apellido, ' ',u.nombre,' (',u.dni,')')) nombre
            FROM mds_seg_usuario u 
            WHERE idcontacto IS NOT NULL
            AND activo = 1 
            ORDER BY apellido ASC
            "
        )->all();
        $listado = ArrayHelper::map($usuarios, 'idusuario', 'nombre');
        return $listado;
    }

    protected function getFilterUsuarioCarga()
    {
        $usuarioCarga = Mds_rendicion::find()
            ->select([
                'mds_rendicion.idusuario_carga',
                'CONCAT(UPPER(mds_seg_usuario.apellido),\', \', UPPER(mds_seg_usuario.nombre)) AS nombreUsuario'
            ])
            ->innerJoin('mds_seg_usuario', 'mds_rendicion.idusuario_carga=mds_seg_usuario.idusuario')
            ->orderBy(['nombreUsuario' => SORT_ASC])
            ->asArray()->all();
        $usuarioFiltro = ArrayHelper::map($usuarioCarga, 'idusuario_carga', 'nombreUsuario');
        return $usuarioFiltro;
    }

    protected function getFilterLugar()
    {
        $lugares = Mds_rendicion::getLugaresFiltro();
        if ($lugares) {
            return  ArrayHelper::map($lugares, 'idcapaitem', 'nombreLugar');
        }
        return [];
    }

    private function idcapaByidlugar($model)
    {
        if ($model->lugar) {
            $capa = Sds_gis_capa_item::find()->where("idcapaitem = {$model->idlugar}")->one();
            if ($capa) {
                $model->idcapa = $capa->idcapa;
            }
        }
    }

    private function armarDateParaMySql($model)
    {
        if ($model->fecha_comprobante) {
            $model->fecha_comprobante = date_format(DateTime::createFromFormat('d/m/Y', $model->fecha_comprobante), 'Y-m-d');
        }
        if ($model->fecha_vale) {
            $model->fecha_vale = date_format(DateTime::createFromFormat('d/m/Y', $model->fecha_vale), 'Y-m-d');
        }
    }

    private function armarDateParaVista($model)
    {
        if ($model->fecha_comprobante) {
            $model->fecha_comprobante = date_format(DateTime::createFromFormat('Y-m-d', $model->fecha_comprobante), 'd/m/Y');
        }
        if ($model->fecha_vale) {
            $model->fecha_vale = date_format(DateTime::createFromFormat('Y-m-d', $model->fecha_vale), 'd/m/Y');
        }
    }

    private function storeAdjuntoOtros($adjuntos, $model)
    {
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $pathRendiciones = __DIR__ . '/../web/uploads/rendicion/';
        $date = date('Y-m-d_H_i_s', time());
        foreach ($adjuntos as $key => $adjunto) {
            $path_info = pathinfo($adjunto["temp"]);
            $extension = $path_info['extension'];
            $nameFile = "rendicion_{$model->idrendicion}_{$date}_{$key}.{$extension}";
            if (rename($pathTemp . $adjunto['temp'], $pathRendiciones  . $nameFile)) {
                Mds_legales_archivo::saveFile($adjunto['nombre_original'], 'mds_rendicion', 'registro_rendicion', $model->idrendicion, $nameFile);
            }
        }
    }

    private function storeAdjuntoComprobantesOtros($adjuntos, $model_comprobante)
    {
        $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
        $pathRendiciones = __DIR__ . '/../web/uploads/rendicion/';
        $date = date('Y-m-d_H_i_s', time());
        foreach ($adjuntos as $key => $adjunto) {
            $path_info = pathinfo($adjunto["temp"]);
            $extension = $path_info['extension'];
            $nameFile = "comprobante_{$model_comprobante->idrendicion_comprobante}_{$date}_{$key}.{$extension}";
            if (rename($pathTemp . $adjunto['temp'], $pathRendiciones  . $nameFile)) {
                Mds_legales_archivo::saveFile($adjunto['nombre_original'], 'mds_rendicion', 'registro_rendicion_comprobante', $model_comprobante->idrendicion_comprobante, $nameFile);
            }
        }
    }

    private function eliminarAdjuntoOtros($adjuntosEliminados)
    {
        foreach ($adjuntosEliminados as $idAdjunto) {
            $modelArchivo = Mds_legales_archivo::findOne($idAdjunto);
            $modelArchivo->activo = 0;
            $modelArchivo->save();
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_legales_archivo', $idAdjunto, $modelArchivo->getAttributes());
        }
    }
}
