<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_pad_padron".
 *
 * @property int $idpadron
 * @property string $circuito_anterior
 * @property string $circuito_nuevo
 * @property string $denominacion_circuito
 * @property string $afiliacion
 * @property string $documento
 * @property string $apellido
 * @property string $nombre
 * @property string $calle
 * @property string|null $altura
 */
class Mds_pad_padron extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_pad_padron';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['circuito_anterior', 'circuito_nuevo', 'denominacion_circuito', 'afiliacion', 'documento', 'apellido', 'nombre', 'calle'], 'required'],
            [['circuito_anterior', 'circuito_nuevo', 'denominacion_circuito', 'afiliacion', 'documento', 'apellido', 'nombre', 'calle', 'altura'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpadron' => 'Idpadron',
            'circuito_anterior' => 'Circuito Anterior',
            'circuito_nuevo' => 'Circuito Nuevo',
            'denominacion_circuito' => 'Denominacion Circuito',
            'afiliacion' => 'Afiliacion',
            'documento' => 'Documento',
            'apellido' => 'Apellido',
            'nombre' => 'Nombre',
            'calle' => 'Calle',
            'altura' => 'Altura',
        ];
    }
}
