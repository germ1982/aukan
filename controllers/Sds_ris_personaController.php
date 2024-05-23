<?php

namespace app\controllers;

use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use Yii;
use app\models\Sds_ris_persona;
use app\models\Sds_ris_persona_enfermedad;
use app\models\Sds_ris_personaSearch;
use app\models\Sds_ris_risneu;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ris_persona_discapacidad;
use app\models\Sds_ris_persona_sustancia;

/**
 * Sds_ris_personaController implements the CRUD actions for Sds_ris_persona model.
 */
class Sds_ris_personaController extends Controller
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
        ];
    }

    /**
     * Lists all Sds_ris_persona models.
     * @return mixed
     */
    public function actionIndex($oficial = false)
    {
        $searchModel = new Sds_ris_personaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_ris_persona', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'oficial' => $oficial,
        ]);
    }


    /**
     * Displays a single Sds_ris_persona model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $oficial = false)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_ris_persona', $id, array());
        $model = $this->findModel($id);
        $model_persona = Sds_com_persona::findOne($model->idpersona);
        $tipoParentezco = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_PARENTEZCO), 'idconfiguracion', 'descripcion');
        $tipoSitConyugal = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_SIT_CONYUGAL), 'idconfiguracion', 'descripcion');
        $tipoEscolaridad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ESCOLARIDAD), 'idconfiguracion', 'descripcion');
        $tipoUltAnioAprobado = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ULTIMO_ANIO_APROBADO), 'idconfiguracion', 'descripcion');
        $tipoEstEducativo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TIPO_EST_EDUCATIVO), 'idconfiguracion', 'descripcion');
        $tipoTrabajo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TRABAJO), 'idconfiguracion', 'descripcion');
        $tipoVinculoContractual = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VINCULO_CONTRACTUAL), 'idconfiguracion', 'descripcion');
        $tipoTipoTrabajo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TIPO_TRABAJO), 'idconfiguracion', 'descripcion');
        $tipoDiscapacidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_DISCAPACIDAD), 'idconfiguracion', 'descripcion');
        $tipoEnfermedad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ENFERMEDAD), 'idconfiguracion', 'descripcion');
        $tipoCoberturaSalud = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_COBERTURA_SALUD), 'idconfiguracion', 'descripcion');
        $configuracionParentezco = Sds_com_configuracion::findBySql("(select idconfiguracion from sds_com_configuracion where idconfiguraciontipo=11 limit 1)")->one()->idconfiguracion;
        $tipoDoc = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TIPO_DOC), 'idconfiguracion', 'descripcion');
        $tipoGenero = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO), 'idconfiguracion', 'descripcion');
        $tipoGeneroAutopercibido = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO_AUTOPERCIBIDO), 'idconfiguracion', 'descripcion');
        $tipoNacionalidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD), 'idconfiguracion', 'descripcion');
        $tipoCondicionHacinamiento = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::CONDICIION_HACINAMIENTO), 'idconfiguracion', 'descripcion');
        $tipoPueblosOriginarios = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::PUEBLOS_ORIGINARIOS), 'idconfiguracion', 'descripcion');
        $tipoSustancia = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::SUSTANCIA), 'idconfiguracion', 'descripcion');
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Persona #" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                    'model_persona' => $model_persona,
                    'tipoParentezco' => $tipoParentezco,
                    'tipoSitConyugal' => $tipoSitConyugal,
                    'tipoEscolaridad' => $tipoEscolaridad,
                    'tipoUltAnioAprobado' => $tipoUltAnioAprobado,
                    'tipoEstEducativo' => $tipoEstEducativo,
                    'tipoTrabajo' => $tipoTrabajo,
                    'tipoVinculoContractual' => $tipoVinculoContractual,
                    'tipoTipoTrabajo' => $tipoTipoTrabajo,
                    'tipoDiscapacidad' => $tipoDiscapacidad,
                    'tipoEnfermedad' => $tipoEnfermedad,
                    'tipoCoberturaSalud' => $tipoCoberturaSalud,
                    'tipoDoc' => $tipoDoc,
                    'tipoGenero' => $tipoGenero,
                    'tipoGeneroAutopercibido' => $tipoGeneroAutopercibido,
                    'tipoNacionalidad' => $tipoNacionalidad,
                    'tipoCondicionHacinamiento' => $tipoCondicionHacinamiento,
                    'tipoPueblosOriginarios' => $tipoPueblosOriginarios,
                    'tipoSustancia' => $tipoSustancia,
                    'oficial' => $oficial
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                //. Html::a('Editar', ['update', 'id' => $id ], ['class' => 'btn btn-primary', 'role' => 'modal-remote', 'name' => 'btn_update' ])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
                'tipoParentezco' => $tipoParentezco,
                'tipoSitConyugal' => $tipoSitConyugal,
                'tipoEscolaridad' => $tipoEscolaridad,
                'tipoUltAnioAprobado' => $tipoUltAnioAprobado,
                'tipoEstEducativo' => $tipoEstEducativo,
                'tipoTrabajo' => $tipoTrabajo,
                'tipoVinculoContractual' => $tipoVinculoContractual,
                'tipoTipoTrabajo' => $tipoTipoTrabajo,
                'tipoDiscapacidad' => $tipoDiscapacidad,
                'tipoEnfermedad' => $tipoEnfermedad,
                'tipoCoberturaSalud' => $tipoCoberturaSalud,
                'configuracionParentezco' => $configuracionParentezco,
                'tipoDoc' => $tipoDoc,
                'tipoGenero' => $tipoGenero,
                'tipoGeneroAutopercibido' => $tipoGeneroAutopercibido,
                'tipoNacionalidad' => $tipoNacionalidad,
                'tipoCondicionHacinamiento' => $tipoCondicionHacinamiento,
                'tipoPueblosOriginarios' => $tipoPueblosOriginarios,
                'tipoSustancia' => $tipoSustancia,
                'oficial' => $oficial
            ]);
        }
    }

    /**
     * Creates a new Sds_ris_persona model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idrisneu, $dni = null, $oficial = false)
    {
        $request = Yii::$app->request;
        $model = new Sds_ris_persona();
        $model->idrisneu = $idrisneu;
        $model->trabajo_porque = 1;
        $model->idpersona = 0;
        $model_persona =  new Sds_com_persona();
        $model_persona->conviviente = 0;

        $tipoParentezco = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_PARENTEZCO), 'idconfiguracion', 'descripcion');
        $tipoSitConyugal = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_SIT_CONYUGAL), 'idconfiguracion', 'descripcion');
        $tipoEscolaridad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ESCOLARIDAD), 'idconfiguracion', 'descripcion');
        $tipoUltAnioAprobado = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ULTIMO_ANIO_APROBADO), 'idconfiguracion', 'descripcion');
        $tipoEstEducativo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TIPO_EST_EDUCATIVO), 'idconfiguracion', 'descripcion');
        $tipoTrabajo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TRABAJO), 'idconfiguracion', 'descripcion');
        $tipoVinculoContractual = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VINCULO_CONTRACTUAL), 'idconfiguracion', 'descripcion');
        $tipoTipoTrabajo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TIPO_TRABAJO), 'idconfiguracion', 'descripcion');
        $tipoDiscapacidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_DISCAPACIDAD), 'idconfiguracion', 'descripcion');
        $tipoEnfermedad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ENFERMEDAD), 'idconfiguracion', 'descripcion');
        $tipoCoberturaSalud = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_COBERTURA_SALUD), 'idconfiguracion', 'descripcion');
        $configuracionParentezco = Sds_com_configuracion::findBySql("(select idconfiguracion from sds_com_configuracion where idconfiguraciontipo=11 limit 1)")->one()->idconfiguracion;
        $tipoDoc = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TIPO_DOC), 'idconfiguracion', 'descripcion');
        $tipoGenero = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO), 'idconfiguracion', 'descripcion');
        $tipoGeneroAutopercibido = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO_AUTOPERCIBIDO), 'idconfiguracion', 'descripcion');
        $tipoNacionalidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD), 'idconfiguracion', 'descripcion');
        $tipoCondicionHacinamiento = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::CONDICIION_HACINAMIENTO), 'idconfiguracion', 'descripcion');
        $tipoPueblosOriginarios = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::PUEBLOS_ORIGINARIOS), 'idconfiguracion', 'descripcion');
        $tipoSustancia = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::SUSTANCIA), 'idconfiguracion', 'descripcion');
        if ($dni != null) {
            if ($dni != 0)
                $model_persona->documento = $dni;
            $model->parentezco = $configuracionParentezco;
        }

        $modelRisPersona = new Sds_ris_persona();
        $risPersona = $modelRisPersona->getFirstPersonaByIdRisneu($idrisneu);
        $esPrimeraPersona = false;
        if (!$risPersona) {
            $esPrimeraPersona = true;
            $model->parentezco = Sds_ris_persona::ID_PARENTESCO_JEFE;
        } else {
            //Solo puede existir un Jefe por Grupo Conviviente.
            $risPersonaJefe = $modelRisPersona->getJefeByIdRisneu($idrisneu);
            if ($risPersonaJefe) {
                unset($tipoParentezco[Sds_ris_persona::ID_PARENTESCO_JEFE]);
            }
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nueva Persona",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'model_persona' => $model_persona,
                        'tipoParentezco' => $tipoParentezco,
                        'tipoSitConyugal' => $tipoSitConyugal,
                        'tipoEscolaridad' => $tipoEscolaridad,
                        'tipoUltAnioAprobado' => $tipoUltAnioAprobado,
                        'tipoEstEducativo' => $tipoEstEducativo,
                        'tipoTrabajo' => $tipoTrabajo,
                        'tipoVinculoContractual' => $tipoVinculoContractual,
                        'tipoTipoTrabajo' => $tipoTipoTrabajo,
                        'tipoDiscapacidad' => $tipoDiscapacidad,
                        'tipoEnfermedad' => $tipoEnfermedad,
                        'tipoCoberturaSalud' => $tipoCoberturaSalud,
                        'configuracionParentezco' => $configuracionParentezco,
                        'tipoDoc' => $tipoDoc,
                        'tipoGenero' => $tipoGenero,
                        'tipoGeneroAutopercibido' => $tipoGeneroAutopercibido,
                        'tipoNacionalidad' => $tipoNacionalidad,
                        'tipoCondicionHacinamiento' => $tipoCondicionHacinamiento,
                        'tipoPueblosOriginarios' => $tipoPueblosOriginarios,
                        'tipoSustancia' => $tipoSustancia,
                        'esPrimeraPersona' => $esPrimeraPersona,
                        'isCreate' => true,
                        'oficial' => $oficial
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {
                // $validacionModel = $model->validate();
                // $validacionPersona = $model_persona->validate();
                // if ($validacionModel && $validacionPersona) {
                $transaction = Yii::$app->db->beginTransaction();

                $ban_persona_existente = 0;
                if ($model->idpersona > 0) {
                    $ban_persona_existente = 1;
                    $model_persona = Sds_com_persona::findOne($model->idpersona);
                    // $model_persona->idpersona = $model->idpersona;
                    // $model_persona->isNewRecord = false;
                }

                if ($model_persona->load($request->post())) {
                    if ($model_persona->fecha_nacimiento != null) {
                        $model_persona->fecha_nacimiento = date('Y-m-d', strtotime(str_replace('/', '-', $model_persona->fecha_nacimiento)));
                    }

                    $enfermedades = $model->enfermedades != null ? $model->enfermedades : array();
                    $enfermedades_count = count($enfermedades);

                    $discapacidades = $model->discapacidades != null ? $model->discapacidades : array();
                    $discapacidades_count = count($discapacidades);

                    $sustancias = $model->sustancias != null ? $model->sustancias : array();
                    $sustancias_count = count($sustancias);

                    $guardado_persona = $model_persona->save();
                    if ($guardado_persona) {
                        $model->idpersona = $model_persona->idpersona;
                        $model->created_at = date('Y-m-d H:i:s');
                        $model->idusuario_carga = Yii::$app->user->id;
                        $guardado_risper = $model->save();

                        //Carga enfermedades
                        for ($index_enf = 0; $index_enf < $enfermedades_count; $index_enf++) {
                            $persona_enfermedad = new Sds_ris_persona_enfermedad();
                            $persona_enfermedad->idpersonarisneu = $model->idpersonarisneu;
                            $persona_enfermedad->enfermedad = $enfermedades[$index_enf];
                            $persona_enfermedad->created_at = date('Y-m-d H:i:s');
                            $persona_enfermedad->idusuario_carga = Yii::$app->user->id;
                            if (!$persona_enfermedad->save()) {
                                $transaction->rollBack();
                                $guardado_risper = false;
                            } else {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'Sds_ris_persona_enfermedad', $persona_enfermedad->idpersonaenfermedad, $persona_enfermedad->getAttributes());
                            }
                        }

                        //Carga discapacidades
                        for ($index_disc = 0; $index_disc < $discapacidades_count; $index_disc++) {
                            $persona_discapacidad = new Sds_ris_persona_discapacidad();
                            $persona_discapacidad->idpersonarisneu = $model->idpersonarisneu;
                            $persona_discapacidad->discapacidad = $discapacidades[$index_disc];
                            $persona_discapacidad->created_at = date('Y-m-d H:i:s');
                            $persona_discapacidad->idusuario_carga = Yii::$app->user->id;
                            if (!$persona_discapacidad->save()) {
                                $transaction->rollBack();
                                $guardado_risper = false;
                            } else {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'Sds_ris_persona_discapacidad', $persona_discapacidad->idpersonadiscapacidad, $persona_discapacidad->getAttributes());
                            }
                        }

                        //Carga sustancias
                        for ($index_sus = 0; $index_sus < $sustancias_count; $index_sus++) {
                            $persona_sustancia = new Sds_ris_persona_sustancia();
                            $persona_sustancia->idpersonarisneu = $model->idpersonarisneu;
                            $persona_sustancia->sustancia = $sustancias[$index_sus];
                            $persona_sustancia->created_at = date('Y-m-d H:i:s');
                            $persona_sustancia->idusuario_carga = Yii::$app->user->id;
                            if (!$persona_sustancia->save()) {
                                $transaction->rollBack();
                                $guardado_risper = false;
                            } else {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'Sds_ris_persona_sustancia', $persona_sustancia->idpersonasustancia, $persona_sustancia->getAttributes());
                            }
                        }

                        //Verifico si la persona figura en otro risneu.
                        $persona_anterior = Sds_ris_persona::findBySql("SELECT risper.*
                                                                            FROM sds_ris_persona risper
                                                                            JOIN sds_com_persona persona on 
                                                                            persona.idpersona=risper.idpersona
                                                                            where risper.activo = 1 and documento=$model_persona->documento 
                                                                                    and risper.idrisneu!=" . $model->idrisneu)->one();
                        //ACA VERIFICO, SI LA PERSONA ES JEFE DE FAMILIA Y EN EL OTRO RISNEU ERA OTRA COSA, LO INACTIVO EN $persona_anterior.
                        //SI LA PERSONA NO ES JEFE DE FAMILIA Y EN EL OTRO TAMPOCO, LO MISMO QUE LO ANTERIOR.                
                        //SI LA PERSONA ERA JEFE DE FAMILIA EN OTRO RISNEU SE INACTIVA EL RISNEU
                        $parentezco_jefe = Sds_com_configuracion::findBySql("select idconfiguracion from sds_com_configuracion where idconfiguraciontipo=11 limit 1")->one()->idconfiguracion;
                        if ($persona_anterior != null && $persona_anterior->parentezco == $parentezco_jefe) {
                            //DESACTIVO RISNEU POR SER JEFE
                            $risneu_anterior = Sds_ris_risneu::findOne($persona_anterior->idrisneu);
                            if (!$risneu_anterior->updateAttributes(['activo' => 0])) {
                                //return print_r($risneu_anterior,true);
                            }
                        } else {
                            //DESACTIVO PERSONA ANTERIOR
                            if ($persona_anterior != null) {
                                if (!$persona_anterior->updateAttributes(['activo' => 0])) {
                                    //return print_r($persona_anterior,true);
                                }
                            }
                        }
                        if ($guardado_risper) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_ris_persona', $model->idpersonarisneu, $model->getAttributes());
                            if ($ban_persona_existente == 1) {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_com_persona', $model->idpersona, $model_persona->getAttributes());
                            } else {
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_com_persona', $model->idpersona, $model_persona->getAttributes());
                            }

                            return [
                                'forceReload' => '#crud-datatable-pjax',
                                'title' => "Nueva Persona",
                                'content' => '<span class="text-success">Persona Agregada correctamente</span>',
                                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                    Html::a('Agregar Otra', ['create', 'idrisneu' => $model->idrisneu ? $model->idrisneu : null], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                            ];
                        } else {
                            /* $model_persona->addError('documento_tipo', $model->idpersona);
                                $model_persona->addError('documento', $model_persona->documento);
                                $model_persona->addError('apellido', $model_persona->apellido);
                                $model_persona->addError('nombre', $model_persona->nombre);
                                $model_persona->addError('genero', $model_persona->genero);
                                $model_persona->addError('nacionalidad', $model_persona->nacionalidad);
                                $model_persona->addError('fecha_nacimiento', $model_persona->fecha_nacimiento);
                                $model->addError('parentezco', 'idrispersona:' . $model->idpersonarisneu . ', idrisneu:' . $model->idrisneu . ', parentezco: ' . $model->parentezco); */
                            $transaction->rollBack();
                        }
                    }
                    // }
                }
            }
            return [
                'title' => "Nueva Persona",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'model_persona' => $model_persona,
                    'tipoParentezco' => $tipoParentezco,
                    'tipoSitConyugal' => $tipoSitConyugal,
                    'tipoEscolaridad' => $tipoEscolaridad,
                    'tipoUltAnioAprobado' => $tipoUltAnioAprobado,
                    'tipoEstEducativo' => $tipoEstEducativo,
                    'tipoTrabajo' => $tipoTrabajo,
                    'tipoVinculoContractual' => $tipoVinculoContractual,
                    'tipoTipoTrabajo' => $tipoTipoTrabajo,
                    'tipoDiscapacidad' => $tipoDiscapacidad,
                    'tipoEnfermedad' => $tipoEnfermedad,
                    'tipoCoberturaSalud' => $tipoCoberturaSalud,
                    'configuracionParentezco' => $configuracionParentezco,
                    'tipoDoc' => $tipoDoc,
                    'tipoGenero' => $tipoGenero,
                    'tipoGeneroAutopercibido' => $tipoGeneroAutopercibido,
                    'tipoNacionalidad' => $tipoNacionalidad,
                    'tipoCondicionHacinamiento' => $tipoCondicionHacinamiento,
                    'tipoPueblosOriginarios' => $tipoPueblosOriginarios,
                    'tipoSustancia' => $tipoSustancia,
                    'esPrimeraPersona' => $esPrimeraPersona,
                    'isCreate' => true,
                    'oficial' => $oficial
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                if ($model->save() && $model_persona->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_ris_persona', $model->idpersonarisneu, $model->getAttributes());
                    return $this->redirect(['view', 'id' => $model->idpersonarisneu]);
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'model_persona' => $model_persona,
                    'tipoParentezco' => $tipoParentezco,
                    'tipoSitConyugal' => $tipoSitConyugal,
                    'tipoEscolaridad' => $tipoEscolaridad,
                    'tipoUltAnioAprobado' => $tipoUltAnioAprobado,
                    'tipoEstEducativo' => $tipoEstEducativo,
                    'tipoTrabajo' => $tipoTrabajo,
                    'tipoVinculoContractual' => $tipoVinculoContractual,
                    'tipoTipoTrabajo' => $tipoTipoTrabajo,
                    'tipoDiscapacidad' => $tipoDiscapacidad,
                    'tipoEnfermedad' => $tipoEnfermedad,
                    'tipoCoberturaSalud' => $tipoCoberturaSalud,
                    'configuracionParentezco' => $configuracionParentezco,
                    'tipoDoc' => $tipoDoc,
                    'tipoGenero' => $tipoGenero,
                    'tipoGeneroAutopercibido' => $tipoGeneroAutopercibido,
                    'tipoNacionalidad' => $tipoNacionalidad,
                    'tipoCondicionHacinamiento' => $tipoCondicionHacinamiento,
                    'tipoPueblosOriginarios' => $tipoPueblosOriginarios,
                    'tipoSustancia' => $tipoSustancia,
                    'esPrimeraPersona' => $esPrimeraPersona,
                    'isCreate' => true,
                    'oficial' => $oficial
                ]);
            }
        }
    }

    public function actionValidar_dni($dni, $idrisneu)
    {
        //Busco la persona, si existe traigo los datos para editar, y se le va a asignar una entrada para este risneu.
        //la vinculación con el anterior va a seguir, pero se va a marcar inactivo.
        $result = array();
        if ($dni != '') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model_persona = Sds_com_persona::find()->where(["documento" => $dni])->one();
            $model = null;
            if ($model_persona != null) {
                array_push($result, $model_persona->getAttributes());
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_com_persona', $model_persona->idpersona, array());
                $model = Sds_ris_persona::find()->where(["idpersona" => $model_persona->idpersona, "activo" => 1])->one();
            }
            if ($model != null) {
                array_push($result, $model->getAttributes());
            }
        }
        return json_encode($result);
    }

    /**
     * Updates an existing Sds_ris_persona model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * 
     * Paso el idrisneu que se está modificando, por si entró en modo edición con datos que vienen de otro risneu viejo.
     */
    public function actionUpdate($id, $idrisneu = null, $oficial = false)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($idrisneu != null) {
            $model->idrisneu = $idrisneu;
        }
        $model_persona = Sds_com_persona::findOne($model->idpersona);
        $enfermedades_borrar = Sds_ris_persona_enfermedad::find()->where(["idpersonarisneu" => $model->idpersonarisneu])->all();
        $discapacidades_borrar = Sds_ris_persona_discapacidad::find()->where(["idpersonarisneu" => $model->idpersonarisneu])->all();
        $sustancias_borrar = Sds_ris_persona_sustancia::find()->where(["idpersonarisneu" => $model->idpersonarisneu])->all();

        $tipoParentezco = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_PARENTEZCO), 'idconfiguracion', 'descripcion');
        $tipoSitConyugal = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_SIT_CONYUGAL), 'idconfiguracion', 'descripcion');
        $tipoEscolaridad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ESCOLARIDAD), 'idconfiguracion', 'descripcion');
        $tipoUltAnioAprobado = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ULTIMO_ANIO_APROBADO), 'idconfiguracion', 'descripcion');
        $tipoEstEducativo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TIPO_EST_EDUCATIVO), 'idconfiguracion', 'descripcion');
        $tipoTrabajo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TRABAJO), 'idconfiguracion', 'descripcion');
        $tipoVinculoContractual = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_VINCULO_CONTRACTUAL), 'idconfiguracion', 'descripcion');
        $tipoTipoTrabajo = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TIPO_TRABAJO), 'idconfiguracion', 'descripcion');
        $tipoDiscapacidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_DISCAPACIDAD), 'idconfiguracion', 'descripcion');
        $tipoEnfermedad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_ENFERMEDAD), 'idconfiguracion', 'descripcion');
        $tipoCoberturaSalud = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_COBERTURA_SALUD), 'idconfiguracion', 'descripcion');
        $configuracionParentezco = Sds_com_configuracion::findBySql("(select idconfiguracion from sds_com_configuracion where idconfiguraciontipo=11 limit 1)")->one()->idconfiguracion;
        $tipoDoc = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TIPO_DOC), 'idconfiguracion', 'descripcion');
        $tipoGenero = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO), 'idconfiguracion', 'descripcion');
        $tipoGeneroAutopercibido = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO_AUTOPERCIBIDO), 'idconfiguracion', 'descripcion');
        $tipoNacionalidad = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD), 'idconfiguracion', 'descripcion');
        $tipoCondicionHacinamiento = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::CONDICIION_HACINAMIENTO), 'idconfiguracion', 'descripcion');
        $tipoPueblosOriginarios = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::PUEBLOS_ORIGINARIOS), 'idconfiguracion', 'descripcion');
        $tipoSustancia = ArrayHelper::map(Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::SUSTANCIA), 'idconfiguracion', 'descripcion');
        $esPrimeraPersona = false;

        $modelRisPersona = new Sds_ris_persona();
        $risPersonaJefe = $modelRisPersona->getJefeByIdRisneu($model->idrisneu);
        if ($model->idpersonarisneu == $risPersonaJefe->idpersonarisneu) {
            $esPrimeraPersona = true;
        } else {
            unset($tipoParentezco[Sds_ris_persona::ID_PARENTESCO_JEFE]);
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Persona #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'model_persona' => $model_persona,
                        'tipoParentezco' => $tipoParentezco,
                        'tipoSitConyugal' => $tipoSitConyugal,
                        'tipoEscolaridad' => $tipoEscolaridad,
                        'tipoUltAnioAprobado' => $tipoUltAnioAprobado,
                        'tipoEstEducativo' => $tipoEstEducativo,
                        'tipoTrabajo' => $tipoTrabajo,
                        'tipoVinculoContractual' => $tipoVinculoContractual,
                        'tipoTipoTrabajo' => $tipoTipoTrabajo,
                        'tipoDiscapacidad' => $tipoDiscapacidad,
                        'tipoEnfermedad' => $tipoEnfermedad,
                        'tipoCoberturaSalud' => $tipoCoberturaSalud,
                        'configuracionParentezco' => $configuracionParentezco,
                        'tipoDoc' => $tipoDoc,
                        'tipoGenero' => $tipoGenero,
                        'tipoGeneroAutopercibido' => $tipoGeneroAutopercibido,
                        'tipoNacionalidad' => $tipoNacionalidad,
                        'tipoCondicionHacinamiento' => $tipoCondicionHacinamiento,
                        'tipoPueblosOriginarios' => $tipoPueblosOriginarios,
                        'tipoSustancia' => $tipoSustancia,
                        'esPrimeraPersona' => $esPrimeraPersona,
                        'isCreate' => false,
                        'oficial' => $oficial
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model_persona->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                if ($model_persona->fecha_nacimiento != null) {
                    $model_persona->fecha_nacimiento = date('Y-m-d', strtotime(str_replace('/', '-', $model_persona->fecha_nacimiento)));
                }

                if (isset($request->post('Sds_ris_persona')['pueblo_originario_pertenece']) && $request->post('Sds_ris_persona')['pueblo_originario_pertenece'] == 0) {
                    $model->pueblo_originario_reconoce = null;
                    $model->pueblo_originario = null;
                }

                if (isset($request->post('Sds_ris_persona')['pueblo_originario_reconoce']) && $request->post('Sds_ris_persona')['pueblo_originario_reconoce'] == 0) {
                    $model->pueblo_originario = null;
                }

                //Eliminar enfermedades
                $enfermedades = $model->enfermedades != null ? $model->enfermedades : array();
                $enfermedades_count = count($enfermedades);
                if ($enfermedades_borrar != null) {
                    foreach ($enfermedades_borrar as $enfermedad) {
                        $enfermedad->deleted_at = date('Y-m-d H:i:s');
                        $enfermedad->idusuario_borra = Yii::$app->user->id;
                        if (!$enfermedad->save()) {
                            $transaction->rollBack();
                            $guardado_risper = false;
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'Sds_ris_persona_enfermedad', $enfermedad->idpersonaenfermedad, $enfermedad->getAttributes());
                        }
                    }
                }

                //Eliminar discapacidades
                $discapacidades = $model->discapacidades != null ? $model->discapacidades : array();
                $discapacidades_count = count($discapacidades);
                if ($discapacidades_borrar != null) {
                    foreach ($discapacidades_borrar as $discapacidad) {
                        $discapacidad->deleted_at = date('Y-m-d H:i:s');
                        $discapacidad->idusuario_borra = Yii::$app->user->id;
                        if (!$discapacidad->save()) {
                            $transaction->rollBack();
                            $guardado_risper = false;
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'Sds_ris_persona_discapacidad', $discapacidad->idpersonadiscapacidad, $discapacidad->getAttributes());
                        }
                    }
                }

                //Eliminar sustancias
                $sustancias = $model->sustancias != null ? $model->sustancias : array();
                $sustancias_count = count($sustancias);
                if ($sustancias_borrar != null) {
                    foreach ($sustancias_borrar as $sustancia) {
                        $sustancia->deleted_at = date('Y-m-d H:i:s');
                        $sustancia->idusuario_borra = Yii::$app->user->id;
                        if (!$sustancia->save()) {
                            $transaction->rollBack();
                            $guardado_risper = false;
                        } else {
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'Sds_ris_persona_sustancia', $sustancia->idpersonasustancia, $sustancia->getAttributes());
                        }
                    }
                }

                $guardado_persona = $model_persona->save();
                $model->idpersona = $model_persona->idpersona;
                $model->updated_at = date('Y-m-d H:i:s');
                $model->idusuario_actualiza = Yii::$app->user->id;
                $guardado_risper = $model->save();

                //Carga enfermedades
                for ($index_enf = 0; $index_enf < $enfermedades_count; $index_enf++) {
                    $persona_enfermedad = new Sds_ris_persona_enfermedad();
                    $persona_enfermedad->idpersonarisneu = $model->idpersonarisneu;
                    $persona_enfermedad->enfermedad = $enfermedades[$index_enf];
                    $persona_enfermedad->created_at = date('Y-m-d H:i:s');
                    $persona_enfermedad->idusuario_carga = Yii::$app->user->id;
                    if (!$persona_enfermedad->save()) {
                        $transaction->rollBack();
                        $guardado_risper = false;
                    } else {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'Sds_ris_persona_enfermedad', $persona_enfermedad->idpersonaenfermedad, $persona_enfermedad->getAttributes());
                    }
                }

                //Carga discapacidades
                for ($index_disc = 0; $index_disc < $discapacidades_count; $index_disc++) {
                    $persona_discapacidad = new Sds_ris_persona_discapacidad();
                    $persona_discapacidad->idpersonarisneu = $model->idpersonarisneu;
                    $persona_discapacidad->discapacidad = $discapacidades[$index_disc];
                    $persona_discapacidad->created_at = date('Y-m-d H:i:s');
                    $persona_discapacidad->idusuario_carga = Yii::$app->user->id;
                    if (!$persona_discapacidad->save()) {
                        $transaction->rollBack();
                        $guardado_risper = false;
                    } else {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'Sds_ris_persona_discapacidad', $persona_discapacidad->idpersonadiscapacidad, $persona_discapacidad->getAttributes());
                    }
                }

                //Carga sustancias
                for ($index_sus = 0; $index_sus < $sustancias_count; $index_sus++) {
                    $persona_sustancia = new Sds_ris_persona_sustancia();
                    $persona_sustancia->idpersonarisneu = $model->idpersonarisneu;
                    $persona_sustancia->sustancia = $sustancias[$index_sus];
                    $persona_sustancia->created_at = date('Y-m-d H:i:s');
                    $persona_sustancia->idusuario_carga = Yii::$app->user->id;
                    if (!$persona_sustancia->save()) {
                        $transaction->rollBack();
                        $guardado_risper = false;
                    } else {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'Sds_ris_persona_sustancia', $persona_sustancia->idpersonasustancia, $persona_sustancia->getAttributes());
                    }
                }

                if ($guardado_persona && $guardado_risper) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_ris_persona', $model->idpersonarisneu, $model->getAttributes());

                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Nueva Persona",
                        'content' => '<span class="text-success">Se actualizó correctamente a la persona</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Agregar Otra', ['create', 'idrisneu' => $model->idrisneu ? $model->idrisneu : null], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                } else {
                    /* $model_persona->addError('documento_tipo', $model->idpersona);
                    $model_persona->addError('documento', $model_persona->documento);
                    $model_persona->addError('apellido', $model_persona->apellido);
                    $model_persona->addError('nombre', $model_persona->nombre);
                    $model_persona->addError('genero', $model_persona->genero);
                    $model_persona->addError('nacionalidad', $model_persona->nacionalidad);
                    $model_persona->addError('fecha_nacimiento', $model_persona->fecha_nacimiento);
                    $model->addError('parentezco', 'idrispersona:' . $model->idpersonarisneu . ', idrisneu:' . $model->idrisneu . ', parentezco: ' . $model->parentezco); */
                    $transaction->rollBack();
                }
            }
            return [
                'title' => "Actualizar Persona #" . $id,
                'content' => $this->renderAjax('update', [
                    'model' => $model,
                    'model_persona' => $model_persona,
                    'tipoParentezco' => $tipoParentezco,
                    'tipoSitConyugal' => $tipoSitConyugal,
                    'tipoEscolaridad' => $tipoEscolaridad,
                    'tipoUltAnioAprobado' => $tipoUltAnioAprobado,
                    'tipoEstEducativo' => $tipoEstEducativo,
                    'tipoTrabajo' => $tipoTrabajo,
                    'tipoVinculoContractual' => $tipoVinculoContractual,
                    'tipoTipoTrabajo' => $tipoTipoTrabajo,
                    'tipoDiscapacidad' => $tipoDiscapacidad,
                    'tipoEnfermedad' => $tipoEnfermedad,
                    'tipoCoberturaSalud' => $tipoCoberturaSalud,
                    'configuracionParentezco' => $configuracionParentezco,
                    'tipoDoc' => $tipoDoc,
                    'tipoGenero' => $tipoGenero,
                    'tipoGeneroAutopercibido' => $tipoGeneroAutopercibido,
                    'tipoNacionalidad' => $tipoNacionalidad,
                    'tipoCondicionHacinamiento' => $tipoCondicionHacinamiento,
                    'tipoPueblosOriginarios' => $tipoPueblosOriginarios,
                    'tipoSustancia' => $tipoSustancia,
                    'esPrimeraPersona' => $esPrimeraPersona,
                    'isCreate' => false,
                    'oficial' => $oficial
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_ris_persona', $model->idpersonarisneu, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->idpersonarisneu]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'model_persona' => $model_persona,
                    'tipoParentezco' => $tipoParentezco,
                    'tipoSitConyugal' => $tipoSitConyugal,
                    'tipoEscolaridad' => $tipoEscolaridad,
                    'tipoUltAnioAprobado' => $tipoUltAnioAprobado,
                    'tipoEstEducativo' => $tipoEstEducativo,
                    'tipoTrabajo' => $tipoTrabajo,
                    'tipoVinculoContractual' => $tipoVinculoContractual,
                    'tipoTipoTrabajo' => $tipoTipoTrabajo,
                    'tipoDiscapacidad' => $tipoDiscapacidad,
                    'tipoEnfermedad' => $tipoEnfermedad,
                    'tipoCoberturaSalud' => $tipoCoberturaSalud,
                    'configuracionParentezco' => $configuracionParentezco,
                    'tipoDoc' => $tipoDoc,
                    'tipoGenero' => $tipoGenero,
                    'tipoGeneroAutopercibido' => $tipoGeneroAutopercibido,
                    'tipoNacionalidad' => $tipoNacionalidad,
                    'tipoCondicionHacinamiento' => $tipoCondicionHacinamiento,
                    'tipoPueblosOriginarios' => $tipoPueblosOriginarios,
                    'tipoSustancia' => $tipoSustancia,
                    'esPrimeraPersona' => $esPrimeraPersona,
                    'isCreate' => false,
                    'oficial' => $oficial
                ]);
            }
        }
    }

    /**
     * Delete an existing Sds_ris_persona model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->activo = 0;
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->idusuario_borra = Yii::$app->user->id;
        if ($model->update()) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_ris_persona', $id, $model->getAttributes());
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
     * Finds the Sds_ris_persona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sds_ris_persona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sds_ris_persona::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
