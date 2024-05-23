<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_relevamiento_planta".
 *
 * @property string $relevado
 * @property string|null $ultima_modificacion
 * @property string $apellido
 * @property string $nombre
 * @property int $documento
 * @property int|null $legajo
 * @property string $Cuil
 * @property string|null $mail
 * @property string|null $telefono
 * @property string $organismo_funciones_actualmente
 * @property string $Categoría
 * @property string|null $lugar_planta_permanente
 * @property string $edificio
 * @property string|null $fecha_ingreso
 * @property string $fecha_nacimiento
 * @property string $funcion_actual
 * @property string|null $observaciones
 */
class View_relevamiento_planta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_relevamiento_planta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ultima_modificacion', 'fecha_ingreso'], 'safe'],
            [['apellido', 'nombre', 'documento', 'organismo_funciones_actualmente', 'edificio', 'funcion_actual'], 'required'],
            [['documento', 'legajo'], 'integer'],
            [['observaciones', 'lugar_carga'], 'string'],
            [['relevado'], 'string', 'max' => 2],
            [['apellido', 'nombre', 'mail', 'edificio', 'funcion_actual'], 'string', 'max' => 100],
            [['Cuil'], 'string', 'max' => 11],
            [['telefono'], 'string', 'max' => 50],
            [['organismo_funciones_actualmente'], 'string', 'max' => 200],
            [['Categoría', 'lugar_planta_permanente'], 'string', 'max' => 255],
            [['fecha_nacimiento'], 'string', 'max' => 12],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'relevado' => 'Relevado',
            'ultima_modificacion' => 'Ultima Modificacion',
            'apellido' => 'Apellido',
            'nombre' => 'Nombre',
            'documento' => 'Documento',
            'legajo' => 'Legajo',
            'Cuil' => 'Cuil',
            'mail' => 'Mail',
            'telefono' => 'Telefono',
            'organismo_funciones_actualmente' => 'Organismo Funciones Actualmente',
            'Categoría' => 'Categoría',
            'lugar_planta_permanente' => 'Lugar Planta Permanente',
            'edificio' => 'Edificio',
            'fecha_ingreso' => 'Fecha Ingreso',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'funcion_actual' => 'Funcion Actual',
            'observaciones' => 'Observaciones',
            'lugar_carga' => 'Lugar de Carga'
        ];
    }

    public static function primaryKey()
    {
        return ['documento', 'legajo'];
    }
}
