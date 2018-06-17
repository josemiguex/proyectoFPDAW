<!-- Formulario de añadir contraseña -->
<form id="añadirform" enctype="multipart/form-data" method="POST">
		<b>Título *</b><br> <input id="titulo" type="text" name="titulo" required></input><br>
		<b>Historia *</b><br> 
		
		<textarea id="historia" cols="70" rows="15" name="historia" required></textarea><br>

		<b>Categoría *</b><br> <select id="categoriaañadir" name="categoria" required></select><br>
		
		<label for="img"><b>Imagen</b></label><br>
		<input id="img" type="file" name="img"></input>
		</select>
	</form>