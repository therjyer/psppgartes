<?php header("Content-type: text/html; charset=UTF-8"); ?>

<?php
	/*
	* restringir o acesso de certas p�ginas do sistema
	*/

	//  Configura��es do Script
	// ==============================
	$_SG['conectaServidor'] = true;    // Abre uma conex�o com o servidor MySQL?
	$_SG['abreSessao'] = true;         // Inicia a sess�o com um session_start()?

	$_SG['caseSensitive'] = false;     // Usar case-sensitive? Onde 'thiago' � diferente de 'THIAGO'

	$_SG['validaSempre'] = true;       // Deseja validar o usu�rio e a senha a cada carregamento de p�gina?
	// Evita que, ao mudar os dados do usu�rio no banco de dado o mesmo contiue logado.

	$_SG['servidor'] = 'localhost';    // Servidor MySQL
	//$_SG['usuario'] = 'psppgartes';          // Usu�rio MySQL php6
        $_SG['usuario'] = 'u613711144_psppgdev';          // Usu�rio MySQL
	//$_SG['senha'] = 'u7HQ9j8KjI';                // Senha MySQL pup5
        $_SG['senha'] = ':2!2g6G#';                // Senha MySQL
	$_SG['banco'] = 'u613711144_psppgdev';            // Banco de dados MySQL

	$_SG['paginaLogin'] = 'login.php'; // P�gina de login

	$_SG['tabela'] = 'usuarios';       // Nome da tabela onde os usu�rios s�o salvos
	// ==============================

	// ======================================
	//   ~ N�o edite a partir deste ponto ~
	// ======================================

	// Verifica se precisa fazer a conex�o com o MySQL
	if ($_SG['conectaServidor'] == true) {
		$_SG['link'] = mysqli_connect($_SG['servidor'], $_SG['usuario'], $_SG['senha'],'u613711144_psppgdev') or die("MySQL: N�o foi poss�vel conectar-se ao servidor [".$_SG['servidor']."].");
	//mysqli_select_db($_SG['banco'], $_SG['link']) or die("MySQL: N�o foi poss�vel conectar-se ao banco de dados [".$_SG['banco']."].");
                mysqli_select_db($_SG['link'],$_SG['banco']) or die("MySQL: N�o foi poss�vel conectar-se ao banco de dados [".$_SG['banco']."].");
	}

	// Verifica se precisa iniciar a sess�o
	if ($_SG['abreSessao'] == true) {
		session_start();
	}

	/**
	* Fun��o que valida um usu�rio e senha
	*
	* @param string $usuario - O usu�rio a ser validado
	* @param string $senha - A senha a ser validada
	*
	* @return bool - Se o usu�rio foi validado ou n�o (true/false)
	*/
	function validaUsuario($usuario, $senha) {
		global $_SG;

		$cS = ($_SG['caseSensitive']) ? 'BINARY' : '';

		// Usa a fun��o addslashes para escapar as aspas
		$nusuario = addslashes($usuario);
		$nsenha = addslashes($senha);

		// Monta uma consulta SQL (query) para procurar um usu�rio
		$sql = "SELECT `id`, `nome`,`tipo`,`estado`   FROM `".$_SG['tabela']."` WHERE ".$cS." `email` = '".$nusuario."' AND ".$cS." `senha` = password('".$nsenha."') LIMIT 1";
		//$query = mysql_query($sql); PHP5
                $query = mysqli_query($_SG['link'],$sql);
		//$resultado = mysql_fetch_assoc($query); PHP5
                $resultado = mysqli_fetch_assoc($query);
		// Verifica se encontrou algum registro

		if (empty($resultado)) {
			// Nenhum registro foi encontrado => o usu�rio � inv�lido
			return false;
		} else {
			// O registro foi encontrado => o usu�rio � valido

			// Definimos tres valores na sess�o com os dados do usu�rio
			$_SESSION['usuarioID'] = $resultado['id']; // Pega o valor da coluna 'id do 		registro encontrado no MySQL

			$_SESSION['usuarioNome'] = $resultado['nome']; // Pega o valor da coluna 'nome' do registro encontrado no MySQL
			
			$_SESSION['usuarioTipo'] = $resultado['tipo'];
			
			$_SESSION['usuarioEstado'] = $resultado['estado'];
		//	$_SESSION['usuarioLotacao'] = $resultado['codigo_lotacao'];

			// Verifica a op��o se sempre validar o login
			if ($_SG['validaSempre'] == true) {

				// Definimos dois valores na sess�o com os dados do login
				$_SESSION['usuarioLogin'] = $usuario;
				$_SESSION['usuarioSenha'] = $senha;
				
			}

			return true;
		}
		}

	/**
	* Fun��o que protege uma p�gina
	*/
	
		
	function protegePaginaAdm() {
		global $_SG;

		if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome']) OR $_SESSION['usuarioTipo'] != 1 OR $_SESSION['usuarioEstado'] != 1) {

		// N�o h� usu�rio logado, manda pra p�gina de login
		expulsaVisitante();
		} else if ((!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome']) OR $_SESSION['usuarioTipo'] != 1 OR $_SESSION['usuarioEstado'] != 1)) {

			// H� usu�rio logado, verifica se precisa validar o login novamente
			if ($_SG['validaSempre'] == true) {

				// Verifica se os dados salvos na sess�o batem com os dados do banco de dados
				if (!validaUsuario($_SESSION['usuarioLogin'], $_SESSION['usuarioSenha']) OR $_SESSION['usuarioTipo'] != 1 OR $_SESSION['usuarioEstado'] != 1) {
			
				// Os dados n�o batem, manda pra tela de login
				expulsaVisitante();
				}
			}
		}
	}
	
	function protegePagina() {
		global $_SG;

		if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome']) OR $_SESSION['usuarioEstado'] != 1) {

		// N�o h� usu�rio logado, manda pra p�gina de login
		expulsaVisitante();
		} else if ((!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome']))OR $_SESSION['usuarioEstado'] != 1) {

			// H� usu�rio logado, verifica se precisa validar o login novamente
			if ($_SG['validaSempre'] == true) {

				// Verifica se os dados salvos na sess�o batem com os dados do banco de dados
				if (!validaUsuario($_SESSION['usuarioLogin'], $_SESSION['usuarioSenha'])OR $_SESSION['usuarioEstado'] != 1) {
			
				// Os dados n�o batem, manda pra tela de login
				expulsaVisitante();
				}
			}
		}
	}
	
	
	
	

	/**
	* Fun��o para expulsar um visitante
	*/
	function expulsaVisitante() {
		global $_SG;

		// Remove as vari�veis da sess�o (caso elas existam)
		unset($_SESSION['usuarioID'], $_SESSION['usuarioNome'], $_SESSION['usuarioLogin'], $_SESSION['usuarioSenha'],$_SESSION['usuarioTipo'], $_SESSION['usuarioEstado']);

		// Manda pra tela de login
		header("Location: ".$_SG['paginaLogin']);
	}
	

	
?>