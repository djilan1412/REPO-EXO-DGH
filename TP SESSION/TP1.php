<?php
session_start();

if (isset($_POST['nom_utilisateur'])) {
    $_SESSION['nom_utilisateur'] = $_POST['nom_utilisateur'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<?php
if (!isset($_SESSION['nom_utilisateur'])) {
    ?>
    <h1>Login</h1>
    <form action="" method="post">
        <label>Username :</label>
        <input type="text" name="nom_utilisateur">
        <br><br>
        <input type="submit" value="Valider">
    </form>
    <?php
}
else {
    ?>
    <h1>Bonjour <?php echo $_SESSION['nom_utilisateur']; ?></h1>
    <?php
}
?>

</body>
</html>