<?php
	// Lista de usuarios que se imprime en la página de administración
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

	// Recogemos de la base de datos todos los datos de los usuarios
	$sql = "SELECT categorias.* FROM categorias ORDER BY ID DESC" ;


	try {
	    $resultado = $lnk -> query($sql.$limit) ;
	    $resultado2 = $lnk->query($sql);
	 

	}
	catch (PDOException $e)
	{
	    echo $e->getMessage();
	    die();
	}

		$fla = $resultado->fetchAll(PDO::FETCH_ASSOC);
	?>
	<div id="listacategorias">
	<?php
	// Pintamos una tabla con los datos de cada usuario
	echo "<table id='categoriastabla'>";
	echo "<tr>";
	echo "<th>ID</th>";
	echo "<th>Nombre</th>";
	echo "<th>Acciones</th>";

	echo "</tr>";
	foreach ($fla as $categoria) {
		echo "<tr>";
		echo "<td class='id' id='".$categoria['id']."'>".$categoria['id']."</td>";
		echo "<td class='nombre' id='".$categoria['nombre']."'>".$categoria['nombre']."</td>";
		echo "<td><button class='borrarcategoria'><i class='fas fa-trash-alt'></i> Eliminar</button>";
		echo "<button class='modificarcategoria'><i class='fas fa-pencil-alt'></i> Modificar</button></td>";
		

		echo "<tr>";
	}
	echo "</table>";

?>
</div>
<!-- Sistema de paginación -->
<ul class="paginationCategorias">
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