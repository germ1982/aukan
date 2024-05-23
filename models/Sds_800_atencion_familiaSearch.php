<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_800_atencion_familia;

/**
 * Sds_800_atencion_familiaSearch represents the model behind the search form about `app\models\Sds_800_atencion_familia`.
 */
class Sds_800_atencion_familiaSearch extends Sds_800_atencion_familia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idllamada', 'idpersona', 'lugar_intervencion', 'edad', 'idpersona_referente', 'parentezco', 'sabe_leer', 'nivel_estudio', 'trabaja', 'atendido', 'beneficio_social', 'centro_salud', 'obra_social', 'tratamiento_medico', 'orientado', 'intoxicado', 'violentado', 'idusuario'], 'integer'],
            [['lugar_especificacion', 'defensora', 'alojado', 'hogar', 'dia_hora', 'operador', 'equipo_tecnico', 'establecimiento', 'tipo_trabajo', 'institucion', 'nombre_profesionales', 'area_beneficio', 'nombre_centro_salud', 'nombre_obra_social', 'tratamiento_institucion', 'plan_accion', 'fecha_intervencion'], 'safe'],
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
    public function search($params)
    {
        $query = Sds_800_atencion_familia::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idllamada' => $this->idllamada,
            'idpersona' => $this->idpersona,
            'lugar_intervencion' => $this->lugar_intervencion,
            'edad' => $this->edad,
            'idpersona_referente' => $this->idpersona_referente,
            'parentezco' => $this->parentezco,
            'dia_hora' => $this->dia_hora,
            'sabe_leer' => $this->sabe_leer,
            'nivel_estudio' => $this->nivel_estudio,
            'trabaja' => $this->trabaja,
            'atendido' => $this->atendido,
            'beneficio_social' => $this->beneficio_social,
            'centro_salud' => $this->centro_salud,
            'obra_social' => $this->obra_social,
            'tratamiento_medico' => $this->tratamiento_medico,
            'orientado' => $this->orientado,
            'intoxicado' => $this->intoxicado,
            'violentado' => $this->violentado,
            'fecha_intervencion' => $this->fecha_intervencion,
            'idusuario' => $this->idusuario,
        ]);

        $query->andFilterWhere(['like', 'lugar_especificacion', $this->lugar_especificacion])
            ->andFilterWhere(['like', 'defensora', $this->defensora])
            ->andFilterWhere(['like', 'alojado', $this->alojado])
            ->andFilterWhere(['like', 'hogar', $this->hogar])
            ->andFilterWhere(['like', 'operador', $this->operador])
            ->andFilterWhere(['like', 'equipo_tecnico', $this->equipo_tecnico])
            ->andFilterWhere(['like', 'establecimiento', $this->establecimiento])
            ->andFilterWhere(['like', 'tipo_trabajo', $this->tipo_trabajo])
            ->andFilterWhere(['like', 'institucion', $this->institucion])
            ->andFilterWhere(['like', 'nombre_profesionales', $this->nombre_profesionales])
            ->andFilterWhere(['like', 'area_beneficio', $this->area_beneficio])
            ->andFilterWhere(['like', 'nombre_centro_salud', $this->nombre_centro_salud])
            ->andFilterWhere(['like', 'nombre_obra_social', $this->nombre_obra_social])
            ->andFilterWhere(['like', 'tratamiento_institucion', $this->tratamiento_institucion])
            ->andFilterWhere(['like', 'plan_accion', $this->plan_accion]);
          //  ->andFilterWhere(['like', 'persona_datos', $this->persona_datos]);


        return $dataProvider;
    }
}
