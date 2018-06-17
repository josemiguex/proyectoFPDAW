
<?php 

session_start();
if (isset($_POST['usuario'])) {
	//Establecemos una conexión con el servidor
	include "../conexion.php";

	// Ponemos al usuario seleccionado como administrador
	$sql = "UPDATE usuarios set `admin`='1' WHERE id='".$_POST['usuario']."'" ;
	$reg = $lnk -> query($sql) ;

}
// Actualizamos la lista de usuarios
include "../listausuarios.php";

?>
