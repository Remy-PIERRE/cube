<?php
// création nom du fichier .sqlite //
define('DB_FILE', 'database.sqlite');

function connectDatabase()
{
    // on vérifie que le fichier existe //
    if (!file_exists(DB_FILE)) {
        die("Le fichier de la base de données n'existe pas.");
    }

    // instance de la BDD //
    $db = new SQLite3(DB_FILE);

    return $db;
}
