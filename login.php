<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/login.css">
    <!-- Librería para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="funciones/loguear_usuario.js"></script>
    <title>Login</title>
</head>

<body>
    <header class="encabezado">
        <div class="logo">PymeCode</div>
    </header>

    <div class="contenedor">
        <div class="seccion-principal">
            <div class="contenido-principal">
                <h1 class="titulo-principal">Impulsa tu Negocio: <span>Organiza y Crece</span></h1>
                <p class="texto-principal">Optimiza la gestión de clientes y facturas, únete a cientos de empresas que
                    mejoran su eficiencia cada día y lleva el control total de tu negocio con soluciones pensadas para
                    pequeñas y medianas empresas.</p>
                <div class="contenedor-cta">
                    <a href="registrarEmpresa.php" class="boton-cta">¡Registra tu empresa!</a>
                </div>
            </div>
        </div>

        <div class="seccion-autenticacion">
            <div class="tarjeta-inicio-sesion">
                <h2>Bienvenido de nuevo</h2>
                <form id="formulario-login" onsubmit="Loguear_usuario(event)">
                    <div class="campo-formulario">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" placeholder="Ingrese su correo" name="correo_usuario" required>
                    </div>
                    <div class="campo-formulario">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" placeholder="Ingrese su contraseña" name="contraseña_usuario"
                            required>
                    </div>
                    <button type="submit" class="boton-enviar">Iniciar Sesión</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="pie-pagina">
        <p>&copy; <?php echo date('Y'); ?> PymeCode - Todos los derechos reservados</p>
    </footer>
</body>

</html>