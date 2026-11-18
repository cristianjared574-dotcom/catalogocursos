<?php
header("Content-Type: application/json; charset=UTF-8");
require_once "config/conexionCursos.php";


$carrera_id = isset($_GET['carrera_id']) ? intval($_GET['carrera_id']) : 0;

try {
    if ($carrera_id > 0) {
        
        $stmt = $pdo->prepare("SELECT c.id_curso, c.id_carrera, c.nombre_curso, c.ruta_imagen, c.cupo_total, 
                              (SELECT COUNT(*) FROM inscripciones i WHERE i.id_curso = c.id_curso) AS total_registros 
                              FROM cursos c 
                              WHERE c.id_carrera = ?");
        $stmt->execute([$carrera_id]);
    } else {
        $stmt = $pdo->query("SELECT c.id_curso, c.id_carrera, c.nombre_curso, c.ruta_imagen, c.cupo_total, 
                             (SELECT COUNT(*) FROM inscripciones i WHERE i.id_curso = c.id_curso) AS total_registros 
                             FROM cursos c");
    }
    
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($registros as &$reg) {
        $reg['id_curso'] = (int)$reg['id_curso'];
        $reg['id_carrera'] = (int)$reg['id_carrera'];
        $reg['total_registros'] = (int)$reg['total_registros'];
        $reg['cupo_total'] = (string)$reg['cupo_total'];
    }

    echo json_encode($registros, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(["error" => "Error al obtener los registros: " . $e->getMessage()]);
}
?>