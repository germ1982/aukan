<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "empleado".
 *
 * @property int $idempleado
 * @property int $idpersona
 * @property int $iddispositivo
 * @property int $legajo
 * @property string|null $email
 * @property string|null $telefono
 * @property string|null $foto
 * @property int $activo
 * @property int|null $categoria
 * @property int|null $antiguedad_legal
 * @property int|null $antiguedad_total
 * @property string|null $ingreso_real
 * @property string|null $ingreso_administrativo
 * @property int|null $contratacion
 * @property int|null $cuil
 * @property int|null $funcion
 * @property int|null $fichado
 * @property int|null $afiliacion
 */
class Empleado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $documento;
    public $descripcion;
    public $imageFile;
    public $orden;
    public static function tableName()
    {
        return 'empleado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersona', 'iddispositivo', 'legajo', 'activo','foto'], 'required'],
            [['idpersona', 'iddispositivo', 'legajo', 'activo', 'categoria', 'antiguedad_legal', 'antiguedad_total', 'contratacion', 'cuil', 'funcion', 'fichado', 'afiliacion'], 'integer'],
            [['ingreso_real', 'ingreso_administrativo'], 'safe'],
            [['email', 'foto','descripcion'], 'string', 'max' => 100],
            [['telefono'], 'string', 'max' => 50],
            [['imageFile'], 'file', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idempleado' => 'Id',
            'idpersona' => 'Empleado',
            'iddispositivo' => 'Sector',
            'legajo' => 'Legajo',
            'email' => 'Email',
            'telefono' => 'Telefono',
            'foto' => 'Imagen',
            'activo' => 'Activo',
            'categoria' => 'Categoria',
            'antiguedad_legal' => 'Antiguedad Legal',
            'antiguedad_total' => 'Antiguedad Total',
            'ingreso_real' => 'Ingreso Real',
            'ingreso_administrativo' => 'Ingreso Administrativo',
            'contratacion' => 'Contratacion',
            'cuil' => 'Cuil',
            'funcion' => 'Funcion',
            'fichado' => 'Fichado',
            'afiliacion' => 'Afiliacion',
        ];
    }

    public function getPersona()
    {
        return $this->hasOne(Persona::className(), ['idpersona' => 'idpersona']);
    }

    public static function get_empleados($modulo='')
    {
        $filtro = $modulo ? " and idempleado in (SELECT idempleado from $modulo)" :'';
        $sql = "SELECT  e.idempleado,concat( p.apellido ,' ', p.nombre) as descripcion
                from empleado e 
                join personas p on p.idpersona = e.idpersona
                where e.activo=1 $filtro
                order by p.apellido ,p.nombre";
        $empleados = Empleado::findBySql($sql)->all();
        
        return $empleados;
    }

    public static function get_empleado($id)
    {
        $sql = "SELECT  e.idempleado,concat( p.apellido ,' ', p.nombre) as descripcion
                from empleado e 
                join persona p on p.idpersona = e.idpersona
                where e.activo=1 and e.idempleado = $id";
        $empleado = Empleado::findBySql($sql)->one();
        return $empleado;
    }

    public static function get_empleados_organismo($idorganismo)
    {
        $sql = "SELECT  e.idempleado,concat( p.apellido ,' ', p.nombre) as descripcion
                from empleado e 
                join personas p on p.idpersona = e.idpersona
                join organismo_dispositivo d on e.iddispositivo =d.iddispositivo
                where e.activo=1 and d.idorganismo = $idorganismo
                order by p.apellido ,p.nombre";
        $empleados = Empleado::findBySql($sql)->all();
        
        return $empleados;
    }
}
