<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion_tipo".
 *
 * @property int $id_configuracion_tipo
 * @property string $descripcion
 * @property int $activo
 */
class ConfiguracionTipo extends \yii\db\ActiveRecord
{
    const SIN_ASIGNAR = 1;
    const TIPO_DOCUMENTO = 2;
    const GENERO = 3;
    const NACIONALIDAD = 4;
    const CATEGORIA_LABORAL = 5;
    const TIPO_DE_CONTRATACION = 6;
    const FUNCION_LABORAL = 7;
    const AFILIACION_GREMIAL = 8;
    const CAPACITACION = 9;
    const TIPO_OBSERVACION_EMPLEADO = 10;
    const TITULO_CAPACITACION = 11;
    const TIPO_ARTICULO = 12;
    const UNIDAD_DE_MEDIDA = 13;
    const MARCA = 14;
    const TIPO_RUBRO = 15;
    const PERFIL_DE_USUARIO = 16;
    const PERFIL_DE_USUARIO_TIPO_DE_PERMISO = 17;
    const PERFIL_DE_USUARIO_PERMISO_BOTON = 18;
    const PERFIL_DE_USUARIO_PERMISO_TARJETA = 19;
    const TIPO_ESTADO_ARTICULO = 20 ;
    const STOCK_ORIGEN = 21 ;
    const MARCA_VEHICULO = 22 ;
    const MARCA_MOTO = 23 ;


  
    public static function tableName()
    {
        return 'configuracion_tipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_configuracion_tipo' => 'Id',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
        ];
    }
}
