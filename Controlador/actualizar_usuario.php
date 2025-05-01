<?php
session_start();
include '../Modelo/conexionPDO.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_SESSION['id_usuario'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $carrera = $_POST['carrera'];
    $facultad = $_POST['facultad'];

    try {
        $stmt = $conn->prepare("
            UPDATE usuarios 
            SET nombre = :nombre, correo = :correo, telefono = :telefono, carrera = :carrera, id_facu = :facultad 
            WHERE id_usuario = :id_usuario
        ");

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':carrera', $carrera);
        $stmt->bindParam(':facultad', $facultad);
        $stmt->bindParam(':id_usuario', $id_usuario);

        if ($stmt->execute()) {
            // Actualizar la sesión también
            $_SESSION['nombre'] = $nombre;
            $_SESSION['correo'] = $correo;
            $_SESSION['telefono'] = $telefono;
            $_SESSION['carrera'] = $carrera;
            $_SESSION['id_facu'] = $facultad;

            // Opcional: obtener nombre_facu de nuevo
            $facu_stmt = $conn->prepare("SELECT nombre_facu FROM facultad WHERE id_facu = :id");
            $facu_stmt->bindParam(':id', $facultad);
            $facu_stmt->execute();
            $_SESSION['nombre_facu'] = $facu_stmt->fetchColumn();

            echo "<script>alert('✅ Información actualizada'); window.location.href='../Vista/Usuario/usuario.php';</script>";
        } else {
            echo "<script>alert('❌ Error al actualizar'); window.history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>
