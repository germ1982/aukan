<?php

namespace app\components;

use app\models\Mds_seg_permiso;
use Yii;

class AccessRule extends \yii\filters\AccessRule
{

    /**
     * @inheritdoc
     */
    protected function matchRole($user)
    {
        $usuario = $user->identity;
        $id = $usuario != null ? $usuario->idusuario : null;
        if (!isset($id) || $id == null) {
            $model = new \app\models\LoginForm();
            Yii::$app->user->returnUrl=Yii::$app->request->url;
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model
            ]);
        }
        if (isset($id) && $id != null) {
            $idPermisos = Mds_seg_permiso::getPermisosByIdUsuario($id)->all();
        }
        if (empty($this->roles)) {
            return true;
        }
        foreach ($this->roles as $role) {
            if ($role == '?') {
                if ($user->getIsGuest()) {
                    return true;
                }
            } else if($role=='@'){
                if (!$user->getIsGuest()) {
                    return true;
                }
            }else{
                if (isset($idPermisos) && $idPermisos != null) {
                    foreach ($idPermisos as $r) {
                        if (!$user->getIsGuest() && $role == $r->iditem) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}
