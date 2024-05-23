<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_hor_ingreso_externo;

/**
 * Mds_hor_ingreso_externoSearch represents the model behind the search form about `app\models\Mds_hor_ingreso_externo`.
 */
class Mds_hor_ingreso_externoSearch extends Mds_hor_ingreso_externo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idingresoexterno', 'idpersona', 'idcontacto'], 'integer'],
            [['fecha_hora', 'observaciones', 'fdesde', 'fhasta', 'fecha_hora_ingreso', 'idorganismo', 'estado', 'contacto'], 'safe'],
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
        $query = Mds_hor_ingreso_externo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['idpersona', 'fecha_hora', 'estado', 'motivo', 'contacto', 'observaciones', 'fecha_hora_ingreso', 'idorganismo'],
                'defaultOrder' => ['fecha_hora' => SORT_DESC]
            ]
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
            $sql_desde = "fecha_hora >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha_hora <= '$fecha_hasta_aux'";
        }

        $if = "IF(isnull(ie.idcontacto) AND isnull(ie.fecha_hora_ingreso), 'Pendiente', IF(isnull(ie.idcontacto) AND not isnull(ie.fecha_hora_ingreso), 'Rechazado','Aceptado')) AS estado";

        $query->select(["ie.*, $if"]);
        $query->addSelect([
            "ifnull((SELECT CONCAT(`p`.`apellido`, ', ',`p`.`nombre`) FROM sds_com_persona p JOIN mds_org_contacto c ON `p`.`idpersona`=`c`.`idpersona` WHERE `c`.`idcontacto`=`ie`.`idcontacto`), 'Sin Asignar') contacto"
        ]);
        // $query->select("p.*, idpersona");
        // $query->addSelect("CONCAT(`p`.`apellido`, ', ', `p`.`nombre`) persona FROM sds_com_persona p ");
        // $query->Select('(SELECT nombre FROM sds_com_persona )persona');
        $query->from("sds_com_persona p");
        $query->from("mds_hor_ingreso_externo ie");

        $query->andFilterWhere([
            'o.motivo' => $this->motivo,
            'ie.idingresoexterno' => $this->idingresoexterno,
            'ie.idpersona' => $this->persona,
            'ie.fecha_hora' => $this->fecha_hora,
            'ie.idcontacto' => $this->contacto != -1 ? $this->contacto : null,
            'ie.idorganismo' => $this->idorganismo,
        ]);

        if ($this->idcontacto == -1) {
            $query->andWhere('isnull(ie.idcontacto)');
        }

        if ($this->fecha_hora_ingreso == -1) {
            $query->andWhere('isnull(ie.fecha_hora_ingreso)');
        }

        $query->andFilterWhere(['like', 'ie.observaciones', $this->observaciones])
            ->andWhere($sql_desde)->andWhere($sql_hasta);
        $query->having("estado like '%$this->estado%'");

        return $dataProvider;
    }
}
