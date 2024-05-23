<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_conc_impuganacion".
 *
 * @property int $idpostulacion
 * @property int $idusuario Usuario que carga
 * @property int $idusuario_borra Usuario que borra
 * 
 * @property string $observacion
 * @property string $archivo
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 */
class Mds_conc_impugnacion extends \yii\db\ActiveRecord
{
    const PATH = "uploads/concurso/";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_conc_impugnacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['observacion', 'archivo', 'idusuario', 'created_at'], 'required'],
            [['idpostulacion', 'idusuario', 'idusuario_borra'], 'integer'],
            [['created_at', 'idusuario'], 'safe'],
            [['observacion', 'archivo', 'created_at', 'deleted_at', 'updated_at'], 'string'],
            [['archivo'], 'string', 'max' => 255],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
            [['archivo'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 52428800],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpostulacion' => '# Impuganción',
            'observacion' => 'Observación',
            'archivo' => 'Documentación Adjunta',
            'idusuario' => 'Usuario de carga',
            'idusuario_borra' => 'Usuario borra',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de modificación',
            'deleted_at' => 'Activo',
        ];
    }

    /**
     * Gets query for [[idusuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[idusuario_borra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioBorra()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_borra']);
    }

    /**
     * Gets query for [[created_at]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFechaCarga()
    {
        $date = date_create($this->created_at);
        $fecha = date_format($date, 'd/m/Y');
        $hora = date_format($date, 'H:i');
        return $fecha . ' a las ' . $hora . ' hs';
    }

    public static function getExtension($file)
    {
        $array = explode(".", $file);
        $extension = end($array);
        $extImagenes = array('jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp');
        if (in_array($extension, $extImagenes)) {
            return 'image';
        } else {
            return $extension;
        }
    }
}
