<?php
 include("../principal/seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
// include("dbconnect.inc.php");
 protegePagina(); // Chama a fun��o que protege a p�gina

        $idCandidato= $_GET['idCandidato'];
        $idProcesso= $_GET['idProcesso'];
         $idTipo= $_GET['idTipo'];
     //   $idProfessor= $_GET['idProfessor'];  
	$pontuacaoTotal = $_POST ["total_val"];
        



	
// Recebe os dados provenientes dos anexos enviados
 include("../principal/dbconnect.inc.php"); 
 
$query = "UPDATE candidato SET pontuacaoCurriculo='$pontuacaoTotal', estadoCurriculo=1 WHERE idCandidato = '$idCandidato' AND estado=1";
// php5$exec = mysql_query($query,$conexao);        
        mysqli_query($conexao, $query); //php 7

	
   // header ("Location: avProva1.php?msg=1&processo=$processo"); 
if ($idTipo == 1 )
    {
    echo "<meta http-equiv='refresh' content='0;URL=../principal/avaliarCurriculo.php?msg=1&idProcesso=$idProcesso'>";
    }
    else
    {
        echo "<meta http-equiv='refresh' content='0;URL=../principal/avaliarCurriculo.php?msg=1&idProcesso=$idProcesso'>";
    }
    
	
?> 