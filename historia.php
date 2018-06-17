<?php
	session_start() ;
	//Establecemos una conexión con el servidor
	include "conexion.php";

	if (!isset($_GET['id'])) {
		header("location: historias");
		die();
	}

	// Si el tiempo de inactividad es mayor a una hora se cierra la sesión
    if (isset($_SESSION['tiempo']) && (time() - $_SESSION['tiempo'] > 3600)) {
	    session_destroy();
	    /* Aquí redireccionas a la url especifica */
	    header("Location: historia?id=".$_GET['id']);
	    die();  
	} else {
	    $_SESSION['tiempo']=time();
	}

		// Seleccionamos la historia y cogemos todos sus datos
	$sql = "SELECT notamedia, historiasDeTerror.*,usuarios.usuario,usuarios.id as usuario_id,usuarios.avatar, categorias.nombre FROM historiasDeTerror INNER JOIN categorias ON categorias.id=historiasDeTerror.categoria_id INNER JOIN usuarios ON usuarios.id=historiasDeTerror.usuario_id LEFT JOIN ( SELECT historia_id,TRUNCATE(AVG(puntuacion),2) as notamedia FROM puntuaciones WHERE historia_id=".$_GET['id'].") as p ON historiasDeTerror.id=p.historia_id WHERE historiasDeTerror.id=".$_GET['id']." GROUP BY notamedia" ;
	$resultado = $lnk -> query($sql) ;
	$fla = $resultado->fetchAll(PDO::FETCH_ASSOC);

	// Seleccionamos la lista de categorías
	$sql = "SELECT * FROM categorias";
	$sentencia = $lnk -> query($sql) ;
	
	$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

	$z = "";
	foreach ($resultado as $resultado) {
		$z = $z."<option value='".$resultado['id']."'>".$resultado['nombre']."</option>";
	}

	if (isset($_SESSION['id'])) {
		$sql2 = "SELECT * FROM puntuaciones WHERE puntuaciones.historia_id=".$_GET['id']." AND puntuaciones.usuario_id=".$_SESSION['usuario_id']." LIMIT 1" ;
		$resultado2 = $lnk -> query($sql2) ;
		$fla2 = $resultado2->fetchAll(PDO::FETCH_ASSOC);
	}
	
?>

<!DOCTYPE html>
<style>
/* Para que haya un margen en la lista de datos en la historia */
	#infohistoria li {
	  margin: 10px 0;
	}
</style>
<html>
<head>
<title>Ver historia</title>
<meta name="viewport" content="width=device-width">
<link rel="icon" href="img/ghost.ico" type="image/x-icon"/>

<meta charset="utf-8">

<!-- Cargo las hojas de estilo-->
<link rel="stylesheet" type="text/css" href="ui-lightness/jquery-ui-1.10.3.custom.css"/>
<link rel="stylesheet" type="text/css" href="css/estilo.css"/>
<link rel="stylesheet" type="text/css" href="css/fontawesome-all.css"/>

<!-- Cargo los scripts js-->
<script src="js/jquery.js"></script>
<script src="js/core.js"></script>
<script src="js/jquery-ui-1.10.3.custom.js"></script>
<script src="js/funciones.js"></script>
<script src="js/tinymce/tinymce.min.js"></script>

<script>
var usuario_id;

// Al clickear en el botón volver atrás vuelve a la página principal
$(document).on("click","#goback",function(){
		 window.location.href = "historias";
	});

$(document).ready(function() {
	<?php if (isset($_SESSION['id'])) {
	?>
		usr = "<?= $_SESSION['usr'] ?>";
		usuario_id = "<?= $_SESSION['usuario_id'] ?>";
	<?php
		}
	?>

	<?php
		if (isset($fla2['0'])) {
	?>
		$("#puntuardiv").css('display','none');
		$("#eliminarpuntuaciondiv").css('display','block');
	<?php } else { ?>
		$("#puntuardiv").css('display','block');
	    $("#eliminarpuntuaciondiv").css('display','none');
	<?php } ?>

	$("#categoriamodificar").html("<?= $z ?>");
	$('#categoriamodificar option[value=<?= $fla['0']['categoria_id'] ?>]').attr('selected',true);
	var imganterior = "<?= $fla['0']['img'] ?>";
	$('#imganterior').val(imganterior);
	
	var historia = $.trim($("#contenedor").children(".cuerpohistoria").html());
		$("#historiamodificar").val(historia);
        
});
</script>
</head>
<body>
	<header id="header">
		<span id="infologin">Ver historia</span>
		<a href="javascript:void(0);" class="icon" onclick="toogleMenu()">&#9776;</a>

		<nav id="menu">
			<ul id="botones" class="topnav">
					<li><button id="goback"><i class="fas fa-arrow-left"></i> Volver</button></li>
				<?php

				// Sólo se puede eliminar la historia si el usuario está logueado
				if (isset($_SESSION['usr']) && $_SESSION['usr'] == $fla['0']['usuario']) {
				?>
					<li><button class='borrar'><i class="fas fa-trash-alt"></i> Eliminar</button></li>
					<li><button class='modificar'><i class="fas fa-pencil-alt"></i> Modificar</img></button></li>
				<?php
				}
				?>

			</ul>	
		</nav>
	</header>
<br>
<!-- CAPA DE DIALOGO ELIMINAR HISTORIA -->
	<div id="dialogoborrar" title="Eliminar historia">
	  <p>¿Esta seguro que desea eliminar la historia?</p>
	</div>

	<!-- CAPA DE DIALOGO MODIFICAR HISTORIA -->
	<div id="dialogomodificar" title="Modificar historia" >
		<?php include "formularios/form_modificar.php"; ?>
	</div>
   <script>
       $(document).ready(function () {
              tinymce.init({height : "230",forced_root_block : "", selector:'#historiamodificar',menubar:false,statusbar:false,plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools colorpicker textpattern help',
                  toolbar1: 'bold italic underline strikethrough forecolor backcolor | removeformat',});
       });
   </script>
	<div id="contenedor" class="historia">

	<!-- Pinto la historia y los datos del usuario que lo ha escrito -->
		<h2 style="text-align: center" class='titulo' data-idhistoria="<?= $fla['0']['id'] ?>"><?= ucfirst($fla['0']['Título']) ?></h2>
		<?php if ($fla['0']['img'] != "") {
			?>
				<center><img id="img_historia" src="subidas/img_historias/<?= $fla['0']['img'] ?>" style="height:256px;border: 1px solid white;margin: 0 auto"></img></center>
			<?php } ?>
		<p class="cuerpohistoria"> <?= str_replace("\n", "<br>", ucfirst($fla['0']['Historia'])) ?></p>
		
	</div>
	
	<ul id="infohistoria">
			<li><b>Categoria: </b><?= $fla['0']['nombre'] ?></li>
			<li><b>Fecha: </b><?= $fla['0']['fecha'] ?></li>
			<li><b>Notamedia:</b><span id='notamedia'>
			<?php
			if (isset($fla['0']['notamedia'])) {
					echo $fla['0']['notamedia'];
				} else {
					echo "Sin asignar";
				}
			?>
			</span></li>
			

			<li><b>Escrito por: </b><?= $fla['0']['usuario'] ?></li>
			<?php if ($fla['0']['avatar'] != "") {
			?>
				<img id="actualuserimage" src="subidas/avatares/<?= $fla['0']['avatar'] ?>" style="height:128px;border: 1px solid white"></img>
			<?php } else {
			?>
				<img id="actualuserimage" src="img/perfil.jpg" style="height:128px;border: 1px solid white"></img>
			<?php
			}
			?>
			
		</ul>

		<?php
			// Sólo puedes eliminar y añadir puntuaciones si has iniciado sesión
			if (isset($_SESSION['usr']) && ($_SESSION['usuario_id'] != $fla['0']['usuario_id'])) {
		?>
			<div id='eliminarpuntuaciondiv'>
				<button id='eliminarpuntuacion'><i class="fas fa-eraser"></i> Eliminar puntuación</button>
				</div>

			<div id='puntuardiv'>
				<input type='number' id='nota' name='puntuacion' min='0' max='10' required>
				<button id='puntuar'>Puntuar</button>
				<span id="errorpuntuacion"></span>
				</div>
		<?php 
			}
		?>

	
	<!-- Modal para mostrar mensajes en pantalla -->
	<div id="infomodal" class="modal">

	  <!-- Contenido del modal -->
	  <div class="modal-content">
	    <span class="close">&times;</span>
	    <p id="info"></p>
	  </div>

	</div>
</body>
</html>