<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_org_contacto;

/**
 * Mds_org_contactoSearch represents the model behind the search form about `app\models\Mds_org_contacto`.
 */
class Mds_org_contactoSearch extends Mds_org_contacto
{
    public $documento;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['legajo', 'idcontacto', 'iddispositivo', 'idpersona','eventual','interno','perfil','categoria','idorganismo','tipo_contratacion','actividad'], 'integer'],
            [['mail', 'telefono', 'activo', 'rotativo', 'acompaniante', 'idpersona', 'documento', 
            'nombre','ubicacion_fisica','idlocalidad','calle','numero','norma_legal', 'servicio'], 'safe'],
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
        $query = Mds_org_contacto::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //ANOTEZE: el sort es para que se ordene por cada columna y te aparezcan en azules.
            'sort' => [
                'attributes' => [
                    'legajo', 'documento', 'idcontacto', 'nombre', 'idpersona', 'mail','perfil','ubicacion_fisica',
                    'telefono', 'telefono', 'iddispositivo', 'rotativo', 'eventual', 'acompaniante', 'interno', 'activo',
                    'idlocalidad','categoria', 'idorganismo','norma_legal','tipo_contratacion','actividad', 'servicio'
                ],
                'defaultOrder' => ['idpersona' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        //Verifico que el usuario tenga idusuario asignado, caso contrario redirecciono a Login
        $user = Yii::$app->user->identity;
        $idusuario = $user != null ? $user->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }

        $query->addSelect(['c.*', 'concat(p.apellido,\', \',p.nombre) nombre', 'p.documento','p.idlocalidad', 'd.idorganismo'])
        ->from('mds_org_contacto c')
        ->innerJoin('sds_com_persona p', 'c.idpersona=p.idpersona')
        ->innerJoin('mds_org_dispositivo d', 'c.iddispositivo=d.iddispositivo')
        //->innerJoin('mds_org_organismo o', 'o.idorganismo=d.idorganismo')
        ->leftJoin('sds_com_localidad loc', 'loc.idlocalidad=p.idlocalidad')
        ->where('d.idcapaitem in (SELECT idcapaitem FROM mds_seg_usuario_capa_item where idusuario='.$idusuario.')
        or ifnull((SELECT count(idusuario) FROM mds_seg_usuario_capa_item where idusuario='.$idusuario.'), 0) = 0');

        $query->andFilterWhere([
            'c.idcontacto' => $this->idcontacto,
            'c.iddispositivo' => $this->iddispositivo,
            'd.idorganismo' => $this->idorganismo,
            'c.activo' => $this->activo,
            'c.rotativo' => $this->rotativo,
            'c.eventual' => $this->eventual,
            'c.interno' => $this->interno,
            'c.perfil' => $this->perfil,
            'c.actividad' => $this->actividad,
            'c.acompaniante' => $this->acompaniante,
            'c.tipo_contratacion' => $this->tipo_contratacion,
            'c.servicio' => $this->servicio
            
        ]);

        if ($this->idlocalidad==null) {
            $this->idlocalidad=-1;
        }

        if ($this->idorganismo==null) {
            $this->idorganismo=-1;
        }

        $query->andFilterWhere(['like', 'c.mail', $this->mail])
            ->andFilterWhere(['like', 'c.telefono', $this->telefono])
            ->andFilterWhere(['like', 'c.categoria', $this->categoria])
            ->andFilterWhere(['like', 'c.legajo', $this->legajo])
            ->andFilterWhere(['like', 'c.ubicacion_fisica', $this->ubicacion_fisica])
            ->andFilterWhere(['like', 'c.norma_legal', $this->norma_legal])
            //->andWhere("norma_legal like '%" . $this->norma_legal . "%'")
            ->andWhere("documento like '%" . $this->documento . "%'")
            ->andWhere("((loc.idprovincia=58 and " . $this->idlocalidad . "=1) 
                        or (loc.idlocalidad is null and " . $this->idlocalidad . "=0) 
                        or (loc.idlocalidad is not null and loc.idprovincia!=58 and " . $this->idlocalidad . "=2) 
                        or " . $this->idlocalidad . "<0)")
            
            ->having("nombre like '%" . $this->nombre . "%'");


        return $dataProvider;
    }
}
