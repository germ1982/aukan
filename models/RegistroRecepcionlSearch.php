<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RegistroRecepcion;

/**
 * RegistroRecepcionlSearch represents the model behind the search form about `app\models\RegistroRecepcion`.
 */
class RegistroRecepcionlSearch extends RegistroRecepcion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_registro_recepcion', 'dni', 'acceso', 'id_dispositivo_derivacion', 'id_responsable_derivacion', 'id_tipo_recepcion'], 'integer'],
            [['fecha', 'hora', 'motivo', 'observacion'], 'safe'],
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
        $query = RegistroRecepcion::find();

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
            'id_registro_recepcion' => $this->id_registro_recepcion,
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'dni' => $this->dni,
            'acceso' => $this->acceso,
            'id_dispositivo_derivacion' => $this->id_dispositivo_derivacion,
            'id_responsable_derivacion' => $this->id_responsable_derivacion,
            'id_tipo_recepcion' => $this->id_tipo_recepcion,
        ]);

        $query->andFilterWhere(['like', 'motivo', $this->motivo])
            ->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}
