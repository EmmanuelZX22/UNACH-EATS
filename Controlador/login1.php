<?php
session_start();
include '../Modelo/conexionPDO.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    try {
        // Buscar usuario con su facultad y rol
        $stmt = $conn->prepare("
            SELECT usuarios.*, facultad.nombre_facu, roles.nombre_rol
            FROM usuarios
            INNER JOIN facultad ON usuarios.id_facu = facultad.id_facu
            INNER JOIN roles ON usuarios.id_rol = roles.id_rol
            WHERE usuarios.correo = :correo
        ");
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($contrasena, $user['contrasena'])) {
                // Guardar los datos del usuario en la sesión
                foreach ($user as $key => $value) {
                    $_SESSION[$key] = $value;
                }

                // Si es encargado (rol 2), buscar su cafetería
                if ($user['id_rol'] == 2) {
                    $stmtCafe = $conn->prepare("
                        SELECT cafeteria.*, facultad.nombre_facu AS nombre_facu_cafe
                        FROM cafeteria
                        INNER JOIN facultad ON cafeteria.id_facu = facultad.id_facu
                        WHERE cafeteria.id_usuario_encargado = :id_usuario
                    ");
                    $stmtCafe->bindParam(':id_usuario', $user['id_usuario']);
                    $stmtCafe->execute();
                
                    if ($stmtCafe->rowCount() > 0) {
                        $cafe = $stmtCafe->fetch(PDO::FETCH_ASSOC);
                        $_SESSION['id_cafe'] = $cafe['id_cafe'];
                        $_SESSION['nombre_cafe'] = $cafe['nombre_cafe'];
                        $_SESSION['Descripcion'] = $cafe['Descripcion'];
                        $_SESSION['nombre_facu_cafe'] = $cafe['nombre_facu_cafe'];
                    } else {
                        $_SESSION['id_cafe'] = null;
                        $_SESSION['nombre_cafe'] = "Sin cafetería asignada";
                        $_SESSION['Descripcion'] = "Sin descripción disponible";
                        $_SESSION['nombre_facu_cafe'] = "Sin facultad asignada";
                    }
                }

                // Redirección según el rol
                switch ($user['id_rol']) {
                    case 1: // Usuario común
                        header("Location: ../Vista/Usuario/indexusuario.php");
                        break;
                    case 2: // Encargado de cafetería
                        header("Location: ../Vista/Cafeteria/MenuC.php");
                        break;
                    case 3: // Repartidor
                        header("Location: ../Vista/Delivery/Menu.php");
                        break;
                    case 4: // Administrador
                        header("Location: ../Vista/admin/menu.php");
                        break;
                    default:
                        echo "<script>alert('⚠️ Rol no reconocido'); window.history.back();</script>";
                        break;
                }

                exit();
            } else {
                echo "<script>alert('⚠️ Contraseña incorrecta'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('⚠️ El correo no está registrado'); window.history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "❌ Error en la base de datos: " . $e->getMessage();
    }
} else {
    echo "❌ Acceso no permitido.";
}
