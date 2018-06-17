<!-- Formulario de cambiar contraseña-->
<form id="changepassform" method="POST">

	<div class="ac">
		<label for="actualpassword">Contraseña actual</label>
		<input type="password" id="actualpassword" name="actualpassword" required></input>
	</div>

	<div class="np"
		<label for="newpassword">Contraseña nueva</label>
		<input type="password" id="newpassword" name="newpassword" required></input>
	</div>

	<div class="npc">
		<label for="newpassword2">Confirmar contraseña</label>
		<input type="password" id="newpassword2" name="newpassword2" required></input>
	</div>

	<input type="hidden" id="usuario_id" value="<?= $usuario['0']['id'] ?>"></input>
	<span id="infoerror"></span>
</form>

