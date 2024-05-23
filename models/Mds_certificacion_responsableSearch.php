<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_certificacion_responsable;

/**
 * Mds_certificacion_responsableSearch represents the model behind the search form of `app\models\Mds_certificacion_responsable`.
 */
class Mds_certificacion_responsableSearch extends Mds_certificacion_responsable
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idresponsable', 'idcertificacion', 'dni', 'idparentesco', 'idpersona', 'idusuario_modifica'], 'integer'],
            [['nombre_apellido', 'cbu_alias', 'parentesco_otro', 'motivo_cambio', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
    public function search($params)
    {
        $query = Mds_certificacion_responsable::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idresponsable' => $this->idresponsable,
            'idcertificacion' => $this->idcertificacion,
            'dni' => $this->dni,
            'idparentesco' => $this->idparentesco,
            'idpersona' => $this->idpersona,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'idusuario_modifica' => $this->idusuario_modifica,
        ]);

        $query->andFilterWhere(['like', 'nombre_apellido', $this->nombre_apellido])
            ->andFilterWhere(['like', 'cbu_alias', $this->cbu_alias])
            ->andFilterWhere(['like', 'parentesco_otro', $this->parentesco_otro])
            ->andFilterWhere(['like', 'motivo_cambio', $this->motivo_cambio]);

        return $dataProvider;
    }
}
