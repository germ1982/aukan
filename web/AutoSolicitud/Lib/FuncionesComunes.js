	function getValueDeLista(ListaDeDatos, CampoDataId, IdValue){
		var x = document.getElementById(ListaDeDatos).options.length;
		var v, Id, devolver;
		for (var i= 0;i<x;i++)
		{
			v = document.getElementById(ListaDeDatos).options[i].value;
			Id = $('#' + ListaDeDatos + ' [value="' + v + '"]').data(CampoDataId);
			if (Id == IdValue)
			{
				devolver = v;
			}
		}
		return devolver;}



	function getIdVinculoDeListaGenerica(ListaDeDatos, IdValue){

		var x = document.getElementById(ListaDeDatos).options.length;
		var v, Id, devolver, IdVinculo;
		for (var i= 0;i<x;i++)
		{
			v = document.getElementById(ListaDeDatos).options[i].value;
			IdVinculo = $('#' + ListaDeDatos + ' [value="' + v + '"]').data('idvinculo');
			Id = $('#' + ListaDeDatos + ' [value="' + v + '"]').data('idvalue');
			if (Id == IdValue)
			{
				devolver = IdVinculo;
			}
		}
		return devolver;}

	
	function getValueDeListaGenerica(ListaDeDatos, IdValue){

		var x = document.getElementById(ListaDeDatos).options.length;

		var v, Id, devolver;
		for (var i= 0;i<x;i++)
		{

			v = document.getElementById(ListaDeDatos).options[i].value;
			//alert(v);
			Id = $('#' + ListaDeDatos + ' [value="' + v + '"]').data('idvalue');
			
			//alert('length:' + x + ', i: ' + i + ', Value: ' + v  + ', IdValue: ' + Id);

			if (Id == IdValue)
			{
				devolver = v;
			}
		}
		return devolver;}

	function getIdValueDeListaGenerica(ListaDeDatos, DataValue)	{
		var x = document.getElementById(ListaDeDatos).options.length;
		var v, devolver;
		for (var i= 0;i<x;i++)
		{
			v = document.getElementById(ListaDeDatos).options[i].value;
			if (v == DataValue)
			{
				devolver = $('#' + ListaDeDatos + ' [value="' + v + '"]').data('idvalue');
			}
		}
		return devolver;}



	function MostrarListadoGenerico(ListaDeDatos){
   		var x = document.getElementById(ListaDeDatos).options.length;
		var v;
		var devolver = "";
		for (var i= 0;i<x;i++)
		{
			v = document.getElementById(ListaDeDatos).options[i].value;
			devolver = devolver + v + "\n";
		}
		return devolver;}

   	function CrearArrayConValuesDeListaVinculada(DataList, IdVinculo){
		var x = document.getElementById(DataList).options.length;
		var val, id;
		var devolver = [];
		var aux = 0
		for(var i=0;i<x;i++)
		{	
			val= document.getElementById(DataList).options[i].value;
			idv = $('#' + DataList + ' [value="' + val + '"]').data('idvinculo');
			if (idv == IdVinculo)
			{
				devolver[aux] = val;
				aux++;
			}
		}
		return devolver;}

	function ListarArrayNumericamente(ArrayComun){
		var x = ArrayComun.length;
		var devolver= "";
		var val;
		for(var i=0;i<x;i++)
		{	
				val = ArrayComun[i];
				devolver = devolver + i + '. ' + val + '\n';
		}
		return devolver;}

	function ValidarIntervaloNumerico(NumSeleccionado,NumRango)
		{
			var txt = "Debe Ingresar un numero entre 0 y " + (NumRango-1);
			if (!/^([0-9])*$/.test(NumSeleccionado))
				{
					alert(txt);
					return false;
				}

			if (NumSeleccionado>=NumRango)
				{
					alert(txt);
					return false;
				}

			if (NumSeleccionado=="")
				{
					alert(txt);
					return false;
				}
			return true;
		}

	function ValidarDatoExistente(DataList, Dato){
   		//si devuelve true el dato es real en el listado
   		//Si devuelve falso es porque el dato esta vacio o no existeen el listado
   		if (Dato == "") 
   		{
   			alert("Hay campos Vacios!!");
			return false;
   		}
   		var x = document.getElementById(DataList).options.length;
		var val;
		for(var i=0;i<x;i++)
		{	
			val= document.getElementById(DataList).options[i].value;
			if (Dato == val)
			{
				return true;
			}
		}
		alert(Dato + ": Dato no existente");
		return false;}

	function ValidarDatoRepetido(DataList, Dato)
			{
				//si ya existe un dato devuelve true
		   		if (Dato == "") 
		   		{
		   			alert("Hay campos Vacios!!");
					return true;
		   		}
		   		var x = document.getElementById(DataList).options.length;
				var val;
				for(var i=0;i<x;i++)
				{	
					val= document.getElementById(DataList).options[i].value;
					if (Dato == val)
					{
						alert('Ya existe ' + Dato);
						return true;
					}
			}
			return false;}
		
	function getFechaActual()
		{
			var f = new Date();
			var d = f.getDate();
			if (d<=9) {d="0"+d;}
			var m = f.getMonth() + 1;
			if (m<=9) {m="0"+m;}
			var a = f.getFullYear();
			var aux = d + "/" + m + "/" + a;
			return aux;
		}

	function getHoraActual()
		{
			var f = new Date();
			var h = f.getHours()
			if (h<=9) {h="0"+h;}
			var m = f.getMinutes()
			if (m<=9) {m="0"+m;}
			var aux = h + ":" + m;
			return aux;
		}

	function MostrarFechaActual(InputFecha)
		{
			var date = new Date();

			var day = date.getDate();
			var month = date.getMonth() + 1;
			var year = date.getFullYear();

			if (month < 10) month = "0" + month;
			if (day < 10) day = "0" + day;

			var today = year + "-" + month + "-" + day;   
			document.getElementById(InputFecha).value = today;
		}

	function MostrarFecha(InputFecha, Fecha)
		{
			//el parametro fecha ya debe venir formateado en dd/mm/aaaa, el digito de separacion es indiferente
			var date = new Date();

			var day = Fecha.substring(0,2);

			var month = Fecha.substring(3,5);

			var year = Fecha.substring(6,10);

			var today = year + "-" + month + "-" + day;   
			//alert(today);
			document.getElementById(InputFecha).value = today;
		}

	function QuitarEspacios(cadena)
		{
			var Car = "";
			var tope = cadena.length;
			var sCadenaSinBlancos = "";
			for (var i=0; i < tope; i++) 
				{
					Car = cadena.charAt(i);
					sCadenaSinBlancos = sCadenaSinBlancos + Car;
					sCadenaSinBlancos = sCadenaSinBlancos.trim();
				}

			return sCadenaSinBlancos;
		}

	function TildarTecnicos(Tecnicos, NombreCheckbox)
		{		
			var tec = "";
			var n ="";
			$("input:checkbox").each(function(){
				tec = $(this).val();
				n = Tecnicos.search(tec);
				if(n>=0)
					{
						tec = NombreCheckbox + QuitarEspacios(tec);
						document.getElementById(tec).checked = true;
						tec = "";
					}
			});
		}

	function FiltrarTabla(InputFiltro, TablaFiltro,NroCampoAFiltrar) 
		{
			//Si la tabla tarda mucho en cargar no tiene sentido, investigar ajax
			var input, filter, table, tr, td, i;
			input = document.getElementById(InputFiltro);
			table = document.getElementById(TablaFiltro);

			filter = input.value.toUpperCase();
			alert(filter);
			
			tr = table.getElementsByTagName("tr");
			for (i = 0; i < tr.length; i++) 
			{
				//aca busca por toda la fila, ver de organizar los filtros aca.

				td = tr[i].getElementsByTagName("td")[NroCampoAFiltrar];


				if (td) {
						  if (td.innerHTML.toUpperCase().indexOf(filter) > -1) 
						  	{tr[i].style.display = "";} 
						  else 
						  	{tr[i].style.display = "none";}
						}       
			}
			
		}

	function ucWords(string)
		{
			 var arrayWords;
			 var returnString = "";
			 var len;
			 arrayWords = string.split(" ");
			 len = arrayWords.length;
			 for(i=0;i < len ;i++)
				{
					  if(i != (len-1))
						  {
						  	returnString = returnString+ucFirst(arrayWords[i])+" ";
						  }
					  else
						  {
							returnString = returnString+ucFirst(arrayWords[i]);
						  }
				 }
			 return returnString;
		}

	function ucFirst(string)
		{
			 return string.substr(0,1).toUpperCase()+string.substr(1,string.length).toLowerCase();
		}