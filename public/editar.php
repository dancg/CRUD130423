<?php 
session_start();
require_once __DIR__."./../vendor/autoload.php";
use Src\Usuario;
function mostrarErrores($nombre){
    if(isset($_SESSION[$nombre])){
        echo "<p class='text-danger italics'>{$_SESSION[$nombre]}</p>";
        unset($_SESSION[$nombre]);
    } 
}
if(!isset($_GET['id'])){
    header("Location:index.php");
    die();
}
$id = $_GET['id'];
$usuario = Usuario::readUsuario($id);
if(isset($_POST['editar'])){
    //Procesamos el formulario
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $is_admin = $_POST['is_admin'];
    $sueldo = trim($_POST['sueldo']);
    $errores = false;
    //Validamos los datos
    if(strlen($nombre) < 3){
        $errores = true;
        $_SESSION['errornombre']="***El campo nombre debe tener como mínimo 3 caracteres***";
    }
    if(strlen($apellidos) < 5){
        $errores = true;
        $_SESSION['errorapellidos']="***El campo apellidos debe tener como mínimo 5 caracteres***";
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errores = true;
        $_SESSION['erroremail']="***Email inválido***";
    }else {
        //Hay que comprobar que el email no exista, hay que hacer un método en la clase usuario
        if(Usuario::existeEmail($email, $id)){
            $errores = true;
            $_SESSION['erroremail']="***El email introducido ya existe, ponga otro***";
        }
    }
    if(!isset($_POST['is_admin'])){
        $errores = true;
        $_SESSION['erroris_admin']="***Debe seleccionar SI o NO***";
    }else {
        if($is_admin != "SI" && $is_admin !="NO"){
            $errores = true;
            $_SESSION['erroris_admin']="***Valor inválido***";
        }
    }
    if(is_numeric($sueldo)){
        if($sueldo < 0 || $sueldo >9999.99){
            $errores = true;
            $_SESSION['errorsueldo']="***El sueldo no puede ser negativo o mayor de 9999.99 euros***";
        }
    }else {
        $errores = true;
        $_SESSION['errorsueldo']="***Sueldo inválido***";
    }
    if($errores){
        header("Location:{$_SERVER['PHP_SELF']}?id=$id");
        die();
    }
    //Si hemos llegado hasta aquí no hay errores, podemos proceder a crear el usuario (metodo create)
    (new Usuario)->setNombre($nombre)
    ->setApellidos($apellidos)
    ->setEmail($email)
    ->setIs_admin($is_admin)
    ->setSueldo($sueldo)
   ->update($id);
    $_SESSION['mensaje']="Usuario Actualizado";
    header("Location:index.php");
    die();
    
} else {
    //Pintamos el formulario

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
    <title>Editar</title>
</head>

<body style="background-color:cyan">
</body>
<h5 class="text-center my-4">Editar Usuario</h5>
<div class="container">
    <form name="as" method="POST" action="<?php echo $_SERVER['PHP_SELF']."?id=$id"; ?>">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" 
            value="<?php echo $usuario->nombre; ?>" placeholder="Nombre...">
            <?php
                mostrarErrores('errornombre');
            ?>
        </div>
        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" 
            value="<?php echo $usuario->apellidos; ?>" placeholder="Apellidos...">
            <?php
                mostrarErrores('errorapellidos');
            ?>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
            value="<?php echo $usuario->email; ?>" placeholder="Correo electrónico...">
            <?php
                mostrarErrores('erroremail');
            ?>
        </div>
        <div class="mb-3">
            <label for="is_admin" class="form-label">ES ADMIN?</label>
            <div class="form-check">
            <input class="form-check-input" type="radio" name="is_admin" id="SI" value="SI"
            <?php if($usuario->is_admin =="SI") echo "checked"; ?>>
            <label class="form-check-label" for="SI">
                SI
            </label>
            </div>
            <div class="form-check">
            <input class="form-check-input" type="radio" name="is_admin" id="NO" value="NO"
            <?php if($usuario->is_admin =="NO") echo "checked"; ?>>
            <label class="form-check-label" for="NO">
                NO
            </label>
            </div>
            <?php
                mostrarErrores('erroris_admin');
            ?>
        </div>
        <div class="mb-3">
            <label for="sueldo" class="form-label">Sueldo(€)</label>
            <input type="number" step="0.01" class="form-control" id="sueldo" name="sueldo" 
            value="<?php echo $usuario->sueldo; ?>" placeholder="Sueldo...">
            <?php
                mostrarErrores('errorsueldo');
            ?>
        </div>
        <div class="text-center mt-4">
        <button type="submit" name="editar" class="btn btn-primary">Editar</button>
        <a href="index.php" class="btn btn-danger">
            <i class="fas fa-xmark"></i>Cancelar
        </a>
        </div>
    </form>
</div>

</html>
<?php } ?>