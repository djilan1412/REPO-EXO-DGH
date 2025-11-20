<?php

try {
    $bdd = new PDO('mysql:host=localhost;dbname=jo;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur BDD : ' . $e->getMessage());
}

$msg = "";
if (!empty($_POST)) {
    $nom = $_POST['nom'];
    $pays = $_POST['pays'];
    $course = $_POST['course'];
    $temps = $_POST['temps'];

    if (strlen($pays) !== 3) {
        $msg = "Erreur : Le pays doit faire 3 lettres.";
    }
    elseif (!is_numeric($temps)) {
        $msg = "Erreur : Le temps doit être un nombre.";
    } 
    else {
        $pays = strtoupper($pays);
        
        $sql = "INSERT INTO `100` (nom, pays, course, temps) VALUES (?, ?, ?, ?)";
        $req = $bdd->prepare($sql);
        $req->execute([$nom, $pays, $course, $temps]);
        $msg = "Résultat ajouté !";
    }
}

$col = $_GET['col'] ?? 'temps';
$dir = $_GET['dir'] ?? 'ASC';
$inverse_dir = ($dir === 'ASC') ? 'DESC' : 'ASC';

$search = $_GET['search'] ?? '';
$where = "";
$params = [];
if ($search) {
    $where = "WHERE nom LIKE ? OR pays LIKE ?";
    $params = ["%$search%", "%$search%"];
}

$limit = 10;
$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$reqCount = $bdd->prepare("SELECT count(*) FROM `100` $where");
$reqCount->execute($params);
$total = $reqCount->fetchColumn();
$nbPages = ceil($total / $limit);

$sql = "SELECT *, RANK() OVER (PARTITION BY course ORDER BY temps ASC) as rang 
        FROM `100` 
        $where 
        ORDER BY `$col` $dir 
        LIMIT $limit OFFSET $offset";

$req = $bdd->prepare($sql);
foreach ($params as $k => $v) {
    $req->bindValue($k + 1, $v);
}
$req->execute();
$resultats = $req->fetchAll();

$courses = $bdd->query("SELECT DISTINCT course FROM `100` ORDER BY course")->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JO PHP</title>
</head>
<body>

    <h2>Ajouter un résultat</h2>
    <?php if($msg) echo "<p style='color:red'>$msg</p>"; ?>

    <form method="POST">
        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="pays" placeholder="Pays (3 lettres)" maxlength="3" required>
        
        <select name="course" required>
            <option value="">-- Choisir course --</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?= $c ?>"><?= $c ?></option>
            <?php endforeach; ?>
        </select>

        <input type="text" name="temps" placeholder="Temps" required>
        <button type="submit">Ajouter</button>
    </form>

    <hr>

    <form method="GET">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher...">
        <button type="submit">Chercher</button>
        <?php if($search): ?><a href="index.php">Annuler</a><?php endif; ?>
    </form>

    <br>

    <table border="1" width="100%" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th># (Rang)</th>
                <th><a href="?col=nom&dir=<?= $inverse_dir ?>&search=<?= $search ?>">Nom</a></th>
                <th><a href="?col=pays&dir=<?= $inverse_dir ?>&search=<?= $search ?>">Pays</a></th>
                <th><a href="?col=course&dir=<?= $inverse_dir ?>&search=<?= $search ?>">Course</a></th>
                <th><a href="?col=temps&dir=<?= $inverse_dir ?>&search=<?= $search ?>">Temps</a></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultats as $row): ?>
            <tr>
                <td><b><?= $row['rang'] ?></b></td>
                <td><?= $row['nom'] ?></td>
                <td><?= $row['pays'] ?></td>
                <td><?= $row['course'] ?></td>
                <td><?= $row['temps'] ?></td>
                <td>
                    <a href="modifier.php?id=<?= $row['id'] ?>">Modifier</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p style="text-align: center;">
        Pages : 
        <?php for ($i = 1; $i <= $nbPages; $i++): ?>
            <a href="?page=<?= $i ?>&col=<?= $col ?>&dir=<?= $dir ?>&search=<?= $search ?>" 
               style="<?= $i == $page ? 'font-weight:bold; color:red;' : '' ?>">
               [<?= $i ?>]
            </a>
        <?php endfor; ?>
    </p>

</body>
</html>