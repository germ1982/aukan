<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StockInformaticaEgreso;

/**
 * StockInformaticaEgresoSearch represents the model behind the search form about `app\models\StockInformaticaEgreso`.
 */
class StockInformaticaEgresoSearch extends StockInformaticaEgreso
{
    public $solicitante; // 👈 1. Campo virtual para buscar por texto (apellido y/o nombre)
    public $autorizacion;
    public $despachante;
    public $receptor;

    public function rules()
    {
        return [
            [['idegreso', 'idpersona_recibe','id_dispositivo_destino'], 'integer'],
            [['fecha', 'observacion', 'fdesde', 'fhasta', 'solicitante', 'autorizacion', 'despachante','receptor'], 'safe'], // 👈 2. Agregamos 'solicitante' a las reglas como atributo seguro
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
        $query = StockInformaticaEgreso::find();
        $query->joinWith(['personaSolicitante persona'])
          ->leftJoin('empleado empleadoAutorizacion', 'stock_informatica_egreso.idempleado_autorizacion = empleadoAutorizacion.idempleado') // Reemplaza por la tabla real de Empleado si es diferente
          ->leftJoin('personas persona_aut', 'empleadoAutorizacion.idpersona = persona_aut.idpersona') // Asegura que idpersona sea la PK de Persona
          ->leftJoin('empleado empleadoDespacha', 'stock_informatica_egreso.idempleado_despacha = empleadoDespacha.idempleado')
          ->leftJoin('personas persona_des', 'empleadoDespacha.idpersona = persona_des.idpersona')
          ->leftJoin('personas persona_rec', 'stock_informatica_egreso.idpersona_recibe = persona_rec.idpersona'); // Aquí para el receptor

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 50], // <-- ¡Aquí cambias la cantidad de registros por página!
            'sort' => [
                'defaultOrder' => [
                    'fecha' => SORT_DESC,
                    'idegreso' => SORT_DESC,
                ],
                'attributes' => [
                    'fecha',
                    'idegreso',
                    'idpersona_recibe',
                    'observacion',
                    'solicitante',
                    'autorizacion',
                    'despachante',
                    'receptor',
                    'id_dispositivo_destino',
                ],

            ],
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
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha,'$fecha_hasta_aux')<=0 ";
        }

        $query->andFilterWhere([
            'idegreso' => $this->idegreso,
            'fecha' => $this->fecha,
            'id_dispositivo_destino' => $this->id_dispositivo_destino
            //'idpersona_solicitante' => $this->idpersona_solicitante,
            //'idempleado_autorizacion' => $this->idempleado_autorizacion,
            //'idempleado_despacha' => $this->idempleado_despacha,
            //'idpersona_recibe' => $this->idpersona_recibe,
        ]);

        $query->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        // 👇 5. Filtro por texto ingresado en el campo virtual 'solicitante'
        if (!empty($this->solicitante)) {
            $query->andWhere([
                'or',
                ['like', 'persona.apellido', $this->solicitante],
                ['like', 'persona.nombre', $this->solicitante],
                ['like', "CONCAT(persona.apellido, ' ', persona.nombre)", $this->solicitante],
            ]);
        }

        // Autorización
        if (!empty($this->autorizacion)) {
            $query->andWhere([
                'or',
                ['like', 'persona_aut.apellido', $this->autorizacion],
                ['like', 'persona_aut.nombre', $this->autorizacion],
                ['like', new \yii\db\Expression("CONCAT(persona_aut.apellido, ' ', persona_aut.nombre)"), $this->autorizacion]


            ]);
        }

        if (!empty($this->despachante)) {
            $query->andWhere([
                'or',
                ['like', 'persona_des.apellido', $this->despachante],
                ['like', 'persona_des.nombre', $this->despachante],
                ['like', new \yii\db\Expression("CONCAT(persona_des.apellido, ' ', persona_des.nombre)"), $this->despachante]
            ]);
        }

        if (!empty($this->receptor)) {
            $query->andWhere([
                'or',
                ['like', 'persona_rec.apellido', $this->receptor],
                ['like', 'persona_rec.nombre', $this->receptor],
                ['like', new \yii\db\Expression("CONCAT(persona_rec.apellido, ' ', persona_rec.nombre)"), $this->receptor]
            ]);
        }

        return $dataProvider;
    }
}
