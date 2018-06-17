<?php

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;

	require "PHPMailer/PHPMailer.php";
	require "PHPMailer/Exception.php";
	require "PHPMailer/SMTP.php";

	// Función que genera una nueva contraseña

	function newPassword(){
	    $an = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $su = strlen($an) - 1;
	    return substr($an, rand(0, $su), 1) .
	            substr($an, rand(0, $su), 1) .
	            substr($an, rand(0, $su), 1) .
	            substr($an, rand(0, $su), 1) .
	            substr($an, rand(0, $su), 1) .
	            substr($an, rand(0, $su), 1) .
	            substr($an, rand(0, $su), 1) .
	            substr($an, rand(0, $su), 1);
	}
	//Establecemos una conexión con el servidor
	include "../conexion.php";

	// Primero miramos si el email introducido existe en la base de datos
	$sql = "SELECT usuario FROM usuarios WHERE email='".$_POST['email']."'" ;
	$resultado = $lnk -> query($sql) ;
	$fla = $resultado->fetchAll(PDO::FETCH_ASSOC);
	// Si existe un usuario con ese email

	if (isset($fla['0']['usuario'])) {
		// Generamos una nueva contraseña
		$newpass = newPassword();
		// Cambiamos la contraseña en la base de datos
		$sql2 = "UPDATE usuarios SET contraseña='".md5($newpass)."' WHERE email='".$_POST['email']."'" ;
		$resultado = $lnk -> query($sql2) ;

		//Envio del email con la nueva contraseña
		$email_user = "josemiguel983469@gmail.com";
		$email_password = "Fpdaw123@";
		$the_subject = "Recuperación de contraseña";
		$address_to = $_POST['email'];
		$from_name = "Historias de terror";
		$phpmailer = new PHPMailer();
		// ---------- datos de la cuenta de Gmail -------------------------------
		$phpmailer->Username = $email_user;
		$phpmailer->Password = $email_password; 
		//-----------------------------------------------------------------------
		// $phpmailer->SMTPDebug = 1;
		$phpmailer->CharSet = 'UTF-8';
		$phpmailer->SMTPSecure = 'ssl';
		$phpmailer->Host = "smtp.gmail.com"; // GMail
		$phpmailer->Port = 465;
		$phpmailer->IsSMTP(); // use SMTP
		$phpmailer->SMTPAuth = true;
		$phpmailer->setFrom($phpmailer->Username,$from_name);
		$phpmailer->AddAddress($address_to); // recipients email
		$phpmailer->Subject = $the_subject;	
		$phpmailer->Body .= "<p>Esta es su nueva contraseña de acceso: ".$newpass."</p><a href='http://".$_SERVER['HTTP_HOST']."/trabajophpajax/historias'>Haga click aquí para acceder a la página</a>";

		$phpmailer->IsHTML(true);
		$phpmailer->Send();
        echo true;

	} else {
		echo false;
	}

?>
