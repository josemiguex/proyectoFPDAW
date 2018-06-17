<?php

	if (!isset($_SESSION)) { // Se inicia la sesión en caso de que no se haya iniciado
		session_start();
	}

	//Establecemos una conexión con el servidor
	include "conexion.php";

	$row = $_POST['row'];
	$rowperpage = 3;

	/*//En caso de que no me llegen parámetros de paginación
	//Inicializamos valores de la paginación como página 1
	if (empty($_POST["page"]) || ($_POST["page"]==1) ) {
		$regcomienzo = 0;
	} else {
		$regcomienzo = (($_POST["page"]-1) * $numregxpagina);
		$paginaactual= $_POST["page"];
	}
	//LIMIT PARA PAGINACION
	$limit = " LIMIT ". $regcomienzo . "," . $numregxpagina;*/

	// Recogemos los datos de las historias
	$sql = "SELECT notamedia,historiasDeTerror.*,usuarios.usuario, categorias.nombre FROM historiasDeTerror LEFT JOIN categorias ON categorias.id=historiasDeTerror.categoria_id LEFT JOIN usuarios ON usuarios.id=historiasDeTerror.usuario_id LEFT JOIN ( SELECT historia_id,AVG(puntuacion) as notamedia FROM puntuaciones GROUP BY historia_id) as p ON historiasDeTerror.id=p.historia_id";
	//Según los parámetros que reciba se cambia la sentencia que se ejecutará
	if (isset($_POST['busquedahistoria']) || isset($_POST['categoria'])) {
		$sql .= " WHERE";
	}

	if (isset($_POST['busquedahistoria'])) {
			$sql .= " `Título` LIKE '%".$_POST['busquedahistoria']."%' ";
	}

	if (isset($_POST['busquedahistoria']) && isset($_POST['categoria'])) {
		$sql .= " AND";
	}

	if (isset($_POST['categoria'])) {
		$sql .= " categoria_id='".$_POST['categoria']."' ";
	}

	if (isset($_POST['ordenapor'])) {
		$sql .= " ORDER BY ".$_POST['ordenapor'];

		if (isset($_POST['asc'])) {
			$sql .= " ASC ";
		} else {
			$sql .= " DESC ";
		}
	}

	// Si no se obtiene el ordenapor, por defecto se ordenará por ID
	if (!isset($_POST['ordenapor'])) {
		$sql .= " ORDER BY ID DESC";
	}

	try {
		$limit = " limit ".$row.",".$rowperpage;

    $resultado = $lnk -> query($sql.$limit) ;
	 

	}

	catch (PDOException $e)
	{
	    echo $e->getMessage();
	    die();
	}

	// Recojo en un aray la lista de historias
	$fla = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<?php

	// Se imprime la lista de historias

	if (isset($fla['0'])){
		
	foreach ($fla as $historia) {
		echo "<div class='historia'>";
		echo "<h2 class='titulo' id='".$historia['id']."' onclick='mostrar(\"historia-".$historia['id']."\")'>".ucfirst($historia['Título'])."</h2>" ;

		// Si la historia tiene una imagen, se mostrará
		if ($historia['img'] != ""){
			echo "<img src='subidas/img_historias/".$historia['img']."' style='height:256px'></img>";
		}
		//echo "<div class='historia' id='historia-".$historia['id']."' data-idhistoria=".$historia['id']." style='display:none'>" ;

		if (strlen($historia['Historia']) > 200) {
			$puntos = "..."; // Se muestra puntos suspensivos en la historia si tiene más de 100 carácteres
		} else {
			$puntos = "";
		}

		echo "<p>".substr(str_replace("\n", "<br>", ucfirst($historia['Historia'])),0,200).$puntos."</p>" ;
		// Para acceder a la historia completa
		echo "<a class='enlacehistoria' href='historia?id=".$historia['id']."'>Ver historia completa --></a><br>";
		echo "<br>";
		echo "</div>";
	}

	

	}
?>