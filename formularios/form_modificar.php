<!-- Formulario de modificar historia -->
<form id="modificarform" enctype="multipart/form-data">

	<label for="titulo"><b>Título</b> *</label><br>
	<input style="width: 80%" type="text" id="titulomodificar" name="titulo" value=""></input><br>
	
	<label for="historia"><b>Historia</b> *</label><br>
	
	<textarea id="historiamodificar" cols="70" rows="15" name="historia" required>
	</textarea>
	<b>Categoría *</b><br> <select id="categoriamodificar" name="categoria" required></select><br>
	<b>Cambiar imagen</b><br>
	<input id="imgmodificar" type="file" name="imgmodificar"></input>
	<input id="imganterior" type="hidden" name="imganterior" required></input>


	<input id="idhistoria" type="hidden" value="">
</form>
