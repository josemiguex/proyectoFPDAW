// Funciones relacionadas con la gestión del perfil del usuario

$(document).ready(function() {
//VENTANA CAMBIAR CONTRASEÑA
	$("#dialogochangepass").dialog({
		autoOpen: false,
		resizable: true,
		modal: true,
		width: '500px',
		buttons: {
		"Cambiar contraseña": function() {
			$("#changepassform").submit();	
		},
		"Cancelar": function() {
				$("#dialogochangepass").dialog( "close" );
		}
		}//buttons
	});	

// Validación cambiar contraseña
	$('#changepassform').validate({
		rules: {
			actualpassword: { required: true},
			newpassword: { required: true},
			newpassword2: { required: true}
		},
		messages: {

			actualpassword: "Introduzca la contraseña actual.",
			newpassword: "Debe escribir la nueva contraseña.",
			newpassword2: "Debe confirmar la nueva contraseña."

		},
		submitHandler: function (form) {
			$("#infoerror").html("");

			// Se recoge los valores del formulario y se envian al archivo cambiar_contraseña.php
			$.post("operaciones/cambiar_contrasena.php", {
				"actualpassword": $("#actualpassword").val(),
				"newpassword": $("#newpassword").val(),
				"newpassword2" : $("#newpassword2").val()
				
			},function(data){
				// Si recibe un error, se muestra por pantalla ese error
				if (data == "error1") {
					$("#infoerror").html("<b>La contraseña actual es incorrecta</b>");
				} else if (data == "error2") {
					$("#infoerror").html("<b>Las contraseñas no coinciden</b>");
				} else if (data == "noerror") {
					// Se cierra la ventana y se imprime un modal mostrando un mensaje
					$("#dialogochangepass").dialog( "close" );
					$("#info").html("Se ha cambiado la contraseña correctamente");
					$("#infomodal").css('display','block');

					// Se limpia el formulario
					$("#changepassform")[0].reset();
				}

			});
			
		}
	});

	//VENTANA CAMBIAR IMAGEN DE USUARIO
	$("#dialogochangeuserimage").dialog({
		autoOpen: false,
		resizable: true,
		modal: true,
		width: '500px',
		buttons: {
		"Cambiar imagen": function() {
			$("#changeuserimageform").submit();	
		},
		"Cancelar": function() {
				$("#dialogochangeuserimage").dialog( "close" );
		}
		}//buttons
	});	

// Validación cambiar imagen usuario
	$('#changeuserimageform').validate({
		rules: {
			userimage: { required: true}
		},
		messages: {

			userimage: "Seleccione una imagen."
		},
		submitHandler: function (form) {
			// Se recoge los valores del formulario y se envian al archivo cambiar_imagen_usuario.php
			var form_data = new FormData();
			form_data.append('userimage',$("#userimage").prop("files")[0])
			form_data.append('usuario_id',$("#usuario_id").val())

			$.ajax({
                data:  form_data,
                url:   'operaciones/cambiar_imagen_usuario.php',
                type:  'post',
                contentType: false,
				processData: false,
                success:  function (data) {	
	                // Recargamos la página
	                location.reload();
				}
			});
			
		}
	});

	$("#changepass").on('click', function() {
		$("#dialogochangepass").dialog( "open" );
	});

	$("#changeuserimage").on('click', function() {
		$("#dialogochangeuserimage").dialog( "open" );
	});

	// Al pulsar el botón x del modal se cierra
	$(".close").on('click', function() {
		$("#infomodal").css('display','none');
	});

});

// Al clickear en cualquier parte de la página excepto en el modal cierra el modal
$(window).on('click', function(event) {
	if (event.target == document.getElementById('infomodal')) {
		$("#infomodal").css('display','none');

	}

});