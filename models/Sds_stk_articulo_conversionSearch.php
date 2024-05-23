<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_articulo_conversion;

/**
 * Sds_stk_articulo_conversionSearch represents the model behind the search form about `app\models\Sds_stk_articulo_conversion`.
 */
class Sds_stk_articulo_conversionSearch extends Sds_stk_articulo_conversion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idarticuloconversion', 'articulo_base', 'articulo_convertido'], 'integer'],
            [['descripcion_base', 'descripcion_convertido'], 'string'],
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
        $query = Sds_stk_articulo_conversion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes'=>[
                    'descripcion_base', 
                    'descripcion_convertido'
                    ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /*Se hace un select de la tabla sds_stk_articulo_conversion y se obtienen las descripciones de los articulos */

        $query->select('sds_stk_articulo_conversion.*, ab.descripcion descripcion_base, ac.descripcion descripcion_convertido,')
        ->join('LEFT JOIN', 'sds_stk_articulo ab', 'articulo_base=ab.idarticulo')
        ->join('LEFT JOIN', 'sds_stk_articulo ac', 'articulo_convertido=ac.idarticulo')
        ->where(['ab.organismo'=>Yii::$app->user->identity->organismo_stock]);


        $query->andFilterWhere([
            'idarticuloconversion' => $this->idarticuloconversion,
            'articulo_base' => $this->descripcion_base,
            'articulo_convertido' => $this->descripcion_convertido,
        ]);

        return $dataProvider;
    }
}
