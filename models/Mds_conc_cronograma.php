<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_conc_cronograma".
 *
 * @property int $idetapa
 * @property int $idusuario Usuario que carga
 * @property int $idusuario_borra Usuario que borra
 * 
 * @property string $nombre
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property integer $estado
 * @property integer $orden
 * @property string $detalle
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 */
class Mds_conc_cronograma extends \yii\db\ActiveRecord
{

    public $date_inicio;
    public $hour_inicio;
    public $date_fin;
    public $hour_fin;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_conc_cronograma';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'estado', 'orden', 'fecha_inicio', 'idusuario', 'created_at', 'idconcurso'], 'required'],
            [['idetapa', 'idusuario', 'idusuario_borra', 'estado', 'orden', 'idconcurso'], 'integer'],
            [['created_at', 'fecha_fin'], 'safe'],
            [['nombre', 'detalle', 'created_at', 'updated_at', 'deleted_at'], 'string'],
            [['detalle'], 'string', 'max' => 500],
            [['nombre'], 'string', 'max' => 250],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idetapa' => '#',
            'idconcurso' => 'Concurso',
            'nombre' => 'Nombre',
            'estado' => 'Estado',
            'orden' => 'Orden',
            'detalle' => 'Detalle',
            'fecha_inicio' => 'Fecha inicio',
            'fecha_fin' => 'Fecha fin',
            'idusuario' => 'Usuario de carga',
            'idusuario_borra' => 'Usuario borra',
            'created_at' => 'Fecha de carga',
            'updated_at' => 'Fecha de actualización',
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
     * Gets query for [[idconcurso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConcurso()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idconcurso']);
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
        return "$fecha a las $hora" . "hs";
    }

    public static function getConcursosFiltro()
    {
        return Mds_conc_vacante::find()
            ->select("configuracion.idconfiguracion, 
                configuracion.descripcion as concurso")
            ->from("mds_conc_cronograma as cronograma")
            ->innerJoin('sds_com_configuracion as configuracion', 'configuracion.idconfiguracion = cronograma.idconcurso')
            ->where("cronograma.deleted_at IS NULL 
                AND configuracion.activo = 1")
            ->orderBy(['concurso' => SORT_ASC])
            ->asArray()
            ->all();
    }
}
