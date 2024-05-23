<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_ent_tipo".
 *
 * @property int $idtipo
 * @property string $descripcion
 * @property int $activo
 *
 * @property SdsEntEntrega[] $sdsEntEntregas
 * @property SdsEntSolicitud[] $sdsEntSolicituds
 */
class Sds_ent_tipo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_ent_tipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'activo'], 'required'],
            [['activo','tiene_numero'], 'integer'],
            [['descripcion'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idtipo' => 'Idtipo',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
            'tiene_numero' => 'Lleva Número',
        ];
    }

    /**
     * Gets query for [[SdsEntEntregas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsEntEntregas()
    {
        return $this->hasMany(Sds_ent_entrega::className(), ['idtipo' => 'idtipo']);
    }

    /**
     * Gets query for [[SdsEntSolicituds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsEntSolicituds()
    {
        return $this->hasMany(Sds_ent_solicitud::className(), ['idtipo' => 'idtipo']);
    }
}
