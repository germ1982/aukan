<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_legales_archivo".
 *
 * @property int $idlegalesarchivo
 * @property string|null $nombre
 * @property string|null $path
 * @property string|null $objeto
 * @property int|null $id_objeto
 * @property string $tipo
 * @property string $fecha_alta
 * @property int|null $activo
 */
class Mds_legales_archivo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_legales_archivo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_objeto', 'activo'], 'integer'],
            [['fecha_alta'], 'required'],
            [['fecha_alta'], 'safe'],
            [['nombre', 'path', 'objeto', 'tipo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlegalesarchivo' => 'Idlegalesarchivo',
            'nombre' => 'Nombre',
            'path' => 'Path',
            'objeto' => 'Objeto',
            'id_objeto' => 'Id Objeto',
            'fecha_alta' => 'Fecha Alta',
            'activo' => 'Activo',
        ];
    }

    public static function saveFile($nombre, $objeto, $tipo, $id, $path)
    {
        $archivo = new Mds_legales_archivo();
        $archivo->nombre = $nombre;
        $archivo->objeto = $objeto;
        $archivo->tipo = $tipo;
        $archivo->id_objeto = $id;
        $archivo->path = $path;
        $archivo->fecha_alta = date('Y-m-d H:i:s');
        $archivo->activo = 1;
        $archivo->save();
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_legales_archivo', $archivo->idlegalesarchivo, $archivo->getAttributes());
    }
}
