<?php
function get_cookies(){
    // Verifica si hay cookies almacenadas
    if (isset($_COOKIE) && !empty($_COOKIE)) {
        echo "<table class='table'>
              <thead class='table-dark'>
                <tr>
                    <th>Nombre</th>
                    <th>Valor</th>
                    <th>Categoría</th>
                    <th>Dominio</th>
                    <th>Descripción</th>
                    <th>Duración</th>
                    <th>Gestionado</th>
                    <th>Política de privacidad</th>
                    <th></th>
                </tr>
                </thead>";

        // Lee el archivo CSV desde la URL de GitHub
        $csvData = file_get_contents('https://raw.githubusercontent.com/jkwakman/Open-Cookie-Database/master/open-cookie-database.csv');
        $csvRows = str_getcsv($csvData, "\n");

        // Itera a través de todas las cookies
        foreach ($_COOKIE as $nombre => $valor) {
            echo "<tr>";
            echo "<td>$nombre</td>";
            echo "<td>$valor</td>";

            // Busca en el archivo CSV para obtener más información sobre la cookie
            $cookieInfo = buscarInformacionCookie($csvRows, $nombre);

            // Muestra la información adicional de la cookie
            if ($cookieInfo) {
                echo "<td>" . traducirTexto($cookieInfo['Category'], 'es') . "</td>";
                echo "<td>" . traducirTexto($cookieInfo['Domain'], 'es') . "</td>";
                echo "<td>" . traducirTexto($cookieInfo['Description'], 'es') . "</td>";
                echo "<td>" . traducirTexto($cookieInfo['Retention period'], 'es') . "</td>";
                echo "<td>" . traducirTexto($cookieInfo['Data Controller'], 'es') . "</td>";
                echo "<td><a href=" . traducirTexto($cookieInfo['User Privacy & GDPR Rights Portals'], 'es') . ">Leer</a></td>";

                // Verifica si la categoría es diferente de "Functional" para incluir el botón de eliminar
                if ($cookieInfo['Category'] !== 'Functional') {
                    // Agrega un botón para eliminar la cookie
                    echo "<td>
                            <button class='btn btn-danger btn-delete-cookie' data-name='$nombre'>Eliminar</button>
                          </td>";
                } else {
                    // Si la categoría es "Functional", coloca una celda vacía en lugar del botón de eliminar
                    echo "<td></td>";
                }
            } else {
                echo "<td colspan='6'>Información no disponible</td>";
            }

            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se detectaron cookies.";
    }
}

// Función para buscar información adicional de la cookie en el archivo CSV
function buscarInformacionCookie($csvRows, $nombreCookie) {
    foreach ($csvRows as $csvRow) {
        $data = str_getcsv($csvRow);
        if (isset($data[3])) { // Verifica si el índice 3 está definido
            $cookieKey = $data[3]; // La columna 3 es 'Cookie / Data Key name'

            // Verifica si el nombre de la cookie comienza con el prefijo del Cookie / Data Key name
            if (is_string($nombreCookie) && is_string($cookieKey) && strpos($nombreCookie, $cookieKey) === 0) {
                return [
                    'Cookie / Data Key name' => $cookieKey,
                    'Category' => isset($data[2]) ? $data[2] : '',
                    'Domain' => isset($data[4]) ? $data[4] : '',
                    'Description' => isset($data[5]) ? $data[5] : '',
                    'Retention period' => isset($data[6]) ? $data[6] : '', // Agrega el periodo de retención
                    'Data Controller' => isset($data[7]) ? $data[7] : '', // Agrega el periodo de retención
                    'User Privacy & GDPR Rights Portals' => isset($data[8]) ? $data[8] : '', // Agrega el periodo de retención
                    // Agrega más campos según sea necesario
                ];
            }
        }
    }
    return null;
}
?>
