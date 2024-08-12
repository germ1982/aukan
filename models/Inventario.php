<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventario".
 *
 * @property int $idInventario
 * @property int|null $idarticulo
 * @property int|null $cantidad
 * @property int|null $iddispositivo
 * @property int|null $idempleado
 * @property int|null $idestado
 * @property string|null $observacion
 * @property int|null $activo
 */
class Inventario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idarticulo', 'cantidad', 'iddispositivo', 'idempleado', 'idestado', 'activo'], 'integer'],
            [['observacion'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idInventario' => 'Id',
            'idarticulo' => 'Articulo',
            'cantidad' => 'Cantidad',
            'iddispositivo' => 'Dispositivo Deposito',
            'idempleado' => 'Empleado a cargo',
            'idestado' => 'Estado',
            'observacion' => 'Observacion',
            'activo' => 'Activo',
        ];
    }
}
