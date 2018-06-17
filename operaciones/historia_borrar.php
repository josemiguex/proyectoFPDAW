<?php
session_start();
include "../conexion.php";
	if (isset($_POST["idhistoria"])){	
	// Eliminamos la historia de la base de datos
	$sql = "DELETE FROM historiasDeTerror WHERE id=".$_POST["idhistoria"] ;
		$reg = $lnk -> query($sql) ;

	// Eliminamos sus puntuaciones
	$sql = "DELETE FROM puntuaciones WHERE historia_id=".$_POST["idhistoria"] ;
		$reg = $lnk -> query($sql) ;
}

// Dependiendo si estamos en la página de administración o no, se recarga una lista diferente

if (isset($_POST['admin']) && $_POST['admin'] == "true") {
	include "../listaadmin.php";
} else {
    echo "noerror";
}
?>
