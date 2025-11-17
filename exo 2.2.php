 <?php
$texte = "HOLA HOLA !";
 
function my_strrevrs($texte) {
    $inverse = '';
    for ($i = strlen($texte) - 1; $i >= 0; $i--) {
        $inverse .= $texte[$i];
    }
    return $inverse;
}
echo my_strrevrs($texte);