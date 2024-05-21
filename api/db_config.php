<?php
// à utiliser pour initialiser la BDD //

require_once 'config.php';

// instance de la BDD //
$db = connectDatabase();

if ($db) {
    // SQL => création de la table articles //
    $query = 'CREATE TABLE IF NOT EXISTS articles (
                            id INTEGER PRIMARY KEY AUTOINCREMENT,
                            title TEXT NOT NULL,
                            author TEXT NOT NULL
                        )';

    // on exécute la requête //
    if ($db->exec($query)) {
        echo "Table 'articles' créée avec succès.";
    } else {
        echo "Erreur lors de la création de la table : " . $conn->lastErrorMsg();
    }

    // SQL => ajout des articles //
    $query = "INSERT INTO articles (title, author) VALUES
                            ('Château Margaux', 'Paul Pontallier'),
                            ('Domaine de la Romanée-Conti', 'Aubert de Villaine'),
                            ('Pétrus', 'Olivier Berrouet'),
                            ('Château de Yquem', 'Pierre Lurton'),
                            ('Opus One', 'Michael Silacci'),
                            ('Château Cheval Blanc', 'Pierre Lurton'),
                            ('Vega Sicilia Unico', 'Pablo Álvarez'),
                            ('Penfolds Grange', 'Peter Gago'),
                            ('Sassicaia', 'Sebastiano Rosa'),
                            ('Château Mouton Rothschild', 'Philippe Dhalluin')";

    // on exécute la requête //
    if ($db->exec($query)) {
        echo "Articles d'exemple insérés avec succès.";
    } else {
        echo "Erreur lors de l'insertion des articles : " . $conn->lastErrorMsg();
    }

    // fermeture de la connexion à la DB //
    $db->close();
}
