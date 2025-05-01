<?php
        require("../modelo/conexionPDO.php");
        try{
            //verifico los datos del login
            $correo=htmlentities(addslashes($_POST['correo']));
            $clave = $_POST['clave'];
            $sql = "SELECT * FROM t_usuarios WHERE correo = :correo";
            //preparo la consulta SQL
            $resultado=$conn->prepare($sql);
            //ejecucion de la consulta
            $resultado->execute(array(":correo"=>$correo));
            
            $login=$resultado->fetch(PDO::FETCH_ASSOC);
            if(password_verify($clave, $login['clave'])) { 
                echo '<script>
                    Swal.fire({
                    icon: "success",
                    title:"Usuario aceptado",
                    text: "Registro correcto",
                    showConfirmButton: true,
                    confirmButtonText: "Aceptar"
                }) </script>'; 
                header("Location: ../indexUsuario.php"); 
            }else{
                //Cierra cadena de conexión
                $query = null;
                $conn = null;
                echo "Error de conexión";
                header("Location: ../../index.php?error=si"); 
            }
    
        }catch(Exception $e){
            die($e->getMessage());
        }
    ?>