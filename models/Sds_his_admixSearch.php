<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_his_admix;

/**
 * Sds_his_admixSearch represents the model behind the search form about `app\models\Sds_his_admix`.
 */
class Sds_his_admixSearch extends Sds_his_admix
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documento_numero'], 'integer'],
            [['nombre', 'servicio', 'fecha', 'periodo', 'extracto'], 'safe'],
            [['importe'], 'number'],
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
        $query = Sds_his_admix::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['fecha', 'importe', 'servicio', 'extracto'],
            ],
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //Creo la query de los Subsidios Admix (Historico exportado)
        $queryHis=(new \yii\db\Query())
        ->select ('fecha, importe, servicio, extracto')
        ->from('sds_his_admix')
        ->where('documento_numero='.$this->documento_numero);
        
        //Creo la query de los Subsidios de Desempleo
        $queryDesempleo=(new \yii\db\Query())
        ->select('fecha, monto, CAST("SUBSIDIO DE DESEMPLEO" AS CHAR), programa')
        ->from('mds_por_desempleo')
        ->where('dni='.$this->documento_numero);
        
        //Creo la query de los Subsidios de Familia
        $queryFamilia=(new \yii\db\Query())
        ->select(['CAST(CONCAT(anio,\'-\',mes,\'-01\') AS DATE)', 'importe', 'programa', 'subprograma'])
        ->from ('mds_por_familia')
        ->where('dni='.$this->documento_numero);
        
        //Creo la query de los Subsidios Social Transitorio
        $querySst=(new \yii\db\Query())
        ->select(['CAST(CONCAT(anio,\'-\',mes,\'-01\') AS DATE)', 'monto', 'CAST("SUB. SOCIAL TRANSITORIO" AS CHAR)' , 'CONCAT(tipo,\'-\',destino)'])
        ->from('mds_por_sst')
        ->where('dni='.$this->documento_numero);

        //Ralizo la union de las distintas query's
        $query->select('*')
        ->from($queryHis->union($queryDesempleo, true)->union($queryFamilia, true)->union($querySst, true))
        ->orderBy('fecha DESC');

        return $dataProvider;
    }
}
