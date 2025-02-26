<?php

include("../principal/seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
protegePagina(); // Chama a fun��o que protege a p�gina
// connect to the database
//arquivos que configura a conexao com o banco
include("../principal/dbconnect.inc.php");

$idProcesso = $_GET['idProcesso'];
$id = $_GET['id'];

$estado = 0;
// delete the entry
//$result = mysqli_query($_SG['link'], "UPDATE candidato SET estado='$estado' WHERE idCandidato='$id' AND processo='$idProcesso'")         or die(mysql_error());
$query = "UPDATE candidato SET estado='$estado' WHERE idCandidato='$id' AND processo='$idProcesso'";
try {
    mysqli_query($conexao, $query);
} catch (Exception $exc) {
    
    echo "A Exclusão falhou ";
    echo $exc->getTraceAsString();
}


// redirect back to the view page
//header ("Location: candidatos.php?msg=3&processo=$processo");
//echo "<meta http-equiv='refresh' content='0;URL=Location:../principal/candidatosMestrado.php?msg=3&idprocesso=$idProcesso'>";
echo "<meta http-equiv='refresh' content='0;URL=../principal/candidatosMestrado.php?msg=1&idProcesso=$idProcesso'>";
