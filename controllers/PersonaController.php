<?php

namespace app\controllers;

use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\ConstantesGlobales;
use app\models\Empleado;
use app\models\LogPlataforma;
use Yii;
use app\models\Persona;
use app\models\PersonaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Html;

/**
 * PersonaController implements the CRUD actions for Persona model.
 */
class PersonaController extends Controller
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
     * Lists all Persona models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=50;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Persona model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Persona Id " . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::a('Editar', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
            ];
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }
    /**
     * Creates a new Persona model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Persona();

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($request->isGet) {
                return [
                    'title' => 'Nueva Persona',
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
            }
            else if ($model->load($request->post())) {
                  $transaction = Yii::$app->db->beginTransaction();
                  $guardado = true;

                    $model->padre = null;
                    $model->conviviente=0;
                  
                  if ($guardado && $model->save()) {
                      $transaction->commit();
                      LogPlataforma::registrar(ConstantesGlobales::PERSONAS,ConstantesGlobales::CREACION,$model->idpersona);
                      return [
                          'title' => "Nueva Persona",
                          'content' => '<span class="text-success">Nodo Creada Correctamente</span>',
                          'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                      ];
                  }
              }
              return [
                  'title' => "Nueva Persona Faltan datos!!! Complete Los datos Faltantes!!!",
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
                    'title' => 'Editar Persona',
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
            else if ($model->load($request->post())) {
                  $transaction = Yii::$app->db->beginTransaction();
                  $guardado = true;

                  /* var_dump($model->conviviente); die; // <-- agregá esto temporalmente */
                  if ($guardado && $model->save()) {
                      $transaction->commit();
                      LogPlataforma::registrar(ConstantesGlobales::PERSONAS,ConstantesGlobales::MODIFICACION,$model->idpersona);
                      return [
                          'title' => "Editar Persona",
                          'content' => '<span class="text-success">Persona Editada Correctamente</span>',
                          'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                      ];
                  }
              }
              return [
                  'title' => "Editar Persona Faltan datos!!! Complete Los datos Faltantes!!!",
                  'content' => $this->renderAjax('create', [
                      'model' => $model,
                  ]),
                  'footer' => Html::button('Cerrar', ['id' => 'btnCerrar', 'class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                      Html::button('Guardar', ['id' => 'btnGuardar', 'class' => 'btn btn-primary', 'type' => "submit"])
  
              ];
          }
    }

    /**
     * Deletes an existing Persona model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        LogPlataforma::registrar(ConstantesGlobales::PERSONAS,ConstantesGlobales::ELIMINACION,$id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Persona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Persona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Persona::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionValidar_dni($dni)
    {
        $result = [];
        $model_persona = Persona::find()
            ->where(['documento' => $dni])
            ->one();
        if ($model_persona != null) {
            array_push($result, $model_persona->getAttributes());
        }
        return json_encode($result);
    }

    public function actionGet_cuil($dni, $genero)
    {
        $persona = Persona::find()->where(['documento' => $dni])->one();
        if ($persona) {
            $empleado = Empleado::find()->where(['idpersona' => $persona->idpersona])->one();
            if ($empleado && !empty($empleado->cuil)) {
                return $empleado->cuil;
            }
        }
        $genero = ($genero == 20) ? 'F' : 'M'; // Asumiendo que 20 es femenino y 21 es masculino, ajusta según tu lógica
        $curl = curl_init();
        $SSLCERT_PATH = env('SSLCERT_PATH');
        $SSLKEY_PATH = env('SSLKEY_PATH');

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://xroadmingobierno.neuquen.gob.ar/r1/OPTIC/GOB/GOB00001/GP-RENAPER/WS_RENAPER_DOCUMENTO/' . $dni . '/' . $genero,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSLCERT => $SSLCERT_PATH,
            CURLOPT_SSLKEY => $SSLKEY_PATH,
            CURLOPT_POSTFIELDS => 'servicio=get_persona_renaper&filtro%20=documento%3D' . $dni . '%26sexo%3D' . $genero . '&auditoria=sur&usuario_auditoria=sur&tipo=0',
            CURLOPT_HTTPHEADER => array(
                'x-road-client: OPTIC/GOB/GOB00018/GP-SUBSEFAMILIA',
                'Content-Type: application/x-www-form-urlencoded'
            ),

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $renaper_data = json_decode($response, true);

        // Si no hay datos válidos, devolvés cadena vacía
        if (!isset($renaper_data['data']) || empty($renaper_data['data']['apellido'])) {
            return '';
        }
        return $renaper_data['data']['cuil'] ?? '';
    }
    public function actionGet_persona($dni)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // 1. Buscar en BD
        $model = Persona::find()->where(['documento' => $dni])->one();
        if ($model) {
            return $model->toArray(); // 🔥 devuelvo array para JS
        }

        // 2. Intentar RENAPER con F y M
        $data = '';

        $generos = ['F' => 20, 'M' => 21];

        foreach ($generos as $generoLetra => $generoCodigo) {
            $renaper_response = $this->actionGet_persona_renaper($dni, $generoLetra);
            $renaper_data = json_decode($renaper_response, true);

            if (isset($renaper_data['data'])) {
                $data = $renaper_data['data'];
                $data['documento'] = $dni;
                $data['genero'] = $generoCodigo;
                //return $data; // 🔥 devuelvo datos de RENAPER para JS
                $guardar = $this->actionGuardar_persona_desde_renaper($data);

                if ($guardar !== null) {
                        return $guardar->toArray();
                    }          

                return '';
            }
        }

        return '';
    }

    
    public function actionGuardar_persona_desde_renaper($data)
    {

        $model = new Persona();
        
        $model->apellido = $data['apellido'] ?? null;
        
        $model->nombre = $data['nombres'] ?? null;
        $model->documento = $data['documento'] ?? null;
        
        // Corrección para fecha de nacimiento
        if (isset($data['fecha_nacimiento']) && !empty($data['fecha_nacimiento'])) {
            $model->fecha_nacimiento = \DateTime::createFromFormat('d/m/Y', $data['fecha_nacimiento'])->format('Y-m-d');
        } else {
            $model->fecha_nacimiento = null;
        }

        // Corrección para fecha de fallecimiento (la clave del problema)
        if (isset($data['fecha_fallecimiento']) && !empty($data['fecha_fallecimiento'])) {
            $model->fecha_fallecimiento = \DateTime::createFromFormat('d/m/Y', $data['fecha_fallecimiento'])->format('Y-m-d');
        } else {
            $model->fecha_fallecimiento = null;
        }
        
        $model->documento_tipo = 2; // valor por defecto
        $model->nacionalidad = $this->get_nacionalidad($data['nacionalidad']);   // Argentina, por ejemplo
        $model->genero = $data['genero'] ?? null;         // Masculino/Femenino, según el contexto

        $model->domicilio_calle = $data['calle'] ?? null;
        $model->domicilio_numero = $data['numero'] ?? '0';
        $model->domicilio = $data['monoblock'] ?? null . ' ' . ($data['piso'] ?? '') . ' ' . ($data['depto'] ?? '');
        $model->idlocalidad = null; // podés resolverlo con lógica propia

        $model->conviviente = 0;
        $model->padre = null;
        $model->madre = null;


        if ($model->save()) {
            LogPlataforma::registrar(ConstantesGlobales::PERSONAS,ConstantesGlobales::CREACION,$model->idpersona,"Se trajo de RENAPER");
            return $model;
        } else {
            // Log de errores si querés investigar
            Yii::error($model->getErrors(), 'persona_renaper');
            return null;
        }
    }

    public function actionGet_persona_renaper($dni, $genero)
    {
        $curl = curl_init();

        $SSLCERT_PATH = env('SSLCERT_PATH');
        $SSLKEY_PATH = env('SSLKEY_PATH');

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://xroadmingobierno.neuquen.gob.ar/r1/OPTIC/GOB/GOB00001/GP-RENAPER/WS_RENAPER_DOCUMENTO/' . $dni . '/' . $genero,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSLCERT => $SSLCERT_PATH,
            CURLOPT_SSLKEY => $SSLKEY_PATH,
            CURLOPT_POSTFIELDS => 'servicio=get_persona_renaper&filtro%20=documento%3D' . $dni . '%26sexo%3D' . $genero . '&auditoria=sur&usuario_auditoria=sur&tipo=0',
            CURLOPT_HTTPHEADER => array(
                'x-road-client: OPTIC/GOB/GOB00018/GP-SUBSEFAMILIA',
                'Content-Type: application/x-www-form-urlencoded'
            ),

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $renaper_data = json_decode($response, true);

        // Si no hay datos válidos, devolvés cadena vacía
        if (!isset($renaper_data['data']) || empty($renaper_data['data']['apellido'])) {
            return '';
        }
        return $response;
    }

    public function actionActualizar_persona_renaper($idpersona){

    $model = $this->findModel($idpersona);
    
    }

    public static function get_nacionalidad($nacionalidad)
    {
        $nac = Configuracion::find()
            ->where(['id_configuracion_tipo' => ConfiguracionTipo::NACIONALIDAD])
            ->andWhere(['like', 'descripcion', $nacionalidad])
            ->one();

        return $nac->id_configuracion ?? null;
    }


}
