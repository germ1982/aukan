<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_stk_movimiento;

/**
 * Sds_stk_movimientoSearch represents the model behind the search form about `app\models\Sds_stk_movimiento`.
 */
class Sds_stk_movimientoSearch extends Sds_stk_movimiento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmovimiento', 'tipo', 'cantidad', 'deposito_ingreso', 'idarticulo', 'deposito_egreso', 'item_recepcion', 'item_entrega', 'organismo'], 'integer'],
            [['fecha_hora', 'fdesde', 'fhasta', 'origen', 'destino', 'organismo'], 'safe'],
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
        $query = Sds_stk_movimiento::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['fecha_hora', 'idarticulo', 'tipo', 'cantidad', 'origen', 'destino', 'organismo'],
                'defaultOrder' => ['fecha_hora' => SORT_DESC]
            ]
        ]);

        $dataProvider->pagination->pageSize = 30;

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha_hora,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha_hora,'$fecha_hasta_aux')<=0 ";
        }
        if ($this->origen == null) {
            $having_origen = '';
            $ban = 0;
        } else {
            $having_origen = '(origen like "%' . $this->origen . '%")';
            $ban = 1;
        }
        if ($this->destino == null) {
            $having_destino = '';
        } else {
            $having_destino = '(destino like "%' . $this->destino . '%")';
            if ($ban == 1) {
                $having_destino = " and $having_destino";
            }
            $ban = 1;
        }
        if ($this->organismo == null) {
            $having_organismo = '';
        } else {
            $having_organismo = '(organismo like "%' . $this->organismo . '%")';
            if ($ban == 1) {
                $having_organismo = " and $having_organismo";
            }
        }
        /* $sql_origen = "SELECT CASE  WHEN M.tipo = 1 THEN (Select R.expediente from sds_stk_recepcion_item RI
                                                            INNER JOIN sds_stk_recepcion R on R.idrecepcion = RI.idrecepcion
                                                            Where RI.idrecepcionitem = M.item_recepcion)
                                    WHEN M.tipo = 2 then (Select D.descripcion from sds_stk_deposito D WHERE D.iddeposito = M.deposito_egreso)
                                    WHEN M.tipo = 3 then (Select DD.descripcion
                                                            from sds_stk_entrega_item DEI
                                                            INNER JOIN sds_stk_recepcion_item DRI on DRI.idrecepcionitem = DEI.recepcion_item
                                                            INNER JOIN sds_stk_movimiento DMO on DMO.item_recepcion = DRI.idrecepcionitem
                                                            INNER JOIN sds_stk_deposito DD on DD.iddeposito = DMO.deposito_ingreso
                                                            WHERE DEI.identregaitem = M.item_entrega)
                                    END AS origen
                        FROM sds_stk_movimiento M
                        Where M.idmovimiento = sds_stk_movimiento.idmovimiento"; */
        $sql_origen = "SELECT CASE  WHEN M.tipo = 1 THEN (Select R.expediente from sds_stk_recepcion_item RI
                                                            INNER JOIN sds_stk_recepcion R on R.idrecepcion = RI.idrecepcion
                                    Where RI.idrecepcionitem = M.item_recepcion)
                                    WHEN M.tipo = 2 then (Select D.descripcion from sds_stk_deposito D WHERE D.iddeposito = M.deposito_egreso)
                                    WHEN M.tipo = 3 then (Select DD.descripcion
                                                            from sds_stk_deposito DD
                                                            inner join sds_stk_movimiento DMO on DMO.deposito_egreso = DD.iddeposito
                                                                WHERE DMO.item_entrega = M.item_entrega)
                                    WHEN M.tipo = 4 THEN (Select D.descripcion from sds_stk_deposito D WHERE D.iddeposito = M.deposito_egreso)
                                    END AS origen
                                    FROM sds_stk_movimiento M
                                    Where M.idmovimiento = sds_stk_movimiento.idmovimiento";
        $sql_destino = "SELECT CASE WHEN DM.tipo = 1 THEN (Select DD.descripcion from sds_stk_deposito DD WHERE DD.iddeposito = DM.deposito_ingreso)
                                    WHEN DM.tipo = 2 then (Select DD2.descripcion from sds_stk_deposito DD2 WHERE DD2.iddeposito = DM.deposito_ingreso)
                                    WHEN DM.tipo = 3 then (Select concat('DNI: ', DP.documento , ' - ' , DP.apellido,', ',DP.nombre) as persona
                                                            from sds_com_persona DP
                                                            INNER JOIN mds_org_contacto DC on DC.idpersona = DP.idpersona
                                                            INNER JOIN sds_stk_entrega DE on DE.idcontacto = DC.idcontacto
                                                            INNER JOIN sds_stk_entrega_item DEI on DEI.identrega = DE.identrega
                                                            WHERE DEI.identregaitem = DM.item_entrega)
                                    WHEN DM.tipo = 4 THEN (Select DD.descripcion from sds_stk_deposito DD WHERE DD.iddeposito = DM.deposito_ingreso)
                                    END AS destino

                            FROM sds_stk_movimiento DM
                            Where DM.idmovimiento = sds_stk_movimiento.idmovimiento";

        //$sql_organismo = "SELECT di.idorganismo as organismo From sds_stk_deposito di where di.iddeposito = sds_stk_movimiento.deposito_ingreso";
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        $usuario = Mds_seg_usuario::findOne($idusuario);
        $id_organismo = $usuario->organismo_stock;
        $sql_organismo = "SELECT IF((SELECT di.idorganismo From sds_stk_deposito di where di.iddeposito = sds_stk_movimiento.deposito_ingreso)=$id_organismo 
                                 or (SELECT de.idorganismo From sds_stk_deposito de where de.iddeposito = sds_stk_movimiento.deposito_egreso)=$id_organismo, 1,0) as organismo";
        /* aca tambien estaria cambiar la consult preguntando si el deposito de ingreso o el de egreso 
        es igual al organismo del usuario devuelva 1, sino 0...  y despues en el filtro del index le pongo que muestre los = a 1*/
        $query->addSelect(['sds_stk_movimiento.*', "($sql_origen) as origen", "($sql_destino) as destino", "a.organismo"]);

        $query->join('inner join', 'sds_stk_articulo a', 'a.idarticulo=sds_stk_movimiento.idarticulo');

        $query->andFilterWhere([
            'idmovimiento' => $this->idmovimiento,
            'tipo' => $this->tipo,
            'cantidad' => $this->cantidad,
            'deposito_ingreso' => $this->deposito_ingreso,
            'sds_stk_movimiento.idarticulo' => $this->idarticulo,
            'deposito_egreso' => $this->deposito_egreso,
            'fecha_hora' => $this->fecha_hora,
            'item_recepcion' => $this->item_recepcion,
            'item_entrega' => $this->item_entrega,
        ])

            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        $query->having($having_origen . $having_destino . $having_organismo);

        return $dataProvider;
    }
}
