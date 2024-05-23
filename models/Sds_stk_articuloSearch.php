<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_articulo;

/**
 * Sds_stk_articuloSearch represents the model behind the search form about `app\models\Sds_stk_articulo`.
 */
class Sds_stk_articuloSearch extends Sds_stk_articulo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'idarticulo',
                    'disponible',
                    'entregado',
                    'ingresado',
                    'unidad_medida',
                    'organismo',
                    'rubro',
                    'orden',
                    'devolucion'
                ],
                'integer',
            ],
            [
                [
                    'descripcion',
                    'activo',
                    'disponible',
                    'entregado',
                    'ingresado',
                    'abreviatura',
                ],
                'safe',
            ],
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
        $query = Sds_stk_articulo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'rubro',
                    'descripcion',
                    'ingresado',
                    'ingresado',
                    'entregado',
                    'disponible',
                    'activo',
                    'orden',
                    'unidad_medida',
                    'devolucion'
                ],
                'defaultOrder' => ['orden' => SORT_ASC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->ingresado == null) {
            $having_ingresado = '(ingresado IS NOT NULL)';
        } else {
            $having_ingresado = '(ingresado=' . $this->ingresado . ')';
        }

        if ($this->entregado == null) {
            $having_entregado = '(entregado IS NOT NULL)';
        } else {
            $having_entregado = '(entregado=' . $this->entregado . ')';
        }

        if ($this->disponible == null) {
            $having_disponible = '(disponible IS NOT NULL)';
        } else {
            $having_disponible = '(disponible=' . $this->disponible . ')';
        }

        /* $mysql_ingresado =
            '(Select COALESCE(SUM(cantidad),0) from sds_stk_recepcion_item i where i.idarticulo = sds_stk_articulo.idarticulo)';
        $mysql_entregado =
            '(Select COALESCE(SUM(cantidad),0) from sds_reg_entrega e where e.idarticulo = sds_stk_articulo.idarticulo)';
        $mysql_disponible = "($mysql_ingresado - $mysql_entregado)"; */

        $query->addSelect(
            "`a`.*, IFNULL(`i`.`ingresado`, 0) `ingresado`, IFNULL(`e`.`entregado`, 0) `entregado`, (IFNULL(`i`.`ingresado`, 0) - IFNULL(`e`.`entregado`, 0)) `disponible`"
            //"$mysql_ingresado as ingresado",
            //"$mysql_entregado as entregado",
            //"$mysql_disponible as disponible",
        )
        ->from("sds_stk_articulo a")
        ->leftJoin("(SELECT idarticulo, SUM(cantidad) ingresado FROM view_stock_detalle WHERE cantidad > 0 GROUP BY idarticulo) i", "i.idarticulo=a.idarticulo")
        ->leftJoin("(SELECT idarticulo, SUM(-cantidad) entregado FROM view_stock_detalle WHERE cantidad < 0 GROUP BY idarticulo) e", "e.idarticulo=a.idarticulo");

        $query->andFilterWhere([
            'idarticulo' => $this->idarticulo,
            'rubro' => $this->rubro,
            'unidad_medida' => $this->unidad_medida,
            'organismo' => $this->organismo,
            'orden' => $this->orden,
            'devolucion' => $this->devolucion,
        ]);

        $query
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'activo', $this->activo]);
            
        /* $query->having(
            $having_entregado .
                ' and ' .
                $having_ingresado .
                ' and ' .
                $having_disponible
        ); */

        return $dataProvider;
    }
}
