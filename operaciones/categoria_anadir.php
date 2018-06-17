<?php 
session_start();
if (isset($_POST['categoria'])) {
	//Establecemos una conexión con el la base de datos
	include "../conexion.php";
	setlocale(LC_ALL,"es_ES");

	//Añadimos la categoria a la base de datos
	$sql = "INSERT INTO categorias (`nombre`) VALUES ('".$_POST['categoria']."')" ;
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
