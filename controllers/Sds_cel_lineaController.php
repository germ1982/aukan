<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use Yii;
use app\models\Sds_cel_linea;
use app\models\Sds_cel_lineaSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use app\models\Sds_bdc_equipo;
use app\models\Sds_cel_movimiento_linea;
use app\models\Sds_cel_plan;
use app\models\Sds_com_configuracion;
use Exception;
use kartik\mpdf\Pdf;
use yii\helpers\ArrayHelper;

/**
 * Sds_cel_lineaController implements the CRUD actions for Sds_cel_linea model.
 */
class Sds_cel_lineaController extends Controller
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
                'only' => ['index', 'create', 'update', 'delete', 'view', 'reporte_corpo', 'reporte_entrega_equipo', 'logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'reporte_corpo', 'reporte_entrega_equipo', 'logout'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_CEL_CORPORATIVOS,
                        ],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $searchModel = new Sds_cel_lineaSearch();
        $filterEstado=ArrayHelper::map(
            Sds_cel_linea::findBySql(
                "SELECT ml.tipo id_ultimo_movimiento, conf.descripcion ultimo_movimiento
                FROM sds_cel_linea l
                left join sds_cel_movimiento_linea ml ON l.idlinea=ml.idlinea 
                left join sds_com_configuracion conf ON ml.tipo=conf.idconfiguracion 
                WHERE ml.tipo is not null
                GROUP BY ml.tipo
                ORDER BY ultimo_movimiento ASC"
            )->all(),
            'id_ultimo_movimiento',
            'ultimo_movimiento'
            );
        $searchModel->cuenta = Yii::$app->user->identity->celular_cuenta;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_cel_linea', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filterEstado' => $filterEstado
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_cel_linea', $id, array());
        $model=$this->findModel($id);
        if($equipo=Sds_bdc_equipo::findOne($model->idequipo)){
            $marca=Sds_com_configuracion::findOne($equipo->marca);
            $equipo->marca=$marca->descripcion;
        }else{
            $equipo=new Sds_bdc_equipo();
        }
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => '<div class="label label-primary" style="font-size:22px;">
                    Linea: <b>'.$model->numero.'</b></div>',
                'content' => '<div class="container-fluid">'.$this->renderAjax('view', [
                    'model' => $model,
                    'equipo' => $equipo
                ]).'</div>',
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionCreate()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $request = Yii::$app->request;
        $model = new Sds_cel_linea();
        $model->idusuario  = Yii::$app->user->identity->idusuario;
        
        if ($request->isAjax) {
            /* Process for ajax request */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!$request->isGet && $model->load($request->post())) {
                $model->estado=Sds_cel_linea::ESTADO_RELEVADO;
                $model->fecha_entrega=date('Y-m-d');
                $equipo=Sds_bdc_equipo::findOne($model->idequipo);
                try{
                    $transaction = Yii::$app->db->beginTransaction();
                    if($model->save()) {
                        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_cel_linea', $model->idlinea, $model->getAttributes());
                        $movimiento_linea=new Sds_cel_movimiento_linea();
                        $movimiento_linea->fecha_hora=date('Y-m-d H:i:s');
                        $movimiento_linea->idusuario=$model->idusuario;
                        $movimiento_linea->solicitante=$equipo->responsable;
                        $movimiento_linea->tipo=Sds_cel_movimiento_linea::MOV_ALTA;
                        $movimiento_linea->responsable_nuevo=$equipo->responsable;
                        $movimiento_linea->equipo_nuevo=$equipo->idequipo;
                        $movimiento_linea->organismo_nuevo=$equipo->idorganismo;
                        $movimiento_linea->organismo_cuenta_nuevo=$model->organismo_padre;
                        $movimiento_linea->plan_nuevo=$model->idplan;
                        $movimiento_linea->observaciones="Movimiento generado automáticamente al dar de alta la linea ".$model->numero;
                        $movimiento_linea->idlinea=$model->idlinea;
                        if($movimiento_linea->save()){
                            $transaction->commit();
                            $mensaje_success='¡Linea '.$model->numero.' creada exitosamente!';
                            $model=new Sds_cel_linea();
                        }else{
                            throw new Exception('No fue posible registrar el movimiento de alta de la linea. Por favor intente nuevamente.');
                        }
                    }else{
                        throw new Exception('No fue posible registrar la linea. Por favor intente nuevamente.');
                    }
                }catch (Exception $e) {
                    $transaction->rollBack();
                    $mensaje_error=$e->getMessage();
                }
            }
            return [
                'title' => "Nueva Línea",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'mensaje_error' => (isset($mensaje_error)?$mensaje_error:null),
                    'mensaje_success' => (isset($mensaje_success)?$mensaje_success:null),
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])

            ];
        }else {
            return $this->redirect(['index']);
        }
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Actualizar Línea",
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
                ];
            } else if ($model->load($request->post())) {
                if ($model->save(false)) {
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_cel_linea', $model->idlinea, $model->getAttributes());
                    $equipo=Sds_bdc_equipo::findOne($model->idequipo);
                    $equipo->marca=Sds_com_configuracion::getDescripcion($equipo->marca);
                    return [
                        'title' => "Linea editada correctamente",
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                            'equipo' => $equipo
                        ]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            }
            return [
                'title' => "Actualizar Línea",
                'content' => $this->renderAjax('update', [
                    'model' => $model
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal", 'id' => 'btnCerrar']) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit", 'id' => 'btnGuardar'])
            ];
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_cel_linea', $id, $model->getAttributes());
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
        if (($model = Sds_cel_linea::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionReporte_corpo($numero, $idplan, $equipo_tipo, $estado, 
    $idcontacto, $idorganismo, $iddispositivo,$organismo_padre,$idusuario,$observaciones, $activo)
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_hor_asistencia_reporte/reporte_asistencia', $idcontacto, array());
        $content = $this->renderPartial('reporte_corpo', ['numero'=>$numero, 
        'idplan'=>$idplan, 'equipo_tipo'=>$equipo_tipo, 'estado'=>$estado, 'idcontacto'=>$idcontacto, 'idorganismo'=>$idorganismo,
        'iddispositivo'=> $iddispositivo,'idusuario'=> $idusuario,'organismo_padre'=>$organismo_padre,
        'observaciones'=>$observaciones,'activo'=>$activo]); // setup kartik\mpdf\Pdf component         
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
                'SetTitle' => 'REPORTE DE ASISTENCIAS',
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);

        return $pdf->render();
    }

    public function actionReporte_entrega_equipo($idlinea){
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/']);
        }
        $linea=$this->findModel($idlinea);
        //MOSTRAR DATOS DEL RESPONSABLE DE LINEA/EQUIPO!!!!!!!!!
        $responsable=Mds_org_contacto::findBySql(
            "SELECT c.*, UPPER(p.nombre) nombre, UPPER(p.apellido) apellido
            FROM sds_bdc_equipo e
            JOIN mds_org_contacto c ON e.responsable=c.idcontacto
            JOIN sds_com_persona p ON p.idpersona=c.idpersona
            WHERE e.idequipo=".$linea->idequipo)->one();
        $dispositivo_resp=Mds_org_dispositivo::findOne($responsable->iddispositivo);
        $usuario_entrega=Mds_org_contacto::findBySql(
            "SELECT UPPER(u.nombre) nombre, upper(u.apellido) apellido, c.legajo
            FROM mds_seg_usuario u
            JOIN mds_org_contacto c ON u.idcontacto=c.idcontacto
            WHERE u.idusuario=".$linea->idusuario
            )->one();
        $equipo=Sds_bdc_equipo::findOne($linea->idequipo);
        if($marca=Sds_com_configuracion::findOne($equipo->marca)){
            $equipo->marca=$marca->descripcion;
        }else{
            $equipo->marca='- SIN DATOS -';
        }
        $plan=Sds_cel_plan::findOne($linea->idplan);

        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_cel_linea/reporte_entrega_equipo', $idlinea, array());
        $content = $this->renderPartial('reporte_entrega_equipo', [
            'linea'=>$linea,
            'responsable'=>$responsable,
            'usuario_entrega' => $usuario_entrega,
            'equipo'=>$equipo,
            'dispositivo_resp' => $dispositivo_resp->descripcion,
            'plan' => $plan->descripcion
        ]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'Entrega de Equipo Corporativo',
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);

        return $pdf->render();
    }
}
