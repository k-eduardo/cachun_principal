<?php
	$busqueda = $_GET["b"];
	$subfiltro = $_GET["s"];
	require("../bd.php");
	conectar();
	if ($busqueda=="recientes"){
		$resultado = ejecutar("SELECT t.archivo FROM trabajos AS t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo ORDER BY t.trabajo DESC");
	}
	elseif ($busqueda="leidos") {
		$resultado = ejecutar("SELECT t.archivo FROM trabajos AS t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo ORDER BY visto");
	}
	elseif ($busqueda="numero") {
		$resultado = ejecutar("SELECT t.archivo FROM trabajos AS t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo WHERE t.número=$busqueda ORDER BY t.publicación");
	}
	elseif ($busqueda="facultad") {
		$resultado = ejecutar("WITH temporal AS (
SELECT t.trabajo,t.autor,t.archivo FROM trabajos as t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo ORDER BY t.publicación )
SELECT w.archivo FROM temporal as w JOIN autores as a ON a.autor = w.autor WHERE a.facultad = $subfiltro;
;");
	}
	elseif ($busqueda="ilustracion") {
		$resultado = ejecutar("SELECT t.archivo FROM trabajos AS t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo WHERE t.categoría=2 ORDER BY t.publicación");
	}
	elseif ($busqueda="genero") {
		$resultado = ejecutar("SELECT t.archivo FROM trabajos AS t JOIN rel_trabajos AS rt ON rt.adjunto = t.trabajo WHERE t.categoría=$subfiltro ORDER BY t.publicación");
	}
	$i=0;
	$elJSON = array();
while($row = pg_fetch_array($resultado)){
	$i = $i+1;
	$id = "#i{$i}";
   array_push($elJSON,array('id' => $id, 'imagen' => $row[0]));}
echo json_encode(array("elJSON" => $elJSON));
?>
