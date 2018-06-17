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

session_start();
if (isset($_POST['titulo'])) {
	//Establecemos una conexión con el servidor
	include "../conexion.php";

	// Si obtengo un archivo
	if (isset($_FILES['img']['name'])) {
		// Obtengo la extensión del archivo
		$info = new SplFileInfo(basename($_FILES['img']['name']));

		// Elegimos el directorio de subida y cambiamos el nombre del archivo
		$dir_subida = '../subidas/img_historias/';
		$filename = randomcode().".".$info->getExtension();

	} else {
		$filename = $_POST['imganterior'];
	}

	// Modificamos la historia en la base de datos
	$sql = "UPDATE historiasDeTerror set `Título`='".$_POST['titulo']."',`Historia`='".$_POST['historia']."', img='".$filename."',categoria_id=".$_POST['categoria_id']." WHERE `id`='".$_POST['idhistoria']."'" ;
	$reg = $lnk -> query($sql) ;

	if (!$reg) {
			//Si hay error 1062 (historia duplicada) no se añade la historia
	    if ($lnk->errorInfo()['1'] == 1062) {
		echo "duplicated";
		}
	    die();
	}

	if (isset($_FILES['img']['name'])) {
		// Subimos la imagen de la historia
		$fichero_subido = $dir_subida . $filename;
		move_uploaded_file($_FILES['img']['tmp_name'], $fichero_subido);
	}
	echo $_POST['idhistoria'];
}

?>
