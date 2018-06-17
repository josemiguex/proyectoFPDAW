<?php
	session_start();
	include "../conexion.php";

	// Primero se comprueba si la contraseña actual es correcta
	$sql = "SELECT contraseña FROM usuarios WHERE id='".$_SESSION['usuario_id']."'" ;
	$resultado = $lnk->query($sql);
	$usuario = $resultado->fetchAll(PDO::FETCH_ASSOC);

	if ($usuario['0']['contraseña'] == md5($_POST['actualpassword'])) {

		// Luego se comprueba si coinciden las contraseñas introducidas
		if ($_POST['newpassword'] == $_POST['newpassword2']) {

			// Si no hay ningún problema se cambia la contraseña del usuario
			$sql = "UPDATE usuarios SET contraseña='".md5($_POST['newpassword'])."' WHERE id='".$_SESSION['usuario_id']."'" ;
			$resultado = $lnk->query($sql);
			echo "noerror";

		} else {
			echo "error2";
		}

	} else {
		echo "error1";
	}
?>