<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_legales_respuesta".
 *
 * @property string|null $dni
 * @property string|null $nombres
 * @property string|null $apellido
 * @property string|null $fecha_nacimiento
 * @property string|null $cuil
 * @property string|null $calle
 * @property string|null $numero
 * @property string|null $piso
 * @property string|null $departamento
 * @property string|null $codigo_postal
 * @property string|null $barrio
 * @property string|null $monoblock
 * @property string|null $ciudad
 * @property string|null $municipio
 * @property string|null $provincia
 * @property string|null $pais
 * @property string|null $nacionalidad
 * @property int $codigo_fallecido
 * @property string|null $mensaje_fallecido
 * @property string|null $fecha_fallecimiento
 * @property string|null $genero
 */
class Mds_conc_proneu extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_conc_proneu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nro_doc'], 'required'],
            [['idconcproneu', 'convenio_nro'], 'integer'],
            [[
                'tipo_doc', 'nro_doc', 'apellido', 'nombre',
                'fecha_nacimiento', 'sexo', 'estado_civil',
                'conyuge', 'titulo', 'empleado_activo', 'legajo',
                'fecha_alta', 'fecha_baja', 'relacion_laboral',
                'estado_puesto', 'estado'
            ], 'string', 'max' => 100],
            [['convenio_des'], 'string', 'max' => 255],
            [['servicio'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return
            [
                'idconcproneu' => '#',
                'tipo_doc' => 'Tipo Documento',
                'nro_doc' => 'DNI',
                'apellido' => 'Apellido',
                'nombre' => 'Nombre',
                'fecha_nacimiento' => 'Fecha Nacimiento',
                'sexo' => 'Sexo',
                'estado_civil' => 'Estado Civil',
                'conyuge' => 'Conyuge',
                'titulo' => 'Título',
                'empleado_activo' => 'Empleado Activo',
                'legajo' => 'Legajo',
                'servicio' => 'Servicio',
                'fecha_alta' => 'Fecha Alta',
                'fecha_baja' => 'Fecha Baja',
                'categoria' => 'Categoría',
                'relacion_laboral' => 'Relación Laboral',
                'convenio_nro' => 'Convenio Nro',
                'convenio_des' => 'Convenio Descripción',
                'estado_puesto' => 'Estado Puesto',
                'estado' => 'Estado',
            ];
    }
}
