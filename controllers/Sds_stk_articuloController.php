<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_item;
use Yii;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_articuloSearch;
use app\models\Sds_stk_recepcion_item;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_sys_log;
use app\models\Mds_seg_usuario;
use app\models\Sds_stk_deposito;
use app\models\Sds_stk_entrega;
use app\models\Sds_stk_entrega_item;
use app\models\Sds_stk_movimiento;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use kartik\mpdf\Pdf;
use yii\helpers\Url;
use app\models\Sds_stk_orden_compra_item;


class Sds_stk_articuloController extends Controller
{

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
                'only' => [
                    'index',
                    'create',
                    'update',
                    'delete',
                    'view',
                    'logout',
                    'view_stock_general',
                    'form_safipro',
                    'get_grilla_stock_general_modal_rubro',
                    'get_grilla_ultimas_entregas',
                    'get_grilla_stock_general',
                    'get_boton_imprimir',
                    'imprimir_stock_general',
                    'get_boton_imprimir_entregado_pesos',
                    'imprimir_entregado_pesos',
                    'get_articulo_disponible_en_depositos',
                    'get_grilla_disponible_en_depositos',
                    'verificar_existencia',
                    'create_ext', 'cmb_articulo',
                    'get_input_select2_articulos',
                    'get_stock_disponible',
                    'get_stock_minimo',
                    'get_stock_ingresado',
                    'get_stock_entregado',
                    'get_boton_imprimir_responsables',
                    'imprimir_entregado_responsable'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'create_ext',
                            'delete',
                            'update',
                            'view',
                            'logout',
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [Mds_seg_item::MODULO_STK_RECEPCION],
                    ],
                    [
                        'actions' => [
                            'view_stock_general',
                            'get_grilla_stock_general_modal_rubro',
                            'get_grilla_ultimas_entregas',
                            'get_grilla_stock_general',
                            'get_boton_imprimir',
                            'imprimir_stock_general',
                            'get_boton_imprimir_entregado_pesos',
                            'imprimir_entregado_pesos',
                        ],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [Mds_seg_item::STK_GENERAL],
                    ],
                    [
                        'actions' => [
                            'form_safipro',
                            'get_articulo_disponible_en_depositos',
                            'get_grilla_disponible_en_depositos',
                            'cmb_articulo',
                            'get_input_select2_articulos',
                            'get_stock_disponible',
                            'get_stock_minimo',
                            'get_stock_ingresado',
                            'get_stock_entregado',
                            'get_boton_imprimir_responsables',
                            'imprimir_entregado_responsable'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],

            ],
        ];
    }

    public function actionIndex()
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);

        $searchModel = new Sds_stk_articuloSearch();
        if ($usuario->organismo_stock) {
            $id_organismo = $usuario->organismo_stock;
            $searchModel->organismo = $id_organismo;
            $searchModel->rubro = Mds_org_organismo::findOne(
                $id_organismo
            )->idrubro;
        }

        $searchModel->activo = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_stk_articulo',
            null,
            []
        );
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(
            Mds_sys_log::ACCION_CONSULTA,
            'sds_stk_articulo',
            $id,
            []
        );
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => Sds_stk_articulo::findOne($id)->descripcion,
                //'title' => "Articulo Numero: " . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' =>
                Html::button('Cerrar', [
                    'id' => 'btnCerrar',
                    'class' => 'btn btn-default pull-left',
                    'data-dismiss' => 'modal',
                ]) .
                    Html::a(
                        'Editar',
                        ['update', 'id' => $id],
                        ['class' => 'btn btn-primary', 'role' => 'modal-remote']
                    ),
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }
    public function actionView_stock_general($rubro = null)
    {
        return $this->render('home_stock_general', ['rubro' => $rubro]);
    }

    public function actionForm_safipro($id)
    {
        $request = Yii::$app->request;
        //$model = Sds_stk_articulo::findOne($id);
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' =>
                '<h4><b style="color:#0088cc;">Articulo: Items Safipro</b></h4>',
                'content' => $this->renderAjax('_form_safipro', [
                    'model' => $this->findModel($id),
                ]),
                //'content' => $this->renderAjax('_form_safipro'),
                'footer' => Html::button('Cerrar', [
                    'id' => 'btnCerrar',
                    'class' => 'btn btn-default pull-left',
                    'data-dismiss' => 'modal',
                ]),
            ];
        }
    }

    public function actionGet_grilla_stock_general_modal_rubro()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' =>
                '<h4><b style="color:#0088cc;">Stock General</b></h4>',
                'content' => $this->renderAjax('_form_stock_general'),
                'footer' => Html::button('Cerrar', [
                    'id' => 'btnCerrar',
                    'class' => 'btn btn-default pull-left',
                    'data-dismiss' => 'modal',
                ]),
            ];
        }
    }



    public function actionGet_grilla_ultimas_entregas($beneficiario = '')
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        $id_organismo = 0;
        if ($usuario->organismo_stock) {
            $id_organismo = $usuario->organismo_stock;
        }

        //$consulta = "SELECT CONCAT(ei.cantidad,' ',a.descripcion,' a <b>', p.nombre,' ',p.apellido,'</b>') as observaciones
        $consulta = "SELECT CONCAT(DATE_FORMAT(e.fecha_hora,'%d/%m'),IF(e.fecha_hora < '".Sds_stk_articulo::PERIODO_PRUEBA."', ' <span class=\"text-success\">(Per&iacute;odo Prueba)</span> ', ' - '), '<b>', p.nombre,' ',p.apellido,' (DNI: ',p.documento,'):</b><br>',
                    GROUP_CONCAT(CONCAT(ei.cantidad,' ',a.descripcion) SEPARATOR ' | '),' <b>(', pr.nombre,' ',pr.apellido,')</b>') as observaciones 
                    FROM sds_stk_entrega_item ei
                    JOIN sds_stk_entrega e on ei.identrega = e.identrega
                    JOIN sds_stk_recepcion_item ri on ei.recepcion_item = ri.idrecepcionitem
                    JOIN sds_stk_articulo a on ei.idarticulo = a.idarticulo
                    JOIN sds_com_persona p on p.idpersona = e.idpersona
                    JOIN mds_org_contacto c on c.idcontacto = e.idcontacto
                    JOIN sds_com_persona pr on pr.idpersona = c.idpersona
                    WHERE e.organismo = $id_organismo
                    GROUP BY e.identrega
                    HAVING observaciones LIKE '%$beneficiario%'                    
                    ORDER BY e.fecha_hora DESC
                    LIMIT 200";
        //return $consulta;
        $entregas = Sds_stk_entrega::findBySql($consulta)->all();

        $tabla = "<table class='table table-striped table-bordered table-hover' style='width:100%;border-collapse: separate;'>";
        $tabla = "$tabla<thead><tr><th style='position: sticky; top:0px;border: 1px solid #ddd;background-color: #FFF;'><span class='col-md-4' style='padding-top:8px;'>Últimas Entregas</span><div class='col-md-6'><input type='text' class='form-control' id='beneficiario' value='$beneficiario'></div><div class='col-md-2' style='padding-left:0;'><input type='button' class='btn btn-primary btn-small' value='Buscar' onclick='buscar_entregas();'></div></th></tr></thead><tbody>";
        foreach ($entregas as $entrega) {
            $tabla = "$tabla<tr><td style='text-align:left'>$entrega->observaciones</td></tr>";
        }
        $tabla = "$tabla</tbody></table>";
        //return $consulta;

        return $tabla;
    }

    public function actionGet_grilla_stock_general($rubro = null, $ver_minimo = false, $fecha_hasta = null)
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        $id_organismo = $usuario->organismo_stock ? $usuario->organismo_stock : 0;

        $where_articulos = "activo = 1 and organismo = $id_organismo";
        $where_articulos = $rubro ? "$where_articulos and rubro = $rubro" : $where_articulos;

        $articulos = Sds_stk_articulo::find()->where($where_articulos)->orderBy(['orden' => SORT_ASC, 'descripcion' => SORT_ASC])->all();
        $depositos = Sds_stk_deposito::find()->where(['activo' => 1, 'idorganismo' => $id_organismo])->orderBy(['descripcion' => SORT_ASC])->all();

        $tabla = "<table class='table table-striped table-bordered table-hover' style='width:100%'>"; //aca empieza la tabla
        $tabla = "$tabla<tr><th>Articulos</th>"; //aca empieza la linea de titulos

        foreach ($depositos as $deposito) {
            $tabla = "$tabla<th style='text-align:center;width:30px'>$deposito->descripcion</th>";
        }
        $tabla = "$tabla<th style='text-align:center;width:30px'>Total</th>";
        /* $tabla = "$tabla<th>total_en_oc</th>";
         $tabla = "$tabla<th>total_en_recepcion</th>"; */
        $tabla = "$tabla<th style='text-align:center;width:30px'>Stk Prov</th>";

        $tabla = "$tabla</tr>"; //aca termina la linea de titulos

        foreach ($articulos as $articulo) //aca empieza a recorrer los articulos
        {
            $stock_min = $articulo->stock_minimo ? $articulo->stock_minimo : 0;
            $tabla = "$tabla<tr><td style='text-align:left'>$articulo->descripcion</td>";
            $valor_total = 0;
            $consulta_fecha = '';
            if ($fecha_hasta != null) {
                $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $fecha_hasta)), 'Y-m-d H:i:s');
                $consulta_fecha = " and DATEDIFF(fecha_emision,'$fecha_hasta_aux')<=0 ";
            }

            $total_en_oc = Sds_stk_orden_compra_item::findBySql("SELECT SUM(oci.cantidad) as cantidad 
                                                                FROM sds_stk_orden_compra_item oci 
                                                                join sds_stk_orden_compra oc on oc.idordencompra=oci.idordencompra
                                                                WHERE oci.idarticulo = $articulo->idarticulo
                                                                $consulta_fecha")->one()->cantidad;
            if (isset($fecha_hasta_aux)) {
                $consulta_fecha = " and DATEDIFF(fecha,'$fecha_hasta_aux')<=0 ";
            }
            $total_en_recepcion = Sds_stk_recepcion_item::findBySql("SELECT SUM(ri.cantidad) as cantidad 
                                                                    FROM sds_stk_recepcion_item ri 
                                                                    join sds_stk_recepcion rec on rec.idrecepcion=ri.idrecepcion
                                                                    WHERE ri.idarticulo = $articulo->idarticulo 
                                                                    $consulta_fecha
                                                                    and ri.idordencompraitem >0")->one()->cantidad;
            $Stk_Prov = $total_en_oc - $total_en_recepcion;
            //Se setean valores disponibles por depósito y se calcula total por artículo.
            //Decidí sacar el html aparte y meter el valor en una variable auxiliar, para poder tomar el total después para mostrar u ocultar disponibles.
            foreach ($depositos as $deposito) //aca empieza a recorrer los depositos de cada articulo y busca sus valores
            {
                $valor = $this->actionGet_articulo_disponible_en_depositos($articulo->idarticulo, $deposito->iddeposito, $fecha_hasta);
                $valor_total = $valor_total + $valor;
                $deposito->disponible = $valor;

                /* $aux = "valor: $valor<br>valor_total: $valor_total<br>deposito->disponible: $deposito->disponible<br>ver_minimo: $ver_minimo<br>stock_min: $stock_min<br>articulo->ocultar: $articulo->ocultar";
                    $tabla = "$tabla<td style='text-align:center;width:30px'>$aux</td>"; */
            }
            foreach ($depositos as $deposito) //aca lo dibuja con colores segun los valores
            {
                if ($deposito->disponible > $stock_min) {
                    $tabla = "$tabla<td style='text-align:center;width:30px'>$deposito->disponible</td>";
                } else {
                    /* $tabla = "$tabla<td style='text-align:center;width:30px'>$deposito->disponible</td>"; */
                    $tabla = "$tabla<td style='color:#CE1717;text-align:center;width:30px'>" .
                        ($ver_minimo || $valor_total > $stock_min || ($stock_min == 0 && $valor_total == 0) || !$articulo->ocultar ? $deposito->disponible : '') .
                        '</td>';
                }
            }
            if ($valor_total > $stock_min) {
                $tabla = "$tabla<td style='text-align:center;width:30px'>$valor_total</td>";
            } else {
                $tabla =
                    "$tabla<td style='color:#CE1717;text-align:center;width:30px'>" .
                    ($ver_minimo ||
                        $valor_total > $stock_min ||
                        ($stock_min == 0 && $valor_total == 0) ||
                        !$articulo->ocultar
                        ? $valor_total
                        : '<i>Consultar</i>') .
                    '</td>';
            }

            /* $tabla = "$tabla<td style='background-color:#f2dede;'>$total_en_oc</td>";
             $tabla = "$tabla<td style='background-color:#f2dede;'>$total_en_recepcion</td>"; */

            if ($Stk_Prov > 0) {
                $tabla = "$tabla<td style='text-align:center;width:30px'>$Stk_Prov</td>";
            } else {
                $tabla = "$tabla<td style='color:#CE1717;text-align:center;width:30px'>$Stk_Prov</td>";
            }

            $tabla = "$tabla</tr>";
        }

        $tabla = "$tabla</table>";

        return $tabla;
    }

    public function actionGet_boton_imprimir(
        $rubro = null,
        $ver_minimo = false,
        $fecha_hasta = null
    ) {
        $url = Url::to([
            '/sds_stk_articulo/imprimir_stock_general',
            'rubro' => $rubro,
            'ver_minimo' => $ver_minimo,
            'fecha_hasta' => $fecha_hasta,
        ]);
        return Html::a('IMPRIMIR <span class= "fas fa-print"></span>', $url, [
            'title' => 'Imprimir ',
            'role' => 'post',
            'data-pjax' => 0,
            'target' => '_blank',
            'data-toggle' => 'tooltip',
        ]);
    }

    public function actionImprimir_stock_general(
        $rubro = null,
        $ver_minimo = false,
        $fecha_hasta = null
    ) {
        $tabla = $this->actionGet_grilla_stock_general(
            $rubro,
            $ver_minimo,
            $fecha_hasta
        );

        $content = $this->renderPartial('imprimir_stock_general', [
            'tabla' => $tabla,
        ]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/style_table.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'ACTA DE ENTREGA',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }

    public function actionGet_boton_imprimir_entregado_pesos($idarticulo = null, $anio, $idos = null)
    {
        $url = Url::to([
            '/sds_stk_articulo/imprimir_entregado_pesos',
            'idarticulo' => $idarticulo,
            'anio' => $anio,
            'idos' => $idos,
        ]);
        return Html::a('<span class="fas fa-print"></span>', $url, [
            'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
            'data-toggle' => 'tooltip',
            'title' => ('Exportar PDF')
        ]);
    }

    public function actionImprimir_entregado_pesos($idarticulo = null, $anio, $idos = null)
    {
        $tabla = View_stock_inversionController::actionGet_grilla_inversiones(
            $idarticulo,
            $anio,
            $idos
        );

        //return $tabla;

        $content = $this->renderPartial('imprimir_entregado_pesos', [
            'tabla' => $tabla,
        ]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/style_table.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'ACTA DE ENTREGA',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }

    public function actionGet_articulo_disponible_en_depositos($id_articulo, $id_deposito, $fecha_hasta)
    {
        $consulta_fecha = '';
        if ($fecha_hasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', "$fecha_hasta 23:59:59")), 'Y-m-d H:i:s');
            $consulta_fecha = " and d.fecha_hora <= '$fecha_hasta_aux'";
        }

        $consulta = "SELECT ifnull(sum(cantidad),0) as disponible
                    FROM view_stock_detalle d 
                    WHERE d.deposito = $id_deposito and d.idarticulo = $id_articulo $consulta_fecha
                    group by d.deposito,d.idarticulo";

        $valor = Sds_stk_movimiento::findBySql($consulta)->one();
        if ($valor) {
            return $valor->disponible;
        } else {
            return 0;
        }
    }

    /* public function actionGet_articulo_disponible_en_depositos_old(
        $id_articulo,
        $id_deposito,
        $fecha_hasta
    ) {
        $consulta_fecha = '';
        if ($fecha_hasta != null) {
            $fecha_hasta_aux = date_format(
                date_create(str_replace('/', '-', $fecha_hasta)),
                'Y-m-d H:i:s'
            );
            $consulta_fecha =
                ' and DATEDIFF(fecha_hora,\'' . $fecha_hasta_aux . '\')<=0 ';
        }
        $sql_suma_cantidad = "SELECT ifnull(sum(mo.cantidad),0) from sds_stk_movimiento mo 
                            where mo.deposito_ingreso = d.iddeposito and mo.idarticulo = a.idarticulo
                            $consulta_fecha";
        $sql_resta_cantidad = "SELECT ifnull(sum(mo.cantidad),0) from sds_stk_movimiento mo 
                            where mo.deposito_egreso = d.iddeposito and mo.idarticulo = a.idarticulo
                            $consulta_fecha";
        $disponible = "($sql_suma_cantidad)-($sql_resta_cantidad)";
        $consulta = "SELECT m.deposito_ingreso as deposito_ingreso, d.descripcion as deposito, $disponible as disponible
                        from sds_stk_movimiento m
                        INNER JOIN sds_stk_articulo a on m.idarticulo = a.idarticulo
                        INNER JOIN sds_stk_deposito d on m.deposito_ingreso = d.iddeposito
                        WHERE a.idarticulo = $id_articulo and d.iddeposito= $id_deposito 
                        $consulta_fecha";
        $valor = Sds_stk_movimiento::findBySql($consulta)->one();
        if ($valor) {
            return $valor->disponible;
        } else {
            return 0;
        }
    } */

    public function actionGet_grilla_disponible_en_depositos($id_articulo)
    {
        $sql_suma_cantidad =
            'SELECT ifnull(sum(mo.cantidad),0) from sds_stk_movimiento mo where mo.deposito_ingreso = d.iddeposito and mo.idarticulo = a.idarticulo';
        $sql_resta_cantidad =
            'SELECT ifnull(sum(mo.cantidad),0) from sds_stk_movimiento mo where mo.deposito_egreso = d.iddeposito and mo.idarticulo = a.idarticulo';
        $disponible = "($sql_suma_cantidad)-($sql_resta_cantidad)";
        $consulta = "SELECT m.deposito_ingreso as deposito_ingreso, d.descripcion as deposito, $disponible as disponible
                        from sds_stk_movimiento m
                        INNER JOIN sds_stk_articulo a on m.idarticulo = a.idarticulo
                        INNER JOIN sds_stk_deposito d on m.deposito_ingreso = d.iddeposito
                        WHERE a.idarticulo = $id_articulo and $disponible >0 ";

        $dataProvider = new ActiveDataProvider([
            'query' => Sds_stk_movimiento::findBySql($consulta),
        ]);

        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => '',
            'id' => 'grilla_movimientos',
            'columns' => ['deposito', 'disponible'],
        ]);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Sds_stk_articulo();
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;

        $model->activo = 1;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Nuevo Articulo',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Guardar', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            } elseif ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $usuario = Mds_seg_usuario::findOne($idusuario);
                if ($usuario->organismo_stock) {
                    $model->organismo = $usuario->organismo_stock;
                }

                if ($guardado && $model->save()) {
                    $transaction->commit();
                    Mds_sys_log::guardarLog(
                        Mds_sys_log::ACCION_NUEVO,
                        'sds_stk_articulo',
                        $model->idarticulo,
                        $model->getAttributes()
                    );
                    return [
                        'title' => 'Nuevo Registro Tecnico',
                        'content' =>
                        '<span class="text-success">Registro Creado Correctamente</span>',
                        'footer' => Html::button('Cerrar', [
                            'id' => 'btnCerrar',
                            'id' => 'btnCerrar',
                            'class' => 'btn btn-default pull-left',
                            'data-dismiss' => 'modal',
                        ]),
                        Html::a(
                            'Create More',
                            ['create'],
                            [
                                'class' => 'btn btn-primary',
                                'role' => 'modal-remote',
                            ]
                        ),
                    ];
                } else {
                    return [
                        'title' => 'Error',
                        'content' =>
                        '<span class="text-success">Ya existe</span>',
                        'footer' => Html::button('Cerrar', [
                            'id' => 'btnCerrar',
                            'id' => 'btnCerrar',
                            'class' => 'btn btn-default pull-left',
                            'data-dismiss' => 'modal',
                        ]),
                    ];
                }
            }
        }
    }

    public function actionVerificar_existencia($articulo)
    {
        $articulo = Sds_stk_articulo::find()
            ->where(['descripcion' => "$articulo"])
            ->one();
        if ($articulo) {
            return 1;
        } else {
            return 0;
        }
    }
    public function actionCreate_ext()
    {
        $request = Yii::$app->request;
        $model = new Sds_stk_articulo();
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $model->activo = 1;
        $request = Yii::$app->request;
        if ($model->load($request->post())) {
            $usuario = Mds_seg_usuario::findOne($idusuario);
            if ($usuario->organismo_stock) {
                $model->organismo = $usuario->organismo_stock;
            }

            $model_movimiento = new Sds_stk_movimiento();
            $model_movimiento->tipo = 0;
            $model_movimiento->idarticulo = $model->idarticulo;
            $model_movimiento->fecha_hora = getdate();
            $model_movimiento->save();

            if ($model->save()) {
                Mds_sys_log::guardarLog(
                    Mds_sys_log::ACCION_NUEVO,
                    'sds_stk_articulo',
                    $model->idarticulo,
                    $model->getAttributes()
                );
                $model_movimiento = new Sds_stk_movimiento();
                $model_movimiento->tipo = 0;
                $model_movimiento->idarticulo = $model->idarticulo;
                $model_movimiento->fecha_hora = date('Y-m-d H:i:s'); //'2021-10-10'; //getdate();
                $model_movimiento->save();

                return $model->idarticulo;
            } else {
                return $this->renderAjax('//sds_stk_articulo/create', [
                    'model' => $model,
                    'botones' => true,
                ]);
            }
        } else {
            return $this->renderAjax('//sds_stk_articulo/create', [
                'model' => $model,
                'botones' => true,
            ]);
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
                    'title' => 'Editar Articulo Numero: ' . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Guardar', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            } elseif ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(
                    Mds_sys_log::ACCION_EDITAR,
                    'sds_stk_articulo',
                    $model->idarticulo,
                    $model->getAttributes()
                );
                return [
                    //'forceReload' => '#crud-datatable-pjax',
                    'title' => 'Articulo Numero: ' . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::a(
                            'Editar',
                            ['update', 'id' => $id],
                            [
                                'class' => 'btn btn-primary',
                                'role' => 'modal-remote',
                            ]
                        ),
                ];
            } else {
                return [
                    'title' => 'Editar Articulo Numero: ' . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' =>
                    Html::button('Cerrar', [
                        'id' => 'btnCerrar',
                        'class' => 'btn btn-default pull-left',
                        'data-dismiss' => 'modal',
                    ]) .
                        Html::button('Guardar', [
                            'id' => 'btnGuardar',
                            'class' => 'btn btn-primary',
                            'type' => 'submit',
                        ]),
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(
                    Mds_sys_log::ACCION_EDITAR,
                    'sds_stk_articulo',
                    $model->idarticulo,
                    $model->getAttributes()
                );
                return $this->redirect(['view', 'id' => $model->idarticulo]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(
                Mds_sys_log::ACCION_ELIMINAR,
                'sds_stk_articulo',
                $id,
                $model->getAttributes()
            );
        }

        if ($request->isAjax) {
            /*
             *   Process for ajax request
             */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceClose' => true,
                'forceReload' => '#crud-datatable-pjax',
            ];
        } else {
            /*
             *   Process for non-ajax request
             */
            return $this->redirect(['index']);
        }
    }

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
            return [
                'forceClose' => true,
                'forceReload' => '#crud-datatable-pjax',
            ];
        } else { */
    /*
             *   Process for non-ajax request
             */
    /* return $this->redirect(['index']);
        }
    } */

    protected function findModel($id)
    {
        if (($model = Sds_stk_articulo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(
                'The requested page does not exist.'
            );
        }
    }


    public function actionCmb_articulo($rubro = null)
    {
        //$articulos = Sds_stk_articulo::find()->where(["activo" => 1])->orderBy(['descripcion' => SORT_ASC])->all();
        $rubro = $rubro ?  " and rubro = $rubro " : "";

        $consulta = "SELECT A.idarticulo as idarticulo, CONCAT(A.descripcion,' (en ',C.descripcion,')') as descripcion
            FROM sds_stk_articulo A
            INNER JOIN sds_com_configuracion C on A.unidad_medida = C.idconfiguracion
            WHERE A.activo = 1 $rubro order by A.descripcion";
        $articulos = Sds_stk_articulo::findBySql($consulta)->all();
        $cmbArticulos = '';
        if (sizeof($articulos) > 0) {
            foreach ($articulos as $articulo) {
                $cmbArticulos =
                    $cmbArticulos .
                    "<option value='" .
                    $articulo->idarticulo .
                    "'>" .
                    $articulo->descripcion .
                    '</option>';
            }
        } else {
            $cmbArticulos = '<option value=null></option>';
        }
        return $cmbArticulos;
    }

    public static function actionGet_input_select2_articulos($form, $model, $atributo = null, $id_input = null, $label = null, $where = null, $onchange = null)
    {
        $where = $where ? "activo = 1 and $where " : null;
        $placeholder = 'Seleccione Articulo';
        $placeholder = $placeholder ? $placeholder : 'Articulos';
        $label = $label ? $label : 'Articulos';
        $id_input = $id_input ? $id_input : 'cmb_articulo';
        $atributo = $atributo ? $atributo : 'idarticulo';
        $datos = Sds_stk_articulo::find()->where($where)->orderby('descripcion')->all();
        return SiteController::actionGet_input_select2($form, $model, $atributo, $id_input, $datos, $label, $placeholder, $where, $onchange);
    }


    public function actionGet_stock_disponible($idarticulo)
    {
        $stock_ingresado = $this->actionGet_stock_ingresado($idarticulo);

        $stock_entregado = $this->actionGet_stock_entregado($idarticulo);

        $disponible = $stock_ingresado - $stock_entregado;

        return $disponible;
    }

    public function actionGet_stock_minimo($idarticulo)
    {
        $articulo = Sds_stk_articulo::findOne($idarticulo);
        return $articulo->stock_minimo;
    }

    public function actionGet_stock_ingresado($idarticulo)
    {
        $cantidad_ingresada = Sds_stk_recepcion_item::find()
            ->where(['idarticulo' => $idarticulo])
            ->sum('cantidad');

        if ($cantidad_ingresada == '') {
            $cantidad_ingresada = 0;
        }

        return $cantidad_ingresada;
    }

    public function actionGet_stock_entregado($idarticulo)
    {
        //$cantidad_entregada = Sds_stk_entrega_item::find()->where(['idarticulo' => $idarticulo])->sum('cantidad');

        $cantidad_entregada = Sds_stk_entrega_item::findBySql(
            'SELECT I.idarticulo as idarticulo, E.cantidad as cantidad FROM sds_stk_entrega_item E INNER JOIN sds_stk_recepcion_item I on E.recepcion_item = I.idrecepcionitem WHERE I.idarticulo = ' .
                $idarticulo
        )->sum('cantidad');
        if ($cantidad_entregada == '') {
            $cantidad_entregada = 0;
        }

        return $cantidad_entregada;
    }

    public function actionGet_boton_imprimir_responsables()
    {
        $desde = Yii::$app->request->post('desde');
        $hasta = Yii::$app->request->post('hasta');
        //$articulos = explode(',', Yii::$app->request->post('articulos'));
        $articulos = Yii::$app->request->post('articulos');
        $idcontacto = Yii::$app->request->post('idcontacto');

        $url = Url::to([
            '/sds_stk_articulo/imprimir_entregado_responsable',
            'desde' => $desde,
            'hasta' => $hasta,
            'idcontacto' => $idcontacto,
            'articulos' => $articulos,
        ]);

        return Html::a('<span class="fas fa-print"></span>', $url, [
            'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
            'data-toggle' => 'tooltip',
            'title' => ('Exportar PDF')
        ]);
    }


    public function actionImprimir_entregado_responsable($desde = null, $hasta = null, $idcontacto = null, $articulos = null)
    {
        $tabla = Sds_stk_entregaController::actionGet_grilla_responsables($desde, $hasta, $articulos, $idcontacto);
        $periodo = $desde ? "desde el $desde" : '';
        $periodo = $hasta ? "$periodo hasta el $hasta" : "$periodo";




        $content = $this->renderPartial('imprimir_entregado_responsable', [
            'tabla' => $tabla,
            'periodo' => $periodo,

        ]);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' =>
            '@vendor/kartik-v/yii2-mpdf/src/assets/style_table.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'ACTA DE ENTREGA A RESPONSABLES',
                'SetHeader' => null,
                'SetFooter' => null,
            ],
        ]);

        return $pdf->render();
    }
}
