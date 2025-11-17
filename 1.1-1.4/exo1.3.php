<?php 
function exo3($n){
    for ($i=1; $i <= $n; $i++) { 
        for ($j=1; $j <= $i; $j++) { 
            echo $i;
        }
        echo "<br>" ;
    }
}
exo3(5); 