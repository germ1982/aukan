<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_reg_ip;

/**
 * Sds_reg_ipSearch represents the model behind the search form about `app\models\Sds_reg_ip`.
 */
class Sds_reg_ipSearch extends Sds_reg_ip
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idip', 'ip', 'idcontacto', 'asignacion', 'sistema_operativo', 'procesador', 'memoria', 'disco', 'conectividad', 'iddispositivo', 'idpersona', 'organismo'], 'integer'],
            [['subred', 'observaciones', 'iddispositivo', 'idpersona', 'ip_completa', 'organismo'], 'safe'],
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
        $query = Sds_reg_ip::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //ANOTEZE: el sort es para que se ordene por cada columna y te aparezcan en azules. 
            'sort' => [
                'attributes' => ['ip_completa', 'ip', 'subred', 'iddispositivo', 'organismo', 'idpersona', 'observaciones', 'asignacion', 'sistema_operativo', 'procesador', 'memoria', 'disco', 'conectividad'],
                'defaultOrder' => ['subred' => SORT_ASC, 'ip' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->organismo == null) {
            $this->organismo = -1;
        }
        if ($this->iddispositivo == null) {
            $this->iddispositivo = -1;
        }
        if ($this->idpersona == null) {
            $this->idpersona = -1;
        }
        $query->addSelect([
            'sds_reg_ip.*',
            '(SELECT iddispositivo FROM mds_org_contacto c where c.idcontacto=sds_reg_ip.idcontacto) AS iddispositivo',
            '(SELECT idpersona FROM mds_org_contacto c where c.idcontacto=sds_reg_ip.idcontacto) AS idpersona',
            'concat(subred,".",ip) ip_completa',
            '(SELECT idorganismo FROM sds_bdc_equipo e WHERE e.idequipo = sds_reg_ip.idequipo) AS organismo'
        ]);
        $query->andFilterWhere([
            'idip' => $this->idip,
            'ip' => $this->ip,
            'idcontacto' => $this->idcontacto,
            'asignacion' => $this->asignacion,
            'sistema_operativo' => $this->sistema_operativo,
            'procesador' => $this->procesador,
            'memoria' => $this->memoria,
            'disco' => $this->disco,
            'conectividad' => $this->conectividad,


        ]);

        $query->andFilterWhere(['like', 'concat(subred,".",ip)', $this->ip_completa])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);
        $query->having('(idpersona=' . $this->idpersona . ' or 0>' . $this->idpersona . ') and (iddispositivo=' . $this->iddispositivo . ' or 0>' . $this->iddispositivo . ') and (organismo=' . $this->organismo . ' or 0>' . $this->organismo . ')');
        //$query->having('(idpersona=' . $this->idpersona . ' or 0>' . $this->idpersona . ') and (iddispositivo=' . $this->iddispositivo . ' or 0>' . $this->iddispositivo . ')');
        return $dataProvider;
    }
}
