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
    public $iddisco;
    public $idram;
    public $idmicro;
    public $idso;
    public $tiene_red;
    public $es_cpu;
    public $idseñal;
    public $idtecnologia_señal;
    public $tiene_ip;
    public $idip;
    public $idpuerta_enlace;
    public $idmascara_red;
    public $iddns_red;
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
            [['origen_alta', 'iddisco', 'idram', 'idmicro', 'idso', 'tiene_red', 'es_cpu', 'idseñal', 'idtecnologia_señal', 'tiene_ip', 'idip', 'idpuerta_enlace', 'idmascara_red', 'iddns_red'], 'safe'],
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
            'iddisco' => 'Disco',
            'idram' => 'RAM',
            'idmicro' => 'Micro',
            'idso' => 'Sistema Operativo',
            'tiene_red' => 'Red',
            'es_cpu' => 'CPU',
            'idseñal' => 'Señal',
            'idtecnologia_señal' => 'Tecnologia',
            'tiene_ip' => 'Tiene IP',
            'idip' => 'IP',
            'idpuerta_enlace' => 'Puerta de Enlace',
            'idmascara_red' => 'Máscara de Red',
            'iddns_red' => 'DNS de Red',
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
