<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_org_informe;

/**
 * Mds_org_informeSearch represents the model behind the search form about `app\models\Mds_org_informe`.
 */
class Mds_org_informeSearch extends Mds_org_informe
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idinforme'], 'integer'],
            [['asunto', 'fecha', 'fdesde', 'fhasta', 'visto', 'tipo', 'iddispositivo', 'idusuario', 'compartidos', 'vistos'], 'safe'],
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
        $query = Mds_org_informe::find()->select(['mds_org_informe.idinforme', 'asunto', 'fecha', 'tipo', 'iddispositivo', 'mds_org_informe.idusuario']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idinforme',
                    'asunto',
                    'fecha',
                    'tipo',
                    'apellido',
                    'iddispositivo',
                    'idusuario',
                ],
                'defaultOrder' => ['idinforme' => SORT_DESC]
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
            $sql_desde = "fecha >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha <= '$fecha_hasta_aux'";
        }

        // Obtiene idusuario de quien está logueado, para mostrarle los informes creados por ese usuario
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }

        // Obtener los ids de informes compartidos con el usuario logueado
        $sql_informesIds = 'mds_org_informe.idinforme IS NULL';
        $informes = Mds_org_informe_usuario::findAll(["idusuario" => $idusuario]);
        if (sizeof($informes) > 0) {
            $ids = '';
            foreach ($informes as $informe) {
                $ids = $ids . $informe->idinforme;
                if (next($informes)) {
                    $ids =  $ids . ', ';
                }
            }
            $sql_informesIds = "mds_org_informe.idinforme in ($ids)";
        }

        // Verifica la visualizacion
        $sql_visto = '';
        if ($this->visto != null) {
            $sql_visto = "mds_org_informe.idinforme IN (
                SELECT idinforme
                FROM mds_org_informe_usuario 
                WHERE mds_org_informe_usuario.idusuario = $idusuario 
                AND visto = {$this->visto}
                )";
        }

        if ($this->compartidos) {
            $query->innerJoin('mds_org_informe_usuario compartidos', 'compartidos.idinforme = mds_org_informe.idinforme');
            $query->andFilterWhere(['in', 'compartidos.idusuario', $this->compartidos]);
        }
        
        if ($this->vistos) {
            $query->innerJoin('mds_org_informe_usuario vistos', 'vistos.idinforme = mds_org_informe.idinforme');
            $query->andFilterWhere(['=', 'vistos.visto', Mds_org_informe::VISTO_VALUE]);
            $query->andFilterWhere(['in', 'vistos.idusuario', $this->vistos]);
        }


        $query->andFilterWhere(['mds_org_informe.idinforme' => $this->idinforme])
            ->andFilterWhere(['in', 'tipo', $this->tipo])
            ->andFilterWhere(['in', 'iddispositivo', $this->iddispositivo])
            ->andFilterWhere(['in', 'mds_org_informe.idusuario', $this->idusuario])
            ->andFilterWhere(['like', 'asunto', $this->asunto])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta)
            ->andWhere($sql_informesIds)
            ->andWhere($sql_visto);

        return $dataProvider;
    }

    public function searchEnviados($params)
    {
        $query = Mds_org_informe::find()->select(['mds_org_informe.idinforme', 'asunto', 'fecha', 'tipo', 'iddispositivo', 'mds_org_informe.idusuario']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idinforme',
                    'asunto',
                    'fecha',
                    'tipo',
                    'apellido',
                    'iddispositivo',
                    'idusuario',
                ],
                'defaultOrder' => ['idinforme' => SORT_DESC]
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
            $sql_desde = "fecha >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha <= '$fecha_hasta_aux'";
        }

        // Obtiene idusuario de quien está logueado, para mostrarle los informes creados por ese usuario
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }

        if ($this->compartidos) {
            $query->innerJoin('mds_org_informe_usuario compartidos', 'compartidos.idinforme = mds_org_informe.idinforme');
            $query->andFilterWhere(['in', 'compartidos.idusuario', $this->compartidos]);
        }
        
        if ($this->vistos) {
            $query->innerJoin('mds_org_informe_usuario vistos', 'vistos.idinforme = mds_org_informe.idinforme');
            $query->andFilterWhere(['=', 'vistos.visto', Mds_org_informe::VISTO_VALUE]);
            $query->andFilterWhere(['in', 'vistos.idusuario', $this->vistos]);
        }

        $query->andFilterWhere(['mds_org_informe.idinforme' => $this->idinforme])
            ->andFilterWhere(['=', 'mds_org_informe.idusuario', $idusuario])
            ->andFilterWhere(['in', 'tipo', $this->tipo])
            ->andFilterWhere(['in', 'iddispositivo', $this->iddispositivo])
            ->andFilterWhere(['like', 'asunto', $this->asunto])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);

        return $dataProvider;
    }
}
