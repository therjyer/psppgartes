<?php
 include("../principal/seguranca.php");  // Inclui o arquivo com o sistema de seguran�a
// include("dbconnect.inc.php");
 protegePagina(); // Chama a fun��o que protege a p�gina

		//echo "1";
        $idCandidato= $_GET['idCandidato'];
        $idProcesso= $_GET['idProcesso']; 
        $idTipo= $_GET['idTipo'];        
		$pontuacaoTotal = $_POST ["total_val"];
		//echo "2";


	
// Recebe os dados provenientes dos anexos enviados
 include("../principal/dbconnect.inc.php");
	
$query = "UPDATE candidato SET notaEntrevista='$pontuacaoTotal', estadoEntrevista='1' WHERE idCandidato = '$idCandidato'";

//echo $query;

// php5$exec = mysql_query($query,$conexao);        
        mysqli_query($conexao, $query); //php 7



    echo "<meta http-equiv='refresh' content='0;URL=../principal/avaliarEntrevista.php?msg=1&idProcesso=$idProcesso'>";   
	
?> 