<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Inventario;

/**
 * InventarioSearch represents the model behind the search form about `app\models\Inventario`.
 */
class InventarioSearch extends Inventario
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idinventario', 'idestado', 'activo'], 'integer'],
            // Pasamos idarticulo, iddispositivo e idempleado a safe para permitir búsquedas por texto libre
            [['idarticulo', 'iddispositivo', 'idempleado', 'observacion'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    // En app\models\InventarioSearch.php

    public function search($params)
    {
        $query = Inventario::find();

        // Cambiamos 'articulo' por 'idarticulo' (o 'Articulo' según corresponda en tu modelo Inventario)
        $query->joinWith([
            'articulo.idtipo0 ct',  // Alias 'ct' para el tipo de artículo
            'articulo.idmarca0 cm', // Alias 'cm' para la marca de artículo
            'dispositivo d' => function ($q) {
                $q->joinWith([
                    'idoficina0 eo' => function ($q2) {
                        $q2->joinWith(['idedificio0 e']);
                    },
                    'idorganismo0 o' => function ($q3) {
                        $q3->joinWith(['iddecretos od']); // Asumiendo relación con decretos o la tabla directa
                    }
                ]);
            },
            'empleado.persona'
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idinventario' => $this->idinventario,
            'idestado' => $this->idestado,
            'activo' => $this->activo,
        ]);

        $query->andFilterWhere(['like', 'observacion', $this->observacion]);

        // Búsqueda desordenada en Artículo
        if (!empty($this->idarticulo)) {
            $palabrasArticulo = explode(' ', trim($this->idarticulo));
            foreach ($palabrasArticulo as $palabra) {
                if ($palabra !== '') {
                    $query->andWhere([
                        'or',
                        ['like', 'ct.descripcion', $palabra],
                        ['like', 'cm.descripcion', $palabra],
                        ['like', 'articulo.modelo', $palabra],
                        ['like', 'articulo.descripcion', $palabra],
                    ]);
                }
            }
        }

        // Búsqueda desordenada en Dispositivo
        if (!empty($this->iddispositivo)) {
            $palabrasDispositivo = explode(' ', trim($this->iddispositivo));
            foreach ($palabrasDispositivo as $palabra) {
                if ($palabra !== '') {
                    $query->andWhere([
                        'or',
                        ['like', 'd.descripcion', $palabra],
                        ['like', 'eo.descripcion', $palabra],
                        ['like', 'e.descripcion_fija', $palabra],
                        ['like', 'o.abreviatura', $palabra],
                        ['like', 'od.descripcion', $palabra],
                    ]);
                }
            }
        }

        // Búsqueda desordenada en Empleado
        if (!empty($this->idempleado)) {
            $palabrasEmpleado = explode(' ', trim($this->idempleado));
            foreach ($palabrasEmpleado as $palabra) {
                if ($palabra !== '') {
                    $query->andWhere([
                        'or',
                        ['like', 'personas.nombre', $palabra],
                        ['like', 'personas.apellido', $palabra],
                    ]);
                }
            }
        }

        return $dataProvider;
    }
}
