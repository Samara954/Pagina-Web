<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $mysqli = new mysqli("localhost", "root", "123", "ropa", 3306);
    
    // Verificar la conexión
    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);
    }
    
    // Obtener los datos del formulario
    $nombre_producto = $_POST["nombre_producto"];
    $precio = $_POST["precio"];
    $descripcion_producto = $_POST["descripcion_producto"];
    $marca_id = $_POST["marca_id"];
    
    // Preparar la consulta SQL para insertar los datos en la tabla Productos
    $consulta_insertar_producto = "INSERT INTO productos (nombre, precio, descripcion, MarcaID) VALUES ('$nombre_producto', '$precio', '$descripcion_producto', '$marca_id')";
    
    // Ejecutar la consulta
    if ($mysqli->query($consulta_insertar_producto) === TRUE) {
        echo "<p>Producto ingresado correctamente.</p>";
    } else {
        echo "Error al ingresar el producto: " . $mysqli->error;
    }
    
    // Cerrar la conexión
    $mysqli->close();
}
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!-- Bootstrap CSS v5.2.1 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>
            <div>
                <table class="table w-50">
                    <tr class="table-dark text-center">
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                    </tr>
                    <?php
                    // Consulta para obtener las marcas de la base de datos
                    $mysqli = new mysqli("localhost", "root", "123", "ropa", 3306);
                    $consulta_marcas = "SELECT * FROM marcas";
                    $resultado_marcas = $mysqli->query($consulta_marcas);
                    while ($fila_marca = $resultado_marcas->fetch_assoc()) {
                        echo "<tr class='text-center'>";
                        echo "<td>".$fila_marca['nombre']."</td>";
                        echo "<td>".$fila_marca['descripcion']."</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>

            <div>
                <table class="table w-50">
                    <tr class="table-dark text-center">
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Marca</th>
                    </tr>
                    <?php
                    // Consulta para obtener los productos de la base de datos
                    $mysqli = new mysqli("localhost", "root", "123", "ropa", 3306);
                    $consulta_productos = "SELECT * FROM productos";
                    $resultado_productos = $mysqli->query($consulta_productos);
                    while ($fila_productos = $resultado_productos->fetch_assoc()) {
                        echo "<tr class='text-center'>";
                        echo "<td>".$fila_productos['nombre']."</td>";
                        echo "<td>".$fila_productos['precio']."</td>";
                        echo "<td>".$fila_productos['descripcion']."</td>";
                        echo "<td>".$fila_productos['MarcaID']."</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            
            <!-- Formulario para ingresar nuevos productos -->
            <div class="container mt-4">
                <h2>Ingresar Nuevo Producto</h2>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="mb-3">
                        <label for="nombre_producto" class="form-label">Nombre del Producto:</label>
                        <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio:</label>
                        <input type="text" class="form-control" id="precio" name="precio" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_producto" class="form-label">Descripción del Producto:</label>
                        <input type="text" class="form-control" id="descripcion_producto" name="descripcion_producto" required>
                    </div>
                    <div class="mb-3">
                        <label for="marca_id" class="form-label">ID de la Marca:</label>
                        <input type="text" class="form-control" id="marca_id" name="marca_id" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar Producto</button>
                </form>
            </div>
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    </body>
</html>
