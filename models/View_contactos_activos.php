<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_contactos_activos".
 *
 * @property int $idcontacto
 * @property string|null $mail
 * @property string|null $telefono
 * @property int $iddispositivo
 * @property int $activo
 * @property int|null $legajo
 * @property int $idpersona
 * @property int $rotativo
 * @property int $eventual
 * @property int $acompaniante
 * @property int $interno
 * @property int|null $perfil
 * @property int|null $idoficina
 * @property int|null $actividad
 * @property int $esencial
 * @property int $planta_politica
 * @property int|null $categoria
 * @property string|null $ubicacion_fisica
 * @property int|null $tipo_contratacion 0: Planta Política; 1: Planta Permanente; 2: Eventuales; 3: Contrato
 * @property string|null $fecha_ingreso
 * @property int|null $unidad_operativa
 * @property string|null $cuil
 * @property float|null $antiguedad_administrativa
 * @property float|null $antiguedad_privada
 * @property float|null $antiguedad_total
 * @property string|null $titulo
 * @property string|null $fecha_ingreso_planta
 * @property int $turno_rotativo
 * @property int $ficha
 * @property int $retenido
 * @property int $documento
 * @property int $documento_tipo
 * @property int $nacionalidad
 * @property int $genero
 * @property string $fecha_nacimiento
 * @property string $nombre
 * @property string $apellido
 * @property int|null $padre
 * @property int $conviviente
 * @property string|null $domicilio_calle
 * @property string|null $domicilio_numero
 * @property int|null $idlocalidad
 * @property float|null $latitud
 * @property float|null $longitud
 */
class View_contactos_activos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_contactos_activos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcontacto', 'iddispositivo', 'activo', 'legajo', 'idpersona', 'rotativo', 'eventual', 'acompaniante', 'interno', 'perfil', 'idoficina', 'actividad', 'esencial', 'planta_politica', 'categoria', 'tipo_contratacion', 'unidad_operativa', 'turno_rotativo', 'ficha', 'retenido', 'documento', 'documento_tipo', 'nacionalidad', 'genero', 'padre', 'conviviente', 'idlocalidad'], 'integer'],
            [['idpersona', 'documento', 'documento_tipo', 'nacionalidad', 'genero', 'nombre', 'apellido'], 'required'],
            [['fecha_ingreso', 'fecha_ingreso_planta', 'fecha_nacimiento'], 'safe'],
            [['antiguedad_administrativa', 'antiguedad_privada', 'antiguedad_total', 'latitud', 'longitud'], 'number'],
            [['mail', 'nombre', 'apellido'], 'string', 'max' => 100],
            [['telefono'], 'string', 'max' => 50],
            [['ubicacion_fisica', 'domicilio_numero'], 'string', 'max' => 45],
            [['cuil'], 'string', 'max' => 11],
            [['titulo'], 'string', 'max' => 200],
            [['domicilio_calle'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcontacto' => 'Idcontacto',
            'mail' => 'Mail',
            'telefono' => 'Telefono',
            'iddispositivo' => 'Iddispositivo',
            'activo' => 'Activo',
            'legajo' => 'Legajo',
            'idpersona' => 'Idpersona',
            'rotativo' => 'Rotativo',
            'eventual' => 'Eventual',
            'acompaniante' => 'Acompaniante',
            'interno' => 'Interno',
            'perfil' => 'Perfil',
            'idoficina' => 'Idoficina',
            'actividad' => 'Actividad',
            'esencial' => 'Esencial',
            'planta_politica' => 'Planta Politica',
            'categoria' => 'Categoria',
            'ubicacion_fisica' => 'Ubicacion Fisica',
            'tipo_contratacion' => 'Tipo Contratacion',
            'fecha_ingreso' => 'Fecha Ingreso',
            'unidad_operativa' => 'Unidad Operativa',
            'cuil' => 'Cuil',
            'antiguedad_administrativa' => 'Antiguedad Administrativa',
            'antiguedad_privada' => 'Antiguedad Privada',
            'antiguedad_total' => 'Antiguedad Total',
            'titulo' => 'Titulo',
            'fecha_ingreso_planta' => 'Fecha Ingreso Planta',
            'turno_rotativo' => 'Turno Rotativo',
            'ficha' => 'Ficha',
            'retenido' => 'Retenido',
            'documento' => 'Documento',
            'documento_tipo' => 'Documento Tipo',
            'nacionalidad' => 'Nacionalidad',
            'genero' => 'Genero',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'padre' => 'Padre',
            'conviviente' => 'Conviviente',
            'domicilio_calle' => 'Domicilio Calle',
            'domicilio_numero' => 'Domicilio Numero',
            'idlocalidad' => 'Idlocalidad',
            'latitud' => 'Latitud',
            'longitud' => 'Longitud',
        ];
    }
}
