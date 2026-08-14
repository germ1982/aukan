<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informatica_ip".
 *
 * @property int $idip
 * @property int $iddispositivo_red
 * @property string $ip
 * @property string|null $mac
 * @property string|null $observacion
 * @property int|null $mascara
 * @property int|null $puerta_enlace
 *
 * @property InventarioDispositivoRed $iddispositivoRed
 * @property Configuracion $mascara0
 * @property Configuracion $puertaEnlace
 */
class InformaticaIp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'informatica_ip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip'], 'required'],
            [['iddispositivo_red', 'mascara', 'puerta_enlace', 'dns', 'usada'], 'integer'],
            [['observacion'], 'string'],
            [['ip'], 'string', 'max' => 45],
            [['mac'], 'string', 'max' => 17],
            [['ip'], 'unique'],
            [['mascara'], 'exist', 'skipOnError' => true, 'targetClass' => Configuracion::className(), 'targetAttribute' => ['mascara' => 'id_configuracion']],
            [['puerta_enlace'], 'exist', 'skipOnError' => true, 'targetClass' => Configuracion::className(), 'targetAttribute' => ['puerta_enlace' => 'id_configuracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idip' => 'Idip',
            'iddispositivo_red' => 'Dispositivo Vinculado',
            'ip' => 'Ip',
            'mac' => 'Mac Address',
            'observacion' => 'Observacion',
            'mascara' => 'Mascara',
            'puerta_enlace' => 'Puerta Enlace',
            'dns' => 'DNS',
            'usada' => 'Usada',
        ];
    }

    /**
     * Gets query for [[IddispositivoRed]].
     *
     * @return \yii\db\ActiveQuery
     */


    /**
     * Gets query for [[Mascara0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMascara0()
    {
        return $this->hasOne(Configuracion::className(), ['id_configuracion' => 'mascara']);
    }

    /**
     * Gets query for [[PuertaEnlace]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPuertaEnlace()
    {
        return $this->hasOne(Configuracion::className(), ['id_configuracion' => 'puerta_enlace']);
    }
    public static function get_ips()
    {
        return self::find()->indexBy('idip')->asArray()->all();
    }

    // En InformaticaIp.php

    public static function liberar($id)
    {
        // Al liberar reseteamos el estado a 0 y limpiamos los datos asociados a la red
        return self::updateAll([
            'usada' => 0,
            'iddispositivo_red' => null,
            'mac' => null,
            'observacion' => null,
            'dns' => null
        ], ['idip' => $id]);
    }

    public static function ocupar($id)
    {
        // Al ocupar solo marcamos el flag como usado (los datos se completan al asignar)
        return self::updateAll(['usada' => 1], ['idip' => $id]);
    }

    public static function get_dispositivo_red($id)
    {
        // Si no hay ID, retornamos un arreglo vacío
        if (!$id) {
            return ['observacion' => '', 'iddispositivo' => null];
        }

        $sql = "SELECT 
                CONCAT(cat.descripcion, ' en ' , e.descripcion_fija, ' ', eo.descripcion,' (', d.descripcion,' - ', COALESCE(CONCAT(p.apellido, ' ', p.nombre), 'Sin asignar'), ')') as observacion,
                d.iddispositivo
            FROM inventario i
            JOIN articulo a ON i.idarticulo = a.idarticulo
            JOIN configuracion cat ON a.idtipo = cat.id_configuracion
            JOIN organismo_dispositivo d ON i.iddispositivo = d.iddispositivo
            JOIN edificio_oficina eo ON eo.idoficina = d.idoficina
            JOIN edificio e ON e.idedificio = eo.idedificio
            JOIN inventario_dispositivo_red idr ON idr.idinventario = i.idinventario
            LEFT JOIN empleado em ON em.idempleado = i.idempleado
            LEFT JOIN personas p ON p.idpersona = em.idpersona
            WHERE idr.iddispositivo_red = :id";

        $resultado = \Yii::$app->db->createCommand($sql, [':id' => $id])->queryOne();

        return $resultado ?: ['observacion' => '', 'iddispositivo' => null];
    }
}
