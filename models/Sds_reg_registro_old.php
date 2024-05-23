<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_reg_registro".
 *
 * @property int $idregistro
 * @property string $fecha_hora
 * @property int $idorganismo
 * @property int $usuario_solicitante
 * @property string $problema
 * @property string $fecha_trabajo
 * @property int|null $usuario_derivacion
 * @property int|null $registro_abierto
 * @property int|null $incidencia_relacionada
 * @property int|null $autorizado
 * @property int $tipo
 * @property string|null $fecha_ingreso
 * @property int|null $usuario_ingreso
 * @property string|null $fecha_solucion
 *
 * @property MsdOrgOrganismo $idorganismo0
 * @property MdsSegUsuario $usuarioSolicitante
 */
class Sds_reg_registro_old extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_reg_registro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora', 'idorganismo', 'usuario_solicitante', 'problema', 'fecha_trabajo'], 'required'],
            [['fecha_hora', 'fecha_trabajo', 'fecha_ingreso', 'fecha_solucion'], 'safe'],
            [['idorganismo', 'usuario_solicitante', 'usuario_derivacion', 'registro_abierto', 'incidencia_relacionada', 'autorizado', 'tipo', 'usuario_ingreso'], 'integer'],
            [['problema'], 'string'],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['usuario_solicitante'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['usuario_solicitante' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idregistro' => 'Idregistro',
            'fecha_hora' => 'Fecha Hora',
            'idorganismo' => 'Idorganismo',
            'usuario_solicitante' => 'Usuario Solicitante',
            'problema' => 'Problema',
            'fecha_trabajo' => 'Fecha Trabajo',
            'usuario_derivacion' => 'Usuario Derivacion',
            'registro_abierto' => 'Registro Abierto',
            'incidencia_relacionada' => 'Incidencia Relacionada',
            'autorizado' => 'Autorizado',
            'tipo' => 'Tipo',
            'fecha_ingreso' => 'Fecha Ingreso',
            'usuario_ingreso' => 'Usuario Ingreso',
            'fecha_solucion' => 'Fecha Solucion',
        ];
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Mds_org_organismo::className(), ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[UsuarioSolicitante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioSolicitante()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'usuario_solicitante']);
    }
}
