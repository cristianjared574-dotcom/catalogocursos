<?php

header("Content-Type: application/json; charset=UTF-8");
require_once "config/conexionCursos.php";

$curso_id = isset($_GET['curso_id']) ? intval($_GET['curso_id']) : 0;

if ($curso_id <= 0) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["error" => "El ID de curso proporcionado no es valido"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id_curso, id_carrera, nombre_curso, codigo_curso, descripcion_curso, salon, edificio, total_horas, dias_atencion, hora_inicio, hora_fin, cupo_total, ruta_imagen FROM cursos WHERE id_curso = ?");
    $stmt->execute([$curso_id]);
    $curso = $stmt->fetchAll();

    
    foreach ($curso as &$c) {
        $c['id_curso'] = (int)$c['id_curso'];
    }

    
    echo json_encode($curso);

} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Error al obtener el detalle del curso: " . $e->getMessage()]);
}
?>