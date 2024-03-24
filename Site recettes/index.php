<?php
// tableau de recettes
include 'recettes.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercice Recettes</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Recettes de cuisine</h1>
    </header>
    <main>
        <h2>Liste des recettes</h2>
        <ul>
            <?php foreach ($recettes as $id => $recette): ?>
                <li>
                    <a href="detail.php?id=<?= $id ?>">
                        <h3>
                            <?= $recette['titre'] ?>
                        </h3>
                    </a>
                    <p>
                        <?= $recette['description'] ?>
                    </p>
                    <p>
                        <?= $recette['difficulté'] ?>
                    </p>
                    <p>
                        <?= $recette['temps_total'] ?>
                    </p>
                    <?php if (is_array($recette['régime_spécial'])): ?>
                        <?php foreach ($recette['régime_spécial'] as $regime): ?>
                            <div class="<?php echo $regime; ?>">
                                <?php echo $regime; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </li>
            <?php endforeach; ?>
        </ul>


        <!-- Formulaire d'ajout de recette -->
        <h2>Ajouter une recette</h2>
        <form action="ajouter_recette.php" method="post">
            <label for="titre">Titre:</label>
            <input type="text" id="titre" name="titre" required><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea><br>

            <label for="difficulte">Difficulté (note sur 5):</label>
            <select id="difficulte" name="difficulte" required>
                <option value="★☆☆☆☆">★☆☆☆☆</option>
                <option value="★★☆☆☆">★★☆☆☆</option>
                <option value="★★★☆☆">★★★☆☆</option>
                <option value="★★★★☆">★★★★☆</option>
                <option value="★★★★★">★★★★★</option>
            </select><br>

            <label for="temps_total">Temps total (en minutes):</label>
            <input type="number" id="temps_total" name="temps_total" min="0" required><br>

            <label for="régime_special">Régime spécial:</label>
            <input type="text" id="régime_special" name="régime_special"><br>

            <label for="temps_préparation">Temps de préparation (en minutes):</label>
            <input type="number" id="temps_préparation" name="temps_préparation" min="0" required><br>

            <label for="temps_cuisson">Temps de cuisson (en minutes):</label>
            <input type="number" id="temps_cuisson" name="temps_cuisson" min="0" required><br>

            <label for="nombre_personnes">Nombre de personnes:</label>
            <input type="number" id="nombre_personnes" name="nombre_personnes" min="1" required><br>

            <label for="ingredients">Ingrédients:</label>
            <textarea id="ingredients" name="ingredients" rows="4" cols="50" required></textarea><br>

            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" rows="4" cols="50" required></textarea><br>

            <label for="ustensils">Ustensiles:</label>
            <textarea id="ustensils" name="ustensils" rows="4" cols="50" required></textarea><br>

            <button type="submit">Ajouter la recette</button>
        </form>



    </main>
    <footer>
        <!-- Pied de page -->
    </footer>
</body>

</html>