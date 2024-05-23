<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_800_atencion_interior;

/**
 * Sds_800_atencion_interiorSearch represents the model behind the search form about `app\models\Sds_800_atencion_interior`.
 */
class Sds_800_atencion_interiorSearch extends Sds_800_atencion_interior
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idllamada', 'idpersona', 'lugar_intervencion', 'idpersona_referente', 'parentezco', 'idusuario'], 'integer'],
            [['lugar_especificacion', 'defensora', 'plan_accion', 'fecha_intervencion', 'archivo_adjunto'], 'safe'],
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
        $query = Sds_800_atencion_interior::find();

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
            'idpersona_referente' => $this->idpersona_referente,
            'parentezco' => $this->parentezco,
            'fecha_intervencion' => $this->fecha_intervencion,
            'idusuario' => $this->idusuario,
        ]);

        $query->andFilterWhere(['like', 'lugar_especificacion', $this->lugar_especificacion])
            ->andFilterWhere(['like', 'defensora', $this->defensora])
            ->andFilterWhere(['like', 'plan_accion', $this->plan_accion])
            ->andFilterWhere(['like', 'archivo_adjunto', $this->archivo_adjunto]);

        return $dataProvider;
    }
}
