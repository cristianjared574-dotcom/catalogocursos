<?php
$enServidor = isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'somos4a.org') !== false;

$host = $enServidor ? "localhost" : "157.164.172.39"; 
$db="u845581198_equipo12345";
$user="u845581198_equipo5";
$pass="Cshj1234";

try{
    $pdo=new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4",$user,$pass);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);
}catch(PDOException $e){
    header("Content-Type:application/json",true,500);
    echo json_encode([
        "error"=>"Error critico de conexión al servidor",
        "detalle"=>$e->getMessage()
    ]);
    exit;
}
?>