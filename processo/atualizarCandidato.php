<?php

include("../principal/seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
// include("dbconnect.inc.php");
protegePagina(); // Chama a fun��o que protege a p�gina
?>

<?php

$id = $_GET["id"];
$nome = $_POST ["nome"];
$email = $_POST ["email"];
$cpf = $_POST ["cpf"];
$linhaPesquisa = $_POST ["linhaPesquisa"];
$areaAtuacao = $_POST ["areaAtuacao"];
$cotas = $_POST ["cotas"];

$idProcesso = $_GET["idProcesso"];
//   $consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
//  $processo = mysql_result($consulta, 0, 'processo'); 
// Recebe os dados provenientes dos anexos enviados
include("../principal/dbconnect.inc.php");

$query = "UPDATE candidato SET nome='$nome', email='$email', cpf='$cpf',cotas='$cotas', optLinhaPesquisa='$linhaPesquisa', optCampo='$areaAtuacao' WHERE idCandidato = '$id' AND processo='$idProcesso'";
// $exec = mysql_query($query, $conexao);  versãp php 5
mysqli_query($conexao, $query); //php 7

//header ("Location: candidatos.php?msg=2&processo=$processo");
echo "<meta http-equiv='refresh' content='0;URL=../principal/candidatosMestrado.php?idProcesso=$idProcesso&msg=2'>";
?>