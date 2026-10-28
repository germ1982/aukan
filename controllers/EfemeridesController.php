<?php

namespace app\controllers;

use Yii;
use app\models\Efemerides;
use app\models\EfemeridesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * EfemeridesController implements the CRUD actions for Efemerides model.
 */
class EfemeridesController extends Controller
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
     * Lists all Efemerides models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EfemeridesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Efemerides model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Efemerides #" . $id,
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

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Efemerides();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Create new Efemerides",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => 'submit'])
                ];
            } elseif ($model->load($request->post()) && $model->save()) {
                // Guarda las relaciones de reglas y procesa las fotos con el formato requerido
                $this->guardarRelacionesYFotos($model);

                return [
                    //'forceReload' => '#crud-datatable-pjax',
                    'title' => "Create new Efemerides",
                    'content' => '<span class="text-success">Create Efemerides success</span>',
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                        Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Create new Efemerides",
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => 'submit'])
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                $this->guardarRelacionesYFotos($model);
                return $this->redirect(['view', 'id' => $model->idefemeride]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->loadReglaData(); // Carga la regla asociada si la tiene

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => "Update Efemerides #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => 'submit'])
                ];
            } elseif ($model->load($request->post()) && $model->save()) {
                // Actualiza reglas y acumula nuevas fotos sin tocar las existentes
                $this->guardarRelacionesYFotos($model);

                return [
                    //'forceReload' => '#crud-datatable-pjax',
                    'title' => "Update Efemerides #" . $id,
                    'content' => $this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                        Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                ];
            } else {
                return [
                    'title' => "Update Efemerides #" . $id,
                    'content' => $this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                        Html::button('Save', ['class' => 'btn btn-primary', 'type' => 'submit'])
                ];
            }
        } else {
            if ($model->load($request->post()) && $model->save()) {
                $this->guardarRelacionesYFotos($model);
                return $this->redirect(['view', 'id' => $model->idefemeride]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionDeleteFoto()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $idfoto = Yii::$app->request->post('idfoto');

        $fotoDb = (new \yii\db\Query())
            ->from('efemerides_fotos')
            ->where(['idfoto' => $idfoto])
            ->one();

        if ($fotoDb) {
            // Borra el archivo físico de la carpeta de inmediato
            $filePath = Yii::getAlias('@webroot/img/efemerides/') . $fotoDb['foto'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            // Borra el registro de la base de datos de inmediato
            Yii::$app->db->createCommand()->delete('efemerides_fotos', ['idfoto' => $idfoto])->execute();
            return ['success' => true];
        }
        return ['success' => false];
    }

    private function guardarRelacionesYFotos($model)
    {
        $connection = Yii::$app->db;

        // 1. GESTIÓN DE REGLA DINÁMICA
        $connection->createCommand()->delete('efemerides_reglas', ['idefemeride' => $model->idefemeride])->execute();

        if (!empty($model->regla) && $model->regla == 1) {
            $connection->createCommand()->insert('efemerides_reglas', [
                'idefemeride' => $model->idefemeride,
                'num_semana' => $model->num_semana,
                'dia_semana' => $model->dia_semana,
            ])->execute();
        }

        // 2. GESTIÓN DE FOTOS MÚLTIPLES (Formato efemerides_fecha_000 + Compresión automática)
        $archivos = UploadedFile::getInstances($model, 'fotos');
        if (!empty($archivos)) {
            $fechaStr = str_replace('-', '', $model->fecha); // Quita guiones a la fecha

            $maxOrden = (int) (new \yii\db\Query())
                ->from('efemerides_fotos')
                ->where(['idefemeride' => $model->idefemeride])
                ->max('orden');

            $path = Yii::getAlias('@webroot/img/efemerides/');
            if (!is_dir($path)) {
                mkdir($path, 0775, true);
            }

            foreach ($archivos as $file) {
                $seq = 0;
                do {
                    $fileName = 'efemerides_' . $fechaStr . '_' . str_pad($seq, 3, '0', STR_PAD_LEFT) . '.' . $file->extension;
                    $filePath = $path . $fileName;
                    $existsInDb = (new \yii\db\Query())->from('efemerides_fotos')->where(['foto' => $fileName])->exists();
                    $seq++;
                } while (file_exists($filePath) || $existsInDb);

                $seq--;
                $fileName = 'efemerides_' . $fechaStr . '_' . str_pad($seq, 3, '0', STR_PAD_LEFT) . '.' . $file->extension;
                $filePath = $path . $fileName;

                // Comprime y guarda la imagen automáticamente (máx 1200px, 80% calidad)
                if ($this->comprimirYGuardarImagen($file, $filePath, 1200, 1200, 80)) {
                    $maxOrden++;
                    $connection->createCommand()->insert('efemerides_fotos', [
                        'idefemeride' => $model->idefemeride,
                        'foto' => $fileName,
                        'orden' => $maxOrden,
                    ])->execute();
                }
            }
        }
    }

    /**
     * Comprime, redimensiona y guarda la imagen utilizando la librería GD de PHP.
     */
    private function comprimirYGuardarImagen($file, $destination, $maxWidth = 1200, $maxHeight = 1200, $quality = 80)
    {
        // Si la extensión GD no está habilitada en PHP, guarda el archivo directamente sin comprimir
        if (!extension_loaded('gd')) {
            return $file->saveAs($destination);
        }

        $tmpPath = $file->tempName;
        list($width, $height, $type) = getimagesize($tmpPath);

        if (!$width || !$height) {
            return $file->saveAs($destination);
        }

        $ratio = $width / $height;
        if ($maxWidth / $maxHeight > $ratio) {
            $newWidth = $maxHeight * $ratio;
            $newHeight = $maxHeight;
        } else {
            $newHeight = $maxWidth / $ratio;
            $newWidth = $maxWidth;
        }

        if ($width < $maxWidth && $height < $maxHeight) {
            $newWidth = $width;
            $newHeight = $height;
        }

        $src = null;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $src = imagecreatefromjpeg($tmpPath);
                break;
            case IMAGETYPE_PNG:
                $src = imagecreatefrompng($tmpPath);
                break;
            case IMAGETYPE_WEBP:
                $src = imagecreatefromwebp($tmpPath);
                break;
            default:
                return $file->saveAs($destination);
        }

        if (!$src) {
            return $file->saveAs($destination);
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);

        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP) {
            imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $success = false;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $success = imagejpeg($dst, $destination, $quality);
                break;
            case IMAGETYPE_PNG:
                $pngCompression = (int)round((100 - $quality) / 11.11);
                $success = imagepng($dst, $destination, $pngCompression);
                break;
            case IMAGETYPE_WEBP:
                $success = imagewebp($dst, $destination, $quality);
                break;
        }

        imagedestroy($src);
        imagedestroy($dst);

        return $success;
    }
    /**
     * Delete an existing Efemerides model.
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
     * Delete multiple existing Efemerides model.
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
     * Finds the Efemerides model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Efemerides the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Efemerides::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
