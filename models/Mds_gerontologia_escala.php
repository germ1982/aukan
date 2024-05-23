<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_gerontologia_escala".
 *
 * @property int $idgerontologiaescala
 * @property int|null $iditem
 * @property int|null $valor
 *
 * @property SdsComConfiguracion $iditem0
 */
class Mds_gerontologia_escala extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_gerontologia_escala';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idconfiguracion', 'valor'], 'integer'],
            [['idconfiguracion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idconfiguracion' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idgerontologiaescala' => 'Idgerontologiaescala',
            'idconfiguracion' => 'idconfiguracion',
            'valor' => 'Valor',
        ];
    }

    /**
     * Gets query for [[Iditem0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIditem0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idconfiguracion']);
    }
}
