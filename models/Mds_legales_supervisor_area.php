<?php

namespace app\models;

/**
 * This is the model class for table "mds_legales_supervisor_area".
 *
 * @property int $idlegalessupervisorarea
 * @property int $idusuario
 * @property int $idarea
 * @property int $idusuario_alta
 * @property int|null $idusuario_borra
 * @property string $created_at
 * @property string|null $deleted_at
 * @property string|null $observaciones
 */
class Mds_legales_supervisor_area extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_legales_supervisor_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idusuario', 'idarea', 'idusuario_alta', 'created_at'], 'required'],
            [['idlegalessupervisorarea', 'idusuario', 'idarea', 'idusuario_alta', 'idusuario_borra', 'idusuario_modifica'], 'integer'],
            [['observaciones'], 'string'],
            [['created_at', 'deleted_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idusuario' => 'Usuario',
            'idarea' => 'Área',
            'deleted_at' => 'Activo',
        ];
    }
    public function getArea()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'idarea']);
    }

    public function getUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario']);
    }

    public function getSupervisoresArea() {
        return $this->find()->select(['idusuario', 'idarea'])->where('deleted_at IS NULL')->asArray()->all();
    }

}
