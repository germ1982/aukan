<?php

namespace app\controllers;

use app\models\Mds_org_dispositivo;
use Yii;
use app\models\Mds_org_informe;
use app\models\Mds_org_informe_usuario;
use app\models\Mds_org_informeSearch;
use app\models\Mds_seg_usuario;
use app\models\Mds_sys_log;
use app\models\Mds_legales_archivo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_org_organismo;

use kartik\mpdf\Pdf;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use \yii\web\Response;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

/**
 * Mds_org_informeController implements the CRUD actions for Mds_org_informe model.
 */
class Mds_org_informeController extends Controller
{
    /**
     * @inheritdoc
     */
    //No debe tener behaviors, se verifica que el usuario este logueado en cada funcion

    /**
     * Lists all Mds_org_informe models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioAuth = Yii::$app->user->identity;
        if ($usuarioAuth) {
            $searchModel = new Mds_org_informeSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_informe', null, array());
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'tiposFiltro' => $this->getFilterTipos('RECIBIDOS'),
                'usuariosFiltro' => $this->getFilterUsuarios('RECIBIDOS'),
                'dispositivosFiltro' => $this->getFilterDispositivos('RECIBIDOS'),
                'compartidosFiltro' => $this->compartidosFiltro(null, 'RECIBIDOS'),
                'vistosFiltro' => $this->compartidosFiltro(Mds_org_informe::VISTO_VALUE, 'RECIBIDOS'),
                'title' => 'Informes Recibidos'
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionIndex_enviados()
    {
        $usuarioAuth = Yii::$app->user->identity;
        if ($usuarioAuth) {
            $searchModel = new Mds_org_informeSearch();
            $dataProvider = $searchModel->searchEnviados(Yii::$app->request->queryParams);
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_informe', null, array());
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'tiposFiltro' => $this->getFilterTipos('ENVIADOS'),
                'usuariosFiltro' => $this->getFilterUsuarios('ENVIADOS'),
                'dispositivosFiltro' => $this->getFilterDispositivos('ENVIADOS'),
                'compartidosFiltro' => $this->compartidosFiltro(null, 'ENVIADOS'),
                'vistosFiltro' => $this->compartidosFiltro(Mds_org_informe::VISTO_VALUE, 'ENVIADOS'),
                'title' => 'Informes Enviados'
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }


    /**
     * Displays a single Mds_org_informe model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $usuarioAuth = Yii::$app->user->identity;

        if ($usuarioAuth) {
            if (Yii::$app->user->identity->idcontacto) {
                $urlReferer = $_SERVER["HTTP_REFERER"] ?? null;
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if ($urlReferer && strpos($urlReferer, 'mds_org_informe') && strpos($urlReferer, 'enviados')) {
                    $_SESSION["urlAnteriorMdsOrgInforme"] = $urlReferer;
                } else {
                    $_SESSION["urlAnteriorMdsOrgInforme"] = null;
                }

                $request = Yii::$app->request;
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_informe', $id, array());
                $model = $this->findModel($id);

                $usuario = Yii::$app->user->identity;

                // Buscar todos los compartidos
                $compartidos = $model->getCompartidos(null, null);

                $compartidosUsuariosId = array();
                foreach ($compartidos as $compartido) {
                    array_push($compartidosUsuariosId, $compartido['idusuario']);
                }

                if ($usuario->idusuario == $model['idusuario'] || in_array($usuario->idusuario, $compartidosUsuariosId)) {

                    $idusuario = $usuario != null ? $usuario->idusuario : null;
                    if (!isset($idusuario) || $idusuario == null) {
                        $model = new \app\models\LoginForm();
                        return Yii::$app->getResponse()->redirect([
                            'site/login',
                            'model' => $model,
                        ]);
                    }
                    $comp = Mds_org_informe_usuario::findOne(["idinforme" => $model->idinforme, "idusuario" => $idusuario]);
                    if ($comp && $comp->visto === 1) {
                        // Marcar como Visto
                        $comp->visto = 2;
                        $comp->visto_fecha = date('Y-m-d H:i:s');
                        $comp->save();
                    }

                    $model->idorganismo = Mds_org_dispositivo::findOne($model->iddispositivo)->idorganismo;

                    if ($request->isAjax) {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [
                            'title' => "Ver Informe N° " . $model->idinforme,
                            'content' => $this->renderAjax('view', [
                                'model' => $model,
                                'compartidos' => $compartidos,
                                'urlAnterior' => isset($_SESSION["urlAnteriorMdsOrgInforme"]) ? $_SESSION["urlAnteriorMdsOrgInforme"] : 'index.php?r=mds_org_informe',
                            ]),
                            'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"])
                        ];
                    } else {
                        return $this->render('view', [
                            'model' => $model,
                            'compartidos' => $compartidos,
                            'urlAnterior' => isset($_SESSION["urlAnteriorMdsOrgInforme"]) ? $_SESSION["urlAnteriorMdsOrgInforme"] : 'index.php?r=mds_org_informe',
                        ]);
                    }
                } else {
                    throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                }
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'No tiene permitido ejecutar esta acción ya que no tiene un contacto asociado.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Creates a new Mds_org_informe model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $usuarioAuth = Yii::$app->user->identity;

        if ($usuarioAuth) {
            if (Yii::$app->user->identity->idcontacto) {
                $urlReferer = $_SERVER["HTTP_REFERER"] ?? null;
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if ($urlReferer && strpos($urlReferer, 'mds_org_informe') && strpos($urlReferer, 'enviados')) {
                    $_SESSION["urlAnteriorMdsOrgInforme"] = $urlReferer;
                } else {
                    $_SESSION["urlAnteriorMdsOrgInforme"] = null;
                }

                $request = Yii::$app->request;
                $model = new Mds_org_informe();
                $model->fecha = date('d-m-Y');
                $usuario = Yii::$app->user->identity;
                $idusuario = $usuario != null ? $usuario->idusuario : null;
                if (!isset($idusuario) || $idusuario == null) {
                    $model = new \app\models\LoginForm();
                    return Yii::$app->getResponse()->redirect([
                        'site/login',
                        'model' => $model,
                    ]);
                }
                $model->idusuario = $usuario->idusuario;
                $model->iddispositivo = -1;

                if ($request->isAjax) {
                } else {
                    /*
                *   Guardado sin modal
                */
                    if ($model->load($request->post())) {
                        $transaction = Yii::$app->db->beginTransaction();
                        $model->fecha = date_format(date_create($model->fecha), 'Y-m-d');

                        //ANOTEZE: Aca es recomendable para probar el guardado, pasar como parámetro false, para forzar el insert y ver el error que tira en la db.
                        //Luego de depurar, sacarlo y dejar el save sin parámetros, porque sirve para saltar validaciones.
                        if ($model->save()) {
                            $informes = $model->informes != null ? $model->informes : array();
                            $informes_count = count($informes);

                            // Upload archivo adjunto
                            // $tmpfile = UploadedFile::getInstance($model, 'temp_archivo_adjunto');
                            // if (isset($tmpfile)) {

                            //     $extension = $tmpfile->extension;
                            //     $nombre = $model->random_filename(30, '/uploads/dispositivos', $extension);
                            //     $model->archivo_adjunto_path = $nombre;
                            //     if (!file_exists('uploads/dispositivos/' . $model->iddispositivo . '/archivo/')) {
                            //         mkdir('uploads/dispositivos/' . $model->iddispositivo . '/archivo/', 0777, true);
                            //     }
                            //     $tmpfile->saveAs('uploads/dispositivos/' . $model->iddispositivo . '/archivo/' . $nombre);
                            //     $model->save();
                            // }
                            if (Yii::$app->request->post()['adjuntos_eliminados'] != "") {
                                $this->eliminarAdjuntos(Yii::$app->request->post()['adjuntos_eliminados']);
                            }

                            if (isset(Yii::$app->request->post()['Mds_org_informe']['adjuntos']) && Yii::$app->request->post()['Mds_org_informe']['adjuntos']) {
                                $pathTemp = __DIR__ . '/../web/uploads/legales/temp/';
                                $pathRespuestas = __DIR__ . '/../web/uploads/informes/';
                                $date = date('Y-m-d_H_i_s', time());

                                $adjuntos = json_decode(Yii::$app->request->post()['Mds_org_informe']['adjuntos'], true);
                                foreach ($adjuntos as $key => $adjunto) {
                                    $path_info = pathinfo($adjunto['temp']);
                                    $extension = $path_info['extension'];
                                    $nameFile = "informe_{$model->idinforme}_{$date}_{$key}.{$extension}";
                                    if (rename($pathTemp . $adjunto['temp'], $pathRespuestas . $nameFile)) {
                                        Mds_legales_archivo::saveFile($adjunto['nombre_original'], 'mds_org_informe', 'informe', $model->idinforme, $nameFile);
                                    }
                                }
                            }

                            $guardado = true;
                            for ($index_informe = 0; $index_informe < $informes_count; $index_informe++) {
                                $usuario_informe = new Mds_org_informe_usuario();
                                $usuario_informe->idinforme = $model->idinforme;
                                $usuario_informe->idusuario = $informes[$index_informe];
                                $usuario_informe->visto = 1;
                                if (!$usuario_informe->save()) {
                                    $transaction->rollBack();
                                    $guardado = false;
                                }
                            }

                            if ($guardado) {
                                $transaction->commit();
                                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_org_informe', $model->idinforme, $model->getAttributes());
                                return $this->redirect(['mds_org_informe/index_enviados']);
                            } else {
                                $transaction->rollBack();
                            }
                        }
                    } else {
                        $usuarios = ArrayHelper::map(
                            Mds_seg_usuario::find()
                                ->select(['idusuario', 'UPPER(CONCAT(apellido, \', \', nombre)) AS nombre'])
                                ->where("idcontacto IS NOT NULL AND activo = 1")
                                ->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])->all(),
                            'idusuario',
                            'nombre'
                        );

                        $tiposInforme = ArrayHelper::map(
                            Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TIPO_INFORME),
                            'idconfiguracion',
                            'descripcion'
                        );

                        $organismos = ArrayHelper::map(
                            Mds_org_organismo::find()->innerJoin('mds_org_dispositivo', 'mds_org_organismo.idorganismo = mds_org_dispositivo.idorganismo')->orderBy(['descripcion' => SORT_ASC])->all(),
                            'idorganismo',
                            'descripcion'
                        );

                        return $this->render('create', [
                            'model' => $model,
                            'tiposInforme' => $tiposInforme,
                            'organismos' => $organismos,
                            'usuarios' => $usuarios,
                            'urlAnterior' => isset($_SESSION["urlAnteriorMdsOrgInforme"]) ? $_SESSION["urlAnteriorMdsOrgInforme"] : 'index.php?r=mds_org_informe',
                        ]);
                    }
                }
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'No tiene permitido ejecutar esta acción ya que no tiene un contacto asociado.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Updates an existing Mds_org_informe model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $usuarioAuth = Yii::$app->user->identity;

        if ($usuarioAuth) {
            if (Yii::$app->user->identity->idcontacto) {
                $request = Yii::$app->request;
                $model = $this->findModel($id);
                $usuario = Yii::$app->user->identity;

                if ($usuario->idusuario == $model['idusuario']) {
                    $model->idorganismo = Mds_org_dispositivo::findOne($model->iddispositivo)->idorganismo;
                    $model->borrar_adjunto = false;
                    // $informes_borrar = Mds_org_informe_usuario::find()->where(["idinforme" => $model->idinforme])->all();

                    if ($request->isAjax) {
                    } else {
                        /*
                    *   Guardado sin modal
                    */
                        if ($model->load($request->post())) {
                            $transaction = Yii::$app->db->beginTransaction();
                            $model->fecha = date_format(date_create($model->fecha), 'Y-m-d');

                            // Update compartidos
                            $informes = $model->informes != null ? $model->informes : array();
                            $informes_count = count($informes);

                            //ANOTEZE: Aca es recomendable para probar el guardado, pasar como parámetro false, para forzar el insert y ver el error que tira en la db.
                            //Luego de depurar, sacarlo y dejar el save sin parámetros, porque sirve para saltar validaciones.
                            $guardado = $model->save();
                            if ($guardado) {
                                for ($index_informe = 0; $index_informe < $informes_count; $index_informe++) {
                                    $informeUsuario = Mds_org_informe_usuario::find()->where(['idinforme' => $model->idinforme, 'idusuario' => $informes[$index_informe]])->one();
                                    if (!$informeUsuario) {
                                        $usuario_informe = new Mds_org_informe_usuario();
                                        $usuario_informe->idinforme = $model->idinforme;
                                        $usuario_informe->idusuario = $informes[$index_informe];
                                        $usuario_informe->visto = 1;
                                        if (!$usuario_informe->save()) {
                                            $transaction->rollBack();
                                            $guardado = false;
                                        }
                                    }
                                }

                                if ($guardado) {
                                    $transaction->commit();
                                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_org_informe', $model->idinforme, $model->getAttributes());
                                    return $this->redirect(['mds_org_informe/index_enviados']);
                                } else {
                                    $transaction->rollBack();
                                }
                            }
                        } else {
                            $usuarios = ArrayHelper::map(
                                Mds_seg_usuario::find()
                                    ->select(['idusuario', 'UPPER(CONCAT(apellido, \', \', nombre)) AS nombre'])
                                    ->where("idcontacto IS NOT NULL AND activo = 1")
                                    ->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])->all(),
                                'idusuario',
                                'nombre'
                            );

                            $tiposInforme = ArrayHelper::map(
                                Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_TIPO_INFORME),
                                'idconfiguracion',
                                'descripcion'
                            );

                            $organismos = ArrayHelper::map(
                                Mds_org_organismo::find()->innerJoin('mds_org_dispositivo', 'mds_org_organismo.idorganismo = mds_org_dispositivo.idorganismo')->orderBy(['descripcion' => SORT_ASC])->all(),
                                'idorganismo',
                                'descripcion'
                            );

                            // Buscar todos los compartidos
                            $compartidos = $model->getCompartidos(null, null);
                            $cantidadCompartidos = count($compartidos);
                            $cantMaxCompartidos = Mds_org_informe::CANT_MAX_COMPARTIDOS;

                            if ($cantidadCompartidos < $cantMaxCompartidos) {
                                $cantMaxCompartidos = $cantMaxCompartidos - $cantidadCompartidos;
                            } else {
                                $cantMaxCompartidos = 0;
                            }

                            return $this->render('update', [
                                'model' => $model,
                                'usuarios' => $usuarios,
                                'tiposInforme' => $tiposInforme,
                                'organismos' => $organismos,
                                'compartidos' => $compartidos,
                                'cantMaxCompartidos' => $cantMaxCompartidos,
                                'urlAnterior' => 'index.php?r=mds_org_informe/index_enviados',
                            ]);
                        }
                    }
                } else {
                    throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                }
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'No tiene permitido ejecutar esta acción ya que no tiene un contacto asociado.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Finds the Mds_org_informe model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_org_informe the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $usuarioAuth = Yii::$app->user->identity;
        if ($usuarioAuth) {
            if (($model = Mds_org_informe::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }


    public function actionReporte_informe($idinforme)
    {
        $usuarioAuth = Yii::$app->user->identity;

        if ($usuarioAuth) {
            if (Yii::$app->user->identity->idcontacto) {
                $usuarioAuth = Yii::$app->user->identity;
                $modelInforme = new Mds_org_informe();
                $informe = $modelInforme->find()->where(['idinforme' => $idinforme])->one();
                $compartidos = $informe->getCompartidos(null, null);

                $compartidosUsuariosId = array();
                foreach ($compartidos as $compartido) {
                    array_push($compartidosUsuariosId, $compartido['idusuario']);
                }

                if ($usuarioAuth->idusuario == $informe['idusuario'] || in_array($usuarioAuth->idusuario, $compartidosUsuariosId)) {
                    $usuarioAuthApellido = mb_strtoupper($usuarioAuth->apellido);
                    $usuarioAuthNombre = mb_strtoupper($usuarioAuth->nombre);

                    $dateToday = date('d/m/Y H:i:s');

                    Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_org_informe/reporte_informe', $idinforme, array());

                    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
                    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                    $fechaHeader = ($dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y'));



                    $compartidoCon = '';
                    $vistoPor = '';
                    $existeVisto = false;
                    if (!empty($compartidos)) {
                        foreach ($compartidos as $compartido) {
                            if ($compartido->visto === 2) {
                                $guionVisto = $existeVisto ? " - " : ' ';
                                $vistoFecha = $compartido->visto_fecha ? date('d/m/Y H:i', strtotime($compartido->visto_fecha)) : null;
                                $visto = $vistoFecha ? " ($vistoFecha)" : "";
                                $vistoPor .= $guionVisto . mb_strtoupper($compartido->idusuario0->apellido) . ', ' . mb_strtoupper($compartido->idusuario0->nombre) . $visto;
                                if (!$existeVisto) {
                                    $existeVisto = true;
                                }
                            }
                            $compartidoCon .= ' ' . mb_strtoupper($compartido->idusuario0->apellido) . ', ' . mb_strtoupper($compartido->idusuario0->nombre);
                            if (next($compartidos)) {
                                $compartidoCon .=  ' - ';
                            }
                        }
                    }

                    $content = $this->renderPartial('reporte_informe', ['informe' => $informe, 'fechaHeader' => $fechaHeader, 'compartidoCon' => $compartidoCon, 'vistoPor' => $vistoPor]); // setup kartik\mpdf\Pdf component 
                    //    print_r($content);
                    $pdf = new Pdf([
                        'mode' => Pdf::MODE_UTF8,
                        'format' => Pdf::FORMAT_A4,
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'destination' => Pdf::DEST_BROWSER,
                        'marginBottom' => 20,
                        'content' => $content,
                        'defaultFontSize' => 12,
                        'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                        // any css to be embedded if required
                        'cssInline' => '.kv-heading-1{font-size:18px}',
                        'methods' => [
                            'SetTitle' => 'INFORME PDF',
                            'SetHeader' => null,
                            'SetFooter' => ["<p style='text-align:left'>Imprime $usuarioAuthApellido, $usuarioAuthNombre - {$dateToday} <br> Ministerio de Desarrollo Social y Trabajo - Página {PAGENO} de {nb} </p>"],
                        ]
                    ]);

                    return $pdf->render();
                } else {
                    throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
                }
            } else {
                throw new ForbiddenHttpException(Yii::t('yii', 'No tiene permitido ejecutar esta acción ya que no tiene un contacto asociado.'));
            }
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionGet_idorganismo($iddispositivo)
    {
        $usuarioAuth = Yii::$app->user->identity;
        if ($usuarioAuth) {
            $dispositivo = Mds_org_dispositivo::findOne($iddispositivo);
            return $dispositivo->idorganismo;
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    protected function getFilterTipos($llamadoDesde)
    {
        $usuarioId = Yii::$app->user->identity->idusuario;
        switch ($llamadoDesde) {
            case 'ENVIADOS':
                $where = "informe.idusuario = $usuarioId";
                $leftJoin = "";
                break;
            case 'RECIBIDOS':
                $where = "informeUsuario.idusuario = $usuarioId";
                $leftJoin = "LEFT JOIN mds_org_informe_usuario informeUsuario ON informe.idinforme = informeUsuario.idinforme";
                break;
            default:
                $where = "(informe.idusuario = $usuarioId OR informeUsuario.idusuario = $usuarioId)";
                $leftJoin = "LEFT JOIN mds_org_informe_usuario informeUsuario ON informe.idinforme = informeUsuario.idinforme";
                break;
        }
        $tiposFiltro = Mds_org_informe::findBySql(
            "SELECT tipo, descripcion
                FROM mds_org_informe informe 
                INNER JOIN sds_com_configuracion configuracion 
                ON informe.tipo = configuracion.idconfiguracion 
                $leftJoin
                WHERE configuracion.activo = 1 AND $where
                ORDER BY descripcion ASC
                "
        )->asArray()->all();

        $tiposFiltro = ArrayHelper::map($tiposFiltro, 'tipo', 'descripcion');
        return $tiposFiltro;
    }

    protected function getFilterUsuarios($llamadoDesde)
    {
        $usuarioId = Yii::$app->user->identity->idusuario;

        switch ($llamadoDesde) {
            case 'ENVIADOS':
                $where = "informe.idusuario = $usuarioId";
                $leftJoin = "";
                break;
            case 'RECIBIDOS':
                $where = "informeUsuario.idusuario = $usuarioId";
                $leftJoin = "LEFT JOIN mds_org_informe_usuario informeUsuario ON informe.idinforme = informeUsuario.idinforme";
                break;
            default:
                $where = "(informe.idusuario = $usuarioId OR informeUsuario.idusuario = $usuarioId)";
                $leftJoin = "LEFT JOIN mds_org_informe_usuario informeUsuario ON informe.idinforme = informeUsuario.idinforme";
                break;
        }

        $usuariosFiltro = Mds_seg_usuario::findBySql(
            "SELECT usuario.idusuario as usuario_idusuario, UPPER(CONCAT(usuario.apellido,', ',usuario.nombre)) as usuario_fullname  
                FROM mds_org_informe informe 
                INNER JOIN mds_seg_usuario usuario 
                ON informe.idusuario = usuario.idusuario
                $leftJoin
                WHERE usuario.activo = 1 AND $where
                ORDER BY usuario_fullname ASC
                "
        )->asArray()->all();

        $usuariosFiltro = ArrayHelper::map($usuariosFiltro, 'usuario_idusuario', 'usuario_fullname');
        return $usuariosFiltro;
    }

    protected function getFilterDispositivos($llamadoDesde)
    {
        $usuarioId = Yii::$app->user->identity->idusuario;

        switch ($llamadoDesde) {
            case 'ENVIADOS':
                $where = "informe.idusuario = $usuarioId";
                $leftJoin = "";
                break;
            case 'RECIBIDOS':
                $where = "informeUsuario.idusuario = $usuarioId";
                $leftJoin = "LEFT JOIN mds_org_informe_usuario informeUsuario ON informe.idinforme = informeUsuario.idinforme";
                break;
            default:
                $where = "(informe.idusuario = $usuarioId OR informeUsuario.idusuario = $usuarioId)";
                $leftJoin = "LEFT JOIN mds_org_informe_usuario informeUsuario ON informe.idinforme = informeUsuario.idinforme";
                break;
        }

        $dispositivosFiltro = Mds_org_informe::findBySql(
            "SELECT informe.iddispositivo as informe_iddispositivo, CONCAT(dispositivo.descripcion,' - ',organismo.descripcion) as dispositivo_name  
                FROM mds_org_informe informe 
                INNER JOIN mds_org_dispositivo dispositivo 
                ON informe.iddispositivo = dispositivo.iddispositivo
                INNER JOIN mds_org_organismo organismo 
                ON dispositivo.idorganismo = organismo.idorganismo  
                $leftJoin
                WHERE dispositivo.activo = 1 AND $where
                ORDER BY dispositivo.descripcion ASC
                "
        )->asArray()->all();

        $dispositivosFiltro = ArrayHelper::map($dispositivosFiltro, 'informe_iddispositivo', 'dispositivo_name');
        return $dispositivosFiltro;
    }

    protected function compartidosFiltro($visto, $llamadoDesde)
    {
        $idUsuario = Yii::$app->user->identity->idusuario;

        switch ($llamadoDesde) {
            case 'ENVIADOS':
                $where = "informe.idusuario = $idUsuario";
                break;
            case 'RECIBIDOS':
                $where = "informeUsuario.idinforme IN (select idinforme from mds_org_informe_usuario where idusuario = $idUsuario)";
                break;
            default:
                $where = "informe.idusuario = $idUsuario OR informeUsuario.idinforme IN (select idinforme from mds_org_informe_usuario where idusuario = $idUsuario)";
                break;
        }

        if ($visto) {
            $where .= " AND visto = $visto";
        }
        $compartidosFiltro = Mds_org_informe_usuario::findBySql(
            "SELECT informeUsuario.idusuario, CONCAT(UPPER(usuario.apellido),', ', UPPER(usuario.nombre)) as nombreUsuario  
                FROM mds_org_informe_usuario informeUsuario 
                INNER JOIN mds_seg_usuario usuario
                ON informeUsuario.idusuario = usuario.idusuario
                INNER JOIN mds_org_informe informe
                ON informeUsuario.idinforme = informe.idinforme
                WHERE $where
                ORDER BY usuario.apellido ASC, usuario.nombre ASC
                "
        )->asArray()->all();

        $compartidosFiltro = ArrayHelper::map($compartidosFiltro, 'idusuario', 'nombreUsuario');
        return $compartidosFiltro;
    }
}
