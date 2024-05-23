<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_conc_vacante".
 *
 * @property int $idvacante
 * @property int $categoria
 * @property int $cantidad
 * @property int $idconcurso
 * @property int $requiere_titulo
 * @property int $idusuario
 * @property int $idusuario_borra
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * 
 */
class Mds_conc_vacante extends \yii\db\ActiveRecord
{
    public $capa;
    public $cantPostulaciones;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_conc_vacante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['requiere_titulo', 'idusuario', 'created_at', 'idconcurso', 'cantidad', 'categoria'], 'required'],
            [['idvacante', 'categoria', 'cantidad', 'idusuario', 'idusuario_borra', 'requiere_titulo', 'idconcurso'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'string'],
            ['cantidad', 'integer', 'min' => 1, 'message' => 'Cantidad debe ser un número mayor a 0.'],
            [['categoria'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['categoria' => 'idconfiguracion']],
            [['categoria'], 'customValidationRuleCategoria'],
        ];
    }

    public function customValidationRuleCategoria($attribute)
    {
        $idVacante = $this->idvacante ? $this->idvacante : null;
        if (!$idVacante) {
            $idConcurso = $this->idconcurso;
            if ($idConcurso) {
                $categoriaDisponible = Mds_conc_vacante::verificarCategoriaDisponible($this->$attribute, $idConcurso, $idVacante);
                if (!$categoriaDisponible) {
                    $this->addError($attribute, 'La categoría seleccionada ya se encuentra registrada como vacante.');
                }
            } else {
                $this->addError($attribute, 'Debe seleccionar un concurso primero.');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idvacante' => 'Vacante',
            'categoria' => 'Categoría',
            'cantidad' => 'Cantidad',
            'idconcurso' => 'Concurso',
            'requiere_titulo' => '¿Requiere título?',
            'idusuario' => 'Usuario Alta',
            'idusuario_borra' => 'Usuario Borra',
            'created_at' => 'Fecha Alta',
            'updated_at' => 'Fecha Actualización',
            'deleted_at' => 'Activo',
        ];
    }

    public function getCategoria0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'categoria']);
    }

    public function getConcurso()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idconcurso']);
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

    public static function getCategoriasFiltro()
    {
        return Mds_conc_vacante::find()
            ->select("configuracion.idconfiguracion, 
                configuracion.descripcion as categoria")
            ->from("mds_conc_vacante as vacante")
            ->innerJoin('sds_com_configuracion as configuracion', 'configuracion.idconfiguracion = vacante.categoria')
            ->where("vacante.deleted_at IS NULL 
                AND configuracion.activo = 1")
            ->orderBy(['categoria' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public static function getConcursosFiltro()
    {
        return Mds_conc_vacante::find()
            ->select("configuracion.idconfiguracion, 
                configuracion.descripcion as concurso")
            ->from("mds_conc_vacante as vacante")
            ->innerJoin('sds_com_configuracion as configuracion', 'configuracion.idconfiguracion = vacante.idconcurso')
            ->where("vacante.deleted_at IS NULL 
                AND configuracion.activo = 1")
            ->orderBy(['concurso' => SORT_ASC])
            ->asArray()
            ->all();
    }

    public function getPostulaciones()
    {
        return Mds_conc_postulacion::find()->where(['idvacante' => $this->idvacante, 'deleted_at' => null])->all();
    }

    public static function verificarCategoriaDisponible($idCategoria, $idConcurso, $idVacante)
    {
        /*Si llega un idVacante, debo excluirlo de la busqueda 
        (esto se usa para el update/delete/reactivate, para poder 
        guardar y que la validacion no se fije sobre el propio elemento) */
        $where = "categoria = $idCategoria AND idconcurso = $idConcurso AND deleted_at IS NULL";
        if ($idVacante) {
            $where .= " AND idvacante != $idVacante";
        }

        $consultaCategoriaDisponible = Mds_conc_vacante::find()
            ->select("idvacante")
            ->where($where)
            ->asArray()
            ->one();

        return empty($consultaCategoriaDisponible);
    }

    public static function getCategoriasDisponiblesByConcurso($idConcurso, $idVacante)
    {
        /*Si llega un idVacante, debo excluirlo de la busqueda 
        (esto se usa para el update/delete/reactivate, para poder 
        guardar y que la validacion no se fije sobre el propio elemento) */
        $where = "idconcurso = $idConcurso AND deleted_at is NULL";
        if ($idVacante) {
            $where .= " AND idvacante != $idVacante";
        }

        $subqueryCategoriasDelConcurso = Mds_conc_vacante::find()
            ->select('categoria')
            ->where($where);

        //Categorias que no poseen una vacante en el concurso
        return Sds_com_configuracion::find()
            ->select(['idconfiguracion', 'descripcion'])
            ->where(["idconfiguraciontipo" => Sds_com_configuracion_tipo::CONCURSO_SOLICITUD_CATEGORIA, "activo" => 1])
            ->andWhere(['not in', 'idconfiguracion', $subqueryCategoriasDelConcurso])
            ->orderBy(['descripcion' => SORT_ASC])
            ->all();
    }
}
