<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_800_llamada;

/**
 * Sds_800_llamadaSearch represents the model behind the search form about `app\models\Sds_800_llamada`.
 */
class Sds_800_llamadaSearch extends Sds_800_llamada
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idllamada', 'idpersona', 'idrisneu', 'tipo', 'genero', 'idderivacion', 'afectado_dni', 'idusuario', 'estado', 'idorigen', 'area'], 'integer'],
            [['idrisneu', 'institucion', 'vinculo', 'detalle', 'afectado_nombre', 'afectado_apodo', 'fecha_hora', 'fdesde', 'fhasta', 'dni', 'telefono', 'nombre_completo', 'persona_afectada', 'estado', 'afectado_tratamiento', 'area', 'area_interviniente', 'deleted_at', 'profesional_interviniente'], 'safe'],
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
    public function search($params, $fechaInicio = null, $fechaFin = null)
    {
        $query = Sds_800_llamada::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['idllamada', 'idrisneu', 'idusuario', 'fecha_hora', 'dni', 'telefono', 'nombre_completo', 'tipo', 'genero', 'idderivacion', 'persona_afectada', 'estado', 'afectado_tratamiento', 'idorigen', 'area_interviniente', 'profesional_interviniente', 'deleted_at'],
                'defaultOrder' => ['fecha_hora' => SORT_DESC, 'idllamada' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d');
            $sql_desde = "fecha_hora >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_create($this->fhasta);
            $fecha_hasta_aux = $fecha_hasta_aux->modify('+1 day');
            $fecha_hasta_aux = date_format($fecha_hasta_aux, 'Y-m-d');
            $sql_hasta = "fecha_hora <= '$fecha_hasta_aux'";
        }
        $query->addSelect([
            "sds_800_llamada.deleted_at",
            "profesional_interviniente",
            "idllamada",
            "idrisneu",
            "idusuario", "fecha_hora", "documento as dni", "genero", "afectado_dni", "telefono", "concat(nombre,' ',apellido) nombre_completo",
            "tipo", "idderivacion", "afectado_tratamiento", "trim(concat(ifnull(concat('DNI ',afectado_dni),' '),
                        if(afectado_dni is not null and ((afectado_nombre is not null and afectado_nombre!='') or (afectado_apodo is not null and afectado_apodo!='')),'<br>',''),ifnull(afectado_nombre,' '),
                        if((afectado_dni is not null or (afectado_nombre is not null and afectado_nombre!='')) and 
                        afectado_apodo is not null and afectado_apodo!='','<br>',''),
                        if(afectado_apodo != '',concat('\"',afectado_apodo,'\"'),''))) persona_afectada", "estado", "area", "idorigen", "area_interviniente"
        ]);
        $query->from(["sds_800_llamada", "sds_800_persona", "sds_com_persona"]);
        $query->andWhere('sds_800_llamada.idpersona=sds_800_persona.idpersona')
            ->andWhere('sds_800_persona.idpersona=sds_com_persona.idpersona');
        $query->andFilterWhere([
            'tipo' => $this->tipo,
            'genero' => $this->genero,
            'idderivacion' => $this->idderivacion,
            'idusuario' => $this->idusuario,
            'profesional_interviniente' => $this->profesional_interviniente
        ]);
        $query->andWhere(["sds_800_llamada.area" => $this->area]);

        if ($this->deleted_at === '0') {
            $query->andWhere(['not', ['deleted_at' => null]]);
        } else {
            $query->andWhere(['deleted_at' => null]);
        }

        if ($fechaInicio || $fechaFin) {
            if ($fechaFin) {
                $fechaFin = date_create($fechaFin);
                $fechaFin = $fechaFin->modify('+1 day');
                $fechaFin = date_format($fechaFin, 'Y-m-d');
            }
            $whereFechaCarga = '';
            if ($fechaInicio && $fechaFin) {
                $whereFechaCarga .= "sds_800_llamada.fecha_hora >= '$fechaInicio' AND sds_800_llamada.fecha_hora <= '$fechaFin'";
            } else if ($fechaInicio) {
                $whereFechaCarga .= "sds_800_llamada.fecha_hora >= '$fechaInicio'";
            } else if ($fechaFin) {
                $whereFechaCarga .= "sds_800_llamada.fecha_hora <= '$fechaFin'";
            }

            if ($whereFechaCarga) {
                $query->andWhere($whereFechaCarga);
            }
        }

        $query->andFilterWhere(['like', 'documento', $this->dni])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'detalle', $this->detalle])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'afectado_tratamiento', $this->afectado_tratamiento])
            ->andFilterWhere(['like', 'area_interviniente', $this->area_interviniente])
            ->andFilterWhere(['idllamada' => $this->idllamada])
            ->andFilterWhere(['idrisneu' => $this->idrisneu])
            ->andWhere($sql_desde)->andWhere($sql_hasta);
        // $query->andWhere(["sds_800_llamada.deleted_at" => null]);

        $query->having("nombre_completo like '%" . $this->nombre_completo . "%' and persona_afectada like '%" . $this->persona_afectada . "%'");
        // $query->orderBy(['fecha_hora' => SORT_DESC]);

        return $dataProvider;
    }
}
