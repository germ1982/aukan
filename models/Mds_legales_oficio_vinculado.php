<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_legales_oficio_vinculado".
 *
 * @property int $idlegalesoficiovinculado
 * @property int $idlegalesoficio
 * @property int|null $idpersona
 * @property int|null $idparentesco
 * @property int|null $idtipodocumento
 * @property string|null $documento
 * @property string|null $apellido
 * @property string|null $nombre
 * @property string|null $domicilio_calle
 * @property string|null $domicilio_numero
 * @property string|null $telefono
 * @property string|null $mail
 * @property string|null $observaciones
 * @property int|null $idusuario_alta
 * @property int|null $idusuario_borra
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property string|null $auditoria
 */
class mds_legales_oficio_vinculado extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_legales_oficio_vinculado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idlegalesoficio'], 'required'],
            [[
                'idlegalesoficiovinculado', 'idlegalesoficio', 'idpersona', 'idparentesco',
                'idtipodocumento', 'idusuario_alta', 'idusuario_borra'
            ], 'integer'],
            [['documento', 'apellido', 'nombre', 'domicilio_calle', 'domicilio_numero', 'telefono', 'auditoria', 'observaciones', 'mail'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlegalesoficiovinculado' => 'idlegalesoficiovinculado',
            'idlegalesoficio' => 'idlegalesoficio',
            'idpersona' => 'Persona',
            'idparentesco' => 'Parentesco',
            'idtipodocumento' => 'Tipo de documento',
            'documento' => 'Nro. de documento',
            'apellido' => 'Apellido',
            'nombre' => 'Nombre',
            'domicilio_calle' => 'Domicilio Calle',
            'domicilio_numero' => 'Domicilio Número',
            'telefono' => 'Teléfono',
            'mail' => 'Mail',
            'auditoria' => 'Auditoria',
            'observaciones' => 'Observaciones',
            'deleted_at' => 'Activo',
        ];
    }
    public function getoficio()
    {
        return $this->hasOne(Mds_legales_oficio::class, ['idlegalesoficio' => 'idlegalesoficio']);
    }
    public function getPersona()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idpersona']);
    }
    public function getTipoDocumento()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idtipodocumento']);
    }
    public function getParentesco()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idparentesco']);
    }

    public function checkDniRepetido($dni, $idlegalesoficio) {
        $esDniRepetido = false;

        $oficioVinculado = Mds_legales_oficio_vinculado::find()
        ->leftJoin('sds_com_persona', 'sds_com_persona.idpersona = mds_legales_oficio_vinculado.idpersona')
        ->where("mds_legales_oficio_vinculado.idlegalesoficio = $idlegalesoficio AND mds_legales_oficio_vinculado.deleted_at IS NULL AND (mds_legales_oficio_vinculado.documento = '$dni' OR sds_com_persona.documento = '$dni')")
        ->one();

        if ($oficioVinculado) {
            $esDniRepetido = true;
        }

        return $esDniRepetido;
    }

    public static function getPersonasVinculadasByRequerimiento($id) {
        return Mds_legales_oficio_vinculado::find()
        ->select(["mds_legales_oficio_vinculado.idpersona,
                idparentesco,
                IFNULL(mds_legales_oficio_vinculado.idtipodocumento, sds_com_persona.documento_tipo) as idtipodocumento,
                IFNULL(mds_legales_oficio_vinculado.documento, sds_com_persona.documento) as documento,
                IFNULL(mds_legales_oficio_vinculado.apellido, sds_com_persona.apellido) as apellido,
                IFNULL(mds_legales_oficio_vinculado.nombre, sds_com_persona.nombre) as nombre,
                IFNULL(mds_legales_oficio_vinculado.domicilio_calle, sds_com_persona.domicilio_calle) as domicilio_calle,
                IFNULL(mds_legales_oficio_vinculado.domicilio_numero, sds_com_persona.domicilio_numero) as domicilio_numero,
                telefono,
                mail,
                observaciones,
                sds_com_configuracion.descripcion as parentescoDescripcion,
                sds_com_persona.genero,
                sds_com_persona.nacionalidad,
                sds_com_persona.fecha_nacimiento
                "])
        ->leftJoin('sds_com_configuracion','sds_com_configuracion.idconfiguracion = mds_legales_oficio_vinculado.idparentesco')
        ->leftJoin('sds_com_persona','sds_com_persona.idpersona = mds_legales_oficio_vinculado.idpersona')
        ->where("deleted_at IS NULL AND idlegalesoficio = $id")
        ->asArray()
        ->all();
    }
}
