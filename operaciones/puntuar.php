<?php 
session_start();
if (isset($_POST['puntuacion'])) {
	//Establecemos una conexión con el la base de datos
	include "../conexion.php";
	setlocale(LC_ALL,"es_ES");

	//Añadimos la puntuacion a la base de datos
	$sql = "INSERT INTO puntuaciones (`historia_id`, `usuario_id`,`puntuacion`) VALUES ('".$_POST['historia_id']."','".$_POST['usuario_id']."','".$_POST['puntuacion']."')" ;

		$reg = $lnk -> query($sql) ;

	//Actualizamos la puntuación que se muestra en la página
	$sql2 = "SELECT notamedia FROM historiasDeTerror INNER JOIN categorias ON categorias.id=historiasDeTerror.categoria_id INNER JOIN usuarios ON usuarios.id=historiasDeTerror.usuario_id LEFT JOIN ( SELECT historia_id,TRUNCATE(AVG(puntuacion), 2) as notamedia FROM puntuaciones WHERE historia_id=".$_POST['historia_id'].") as p ON historiasDeTerror.id=p.historia_id WHERE historiasDeTerror.id=".$_POST['historia_id']." GROUP BY notamedia" ;
		$reg = $lnk -> query($sql2) ;
		$puntuacion = $reg->fetchAll(PDO::FETCH_ASSOC);
		echo $puntuacion['0']['notamedia'];

}
?>