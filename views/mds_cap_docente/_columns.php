<?php
use app\models\Mds_cap_instancia;
use app\models\Sds_com_configuracion;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_persona;
use yii\helpers\Html;

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
$idcontacto  = Yii::$app->user->identity->idcontacto;

return [
    /*[
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'iddocente',
        'label' => 'Nro.Docente',
        'width' => '5%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idpersona',
        'width' => '5%',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idpersona',
        'label' => 'Nombre y Apellido',
        'width' => '30%',
        'value' => function ($model) {
            $persona = $model->idpersona;
            if ($persona != null) {
                $persona = Sds_com_persona::findOne($persona);
                return $persona->nombre . " " . $persona->apellido;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_persona::find()->where("idpersona in (select idpersona from mds_cap_docente)")->orderBy(['nombre' => SORT_ASC])->all(),
            'idpersona',
            function ($model){
                $per = Sds_com_persona::findOne($model->idpersona);
                return $per->nombre . " " . $per->apellido;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Persona...'],
        'format' => 'raw',                
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'email',
        'label' => 'Email',
        'width' => '30%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'telefono',
        'label' => 'Teléfono',
        'width' => '30%',
    ],
    /*[
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'firma',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'firma_digital',
        'label' => 'Firma',
        'value' => function ($model) {
            $firma_digital = $model->firma_digital;
            switch ($firma_digital) {
                case 0:
                    return "NO";
                case 1:
                    return "SI";                
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ['0' => 'NO', '1' => 'SI'],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Firma Digital?'],
        'format' => 'raw',
        'width' => '10%',        
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'profesion_corta',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'cargo_certificado',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' =>  ($idusuario==98 ? '{migrar} ' : '').'{view} {update}',       
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 

        'buttons' => [        
                            'migrar' => function ($url, $model) {
                                $url =  Url::to(['/mds_cap_docente/migrarfirma']);
                                return Html::a('<span class= "fab fa-angellist"></span>', $url, [
                                    'role' => 'modal-remote', 'title' => 'migrar firmas',
                                    'data-confirm' => false, 'data-method' => false,
                                    'data-request-method' => 'post',
                                    'data-toggle' => 'tooltip',
                                ]);
                            },        
                        ],
    ],
    


];   