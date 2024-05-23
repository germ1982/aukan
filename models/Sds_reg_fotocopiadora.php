<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_reg_fotocopiadora".
 *
 * @property int $idfotocopiadora
 * @property int $idproveedor
 * @property string $expediente_fisico
 * @property string $expediente_gde
 * @property string $safipro
 * @property int $idorganismo
 * @property string $lugar
 * @property int $idequipo
 * @property string $vencimiento
 * @property int $copias
 * @property string|null $observaciones
 *
 * @property Sds_bdc_equipo $idequipo0
 * @property Mds_org_organismo $idorganismo0
 * @property Sds_com_configuracion $idproveedor0
 */
class Sds_reg_fotocopiadora extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_reg_fotocopiadora';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idproveedor', 'expediente_fisico', 'expediente_gde', 'safipro', 'idorganismo', 'lugar', 'idequipo', 'vencimiento', 'copias'], 'required'],
            [['idproveedor', 'idorganismo', 'idequipo', 'copias'], 'integer'],
            [['vencimiento'], 'safe'],
            [['observaciones'], 'string'],
            [['expediente_fisico', 'expediente_gde', 'safipro'], 'string', 'max' => 45],
            [['lugar'], 'string', 'max' => 255],
            [['idequipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_bdc_equipo::class, 'targetAttribute' => ['idequipo' => 'idequipo']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::class, 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['idproveedor'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idproveedor' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idfotocopiadora' => 'Idfotocopiadora',
            'idproveedor' => 'Proveedor',
            'expediente_fisico' => 'Exp Físico',
            'expediente_gde' => 'Exp GDE',
            'safipro' => 'Safipro',
            'idorganismo' => 'Organismo',
            'lugar' => 'Lugar Real',
            'idequipo' => 'Equipo',
            'copias' => 'Copias',
            'vencimiento' => 'Vencimiento',
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
        return $this->hasOne(Sds_bdc_equipo::class, ['idequipo' => 'idequipo']);
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
     * Gets query for [[Idproveedor0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdproveedor0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idproveedor']);
    }
}
