<?php
header("Content-Type: application/json");

// pour se connecter à la BDD //
require_once 'config.php';
$db = connectDatabase();

// on vérifie la requête reçue //
$request_method = $_SERVER["REQUEST_METHOD"];

// si method === "GET" //
if ($request_method == 'GET') {
    getArticles($db);
} else {
    http_response_code(404);
    echo json_encode(["message" => "Route not found"]);
}

// renvoie la liste d'articles //
function getArticles($db)
{
    // la requête SQL //
    $query = "SELECT * FROM articles";
    $result = $db->query($query);

    // la liste contenant les articles //
    $articles = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $articles[] = $row;
    }

    // formatage en JSON puis fermeture de la connexion à la DB //
    echo json_encode($articles);
    $db->close();
}
