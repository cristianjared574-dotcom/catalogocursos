<?php
header("Content-Type: application/json; charset=UTF-8");

//importar el archivo de conexión creado
require_once "config/conexionCursos.php";

    //se lee el json enviado en el cuerpo de la peticion body
    $jsonInput = file_get_contents("php://input");
    $data = json_decode($jsonInput, true);

    $matricula=isset($data['matricula']) ? trim($data['matricula']):'';

    if(empty($matricula)){
        header("HTTP/1.1 400 Bad Request");
        echo json_encode([
            "exito"=> false,
            "mensaje"=> "Faltan datos obligatorios para completar el registro al curso."
        ]);
        exit;
    }

    try{
    $stmt=$pdo->prepare("SELECT matricula,nombre_alumno, correo_institucional FROM alumnos WHERE matricula = ?");
    $stmt->execute([$matricula]);

    $alumno=$stmt->fetch();

    if($alumno){
        echo json_encode([
            "exito"=> true,
            "mensaje"=> "El alumno " . $alumno['nombre_alumno'] . " existe!",
            "matricula" => $alumno['matricula'],
            "nombre_alumno" => $alumno['nombre_alumno'],
            "correo_institucional"=> $alumno['correo_institucional']

        ]);
    }else{
        echo json_encode([
            "exito"=>false,
            "mensaje"=> "Alumno no existe"
        ]);
    }


    }catch(Exception $e){
    //errores criticos del servido
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode([
        "exito"=>false,
        "mensaje"=> "Error en el servidor: ".$e->getMessage()
    ]);
    }

?>
