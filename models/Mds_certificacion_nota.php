<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_certificacion_nota".
 *
 * @property int $idcertificacionnota
 * @property int $idcertificacion
 * @property string $nota
 * @property string $fecha
 * @property int $numero
 * @property int $anio
 * @property string $referencia
 * @property string $destinatario_nombre
 * @property string $destinatario_direccion
 * @property int $idusuario_carga Usuario que carga
 * @property int|null $idusuario_borra Usuario que borra 
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property MdsCertificacion $idcertificacion0
 */
class Mds_certificacion_nota extends \yii\db\ActiveRecord
{
    const DESTINATARIO_NOMBRE = "Sra. Verónica Gómez";
    const DESTINATARIO_DIRECCION = "Directora General de Fondos Sociales MDSyT";
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion_nota';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcertificacion', 'nota', 'fecha', 'numero', 'anio', 'referencia', 'destinatario_nombre', 'destinatario_direccion', 'created_at', 'idusuario_carga'], 'required'],
            [['idcertificacionnota', 'numero', 'anio', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['nota', 'referencia', 'destinatario_nombre', 'destinatario_direccion'], 'string'],
            [['fecha', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['idcertificacion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_certificacion::class, 'targetAttribute' => ['idcertificacion' => 'idcertificacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcertificacionnota' => '#',
            'idcertificacion' => '# Certificación',
            'nota' => 'Nota',
            'fecha' => 'Fecha',
            'numero' => 'Número',
            'anio' => 'Año',
            'referencia' => 'Referencia',
            'destinatario_nombre' => 'Nombre destinatario',
            'destinatario_direccion' => 'Dirección destinatario',
            'idusuario_carga' => 'Usuario Carga',
            'idusuario_borra' => 'Usuario Borra',
            'created_at' => 'Fecha carga',
            'updated_at' => 'Fecha actualiza',
            'deleted_at' => 'Fecha borra',

        ];
    }

    /**
     * Gets query for [[idcertificacion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCertificacion()
    {
        return $this->hasOne(Mds_certificacion::class, ['idcertificacion' => 'idcertificacion']);
    }

    /**
     * Gets query for [[idusuario_carga]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario_carga' => 'idusuario']);
    }

    public static function findByCertificacionId($id)
    {
        return  Mds_certificacion_nota::find()
            ->select('idcertificacionnota, 
                        nota, 
                        fecha, 
                        numero, 
                        anio, 
                        referencia, 
                        destinatario_nombre, 
                        destinatario_direccion,
                        created_at
                        ')
            ->where(['idcertificacion' => $id])
            ->one();
    }
}
