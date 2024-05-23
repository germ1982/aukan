function get_datos_ristro(ristro,dato_mostrar)
     {
          var cantidad_arrobas = get_cantidad_arrobas(ristro);
          var dato_return = "";

          if(validar_arrobas(cantidad_arrobas))
               {
                    var dato_return = definir_tipo_dni_a_mostrar(cantidad_arrobas,ristro,dato_mostrar);
               } 

          return ucWords(dato_return);
     }

function get_cantidad_arrobas(ristro)
     {
          var arrobas = 0;
          for(var i=0;i<=ristro.length;i++)
               {
                    if(ristro[i]=='@')
                         {arrobas++;};
               };
          return arrobas;
     }

function validar_arrobas(cantidad)
     {

          
          switch(cantidad) 
               {
                    case 7:
                         return true;
                         break;
                    case 8:
                         return true;
                         break;
                    case 14:
                         return true;
                         break;
                    case 16:
                         return true;
                         break;
                    default:
                         return false;
               }
     }

function definir_tipo_dni_a_mostrar(cantidad_arrobas,ristro,dato_mostrar)
     {

          var dato_return='';
          switch(cantidad_arrobas) {
               case 7:
                    dato_return = get_dato_de_ristro_de_7_arrobas(ristro,dato_mostrar);
                    break;
               case 8:
                    dato_return = get_dato_de_ristro_de_8_arrobas(ristro,dato_mostrar);
                    break;
               case 14:
                    dato_return = get_dato_de_ristro_de_16_arrobas(ristro,dato_mostrar);
                    break;
               case 16:
                    dato_return = get_dato_de_ristro_de_16_arrobas(ristro,dato_mostrar);
                    break;
          }
          return dato_return;
     }

function get_dato_de_ristro_de_7_arrobas(ristro,dato_mostrar)
     {
          var dato_return="";
          switch(dato_mostrar) {
               case 'apellido':
                              dato_return = get_dato_ristro(ristro,0,1);
                              break;
               case 'nombre':
                              dato_return = get_dato_ristro(ristro,1,2);
                              break;
               case 'dni':
                              dato_return = get_dato_ristro(ristro,3,4);
                              break;
               case 'nacimiento':
                              dato_return = get_dato_ristro(ristro,5,6);
                              break;
               case 'genero':
                              dato_return = get_dato_ristro(ristro,2,3);
                              break;
               case 'ejemplar':
                              dato_return = get_dato_ristro(ristro,4,5);
                              break;
               case 'tramite':
                              dato_return = get_dato_ristro_con_arroba_inicial_y_distancia(ristro,6,9);
                              break;
          }

          return dato_return;
     }

function get_dato_de_ristro_de_8_arrobas(ristro,dato_mostrar)
     {
          
          var dato_return='';
          switch(dato_mostrar) {
               case 'apellido':
                              dato_return = get_dato_ristro(ristro,0,1);
                              break;
               case 'nombre':
                              dato_return = get_dato_ristro(ristro,1,2);
                              break;
               case 'dni':
                              dato_return = get_dato_ristro(ristro,3,4);
                              break;
               case 'nacimiento':
                              dato_return = get_dato_ristro(ristro,5,6);
                              break;
               case 'genero':
                              dato_return = get_dato_ristro(ristro,2,3);
                              break;
               case 'ejemplar':
                              dato_return = get_dato_ristro(ristro,4,5);
                              break;
               case 'tramite':
                              dato_return = get_dato_ristro(ristro,6,7);
                              break;
          }

          return dato_return;
     }



function get_dato_de_ristro_de_16_arrobas(ristro,dato_mostrar)
     {
          var dato_return="";
          switch(dato_mostrar) {
               case 'apellido':
                              dato_return = get_dato_ristro(ristro,3,4);
                              break;
               case 'nombre':
                              dato_return = get_dato_ristro(ristro,4,5);
                              break;
               case 'dni':
                              dato_return = get_dato_ristro(ristro,0,1);
                              break;
               case 'nacimiento':
                              dato_return = get_dato_ristro(ristro,6,7);
                              break;
               case 'genero':
                              dato_return = get_dato_ristro(ristro,7,8);
                              break;
               case 'ejemplar':
                              dato_return = get_dato_ristro(ristro,1,2);
                              break;
               case 'tramite':
                              dato_return = get_dato_ristro(ristro,8,9);
                              break;
          }

          return dato_return;

     }

function get_dato_ristro_con_arroba_inicial_y_distancia(ristro,ArrobaInicial,Distancia)
     {

               var Len = ristro.length;
               var arrobas = 0;
               var Dato, InicioReal, FinalReal;
               for(var i=0;i<=Len;i++)
                    {
                         if (arrobas==ArrobaInicial)
                              {InicioReal = i+1;};
                         if(ristro[i]=='@')
                              {arrobas++;};
                    }
               FinalReal = InicioReal + Distancia;
               Dato = ristro.substring(InicioReal,FinalReal);
               return Dato;
     }


function get_dato_ristro(ristro,arroba_inicial,arroba_final)
		{

				var Len = ristro.length;
				var arrobas = 0;
				var Dato, InicioReal, FinalReal;
				for(var i=0;i<=Len;i++)
					{

						if (arrobas==arroba_inicial)
							{InicioReal = i+1;};
						if (arrobas==arroba_final)
							{FinalReal = i;};
						if(ristro[i]=='@')
							{arrobas++;};
					}

				Dato = ristro.substring(InicioReal,FinalReal);
				return Dato;
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