<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "edificio_oficina".
 *
 * @property int $idoficina
 * @property string $descripcion
 * @property int $idedificio
 * @property string|null $plano_ubicacion
 * @property int|null $activo
 */
class EdificioOficina extends \yii\db\ActiveRecord
{
    public $imageFile;

    public static function tableName()
    {
        return 'edificio_oficina';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'idedificio'], 'required'],
            [['idedificio', 'activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 100],
            [['plano_ubicacion'], 'string', 'max' => 45],
            [['imageFile'], 'file', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idoficina' => 'Id',
            'descripcion' => 'Oficina',
            'idedificio' => 'Edificio',
            'plano_ubicacion' => 'Ubicacion',
            'activo' => 'Activo',
        ];
    }

    public static function get_ubicacion_oficina($idoficina)
    {
        $oficina = self::findOne($idoficina);
        if ($oficina) {
            $edificio = Edificio::findOne($oficina->idedificio);
            return $edificio ? $edificio->descripcion . ' - ' . $oficina->descripcion : $oficina->descripcion;
        }
        return null;
    }

    // En app\models\EdificioOficina.php

    public static function get_oficinas_por_edificio($idedificio)
    {
        if (empty($idedificio)) {
            return [];
        }

        return self::find()
            ->where(['idedificio' => $idedificio, 'activo' => 1])
            ->orderBy(['descripcion' => SORT_ASC])
            ->all();
    }

    public function getIdedificio0()
    {
        // Define la relación de uno a uno/muchos a uno con la tabla Edificio
        return $this->hasOne(Edificio::class, ['idedificio' => 'idedificio']);
    }
}
