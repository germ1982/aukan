function pagina(nropagina,target,pag)
{ 
 ajax_Pagina=nuevoAjax();
 ajax_Pagina.open("POST",pag, true);
 ajax_Pagina.onreadystatechange=function() 
 	{
  		if (ajax_Pagina.readyState==4) 
  			{ ///alert(ajax_Pagina.responseText);
   				document.getElementById(target).innerHTML = ajax_Pagina.responseText;
  			}
 	}
	ajax_Pagina.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Pagina.send("pag="+nropagina);
}

//--------------------------------------------------------------
// FUNCIONES PARA NOMENCLADOR
//----------------------------------------------------------------

function guardarNomenc(idnomenclador_nom,codigo_nom,descripcion_nom)	
{
	ajax_Nom = nuevoAjax();
	ajax_Nom.open("POST","guardarNomenc.php", true);
	ajax_Nom.onreadystatechange = function()
	{
		if (ajax_Nom.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('idnomenclador_nomenc').value = '';
			document.getElementById('codigo_nomenc').value = '';
			document.getElementById('descrip_nomenc').value = '';
		}
	}
	ajax_Nom.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Nom.send("idnomenclador="+idnomenclador_nom+"&codigo="+codigo_nom+"&descripcion="+descripcion_nom);
}

function guardarNomenclador(target,idnomenclador_nom,codigo_nom,descripcion_nom)	
{
	var divtarget=document.getElementById(target);
	ajax_N = nuevoAjax();
	ajax_N.open("POST","guardarNomenc.php", true);
	ajax_N.onreadystatechange = function()
	{
		if (ajax_N.readyState==4)
		{	
			divtarget.innerHTML= ajax_N.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idnomenclador_nomenc').value = '';
			document.getElementById('codigo_nomenc').value = '';
			document.getElementById('descrip_nomenc').value = '';
		}
	}
	ajax_N.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_N.send("idnomenclador="+idnomenclador_nom+"&codigo="+codigo_nom+"&descripcion="+descripcion_nom);
}

function mostrarNomenclador(idnomenclador_nom,codigo_nom,descripcion_nom)
{ 
	document.getElementById('idnomenclador_nomenc').value = document.getElementById(idnomenclador_nom).value ;
	document.getElementById('codigo_nomenc').value = document.getElementById(codigo_nom).value ;
	document.getElementById('descrip_nomenc').value = document.getElementById(descripcion_nom).value ;
}

function borrarNomenclador(idnomenclador_nom)
{
	var id = document.getElementById(idnomenclador_nom).value;
	var target = document.getElementById('listadoNomenclador');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_No = nuevoAjax();
		ajax_No.open("POST", "borrarNomenc.php", true);
		ajax_No.onreadystatechange = function()
		{
			if (ajax_No.readyState==4)
			{
				 target.innerHTML = ajax_No.responseText;
			}
		}
		ajax_No.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_No.send("idnomenclador="+id);
	}
}


function buscarMostrarNomenc(codigo_nomenclador,descripcion_nomenclador)
	{
		var codigoNom = document.getElementById(codigo_nomenclador).value;
		var descripcionNom = document.getElementById(descripcion_nomenclador).value;
		
				ajax_BN=nuevoAjax();	
				ajax_BN.open("POST","listadoNomenc_tabla.php", true);
				ajax_BN.onreadystatechange=function()
				{
					if (ajax_BN.readyState==4) 
						{
						    var target = document.getElementById('listadoNomencTabla');	
							target.innerHTML= ajax_BN.responseText;
						}
				}
			ajax_BN.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_BN.send("codigo="+codigoNom+"&descripcion="+descripcionNom);
	}
	
//--------------------------------------------------------------
// FUNCIONES PARA MOTIVO INTERNACION
//----------------------------------------------------------------

function guardarMotivo(idmotivo_motivo,codigo_motivo,descripcion_motivo)	
{
	ajax_Mot = nuevoAjax();
	ajax_Mot.open("POST","guardarMotivoInternacion.php", true);
	ajax_Mot.onreadystatechange = function()
	{
		if (ajax_Mot.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('idmotivo_inter').value = '';
			document.getElementById('codigo_motivo_inter').value = '';
			document.getElementById('descrip_motivo_inter').value = '';
		}
	}
	ajax_Mot.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Mot.send("idmotivo_internacion="+idmotivo_motivo+"&codigo="+codigo_motivo+"&descripcion="+descripcion_motivo);
}

function guardarMotivoInternacion(target,idmotivo_motivo,codigo_motivo,descripcion_motivo)	
{
	var divtarget=document.getElementById(target);
	ajax_Mo = nuevoAjax();
	ajax_Mo.open("POST","guardarMotivoInternacion.php", true);
	ajax_Mo.onreadystatechange = function()
	{
		if (ajax_Mo.readyState==4)
		{	
			divtarget.innerHTML= ajax_Mo.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idmotivo_inter').value = '';
			document.getElementById('codigo_motivo_inter').value = '';
			document.getElementById('descrip_motivo_inter').value = '';
		}
	}
	ajax_Mo.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Mo.send("idmotivo_internacion="+idmotivo_motivo+"&codigo="+codigo_motivo+"&descripcion="+descripcion_motivo);
}


function borrarMotivoInternacion(idmotivo_motivo)
{
	var id = document.getElementById(idmotivo_motivo).value;
	var target = document.getElementById('listadoMotivoInternacion');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Moti = nuevoAjax();
		ajax_Moti.open("POST", "borrarMotivoInternacion.php", true);
		ajax_Moti.onreadystatechange = function()
		{
			if (ajax_Moti.readyState==4)
			{
				 target.innerHTML = ajax_Moti.responseText;
			}
		}
		ajax_Moti.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Moti.send("idmotivo_internacion="+id);
	}
}

function mostrarMotivoInternacion(idmotivo_motivo,codigo_motivo,descripcion_motivo)
{ 
	document.getElementById('idmotivo_inter').value = document.getElementById(idmotivo_motivo).value ;
	document.getElementById('codigo_motivo_inter').value = document.getElementById(codigo_motivo).value ;
	document.getElementById('descrip_motivo_inter').value = document.getElementById(descripcion_motivo).value ;
}


function buscarMotivoInternacion(codigo_motivo,descripcion_motivo)
	{
		var codigoMot = document.getElementById(codigo_motivo).value;
		var descripcionMot = document.getElementById(descripcion_motivo).value;
		
				ajax_BM=nuevoAjax();	
				ajax_BM.open("POST","listadoMotivo_tabla.php", true);
				ajax_BM.onreadystatechange=function()
				{
					if (ajax_BM.readyState==4) 
						{
						    var target = document.getElementById('listadoMotivoInternacionTabla');	
							target.innerHTML= ajax_BM.responseText;
						}
				}
			ajax_BM.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_BM.send("codigo="+codigoMot+"&descripcion="+descripcionMot);
	}
	
		
//--------------------------------------------------------------
// FUNCIONES PARA BATERIAS LABORATORIO
//----------------------------------------------------------------
	
function guardarBateriasLaboratorio(idbateria_lab,descrip_bat)	
{
	ajax_Bat = nuevoAjax();
	ajax_Bat.open("POST","guardarBateriasLaboratorio.php", true);
	ajax_Bat.onreadystatechange = function()
	{
		if (ajax_Bat.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('idbateria_laboratorio').value = '';
			document.getElementById('descripcion_bat').value = '';
		}
	}
	ajax_Bat.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Bat.send("idbateria_laboratorio="+idbateria_lab+"&descripcion="+descrip_bat);
}
	

function guardarBateria(target,idbateria_lab,descrip_bat)	
{
	var divtarget=document.getElementById(target);
	ajax_Bate = nuevoAjax();
	ajax_Bate.open("POST","guardarBateriasLaboratorio.php", true);
	ajax_Bate.onreadystatechange = function()
	{
		if (ajax_Bate.readyState==4)
		{	
			divtarget.innerHTML= ajax_Bate.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idbateria_laboratorio').value = '';
			document.getElementById('descripcion_bat').value = '';
		}
	}
	ajax_Bate.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Bate.send("idbateria_laboratorio="+idbateria_lab+"&descripcion="+descrip_bat);
}	
	

function borrarBateriasLaboratorio(idbateria_laboratorio)
{
	var id = document.getElementById(idbateria_laboratorio).value;
	var target = document.getElementById('listadoBateriasLaboratorio');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Bater = nuevoAjax();
		ajax_Bater.open("POST", "borrarBateriasLaboratorio.php", true);
		ajax_Bater.onreadystatechange = function()
		{
			if (ajax_Bater.readyState==4)
			{
				 target.innerHTML = ajax_Bater.responseText;
			}
		}
		ajax_Bater.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Bater.send("idbateria_laboratorio="+id);
	}
}
	
function mostrarBateriasLaboratorio(idbateria_lab,descrip_bat)
{ 
	document.getElementById('idbateria_laboratorio').value = document.getElementById(idbateria_lab).value ;
	document.getElementById('descripcion_bat').value = document.getElementById(descrip_bat).value ;
}

function buscarBateriasLaboratorio(descripcion_bateria)
	{
		var descripcionbat = document.getElementById(descripcion_bateria).value;
		
				ajax_Bateria=nuevoAjax();	
				ajax_Bateria.open("POST","listadoBateriasLaboratorio_tabla.php", true);
				ajax_Bateria.onreadystatechange=function()
				{
					if (ajax_Bateria.readyState==4) 
						{
						    var target = document.getElementById('listadoBuscarBaterias');	
							target.innerHTML= ajax_Bateria.responseText;
						}
				}
			ajax_Bateria.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Bateria.send("descripcion="+descripcionbat);
	}

	
//--------------------------------------------------------------
// FUNCIONES PARA CLASIFICACION
//----------------------------------------------------------------
	
function guardarClasificacion(idclasificacion_clas,nombre_clas) 	
{
	ajax_CL = nuevoAjax();
	ajax_CL.open("POST","guardarClasificacion.php", true);
	ajax_CL.onreadystatechange = function()
	{
		if (ajax_CL.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('idclasificacion').value = '';
			document.getElementById('nombre_clasif').value = '';
		}
	}
	ajax_CL.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_CL.send("idclasificacion="+idclasificacion_clas+"&nombre="+nombre_clas);
}
	

function guardarClasif(target,idclasificacion_clas,nombre_clas)	
{
	var divtarget=document.getElementById(target);
	ajax_Cla = nuevoAjax();
	ajax_Cla.open("POST","guardarClasificacion.php", true);
	ajax_Cla.onreadystatechange = function()
	{
		if (ajax_Cla.readyState==4)
		{	
			divtarget.innerHTML= ajax_Cla.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idclasific').value = '';
			document.getElementById('nombre_clasific').value = '';
		}
	}
	ajax_Cla.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Cla.send("idclasificacion="+idclasificacion_clas+"&nombre="+nombre_clas);
}	

function borrarClasificacion(idclasific)
{
	var id = document.getElementById(idclasific).value;
	var target = document.getElementById('listadoClasificacion');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Clasi = nuevoAjax();
		ajax_Clasi.open("POST", "borrarClasificacion.php", true);
		ajax_Clasi.onreadystatechange = function()
		{
			if (ajax_Clasi.readyState==4)
			{
				 target.innerHTML = ajax_Clasi.responseText;
			}
		}
		ajax_Clasi.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Clasi.send("idclasificacion="+id);
	}
}

function mostrarClasificacion(idclasificacion_clas,nombre_clas)
{ 
	document.getElementById('idclasific').value = document.getElementById(idclasificacion_clas).value ;
	document.getElementById('nombre_clasific').value = document.getElementById(nombre_clas).value ;
}

function buscarClasificacion(nombre_clasificacion)
	{
		var nombreClas = document.getElementById(nombre_clasificacion).value;
		
				ajax_Clasific=nuevoAjax();	
				ajax_Clasific.open("POST","listadoClasificacion_tabla.php", true);
				ajax_Clasific.onreadystatechange=function()
				{
					if (ajax_Clasific.readyState==4) 
						{
						    var target = document.getElementById('listadoClasif');	
							target.innerHTML= ajax_Clasific.responseText;
						}
				}
			ajax_Clasific.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Clasific.send("nombre="+nombreClas);
	}
//--------------------------------------------------------------
// FUNCIONES PARA CAMA
//----------------------------------------------------------------

function guardarcama(habitacion_ca,cama_ca,descripcion_ca)
{
	ajax_Ca = nuevoAjax();
	ajax_Ca.open("POST","guardarCamas.php", true);
	ajax_Ca.onreadystatechange = function()
	{
		if (ajax_Ca.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('habitac_cama').value = '';
			document.getElementById('cama_cama').value = '';
			document.getElementById('descrip_cama').value = '';
		}
	}
	
	ajax_Ca.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Ca.send("habitacion="+habitacion_ca+"&cama="+cama_ca+"&descripcion="+descripcion_ca);

}

function guardarCamas_c(target,habitacion_oc,cama_oc,descripcion_oc,habitacion_c,cama_c,descripcion_c)	
{
	var divtarget=document.getElementById(target);
	ajax_Cam = nuevoAjax();
	ajax_Cam.open("POST","guardarCamas.php", true);
	ajax_Cam.onreadystatechange = function()
	{
		if (ajax_Cam.readyState==4)
		{	
			divtarget.innerHTML= ajax_Cam.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('habitacion_oculto').value = '';
			document.getElementById('cama_oculto').value = '';
			document.getElementById('descripcion_oculto').value = '';
			document.getElementById('habitacion_cam').value = '';
			document.getElementById('cama_cam').value = '';
			document.getElementById('descripcion_cam').value = '';
		}
	}
	ajax_Cam.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Cam.send("habitacion="+habitacion_oc+"&cama="+cama_oc+"&descripcion="+descripcion_oc+"&habit="+habitacion_c+"&ca="+cama_c+"&descrip="+descripcion_c);
}

function borrarCamas(habitacion_c,cama_c,descripcion_c)
{
	var hab = document.getElementById(habitacion_c).value;
	var cam = document.getElementById(cama_c).value;
	var des = document.getElementById(descripcion_c).value;
	
	var target = document.getElementById('listadoCamas');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Cama = nuevoAjax();
		ajax_Cama.open("POST", "borrarCamas.php", true);
		ajax_Cama.onreadystatechange = function()
		{
			if (ajax_Cama.readyState==4)
			{
				 target.innerHTML = ajax_Cama.responseText;
			}
		}
		ajax_Cama.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Cama.send("habitacion="+hab+"&cama="+cam+"&descripcion="+des);
	}
}

function mostrarCamas(habitacion_oc,cama_oc,descripcion_oc,habitacion_c,cama_c,descripcion_c)
{ 	
	document.getElementById('habitacion_oculto').value = document.getElementById(habitacion_oc).value ;
	document.getElementById('cama_oculto').value = document.getElementById(cama_oc).value ;
	document.getElementById('descripcion_oculto').value = document.getElementById(descripcion_oc).value ;
	
	document.getElementById('habitacion_cam').value = document.getElementById(habitacion_c).value ;
	document.getElementById('cama_cam').value = document.getElementById(cama_c).value ;
	document.getElementById('descripcion_cam').value = document.getElementById(descripcion_c).value ;
}


function buscarCamas(hab_cama,ca_cama,des_cama)
	{
		var hab = document.getElementById(hab_cama).value;
		var cama = document.getElementById(ca_cama).value;
		var desc = document.getElementById(des_cama).value;
	
				ajax_Cama_c=nuevoAjax();	
				ajax_Cama_c.open("POST","listadoCamas_tabla.php", true);
				ajax_Cama_c.onreadystatechange=function()
				{
					if (ajax_Cama_c.readyState==4) 
						{
						    var target = document.getElementById('listadoCa');	
							target.innerHTML= ajax_Cama_c.responseText;
						}
				}
			ajax_Cama_c.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Cama_c.send("habitacion="+hab+"&cama="+cama+"&descripcion="+desc);
	}
	
		















//--------------------------------------------------------------
// FUNCIONES PARA CATEGORIA KNAUS
//----------------------------------------------------------------

function guardarCategoriaKnaus(codigo_categoria,nombre_categoria,coeficiente_categoria)	
{
	ajax_Cat = nuevoAjax();
	ajax_Cat.open("POST","guardarCategoriaKnaus.php", true);
	ajax_Cat.onreadystatechange = function()
	{
		if (ajax_Cat.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('codigo_cat').value = '';
			document.getElementById('nombre_cat').value = '';
			document.getElementById('coeficiente_cat').value = '';
		}
	}
	ajax_Cat.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Cat.send("codigo="+codigo_categoria+"&nombre="+nombre_categoria+"&coeficiente="+coeficiente_categoria);
}

function guardarCategoriaK(target,cod_categoria,nom_categoria,coefic_categoria)	
{
	var divtarget=document.getElementById(target);
	ajax_Cate = nuevoAjax();
	ajax_Cate.open("POST","guardarCategoriaKnaus.php", true);
	ajax_Cate.onreadystatechange = function()
	{
		if (ajax_Cate.readyState==4)
		{	
			divtarget.innerHTML= ajax_Cate.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('codigo_categ').value = '';
			document.getElementById('nombre_categ').value = '';
			document.getElementById('coeficiente_categ').value = '';
		}
	}
	ajax_Cate.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Cate.send("codigo="+cod_categoria+"&nombre="+nom_categoria+"&coeficiente="+coefic_categoria);
}

function mostrarCategoriaKnaus(cod_categoria,nom_categoria,coefic_categoria)
{ 
	document.getElementById('codigo_categ').value = document.getElementById(cod_categoria).value ;
	document.getElementById('nombre_categ').value = document.getElementById(nom_categoria).value ;
	document.getElementById('coeficiente_categ').value = document.getElementById(coefic_categoria).value ;
}

function borrarCategoriaKnaus(cod_categoria)
{
	var cod = document.getElementById(cod_categoria).value;
	var target = document.getElementById('listadoCategoriaKnaus');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Categ = nuevoAjax();
		ajax_Categ.open("POST", "borrarCategoriaKnaus.php", true);
		ajax_Categ.onreadystatechange = function()
		{
			if (ajax_Categ.readyState==4)
			{
				 target.innerHTML = ajax_Categ.responseText;
			}
		}
		ajax_Categ.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Categ.send("codigo="+cod);
	}
}

function buscarCategoriaKnaus(nombreCateg,coeficCateg)
	{
		var nomCat = document.getElementById(nombreCateg).value;
		var coefCat = document.getElementById(coeficCateg).value;
		
				ajax_Categoria=nuevoAjax();	
				ajax_Categoria.open("POST","listadoCategoriaKnaus_tabla.php", true);
				ajax_Categoria.onreadystatechange=function()
				{
					if (ajax_Categoria.readyState==4) 
						{
						    var target = document.getElementById('listadoCategoria');	
							target.innerHTML= ajax_Categoria.responseText;
						}
				}
			ajax_Categoria.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Categoria.send("nombre="+nomCat+"&coeficiente="+coefCat);
	}
	
//----------------------------------------------------------------------
// FUNCIONES PARA PATOLOGIA
//----------------------------------------------------------------------
	
function guardarPatologia(codigo_patolo,nombre_patolo)	
{
	ajax_Pat = nuevoAjax();
	ajax_Pat.open("POST","guardarpatologia.php", true);
	ajax_Pat.onreadystatechange = function()
	{
		if (ajax_Pat.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('codigo_pato').value = '';
			document.getElementById('nombre_pato').value = '';
		}
	}
	ajax_Pat.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Pat.send("codigo="+codigo_patolo+"&nombre="+nombre_patolo);
}
	

function guardarPato(target,codigo_patolog,nombre_patolog)	
{
	var divtarget=document.getElementById(target);
	ajax_Pato = nuevoAjax();
	ajax_Pato.open("POST","guardarPatologia.php", true);
	ajax_Pato.onreadystatechange = function()
	{
		if (ajax_Pato.readyState==4)
		{	
			divtarget.innerHTML= ajax_Pato.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('codigo_patol').value = '';
			document.getElementById('nombre_patol').value = '';
		}
	}
	ajax_Pato.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Pato.send("codigo="+codigo_patolog+"&nombre="+nombre_patolog);
}	
	
function borrarPatologia(codigo_patolog)
{
	var cod = document.getElementById(codigo_patolog).value;
	var target = document.getElementById('listadoPatologia');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Patolo = nuevoAjax();
		ajax_Patolo.open("POST", "borrarPatologia.php", true);
		ajax_Patolo.onreadystatechange = function()
		{
			if (ajax_Patolo.readyState==4)
			{
				 target.innerHTML = ajax_Patolo.responseText;
			}
		}
		ajax_Patolo.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Patolo.send("codigo="+cod);
	}
}
	
function mostrarPatologia(codigo_patolog,nombre_patolog)
{ 
	document.getElementById('codigo_patol').value = document.getElementById(codigo_patolog).value ;
	document.getElementById('nombre_patol').value = document.getElementById(nombre_patolog).value ;
}

function buscarPatologia(nombre_patologia)
	{
		var nombrePat = document.getElementById(nombre_patologia).value;
		
				ajax_Patolog=nuevoAjax();	
				ajax_Patolog.open("POST","listadoPatologia_tabla.php", true);
				ajax_Patolog.onreadystatechange=function()
				{
					if (ajax_Patolog.readyState==4) 
						{
						    var target = document.getElementById('listadoPatolog');	
							target.innerHTML= ajax_Patolog.responseText;
						}
				}
			ajax_Patolog.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Patolog.send("nombre="+nombrePat);
	}


//----------------------------------------------------------------------
// FUNCIONES ROL
//----------------------------------------------------------------------
	
function guardarRol(idrol_rol_rol,nombre_rol_rol,perfil_rol_rol)	
{
	ajax_Ro = nuevoAjax();
	ajax_Ro.open("POST","guardarRol.php", true);
	ajax_Ro.onreadystatechange = function()
	{
		if (ajax_Ro.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('idrol_rol').value = '';
			document.getElementById('nombre_rol').value = '';
			document.getElementById('perfil_rol').value = '';
		}
	}
	ajax_Ro.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Ro.send("idrol="+idrol_rol_rol+"&nombre="+nombre_rol_rol+"&perfil="+perfil_rol_rol);
}
	
function guardarRol_rol(target,idrol_rol_r,nombre_rol_r,perfil_rol_r)	
{
	var divtarget=document.getElementById(target);
	ajax_Rol = nuevoAjax();
	ajax_Rol.open("POST","guardarRol.php", true);
	ajax_Rol.onreadystatechange = function()
	{
		if (ajax_Rol.readyState==4)
		{	
			divtarget.innerHTML= ajax_Rol.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idrol_rol_ro').value = '';
			document.getElementById('nombre_rol_ro').value = '';
			document.getElementById('perfil_rol_ro').value = '';
		}
	}
	ajax_Rol.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Rol.send("idrol="+idrol_rol_r+"&nombre="+nombre_rol_r+"&perfil="+perfil_rol_r);
}	
	

function borrarRol(idrol_rol_r)
{
	var id = document.getElementById(idrol_rol_r).value;
	var target = document.getElementById('listadoRol');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Rol_ro = nuevoAjax();
		ajax_Rol_ro.open("POST", "borrarRol.php", true);
		ajax_Rol_ro.onreadystatechange = function()
		{
			if (ajax_Rol_ro.readyState==4)
			{
				 target.innerHTML = ajax_Rol_ro.responseText;
			}
		}
		ajax_Rol_ro.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Rol_ro.send("idrol="+id);
	}
}
	
function mostrarRol(idrol_rol_r,nombre_rol_r,perfil_rol_r)
{ 
	document.getElementById('idrol_rol_ro').value = document.getElementById(idrol_rol_r).value ;
	document.getElementById('nombre_rol_ro').value = document.getElementById(nombre_rol_r).value ;
	document.getElementById('perfil_rol_ro').value = document.getElementById(perfil_rol_r).value ;
}

function buscarRol(nombre_r)
	{
		var nombrerol = document.getElementById(nombre_r).value;
		
				ajax_Rol_rol=nuevoAjax();	
				ajax_Rol_rol.open("POST","listadoRol_tabla.php", true);
				ajax_Rol_rol.onreadystatechange=function()
				{
					if (ajax_Rol_rol.readyState==4) 
						{
						    var target = document.getElementById('listadoRol_r');	
							target.innerHTML= ajax_Rol_rol.responseText;
						}
				}
			ajax_Rol_rol.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Rol_rol.send("nombre="+nombrerol);
	}

	//--------------------------------------------------------------
// FUNCIONES PARA PROFESIONALES
//----------------------------------------------------------------

function guardarProfesionales(idprofesional_profe,nombre_profe,rol_profe,direccion_profe,localidad_profe,provincia_profe,codPos_profe,telefono_profe,matricula_profe,especialidad_profe,mail_profe)	
{
	ajax_Pro = nuevoAjax();
	ajax_Pro.open("POST","guardarProfesionales.php", true);
	ajax_Pro.onreadystatechange = function()
	{
		if (ajax_Pro.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('idprofesional_profes').value = '';
			document.getElementById('nombre_profes').value = '';
			document.getElementById('rol_profes').value = '';
			document.getElementById('direccion_profes').value = '';
			document.getElementById('localidad_profes').value = '';
			document.getElementById('provincia_profes').value = '';
			document.getElementById('codPos_profes').value = '';
			document.getElementById('telefono_profes').value = '';
			document.getElementById('matricula_profes').value = '';
			document.getElementById('especialidad_profes').value = '';
			document.getElementById('mail_profes').value = '';
		}
	}
	ajax_Pro.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Pro.send("idprofesional="+idprofesional_profe+"&nombre="+nombre_profe+"&rol="+rol_profe+"&direccion="+direccion_profe+"&localidad="+localidad_profe+"&provincia="+provincia_profe+"&telefono="+telefono_profe+"&COD_POS="+codPos_profe+telefono_profe+"&matricula="+matricula_profe+"&idespecialidad="+especialidad_profe+"&mail="+mail_profe);
}

function guardarProfe(target,idprofesional_pr,nombre_pr,rol_pr,direccion_pr,localidad_pr,provincia_pr,codPos_pr,telefono_pr,matricula_pr,especialidad_pr,mail_pr)	
{
	var divtarget=document.getElementById(target);
	ajax_Prof = nuevoAjax();
	ajax_Prof.open("POST","guardarProfesionales.php", true);
	ajax_Prof.onreadystatechange = function()
	{
		if (ajax_Prof.readyState==4)
		{	
			divtarget.innerHTML= ajax_Prof.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idprofesional_p').value = '';
			document.getElementById('nombre_p').value = '';
			document.getElementById('rol_p').value = '';
			document.getElementById('direccion_p').value = '';
			document.getElementById('localidad_p').value = '';
			document.getElementById('provincia_p').value = '';
			document.getElementById('codPos_p').value = '';
			document.getElementById('telefono_p').value = '';
			document.getElementById('matricula_p').value = '';
			document.getElementById('especialidad_p').value = '';
			document.getElementById('mail_p').value = '';	
		}
	}
	ajax_Prof.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Prof.send("idprofesional="+idprofesional_pr+"&nombre="+nombre_pr+"&rol="+rol_pr+"&direccion="+direccion_pr+"&localidad="+localidad_pr+"&provincia="+provincia_pr+"&cod_pos="+codPos_pr+"&telefono="+telefono_pr+"&matricula="+matricula_pr+"&idespecialidad="+especialidad_pr+"&mail="+mail_pr);
}

function mostrarProfesionales(idprofesional_pr,nombre_pr,rol_pr,direccion_pr,localidad_pr,provincia_pr,codPos_pr,telefono_pr,matricula_pr,especialidad_pr,mail_pr)
{
	document.getElementById('idprofesional_p').value = document.getElementById(idprofesional_pr).value ;
	document.getElementById('nombre_p').value = document.getElementById(nombre_pr).value ;
	document.getElementById('rol_p').value = document.getElementById(rol_pr).value ;
	document.getElementById('direccion_p').value = document.getElementById(direccion_pr).value ;
	document.getElementById('localidad_p').value = document.getElementById(localidad_pr).value ;
	document.getElementById('provincia_p').value = document.getElementById(provincia_pr).value ;
	document.getElementById('codPos_p').value = document.getElementById(codPos_pr).value ;
	document.getElementById('telefono_p').value = document.getElementById(telefono_pr).value ;
	document.getElementById('matricula_p').value = document.getElementById(matricula_pr).value ;
	document.getElementById('especialidad_p').value = document.getElementById(especialidad_pr).value ;
	document.getElementById('mail_p').value = document.getElementById(mail_pr).value ;
}

function borrarProfesionales(idprofesional_pr)
{
	var id = document.getElementById(idprofesional_pr).value;
	var target = document.getElementById('listadoProfesionales');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Profe = nuevoAjax();
		ajax_Profe.open("POST", "borrarProfesionales.php", true);
		ajax_Profe.onreadystatechange = function()
		{
			if (ajax_Profe.readyState==4)
			{
				 target.innerHTML = ajax_Profe.responseText;
			}
		}
		ajax_Profe.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Profe.send("idprofesional="+id);
	}
}

function buscarProfesionales(nomb_prof,especiali_profe)
	{
		var nombreProf = document.getElementById(nomb_prof).value;
		var especialidadProf = document.getElementById(especiali_profe).value;
				
				ajax_Profesio=nuevoAjax();	
				ajax_Profesio.open("POST","listadoProfesionales_tabla.php", true);
				ajax_Profesio.onreadystatechange=function()
				{
					if (ajax_Profesio.readyState==4) 
						{
						    var target = document.getElementById('listadoProf');	
							target.innerHTML= ajax_Profesio.responseText;
						}
				}
			ajax_Profesio.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Profesio.send("nombre="+nombreProf+"&idespecialidad="+especialidadProf);
			
	}
	
	
//--------------------------------------------------------------
// FUNCIONES PARA PROCEDENCIA
//----------------------------------------------------------------	
	
function guardarProcedencia(codigo_proc,nombre_proc)
{
	ajax_Pro = nuevoAjax();
	ajax_Pro.open("POST","guardarProcedencia.php", true);
	ajax_Pro.onreadystatechange = function()
	{
		if (ajax_Pro.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('codigo_pro').value = '';
			document.getElementById('nombre_pro').value = '';
		}
	}
	ajax_Pro.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Pro.send("codigo="+codigo_proc+"&nombre="+nombre_proc);
}
	

function guardarProced(target,codigo_pr,nombre_pr)	
{
	var divtarget=document.getElementById(target);
	ajax_Proc = nuevoAjax();
	ajax_Proc.open("POST","guardarProcedencia.php", true);
	ajax_Proc.onreadystatechange = function()
	{
		if (ajax_Proc.readyState==4)
		{	
			divtarget.innerHTML= ajax_Proc.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('codigo_proced').value = '';
			document.getElementById('nombre_proced').value = '';
		}
	}
	ajax_Proc.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Proc.send("codigo="+codigo_pr+"&nombre="+nombre_pr);
}	
	
function borrarProcedencia(codigo_p)
{
	var cod = document.getElementById(codigo_p).value;
	var target = document.getElementById('listadoProcedencia');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Proce = nuevoAjax();
		ajax_Proce.open("POST", "borrarProcedencia.php", true);
		ajax_Proce.onreadystatechange = function()
		{
			if (ajax_Proce.readyState==4)
			{
				 target.innerHTML = ajax_Proce.responseText;
			}
		}
		ajax_Proce.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Proce.send("codigo="+cod);
	}
}
	
function mostrarProcedencia(codigo_pr,nombre_pr)
{ 
	document.getElementById('codigo_proced').value = document.getElementById(codigo_pr).value ;
	document.getElementById('nombre_proced').value = document.getElementById(nombre_pr).value ;
}

function buscarProcedencia(nombre_procedencia)
	{
		var nombreProc = document.getElementById(nombre_procedencia).value;
		
				ajax_Procede=nuevoAjax();	
				ajax_Procede.open("POST","listadoProcedencia_tabla.php", true);
				ajax_Procede.onreadystatechange=function()
				{
					if (ajax_Procede.readyState==4) 
						{
						    var target = document.getElementById('listadoProc');	
							target.innerHTML= ajax_Procede.responseText;
						}
				}
			ajax_Procede.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Procede.send("nombre="+nombreProc);
	}


//--------------------------------------------------------------
// FUNCIONES PARA MOTIVOS INGRESO CIE10
//----------------------------------------------------------------	
	
function guardarCie10(nombre_cie,tipo)
{
	ajax_Ci = nuevoAjax();
	ajax_Ci.open("POST","guardarCie10.php", true);
	ajax_Ci.onreadystatechange = function()
	{
		if (ajax_Ci.readyState==4)
		{	
			//alert(ajax_N.responseText);

			document.getElementById('nombre_cie10').value = '';
		}
	}
	ajax_Ci.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Ci.send("nombre="+nombre_cie+"&tipo="+tipo);

}
	
function guardarCie(target,codigo_o,nombre_o,tipo_o,codigo_ci,nombre_ci,tipo_ci)
{
	var divtarget=document.getElementById(target);
	ajax_Cie = nuevoAjax();
	ajax_Cie.open("POST","guardarCie10.php", true);
	ajax_Cie.onreadystatechange = function()
	{
		if (ajax_Cie.readyState==4)
		{	
			divtarget.innerHTML= ajax_Cie.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('codigo_oculto').value = '';
			document.getElementById('nombre_oculto').value = '';
			document.getElementById('codigo_cie').value = '';
			document.getElementById('nombre_cie').value = '';
			document.getElementById('tipo_cie').value = '';
			document.getElementById('tipo_oculto').value = '';
		}
	}
	ajax_Cie.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Cie.send("cod="+codigo_o+"&nom="+nombre_o+"&codigo="+codigo_ci+"&nombre="+nombre_ci+"&tip="+tipo_o+"&tipo="+tipo_ci);

}	
	
function borrarCie10(codigo_ci,nombre_ci)
{
	var codi = document.getElementById(codigo_ci).value;
	var nomb = document.getElementById(nombre_ci).value;
	var target = document.getElementById('listadoCie10');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Cie_c = nuevoAjax();
		ajax_Cie_c.open("POST", "borrarCie10.php", true);
		ajax_Cie_c.onreadystatechange = function()
		{
			if (ajax_Cie_c.readyState==4)
			{
				 target.innerHTML = ajax_Cie_c.responseText;
			}
		}
		ajax_Cie_c.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Cie_c.send("codigo="+codi+"&nombre="+nomb);
	}
}
	
function mostrarCie10(codigo_pr,nombre_pr,tipo,tipo_o)
{   
    document.getElementById('codigo_oculto').value = document.getElementById(codigo_pr).value ;
	document.getElementById('nombre_oculto').value = document.getElementById(nombre_pr).value ; 
	document.getElementById('codigo_cie').value = document.getElementById(codigo_pr).value ;
	document.getElementById('nombre_cie').value = document.getElementById(nombre_pr).value ;
	document.getElementById('tipo_oculto').value = document.getElementById(tipo_o).value ;

	document.getElementById('tipo_cie').options[0].text = document.getElementById(tipo).value ;
	document.getElementById('tipo_cie').options[0].value=document.getElementById(tipo).value ;
	document.getElementById('tipo_cie').options[0].selected=true;
}

function buscarCie10(codigo_cie_c,nombre_cie_c)
	{
		var codigoCie = document.getElementById(codigo_cie_c).value;
		var nombreCie = document.getElementById(nombre_cie_c).value;
				ajax_Cie_ci=nuevoAjax();	
				ajax_Cie_ci.open("POST","listadoCie10_tabla.php", true);
				ajax_Cie_ci.onreadystatechange=function()
				{
					if (ajax_Cie_ci.readyState==4) 
						{
						    var target = document.getElementById('listadoC');	
							target.innerHTML= ajax_Cie_ci.responseText;
						}
				}
			ajax_Cie_ci.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Cie_ci.send("codigo="+codigoCie+"&nombre="+nombreCie);
	}


//--------------------------------------------------------------
// FUNCIONES PARA UNIDADES MEDIDAS
//----------------------------------------------------------------	
	
function guardarUnidades(idunidad_med,nombre_med)
{
	ajax_Uni = nuevoAjax();
	ajax_Uni.open("POST","guardarUnidades.php", true);
	ajax_Uni.onreadystatechange = function()
	{
		if (ajax_Uni.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('idunidad_medida').value = '';
			document.getElementById('nombre_medida').value = '';
		}
	}
	ajax_Uni.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Uni.send("idunidad="+idunidad_med+"&nombre="+nombre_med);
}
	

function guardarUni(target,idunidad_me,nombre_me)
{
	var divtarget=document.getElementById(target);
	ajax_Unid = nuevoAjax();
	ajax_Unid.open("POST","guardarUnidades.php", true);
	ajax_Unid.onreadystatechange = function()
	{
		if (ajax_Unid.readyState==4)
		{	
			divtarget.innerHTML= ajax_Unid.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idunidad_medi').value = '';
			document.getElementById('nombre_medi').value = '';
		}
	}
	ajax_Unid.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Unid.send("idunidad="+idunidad_me+"&nombre="+nombre_me);
}	
	
function borrarUnidades(iduni)
{
	var id = document.getElementById(iduni).value;
	var target = document.getElementById('listadoUnidades');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Unida = nuevoAjax();
		ajax_Unida.open("POST", "borrarUnidades.php", true);
		ajax_Unida.onreadystatechange = function()
		{
			if (ajax_Unida.readyState==4)
			{
				 target.innerHTML = ajax_Unida.responseText;
			}
		}
		ajax_Unida.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Unida.send("idunidad="+id);
	}
}
	
function mostrarUnidades(id_m,nombre_m)
{ 
	document.getElementById('idunidad_medi').value = document.getElementById(id_m).value ;
	document.getElementById('nombre_medi').value = document.getElementById(nombre_m).value ;
}

function buscarUnidades(nombre_u)
	{
		var nombreUni = document.getElementById(nombre_u).value;
		
				ajax_Unidad=nuevoAjax();	
				ajax_Unidad.open("POST","listadoUnidades_tabla.php", true);
				ajax_Unidad.onreadystatechange=function()
				{
					if (ajax_Unidad.readyState==4) 
						{
						    var target = document.getElementById('listadoUni');	
							target.innerHTML= ajax_Unidad.responseText;
						}
				}
			ajax_Unidad.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Unidad.send("nombre="+nombreUni);
	}

	//--------------------------------------------------------------
// FUNCIONES PARA CATALOGO
//----------------------------------------------------------------

function guardarCatalogo(codigo_cata,descripcion_cata,unidad_cata,clasificacion_cata,cantidad_cata)
{
	ajax_Cata = nuevoAjax();
	ajax_Cata.open("POST","guardarCatalogo.php", true);
	ajax_Cata.onreadystatechange = function()
	{
		if (ajax_Cata.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('codigo_catalogo').value = '';
			document.getElementById('descripcion_catalogo').value = '';
			document.getElementById('unidad_catalogo').value = '';
			document.getElementById('clasificacion_catalogo').value = '';
			document.getElementById('cantidad_catalogo').value = '';
		}
	}
	ajax_Cata.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Cata.send("codigo="+codigo_cata+"&descripcion="+descripcion_cata+"&idunidad="+unidad_cata+"&idclasificacion="+clasificacion_cata+"&cantidad="+cantidad_cata);
}

function guardarCata(target,codigo_ca,descripcion_ca,unidad_ca,clasificacion_ca,cantidad_ca)
{
	var divtarget=document.getElementById(target);
	ajax_Catal = nuevoAjax();
	ajax_Catal.open("POST","guardarCatalogo.php", true);
	ajax_Catal.onreadystatechange = function()
	{
		if (ajax_Catal.readyState==4)
		{	
			divtarget.innerHTML= ajax_Catal.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('codigo_catal').value = '';
			document.getElementById('descripcion_catal').value = '';
			document.getElementById('unidad_catal').value = '';
			document.getElementById('clasificacion_catal').value = '';
			document.getElementById('cantidad_catal').value = '';
		}
	}
	ajax_Catal.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Catal.send("codigo="+codigo_ca+"&descripcion="+descripcion_ca+"&idunidad="+unidad_ca+"&idclasificacion="+clasificacion_ca+"&cantidad="+cantidad_ca);
}

function mostrarCatalogo(codigo_ca,descripcion_ca,unidad_ca,clasificacion_ca,cantidad_ca)
{
	document.getElementById('codigo_catal').value = document.getElementById(codigo_ca).value ;
	document.getElementById('descripcion_catal').value = document.getElementById(descripcion_ca).value ;
	document.getElementById('unidad_catal').value = document.getElementById(unidad_ca).value ;
	document.getElementById('clasificacion_catal').value = document.getElementById(clasificacion_ca).value ;
	document.getElementById('cantidad_catal').value = document.getElementById(cantidad_ca).value ;
	
}

function borrarCatalogo(codigo_c)
{
	var cod = document.getElementById(codigo_c).value;
	var target = document.getElementById('listadoCatalogo');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Catalo = nuevoAjax();
		ajax_Catalo.open("POST", "borrarCatalogo.php", true);
		ajax_Catalo.onreadystatechange = function()
		{
			if (ajax_Catalo.readyState==4)
			{
				 target.innerHTML = ajax_Catalo.responseText;
			}
		}
		ajax_Catalo.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Catalo.send("codigo="+cod);
	}
}

function buscarCatalogo(descrip_c)
	{
		var descripCata = document.getElementById(descrip_c).value;
		
				
				ajax_Catalog = nuevoAjax();	
				ajax_Catalog.open("POST","listadoCatalogo_tabla.php", true);
				ajax_Catalog.onreadystatechange=function()
				{
					if (ajax_Catalog.readyState==4) 
						{
						    var target = document.getElementById('listadoCata');	
							target.innerHTML= ajax_Catalog.responseText;
						}
				}
			ajax_Catalog.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Catalog.send("descripcion="+descripCata);
			
	}
	//--------------------------------------------------------------
// FUNCIONES PARA VIA MEDICAMENTO
//----------------------------------------------------------------
	
function guardarVia(iddroga_v,via_v)
{
	ajax_V = nuevoAjax();
	ajax_V.open("POST","guardarVia.php", true);
	ajax_V.onreadystatechange = function()
	{
		if (ajax_V.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('iddroga_via').value = '';
			document.getElementById('via_via').value = '';
		}
	}
	ajax_V.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_V.send("iddroga="+iddroga_v+"&via="+via_v);
}
	
function guardarVia_via(target,iddroga_ocul,via_ocul,iddro_via,vi_via)	
{
	var divtarget=document.getElementById(target);
	ajax_Vi = nuevoAjax();
	ajax_Vi.open("POST","guardarVia.php", true);
	ajax_Vi.onreadystatechange = function()
	{
		if (ajax_Vi.readyState==4)
		{	
			divtarget.innerHTML= ajax_Vi.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('iddroga_oculto').value = '';
			document.getElementById('via_oculto').value = '';
			document.getElementById('iddroga_vi').value = '';
			document.getElementById('via_vi').value = '';
		}
	}
	ajax_Vi.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Vi.send("iddroga="+iddro_via+"&via="+vi_via+"&iddro="+iddroga_ocul+"&vi="+via_ocul);

}	
	
function borrarVia(iddro_via,vi_via)
{
	var id = document.getElementById(iddro_via).value;
	var via_via = document.getElementById(vi_via).value;
	
	var target = document.getElementById('listadoVia');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Via = nuevoAjax();
		ajax_Via.open("POST", "borrarVia.php", true);
		ajax_Via.onreadystatechange = function()
		{
			if (ajax_Via.readyState==4)
			{
				 target.innerHTML = ajax_Via.responseText;
			}
		}
		ajax_Via.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Via.send("iddroga="+id+"&via="+via_via);	
	}
}
	
function mostrarVia(iddroga_ocul,via_ocul,iddro_via,vi_via)
{
	document.getElementById('iddroga_oculto').value = document.getElementById(iddroga_ocul).value ;
	document.getElementById('via_oculto').value = document.getElementById(via_ocul).value ;
	document.getElementById('iddroga_vi').value = document.getElementById(iddro_via).value ;
	document.getElementById('via_vi').value = document.getElementById(vi_via).value ;
}

function buscarVia(idd_via,vi_via)
	{
		var idd = document.getElementById(idd_via).value;
		
				ajax_Via_via=nuevoAjax();	
				ajax_Via_via.open("POST","listadoVia_tabla.php", true);
				ajax_Via_via.onreadystatechange=function()
				{
					if (ajax_Via_via.readyState==4) 
						{
						    var target = document.getElementById('listadoV');	
							target.innerHTML= ajax_Via_via.responseText;
						}
				}
			ajax_Via_via.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Via_via.send("iddroga="+idd);
			
	}

	//--------------------------------------------------------------
// FUNCIONES PARA DOSIS MEDICAMENTO
//----------------------------------------------------------------
	
function guardarDosis(iddroga_medi,dosis_medi)
{
	ajax_Do = nuevoAjax();
	ajax_Do.open("POST","guardarDosis.php", true);
	ajax_Do.onreadystatechange = function()
	{
		if (ajax_Do.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('iddroga_medicamento').value = '';
			document.getElementById('dosis_medicamento').value = '';
		}
	}
	ajax_Do.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Do.send("iddroga="+iddroga_medi+"&dosis="+dosis_medi);
}
	
function guardarDosisMedi(target,iddroga_ocul,dosis_ocul,iddroga_me,dosis_me)	
{
	var divtarget=document.getElementById(target);
	ajax_Dos = nuevoAjax();
	ajax_Dos.open("POST","guardarDosis.php", true);
	ajax_Dos.onreadystatechange = function()
	{
		if (ajax_Dos.readyState==4)
		{	
			divtarget.innerHTML= ajax_Dos.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('iddroga_oculto').value = '';
			document.getElementById('dosis_oculto').value = '';
			document.getElementById('iddroga_medica').value = '';
			document.getElementById('dosis_medica').value = '';
		}
	}
	ajax_Dos.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Dos.send("iddro="+iddroga_ocul+"&do="+dosis_ocul+"&iddroga="+iddroga_me+"&dosis="+dosis_me);
}	
	

function borrarDosis(iddroga_me,dosis_me)
{
	var id = document.getElementById(iddroga_me).value;
	var dosis_m = document.getElementById(dosis_me).value;
	
	var target = document.getElementById('listadoDosis');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Dosi = nuevoAjax();
		ajax_Dosi.open("POST", "borrarDosis.php", true);
		ajax_Dosi.onreadystatechange = function()
		{
			if (ajax_Dosi.readyState==4)
			{
				 target.innerHTML = ajax_Dosi.responseText;
			}
		}
		ajax_Dosi.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Dosi.send("iddroga="+id+"&dosis="+dosis_m);
	}
}
	
function mostrarDosis(iddroga_ocul,dosis_ocul,iddroga_me,dosis_me)
{ 	
	document.getElementById('iddroga_oculto').value = document.getElementById(iddroga_ocul).value;
	document.getElementById('dosis_oculto').value = document.getElementById(dosis_ocul).value;
	document.getElementById('iddroga_medica').value = document.getElementById(iddroga_me).value;
	document.getElementById('dosis_medica').value = document.getElementById(dosis_me).value;
}

function buscarDosis(iddroga_m)
	{
		var d=document.getElementById(iddroga_m).value;
		
				ajax_Dosis=nuevoAjax();	
				ajax_Dosis.open("POST","listadoDosis_tabla.php", true);
				ajax_Dosis.onreadystatechange=function()
				{
					if (ajax_Dosis.readyState==4) 
						{
						    var target = document.getElementById('listadoDo');	
							target.innerHTML= ajax_Dosis.responseText;
						}
				}
			ajax_Dosis.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Dosis.send("iddroga="+d);
			
	}

//--------------------------------------------------------------
// FUNCIONES PARA ESPECIALIDADES
//----------------------------------------------------------------	
	
function guardarEspecialidades(idespecialidad_espec,nombre_espec)
{
	ajax_Esp = nuevoAjax();
	ajax_Esp.open("POST","guardarEspecialidades.php", true);
	ajax_Esp.onreadystatechange = function()
	{
		if (ajax_Esp.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('idespecialidad_especial').value = '';
			document.getElementById('nombre_especial').value = '';
		}
	}
	ajax_Esp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Esp.send("idespecialidad="+idespecialidad_espec+"&nombre="+nombre_espec);

	

function guardarUni(target,idunidad_me,nombre_me)
{
	var divtarget=document.getElementById(target);
	ajax_Unid = nuevoAjax();
	ajax_Unid.open("POST","guardarUnidades.php", true);
	ajax_Unid.onreadystatechange = function()
	{
		if (ajax_Unid.readyState==4)
		{	
			divtarget.innerHTML= ajax_Unid.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idunidad_medi').value = '';
			document.getElementById('nombre_medi').value = '';
		}
	}
	ajax_Unid.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Unid.send("idunidad="+idunidad_me+"&nombre="+nombre_me);
}	
	
function borrarUnidades(iduni)
{
	var id = document.getElementById(iduni).value;
	var target = document.getElementById('listadoUnidades');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Unida = nuevoAjax();
		ajax_Unida.open("POST", "borrarUnidades.php", true);
		ajax_Unida.onreadystatechange = function()
		{
			if (ajax_Unida.readyState==4)
			{
				 target.innerHTML = ajax_Unida.responseText;
			}
		}
		ajax_Unida.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Unida.send("idunidad="+id);
	}
}
	
function mostrarUnidades(id_m,nombre_m)
{ 
	document.getElementById('idunidad_medi').value = document.getElementById(id_m).value ;
	document.getElementById('nombre_medi').value = document.getElementById(nombre_m).value ;
}

function buscarUnidades(nombre_u)
	{
		var nombreUni = document.getElementById(nombre_u).value;
		
				ajax_Unidad=nuevoAjax();	
				ajax_Unidad.open("POST","listadoUnidades_tabla.php", true);
				ajax_Unidad.onreadystatechange=function()
				{
					if (ajax_Unidad.readyState==4) 
						{
						    var target = document.getElementById('listadoUni');	
							target.innerHTML= ajax_Unidad.responseText;
						}
				}
			ajax_Unidad.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Unidad.send("nombre="+nombreUni);
	}


//--------------------------------------------------------------
// FUNCIONES PARA FINANCIACION
//----------------------------------------------------------------
	
function guardarFinanciacion(idfinanciacion_fin,nombre_fin)	
{
	ajax_Fin=nuevoAjax();
	ajax_Fin.open("POST","guardarFinanciacion.php", true);
	ajax_Fin.onreadystatechange = function()
	{
		if (ajax_Fin.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('idfinanciacion_financiacion').value ='';
			document.getElementById('nombre_financiacion').value ='';
		}
	}
	ajax_Fin.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Fin.send("idfinanciacion="+idfinanciacion_fin+"&nombre="+nombre_fin);
}
	

function guardarFinanc(target,idfinanc_fi,nombre_fi)	
{
	var divtarget=document.getElementById(target);
	ajax_Finan = nuevoAjax();
	ajax_Finan.open("POST","guardarFinanciacion.php", true);
	ajax_Finan.onreadystatechange = function()
	{
		if (ajax_Finan.readyState==4)
		{	
			divtarget.innerHTML= ajax_Finan.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idfinanc_financ').value = '';
			document.getElementById('nombre_financ').value = '';
		}
	}
	ajax_Finan.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Finan.send("idfinanciacion="+idfinanc_fi+"&nombre="+nombre_fi);
}	
	

function borrarFinanciacion(idfinanc_financ)
{
	var id = document.getElementById(idfinanc_financ).value;
	var target = document.getElementById('listadoFinanciacion');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Financ = nuevoAjax();
		ajax_Financ.open("POST", "borrarFinanciacion.php", true);
		ajax_Financ.onreadystatechange = function()
		{
			if (ajax_Financ.readyState==4)
			{
				 target.innerHTML = ajax_Financ.responseText;
			}
		}
		ajax_Financ.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Financ.send("idfinanciacion="+id);
	}
}
	
function mostrarFinanciacion(idfinanc_fi,nombre_fi)
{ 
	document.getElementById('idfinanc_financ').value = document.getElementById(idfinanc_fi).value ;
	document.getElementById('nombre_financ').value = document.getElementById(nombre_fi).value ;
}

function buscarFinanciacion(nombre_fin)
	{
		var nom_fin = document.getElementById(nombre_fin).value;
		
				ajax_Financiac=nuevoAjax();	
				ajax_Financiac.open("POST","listadoFinanciacion_tabla.php", true);
				ajax_Financiac.onreadystatechange=function()
				{
					if (ajax_Financiac.readyState==4) 
						{
						    var target = document.getElementById('listadoFinanc');	
							target.innerHTML= ajax_Financiac.responseText;
						}
				}
			ajax_Financiac.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Financiac.send("nombre="+nom_fin);
	}

	

//--------------------------------------------------------------
// FUNCIONES PARA DIAGNOSTICOS FINALES
//----------------------------------------------------------------

function guardarDiagnosticos(codigo_diagnost,descripcion_diagnost)
{
	ajax_Diag = nuevoAjax();
	ajax_Diag.open("POST","guardarDiagnosticos.php", true);
	ajax_Diag.onreadystatechange = function()
	{
		if (ajax_Diag.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('codigo_diagnosticos').value = '';
			document.getElementById('descripcion_diagnosticos').value = '';
		}
	}
	ajax_Diag.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Diag.send("codigo="+codigo_diagnost+"&descripcion="+descripcion_diagnost);
}
	

function guardarDiagnost(target,codigo_d,descripcion_d)
{
	var divtarget=document.getElementById(target);
	ajax_Diagn = nuevoAjax();
	ajax_Diagn.open("POST","guardarDiagnosticos.php", true);
	ajax_Diagn.onreadystatechange = function()
	{
		if (ajax_Diagn.readyState==4)
		{	
			divtarget.innerHTML= ajax_Diagn.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('codigo_diag').value = '';
			document.getElementById('descripcion_diag').value = '';
		}
	}
	ajax_Diagn.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Diagn.send("codigo="+codigo_d+"&descripcion="+descripcion_d);
}	
	
function borrarDiagnosticos(codigo_d)
{
	var cod = document.getElementById(codigo_d).value;
	var target = document.getElementById('listadoDiagnosticos');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Diagnos = nuevoAjax();
		ajax_Diagnos.open("POST", "borrarDiagnosticos.php", true);
		ajax_Diagnos.onreadystatechange = function()
		{
			if (ajax_Diagnos.readyState==4)
			{
				 target.innerHTML = ajax_Diagnos.responseText;
			}
		}
		ajax_Diagnos.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Diagnos.send("codigo="+cod);
	}
}
	
function mostrarDiagnosticos(codigo_d,descripcion_d)
{ 
	document.getElementById('codigo_diag').value = document.getElementById(codigo_d).value ;
	document.getElementById('descripcion_diag').value = document.getElementById(descripcion_d).value ;
}

function buscarDiagnosticos(diagnosticos_f)
	{
		var diag = document.getElementById(diagnosticos_f).value;
		
				ajax_Diagnosti=nuevoAjax();	
				ajax_Diagnosti.open("POST","listadoDiagnosticos_tabla.php", true);
				ajax_Diagnosti.onreadystatechange=function()
				{
					if (ajax_Diagnosti.readyState==4) 
						{
						    var target = document.getElementById('listadoDiag');	
							target.innerHTML= ajax_Diagnosti.responseText;
						}
				}
			ajax_Diagnosti.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Diagnosti.send("descripcion="+diag);
	}
		
//--------------------------------------------------------------
// FUNCIONES PARA TRASLADO
//----------------------------------------------------------------
	
function guardarTraslado(idtraslado_tras,lugar_tras)
{
	ajax_Tra = nuevoAjax();
	ajax_Tra.open("POST","guardarTraslado.php", true);
	ajax_Tra.onreadystatechange = function()
	{
		if (ajax_Tra.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('idtraslado_traslado').value = '';
			document.getElementById('lugar_traslado').value = '';
		}
	}
	ajax_Tra.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Tra.send("idtraslado="+idtraslado_tras+"&lugar="+lugar_tras);
}
	
function guardarTras(target,idtraslado_t,lugar_t)	
{
	var divtarget=document.getElementById(target);
	ajax_Tras = nuevoAjax();
	ajax_Tras.open("POST","guardarTraslado.php", true);
	ajax_Tras.onreadystatechange = function()
	{
		if (ajax_Tras.readyState==4)
		{	
			divtarget.innerHTML= ajax_Tras.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idtraslado_tras').value = '';
			document.getElementById('lugar_tras').value = '';
		}
	}
	ajax_Tras.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Tras.send("idtraslado="+idtraslado_t+"&lugar="+lugar_t);
}	
	

function borrarTraslado(idtraslado_t)
{
	var id = document.getElementById(idtraslado_t).value;
	var target = document.getElementById('listadoTraslado');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Trasla = nuevoAjax();
		ajax_Trasla.open("POST", "borrarTraslado.php", true);
		ajax_Trasla.onreadystatechange = function()
		{
			if (ajax_Trasla.readyState==4)
			{
				 target.innerHTML = ajax_Trasla.responseText;
			}
		}
		ajax_Trasla.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Trasla.send("idtraslado="+id);
	}
}
	
function mostrarTraslado(idtraslado_t,lugar_t)
{ 
	document.getElementById('idtraslado_tras').value = document.getElementById(idtraslado_t).value ;
	document.getElementById('lugar_tras').value = document.getElementById(lugar_t).value ;
}

function buscarTraslado(lugar_traslado)
	{
		var lugar = document.getElementById(lugar_traslado).value;
		
				ajax_Traslado=nuevoAjax();	
				ajax_Traslado.open("POST","listadoTraslado_tabla.php", true);
				ajax_Traslado.onreadystatechange=function()
				{
					if (ajax_Traslado.readyState==4) 
						{
						    var target = document.getElementById('listadoTras');	
							target.innerHTML= ajax_Traslado.responseText;
						}
				}
			ajax_Traslado.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Traslado.send("lugar="+lugar);
	}

	
	
//--------------------------------------------------------------
// FUNCIONES PARA PAIS
//----------------------------------------------------------------	
	
function guardarPais(idpais_pa,nombre_pa)
{
	ajax_Pai = nuevoAjax();
	ajax_Pai.open("POST","guardarPais.php", true);
	ajax_Pai.onreadystatechange = function()
	{
		if (ajax_Pai.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('idpais_pais').value = '';
			document.getElementById('nombre_pais').value = '';
		}
	}
	ajax_Pai.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Pai.send("idpais="+idpais_pa+"&nombre="+nombre_pa);
}
	

function guardarPais_pais(target,idpais_p,nombre_p)	
{
	var divtarget=document.getElementById(target);
	ajax_Pais = nuevoAjax();
	ajax_Pais.open("POST","guardarPais.php", true);
	ajax_Pais.onreadystatechange = function()
	{
		if (ajax_Pais.readyState==4)
		{	
			divtarget.innerHTML= ajax_Pais.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idpais_pais_p').value = '';
			document.getElementById('nombre_pais_p').value = '';
		}
	}
	ajax_Pais.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Pais.send("idpais="+idpais_p+"&nombre="+nombre_p);
}	
	
function borrarPais(idpais_p)
{
	var idpais = document.getElementById(idpais_p).value;
	var target = document.getElementById('listadoPais');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Pais_p = nuevoAjax();
		ajax_Pais_p.open("POST", "borrarPais.php", true);
		ajax_Pais_p.onreadystatechange = function()
		{
			if (ajax_Pais_p.readyState==4)
			{
				 target.innerHTML = ajax_Pais_p.responseText;
			}
		}
		ajax_Pais_p.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Pais_p.send("idpais="+idpais);
	}
}
	
function mostrarPais(idpais_p,nombre_p)
{ 
	document.getElementById('idpais_pais_p').value = document.getElementById(idpais_p).value ;
	document.getElementById('nombre_pais_p').value = document.getElementById(nombre_p).value ;
}

function buscarPais(nom_p)
	{
		var nombrePais = document.getElementById(nom_p).value;
		
				ajax_Pais_pa=nuevoAjax();	
				ajax_Pais_pa.open("POST","listadoPais_tabla.php", true);
				ajax_Pais_pa.onreadystatechange=function()
				{
					if (ajax_Pais_pa.readyState==4) 
						{
						    var target = document.getElementById('listadoPa');	
							target.innerHTML= ajax_Pais_pa.responseText;
						}
				}
			ajax_Pais_pa.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Pais_pa.send("nombre="+nombrePais);
	}


//--------------------------------------------------------------
// FUNCIONES PARA PROVINCIA
//----------------------------------------------------------------	

function guardarProvincia(idprovincia_provin,idpais_provin,nombre_provin)
{		
	ajax_Prov = nuevoAjax();
	ajax_Prov.open("POST","guardarProvincia.php", true);
	ajax_Prov.onreadystatechange = function()
	{
		if (ajax_Prov.readyState==4)
		{	
			//alert(ajax_Prov.responseText);
			document.getElementById('idprovincia_provincia').value = '';
			document.getElementById('idpais_provincia').value = '';	
			document.getElementById('nombre_provincia').value = '';
		}
	}
	ajax_Prov.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Prov.send("idprovincia="+idprovincia_provin+"&idpais="+idpais_provin+"&nombre="+nombre_provin);
}
	
function guardarProvin(target,idprovincia_pro,nombre_pro,idpais_pro)
{	
	var divtarget=document.getElementById(target);
	ajax_Provi = nuevoAjax();
	ajax_Provi.open("POST","guardarProvincia.php", true);
	ajax_Provi.onreadystatechange = function()
	{
		if (ajax_Provi.readyState==4)
		{	
			divtarget.innerHTML= ajax_Provi.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idprovincia_provin').value = '';
			document.getElementById('nombre_provin').value = '';
			document.getElementById('idpais_provin').value = '';
		}
	}
	ajax_Provi.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Provi.send("idprovincia="+idprovincia_pro+"&idpais="+idpais_pro+"&nombre="+nombre_pro);
}	
	
function borrarProvincia(idprovincia_pro)
{
	var idprov = document.getElementById(idprovincia_pro).value;
	var target = document.getElementById('listadoProvincia');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Provin = nuevoAjax();
		ajax_Provin.open("POST", "borrarProvincia.php", true);
		ajax_Provin.onreadystatechange = function()
		{
			if (ajax_Provin.readyState==4)
			{
				 target.innerHTML = ajax_Provin.responseText;
			}
		}
		ajax_Provin.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Provin.send("idprovincia="+idprov);
	}
}
	
function mostrarProvincia(idprovincia_pro,nombre_pro,idpais_pro)
{ 	
	document.getElementById('idprovincia_provin').value = document.getElementById(idprovincia_pro).value ;
	document.getElementById('idpais_provin').value = document.getElementById(idpais_pro).value ;
	document.getElementById('nombre_provin').value = document.getElementById(nombre_pro).value ;
}

function buscarProvincia(nombre_prov,idpais_pro)
	{
		var nomProv = document.getElementById(nombre_prov).value;
		var idpa = document.getElementById(idpais_pro).value;
		
				ajax_Provincia=nuevoAjax();	
				ajax_Provincia.open("POST","listadoProvincia_tabla.php", true);
				ajax_Provincia.onreadystatechange=function()
				{
					if (ajax_Provincia.readyState==4) 
						{
						    var target = document.getElementById('listadoProv');	
							target.innerHTML= ajax_Provincia.responseText;
						}
				}
			ajax_Provincia.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Provincia.send("nombre="+nomProv+"&idpais="+idpa);
	}
//----------------------------------------------------------------------
// FUNCIONES PARA comorbilidades_antecedentes
//----------------------------------------------------------------------
	
function guardarComorbilidades(codigo_comor,nombre_comor)	
{
	ajax_Como = nuevoAjax();
	ajax_Como.open("POST","guardarComorbilidades.php", true);
	ajax_Como.onreadystatechange = function()
	{
		if (ajax_Como.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('codigo_comorbilidades').value = '';
			document.getElementById('nombre_comorbilidades').value = '';
		}
	}
	ajax_Como.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Como.send("codigo="+codigo_comor+"&nombre="+nombre_comor);
}
	

function guardarComor(target,codigo_co,nombre_co)	
{
	var divtarget=document.getElementById(target);
	ajax_Comor = nuevoAjax();
	ajax_Comor.open("POST","guardarComorbilidades.php", true);
	ajax_Comor.onreadystatechange = function()
	{
		if (ajax_Comor.readyState==4)
		{	
			divtarget.innerHTML= ajax_Comor.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('codigo_como').value = '';
			document.getElementById('nombre_como').value = '';
		}
	}
	ajax_Comor.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Comor.send("codigo="+codigo_co+"&nombre="+nombre_co);
}	
	
function borrarComorbilidades(codigo_co)
{
	var cod = document.getElementById(codigo_co).value;
	var target = document.getElementById('listadoComorbilidades');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Comorbi = nuevoAjax();
		ajax_Comorbi.open("POST", "borrarComorbilidades.php", true);
		ajax_Comorbi.onreadystatechange = function()
		{
			if (ajax_Comorbi.readyState==4)
			{
				 target.innerHTML = ajax_Comorbi.responseText;
			}
		}
		ajax_Comorbi.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Comorbi.send("codigo="+cod);
	}
}
	
function mostrarComorbilidades(codigo_co,nombre_co)
{ 
	document.getElementById('codigo_como').value = document.getElementById(codigo_co).value ;
	document.getElementById('nombre_como').value = document.getElementById(nombre_co).value ;
}

function buscarComorbilidades(nombre_comorb)
	{
		var nombreCo = document.getElementById(nombre_comorb).value;
		
				ajax_Comorbili=nuevoAjax();	
				ajax_Comorbili.open("POST","listadoComorbilidades_tabla.php", true);
				ajax_Comorbili.onreadystatechange=function()
				{
					if (ajax_Comorbili.readyState==4) 
						{
						    var target = document.getElementById('listadoComo');	
							target.innerHTML= ajax_Comorbili.responseText;
						}
				}
			ajax_Comorbili.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			ajax_Comorbili.send("nombre="+nombreCo);
	}


//----------------------------------------------------------------------
// FUNCIONES PLANES FINANCIACION
//----------------------------------------------------------------------
	
function guardarPlanes(idplan_financi,idobra_financi,nombre_financi)	
{
	ajax_Pla = nuevoAjax();
	ajax_Pla.open("POST","guardarPlanes.php", true);
	ajax_Pla.onreadystatechange = function()
	{
		if (ajax_Pla.readyState==4)
		{	
			//alert(ajax_N.responseText);
			document.getElementById('idplan_financiacion').value = '';
			document.getElementById('idobra_financiacion').value = '';
			document.getElementById('nombre_financiacion').value = '';
		}
	}
	ajax_Pla.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Pla.send("idplan_financiacion="+idplan_financi+"&idobra_social="+idobra_financi+"&nombre="+nombre_financi);
}
	
function guardarPlan(target,idplan_fi,nombre_fi,idobra_fi)	
{
	var divtarget=document.getElementById(target);
	ajax_Plan = nuevoAjax();
	ajax_Plan.open("POST","guardarPlanes.php", true);
	ajax_Plan.onreadystatechange = function()
	{
		if (ajax_Plan.readyState==4)
		{	
			divtarget.innerHTML= ajax_Plan.responseText;
			//alert(ajax_N.responseText);
			document.getElementById('idplan_fin').value = '';
			document.getElementById('nombre_fin').value = '';
			document.getElementById('idobra_fin').value = '';
		}
	}
	ajax_Plan.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_Plan.send("idplan_financiacion="+idplan_fi+"&nombre="+nombre_fi+"&idobra_social="+idobra_fi);
}	
	
function borrarPLanes(idplan_fi)
{
	var id = document.getElementById(idplan_fi).value;
	var target = document.getElementById('listadoPlanes');
	var eliminar = confirm("De verdad desea eliminar este dato?")
	if (eliminar)
	{
		ajax_Plane = nuevoAjax();
		ajax_Plane.open("POST","borrarPlanes.php", true);
		ajax_Plane.onreadystatechange = function()
		{
			if (ajax_Plane.readyState==4)
			{
				 target.innerHTML = ajax_Plane.responseText;
			}
		}
		ajax_Plane.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Plane.send("idplan_financiacion="+id);
	}
}
	
function mostrarPlanes(idplan_fi,nombre_fi,idobra_fi)
{ 
	document.getElementById('idplan_fin').value = document.getElementById(idplan_fi).value ;
	document.getElementById('nombre_fin').value = document.getElementById(nombre_fi).value ;
	document.getElementById('idobra_fin').value = document.getElementById(idobra_fi).value ;
}

function buscarPlanes(nombre_f,idobra_f)
{	
    var nom = document.getElementById(nombre_f).value;
	var id = document.getElementById(idobra_f).value;
	ajax_Planes=nuevoAjax();	
	ajax_Planes.open("POST","listadoPlanes_tabla.php", true);
	ajax_Planes.onreadystatechange=function()
	{
	    if (ajax_Planes.readyState==4) 
		{
		    var target = document.getElementById('listadoPlan');	
			target.innerHTML= ajax_Planes.responseText;
			}
		}
		ajax_Planes.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax_Planes.send("idobra_social="+id+"&nombre="+nom);
    }
}