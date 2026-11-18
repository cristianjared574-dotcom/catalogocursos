<?php
header("Content-Type:application/json;charset=UTF-8");
require_once "config/conexionCursos.php"; //conexion

$id_carrera=isset($_GET['id_carrera'])?intval($_GET['id_carrera']):0;

try{
    if($id_carrera>0){
        $stmt=$pdo->prepare("SELECT id_curso, id_carrera, nombre_curso, codigo_curso, descripcion_curso, salon, edificio, total_horas, dias_atencion, hora_inicio, hora_fin, cupo_total, ruta_imagen FROM cursos WHERE id_carrera = ?");
        $stmt->execute([$id_carrera]);
    }else{
        $stmt=$pdo->query("SELECT id_curso, id_carrera, nombre_curso, codigo_curso, descripcion_curso, salon, edificio, total_horas, dias_atencion, hora_inicio, hora_fin, cupo_total, ruta_imagen FROM cursos");
    }
    $cursos=$stmt->fetchAll();

    foreach($cursos as &$c){
        $c['id_curso']=(int)$c['id_curso'];
        $c['id_carrera']=(int)$c['id_carrera'];
        $c['total_horas']=(int)$c['total_horas'];
        $c['cupo_total']=(int)$c['cupo_total'];
    }

    echo json_encode($cursos);
}catch(Exception $e){
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Error al obtener los catalogos de cursos:".$e->getMessage()]);
}
?>