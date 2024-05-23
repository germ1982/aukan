<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_r_diagnostico".
 *
 * @property int $iddiagnostico  
 * @property int $valor
 * @property int $idvardimension
 * @property string $fecha
 * @property int|null $iddispositivo
 * @property int|null $idejido
 * @property int|null $valor_dimension
 * @property int $activo
 *
 * @property Sds_gis_capa_item $iddispositivo0
 * @property Mds_r_ejidos $idejido0
 * @property Mds_r_variable_dimension $idvardimension0
 */
class Mds_r_diagnostico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_r_diagnostico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['valor', 'idvardimension', 'iddispositivo', 'idejido','activo'], 'integer'],
            [['fecha'], 'required'],
            [['fecha','activo','valor_dimension'], 'safe'],
            [['iddispositivo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_gis_capa_item::className(), 'targetAttribute' => ['iddispositivo' => 'idcapaitem']],
            [['idejido'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_r_ejidos::className(), 'targetAttribute' => ['idejido' => 'idejido']],
            [['idvardimension'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_r_variable_dimension::className(), 'targetAttribute' => ['idvardimension' => 'idvardimension']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddiagnostico' => 'Iddiagnostico',
            'valor' => 'Valor',
            'idvardimension' => 'Idvardimension',
            'fecha' => 'Fecha',
            'iddispositivo' => 'Iddispositivo',
            'idejido' => 'Idejido',
        ];
    }

    /**
     * Gets query for [[Iddispositivo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddispositivo0()
    {
        return $this->hasOne(Sds_gis_capa_item::className(), ['idcapaitem' => 'iddispositivo']);
    }

    /**
     * Gets query for [[Idejido0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdejido0()
    {
        return $this->hasOne(Mds_r_ejidos::className(), ['idejido' => 'idejido']);
    }

    /**
     * Gets query for [[Idvardimension0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdvardimension0()
    {
        return $this->hasOne(Mds_r_variable_dimension::className(), ['idvardimension' => 'idvardimension']);
    }
}
