<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_ris_persona;

/**
 * Sds_ris_personaSearch represents the model behind the search form about `app\models\Sds_ris_persona`.
 */
class Sds_ris_personaSearch extends Sds_ris_persona
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'idpersonarisneu', 'idpersona', 'idrisneu', 'parentezco', 'situacion_conyugal',
                'escolaridad', 'ultimo_ano_aprobado', 'tipo_establecimiento_educativo', 'vinculo_contractual',
                'trabajo', 'trabajo_tipo', 'trabajo_horas', 'trabajo_dias', 'cobertura_salud',
                'trabajo_porque'
            ], 'integer'],
            [['cud', 'ingreso', 'apellido', 'nombre', 'documento', 'observaciones'], 'safe'],
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
        $query = Sds_ris_persona::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['apellido', 'nombre', 'doc_tipo_num', 'parentezco'],
                'defaultOrder' => ['parentezco' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->addSelect(["rp.idpersonarisneu", "rp.parentezco", "p.apellido", "p.nombre", "concat((select descripcion
                        from sds_com_configuracion where idconfiguraciontipo=14 and idconfiguracion=p.documento_tipo),' ',p.documento) doc_tipo_num"]);
        $query->from("sds_ris_persona rp");
        $query->join("inner join", "sds_com_persona p", "p.idpersona=rp.idpersona");
        $query->where(["idrisneu" => $this->idrisneu]);
        $query->andWhere(["activo" => 1]);

        return $dataProvider;
    }
}
