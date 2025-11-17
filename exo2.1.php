<?php
 
$nombres = [10, 20, 30, 40, 50];
 
function calcMoy($nombres) {
    return array_sum($nombres) / count($nombres);
}
 
echo calcMoy($nombres);