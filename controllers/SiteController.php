<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Usuarios;
use kartik\mpdf\Pdf;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;


class SiteController extends Controller
{
      
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
                                          return true; //Usuarios::isUserAdmin(Yii::$app->user->identity->id);
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
                                          return true; //Usuarios::isUserSimple(Yii::$app->Usuarios->identity->id);
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
            echo "<script>console.log('entro al actionIndex');</script>";
            return $this->render('home');
      }

      /**
       * Login action.
       *
       * @return Response|string
       */
      public function actionLogin()
      {
          // Establecer un layout específico para la página de login
          $this->layout = 'loginLayout';
  
          // Mensaje de depuración
          echo "<script>console.log('Entrando en actionLogin');</script>";
  
          // Si el usuario ya está autenticado, redirigir a la página principal
          if (!Yii::$app->user->isGuest) {
              echo "<script>console.log('El usuario ya está autenticado');</script>";
              return $this->redirect(["site/index"]);
          }
  
          // Crear una instancia del modelo LoginForm
          $model = new LoginForm();
  
          // Cargar los datos del formulario y validar
          if ($model->load(Yii::$app->request->post()) && $model->login()) {
              echo "<script>console.log('Inicio de sesión exitoso');</script>";
  
              // Obtener el ID del usuario autenticado
              $idUsuario = Yii::$app->user->identity->id;
              echo "<script>console.log('ID de usuario: ' + " . json_encode($idUsuario) . ");</script>";
  
              // Redirigir a la página principal
              return $this->redirect(["site/index"]);
          } else {
              echo "<script>console.log('Error en la autenticación o datos del formulario no cargados');</script>";
  
              // Renderizar la vista de login con el modelo
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

      public static function actionGet_input_select($form, $model, $atributo, $id_input, $datos, $iddatos, $descripciondatos, $label = null, $placeholder = null, $onchange = null)
      {
          $options = ['id' => $id_input];
      
          if ($placeholder) {
              $options['prompt'] = $placeholder;
          }
          if ($onchange) {
              $options['onchange'] = $onchange;
          }
      
          return $form->field($model, $atributo)
              ->dropDownList(ArrayHelper::map($datos, $iddatos, $descripciondatos), $options)
              ->label($label);
      }
      

      public static function actionGet_input_select2($form, $model, $atributo, $id_input, $datos, $iddatos, $descripciondatos, $label = null, $placeholder = null, $where = null, $onchange = null, $readonly = null, $disabled = null, $html = false)
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
                        'disabled' => $disabled,
                        'escapeMarkup' => ( new \yii\web\JsExpression('function(m) { return m; }')),  // No escapar el HTML
                  ],
            ])
                  ->label($label);
      }

      public static function actionGet_input_select2_multiple($form, $model, $atributo, $id_input, $datos, $iddatos, $descripciondatos, $label = null, $placeholder = null, $where = null, $readonly = null, $disabled = null)
      {
            $label = $label ? $label : '';
            $placeholder = $placeholder ? $placeholder : '';
            $where = $where ? $where : '';
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
                        //'onchange' => $onchange,
                        'multiple' => true

                  ],
                  'size' => Select2::MEDIUM,
                  'pluginOptions' => [
                        'tags' => true,
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
                  'style' => 'padding: 9px 12px;'
            ]);
      }

      
}
