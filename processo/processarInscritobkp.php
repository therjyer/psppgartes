<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SESSION['tipoinscrito'] == '1') {
    $tipoTitulo = "Mestrado";
} else {
    $tipoTitulo = "Doutorado";
}
$tipoinscrito = $_SESSION['tipoinscrito'];

// Função para formatar datas para o formato 'YYYY-MM-DD'
function formatarData($dia, $mes, $ano) {
    // Verificar se o dia, mês e ano são válidos
    if (checkdate($mes, $dia, $ano)) {
        // Retornar a data no formato 'YYYY-MM-DD'
        return sprintf('%04d-%02d-%02d', $ano, $mes, $dia);
    } else {
        // Caso a data não seja válida, retornar uma string vazia
        return "";
    }
}

// Função para verificar e retornar o valor de uma variável POST
function obterPost($campo) {
    return filter_input(INPUT_POST, $campo, FILTER_SANITIZE_STRING);
}

// Mapear dados do formulário para variáveis com o mesmo nome dos campos da tabela DB_INSCRITOS
$txtNome = obterPost("txtNome");
$txtNomeSocial = obterPost("txtNomeSocial");
$txtLinkCvLattes = obterPost("txtLinkCvLattes");
$txtNacionalidade = obterPost("txtNacionalidade");
$txtNaturalidade = obterPost("txtNaturalidade");
$txtEndereco = obterPost("txtEndereco");
$txtTelefone = obterPost("txtTelefone");
$txtCelular = obterPost("txtCelular");
$txtEmail = obterPost("txtEmail");
$txtNomeEnsinoSuperior = obterPost("txtNomeEnsinoSuperior");
$txtSiglaEnsinoSuperior = obterPost("txtSiglaEnsinoSuperior");
$txtCurso = obterPost("txtCurso");
$txtTitulo = obterPost("txtTitulo");
$txtTituloProjeto = obterPost("txtTituloProjeto");
$txtOrientador1 = obterPost("txtOrientador1");
$txtNomeInstituicao = mysqli_real_escape_string($con, $txtNomeInstituicao);
$txtNomeInstituicao = !empty($txtNomeInstituicao) ? "'$txtNomeInstituicao'" : "NULL";
$txtEspecial = isset($_POST["txtEspecial"]) ? mysqli_real_escape_string($con, $_POST["txtEspecial"]) : NULL;
$txtEspecial = !empty($txtEspecial) ? "'$txtEspecial'" : "NULL";

// Tratando datas de nascimento
$dia = obterPost("dtNascimento_1");
$mes = obterPost("dtNascimento_2");
$ano = obterPost("dtNascimento_3");

$dtNascimento = formatarData($dia, $mes, $ano);

// // Verificar se a data foi formatada corretamente
// if (empty($dtNascimento)) {
//     die('Data de nascimento inválida.');
// }

// echo "Data de nascimento formatada: " . $dtNascimento;

$txtNumRG = obterPost("txtNumRG");
$txtEmissorRg = obterPost("txtEmissorRg");

// Tratando datas do RG
$dtRg = formatarData(obterPost("dtRg_1"), obterPost("dtRg_2"), obterPost("dtRg_3"));

$txtCPF = obterPost("txtCPF");
$txtVisto = obterPost("txtVisto");

if ($txtVisto == "") {
    $dtInicioVigenciaVisto = "";
    $dtTerminoVigenciaVisto = "";
} else {
    // Tratando datas do visto
    $dtInicioVigenciaVisto = formatarData(obterPost("dtInicioVigenciaVisto_1"), obterPost("dtInicioVigenciaVisto_2"), obterPost("dtInicioVigenciaVisto_3"));
    $dtTerminoVigenciaVisto = formatarData(obterPost("dtTerminoVigenciaVisto_1"), obterPost("dtTerminoVigenciaVisto_2"), obterPost("dtTerminoVigenciaVisto_3"));
}


$bolAtendimentoEspecial = obterPost("optAtendimentoEspecial");
$bolAtendimentoEspecial = $bolAtendimentoEspecial == '1' ? 1 : 0;

// Tratando datas de curso
$dtInicioCurso = formatarData(obterPost("dtInicioCurso_1"), obterPost("dtInicioCurso_2"), obterPost("dtInicioCurso_3"));
$dtTerminoCurso = formatarData(obterPost("dtTerminoCurso_1"), obterPost("dtTerminoCurso_2"), obterPost("dtTerminoCurso_3"));

$optCampo = obterPost("optCampo");
$optLinhaPesquisa = obterPost("optLinhaPesquisa");
$bolVinculoEmpregaticio = obterPost("optVinculoEmpregaticio");
$bolVinculoEmpregaticio = $bolVinculoEmpregaticio == '1' ? 1 : 0;

$optTipoProcesso = $tipoinscrito;

// INICIANDO O EMAIL

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer();

$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
?>

<!DOCTYPE html>
<html class="no-js" lang="pt-br" xml:lang="pt-br">
<?php header("Content-type: text/html; charset=UTF-8"); ?>
    <head>
        <title>PSPPGARTES</title>
        <link rel="stylesheet" type="text/css" href="view.css" media="all">
        <script type="text/javascript" src="view.js"></script>

    </head>
    <body id="main_body" >

        <img id="top" src="top.png" alt="">
        <div id="form_container">

            <h1><a>PSPPGARTES</a></h1>
            <form id="form_98277" class="appnitro"  method="post" action="">
                <div class="form_description">
                    <h3>Formulário de Solicitação de Inscrição - Edital 2025 - <?php echo $tipoTitulo ?></h3>
                </div>
                <ul>
                    <?php
                    // Configurações do servidor FTP
                    $ftp_host = "br860.hostgator.com.br"; // Host FTP correto
                    $ftp_user = "pokkin57"; // Seu usuário FTP
                    $ftp_pass = "05[3=g_B1eRRue4(0kt%5Kg5aVRzq"; // Sua senha FTP
                    $ftp_port = 21; // Porta FTP padrão (tente 990 se usar FTPS)
                    $ftp_dir = "/psppgartes.pokkins.com/up2025"; // Diretório remoto onde os arquivos serão armazenados
                    $conn = mysqli_connect('localhost', 'psppgartes', '1RNYK]lTMJapb24s', 'st_psppgartes');

                    if (!$conn) {
                        die('Erro na conexão com o servidor de dados: ' . mysqli_connect_error());
                    }

                    mysqli_query($conn, "SET NAMES 'utf8_general_ci'");
                    mysqli_query($conn, "SET time_zone = '-3:00'");

                    // Pega o ano corrente
                    $anoCorrente = date('Y');

                    // Nome da tabela com o ano corrente
                    $tabelaInscritos = "inscritos" . $anoCorrente;

                    // Verifica se a tabela já existe
                    $tabelaExistenteQuery = "SHOW TABLES LIKE '$tabelaInscritos'";
                    $resultTabelaExistente = mysqli_query($conn, $tabelaExistenteQuery);

                    $txtOrientador1 = trim($txtOrientador1);  // Remove espaços extras antes ou depois do valor

                    if (mysqli_num_rows($resultTabelaExistente) == 0) {
                        // Se a tabela não existe, cria a tabela para o ano corrente
                        $criarTabela = "
                        CREATE TABLE `$tabelaInscritos` (
                            `idCandidato` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                            `txtNome` VARCHAR(255),
                            `txtNomeSocial` VARCHAR(255),
                            `txtLinkCvLattes` TEXT,
                            `txtNacionalidade` VARCHAR(255),
                            `txtNaturalidade` VARCHAR(255),
                            `dtNascimento` DATE,
                            `txtNumRG` VARCHAR(50),
                            `txtEmissorRg` VARCHAR(50),
                            `txtCPF` VARCHAR(14),
                            `txtVisto` VARCHAR(50),
                            `dtInicioVigenciaVisto` DATE,
                            `dtTerminoVigenciaVisto` DATE,
                            `txtEndereco` TEXT,
                            `txtTelefone` VARCHAR(20),
                            `txtCelular` VARCHAR(20),
                            `txtEmail` VARCHAR(255),
                            `bolAtendimentoEspecial` BOOLEAN,
                            `txtEspecial` TEXT,
                            `txtNomeEnsinoSuperior` VARCHAR(255),
                            `txtSiglaEnsinoSuperior` VARCHAR(50),
                            `txtCurso` VARCHAR(255),
                            `txtTitulo` VARCHAR(255),
                            `dtInicioCurso` DATE,
                            `dtTerminoCurso` DATE,
                            `txtTituloProjeto` VARCHAR(255),
                            `optCampo` INT,
                            `optLinhaPesquisa` VARCHAR(255),
                            `txtOrientador1` VARCHAR(255),
                            `bolVinculoEmpregaticio` BOOLEAN,
                            `txtInstituicao` VARCHAR(255),
                            `optTipoProcesso` INT,
                            `numInscricao` VARCHAR(50)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                        
                        if (!mysqli_query($conn, $criarTabela)) {
                            die('Erro ao criar tabela: ' . mysqli_error($conn));
                        }
                        echo "Tabela $tabelaInscritos criada com sucesso.<br>";
                    }

                    // Verificar se o CPF já existe
                    $verificarDuplicado = "SELECT COUNT(*) AS TOTAL FROM `$tabelaInscritos` WHERE `txtCPF` = '$txtCPF'";
                    $quantidade = mysqli_query($conn, $verificarDuplicado);
                    $data = mysqli_fetch_assoc($quantidade);

                    if ($data['TOTAL'] !== "0") {
                        die("O CPF '$txtCPF' já foi cadastrado para o PSPPGARTES $anoCorrente<p><a href='http://www.ppgartes.propesp.ufpa.br/'>www.ppgartes.propesp.ufpa.br</a></p>");
                    }

                    $countArquivo = 1;

                    foreach ($_FILES["Anexos"]["error"] as $key => $error) {
                        if ($error == UPLOAD_ERR_OK) {
                            $tmp_name = $_FILES["Anexos"]["tmp_name"][$key];
                            $original_name = $_FILES["Anexos"]["name"][$key];
                            $name_target = $txtCPF . '_' . $countArquivo . ".pdf";
                            
                            // Conectando ao servidor FTP
                            $ftp_conn = ftp_ssl_connect($ftp_host, $ftp_port); // Usando SSL/TLS
                            if (!$ftp_conn) {
                                die("Erro ao conectar no FTP!");
                            }
                    
                            // Fazendo login
                            $login = ftp_login($ftp_conn, $ftp_user, $ftp_pass);
                            if (!$login) {
                                die("Erro ao fazer login no FTP!");
                            }
                    
                            // Ativar modo passivo (necessário para HostGator)
                            ftp_pasv($ftp_conn, true);
                    
                            // Verificar se a pasta remota existe
                            if (!@ftp_chdir($ftp_conn, $ftp_dir)) {
                                if (!ftp_mkdir($ftp_conn, $ftp_dir)) {
                                    die("Erro: Diretório remoto não existe e não foi possível criá-lo!");
                                }
                            }
                    
                            // Caminho do arquivo remoto no servidor
                            $remote_file = $ftp_dir . '/' . $name_target; // A parte crucial: corrigir o caminho remoto com "/"
                    
                            // Verifica se o arquivo temporário existe antes de enviar
                            if (!file_exists($tmp_name)) {
                                die("Erro: Arquivo temporário não encontrado no servidor!");
                            }
                    
                            // Abrir o arquivo local para envio
                            $file = fopen($tmp_name, 'r');
                    
                            // Enviar o arquivo para o servidor FTP
                            if (ftp_fput($ftp_conn, $remote_file, $file, FTP_BINARY)) {
                                // Gerar o link do arquivo
                                $linkemail = "<a href='https://psppgartes.pokkins.com/up2025/$name_target' target='_blank'>$original_name</a>";
                                $listalinks .= "<br/>" . $linkemail;
                            } else {
                                echo "Erro ao enviar arquivo!";
                            }
                    
                            // Fechar o arquivo e a conexão FTP
                            fclose($file);
                            ftp_close($ftp_conn);
                    
                            $countArquivo++;
                        }
                    }

                    // Inserir dados do inscrito

                    $inserirInscrito = "
                    INSERT INTO `$tabelaInscritos` (
                        `txtNome`, `txtNomeSocial`, `txtLinkCvLattes`, `txtNacionalidade`, `txtNaturalidade`, `dtNascimento`,
                        `txtNumRG`, `txtEmissorRg`, `txtCPF`, `txtVisto`, `dtInicioVigenciaVisto`, `dtTerminoVigenciaVisto`,
                        `txtEndereco`, `txtTelefone`, `txtCelular`, `txtEmail`, `bolAtendimentoEspecial`, `txtEspecial`,
                        `txtNomeEnsinoSuperior`, `txtSiglaEnsinoSuperior`, `txtCurso`, `txtTitulo`,
                        `dtInicioCurso`, `dtTerminoCurso`, `txtTituloProjeto`, `optCampo`, `optLinhaPesquisa`,
                        `txtOrientador1`, `bolVinculoEmpregaticio`, `txtInstituicao`, `optTipoProcesso`
                    ) VALUES (
                        '$txtNome', '$txtNomeSocial', '$txtLinkCvLattes', '$txtNacionalidade', '$txtNaturalidade', '$dtNascimento',
                        '$txtNumRG', '$txtEmissorRg', '$txtCPF', '$txtVisto', '$dtInicioVigenciaVisto', '$dtTerminoVigenciaVisto',
                        '$txtEndereco', '$txtTelefone', '$txtCelular', '$txtEmail', $bolAtendimentoEspecial, '$txtEspecial',
                        '$txtNomeEnsinoSuperior', '$txtSiglaEnsinoSuperior', '$txtCurso', '$txtTitulo',
                        '$dtInicioCurso', '$dtTerminoCurso', '$txtTituloProjeto', $optCampo, '$optLinhaPesquisa',
                        '$txtOrientador1', $bolVinculoEmpregaticio, '$txtInstituicao', $optTipoProcesso
                    )";

                    $result = mysqli_query($conn, $inserirInscrito);
                    if (!$result) {
                        die("<h2 class='texto_info'>Não foi possível realizar cadastro. Erro: " . mysqli_error($conn) . "</h2>");
                    }

                    $idCandidato = mysqli_insert_id($conn);
                    $Matricula = $idCandidato . '/' . $anoCorrente;

                    $atualizarMatricula = "UPDATE `$tabelaInscritos` SET `numInscricao`= '$Matricula' WHERE `idCandidato` = $idCandidato";
                    mysqli_query($conn, $atualizarMatricula);

                    mysqli_close($conn);

                    // Função para configurar o envio de e-mail
                    function configurarEmail($mail, $assunto, $corpo, $destinatarios = [], $bcc = [])
                    {
                        // Configuração do SMTP
                        $mail->IsSMTP();
                        $mail->Host = 'smtp.titan.email';
                        $mail->Port = 587;
                        $mail->SMTPSecure = 'tls';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'psppgartes@pokkins.com';
                        $mail->Password = '<P~XGX;Pm|/A60J';
                        $mail->From = 'psppgartes@pokkins.com';
                        $mail->FromName = 'Sistema PPGARTES';
                        $mail->AddReplyTo('naoresponder@ufpa.br');
                        
                        // Adicionar destinatários
                        foreach ($destinatarios as $destinatario) {
                            $mail->AddAddress($destinatario);
                        }

                        // Adicionar BCC
                        foreach ($bcc as $bccEmail => $nome) {
                            $mail->AddBCC($bccEmail, $nome);
                        }

                        $mail->IsHTML(true);
                        $mail->CharSet = 'UTF-8';
                        $mail->Subject = $assunto;
                        $mail->Body = $corpo;
                        $mail->AltBody = '---------\r\n';

                        // Enviar o e-mail
                        if (!$mail->send()) {
                            return false;
                        }
                        return true;
                    }

                    // Função para formatar o campo de seleção
                    function formatarCampo($optCampo)
                    {
                        switch ($optCampo) {
                            case 1: return 'Teatro';
                            case 2: return 'Música';
                            case 3: return 'Dança';
                            case 4: return 'Artes Visuais';
                            case 5: return 'Cinema';
                            default: return 'Desconhecido';
                        }
                    }

                    // Função para formatar a resposta do vínculo empregatício
                    function formatarVinculoEmpregaticio($valor)
                    {
                        return $valor == 0 ? 'Não' : 'Sim';
                    }

                    // Função para gerar o corpo do e-mail para o PPGARTES
                    function gerarCorpoEmailPPGARTES($dados)
                    {
                        // Gerar o corpo do e-mail em formato HTML
                        $campo = formatarCampo($dados['optCampo']);
                        $atendimentoEspecial = formatarVinculoEmpregaticio($dados['bolAtendimentoEspecial']);
                        $vinculoEmpregaticio = formatarVinculoEmpregaticio($dados['bolVinculoEmpregaticio']);
                        
                        $textoEmail = "<p>Novo Cadastro no Sistema do Processo Seletivo do PPGARTES - 2025.</p>";
                        $textoEmail .= "<p>Dados da inscrição<br/></p><table border='1'>";

                        foreach ($dados as $campo => $valor) {
                            // Excluir campos desnecessários
                            if (in_array($campo, ['optCampo', 'bolAtendimentoEspecial', 'bolVinculoEmpregaticio'])) continue;

                            // Criar uma linha da tabela para cada campo
                            $textoEmail .= "<tr><td>{$campo}</td><td>{$valor}</td></tr>";
                        }

                        $textoEmail .= "<tr><td>Campo</td><td>{$campo}</td></tr>";
                        $textoEmail .= "<tr><td>Vinculo Empregatício</td><td>{$vinculoEmpregaticio}</td></tr>";
                        $textoEmail .= "</table><br/>" . $dados['listalinks'];

                        return $textoEmail;
                    }

                    // Enviar e-mail para o PPGARTES
                    $dadosPPGARTES = [
                        'tipoTitulo' => $tipoTitulo,
                        'Matricula' => $Matricula,
                        'Nome' => $txtNome,
                        'NomeSocial' => $txtNomeSocial,
                        'CvLattes' => $txtLinkCvLattes,
                        'Nacionalidade' => $txtNacionalidade,
                        'Naturalidade' => $txtNaturalidade,
                        'DataNascimento' => $dtNascimento,
                        'NumRG' => $txtNumRG,
                        'EmissorRg' => $txtEmissorRg,
                        'CPF' => $txtCPF,
                        'Visto' => $txtVisto,
                        'DataInicioVisto' => $dtInicioVigenciaVisto,
                        'DataTerminoVisto' => $dtTerminoVigenciaVisto,
                        'Endereco' => $txtEndereco,
                        'Telefone' => $txtTelefone,
                        'Celular' => $txtCelular,
                        'Email' => $txtEmail,
                        'AtendimentoEspecial' => $txtAtendimentoEspecial,
                        'QualAtendimentoEspecial' => $txtEspecial,
                        'NomeEnsinoSuperior' => $txtNomeEnsinoSuperior,
                        'SiglaEnsinoSuperior' => $txtSiglaEnsinoSuperior,
                        'Curso' => $txtCurso,
                        'Titulo' => $txtTitulo,
                        'DtInicioCurso' => $dtInicioCurso,
                        'DtTerminoCurso' => $dtTerminoCurso,
                        'TituloProjeto' => $txtTituloProjeto,
                        'Campo' => $txtcampo,
                        'LinhaPesquisa' => $optLinhaPesquisa,
                        'Orientador' => orientador($txtOrientador1),
                        'VinculoEmpregatício' => $txtVinculoEmpregatici,
                        'NomeInstituicao' => $txtNomeInstituicao,
                    ];

                    // Preparar o corpo do e-mail
                    $corpoPPGARTES = gerarCorpoEmailPPGARTES($dadosPPGARTES);
                    $assuntoPPGARTES = "Processo Seletivo PPGArtes 2025 " . $tipoTitulo . " - " . $txtNome;

                    // Destinatários
                    $destinatariosPPGARTES = ($tipoinscrito == 1) ? ["psppgartes.mestrado@gmail.com"] : ["psppgartesdoutorado2@gmail.com"];
                    $bccPPGARTES = ['thiago.correa@icen.ufpa.br' => 'Thiago Castro'];

                    // Enviar e-mail
                    if (!configurarEmail($mail, $assuntoPPGARTES, $corpoPPGARTES, $destinatariosPPGARTES, $bccPPGARTES)) {
                        echo "Erro no envio do E-mail para o PSPPGARTES";
                    } else {
                        echo "E-mail enviado para o PSPPGARTES<br />";
                    }

                    // Enviar e-mail para o inscrito
                    $tempoFormatado = (new DateTime())->format('d-m-Y H:i:s');
                    $assuntoInscrito = "Processo Seletivo PPGArtes 2025 - Confirmação de Inscrição de " . $txtNome;
                    $corpoInscrito = "<p>Sua inscrição no Processo Seletivo do PPGARTES foi realizada em $tempoFormatado</p><p>Acompanhe o processo seletivo em www.ppgartes.propesp.ufpa.br<br/></p>";
                    $destinatariosInscrito = [$txtEmail];
                    $bccInscrito = ['thiago.correa@icen.ufpa.br' => 'Thiago Castro'];

                    if (!configurarEmail($mail, $assuntoInscrito, $corpoInscrito, $destinatariosInscrito, $bccInscrito)) {
                        echo "Erro no envio do E-mail para $txtEmail";
                    } else {
                        echo "E-mail de confirmação enviado para $txtEmail<br />";
                    }
                ?>

                    <h3>INSCRIÇÃO CONCLUÍDA COM SUCESSO!</h3>
                    <p>Parabéns! Sua inscrição foi concluída com sucesso.</p>
                    <p>Uma confirmação foi enviada para <strong><?php echo htmlspecialchars($txtEmail); ?></strong> às <?php echo htmlspecialchars($tempoFormatado); ?>.</p>
                    <p>Verifique sua Caixa de Entrada. Caso não encontre o e-mail de confirmação, não se esqueça de verificar a pasta de SPAM.</p>
                    <p>Para mais informações, acesse o site do processo seletivo:</p>
                    <p><a href="http://www.ppgartes.propesp.ufpa.br/" target="_blank" rel="noopener noreferrer" aria-label="Acesse o site do processo seletivo PPGARTES">www.ppgartes.propesp.ufpa.br</a></p>
                </ul>
            </form>
        </div>
        <img id="bottom" src="bottom.png" alt="">
    </body>
</html>