<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_inv_entrega".
 *
 * @property int $identrega
 * @property int $idespecie Clave foranea a la tabla de configuracion
 * @property int|null $cantidad
 * @property string|null $fecha
 * @property int|null $estado
 * @property int $idpersona clave foranea a la tabla mds_inv_persona
 * @property string|null $fecha_entrega
 *
 * @property Sds_com_configuracion $idespecie0
 * @property Mds_inv_persona $idpersona0
 */
class Mds_inv_entrega extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $picture;
    public $lugardeentrega;
    public static function tableName()
    {
        return 'mds_inv_entrega';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idespecie', 'cantidad', 'estado', 'idpersona','idlugar','temporada'], 'integer'],
            [['fecha','fecha_entrega'], 'safe'],
            [['idpersona'], 'required'],
            [['idespecie'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['idespecie' => 'idconfiguracion']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_inv_persona::className(), 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['idlugar'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_gis_capa_item::className(), 'targetAttribute' => ['idlugar' => 'idcapaitem']],
            [['temporada'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['temporada' => 'idconfiguracion']],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'identrega' => 'Identrega',
            'idespecie' => 'Especie',
            'cantidad' => 'Cantidad',
            'fecha' => 'Fecha',
            'estado' => 'Estado',
            'idpersona' => 'Idpersona',
            'fecha_entrega' => 'Fecha Entrega',
            'temporada' => 'Temporada',
            
        ];
    }

    /**
     * Gets query for [[Idespecie0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdespecie0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'idespecie']);
    }

    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersona0()
    {
        return $this->hasOne(Mds_inv_persona::className(), ['idpersona' => 'idpersona']);
    }
}
