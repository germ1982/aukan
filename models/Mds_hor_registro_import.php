<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_hor_registro".
 *
 * @property int $idregistrohorario
 * @property int $idcontacto
 * @property string $fecha
 * @property int $origen 0=Importado; 1=Manual
 * @property string|null $observaciones
 * @property int $activo
 *
 * @property MdsOrgContacto $idcontacto0
 */
class Mds_hor_registro_import extends Mds_hor_registro
{
    public $archivo_txt;
    public $archivo_xls;    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['archivo_txt','archivo_xls'], 'safe'],            
        ];
    }
}
