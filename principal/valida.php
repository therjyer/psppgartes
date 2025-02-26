<?php
declare(strict_types=1);
error_reporting(-1); // maximum errors
ini_set('display_errors', '1'); // show on screen

// Inclui o arquivo com o sistema de seguran�a
//set_include_path ( '/psppgartes' );
include("seguranca.php");

//mysqli_query($_SG['link'], "CALL deleteContents()"); php5
mysqli_query($_SG['link'], "CALL deleteContents()");

// Verifica se um formul�rio foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Salva duas vari�veis com o que foi digitado no formul�rio
    // Detalhe: faz uma verifica��o com isset() pra saber se o campo foi preenchido
    $usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
    $senha = (isset($_POST['senha'])) ? $_POST['senha'] : '';
    //	$usuario = "rafamesquita89@gmail.com";
    //	$senha = "123456";
    // Utiliza uma fun��o criada no seguranca.php pra validar os dados digitados
    if (validaUsuario($usuario, $senha) == true) {
        header('Location: listarProcessos.php');
        exit;
    } else {
        header("Location: ../index.php?msg=1");
        exit;
    }
}
?>
