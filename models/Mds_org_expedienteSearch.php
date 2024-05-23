<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_org_expediente;

/**
 * Mds_org_expedienteSearch represents the model behind the search form about `app\models\Mds_org_expediente`.
 */
class Mds_org_expedienteSearch extends Mds_org_expediente
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idexpediente', 'pedido_numero', 'idorganismo'], 'integer'],
            [['fecha_ingreso', 'expediente', 'gde', 'causante', 'extracto', 'destino', 'fecha_salida','fidesde', 'fihasta','fsdesde', 'fshasta'], 'safe'],
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
        $query = Mds_org_expediente::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $sql_idesde = '';
        $sql_ihasta = '';
        if ($this->fidesde != null) {
            $fecha_idesde_aux = date_format(date_create(str_replace('/', '-', $this->fidesde)), 'Y-m-d');
            $sql_idesde = "DATEDIFF(fecha_ingreso,'$fecha_idesde_aux')>=0 ";
        }
        if ($this->fihasta != null) {
            $fecha_ihasta_aux = date_format(date_create(str_replace('/', '-', $this->fihasta)), 'Y-m-d');
            $sql_ihasta = "DATEDIFF(fecha_ingreso,'$fecha_ihasta_aux')<=0 ";
        }

        $sql_sdesde = '';
        $sql_shasta = '';
        if ($this->fsdesde != null) {
            $fecha_sdesde_aux = date_format(date_create(str_replace('/', '-', $this->fsdesde)), 'Y-m-d');
            $sql_sdesde = "DATEDIFF(fecha_salida,'$fecha_sdesde_aux')>=0 ";
        }
        if ($this->fshasta != null) {
            $fecha_shasta_aux = date_format(date_create(str_replace('/', '-', $this->fshasta)), 'Y-m-d');
            $sql_shasta = "DATEDIFF(fecha_salida,'$fecha_shasta_aux')<=0 ";
        }

        $query->andFilterWhere([
            'idexpediente' => $this->idexpediente,
            'fecha_ingreso' => $this->fecha_ingreso,
            'pedido_numero' => $this->pedido_numero,
            'fecha_salida' => $this->fecha_salida,
            'idorganismo' => $this->idorganismo,
        ]);

        $query->andFilterWhere(['like', 'expediente', $this->expediente])
            ->andFilterWhere(['like', 'gde', $this->gde])
            ->andFilterWhere(['like', 'causante', $this->causante])
            ->andFilterWhere(['like', 'extracto', $this->extracto])
            ->andFilterWhere(['like', 'destino', $this->destino])
            ->andWhere($sql_idesde)
            ->andWhere($sql_ihasta)
            ->andWhere($sql_sdesde)
            ->andWhere($sql_shasta);

        return $dataProvider;
    }
}
