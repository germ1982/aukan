<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_legales_respuesta".
 *
 * @property int $idlegalesrespuesta
 * @property int $idlegalesoficio
 * @property int $idrespuestacorreccion
 * @property int $idusuario
 * @property string|null $texto_repuesta
 * @property string|null $archivo_respuesta
 *  @property string|null $comprobante
 * @property string $fecha_carga
 * @property int $entregado
 * @property string|null nro_nota
 */
class Mds_legales_respuesta extends \yii\db\ActiveRecord
{
    const PATH_ADJUNTOS_RESPUESTAS = "uploads/legales/respuestas/";
    const PATH_ADJUNTOS_RESPUESTAS_SUPERVISOR = "uploads/legales/respuestas_supervisor/";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_legales_respuesta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idlegalesoficio', 'idusuario', 'fecha_carga'], 'required'],
            [['idlegalesoficio', 'idusuario', 'idrespuestacorreccion'], 'integer'],
            [['texto_repuesta', 'observacion_final'], 'string'],
            [['entregado'], 'integer'],
            [['fecha_carga'], 'safe'],
            [['archivo_respuesta'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlegalesrespuesta' => 'Idlegalesrespuesta',
            'idlegalesoficio' => 'Idlegalesoficio',
            'idrepuestacorreccion' => 'Respuesta Correccion',
            'idusuario' => 'Idusuario',
            'texto_repuesta' => 'Respuesta',
            'archivo_respuesta' => 'Archivo Respuesta',
            'fecha_carga' => 'Fecha Carga',
            'comprobante' => 'Archivo comproante',
            'entregado' => 'Entregado'
        ];
    }
    public function getOficio()
    {
        return $this->hasOne(Mds_legales_oficio::class, ['idlegalesoficio' => 'idlegalesoficio']);
    }

    public function getUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    public function getRespuestaCorreccion()
    {
        return $this->hasOne(Mds_legales_respuesta::class, ['idlegalesrespuesta' => 'idrespuestacorreccion']);
    }

    public function getEstados()
    {
        return $this->hasMany(Mds_legales_respuesta_estado::class, ['idlegalesrespuesta' => 'idlegalesrespuesta'])->orderBy([
            'idlegalesrespuestaestado' => SORT_DESC
        ]);
    }

    public function getVistos()
    {
        return $this->hasMany(Mds_legales_respuesta_visto::class, ['idlegalesrespuesta' => 'idlegalesrespuesta'])->where(['activo' => 1])->orderBy([
            'idlegalesrespuestavisto' => SORT_DESC
        ]);
    }
    public function getVistos0()
    {
        return $this->hasMany(Mds_legales_respuesta_visto::class, ['idlegalesrespuesta' => 'idlegalesrespuesta']);
    }

    public function getUltimoEstado()
    {
        return $this->hasMany(Mds_legales_respuesta_estado::class, ['idlegalesrespuesta' => 'idlegalesrespuesta'])->orderBy([
            'idlegalesrespuestaestado' => SORT_DESC
        ])->one();
    }

    public function getUltimoEstadoAprobado()
    {
        return $this->hasMany(Mds_legales_respuesta_estado::class, ['idlegalesrespuesta' => 'idlegalesrespuesta'])->where(['estado' => Mds_legales_respuesta_estado::APROBADA])->orderBy([
            'idlegalesrespuestaestado' => SORT_DESC
        ])->one();
    }

    public function getUltimaRespuestaEstadoByEstadoId($estado)
    {
        return $this->hasOne(Mds_legales_respuesta_estado::class, ['idlegalesrespuesta' => 'idlegalesrespuesta'])->where(['estado' => $estado])->orderBy([
            'idlegalesrespuestaestado' => SORT_DESC
        ])->one();
    }

    public function getDerivacion()
    {
        return $this->hasOne(Mds_legales_derivacion::class, ['idlegalesoficio' => 'idlegalesoficio'])->where(['supervisor' => 0, 'fecha_usu_no_corresponde' => null, 'activo' => 1, 'idusuario' => $this->idusuario]);
    }
    
    /*public function getProfesionalesIntervinientes(){
        return $this->hasMany(Mds_legales_profesionales_intervinientes::class, ['idlegalesrespuesta' => 'idrespuesta']);
    }*/
    public function getProfesionalesIntervinientes()
    {
        return Mds_legales_profesionales_intervinientes::find()->where(['idrespuesta' => $this->idlegalesrespuesta])->all();
    }

    /*Si tiene estado distinto a pendiente de autorizacion, devuelve false*/
    public function yaTieneEstado()
    {
        $yaTieneEstado = false;
        $estados = Mds_legales_respuesta_estado::find()
            ->where(['idlegalesrespuesta' => $this->idlegalesrespuesta])
            ->andWhere(['!=', 'estado', Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION])->count();
        if ($estados >= 1) {
            $yaTieneEstado = true;
        }
        return $yaTieneEstado;
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
    public function getAdjuntos($llamadoDesde = null)
    {
        $adjuntos =  Mds_legales_archivo::find()
            ->where(['objeto' => 'mds_legales_respuesta', 'tipo' => 'respuesta', 'activo' => true])
            ->andWhere(['=', 'id_objeto', $this->idlegalesrespuesta])->all();
        foreach ($adjuntos as $adjunto) {
            if ($llamadoDesde != 'replicarArchivos') {
                $adjunto->path = self::PATH_ADJUNTOS_RESPUESTAS . $adjunto->path;
            }
        }
        return $adjuntos;
    }

    public function getAdjuntosRespuestaSupervisor()
    {
        $adjuntos =  Mds_legales_archivo::find()
            ->where(['objeto' => 'mds_legales_respuesta', 'tipo' => 'respuesta_supervisor', 'activo' => true])
            ->andWhere(['=', 'id_objeto', $this->idlegalesrespuesta])->all();
        foreach ($adjuntos as $adjunto) {
            $adjunto->path = self::PATH_ADJUNTOS_RESPUESTAS_SUPERVISOR . $adjunto->path;
        }
        return $adjuntos;
    }
}
