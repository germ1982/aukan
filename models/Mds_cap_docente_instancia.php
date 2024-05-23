<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_cap_docente_instancia".
 *
 * @property int $iddocenteinstancia
 * @property int $id_docente
 * @property int $id_instancia
 * @property int|null $rol_curso
 * @property int|null $firmante
 *
 * @property SdsComConfiguracion $rolCurso
 * @property MdsCapDocente $docente
 * @property MdsCapInstancia $instancia
 */
class Mds_cap_docente_instancia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_cap_docente_instancia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_docente', 'id_instancia'], 'required'],
            [['estado_cert','id_docente', 'id_instancia', 'rol_curso', 'firmante'], 'integer'],
            [['codigo_qr','path_cert'], 'string'],
            [['rol_curso'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['rol_curso' => 'idconfiguracion']],
            [['id_docente'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_cap_docente::className(), 'targetAttribute' => ['id_docente' => 'idpersona']],
            [['id_instancia'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_cap_instancia::className(), 'targetAttribute' => ['id_instancia' => 'idinstancia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddocenteinstancia' => 'Iddocenteinstancia',
            'id_docente' => 'Id Docente',
            'id_instancia' => 'Id Instancia',
            'rol_curso' => 'Rol Curso',
            'firmante' => 'Firmante',
        ];
    }

    /**
     * Gets query for [[RolCurso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRolCurso()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'rol_curso']);
    }

    /**
     * Gets query for [[Docente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocente()
    {
        return $this->hasOne(Mds_cap_docente::className(), ['idpersona' => 'id_docente']);
    }

    /**
     * Gets query for [[Instancia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInstancia()
    {
        return $this->hasOne(Mds_cap_instancia::className(), ['idinstancia' => 'id_instancia']);
    }
}
