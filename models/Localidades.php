<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "localidades".
 *
 * @property int $id
 * @property string|null $id_provincia
 * @property string $localidad
 */
class Localidades extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'localidades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['localidad'], 'required'],
            [['id_provincia'], 'string', 'max' => 100],
            [['localidad'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_provincia' => 'Id Provincia',
            'localidad' => 'Localidad',
        ];
    }

    public static function get_localidades($id_provincia = null)
    {
        $query = self::find()->select(['id', 'localidad'])->orderBy('localidad');
        if ($id_provincia !== null) {
            $query->where(['id_provincia' => $id_provincia]);
        }
        return $query->asArray()->all();
    }
}
