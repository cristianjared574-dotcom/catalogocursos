<?php
header("Content-Type:application/json");
require_once "config/conexionCursos.php";

try{
    $stmt=$pdo->query("SELECT id_carrera,nombre_carrera FROM carreras ORDER BY nombre_carrera ASC");
    $carreras=$stmt->fetchAll();
    echo json_encode($carreras);

}catch(Exception $e){
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Error al obtener las carreras:".$e->getMessage()]);    
}
?>