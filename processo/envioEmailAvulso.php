<?php

include 'config.php';


function orientador($idOrientador) {



    switch ($idOrientador) {
        case "67": return "Ana Flavia Mendes Sapucahy"; break;
        case "129": return "Iara Regina da Silva Souza"; break;
        case "70": return "Orlando Maneschy"; break;
        case "71": return "Valzeli Figueira Sampaio"; Break;
        case "100": return "Ivone Xavier"; Break;
        case "124": return "Maria dos Remedios de Brito"; Break;
        case "68": return "Aureo Deo DeFreitas Junior"; Break;
        case "125": return "Jose Afonso Medeiros Souza"; Break;
        case "111": return "Jose Denis de Oliveira Bezerra"; Break;
        case "105": return "Rosangela Marques de Britto"; Break;
        case "91": return "Giselle Guilhon"; Break;
        case "135": return "Alexandre Sequeira"; Break;
        case "136": return "Savio Stoco"; Break;
        case "137": return "Alex Damasceno"; Break;
        case "138": return "Marcia Bittencourt"; Break;
       

        default: return $idOrientador;
    }
}


//Seleciona o Canduidato pelo idCandidato
//SELECT * FROM `db_inscritos` WHERE `idCandidato` = 42

$registroCandidato="SELECT * FROM `db_inscritos` WHERE `idCandidato` = 42";

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbDatabase, $dbPort);
 if (!$conn)
                        {
                        die('Erro na conexão com o servidor de dados: ' . mysqli_connect_error());
                        }                    


$result = mysqli_query($conn, $registroCandidato);
                    if (!$result)
                        {
                        die("<h2 class = 'texto_info'>Não foi possível encontrar o registro</a></p>");
                        }                    


// Define os dados do servidor e tipo de conexão
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                    $mail->IsSMTP(); // Define que a mensagem será SMTP
                    $mail->Host = $mailSMTPHost;
                    $mail->Port = $mailSMTPPort;

                    $mail->SMTPSecure = $mailSMTPSecure; //PODE SER "ssl"
                    $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
                    $mail->Username = $mailSMTPUser; // Usuário do servidor SMTP
                    $mail->Password = $mailSMTPPassword; // Senha do servidor SMTP
// Define o remetente
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->Sender = "naoresponder@ufpa.br";

                    $mail->From = "naoresponder@psppgartes.4gestor.net"; // Seu e-mail
                    $mail->FromName = "Sistema de Avaliação PPGARTES"; // Seu nome
                    $mail->AddReplyTo("naoresponder@ufpa.br");
// Define os destinatário(s)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
//$mail->AddAddress('fulano@dominio.com.br', 'Fulano da Silva');
//EMAIL DA PPGARTES $mail->AddAddress("psppgartesdoutorado2@gmail.com");
//
                    if ($tipoinscrito == 1)
                        {
                        $mail->AddAddress($mailAddressMestrado);
                        } else
                        {
                        $mail->AddAddress($mailAddressDoutorado);
                        }

                    $mail->AddBCC($mailAddBCCAddress, $mailAddBCCAddressName);
//$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
//$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Oculta
// Define os dados técnicos da Mensagem
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
//$mail->CharSet = 'iso-8859-1'; // Charset da mensagem (opcional)
// Define a mensagem (Texto e Assunto)
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                    $mail->Subject = "PSPPGARTES2021 " .  $tipoTitulo. " - " . $txtNome; // Assunto da mensagem
//$mail->Body

                    if ($bolAtendimentoEspecial == 0)
                        {
                        $txtAtendimentoEspecial = "Não";
                        } else
                        {
                        $txtAtendimentoEspecial = "Sim";
                        }

                    if ($bolVinculoEmpregaticio == 0)
                        {
                        $txtVinculoEmpregatici = "Não";
                        } else
                        {
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



                    $textoEmail = "<p>Novo Cadastro no Sistema do Processo Seletivo do PPGARTES.</p>" .
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
                            "<tr><td>Titulo</td><td>" . $txtTitulo . "</td></tr>" .
                            "<tr><td>dt Inicio Curso</td><td>" . $dtInicioCurso . "</td></tr>" .
                            "<tr><td>dt Termino Curso</td><td>" . $dtTerminoCurso . "</td></tr>" .
                            "<tr><td>Título do Projeto</td><td>" . $txtTituloProjeto . "</td></tr>" .
                            "<tr><td>Campo</td><td>" . $txtcampo . "</td></tr>" .
                            "<tr><td>LinhaPesquisa</td><td>" . $optLinhaPesquisa . "</td></tr>" .
                            "<tr><td>Orientador1</td><td>" . orientador($txtOrientador1) . "</td></tr>" .
                            "<tr><td>Orientador2</td><td>" . orientador($txtOrientador2) . "</td></tr>" .
                            "<tr><td>Vinculo Empregaticio</td><td>" . $txtVinculoEmpregatici . "</td></tr>" .
                            "<tr><td>None da Instituição</td><td>" . $txtNomeInstituicao . "</td></tr>" .
                            "</table>";

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
                    if (!$mail->send())
                        {
                        echo "Erro no envio do E-mail para o PSPPGARTES";
                        } else
                        {
                        echo "E-mail enviado para o PSPPGARTES<br />";
                        }
// Limpa os destinatários e os anexos
                    $mail->ClearAllRecipients();
                    $mail->ClearAttachments();
  
  ?>