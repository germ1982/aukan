<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_reg_interno;

/**
 * Sds_reg_internoSearch represents the model behind the search form about `app\models\Sds_reg_interno`.
 */
class Sds_reg_internoSearch extends Sds_reg_interno
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idinterno', 'idcapaitem', 'iddispositivo', 'idcontacto', 'grupo'], 'integer'],
            [['recepcion', 'responsable', 'organismo', 'edificio', 'dispositivo'], 'safe'],
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
        $query = Sds_reg_interno::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['idinterno', 'idcapaitem', 'iddispositivo', 'idcontacto', 'grupo', 'recepcion','responsable', 'organismo', 'edificio', 'dispositivo'],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->select(['interno.*','org.descripcion as organismo', 'item.descripcion edificio', 'dispo.descripcion dispositivo'])
        ->from('sds_reg_interno as interno')
        ->innerJoin('sds_gis_capa_item as item', 'interno.idcapaitem=item.idcapaitem')
        ->innerJoin('mds_org_dispositivo as dispo', 'interno.iddispositivo=dispo.iddispositivo')
        ->innerJoin('mds_org_organismo as org', 'dispo.idorganismo=org.idorganismo');
        
        $query->andFilterWhere([
            'idinterno' => $this->idinterno,
            'idcapaitem' => $this->idcapaitem,
            'iddispositivo' => $this->iddispositivo,
            'idcontacto' => $this->idcontacto,
            'grupo' => $this->grupo,
        ]);

        $query->andFilterWhere(['like', 'recepcion', $this->recepcion])
        ->andFilterWhere(['like', 'responsable', $this->responsable])
        ->andFilterWhere(['like', 'org.descripcion', $this->organismo])
        ->andFilterWhere(['like', 'item.descripcion', $this->edificio])
        ->andFilterWhere(['like', 'dispo.descripcion', $this->dispositivo]);

        return $dataProvider;
    }
}
