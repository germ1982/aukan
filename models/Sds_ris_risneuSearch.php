<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_ris_risneu;

/**
 * Sds_ris_risneuSearch represents the model behind the search form about `app\models\Sds_ris_risneu`.
 */
class Sds_ris_risneuSearch extends Sds_ris_risneu
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'idrisneu', 'calle', 'calle_interseccion', 'idbarrio', 'idusuario', 'area',
                'realizado_por', 'vivienda_uso', 'vivienda_ubicacion', 'vivienda_propiedad',
                'vivienda_habitaciones', 'vivienda_tipo', 'vivienda_piso', 'vivienda_agua_obtiene',
                'vivienda_agua', 'vivienda_bano', 'vivienda_desague', 'vivienda_iluminacion', 'vivienda_medidor',
                'vivienda_combustible_calefaccion', 'vivienda_combustible_cocina', 'vivienda_techo',
                'vivienda_paredes', 'estado', 'oficial'
            ], 'integer'],
            [[
                'fecha_carga', 'fecha', 'calle_numero', 'casa', 'torre', 'piso', 'depto', 'manzana',
                'parcela', 'lote', 'pilar', 'observaciones', 'benef_nombre', 'benef_dni', 'idlocalidad',
                'fdesde', 'fhasta', 'estado', 'oficial', 'idencuestador', 'beneficiarios'
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
    public function search($params, $fechaInicio = null, $fechaFin = null, $idlocalidad = null, $idencuestador = null, $estado = null, $idrealizadopor = null, $idarea = null)
    {
        $idSituacionCalle = 4929;
        $query = Sds_ris_risneu::find()->distinct();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['idrisneu', 'fecha', 'estado', 'oficial'],
                'defaultOrder' => ['idrisneu' => SORT_DESC]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /*
        Esta incompleto cuando: no tiene personas en el grupo conviviente o no es situacion calle y alguna de las opciones de vivienda es 'sin asignar'
        Esta completo cuando: tiene personas en el grupo conviviente y es situacion calle o todas las opciones de vivienda estan asignadas
        Esta inactivo cuando: el activo del risneu es 0 
        */
        $query->addSelect([
            "sds_ris_risneu.*, if(sds_ris_risneu.activo,
                    if (
                        risPersonaEstado.idpersonarisneu IS NULL or 
                        (
                            vivienda_uso!=$idSituacionCalle and 
                            (
                                vivienda_uso=1 or 
                                vivienda_ubicacion=1 or 
                                vivienda_propiedad=1 or
                                vivienda_habitaciones=0 or 
                                vivienda_tipo=1 or 
                                vivienda_piso=1 or 
                                vivienda_agua_obtiene=1 or 
                                vivienda_agua=1 or 
                                vivienda_bano=1 or 
                                vivienda_desague=1 or 
                                vivienda_iluminacion=1 or 
                                vivienda_medidor=1 or
                                vivienda_combustible_calefaccion=1 or 
                                vivienda_combustible_cocina=1 or 
                                vivienda_techo=1 or 
                                vivienda_paredes=1
                            )
                        ),
                        0,1
                    )
                ,2) estado "
        ]);
        $query->from("sds_ris_risneu");
        $query->leftJoin("sds_ris_persona as risPersonaEstado", "risPersonaEstado.idrisneu = sds_ris_risneu.idrisneu AND risPersonaEstado.activo = 1");

        $whereFechaCarga = $this->estado == null ? "sds_ris_risneu.activo = 1" : '';
        if ($fechaInicio || $fechaFin) {

            if ($fechaInicio && $fechaFin) {
                $whereFechaCarga .= " AND sds_ris_risneu.fecha_carga >= '$fechaInicio' AND sds_ris_risneu.fecha_carga <= '$fechaFin'";
            } else if ($fechaInicio) {
                $whereFechaCarga .= " AND sds_ris_risneu.fecha_carga >= '$fechaInicio'";
            } else if ($fechaFin) {
                $whereFechaCarga .= " AND sds_ris_risneu.fecha_carga <= '$fechaFin'";
            }
        }
        $query->andWhere($whereFechaCarga);

        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d');
            $sql_desde = "fecha >= '$fecha_desde_aux'";
        }

        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha <= '$fecha_hasta_aux'";
        }

        if ($this->idencuestador) {
            $query->andWhere(
                ['in', 'sds_ris_risneu.encuestador', $this->idencuestador]
            );
        }

        if ($this->idlocalidad) {
            $query->join("left join", "sds_com_barrio as barrio", "sds_ris_risneu.idbarrio = barrio.idbarrio");
            $query->andWhere(
                ['in', 'barrio.idlocalidad', $this->idlocalidad]
            );
        }

        if ($this->beneficiarios) {
            $query->leftJoin("sds_ris_persona as risPersona", "risPersona.idrisneu = sds_ris_risneu.idrisneu")
                ->leftJoin("sds_com_persona as persona", "risPersona.idpersona = persona.idpersona AND risPersona.activo = 1");

            //Esta opcion busca por mas de un nombre/apellido/dni
            // $beneficiariosExplode = explode(' ', $this->beneficiarios);
            // foreach ($beneficiariosExplode as $word) {
            //     $sqlBeneficiariosArray[] = "UPPER(persona.nombre) LIKE '%" . mb_strtoupper($word) . "%'";
            //     $sqlBeneficiariosArray[] = "UPPER(persona.apellido) LIKE '%" . mb_strtoupper($word) . "%'";
            //     $sqlBeneficiariosArray[] = "persona.documento LIKE '%" . mb_strtoupper($word) . "%'";
            // }
            // $sqlBeneficiarios = implode(" OR ", $sqlBeneficiariosArray);
            // $query->andWhere($sqlBeneficiarios);

            //Esta opcion solo busca por un nombre/apellido/dni
            $query->andWhere(
                [
                    'or',
                    ['like', 'persona.nombre', $this->beneficiarios],
                    ['like', 'persona.apellido', $this->beneficiarios],
                    ['like', 'concat( persona.apellido, " ", persona.nombre )', $this->beneficiarios],
                    ['like', 'concat( persona.nombre, " ", persona.apellido )', $this->beneficiarios],
                    ['like', 'persona.documento', $this->beneficiarios],
                    ['like', 'sds_ris_risneu.dni', $this->beneficiarios],
                ]
            );
        }

        if ($this->oficial != null) {
            $query->andWhere(["sds_ris_risneu.oficial" => $this->oficial]);
        }

        if ($this->estado != null) {
            $query->having("(estado={$this->estado} or -1={$this->estado})");
        }

        if ($idlocalidad) {
            $query->join("left join", "sds_com_barrio as barrio", "sds_ris_risneu.idbarrio = barrio.idbarrio");
            $query->andWhere(['barrio.idlocalidad' => $idlocalidad]);
        }

        if ($idencuestador) {
            $query->andWhere(['sds_ris_risneu.encuestador' => $idencuestador]);
        }

        if ($estado != null) {
            $query->having("estado=$estado");
        }

        if ($idrealizadopor) {
            $query->andWhere(['sds_ris_risneu.realizado_por' => $idrealizadopor]);
        }

        if ($idarea) {
            $query->andWhere(['sds_ris_risneu.area' => $idarea]);
        }

        $query->andFilterWhere(['sds_ris_risneu.idrisneu' => $this->idrisneu])
            ->andWhere($sql_desde)->andWhere($sql_hasta);

        return $dataProvider;
    }
}
