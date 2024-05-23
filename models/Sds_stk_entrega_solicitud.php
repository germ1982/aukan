<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_entrega_solicitud".
 *
 * @property int $identregasolicitud
 * @property string $fecha_hora
 * @property int $idorganismo
 * @property int $idcontacto
 * @property int|null $idpersona
 * @property string|null $observaciones
 * @property int $dni
 * @property int|null $identrega
 *
 * @property Mds_org_contacto $idcontacto0
 * @property Sds_stk_entrega $identrega0
 * @property Mds_org_organismo $idorganismo0
 * @property Sds_com_persona $idpersona0
 * @property SdsStkEntregaSolicitudItem[] $sdsStkEntregaSolicitudItems
 */
class Sds_stk_entrega_solicitud extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_stk_entrega_solicitud';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora', 'idorganismo', 'idcontacto', 'dni'], 'required'],
            [['fecha_hora','fdesde', 'fhasta'], 'safe'],
            [['idorganismo', 'idcontacto', 'idpersona', 'dni', 'identrega'], 'integer'],
            [['observaciones'], 'string'],
            [['identrega'], 'unique'],
            [['idcontacto'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['idcontacto' => 'idcontacto']],
            [['identrega'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_entrega::class, 'targetAttribute' => ['identrega' => 'identrega']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::class, 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'identregasolicitud' => 'Identregasolicitud',
            'fecha_hora' => 'Fecha/Hora',
            'idorganismo' => 'Idorganismo',
            'idcontacto' => 'Responsable',
            'idpersona' => 'Idpersona',
            'observaciones' => 'Observaciones',
            'dni' => 'Destinatario',
            'identrega' => 'Entrega',
        ];
    }

    /**
     * Gets query for [[Idcontacto0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcontacto0()
    {
        return $this->hasOne(Mds_org_contacto::class, ['idcontacto' => 'idcontacto']);
    }

    /**
     * Gets query for [[Identrega0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdentrega0()
    {
        return $this->hasOne(Sds_stk_entrega::class, ['identrega' => 'identrega']);
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Mds_org_organismo::class, ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersona0()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[SdsStkEntregaSolicitudItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsStkEntregaSolicitudItems()
    {
        return $this->hasMany(SdsStkEntregaSolicitudItem::class, ['identregasolicitud' => 'identregasolicitud']);
    }
}
