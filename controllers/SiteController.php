<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario;
use app\models\Sds_his_admix;
use app\models\Sds_his_admixSearch;
use app\models\Sds_his_entregaSearch;
use app\models\Mds_seg_usuario_status;
use kartik\mpdf\Pdf;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Mds_sys_log;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'user', 'admin', 'create', 'update', 'view'],
                'rules' => [
                    [
                        //El administrador tiene permisos sobre las siguientes acciones
                        'actions' => ['logout', 'user', 'admin', 'create', 'update', 'view'],
                        //Esta propiedad establece que tiene permisos
                        'allow' => true,
                        //Usuarios autenticados, el signo ? es para invitados
                        'roles' => ['@'],
                        //Este método nos permite crear un filtro sobre la identidad del usuario
                        //y así establecer si tiene permisos o no
                        'matchCallback' => function ($rule, $action) {
                            //Llamada al método que comprueba si es un administrador
                            return Mds_seg_usuario::isUserAdmin(Yii::$app->user->identity->id);
                        },
                    ],
                    [
                        //Los usuarios simples tienen permisos sobre las siguientes acciones
                        'actions' => ['logout', 'user'],
                        //Esta propiedad establece que tiene permisos
                        'allow' => true,
                        //Usuarios autenticados, el signo ? es para invitados
                        'roles' => ['@'],
                        //Este método nos permite crear un filtro sobre la identidad del usuario
                        //y así establecer si tiene permisos o no
                        'matchCallback' => function ($rule, $action) {
                            //Llamada al método que comprueba si es un usuario simple
                            return Mds_seg_usuario::isUserSimple(Yii::$app->Mds_seg_usuario->identity->id);
                        },
                    ],
                ],
            ],
            //Controla el modo en que se accede a las acciones, en este ejemplo a la acción logout
            //sólo se puede acceder a través del método post
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('home');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'loginLayout';
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(["site/index"]);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $idUsuario = Yii::$app->user->identity->idusuario;
            $tokenCaptcha = isset(Yii::$app->request->post()['g-recaptcha-response']) ? Yii::$app->request->post()['g-recaptcha-response'] : null;

            /*
            Cuando configuren el proxy cambiar el true por:
            $this->verificarRecaptcha($tokenCaptcha)
            */
            if (true) {
                $usuario = Mds_seg_usuario::findOne($idUsuario);

                $this->loginApiSurWs($usuario, $model);
                $this->loginApiSurNest($usuario->user, $model->password, $idUsuario);

                if (!empty(Yii::$app->user->returnUrl)) {
                    return $this->redirect(Yii::$app->user->returnUrl);
                }
                return $this->redirect(["site/index"]);
            } else {
                $modelSegUsuarioStatus = new Mds_seg_usuario_status();
                $modelSegUsuarioStatus->idusuario = $idUsuario;
                $modelSegUsuarioStatus->created_at = date('Y-m-d H:i:s');
                $modelSegUsuarioStatus->idusuario_carga = $idUsuario;
                $modelSegUsuarioStatus->idestado = Mds_seg_usuario_status::ESTADO_ERROR_CAPTCHA;
                $modelSegUsuarioStatus->save();
                $model->addError('password', 'Error al validar el captcha de seguridad');
                Yii::$app->user->logout();
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        } else {
            $model->recaptchaToken = env('CAPTCHA_SITE_KEY');
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        $this->layout = 'loginLayout';
        Yii::$app->user->logout();
        $model = new LoginForm();
        return $this->redirect([
            'login',
            'model' => $model,
        ]);
    }

    public function actionViolencia()
    {
        return $this->render('violencia/index', [
            //'model' => $model,
        ]);
    }

    public function actionCruce()
    {
        return $this->render('cruce', [
            //'model' => $model,
        ]);
    }

    public function actionCruce_historico($dni = null)
    {
        if ($dni != null) {
            $searchModelEntrega = new Sds_his_entregaSearch();
            $searchModelEntrega->numero_documento = $dni;
            $dataProviderEntrega = $searchModelEntrega->search(Yii::$app->request->queryParams);

            $searchModelSubsidio = new Sds_his_admixSearch();
            $searchModelSubsidio->documento_numero = $dni;
            $dataProviderSubsidio  = $searchModelSubsidio->search(Yii::$app->request->queryParams);

            return $this->render('historico/cruce_historico', [
                'dni' => $dni,
                'dataProviderEntrega' => $dataProviderEntrega,
                'dataProviderSubsidio' => $dataProviderSubsidio
            ]);
        } else {
            return $this->render('historico/cruce_historico', [
                'dni' => null
            ]);
        }
    }

    public function actionReporte_historico($dni)
    {
        $searchModelEntrega = new Sds_his_entregaSearch();
        $searchModelEntrega->numero_documento = $dni;
        $dataProviderEntrega = $searchModelEntrega->search(Yii::$app->request->queryParams);

        $searchModelSubsidio = new Sds_his_admixSearch();
        $searchModelSubsidio->documento_numero = $dni;
        $dataProviderSubsidio  = $searchModelSubsidio->search(Yii::$app->request->queryParams);

        $content = $this->renderPartial('historico/reporte_historico', [
            'dni' => $dni,
            'dataProviderEntrega' => $dataProviderEntrega,
            'dataProviderSubsidio' => $dataProviderSubsidio
        ]); // setup kartik\mpdf\Pdf component 

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
                'SetTitle' => 'Reporte Historico PDF',
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);

        return $pdf->render();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public static function actionGet_input_select2($form, $model, $atributo, $id_input, $datos, $iddatos, $descripciondatos, $label = null, $placeholder = null, $where = null, $onchange = null, $readonly = null, $disabled = null)
    {
        $label = $label ? $label : '';
        $placeholder = $placeholder ? $placeholder : '';
        $where = $where ? $where : '';
        $onchange = $onchange ? $onchange : '';
        $readonly = $readonly ? $readonly : false;
        $disabled = $disabled ? $disabled : false;
        return $form->field($model, $atributo)->widget(Select2::classname(), [
            'data' => ArrayHelper::map(
                $datos,
                $iddatos,
                $descripciondatos
            ),
            'options' => [
                'id' => $id_input,
                'placeholder' => $placeholder,
                'onchange' => $onchange,

            ],
            'pluginOptions' => [
                'allowClear' => true,
                'disabled' => $disabled
            ],
        ])
            ->label($label);
    }



    public static function actionGet_input_fecha($form, $model, $atributo, $id_input, $label = null, $readonly = null, $fecha_hasta = null, $disabled = false, $onchange = null)
    {
        /* Esta funcion crea control de la fecha usando una sola linea desde donde sea invocada,
        se pasan como parametros el form y model que se este usando, el atributo de fecha del modelo para 
        el que se quiere usar, el id del control para usar con javascrip, y opcional el label. */
        $readonly = $readonly ? $readonly : false;
        $onchange = $onchange ? $onchange : '';
        return $form->field($model, $atributo)->widget(DatePicker::ClassName(), [
            'name' => 'check_issue_date',
            'language' => 'es',
            'readonly' => $readonly,
            'layout' => '{picker}{input}',

            'options' => [
                'class' => 'form-control input-md',
                'id' => $id_input,
                'disabled' => $disabled,
                'placeholder' => 'DD / MM / YYYY',
                'onchange' => $onchange,
            ],
            'pluginOptions' => [
                'value' => null,
                'format' => 'dd/mm/yyyy',
                'endDate' => $fecha_hasta,
                'todayHighlight' => true,
                'autoclose' => true,
            ]
        ])->label($label);
    }
    public static function actionGet_input_hora($form, $model, $atributo, $id_input, $label = null, $readonly = false, $disabled = false)
    {
        /* Esta funcion crea control de la hora usando una sola linea desde donde sea invocada,
        se pasan como parametros el form y model que se este usando, el atributo de fecha del modelo para 
        el que se quiere usar, el id del control para usar con javascrip, y opcional el label. */
        return $form->field($model, $atributo)->widget(TimePicker::classname(), [
            'options' => [
                'id' => $id_input,
                'tabIndex' => '1',
                'disabled' => $disabled,
                'readonly' => $readonly
            ],
            'pluginOptions' => [
                'showSeconds' => false,
                'showMeridian' => false,
                'minuteStep' => 15,
            ]
        ])->label($label);
    }

    public static function actionGet_boton_buscar_x_documento($id_boton, $titulo, $funcion_onclick)
    {
        /* esta funcion crea un boton de busqueda en una sola linea.
        se pasan por parametros el id del boton por si quiero usarlo en javascript,
        un titulo, y la funcion javascript que va a desarrollar la busqueda o validacion. */
        return Html::a('<i class="glyphicon glyphicon-search"></i>', null, [
            'name' => $id_boton,
            'id' => $id_boton,
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'class' => 'btn btn-primary',
            'title' => Yii::t('app', $titulo),
            'onclick' => $funcion_onclick,
        ]);
    }

    protected function loginApiSurWs($usuario, $model)
    {
        $ch = curl_init();
        $myvars = [
            'servicio' => 'login_sur',
            'auditoria' => $usuario->user,
            'usuario_auditoria' =>  $usuario->user,
            'filtro' => 'user=' . $usuario->user . '&pass=' . $model->password,
            'tipo' => 0
        ];
        curl_setopt($ch, CURLOPT_URL, "https://apisur.neuquen.gov.ar/index.php");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $records = curl_exec($ch);
        if ($records) {
            $records = json_decode($records);
            if ($usuario) {
                $usuario->accessToken = !empty($records->records) ? $records->records[0]->token : null;
                $usuario->save();
                $permisos = Mds_seg_permiso::getPermisosByIdUsuario($usuario->idusuario)->all();
                foreach ($permisos as $permiso) {
                    if ($permiso->iditem == Mds_seg_item::STK_CARGA_DEPOSITO) {
                        return $this->redirect(["/sds_stk_entrega", "celular" => "true"]);
                    }
                }
            }
        }
    }

    protected function loginApiSurNest($user, $pass, $idUsuario)
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'LOGIN_SUR_API_NEST', $idUsuario, array());
        $token = '';

        $postData = [
            'user' => $user,
            'pass' => $pass
        ];

        $this->limpiarTokenNest();

        try {
            // Inicializa la biblioteca Curl
            $curl = curl_init();

            // Configura la URL de destino
            $url = env('ENDPOINT_API_SUR_NEST') . "/" . env('ENDPOINT_API_SUR_NEST_LOGIN');

            // Configura las opciones de la solicitud Curl
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            // Realiza la solicitud GET
            $response = curl_exec($curl);
            $response = json_decode($response);
            // Cierra la sesión Curl
            curl_close($curl);

            if ($response && $response->records && $response->records[0]) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'LOGIN_SUR_API_NEST_SUCCESS', $idUsuario, array());
                $token = $response->records[0]->token;

                if ($token) {
                    $_SESSION["tokenNest"] = $token;
                }
            } else {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'LOGIN_SUR_API_NEST_FAIL', $idUsuario, array());
            }
        } catch (\Exception $e) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'LOGIN_SUR_API_NEST_ERROR', $idUsuario, array());
        }
    }

    protected function verificarRecaptcha($token)
    {
        $success = false;

        $secretKey = env('CAPTCHA_SECRET_KEY');
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $data = [
            'secret' => $secretKey,
            'response' => $token,
        ];

        $options = [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
        ];

        try {
            $ch = curl_init($url);
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            curl_close($ch);
            if ($response) {
                $responseData = json_decode($response);
                $success = $responseData->success;
            }
        } catch (\Exception $e) {
        }

        return $success;
    }

    protected function limpiarTokenNest()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION["tokenNest"] = null;
    }
}
