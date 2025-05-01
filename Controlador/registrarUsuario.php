<?php
session_start();
require("../Modelo/conexionPDO.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $correo = $_POST['email'];
        $nombre = $_POST['nombre']; 
        $carrera = $_POST['carrera'];
        $telefono = $_POST['telefono'];
        $id_facu = $_POST['id_facu'];
        $id_rol = 1; // fijo
        $contrasena = $_POST['contrasena'];
        $passwordHash = password_hash($contrasena, PASSWORD_DEFAULT);

        
        $checkSql = "SELECT COUNT(*) FROM usuarios WHERE correo = :correo";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindParam(':correo', $correo);
        $checkStmt->execute();
        $correoExiste = $checkStmt->fetchColumn();

        if ($correoExiste > 0) {
            // El correo ya está registrado
            echo "<script>alert('El correo ya está registrado. Intenta con otro.'); window.history.back();</script>";
            exit();
        }

        $sql = "INSERT INTO usuarios (id_facu, id_rol, nombre, carrera, telefono, correo, contrasena) 
        VALUES (:id_facu, :id_rol, :nombre, :carrera, :telefono, :correo, :contrasena)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_facu', $id_facu, PDO::PARAM_INT);
        $stmt->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':carrera', $carrera);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':contrasena', $passwordHash); // ← cambia aquí también


        $stmt->execute();

        header("Location: ../Vista/login.php");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Acceso no autorizado.";
}
?>
