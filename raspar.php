<?php
// Defina suas credenciais
$email = "seu_email@gmail.com";
$senha = "sua_senha";
$servidor_imap = "{imap.gmail.com:993/imap/ssl}INBOX";  // Para Gmail

// Conexão ao banco de dados
$servername = "localhost";
$username = "usuario_bd";
$password = "senha_bd";
$dbname = "nome_do_banco";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se há erro na conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Estabelece a conexão IMAP
$caixa_entrada = imap_open($servidor_imap, $email, $senha) or die('Não foi possível conectar ao servidor IMAP: ' . imap_last_error());

// Número total de e-mails na caixa de entrada
$num_emails = imap_num_msg($caixa_entrada);

// Exibe os últimos 5 e-mails e filtra os que contêm "jotform"
for ($i = $num_emails; $i > $num_emails - 5; $i--) {
    // Obtém o cabeçalho do e-mail
    $cabecalho = imap_headerinfo($caixa_entrada, $i);
    
    // Verifica se o assunto ou corpo contém "jotform"
    if (strpos(strtolower($cabecalho->subject), 'jotform') !== false || strpos(strtolower($cabecalho->fromaddress), 'jotform') !== false) {
        echo "E-mail contendo 'jotform' encontrado:<br>";
        echo "De: " . $cabecalho->fromaddress . "<br>";
        echo "Assunto: " . $cabecalho->subject . "<br>";
        echo "Data: " . $cabecalho->date . "<br>";
        
        // Obtém o corpo do e-mail
        $corpo = imap_fetchbody($caixa_entrada, $i, 1);
        echo "Corpo do e-mail: " . htmlspecialchars($corpo) . "<br><br>";
        
        // Aqui, você vai extrair os dados que você precisa do e-mail.
        // Este é um exemplo de inserção de dados no banco.
        
        // Você pode ter que extrair os valores a partir do conteúdo do e-mail usando expressões regulares ou outra lógica.
        
        // Exemplo de inserção (ajuste conforme necessário):
        $sql = "INSERT INTO tabela_candidatos (
            txtNome, txtNomeSocial, txtLinkCvLattes, txtNacionalidade, txtNaturalidade, 
            dtNascimento, txtNumRG, txtEmissorRg, txtCPF, txtVisto, dtInicioVigenciaVisto, 
            dtTerminoVigenciaVisto, txtEndereco, txtTelefone, txtCelular, txtEmail, 
            bolAtendimentoEspecial, txtEspecial, txtNomeEnsinoSuperior, txtSiglaEnsinoSuperior, 
            txtCurso, txtTitulo, dtInicioCurso, dtTerminoCurso, txtTituloProjeto, optCampo, 
            optLinhaPesquisa, txtOrientador1, bolVinculoEmpregaticio, txtNomeInstituicao, 
            optTipoProcesso, numInscricao) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        // Substitua os valores pelas variáveis que você vai extrair do e-mail.
        $stmt->bind_param("ssssssssssssssssssssssssssssssss", $txtNome, $txtNomeSocial, $txtLinkCvLattes, $txtNacionalidade, $txtNaturalidade, $dtNascimento, $txtNumRG, $txtEmissorRg, $txtCPF, $txtVisto, $dtInicioVigenciaVisto, $dtTerminoVigenciaVisto, $txtEndereco, $txtTelefone, $txtCelular, $txtEmail, $bolAtendimentoEspecial, $txtEspecial, $txtNomeEnsinoSuperior, $txtSiglaEnsinoSuperior, $txtCurso, $txtTitulo, $dtInicioCurso, $dtTerminoCurso, $txtTituloProjeto, $optCampo, $optLinhaPesquisa, $txtOrientador1, $bolVinculoEmpregaticio, $txtNomeInstituicao, $optTipoProcesso, $numInscricao);
        
        // Execute a inserção
        $stmt->execute();
        
        echo "Dados inseridos no banco.<br>";
    }
}

// Fecha a conexão IMAP
imap_close($caixa_entrada);

// Fecha a conexão com o banco de dados
$conn->close();
?>
