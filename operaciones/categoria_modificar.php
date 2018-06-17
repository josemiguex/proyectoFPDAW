<?php
session_start();
if (isset($_POST['categoria_id'])) {
	//Establecemos una conexión con el servidor
	include "../conexion.php";

	// Modificamos la categoria en la base de datos
	$sql = "UPDATE categorias set `nombre`='".$_POST['categoria_nombre']."' WHERE `id`='".$_POST['categoria_id']."'" ;
	$reg = $lnk -> query($sql) ;
	if (!$reg) {
			//Si hay error 1062 (historia duplicada) no se añade la historia
	    if ($lnk->errorInfo()['1'] == 1062) {
		echo "duplicated";
		}
	    die();
	}
	echo "noerror";
}

?>
