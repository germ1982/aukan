<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_bdc_visita_equipo".
 *
 * @property int $idvisitaequipo
 * @property int $idvisita 
 * @property int|null $idequipo
 * @property string $ip
 * @property int $idresponsable
 * @property string|null $observaciones
 *
 * @property SdsBdcEquipo $idequipo0
 * @property MdsOrgContacto $idresponsable0
 *  @property SdsBdcVisita $idvisita0 
 */
class Sds_bdc_visita_equipo extends \yii\db\ActiveRecord
{
    public $responsable, $ip_filtro;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_bdc_visita_equipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idvisita','idequipo', 'idresponsable'], 'integer'],
            [['idvisita','ip','idequipo','idresponsable'], 'required'],
            [['observaciones'], 'string'],
            [['responsable', 'ip_filtro'], 'safe'],
            [['ip', 'ip_filtro'], 'ip', 'ipv6' => false],
            [['ip'], 'string'],
            [['idequipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_bdc_equipo:: className(), 'targetAttribute' => ['idequipo' => 'idequipo']],
            [['idresponsable'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::className(), 'targetAttribute' => ['idresponsable' => 'idcontacto']],
            [['idvisita'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_bdc_visita::className(), 'targetAttribute' => ['idvisita' => 'idvisita']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idvisitaequipo' => 'Visita Equipo',
            'idvisita' => 'Visita',
            'idequipo' => 'Equipo',
            'ip' => 'IP',
            'ip_filtro' => 'IP',
            'idresponsable' => 'Responsable',
            'responsable' => 'Responsable',
            'observaciones' => 'Observaciones',
        ];
    }

    /**
     * Gets query for [[Idequipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdequipo0()
    {
        return $this->hasOne(Sds_bdc_equipo::className(), ['idequipo' => 'idequipo']);
    }

    /**
     * Gets query for [[Idresponsable0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdresponsable0()
    {
        return $this->hasOne(Mds_org_contacto::className(), ['idcontacto' => 'idresponsable']);
    }

    /**
    * Gets query for [[Idvisita0]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getIdvisita0()
    {
        return $this->hasOne(Sds_bdc_visita::className(), ['idvisita' => 'idvisita']);
    }
}
