<?php

include "conexion.php";
  // Respuesta
  $ans = [] ;

  // Buscamos en la URL la API_KEY del usuario.
  $api = $_GET["api_key"] ;

  
// Si no se introduce nada, muestra mensaje de error de que debes introducir una key válida
  if (empty($api)) {  
    $ans["status_message"] = "Invalid API key: You must be granted a valid key." ;
    $ans["success"] = false ;
    
  } else {

   //Recoge los datos de la historia del código introducido
    $sql = "SELECT historiasDeTerror.*, categorias.nombre as categoria FROM historiasDeTerror INNER JOIN categorias ON categorias.id=historiasDeTerror.categoria_id WHERE SHA1(CONCAT(historiasDeTerror.id,historiasDeTerror.título)) = '$api' " ;

    $res = $lnk->query($sql) ;
    //Si no hay ninguna fila se muestra un mensaje de error de que no encuentra una historia
    if ($res->rowCount() == 0) {      
      $ans["status_message"] = "No se encuentra la historia en la base de datos." ;
      $ans["success"] = false ;
    } else {
      $asg = [];
      $data = $res->fetchAll(PDO::FETCH_ASSOC); 

        
      $ans["success"] = true ;
      $ans["data"] = $data ;
    }
  }

  // Respondemos a la petición
  header("Content-Type: application/json;charset=utf-8") ;
  echo json_encode($ans) ;
