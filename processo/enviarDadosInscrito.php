<?php

function orientador($idOrientador) {

    switch ($idOrientador) {
        case "67": return "Ana Flavia Mendes Sapucahy";
            break;
        case "68": return "Áureo Deo DeFreitas Júnior";
            break;
        case "70": return "Orlando Franco Maneschy";
            break;
        case "71": return "Valzeli Figueira Sampaio";
            break;
        case "74": return "Benedita Afonso Martins";
            break;
        case "91": return "Giselle Guilhon Antunes Camargo";
            break;
        case "109": return "Ana Cláudia do Amaral Leão";
            break;
        case "111": return "Jose Denis Bezerra";
            break;
        case "124": return "Maria dos Remedios de Brito";
            break;
        case "127": return "Liliam Cristina Barros Cohen";
            break;
        case "128": return "Larissa Latif Placido Sare";
            break;
        case "135": return "Alexandre Romariz Sequeira";
            break;
        case "136": return "Sávio Luís Stoco";
            break;
        case "137": return "Alex Ferreira Damasceno";
            break;
        case "138": return "Marcia Mariana Bittencourt Brito";
            break;
        case "139": return "Gustavo Soranz Goncalves";
            break;
        case "140": return "Thales Branche Paes de Mendonça";
            break;
        case "141": return "Ana Cláudia da Cruz Melo";
            break;
        case "142": return "Francisco Pereira Smith Júnior";
            break;
        case "143": return "Hosana Celeste Oliveira";
            break;

        default: return $idOrientador;
    }
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SESSION['tipoinscrito'] == '1') {
    $tipoTitulo = "Mestrado";
} else {
    $tipoTitulo = "Doutorado";
}
$tipoinscrito = $_SESSION['tipoinscrito'];

/*
 *  MAPEANDO DADOS DO FORMUL�RIO PARA VARI�VES
 *  COM O MESMO NOME DOS CAMPOS DA TABELA DB_INSCRITOS
 */
$txtLinkCvLattes = $_POST["txtLinkCvLattes"];
$txtNome = $_POST["txtNome"];
$txtNacionalidade = $_POST["txtNacionalidade"];
$txtNaturalidade = $_POST["txtNaturalidade"];
$dtNascimento = $_POST["dtNascimento_3"] . "/" . $_POST["dtNascimento_2"] . "/" . $_POST["dtNascimento_1"];
$txtNumRG = $_POST["txtNumRG"];
$txtEmissorRg = $_POST["txtEmissorRg"];
$dtRg = $_POST["dtRg_1"] . "/" . $_POST["dtRg_2"] . "/" . $_POST["dtRg_3"];
$txtCPF = $_POST["txtCPF"];
$txtVisto = $_POST["txtVisto"];

if ($txtVisto == "") {
    $dtInicioVigenciaVisto = "";
    $dtTerminoVigenciaVisto = "";
} else {
    $dtInicioVigenciaVisto = $_POST["dtInicioVigenciaVisto_3"] . "/" . $_POST["dtInicioVigenciaVisto_2"] . "/" . $_POST["dtInicioVigenciaVisto_1"];
    $res = checkdate($_POST["dtInicioVigenciaVisto_2"], $_POST["dtInicioVigenciaVisto_1"], $_POST["dtInicioVigenciaVisto_3"]);
    if ($res != 1) {
        $dtInicioVigenciaVisto = "";

        if ($dtInicioVigenciaVisto == "//") {
            $dtInicioVigenciaVisto = "";
        }
    }
    $dtTerminoVigenciaVisto = $_POST["dtTerminoVigenciaVisto_3"] . "/" . $_POST["dtTerminoVigenciaVisto_2"] . "/" . $_POST["dtTerminoVigenciaVisto_1"];
    $res = checkdate($_POST["dtTerminoVigenciaVisto_2"], $_POST["dtTerminoVigenciaVisto_1"], $_POST["dtTerminoVigenciaVisto_3"]);
    if ($res != 1) {
        $dtTerminoVigenciaVisto = "";
        if ($dtTerminoVigenciaVisto == "//") {
            $dtTerminoVigenciaVisto = "";
        }
    }
}
$txtEndereco = $_POST["txtEndereco"];
$txtTelefone = $_POST["txtTelefone"];
$txtCelular = $_POST["txtCelular"];
$txtEmail = $_POST["txtEmail"];
$bolAtendimentoEspecial = $_POST["optAtendimentoEspecial"];
$txtEspecial = $_POST["txtEspecial"];
//$txtLocaldeProva = $_POST["txtLocaldeProva"];
// RETIRADO NO PSPPGARTES 2021, SETANDO PARA ""
$txtLocaldeProva = "";
$txtNomeEnsinoSuperior = $_POST["txtNomeEnsinoSuperior"];
$txtSiglaEnsinoSuperior = $_POST["txtSiglaEnsinoSuperior"];
$txtCurso = $_POST["txtCurso"];
$txtTitulo = $_POST["txtTitulo"];
$dtInicioCurso = $_POST["dtInicioCurso_3"] . "/" . $_POST["dtInicioCurso_2"] . "/" . $_POST["dtInicioCurso_1"];
$dtTerminoCurso = $_POST["dtTerminoCurso_3"] . "/" . $_POST["dtTerminoCurso_2"] . "/" . $_POST["dtTerminoCurso_1"];
$txtTituloProjeto = $_POST["txtTituloProjeto"];
$optCampo = $_POST["optCampo"];
$optLinhaPesquisa = $_POST["optLinhaPesquisa"];
$txtOrientador1 = $_POST["optOrientador1"];
$txtOrientador2 = $_POST["optOrientador2"];
$bolVinculoEmpregaticio = $_POST["optVinculoEmpregaticio"];
$txtNomeInstituicao = $_POST["txtNomeInstituicao"];

//die("Orientador " . $txtOrientador1 . " " . $txtOrientador2);


/*
 * INICIANDO O EMAIL
 */
include "../ferramentas/phpmailer/PHPMailerAutoload.php";
//require("phpmailer/class.phpmailer.php");
//$mail = new \PHPMailer\PHPMailer\PHPMailer ;
$mail = new PHPMailer();

$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

    <!--?php header("Content-type: text/html; charset=ISO-8859-1"); ?-->  
<?php header("Content-type: text/html; charset=UTF-8"); ?>  
    <head>
        <!--meta http-equiv="Content-Type" content="text/html; charset=UTF-8"-->
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
                    <h2>PSPPGARTES</h2>
                    <p>Formulário de Solicitação de Inscrição - Edital 2022</p>
                </div>						
                <ul >
                    <!--h3>Processando arquivos</h3-->
                    <p></p>

                    <?php
                    /*
                     * ABRINDO CONEX�O COM O BANCO DE DADOS 
                     */
                    //hostinger 
                    $conn = mysqli_connect('localhost', 'u613711144_psppgartes', ':2!2g6G#', 'u613711144_psppgartes', '3306');
                    //local $conn = mysqli_connect('localhost', 'u268037633_psppgartes', ':2!2g6G#', 'u268037633_psppgartes', '3306');
                    //$conn = mysqli_connect('localhost', 'u268037633_psppgartes', ':2!2g6G#', 'st_psppgartes', '3306');
                    if (!$conn) {
                        die('Erro na conexão com o servidor de dados: ' . mysqli_connect_error());
                    }
                    mysqli_query($conn, "SET NAMES 'utf8_general_ci'");
                    mysqli_query($conn, "SET time_zone = '-3:00'");
                    /*
                     * VERIFICANDO SE O CPF J� FOI CADASTRADO
                     */

                    $verificarDuplicado = "SELECT COUNT(*) AS TOTAL FROM `db_inscritos` WHERE `txtCPF` = '$txtCPF' ";
                    $quantidade = mysqli_query($conn, $verificarDuplicado);
                    $data = mysqli_fetch_assoc($quantidade);
                    //echo $data['TOTAL'];

                    if ($data['TOTAL'] !== "0") {
                        die("O CPF '$txtCPF' já foi cadastrado para o PSPPGARTES 2022<p><a href='http://www.ppgartes.propesp.ufpa.br/'>www.ppgartes.propesp.ufpa.br</a></p>");
                    }


                    /*
                     * PROCESSANDO OS ARQUIVOS 
                     * SALVANDO-OS NO SERVIDOR E ANEXANDO-OS AO EMAIL
                     * A SER ENVIADO PARA O PPGARTES
                     */
                    $countArquivo = 1;
                    $linkemail = "";
                    $listalinks = "";
                    foreach ($_FILES["Anexos"]["error"] as $key => $error) {
                        if ($error == UPLOAD_ERR_OK) {
                            $tmp_name = $_FILES["Anexos"]["tmp_name"][$key];

                            $name = basename($_FILES["Anexos"]["name"][$key]);
                            //$pos = $key[]
                            $name_target = $txtCPF . '_' . $countArquivo . ".pdf";
                            $linkemail = "https://psppgartes.4gestor.net/up2022/" . $name_target;
                            
                            $linkemail = "<a href = 'https://psppgartes.4gestor.net/up2022/$name_target' target = '_blank'>$name</a>";
                            
                            $countArquivo = $countArquivo + 1;
                            //$name_target = $txtCPF . '_' . $name; //até 2021 com o nome que poderia ter acentos
                            //echo $name_target . '<br />';

                            move_uploaded_file($tmp_name, "../up2022/$name_target");
                            //$mail->AddAttachment("../uploads/$name_target", $name_target);
                            $listalinks = $listalinks . "<br/>" . $linkemail;
                        }
                    }
                    //echo "<br />ARQUIVOS PROCESSADOS";
                    ?>
                    <!--h3>Processando dados do formul�rio de inscri��o</h3-->
                    <p></p>
                    <?php
                    /*
                     * SALVANDO OS DADOS NA TABELA DB_INSCRITOS

                      $conn = mysqli_connect('localhost', 'u268037633_psppgartes', ':2!2g6G#', 'u268037633_psppgartes', '3306');
                      if (!$conn)
                      {
                      die('Erro na conex�o com o servidor de dados: ' . mysqli_connect_error());
                      }
                      mysqli_query($conn, 'SET NAMES \'utf8_general_ci');
                     */

                    /*
                     * INSERIR CADASTRO DE INSCRITO
                     */
                    $inserirInscrito = "INSERT INTO `db_inscritos`  (  `txtNome`,`txtLinkCvLattes`,`txtNacionalidade`,`txtNaturalidade`,`dtNascimento`,`txtNumRG`,`txtEmissorRg`,`txtCPF`,`txtVisto`,`dtInicioVigenciaVisto`, `dtTerminoVigenciaVisto`,`txtEndereco`,`txtTelefone`,`txtCelular`,`txtEmail`, `bolAtendimentoEspecial`, `txtEspecial`, `txtLocaldeProva`,`txtNomeEnsinoSuperior`,`txtSiglaEnsinoSuperior`,`txtCurso`,`txtTitulo`,`dtInicioCurso`,`dtTerminoCurso`, `txtTituloProjeto`,`optCampo`,`optLinhaPesquisa`,`optOrientador1`,`optOrientador2`,  `bolVinculoEmpregaticio`, `txtInstituicao`, `optTipoProcesso`)" .
                            "VALUES ('$txtNome', '$txtLinkCvLattes','$txtNacionalidade', '$txtNaturalidade', '$dtNascimento', '$txtNumRG', '$txtEmissorRg', '$txtCPF', '$txtVisto', '$dtInicioVigenciaVisto', '$dtTerminoVigenciaVisto', '$txtEndereco', '$txtTelefone', '$txtCelular', '$txtEmail', $bolAtendimentoEspecial , '$txtEspecial', '$txtLocaldeProva', '$txtNomeEnsinoSuperior', '$txtSiglaEnsinoSuperior', '$txtCurso',  '$txtTitulo', '$dtInicioCurso', '$dtTerminoCurso', '$txtTituloProjeto', $optCampo, $optLinhaPesquisa, $txtOrientador1, $txtOrientador2, $bolVinculoEmpregaticio, '$txtNomeInstituicao' , $tipoinscrito  )";

                    //echo $inserirInscrito;
                    $result = mysqli_query($conn, $inserirInscrito);
                    if (!$result) {
                        die("<h2 class = 'texto_info'>Não foi possível realizar cadastro. Tente novamente mais tarde</h2><p><a href='http://www.ppgartes.propesp.ufpa.br/'>www.ppgartes.propesp.ufpa.br</a></p>");
                    }
                    $idCandidato = mysqli_insert_id($conn);
                    $Matricula = $idCandidato . '/2022';
                    $atualizarMatricula = "UPDATE `db_inscritos` SET `numInscricao`= '$Matricula' WHERE `idCandidato` = $idCandidato";
                    $result = mysqli_query($conn, $atualizarMatricula);
//echo "<br />DADOS PROCESSADOS";
//require("phpmailer/class.phpmailer.php");
//echo "Resultado = ' . $result . '<br />';

                    /*
                     * FECHANDO A CONEX�O DO BANCO DE DADOS
                     */
                    mysqli_close($conn);
                    ?>                   

                    <!--h3>Finalizando</h3-->
                    <p></p>
                    <?php
                    /*
                     * ENVIANDO EMAIL PARA O PPGARTES
                     */

// Define os dados do servidor e tipo de conex�o
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                    $mail->IsSMTP(); // Define que a mensagem ser� SMTP
                    $mail->Host = 'smtp.hostinger.com.br';
                    $mail->Port = '587';

                    $mail->SMTPSecure = 'tls'; //PODE SER "ssl"
                    $mail->SMTPAuth = true; // Usa autentica��o SMTP? (opcional)
                    $mail->Username = 'naoresponder@psppgartes.4gestor.net'; // Usu�rio do servidor SMTP
                    $mail->Password = '9]:CifrSDd'; // Senha do servidor SMTP
// Define o remetente
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->Sender = "naoresponder@ufpa.br";
                    date_default_timezone_set('America/Belem');
                    $mail->From = "naoresponder@psppgartes.4gestor.net"; // Seu e-mail
                    $mail->FromName = "Sistema PPGARTES"; // Seu nome
                    $mail->AddReplyTo("naoresponder@ufpa.br");
// Define os destinat�rio(s)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAddress('fulano@dominio.com.br', 'Fulano da Silva');
//EMAIL DA PPGARTES $mail->AddAddress("psppgartesdoutorado2@gmail.com");
//
                    if ($tipoinscrito == 1) {
                        //$mail->AddAddress("psppgartes.mestrado@gmail.com");
                    } else {
                        //$mail->AddAddress("psppgartesdoutorado2@gmail.com");
                    }

                    $mail->AddBCC('alexmota.br@gmail.com', 'Alexandre PPGARTES');
//$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
//$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // C�pia Oculta
// Define os dados t�cnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                    $mail->IsHTML(true); // Define que o e-mail ser� enviado como HTML
                    $mail->CharSet = 'UTF-8';
                    //date_default_timezone_set('America/Belem');
//$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                    $mail->Subject = "PSPPGARTES2022 " . $tipoTitulo . " - " . $txtNome; // Assunto da mensagem
//$mail->Body

                    if ($bolAtendimentoEspecial == 0) {
                        $txtAtendimentoEspecial = "Não";
                    } else {
                        $txtAtendimentoEspecial = "Sim";
                    }

                    if ($bolVinculoEmpregaticio == 0) {
                        $txtVinculoEmpregatici = "Não";
                    } else {
                        $txtVinculoEmpregatici = "Sim";
                    }

                    switch ($optCampo) {
                        case 1:
                            $txtcampo = "Teatro";
                            break;
                        case 2:
                            $txtcampo = "Música";
                            break;
                        case 3:
                            $txtcampo = "Dança";
                            break;
                        case 4:
                            $txtcampo = "Artes Visuais";
                            break;
                        case 5:
                            $txtcampo = "Cinema";
                            break;
                    }



                    $textoEmail = "<p>Novo Cadastro no Sistema do Processo Seletivo do PPGARTES - 2022.</p>" .
                            "<p>Dados da inscrição<br/></p>" .
                            "<table Border = 1>" .
                            "<tr><td>Tipo</td><td>" . $tipoTitulo . "</td><tr/>" .
                            "<tr><td>Matrícula</td><td>" . $Matricula . "</td><tr/>" .
                            "<tr><td>Nome</td><td>" . $txtNome . "</td><tr/>" .
                            "<tr><td>Cv Lattes</td><td>" . $txtLinkCvLattes . "</td></tr>" .
                            "<tr><td>Nacionalidade</td><td>" . $txtNacionalidade . "</td></tr>" .
                            "<tr><td>Naturalidade</td><td>" . $txtNaturalidade . "</td></tr>" .
                            "<tr><td>Data Nascimento</td><td>" . $dtNascimento . "</td></tr>" .
                            "<tr><td>NumRG</td><td>" . $txtNumRG . "</td></tr>" .
                            "<tr><td>EmissorRg</td><td>" . $txtEmissorRg . "</td></tr>" .
                            "<tr><td>CPF</td><td>" . $txtCPF . "</td></tr>" .
                            "<tr><td>Visto</td><td>" . $txtVisto . "</td></tr>" .
                            "<tr><td>Data Início Visto</td><td>" . $dtInicioVigenciaVisto . "</td></tr>" .
                            "<tr><td>Data Término Visto</td><td>" . $dtTerminoVigenciaVisto . "</td></tr>" .
                            "<tr><td>Endereco</td><td>" . $txtEndereco . "</td></tr>" .
                            "<tr><td>Telefone</td><td>" . $txtTelefone . "</td></tr>" .
                            "<tr><td>Celular</td><td>" . $txtCelular . "</td></tr>" .
                            "<tr><td>Email</td><td>" . $txtEmail . "</td></tr>" .
                            "optLocaldeProva</td><td>" . $txtLocaldeProva . "</td></tr>" .
                            "<tr><td>Atendimento Especial</td><td>" . $txtAtendimentoEspecial . "</td></tr>" .
                            "<tr><td>Qual Atendimento Especial</td><td>" . $txtEspecial . "</td></tr>" .
                            "<tr><td>Nome Ensino Superior</td><td>" . $txtNomeEnsinoSuperior . "</td></tr>" .
                            "<tr><td>Sigla EnsinoSuperior</td><td>" . $txtSiglaEnsinoSuperior . "</td></tr>" .
                            "<tr><td>Curso</td><td>" . $txtCurso . "</td></tr>" .
                            "<tr><td>Título</td><td>" . $txtTitulo . "</td></tr>" .
                            "<tr><td>dt Inicio Curso</td><td>" . $dtInicioCurso . "</td></tr>" .
                            "<tr><td>dt Termino Curso</td><td>" . $dtTerminoCurso . "</td></tr>" .
                            "<tr><td>Título do Projeto</td><td>" . $txtTituloProjeto . "</td></tr>" .
                            "<tr><td>Campo</td><td>" . $txtcampo . "</td></tr>" .
                            "<tr><td>LinhaPesquisa</td><td>" . $optLinhaPesquisa . "</td></tr>" .
                            "<tr><td>Orientador1</td><td>" . orientador($txtOrientador1) . "</td></tr>" .
                            "<tr><td>Orientador2</td><td>" . orientador($txtOrientador2) . "</td></tr>" .
                            "<tr><td>Vinculo Empregatício</td><td>" . $txtVinculoEmpregatici . "</td></tr>" .
                            "<tr><td>None da Instituição</td><td>" . $txtNomeInstituicao . "</td></tr>" .
                            "</table><br/>" .
                            $listalinks;

                    //die($textoEmail);
//$txtNomeInstituicao
                    $mail->Body = $textoEmail;
                    $mail->AltBody = "---------\r\n ";

//$mail->AddAttachment("/uploads", "documento.pdf");
// Define os anexos (opcional)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
// Envia o e-mail
//$enviado = $mail->Send();
                    if (!$mail->send()) {
                        echo "Erro no envio do E-mail para o PSPPGARTES";
                    } else {
                        echo "E-mail enviado para o PSPPGARTES<br />";
                    }
// Limpa os destinat�rios e os anexos
                    $mail->ClearAllRecipients();
                    $mail->ClearAttachments();
                    /*
                     * ENVIANDO EMAIL PARA O INSCRITO
                     */
                    date_default_timezone_set('America/Belem');
                    $DataAtual = new DateTime();

                    $tempoFormatado = $DataAtual->format('d-m-Y H:i:s');
                    $mail->From = "naoresponder@psppgartes.4gestor.net"; // Seu e-mail
                    $mail->FromName = "Sistema PPGARTES"; // Seu nome
                    $mail->AddReplyTo("naoresponder@ufpa.br");
//EMAIL DO INSCRITO
                    $mail->AddAddress($txtEmail);
                    $mail->AddBCC('alexmota.br@gmail.com', 'Alexandre PPGARTES');
                    $mail->Subject = "PSPPGARTES2022 - Confirmação de Inscrição de " . $txtNome; // Assunto da mensagem
                    $textoEmail = "<p>Sua inscrição no Processo Seletivo do PPGARTES foi realizado em $tempoFormatado</p>" .
                            "<p>Acompanhe o processo seletivo em www.ppgartes.propesp.ufpa.br<br/></p>";
                    $mail->Body = $textoEmail;
                    $mail->AltBody = "---------\r\n ";
                    if (!$mail->send()) {
                        echo "Erro no envio do E-mail para $txtEmail";
                    } else {
                        echo "E-mail de confirmação enviado para $txtEmail<br />";
                    }
                    //session_unset();
                    //session_destroy();
                    ?>
                    <h3>INSCRIÇÃO CONCLUÍDA COM SUCESSO!</h3>
                    <p></p>
                    <p>Confirmação de inscrição enviada para <b><?php echo $txtEmail ?></b> às <?php echo $tempoFormatado ?></p>
                    <p>Verifique sua Caixa de Entrada. Caso não encontre o e-mail de confirmação, verifique a caixa de SPAM</p> 
                    <p></p>
                    <p><a href='http://www.ppgartes.propesp.ufpa.br/'>www.ppgartes.propesp.ufpa.br</a></p>

                    <!--li class="buttons">
                        <input type="hidden" name="form_id" value="98277" />

                        <input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
                    </li-->
                </ul>
            </form>	

        </div>
        <img id="bottom" src="bottom.png" alt="">
    </body>

</html>


