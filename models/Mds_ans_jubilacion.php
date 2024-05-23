<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_ans_jubilacion".
 *
 * @property int $idjubilacion
 * @property string|null $tipo_dni
 * @property string|null $dni
 * @property string|null $cuil
 * @property string|null $nombre_apellido
 * @property string|null $beneficio
 * @property string|null $periodo
 */
class Mds_ans_jubilacion extends \yii\db\ActiveRecord
{
    //ANOTEZE: Agrego variable auxiliar para filtro beneficio personalizado
    public $beneficio_grupo;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_ans_jubilacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo_dni'], 'string', 'max' => 2],
            [['dni'], 'string', 'max' => 8],
            [['cuil', 'beneficio'], 'string', 'max' => 11],
            [['nombre_apellido'], 'string', 'max' => 27],
            [['periodo'], 'string', 'max' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idjubilacion' => 'Idjubilacion',
            'tipo_dni' => 'Tipo Dni',
            'dni' => 'DNI',
            'cuil' => 'CUIL',
            'nombre_apellido' => 'Nombre y Apellido',
            'beneficio' => 'Beneficio',
            'periodo' => 'Período',
        ];
    }
}
