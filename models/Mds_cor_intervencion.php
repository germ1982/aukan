<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mds_cor_intervencion".
 *
 * @property int $idintervencion
 * @property int $idpersona
 * @property string $fecha_hora
 * @property int $idusuario
 * @property string|null $derivaciones_previas
 * @property int|null $referente_dni
 * @property string|null $referente_nombre
 * @property string|null $referente_vinculo
 * @property string|null $detalle
 * @property string|null $intervenciones
 * @property string|null $derivaciones
 * @property int $profesional
 * @property string $fecha_informe
 * @property string|null $referente_telefono
 * @property int $tipo
 * @property string|null $nombre_autopercibido
 * @property string|null $deleted_at
 *
 * @property SdsComPersona $idpersona0
 * @property MdsOrgContacto $profesional0
 * @property SdsComConfiguracion $tipo0
 * @property MdsSegUsuario $idusuario0
 */
class Mds_cor_intervencion extends \yii\db\ActiveRecord
{
    public $dni;
    public $dni_beneficiario;
    public $nombre;
    public $apellido;
    public $fecha_nacimiento;
    public $genero;
    public $genero_autopercibido;
    public $edad;
    public $provincia;
    public $fdesde;
    public $fhasta;
    public $fdesde1;
    public $fhasta1;
    public $temp_archivo_salud;
    public $compartido_con; //Arreglo con ids de intervenciones. A persistir en tabla intermedia de mds_org_intervencion_usuario
    public $idpersona_intervencion;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_cor_intervencion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        //ANOTEZE: Acá Saco el idintervención, debería haberse agregado como autoincrementable en un principio.
        //Normalmente si yii lo toma dentro de las rules es porque alguien se comió setearlo, entonces está esperándolo al guardar en el create...
        return [
            [[/*'idintervencion',*/'idpersona', 'fecha_hora', 'idusuario', 'profesional', 'fecha_informe', 'tipo'], 'required'],
            [[/*'idintervencion',*/'idpersona', 'idusuario', 'referente_dni', 'profesional', 'tipo', 'ley', 'idllamada', 'idusuario_borra', 'idlocalidad', 'idtiemporesidencianqn', 'iddenuncia'], 'integer'],
            [['fecha_hora', 'fecha_informe', 'dni', 'nombre', 'apellido', 'dni_beneficiario', 'fdesde', 'fhasta', 'fdesde1', 'fhasta1', 'compartido_con', 'deleted_at', 'idpersona_intervencion'],  'safe'],
            [['derivaciones_previas', 'detalle', 'intervenciones', 'derivaciones', 'plan_accion', 'nombre_autopercibido'], 'string'],
            [['referente_nombre'], 'string', 'max' => 100],
            [['referente_vinculo'], 'string', 'max' => 50],
            [['referente_telefono'], 'string', 'max' => 45],
            [['nombre_autopercibido'], 'string', 'max' => 255],
            [['temp_archivo_salud'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 1000000],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['profesional'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['profesional' => 'idcontacto']],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo' => 'idconfiguracion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['ley'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['ley' => 'idconfiguracion']],
            [['idllamada'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_llamada::class, 'targetAttribute' => ['idllamada' => 'idllamada']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idintervencion' => 'Nro. Intervención',
            'idpersona' => 'Persona',
            'fecha_hora' => 'Fecha Hora',
            'idusuario' => 'Idusuario',
            'derivaciones_previas' => 'Derivaciones Previas',
            'referente_dni' => 'Dni Responsable',
            'referente_nombre' => 'Nombre Responsable',
            'referente_vinculo' => 'Vínculo Responsable',
            'detalle' => 'Detalle',
            'intervenciones' => 'Intervencion Realizada',
            'derivaciones' => 'Derivaciones Futuras',
            'profesional' => 'Profesional Interviniente',
            'fecha_informe' => 'Fecha de intervención',
            'referente_telefono' => 'Teléfono del Responsable',
            'tipo' => 'Tipo',
            'ley' => 'Ley',
            'dni' => 'DNI',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'temp_archivo_salud' => 'Seleccionar un Archivo (imagen o PDF)',
            'idllamada' => 'Nro. de Atención en Guardias Integradas',
            'deleted_at' => 'Activo',
            'genero' => 'Género',
            'genero_autopercibido' => 'Género autopercibido',
            'nombre_autopercibido' => 'Nombre autopercibido',
            'idlocalidad' => 'Origen Localidad',
            'provincia' => 'Origen Provincia',
            'idtiemporesidencianqn' => 'Tiempo de residencia en Neuquén',
            'plan_accion' => 'Plan de acción',
            'iddenuncia' => 'Denuncia',
        ];
    }

    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersona()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idpersona']);
    }
    /*Para obtener la relacion persona*/
    public function getIdpersona0()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[Profesional0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfesional0()
    {
        return $this->hasOne(Mds_org_contacto::class, ['idcontacto' => 'profesional']);
    }

    /**
     * Gets query for [[Tipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipo0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo']);
    }

    public function getLey0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'ley']);
    }

    public function getIdllamada()
    {
        return $this->hasOne(Sds_800_llamada::class, ['idllamada' => 'idllamada']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    public function getLocalidad()
    {
        return $this->hasOne(Sds_com_localidad::class, ['idlocalidad' => 'idlocalidad']);
    }

    public function getTiemporesidencianqn()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idtiemporesidencianqn']);
    }

    public function getDenuncia()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'iddenuncia']);
    }

    public function getArticulaciones()
    {
        return Mds_cor_intervencion_articulacion::find()->where(['idintervencion' => $this->idintervencion, 'deleted_at' => null])->all();
    }
    public function getConsumos()
    {
        return Mds_cor_intervencion_consumo::find()->where(['idintervencion' => $this->idintervencion, 'deleted_at' => null])->all();
    }
    public function getProblemas()
    {
        return Mds_cor_intervencion_problema::find()->where(['idintervencion' => $this->idintervencion, 'deleted_at' => null])->all();
    }

    public static function getPersonaFiltro($hasRolAdminGeneral)
    {
        $idUsuario = Yii::$app->user->identity->idusuario;
        $where = '';
        if (!$hasRolAdminGeneral) {
            $where = "WHERE (mds_cor_intervencion.idusuario = $idUsuario OR mds_cor_intervencion_usuario.idusuario = $idUsuario) AND mds_cor_intervencion.deleted_at IS NULL";
        }

        return ArrayHelper::map(
            Sds_com_persona::find()
                ->where("idpersona in 
            (
                select idpersona 
                from mds_cor_intervencion
                left join mds_cor_intervencion_usuario on mds_cor_intervencion.idintervencion = mds_cor_intervencion_usuario.idintervencion
                $where
            )")
                ->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])
                ->all(),
            'idpersona',
            function ($model) {
                return mb_strtoupper($model->nombre) . " " . mb_strtoupper($model->apellido);
            }
        );
    }

    public static function getProfesionalFiltro($hasRolAdminGeneral)
    {
        $idUsuario = Yii::$app->user->identity->idusuario;
        $where = '';
        if (!$hasRolAdminGeneral) {
            $where = "WHERE (mds_cor_intervencion.idusuario = $idUsuario OR mds_cor_intervencion_usuario.idusuario = $idUsuario) AND mds_cor_intervencion.deleted_at IS NULL";
        }

        return ArrayHelper::map(
            Mds_org_contacto::findBySql("select c.idcontacto, p.nombre, p.apellido 
                from mds_org_contacto c 
                join sds_com_persona p on p.idpersona = c.idpersona 
                join mds_cor_intervencion on c.idcontacto = mds_cor_intervencion.profesional
                left join mds_cor_intervencion_usuario on mds_cor_intervencion.idintervencion = mds_cor_intervencion_usuario.idintervencion
                $where 
                order by nombre ASC, apellido ASC;")->all(),
            'idcontacto',
            function ($model) {
                return mb_strtoupper($model->nombre) . " " . mb_strtoupper($model->apellido);
            }
        );
    }

    public static function getTipoFiltro($hasRolAdminGeneral)
    {
        $idUsuario = Yii::$app->user->identity->idusuario;
        $where = '';
        if (!$hasRolAdminGeneral) {
            $where = "WHERE (mds_cor_intervencion.idusuario = $idUsuario OR mds_cor_intervencion_usuario.idusuario = $idUsuario) AND mds_cor_intervencion.deleted_at IS NULL";
        }

        return ArrayHelper::map(Sds_com_configuracion::find()
            ->where("idconfiguracion in 
                (
                    select tipo 
                    from mds_cor_intervencion
                    left join mds_cor_intervencion_usuario on mds_cor_intervencion.idintervencion = mds_cor_intervencion_usuario.idintervencion
                    $where
                )")
            ->orderBy(['descripcion' => SORT_ASC])
            ->all(), 'idconfiguracion', 'descripcion');
    }
}
