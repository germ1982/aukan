<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_cor_intervencion;
use app\models\Mds_cor_intervencion_usuario;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use app\models\Mds_sys_log;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Mds_cor_intervencion_usuarioController implements the CRUD actions for Mds_cor_intervencion model.
 */
class Mds_cor_intervencion_usuarioController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'cmb_usuarios_dispositivo','cmd_usuarios_organismos','cmb_usuarios','cmb_dispositivo','compartidos'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'cmb_usuarios_dispositivo','cmd_usuarios_organismos','cmb_usuarios','cmb_dispositivo','compartidos'],
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
     * Lists all Mds_cor_intervencion_usuario models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Mds_cor_intervencion::findOne($id);

        $model->compartido_con = $this->searchCompartidos($model->idintervencion);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_cor_intervencion_usuario&id=' . $id, null, array());
        return [
            'title' => "Compartir Con",
            'content' => $this->renderAjax('index', [
                'model' => $model,
            ]),
            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
        ];
    }

    public function searchCompartidos($id)
    {
        $intervenciones_borrar = Mds_cor_intervencion_usuario::find()->where(["idintervencion" => $id])->all();
        $compartido = [];
        foreach ($intervenciones_borrar as $intervencion) {
            $usuario = Mds_seg_usuario::findOne(['idusuario' => $intervencion->idusuario]);
            array_push($compartido, [
                'nombre' => $usuario->nombre,
                'apellido' => $usuario->apellido,
                'idusuario' => $usuario->idusuario,
                'editar' => $intervencion->editar,
                'idintervencion' => $intervencion->idintervencion,
                'id' => $intervencion->idintervencionusuario
            ]);
        }
        return new ArrayDataProvider([
            'allModels' => $compartido,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'attributes' => ['nombre', 'apellido'],
            ],
        ]);
    }

    /**
     * Creates a new Mds_cor_intervencion_usuario model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $request = Yii::$app->request;
        $model = new Mds_cor_intervencion_usuario();
        // Valores iniciales de los selects
        $model->usuarios = $this->actionCmb_usuarios();
        $model->organismos = Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all();
        $model->dispositivos = Mds_org_dispositivo::find()->orderBy(['descripcion' => SORT_ASC])->all();

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Agregar Usuarios",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::a('<span class="btn btn-default pull-left">Cerrar</span>', Url::to(['/mds_cor_intervencion_usuario/index', 'id' => $id]), ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Compartir', 'data-toggle' => 'tooltip']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {

                $agregar = $model->agregar;
                if ($agregar) {
                    $agregar_count = count($agregar);
                    if ($agregar_count > 0) {
                        $transaction = Yii::$app->db->beginTransaction();
                        $guardado = true;
                        for ($index_agregar = 0; $index_agregar < $agregar_count; $index_agregar++) {

                            // Primero valido que ya no lo esté compartiendo
                            $comp = Mds_cor_intervencion_usuario::findOne(['idintervencion' => $id, 'idusuario' => intval($agregar[$index_agregar])]);

                            if (!$comp) {
                                // creo nuevo intervencion_usuario
                                $new_comp = new Mds_cor_intervencion_usuario();
                                $new_comp->idusuario = intval($agregar[$index_agregar]);
                                $new_comp->idintervencion = $id;
                                $new_comp->editar = 0;
                                // print_r($new_comp);
                                if (!$new_comp->save()) {
                                    $transaction->rollBack();
                                    $guardado = false;
                                }
                            }
                        }
                        if ($guardado) {
                            $transaction->commit();
                            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_cor_intervencion_usuario', $model->idintervencionusuario, $model->getAttributes());
                        } else {
                            $transaction->rollBack();
                        }
                    }
                }

                // Carga index
                $model = Mds_cor_intervencion::findOne($id);
                $model->compartido_con = $this->searchCompartidos($id);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'title' => "Compartir Con",
                    'content' => $this->renderAjax('index', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            } else {
                return [
                    'title' => "Agregar Usuarios",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::a('<span class="btn btn-default pull-left">Cerrar</span>', Url::to(['/mds_cor_intervencion_usuario/index', 'id' => $model->idintervencion]), ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Compartir', 'data-toggle' => 'tooltip']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        }
    }

    /**
     * Updates an existing Mds_cor_intervencion_usuario model.
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
                    'title' => "Editar ¿Puede compartir?",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::a('<span class="btn btn-default pull-left">Cerrar</span>', Url::to(['/mds_cor_intervencion_usuario/index', 'id' => $model->idintervencion]), ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Compartir', 'data-toggle' => 'tooltip']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else {
                if ($model->load($request->post()) && $model->save()) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_cor_intervencion_usuario', $model->idintervencionusuario, $model->getAttributes());                    
                    $model = Mds_cor_intervencion::findOne($model->idintervencion);
                    $model->compartido_con = $this->searchCompartidos($model->idintervencion);
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'title' => "Compartir Con",
                        'content' => $this->renderAjax('index', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                } else {
                    return [
                        'title' => "Editar ¿Puede compartir?",
                        'content' => $this->renderAjax('update', [
                            'model' => $model,
                        ]),
                        'footer' => Html::a('<span class="btn btn-primary mr-2">Volver</span>', Url::to(['/mds_cor_intervencion_usuario/index', 'id' => $model->idintervencion]), ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Compartir', 'data-toggle' => 'tooltip']) .
                            Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            }
        } else {
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idpermiso]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Mds_cor_intervencion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $idintervencion = $model->idintervencion;
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_cor_intervencion_usuario', $id, $model->getAttributes());
        }
        $model = Mds_cor_intervencion::findOne($idintervencion);

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            $model->compartido_con = $this->searchCompartidos($model->idintervencion);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Compartir Con",
                'content' => $this->renderAjax('index', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Mds_cor_intervencion_usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_cor_intervencion_usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_cor_intervencion_usuario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCmb_dispositivo()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $idorganismo = $parents[0];
                $data = array();

                if ($idorganismo != '') {
                    $out =  Mds_org_dispositivo::find()
                        ->where(["activo" => 1])->andWhere("(idorganismo = " . $idorganismo . " or 0> " . $idorganismo . ")")
                        ->orderBy(['descripcion' => SORT_ASC])->all();

                    foreach ($out as $elto) {
                        $data = array_merge($data, array(['id' => $elto['idorganismo'], 'name' => $elto['descripcion']]));
                    }
                }
            }
        }
        // $data = array_merge($data, array(['id' => 77777, 'name' => $idorganismo]));
        return ['output' => $data, 'selected' => ''];
    }

    public function actionCmb_usuarios()
    {
        $data =  Mds_seg_usuario::findBySql("select u.* 
                from mds_seg_usuario u
                inner join mds_org_contacto c on u.idcontacto = c.idcontacto 
                inner join mds_org_dispositivo d on c.iddispositivo = d.iddispositivo 
                order by u.nombre asc, u.apellido asc;")->all();
        $usuarios = "";
        if (sizeof($data) > 0) {
            foreach ($data as $usuario) {
                $usuarios = $usuarios . "<option value='" . $usuario->idusuario . "'>" .
                    $usuario->nombre . " " . $usuario->apellido . "</option>";
            }
        } else {
            $usuarios = "<option value=null></option>";
        }
        return $data;
    }

    public function actionCmb_usuarios_organismo($idorganismo)
    {
        if ($idorganismo != null) {
            $data =  Mds_seg_usuario::findBySql("select u.* 
                    from mds_seg_usuario u
                    inner join mds_org_contacto c on u.idcontacto = c.idcontacto 
                    inner join mds_org_dispositivo d on c.iddispositivo = d.iddispositivo 
                    where d.idorganismo = $idorganismo
                    order by u.nombre asc, u.apellido asc;")->all();
        }
        $usuarios = "";
        if (sizeof($data) > 0) {
            foreach ($data as $usuario) {
                $usuarios = $usuarios . "<option value='" . $usuario->idusuario . "'>" .
                    $usuario->nombre . " " . $usuario->apellido . "</option>";
            }
        } else {
            $usuarios = "<option value=null></option>";
        }
        return $usuarios;
    }

    public function actionCmb_usuarios_dispositivo($iddispositivo)
    {
        if ($iddispositivo != null) {
            $data =  Mds_seg_usuario::find()
                ->innerJoin('mds_org_contacto', 'mds_org_contacto.idcontacto = mds_seg_usuario.idcontacto')
                ->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])
                ->where(["mds_seg_usuario.activo" => 1, "iddispositivo" => $iddispositivo])
                ->all();
        }
        $usuarios = "";
        if (sizeof($data) > 0) {
            foreach ($data as $usuario) {
                $usuarios = $usuarios . "<option value='" . $usuario->idusuario . "'>" .
                    $usuario->nombre . " " . $usuario->apellido . "</option>";
            }
        } else {
            $usuarios = "<option value=null></option>";
        }
        return $usuarios;
    }
}
