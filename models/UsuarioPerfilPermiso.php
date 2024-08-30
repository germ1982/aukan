<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario_perfil_permiso".
 *
 * @property int $idpermiso
 * @property int $idperfil
 * @property int $idtipopermiso
 * @property string|null $modulo
 * @property string|null $item
 * @property string $descripcion
 */
class UsuarioPerfilPermiso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario_perfil_permiso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idperfil', 'idtipopermiso', 'descripcion'], 'required'],
            [['idperfil', 'idtipopermiso'], 'integer'],
            [['descripcion'], 'string'],
            [['modulo', 'item'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpermiso' => 'ID',
            'idperfil' => 'Perfil',
            'idtipopermiso' => 'Tipo de Permiso',
            'modulo' => 'Modulo',
            'item' => 'Item',
            'descripcion' => 'Descripcion',
        ];
    }
    public function permiso($tipo,$item,$modulo = null){
        $userId = Yii::$app->user->id;
        $perfiles = UsuarioAsignacionPerfil::find()->where(['idusuario' => $userId])->all();

        //el siguiente if solo ocurre si el usuario no tiene perfiles
        if (empty($perfiles)) {
            if($tipo=='menu' && $item == 1) {
                return true;
            }
            return false;
        }

        //el siguinte if devuelve true siempre que el usuario sea administrador
        $es_administrador = UsuarioAsignacionPerfil::find()->where(['idperfil'=>167, 'idusuario' => $userId])->one();        
        if ($es_administrador !== null) {
            // Se encontró un registro, retorna true
            return true;
        }

        if($tipo=='menu') {
            return $this->permiso_menu($item,$perfiles);
        }

        if($tipo=='boton') {
            return $this->permiso_boton($item,$modulo,$perfiles);
        }

    }

    public function permiso_menu($item,$perfiles){

        if($item == 1) {
            return true;
        }

        foreach ($perfiles as $perfil) {
            $permiso = UsuarioPerfilPermiso::find()->where(['idperfil'=>$perfil->idperfil,'modulo'=>'menu','item'=>$item])->one();
            if($permiso){return true;}
        }
        return false;

    }

    public function permiso_boton($item,$modulo,$perfiles){

        foreach ($perfiles as $perfil) {
            $permiso = UsuarioPerfilPermiso::find()->where(['idperfil'=>$perfil->idperfil,'modulo'=> $modulo,'item'=>$item])->one();
            if($permiso){return true;}
        }
        
        return false;

    }

}
