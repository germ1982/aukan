<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_com_configuracion".
 *
 * @property int $idconfiguracion
 * @property int $idconfiguraciontipo
 * @property string $descripcion
 * @property int $activo
 *
 * @property SdsComConfiguracionTipo $idconfiguraciontipo0
 * @property SdsComPersona[] $sdsComPersonas
 * @property SdsComPersona[] $sdsComPersonas0
 * @property SdsComPersona[] $sdsComPersonas1
 * @property SdsRisPersona[] $sdsRisPersonas
 * @property SdsRisPersona[] $sdsRisPersonas0
 * @property SdsRisPersona[] $sdsRisPersonas1
 * @property SdsRisPersona[] $sdsRisPersonas2
 * @property SdsRisPersona[] $sdsRisPersonas3
 * @property SdsRisPersona[] $sdsRisPersonas4
 * @property SdsRisPersona[] $sdsRisPersonas5
 * @property SdsRisPersona[] $sdsRisPersonas6
 * @property SdsRisPersona[] $sdsRisPersonas7
 * @property SdsRisPersona[] $sdsRisPersonas8
 * @property SdsRisPersona[] $sdsRisPersonas9
 * @property SdsRisPersonaEnfermedad[] $sdsRisPersonaEnfermedads
 * @property SdsRisRisneu[] $sdsRisRisneus
 * @property SdsRisRisneu[] $sdsRisRisneus0
 * @property SdsRisRisneu[] $sdsRisRisneus1
 * @property SdsRisRisneu[] $sdsRisRisneus2
 * @property SdsRisRisneu[] $sdsRisRisneus3
 * @property SdsRisRisneu[] $sdsRisRisneus4
 * @property SdsRisRisneu[] $sdsRisRisneus5
 * @property SdsRisRisneu[] $sdsRisRisneus6
 * @property SdsRisRisneu[] $sdsRisRisneus7
 * @property SdsRisRisneu[] $sdsRisRisneus8
 * @property SdsRisRisneu[] $sdsRisRisneus9
 * @property SdsRisRisneu[] $sdsRisRisneus10
 * @property SdsRisRisneu[] $sdsRisRisneus11
 * @property SdsRisRisneu[] $sdsRisRisneus12
 * @property SdsRisRisneu[] $sdsRisRisneus13
 * @property SdsRisRisneu[] $sdsRisRisneus14
 * @property SdsRisRisneu[] $sdsRisRisneus15
 * @property SdsRisRisneu[] $sdsRisRisneus16
 * @property SdsRisRisneu[] $sdsRisRisneus17
 */
class Sds_com_configuracion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_com_configuracion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idconfiguraciontipo', 'descripcion', 'activo'], 'required'],
            [['idconfiguraciontipo', 'activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 255],
            [['idconfiguraciontipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion_tipo::className(), 'targetAttribute' => ['idconfiguraciontipo' => 'idconfiguraciontipo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idconfiguracion' => 'Idconfiguracion',
            'idconfiguraciontipo' => 'Tipo',
            'descripcion' => 'Descripción',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[Idconfiguraciontipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConfiguraciontipo()
    {
        return $this->hasOne(Sds_com_configuracion_tipo::className(), ['idconfiguraciontipo' => 'idconfiguraciontipo']);
    }

    public static function getConfiguraciones($tipo, $obligatorio = true)
    {
        $tipo_sin_asignar = $obligatorio ? $tipo : 1;
        return Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => $tipo])
            ->orWhere(['idconfiguraciontipo' => $tipo_sin_asignar])
            ->orderBy(['descripcion' => SORT_ASC])->all();
    }
    //Hacemos esta funcion para retornar los configuraciones tipo sin orden
    public static function getConfiguracionesSinOrden($tipo, $obligatorio = true)
    {
        $tipo_sin_asignar = $obligatorio ? $tipo : 1;
        return Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => $tipo])
            ->orWhere(['idconfiguraciontipo' => $tipo_sin_asignar])
            ->all();
    }

    public static function getConfiguracionesActivas($tipo, $obligatorio = true)
    {
        $tipo_sin_asignar = $obligatorio ? $tipo : 1;
        return Sds_com_configuracion::find()
            ->where("activo = 1 && (idconfiguraciontipo = $tipo || idconfiguraciontipo = $tipo_sin_asignar)")
            ->orderBy(['descripcion' => SORT_ASC])->all();
    }

    public static function getDescripcion($id)
    {
        $configuracion = Sds_com_configuracion::findOne($id);
        if ($configuracion != null) {
            return $configuracion->descripcion;
        }
        return null;
    }
}
