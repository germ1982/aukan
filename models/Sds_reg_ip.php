<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_reg_ip".
 *
 * @property int $idip
 * @property int $ip Ej: 77
 * @property string $subred Ej: 10.1.73
 * @property int|null $idcontacto
 * @property string|null $observaciones
 * @property int $asignacion
 * @property int|null $sistema_operativo
 * @property int|null $procesador
 * @property int|null $memoria
 * @property int|null $disco
 * @property int|null $conectividad
 *
 * @property SdsComConfiguracion $asignacion0
 * @property SdsComConfiguracion $conectividad0
 * @property MdsOrgContacto $idcontacto0
 * @property SdsComConfiguracion $disco0
 * @property SdsComConfiguracion $memoria0
 * @property SdsComConfiguracion $procesador0
 * @property SdsComConfiguracion $sistemaOperativo
 */
class Sds_reg_ip extends \yii\db\ActiveRecord
{
    const ASIGNACION_PC_USUARIO=259;
    const ASIGNACION_IMPRESORA=603;
    
    public $idpersona;
    public $iddispositivo;
    public $ip_completa;
    public $organismo;
    public $usuario;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_reg_ip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip', 'subred', 'asignacion'], 'required'],
            [['ip', 'idcontacto', 'asignacion', 'sistema_operativo', 'procesador', 'memoria', 'disco', 'conectividad','iddispositivo','idpersona', 'idequipo','organismo'], 'integer'],
            [['observaciones'], 'string'],
            [['iddispositivo','idpersona','ip_completa','organismo'], 'safe'],
            [['subred','ip_completa'], 'string', 'max' => 11],
            [['asignacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['asignacion' => 'idconfiguracion']],
            [['conectividad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['conectividad' => 'idconfiguracion']],
            [['idcontacto'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::className(), 'targetAttribute' => ['idcontacto' => 'idcontacto']],
            [['disco'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['disco' => 'idconfiguracion']],
            [['memoria'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['memoria' => 'idconfiguracion']],
            [['procesador'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['procesador' => 'idconfiguracion']],
            [['sistema_operativo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['sistema_operativo' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idip' => 'Idip',
            'ip' => 'IP',
            'subred' => 'Subred',
            'idcontacto' => 'Idcontacto',
            'observaciones' => 'Observaciones',
            'asignacion' => 'Asignacion',
            'sistema_operativo' => 'Sis.Oper.',
            'procesador' => 'Procesador',
            'memoria' => 'Memoria',
            'disco' => 'Disco',
            'conectividad' => 'Conectividad',
        ];
    }

    /**
     * Gets query for [[Asignacion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsignacion0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'asignacion']);
    }

    /**
     * Gets query for [[Conectividad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConectividad0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'conectividad']);
    }

    /**
     * Gets query for [[Idcontacto0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcontacto0()
    {
        return $this->hasOne(Mds_org_contacto::className(), ['idcontacto' => 'idcontacto']);
    }

    /**
     * Gets query for [[Disco0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisco0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'disco']);
    }

    /**
     * Gets query for [[Memoria0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMemoria0()
    {
        return $this->hasOne(Sds_Com_configuracion::className(), ['idconfiguracion' => 'memoria']);
    }

    /**
     * Gets query for [[Procesador0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcesador0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'procesador']);
    }

    /**
     * Gets query for [[SistemaOperativo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSistemaOperativo()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'sistema_operativo']);
    }
}
