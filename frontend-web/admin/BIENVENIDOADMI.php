<?php
require __DIR__.'/../../Backend/auth/controller/admin.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido Admin</title>
    <link rel="shortcut icon" href="img/c.png" type="image/x-icon" />
    <link rel="stylesheet" href="css/styl.css?v=<?php echo (rand()); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <header>
        <h1><b>¡Bienvenido a SETS!</b></h1>
        <h2>Gracias por registrarte con Nosotros.</h2>
    </header>
    <main>
        <img src="img/ajustes.png" alt="Bienvenida" style="width: 30%;">
       <br>
       <p><b>Registro Realizado Correctamente ! </b></p>
       <br>
        <p><b>Estamos emocionados de tenerte con nosotros ADMIN <br>
        Ahora Continua e Inicia en este nuevo Mundo:</b></p>
        
        <a href="inicioprincipal.php" class="btn btn-success btn-lg">Iniciar</a>
    </main>
</body>
</html>