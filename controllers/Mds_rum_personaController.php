<?php

namespace app\controllers;

use Yii;
use app\models\Mds_rum_persona;
use app\models\Sds_com_persona;
use app\models\Mds_seg_usuario;
use app\models\Mds_seg_usuarioSearch;


use app\models\Mds_rum_personaSearch;
use app\models\Mds_rum_filtro;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use kartik\mpdf\Pdf;
use app\models\Mds_sys_log;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\components\AccessRule;
use app\models\Mds_seg_item;
/**
 * Mds_rum_personaController implements the CRUD actions for Mds_rum_persona model.
 */
function write_to_console($data) {
    $console = $data;
  if (is_array($console))
  {
    $console = 'es un array '.implode(',', $console);

  }
   
  echo "<script>console.log('Console: " . $console . "' );</script>";
 }

class Mds_rum_personaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index', 'view','create', 'update', 'delete','mi_cv2','filtrar_datos4','reenviar_confirmacion' ],
                'rules' => [
                    [
                        'actions' =>['index', 'view','create', 'update', 'delete','mi_cv2','filtrar_datos4','reenviar_confirmacion' ],
                        'allow' => true,
                        // Solo Admin Rumbo
                        'roles' => [
                            Mds_seg_item::MODULO_RUM_CV,                           
                        ],
                    ],
                ],
            ],
        ];
    }


    /**
     * Lists all Mds_rum_persona models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new Mds_rum_personaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_persona', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Mds_rum_persona model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_persona', $id, array());
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Ver  Curriculum",
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                            //Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }
    public function actionMi_cv2($id_seg_usuario)// se llama desde la web rumbo para generar un cv en pdf . Ha que ver la posibilidad de poner un token
    {
        Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_persona/mi_cv2', $id_seg_usuario, array());
        $content = $this->renderPartial('mi_cv2', ['id_seg_usuario' => $id_seg_usuario]); 
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'defaultFontSize' => 12,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'methods' => [
                'SetTitle' => 'ACTA DE ENTREGA',
                'SetHeader' => null,
                'SetFooter' => null,
            ]
        ]);

        return $pdf->render();
    }

    /**
     * Creates a new Mds_rum_persona model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Mds_rum_persona();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Mds_rum_persona",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_persona', $model->id, $model->getAttributes());
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Mds_rum_persona",
                    'content'=>'<span class="text-success">Create Mds_rum_persona success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Mds_rum_persona",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_NUEVO, 'mds_rum_persona', $model->id, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Mds_rum_persona model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Mds_rum_persona #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_persona', $model->id, $model->getAttributes());
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Mds_rum_persona #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Mds_rum_persona #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                Mds_sys_log::guardarLog(Mds_sys_log::ACCION_EDITAR, 'mds_rum_persona', $model->id, $model->getAttributes());
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }
    /*public function actionFiltrar_datos3($dni,$nombre,$apellido,$genero,$provincia,$localidad,$desde,$hasta,$sanitaria,$fondo,$dispviajar,$vehiculo,
    $civil,$conducir,$detallehab,$disphor,$culmino,$cadfil,$capfil,$labfil)
    {

      $model_persona=Mds_rum_persona::findBySql("select * from mds_rum_persona where (id= 2320)");
      //$searchModel = new Mds_rum_personaSearch();
      //$searchModel = new Mds_rum_personaSearch();
      //$searchModel =Mds_rum_personaSearch::findBySql("select * from mds_rum_persona where id= 2320");
      //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      //print_r($dataProvider);
      $query=Mds_rum_persona::findBySql("select * from mds_rum_persona where (id= 2320 or id=2406)");     
      $dataProvider = new ActiveDataProvider(['query' => $query]);
      $searchModel = new Mds_rum_personaSearch();        
      $searchModel->id = '2320';

      //print_r($dataProvider);
           //Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_persona', null, array());
           return $this->render('index', [
               'searchModel' => $searchModel,
               'dataProvider' => $dataProvider,
           ]);
   
   
          
       }*/
    public function actionFiltrar_datos4()
    {
      $model=new Mds_rum_filtro;
      $model->load(Yii::$app->request->post());

      $where_com_persona="";
      $where_rum_persona="";
      $where_info_comp="";
      $where_formacion="";
      $datos_sds_com_persona=false;
      $datos_mds_rum_persona=false;
      $datos_info_comp=false;
      $datos_formacion=false;
      $datos_capacitacion=false;
      $where_capacitacion="";

      $datos_laborales=false;
      $where_laboral="";


      $todos=true;
      $dni=trim($model->dni);
      
      if ($dni!='') 
      {$where_com_persona.=' documento='.$dni;$datos_sds_com_persona=true;} 
      
      $nombre=strtoupper(trim($model->nombre)); 
      if ($nombre!='') 
      { if ($datos_sds_com_persona==true)
        {$where_com_persona.=' and UPPER(nombre) like "%'.$nombre.'%" ';}
        else { $where_com_persona.=' UPPER(nombre) like "%'.$nombre.'%" ';$datos_sds_com_persona=true; }                     
        
      }
      $apellido=strtoupper(trim($model->apellido)); 
      if ($apellido!='') 
      { if ($datos_sds_com_persona==true)
        {$where_com_persona.=' and UPPER(apellido) like "%'.$apellido.'%" ';}
        else { $where_com_persona.=' UPPER(apellido) like "%'.$apellido.'%" ';$datos_sds_com_persona=true;}        
      }
      $genero=trim($model->genero);            
      if ($genero!='') 
      { if ($datos_sds_com_persona==true)
        {$where_com_persona.=' and genero='.$genero;}
        else { $where_com_persona.=' genero='.$genero;$datos_sds_com_persona=true;}        
      }

      $edad_desde=trim($model->edad_desde);     
      $edad_hasta=trim($model->edad_hasta);   
      if (($edad_desde=='') && ($edad_hasta==''))
      { }  
      else
      { 
        if (($edad_desde=='') && ($edad_hasta!=''))// se busca por la edad edad_hasta
        {
          if ($datos_sds_com_persona==true)
          {$where_com_persona.=' and TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE())='.$edad_hasta;}
          else { $where_com_persona.=' TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE())='.$edad_hasta;$datos_sds_com_persona=true;}  
        }
        else
        {  
          if (($edad_desde!='') && ($edad_hasta==''))// se busca por la edad edad_desde
          {

            if ($datos_sds_com_persona==true)
            {$where_com_persona.=' and TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE())='.$edad_desde;}
            else { $where_com_persona.=' TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE())='.$edad_desde;$datos_sds_com_persona=true;}  
          }
          else
          {
            if ($edad_desde <= $edad_hasta)
            {
              if ($datos_sds_com_persona==true)
              {$where_com_persona.=' and TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE()) >='.$edad_desde.' and TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE()) <='.$edad_hasta;}
              else { $where_com_persona.=' TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE()) >='.$edad_desde.' and TIMESTAMPDIFF(YEAR,fecha_nacimiento,CURDATE()) <='.$edad_hasta;$datos_sds_com_persona=true;}  
            }
            else
            {}
          }
        }
      }      
      //iddomicilio
      
      $id_localidad=trim($model->id_localidad);
     // print_r($id_localidad);
      if ($id_localidad!='')
      {
        if ($datos_mds_rum_persona==true)
        {$where_rum_persona.=' and iddomicilio in (select id from mds_rum_domicilio where idlocalidad='.$id_localidad.')';}
        else { $where_rum_persona.=' iddomicilio in (select id from mds_rum_domicilio where idlocalidad='.$id_localidad.')'; $datos_mds_rum_persona=true;} 
      }
//print_r($where_rum_persona);
      $estado_civil=trim($model->estado_civil);            
      if ($estado_civil!='') 
      { if ($datos_mds_rum_persona==true)
        {$where_rum_persona.=' and idestadocivil='.$estado_civil;}
        else { $where_rum_persona.=' idestadocivil='.$estado_civil;$datos_mds_rum_persona=true;}        
      }

      $libreta_san=trim($model->libreta_san); 
      if ($libreta_san!='') 
      {          
            if ($datos_info_comp==true)
            {$where_info_comp.=' and libsanitaria='.$libreta_san;}
            else { $where_info_comp.=' libsanitaria='.$libreta_san;$datos_info_comp=true;}        
      }

      if ($model->tienelicconducir)
      {
          $licencias=$model->licencias;           
          if (is_array($licencias))
          {
            $licencias = implode(',', $licencias);    
          }
        
          if ($licencias!='') 
          {         
                $in_licencias=' ('.$licencias.') '; 
                
                if ($datos_info_comp==true)
                {$where_info_comp.=' and idlicconducir in '.$in_licencias;}
                else { $where_info_comp.=' idlicconducir in '.$in_licencias;$datos_info_comp=true;}        
          }
          
      }
      else
      {

      }
      

      $libreta_fondo=trim($model->libreta_fondo); 
      if ($libreta_fondo!='') 
      {          
            if ($datos_info_comp==true)
            {$where_info_comp.=' and tienelibretaconstruct='.$libreta_fondo;}
            else { $where_info_comp.=' tienelibretaconstruct='.$libreta_fondo;$datos_info_comp=true;}        
      }
      
      $disp_viaje=trim($model->disp_viaje); 
      if ($disp_viaje!='') 
      {          
            if ($datos_info_comp==true)
            {$where_info_comp.=' and disponibilidadviaje='.$disp_viaje;}
            else { $where_info_comp.=' disponibilidadviaje='.$disp_viaje;$datos_info_comp=true;}        
      }
      
      $veh_prop=trim($model->veh_prop); 
      if ($veh_prop!='') 
      {          
            if ($datos_info_comp==true)
            {$where_info_comp.=' and vehiculopropio='.$veh_prop;}
            else { $where_info_comp.=' vehiculopropio='.$veh_prop;$datos_info_comp=true;}        
      }
      
      $disp_hor=trim($model->disp_hor); 
      if ($disp_hor!='') 
      {          
            if ($datos_info_comp==true)
            {$where_info_comp.=' and iddisphoraria='.$disp_hor;}
            else { $where_info_comp.=' iddisphoraria='.$disp_hor;$datos_info_comp=true;}        
      }
      
      $tienelicconducir=trim($model->tienelicconducir); 
      if ($tienelicconducir!='') 
      {          
            if ($datos_info_comp==true)
            {$where_info_comp.=' and tienelicconducir='.$tienelicconducir;}
            else { $where_info_comp.=' tienelicconducir='.$tienelicconducir;$datos_info_comp=true;}        
      }
      
      $habilidades=strtoupper(trim($model->habilidades)); 
      if ($habilidades!='') 
      { if ($datos_info_comp==true)
        {$where_info_comp.=' and UPPER(habilidades) like "%'.$habilidades.'%" ';}
        else { $where_info_comp.=' UPPER(habilidades) like "%'.$habilidades.'%" ';$datos_info_comp=true; }                     
        
      }

      $nivel_institucion=trim($model->nivel_institucion); 
      if ($nivel_institucion!='') 
      { if ($datos_formacion==true)
        {$where_formacion.=' and nivel='.$nivel_institucion.' ';}
        else { $where_formacion.=' nivel='.$nivel_institucion.' ';$datos_formacion=true; }                     
        
      }
      $culmino_formacion=trim($model->culmino_formacion); 
      if ($culmino_formacion!='') 
      { if ($datos_formacion==true)
        {$where_formacion.=' and culmino='.$culmino_formacion.' ';}
        else { $where_formacion.=' culmino='.$culmino_formacion.' ';$datos_formacion=true; }                     
        
      }
      
      $filtro_formacion=strtoupper(trim($model->filtro_formacion)); 
      if ($filtro_formacion!='') 
      { if ($datos_formacion==true)
        {$where_formacion.=' and (UPPER(detalle) like "%'.$filtro_formacion.'%"  OR UPPER(observacion) like "%'.$filtro_formacion.'%"  OR UPPER(nombre_instituto) like  "%'.$filtro_formacion.'%" ) ';}
        else { $where_formacion.=' (UPPER(detalle) like "%'.$filtro_formacion.'%" OR UPPER(observacion) like "%'.$filtro_formacion.'%"  OR UPPER(nombre_instituto) like  "%'.$filtro_formacion.'%" ) ';$datos_formacion=true; }                     
        
      }

   
      $filtro_capacitacion=strtoupper(trim($model->filtro_capacitacion)); 
      if ($filtro_capacitacion!='') 
      { if ($datos_capacitacion==true)
        {$where_capacitacion.=' and (UPPER(nombrecap) like "%'.$filtro_capacitacion.'%"  OR UPPER(lugarcapacitacion) like "%'.$filtro_capacitacion.'%"  OR UPPER(organizador) like  "%'.$filtro_capacitacion.'%" OR UPPER(descripcion) like  "%'.$filtro_capacitacion.'%" ) ';}
        else { $where_capacitacion.=' (UPPER(nombrecap) like "%'.$filtro_capacitacion.'%" OR UPPER(lugarcapacitacion) like "%'.$filtro_capacitacion.'%"  OR UPPER(organizador) like  "%'.$filtro_capacitacion.'%" OR UPPER(descripcion) like  "%'.$filtro_capacitacion.'%" ) ';$datos_capacitacion=true; }                     
        
      }
      
      $filtro_laboral=strtoupper(trim($model->filtro_laboral)); 
      if ($filtro_laboral!='') 
      { if ($datos_laborales==true)
        {$where_laboral.=' and (UPPER(puesto) like "%'.$filtro_laboral.'%"  OR UPPER(entidad) like "%'.$filtro_laboral.'%"  OR UPPER(periodo) like  "%'.$filtro_laboral.'%" OR UPPER(descripcion) like  "%'.$filtro_laboral.'%" ) ';}
        else { $where_laboral.=' (UPPER(puesto) like "%'.$filtro_laboral.'%" OR UPPER(entidad) like "%'.$filtro_laboral.'%"  OR UPPER(periodo) like  "%'.$filtro_laboral.'%" OR UPPER(descripcion) like  "%'.$filtro_laboral.'%" ) ';$datos_laborales=true; }                     
        
      }

    
//print_r($where_capacitacion);

      if ($datos_mds_rum_persona)
      {
          if ($datos_sds_com_persona)
          {    
              if ($datos_info_comp) 
              {
                  if ($datos_formacion) 
                  { 
                      if ($datos_capacitacion) 
                      {
                        if ($datos_laborales) 
                        {
                          $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.") and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")";
                          $todos=false;
                        }
                        else
                        {
                          $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                          $todos=false;
                        }
                        
                        
                      }
                      else
                      {
                        if ($datos_laborales) 
                        {
                          $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                          $todos=false;
                        }
                        else
                        {
                          $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")";
                          $todos=false;
                        }
                        
                      }
                    
                  }
                  else
                  {
                    if ($datos_capacitacion) 
                    {
                      if ($datos_laborales)
                      {
                        $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.") and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")   and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                        $todos=false;
                      }
                      else
                      {
                        $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.") and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                        $todos=false;
                      }
                      
                    }
                    else
                    {
                      if ($datos_laborales)
                      {
                        $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                        $todos=false;
                      }
                      else
                      {
                        $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")";
                        $todos=false;
                      }
                      
                    }
                    
                  }                
              }
              else
              {
                if ($datos_formacion) 
                {
                  if ($datos_capacitacion) 
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false; 
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                      $todos=false; 
                    }
                    
                  }
                  else
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false;
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")";
                      $todos=false;
                    }
                     
                  }
                  
                }                  
                else
                {
                  if ($datos_capacitacion) 
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false; 
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                      $todos=false; 
                    }
                    

                  }
                  else
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false; 
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")";
                      $todos=false; 
                    }
                    
                  }
                  
                }                               
              }                                        
          }
          else
          {
              if ($datos_info_comp) 
              {
                if ($datos_formacion)
                {
                    if ($datos_capacitacion) 
                    {
                      if ($datos_laborales)
                      {
                        $consulta="select * from mds_rum_persona where ".$where_rum_persona." and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")  and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                        $todos=false;
                      }
                      else
                      {
                        $consulta="select * from mds_rum_persona where ".$where_rum_persona." and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")  and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                        $todos=false;
                      }
                      
                    }
                    else
                    {
                      if ($datos_laborales)
                      {
                        $consulta="select * from mds_rum_persona where ".$where_rum_persona." and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")  and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                        $todos=false;
                      }
                      else
                      {
                        $consulta="select * from mds_rum_persona where ".$where_rum_persona." and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")  and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")";
                        $todos=false;
                      }                      
                    }
                  
                }
                else
                {
                  if ($datos_capacitacion) 
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false;
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                      $todos=false;
                    }
                    
                  }
                  else
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false;
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")";
                      $todos=false;
                    }
                    
                  }
                  
                }
                
              }
              else
              {
                
                if ($datos_formacion)
                {
                  if ($datos_capacitacion)
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false;
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                      $todos=false;
                    }
                    
                  }
                  else
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false;

                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")";
                      $todos=false;
                    }
                    
                  }
                  
                }
                else
                {
                  if ($datos_capacitacion)
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false;
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona." and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                      $todos=false;
                    }
                    
                  }
                  else
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona."  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false;
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where ".$where_rum_persona;
                      $todos=false;
                    }
                    
                  }
                  
                }                
              }

                 
          }
      }
      else
      { 
        if ($datos_sds_com_persona)
        {     
              if ($datos_info_comp) 
              {
                  if ($datos_formacion)
                  {
                    if ($datos_capacitacion)
                    {
                      if ($datos_laborales)
                      {
                        $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")  and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")  and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                        $todos=false;
                      }
                      else
                      {
                        $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")  and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")  and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                        $todos=false;
                      }
                      
                    }
                    else
                    {
                      if ($datos_laborales)
                      {
                        $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")  and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")  and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                        $todos=false; 
                      }
                      else
                      {
                        $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")  and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")  and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")";
                        $todos=false; 

                      }
                      
                    }
                    
                  }
                  else
                  {
                    if ($datos_capacitacion)
                    {
                      if ($datos_laborales)
                      {
                        $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")  and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                        $todos=false; 
                      }
                      else
                      {
                        $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")  and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                        $todos=false; 
                      }
                      
                    }
                    else
                    {
                      if ($datos_laborales)
                      {
                        $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")  and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                        $todos=false; 
                      }
                      else
                      {
                        $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")  and iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")";
                        $todos=false; 
                      }
                     

                    }
                    
                  }                
              }
              else
              {
                if ($datos_formacion)
                {
                  if ($datos_capacitacion)
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false; 
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                      $todos=false; 
                    }
                    
                  }
                  else
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false; 
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")";
                      $todos=false; 
                    }
                    
                  }
                  
                }
                else
                {
                  if ($datos_capacitacion)
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false;
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                      $todos=false;
                    }
                    
                  }
                  else
                  {
                    if ($datos_laborales)
                    {
                      $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                      $todos=false;
                    }
                    else
                    {
                      $consulta="select * from mds_rum_persona where id_com_persona in (select idpersona from sds_com_persona where ".$where_com_persona.")";
                      $todos=false;
                    }
                    
                  }
                   
                }                
              }
                                          
        }
        else
        {
          if ($datos_info_comp) 
          {
            if ($datos_formacion)
            {
              if ($datos_capacitacion)
              {
                if ($datos_laborales)
                {
                  $consulta="select * from mds_rum_persona where  iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                  $todos=false;
                }
                else
                {
                  $consulta="select * from mds_rum_persona where  iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                  $todos=false;
                }
                
              }
              else
              {
                if ($datos_laborales)
                {
                  $consulta="select * from mds_rum_persona where  iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                  $todos=false;
                }
                else
                {
                  $consulta="select * from mds_rum_persona where  iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.") and id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")";
                  $todos=false;
                }
                
              }
               
            }
            else
            {
              if ($datos_capacitacion)
              {
                if ($datos_laborales)
                {
                  $consulta="select * from mds_rum_persona where  iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                  $todos=false; 
                }
                else
                {
                  $consulta="select * from mds_rum_persona where  iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                  $todos=false; 
                }
                
              }
              else
              {
                if ($datos_laborales)
                {
                  $consulta="select * from mds_rum_persona where  iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                  $todos=false; 
                }
                else
                {
                  $consulta="select * from mds_rum_persona where  iddocadicional>0 and iddocadicional in (select id from mds_rum_docadicional where ".$where_info_comp.")";
                  $todos=false; 
                }
                
              }
             
            }
            

          }
          else
          {
            if ($datos_formacion)
            {
              if ($datos_capacitacion)
              {
                if ($datos_laborales)
                {
                  $consulta="select * from mds_rum_persona where  id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                  $todos=false; 
                }
                else
                {
                  $consulta="select * from mds_rum_persona where  id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")   and id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                  $todos=false; 
                }
                
              }
              else
              {
                if ($datos_laborales)
                {
                  $consulta="select * from mds_rum_persona where  id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                  $todos=false; 
                }
                else
                {
                  $consulta="select * from mds_rum_persona where  id in (select idpersona from mds_rum_formacionacademica where ".$where_formacion.")";
                  $todos=false; 
                }
                
              }
              
            }
            else
            {
              if ($datos_capacitacion)
              {
                if ($datos_laborales)
                {
                  $consulta="select * from mds_rum_persona where  id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")  and id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                  $todos=false; 
                }
                else
                {
                  $consulta="select * from mds_rum_persona where  id in (select idpersona from mds_rum_capacitacion where ".$where_capacitacion.")";
                  $todos=false; 
                }                
              }
              else
              {
                if ($datos_laborales)
                {
                  $consulta="select * from mds_rum_persona where  id in (select idpersona from mds_rum_experiencia where ".$where_laboral.")"; 
                  $todos=false; 
                }
                else
                {

                }
              }

            }

          }
        }
      }
      
         
      if ($todos)
      {
        $consulta="select * from mds_rum_persona ";
      }
     // print_r( $consulta);  
      $searchModel = new Mds_rum_personaSearch();      
      
      $query=Mds_rum_persona::findBySql($consulta);      

     $dataProvider = new ActiveDataProvider(['query' => $query],['totalItemCount'=>3,]);
     $dataProvider->setPagination(['pageSize' => 5]);     
           //Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_persona', null, array());
           return $this->render('index', [
               'searchModel' => $searchModel,
               'dataProvider' => $dataProvider,
               'model_filtro2' => $model,
           ]);
   
           
           //$dni = $request->dni;
           
           //print_r( $model->dni);
           
          
       }
    /*public function actionFiltrar_datos2($codigo)
    {

   $model_persona=Mds_rum_persona::findBySql("select * from mds_rum_persona where id=".$codigo);
   //$searchModel = new Mds_rum_personaSearch();
   $searchModel = new Mds_rum_personaSearch();
   //$searchModel =Mds_rum_personaSearch::findBySql("select * from mds_rum_persona where (id= 2320 or id=2406)");
   //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
   //print_r($dataProvider);
   $query=Mds_rum_persona::findBySql("select * from mds_rum_persona where id=".$codigo);      
   $dataProvider = new ActiveDataProvider(['query' => $query]);
   //print_r($dataProvider);
        //Mds_sys_log::guardarLog(Mds_sys_log::ACCION_CONSULTA, 'mds_rum_persona', null, array());
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);


       
    }*/

    
    /**
     * Delete an existing Mds_rum_persona model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        if ($model->delete() > 0) {
            Mds_sys_log::guardarLog(Mds_sys_log::ACCION_ELIMINAR, 'mds_rum_persona', $id, $model->getAttributes());
        }
        

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

    /**
     * Finds the Mds_rum_persona model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mds_rum_persona the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mds_rum_persona::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionReenviar_confirmacion($id) //id de rum persona. Reenviar la confirmacion de la cuenta de usuario para acceso a la web de RUMBO, al  email del usuario rtegistrado
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id); // $model es el modelo de rum_persona
        $cad_estado="asdas";        
        //$id_persona=$model->idpersona;
        $id_seg_usuario=$model->id_seg_usuario;
        $id_com_persona=$model->id_com_persona;
        $una_com_persona = Sds_com_persona::findOne($model->id_com_persona);
        $un_seg_usuario = Mds_seg_usuario::findOne($model->id_seg_usuario);
        
        //$un_usuario=Mds_seg_usuario::findOne($id_persona); 
        $fechaenvio=strftime( "%Y-%m-%d", time() ); 
        $unafecha = explode ("-",$fechaenvio);
        $fechaenvio= trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]);  
        $horaenvio=strftime( "%H:%M:%S", time() );	
     

        $fullname=$una_com_persona->nombre.' '.$una_com_persona->apellido;
        $documento=$una_com_persona->documento;
        $user=$un_seg_usuario->user;     
        $email=$un_seg_usuario->mail;
        $fechaalta=$model->fechaalta;
        $unafecha = explode ("-",$fechaalta);
        $fechaalta=trim($unafecha[2])."/".trim($unafecha[1])."/".trim($unafecha[0]);
        $horaalta=$model->horaalta;

        $fecha_inscripcion=$fechaalta.' a las '.$horaalta;


        $fechaactual=strftime( "%Y-%m-%d", time() );  
        $horaactual=strftime( "%H:%M:%S", time() );
        $cadenaid = strval( $id ).$fechaactual.$horaactual;
        $convertir=$fullname.$cadenaid;
        $verification_code=substr(md5($convertir), 0,8);

        $model_seg_usuario = new Mds_seg_usuario();
        $model_seg_usuario=Mds_seg_usuario::findOne($model->id_seg_usuario);
        $model_seg_usuario->verification_code=$verification_code;
        $model_seg_usuario->save();
        
        
        $cad_cuerpo='<!doctype html>
        <html>
        
        <head>
          <meta name="viewport" content="width=device-width">
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <title>Simple Transactional Email</title>
          <style>
            /* -------------------------------------
                INLINED WITH htmlemail.io/inline
            ------------------------------------- */
            /* -------------------------------------
                RESPONSIVE AND MOBILE FRIENDLY STYLES
            ------------------------------------- */
            @media only screen and (max-width: 620px) {
              table[class=body] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
              }
        
              table[class=body] p,
              table[class=body] ul,
              table[class=body] ol,
              table[class=body] td,
              table[class=body] span,
              table[class=body] a {
                font-size: 16px !important;
              }
        
              table[class=body] .wrapper,
              table[class=body] .article {
                padding: 10px !important;
              }
        
              table[class=body] .content {
                padding: 0 !important;
              }
        
              table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
              }
        
              table[class=body] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
              }
        
              table[class=body] .btn table {
                width: 100% !important;
              }
        
              table[class=body] .btn a {
                width: 100% !important;
              }
        
              table[class=body] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
              }
            }
        
            /* -------------------------------------
                PRESERVE THESE STYLES IN THE HEAD
            ------------------------------------- */
            @media all {
              .ExternalClass {
                width: 100%;
              }
        
              .ExternalClass,
              .ExternalClass p,
              .ExternalClass span,
              .ExternalClass font,
              .ExternalClass td,
              .ExternalClass div {
                line-height: 100%;
              }
        
              .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
              }
        
              #MessageViewBody a {
                color: inherit;
                text-decoration: none;
                font-size: inherit;
                font-family: inherit;
                font-weight: inherit;
                line-height: inherit;
              }
        
              .btn-primary table td:hover {
                background-color: #34495e !important;
              }
        
              .btn-primary a:hover {
                background-color: #34495e !important;
                border-color: #34495e !important;
              }
            }
          </style>
        </head>
        
        <body class=""
          style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
          <span class="preheader"
            style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;"></span>
          <table border="0" cellpadding="0" cellspacing="0" class="body"
            style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
            <tr>
              <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
              <td class="container"
                style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
                <div class="content"
                  style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">
        
                  <!-- START CENTERED WHITE CONTAINER -->
                  <table class="main"
                    style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">
        
                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                      <td>
                        <img src="cid:email_header" width="580px">
                      </td>
                    </tr>
                    <tr>
                      <td class="wrapper"
                        style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                        <table border="0" cellpadding="0" cellspacing="0"
                          style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                          <tr>
                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                              <p style="text-align: right; font-weight: bold;">
                                Nro de Comprobante: '.$id.'
                              </p>
                              <p
                                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
                                Estimado/a '.$fullname.'</p>
                              <p
                                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">
                                El registro se ha realizado correctamente</p>
                              <h3>Datos de Registro:</h3>
                              <table>
                                <tr>
                                  <td>Nombre y Apellido:</td>
                                  <td style="font-weight: bold; font-style: italic;">'.$fullname.'</td>
                                </tr>
                                <tr>
                                  <td>Documento:</td>
                                  <td>'.$documento.'</td>
                                </tr>
                                <tr>
                                  <td>Nombre de Usuario:</td>
                                  <td>'.$user.'</td>
                                </tr>
                                <tr>
                                  <td>Email:</td>
                                  <td>'.$email.'</td>
                                </tr>
                                <tr>
                                  <td>Fecha y Hora:</td>
                                  <td>'.$fecha_inscripcion.'</td>
                                </tr>
                              </table>             
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td style="text-align: center;">
                        <table style="width: 100%;">
                          <tr>
                            <td>
                              <a href="https://rumbo.neuquen.gov.ar/registro_finalizado.php?id='.$id_seg_usuario.'&vc='.$verification_code.'" style="color: white; text-decoration: none; cursor: pointer; background-color: #53ab79; font-weight: bold; padding: 1rem; margin: 0 5% 5% 5%; text-transform: uppercase;">                             
                                Click aqui para Verificar Cuenta
                              </a>
                            </td>
                          </tr>
                        </table>
                      <td>
                    </tr>
                    <tr>
                        <td><br><br></td>
                    </tr>
                    <tr>
                      <td style="text-align: center;">
                        <table style="width: 100%;">
                          <tr>
                            <td>
                              <a href="https://www.youtube.com/watch?v=0ckEKw9aiuo" style="color: white; text-decoration: none; cursor: pointer; background-color: #3f27aa; font-weight: bold; padding: 1rem; margin: 0 5% 5% 5%; text-transform: uppercase;">                     
                                Ver video Instructivo
                              </a>
                            </td>
                          </tr>
                        </table>
                      <td>
                    </tr>
                    <tr>
                      <td>
                        <br>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <img src="cid:email_footer" width="580px">
                      </td>
                    </tr>
        
                    <!-- END MAIN CONTENT AREA -->
                  </table>
        
                  <!-- START FOOTER -->
                  <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                    <table border="0" cellpadding="0" cellspacing="0"
                      style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                      <tr>
                        <td class="content-block"
                          style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                          <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Ministerio de
                            Desarrollo Social y Trabajo
                          </span>
                        </td>
                      </tr>
                      <tr>
                        <td class="content-block powered-by"
                          style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                          Enviado por "Rumbo" un Producto de "SUR - Sistema Unico de Registro"
                        </td>
                      </tr>
                    </table>
                  </div>
                  <!-- END FOOTER -->
        
                  <!-- END CENTERED WHITE CONTAINER -->
                </div>
              </td>
              <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
            </tr>
          </table>
        </body>
        
        </html>';
        //$cad_cuerpo = str_replace('$fullname','Luis Eduardo Garcia', $cad_cuerpo);
        
        
        Yii::$app->mailer->compose()
        ->setFrom('rumbo@neuquen.gov.ar', 'Portal de Formacion y Empleo - RUMBO')
        ->setTo($email)
        ->setSubject('Rumbo: Comprobante de Registro para Validacion')
        ->setTextBody($cad_estado)
        ->setHtmlBody( $cad_cuerpo)
        ->send();

        return $cad_estado;
    }
}
