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
            [['iddispositivo_red', 'mascara', 'puerta_enlace','dns'], 'integer'],
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
            'iddispositivo_red' => 'Iddispositivo Red',
            'ip' => 'Ip',
            'mac' => 'Mac',
            'observacion' => 'Observacion',
            'mascara' => 'Mascara',
            'puerta_enlace' => 'Puerta Enlace',
            'dns' => 'DNS',
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
    public static function get_ips() {
        return self::find()->indexBy('idip')->asArray()->all();
    }   
}
