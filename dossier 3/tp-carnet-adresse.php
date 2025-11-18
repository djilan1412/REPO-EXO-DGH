<?php 
$contacts = ["Alice Dupont", "John Doe", "Jean Martin", ];
 
$existants = file("contact.txt", FILE_IGNORE_NEW_LINES);
 
foreach ($contacts as $c) {
    if (!in_array($c, $existants)) {
        $existants[] = $c;
    }
}
 
file_put_contents("contact.txt", implode("<br>", $existants));
 
echo " Nouveau contact ajouté. <br>";
$contacts_fini = file_get_contents("contact.txt");
echo "<br> Contenu fichier contact.txt :<br>" . $contacts_fini;
?>