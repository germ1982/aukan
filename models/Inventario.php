<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventario".
 *
 * @property int $idinventario
 * @property int|null $idarticulo
 * @property int|null $cantidad
 * @property int|null $iddispositivo
 * @property int|null $idempleado
 * @property int|null $idpersona
 * @property int|null $idestado
 * @property string|null $observacion 
 * @property int|null $activo
 * @property int|null $idtipo
 */
class Inventario extends \yii\db\ActiveRecord
{
    public $idpersona;
    public $origen_alta;
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
            [['idarticulo', 'cantidad', 'iddispositivo', 'idempleado', 'idestado', 'activo','idpersona'], 'integer'],
            [['observacion'], 'string'],
            [['origen_alta'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idinventario' => 'Id',
            'idarticulo' => 'Articulo',
            'cantidad' => 'Cantidad',
            'iddispositivo' => 'Dispositivo Deposito',
            'idempleado' => 'Empleado a cargo',
            'idestado' => 'Estado',
            'observacion' => 'Observacion',
            'activo' => 'Activo',
        ];
    }

    public static function get_por_dispositivo($iddispositivo)
{
    $sql = "SELECT 
                a.idarticulo,

                CONCAT(
                SUM(COALESCE(i.cantidad, 1)), ' - ',
                    ct.descripcion,' ',
                    cm.descripcion,' ',
                    a.modelo,' ',
                    cum.descripcion,' ',
                    a.descripcion
                ) AS descripcion
            FROM inventario i
            JOIN articulo a ON a.idarticulo = i.idarticulo
            JOIN configuracion ct ON ct.id_configuracion = a.idtipo
            JOIN configuracion cm ON cm.id_configuracion = a.idmarca
            JOIN configuracion cum ON cum.id_configuracion = a.id_unidad_medida
            where i.iddispositivo = $iddispositivo and i.activo = 1
            GROUP BY a.idarticulo
            ORDER BY ct.descripcion,
                    cm.descripcion,
                    a.modelo,
                    cum.descripcion,
                    a.descripcion;
                    ";
    return Articulo::findBySql($sql)->asArray()->all();
}
}
