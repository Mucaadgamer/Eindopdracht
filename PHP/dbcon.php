<?php  

    // Database connection with PDO where you can easly change from database you can use sql or other databases
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'emplyeedatabase';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Verbinding mislukt: " . $e->getMessage());
    }

?>