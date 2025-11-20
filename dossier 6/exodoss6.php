<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=jo;charset=utf8', 'root', '');
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

$whitelist = ['nom', 'pays', 'course', 'temps'];
$col = 'temps';

if (isset($_GET['col']) && in_array($_GET['col'], $whitelist)) {
    $col = $_GET['col'];
}

$ordre = 'ASC';

if (isset($_GET['dir']) && $_GET['dir'] === 'DESC') {
    $ordre = 'DESC';
}

$sql = "SELECT * FROM jo.`100` ORDER BY `$col` $ordre";

$sth = $dbh->prepare($sql);
$sth->execute();

$data = $sth->fetchAll(PDO::FETCH_ASSOC);
$ordre_inverse = ($ordre === 'ASC') ? 'DESC' : 'ASC';
$fleche = ($ordre === 'ASC') ? '↑' : '↓';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Classement JO</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <table>
        <thead>
            <tr>
                
                <th>
                    <a href="?col=nom&dir=<?php echo $ordre_inverse; ?>">Nom</a>
                    <?php if ($col === 'nom') echo "<span class='rouge'>$fleche</span>"; ?>
                </th>

                <th>
                    <a href="?col=pays&dir=<?php echo $ordre_inverse; ?>">Pays</a>
                    <?php if ($col === 'pays') echo "<span class='rouge'>$fleche</span>"; ?>
                </th>

                <th>
                    <a href="?col=course&dir=<?php echo $ordre_inverse; ?>">Course</a>
                    <?php if ($col === 'course') echo "<span class='rouge'>$fleche</span>"; ?>
                </th>

                <th>
                    <a href="?col=temps&dir=<?php echo $ordre_inverse; ?>">Temps</a>
                    <?php if ($col === 'temps') echo "<span class='rouge'>$fleche</span>"; ?>
                </th>

            </tr>
        </thead>

        <tbody>
            <?php foreach ($data as $ligne) { ?>
                <tr>
                    <td><?php echo $ligne["nom"]; ?></td>
                    <td><?php echo $ligne["pays"]; ?></td>
                    <td><?php echo $ligne["course"]; ?></td>
                    <td><?php echo $ligne["temps"]; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>