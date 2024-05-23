<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_reg_contrasenia;

/**
 * Mds_reg_contraseniaSearch represents the model behind the search form about `app\models\Mds_reg_contrasenia`.
 */
class Mds_reg_contraseniaSearch extends Mds_reg_contrasenia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcontrasenia', 'idorganismo', 'tipo'], 'integer'],
            [['fecha_carga', 'ip', 'descripcion', 'usuario', 'contrasenia', 'ubicacion', 'observaciones'], 'safe'],
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
        $user=Mds_seg_usuario::findOne(Yii::$app->user->identity->idusuario);
        $query = Mds_reg_contrasenia::find();

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
            'idcontrasenia' => $this->idcontrasenia,
            'fecha_carga' => $this->fecha_carga,
            'idorganismo' => $user->organismo_stock,
            'tipo' => $this->tipo,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'usuario', $this->usuario])
            ->andFilterWhere(['like', 'contrasenia', $this->contrasenia])
            ->andFilterWhere(['like', 'ubicacion', $this->ubicacion])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
