<?php

namespace app\controllers;

use app\components\AccessRule;
use Yii;
use app\models\Mds_atp_alta;
use app\models\Mds_atp_altaSearch;
use DateTime;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Mds_atp_solicitud;
use app\models\Mds_atp_sucursal;
use app\models\Mds_seg_item;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_localidad;
use Exception;
use yii\filters\AccessControl;

/**
 * Mds_atp_altaController implements the CRUD actions for Mds_atp_alta model.
 */
class Mds_atp_altaController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index', 'create', 'view', 'change_estado'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'view', 'change_estado'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => [
                            Mds_seg_item::MODULO_ATP_ALTA,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Mds_atp_alta models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_atp_altaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_atp_alta model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->fechahora = date('d-m-Y H:i', strtotime($model->fechahora));
        $usuario = Mds_seg_usuario::findOne($model->idusuario);
        $model->idusuario = $usuario->nombre . " " . $usuario->apellido;
        $model->estado = $this->getEstado($model->estado);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "ATPCen Alta N°" . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Mds_atp_alta model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_atp_alta();

        $model->fechahora = date('Y-m-d H:i');

        $usuario = Yii::$app->user->identity;
        $model->idusuario = $usuario->idusuario;

        $model->path = 'undefined';

        $model->estado = 0;

        // Buscar todas las sucursales
        $sucursales = Mds_atp_solicitud::find()->all();

        // Buscar la cantidad de altas generadas
        $count_altas = Mds_atp_solicitud::find()->where(['estado' => Mds_atp_solicitud::PENDIENTE_ALTA])->count();

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isGet) {
            return [
                'title' => "Generar Altas",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                    'sucursales' => $sucursales,
                    'count_altas' => $count_altas
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Generar Altas', ['class' => 'btn btn-primary', 'type' => "submit"])

            ];
        } else if ($model->load($request->post())) {

            if (!$model->idsucursal) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Generar Altas",
                    'content' => '<span class="text-error">Por favor, seleccione una sucursal antes de continuar.</span>',
                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                ];
            }

            # Numero de cinta - Numero correlativo de cinta enviada por la Entidad
            $numero_de_cinta = "000007";
            $dia = date("dmy");
            $header = "000042" . $numero_de_cinta . $dia . "12:11:42SO206D";
            $header_ultima = $this->completarTexto(468, "");
            $header = $header . $header_ultima;
            # Completo header

            # TRAILER COMPLETAR
            $trailer_ = "9990000084";
            $trailer_header = $this->completarNumero(15, "3570");
            $trailler_filler = $this->completarTexto(475, "");
            $trailer_fila = $trailer_ . $trailer_header . $trailler_filler;
            # FIN TRAILER

            $codigo_movimiento = "143";

            $fecha_movimiento = date("dmy");
            $entidad = "042";

            $digito_verificado = "0"; // no tengo el digito verificador        
            $filler_numerico = "000000000";
            $filler_texto = "        ";

            $codigo_postal = "00000";

            $cod_provincia = "16";

            $codigo_cedula = "  ";
            $CEDULA = "         ";
            $cod_documento = "DU";

            $nacionalidad_texto = "ARG";

            $texto_intermedio = "60R000000000000000  00100000000000000011001120000000    0000000   115849";

            $texto_intermedio2 = $this->completarTexto(62, "") . "0000000" . "100" . $this->completarTexto(59, "");

            $texto_final = "4100                 000TICKE          TICKE111000000000000000000000000123001                       ";

            $solicitud_actual = null;

            try {
                // Recuperar de la tabla mds_atp_solicitud los registro con estado 4 = APROBADA
                $solicitudes = Mds_atp_solicitud::find()->where(['estado' => Mds_atp_solicitud::PENDIENTE_ALTA])->all();

                // Crear directorio formado por idusuario y fecha actual en milisegundos
                $path = "uploads/atpcen/altas/" . $model->idusuario . '_' . $this->getTimestamp();
                mkdir($path, 0777, true);

                $model->path = $path . "/PNSA2430263-Ent042-Novedades-GAFXXXXXX-$fecha_movimiento.txt";
                $fileName = Yii::getAlias("@app/web/$model->path");
                $file = fopen($fileName, "w");

                fwrite($file, $this->formateo($header) . "\n");

                foreach ($solicitudes as $key => $solicitud) {
                    $solicitud_actual = $solicitud;
                    $nro_solicitud = $this->completarNumero(6, $solicitud->id);

                    $fecha_nacimiento = $solicitud->fecha_nacimiento;
                    $fecha_nacimiento = date("dmy", strtotime($fecha_nacimiento));
                    $fecha_nacimientoM = date("dMy", strtotime($fecha_nacimiento));
                    // Calcular edad
                    $fecha_actual = new DateTime();
                    $edad = $fecha_actual->diff(new DateTime($fecha_nacimientoM))->y;

                    $nombre = "";
                    $apellido = "";
                    $nro_documento = "";
                    $nro_documento_afinidad = "";
                    $sexo = "";
                    $cuit = "";

                    // Si la edad es menor a 18 busco los datos el tutor
                    if ($edad < 18) {
                        $apellido = $this->formateo($solicitud->tutor_apellido);
                        $nombre = $this->formateo($solicitud->tutor_nombre);


                        $fecha_nacimiento = $solicitud->tutor_fecha_nacimiento;
                        $fecha_nacimiento = date("dmy", strtotime($fecha_nacimiento));

                        $sexo = $solicitud->tutor_sexo[0];

                        $nro_documento = $solicitud->tutor_documento;
                        $nro_documento_afinidad = $solicitud->tutor_documento;
                        $nro_documento = $this->completarNumero(9, intval($nro_documento));

                        $cuit = $solicitud->tutor_cuil;
                        $cuit = str_replace("-", "", $cuit);
                        $cuit = $this->completarNumero(11, $cuit);
                    } else {
                        $apellido = $this->formateo($solicitud->apellido);
                        $nombre = $this->formateo($solicitud->nombre);

                        $sexo = $solicitud->sexo[0];
                        $nro_documento_afinidad = $solicitud->documento;
                        $nro_documento = $solicitud->documento;
                        $nro_documento = $this->completarNumero(9, intval($nro_documento));

                        $cuit = $solicitud->cuil;
                        $cuit = str_replace("-", "", $cuit);
                        $cuit = $this->completarNumero(11, $cuit);
                    }

                    // APELLIDO Y NOMBRE DE TAMAÑO 24
                    $nombreapellido = strtoupper($apellido) . " " . strtoupper($nombre);
                    $nombreapellido = str_replace("Ñ", "", $nombreapellido);
                    $nombreapellido = str_replace("&", "", $nombreapellido);
                    $nombreapellido = $this->cortaryrellenar(24, $nombreapellido);

                    if (!$solicitud->idlocalidad) {
                        // Eliminar archivo
                        fclose($file);
                        unlink($fileName);
                        rmdir($path);

                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "Error al Generar Altas",
                            'content' => "<span class='text-danger'>La solicitud con Documento N° $solicitud->documento no tiene una localidad asociada. Por favor, completar dicho registro y volver a intentar generar el documento.</span>",
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                    $dataLocalidad = Sds_com_localidad::findOne($solicitud->idlocalidad);
                    $localidad = strtoupper($dataLocalidad->descripcion);
                    $localidad = $this->formateo($localidad);
                    $localidad = $this->cortaryrellenar(20, $localidad);

                    $telefono = $solicitud->telefono;
                    $telefono = $this->cortaryrellenar(12, "");

                    $dataSucursal = Mds_atp_sucursal::findOne($model->idsucursal);
                    $sucursal = $dataSucursal->codigo;

                    $calleynumero = $dataSucursal->direccion;
                    $calleynumero = $this->completarTexto(30, $calleynumero);

                    $piso = $this->completarTexto(2, "");
                    $departamento = $this->completarTexto(3, "");

                    if (!$dataLocalidad->codigo_postal > 0) {
                        // Eliminar archivo
                        fclose($file);
                        unlink($fileName);
                        rmdir($path);

                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "Error al Generar Altas",
                            'content' => "<span class='text-danger'>La localidad " . strtoupper($dataLocalidad->descripcion) . " no tiene código postal asociado, completar dicho registro y volver a intentar generar el documento.</span>",
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    }
                    $codigo_postal = $this->completarNumero(5, $dataLocalidad->codigo_postal);

                    $descripcion_afinidad = $this->completarTexto(17, $nro_documento_afinidad);

                    // $digito = "0";

                    //del 1 al 8 de los campos
                    $primeraparte = $codigo_movimiento . $nro_solicitud . $fecha_movimiento . $entidad . $sucursal . $digito_verificado . $filler_numerico . $filler_texto;

                    //del 9 al 23 de los campos
                    $segundaparte = $nombreapellido . $calleynumero . $piso . $departamento . $codigo_postal . $localidad . $cod_provincia . $telefono . $codigo_cedula . $CEDULA . $cod_documento . $nro_documento . $fecha_nacimiento . $nacionalidad_texto . $sexo;

                    //del 24 al 48
                    $terceraparte = $texto_intermedio . $descripcion_afinidad . $texto_intermedio2;

                    //del 49 al 49
                    $cuartaparte = $cuit;

                    //del 50 al 63
                    $quintaparte = $texto_final;

                    $linea = $primeraparte . $segundaparte . $terceraparte . $cuartaparte . $quintaparte;

                    fwrite($file, $this->formateo($linea) . "\n");
                }

                fwrite($file, $this->formateo($trailer_fila) . "\n");

                fclose($file);

                // Guardar registro
                $model->save();
            } catch (\Throwable $th) {
                throw new Exception('Error: ' . $solicitud_actual->documento . ' ' . $th->toString);
            }

            return [
                'forceReload' => '#crud-datatable-pjax',
                'title' => "Generar Altas",
                'content' => '<span class="text-success">Alta Generada con éxito.</span>',
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return [
                'title' => "Create new Mds_atp_alta",
                'content' => $this->renderAjax('create', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    /**
     * 
     */
    public function actionChange_estado($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($request->isGet) {
            return [
                'title' => "Cambio de Estado registro " . $id,
                'content' => $this->renderAjax('change_estado', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        } else if ($model->load($request->post()) && $model->save()) {
            $model->fechahora = date('d-m-Y H:i', strtotime($model->fechahora));
            $usuario = Mds_seg_usuario::findOne($model->idusuario);
            $model->idusuario = $usuario->nombre . " " . $usuario->apellido;
            $model->estado = $this->getEstado($model->estado);
            return [
                'forceReload' => '#crud-datatable-pjax',
                'title' => "Registro Alta " . $id,
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
            ];
        } else {
            return [
                'title' => "Cambio de Estado registro " . $id,
                'content' => $this->renderAjax('change_estado', [
                    'model' => $model,
                ]),
                'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                    Html::button('Guardar', ['class' => 'btn btn-primary', 'type' => "submit"])
            ];
        }
    }

    protected function getEstado($estado)
    {
        switch ($estado) {
            case Mds_atp_alta::GENERADO:
                return 'Generado';
                break;
            case Mds_atp_alta::ACEPTADO:
                return 'Aceptado';
                break;
            case Mds_atp_alta::RECHAZADO:
                return 'Rechazado';
                break;
        }
    }

    protected function completarTexto($total, $valor)
    {

        $numero = strval($valor);

        while (strlen($numero) < $total) {
            $numero = $numero . " ";
        }

        return $numero;
    }

    protected function cortaryrellenar($total, $texto)
    {

        if (strlen($texto) > $total) {
            $texto = substr($texto, 0, $total - 1);
        }
        return $this->completarTexto($total, $texto);
    }

    protected function completarNumero($total, $valor)
    {
        $numero = strval($valor);

        while (strlen($numero) < $total) {
            $numero = "0" . $numero;
        }

        return $numero;
    }

    protected function formateo($linea)
    {
        $fila = strtoupper($linea);
        $replacements = array(
            "Á" => "A",
            "á" => "A",
            "É" => "E",
            "é" => "E",
            "Í" => "I",
            "í" => "I",
            "Ó" => "O",
            "ó" => "O",
            "Ú" => "U",
            "ú" => "U",
            "Ü" => "U",
            "ü" => "U"
        );
        $fila = str_replace(array_keys($replacements), array_values($replacements), $fila);
        return $fila;
    }

    /**
     * Finds the Mds_atp_alta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_atp_alta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_atp_alta::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /** Obtener timestamp de la hora actual */
    protected function getTimestamp()
    {
        // Obtenemos la hora actual en segundos y microsegundos
        $microtime = microtime();

        // Separamos los segundos y los microsegundos en un array
        list($seconds, $microSeconds) = explode(' ', $microtime);

        // Obtenemos los milisegundos
        $milliseconds = round($microSeconds * 1000);

        return $milliseconds;
    }
}
