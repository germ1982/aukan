<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_cap_docente;

/**
 * Mds_cap_docenteSearch represents the model behind the search form about `app\models\Mds_cap_docente`.
 */
class Mds_cap_docenteSearch extends Mds_cap_docente
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idpersona', 'profesion_corta', 'localidad', 'dni',], 'integer'],
            [['telefono', 'mail', 'dni','datos_docente', 'firma', 'firma_digital', 'cargo_certificado'], 'safe'],
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
        $query = Mds_cap_docente::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['dni', 'telefono', 'firma_digital','nombre', 'apellido', 'email', 'idpersona'],
            ]            
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([            
            'idpersona' => $this->idpersona,
            'profesion_corta' => $this->profesion_corta,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'datos_docente', $this->datos_docente])
            ->andFilterWhere(['like', 'firma', $this->firma])
            ->andFilterWhere(['like', 'firma_digital', $this->firma_digital])
            ->andFilterWhere(['like', 'cargo_certificado', $this->cargo_certificado])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'localidad', $this->localidad])
            ->andWhere("idpersona in (SELECT idpersona from sds_com_persona where documento like '%" . $this->dni . "%')");
        return $dataProvider;
    }
}
