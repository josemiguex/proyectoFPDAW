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
	//Establecemos una conexión con el la base de datos
	include "../conexion.php";
	setlocale(LC_ALL,"es_ES");

	// Almaceno en una variable la fecha actual con la función date
	$fecha = date("Y")."-".date("m")."-".date("d");
	
	// Si obtengo un archivo
	if (isset($_FILES['img']['name'])) {
		// Obtengo la extensión del archivo
		$info = new SplFileInfo(basename($_FILES['img']['name']));

		// Elegimos el directorio de subida y cambiamos el nombre del archivo
		$dir_subida = '../subidas/img_historias/';
		$filename = randomcode().".".$info->getExtension();

	} else {
		$filename = "";
	}

	//Añadimos la historia a la base de datos
	$sql = "INSERT INTO historiasDeTerror (`Título`, `Historia`,`usuario_id`,`categoria_id`,`fecha`,`img`) VALUES ('".$_POST['titulo']."','".$_POST['historia']."','".$_POST['usuario_id']."','".$_POST['categoria_id']."','".$fecha."','".$filename."')" ;
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
	echo "noerror";
}
?>
