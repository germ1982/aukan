<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "efemerides".
 *
 * @property int $idefemeride
 * @property string $fecha Fecha histórica o de fundación (AAAA-MM-DD)
 * @property string $titulo Título del hito o aniversario
 * @property string $descripcion Contexto histórico, geográfico y turístico
 * @property int $activo Estado de la efeméride (1: Activa, 0: Inactiva)
 */
class Efemerides extends \yii\db\ActiveRecord
{
    // Atributos virtuales para el formulario y gestión de reglas/fotos
    public $regla;       // Checkbox booleano para saber si usa regla dinámica
    public $num_semana;  // Número de semana (1 a 5)
    public $dia_semana;  // Día de la semana (0 a 6)
    public $fotos;       // Archivos múltiples subidos desde el input file

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'efemerides';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'titulo', 'descripcion'], 'required'],
            [['fecha'], 'safe'],
            [['descripcion'], 'string'],
            [['activo', 'num_semana', 'dia_semana'], 'integer'],
            [['regla'], 'boolean'],
            [['titulo'], 'string', 'max' => 150],
            [['fotos'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idefemeride' => 'ID',
            'fecha' => 'Fecha',
            'titulo' => 'Título',
            'descripcion' => 'Descripción',
            'activo' => 'Activo',
            'regla' => '¿Es regla dinámica?',
            'num_semana' => 'Semana del mes',
            'dia_semana' => 'Día de la semana',
            'fotos' => 'Galería de Fotos',
        ];
    }

    /**
     * Método auxiliar para inicializar los atributos virtuales al hacer un Update
     */
    public function loadReglaData()
    {
        $reglaDb = (new \yii\db\Query())
            ->from('efemerides_reglas')
            ->where(['idefemeride' => $this->idefemeride])
            ->one();

        if ($reglaDb) {
            $this->regla = true;
            $this->num_semana = $reglaDb['num_semana'];
            $this->dia_semana = $reglaDb['dia_semana'];
        } else {
            $this->regla = false;
        }
    }
}