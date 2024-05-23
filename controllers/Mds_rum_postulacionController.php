<?php

namespace app\controllers;

use Yii;
use app\models\Mds_rum_oferta_laboral;
use app\models\Mds_rum_empleador;
use app\models\Mds_rum_postulacion;
use app\models\Sds_com_persona;
use app\models\Sds_com_configuracion;

use app\models\Mds_rum_postulacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_seg_usuario_rol;
use app\components\AccessRule;
use app\models\Mds_rum_persona;
use app\widgets;
use yii\filters\AccessControl;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use app\models\Mds_sys_log;

date_default_timezone_set('America/Argentina/Buenos_Aires');
/**
 * Mds_rum_postulacionController implements the CRUD actions for Mds_rum_postulacion model.
 */
class Mds_rum_postulacionController extends Controller
{
    /**
     * @inheritdoc
     */
    /*  public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }*/

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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'index_postulados','view3','create2','postulacion','update2','Notificar_estado','postular'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'index_postulados','view3','create2','postulacion','update2','Notificar_estado','postular'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_RUM_POSTULACION,
                            Mds_seg_item::MODULO_RUM_OFERTA_LABORAL,
                            
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_rum_postulacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_rum_postulacionSearch();
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_postulacion', null, array());
        $un_rol_usuario = Mds_seg_usuario_rol::find()
            ->where(['idusuario' => $usuario->idusuario])
            ->andWhere(["idrol" => 38])
            ->one();
        if ($un_rol_usuario == null) {
        } else {
            if ($un_rol_usuario->idrol == 38) //id de la tabla mds_seg_rol para el rol Rum Empleador
            {
                //1. buscamos las empresas del usuario actual

                $empresas_usuario = Mds_rum_empleador::find()
                    ->where(['idpersona' => $usuario->idusuario])
                    ->all();
                //2. buscamos todas las ofertas laborales de esas empresas
                $post_final = array();
                $i = 0;
                foreach ($empresas_usuario as $una_emp) {
                    $post_final[$i] = $una_emp->id;
                    $i++;
                }
                $ofertas_laborales = Mds_rum_oferta_laboral::find()
                    ->where(['in', 'id_empleador', $post_final])
                    ->all();
                //3. buscamos todas las postulaciones de estas ofertas laborales
                $post_final2 = array();
                $i = 0;
                foreach ($ofertas_laborales as $una_of) {
                    $post_final2[$i] = $una_of->id;
                    $i++;
                }

                $dataProvider->query->where(['in', 'id_oferta', $post_final2]);
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionIndex_postulados($id_oferta = 0) //Postulantes para una oferta laboral
    {
        $searchModel = new Mds_rum_postulacionSearch();
        $searchModel->id_oferta = $id_oferta;
        $una_oferta = Mds_rum_oferta_laboral::findOne($id_oferta);

        $unafecha = explode("-", $una_oferta->fecha_publicacion);
        $fecha_publicacion = trim($unafecha[2]) . "/" . trim($unafecha[1]) . "/" . trim($unafecha[0]);
        $titulo = "RUMBO::Postulantes a la Oferta Laboral:<br>" . $una_oferta->titulo . " (" . $fecha_publicacion . ")";
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_postulacion', null, array('idoferta' => $id_oferta));
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'title' => $titulo,
            'content' => $this->renderAjax('index_postulados', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
        ];
    }

    /**
     * Displays a single Mds_rum_postulacion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) // ver informacion de una postulacion determinada
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_postulacion', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "RUMBO:: Ver Postulación",
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
    public function actionView3($id, $id_oferta) // Dada una oferta laboral, vemos las postulaciones de la misma
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $el_usuario = Yii::$app->user->identity;
        // analizando el rol
        $un_rol_usuario = Mds_seg_usuario_rol::find()
            ->where(['idusuario' => $el_usuario->idusuario])
            ->andWhere(["idrol" => 38])
            ->one();
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_postulacion', $id, array('idoferta' => $id_oferta));
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($un_rol_usuario == null) {
                return [
                    'title' => "RUMBO:: Ver Postulación ",
                    'content' => $this->renderAjax('view3', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer' => Html::a(
                        ' Volver',
                        ['index_postulados', 'id_oferta' => $id_oferta],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .
                        //'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        Html::a('Editar', ['update2', 'id' => $id, 'id_oferta' => $id_oferta], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else // es un admin empresa
            {
                return [
                    'title' => "RUMBO:: Ver Postulación ",
                    'content' => $this->renderAjax('view3', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer' => Html::a(
                        ' Volver',
                        ['index_postulados', 'id_oferta' => $id_oferta],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    )
                    //'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).

                ];
            }
        } else {
            return $this->render('view3', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Mds_rum_postulacion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_rum_postulacion();


        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "RUMBO:: Nueva Postulación",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {

                $fechapost = strftime("%Y-%m-%d", time());
                $horapost = strftime("%H:%M:%S", time());
                $model->fecha_post = $fechapost;
                $model->hora_post = $horapost;
                $model->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_postulacion', $model->id, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "RUMBO:: Nueva Postulación",
                    'content' => '<span class="text-success">Nueva Postulación Creada Exitosamente</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Crear más', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "RUMBO:: Nueva Postulación",
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_postulacion', $model->id, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionCreate2($id_oferta)//Postular una persona a la Oferta Laboral
    {
        $request = Yii::$app->request;
        $model = new Mds_rum_postulacion();
        $model->id_oferta = $id_oferta;
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            $una_oferta = Mds_rum_oferta_laboral::findOne($id_oferta);
            $unafecha = explode("-", $una_oferta->fecha_publicacion);
            $fecha_publicacion = trim($unafecha[2]) . "/" . trim($unafecha[1]) . "/" . trim($unafecha[0]);
            $titulo = "RUMBO::Postular una persona a la Oferta Laboral:<br>" . $una_oferta->titulo . " (" . $fecha_publicacion . ")";

            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => $titulo,
                    'content' => $this->renderAjax('create2', [
                        'model' => $model,
                    ]),
                    //'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    'footer' => Html::a(
                        ' Volver',
                        ['index_postulados', 'id_oferta' => $id_oferta],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .

                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {

                $fechapost = strftime("%Y-%m-%d", time());
                $horapost = strftime("%H:%M:%S", time());
                $model->fecha_post = $fechapost;
                $model->hora_post = $horapost;
                $model->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_postulacion', $model->id, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => $titulo,
                    'content' => '<span class="text-success">Nueva Postulación Creada Exitosamente</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Crear más', ['create2', 'id_oferta' => $id_oferta], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => $titulo,
                    'content' => $this->renderAjax('create2', [
                        'model' => $model,
                    ]),
                    //'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    'footer' => Html::a(
                        ' Volver',
                        ['index_postulados', 'id_oferta' => $id_oferta],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_postulacion', $model->id, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionPostulacion()
    {
        $request = Yii::$app->request;
        $model = new Mds_rum_postulacion();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "RUMBO:: Nueva Postulación",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])

                ];
            } else if ($model->load($request->post())) {
                $fechapost = strftime("%Y-%m-%d", time());
                $horapost = strftime("%H:%M:%S", time());
                $model->fecha_post = $fechapost;
                $model->hora_post = $horapost;
                $model->save();
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_postulacion', $model->id, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "RUMBO:: Nueva Postulación",
                    'content' => '<span class="text-success">Nueva Postulación Creada Exitosamente</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Crear más', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                ];
            } else {
                return [
                    'title' => "RUMBO:: Nueva Postulación",
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
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing Mds_rum_postulacion model.
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
                    'title' => "RUMBO:: Editar Postulación",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_postulacion', $model->id, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "RUMBO:: Editar Postulación",
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "RUMBO:: Editar Postulación",
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
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_postulacion', $model->id, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }
    public function actionUpdate2($id, $id_oferta)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {

                $searchModel = new Mds_rum_postulacionSearch();

                $una_oferta = Mds_rum_oferta_laboral::findOne($id_oferta);

                $unafecha = explode("-", $una_oferta->fecha_publicacion);
                $fecha_publicacion = trim($unafecha[2]) . "/" . trim($unafecha[1]) . "/" . trim($unafecha[0]);
                $titulo = "RUMBO:: Editar Postulación:<br>" . $una_oferta->titulo . " (" . $fecha_publicacion . ")";

                return [
                    'title' => $titulo,
                    'content' => $this->renderAjax('update2', [
                        'model' => $model,
                    ]),
                    'footer' => Html::a(
                        ' Volver',
                        ['index_postulados', 'id_oferta' => $id_oferta],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .
                        //'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_postulacion', $model->id, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "RUMBO:: Ver Postulación",
                    'content' => $this->renderAjax('view3', [
                        'model' => $model,
                    ]),
                    'footer' => Html::a(
                        ' Volver',
                        ['index_postulados', 'id_oferta' => $id_oferta],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .
                        //'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        Html::a('Editar', ['update2', 'id' => $id, 'id_oferta' => $id_oferta], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "RUMBO:: Editar Postulación",
                    'content' => $this->renderAjax('update2', [
                        'model' => $model,
                    ]),
                    'footer' => Html::a(
                        ' Volver',
                        ['index_postulados', 'id_oferta' => $id_oferta],
                        ['role' => 'modal-remote', 'class' => 'btn btn-info pull-left']
                    ) .
                        //'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_postulacion', $model->id, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionNotificar_estado($id) //$id es el id de la postulacion:: de mds_rum_postulacion
    {

        $request = Yii::$app->request;
        $model = $this->findModel($id); //$model es el modelo de la postulacion
        //busco el  email de la persona
        $id_de_la_persona = $model->id_persona; //id_de_la_persona es el id de rum_persona
        $un_rum_persona = Mds_rum_persona::findOne($id_de_la_persona);
        $un_seg_usuario = Mds_seg_usuario::findOne($un_rum_persona->id_seg_usuario);
        $un_com_persona = Sds_com_persona::findOne($un_rum_persona->id_com_persona);
        $una_postulacion = Mds_rum_postulacion::findOne($id);
        $una_oferta = Mds_rum_oferta_laboral::findOne($una_postulacion->id_oferta);


        $un_genero = Sds_com_configuracion::findOne($un_com_persona->genero);
        $el_genero=$un_genero->descripcion;   
        $cad_gen='';
        if ($el_genero=='Femenino')
        {$cad_gen='Sra.';}
        else
        {
            if ($el_genero=='Masculino')
            {$cad_gen='Sr.';}
            else
            {
                $cad_gen='';
            }

        }
        


        /*Le informamos que la empresa responsable de la búsqueda publicada para la cual usted se postulo, no ha seleccionado su perfil para el puesto solicitado. 

Le invitanos a que esté atenta/o a nuevas ofertas laborales que prontamente estaremos publicando en Rumbo. 

Saludos cordiales*/
/*->setTo($un_seg_usuario->mail)*/

        $el_estado=$model->estado;
        $cad_cuerpo_user='';
        $cad_estado = 'Estado de la postulación: ' . $model->estado;
        $fecha_pub=$una_oferta->fecha_publicacion;
       
        $unafechapub = explode ("-",$fecha_pub);
        $fecha_pub=trim($unafechapub[2])."/".trim($unafechapub[1])."/".trim($unafechapub[0]);
        $horapub=$model->hora_publicacion;
        
        if ($el_estado=='elegido')
        {
            $cad_cuerpo_user = 'De nuestra consideracion. Estimadx '.$cad_gen.' '.$un_com_persona->nombre.' '.$un_com_persona->apellido
            .', le informamos que la empresa responsable de la busqueda publicada para la cual usted se postulo, ya ha visto su Curriculum Vitae, y ha quedado "elegido" para una pronta entrevista laboral.<br>
            Le pedimos que este atento, por este medio, a los resultados del proceso de selección.<br><br>-Oferta Laboral: '.$una_oferta->titulo.'<br>-Estado de la Postulacion: '.$model->estado.'<br>-Fecha/hora Publicacion: '.$fecha_pub.' '.$horapub; 
        }
        else
        {

            if ($el_estado=='postulado')
            {
                $cad_cuerpo_user = 'De nuestra consideracion. Estimadx '.$cad_gen.' '.$un_com_persona->nombre.' '.$un_com_persona->apellido
                .', le informamos que la empresa responsable de la busqueda publicada para la cual usted se postulo, ya ha visto su Curriculum Vitae<br>
                Le pedimos que este atento, por este medio, a los resultados del proceso de selección.<br><br>-Oferta Laboral: '.$una_oferta->titulo.'<br>-Estado de la Postulacion: '.$model->estado.'<br>-Fecha/hora Publicacion: '.$fecha_pub.' '.$horapub;  
            }
            else
            {
                if ($el_estado=='seleccionado')
                {
                    $cad_cuerpo_user = 'De nuestra consideracion. Estimadx '.$cad_gen.' '.$un_com_persona->nombre.' '.$un_com_persona->apellido
                    .', le informamos que la empresa responsable de la busqueda publicada para la cual usted se postulo, ha seleccionado su perfil como un posible aspirante para cubrir para el puesto requerido.<br>
                    Le pedimos que este atento, por este medio, a los resultados del proceso de selección.<br><br>-Oferta Laboral: '.$una_oferta->titulo.'<br>-Estado de la Postulacion: '.$model->estado.'<br>-Fecha/hora Publicacion: '.$fecha_pub.' '.$horapub; 
                }
                else
                {
                    if ($el_estado=='finalista')
                    {
                        $cad_cuerpo_user = 'De nuestra consideracion. Estimadx '.$cad_gen.' '.$un_com_persona->nombre.' '.$un_com_persona->apellido
                        .', le informamos que la empresa responsable de la busqueda publicada para la cual usted se postulo, evaluo su perfil, y ha sido designado a cubrir el puesto laboral.<br>
                        Le pedimos que este atento, a este medio y/o contacto telefonico, en el que le informaremos sobre los pasos a seguir sobre su incorporacion a la empresa.<br><br>-Oferta Laboral: '.$una_oferta->titulo.'<br>-Estado de la Postulacion: '.$model->estado.'<br>-Fecha/hora Publicacion: '.$fecha_pub.' '.$horapub; 
                    }
                    else
                    {
                        if ($el_estado=='descartado')
                        {
                            $cad_cuerpo_user = 'De nuestra consideracion. Estimadx '.$cad_gen.' '.$un_com_persona->nombre.' '.$un_com_persona->apellido
                            .', le informamos que la empresa responsable de la busqueda publicada para la cual usted se postulo, no ha seleccionado su perfil para el puesto requerido.<br>
                            Le invitanos a que este atenta/o a nuevas ofertas laborales que prontamente estaremos publicando en Rumbo.<br><br>-Oferta Laboral: '.$una_oferta->titulo.'<br>-Estado de la Postulacion: '.$model->estado.'<br>-Fecha/hora Publicacion: '.$fecha_pub.' '.$horapub; 
                        }
                    }
                }
            }
        }
        
        $cad_cuerpo_admin = 'En el dia de la fecha se notifico al usuario '.$un_com_persona->nombre.' '.$un_com_persona->apellido.' sobre el estado de su postulacion a la oferta laboral '.$una_oferta->titulo.'<br>'.$cad_estado;        
        Yii::$app->mailer->compose()
            ->setFrom('rumbo@neuquen.gov.ar')
            
            ->setTo('edugarciacnqn@gmail.com')
            ->setSubject('Notificación a usuario Rumbo sobre el estado de su Postulación')
            ->setTextBody($cad_estado)
            ->setHtmlBody($cad_cuerpo_user)
            ->send();
        Yii::$app->mailer->compose()
            ->setFrom('rumbo@neuquen.gov.ar')
            ->setTo('rumbo@neuquen.gov.ar')
            ->setSubject('Notificación a usuario Rumbo sobre el estado de su Postulación')
            ->setTextBody($cad_estado)
            ->setHtmlBody($cad_cuerpo_admin)
            ->send();

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_postulacion/notificar_estado', $id, null);

        return $model->estado;
    }
    public function actionPostular($id)
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
                    'title' => "RUMBO:: Editar Postulación",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),

                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_postulacion', $id, $model->getAttributes());
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "RUMBO:: Editar Postulación",
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "RUMBO:: Editar Postulación",
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
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }
    /**
     * Delete an existing Mds_rum_postulacion model.
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
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_rum_postulacion', $id, $model->getAttributes());
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
     * Finds the Mds_rum_postulacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_rum_postulacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_rum_postulacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
