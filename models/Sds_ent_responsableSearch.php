<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_ent_responsable;

/**
 * Sds_ent_responsableSearch represents the model behind the search form about `app\models\Sds_ent_responsable`.
 */
class Sds_ent_responsableSearch extends Sds_ent_responsable
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idresponsable', 'deudor', 'dni', 'idorganismoexterno'], 'integer'],
            [['mail', 'telefono', 'dni_frente', 'dni_dorso', 'responsable', 'fecha_deuda', 'ultima_adeuda'], 'safe'],
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
        $query = Sds_ent_responsable::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['responsable', 'dni', 'telefono', 'mail', 'deudor', 'idorganismoexterno', 'ultima_adeuda'],
                'defaultOrder' => ['responsable' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /* SELECT idconfiguracion as idresponsable,descripcion responsable,telefono,mail,ifnull(saldo,0)>0 deudor
        FROM sds_com_configuracion resp
        LEFT JOIN sds_ent_responsable datos_resp on datos_resp.idresponsable=resp.idconfiguracion
        LEFT JOIN (SELECT receptor, sum(cantidad - IFNULL(rendidas,0)) saldo
                FROM sds_ent_entrega ent
                JOIN sds_ent_tipo tipo ON tipo.idtipo= ent.idtipo
                LEFT JOIN (SELECT emisor,sum(cantidad) rendidas
                FROM sds_ent_entrega entfinal
                WHERE dni IS NOT null
                GROUP BY emisor) tempRend ON tempRend.emisor=ent.identrega
                WHERE YEAR(ent.fecha_hora) >=2021 AND receptor IS NOT null
                GROUP BY receptor
                ORDER BY receptor) temp_deuda ON temp_deuda.receptor=resp.idconfiguracion
        WHERE resp.idconfiguraciontipo=44
        ORDER BY descripcion */

        if ($this->deudor == null) {
            $this->deudor = -1;
        }
        if ($this->fecha_deuda == null) {
            $this->fecha_deuda = "2021-08-01";
        } else {
            $this->fecha_deuda = date('Y-m-d', strtotime(str_replace('/', '-', $this->fecha_deuda)));
        }
        $query->addSelect([
            'dni',
            'idconfiguracion as idresponsable',
            'descripcion as responsable',
            'ifnull(telefono,\'\') as telefono',
            'ifnull(mail,\'\') as mail',
            'ifnull(saldo,0)>0 as deudor',
            'idorganismoexterno'
        ]);
        $query->from('sds_com_configuracion resp');
        $query->join('left join', 'sds_ent_responsable datos_resp', 'datos_resp.idresponsable=resp.idconfiguracion');
        $query->join('left join', '(SELECT receptor, sum(cantidad - IFNULL(rendidas,0)) saldo
                                    FROM sds_ent_entrega ent
                                    JOIN sds_ent_tipo tipo ON tipo.idtipo= ent.idtipo
                                    LEFT JOIN (SELECT emisor,sum(cantidad) rendidas
                                    FROM sds_ent_entrega entfinal
                                    GROUP BY emisor) tempRend ON tempRend.emisor=ent.identrega
                                    WHERE ent.fecha_hora>=\'' . $this->fecha_deuda . '\' AND receptor IS NOT null
                                    GROUP BY receptor
                                    ORDER BY receptor) temp_deuda', 'temp_deuda.receptor=resp.idconfiguracion');
        $query->where("resp.idconfiguraciontipo=44");
        $query->andFilterWhere([
            'idresponsable' => $this->idresponsable,
            'idorganismoexterno' => $this->idorganismoexterno,
        ]);

        $query->andFilterWhere(['like', 'mail', $this->mail])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'dni', $this->dni]);
        $query->having("responsable like '%" . $this->responsable . "%' and (deudor=" . $this->deudor . " or " . $this->deudor . "=-1)");
        $query->orderBy(["responsable" => SORT_ASC]);

        return $dataProvider;
    }
}
