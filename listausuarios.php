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
	$sql = "SELECT usuarios.* FROM usuarios" ;


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
	<div id="listausuarios">
	<?php
		$fla = $resultado->fetchAll(PDO::FETCH_ASSOC);
	// Pintamos una tabla con los datos de cada usuario
	echo "<table id='usuariostabla'>";
	echo "<tr>";
	echo "<th>ID</th>";
	echo "<th>Nombre</th>";
	echo "<th>Apellidos</th>";
	echo "<th>Nick</th>";
	echo "<th>Email</th>";
	echo "<th>Tipo de usuario</thth>";

	echo "</tr>";
	foreach ($fla as $usuario) {
		echo "<tr>";
		echo "<td class='id' id='".$usuario['id']."'>".$usuario['id']."</td>";
		echo "<td class='nombre' id='".$usuario['nombre']."'>".$usuario['nombre']."</td>";
		echo "<td class='apellidos' id='".$usuario['apellidos']."'>".$usuario['apellidos']."</td>";
		echo "<td class='usuario' id='".$usuario['usuario']."'>".$usuario['usuario']."</td>";
		echo "<td class='id' id='".$usuario['email']."'>".$usuario['email']."</td>";
		if ($usuario['admin'] == 0) {
			echo "<td><button id='botonadmin'>Administrador</button></td>";
		} else {
			echo "<td>Ya es administrador</td>";
		}

		echo "<tr>";
	}
	echo "</table>";

?>
</div>
<!-- Sistema de paginación -->
<ul class="paginationUsuarios">
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