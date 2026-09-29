<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RegistroTecnicoIncidencia;

/**
 * RegistroTecnicoIncidenciaSearch represents the model behind the search form about `app\models\RegistroTecnicoIncidencia`.
 */
class RegistroTecnicoIncidenciaSearch extends RegistroTecnicoIncidencia
{
    /**
     * @inheritdoc
     */
    public $fdesde;
    public $fhasta;

    public function rules()
    {
        return [
            [['idincidencia', 'idregistro', 'idingresante', 'idrecepciona', 'iddispositivo', 'idestado', 'iddespacha', 'idretira'], 'integer'],
            [['fdesde', 'fhasta', 'fecha_ingreso', 'idinventario'], 'safe'],
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
        $query = RegistroTecnicoIncidencia::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Subconsulta para obtener la fecha y la descripción del primer movimiento de cada incidencia
        $primerMovimientoSubquery = (new \yii\db\Query())
            ->select(['m.idincidencia', 'm.fecha', 'm.descripcion'])
            ->from('registro_tecnico_incidencia_movimiento m')
            ->innerJoin(
                '(SELECT idincidencia, MIN(idmovimiento) AS min_idmovimiento FROM registro_tecnico_incidencia_movimiento GROUP BY idincidencia) primer_mov',
                'm.idincidencia = primer_mov.idincidencia AND m.idmovimiento = primer_mov.min_idmovimiento'
            );

        // JOIN con el primer movimiento
        $query->leftJoin(
            ['primer_movimiento' => $primerMovimientoSubquery],
            'primer_movimiento.idincidencia = registro_tecnico_incidencia.idincidencia'
        );

        // JOINs opcionales con Inventario y Articulo para filtrar por la descripción del equipo de inventario
        $query->leftJoin('inventario i', 'i.idinventario = registro_tecnico_incidencia.idinventario AND i.activo = 1')
              ->leftJoin('articulo a', 'a.idarticulo = i.idarticulo')
              ->leftJoin('configuracion ct', 'ct.id_configuracion = a.idtipo')
              ->leftJoin('configuracion cm', 'cm.id_configuracion = a.idmarca')
              ->leftJoin('configuracion cum', 'cum.id_configuracion = a.id_unidad_medida');

        // Filtro de Fechas
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-',$this->fdesde)), 'Y-m-d');
            $query->andWhere(['>=', 'primer_movimiento.fecha',$fecha_desde_aux]);
        }

        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-',$this->fhasta)), 'Y-m-d');
            $query->andWhere(['<=', 'primer_movimiento.fecha',$fecha_hasta_aux]);
        }

        // Filtro tipo LIKE para la columna Equipo (idinventario)
        if (!empty($this->idinventario)) {$query->andWhere([
                'OR',
                ['like', 'primer_movimiento.descripcion', $this->idinventario],
                ['like', "CONCAT(COALESCE(ct.descripcion,''), ' ', COALESCE(cm.descripcion,''), ' ', COALESCE(a.modelo,''), ' ', COALESCE(cum.descripcion,''), ' ', COALESCE(a.descripcion,''))", $this->idinventario],
            ]);
        }

        // Filtros exactos de enteros
        $query->andFilterWhere([
            'registro_tecnico_incidencia.idincidencia' => $this->idincidencia,
            'registro_tecnico_incidencia.idregistro' => $this->idregistro,
            'registro_tecnico_incidencia.idingresante' => $this->idingresante,
            'registro_tecnico_incidencia.idrecepciona' => $this->idrecepciona,
            'registro_tecnico_incidencia.iddispositivo' => $this->iddispositivo,
            'registro_tecnico_incidencia.idestado' => $this->idestado,
            'registro_tecnico_incidencia.iddespacha' => $this->iddespacha,
            'registro_tecnico_incidencia.idretira' => $this->idretira,
        ]);

        return $dataProvider;
    }
}