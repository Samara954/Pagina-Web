<?php
// Verificar si se ha enviado el formulario para agregar un producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_producto'])) {
    // Conectar a la base de datos
    $mysqli = new mysqli("192.168.1.3", "root", "123", "ropa", 3306);
    
    // Verificar la conexión
    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);
    }
    
    // Obtener los datos del formulario
    $nombre_producto = $_POST["nombre_producto"];
    $precio = $_POST["precio"];
    $descripcion_producto = $_POST["descripcion_producto"];
    $marca_id = $_POST["marca_id"];

    // Clave y IV de cifrado
    $clave = "test-key";
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CBC'));
    
    // Cifrado de la descripción del producto
    $descripcion_cifrada = openssl_encrypt($descripcion_producto, 'AES-128-CBC', $clave, 0, $iv);
    $descripcion_cifrada = base64_encode($descripcion_cifrada . '::' . $iv); // Guardar IV junto con el texto cifrado
    
    // Preparar la consulta SQL para insertar los datos en la tabla Productos
    $consulta_insertar_producto = "INSERT INTO productos (nombre, precio, descripcion, MarcaID) VALUES ('$nombre_producto', '$precio', '$descripcion_cifrada', '$marca_id')";
    
    // Ejecutar la consulta
    if ($mysqli->query($consulta_insertar_producto) === TRUE) {
        echo "<p>Producto ingresado correctamente.</p>";
    } else {
        echo "Error al ingresar el producto: " . $mysqli->error;
    }
    
    // Cerrar la conexión
    $mysqli->close();
}

// Verificar si se ha enviado el formulario para desencriptar las descripciones
$desencriptar_todo = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['desencriptar_todo'])) {
    $desencriptar_todo = true;
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
            <!-- Botón para desencriptar todas las descripciones -->
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <button type="submit" name="desencriptar_todo" class="btn btn-warning mb-3">Desencriptar Todo</button>
            </form>
            <table class="table w-50">
                <tr class="table-dark text-center">
                    <th scope="col">Nombre</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Marca</th>
                </tr>
                <?php
                // Consulta para obtener los productos de la base de datos
                $mysqli = new mysqli("192.168.1.3", "root", "123", "ropa", 3306);
                $consulta_productos = "SELECT * FROM productos";
                $resultado_productos = $mysqli->query($consulta_productos);
                
                // Clave para desencriptar
                $clave = "test-key";
                
                while ($fila_productos = $resultado_productos->fetch_assoc()) {

                    $descripcion_descifrada = $fila_productos['descripcion'];

                    if ($desencriptar_todo && strpos($fila_productos['descripcion'], '::') !== false) {

                            $descripcion_base64=base64_decode($fila_productos['descripcion']);
                        if($descripcion_base64 !== false){

                            // Desencriptar la descripción del producto
                            $descripcion_partes = explode('::', $descripcion_base64, 2);

                            if(count($descripcion_partes) == 2){

                                list($descripcion_cifrada, $iv) = $descripcion_partes;
                                $descripcion_descifrada = openssl_decrypt($descripcion_cifrada, 'AES-128-CBC', $clave, 0, $iv);

                            }

                        }
                        
                    } 
                    
                    echo "<tr class='text-center'>";
                    echo "<td>".$fila_productos['nombre']."</td>";
                    echo "<td>".$fila_productos['precio']."</td>";
                    echo "<td>".$descripcion_descifrada."</td>";
                    echo "<td>".$fila_productos['MarcaID']."</td>";
                    echo "</tr>";
                }
                
                // Cerrar la conexión
                $mysqli->close();
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
                <button type="submit" name="agregar_producto" class="btn btn-primary">Agregar Producto</button>
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
