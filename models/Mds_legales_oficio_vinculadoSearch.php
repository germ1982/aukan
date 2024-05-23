<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_legales_oficio_vinculado;
use Yii;

/**
 * Mds_legales_oficio_vinculadoSearch represents the model behind the search form of `app\models\Mds_legales_oficio_vinculado`.
 */
class Mds_legales_oficio_vinculadoSearch extends Mds_legales_oficio_vinculado
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'idlegalesoficiovinculado', 'idlegalesoficio', 'idpersona', 'idparentesco', 'idtipodocumento', 'documento', 'apellido', 'nombre', 'domicilio', 'telefono', 'auditoria', 'observaciones', 'mail'
            ], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Mds_legales_oficio_vinculado::find();
        $usuarioAuth = Yii::$app->user->identity;

        $hasRolAdminGeneral = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_ADMIN_GENERAL);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idlegalesoficiovinculado' => SORT_ASC]],
        ]);

        $this->load($params);

        if ($hasRolAdminGeneral) {
            if ($this->deleted_at === '0') {
                $query->andWhere(['not', ['deleted_at' => null]]);
            } else if ($this->deleted_at === '1') {
                $query->andWhere(['deleted_at' => null]);
            }
        } else {
            $query->andWhere(['deleted_at' => null]);
        }

        $query->joinWith('persona');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }
        $query->andFilterWhere(['idlegalesoficio' => $this->idlegalesoficio]);

        if ($this->idpersona) {
            $query->andWhere([
                'or',
                ['like', 'sds_com_persona.documento', $this->idpersona],
                ['like', 'sds_com_persona.nombre', $this->idpersona],
                ['like', 'sds_com_persona.apellido', $this->idpersona]
            ]);
        };

        return $dataProvider;
    }
}
function armarDateParaMySql($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $anio = substr($fecha, 6, 4);
    $mes  = substr($fecha, 3, 2);
    $dia = substr($fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}
