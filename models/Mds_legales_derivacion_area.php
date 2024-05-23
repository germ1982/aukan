<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_legales_derivacion".
 *
 * @property int $idlegalesderivacionarea
 * @property int $idoficio
 * @property int $iddispositivo
 */
class Mds_legales_derivacion_area extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_legales_derivacion_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idoficio', 'iddispositivo'], 'required'],
            [['idoficio', 'iddispositivo'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idoficio' => 'id oficio',
            'iddispositivo' => 'id dispositivo'
        ];
    }

    public function getOficio()
    {
        return $this->hasOne(Mds_legales_oficio::class, ['idoficio' => 'idoficio']);
    }
    public function getDispositivo()
    {
        return $this->hasOne(Mds_org_dispositivo::class, ['iddispositivo' => 'iddispositivo']);
    }
}
