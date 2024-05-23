<?php
/**
 * This is the model class for table "mds_rum_localidad".
 *
 * @property int $id
 * @property int $id_provincia
 * @property string $nombre
 * @property int $codigo_postal
 */
namespace app\models;

use yii\base\Model;

class Mds_rum_filtro extends Model
{
    public $dni;
    public $nombre;   
    public $apellido; 
    public $genero;
    public $estado_civil;
    public $libreta_san; 
    public $libreta_fondo;
    public $disp_viaje;
    public $veh_prop;
    public $disp_hor;
    public $tienelicconducir;
    public $habilidades;
    public $nivel_institucion;
    public $culmino_formacion;
    public $filtro_formacion;
    public $filtro_capacitacion;
    public $filtro_laboral;
    public $edad_desde;
    public $edad_hasta;
    public $la_provincia;
    public $id_localidad;
    public $licencias;
    
    
    public function rules()
    {
        return [
            [['la_provincia','id_localidad','edad_hasta','edad_desde','filtro_laboral','filtro_capacitacion','filtro_formacion','culmino_formacion','nivel_institucion','habilidades','dni','nombre','apellido','genero','estado_civil','libreta_san','libreta_fondo','disp_viaje','veh_prop','disp_hor','tienelicconducir','licencias'], 'safe'],
        ];
    }
}
