// Aquí adelante se crea el objeto que crea y actualiza el mosaico

var abrirycerrardeojos = 100;

var mosaico = {
	peticion: new PeticionXH('pp/php/pxh','m'),
	plantillaestilo: 'url(img/mosaico/«archivo»)',
	plantillatexto: '<h1>«titulo»</h1><br><h2>«resumen»</h2>',

	solicitar: function(campo,sub,identificador) {
		if($(".seleccion").attr("id")==campo){
		}
		else {
			$("#mosaico").animate({opacity:0},50);
			if (sub=="NA"){
				mosaico.peticion.enviar({m:campo},mosaico.cargar,1);
			}
			else {
				$(identificador).switchClass("filtro2","seleccion2");
				$(".seleccion").switchClass("seleccion","filtro");
				$(".seleccion2").switchClass("seleccion2","filtro2");
				mosaico.peticion.enviar({m:campo, s:sub},mosaico.cargar,1);
			}
		}
	},

	cargar: function() {
		temp = mosaico.peticion.xhr.responseXML.documentElement.getElementsByTagName('elemento');
		var n=temp.length;
		if(n<10){
			$("#i10").css('background-image','url(img/mosaico/escribe.jpg)');
			var A=[1,2,3,4,5,6,7,8,9];
		}
		else{
			var A=[1,2,3,4,5,6,7,8,9,10];
		}
		R=A.sort(function() {return 0.5 - Math.random()});
		for (var i=0; i<10; i++) {
			blaidi="#i"+R[i];
			if(i<n){
			bla=mosaico.plantillaestilo.insertarXML(temp[i]);
			blabla=mosaico.plantillatexto.insertarXML(temp[i]);
			$(blaidi).css('background-image', bla);				
			}
			else if(i>=n && i<9){
			bla="url(img/mosaico/gris"+R[i]+".jpg)";
			$(blaidi).css('background-image',bla);
			}
		}
		setTimeout(function(){$("#mosaico").animate({opacity:1},abrirycerrardeojos)},300);
	},

	generar: function(){//En construcción, el código funciona bien sin esta función :)
		obj('mosaico').innerHTML='';

	}
}

// Aquí adelante se crea el objeto que crea y actualiza el submenú cuando es seleccionada una opción del menú del mosaico que admite una subespecificación
var subMenu =  {
	peticion: new PeticionXH('pp/php/pxh','s'),
	plantilla: '<div id="«clave»">«menu» <div onclick=mosaico.solicitar("«mosaico»",«clave»,this) class="filtro2"></div></div>',

	solicitar: function(campo) {
		subMenu.peticion.enviar({s:campo},subMenu.cargar,1);
	},

	cargar: function(){
		obj('sub').innerHTML='';
		temp = subMenu.peticion.xhr.responseXML.documentElement.getElementsByTagName('elemento');
		var n=temp.length;
		for (var i=0; i<n; i++) {
			obj('sub').innerHTML+=subMenu.plantilla.insertarXML(temp[i]);
		}
	}

}


$(document).ready(function () {

$().mouseover().mouseout();

	$(".ContenedorImagenM").mouseover(function(){
		ide = $(this).attr("id");
	 	$(this).children(".info").animate({opacity: 1}, 200);
	 }).mouseout(function(){
		$(this).children(".info").animate({opacity: 0}, 200);
		
	});

	$(".filtro, .seleccion, .subseleccion, .afueraF, .afueraS, .subfiltro, .filtro2, .seleccion2").on("click",function(){
		var tiempo=300;
		var tiempo2=400;
		$(".filtro, .seleccion, .subseleccion, .afueraF, .afueraS, .subfiltro, .filtro2, .seleccion2").stop(true,true);

		if ($(this).attr("class") == "filtro" ) {
			$(this).switchClass("filtro","seleccion",tiempo);
			$(".seleccion").switchClass("seleccion","filtro",tiempo);
			$(".subseleccion").switchClass("subseleccion","subfiltro",tiempo);
		}
		else if ($(this).attr("class") == "subfiltro") {
			$(this).switchClass("subfiltro","subseleccion",tiempo);
			var distancia=-108;
			//Aquí necesitamos poner la orden para cambiar el submenú			
			var identificador = $(this).parent().attr("id");
			subMenu.solicitar(identificador);
			if (identificador == "numero") {var arriba =  distancia.toString()+"px";}
			else if (identificador == "facultad") {var arriba = (distancia-32.5).toString()+"px";}
			else if (identificador == "genero") {var arriba = (distancia-65).toString()+"px";}

			$(".filtros").children().not("#"+identificador).animate({marginRight:'800px',opacity:0},tiempo);
			$(this).parent().animate({marginTop:arriba},tiempo2);
			$(".subfiltros").animate({marginRight:"-180px",opacity:1},tiempo2);
			}
		else if ($(this).attr("class") == "subseleccion") {
			if(!$(".seleccion2")[0]){
				$(".subseleccion").switchClass("subseleccion","subfiltro",tiempo);
			}
			var identificador = "#"+$(this).parent().attr("id");
			$(".filtros").children().not(identificador).animate({marginRight:'0px',opacity:1},tiempo);
			$(this).parent().animate({marginTop:"10px"},tiempo2);			
			$(".subfiltros").animate({marginRight:"800px",opacity:0},tiempo2);
		}
	});


});

