<?php
include 'recettes.php';

// Récupérer les données du formulaire
$titre = $_POST['titre'];
$description = $_POST['description'];
$difficulte = $_POST['difficulte'];
$temps_total = $_POST['temps_total'];
$régime_spécial = isset($_POST['régime_special']) ? $_POST['régime_special'] : '';
$temps_préparation = $_POST['temps_préparation'];
$temps_cuisson = $_POST['temps_cuisson'];
$nombre_personnes = $_POST['nombre_personnes'];
$ingredients = explode("\n", $_POST['ingredients']);
$instructions = explode("\n", $_POST['instructions']);
$ustensils = explode("\n", $_POST['ustensils']);

// Générer un ID pour la nouvelle recette
$new_id = max(array_keys($recettes)) + 1;

// Créer une nouvelle entrée de recette
$new_recipe = [
    'titre' => $titre,
    'description' => $description,
    'difficulté' => $difficulte,
    'temps_total' => $temps_total,
    'régime_spécial' => $régime_spécial,
    'temps_préparation' => $temps_préparation,
    'temps_cuisson' => $temps_cuisson,
    'nombre_personnes' => $nombre_personnes,
    'ingrédients' => $ingredients,
    'instructions' => $instructions,
    'ustensils' => $ustensils,
];

// Ajouter la nouvelle recette au tableau existant des recettes
$recettes[$new_id] = $new_recipe;

// Réécrire le tableau des recettes mis à jour dans le fichier
file_put_contents('recettes.php', '<?php $recettes = ' . var_export($recettes, true) . ';');

// Rediriger vers la page listant toutes les recettes
header('Location: index.php');
exit();



