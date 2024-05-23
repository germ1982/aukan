<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_acomp_asistencia".
 *
 * @property int $idasistencia
 * @property int $idusuario_carga Usuario que carga el registro
 * @property int|null $idbeneficiario
 * @property int|null $idlocalidad
 * @property int|null $idlocalidad_ingreso
 * @property int|null $idriesgo
 * @property string|null $observaciones
 * @property string|null $periodo_desde
 * @property string|null $periodo_hasta
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property SdsComPersona $idbeneficiario0
 * @property SdsComLocalidad $idlocalidad0
 * @property SdsComLocalidad $idlocalidadIngreso
 * @property SdsComConfiguracion $idriesgo0
 * @property MdsSegUsuario $usuarioCarga
 */
class Mds_acomp_asistencia extends \yii\db\ActiveRecord
{

    const ID_ITEM_SEGURIDAD = 122;
    const ID_ROL_GLOBAL = 111;
    const ID_ROL_USUARIO = 112;
    const ID_ROL_ADMIN_GENERAL = 177;
    const ID_PROVINCIA_NEUQUEN = 58;
    const ID_LOCALIDAD_NEUQUEN_CAPITAL = 58035070;
    const CONFIGURACION_TIPO_RIESGO = 102;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_acomp_asistencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idbeneficiario', 'idusuario_carga', 'idlocalidad', 'idlocalidad_ingreso', 'idriesgo', 'periodo_desde', 'periodo_hasta','created_at'], 'required'],
            [['idusuario_carga', 'idusuario_borra' , 'idbeneficiario', 'idlocalidad', 'idlocalidad_ingreso', 'idriesgo'], 'integer'],
            [['observaciones'], 'string'],
            [['periodo_desde', 'periodo_hasta', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['idbeneficiario'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idbeneficiario' => 'idpersona']],
            [['idlocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::class, 'targetAttribute' => ['idlocalidad' => 'idlocalidad']],
            [['idlocalidad_ingreso'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::class, 'targetAttribute' => ['idlocalidad_ingreso' => 'idlocalidad']],
            [['idriesgo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idriesgo' => 'idconfiguracion']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idasistencia' => '#',
            'idusuario_carga' => 'Idusuario Carga',
            'idbeneficiario' => 'Beneficiario',
            'idlocalidad' => 'Localidad',
            'idlocalidad_ingreso' => 'Localidad Ingreso',
            'idriesgo' => 'Riesgo',
            'observaciones' => 'Observaciones',
            'periodo_desde' => 'Periodo Desde',
            'periodo_hasta' => 'Periodo Hasta',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Activo',
        ];
    }

    /**
     * Gets query for [[Idbeneficiario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBeneficiario()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idbeneficiario']);
    }

    /**
     * Gets query for [[Idlocalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidad()
    {
        return $this->hasOne(Sds_com_localidad::class, ['idlocalidad' => 'idlocalidad']);
    }

    /**
     * Gets query for [[IdlocalidadIngreso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidadIngreso()
    {
        return $this->hasOne(Sds_com_localidad::class, ['idlocalidad' => 'idlocalidad_ingreso']);
    }

    /**
     * Gets query for [[Idriesgo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRiesgo()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idriesgo']);
    }

    /**
     * Gets query for [[usuarioCarga]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_carga']);
    }
}
