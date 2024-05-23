<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_hor_remanente".
 *
 * @property int $idremanente
 * @property int $idcontacto
 * @property int $anio
 * @property int $dias
 *
 * @property Mds_org_contacto $idcontacto0
 */
class Mds_hor_remanente extends \yii\db\ActiveRecord
{
    const ID_FECHA_ALTA=2329;
    public $temp_excel_import;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_hor_remanente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcontacto', 'anio', 'dias'], 'required'],
            [['idcontacto', 'anio', 'dias'], 'integer'],
            [['idcontacto'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['idcontacto' => 'idcontacto']],
            [['temp_excel_import'], 'file', 'extensions' => 'xlsx,xls', 'maxSize' => 100000000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idremanente' => 'Idremanente',
            'idcontacto' => 'Idcontacto',
            'anio' => 'Anio',
            'dias' => 'Dias',
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
}
