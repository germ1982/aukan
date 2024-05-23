<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_legales_derivacion".
 *
 * @property int $idlegalesderivacionarea
 * @property int $idoficio
 * @property int $iddispositivo
 */
class Mds_legales_profesionales_intervinientes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_legales_profesionales_intervinientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrespuesta', 'idusuario'], 'required'],
            [['idrespuesta', 'idusuario'], 'integer']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idrespuesta' => 'respuesta',
            'idusuario' => 'usuario'
        ];
    }

    public function getRespuesta()
    {
        return $this->hasOne(Mds_legales_Respuesta::class, ['idrespuesta' => 'idlegalesrespuesta']);
    }
    public function getUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
        //return Mds_seg_usuario::find()->where(['idusuario'=>$this->idusuario])->one();
    }
}
