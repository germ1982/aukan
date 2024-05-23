<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_ent_entrega;

/**
 * Sds_ent_entregaSearch represents the model behind the search form about `app\models\Sds_ent_entrega`.
 */
class Sds_ent_entregaSearch extends Sds_ent_entrega
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identrega', 'cantidad', 'dni', 'idtipo', 'idusuario', 'emisor', 'receptor', 'proveedor','interior'], 'integer'],
            [[
                'fecha_hora', 'observaciones', 'dni_frente', 'dni_dorso', 'fdesde', 'fhasta', 'estado',
                'estado_acta', 'entidad', 'oc', 'nombre_receptor', 'nombre_emisor', 'estado_cierre'
            ], 'safe'],
            [['latitud', 'longitud'], 'number'],
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
        $query = Sds_ent_entrega::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'dni', 'cantidad', 'idtipo', 'observaciones', 'idusuario', 'estado', 'receptor',
                    'nombre_receptor', 'emisor', 'nombre_emisor', 'fecha_hora', 'estado_cierre', 'entidad', 'estado_acta'
                ],
                'defaultOrder' => ['fecha_hora' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $deudores = false;
        if ($this->estado == Sds_ent_entrega::ESTADO_DEUDOR) {
            $deudores = true;
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
        if ($this->estado == null) {
            $this->estado = Sds_ent_entrega::ESTADO_FINAL;
        }
        if ($this->estado_acta == null) {
            $this->estado_acta = -1;
        }
        if ($this->estado_cierre == null) {
            $this->estado_cierre = -1;
        }
        $user = Yii::$app->user->identity;
        $idusuario = $user != null ? $user->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }

        $query->addSelect([
            "(SELECT externo FROM mds_seg_usuario usu where usu.idusuario=entrega.idusuario) entidad", "entrega.identrega",
            "fecha_hora", "cantidad", "dni", "idtipo", "idusuario", "concat(if(numero is not null,concat('<b>N° </b>',numero,'<br>'),''),observaciones) observaciones",
            "emisor", "ifnull((select concat(entrega.emisor,' - ',
            DATE_FORMAT(ent.fecha_hora,'%d/%m/%Y %H:%i'),' - ',
            (select descripcion from sds_com_configuracion conf where conf.idconfiguracion=ent.receptor),
            ' - Cant.: ', ent.cantidad)
            from sds_ent_entrega ent where ent.identrega=entrega.emisor),'Primer Ingreso') nombre_emisor", "receptor",
            "(select descripcion from sds_com_configuracion conf where conf.idconfiguracion=entrega.receptor) nombre_receptor",
            "proveedor", "oc", "if(acta is not null,1,0) estado_acta",
            ($this->estado != Sds_ent_entrega::ESTADO_FINAL ? "if(ifnull(saldo,cantidad)=0, 2, if(fecha_cierre is not null,1,0))" : "if(fecha_cierre is not null,1,0)") . " estado_cierre",
            ($this->estado != Sds_ent_entrega::ESTADO_FINAL ? "ifnull(saldo,cantidad)" : "cantidad") . " saldo", 'fecha_cierre', 'numero_desde', 'numero_hasta'
        ]);
        $query->from('sds_ent_entrega entrega');
        if ($deudores || $this->estado != Sds_ent_entrega::ESTADO_FINAL) {
            $query->join('left join', '(SELECT ent.identrega, sum(cantidad - IFNULL(rendidas,0)) saldo
                                    FROM sds_ent_entrega ent
                                    JOIN sds_ent_tipo tipo ON tipo.idtipo= ent.idtipo
                                    LEFT JOIN (SELECT emisor,sum(cantidad) rendidas
                                    FROM sds_ent_entrega entfinal
                                    GROUP BY emisor) tempRend ON tempRend.emisor=ent.identrega                                    
                                    GROUP BY ent.identrega
                                    ORDER BY receptor) temp_deuda', 'temp_deuda.identrega=entrega.identrega');
        }
        $query->andFilterWhere([
            'fecha_hora' => $this->fecha_hora,
            'cantidad' => $this->cantidad,
            'dni' => $this->dni,
            'idtipo' => $this->idtipo,
            'idusuario' => $this->idusuario,
        ]);
        $query->andFilterWhere([
            'like', 'entrega.identrega', $this->identrega
        ]);
        $query->andFilterWhere(['like', 'concat(ifnull(numero,\'\'),observaciones)', $this->observaciones])
            ->andWhere($sql_desde)->andWhere($sql_hasta)
            ->andWhere(" ((" . $this->estado . "!=" . Sds_ent_entrega::ESTADO_FINAL . " and dni is null) 
                        or (" . $this->estado . "=" . Sds_ent_entrega::ESTADO_FINAL . " and dni is not null)) ");
        if ($deudores) {
            $query->andWhere("DATEDIFF(curdate(),entrega.fecha_hora)>14");
            $query->having('saldo>0');
        } else {
            if ($this->estado != Sds_ent_entrega::ESTADO_INICIAL) {
                $permiso_todas = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=" . $user->idusuario . ")
                                                and (iditem=" . Mds_seg_item::MODULO_ENT_VER_TODAS . ")")->one();
                if ($permiso_todas == null) {
                    $query->andWhere("(entrega.emisor in (select identrega from sds_ent_entrega ent where ent.receptor = 
                                    (select responsable from mds_seg_usuario where idusuario=" . $user->idusuario . ")))");
                }
            }
            if ($this->estado == Sds_ent_entrega::ESTADO_FINAL) {
                $query->having('entidad=' . $user->externo . ' and (estado_acta=' . $this->estado_acta . ' or 0>' . $this->estado_acta . ')');
            } elseif ($this->estado == Sds_ent_entrega::ESTADO_INICIAL) {
                $query->having('emisor is null');
            } else {
                $query->having('emisor is not null');
            }
        }
        $query->andHaving($this->estado != Sds_ent_entrega::ESTADO_FINAL ? "nombre_receptor like '%" . $this->nombre_receptor . "%' " : "");
        $query->andHaving($this->estado != Sds_ent_entrega::ESTADO_INICIAL ? "nombre_emisor like '%" . $this->nombre_emisor . "%'" : "");
        $query->andHaving("(estado_cierre=" . $this->estado_cierre . " or " . $this->estado_cierre . "<0)");

        return $dataProvider;
    }
}
