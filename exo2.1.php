<?php
 
$numbers = [10, 20, 30, 40, 50];
 
function calcmoy($numbers) {
    return array_sum($numbers) / count($numbers);
}
 
echo calcmoy($numbers);