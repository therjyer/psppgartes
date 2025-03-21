<?php
//conex�o ao banco
    //$conexao = mysql_connect("localhost","psppgartes","u7HQ9j8KjI"); php5
    //$conexao = mysqli_connect('localhost','u613711144_psppgartes',':2!2g6G#','u613711144_psppgartes');
    //mysql_select_db("st_psppgartes");  php5 
    
    //novo php7
    //$conexao = mysqli_connect('localhost','u613711144_psppgartes',':2!2g6G#','u613711144_psppgartes', '3306');
$conexao = mysqli_connect('localhost','psppgartes','1RNYK]lTMJapb24s','st_psppgartes', '3306');
    
    //mysqli_select_db($_SG['link'], "st_psppgartes");
    // mysqli_select_db($_SG['link'], "u613711144_psppgartes"); //4gestor
    mysqli_select_db($_SG['link'], "st_psppgartes"); //ufpa
?>