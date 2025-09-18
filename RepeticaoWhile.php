<?php
    
    if(!empty($_GET['cont'])&& !empty ($_GET['numero'])){
        $cont = $_GET['cont'];
        $numero = $_GET['numero'];
    while($cont < 10){
            echo nl2br ("O número é : $numero\n");
            $numero = $numero + 1;
            $cont = $cont + 1;
    }
}else{
    if(!empty($_GET['cont'])){
        echo ("Não foi setado numero"); 
    }else 
    echo ("Não foi setado cont");
}


?>