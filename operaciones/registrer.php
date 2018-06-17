<?php

function randomcode() {
    $an = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $su = strlen($an) - 1;
    return substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1);
}

$mensaje = "";
session_start() ;
include "../conexion.php";

if ($_POST['pass'] == $_POST['passconfirm']) {

	if (isset($_FILES['avatar']['name'])) {
		// Obtengo la extensión del archivo
		$info = new SplFileInfo(basename($_FILES['avatar']['name']));

		// Elegimos el directorio de subida y cambiamos el nombre del archivo
		$dir_subida = '../subidas/avatares/';
		$filename = randomcode().".".$info->getExtension();

	} else {
		$filename = "";
	}

	//Añadimos usuario a la base de datos (Si no existe un usuario que tenga el mismo nombre o email)
	$sql = "INSERT INTO usuarios (`nombre`,`apellidos`,`email`,`usuario`,`contraseña`,`admin`,`avatar`) values ('".$_POST['nombre']."','".$_POST['apellidos']."','".$_POST['email']."','".$_POST['usr']."','".md5($_POST['pass'])."','0','".$filename."')" ;
	$sentencia = $lnk -> query($sql) ;

	if (!$sentencia) {
		//Si hay error 1062 (usuario duplicado) no se añade el usuario
		if ($lnk->errorInfo()['1'] == 1062) {
			echo false;
		}
		die();


	}

	if (isset($_FILES['avatar']['name'])) {
		// Subimos el avatar (la imagen del usuario)
		$fichero_subido = $dir_subida . $filename;
		move_uploaded_file($_FILES['avatar']['tmp_name'], $fichero_subido);
	}


//Hacemos login
$sql = "SELECT * FROM usuarios WHERE usuario='".$_POST['usr']."' LIMIT 1" ;
	$sentencia = $lnk -> query($sql) ;
	
	$datosusuario = $sentencia->fetchAll();
	
		// Crear las variables de sessión necesarias
			$_SESSION["id"] = session_id();
			$_SESSION["usr"] = $_POST['usr'];
			$_SESSION["usuario_id"] = $datosusuario['0']['id'];
			$_SESSION['tiempo'] = time();

			//Para que ajax reciba el id del usuario
			echo $datosusuario['0']['id'];


} else {
	echo "errorconfirm";
}
?>
