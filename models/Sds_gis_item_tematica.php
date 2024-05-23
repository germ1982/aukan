<?php

namespace app\models;
use Yii;

/**
 * This is the model class for table "s".
 *
 * @property int $iditemtematica
 * @property int $iditem
 * @property int $idtematica
 *
 */

class Sds_gis_item_tematica extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'sds_gis_item_tematica';
    }
    public function rules()
    {
        return [
            [['iditem', 'idtematica'], 'required'],
            [[ 'iditem', 'idtematica'], 'integer']
        ];
    }
}