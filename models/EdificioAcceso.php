<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "edificio_acceso".
 *
 * @property int $id_edificio_acceso
 * @property int $idedificio
 * @property string $descripcion
 *
 * @property Edificio $idedificio0
 */
class EdificioAcceso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'edificio_acceso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_edificio_acceso', 'idedificio', 'descripcion'], 'required'],
            [['id_edificio_acceso', 'idedificio'], 'integer'],
            [['descripcion'], 'string', 'max' => 255],
            [['id_edificio_acceso'], 'unique'],
            [['idedificio'], 'exist', 'skipOnError' => true, 'targetClass' => Edificio::className(), 'targetAttribute' => ['idedificio' => 'idedificio']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_edificio_acceso' => 'Id Edificio Acceso',
            'idedificio' => 'Idedificio',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * Gets query for [[Idedificio0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdedificio0()
    {
        return $this->hasOne(Edificio::className(), ['idedificio' => 'idedificio']);
    }
    public static function getListaAccesos($idedificio = null)
{
    $query = self::find()
        ->select(['descripcion', 'id_edificio_acceso'])
        ->indexBy('id_edificio_acceso');

    if ($idedificio !== null) {
        $query->where(['idedificio' => $idedificio]);
    }

    return $query->column();
}

public static function get_acceso_descripcion($id_edificio_acceso)
{
    $acceso = self::findOne($id_edificio_acceso);

    return $acceso ? $acceso->descripcion : null;
}

}
