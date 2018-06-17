<!-- Formulario de cambiar imagen de usuario-->
<form id="changeuserimageform" enctype="multipart/form-data" method="POST">
	<b>Imagen:</b><br> <input type="file" id="userimage" name="userimage" required></input><br>
	<input type="hidden" id="usuario_id" name="usuario_id" value="<?= $usuario['0']['id'] ?>"></input>
	<span id="infoerror"></span>
</form>

