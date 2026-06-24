<?php

namespace app\controllers;

use app\models\ConstantesGlobales;
use Yii;
use app\models\Empleado;
use app\models\EmpleadoSearch;
use app\models\LogPlataforma;
use app\models\Organismo;
use app\models\OrganismoDispositivo;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;


/**
 * EmpleadoController implements the CRUD actions for Empleado model.
 */
class EmpleadoController extends Controller
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
     * Lists all Empleado models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmpleadoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 50;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Empleado model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Empleado " . $id,
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
     * Creates a new Empleado model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($origen_alta = 0, $iddispositivo = null)
    {
        $request = Yii::$app->request;
        $model = new Empleado();

        $model->origen_alta = $origen_alta;

        if ($origen_alta == 1 && $iddispositivo != null) {
            $model->iddispositivo = $iddispositivo;
        }

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Nuevo Empleado',
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
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;

                if ($model->idpersona == null) $guardado = false;

                //Aca comienza el proceso de guardado de la imagen:
                //primero rescata los datos de la imagen cargados en el widget fileInput
                $tmpfile = UploadedFile::getInstance($model, 'imageFile');

                //
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;

                    $nuevo_nombre = "empleado-$model->legajo.$extension";
                    $model->foto = $nuevo_nombre;
                    $tmpfile->saveAs('img/empleados-fotos/' . $nuevo_nombre);
                } else {
                    $model->foto = "empleado_0.png";
                }

                $model->ingreso_real = ($model->ingreso_real == '0000-00-00') ? date('Y-m-d') : $model->ingreso_real;
                $model->ingreso_administrativo = ($model->ingreso_administrativo == '0000-00-00') ? date('Y-m-d') : $model->ingreso_administrativo;


                if ($guardado && $model->save()) {
                    $transaction->commit();
                    LogPlataforma::registrar(ConstantesGlobales::EMPLEADOS, ConstantesGlobales::CREACION, $model->idempleado);
                    return [
                        'title' => "Nuevo Empleado",
                        'content' => '<span class="text-success">Empleado Creado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                    ];
                }
            }
            return [
                'title' => "Nuevo Empleado, Faltan datos!!! Complete Los datos Faltantes!!!",
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
                    'title' => 'Editar Empleado',
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
            } else if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $guardado = true;


                $ingreso_real = ArmarDateParaMySql($model->ingreso_real);
                $ingreso_real = date_create($ingreso_real);
                $ingreso_real = date_format($ingreso_real, 'Y-m-d');
                $model->ingreso_real = $ingreso_real;

                $ingreso_administrativo = ArmarDateParaMySql($model->ingreso_administrativo);
                $ingreso_administrativo = date_create($ingreso_administrativo);
                $ingreso_administrativo = date_format($ingreso_administrativo, 'Y-m-d');
                $model->ingreso_administrativo = $ingreso_administrativo;

                if ($model->idpersona == null) $guardado = false;

                //Aca comienza el proceso de guardado de la imagen:
                //primero rescata los datos de la imagen cargados en el widget fileInput
                $tmpfile = UploadedFile::getInstance($model, 'imageFile');

                //
                if (isset($tmpfile)) {
                    $extension = $tmpfile->extension;

                    $nuevo_nombre = "empleado-$model->legajo.$extension";
                    $model->foto = $nuevo_nombre;
                    $tmpfile->saveAs('img/empleados-fotos/' . $nuevo_nombre);
                } /* else {
                    $model->foto = "empleado_0.png";
                } */

                if ($guardado && $model->save()) {
                    $transaction->commit();
                    LogPlataforma::registrar(ConstantesGlobales::EMPLEADOS, ConstantesGlobales::MODIFICACION, $model->idempleado);
                    return [
                        'title' => "Editar Empleado",
                        'content' => '<span class="text-success">Empleado Editado Correctamente</span>',
                        'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
                    ];
                }
            }
            return [
                'title' => "Editar Empleado, Faltan datos!!! Complete Los datos Faltantes!!!",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    /**
     * Delete an existing Empleado model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();
        LogPlataforma::registrar(ConstantesGlobales::EMPLEADOS, ConstantesGlobales::ELIMINACION, $id);
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
     * Delete multiple existing Empleado model.
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
     * Finds the Empleado model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Empleado the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Empleado::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionGet_dispositivo($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $empleado = Empleado::findOne($id);
        return $empleado->iddispositivo ?? null;
    }

    public function actionGet_por_dispositivo($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $sql = "SELECT e.idempleado, CONCAT(p.apellido, ' ', p.nombre) as descripcion
            FROM empleado e
            JOIN personas p ON p.idpersona = e.idpersona
            WHERE e.activo = 1 AND e.iddispositivo = $id
            ORDER BY p.apellido, p.nombre";
        return Empleado::findBySql($sql)->asArray()->all();
    }

    public function actionGet_empleados()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $sql = "SELECT e.idempleado, CONCAT(p.apellido, ' ', p.nombre) as descripcion
            FROM empleado e
            JOIN personas p ON p.idpersona = e.idpersona
            WHERE e.activo = 1 
            ORDER BY p.apellido, p.nombre";
        return Empleado::findBySql($sql)->asArray()->all();
    }

    public function actionMigracion_empleados($iddispositivo_viejo)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'title' => 'Migrar Empleados',
            'content' => $this->renderAjax('migrar_empleados', [
                'iddispositivo_viejo' => $iddispositivo_viejo,
            ]),
            'footer' =>
            Html::button('Cerrar', [
                'id' => 'btnCerrar',
                'class' => 'btn btn-default pull-left',
                'data-dismiss' => 'modal',
            ]) .
                Html::button('Migrar', [
                    'id' => 'btnMigrar',
                    'class' => 'btn btn-primary',
                    'type' => 'submit',
                    'form' => 'form-migrar-empleados', // Esto lo vincula mágicamente
                ]),
        ];
    }

    /*     public function actionMigrar_empleados_old($iddispositivo_viejo, $iddispositivo_nuevo)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;


        $sql = "UPDATE empleado SET iddispositivo = $iddispositivo_nuevo WHERE iddispositivo = $iddispositivo_viejo";
        Yii::$app->db->createCommand($sql)->execute();
        //y si falla?

        return [
            'title' => 'Empleados Migrados',
            'content' => "<span class='text-success'>Empleados del dispositivo migrados correctamente</span>",
            'footer' =>
            Html::button('Cerrar', [
                'id' => 'btnCerrar',
                'class' => 'btn btn-default pull-left',
                'data-dismiss' => 'modal',
            ])
        ];
    } */
    public function actionMigrar_empleados($iddispositivo_viejo)
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($request->isGet) {
            return [
                'title' => 'Migrar Empleados',
                'content' => $this->renderAjax('migrar_empleados', [
                    'iddispositivo_viejo' => $iddispositivo_viejo,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => 'modal']) .
                    Html::button('Migrar', ['id' => 'btnMigrar', 'class' => 'btn btn-primary', 'type' => 'submit']),
            ];
        }

        if ($request->isPost) {
            // Capturamos el ID del combo Select2
            $iddispositivo_nuevo = $request->post('iddispositivo_nuevo');


            if (!$iddispositivo_nuevo) {
                return [
                    'title' => 'Error',
                    'content' => '<span class="text-danger">Debe seleccionar un dispositivo de destino.</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])
                ];
            }

            try {
                $empleados = Empleado::get_por_dispositivo($iddispositivo_viejo);
                $lista_nombres = implode(', ', array_column($empleados, 'descripcion'));
                $sector_viejo = OrganismoDispositivo::get_dispositivo_pro($iddispositivo_viejo)->descripcion;
                $sector_nuevo = OrganismoDispositivo::get_dispositivo_pro($iddispositivo_nuevo)->descripcion;

                /* $txt_viejo = "Se retiraron de $sector_viejo los siguientes empleados: $lista_nombres";
            $txt_nuevo = "Se ingresaron a $sector_viejo los siguientes empleados: $lista_nombres"; */

                $sql = "UPDATE empleado SET iddispositivo = :nuevo WHERE iddispositivo = :viejo";
                Yii::$app->db->createCommand($sql)
                    ->bindValue(':nuevo', $iddispositivo_nuevo)
                    ->bindValue(':viejo', $iddispositivo_viejo)
                    ->execute();

                LogPlataforma::registrar(ConstantesGlobales::DISPOSITIVOS,ConstantesGlobales::MIGRACION_EGRESA_DATOS,$iddispositivo_viejo,"Migracion Saliente"); 

                foreach ($empleados as $e) {
                    // Acceso como array porque viene de asArray()
                    //$txt = "Se migró a {$e['descripcion']} de <b>$sector_viejo</b> a <b>$sector_nuevo</b>";

                    LogPlataforma::registrar(ConstantesGlobales::EMPLEADOS,ConstantesGlobales::MODIFICACION,$e['idempleado'],"Migracion");
                }

                LogPlataforma::registrar(ConstantesGlobales::DISPOSITIVOS,ConstantesGlobales::MIGRACION_INGRESA_DATOS,$iddispositivo_nuevo,"Migracion Entrante"); 
                return [
                    'title' => 'Empleados Migrados',
                    'content' => "<span class='text-success'>Empleados del dispositivo migrados correctamente.</span>",
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])
                ];
            } catch (\Exception $e) {
                return [
                    'title' => 'Error en la Base de Datos',
                    'content' => '<span class="text-danger">No se pudo realizar la migración: ' . $e->getMessage() . '</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default', 'data-dismiss' => 'modal'])
                ];
            }
        }
    }
}


function ArmarDateParaMySql($Fecha)
{
    $anio = substr($Fecha, 6, 4);
    $mes  = substr($Fecha, 3, 2);
    $dia = substr($Fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}
