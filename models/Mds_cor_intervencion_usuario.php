<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_cor_intervencion_usuario".
 *
 * @property int $idintervencionusuario
 * @property int $idintervencion
 * @property int $idusuario
 *
 * @property Mds_cor_intervencion $idintervencion0
 * @property Mds_seg_usuario $idusuario0
 */
class Mds_cor_intervencion_usuario extends \yii\db\ActiveRecord
{
    // Auxiliares para el create 
    public $agregar;
    public $idorganismo;
    public $iddispositivo;
    public $idcompartir;
    public $usuarios;
    public $organismos;
    public $dispositivos;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_cor_intervencion_usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idintervencion', 'idusuario'], 'required'],
            [['idintervencion', 'idusuario', 'editar'], 'integer'],
            [['agregar', 'idorganismo', 'iddispositivo', 'usuarios', 'organismos', 'dispositivos'], 'safe'],
            [['idintervencion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_cor_intervencion::className(), 'targetAttribute' => ['idintervencion' => 'idintervencion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idintervencionusuario' => 'Idintervencionusuario',
            'idintervencion' => 'Idintervencion',
            'idusuario' => 'Idusuario'
        ];
    }

    /**
     * Gets query for [[Idintervencion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdintervencion0()
    {
        return $this->hasOne(Mds_cor_intervencion::className(), ['idintervencion' => 'idintervencion']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario']);
    }
}
