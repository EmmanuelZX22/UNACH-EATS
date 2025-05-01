<?php
session_start();
include '../Modelo/conexionPDO.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cafe = $_POST['id_cafe'];
    $nombre_cafe = $_POST['nombre_cafe'];
    $id_facu = $_POST['facultad'];
    $descripcion = $_POST['descripcion'];

    try {
        $stmt = $conn->prepare("
            UPDATE cafeteria 
            SET nombre_cafe = :nombre_cafe, id_facu = :id_facu, descripcion = :descripcion
            WHERE id_cafe = :id_cafe
        ");

        $stmt->bindParam(':nombre_cafe', $nombre_cafe);
        $stmt->bindParam(':id_facu', $id_facu);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':id_cafe', $id_cafe);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Información de cafetería actualizada'); window.location.href='../Vista/Cafeteria/perfil.php';</script>";
        } else {
            echo "<script>alert('❌ No se pudo actualizar'); window.history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>
