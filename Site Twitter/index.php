<?php

// Démarrage de la session
session_start();

// Connexion à la base de données
try {
    $database = new PDO('mysql:host=localhost;dbname=twitter', 'root', '');
} catch (PDOException $e) {
    die ('Site indisponible');
}

// Fonction pour ajouter un nouvel utilisateur à la base de données
function addUser($database, $username, $email, $password)
{
    // Vérifier si l'e-mail est déjà enregistré
    $query = "SELECT COUNT(*) FROM users WHERE email = :email";
    $statement = $database->prepare($query);
    $statement->execute(array(':email' => $email));
    $emailExists = $statement->fetchColumn();

    // Si l'e-mail n'est pas enregistré, procéder à l'ajout de l'utilisateur
    if (!$emailExists) {
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $statement = $database->prepare($query);
        $statement->execute(
            array(
                ':username' => $username,
                ':email' => $email,
                ':password' => $password
            )
        );

        // Connecter automatiquement l'utilisateur après l'inscription
        authenticateUser($database, $email, $password);
    } else {
        // Si l'e-mail est déjà enregistré, définir un message d'erreur ou gérer en conséquence
        // Pour l'instant, définissons une variable de session pour indiquer l'erreur
        $_SESSION['registration_error'] = "<p class='error'>This email is already registered.</p>";
    }
}

// Fonction pour vérifier si un utilisateur est connecté
function isLoggedIn()
{
    return isset ($_SESSION['user_id']);
}

// Fonction pour authentifier l'utilisateur
function authenticateUser($database, $email, $password)
{
    $query = "SELECT id FROM users WHERE email = :email AND password = :password";
    $statement = $database->prepare($query);
    $statement->execute(
        array(
            ':email' => $email,
            ':password' => $password
        )
    );
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    } else {
        return false;
    }
}

// Fonction pour ajouter un nouveau tweet à la base de données
function addTweet($database, $user_id, $content)
{
    $query = "INSERT INTO tweets (user_id, content, created_at) VALUES (:user_id, :content, NOW())";
    $statement = $database->prepare($query);
    $statement->execute(
        array(
            ':user_id' => $user_id,
            ':content' => $content
        )
    );
}

// Fonction pour supprimer un tweet de la base de données
function deleteTweet($database, $tweet_id, $user_id)
{
    $query = "DELETE FROM tweets WHERE id = :tweet_id AND user_id = :user_id";
    $statement = $database->prepare($query);
    $statement->execute(array(':tweet_id' => $tweet_id, ':user_id' => $user_id));
}

// Fonction pour obtenir les tweets avec les données utilisateur (y compris la fonctionnalité de recherche)
function getTweetsWithUserData($database, $search_query = null)
{
    $query = "SELECT tweets.*, users.username 
              FROM tweets 
              INNER JOIN users ON tweets.user_id = users.id";

    // Si une requête de recherche est fournie, modifier la requête pour inclure la fonctionnalité de recherche
    if ($search_query !== null) {
        $query .= " WHERE tweets.content LIKE :search_query";
    }

    $query .= " ORDER BY tweets.created_at DESC";

    $statement = $database->prepare($query);

    // Si une requête de recherche est fournie, lier le paramètre de recherche à l'instruction préparée
    if ($search_query !== null) {
        $search_param = '%' . $search_query . '%';
        $statement->bindParam(':search_query', $search_param, PDO::PARAM_STR);
    }

    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// Gérer la connexion de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset ($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (authenticateUser($database, $email, $password)) {
        // Redirection après une connexion réussie
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $loginError = "<p class='error'>Invalid email or password</p>";
    }
}

// Gérer la déconnexion de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset ($_POST['logout'])) {
    session_unset();
    session_destroy();
    // Redirection après déconnexion
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Gérer l'inscription de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset ($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Ajouter l'utilisateur à la base de données
    addUser($database, $username, $email, $password);
    // Redirection après l'inscription
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Gérer l'envoi de tweet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset ($_POST['tweet'])) {
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $content = $_POST['content'];
        addTweet($database, $user_id, $content);
        // Redirection après l'envoi de tweet
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Gérer la suppression de tweet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset ($_POST['delete'])) {
    $tweet_id = $_POST['tweet_id'];
    // Vérifier si l'utilisateur est connecté et possède le tweet avant de permettre la suppression
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        deleteTweet($database, $tweet_id, $user_id);
        // Redirection après suppression
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Obtenir tous les tweets avec les données utilisateur
$tweets = getTweetsWithUserData($database);
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Twitter Sesame Street</title>
    <link rel="icon" type="image/x-icon" href="Images/sesame-street-logo.ico">
</head>

<body>
    <img src="Images/sesame-street-sign.png" alt="Panneau de Sesame Street" class="intro-img">

    <div class="container">
        <?php if (!isLoggedIn()): ?>
            <!-- Formulaire de connexion -->
            <div class="form-container">
                <h2>Login</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="email" name="email" placeholder="Email" required><br>
                    <input type="password" name="password" placeholder="Password" required><br>
                    <input type="submit" name="login" value="Login">
                </form>
                <?php if (isset ($loginError)): ?>
                    <p>
                        <?php echo $loginError; ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Formulaire d'inscription -->
            <div class="form-container">
                <h2>Sign Up</h2>
                <?php if (isset ($_SESSION['registration_error'])): ?>
                    <?php echo $_SESSION['registration_error']; ?>
                    <?php unset($_SESSION['registration_error']); ?>
                    <!-- Effacer le message d'erreur après l'affichage -->
                <?php endif; ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="text" name="username" placeholder="Username" required><br>
                    <input type="email" name="email" placeholder="Email" required><br>
                    <input type="password" name="password" placeholder="Password" required><br>
                    <input type="submit" name="register" value="Sign up">
                </form>
            </div>

        <?php else: ?>
            <!-- Bouton de déconnexion -->
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="submit" name="logout" value="Logout">
            </form>

            <!-- Formulaire de Tweet -->
            <div class="form-container">
                <h2>New Tweet</h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <textarea name="content" rows="3" cols="50" required></textarea><br>
                    <input type="submit" name="tweet" value="Tweet">
                </form>
            </div>
        <?php endif; ?>
    </div>

    <!-- Affichage des Tweets -->
    <h2>Tweets</h2>

    <!-- Formulaire de recherche -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <input type="text" name="search_query" placeholder="Search tweets">
        <input type="submit" value="Search">
    </form>

    <ul>
        <?php
        // Vérifier si une requête de recherche est fournie
        if (isset ($_GET['search_query']) && !empty ($_GET['search_query'])) {
            // Récupérer les tweets en fonction de la requête de recherche
            $search_tweets = getTweetsWithUserData($database, $_GET['search_query']);

            // Afficher les résultats de la recherche
            foreach ($search_tweets as $tweet): ?>
                <li class="tweet-container">
                    <img src="Images/profile_pic_<?php echo $tweet['user_id']; ?>.png" class="profile-pic">
                    <div class="tweet-content">
                        <strong>
                            <?php echo $tweet['username']; ?> :
                        </strong>
                        <?php echo $tweet['content']; ?>
                        <p>
                            <?php echo $tweet['created_at']; ?>
                        </p>
                        <!-- Afficher le bouton de suppression pour les tweets de l'utilisateur -->
                        <?php if (isLoggedIn() && $_SESSION['user_id'] === $tweet['user_id']): ?>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display:inline;">
                                <input type="hidden" name="tweet_id" value="<?php echo $tweet['id']; ?>">
                                <input type="submit" name="delete" value="Delete">
                            </form>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach;
        } else {
            // Afficher tous les tweets si aucune requête de recherche n'est fournie
            foreach ($tweets as $tweet): ?>
                <li class="tweet-container">
                    <?php
                    $user_id = $tweet['user_id'];
                    $profile_pic = "Images/profile_pic_$user_id.png";
                    $default_pic = "Images/profile_pic_default.png";
                    ?>

                    <img src="<?php echo file_exists($profile_pic) ? $profile_pic : $default_pic; ?>" class="profile-pic">
                    <div class="tweet-content">
                        <strong>
                            <?php echo $tweet['username']; ?> :
                        </strong>
                        <?php echo $tweet['content']; ?>
                        <p class="tweet-date">
                            <?php echo date('Y-m-d H:i', strtotime($tweet['created_at'])); ?>
                        </p>
                        <!-- Afficher le bouton de suppression pour les tweets de l'utilisateur -->
                        <?php if (isLoggedIn() && $_SESSION['user_id'] === $tweet['user_id']): ?>
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display:inline;">
                                <input type="hidden" name="tweet_id" value="<?php echo $tweet['id']; ?>">
                                <input type="submit" name="delete" value="Delete">
                            </form>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach;
        }
        ?>
    </ul>

</body>

</html>