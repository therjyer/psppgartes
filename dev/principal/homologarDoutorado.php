<?php
	
 include("seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
// include("dbconnect.inc.php");
// protegePagina(); // Chama a fun��o que protege a p�gina

        $idCandidato = $_GET['idCandidato'];
        $idProcesso = $_GET['idProcesso'];  
        $estadoHomologacao = $_GET['estadoHomologacao'];
        $pg=$_GET['page'];

 include("dbconnect.inc.php"); 
	
$query = "UPDATE candidato SET estadoHomologacao='$estadoHomologacao' WHERE idCandidato = $idCandidato";

//antigo php5
//$exec = mysql_query($query,$conexao);
//novo php7
$exec = mysqli_query($conexao, $query);


//header ('Location: homologacao.php?processo=$processo&msg=1'); 
echo "<meta http-equiv='refresh' content='0;URL=homologacaoDoutorado.php?idProcesso=".$idProcesso."&msg=1&page=$pg'>";
 
	
?>

  