<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_com_persona_georef_query".
 *
 * @property int $idpersonageorefquery
 * @property int|null $tipo 0=INNER JOIN;1=LEFT JOIN;2=RIGTH JOIN;3=WHERE
 * @property string|null $descripcion
 * @property string|null $on
 */
class Sds_com_persona_georef_query extends \yii\db\ActiveRecord
{
    const INNER_JOIN = 0;
    const LEFT_JOIN = 1;
    const RIGHT_JOIN = 2;
    const WHERE = 3;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_com_persona_georef_query';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo'], 'integer'],
            [['descripcion', 'on'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpersonageorefquery' => 'Idpersonageorefquery',
            'tipo' => 'Tipo',
            'descripcion' => 'Descripcion',
            'on' => 'On',
        ];
    }
}
