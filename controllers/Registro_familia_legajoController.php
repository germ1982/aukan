<?php

namespace app\controllers;

use app\models\Configuracion;
use app\models\LogPlataforma;

use Yii;
use app\models\RegistroFamiliaLegajo;
use app\models\RegistroFamiliaLegajoSearch;
use Mpdf\Mpdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * Registro_familia_legajoController  RegistroFamiliaLegajo model.
 */
class Registro_familia_legajoController extends Controller
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
        ];
    }

    /**
     * Lists all RegistroFamiliaLegajo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegistroFamiliaLegajoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single RegistroFamiliaLegajo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $tipo = $model->tipo_legajo ? Configuracion::findOne($model->tipo_legajo)->descripcion : "";
        $numero = $model->num_legajo ? "Numero $model->num_legajo" : "";
        $documento  = $model->dni ? " DNI $model->dni" : "";

        $titulo = "Legajo $tipo $numero Perteneciente A $model->apellido $model->nombre $documento";

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => $titulo,
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

    /**
     * Creates a new RegistroFamiliaLegajo model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new RegistroFamiliaLegajo();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Crear nuevo Legajo",
                    'content' => $this->renderAjax('create', ['model' => $model]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $model->archivo_adjunto = "legajo_0.pdf";

                if ($guardado && $model->save()) {

                    $transaction->commit();
                    $tmpfile = UploadedFile::getInstance($model, 'archivo_adjunto_file');

                    if (isset($tmpfile)) {
                        $extension = $tmpfile->extension;

                        $nuevo_nombre = "reg_flia_legajo_$model->id.$extension";
                        $model->archivo_adjunto = $nuevo_nombre;
                        $tmpfile->saveAs('uploads/registro_familia_legajos/' . $nuevo_nombre);
                        $model->save();
                    }
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Crear nuevo Legajo",
                        'content' => '<span class="text-success">Legajo Creado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                        Html::a('Crear Otro', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote']),
                    ];
                }
            }
            return [
                'title' => "Nuevo Legajo, Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
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
                    'title' => "Actualizar Id: " . $id,
                    'content' => $this->renderAjax('update', ['model' => $model]),
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                        Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"]),
                ];
            } else if ($model->load($request->post())) {

                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                $tmpfile = UploadedFile::getInstance($model, 'archivo_adjunto_file');

                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;

                    $nuevo_nombre = "reg_flia_legajo_$model->id.$extension";
                    $model->archivo_adjunto = $nuevo_nombre;
                    $tmpfile->saveAs('uploads/registro_familia_legajos/' . $nuevo_nombre);
                }

                if ($guardado && $model->save()) {

                    $transaction->commit();
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Editar Legajo Id: " . $id,
                        'content' => '<span class="text-success">Legajo Editado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                    ];
                }
            }
            return [
                'title' => "Editar Legajo, Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', ['model' => $model,]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

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
     * Delete multiple existing RegistroFamiliaLegajo model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
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

    /**
     * Finds the RegistroFamiliaLegajo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RegistroFamiliaLegajo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RegistroFamiliaLegajo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionDescargar_archivo($archivo)
    {
        $ruta = Yii::getAlias('@webroot') . '/uploads/registro_familia_legajos/' . $archivo;
        if (file_exists($ruta)) {
            return Yii::$app->response->sendFile($ruta, $archivo, [
                'inline' => false // Forzar descarga directa
            ]);
        } else {
            throw new \yii\web\NotFoundHttpException("El archivo no existe.");
        }
    }
    



    public function actionExportar_pdf()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW; // Formato RAW para PDF
        // Set the correct content-type header for PDF
        \Yii::$app->response->headers->set('Content-Type', 'application/pdf');
        \Yii::$app->response->headers->set('Content-Disposition', 'inline; filename="archivo_unido.pdf"'); // 'inline' para abrir en una nueva pestaña

        $ids = Yii::$app->request->post('ids'); // Recibir IDs del POST

        if (!$ids) {
            return ['error' => 'No se recibieron IDs'];
        }

        $archivos = RegistroFamiliaLegajo::find()->where(['id' => $ids])->all();

        // Ruta a la carpeta donde se almacenan los archivos PDF
        $ruta_base = Yii::getAlias('@webroot') . '/uploads/registro_familia_legajos/';

        // Inicializar mPDF
        $mpdf = new \Mpdf\Mpdf();

        foreach ($archivos as $archivo) {
            $ruta_completa = $ruta_base . $archivo->archivo_adjunto;

            if (file_exists($ruta_completa)) {
                // Importar la primera página del archivo PDF
                $mpdf->SetSourceFile($ruta_completa);
                $tplIdx = $mpdf->ImportPage(1);
                $mpdf->UseTemplate($tplIdx);
                $mpdf->AddPage();
            } else {
                error_log("Archivo no encontrado: " . $ruta_completa);
            }
        }

        // Generar el PDF y enviarlo al navegador
        $mpdf->Output('archivo_unido.pdf', 'I'); // 'I' para mostrar en una pestaña
        // Liberar el buffer de salida para evitar problemas
        ob_clean(); // Limpia cualquier buffer de salida previo
        flush(); // Envia la salida al navegador
        // Finalizamos la ejecución para evitar que Yii haga más salidas
        Yii::$app->end(); // Asegura que no haya ninguna otra salida después de la generación del PDF
    }
}
