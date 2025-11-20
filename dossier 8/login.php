<?php
session_start();
 
$hote = 'localhost';
$nom_bdd = 'user';
$utilisateur_db = 'root';
$mot_de_passe_db = '';
 
try {
    $dbh = new PDO("mysql:host=$hote;dbname=$nom_bdd", $utilisateur_db, $mot_de_passe_db);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
 
$message_succes = "";
$erreurs = [];
 
if (isset($_POST['inscription'])) {
   
    $nom_utilisateur = htmlspecialchars($_POST['username']);
    $mot_de_passe = $_POST['password'];
 
    if (empty($nom_utilisateur)) {
        $erreurs['inscription_username'] = "Le champ username de l'inscription est vide";
    }
    if (empty($mot_de_passe)) {
        $erreurs['inscription_password'] = "Le champ password de l'inscription est vide";
    }
 
    if (empty($erreurs)) {
        $requete = $dbh->prepare("SELECT id FROM user WHERE username = :username");
        $requete->execute(['username' => $nom_utilisateur]);
       
        if ($requete->fetch()) {
            $erreurs['inscription_username'] = "Le username est déjà présent en base";
        }
    }
 
    if (empty($erreurs)) {
        $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
       
        $sql = "INSERT INTO user (username, password) VALUES (:username, :password)";
        $requete = $dbh->prepare($sql);
        $requete->execute(['username' => $nom_utilisateur, 'password' => $hash]);
       
        $message_succes = "<b>Votre inscription est valide</b>";
    }
}
 
if (isset($_POST['connexion'])) {
   
    $nom_utilisateur = htmlspecialchars($_POST['username']);
    $mot_de_passe = $_POST['password'];
 
    if (empty($nom_utilisateur)) {
        $erreurs['login_username'] = "Le champ username de la connexion est vide";
    }
    if (empty($mot_de_passe)) {
        $erreurs['login_password'] = "Le champ password de la connexion est vide";
    }
 
    if (empty($erreurs)) {
        $requete = $dbh->prepare("SELECT * FROM user WHERE username = :username");
        $requete->execute(['username' => $nom_utilisateur]);
        $utilisateur_trouve = $requete->fetch(PDO::FETCH_ASSOC);
 
        if ($utilisateur_trouve) {
            if (password_verify($mot_de_passe, $utilisateur_trouve['password'])) {
                $_SESSION['username'] = $utilisateur_trouve['username'];
            } else {
                $erreurs['login_general'] = "Le mot de passe est invalide";
            }
        } else {
            $erreurs['login_general'] = "Le username n'existe pas dans la base de données";
        }
    }
}
?>
 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription et Connexion</title>
    <style>
        .erreur { color: red; font-size: 0.9em; display: block; }
        .succes { color: green; }
    </style>
</head>
<body>
 
    <?php if (isset($_SESSION['username'])): ?>
       
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
        <p>Vous êtes connecté.</p>
 
    <?php else: ?>
 
        <?php if ($message_succes) echo "<p class='succes'>$message_succes</p>"; ?>
 
        <h1>Inscription</h1>
        <form method="post" action="">
            <label>Username : </label>
            <input type="text" name="username">
            <?php if (isset($erreurs['inscription_username'])) echo "<span class='erreur'>" . $erreurs['inscription_username'] . "</span>"; ?>
            <br/>
 
            <label>Password : </label>
            <input type="password" name="password">
            <?php if (isset($erreurs['inscription_password'])) echo "<span class='erreur'>" . $erreurs['inscription_password'] . "</span>"; ?>
            <br/>
 
            <input type="submit" value="Valider" name="inscription">
        </form>
 
        <hr>
 
        <h1>Connection</h1>
        <form method="post" action="">
            <?php if (isset($erreurs['login_general'])) echo "<p class='erreur'>" . $erreurs['login_general'] . "</p>"; ?>
 
            <label>Username : </label>
            <input type="text" name="username">
            <?php if (isset($erreurs['login_username'])) echo "<span class='erreur'>" . $erreurs['login_username'] . "</span>"; ?>
            <br/>
 
            <label>Password : </label>
            <input type="password" name="password">
            <?php if (isset($erreurs['login_password'])) echo "<span class='erreur'>" . $erreurs['login_password'] . "</span>"; ?>
            <br/>
 
            <input type="submit" value="Valider" name="connexion">
        </form>
 
    <?php endif; ?>
 
</body>
</html>