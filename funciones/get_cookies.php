<?php
function get_cookies() {
    // Verifica si hay cookies almacenadas
    if (isset($_COOKIE) && !empty($_COOKIE)) {
        echo "<table class='table'>
              <thead class='table-dark'>
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Plataforma</th>
                    <th>Descripción</th>
                    <th>Duración</th>
                    <th>Propietario</th>
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

            // Busca en el archivo CSV para obtener más información sobre la cookie
            $cookieInfo = buscarInformacionCookie($csvRows, $nombre);

            // Muestra la información adicional de la cookie
            if ($cookieInfo) {
                echo "<td>" . traducirTexto($cookieInfo['Tipo'], 'es') . "</td>";
                echo "<td>" . traducirTexto($cookieInfo['Plataforma'], 'es') . "</td>";
                echo "<td>" . traducirTexto($cookieInfo['Descripcion'], 'es') . "</td>";
                echo "<td>" . traducirTexto($cookieInfo['Duracion'], 'es') . "</td>";
                echo "<td>" . traducirTexto($cookieInfo['Propietario'], 'es') . "</td>";

                // Verifica si la categoría es diferente de "Functional" para incluir el botón de eliminar
                if ($cookieInfo['Tipo'] !== 'Functional') {
                    // Agrega un botón para eliminar la cookie
                    echo "<td>
                            <button class='btn btn-danger btn-delete-cookie' data-name='$nombre'>Eliminar</button>
                          </td>";
                } else {
                    // Si la categoría es "Functional", coloca una celda vacía en lugar del botón de eliminar
                    echo "<td>No se pueden eliminar las cookies funcionales o técnicas.</td>";
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
        //[1] => Platform // [2] => Category  // [3] => Cookie / Data Key name // [4] => Domain // [5] => Description  // [6] => Retention period //  [7] => Data Controller // [8] => User Privacy & GDPR Rights Portals 
        if (isset($data[3])) { // Verifica si el índice 3 está definido
            $cookieKey = $data[3]; // La columna 3 es 'Cookie / Data Key name'

            // Verifica si el nombre de la cookie es igual al valor del campo 'Cookie / Data Key name'
            if (strcasecmp($nombreCookie, $cookieKey) == 0) {
                return [
                    'Nombre' => $cookieKey,
                    'Tipo' => isset($data[2]) ? $data[2] : '',
                    'Plataforma' => isset($data[1]) ? $data[1] : '',
                    'Descripcion' => isset($data[5]) ? $data[5] : '',
                    'Duracion' => isset($data[6]) ? $data[6] : '',
                    'Propietario' => isset($data[4]) ? $data[4] : '',
                    // Agrega más campos según sea necesario
                ];
            }
        }
    }
    return null;
}

