var usr;
var usuario_id;
var ordenartipo;
var terminoabuscar;
var catselect;
var idhistoria;
var numpage;

// Actualiza la lista de historias
function rellenar(data) {
	$("#contenedor").html(data);
}

// Para comprobar si una variable es un número
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function openPassRecoverForm() {
	$("#dialogologin").dialog( "close" );
	$("#dialogopassrecover").dialog( "open" );
}

function toogleMenu() { //Para mostrar o ocultar el menú en caso de que estemos en una pantalla de móvil
    var x = document.getElementById("botones");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

// Al hacer click en el botón de puntuar
$(document).on('click','#puntuar', function() {
	if (isNumber($("#nota").val()) && $("#nota").val() >= 0 && $("#nota").val() <= 10) {
		var parametros = {
			puntuacion: $("#nota").val(),
			historia_id: $("#contenedor").children().siblings("h2.titulo").attr('data-idhistoria'),
			usuario_id: usuario_id
		};
		$.ajax({
	        data:  parametros,
	        url:   'operaciones/puntuar.php',
	        type:  'post',
	        dataType:'text',
	        success:  function (data) {

	        	$("#notamedia").html(" " + data);
	        	$("#puntuardiv").css('display','none');
	        	$("#eliminarpuntuaciondiv").css('display','block');
	        	$("#errorpuntuacion").html("");
	        }

		});
	} else {
		$("#errorpuntuacion").html("Valor introducido inválido");
	}
});


// Al hacer click en el botón de eliminar puntuación
$(document).on('click','#eliminarpuntuacion', function() {

	var parametros = {
		historia_id: $("#contenedor").children().siblings("h2.titulo").attr('data-idhistoria'),
		usuario_id: usuario_id
	};

	$.ajax({
                data:  parametros,
                url:   'operaciones/eliminarpuntuacion.php',
                type:  'post',
                dataType:'text',
                success:  function (data) {
                	if (data != "") {
                		$("#notamedia").html(" " + data);
                	} else {
                		$("#notamedia").html(" Sin asignar");
                	}
                	$("#eliminarpuntuaciondiv").css('display','none');
                	$("#puntuardiv").css('display','block');
                	

                }
	});
});


$(document).ready(function() {


	$("#emailrecover").on("keypress keyup", function () {
		$("#error").html("");
	});




	// Al pulsar el botón x del modal se cierra
	$(".close").on('click', function() {
		$("#infomodal").css('display','none');
	});

	// Ahora se mostrarán las validaciones de los distintos formularios
	$('#passrecoverform').validate({
		rules: {
			email: { required: true},
		},
		messages: {

			email: "Ingrese un email correcto.",
			
		},
		submitHandler: function () {

			var parametros = {
				email: $("#emailrecover").val(),
			};

			$.ajax({
                data:  parametros,
                url:   'operaciones/recuperar_contrasena.php',
                type:  'post',
                dataType:'text',
                success:  function (data) {
                	if (data == true) { // Si devuelve true se muestra un modal mostrando el mensaje
                		$("#dialogopassrecover").dialog( "close" );
                		$("#info").html("Se ha enviado un email con la nueva contraseña");
						$("#infomodal").css('display','block');
                	} else {
                		$("#error").html("No hay ningún usuario con este email");
                	}
                }

			});		
		}
	});

	//Validación formulario login
	$('#loginform').validate({
		rules: {
			usr: { required: true},
			pass: { required: true}
		},
		messages: {

			usr: "Ingrese un nombre de usuario.",
			
			pass: "Ingrese una contraseña."

		},
		submitHandler: function (form) {

			var parametros = {
				usr : $("#usr").val(),
				pass : $("#pass").val()
			};

			$.ajax({
                data:  parametros,
                url:   'operaciones/login.php',
                type:  'post',
                dataType:'text',
                success:  function (value) {
                	var data = value.split(",");

                	if (data == false) {
                		$("#infologin").html("Usuario/Contraseña incorrectos");
                	} else {
                	// Para que no sea instantáneo el inicio se sesión pongo un setTimeout
                	$("#infologin").html("Iniciando sesión...");
	                	setTimeout(function () {
	    					$("#botonregistrer").remove();
							$("#infologin").html("Bienvenido " + $("#usr").val());
							$("#botonlogin").html("<i class='fas fa-sign-out-alt'></i> Cerrar sesión");
							$("#botonlogin").attr("id",'botonlogout');
							$("#propiasdiv").show();
							usuario_id = data[0];
							
							$( "<li><button id='botonanadir'><i class='fas fa-plus'></i> Añadir</img></button></li>" ).prependTo( "#botones" );
							$( "<li><button id='botonperfil'><i class='fas fa-user-alt'></i> Ver perfil</button></li>" ).prependTo( "#botones" );
							
							if (data[1] == "admin") {
								$( "<li><button id='administracion'><i class='fas fa-cog'></i> Administración</img></button></li>" ).prependTo( "nav ul" );
							}

					}, 1000);
				}
			}

			});
			
			//get			
			//cierra ventana dialogo				
			$("#dialogologin").dialog( "close" );	
		}
	});

// Validación formulario añadir
	$('#añadirform').validate({
		rules: {
			titulo: { required: true},
			historia: { required: true},
			categoria: { required: true}
		},
		messages: {

			titulo: "Ingrese un titulo.",
			
			historia: "Debe escribir una historia.",
			categoria: "Debe introducir una categoria."

		},
		submitHandler: function (form) {

			// Recojo los datos del formulario
			var form_data = new FormData();
			form_data.append('img',$("#img").prop("files")[0])
			form_data.append('titulo',$("#titulo").val())
			form_data.append('historia',tinyMCE.activeEditor.getContent())
			form_data.append('usuario_id',usuario_id)
			form_data.append('fecha',$("#fecha").val())
			form_data.append('categoria_id',$("#categoriaañadir").val())

			$.ajax({
			    url : "operaciones/historia_anadir.php",
			    type: "POST",
			    data : form_data,
			    processData: false,
			    contentType: false,
			    success:function(data){
				if (data == "duplicated") {
					$("#info").html("Ya existe una historia con ese título, elija otro");
					$("#infomodal").css('display','block');
				} else {
					window.location.href = "historias";

				}
			}
				
			});
			$("#dialogoañadir").dialog( "close" );
		}
	});

	// Validación formulario modificar
	$('#modificarform').validate({
		rules: {
			titulo: { required: true},
			historia: { required: true},
		},
		messages: {

			titulo: "Ingrese un titulo.",
			
			historia: "Debe escribir una historia.",

		},
		submitHandler: function (form) {

			// Recojo los datos del formulario
			var form_data = new FormData();
			form_data.append('img',$("#imgmodificar").prop("files")[0])
			form_data.append('imganterior',$("#imganterior").val())
			form_data.append('titulo',$("#titulomodificar").val())
			form_data.append('historia',tinyMCE.activeEditor.getContent())
			form_data.append('idhistoria',$("#idhistoria").val())
			form_data.append('categoria_id',$("#categoriamodificar").val())

			$.ajax({
                data:  form_data,
                url:   'operaciones/historia_modificar.php',
                type:  'post',
                contentType: false,
				processData: false,
                success:  function (data,status) {
                	$("#dialogomodificar").dialog( "close" );
                if (data == "duplicated") {
                	$("#info").html("La historia introducida ya existe en la base de datos");
					$("#infomodal").css('display','block');
					
                } else {
                	
					$("#info").html("Se han guardado los cambios");
					$("#infomodal").css('display','block');
					
					setTimeout(function () {
						window.location.href = "historia?id=" + data
					},1000);
				}
			}
			});//get	
		}
	});

// Validación formulario registro
	$('#registrerform').validate({
		rules: {
			name: { required: true},
			surname: { required: true},
			email: { required: true},
			usr: { required: true},
			pass: { required: true},
			passconfirm: { required: true}
			
			

		},
		messages: {

			usr: "Ingrese un nombre de usuario.",
			name: "Ingrese su nombre.",
			surname: "Ingrese sus apellidos.",
			email: "Ingrese un email correcto.",
			pass: "Ingrese una contraseña.",
			passconfirm: "Confirme la contraseña."

		},
		submitHandler: function (form) {
			// Recogemos los datos del formulario			

			var form_data = new FormData();
			form_data.append('avatar',$("#avatar").prop("files")[0])
			form_data.append('usr',$("#usrregistrer").val())
			form_data.append('pass',$("#passregistrer").val())
			form_data.append('passconfirm',$("#passconfirmregistrer").val())
			form_data.append('nombre',$("#name").val())
			form_data.append('apellidos',$("#surname").val())
			form_data.append('email',$("#email").val())

			$.ajax({
                data:  form_data,
                url:   'operaciones/registrer.php',
                type:  'post',
                contentType: false,
				processData: false,
                success:  function (data) {	
                	console.log(data);
	                	// Si recibe el valor "errorconfirm"
	                if (data == "errorconfirm") {
	                	$("#infologin").html("Las contraseñas no coinciden");
	                } else if (data != false){ // Si recibe false no hay ningún error
	                	$("#infologin").html("Registrando usuario e iniciando sesión...");
	                	
	                	setTimeout(function () {
	                		$("#botonregistrer").remove();
							$("#infologin").html("Bienvenido " + $("#usrregistrer").val());
							$("#botonlogin").html("<i class='fas fa-sign-out-alt'></i> Cerrar sesión");
							$("#botonlogin").attr("id",'botonlogout');
							usuario_id = data;
							$( "<li><button id='botonanadir'><i class='fas fa-plus'></i> Añadir</img></button></li>" ).prependTo( "#botones" );
						    $( "<li><button id='botonperfil'><i class='fas fa-user-alt'></i> Ver perfil</button></li>" ).prependTo( "#botones" );

						},1000);
	                	
	                } else {
	                	$("#infologin").html("El usuario/email introducido ya existe en la base de datos");
	                }
				}
			});
			
			//get			
			//cierra ventana dialogo				
			$("#dialogoregistrer").dialog( "close" );	
		}
	});




$("#añadir").hide();

// Ahora se mostrarán las distintas ventanas del Jquery UI

//VENTANA RECUPERACIÓN DE CONTRASEÑA
	$("#dialogopassrecover").dialog({
		autoOpen: false,
		resizable: true,
		modal: true,
		width: "420px",
		buttons: {
		"Recuperar contraseña": function() {
			$("#passrecoverform").submit();			
		},
		"Cancelar": function() {
				$("#dialogopassrecover").dialog( "close" );
		}
		}//buttons
	});

//VENTANA DIALOGO DE BORRAR
	$("#dialogoborrar").dialog({
		autoOpen: false,
		resizable: true,
		modal: true,
		buttons: {
		"Borrar": function() {

			var parametros = {
				"idhistoria" : idhistoria
			};

			$.ajax({
                data:  parametros,
                url:   'operaciones/historia_borrar.php',
                type:  'post',
                success:  function (data) {
					window.location.href = "historias?delete=1";
					
				}
			});
			
			//get			
			//cierra ventana dialogo				
			$(this).dialog( "close" );												
		},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}//buttons
	});	

	// LOGIN
	$("#dialogologin").dialog({
		autoOpen: false,
		resizable: true,
		modal: true,
		buttons: {
		"Iniciar sesión": function() {
			$("#loginform").submit();

			
			}											
		},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		//buttons
	});	
	// REGISTRARSE
	$("#dialogoregistrer").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		width: '500px',
		buttons: {
		"Registrarse": function() {
			$("#registrerform").submit();
														
		},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}//buttons
	});	

    

	//CERRAR SESIÓN
	$("#dialogologout").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Sí": function() {
			$.ajax({
                url:   'operaciones/logout.php',
                success:  function (data) {	
                $("#infologin").html("Cerrando sesión...");
                	setTimeout(function () {			
					$("#infologin").html("No has iniciado sesión");
					$( "<li><button id='botonregistrer'><i class='fas fa-user-plus'></i> Registrarse</button></li>" ).prependTo( "#botones" );
					$("#botonlogout").html("<i class='fas fa-sign-in-alt'></i> Iniciar sesión");
					$("#botonlogout").attr("id",'botonlogin');
					$("#propiasdiv").hide();
					$("#botonanadir").remove();
					$("#administracion").remove();
					$("#botonperfil").remove();
					
					$(".borrar").remove();
					$(".modificar").remove();
				},1000);
				}
			});
			
			//get			
			//cierra ventana dialogo				
			$(this).dialog( "close" );												
		},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}//buttons
	});



	
//MODIFICAR
$( "#dialogomodificar" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		width: '700px',
		buttons: {
		"Guardar": function() {
					
			$('#modificarform').submit();													
					},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}//buttons
	});		

	// AÑADIR
	$( "#dialogoañadir" ).dialog({
			autoOpen: false,
			resizable: true,
			modal: true,
			width: '700px',
			buttons: {

			"Añadir Historia": function() {	
				$('#añadirform').submit();
						},
			"Cancelar": function() {
					$(this).dialog( "close" );

			}
			}//buttons
		});	

		
	});


	// Al hacer click en el botón de borrar	
	$(document).on("click",".borrar",function(){
		idhistoria = $("#contenedor").children("h2.titulo").attr("data-idhistoria");
		 $("#dialogoborrar").dialog("open"); // Se mostrará la ventana de confirmación del borrado de la historia	
		
	});

	// Al hacer click en el botón de añadir
	$(document).on("click","#botonanadir",function(){
		 $("#dialogoañadir").dialog("open"); // Se mostrará la ventana de añadir historia	
		
	});

	// Al hacer click en el botón de iniciar sesión
	$(document).on("click","#botonlogin",function(){
		 $("#dialogologin").dialog("open");	// Se mostrará la ventana de inicio de sesión	
		
	});

	// Al hacer click en el botón de opciones avanzadas
	$(document).on("click","#advancedopbutton",function(){
		 $("#opavanzadas").fadeToggle(); // Se mostrará o se ocultará las opciones avanzadas	
	});


	// Al hacer click en el botón de cerrar sesión
	$(document).on("click","#botonlogout",function(){
		 $("#dialogologout").dialog("open"); // Mostrará la ventana de confirmación del cierre de sesión
	});

	// Al hacer click en el botón de registrarse
	$(document).on("click","#botonregistrer",function(){
		 $("#dialogoregistrer").dialog("open");	// Mostrará la ventana de registro
		
	});

	//Al hacer click en el botón de administración
	$(document).on("click","#administracion",function(){
		 window.location.href = "administracion"; // Se redirigirá a la página de administración
		
	});

	// Al hacer click en el botón de ver perfil
	$(document).on("click","#botonperfil",function(){
		 window.location.href = "perfil"; // Se redirigirá a la página que muestra información del perfil
		
	});

	//Al clickear en el botón modificar
	$(document).on("click",".modificar",function(){
		idhistoria = $("#contenedor").children("h2.titulo").attr("data-idhistoria");
		//Para que ponga el campo direccion con su valor
		$("#titulomodificar").val($("#contenedor").children("h2.titulo").html());
		
		var historia = $.trim($("#contenedor").children(".cuerpohistoria").html());
		$("#historiamodificar").val(historia);
		$("#idhistoria").val(idhistoria);
		

		$( "#dialogomodificar").dialog("open"); // Se abre la ventana de modificar historia
	});




// Paginación
$(document).on("click",".pagination li a",function(){
	 numpage = $(this).data("page");
	var asc = false;
		if ($("#asc").is(":checked")) {
			asc = true;
		}

	$.post("lista.php",{page:numpage, ordenapor: ordenartipo, busquedahistoria:terminoabuscar, asc: asc},function(data){rellenar(data);	
	});
});


// Al clickear en cualquier parte de la página excepto en el modal cierra el modal
$(window).on('click', function(event) {
	if (event.target == document.getElementById('infomodal')) {
		$("#infomodal").css('display','none');

	}

});

// Muestra u oculta la historia al hacer click en ella
function mostrar(id) {
	$("#" + id).fadeToggle();
}
