<?php
session_start();
require_once __DIR__ . "./../vendor/autoload.php";

use Src\Usuario;

$usuarios = Usuario::read();
if(isset($_POST['borrar'])){
    $id = $_POST['id'];
    Usuario::delete($id);
    $_SESSION['mensaje']="Usuario Eliminado";
    header("Location:{$_SERVER['PHP_SELF']}");
    die();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CDN FONTAWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CDN SeetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Portal</title>
</head>

<body style="background-color:cyan">
    <h5 class="text-center my-4">Listado de usuarios</h5>
    <div class="container">
        <div class="float-end mb-2">
            <div>
                <a href="crear.php" class="btn btn-success">
                    <i class="fas fa-add"> Nuevo Usuario</i>
                </a>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr class="text-center">
                    <th scope="col">ID</th>
                    <th scope="col">NOMBRE</th>
                    <th scope="col">APELLIDOS</th>
                    <th scope="col">EMAIL</th>
                    <th scope="col">ES ADMIN</th>
                    <th scope="col">SUELDO</th>
                    <th scope="col">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($usuarios as $item) {
                    echo <<<TXT
            <tr class="text-center">
            <th scope="row">$item->id</th>
            <td>$item->nombre</td>
            <td>$item->apellidos</td>
            <td>$item->email</td>
            <td>$item->is_admin</td>
            <td>$item->sueldo</td>
            <td>
                <form name="b" method="POST" action="{$_SERVER['PHP_SELF']}">
                    <input type="hidden" name="id" value="{$item->id}">
                    <button class="btn btn-danger" type="submit" name="borrar">
                        <i class="fas fa-trash"></i>
                    </button>
                    <a href="editar.php?id={$item->id}" class="btn btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                </form>
            </td>
            </tr>
            TXT;
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        <?php 
            if(isset($_SESSION['mensaje'])){
                echo <<<TXT
                Swal.fire({
                    icon: 'success',
                    title: '{$_SESSION['mensaje']}',
                    showConfirmButton: false,
                    timer: 1500
                  })
                TXT;
                unset($_SESSION['mensaje']);
            }
        ?>
    </script>
</body>

</html>