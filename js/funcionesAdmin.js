//Variables que irán cambiando conforme navegamos en la página
var numpage; //Variable que indica en que página de la paginación estamos
var usuarioselect; //Variable que indica el usuario seleccionado en la lista de usuarios
var usr;
var ordenartipo; //Variable que indica cómo se ordena las historias en la tabla
var terminoabuscar; //Variable que indica el término introducido en el buscador
var catselect; //Variable que indica la categoría seleccionada

// Busqueda de historia por categorias
$(document).on("click",".listadecategorias li a",function(){
	catselect = $(this).data("id");
	var asc = false;
	if ($("#asc").is(":checked")) {
		asc = true;

	}
	$.post("listaadmin.php",{categoria:catselect, busquedahistoria:terminoabuscar, ordenapor: ordenartipo, asc:asc},function(data){$("#contenedor").html(data);	
	});


});

function toogleMenu() { //Para mostrar o ocultar el menú en caso de que estemos en una pantalla de móvil
    var x = document.getElementById("botones");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

$(document).ready(function() {
// Validar form añadir
	$('#añadirform').validate({
		rules: {
			titulo: { required: true},
			historia: { required: true},
			fecha: { required: true}
		},
		messages: {

			titulo: "Ingrese un titulo.",
			
			historia: "Debe escribir una historia.",
			fecha: "Debe introducir una fecha."

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
					window.location.href = "administracion";

				}
			}
				
			});
			$("#dialogoañadir").dialog( "close" );
		}
	});

	// Validar form añadir
	$('#añadircategoriaform').validate({
		rules: {
			nombrecategoria: { required: true}
		},
		messages: {

			nombrecategoria: "Ingrese un nombre."
		},
		submitHandler: function (form) {

			// Recojo los datos del formulario
			var form_data = new FormData();
			form_data.append('categoria',$("#nombrecategoria").val())

			$.ajax({
			    url : "operaciones/categoria_anadir.php",
			    type: "POST",
			    data : form_data,
			    processData: false,
			    contentType: false,
			    success:function(data){
				if (data == "duplicated") {
					$("#info").html("Ya existe una categoría con ese nombre, elija otro");
					$("#infomodal").css('display','block');
				} else {
					window.location.href = "administracion?listacategorias";

				}
			}
				
			});
			$("#dialogoañadircategoria").dialog( "close" );
		}
	});


$("#añadir").hide();


var idhistoria;
//VENTANA DIALOGO DE BORRAR
	$("#dialogoborrar").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Borrar": function() {
			var parametros = {
				"idhistoria" : idhistoria,
				admin: true
			};

			$.ajax({
                data:  parametros,
                url:   'operaciones/historia_borrar.php',
                type:  'post',
                success:  function (data) {				
					$("#contenedor").html(data);
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
var idcategoria
	//VENTANA DIALOGO DE BORRAR
	$("#dialogoborrarcategoria").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Borrar": function() {
			var parametros = {
				"idcategoria" : idcategoria,
				admin: true
			};

			$.ajax({
                data:  parametros,
                url:   'operaciones/categoria_borrar.php',
                type:  'post',
                success:  function (data) {	
                	if (data == "error") {
                		$("#info").html("Esta categoria la tiene asignada una o más historias");
						$("#infomodal").css('display','block');
                	} else {
                		window.location.href = "administracion?listacategorias";
                	}		
					
				}
			});
			
			//get			
			//cierra ventana dialogo				
			$(this).dialog( "close" );												
		},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}
	});
//Dialogo admin
	$("#dialogoadmin").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Si": function() {
			var parametros = {
				"usuario" : usuarioselect,
			};

			$.ajax({
                data:  parametros,
                url:   'operaciones/admin_anadir.php',
                type:  'post',
                success:  function (data) {				
					$("#listausuarios").html(data);
				}
			});
			
			//get			
			//cierra ventana dialogo				
			$(this).dialog( "close" );	
		},
		"No": function() {
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
		"Cerrar sesión": function() {
			$.ajax({
                url:   'operaciones/logout.php',
                success:  function (data) {	
                	window.location.href = "historias";

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
	
	//MODIFICAR CATEGORIA
	$( "#dialogomodificarcategoria" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Guardar": function() {
					
			$('#modificarcategoriaform').submit();
					},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}//buttons
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
						window.location.href = "administracion";
					},1000);
				}
			}
			});//get	
		}
	});		

	// Validación formulario modificar categoria
	$('#modificarcategoriaform').validate({
		rules: {
			nombrecategoria: { required: true}
		},
		messages: {

			nombrecategoria: "introduzca un nombre.",
			
		},
		submitHandler: function (form) {

			// Recojo los datos del formulario
			var form_data = new FormData();
			form_data.append('categoria_id',$("#idcategoriamodificar").val())
			form_data.append('categoria_nombre',$("#nombrecategoriamodificar").val())

			$.ajax({
                data:  form_data,
                url:   'operaciones/categoria_modificar.php',
                type:  'post',
                contentType: false,
				processData: false,
                success:  function (data,status) {
                	$("#dialogomodificarcategoria").dialog( "close" );
                if (data == "duplicated") {
                	$("#info").html("La categoria introducida ya existe en la base de datos");
					$("#infomodal").css('display','block');
					
                } else {
                	
					$("#info").html("Se han guardado los cambios");
					$("#infomodal").css('display','block');
					
					setTimeout(function () {
						window.location.href = "administracion?listacategorias";
					},1000);
				}
			}
			});//get	
		}
	});		


	
	//--- PAGINACION -----
$(document).on("click",".pagination li a",function(){
	numpage = $(this).data("page");
	var asc = false;
	if ($("#asc").is(":checked")) {
		asc = true;

	}
	$.post("listaadmin.php",{page:numpage, ordenapor: ordenartipo,busquedahistoria: terminoabuscar, asc:asc},function(data){$("#contenedor").html(data);	
	});

});

//Paginación lista de usuarios
$(document).on("click",".paginationUsuarios li a",function(){
	numpage = $(this).data("page");
	$.post("listausuarios.php",{page:numpage},function(data){$("#listausuarios").html(data);	
	});

});

//Paginación lista de categorias
$(document).on("click",".paginationCategorias li a",function(){
	numpage = $(this).data("page");
	$.post("listacategorias.php",{page:numpage},function(data){$("#listacategorias").html(data);	
	});

});

// Buscador
$("#buscadorhistorias").on("keypress keyup", function () {
	terminoabuscar = $("#buscadorhistorias").val();
	
	$.ajax({
			url: "listaadmin.php",
			data:{busquedahistoria:$("#buscadorhistorias").val()},
			type: "post",
			//beforeSend: cargar,
			success: function(data) {
				$("#contenedor").html(data);
			},
			cache: false
		});
}
);

//Ordenar
$(".ordena").on("click",function(){
		
		//obtener el ordenapor
		ordenatipo=$(this).val();
		ordenartipo= $(this).val();
		var asc = false;
		if ($("#asc").is(":checked")) {
			asc = true;

		}
		$.ajax({
			url: "listaadmin.php",
			data:{ordenapor:ordenatipo, busquedahistoria:terminoabuscar, categoria:catselect, asc: asc},
			type: "post",
			//beforeSend: cargar,
			success: function(data) {
				$("#contenedor").html(data);

			},
			cache: false
		});
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

// AÑADIR CATEGORIA
$( "#dialogoañadircategoria" ).dialog({
		autoOpen: false,
		resizable: true,
		modal: true,
		buttons: {

		"Añadir": function() {	
			$('#añadircategoriaform').submit();						
					},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}//buttons
	});	

// Al hacer click en el botón de borrar	
	$(document).on("click",".borrar",function(){
		idhistoria = $(this).parent().siblings("td.id").html();
		 $("#dialogoborrar").dialog("open");		
		
	});

	// Al hacer click en el botón de borrar categoria
	$(document).on("click",".borrarcategoria",function(){
		idcategoria = $(this).parent().siblings("td.id").html();
		 $("#dialogoborrarcategoria").dialog("open");		
		
	});

	// Al hacer click en el botón de añadir
	$(document).on("click","#botonanadir",function(){
		 $("#dialogoañadir").dialog("open");		
		
	});

	// Al hacer click en el botón de añadir
	$(document).on("click","#botonanadircategoria",function(){
		 $("#dialogoañadircategoria").dialog("open");		
		
	});

	// Al hacer click en el botón de opciones avanzadas
	$(document).on("click","#advancedopbutton",function(){
		 $("#opavanzadas").fadeToggle();
	});

	// Al hacer click en el botón de mostrar usuarios
	$(document).on("click","#botonmostrarusuarios",function(){
		$("#botonanadircategoria").hide();
		$("#listacategorias").hide();

		 $("#listausuarios").show();
		 $("#contenedor").hide();
		 $("#advancedopbutton").hide();
		 $("#botonanadir").hide();
		 $("#opavanzadas").hide();
		 $("#botonmostrarusuarios").hide();
		 $("#botonmostrarhistorias").show();
		 $("#botonmostrarcategorias").show();
		 
		 
	});

	// Al hacer click en el botón de mostrar historias
	$(document).on("click","#botonmostrarhistorias",function(){
		$("#botonanadircategoria").hide();
		$("#listacategorias").hide();
		 $("#contenedor").show();
		 $("#listausuarios").hide();
		 $("#advancedopbutton").show();
		 $("#botonanadir").show();
		 $("#botonmostrarusuarios").show();
		 $("#botonmostrarhistorias").hide();
		 $("#botonmostrarcategorias").show();
		 
		 
	});


// Al hacer click en el botón de mostrar categorias
	$(document).on("click","#botonmostrarcategorias",function(){
		 $("#listausuarios").hide();
		 $("#listacategorias").show();
		 $("#contenedor").hide();
		 $("#advancedopbutton").hide();
		 $("#botonanadir").hide();
		 $("#opavanzadas").hide();
		 $("#botonmostrarusuarios").show();
		 $("#botonmostrarhistorias").show();
		 $("#botonmostrarcategorias").hide();
		 $("#botonanadircategoria").show();
		 
	});


	// Al hacer click en el botón de cerrar sesión
	$(document).on("click","#botonlogout",function(){
		 $("#dialogologout").dialog("open");		
		
	});

	// Al hacer click en el botón de administrador
	$(document).on("click","#botonadmin",function(){
		 $("#dialogoadmin").dialog("open");
		 $("#nombreusuario").html($(this).parent().siblings(".usuario").attr('id'));		
		usuarioselect = $(this).parents().siblings(".id").attr('id')
	});

	// Al hacer click en el botón de volver a la página principal
	$("#goback").on("click",function(){
		 window.location.href = "historias";
		
	});


});



//Al clickear en el botón modificar
$(document).on("click",".modificar",function(){
	idhistoria = $(this).parents().siblings("td.id").attr("id");
	categoria_id = $(this).parents().siblings("td.categoria").attr("id");
	imganterior = $(this).parents().siblings("td.img").attr("id");

	//Para que ponga el campo direccion con su valor
	$("#titulomodificar").val($(this).parent().siblings("td.titulo").html());
	
	
	//historia
	
	$("#idhistoria").val(idhistoria);
	$('#categoriamodificar option[value=' + categoria_id + ']').attr('selected',true);
	$("#imganterior").val(imganterior);
	
    var historia = $.trim($(this).parent().siblings("td.contenido").html());
	//$("#historiamodificar").val(historia);
	
	var editor = tinymce.get('historiamodificar'); // use your own editor id here - equals the id of your textarea
    var content = editor.getContent();
    editor.setContent(historia);
$( "#dialogomodificar").dialog("open");
});


//Al clickear en el botón modificar categoria
$(document).on("click",".modificarcategoria",function(){
	idcategoria = $(this).parents().siblings("td.id").attr("id");
	nombrecategoria = $(this).parents().siblings("td.nombre").attr("id");

	$("#nombrecategoriamodificar").val(nombrecategoria);
	$("#idcategoriamodificar").val(idcategoria);
$( "#dialogomodificarcategoria").dialog("open");
});