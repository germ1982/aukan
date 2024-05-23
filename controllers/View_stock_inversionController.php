<?php

namespace app\controllers;

use app\models\Mds_seg_usuario;
use Yii;
use app\models\View_stock_inversion;
use app\models\View_stock_inversion_osSearch;
use app\models\View_stock_inversionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * View_stock_inversionController implements the CRUD actions for View_stock_inversion model.
 */
class View_stock_inversionController extends Controller
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

    /**
     * Lists all View_stock_inversion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new View_stock_inversionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    static public function actionGet_grilla_inversiones($idarticulo = null, $anio, $idos = 0)
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        $idorganismo = 0;
        if ($usuario->organismo_stock) {
            $idorganismo = $usuario->organismo_stock;
        }
        $searchModel = $idos > 0 || $idos == null ? new View_stock_inversion_osSearch() : new View_stock_inversionSearch();
        $searchModel->organismo = $idorganismo;
        $searchModel->idarticulo = $idarticulo;
        $searchModel->anio = $anio;
        if ($idos > 0 || $idos == null) {
            $searchModel->idorganizacionsocial = $idos;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $tabla = "<table class='table table-striped table-bordered table-hover' style='width:100%;border-collapse: separate;'>";
        $tabla = "$tabla<thead style='color: #27323d;'>
                            <tr>
                                <th style='position: sticky; top:0px;border: 1px solid #999;
                                background-color: #CCC;'><span class='col-md-4' 
                                style='padding-top:5px;'>Año</span>
                                </th>" .
            ($idos > 0 || $idos == null ?
                "<th style='position: sticky; top:0px;border: 1px solid #999;
                                background-color: #CCC;'><span class='col-md-4' 
                                style='padding-top:5px;'>Org.Soc.</span>
                                </th>" : "") .
            "<th style='position: sticky; top:0px;border: 1px solid #999;
                                background-color: #CCC;'><span class='col-md-4' 
                                style='padding-top:5px;'>Artículo</span>
                                </th>
                                <th style='position: sticky; top:0px;border: 1px solid #999;
                                background-color: #CCC;'><span class='col-md-4' 
                                style='padding-top:5px;'>Cantidad</span>
                                </th>
                                <th style='position: sticky; top:0px;border: 1px solid #999;
                                background-color: #CCC;'><span class='col-md-4' 
                                style='padding-top:5px;'>Importe</span>
                                </th>
                            </tr>
                        </thead><tbody>";
        $total_inversion = 0;
        foreach ($dataProvider->getModels() as $inversion) {
            $tabla = "$tabla<tr><td style='text-align:center'>$inversion->anio</td>" .
                ($idos > 0 || $idos == null ? "<td style='text-align:left'>$inversion->organizacionsocial</td>" : "") .
                "<td style='text-align:left'>$inversion->articulo</td>                                
                                <td style='text-align:center'>" . round($inversion->cantidad, 0) . "</td>
                                <td style='text-align:right'>$ " . number_format($inversion->importe, 2, ',', '.') . "</td>
                                </tr>";
            $total_inversion += $inversion->importe;
        }
        $tabla = "$tabla<tr style='color: #27323d;position: sticky; top:0px;border: 1px solid #999;
        background-color: #CCC;'><td style='text-align:center'></td>" .
                ($idos > 0 || $idos == null ? "<td style='text-align:left'></td>" : "") .
                "<td style='text-align:left'></td>                                
                                <td style='text-align:center'><b>TOTAL</b></td>
                                <td style='text-align:right'><b>$ " . number_format($total_inversion, 2, ',', '.') . "</b></td>
                                </tr>";
        $tabla = "$tabla</tbody></table>";
        //return $consulta;

        return $tabla;
    }

    /**
     * Displays a single View_stock_inversion model.
     * @param integer $organismo
     * @param integer $idarticulo
     * @param integer $anio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($organismo, $idarticulo, $anio)
    {
        return $this->render('view', [
            'model' => $this->findModel($organismo, $idarticulo, $anio),
        ]);
    }

    /**
     * Creates a new View_stock_inversion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new View_stock_inversion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'organismo' => $model->organismo, 'idarticulo' => $model->idarticulo, 'anio' => $model->anio]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing View_stock_inversion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $organismo
     * @param integer $idarticulo
     * @param integer $anio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($organismo, $idarticulo, $anio)
    {
        $model = $this->findModel($organismo, $idarticulo, $anio);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'organismo' => $model->organismo, 'idarticulo' => $model->idarticulo, 'anio' => $model->anio]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing View_stock_inversion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $organismo
     * @param integer $idarticulo
     * @param integer $anio
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($organismo, $idarticulo, $anio)
    {
        $this->findModel($organismo, $idarticulo, $anio)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the View_stock_inversion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $organismo
     * @param integer $idarticulo
     * @param integer $anio
     * @return View_stock_inversion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($organismo, $idarticulo, $anio)
    {
        if (($model = View_stock_inversion::findOne(['organismo' => $organismo, 'idarticulo' => $idarticulo, 'anio' => $anio])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
