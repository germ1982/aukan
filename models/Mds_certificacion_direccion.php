<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_certificacion_direccion".
 *
 * @property int $idcertificaciondireccion
 * @property int $iddireccion
 * @property int|null $idusuario_carga
 * @property int|null $idusuario_borra
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Sds_com_configuracion $iddireccion0
 * @property MdsSegUsuario $idusuario0
 * @property MdsSegUsuario $idusuarioBorra
 * @property MdsSegUsuario $idusuarioCarga
 */
class Mds_certificacion_direccion extends \yii\db\ActiveRecord
{

    const ID_DIREC_SUBSECRETARIA_FAMILIA = 4404; // lo agregamos como tipo configuracion de direccion
    const ID_DIREC_SUBSECRETARIA_TRABAJO = 4405;
    const ID_DIREC_SUBSECRETARIA_DESARROLLO_SOCIAL = 4406;
    const ID_DIREC_ADMNISTRACION = 4407;

    public $usuario;
    public $fecha_desde;
    public $fecha_hasta;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion_direccion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iddireccion', 'idnivelautorizacion'], 'required'],
            [['iddireccion', 'idusuario_carga', 'idusuario_borra', 'iddireccion_padre'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['iddireccion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['iddireccion' => 'idconfiguracion']],
            [['idnivelautorizacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idnivelautorizacion' => 'idconfiguracion']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcertificaciondireccion' => '#',
            'iddireccion' => 'Dirección',
            'iddireccion_padre' => 'Dirección de la que depende',
            'idusuario_carga' => 'Idusuario Carga',
            'idusuario_borra' => 'Idusuario Borra',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Activo',
            'idnivelautorizacion' => 'Nivel de Autorización'
        ];
    }

    /**
     * Gets query for [[Iddireccion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDireccion0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'iddireccion']);
    }

    /**
     * Gets query for [[idnivelautorizacion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNivelAutorizacion0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idnivelautorizacion']);
    }

    /**
     * Gets query for [[Iddireccion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDireccionPadre()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'iddireccion_padre']);
    }

    /**
     * Gets query for [[IdusuarioBorra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuarioBorra()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_borra']);
    }

    /**
     * Gets query for [[IdusuarioCarga]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_carga']);
    }

    public function getDirector()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'usuario']);
    }

    static function getDireccionUsuario($idusuario)
    {
        $model_certificacion_director = Mds_certificacion_director::find()
            ->select([
                'CONCAT(mds_seg_usuario.nombre, " ",mds_seg_usuario.apellido) as usuario',
                'mds_certificacion_director.idcertificaciondireccion'
            ])
            ->where(['mds_certificacion_director.deleted_at' => null, 'mds_certificacion_director.idusuario' => $idusuario])
            ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_director.idcertificaciondireccion = mds_certificacion_direccion.idcertificaciondireccion AND mds_certificacion_direccion.deleted_at is null')
            ->innerJoin('mds_seg_usuario', 'mds_seg_usuario.idusuario = mds_certificacion_director.idusuario')
            ->orderBy(['mds_certificacion_director.created_at' => SORT_DESC])
            ->asArray()
            ->all();

        return $model_certificacion_director;
    }

    static function getCertificacionDireccionPadre($idcertificaciondireccion)
    {
        $model_certificacion_direccion_padre = null;
        $arrayidniveles = Mds_certificacion::ID_NIVELES_CERTIFICACIONES;
        $model_certificacion_direccion = Mds_certificacion_direccion::find()
            ->where(['mds_certificacion_direccion.deleted_at' => null, 'mds_certificacion_direccion.idcertificaciondireccion' => $idcertificaciondireccion])
            ->one();

        $arraykeys = array_keys($arrayidniveles, $model_certificacion_direccion->idnivelautorizacion);
        $key = $arraykeys[0] + 1;
        $arrayIdNivelesAncestros = array_slice($arrayidniveles, $key);

        if (isset($arrayIdNivelesAncestros)) {
            $model_certificacion_direccion_padre = Mds_certificacion_direccion::find()
                ->where(['mds_certificacion_direccion.deleted_at' => null, 'mds_certificacion_direccion.iddireccion' => $model_certificacion_direccion->iddireccion_padre])
                ->andWhere(['in', 'idnivelautorizacion', $arrayIdNivelesAncestros])
                ->one();
        }
        return $model_certificacion_direccion_padre;
    }

    static function getDireccionesUsuarioByNivel($idusuario, $idnivel)
    {
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL);

        if ($idnivel) {
            if ($hasRolAdminGeneral) {
                $model_certificacion_director = Mds_certificacion_director::find()
                    ->where(['mds_certificacion_director.deleted_at' => null, 'mds_certificacion_direccion.idnivelautorizacion' => $idnivel])
                    ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_director.idcertificaciondireccion = mds_certificacion_direccion.idcertificaciondireccion AND mds_certificacion_direccion.deleted_at is null')
                    ->asArray()
                    ->all();
            } else {
                $model_certificacion_director = Mds_certificacion_director::find()
                    ->where(['mds_certificacion_director.deleted_at' => null, 'mds_certificacion_director.idusuario' => $idusuario, 'mds_certificacion_direccion.idnivelautorizacion' => $idnivel])
                    ->innerJoin('mds_certificacion_direccion', 'mds_certificacion_director.idcertificaciondireccion = mds_certificacion_direccion.idcertificaciondireccion AND mds_certificacion_direccion.deleted_at is null')
                    ->asArray()
                    ->all();
            }
        } else {
            $model_certificacion_director = Mds_certificacion_direccion::find()
                ->where(['mds_certificacion_direccion.deleted_at' => null])
                ->asArray()
                ->all();
        }

        return $model_certificacion_director;
    }

    static function getDireccionesPrevias($arrayidcertificaciondireccion)
    {
        $arrDirecciones = [];

        $direccionesEnNivelUser = Mds_certificacion_direccion::find()
            ->where(['deleted_at' => null])
            ->andWhere(['IN', 'idcertificaciondireccion', $arrayidcertificaciondireccion])->asArray()
            ->all();

        foreach ($direccionesEnNivelUser as $direccion) {
            $direcciones = Mds_certificacion_direccion::find()
                ->where(['iddireccion_padre' => $direccion['iddireccion'], 'deleted_at' => null])->asArray()
                ->all();

            $arrDirecciones = array_merge($arrDirecciones, $direcciones);
        }
        return $arrDirecciones;
    }

    static function getNivelActual($iddireccion)
    {
        $nivel = Mds_certificacion_direccion::find()
            ->select('idnivelautorizacion')
            ->where(['idcertificaciondireccion' => $iddireccion])
            ->asArray()
            ->one();
        if ($nivel) {
            switch ($nivel['idnivelautorizacion']) {
                case Mds_certificacion::ID_NIVEL1:
                    $nivelActual = Mds_certificacion::AREA_NA1;
                    break;
                case Mds_certificacion::ID_NIVEL2:
                    $nivelActual = Mds_certificacion::AREA_NA2;
                    break;
                case Mds_certificacion::ID_NIVEL3:
                    $nivelActual = Mds_certificacion::AREA_NA3;
                    break;
                case Mds_certificacion::ID_NIVEL4:
                    $nivelActual = Mds_certificacion::AREA_NA4;
                    break;
                case Mds_certificacion::ID_NIVEL5:
                    $nivelActual = Mds_certificacion::AREA_ADMINISTRACION;
                    break;
            }
        } else {
            $nivelActual = Mds_certificacion::AREA_SOLICITANTE;
        }
        return $nivelActual;
    }
}
