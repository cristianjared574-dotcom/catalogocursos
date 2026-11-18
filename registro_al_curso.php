<?php
//Le indicamos al cliente (Android/Retrofit) que la respuesta es un JSON estructurado
header("Content-Type: application/json; charset=UTF-8");

//importar el archivo de conexión creado
require_once "config/conexionCursos.php";

try{

    //leer json enviados desde Android
    $jsonInput = file_get_contents("php://input");
    $data = json_decode($jsonInput, true);

    /*aqui se van a recibir los parametros enviados desde android por
    POST ya que este es un metodo de peticion para llenar formularios y es discreto
    trim elimina espacios en blanco de una cadena*/
    $id_curso=isset($data['id_curso']) ? intval($data['id_curso']):0;
    $matricula=isset($data['matricula']) ? trim($data['matricula']):"";
    $fecha_registro=isset($data['fecha_registro']) ? trim($data['fecha_registro']):"";
    $correo_institucional=isset($data['correo_institucional']) ? trim($data['correo_institucional']):"";

    /*if para validar que los campos no esten vacios, utilizamos empty para validar si los campos estan sin contenido
    */
    if($id_curso<=0|| empty($matricula)||empty($fecha_registro)||empty($correo_institucional)){
        header("HTTP/1.1 400 Bad Request");
        echo json_encode([
            "error"=> "Faltan datos obligatorios para completar el registro al curso."
        ]);
        exit;
    }

    //comprobar si el alumno existe
    $stmt=$pdo->prepare("SELECT matricula FROM alumnos WHERE matricula = ?");
    $stmt->execute([$matricula]);
    $alumno=$stmt->fetch();

    //si no existe, guardar su matricula y correo
    if(!$alumno){
        $stmt=$pdo->prepare("INSERT INTO alumnos (matricula, correo_institucional) VALUES (?,?)");
        $stmt->execute([$matricula, $correo_institucional]);
    }

    //si existe, prepara la inscripcion al curso
    $stmt=$pdo->prepare("INSERT INTO inscripciones (id_curso, matricula, fecha_registro) VALUES (?,?,?)");
    //$stmt=$pdo->prepare($stmt);

    //se ejecuta la insercion con los valores recibidos
    $resultado=$stmt->execute([$id_curso, $matricula, $fecha_registro]);

    if($resultado){
        //respuesta exitosa en formato json
        echo json_encode([
            "mensaje"=> "Registro al curso guardado con éxito"

        ]);
    }else{
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode([
            "error"=> "No se pudo almacenar el registro de base de datos"
        ]);
    }

}catch(Exception $e){
    //errores criticos del servido
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode([
        "error"=> "Error en el servidor: ".$e->getMessage()
    ]);
}
?>