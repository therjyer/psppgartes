<?php
 include("../principal/seguranca.php");  // Inclui o arquivo com o sistema de segurança
// include("dbconnect.inc.php");
 protegePagina(); // Chama a função que protege a página

        $idCandidato= $_GET['idCandidato'];
        $idProcesso= $_GET['idProcesso']; 
        $idTipo= $_GET['idTipo'];        
	$total_val = $_POST ["total_val"];
Echo $total_val;
Echo $idCandidato;

	
// Recebe os dados provenientes dos anexos enviados
 include("../principal/dbconnect.inc.php");
	
$query = "UPDATE candidato SET notaEntrevista='$total_val', estadoEntrevista='1' WHERE idCandidato = '$idCandidato'";

// php5$exec = mysql_query($query,$conexao);        
        mysqli_query($conexao, $query); //php 7



    echo "<meta http-equiv='refresh' content='0;URL=../principal/avaliarEntrevista.php?msg=1&idProcesso=$idProcesso'>";   
	
?> 