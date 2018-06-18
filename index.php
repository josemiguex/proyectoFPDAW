<?php 
include "conexion.php";
	
	session_start() ;

    // Si el tiempo de inactividad es mayor a una hora se cierra la sesión
    if (isset($_SESSION['tiempo']) && (time() - $_SESSION['tiempo']) > 3600) {
	    session_destroy();
	    /* Aquí redireccionas a la url especifica */
	    header("Location: historias");
	    die();  
	} else {
        $_SESSION['tiempo']=time();
    }
	
	
	// Cojo de la base de datos las categorias
	$sql = "SELECT * FROM categorias";
	$sentencia = $lnk -> query($sql) ;
	
	$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

	// $z = variable que contendrá un seleccionable con la lista de las categorias para poder seleccionar al añadir una historia
	// $c = lista de links para buscar historias por categoría

	$z = "<option value=''>Seleccione categoría</option>";
	$c = "<li><a href='#' data-page=''>Todas</a></li>";
	foreach ($resultado as $resultado) {
		$z = $z."<option value='".$resultado['id']."'>".$resultado['nombre']."</option>";
		$c = $c."<li><a href='#' data-id='".$resultado['id']."'>Historias de ".$resultado['nombre']."</a></li>";


	}

	 // Cuento el número total de historias
	 $allcount_query = "SELECT count(*) as allcount FROM historiasDeTerror";
	 $allcount_result = $lnk -> query($allcount_query);
	 $allcount_fetch = $allcount_result -> fetchAll(PDO::FETCH_ASSOC);

	 $allcount = $allcount_fetch['0']['allcount'];

	$rowperpage = 6;
	// Recojo la lista de historias
	$historias = "SELECT notamedia,historiasDeTerror.*,usuarios.usuario, categorias.nombre FROM historiasDeTerror LEFT JOIN categorias ON categorias.id=historiasDeTerror.categoria_id LEFT JOIN usuarios ON usuarios.id=historiasDeTerror.usuario_id LEFT JOIN ( SELECT historia_id,AVG(puntuacion) as notamedia FROM puntuaciones GROUP BY historia_id) as p ON historiasDeTerror.id=p.historia_id WHERE 1=1" ;

	$termino = "";
	//Según los parámetros que reciba por GET se cambia la sentencia que se ejecutará
	if (isset($_GET['busquedahistoria']) && $_GET['busquedahistoria'] != "" || isset($_GET['categoria']) && $_GET['categoria'] != "") {
		$termino = $_GET['busquedahistoria'];
	}

	if (isset($_GET['busquedahistoria']) && $_GET['busquedahistoria'] != "") {
			$historias .= " AND `Título` LIKE '%".$_GET['busquedahistoria']."%' ";
	}

	if (isset($_SESSION['id']) && isset($_GET['propias'])) {
			$historias .= " AND usuario_id=".$_SESSION['usuario_id'];
		}

	if (isset($_GET['categoria']) && $_GET['categoria'] != "") {
		$historias .= " AND categoria_id='".$_GET['categoria']."' ";
	}

	if (isset($_GET['ordenapor']) && $_GET['ordenapor'] != "") {
		$historias .= " ORDER BY ".$_GET['ordenapor'];

		if (isset($_GET['asc'])) {
			$historias .= " ASC ";
		} else {
			$historias .= " DESC ";
		}
	}

	if (!isset($_GET['ordenapor']) || ($_GET['ordenapor']) == "") {
		$historias .= " ORDER BY historiasDeTerror.ID DESC";
	}

	$limit = " limit 0,".$rowperpage;
	$resultado2 = $lnk->query($historias.$limit);

	$fla = $resultado2->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
    <!-- Título de la página -->
    <title>Historias de terror</title>
    
    <!-- Favicon -->
    <link rel="icon" href="img/ghost.ico" type="image/x-icon"/>
    <meta charset="utf-8">
    
    <!-- Para que la página se ajuste al tamaño de la pantalla -->
    <meta name="viewport" content="width=device-width">
    
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
    
    // Este código no lo he podido separar en un JS aparte ya que tiene código PHP
    $(document).ready(function() {
    // Cargo el editor de texto
    tinymce.init({height : "250",forced_root_block : "", selector:'#historia',menubar:false,statusbar: false,plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools colorpicker textpattern help',
      toolbar1: 'bold italic underline strikethrough forecolor backcolor | removeformat',});
      
      //Si hay una sesión iniciada cambiar el mensaje de la cabecera
    	<?php if (isset($_SESSION['id'])) {
    	?>
    
    	$("#infologin").html("Bienvenido " + "<?= $_SESSION['usr'] ?>");
    	usr = "<?= $_SESSION['usr'] ?>";
    	usuario_id = "<?= $_SESSION['usuario_id'] ?>";
    	<?php
    	}
    	?>
    
    	$("#categoriaañadir").html("<?= $z ?>"); // Muestra un seleccionable de categorias al añadir una historia
    	$("#categoria").html("<?= $z ?>"); // Muestra un seleccionable de categorias al buscar una historia
    	$(".listadecategorias").html("<?= $c ?>") // Muestra una lista de categorías para buscar la historia por categoría
    
    	<?php // Si se obtiene por get la variable delete se mostrará un modal
    	if (isset($_GET['delete'])) {
    		?>
    		$("#info").html("Se ha eliminado la historia correctamente");
    		$("#infomodal").css('display','block');
    		<?php
    	}
    	?>
    
    	<?php // Si se obtiene por get la variable add se mostrará un modal
    	if (isset($_GET['add'])) {
    		?>
    		$("#info").html("Se ha añadido la historia correctamente");
    		$("#infomodal").css('display','block');
    		<?php
    	}
    	?>
    
    	<?php
    	if (isset($_GET['asc'])) {
    		?>
    		$("#asc").attr("checked",true);
    		<?php
    
    	}
    	?>

    	<?php
    	if (isset($_GET['propias'])) {
    		?>
    		$("#propias").attr("checked",true);
    		<?php
    
    	}
    	?>

    	<?php
    	if (isset($_SESSION['id'])) {
    		?>
    		$("#propiasdiv").show();
    		<?php
    
    	}
    	?>
       
    
    });
    
    </script>

</head>
<body>
    <!--Header de la página-->
    <header id="header" style="position:fixed">
    
    	<span id="infologin">No has iniciado sesión</span>
    	<a href="javascript:void(0);" class="icon" onclick="toogleMenu()">&#9776;</a>
    
    <nav id="menu">
    <ul id="botones" class="topnav">
        <!-- Se cambian los botones según se haya iniciado sesión o no -->
    		<?php if (isset($_SESSION['id'])) {
    		?>
    		<li><button id="botonanadir"><i class="fas fa-plus"></i> Añadir</button></li>
    		<li><button id='botonperfil'><i class="fas fa-user-alt"></i> Ver perfil</button></li>
    		<li><button id="botonlogout"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button></li>
    		<?php
    		} else {
    			?>
    			<li><button id="botonregistrer"><i class="fas fa-user-plus"></i> Registrarse</button></li>
    			<li><button id="botonlogin"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</button></li>
    			<?php
    		}
    		?>
    	</ul>
    		
    	</nav>
    </header>
    <br>
    <br>
    <br>
    <br>
    
    <!-- CAPA DE DIALOGO MODIFICAR HISTORIA -->
    <div class="dialogo" id="dialogomodificar" title="Modificar historia" >
    <?php include "formularios/form_modificar.php"; ?>
    </div>
    
    <!-- CAPA DE DIALOGO AÑADIR HISTORIA -->
    <div class="dialogo" id="dialogoañadir" title="Añadir historia">
    	<?php include "formularios/form_anadir.php"; ?>
    
    </div>
    
    <!-- CAPA DE DIALOGO LOGIN -->
    <div class="dialogo" id="dialogologin" title="Iniciar sesión">
    	<?php include "formularios/form_login.php"; ?>
    
    </div>
    
    <!-- CAPA DE DIALOGO REGISTRO -->
    <div class="dialogo" id="dialogoregistrer" title="Registrarse">
    	<?php include "formularios/form_registrer.php"; ?>
    
    </div>
    
    <!-- CAPA DE DIALOGO RECUPERAR CONTRASEÑA -->
    <div class="dialogo" id="dialogopassrecover" title="Recuperar Contraseña">
    	<?php include "formularios/form_passrecover.php"; ?>
    
    </div>
    
    <!-- CAPA DE DIALOGO CERRAR SESIÓN -->
    <div id="dialogologout" title="Cerrar sesión">
    	<span>¿Está seguro de cerrar la sesión?</span>
    </div>
    </div>
    
    <!-- Botón que muestra las opciones avanzadas -->
    <button id="advancedopbutton"><i class="fas fa-sliders-h"></i> Opciones avanzadas</img></button>
    
    <!-- Opciones avanzadas -->
    <div id="opavanzadas" style="display: none">
    <form method="GET">
    <input type="text" name="busquedahistoria" id="buscadorhistorias" value="<?= $termino ?>" placeholder="Buscador"></input><br>
    
    	<b>Ordenar por:</b> <br>
    
    	<select id="ordenapor" name="ordenapor">
    	    <option value="id">Creación</option>
    		<option value="título">Título</option>
    		<option value="notamedia">Nota media</option>
    		<option value="LENGTH(Historia)">Longitud</option>
    	</select>
    	<input type="checkbox" name="asc" id="asc" value="asc">ASC
    
    	<select id="categoria" name="categoria">
    		
    	</select>
    	<div id="propiasdiv" style="display:none"><input type="checkbox" name="propias" id="propias" value="yes">Tus historias</div>

    	<input type="submit" value="Buscar">
    </form>
    </div>
    <script>
    <?php
    // Si el usuario que se logea es administrador se muestra el botón para ir a la página de administración
    
    if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
    	?>
    	$("#administracion").remove();
    	$( "<li><button id='administracion'><i class='fas fa-cog'></i> Administración</img></button></li>" ).prependTo( "nav ul" );
    	<?php
    }
    ?>
    </script>
    <div id="contenedor">
    
    	<!-- Mostramos la lista de historias -->
    	<?php
    	if (isset($fla['0'])){
    		
    	foreach ($fla as $historia) {
    		echo "<div class='historia' style='text-align: center;'>";
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
        
    		echo "<p>".substr(str_replace("\n", "<br>", ucfirst($historia['Historia'])),0,200).$puntos."</p>";
    		// Para acceder a la historia completa
    		echo "<a class='enlacehistoria' href='historia?id=".$historia['id']."'>Ver historia completa --></a><br>";
    		echo "</br>";
    		echo "</div>";
    		
    		
    	}
    
    	echo "</div>";
    
    	} else {
    		echo "No se ha encontrado ninguna historia<br>";
    	}
    	?>
    	
    
    	 <input type="hidden" id="row" value="3">
     <input type="hidden" id="all" value="<?php echo $allcount; ?>">
    
    	
    
    </div>
     <button onclick="topFunction()" id="goup" title="Ir arriba">Subir</button> 
    
    <!-- El modal -->
    	<div id="infomodal" class="modal">
    
    	  <!-- Contenido del modal -->
    	  <div class="modal-content">
    	    <span class="close">&times;</span>
    	    <p id="info"></p>
    	  </div>
     <script>
     
    $(document).ready(function(){
    	var categoria;
    	var termino;
    	var ordenapor;
    	var asc = "DESC";
    
    // Recogemos por post si existe la categoria, el termino y ordenapor
     <?php if (isset($_GET['categoria']) && $_GET['categoria'] != "") {?>
    	  	categoria = "<?= $_GET['categoria'] ?>"
    	  	$('#categoria option[value=<?= $_GET['categoria'] ?>]').attr('selected',true);
      <?php } ?>
    
      <?php if (isset($_GET['busquedahistoria']) && $_GET['busquedahistoria'] != "") {?>
    	  	termino = "<?= $_GET['busquedahistoria'] ?>"
    	  	<?php } ?>
    
    	<?php if (isset($_GET['ordenapor']) && $_GET['ordenapor'] != "") {?>
    	  	ordenapor = "<?= $_GET['ordenapor'] ?>"
    	  	$('#ordenapor option[value=<?= $_GET['ordenapor'] ?>]').attr('selected',true);
    	  	<?php } ?>
    	  	
    	  <?php if (isset($_GET['asc'])) {?>
    	  	asc = "ASC"
    	  	<?php } ?>
    
     $(window).scroll(function(){ 
        scrollFunction();
    
    	  var d = document.documentElement;
    	  var offset = d.scrollTop + window.innerHeight;
    	  var height = d.offsetHeight;
    
    	  	// Si estamos en la parte de abajo de la pantalla
    	  if((height - offset) < 5) {
    
    		   var row = Number($('#row').val());
    		   var allcount = Number($('#all').val());
    		   var rowperpage = 3;
    		   row = row + rowperpage;
                
    		   // Si el número de historias mostradas es menor al número total de historias
    			if(row <= allcount){
    				$('#row').val(row);
    				$.ajax({
    					 url: 'recoger_historias.php',
    					 type: 'post',
    					 data: {row:row, categoria: categoria,busquedahistoria: termino, ordenapor: ordenapor,asc:asc},
    					 success: function(response){
    					 	
    					 	 $(".historia:last").after(response).show().fadeIn("slow");
    					 	
    					 }
    				});
    			}
    		}
    
    	});
     
    });
    
    // When the user scrolls down 20px from the top of the document, show the button
    
    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("goup").style.display = "block";
        } else {
            document.getElementById("goup").style.display = "none";
        }
    }
    
    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    } 
    </script>
</body>
</html>