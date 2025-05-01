<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error de Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8d7da;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .card {
            padding: 2rem;
            border-radius: 1rem;
            border: 1px solid #f5c6cb;
            background-color: #fff;
            text-align: center;
        }
        .btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card shadow">
        <h2 class="text-danger">¡Error!</h2>
        <p><?php echo htmlspecialchars($_GET['mensaje'] ?? 'Ocurrió un error inesperado.'); ?></p>
        <a href="../login.php" class="btn btn-danger">Volver al login</a>
    </div>
</body>
</html>
