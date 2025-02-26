<?php
	// Inclui o arquivo com o sistema de segurança
	//set_include_path ( '/ppgartes' );
	include("seguranca.php");
	
	//mysqli_query($_SG['link'], "CALL deleteContents()"); php5
        mysqli_query($_SG['link'], "CALL deleteContents()");
	
	// Verifica se um formulário foi enviado
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Salva duas variáveis com o que foi digitado no formulário
		// Detalhe: faz uma verificação com isset() pra saber se o campo foi preenchido
		$usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
		$senha = (isset($_POST['senha'])) ? $_POST['senha'] : '';
  	//	$usuario = "rafamesquita89@gmail.com";
	//	$senha = "123456";
		// Utiliza uma função criada no seguranca.php pra validar os dados digitados
		if (validaUsuario($usuario, $senha) == true) {
			// O usuário e a senha digitados foram validados, manda pra página interna
                   // if ($_SESSION['usuarioTipo']==2){
			// header("Location: /ppgartes/novo/listarProcessos.php"); 2019
                    

                        header("Location: listarProcessos.php");
                   // }else header ("Location: index3.php");
		} else {

			// O usuário e/ou a senha são inválidos, manda de volta pro form de login
			// Para alterar o endereço da página de login, verifique o arquivo seguranca.php
                    header ("Location: ../index.php?msg=1");
                    
 			//expulsaVisitante();
		}
	}
?>
