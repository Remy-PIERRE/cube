<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// les token fonctionnent avec une clef secrete //
// elle ne doit pas être présente directement dans le code de l'app, il faut passer par un fichier .env par exemple pour s'assurer quelle reste secrete //
// ce fichier n'est pas posté sur git => à indiquer dans le .gitignore !!! //
$secretKey = 'your-secret-key';

// setup //
header('Content-Type: application/json');

// on récupère les données contenues dans la requête //
$data = json_decode(file_get_contents('php://input'), true);

// POST //
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // le back vérifie toujours qu'il a les donnée requises pour faire son travail //
    if (isset($data['userName'])) {
        // création du token //
        $payload = [
            // 'iss' => 'your-issuer',
            // 'aud' => 'your-audience',
            // 'iat' => time(),
            // 'nbf' => time(),
            'data' => [
                'userName' => $data['userName'],
            ]
        ];
        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        // la réponse est envoyée //
        echo json_encode([
            'status' => 'success',
            'token' => $jwt
        ]);
    }
}

// POST //
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // on récupère le token dans l'entête de la requête //
    $headers = apache_request_headers();

    // le back vérifie toujours qu'il a les donnée requises pour faire son travail //
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        list($jwt) = sscanf($authHeader, 'Bearer %s');

        if ($jwt) {
            try {
                // on essaie de décoder le token //
                $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));

                // pas d'erreur, l'utilisateur est valide //
                echo json_encode([
                    'status' => 'success',
                    'data' => $decoded->data
                ]);
            } catch (Exception $error) {
                // le token n'est pas valide //
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Token invalid'
                ]);
            }
        }
    } else {
        // pas de token présent dans le header //
        echo json_encode([
            'status' => 'error',
            'message' => 'Authorization header not found'
        ]);
    }
}

// si !POST ou !GET //
else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
