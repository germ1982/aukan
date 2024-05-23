<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_cor_intervencion;
use app\models\Mds_cor_intervencion_articulacion;
use app\models\Mds_cor_intervencion_consumo;
use app\models\Mds_cor_intervencion_problema;
use app\models\Mds_cor_intervencionSearch;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use app\models\Mds_sys_log;
use app\models\Sds_com_persona;
use app\models\Sds_ris_persona;
use app\models\Sds_ris_risneu;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario_rol;
use app\models\Sds_800_llamada;
use app\models\Sds_com_provincia;
use app\models\Mds_cor_intervencion_usuario;

use kartik\mpdf\Pdf;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\ForbiddenHttpException;

/**
 * Mds_cor_intervencionController implements the CRUD actions for Mds_cor_intervencion model.
 */
class Mds_cor_intervencionController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'validar_dni', 'reporte_intervencion'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'validar_dni', 'reporte_intervencion'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_COR_INTERVENCION,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_cor_intervencion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_cor_intervencionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $usuarioAuth = Yii::$app->user->identity;
        $permissionsImprimirRisneu = Mds_seg_permiso::getAllPermissions(Mds_seg_item::MODULO_RIS_RISNEU_IMPRIMIR, $usuarioAuth->idusuario);
        $stringButtonsIndex = '{view} {update} {compartir} {imprimir} {previo}';
        if (!empty($permissionsImprimirRisneu)) {
            $stringButtonsIndex .= ' {imprimirRisneu}';
        }
        $stringButtonsIndex .= ' {delete} {reactivate}';

        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cor_intervencion', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'hasRolAdminGeneral' => $hasRolAdminGeneral,
            'stringButtonsIndex' => $stringButtonsIndex,
            'personaFiltro' => Mds_cor_intervencion::getPersonaFiltro($hasRolAdminGeneral),
            'profesionalFiltro' => Mds_cor_intervencion::getProfesionalFiltro($hasRolAdminGeneral),
            'tipoFiltro' => Mds_cor_intervencion::getTipoFiltro($hasRolAdminGeneral)
        ]);
    }


    /**
     * Displays a single Mds_cor_intervencion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $idUsuario = Yii::$app->user->identity->idusuario;
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        $compartidoConUsuario = Mds_cor_intervencion_usuario::findOne(['idintervencion' => $model->idintervencion, 'idusuario' => $idUsuario]);
        $permiso = $idUsuario == $model->idusuario || $compartidoConUsuario || $hasRolAdminGeneral;

        if ($permiso) {
            $request = Yii::$app->request;
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cor_intervencion', $id, array());

            // Buscar todos los compartidos
            $compartidos = Mds_seg_usuario::find()
                ->select('*')
                ->innerJoin('mds_cor_intervencion_usuario', 'mds_seg_usuario.idusuario = mds_cor_intervencion_usuario.idusuario')
                ->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])
                ->where(["idintervencion" => $model->idintervencion])
                ->all();
            if (sizeof($compartidos) > 0) {
                $model->compartido_con = '';
                foreach ($compartidos as $intervencion) {
                    $fullName = "- <b>" . strtoupper($intervencion->apellido) . "</b>, " . strtoupper($intervencion->nombre);
                    $model->compartido_con = "$model->compartido_con $fullName";
                    if (next($compartidos)) {
                        $model->compartido_con =  "$model->compartido_con <br/> ";
                    }
                }
            } else {
                $model->compartido_con = 'No se seleccionó ningún usuario. La intervención es visible solo para el usuario que la creó.';
            }

            $model_persona = Sds_com_persona::findOne($model->idpersona);
            $model->dni_beneficiario = $model_persona->documento;
            $model->nombre = $model_persona->nombre;
            $model->apellido = $model_persona->apellido;
            $model->fecha_nacimiento = date('d/m/Y', strtotime($model_persona->fecha_nacimiento));
            $model->fecha_informe = date('d/m/Y', strtotime($model->fecha_informe));
            $model->genero = $model_persona->genero0->descripcion;
            $model->edad = $model_persona->getEdad($model_persona->fecha_nacimiento);

            $consumosString = '';
            $consumos = Mds_cor_intervencion_consumo::getConsumosCargadosByIdIntervencion($id);
            if (count($consumos) > 0) {
                foreach ($consumos as $key => $consumo) {
                    $consumosString .=  $key + 1 === count($consumos) ? "{$consumo['descripcion']}" : "{$consumo['descripcion']}, ";
                }
            }

            $problemasString = '';
            $problemas = Mds_cor_intervencion_problema::getProblemasCargadosByIdIntervencion($id);
            if (count($problemas) > 0) {
                foreach ($problemas as $key => $problema) {
                    $problemasString .=  $key + 1 === count($problemas) ? "{$problema['descripcion']}" : "{$problema['descripcion']}, ";
                }
            }

            $articulacionesString = '';
            $articulaciones = Mds_cor_intervencion_articulacion::getArticulacionesCargadasByIdIntervencion($id);
            if (count($articulaciones) > 0) {
                foreach ($articulaciones as $key => $articulacion) {
                    $articulacionesString .=  $key + 1 === count($articulaciones) ? "{$articulacion['descripcion']}" : "{$articulacion['descripcion']}, ";
                }
            }

            return $this->render('view', [
                'model' => $model,
                'consumos' => $consumosString,
                'problemas' => $problemasString,
                'articulaciones' => $articulacionesString,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Mds_cor_intervencion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $request = Yii::$app->request;
        $model = new Mds_cor_intervencion();
        $model->idllamada = $id;
        $model->fecha_hora = date('Y-m-d H:i');
        $user  = Yii::$app->user->identity;
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $model->idusuario = $user->idusuario;
        $model->compartido_con = new ArrayDataProvider([
            'allModels' => [],
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['id', 'name'],
            ],
        ]);

        $listProvincias = Sds_com_provinciaController::getListProvincias();
        $model->provincia = Sds_com_provincia::ID_PROVINCIA_NEUQUEN;
        $listLocalidades = Sds_com_provinciaController::getListLocalidadesByProvincia($model->provincia);
        $listArticulaciones = Mds_cor_intervencion_articulacion::getListArticulaciones();
        $listConsumos = Mds_cor_intervencion_articulacion::getListConsumos();
        $listProblemas = Mds_cor_intervencion_articulacion::getListProblemas();

        if ($request->isGet) {
            return $this->render('create', [
                'model' => $model,
                'listProvincias' => $listProvincias,
                'listLocalidades' => $listLocalidades,
                'listArticulaciones' => $listArticulaciones,
                'listConsumos' => $listConsumos,
                'listProblemas' => $listProblemas,
            ]);
        } else if ($model->load($request->post())) {

            $transaction = Yii::$app->db->beginTransaction();
            if ($model->fecha_informe) {
                $fecha_not = date_create($model->fecha_informe);
                $fecha_not_guardar = date_format($fecha_not, 'Y-m-d');
                $model->fecha_informe = $fecha_not_guardar;
            }
            $model->fecha_hora = date('Y-m-d H:i');

            // Upload archivo salud
            $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_salud');
            if (isset($tmpfile)) {
                if ($tmpfile->getExtension() != 'pdf') {
                    $tmpfile_contents = file_get_contents($tmpfile->tempName);
                    $model->archivo_adjunto = "data:image/png;base64," . base64_encode($tmpfile_contents);
                } else {
                    $tmpfile_contents = file_get_contents($tmpfile->tempName);
                    $model->archivo_adjunto = "data:application/pdf;base64," . base64_encode($tmpfile_contents);
                }
            }

            if ($model->save()) {
                $transaction->commit();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_cor_intervencion', $model->idintervencion, $model->getAttributes());

                if (isset(Yii::$app->request->post()['articulacion'])) {
                    $this->guardarArticulaciones($model, Yii::$app->request->post()['articulacion'], 'create');
                }

                if (isset(Yii::$app->request->post()['consumo'])) {
                    $this->guardarConsumos($model, Yii::$app->request->post()['consumo'], 'create');
                }

                if (isset(Yii::$app->request->post()['articulacion'])) {
                    $this->guardarProblemas($model, Yii::$app->request->post()['problema'], 'create');
                }

                Yii::$app->session->setFlash('success', "Se creó correctamente la intervención.");
                return $this->redirect(['index']);
            }
        }
    }

    public function actionValidar_dni($dni)
    {
        //Busco la persona, si existe traigo los datos para editar
        if ($dni != '') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = Sds_ris_persona::findBySql("SELECT risper.*
                                                FROM sds_ris_persona risper
                                                JOIN sds_com_persona persona ON persona.idpersona=risper.idpersona
                                                JOIN sds_ris_risneu risneu ON risneu.idrisneu=risper.idrisneu
                                                WHERE documento=$dni and risper.activo = 1
                                                ORDER BY risneu.updated_at DESC, risneu.idrisneu DESC")->one();
            $model_persona = null;
            $datos_persona = array();
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
                    ]);
                } else {
                    return $this->redirect([
                        'sds_ris_risneu/create',
                        'finalizar' => false,
                        'dni' => $dni,
                    ]);
                }
            } else {
                $model_persona = Sds_com_persona::findOne($model->idpersona);
                $datos_persona = [
                    'fecha_nacimiento' => date('d/m/Y', strtotime($model_persona->fecha_nacimiento)),
                    'genero' => $model_persona->genero0->descripcion,
                    'edad' => $model_persona->getEdad($model_persona->fecha_nacimiento)
                ];
            }
            $result = array();
            array_push($result, $model->getAttributes());
            array_push($result, $model_persona->getAttributes());
            array_push($result, $datos_persona);
            return json_encode($result);
        }
        return null;
    }

    /**
     * Updates an existing Mds_cor_intervencion model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $idUsuario = Yii::$app->user->identity->idusuario;
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        $compartidoConUsuario = Mds_cor_intervencion_usuario::findOne(['idintervencion' => $model->idintervencion, 'idusuario' => $idUsuario, 'editar' => 1]);
        $permiso = $idUsuario == $model->idusuario || $compartidoConUsuario || $hasRolAdminGeneral;

        if ($permiso) {
            $request = Yii::$app->request;
            $model_persona = Sds_com_persona::findOne($model->idpersona);
            $model->dni_beneficiario = $model_persona->documento;
            $model->nombre = $model_persona->nombre;
            $model->apellido = $model_persona->apellido;
            $model->fecha_nacimiento = date('d/m/Y', strtotime($model_persona->fecha_nacimiento));
            $model->fecha_informe = date('d-m-Y', strtotime($model->fecha_informe));
            $model->genero = $model_persona->genero0->descripcion;
            $model->edad = $model_persona->getEdad($model_persona->fecha_nacimiento);

            $listProvincias = Sds_com_provinciaController::getListProvincias();
            $model->provincia = $model->localidad ? $model->localidad->provincia->idprovincia : null;
            $listLocalidades = array();
            if ($model->provincia) {
                $listLocalidades = Sds_com_provinciaController::getListLocalidadesByProvincia($model->provincia);
            }
            $listArticulaciones = Mds_cor_intervencion_articulacion::getListArticulaciones();
            $articulaciones = $model->getArticulaciones();
            $articulacionesArray = array();
            $listConsumos = Mds_cor_intervencion_articulacion::getListConsumos();
            $consumos = $model->getConsumos();
            $consumosArray = array();
            $listProblemas = Mds_cor_intervencion_articulacion::getListProblemas();
            $problemas = $model->getProblemas();
            $problemasArray = array();

            if (count($articulaciones) > 0) {
                foreach ($articulaciones as $articulacion) {
                    array_push($articulacionesArray, $articulacion['idarticulacion']);
                }
            }
            if (count($consumos) > 0) {
                foreach ($consumos as $consumo) {
                    array_push($consumosArray, $consumo['idconsumo']);
                }
            }
            if (count($problemas) > 0) {
                foreach ($problemas as $problema) {
                    array_push($problemasArray, $problema['idproblema']);
                }
            }

            if ($request->isGet) {
                return $this->render('update', [
                    'model' => $model,
                    'listProvincias' => $listProvincias,
                    'listLocalidades' => $listLocalidades,
                    'listArticulaciones' => $listArticulaciones,
                    'articulaciones' => $articulacionesArray,
                    'listConsumos' => $listConsumos,
                    'consumos' => $consumosArray,
                    'listProblemas' => $listProblemas,
                    'problemas' => $problemasArray,
                ]);
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;
                $model_persona = new Sds_com_persona;
                $model_persona->documento_tipo = '83';
                $model_persona->conviviente = 0;
                $fecha_not = date_create($model->fecha_informe);
                $fecha_not_guardar = date_format($fecha_not, 'Y-m-d');
                $model->fecha_informe = $fecha_not_guardar;
                $model->fecha_hora = date('Y-m-d H:i');

                // Upload archivo salud
                $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_salud');
                if (isset($tmpfile)) {
                    if ($tmpfile->getExtension() != 'pdf') {
                        // print_r($tmpfile);
                        $tmpfile_contents = file_get_contents($tmpfile->tempName);
                        $model->archivo_adjunto = "data:image/png;base64," . base64_encode($tmpfile_contents);
                    } else {
                        // print_r($tmpfile);
                        $tmpfile_contents = file_get_contents($tmpfile->tempName);
                        $model->archivo_adjunto = "data:application/pdf;base64," . base64_encode($tmpfile_contents);
                    }
                }

                if ($model->idpersona > 0) {
                    $model_persona = Sds_com_persona::findOne($model->idpersona);
                }
                if (!$model_persona->save()) {
                    $guardado = false;
                    $transaction->rollBack();
                } else {
                    $model->idpersona = $model_persona->idpersona;
                    //ANOTEZE: Aca es recomendable para probar el guardado, pasar como parámetro false, para forzar el insert y ver el error que tira en la db.
                    //Luego de depurar, sacarlo y dejar el save sin parámetros, porque sirve para saltar validaciones.
                    if ($guardado && $model->save()) {
                        $transaction->commit();
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_cor_intervencion', $id, $model->getAttributes());

                        $articulaciones = array();
                        if (isset(Yii::$app->request->post()['articulacion'])) {
                            $articulaciones = Yii::$app->request->post()['articulacion'];
                        }
                        $this->guardarArticulaciones($model, $articulaciones, 'update');

                        $consumos = array();
                        if (isset(Yii::$app->request->post()['consumo'])) {
                            $consumos = Yii::$app->request->post()['consumo'];
                        }
                        $this->guardarConsumos($model, $consumos, 'update');

                        $problemas = array();
                        if (isset(Yii::$app->request->post()['problema'])) {
                            $problemas = Yii::$app->request->post()['problema'];
                        }
                        $this->guardarProblemas($model, $problemas, 'update');

                        if ($request->isAjax) {
                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return [
                                'title' => "Modificar Intervención #$id",
                                'content' => '<span class="text-success">   Intervención modificada con éxito</span>',
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                    Html::a('Nueva Intervención', ['create'], ['class' => 'btn btn-success', 'role' => 'modal-remote'])
                            ];
                        } else {
                            Yii::$app->session->setFlash('success', "Se actualizó correctamente la intervención.");
                            return $this->redirect(['index']);
                        }
                    }
                }
            }

            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Modificar Intervención #$id",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'listProvincias' => $listProvincias,
                        'listLocalidades' => $listLocalidades,
                        'listArticulaciones' => $listArticulaciones,
                        'listConsumos' => $listConsumos,
                        'listProblemas' => $listProblemas,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-success', 'type' => "submit"])
                ];
            } else {
                return $this->redirect(['index']);
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing Mds_acomp_asistencia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        $model = $this->findModel($id);

        $usuarioAuth = Yii::$app->user->identity;
        if (($hasRolAdminGeneral || $model->idusuario === $usuarioAuth->idusuario) && is_null($model->deleted_at)) {
            $model->deleted_at = date('Y-m-d H:i:s');
            $model->idusuario_borra = Yii::$app->user->id;
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', "Se eliminó correctamente la intervención.");
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_cor_intervencion', $id, $model->getAttributes());
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', "Error al borrar la intervención.");
                }
            } else {
                Yii::$app->session->setFlash('error', "Error al validar los datos de la intervención.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionReactivate($id)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);

        if ($hasRolAdminGeneral) {
            $intervencion = $this->findModel($id);
            if ($intervencion) {
                $intervencion->deleted_at = null;
                $intervencion->idusuario_borra = null;
                if ($intervencion->update()) {
                    Yii::$app->session->setFlash('success', "Se reactivó correctamente la intervención.");
                } else {
                    Yii::$app->session->setFlash('error', "Error al reactivar la intervención.");
                }
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_cor_intervencion', $intervencion->idintervencion, $intervencion->getAttributes());
            } else {
                Yii::$app->session->setFlash('error', "La intervención no existe.");
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Mds_cor_intervencion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_cor_intervencion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_cor_intervencion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionReporte_intervencion($idintervencion)
    {
        $model = $this->findModel($idintervencion);
        $idUsuario = Yii::$app->user->identity->idusuario;
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);
        $compartidoConUsuario = Mds_cor_intervencion_usuario::findOne(['idintervencion' => $model->idintervencion, 'idusuario' => $idUsuario, 'editar' => 1]);
        $permiso = $idUsuario == $model->idusuario || $compartidoConUsuario || $hasRolAdminGeneral;

        if ($permiso) {
            $content = $this->renderPartial('reporte_intervencion', ['idintervencion' => $idintervencion]); // setup kartik\mpdf\Pdf component 
            //        print_r($content);
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
                    'SetTitle' => 'INTERVENCION PDF',
                    'SetHeader' => null,
                    'SetFooter' => null,
                ]
            ]);

            return $pdf->render();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function guardarArticulaciones($intervencion, $articulaciones, $action)
    {
        $today = date('Y-m-d H:i:s');
        if ($action === 'update') {
            $articulacionesCargadas = Mds_cor_intervencion_articulacion::getArticulacionesCargadasByIdIntervencion($intervencion->idintervencion);

            if (count($articulaciones) > 0 && count($articulacionesCargadas) > 0) {
                foreach ($articulacionesCargadas as $keyArticulacionCargada => $articulacionCargada) {
                    foreach ($articulaciones as $keyArticulacion => $articulacion) {
                        if ($articulacionCargada['idconfiguracion'] == $articulacion) {
                            unset($articulacionesCargadas[$keyArticulacionCargada]);
                            unset($articulaciones[$keyArticulacion]);
                        }
                    }
                }
            }

            if (count($articulacionesCargadas) > 0) {
                foreach ($articulacionesCargadas as $articulacionCargada) {
                    $model = Mds_cor_intervencion_articulacion::findOne($articulacionCargada['idintervencionarticulacion']);
                    $model->deleted_at = $today;
                    $model->updated_at = $today;
                    $model->idusuario_borra = Yii::$app->user->identity->idusuario;
                    $model->update();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_cor_intervencion_articulacion', $model->idintervencionarticulacion, $model->getAttributes());
                }
            }
        }

        if (count($articulaciones) > 0) {
            foreach ($articulaciones as $articulacion) {
                $model = new Mds_cor_intervencion_articulacion();
                $model->idintervencion = $intervencion->idintervencion;
                $model->idarticulacion = $articulacion;
                $model->created_at = $today;
                $model->idusuario_carga = Yii::$app->user->identity->idusuario;
                $model->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_cor_intervencion_articulacion', $model->idintervencionarticulacion, $model->getAttributes());
            }
        }
    }

    public function guardarConsumos($intervencion, $consumos, $action)
    {
        $today = date('Y-m-d H:i:s');
        if ($action === 'update') {
            $consumosCargados = Mds_cor_intervencion_consumo::getConsumosCargadosByIdIntervencion($intervencion->idintervencion);

            if (count($consumos) > 0 && count($consumosCargados) > 0) {
                foreach ($consumosCargados as $keyConsumoCargada => $consumoCargado) {
                    foreach ($consumos as $keyConsumo => $consumo) {
                        if ($consumoCargado['idconfiguracion'] == $consumo) {
                            unset($consumosCargados[$keyConsumoCargada]);
                            unset($consumos[$keyConsumo]);
                        }
                    }
                }
            }

            if (count($consumosCargados) > 0) {
                foreach ($consumosCargados as $consumoCargado) {
                    $model = Mds_cor_intervencion_consumo::findOne($consumoCargado['idintervencionconsumo']);
                    $model->deleted_at = $today;
                    $model->updated_at = $today;
                    $model->idusuario_borra = Yii::$app->user->identity->idusuario;
                    $model->update();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_cor_intervencion_consumo', $model->idintervencionconsumo, $model->getAttributes());
                }
            }
        }

        if (count($consumos) > 0) {
            foreach ($consumos as $consumo) {
                $model = new Mds_cor_intervencion_consumo();
                $model->idintervencion = $intervencion->idintervencion;
                $model->idconsumo = $consumo;
                $model->created_at = $today;
                $model->idusuario_carga = Yii::$app->user->identity->idusuario;
                $model->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_cor_intervencion_consumo', $model->idintervencionconsumo, $model->getAttributes());
            }
        }
    }

    public function guardarProblemas($intervencion, $problemas, $action)
    {
        $today = date('Y-m-d H:i:s');
        if ($action === 'update') {
            $problemasCargados = Mds_cor_intervencion_problema::getProblemasCargadosByIdIntervencion($intervencion->idintervencion);

            if (count($problemas) > 0 && count($problemasCargados) > 0) {
                foreach ($problemasCargados as $keyProblemaCargada => $problemaCargada) {
                    foreach ($problemas as $keyProblema => $problema) {
                        if ($problemaCargada['idconfiguracion'] == $problema) {
                            unset($problemasCargados[$keyProblemaCargada]);
                            unset($problemas[$keyProblema]);
                        }
                    }
                }
            }

            if (count($problemasCargados) > 0) {
                foreach ($problemasCargados as $problemaCargado) {
                    $model = Mds_cor_intervencion_problema::findOne($problemaCargado['idintervencionproblema']);
                    $model->deleted_at = $today;
                    $model->updated_at = $today;
                    $model->idusuario_borra = Yii::$app->user->identity->idusuario;
                    $model->update();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_cor_intervencion_problema', $model->idintervencionproblema, $model->getAttributes());
                }
            }
        }

        if (count($problemas) > 0) {
            foreach ($problemas as $problema) {
                $model = new Mds_cor_intervencion_problema();
                $model->idintervencion = $intervencion->idintervencion;
                $model->idproblema = $problema;
                $model->created_at = $today;
                $model->idusuario_carga = Yii::$app->user->identity->idusuario;
                $model->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_cor_intervencion_problema', $model->idintervencionproblema, $model->getAttributes());
            }
        }
    }
}
