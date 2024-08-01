<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organismo_dispositivo".
 *
 * @property int $iddispositivo
 * @property string $descripcion
 * @property int $idorganismo
 * @property int $es_oficial
 * @property int $es_organismo
 * @property int $activo
 * @property string $direccion
 * @property string $alias
 * @property int $idcapaitem
 * @property string|null $telefono
 *
 * @property Organismo $idorganismo0
 */
class OrganismoDispositivo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organismo_dispositivo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'idorganismo', 'direccion', 'alias'], 'required'],
            [['idorganismo', 'es_oficial', 'es_organismo', 'activo', 'idcapaitem'], 'integer'],
            [['descripcion', 'direccion', 'alias', 'telefono'], 'string', 'max' => 100],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddispositivo' => 'Iddispositivo',
            'descripcion' => 'Descripcion',
            'idorganismo' => 'Idorganismo',
            'es_oficial' => 'Es Oficial',
            'es_organismo' => 'Es Organismo',
            'activo' => 'Activo',
            'direccion' => 'Direccion',
            'alias' => 'Alias',
            'idcapaitem' => 'Idcapaitem',
            'telefono' => 'Telefono',
        ];
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Organismo::className(), ['idorganismo' => 'idorganismo']);
    }
}
