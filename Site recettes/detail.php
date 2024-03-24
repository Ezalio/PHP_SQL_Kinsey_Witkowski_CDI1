<?php
// Include the file containing the array of recipes
include 'recettes.php';

// Récupérer l'ID de la recette depuis l'URL
$id = $_GET['id'];

// Récupérer les détails de la recette
$recette = $recettes[$id];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $recette['titre'] ?>
    </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Recettes de cuisine</h1>
    </header>
    <main>
        <article>
            <h2>
                <?= $recette['titre'] ?>
            </h2>
            <p><strong>Description:</strong>
                <?= $recette['description'] ?>
            </p>
            <p><strong>Difficulté:</strong>
                <?= $recette['difficulté'] ?>
            </p>
            <p><strong>Temps total:</strong>
                <?= $recette['temps_total'] ?> minutes
            </p>
            <p><strong>Régime spécial:</strong>
                <?php
                if (is_array($recette['régime_spécial'])) {
                    echo implode(', ', $recette['régime_spécial']);
                } else {
                    echo 'Aucun';
                }
                ?>
            </p>
            <p><strong>Temps de préparation:</strong>
                <?= $recette['temps_préparation'] ?> minutes
            </p>
            <p><strong>Temps de cuisson:</strong>
                <?= $recette['temps_cuisson'] ?> minutes
            </p>
            <p><strong>Nombre de personnes:</strong>
                <?= $recette['nombre_personnes'] ?>
            </p>

            <h3>Ingrédients:</h3>
            <ul>
                <?php foreach ($recette['ingrédients'] as $ingredient): ?>
                    <li>
                        <?= $ingredient ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <h3>Instructions:</h3>
            <ol>
                <?php foreach ($recette['instructions'] as $instruction): ?>
                    <li>
                        <?= $instruction ?>
                    </li>
                <?php endforeach; ?>
            </ol>

            <h3>Ustensiles:</h3>
            <ul>
                <?php foreach ($recette['ustensils'] as $ustensil): ?>
                    <li>
                        <?= $ustensil ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </article>
    </main>
    <footer>
        <!-- Pied de page -->
    </footer>
</body>

</html>