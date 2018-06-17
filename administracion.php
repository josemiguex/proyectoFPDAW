<?php 
include "conexion.php";
	
	session_start() ;
	if (!isset($_SESSION['usr']) || $_SESSION['admin'] != 1) {
			
		header("location: historias") ;
		die();
	}
    
    // Si el tiempo de inactividad es mayor a una hora se cierra la sesión
    if (isset($_SESSION['tiempo']) && (time() - $_SESSION['tiempo'] > 3600)) {
	    session_destroy();
	    /* Aquí redireccionas a la url especifica */
	    header("Location: historias");
	    die();  
	} else {
	    $_SESSION['tiempo']=time();
	}
	
	$sql = "SELECT * FROM categorias";
	$sentencia = $lnk -> query($sql) ;
	
	$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
	$z = "";
	$c = "<li><a href='#' data-page=''>Todas</a></li>";
	foreach ($resultado as $resultado) {
		$z = $z."<option value='".$resultado['id']."'>".$resultado['nombre']."</option>";
		$c = $c."<li><a href='#' data-id='".$resultado['id']."'>Historias de ".$resultado['nombre']."</a></li>";
	}
	
?>

<!DOCTYPE html>

<html>

<head>
<title>Administración</title>
<link rel="icon" href="img/ghost.ico" type="image/x-icon"/>

<meta name="viewport" content="width=device-width">
<meta charset="utf-8">

<link rel="stylesheet" type="text/css" href="ui-lightness/jquery-ui-1.10.3.custom.css"/>
<link rel="stylesheet" type="text/css" href="css/estilo.css"/>
<link rel="stylesheet" type="text/css" href="css/fontawesome-all.css"/>

<script src="js/jquery.js"></script>
<script src="js/funcionesAdmin.js"></script>
<script src="js/core.js"></script>
<script src="js/jquery-ui-1.10.3.custom.js"></script>
<script src="js/tinymce/tinymce.min.js"></script>

<script>
// Este código no puedo ponerlo en un archivo js aparte porque utiliza código php
var usuario_id = "<?= $_SESSION['usuario_id'] ?>";

$(document).ready(function() {

tinymce.init({height : "250",forced_root_block : "", selector:'#historia',menubar:false,statusbar:false,plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools colorpicker textpattern help',
  toolbar1: 'bold italic underline strikethrough forecolor backcolor | removeformat',});
  
  tinymce.init({height : "250",forced_root_block : "", selector:'#historiamodificar',menubar:false,statusbar: false,plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools colorpicker textpattern help',
  toolbar1: 'bold italic underline strikethrough forecolor backcolor | removeformat',});
	<?php if (isset($_SESSION['id'])) {
	?>

		usr = "<?= $_SESSION['usr'] ?>";
	<?php
		}
	?>

	// Muestra la lista de categorias al añadir historia
	$("#categoriaañadir").html("<?= $z ?>");
	$("#categoriamodificar").html("<?= $z ?>");
	// Muestra la lista de categorias al buscar historia
	$(".listadecategorias").html("<?= $c ?>");
	
	<?php if (isset($_GET['listacategorias'])) {
	?>
		$("#listacategorias").show();
		 $("#contenedor").hide();
		 $("#advancedopbutton").hide();
		 $("#botonanadir").hide();
		 $("#opavanzadas").hide();
		 $("#botonmostrarusuarios").show();
		 $("#botonmostrarhistorias").show();
		 $("#botonmostrarcategorias").hide();
		 $("#botonanadircategoria").show();

	<?php
		}
	?>
	
});
</script>

</head>
<body>

<div id="dialogoborrar" title="Eliminar historia">
  <p>¿Esta seguro que desea eliminar la historia?</p>
</div>

<div id="dialogoborrarcategoria" title="Eliminar categoria">
  <p>¿Esta seguro que desea eliminar la categoria?</p>
</div>


<!-- CAPA DE DIALOGO MODIFICAR HISTORIA -->
<div id="dialogomodificar" title="Modificar historia">
<?php include "formularios/form_modificar.php"; ?>
</div>

<!-- CAPA DE DIALOGO MODIFICAR CATEGORIA -->
<div id="dialogomodificarcategoria" title="Modificar categoria">
<?php include "formularios/form_modificar_categoria.php"; ?>
</div>

<!-- CAPA DE DIALOGO AÑADIR HISTORIA -->
<div id="dialogoañadir" title="Añadir historia">
	<?php include "formularios/form_anadir.php"; ?>

</div>

<!-- CAPA DE DIALOGO AÑADIR CATEGORIA -->
<div id="dialogoañadircategoria" title="Añadir categoria">
	<?php include "formularios/form_anadir_categoria.php"; ?>

</div>
<header id="header" style="position:fixed">

	<span id="infologin">Administración</span>
		<a href="javascript:void(0);" class="icon" onclick="toogleMenu()">&#9776;</a>

<nav id="menu">
<ul id="botones" class="topnav">
		<li><button id="goback"><i class="fas fa-arrow-left"></i> Volver</img></button></li>
		<li><button id="botonanadir"><i class="fas fa-plus"></i> Añadir historia</img></button></li>
		<li><button id="botonanadircategoria" style="display:none"><i class="fas fa-plus"></i> Añadir categoria</img></button></li>
		<li><button id="botonmostrarhistorias" class="historias" style="display:none"><i class="fas fa-book"></i> Historias</button></li>
		<li><button id="botonmostrarusuarios" class="usuarios"><i class="fas fa-user-alt"></i> Usuarios</button></li>
		<li><button id="botonmostrarcategorias" class="categorias"><i class="fas fa-tag"></i> Categorias</button></li>
		<li><button id="botonlogout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</img></button>
	</ul>
		
	</nav>
</header>

<br>
<br>
<br>
<br>
<button id="advancedopbutton"><i class="fas fa-sliders-h"></i> Opciones avanzadas</img></button>

<div id="opavanzadas" style="display: none">
	<input type="text"  id="buscadorhistorias" placeholder="Buscador"></input><br>

	<b>Ordenar por:</b> <br>
	<button class="ordena" data-page="<?php echo $paginaactual ?>" value="título">Título</button>
	<button class="ordena" data-page="<?php echo $paginaactual ?>" value="id">Creación</button>
	<button class="ordena" data-page="<?php echo $paginaactual ?>" value="notamedia">Nota media</button>
	<button class="ordena" data-page="<?php echo $paginaactual ?>" value="LENGTH(Historia)">Longitud</button>
	<input type="checkbox" id="asc" value="asc">ASC

	<ul class='listadecategorias'>

	</ul>

</div>
<div id="listausuarios" style="display:none">
	<?php include "listausuarios.php"; ?>
</div>

<div id="listacategorias" style="display:none">
	<?php include "listacategorias.php"; ?>
</div>

<div id="dialogoadmin" style="display:none">
	<b>¿Quiere que <span id="nombreusuario"></span> sea administrador?</b>
</div>

<div id="dialogologout" title="¿Está seguro?">

</div>

<br>
<!-- Contenido de la página -->
<div id="contenedor">
	<?php include "listaadmin.php"; ?>
</div>

 <!-- Trigger/Open The Modal -->
<!-- El modal -->
	<div id="infomodal" class="modal">

	  <!-- Contenido del modal -->
	  <div class="modal-content">
	    <span class="close">&times;</span>
	    <p id="info"></p>
	  </div>
	  </div>
<script src="js/modal.js"></script>

</div> 
</body>
</html>