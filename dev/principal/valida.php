<?php
	// Inclui o arquivo com o sistema de seguran�a
	//set_include_path ( '/ppgartes' );
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
			// O usu�rio e a senha digitados foram validados, manda pra p�gina interna
                   // if ($_SESSION['usuarioTipo']==2){
			// header("Location: /ppgartes/novo/listarProcessos.php"); 2019
                    

                        header("Location: listarProcessos.php");
                   // }else header ("Location: index3.php");
		} else {

			// O usu�rio e/ou a senha s�o inv�lidos, manda de volta pro form de login
			// Para alterar o endere�o da p�gina de login, verifique o arquivo seguranca.php
                    header ("Location: ../index.php?msg=1");
                    
 			//expulsaVisitante();
		}
	}
?>
