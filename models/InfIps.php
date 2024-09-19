<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inf_ips".
 *
 * @property int $idip
 * @property string|null $ip
 * @property string|null $idempleado
 * @property string|null $iddispositivo
 */
class InfIps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $persona;
    public static function tableName()
    {
        return 'inf_ips';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip', 'idempleado' ], 'string', 'max' => 45],

        ];
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
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idip' => 'ID',
            'ip' => 'Direccion Ip',
            'idempleado' => 'Empleado',
            'iddispositivo'=> 'Dispositivo'
        ];
    }
}
