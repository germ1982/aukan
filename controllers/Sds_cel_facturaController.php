<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_cel_factura;
use app\models\Sds_cel_factura_item;
use app\models\Telefonia_vista_integradora;
use app\models\Sds_cel_facturaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use yii\filters\AccessControl;

/**
 * Sds_cel_facturaController implements the CRUD actions for Sds_cel_factura model.
 */
class Sds_cel_facturaController extends Controller
{

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
                    'only' => ['index', 'view', 'create', 'update', 'delete', 'importar', 'procesar_factura'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete', 'importar', 'procesar_factura'],
                            'allow' => true,
                            // Allow users, moderators and admins to create
                            'roles' => [
                                Mds_seg_item::MODULO_CEL_CORPORATIVOS
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
        $searchModel = new Sds_cel_facturaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_cel_factura', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'sds_cel_factura', $id, array());
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Factura numero : " . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_cel_factura();
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Nueva Factura",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;
                $fecha = ArmarDateParaMySql($model->fecha_carga, '00:00');
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->fecha_carga = $fecha;
                if ($guardado && $model->save()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'sds_cel_factura', $model->idfactura, $model->getAttributes());
                    return [
                        'title' => "Nueva Factura De Telefono",
                        'content' => '<span class="text-success">Factura Creada Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                return [
                    'title' => "Nueva Factura",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
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
                    'title' => "Editar Factura numero:" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;
                $fecha = ArmarDateParaMySql($model->fecha_carga, '00:00');
                $fecha = date_create($fecha);
                $fecha = date_format($fecha, 'Y-m-d');
                $model->fecha_carga = $fecha;
                if ($guardado && $model->save(false)) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'sds_cel_factura', $model->idfactura, $model->getAttributes());
                    return [
                        'title' => "Factura editada correctamente",
                        'content' => $this->renderAjax('view', ['model' => $model,]),
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                    ];
                }
            } else {
                return [
                    'title' => "Editar Factura numero:" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
                ];
            }
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'sds_cel_factura', $id, $model->getAttributes());
        }
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
        } else {
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
        if (($model = Sds_cel_factura::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImportar($idfactura)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Importar Items de Factura",
                'content' => $this->renderAjax('importar_factura_items',['idfactura'=>$idfactura]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionProcesar_factura()
    {
        $request = Yii::$app->request;
        if(!$request->isAjax || Yii::$app->user->isGuest){
            return $this->redirect(['index']);
        }
        $datos = Yii::$app->request->post('datos');
        $idfactura = Yii::$app->request->post('idfactura');
        $array = json_decode($datos, true);
        $model = $this->findModel($idfactura);
        $guardado = 0;
        $no_guardado_ya_existentes = 0;
        $no_guardado_error_en_base = 0;
        $no_guardado_datos_diferentes = 0;

        /* Las siguientes variables son nombradas asi acorde al tipo de dato que contienen, y
        guardan el nombre de la primera celda (fila) de la columna con el que se rescataron los datos de exel, 
        si el exel cambia esos nombres deben coregirse en las siguientes variables, ya que son las que se usan 
        para rescatar los datos que se deben enviar a la base de datossa, 
        si la columna no tiene texto las muestra con EMPTY 
        Esto debe hacerse cada vez que importo un exell para tener prevenido cualquier cambio*/
            
        $columna_CUENTA = "CUENTA";
        $columna_NRO_FACTURA = "NRO_FACTURA";
        $columna_DESC_FACTURA = "DESC_FACTURA";
        $columna_INICIO_CICLO = "INICIO_CICLO";
        $columna_FIN_CICLO = "FIN_CICLO";
        $columna_LINEA = "LINEA";
        $columna_ID_PLAN = "ID_PLAN";
        $columna_ID_CONCEPTO = "ID_CONCEPTO";
        $columna_DESCRIPCION_CONCEPTO = "DESCRIPCION_CONCEPTO";
        $columna_CANTIDAD = "CANTIDAD";
        $columna_MONTO_NETO = "MONTO_NETO";
        $columna_MONTO_IMPUESTOS = "MONTO_IMPUESTOS";
        $columna_MONTO_TOTAL = "MONTO_TOTAL";

        /*La variable ban va a servir para saber cuando una linea son datos a guardar, ya que 
        muchos exel antes de empesar a mostrar la info tienen caratulados o titulos*/
        $ban=1;//en este caso los exels arrancan de una
        $anuncio_log = "";//aca guardo que va pasando con cada linea para mostrar al final
        $lineanro = $model->cuenta;
        $model_tabla_integradora = Telefonia_vista_integradora::findOne($lineanro);
        $cuenta = $model_tabla_integradora->cuenta;
        $periodo_mes = $model->periodo_mes;
        $periodo_anio = $model->periodo_anio;
            
        $fondo = "<div>"; //esta variable la uso para ir cambiando el color del fondo del log y dejar el fondo intercalado
        $cont = 1;
        foreach ($array as $linea_exel){
            $cont = $cont+1;
            if($ban==1){//pregunta a $ban si la linea ya son datos a guardar	
                if ($fondo =="<div>"){
                    $fondo = "<div style='background-color:#D5D3D3;'>";
                }else{
                    $fondo = "<div>";
                }
                $anuncio_log = "$anuncio_log $fondo";
                //verifico que los datos de la linea son iguales al periodo y cuenta
                /* Variables para verificar la cuenta y su periodo, si bien cada archivo trae solo esos registros
                puede pasar que erroneamente el usuario suba un exel con otra cuenta y otro periodo, 
                por lo que no esta demas comparar datos antes de guardar */
                $ban_guardar = 1;
                $cuenta_exel = $linea_exel["$columna_CUENTA"];

                if($cuenta_exel=='GLOSARIO'){//quiere decir que ya termino y no debe seguir recorriendo las lineas
                    $ban_guardar=0;
                    $ban=2;
                }else{
                    //$periodo_mes_exel = substr($linea_exel["$columna_INICIO_CICLO"], 5,2);
                    $periodo_mes_exel = $linea_exel["$columna_INICIO_CICLO"];
                    $periodo_mes_exel = substr("$periodo_mes_exel",5,2);
                    $periodo_anio_exel = $linea_exel["$columna_INICIO_CICLO"];
                    $periodo_anio_exel = substr("$periodo_anio_exel",0,4); 
                    if(!($cuenta_exel==$cuenta))
                        {$ban_guardar=0;}
                    if(!($periodo_mes_exel==$periodo_mes))
                        {$ban_guardar=0;}
                    if(!($periodo_anio_exel==$periodo_anio))
                        {$ban_guardar=0;}
                }
                        
                if($ban_guardar==1){
                    //$anuncio_log = "$anuncio_log cuenta parametro: $cuenta \n cuenta exel: $cuenta_exel \n periodo_mes exel: $periodo_mes_exel \n periodo año exel: $periodo_anio_exel";
                    //Variables a guardar
                    if(isset($linea_exel["$columna_LINEA"])){
                        $linea = $linea_exel["$columna_LINEA"];
                    }else{
                        $linea  = "0";
                    }
                    if (isset($linea_exel["$columna_DESCRIPCION_CONCEPTO"])){
                        $concepto = $linea_exel["$columna_DESCRIPCION_CONCEPTO"];
                    }else{
                        $concepto  = '_';
                    }
                    if (isset($linea_exel["$columna_CANTIDAD"])){
                        $cantidad = $linea_exel["$columna_CANTIDAD"];
                    }else{
                        $cantidad  = '0';
                    }
                    $neto = $linea_exel["$columna_MONTO_NETO"];
                    $impuestos = $linea_exel["$columna_MONTO_IMPUESTOS"];
                    $total = $linea_exel["$columna_MONTO_TOTAL"];
                    $idconcepto = $linea_exel["$columna_ID_CONCEPTO"];
                    $sql="SELECT * FROM sds_cel_factura_item WHERE idfactura=$idfactura AND linea='$linea' AND cantidad='$cantidad' AND neto='$neto' AND impuestos='$impuestos' AND total='$total' AND idconcepto='$idconcepto'"; 
                                
                    $model_item_existente = Sds_cel_factura_item::findBySql($sql)->all();
                    if(sizeof($model_item_existente)>0){
                        $no_guardado_ya_existentes++;
                        $anuncio_log = "$anuncio_log <p style='color:red'>linea $cont con cuenta $cuenta_exel y periodo $periodo_mes_exel/$periodo_anio no se ha guardado \nRazon: Ya existe...</p>";
                    }else{
                        $model_item = new Sds_cel_factura_item();
                        $model_item->idfactura = $idfactura;
                        $model_item->linea = $linea;
                        $model_item->concepto = $concepto;
                        $model_item->cantidad = $cantidad;
                        $model_item->neto = $neto;
                        $model_item->impuestos = $impuestos;
                        $model_item->total = $total;
                        $model_item->idconcepto = $idconcepto;
                        if ($model_item->save(false)){
                            $guardado++;
                            $anuncio_log = "$anuncio_log <p style='color:#008F47'>Linea $cont Guardada Correctamente...\nId Item: $model_item->idfacturaitem</p>";
                        }else{
                            $no_guardado_error_en_base++;
                            $aux = "INSERT INTO sds_cel_factura_item (idfactura,linea,concepto,cantidad,neto,impuestos,total,idconcepto) \n values($idfactura,$linea,'$concepto',$cantidad,$neto,$impuestos,$total,'$idconcepto')";
                            $anuncio_log = "$anuncio_log <p style='color:red'>linea $cont con cuenta $cuenta_exel y periodo $periodo_mes_exel/$periodo_anio no se ha guardado \n$aux...</p>";
                        }
                    }
                }else{
                    if($ban==1){
                        $no_guardado_datos_diferentes++;
                        $anuncio_log = "$anuncio_log <p style='color:red'>linea $cont con cuenta $cuenta_exel y periodo $periodo_mes_exel/$periodo_anio no se ha guardado \nRazón: no concuerdan con los datos indicadas en la factura a guardar...</p>";
                    }
                }
                $anuncio_log = "$anuncio_log </div>";
            }
        }
        $estadistica = "Guardados: $guardado \nYa existentes: $no_guardado_ya_existentes \nFalla al guardar en Base de Datos: $no_guardado_error_en_base \nDatos no pertenecientes al plan y periodo: $no_guardado_datos_diferentes\n\n";
        return "<div style='padding-top: 0px !important;padding-bottom: 0px !important;'>$estadistica.$anuncio_log</div>";
    }
}
function ArmarDateParaMySql($Fecha, $Hora)
{
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $H = substr($Hora, 0, 2);
    $m = substr($Hora, 3, 2);
    $DT = "$anio-$mes-$dia $H:$m:00";
    return $DT;
}
