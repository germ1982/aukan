<?php

namespace app\models;

/**
 * This is the model class for table "mds_legales_caratula".
 *
 * @property int $idlegalescaratula
 * @property string $caratula
 * @property string|null $numero_expediente
 * @property int|null $anio_expediente
 * @property string|null $caso
 * @property string|null $observaciones
 * @property int $idusuario_alta
 * @property int|null $idusuario_borra
 * @property int|null $idusuario_modifica
 * @property string $created_at
 * @property string|null $deleted_at
 * @property string|null $updated_at
 */
class Mds_legales_caratula extends \yii\db\ActiveRecord
{
    const DASHBOARD_LIMITE_CARATULAS_MAYOR_CANT_REQUERIMIENTOS = 20;
    public $oficios;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_legales_caratula';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['caratula', 'idusuario_alta', 'created_at'], 'required'],
            [['idlegalescaratula', 'anio_expediente', 'idusuario_alta', 'idusuario_borra', 'idusuario_modifica'], 'integer'],
            [['caratula', 'numero_expediente', 'caso', 'observaciones'], 'string'],
            [['created_at', 'deleted_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlegalescaratula' => '#',
            'caratula' => 'Carátula',
            'numero_expediente' => 'Número Expediente',
            'anio_expediente' => 'Año',
            'caso' => 'Caso',
            'deleted_at' => 'Activo',
        ];
    }

    public static function searchCaratula($inputSearch)
    {
        return Mds_legales_caratula::find()
            ->select("idlegalescaratula, caratula, numero_expediente, anio_expediente, caso")
            ->where("deleted_at IS NULL AND (caratula LIKE '%$inputSearch%' OR numero_expediente = '$inputSearch' OR caso = '$inputSearch')")
            ->asArray()
            ->all();
    }

    public static function getAllCaratulasActivas($where)
    {
        return Mds_legales_caratula::find()
            ->select("idlegalescaratula")
            ->where($where)
            ->asArray()
            ->all();
    }

    public static function getMayorCantidadRequerimientosPorCaratula($whereFechas, $limit)
    {
        $mayorCantidadRequerimientosPorCaratulaArray = [];
        $where = "caratula.caratula IS NOT NULL AND caratula.caratula != '' AND caratula.deleted_at IS NULL and oficio.activo = 1";
        if ($whereFechas) {
            $where .= " AND $whereFechas";
        }

        $mayorCantidadRequerimientosPorCaratula = Mds_legales_caratula::find()
            ->select("cantidadRequerimientos")
            ->from("
                    (SELECT COUNT(*) AS cantidadRequerimientos
                    FROM mds_legales_caratula AS caratula
                    INNER JOIN mds_legales_oficio AS oficio ON oficio.idlegalescaratula = caratula.idlegalescaratula
                    WHERE $where
                    GROUP BY oficio.idlegalescaratula
                    HAVING cantidadRequerimientos > 1
                    ORDER BY cantidadRequerimientos DESC) caratula
                ")
            ->groupBy("cantidadRequerimientos")
            ->orderBy(['cantidadRequerimientos' => SORT_DESC])
            ->limit($limit)
            ->asArray()
            ->all();

            if ($mayorCantidadRequerimientosPorCaratula) {
                foreach ($mayorCantidadRequerimientosPorCaratula as $cantidad) {
                    array_push($mayorCantidadRequerimientosPorCaratulaArray, $cantidad['cantidadRequerimientos']) ;
                }
            }

        return $mayorCantidadRequerimientosPorCaratulaArray;
    }

    public static function getCaratulasConMasRequerimientos($whereFechas, $cantidadRequerimientos)
    {
        $where = "caratula.caratula IS NOT NULL AND caratula.caratula != '' AND caratula.deleted_at IS NULL and oficio.activo = 1";
        if ($whereFechas) {
            $where .= " AND $whereFechas";
        }

        return Mds_legales_caratula::find()
            ->select("*")
            ->from("
                    (SELECT caratula.idlegalescaratula, caratula.numero_expediente, caratula.anio_expediente, caratula.caso, caratula.caratula, COUNT(*) AS cantidadRequerimientos
                    FROM mds_legales_caratula AS caratula
                    INNER JOIN mds_legales_oficio AS oficio ON oficio.idlegalescaratula = caratula.idlegalescaratula
                    WHERE $where
                    GROUP BY oficio.idlegalescaratula
                    HAVING cantidadRequerimientos > 1
                    ORDER BY cantidadRequerimientos DESC) caratula
                ")
            ->where(['in', 'caratula.cantidadRequerimientos', $cantidadRequerimientos])
            ->asArray()
            ->all();
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->caratula = trim($this->caratula);
        $this->numero_expediente = str_replace(' ', '', $this->numero_expediente);
        $this->anio_expediente = str_replace(' ', '', $this->anio_expediente);
        $this->caso = str_replace(' ', '', $this->caso);
        return true;
    }
}
