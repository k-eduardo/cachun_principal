<?php

	$parametros = $_SERVER['QUERY_STRING'];//analizar la cadena de consulta
	parse_str($parametros);//poner en variables de PHP las variables de la cadena de consulta
	//$s:subfiltro(numero,facultad,genero)

	require("../../bd.php");
	require("../../admin/xml.php");

	conectar();

//	$PRUEBA = 1;

	$formato = 'xml';//a menos que se especifique otra cosa, el resultado será un archivo xml

	$principal = new nodo('submenu');

	switch($c)//determinar el contexto de la solicitud
		{
			case 's':
				switch ($s) {
					case "facultad":
						$consulta="WITH ae AS (SELECT a.autor,e.nombre_corto,e.entidad FROM autores as a JOIN entidades as e ON a.facultad=e.entidad), ta AS (SELECT autor, count(*) as trabajos FROM trabajos GROUP BY autor) SELECT 'facultad' as mosaico, ae.nombre_corto AS menu,ae.entidad AS clave FROM ae JOIN ta ON ae.autor=ta.autor GROUP BY ae.nombre_corto,ae.entidad ORDER BY sum(trabajos)";
						break;
					case "numero":
						$consulta="SELECT 'numero' as mosaico, número as clave, número as menu FROM trabajos WHERE número IS NOT NULL GROUP BY número ORDER BY número,clave DESC";
						break;
					case "genero":
						$consulta="SELECT 'genero' as mosaico, c.nombre as menu, c.categoría as clave FROM categorías AS c JOIN trabajos AS t ON c.categoría=t.categoría GROUP BY menu,clave ORDER BY count(*) DESC";
						break;
				}
				break;
				case 'm':
					switch ($m) {
						case "recientes":
							$consulta="SELECT t.archivo, t.título AS titulo, t.notas AS resumen, 'recientes' AS clave FROM trabajos AS t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo ORDER BY t.publicación DESC";
							break;
						case 'vistos':
							$consulta="SELECT t.archivo, t.título AS titulo, t.notas AS resumen, 'vistos' AS clave FROM trabajos AS t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo ORDER BY t.visto DESC";
							break;
						case 'ilustraciones':
							$consulta="SELECT t.archivo, t.título AS titulo, t.notas AS resumen, 'ilustraciones' AS clave FROM trabajos AS t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo WHERE t.categoría=2 ORDER BY t.publicación DESC";
							break;
						case "numero":
							$consulta="SELECT t.archivo, t.título AS titulo, t.notas AS resumen, 'numero' AS clave FROM trabajos AS t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo WHERE t.número=$s ORDER BY t.publicación DESC";
							break;
						case "facultad":
							$consulta="WITH a AS (SELECT t.archivo, t.título as titulo, t.autor, t.notas, t.publicación FROM trabajos AS t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo ORDER BY t.publicación DESC) SELECT archivo, titulo, notas, $m AS clave FROM a JOIN autores ON autores.autor = a.autor WHERE autores.facultad=$s ORDER BY a.publicación ";
							break;
						case 'genero':
							$consulta="SELECT t.archivo, t.título AS titulo, t.notas AS resumen, 'genero' AS clave FROM trabajos AS t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo WHERE t.categoría=$s ORDER BY t.trabajo DESC";
							break;
					}
					break;
			}



	$principal->agregarNodos('elemento',$consulta);

	if(isset($PRUEBA))
		{
		echo "Ejecutándose en modo de prueba\n";
		echo "Variables recibidas por GET:\n";
		
		foreach($_GET as $variable => $valor)
			{
			echo "$variable: $valor\n";	
			}
			
		echo "\nVariables recibidas por POST:\n";
		
		foreach($_POST as $variable => $valor)
			{
			echo "$variable: $valor\n";	
			}
		
		echo "\nFormato de salida: $formato\n";
		if($formato=='txt')
			{
			echo "\nConsulta a a ejecutarse:\n$consulta\n";
			}
		}
	else
		{
		switch($formato)
			{
			case 'xml':
				Header('Content-type: text/xml');
				print(nuevoArbol($principal)->asXML());
				break;
			case 'txt':
				$separador='·';
				$r=ejecutar($consulta);
				$texto='';
				while($info = pg_fetch_array($r,NULL))
					{
					$texto.="${info[0]}$separador";
					}
				echo rtrim($texto,$separador);
				break;
			}
		}
	cerrar();

					

?>
