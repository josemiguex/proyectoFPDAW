<?php
// Lista de historias que se imprime en la página de administración
	if (!isset($_SESSION)) {
		session_start();
	}

	//Establecemos una conexión con el servidor
	include "conexion.php";

	$numregxpagina=8;
	$paginaactual=1;

	//En caso de que no me llegen parámetros de paginación
	//Inicializamos valores de la paginación como página 1
	if (empty($_POST["page"]) || ($_POST["page"]==1) ) {
		$regcomienzo = 0;
	} else {
		$regcomienzo = (($_POST["page"]-1) * $numregxpagina);
		$paginaactual= $_POST["page"];
	}

	//LIMIT PARA PAGINACION
	$limit = " LIMIT ". $regcomienzo . "," . $numregxpagina;

	// Recojo los datos de todas las historias
	$sql = "SELECT notamedia,historiasDeTerror.Título, historiasDeTerror.Historia,historiasDeTerror.img, historiasDeTerror.id,historiasDeTerror.fecha, historiasDeTerror.categoria_id, usuarios.usuario, categorias.nombre  FROM historiasDeTerror INNER JOIN categorias ON categorias.id=historiasDeTerror.categoria_id INNER JOIN usuarios ON usuarios.id=historiasDeTerror.usuario_id LEFT JOIN ( SELECT historia_id,AVG(puntuacion) as notamedia FROM puntuaciones GROUP BY historia_id) as p ON historiasDeTerror.id=p.historia_id" ;

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

		if ($_POST['asc'] == 'true') {
			$sql .= " ASC ";
		} else {
			$sql .= " DESC ";
		}
	}

	if (!isset($_POST['ordenapor'])) {
		$sql .= " ORDER BY ID DESC";

	}

	try {
		$resultado = $lnk -> query($sql.$limit) ;
		$resultado2 = $lnk->query($sql);
	 

	}


	catch (PDOException $e)
	{
	    echo $e->getMessage();
	    die();
	}
?>
<b>Pulse en el título de la historia para leer la historia</b>
<?php
// Imprimo una tabla con la lista de historias
$fla = $resultado->fetchAll(PDO::FETCH_ASSOC);

	echo "<table id='administraciontabla'>";
	echo "<tr>";
	echo "<th>ID</th>";
	echo "<th>Título</th>";
	echo "<th>Categoría</th>";
	echo "<th>Usuario</th>";
	echo "<th>Fecha</th>";
	echo "<th>Notamedia</th>";
	echo "<th>Imagen</th>";
	echo "</tr>";

	foreach ($fla as $historia) {
		echo "<tr>";
		echo "<td class='id' id='".$historia['id']."'>".$historia['id']."</td>";
		echo "<td class='titulo' id='".$historia['Título']."'>".ucfirst($historia['Título'])."</td>";
		echo "<td style='display:none' class='contenido'>".ucfirst($historia['Historia'])."</td>";
		echo "<td class='categoria' id=".$historia['categoria_id'].">".$historia['nombre']."</td>";
		echo "<td>".$historia['usuario']."</td>";
		echo "<td>".$historia['fecha']."</td>";
		echo "<td>".$historia['notamedia']."</td>";
		if ($historia['img'] != "") {
			echo "<td class='img' id='".$historia['img']."'><a href='subidas/img_historias/".$historia['img']."'>Ver</a></td>";
		} else {
			echo "<td class='img' id=''></td>";
		}
		echo "<td><button class='borrar'><i class='fas fa-trash-alt'></i> Eliminar</button></td>";
		echo "<td><button class='modificar'><i class='fas fa-pencil-alt'></i> Modificar</button>";
		echo "<tr>";
	}
	echo "</table>";

?>

<!-- Sistema de paginación -->
<ul class="pagination">
<?php 
if ($paginaactual!=1){?>
  <li><a href="#" data-page="1">Primero</a></li>
  <li><a href="#" data-page="<?php echo ($paginaactual-1)?>"><<</a></li>
<?php
}?>
<?php
//Cuantas páginas

$totalregistros = $resultado2-> rowCount();
$numpaginas=ceil($totalregistros/ $numregxpagina);
for ($i=1;$i<=$numpaginas;$i++){ ?>  
  <li><a href="#" data-page="<?php echo $i?>" 
  <?php
if ($i==$paginaactual){?> class="actual" <?php }?>
  ><?php echo $i?></a></li>
<?php } ?>
<?php 
if ($paginaactual!=$numpaginas){?>

  <li><a href="#" data-page="<?php echo ($paginaactual+1)?>">>></a></li>
  <li><a href="#" data-page="<?php echo $numpaginas?>">Ultimo</a></li>
<?php }?>
</ul>