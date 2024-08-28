<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informatica_web_empleados".
 *
 * @property int $idwebempleado
 * @property int|null $idempleado
 * @property string|null $descripcion
 * @property int|null $activo
 * @property int|null $orden
 */
class InformaticaWebEmpleados extends \yii\db\ActiveRecord
{
    public $funcion_empleado;
    public static function tableName()
    {
        return 'informatica_web_empleados';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idempleado', 'activo', 'orden'], 'integer'],
            [['descripcion'], 'string'],
            [['funcion_empleado'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idwebempleado' => 'Id',
            'idempleado' => 'Empleado',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
            'orden' => 'Orden',
            'funcion_empleado' => 'Funcion',
        ];
    }

        // Relación con Empleado
        public function getEmpleado()
        {
            return $this->hasOne(Empleado::className(), ['idempleado' => 'idempleado']);
        }


        public function permiso_edicion_personal($idempleado){

            $empleado = Empleado::findOne($idempleado);
            $usuario = Usuarios::find()->where(['idpersona' => $empleado->idpersona])->one();

            if (empty($usuario)){return false;}
            $userId = Yii::$app->user->id;

            $perfiles = UsuarioAsignacionPerfil::find()->where(['idusuario' => $userId])->all();

            //el siguiente if solo ocurre si el usuario no tiene perfiles
            if (empty($perfiles)) {
                return false;
            }
    
            //el siguinte if devuelve true siempre que el usuario sea administrador
            $es_administrador = UsuarioAsignacionPerfil::find()->where(['idperfil'=>167, 'idusuario' => $userId])->one();        
            if ($es_administrador !== null) {
                // Se encontró un registro, retorna true
                return true;
            }

            if($userId == $usuario->id){
                foreach ($perfiles as $perfil) {
                    $permiso = UsuarioPerfilPermiso::find()->where(['idperfil'=>$perfil->idperfil,'modulo'=>'InformaticaWebEmpleados','item'=>'edicion'])->one();
                    if($permiso){return true;}
                }
            }
            else{return false;}

    
        }
    

}
