<?php
session_start();
require_once '../Modelo/conexionPDO.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../Controlador/login1.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$conn->prepare("UPDATE pedido SET id_usuario_repartidor = NULL WHERE id_usuario_repartidor = :id_usuario")
     ->execute([':id_usuario' => $id_usuario]);

        // Eliminar registros relacionados
        $stmtCarrito = $conn->prepare("DELETE FROM carrito WHERE id_cafe = :id_cafe");
        $stmtCarrito->bindParam(':id_cafe', $id_cafe);
        $stmtCarrito->execute();

        $stmtReportes = $conn->prepare("DELETE FROM reportes WHERE id_cafe = :id_cafe");
        $stmtReportes->bindParam(':id_cafe', $id_cafe);
        $stmtReportes->execute();

        $stmtPedidos = $conn->prepare("DELETE FROM pedido WHERE id_cafe = :id_cafe");
        $stmtPedidos->bindParam(':id_cafe', $id_cafe);
        $stmtPedidos->execute();

        $stmtPromos = $conn->prepare("DELETE FROM promos WHERE id_cafe = :id_cafe");
        $stmtPromos->bindParam(':id_cafe', $id_cafe);
        $stmtPromos->execute();

        $stmtMenu = $conn->prepare("DELETE FROM menu WHERE id_cafe = :id_cafe");
        $stmtMenu->bindParam(':id_cafe', $id_cafe);
        $stmtMenu->execute();

        // Eliminar cafetería
        $stmtCafe = $conn->prepare("DELETE FROM cafeteria WHERE id_cafe = :id_cafe");
        $stmtCafe->bindParam(':id_cafe', $id_cafe);
        $stmtCafe->execute();

        // Eliminar usuario encargado
        $stmtUsuario = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = :id_usuario");
        $stmtUsuario->bindParam(':id_usuario', $id_usuario);
        $stmtUsuario->execute();

// Elimina el usuario (las restricciones ON DELETE CASCADE o SET NULL se encargan del resto)
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = :id_usuario");
$stmt->execute([':id_usuario' => $id_usuario]);

// Destruye la sesión
session_unset();
session_destroy();

// Redirige al login
header("Location: ../Vista/login.php");
exit();
