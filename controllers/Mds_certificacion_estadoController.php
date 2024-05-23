<?php

namespace app\controllers;

use Yii;
use app\models\Mds_certificacion;
use app\models\Mds_certificacion_estado;
use app\models\Mds_certificacion_estadoSearch;
use app\models\Sds_com_configuracion;
use app\models\Mds_seg_usuario_rol;

use yii\web\Controller;
use yii\helpers\ArrayHelper;
use \yii\filters\AccessControl;

class Mds_certificacion_estadoController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'store'],
                'rules' => [
                    [
                        'actions' => ['index', 'store'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return (Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL));
                        }
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all Mds_certificacion_programa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Mds_certificacion_estadoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'certificacionesFiltro' => $this->getFilterCertificaciones(),
            'beneficiariosFiltro' => $this->getFilterBeneficiarios(),
            'usuariosFiltro' => $this->getFilterUsuarios(),
            'estadosFiltro' => $this->getFilterEstados(),
            'direccionesFiltro' => $this->getFilterDirecciones(),
        ]);
    }

    public function actionStore()
    {
        $respuestaId = Yii::$app->request->post()['idcertificacion'];
        $obs = Yii::$app->request->post()['observaciones'];
        $fechaIni = date('Y-m-d h:i:s');
        Mds_certificacion_estado::actualizarFechaFinUltimoEstado($respuestaId);
        Mds_certificacion_estado::actualizarEstado($respuestaId, $fechaIni, null, $obs, $this->getEstado(Yii::$app->request->post()['idestado']));
        return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
    }

    private function getEstado($labelEstado)
    {
        switch ($labelEstado) {
            case "observar":
                $idestado = Mds_certificacion_estado::ESTADO_OBSERVADA;
                break;
            case "rechazar":
                $idestado = Mds_certificacion_estado::ESTADO_RECHAZADA;
                break;
            case "aprobar":
                $idestado = Mds_certificacion_estado::ESTADO_APROBADA;
                break;
        }
        return $idestado;
    }

    protected function getFilterDirecciones()
    {
        //Busqueda estados
        $estadosFiltro = Sds_com_configuracion::findBySql(
            "SELECT idcertificacionestado, 
                configuracion.idconfiguracion as conf_idconfiguracion, 
                UPPER (configuracion.descripcion) as conf_descripcion 
                FROM mds_certificacion_estado certificacion_estado
                INNER JOIN mds_certificacion_direccion certificacion_direccion
                ON certificacion_estado.iddireccion = certificacion_direccion.idcertificaciondireccion  
                INNER JOIN sds_com_configuracion configuracion 
                ON certificacion_direccion.iddireccion = configuracion.idconfiguracion 
                WHERE certificacion_estado.iddireccion 
                IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1)
                ORDER BY conf_descripcion ASC
                "
        )->asArray()->all();
        $estadosFiltro = ArrayHelper::map($estadosFiltro, 'conf_idconfiguracion', 'conf_descripcion');
        return $estadosFiltro;
    }
    protected function getFilterEstados()
    {
        //Busqueda estados
        $estadosFiltro = Sds_com_configuracion::findBySql(
            "SELECT idcertificacionestado, 
                configuracion.idconfiguracion as conf_idconfiguracion, 
                configuracion.descripcion as conf_descripcion 
                FROM mds_certificacion_estado certificacion_estado 
                INNER JOIN sds_com_configuracion configuracion 
                ON certificacion_estado.idestado = configuracion.idconfiguracion 
                WHERE certificacion_estado.idestado 
                IN (SELECT idconfiguracion FROM sds_com_configuracion WHERE activo = 1)
                ORDER BY conf_descripcion ASC
                "
        )->asArray()->all();
        $estadosFiltro = ArrayHelper::map($estadosFiltro, 'conf_idconfiguracion', 'conf_descripcion');
        return $estadosFiltro;
    }

    protected function getFilterBeneficiarios()
    {
        //Busqueda certificaciones 
        $estadosFiltro = Sds_com_configuracion::findBySql(
            "  SELECT certificacion.idbeneficiario beneficiario_id,
               CONCAT(UCASE(persona.apellido), ', ', UCASE(persona.nombre)) beneficiario_nombre 
                FROM mds_certificacion_estado certificacion_estado 
                INNER JOIN mds_certificacion certificacion
                ON certificacion_estado.idcertificacion = certificacion.idcertificacion
                INNER JOIN sds_com_persona persona
                ON certificacion.idbeneficiario = persona.idpersona
                WHERE certificacion.idbeneficiario
                IN (SELECT idpersona FROM sds_com_persona)
                GROUP BY certificacion.idbeneficiario ORDER BY persona.apellido ASC, persona.nombre ASC;
                "
        )->asArray()->all();
        $estadosFiltro = ArrayHelper::map($estadosFiltro, 'beneficiario_id', 'beneficiario_nombre');
        return $estadosFiltro;
    }
    protected function getFilterCertificaciones()
    {
        //Busqueda certificaciones 
        $estadosFiltro = Sds_com_configuracion::findBySql(
            "
              SELECT certificacion.idcertificacion as idcertificacion FROM mds_certificacion_estado AS certificacion_estado INNER JOIN mds_certificacion certificacion
                ON certificacion_estado.idcertificacion = certificacion.idcertificacion 
                WHERE certificacion.deleted_at IS NULL
                GROUP BY certificacion_estado.idcertificacion;
            "
        )->asArray()->all();
        $certificacionesFiltro = ArrayHelper::map($estadosFiltro, 'idcertificacion', 'idcertificacion');
        return $certificacionesFiltro;
    }
    protected function getFilterUsuarios()
    {
        $usuariosFiltro = Sds_com_configuracion::findBySql(
            "SELECT certificacion_estado.idusuario usuario_id, 
               CONCAT(UCASE(usuario.apellido), ', ', UCASE(usuario.nombre)) usuario_nombre 
                FROM mds_certificacion_estado certificacion_estado 
                INNER JOIN mds_seg_usuario usuario
                ON certificacion_estado.idusuario = usuario.idusuario
                WHERE certificacion_estado.idusuario 
                IN (SELECT idusuario FROM mds_seg_usuario WHERE activo = 1)
                GROUP BY certificacion_estado.idusuario ORDER BY usuario.apellido ASC, usuario.nombre ASC;
                "
        )->asArray()->all();
        $usuariosFiltro = ArrayHelper::map($usuariosFiltro, 'usuario_id', 'usuario_nombre');
        return $usuariosFiltro;
    }
}
