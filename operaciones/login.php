<?php

$mensaje = "";
	session_start() ;

	if (isset($_POST['usr'])) {
	//Establecemos una conexión con el servidor
	include "../conexion.php";
	
	$sql = "SELECT * FROM usuarios WHERE usuario= BINARY :user AND contraseña=:password" ;
	$sentencia = $lnk -> prepare($sql) ;
	
	$sentencia->execute(array(':user' => $_POST['usr'] ,':password'=> md5($_POST['pass']) )) ;
	$resultado = $sentencia->fetchAll();
	
	$data;
	//Si he obtenido un resultado correcto
	if ($resultado != false) {

		// Crear las variables de sessión necesarias

			$_SESSION["id"] = session_id();
			$_SESSION["usr"] = $_POST['usr'];
			$_SESSION["usuario_id"] = $resultado['0']['id'];
			$_SESSION['tiempo'] = time();
			$_SESSION['admin'] = $resultado['0']['admin'];
			
			// Para que ajax reciba el íd del usuario
			echo $resultado['0']['id'];

			if ($resultado['0']['admin'] == 1) {
				echo ",admin";
			}
			
	} else {
	echo false;
	}
}


?>
