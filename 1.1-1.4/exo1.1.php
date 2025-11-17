<?php
//exo 1
function school($age) {
    if ($age < 3) {
        return "crèche";
    } elseif ($age < 6) {
        return "maternelle";
    } elseif ($age < 11) {
        return "primaire";
    } elseif ($age < 16) {
        return "collège";
    } elseif ($age < 18) {
        return "lycée";
    } else {
        return "non";
    }
}

echo school(2);


