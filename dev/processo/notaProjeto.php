<?php
 include("../principal/seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
// include("dbconnect.inc.php");
 protegePagina(); // Chama a fun��o que protege a p�gina

        $idCandidato= $_GET['idCandidato'];
        $idProcesso= $_GET['idProcesso']; 
        $idProfessor= $_GET['idProfessor'];  
	$total_val = $_POST ["total_val"];


	
// Recebe os dados provenientes dos anexos enviados
 include("../principal/dbconnect.inc.php"); 
 
    $consulta = mysqli_query($_SG['link'], "SELECT c.avaliadorAnteprojeto1, c.avaliadorAnteprojeto2  FROM candidato c WHERE c.idCandidato = $idCandidato");
    $row = mysqli_fetch_array($consulta);
    $avaliadorAnteprojeto1 = $row['avaliadorAnteprojeto1'];  
    $avaliadorAnteprojeto2 = $row['avaliadorAnteprojeto2'];  
 
	
    if ($idProfessor==$avaliadorAnteprojeto1){
        $query = "UPDATE candidato SET notaAnteprojeto1='$total_val', estadoAnteprojeto1='1' WHERE idCandidato = '$idCandidato'";
        //$exec = mysql_query($query,$conexao);
        mysqli_query($conexao, $query); //php 7
    }else if ($idProfessor==$avaliadorAnteprojeto2){
        $query = "UPDATE candidato SET notaAnteprojeto2='$total_val', estadoAnteprojeto2='1' WHERE idCandidato = '$idCandidato'";
        // php5$exec = mysql_query($query,$conexao);        
        mysqli_query($conexao, $query); //php 7
    }
	
   // header ("Location: avProva1.php?msg=1&processo=$processo"); 
    echo "<meta http-equiv='refresh' content='0;URL=../principal/avaliarProjeto.php?msg=1&idProcesso=$idProcesso'>";
	
?> 