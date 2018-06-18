<?php
session_start();
include "../conexion.php";
	if (isset($_POST["idcategoria"])){	
	// Eliminamos la historia de la base de datos
	$sql = "DELETE FROM categorias WHERE id=".$_POST["idcategoria"] ;
		$reg = $lnk -> query($sql) ;
		if (!$reg) {
			echo "error";
			die();
		}
		echo "noerror";

}


?>
