<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "mds_org_informe".
 *
 * @property int $idinforme
 * @property string $asunto
 * @property string $detalle
 * @property int $idusuario
 * @property int $tipo idtipo: 46
 * @property int $iddispositivo
 * @property string $fecha
 * @property string|null $archivo_adjunto
 *
 * @property Mds_org_dispositivo $iddispositivo0
 * @property Sds_com_configuracion $tipo0
 * @property Mds_seg_usuario $idusuario0
 * @property Mds_org_informeUsuario[] $mdsOrgInformeUsuarios
 */
class Mds_org_informe extends \yii\db\ActiveRecord
{
    public $idorganismo;
    public $visto;
    public $fdesde;
    public $fhasta;
    public $temp_archivo_adjunto;
    public $borrar_adjunto;
    public $informes; //Arreglo con ids de informes. A persistir en tabla intermedia de mds_org_informe_usuario
    public $compartidos;
    public $vistos;
    const VISTO_VALUE = 2;
    const CANT_MAX_COMPARTIDOS = 20;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_org_informe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['asunto', 'detalle', 'idusuario', 'tipo', 'iddispositivo', 'fecha'], 'required'],
            [['detalle', 'archivo_adjunto', 'archivo_adjunto_path'], 'string'],
            [['idusuario', 'tipo', 'iddispositivo'], 'integer'],
            [['fecha', 'fdesde', 'fhasta', 'idorganismo', 'informes', 'borrar_adjunto'], 'safe'],
            [['asunto'], 'string', 'max' => 255],
            [['temp_archivo_adjunto'], 'file', 'extensions' => 'jpg, jpeg, gif, svg, png, pdf, odt, ods, doc, docx, xls, xlsx', 'maxSize' => 1000000000],
            [['iddispositivo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_dispositivo::class, 'targetAttribute' => ['iddispositivo' => 'iddispositivo']],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo' => 'idconfiguracion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idinforme' => 'N° Informe',
            'asunto' => 'Asunto',
            'detalle' => 'Detalle',
            'idusuario' => 'Creado por',
            'tipo' => 'Tipo de Informe',
            'iddispositivo' => 'Dispositivo',
            'fecha' => 'Fecha (dd-mm-aaaa)',
            'archivo_adjunto' => 'Archivo Adjunto',
            'archivo_adjunto_path' => 'Archivo Adjunto',
            'idorganismo' => 'Organismo',
            'temp_archivo_adjunto' => 'Seleccionar un Archivo'
        ];
    }

    /**
     * Gets query for [[Iddispositivo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddispositivo0()
    {
        return $this->hasOne(Mds_org_dispositivo::class, ['iddispositivo' => 'iddispositivo']);
    }
    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Mds_org_organismo::class, ['idorganismo' => 'idorganismo']);
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

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario0()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }
    /**
     * Gets query for [[Adjunto0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdjunto0()
    {
        return $this->hasOne(Mds_legales_archivo::class, ['id_objeto' => 'idinforme'])->where(['objeto' => 'mds_org_informe']);
    }

    /**
     * Gets query for [[Informeusuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInformeUsuario()
    {
        $idUsuario = Yii::$app->user->identity->idusuario;
        return $this->hasOne(Mds_org_informe_usuario::class, ['idinforme' => 'idinforme'])->where(['idusuario' => $idUsuario]);
    }

    /**
     * Gets query for [[MdsOrgInformeUsuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsOrgInformeUsuarios()
    {
        return $this->hasMany(Mds_org_informe_usuario::class, ['idinforme' => 'idinforme']);
    }

    /**
     * Gets query for [[IdInformes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdInformes()
    {
        return $this->hasMany(Mds_seg_usuario::class, ['idusuario' => 'idusuario'])->viaTable('mds_org_informe_usuario', ['idinforme' => 'idinforme']);
    }


    public function getNombrePersona($idusuario)
    {
        if ($idusuario != null) {
            $user = Mds_seg_usuario::findOne($idusuario);
            if ($user) {
                $contacto = Mds_org_contacto::findOne($user->idcontacto);
                if ($contacto) {
                    $persona = Sds_com_persona::findOne($contacto->idpersona);
                    if ($persona) {
                        return mb_strtoupper($persona->apellido) . ', ' . mb_strtoupper($persona->nombre);
                    }
                }
            }
        }
        return "";
    }

    public static function getInformesNoVistosByIdUsuario()
    {
        $usuario = Yii::$app->user->identity;
        $idusuario = !empty($usuario) ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        return Mds_org_informe::findBySql("select i.idinforme, i.fecha, i.archivo_adjunto, i.asunto, i.idusuario
                                        from mds_org_informe i 
                                        inner join mds_org_informe_usuario c on i.idinforme=c.idinforme
                                        where c.idusuario=" . $idusuario . " and c.visto=1;");
    }

    public function random_filename($length, $directory, $extension)
    {
        // default to this files directory if empty...
        $dir = !empty($directory) && is_dir($directory) ? $directory : dirname(__FILE__);

        do {
            $key = '';
            $keys = array_merge(range(0, 9), range('a', 'z'));

            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
            }
        } while (file_exists($dir . '/' . $key . (!empty($extension) ? '.' . $extension : '')));

        return $key . (!empty($extension) ? '.' . $extension : '');
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

    public function getAdjuntos()
    {
        $adjuntos =  Mds_legales_archivo::find()
            ->where(['objeto' => 'mds_org_informe', 'tipo' => 'informe', 'activo' => true])
            ->andWhere(['=', 'id_objeto', $this->idinforme])->all();

        return $adjuntos;
    }

    public function getCompartidos($visto, $ordenarPor)
    {
        /*
        Si llega visto: 
            visto = 1: filtra por los no vistos
            visto = 2: filtra por los vistos
        sino trae todos

        Si llega ordenarPor: 
            ordenarPor = VISTO_FECHA: ordena por "visto fecha"
        sino por "apellido" y "nombre"
        */ 
        $where = "idinforme = {$this->idinforme}";
        if ($visto) {
            $where .= " AND visto = $visto";
        }

        switch ($ordenarPor) {
            case 'VISTO_FECHA':
                $orderBy = ['visto_fecha' => SORT_DESC, 'apellido' => SORT_ASC, 'nombre' => SORT_ASC];
                break;
            default:
                $orderBy = ['apellido' => SORT_ASC, 'nombre' => SORT_ASC];
                break;
        }

        return Mds_org_informe_usuario::find()
            ->select('mds_org_informe_usuario.idusuario, visto, visto_fecha')
            ->innerJoin('mds_seg_usuario usuario', 'mds_org_informe_usuario.idusuario = usuario.idusuario')
            ->where($where)
            ->orderBy($orderBy)
            ->all();
    }
}
