<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_conc_postulacion".
 *
 * @property int $idpostulacion
 * @property int $idsolicitud
 * @property int $estado
 * @property int $idvacante
 * @property int $puntaje
 * @property int $idusuario
 * @property int $idusuario_borra
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Mds_conc_postulacion extends \yii\db\ActiveRecord
{
    public $documento;
    public $apellido;
    public $nombre;
    public $idconcurso;
    public $legajo;
    public $categoria_actual;
    public $antiguedad;
    public $eventual;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_conc_postulacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idsolicitud', 'idvacante', 'idusuario', 'created_at'], 'required'],
            [['idpostulacion', 'idsolicitud', 'idvacante', 'estado', 'puntaje', 'idusuario', 'idusuario_borra'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'string'],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['estado' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpostulacion' => '#',
            'idsolicitud' => '# Solicitud',
            'idvacante' => 'Vacante',
            'idconcurso' => 'Concurso',
            'estado' => 'Estado',
            'puntaje' => 'Puntaje',
            'idusuario' => 'Usuario Alta',
            'idusuario_borra' => 'Usuario Borra',
            'created_at' => 'Fecha Alta',
            'updated_at' => 'Fecha Actualización',
            'deleted_at' => 'Activo',
            'categoria_actual' => 'Categoría Actual',
            'antiguedad' => 'Antigüedad',
            'eventual' => 'Eventual'
        ];
    }

    public function getSolicitud()
    {
        return $this->hasOne(Mds_conc_solicitud::class, ['idsolicitud' => 'idsolicitud']);
    }

    public function getVacante()
    {
        return $this->hasOne(Mds_conc_vacante::class, ['idvacante' => 'idvacante']);
    }

    public function getEstado0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'estado']);
    }

    public function getImpugnacion()
    {
        return $this->hasMany(Mds_conc_impugnacion::class, ['idpostulacion' => 'idpostulacion'])
            ->where(['deleted_at' => null]);
    }

    /**
     * Gets query for [[idusuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[idusuario_borra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioBorra()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_borra']);
    }


    public function getHistorial()
    {
        return Mds_conc_historial::find()->where(['idpostulacion' => $this->idpostulacion, "deleted_at" => null])->orderBy(['idhistorial' => SORT_DESC])->all();
    }

    public static function getVacantesFiltro($idsolicitud)
    {
        $where = "postulacion.deleted_at IS NULL AND vacante.deleted_at IS NULL";

        if ($idsolicitud) {
            $where .= " AND postulacion.idsolicitud = $idsolicitud";
        }
        return Mds_conc_postulacion::find()
            ->select([
                'vacante.idvacante',
                'categoria.descripcion as detalleVacante'
            ])
            ->from("mds_conc_postulacion as postulacion")
            ->innerJoin('mds_conc_vacante as vacante', 'vacante.idvacante = postulacion.idvacante')
            ->innerJoin('sds_com_configuracion as categoria', 'categoria.idconfiguracion = vacante.categoria')
            ->where($where)
            ->orderBy(['detalleVacante' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public static function getEstadosFiltro($idsolicitud)
    {
        $where = "postulacion.deleted_at IS NULL AND configuracion.activo = 1";

        if ($idsolicitud) {
            $where .= " AND postulacion.idsolicitud = $idsolicitud";
        }

        return Mds_conc_postulacion::find()
            ->select("configuracion.idconfiguracion, 
                    configuracion.descripcion as estado")
            ->from("mds_conc_postulacion as postulacion")
            ->innerJoin('sds_com_configuracion as configuracion', 'configuracion.idconfiguracion = postulacion.estado')
            ->where($where)
            ->orderBy(['estado' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public static function getConcursosFiltro($idsolicitud)
    {
        $where = "postulacion.deleted_at IS NULL AND solicitud.deleted_at IS NULL AND configuracion.activo = 1";
        if ($idsolicitud) {
            $where .= " AND postulacion.idsolicitud = $idsolicitud";
        }

        return Mds_conc_postulacion::find()
            ->select("configuracion.idconfiguracion, 
                configuracion.descripcion as concurso")
            ->from("mds_conc_postulacion as postulacion")
            ->innerJoin('mds_conc_solicitud as solicitud', 'solicitud.idsolicitud = postulacion.idsolicitud')
            ->innerJoin('sds_com_configuracion as configuracion', 'configuracion.idconfiguracion = solicitud.idconcurso')
            ->where($where)
            ->orderBy(['concurso' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public function getUltimoEstado($idhistorial = null)
    {
        $where = "idpostulacion = {$this->idpostulacion} AND deleted_at IS NULL";

        if ($idhistorial) {
            $where .= " AND idhistorial != $idhistorial";
        }

        return Mds_conc_historial::find()
            ->select("idhistorial, estado_nuevo")
            ->where($where)
            ->orderBy(['idhistorial' => SORT_DESC])
            ->one();
    }

    public static function getMotivosImpugnacionByIdPostulacion($idPostulacion)
    {
        $motivosImpugnacion = array();

        //Busco el ultimo historial que tenga estado "NO ADMITIDO" para luego obtener los motivos
        $historial = Mds_conc_historial::find()
            ->select("historial.idhistorial")
            ->from("mds_conc_historial as historial")
            ->where([
                'historial.idpostulacion' => $idPostulacion,
                'historial.deleted_at' => null,
                'historial.estado_nuevo' => Mds_conc_solicitud::ESTADO_NO_ADMITIDO
            ])
            ->orderBy(['historial.idhistorial' => SORT_DESC])
            ->asArray()
            ->one();

        if ($historial) {
            $motivosImpugnacion = Mds_conc_historial::getMotivosImpugnacionByIdHistorial($historial['idhistorial']);
        }

        return $motivosImpugnacion;
    }

    public static function getConcursosByPostulaciones($where)
    {
        return Mds_conc_postulacion::find()
            ->select([
                'concurso.idconfiguracion as idconcurso',
                'concurso.descripcion as descripcion',
            ])
            ->join("inner join", "mds_conc_vacante as vacante", "vacante.idvacante = mds_conc_postulacion.idvacante")
            ->join("inner join", "sds_com_configuracion as concurso", "concurso.idconfiguracion = vacante.idconcurso")
            ->where($where)
            ->groupBy(['vacante.idconcurso'])
            ->orderBy(['descripcion' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public static function getPostulacionByIdConcurso($idConcurso, $where)
    {
        return Mds_conc_postulacion::find()
            ->select([
                'idpostulacion',
                'estado',
                'mds_conc_postulacion.idvacante'
            ])
            ->join("inner join", "mds_conc_vacante as vacante", "vacante.idvacante = mds_conc_postulacion.idvacante")
            ->where($where)
            ->andWhere("vacante.idconcurso = $idConcurso")
            ->all();
    }

    public static function getEstadosByIdConcurso($idConcurso, $where)
    {
        return Mds_conc_postulacion::find()
            ->select([
                'estado.idconfiguracion as estado',
                'estado.descripcion as descripcion',
            ])
            ->join("inner join", "mds_conc_vacante as vacante", "vacante.idvacante = mds_conc_postulacion.idvacante")
            ->join("inner join", "sds_com_configuracion as estado", "estado.idconfiguracion = mds_conc_postulacion.estado")
            ->where($where)
            ->andWhere("vacante.idconcurso = $idConcurso")
            ->groupBy(['mds_conc_postulacion.estado'])
            ->orderBy(['descripcion' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public static function getCategoriasByIdConcurso($idConcurso, $where)
    {
        return Mds_conc_postulacion::find()
            ->select([
                'categoria.idconfiguracion as categoria',
                'categoria.descripcion as descripcion',
            ])
            ->join("inner join", "mds_conc_vacante as vacante", "vacante.idvacante = mds_conc_postulacion.idvacante")
            ->join("inner join", "sds_com_configuracion as categoria", "categoria.idconfiguracion = vacante.categoria")
            ->where($where)
            ->andWhere("vacante.idconcurso = $idConcurso")
            ->groupBy(['vacante.categoria'])
            ->orderBy(['descripcion' => SORT_ASC, 'idconcurso' => SORT_DESC])
            ->asArray()
            ->all();
    }
}
