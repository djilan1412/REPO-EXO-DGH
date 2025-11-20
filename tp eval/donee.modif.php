<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=jo;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if (!empty($_POST)) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $pays = strtoupper($_POST['pays']);
    $course = $_POST['course'];
    $temps = $_POST['temps'];

    $sql = "UPDATE `100` SET nom=?, pays=?, course=?, temps=? WHERE id=?";
    $req = $bdd->prepare($sql);
    $req->execute([$nom, $pays, $course, $temps, $id]);

    header('Location: eval.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $req = $bdd->prepare("SELECT * FROM `100` WHERE id = ?");
    $req->execute([$id]);
    $data = $req->fetch();

    if (!$data) die("ID introuvable.");
} else {
    die("Aucun ID fourni.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modifier</title>
</head>
<body>
    <h2>Modifier les informations</h2>
    
    <form method="POST">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <label>Nom :</label><br>
        <input type="text" name="nom" value="<?= $data['nom'] ?>" required><br><br>

        <label>Pays :</label><br>
        <input type="text" name="pays" value="<?= $data['pays'] ?>" maxlength="3" required><br><br>

        <label>Course :</label><br>
        <input type="text" name="course" value="<?= $data['course'] ?>" required><br><br>

        <label>Temps :</label><br>
        <input type="text" name="temps" value="<?= $data['temps'] ?>" required><br><br>

        <button type="submit">Enregistrer</button>
        <a href="eval.php">Annuler</a>
    </form>
</body>
</html>