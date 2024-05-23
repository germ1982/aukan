<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_legales_derivacion".
 *
 * @property int $idlegalesderivacion
 * @property int $idlegalesoficio
 * @property int $idusuario
 * @property string $fecha_derivacion
 */
class MdsLegalesDerivacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_legales_derivacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idlegalesoficio', 'idusuario', 'fecha_derivacion'], 'required'],
            [['idlegalesoficio', 'idusuario'], 'integer'],
            [['fecha_derivacion'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlegalesderivacion' => 'Idlegalesderivacion',
            'idlegalesoficio' => 'Idlegalesoficio',
            'idusuario' => 'Idusuario',
            'fecha_derivacion' => 'Fecha Derivacion',
        ];
    }
}
