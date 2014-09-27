<html>
<head>
  <link rel="stylesheet" type="text/css" href="pp/css/cachun.css"/>
  <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Averia+Sans+Libre">
  <script type="text/javascript" src="js/jquery/jquery.min.js"></script>
  <script type="text/javascript" src="js/jui/js/jquery-ui-1.10.4.js"></script>
  <script type="text/javascript" src="js/gsap/TwinLite.min.js"></script>

  <script type="text/javascript" src="admin/js/ajax.js"></script>
  <script type="text/javascript" src="admin/js/cadenas.js"></script>
  <script type="text/javascript" src="admin/js/interfaz.js"></script>
  <script type="text/javascript" src="pp/principal.js"></script>
  <script type="text/javascript" src="js/plugins/blurjs.min.js"></script>
</head>
<body class="cuerpo">

<div id="pagina">
	<header class="encabezado">
		<div class="LogoFiltroMenu">
			<a href='#principal'><img id="logo" src="img/logoT1.png"></a>
	    	<img class="PantallaChica" id="MenuPantallaChica" src="img/menu.gif" width="100">
	    	<img class="PantallaChica" id="FiltroPantallaChica" src="img/filtro.png" width="100">
		</div>
	        <nav><ul>
	            <li><a href='p1/index.php'><div>¿Cachún?</div></a></li>
	            <li><a href='#gallery'><div>Catálogo</div></a></li>
	            <li><a href='#blog'><div>Publica</div></a></li>
	            <li><a href='#links'><div>Únete</div></a></li>
	            <li><a href='#sitemap'><div>Otros Proyectos</div></a></li>
	            <li><a href='#Video'><div>Video</div></a></li>
	       </nav></ul>
		<div class="ContenedorIconosContacto">
			<a href="mailto:mochildaeideas@gmail.com"><img class="IconoArriba" src="img/mail-icon.jpg"></a>
			<a href="https://www.facebook.com/RevistaCachun"><img class="IconoArriba" src="img/fb-icon.jpg"></a>
			<a href="https://twitter.com/RevistaCachun"><img class="IconoArriba" src="img/tw-icon.jpg"></a> 
			<a href="https://twitter.com/RevistaCachun"><img class="IconoArriba" src="img/tw-icon.jpg"></a> 
		</div>
	</header>
</div>
<div id="separadorH"></div>

<div class="cuerpo2">

	<div class="ContenedorFiltros">
		<div class="filtros">
			<div id="recientes">recientes <div id="recientes" class="seleccion" onclick=mosaico.solicitar("recientes","NA",this)></div></div>
			<div id="vistos">+ vistos <div onclick=mosaico.solicitar("vistos","NA",this) id="vistos" class="filtro"></div></div>
			<div id="ilustraciones">ilustraciones <div onclick=mosaico.solicitar('ilustraciones',"NA",this) id="ilustraciones" class="filtro"></div></div>
			<div id="numero">número <div class="subfiltro"></div></div>
			<div id="facultad">facultad <div class="subfiltro"></div></div>
			<div id="genero">géneros <div class="subfiltro"></div></div>
		</div>
		<div id="sub" class="subfiltros">
		</div>
	</div>

	<div id="separador"></div>

	<div id="mosaico">
		<?php
			require("bd.php");
			conectar();
			$consulta = "SELECT t.archivo,t.resumen FROM trabajos AS t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo ORDER BY t.trabajo DESC";
			$resultado = ejecutar($consulta);
			$i=1;
			echo '<div class="MosaicoColumna1">';
			while ($row = pg_fetch_array($resultado) and $i<11) {
				echo '<div class="ContenedorImagenM" id="i'.$i.'"><div class="MosaicoImagen" style="background:url(img/mosaico/'.$row[0].');"></div><div id="info'.$i.'" class="info">'.$row[1].'</div></div>';
				if ($i==2 || $i==4 || $i==7) {
		  			echo '</div><div class="MosaicoColumna">';
		  		}
				$i=$i+1;
			}
			echo '</div>';
		?>
	</div>

</div>


</body>
</html>
