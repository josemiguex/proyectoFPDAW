<?php
	session_start();
	include "conexion.php";
    
    // Si el tiempo de inactividad es mayor a una hora se cierra la sesión
    if (isset($_SESSION['tiempo']) && (time() - $_SESSION['tiempo'] > 3600)) {
	    session_destroy();
	    /* Aquí redireccionas a la url especifica */
	    header("Location: historias");
	    die();  
	} else {
	    $_SESSION['tiempo']=time();
	}
	
	// Si no hay ningún usuario logueado se vuelve a la página principal
	if (!isset($_SESSION['usr'])) {
				
			header("location: historias") ;
			die();
		}

	// Recogo la información del perfil
	$sql = "SELECT * FROM usuarios WHERE id='".$_SESSION['usuario_id']."'" ;
	$resultado = $lnk->query($sql);
	$usuario = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>

<html>
<head>

	<title>Ver perfil</title>
	<link rel="icon" href="img/ghost.ico" type="image/x-icon"/>
	<meta name="viewport" content="width=device-width">

	<link rel="stylesheet" type="text/css" href="ui-lightness/jquery-ui-1.10.3.custom.css"/>
	<link rel="stylesheet" type="text/css" href="css/estilo.css"/>
	<link rel="stylesheet" type="text/css" href="css/fontawesome-all.css"/>

	<script src="js/jquery.js"></script>
	<script src="js/funciones.js"></script>
	<script src="js/jquery-ui-1.10.3.custom.js"></script>
	<script src="js/core.js"></script>
	<script src="js/funcionesPerfil.js"></script>



<script>
$(document).on("click","#goback",function(){
		 window.location.href = "historias";
	});
</script>
</head>
<body>

<header id="header" style="position:fixed">

	<span id="infologin">Ver perfil</span>
	<a href="javascript:void(0);" class="icon" onclick="toogleMenu()">&#9776;</a>

	<nav id="menu">
	<ul id="botones" class="topnav">
			<li><button id="goback"><i class="fas fa-arrow-left"></i> Volver</button></li>

			<li><button id="changeuserimage"><i class="fas fa-images"></i> Cambiar imagen de usuario</button></li>
			<li><button id="changepass"><i class="fas fa-key"></i> Cambiar contraseña</button></li>
	</ul>
		
	</nav>
</header>
<br>
<br>
<br>
<br>
<div id="contenedor">
<!-- Imprimo los datos del usuario -->
	<?php if ($usuario['0']['avatar'] != "") {
	?>
		<img id="actualuserimage" src="subidas/avatares/<?= $usuario['0']['avatar'] ?>" style="height:128px;border: 1px solid white"></img>
	<?php } else {
	?>
		<img id="actualuserimage" src="img/perfil.jpg" style="height:128px;border: 1px solid white"></img>
	<?php
	}
	?>
	
	<ul>
		<li><b>Nombre: </b><?= $usuario['0']['nombre'] ?></li>
		<li><b>Apellidos: </b><?= $usuario['0']['apellidos'] ?></li>
		<li><b>Nick: </b><?= $usuario['0']['usuario'] ?></li>
		<li><b>Email: </b><?= $usuario['0']['email'] ?></li>
		<li><b>¿Administrador?: </b>

		<?php if ($usuario['0']['admin'] == 1) {
				echo "Sí";
			} else {
				echo "No";
			}
				?>
		</li>

	</ul>
</div>
<!-- CAPA DE DIALOGO CAMBIAR CONTRASEÑA -->
<div id="dialogochangepass" title="Cambiar contraseña" >
	<?php include "formularios/form_changepass.php"; ?>
</div>

<!-- CAPA DE DIALOGO CAMBIAR AVATAR -->
<div id="dialogochangeuserimage" title="Cambiar imagen de usuario" >
	<?php include "formularios/form_changeuserimage.php"; ?>
</div>
<!-- El modal -->
<div id="infomodal" class="modal">

  <!-- Contenido del modal -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p id="info"></p>
  </div>

</div>
</body>
</html>