<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_recepcion;

/**
 * Sds_stk_recepcionSearch represents the model behind the search form about `app\models\Sds_stk_recepcion`.
 */
class Sds_stk_recepcionSearch extends Sds_stk_recepcion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idrecepcion', 'proveedor','organismo','idordencompra'], 'integer'],
            [['fecha', 'pedido', 'fdesde', 'fhasta','expediente', 'detalle_items'], 'safe'],
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
        $query = Sds_stk_recepcion::find();

        $dataProvider = new ActiveDataProvider([
            'sort' => [
                'attributes' => ["fecha", "proveedor", "expediente", "pedido","idordencompra", "detalle_items"],
                'defaultOrder' => ['fecha' => SORT_DESC]
            ],
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d');
            $sql_desde = "fecha >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha <= '$fecha_hasta_aux'";
        }

        $consulta_detalle_items = "SELECT  
                                    ifnull((select group_concat(concat(ir.cantidad,' ',a.descripcion)SEPARATOR ' <br> ') detalle 
                                    from sds_stk_recepcion_item ir 
                                    join sds_stk_articulo a on a.idarticulo=ir.idarticulo 
                                    where ir.idrecepcion = e.idrecepcion),'') from sds_stk_recepcion e where e.idrecepcion = sds_stk_recepcion.idrecepcion";

        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);

        $consulta_mostrar = "SELECT 2 as mostrar";//si el usuario no tiene deposito asignado setea mostrar en 2 para no dejar vacia la columna
        $having_mostrar ='';//si el usuario no tiene deposito asignado setea el having vacio para que no haya ninguna seleccion

        if($usuario->iddeposito>0)
        /* Si el usuario tiene  deposito asignado hace la sigueinte consulta, 
        donde compara si la cantidad de items de la entrega es igual a 
        la cantidad de items con ese deposito, si es setea mostrar en 1 para mostrar esa entrega
        si no hubiera items, los contadores darian = por lo que tambien muestra la entrega para poder asignarle items*/
            {
                $consulta_mostrar = "SELECT IF(
                                            (SELECT count(*)
                                            from sds_stk_recepcion_item rri 
                                            join sds_stk_movimiento rm on rri.idrecepcionitem = rm.item_recepcion
                                            where rri.idrecepcion = sds_stk_recepcion.idrecepcion)
                                    = 
                                            (SELECT count(*)
                                            from sds_stk_recepcion_item rri 
                                            join sds_stk_movimiento dm on rri.idrecepcionitem = dm.item_recepcion
                                            where rri.idrecepcion = sds_stk_recepcion.idrecepcion AND dm.deposito_ingreso = $usuario->iddeposito)
                                    ,1,0) as mostrar";

                $having_mostrar = "and mostrar = 1";//Setea el having en 1 para que muestre los iten con mostrar en 1
            }
            
        $query->addSelect([" `sds_stk_recepcion`.*", "($consulta_detalle_items)as detalle_items ", "($consulta_mostrar) as mostrar"]);

        $query->andFilterWhere([
            'idrecepcion' => $this->idrecepcion,
            'fecha' => $this->fecha,
            'proveedor' => $this->proveedor,
            'organismo' => $this->organismo,
            'idordencompra' => $this->idordencompra,

        ]);

        $query->andFilterWhere(['like', 'pedido', $this->pedido])
        ->andFilterWhere(['like', 'expediente', $this->expediente])
        ->andWhere($sql_desde)
        ->andWhere($sql_hasta)
        ->having("detalle_items like '%" . $this->detalle_items . "%'  $having_mostrar");
        return $dataProvider;
    }
}
