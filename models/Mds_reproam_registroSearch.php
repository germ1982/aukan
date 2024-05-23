<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_reproam_registro;
use Yii;


/**
 * Mds_reproam_registroSearch represents the model behind the search form of `app\models\Mds_reproam_registro`.
 */
class Mds_reproam_registroSearch extends Mds_reproam_registro
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idregistro'], 'integer'],
            [['idregistro', 'numero_legajo_reproam', 'nombre', 'idlocalidad', 'idbarrio', 'idzona', 'personeria_juridica', 'personeria_juridica_fecha_vencimiento', 'inscripto', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $fechaInicio = null, $fechaFin = null, $tipo = null, $idzona = null, $idlocalidad = null)
    {
        $hasRolUsuario = Mds_legales_oficio::tieneRol(Mds_reproam_registro::ID_ROL_USUARIO);
        $usuarioAuth = Yii::$app->user->identity;
        $query = Mds_reproam_registro::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idregistro' => SORT_DESC]],
        ]);

        $this->load($params);
        if (isset($params['Mds_reproam_registroSearch']['personeria_juridica_fecha_vencimiento']) && $params['Mds_reproam_registroSearch']['personeria_juridica_fecha_vencimiento']) {
            $personeria_juridica_fecha_vencimiento = $params['Mds_reproam_registroSearch']['personeria_juridica_fecha_vencimiento'];
            $personeria_juridica_fecha_vencimiento = armarDateParaMySql($personeria_juridica_fecha_vencimiento);
            $personeria_juridica_fecha_vencimiento = date_create($personeria_juridica_fecha_vencimiento);
            $personeria_juridica_fecha_vencimiento = date_format($personeria_juridica_fecha_vencimiento, 'Y-m-d');
            $this->personeria_juridica_fecha_vencimiento = $personeria_juridica_fecha_vencimiento;
        }

        if ($fechaInicio || $fechaFin) {
            if ($fechaFin) {
                $fechaFin = date_create($fechaFin);
                $fechaFin = $fechaFin->modify('+1 day');
                $fechaFin = date_format($fechaFin, 'Y-m-d');
            }
            if ($fechaInicio && $fechaFin) {
                $whereFechaCarga = "mds_reproam_registro.created_at >= '$fechaInicio' AND mds_reproam_registro.created_at <= '$fechaFin'";
            } else if ($fechaInicio) {
                $whereFechaCarga = "mds_reproam_registro.created_at >= '$fechaInicio'";
            } else if ($fechaFin) {
                $whereFechaCarga = "mds_reproam_registro.created_at <= '$fechaFin'";
            }
            $query->andWhere($whereFechaCarga);
        }

        if ($tipo) {
            if ($tipo == 'con_personeria' || $tipo == 'sin_personeria') {
                $personeriaJuridica = $tipo == 'con_personeria' ? 1 : 0;
                $query->andWhere(['mds_reproam_registro.personeria_juridica' => $personeriaJuridica]);
            } else {
                $constancia = $tipo == 'con_constancia' ? 1 : 0;
                $query->andWhere(['mds_reproam_registro.entrega_constancia_inscripcion' => $constancia]);
            }
        }

        if ($idzona) {
            $query->andWhere(['mds_reproam_registro.idzona' => $idzona]);
        }

        if ($idlocalidad) {
            $query->andWhere(['mds_reproam_registro.idlocalidad' => $idlocalidad]);
        }

        if ($this->deleted_at == null) {
            $query->andWhere(['mds_reproam_registro.deleted_at' => null]);
        }

        if ($this->nombre) {
            $query->andWhere(
                ['like', 'mds_reproam_registro.nombre', $this->nombre]
            );
        }

        if ($this->numero_legajo_reproam) {
            $query->andWhere(
                ['like', 'mds_reproam_registro.numero_legajo_reproam', $this->numero_legajo_reproam]
            );
        }

        if ($this->deleted_at === '0') {
            $query->andWhere(['not', ['deleted_at' => null]]);
        } else if ($this->deleted_at === '1') {
            $query->andWhere(['deleted_at' => null]);
        } 

        if ($this->idlocalidad) {
            $query->andWhere(
                ['in', 'mds_reproam_registro.idlocalidad', $this->idlocalidad]
            );
        }

        if ($this->idbarrio) {
            $query->andWhere(
                ['in', 'mds_reproam_registro.idbarrio', $this->idbarrio]
            );
        }

        if ($this->idzona) {
            $query->andWhere(
                ['in', 'mds_reproam_registro.idzona', $this->idzona]
            );
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idregistro' => $this->idregistro,
            'personeria_juridica' => $this->personeria_juridica,
            'personeria_juridica_fecha_vencimiento' => $this->personeria_juridica_fecha_vencimiento,
            'entrega_constancia_inscripcion' => $this->entrega_constancia_inscripcion,
            'inscripto' => $this->inscripto
        ]);
        $this->personeria_juridica_fecha_vencimiento ? date('d/m/Y', strtotime(str_replace('-', '/', $this->personeria_juridica_fecha_vencimiento))) :  null;
        if (isset($params['Mds_reproam_registroSearch']['personeria_juridica_fecha_vencimiento']) && $params['Mds_reproam_registroSearch']['personeria_juridica_fecha_vencimiento']) {
            $this->personeria_juridica_fecha_vencimiento = $params['Mds_reproam_registroSearch']['personeria_juridica_fecha_vencimiento'];
        }

        return $dataProvider;
    }
}

function armarDateParaMySql($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $anio = substr($fecha, 6, 4);
    $mes  = substr($fecha, 3, 2);
    $dia = substr($fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}
