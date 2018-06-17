<?php 
session_start();
if (isset($_POST['historia_id'])) {
	// Establecemos una conexión con el la base de datos
	include "../conexion.php";
	setlocale(LC_ALL,"es_ES");

	//eliminamos la puntuacion de la base de datos
	$sql = "DELETE FROM puntuaciones WHERE historia_id='".$_POST['historia_id']."' AND usuario_id='".$_POST['usuario_id']."'" ;

	$reg = $lnk -> query($sql) ;

	// Actualizamos la puntuación
	$sql2 = "SELECT notamedia FROM historiasDeTerror INNER JOIN categorias ON categorias.id=historiasDeTerror.categoria_id INNER JOIN usuarios ON usuarios.id=historiasDeTerror.usuario_id LEFT JOIN ( SELECT historia_id,TRUNCATE(AVG(puntuacion), 2) as notamedia FROM puntuaciones WHERE historia_id=".$_POST['historia_id'].") as p ON historiasDeTerror.id=p.historia_id WHERE historiasDeTerror.id=".$_POST['historia_id']." GROUP BY notamedia" ;
		$reg = $lnk -> query($sql2) ;
		$puntuacion = $reg->fetchAll(PDO::FETCH_ASSOC);
		echo $puntuacion['0']['notamedia'];

}
?>