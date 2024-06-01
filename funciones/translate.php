<?php

// Llamada a la API de DeepL para traducir texto
function traducirTexto($texto, $idiomaDestino) {
    $apiKey = 'API_KEY_DEEPL'; // Reemplaza con tu clave de API de DeepL
    $url = 'https://api-free.deepl.com/v2/translate';
    
    $textoTraducido = '';

    $postData = [
        'text' => $texto,
        'target_lang' => $idiomaDestino,
        'auth_key' => $apiKey,
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($postData),
        ],
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response !== false) {
        $responseData = json_decode($response, true);
        if (isset($responseData['translations'][0]['text'])) {
            $textoTraducido = $responseData['translations'][0]['text'];
        }
    }

    return $textoTraducido;
}
