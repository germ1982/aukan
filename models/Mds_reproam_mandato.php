<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_reproam_mandato".
 *
 * @property int $idmandato
 * @property int $idregistro
 * @property string $fecha_desde
 * @property string|null $fecha_hasta
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Mds_reproam_registro $idregistro
 */
class Mds_reproam_mandato extends \yii\db\ActiveRecord
{
    public $idlocalidad;
    public $idzona;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_reproam_mandato';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idregistro', 'titular', 'fecha_desde', 'idusuario_carga', 'created_at'], 'required'],
            [['idregistro', 'titular',  'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['observaciones'], 'string'],
            [['fecha_desde', 'fecha_hasta', 'created_at', 'updated_at', 'deleted_at', 'idlocalidad', 'idzona'], 'safe'],
            [['idregistro'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_reproam_registro::class, 'targetAttribute' => ['idregistro' => 'idregistro']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmandato' => 'Idmandato',
            'idregistro' => 'Registro',
            'fecha_desde' => 'Fecha Desde',
            'fecha_hasta' => 'Fecha Hasta',
            'titular' => 'Carácter',
            'observaciones' => 'Observaciones',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Activo',
        ];
    }

    /**
     * Gets query for [[Idregistro0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegistro()
    {
        return $this->hasOne(Mds_reproam_registro::class, ['idregistro' => 'idregistro']);
    }

}
