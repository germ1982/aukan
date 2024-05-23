<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rum_docadicional".
 *
 * @property int $id
 * @property int $libsanitaria
 * @property int $tienelicconducir
 * @property int $tienelibretaconstruct
 * @property int $idlicconducir
 * @property string $habilidades
 * @property int $disponibilidadviaje
 * @property int $vehiculopropio
 * @property string $estsupmax
 * @property string $oficioprincipal
 * @property int $iddisphoraria
 */
class Mds_rum_docadicional extends \yii\db\ActiveRecord
{
    public $poseelibreta;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rum_docadicional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'libsanitaria', 'tienelicconducir', 'tienelibretaconstruct', 'idlicconducir', 'habilidades', 'disponibilidadviaje', 'vehiculopropio', 'estsupmax', 'oficioprincipal', 'iddisphoraria'], 'required'],
            [['id', 'libsanitaria', 'tienelicconducir', 'tienelibretaconstruct', 'idlicconducir', 'disponibilidadviaje', 'vehiculopropio', 'iddisphoraria'], 'integer'],
            [['habilidades'], 'string'],
            [['estsupmax'], 'string', 'max' => 200],
            [['oficioprincipal'], 'string', 'max' => 254],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'libsanitaria' => 'Libsanitaria',
            'tienelicconducir' => 'Tienelicconducir',
            'tienelibretaconstruct' => 'Tienelibretaconstruct',
            'idlicconducir' => 'Idlicconducir',
            'habilidades' => 'Habilidades',
            'disponibilidadviaje' => 'Disponibilidadviaje',
            'vehiculopropio' => 'Vehiculopropio',
            'estsupmax' => 'Estsupmax',
            'oficioprincipal' => 'Oficioprincipal',
            'iddisphoraria' => 'Iddisphoraria',
        ];
    }
}
