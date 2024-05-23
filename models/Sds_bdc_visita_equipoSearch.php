<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_bdc_visita_equipo;

/**
 * Sds_bdc_visita_equipoSearch represents the model behind the search form about `app\models\Sds_bdc_visita_equipo`.
 */
class Sds_bdc_visita_equipoSearch extends Sds_bdc_visita_equipo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idvisitaequipo', 'idequipo', 'idresponsable', 'idvisita'], 'integer'],
            [['ip', 'observaciones', 'responsable', 'ip_filtro'], 'safe'],
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
        $query = Sds_bdc_visita_equipo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' =>['idequipo', 'idresponsable', 'ip', 'observaciones', 'responsable']
            ]
        ]);

        $this->load($params);
        
        $query->select(['ve.*', 'UPPER(CONCAT(p.apellido, \', \', p.nombre)) AS responsable'])
        ->from('sds_bdc_visita_equipo ve')
        ->innerJoin('mds_org_contacto c', 've.idresponsable = c.idcontacto')
        ->innerJoin('sds_com_persona p', 'c.idpersona = p.idpersona');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            've.idvisitaequipo' => $this->idvisitaequipo,
            've.idequipo' => $this->idequipo,
            've.idresponsable' => $this->idresponsable,
            've.idvisita' => $this->idvisita
        ]);
        

        $query->andFilterWhere(['like', 've.ip', $this->ip]);
        $query->andFilterWhere(['like', 've.ip', $this->ip_filtro]);
        $query->andFilterWhere(['like', 've.observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
