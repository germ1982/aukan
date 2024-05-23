function reposicionaMenu (objeto,event)
{
	posicionY = event.clientY+document.body.scrollTop-10;
	posicionX = event.clientX+document.body.scrollLeft-8;
	document.getElementById(objeto).style.top=posicionY+'px';
	document.getElementById(objeto).style.left=posicionX+'px';
}

function muestraMenu (objeto,event)
{
	document.getElementById(objeto).style.display="inline";
	reposicionaMenu (objeto,event);
}

function insertarPermiso (objeto, archivo)
{
	destino = document.getElementById(objeto);
	if ( destino.value == "" )
	{
		destino.value = archivo;
	}
	else
	{
		destino.value = archivo + ", " + destino.value;
	}
}

function adminDelete (objeto, id, newuserdiv, userlist)
{
	var contenedor = document.getElementById(objeto);
	var datosusuario = document.getElementById(newuserdiv);
	ajaxAdmInsertar = nuevoAjax();
	ajaxAdmInsertar.open("POST", "userDelete.php", true);
	ajaxAdmInsertar.onreadystatechange = function()
	{
		if ( ajaxAdmInsertar.readyState == 4 )
		{
			contenedor.innerHTML = ajaxAdmInsertar.responseText;
		}
	}
	ajaxAdmInsertar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajaxAdmInsertar.send("id="+id);
	datosusuario.style.display="none";
	adminListarUsuarios(userlist);
}

function adminUpdate (objeto, field_user, field_pass, field_mail, field_rol, field_idpr, newuserdiv, userlist)
{
	var contenedor = document.getElementById(objeto);
	var username = document.getElementById(field_user).value;
	var password = document.getElementById(field_pass).value;
	var email = document.getElementById(field_mail).value;
	var rol = document.getElementById(field_rol).value;
	var idprof = document.getElementById(field_idpr).value;
	var datosusuario = document.getElementById(newuserdiv);
	var id = document.getElementById('valorTemporal').value;
	ajaxAdmInsertar = nuevoAjax();
	ajaxAdmInsertar.open("POST", "userUpdate.php", true);
	ajaxAdmInsertar.onreadystatechange = function()
	{
		if ( ajaxAdmInsertar.readyState == 4 )
		{
			contenedor.innerHTML = ajaxAdmInsertar.responseText;
		}
	}
	ajaxAdmInsertar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajaxAdmInsertar.send("id="+id+"&username="+username+"&password="+password+"&email="+email+"&rol="+rol+"&idprof="+idprof);
	datosusuario.style.display="none";
	adminListarUsuarios(userlist);
}

function adminInsertar (objeto, field_user, field_pass, field_mail, field_rol, field_idpr, newuserdiv, userlist)
{
	var contenedor = document.getElementById(objeto);
	var username = document.getElementById(field_user).value;
	var password = document.getElementById(field_pass).value;
	var email = document.getElementById(field_mail).value;
	var rol = document.getElementById(field_rol).value;
	var idprof = document.getElementById(field_idpr).value;
	var datosusuario = document.getElementById(newuserdiv);
	ajaxAdmInsertar = nuevoAjax();
	ajaxAdmInsertar.open("POST", "userInsert.php", true);
	ajaxAdmInsertar.onreadystatechange = function()
	{
		if ( ajaxAdmInsertar.readyState == 4 )
		{
			contenedor.innerHTML = ajaxAdmInsertar.responseText;
		}
	}
	ajaxAdmInsertar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajaxAdmInsertar.send("username="+username+"&password="+password+"&email="+email+"&rol="+rol+"&idprof="+idprof);
	datosusuario.style.display="none";
	adminListarUsuarios(userlist);
}

function adminSuspender (objeto, userlist)
{
	var contenedor = document.getElementById(objeto);
	var id = document.getElementById('valorTemporal').value;
	ajaxAdmInsertar = nuevoAjax();
	ajaxAdmInsertar.open("POST", "userSuspende.php", true);
	ajaxAdmInsertar.onreadystatechange = function()
	{
		if ( ajaxAdmInsertar.readyState == 4 )
		{
			contenedor.innerHTML = ajaxAdmInsertar.responseText;
		}
	}
	ajaxAdmInsertar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajaxAdmInsertar.send("id="+id);
	adminListarUsuarios(userlist);
}

function showBorrar (objeto)
{
	var contenedor = document.getElementById(objeto);
	var id = document.getElementById('valorTemporal').value;
	contenedor.style.display = "block";
	ajaxAdmListar = nuevoAjax();
	ajaxAdmListar.open("POST", "userConfirma.php", true);
	ajaxAdmListar.onreadystatechange = function()
	{
		if ( ajaxAdmListar.readyState == 4 )
		{
			contenedor.innerHTML = ajaxAdmListar.responseText;
		}
	}
	ajaxAdmListar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajaxAdmListar.send("id="+id);
}

function showUser (objeto)
{
	var contenedor = document.getElementById(objeto);
	var id = document.getElementById('valorTemporal').value;
	contenedor.style.display = "block";
	ajaxAdmListar = nuevoAjax();
	ajaxAdmListar.open("POST", "userShow.php", true);
	ajaxAdmListar.onreadystatechange = function()
	{
		if ( ajaxAdmListar.readyState == 4 )
		{
			contenedor.innerHTML = ajaxAdmListar.responseText;
		}
	}
	ajaxAdmListar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajaxAdmListar.send("id="+id);
}

function adminListarUsuarios (objeto)
{
	var contenedor = document.getElementById(objeto);
	ajaxAdmListar = nuevoAjax();
	ajaxAdmListar.open("POST", "userList.php", true);
	ajaxAdmListar.onreadystatechange = function()
	{
		if ( ajaxAdmListar.readyState == 4 )
		{
			contenedor.innerHTML = ajaxAdmListar.responseText;
		}
	}
	ajaxAdmListar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajaxAdmListar.send(null);
}

function adminListarPacientes (objeto)
{
	var contenedor = document.getElementById(objeto);
	ajaxAdmListar = nuevoAjax();
	ajaxAdmListar.open("POST", "tablasPacientes.php", true);
	ajaxAdmListar.onreadystatechange = function()
	{
		if ( ajaxAdmListar.readyState == 4 )
		{
			contenedor.innerHTML = ajaxAdmListar.responseText;
		}
	}
	ajaxAdmListar.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajaxAdmListar.send(null);
}

function adminVerLog (objeto, cuallog)
{
	var contenedor = document.getElementById(objeto);
	ajax = nuevoAjax();
	ajax.open("POST", "showLog.php", true);
	ajax.onreadystatechange = function()
	{
		if ( ajax.readyState == 4 )
		{
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("thislog="+cuallog);
}

function adminOpcGuarda (objeto, objetovalor)
{
	var contenedor = document.getElementById(objeto);
	var valor = document.getElementById(objetovalor).value;
	iraurl = "admin/guardar.php?tag="+objetovalor+"&valor="+valor+"&random="+Math.random();
	contenedor.style.display='inline';
	ajax = nuevoAjax();
	ajax.open("POST", "admin/guardar.php", true);
	ajax.onreadystatechange = function()
	{
		if ( ajax.readyState == 4 )
		{
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("tag="+objetovalor+"&valor="+valor);
	contenedor.style.display='none';
}
function cerrar_sesion(user)
{
    ajax_sesion = nuevoAjax();
	ajax_sesion.open("POST", "comun/cerrar_sesion.php", true);
	ajax_sesion.onreadystatechange = function()
	{
		if ( ajax_sesion.readyState == 4 )
		{
		//alert(ajax_sesion.responseText);
		}
	}
	ajax_sesion.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_sesion.send("userName="+user);

}
function cambiarPassword(anterior_pass,nueva_pass1,nueva_pass2,user)
{
    var pass1 = document.getElementById(nueva_pass1).value;
    var pass2 = document.getElementById(nueva_pass2).value;
    var ant_pass = document.getElementById(anterior_pass).value;
    if ( pass1 != pass2 )
	alert("Contraseñas ingresadas distintas...ingrese nuevamente la contraseña");
    else
    {
	ajax_pass = nuevoAjax();
	ajax_pass.open("POST", "../admin/cambiarPassword.php", true);
	ajax_pass.onreadystatechange = function()
	{
	    if ( ajax_pass.readyState == 4 )
	    {
		alert(ajax_pass.responseText);			
	    }			
        }
	ajax_pass.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_pass.send("userName="+user+"&password="+pass1+'&anterior_pass='+ant_pass);
    }		
}
function asignarRol(userIDProf,userRol)
{
    var idprof = document.getElementById(userIDProf).value;
    ajax_rol = nuevoAjax();
	ajax_rol.open("POST", "asignarRol.php", true);
	ajax_rol.onreadystatechange = function()
	{
		if ( ajax_rol.readyState == 4 )
		{//alert(ajax_rol.responseText);
		//alert(ajax_rol.responseText);
		    document.getElementById(userRol).value=ajax_rol.responseText;
		}
	}
	ajax_rol.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax_rol.send("idprofesional="+idprof);
}
