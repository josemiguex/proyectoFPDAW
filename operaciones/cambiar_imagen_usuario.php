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


	if (isset($_FILES['userimage']['name'])) {
		// Obtengo la extensión del archivo
		$info = new SplFileInfo(basename($_FILES['userimage']['name']));

		// Elegimos el directorio de subida y cambiamos el nombre del archivo
		$dir_subida = '../subidas/avatares/';
		$filename = randomcode().".".$info->getExtension();

		//Añadimos usuario a la base de datos (Si no existe un usuario que tenga el mismo nombre o email)
		$sql = "UPDATE usuarios SET avatar='".$filename."' WHERE id=".$_POST['usuario_id'];
		
		$sentencia = $lnk -> query($sql);

		$fichero_subido = $dir_subida . $filename;
		move_uploaded_file($_FILES['userimage']['tmp_name'], $fichero_subido);

		echo $filename;

	}
?>
