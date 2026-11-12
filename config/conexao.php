<?php
$host = 'localhost';
$dbname = 'vitta_sports';
$dbusuario = 'root';
$dbsenha = 'root';

try{
    $pdo = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8mb4", $dbusuario, $dbsenha,[
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $erro){
    error_log($erro -> getMessage());
    exit('Não foi possível conectar ao banco de dados');
}
unset($host, $dbname, $dbusuario, $dbsenha);