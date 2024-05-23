<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_cap_capacitacion;
use app\models\Mds_cap_capacitacionSearch;
use app\models\Mds_org_organismo;
use app\models\Mds_org_organismo_externo;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_sys_log;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Mds_cap_capacitacionController implements the CRUD actions for Mds_cap_capacitacion model.
 */
class Mds_cap_capacitacionController extends Controller
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
                            Mds_seg_item::MODULO_CAP_CAPACITACION,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_cap_capacitacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_cap_capacitacionSearch();
        $modelForm = new \app\models\LoginForm();

        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $modelForm,
            ]);
        }

        $idcontacto  = Yii::$app->user->identity->idcontacto;
        $idOrganismo = null;
        $idOrganismoExterno  = Yii::$app->user->identity->externo;

        // Verificamos que tenga un contacto asociado (usuario del MDSYT) o un idOrganismoExterno asociado
        if ((!$idcontacto && !$idOrganismoExterno)) {
            Yii::$app->session->setFlash('error_modulo', "El usuario debe tener un contacto asignado o participar de un organismo externo. <br>Comuníquese con un administrador.");
            return Yii::$app->getResponse()->redirect([
                'site',
            ]);
        }

        if ($idcontacto) {
            // Tiene contacto - Usuario MDSYT
            $searchModel->idorganismo = Mds_org_organismo::find()->where(
                "   idorganismo in (select idorganismo
                    from mds_org_contacto contacto,mds_org_dispositivo disp
                    where disp.iddispositivo=contacto.iddispositivo and idcontacto = $idcontacto)"
            )->orderBy(['descripcion' => SORT_ASC])->one()->idorganismo;
            $idOrganismo =  $searchModel->idorganismo;
        } else {
            // No tiene contacto - Usuario externo
            $searchModel->idorganismoexterno = $usuario->externo;
        }


        if ($idOrganismo || $idOrganismoExterno) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cap_capacitacion', null, array());

            if ($idOrganismo) {
                $filterOrganismos = $this->getFilterOrganismos("INTERNO", $idOrganismo, $idusuario, $idcontacto);
                $interno = true;
            } else {
                $filterOrganismos = $this->getFilterOrganismos("EXTERNO", $idOrganismoExterno, $idusuario, null);
                $interno = false;
            }

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'filterOrganismos' => $filterOrganismos,
                'interno' => $interno
            ]);
        } else {
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $modelForm,
            ]);
        }
    }


    /**
     * Displays a single Mds_cap_capacitacion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cap_capacitacion', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Capacitación Numero: " . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Mds_cap_capacitacion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_cap_capacitacion();
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $model->idusuario = $usuario->idusuario;

        if ($usuario->idcontacto) {
            $idcontacto = $usuario->idcontacto;
            $organismo = Mds_org_organismo::find()->where(
                "idorganismo in (select idorganismo
                from mds_org_contacto contacto,mds_org_dispositivo disp
                where
                disp.iddispositivo=contacto.iddispositivo
                and idcontacto = $idcontacto)"
            )->one();
            $model->idorganismo = $organismo->idorganismo;
        } else {
            $organismoExterno = Mds_org_organismo_externo::findOne($usuario->externo);
            $model->idorganismoexterno = $organismoExterno->idorganismoexterno;
        }

        if ($model->idorganismo) {
            $filterOrganismos = $this->getFilterOrganismos("INTERNO", $model->idorganismo, $idusuario, $idcontacto);
            $interno = true;
        } else {
            $filterOrganismos = $this->getFilterOrganismos("EXTERNO", $model->idorganismoexterno, $idusuario, null);
            $interno = false;
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($request->isGet) {
                return [
                    'title' => "Nueva Capacitación",
                    /*                     'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]), */
                    'content' => $this->renderAjax('create', ['model' => $model, 'filterOrganismos' => $filterOrganismos, 'interno' => $interno]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])

                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_cap_capacitacion', $model->idcapacitacion, $model->getAttributes());
                return [
                    'title' => "Nueva Capacitacion",
                    'content' => '<span class="text-success">Capacitacion creada exitosamente</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    //para que salga bien cuando guarda se comenta lo de abajo y se agrega lo de arriba
                    /*                     'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Nueva Capacitación",
                    // 'content'=>'<span class="text-success">Create Mds_cap_capacitacion success</span>', 
                    'content' => $this->renderAjax('create', ['model' => $model,]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal", 'id' => 'btnCerrar']).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote']) */

                ];
            } else {
                return [
                    'title' => "Nueva Capacitación",
                    'content' => $this->renderAjax('create', ['model' => $model, 'filterOrganismos' => $filterOrganismos, 'interno' => $interno]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])

                ];
            }
        } else {

            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idcapacitacion]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'filterOrganismos' => $filterOrganismos,
                    'interno' => $interno
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_cap_capacitacion model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $model->idusuario = $usuario->idusuario;

        if ($usuario->idcontacto) {
            $idcontacto = $usuario->idcontacto;
            $organismo = Mds_org_organismo::find()->where(
                "idorganismo in (select idorganismo
                from mds_org_contacto contacto,mds_org_dispositivo disp
                where
                disp.iddispositivo=contacto.iddispositivo
                and idcontacto = $idcontacto)"
            )->one();
            $model->idorganismo = $organismo->idorganismo;
        } else {
            $organismoExterno = Mds_org_organismo_externo::findOne($usuario->externo);
            $model->idorganismoexterno = $organismoExterno->idorganismoexterno;
        }

        if ($model->idorganismo) {
            $filterOrganismos = $this->getFilterOrganismos("INTERNO", $model->idorganismo, $idusuario, $idcontacto);
            $interno = true;
        } else {
            $filterOrganismos = $this->getFilterOrganismos("EXTERNO", $model->idorganismoexterno, $idusuario, null);
            $interno = false;
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Editar Capacitación Numero: " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'filterOrganismos' => $filterOrganismos,
                        'interno' => $interno
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_cap_capacitacion', $model->idcapacitacion, $model->getAttributes());
                return [
                    'title' => "Capacitacion Numero" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => "Editar Capacitación Numero: " . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                        'filterOrganismos' => $filterOrganismos,
                        'interno' => $interno
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idcapacitacion]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'filterOrganismos' => $filterOrganismos,
                    'interno' => $interno
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_cap_capacitacion model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_cap_capacitacion', $id, $model->getAttributes());
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
     * Finds the Mds_cap_capacitacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_cap_capacitacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_cap_capacitacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Arma el select para filtrar los organismos. Recibe un tipo (INTERNO/EXTERNO) y el ID del organismo
     */
    protected function getFilterOrganismos($tipo, $id, $idusuario = null, $idcontacto = null)
    {

        $itemGlobal = Mds_seg_item::MODULO_CAP_GLOBAL;
        $permisos = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                        idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario AND iditem = $itemGlobal)")->all();
        $permiso_global = 0;
        if ($permisos && count($permisos) > 0) {
            $permiso_global = 1;
        }

        if ($tipo === "INTERNO") {
            $filterOrganismos = Mds_org_organismo::find()->where("idorganismo in (select disp.idorganismo
            from mds_org_contacto contacto,
            mds_org_dispositivo disp
            where disp.iddispositivo=contacto.iddispositivo
            and idcontacto=$idcontacto
            union
            select vinc.vinculacion
            from mds_org_contacto contacto,
            mds_org_dispositivo disp
            join mds_org_organismo_vinculacion vinc on vinc.idorganismo=disp.idorganismo
            where disp.iddispositivo=contacto.iddispositivo
            and idcontacto=$idcontacto) or 1=$permiso_global")->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
            $filterOrganismos = ArrayHelper::map($filterOrganismos, 'idorganismo', 'descripcion');
        } else {
            // EXTERNO
            $filterOrganismos =  Mds_org_organismo_externo::find()->where(['idorganismoexterno' => $id])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
            $filterOrganismos = ArrayHelper::map($filterOrganismos, 'idorganismoexterno', 'descripcion');
        }

        return $filterOrganismos;
    }
}
