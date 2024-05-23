<?php

namespace app\controllers;

use Yii;
use app\models\View_stock_detalle_oc;
use app\models\View_stock_detalle_ocSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * View_stock_detalle_ocController implements the CRUD actions for View_stock_detalle_oc model.
 */
class View_stock_detalle_ocController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    public function actionStock_articulo_grafico($idarticulo, $anio, $idordencompra = null, $organizacion_social = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $searchModel = new View_stock_detalle_ocSearch();
        $searchModel->idarticulo = $idarticulo;
        $searchModel->anio = $anio;
        $searchModel->idordencompra = $idordencompra;
        $searchModel->organizacion_social = $organizacion_social;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //Saco el acumulado de años anteriores.
        $searchModelAcumulado = new View_stock_detalle_ocSearch();
        $searchModelAcumulado->idarticulo = $idarticulo;
        $searchModelAcumulado->idordencompra = $idordencompra;
        $searchModelAcumulado->organizacion_social = $organizacion_social;
        $dataProviderAcumulado = $searchModelAcumulado->search(Yii::$app->request->queryParams);
        $stock_acumulado = 0;
        foreach ($dataProviderAcumulado->getModels() as $stock) {
            if ($stock->anio < $anio && $stock->tipo != 2) {
                $stock_acumulado = $stock_acumulado + $stock->cantidad;
            }
        }
        $result = array();
        $result['graphic'] = array();
        $result['categories'] = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        if ($organizacion_social == null || $organizacion_social == '') {
            //SERIE PARA GRAFICO DE SPLINE RECEPCIONES
            $recepciones = array();
            $recepciones['color'] = '#58BA68';
            $recepciones['name'] = 'Recepciones';
            $recepciones['type'] = 'spline';
            $recepciones['data'] = array();

            //SERIE PARA GRAFICO DE SPLINE TOTAL STOCK
            $total_stock = array();
            $total_stock['color'] = '#2D68DF';
            $total_stock['name'] = 'Stock';
            $total_stock['type'] = 'spline';
            $total_stock['data'] = array();
        }

        //SERIE PARA GRAFICO DE SPLINE ENTREGAS
        $entregas = array();
        $entregas['color'] = '#D53F3A';
        $entregas['name'] = 'Entregas';
        $entregas['type'] = 'spline';
        $entregas['data'] = array();

        //SERIE PARA GRAFICO DE TORTA
        $datos_torta['name'] = 'Total';
        $datos_torta['type'] = 'pie';
        $datos_torta['center'] = [100, 40];
        $datos_torta['size'] = 100;
        $datos_torta['showInLegend'] = false;
        $datos_torta['dataLabels'] = false;

        $stock_total = $stock_acumulado;
        for ($mes = 1; $mes <= sizeof($result['categories']); $mes++) {
            $cant_recepcion = 0;
            $cant_entrega = 0;
            foreach ($dataProvider->getModels() as $stock) {
                if ($stock->mes == $mes) {
                    if ($stock->tipo != 2) {
                        $cant_asignar = $stock->cantidad;
                        $stock_total = $stock_total + $cant_asignar;
                        if ($stock->tipo == 1 || ($stock->tipo == 4 && $cant_asignar > 0)) {
                            $cant_recepcion = $cant_asignar;
                        }
                        if ($stock->tipo == 3 || ($stock->tipo == 4 && $cant_asignar < 0)) {
                            $cant_entrega = -$cant_asignar;
                        }
                    }
                }
            }
            if ($organizacion_social == null || $organizacion_social == '') {
                array_push($total_stock['data'], $stock_total);
                array_push($recepciones['data'], $cant_recepcion);
            }
            array_push($entregas['data'], $cant_entrega);
        }
        if ($organizacion_social == null || $organizacion_social == '') {
            array_push($result['graphic'], $recepciones);
            array_push($result['graphic'], $total_stock);
        }
        array_push($result['graphic'], $entregas);
        $estado = null;
        $valor = 0;
        $totales_torta = array();
        $variables = $organizacion_social != null && $organizacion_social != "" ? 1 : 3;
        for ($i = 1; $i <= $variables; $i++) {
            $color = "#2D68DF";
            switch ($i) {
                case 3:
                    $estado = "Stock";
                    $valor = $stock_total;
                    $color = "#2D68DF";
                    break;
                case 2:
                    $estado = "Recepciones";
                    $valor = array_sum($recepciones['data']);
                    $color = "#58BA68";
                    break;
                case 1:
                    $estado = "Entregas";
                    $valor = array_sum($entregas['data']);
                    $color = "#D53F3A";
                    break;
            }

            $totales_torta['name'] = $estado;
            $totales_torta['y'] = $valor;
            $totales_torta['color'] = $color;

            $datos_torta['data'][] = $totales_torta;
        }
        array_push($result['graphic'], $datos_torta);
        $result['control_datos'] = array();
        array_push($result['control_datos'], $dataProvider->getModels());
        array_push($result['control_datos'], $stock_acumulado);

        return $result;
    }

    /**
     * Finds the View_stock_detalle_oc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $fecha_hora
     * @param integer $idarticulo
     * @param integer $deposito
     * @param integer $tipo
     * @param integer $organismo
     * @return View_stock_detalle_oc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($fecha_hora, $idarticulo, $deposito, $tipo, $organismo)
    {
        if (($model = View_stock_detalle_oc::findOne(['fecha_hora' => $fecha_hora, 'idarticulo' => $idarticulo, 'deposito' => $deposito, 'tipo' => $tipo, 'organismo' => $organismo])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
