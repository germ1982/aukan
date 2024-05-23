<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_org_padron;
use app\models\Mds_org_padronSearch;
use app\models\Mds_pad_padron;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_persona;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * Mds_org_padronController implements the CRUD actions for Mds_org_padron model.
 */
class Mds_org_padronController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'logout', 'generar_contactos', 'actualizar', 'importar'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'logout', 'generar_contactos', 'actualizar', 'importar'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ORG_PADRON,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_org_padron models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_org_padronSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_org_padron model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Mds_org_padron #" . $id,
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
     * Creates a new Mds_org_padron model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionImportar()
    {
        $model = new Mds_org_padron();
        $model->mes = 0 + date("m");
        $model->anio = date("Y");
        Yii::$app->response->format = Response::FORMAT_JSON;
        $registros = Yii::$app->request->post('registros');
        $transaction = Yii::$app->db->beginTransaction();
        if ($registros != null) {
            $cant_guardados = 0;
            $errores = array();
            $registros = json_decode($registros);
            foreach ($registros as $registro) {
                $padron = new Mds_org_padron();
                $padron->mes = $registro->mes;
                $padron->anio = $registro->anio;
                $padron->legajo = $registro->legajo;
                $padron->idunidadoperativa = $registro->idunidadoperativa;
                $padron->categoria = $registro->categoria;
                $padron->apellido_nombre = $registro->apenom;
                $padron->sexo = $registro->sexo == 'F' ? 0 : 1;
                $padron->dni = strval($registro->dni);
                $padron->cuil = $registro->cuil;
                $padron->fecha_nacimiento = date('Y-m-d', strtotime(str_replace('/', '-', $registro->fecha_nac)));
                $padron->fecha_ingreso = date('Y-m-d', strtotime(str_replace('/', '-', $registro->fecha_ingr)));
                $padron->antiguedad_administrativa = $registro->ant_adm;
                $padron->antiguedad_privada = $registro->ant_priv;
                $padron->antiguedad_total = $registro->ant_total;
                $padron->eventual = ($registro->pr == 28 || $registro->pr == 29 ? 1 : 0);
                $padron->pr = $registro->pr;
                $padron->titulo = $registro->titulo;
                if ($padron->save()) {
                    $cant_guardados++;
                } else {
                    $errores_registro = $padron->getErrors();
                    if (!in_array($errores_registro, $errores)) {
                        array_push($errores, $padron->getErrors());
                    }
                }
            }
            if ($cant_guardados > 0) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
            return array('guardados' => $cant_guardados, 'errores' => $errores);
        } else {
            return [
                'title' => "Importar Excel de Padrón",

                'content' => $this->renderAjax('importar', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Importar', ['id' => 'btn_importar', 'class' => 'btn btn-primary'])
            ];
        }
    }

    public function actionActualizar($procesar = 0)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($procesar > 0) {
            $registros = Mds_org_contacto::findBySql("select contacto.idcontacto,contacto.mail,
            contacto.telefono,contacto.iddispositivo,(temp_padron.dni is not null) activo,
            ifnull(temp_padron.legajo,contacto.legajo) legajo, contacto.idpersona,
            contacto.rotativo,contacto.eventual,contacto.acompaniante,contacto.interno,
            contacto.perfil,contacto.idoficina,contacto.actividad,contacto.esencial,contacto.planta_politica,
            ifnull((select idconfiguracion from sds_com_configuracion cat
            where cat.descripcion=temp_padron.categoria),contacto.categoria) categoria,
            contacto.ubicacion_fisica,if(pr is not null and pr=32,4,contacto.tipo_contratacion) tipo_contratacion,
            ifnull(temp_padron.fecha_ingreso,contacto.fecha_ingreso) fecha_ingreso,
            ifnull(temp_padron.fecha_nacimiento,pers.fecha_nacimiento) fecha_nacimiento,
            ifnull(temp_padron.idunidadoperativa,contacto.unidad_operativa) unidad_operativa,
            ifnull(temp_padron.cuil,contacto.cuil) cuil,
            ifnull(temp_padron.antiguedad_administrativa,contacto.antiguedad_administrativa) antiguedad_administrativa,
            ifnull(temp_padron.titulo,contacto.titulo) titulo,
            ifnull(temp_padron.antiguedad_privada,contacto.antiguedad_privada) antiguedad_privada,
            ifnull(temp_padron.antiguedad_total,contacto.antiguedad_total) antiguedad_total,
            TRIM(SUBSTRING_INDEX(temp_padron.apellido_nombre, \",\", 1)) apellido,
            TRIM(SUBSTRING_INDEX(temp_padron.apellido_nombre, \",\", -1)) nombre,
            temp_padron.sexo,
            ifnull(temp_padron.eventual,contacto.eventual) eventual,
            ifnull(temp_padron.dni,pers.documento) documento,
            contacto.fecha_ingreso_planta,contacto.turno_rotativo
            from mds_org_contacto contacto
            join sds_com_persona pers on pers.idpersona=contacto.idpersona
            left join (select padron.legajo,idunidadoperativa,categoria,apellido_nombre,
            sexo,dni,cuil,fecha_nacimiento,
            fecha_ingreso,antiguedad_administrativa,antiguedad_privada,antiguedad_total,eventual,pr,titulo 
            from mds_org_padron padron
            join (select mes,anio
            from mds_org_padron
            group by mes,anio
            order by anio desc,mes desc limit 1) temp
            on (temp.mes=padron.mes and temp.anio=padron.anio))
            temp_padron on pers.documento=temp_padron.dni")->all();
            $transaction = Yii::$app->db->beginTransaction();
            $cant_guardados = 0;
            $errores = array();
            $guardados = array();
            foreach ($registros as $contacto) {
                //Legajo, idunidadoperativa, Categoría, Apellido y Nombre, Sexo, DNI, CUIL, Fecha Nac, 
                //Fecha de Ingreso, Antiguedad Administrativa, Antiguedad Privada, Antiguedad total, Eventual
                $contacto_actualizado = Mds_org_contacto::findOne($contacto->idcontacto);
                $contacto_actualizado->setAttributes($contacto->attributes);
                $categoria = Sds_com_configuracion::findOne($contacto_actualizado->categoria);
                $contacto_actualizado->planta_politica = 0;
                if ($categoria != null) {
                    if ($categoria->idconfiguraciontipo == Sds_com_configuracion_tipo::TIPO_CATEGORIA_CONTRATO) {
                        $contacto_actualizado->tipo_contratacion = Mds_org_contacto::TIPO_CONTRATACION_CONTRATO;
                    } else if ($categoria->idconfiguraciontipo == Sds_com_configuracion_tipo::TIPO_CATEGORIA_CONVENIO) {
                        $contacto_actualizado->tipo_contratacion = $contacto_actualizado->eventual ?
                            Mds_org_contacto::TIPO_CONTRATACION_EVENTUALES : Mds_org_contacto::TIPO_CONTRATACION_PLANTA_PERMANENTE;
                    } else {
                        if ($contacto_actualizado->tipo_contratacion != Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA_PURA) {
                            $contacto_actualizado->tipo_contratacion = Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA;
                        }
                        $contacto_actualizado->planta_politica = 1;
                    }
                }
                switch ($contacto_actualizado->unidad_operativa) {
                    case 1:
                        $contacto_actualizado->unidad_operativa = 2134;
                        break;
                    case 2:
                        $contacto_actualizado->unidad_operativa = 2135;
                        break;
                    case 3:
                        $contacto_actualizado->unidad_operativa = 2136;
                        break;
                    case 4:
                        $contacto_actualizado->unidad_operativa = 2137;
                        break;
                }
                if ($contacto->nombre != null && $contacto->apellido != null) {
                    $persona_actualizada = Sds_com_persona::findOne($contacto->idpersona);
                    $persona_actualizada->fecha_nacimiento = $contacto->fecha_nacimiento;
                    /* $persona_actualizada->apellido = $contacto->apellido;
                    $persona_actualizada->nombre = $contacto->nombre; */
                    $persona_actualizada->genero = $contacto->sexo == 1 ? 82 : 81;
                    $persona_actualizada->documento = intval($contacto->documento);
                    if (!$persona_actualizada->save()) {
                        $errores_registro = $persona_actualizada->getErrors();
                        array_push($errores_registro, 'Error con DNI: ' . $persona_actualizada->documento);
                        if (!in_array($errores_registro, $errores)) {
                            array_push($errores, $errores_registro);
                        }
                    }
                }
                if (($contacto->fecha_ingreso_planta == null) && ($contacto->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA || $contacto->tipo_contratacion == Mds_org_contacto::TIPO_CONTRATACION_PLANTA_PERMANENTE)) {
                    if ($contacto->fecha_ingreso != null) {
                        $contacto_actualizado->fecha_ingreso_planta = $contacto->fecha_ingreso;
                    }
                }
                if (is_null($contacto_actualizado->ficha)) {
                    $contacto_actualizado->ficha = 1;
                }
                if (is_null($contacto_actualizado->retenido)) {
                    $contacto_actualizado->retenido = 0;
                }
                if ($contacto_actualizado->save(false)) {
                    $cant_guardados++;
                    array_push($guardados, $contacto_actualizado);
                } else {
                    $errores_registro = $contacto->getErrors();
                    if (!in_array($errores_registro, $errores)) {
                        array_push($errores, $errores_registro);
                    }
                }
            }
            if ($cant_guardados > 0) {
                $transaction->commit();
                $padrones_sin_contacto = Mds_org_padron::findBySql("select * 
                from mds_org_padron padron
                left join sds_com_persona persona on padron.dni=persona.documento
                left join mds_org_contacto contacto on contacto.idpersona=persona.idpersona
                where idcontacto is null")->all();
                return array(
                    'guardados' => $cant_guardados, 'resultado' => $guardados, 'errores' => $errores,
                    'pendientes' => $padrones_sin_contacto
                );
            } else {
                $transaction->rollBack();
            }
        } else {
            return [
                'title' => "Actualizar Datos de Contacto",
                'content' => $this->renderAjax('actualizar', []),
                'footer' => Html::button('Cancelar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Actualizar Datos', ['id' => 'btn_actualizar', 'class' => 'btn btn-primary'])
            ];
        }
    }

    public function actionGenerar_contactos()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $errores = array();
        $padrones_sin_contacto = Mds_org_padron::findBySql("select padron.* 
                from mds_org_padron padron
                left join sds_com_persona persona on padron.dni=persona.documento
                left join mds_org_contacto contacto on contacto.idpersona=persona.idpersona
                where idcontacto is null 
                group by dni")->all();
        $contactos_generados = array();
        $sin_generar = array();
        foreach ($padrones_sin_contacto as $padron_sc) {
            $generado = true;
            $contacto = new Mds_org_contacto();
            $contacto->mail = "s@d.com";
            $contacto->telefono = "";
            $contacto->iddispositivo = 377;
            $contacto->activo = 1;
            $contacto->legajo = $padron_sc->legajo;
            $contacto->rotativo = 0;
            $contacto->eventual = $padron_sc->eventual;
            $contacto->acompaniante = 0;
            $contacto->interno = 1;
            $contacto->perfil = null;
            $contacto->idoficina = null;
            $contacto->actividad = null;
            $contacto->planta_politica = 0;
            $contacto->titulo = $padron_sc->titulo;
            $categoria = Sds_com_configuracion::find()->where(["descripcion" => $padron_sc->categoria])->one();
            if ($categoria != null) {
                $contacto->categoria = $categoria->idconfiguracion;
                /* if ($categoria->idconfiguraciontipo == Sds_com_configuracion_tipo::TIPO_CATEGORIA_CONTRATO) {
                    $contacto->tipo_contratacion = Mds_org_contacto::TIPO_CONTRATACION_CONTRATO;
                } else if ($categoria->idconfiguraciontipo == Sds_com_configuracion_tipo::TIPO_CATEGORIA_CONVENIO) {
                    $contacto->tipo_contratacion = $contacto->eventual ?
                        Mds_org_contacto::TIPO_CONTRATACION_EVENTUALES : Mds_org_contacto::TIPO_CONTRATACION_PLANTA_PERMANENTE;
                } else {
                    $contacto->tipo_contratacion = $padron_sc->pr != 32 
                        ? Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA
                        : Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA_PURA;
                    $contacto->planta_politica = 1;
                } */
                $tipo_contratacion = null;
                switch ($padron_sc->pr) {
                    case 10:
                    case 21:
                        $tipo_contratacion = Mds_org_contacto::TIPO_CONTRATACION_PLANTA_PERMANENTE;
                        break;
                    case 26:
                        $tipo_contratacion = Mds_org_contacto::TIPO_CONTRATACION_CONTRATO;
                        break;
                    case 28:
                    case 29:
                        $tipo_contratacion = Mds_org_contacto::TIPO_CONTRATACION_EVENTUALES;
                        break;
                    case 31:
                    case 32:
                        $contacto->planta_politica = 1;
                        $tipo_contratacion = $padron_sc->pr != 32
                            ? Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA
                            : Mds_org_contacto::TIPO_CONTRATACION_PLANTA_POLITICA_PURA;
                        break;
                }
                $contacto->tipo_contratacion = $tipo_contratacion;
            }
            $contacto->fecha_ingreso = $padron_sc->fecha_ingreso;
            switch ($padron_sc->idunidadoperativa) {
                case 1:
                    $contacto->unidad_operativa = 2134;
                    break;
                case 2:
                    $contacto->unidad_operativa = 2135;
                    break;
                case 3:
                    $contacto->unidad_operativa = 2136;
                    break;
                case 4:
                    $contacto->unidad_operativa = 2137;
                    break;
            }
            $contacto->cuil = $padron_sc->cuil;
            $contacto->antiguedad_administrativa = $padron_sc->antiguedad_administrativa;
            $contacto->antiguedad_privada = $padron_sc->antiguedad_privada;
            $contacto->antiguedad_total = $padron_sc->antiguedad_total;
            $persona = Sds_com_persona::find()->where(["documento" => $padron_sc->dni])->one();
            if ($persona == null) {
                $persona = new Sds_com_persona();
                $persona->documento_tipo = 83;
                $persona->fecha_nacimiento = $padron_sc->fecha_nacimiento;
                $persona->documento = trim($padron_sc->dni, " ");
                $persona->nacionalidad = 70;
                $persona->genero = $padron_sc->sexo == 1 ? 82 : 81;
                $persona->apellido = trim(substr($padron_sc->apellido_nombre, 0, strpos($padron_sc->apellido_nombre, ',')));
                $persona->nombre = trim(substr($padron_sc->apellido_nombre, strpos($padron_sc->apellido_nombre, ',') + 1, strlen($padron_sc->apellido_nombre)));
                $persona->conviviente = 0;
                if (!$persona->save()) {
                    $errores_registro = $persona->getErrors();
                    if (!in_array($errores_registro, $errores)) {
                        array_push($errores, $errores_registro);
                        $generado = false;
                    }
                }
            }
            if ($persona->idpersona != null) {
                $contacto->idpersona = $persona->idpersona;
                if ($contacto->save(false)) {
                    array_push($contactos_generados, $contacto);
                } else {
                    $errores_registro = $persona->getErrors();
                    if (!in_array($errores_registro, $errores)) {
                        array_push($errores, $errores_registro);
                        $generado = false;
                    }
                }
            }
            if (!$generado) {
                array_push($sin_generar, $padron_sc);
            }
        }
        return array(
            'generados' => $contactos_generados, 'sin_generar' => $sin_generar, 'errores' => $errores
        );
    }

    /**
     * Updates an existing Mds_org_padron model.
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
                    'title' => "Update Mds_org_padron #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Mds_org_padron #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Mds_org_padron #" . $id,
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
                return $this->redirect(['view', 'id' => $model->idpadron]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_org_padron model.
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
     * Delete multiple existing Mds_org_padron model.
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
    /* Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else { */
    /*
            *   Process for non-ajax request
            */
    /* return $this->redirect(['index']);
        }
    } */

    /**
     * Finds the Mds_org_padron model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_org_padron the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_org_padron::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
