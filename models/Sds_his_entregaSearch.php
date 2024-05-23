<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_his_entrega;

/**
 * Sds_his_entregaSearch represents the model behind the search form about `app\models\Sds_his_entrega`.
 */
class Sds_his_entregaSearch extends Sds_his_entrega
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['numero_documento'], 'integer'],
            [['fecha', 'servicio', 'destino'], 'safe'],
            [['cantidad'], 'number'],
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
        $query = Sds_his_entrega::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['fecha', 'servicio', 'cantidad', 'destino'],
            ],
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        //Creo la query de las Entregas Histoticas
        $queryHis=(new \yii\db\Query())
        ->select('fecha,servicio,cantidad,destino')
        ->from('sds_his_entrega') 
        ->where('numero_documento='.$this->numero_documento);

        //Creo la query de las Entregas más recientes
        $queryEntregas=(new \yii\db\Query())
        ->select('e.fecha_hora, t.descripcion, e.cantidad, e.observaciones')
        ->from('sds_ent_entrega e')
        ->join('inner join','sds_ent_tipo t', 't.idtipo=e.idtipo')
        ->where('e.dni='.$this->numero_documento);

        //Ralizo la union de las distintas query's
        $query->select('*')
        ->from($queryHis->union($queryEntregas, true))
        ->orderBy('fecha DESC');
        
        return $dataProvider;
    }
}
