<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_800_atencion_am;

/**
 * Sds_800_atencion_amSearch represents the model behind the search form about `app\models\Sds_800_atencion_am`.
 */
class Sds_800_atencion_amSearch extends Sds_800_atencion_am
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idllamada', 'idpersona', 'idusuario', 'telefono_referente', 'atencion_previa', 'basura', 'cable', 'internet', 'familiares', 'sociales', 'emergente', 'psicologico', 'psiquiatrico', 'administra_dinero', 'plan', 'recreacion', 'centro', 'orientado', 'dependiente', 'intoxicado', 'delirios', 'violentado', 'expresion'], 'integer'],
            [['fecha_hora', 'demanda', 'institucion', 'profesionales', 'sociales_detalle', 'emergente_detalle', 'detalle_dinero', 'detalle_plan', 'observaciones', 'archivo_seguridad', 'archivo_salud'], 'safe'],
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
        $query = Sds_800_atencion_am::find();

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
            'idusuario' => $this->idusuario,
            'fecha_hora' => $this->fecha_hora,
            'telefono_referente' => $this->telefono_referente,
            'atencion_previa' => $this->atencion_previa,
            'basura' => $this->basura,
            'cable' => $this->cable,
            'internet' => $this->internet,
            'familiares' => $this->familiares,
            'sociales' => $this->sociales,
            'emergente' => $this->emergente,
            'psicologico' => $this->psicologico,
            'psiquiatrico' => $this->psiquiatrico,
            'administra_dinero' => $this->administra_dinero,
            'plan' => $this->plan,
            'recreacion' => $this->recreacion,
            'centro' => $this->centro,
            'orientado' => $this->orientado,
            'dependiente' => $this->dependiente,
            'intoxicado' => $this->intoxicado,
            'delirios' => $this->delirios,
            'violentado' => $this->violentado,
            'expresion' => $this->expresion,
        ]);

        $query->andFilterWhere(['like', 'demanda', $this->demanda])
            ->andFilterWhere(['like', 'institucion', $this->institucion])
            ->andFilterWhere(['like', 'profesionales', $this->profesionales])
            ->andFilterWhere(['like', 'sociales_detalle', $this->sociales_detalle])
            ->andFilterWhere(['like', 'emergente_detalle', $this->emergente_detalle])
            ->andFilterWhere(['like', 'detalle_dinero', $this->detalle_dinero])
            ->andFilterWhere(['like', 'detalle_plan', $this->detalle_plan])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'archivo_seguridad', $this->archivo_seguridad])
            ->andFilterWhere(['like', 'archivo_salud', $this->archivo_salud]);

        return $dataProvider;
    }
}
