<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mds_por_familia;

/**
 * mds_por_familiaSearch represents the model behind the search form about `app\models\mds_por_familia`.
 */
class mds_por_familiaSearch extends mds_por_familia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idfamilia', 'mes', 'anio'], 'integer'],
            [['localidad', 'nombre', 'responsable_cobro', 'programa', 'subprograma', 'area', 'responsable_certificacion', 'expediente', 'desde', 'hasta', 'F12', 'F15', 'F16', 'F17', 'F18', 'F19'], 'safe'],
            [['dni', 'cuil', 'dni_responsable', 'importe'], 'number'],
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
        $query = mds_por_familia::find();

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
            'idfamilia' => $this->idfamilia,
            'dni' => $this->dni,
            'cuil' => $this->cuil,
            'dni_responsable' => $this->dni_responsable,
            'importe' => $this->importe,
            'mes' => $this->mes,
            'anio' => $this->anio,
        ]);

        $query->andFilterWhere(['like', 'localidad', $this->localidad])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'responsable_cobro', $this->responsable_cobro])
            ->andFilterWhere(['like', 'programa', $this->programa])
            ->andFilterWhere(['like', 'subprograma', $this->subprograma])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'responsable_certificacion', $this->responsable_certificacion])
            ->andFilterWhere(['like', 'expediente', $this->expediente])
            ->andFilterWhere(['like', 'desde', $this->desde])
            ->andFilterWhere(['like', 'hasta', $this->hasta])
            ->andFilterWhere(['like', 'F12', $this->F12])
            ->andFilterWhere(['like', 'F15', $this->F15])
            ->andFilterWhere(['like', 'F16', $this->F16])
            ->andFilterWhere(['like', 'F17', $this->F17])
            ->andFilterWhere(['like', 'F18', $this->F18])
            ->andFilterWhere(['like', 'F19', $this->F19]);

        return $dataProvider;
    }
}
