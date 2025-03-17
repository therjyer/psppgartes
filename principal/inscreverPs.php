<!DOCTYPE html>
<html lang="pt-BR" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSPPGARTES</title>
    
    <link rel="stylesheet" type="text/css" href="../css/view.css" media="all">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../ferramentas/js/view.js" defer></script>
    <script src="../ferramentas/js/calendar.js" defer></script>
    <script src="../ferramentas/js/valida_cpf_cnpj.js" defer></script>
    <script src="../ferramentas/js/tipoInscricao.js" defer></script>
</head>
<body>
    <?php
    session_start();

    if (!isset($_SESSION['tipoinscrito']) || $_SESSION['tipoinscrito'] === '') {
        header("Location: https://www.ppgartes.propesp.ufpa.br/");
        exit();
    }

    $tipoinscrito = $_SESSION['tipoinscrito'];
    $tipoTitulo = ($tipoinscrito == '1') ? "Mestrado" : "Doutorado";
    ?>

    <h1>Bem-vindo ao PSPPGARTES</h1>
    <p>Você está inscrito no curso de <strong><?php echo htmlspecialchars($tipoTitulo); ?></strong>.</p>
</body>
</html>
<script>
    $(document).ready(function () {

        /**
         * Validação de CPF
         */
        $('#txtCPF').on('change', function () {
            const cpfCnpj = $(this).val();
            if (!valida_cpf_cnpj(cpfCnpj)) {
                $('#CPF_DOWN').text('CPF inválido');
                this.value = '';
            } else {
                $('#CPF_DOWN').text('');
            }
        });

        <?php
        session_start();
        $tipoInscrito = $_SESSION['tipoinscrito'] ?? ''; 
        $nivel = ($tipoInscrito == "1") ? "mestrado" : (($tipoInscrito == "2") ? "doutorado" : "");
        ?>
        const dadosPesquisa = {
            "mestrado": {
                "Poéticas e processos de atuação em artes": [
                    "Prof. Dr. Alex Ferreira Damasceno", 
                    "Prof. Dr. Alexandre Romariz Sequeira", 
                    "Prof.ª Dr.ª Andrea Bentes Flores",
                    "Prof.ª Dr.ª Graziela Ribeiro Baena",
                    "Prof.ª Dr.ª Hosana Celeste Oliveira",
                    "Prof.ª Dr.ª Mayrla Andrade Ferreira",
                    "Prof. Dr. Orlando Franco Maneschy",
                    "Prof. Dr. Thales Branche Paes de Mendonça",
                    "Prof.ª Dr.ª Valzeli Figueira Sampaio"
                ],
                "Teorias e Interfaces Epistêmicas em Artes": [
                    "Prof.ª Dr.ª Ana Cláudia do Amaral Leão",
                    "Prof. Dr. Francisco Pereira Smith Júnior",
                    "Prof.ª Dr.ª Hosana Celeste Oliveira",
                    "Prof.ª Dr.ª Ivone Maria Xavier de Amorim Almeida",
                    "Prof.ª Dr.ª Liliam Cristina Barros Cohen",
                    "Prof.ª Dr.ª Maria dos Remédios de Brito"
                ],
                "Memórias, Histórias e Educação em Artes": [
                    "Prof. Dr. Alex Ferreira Damasceno",
                    "Prof.ª Dr.ª Ana Claudia da Cruz Melo",
                    "Prof. Dr. Áureo Deo de Freitas Junior",
                    "Prof. Dr. José Denis de Oliveira Bezerra",
                    "Prof. Dr. José Afonso Medeiros Souza",
                    "Prof.ª Dr.ª Luzia Gomes Ferreira",
                    "Prof. Dr. Luiz Adriano Daminello",
                    "Prof.ª Dr.ª Márcia Mariana Bittencourt Brito",
                    "Prof. Dr. Orlando Franco Maneschy",
                    "Prof. Dr. Sávio Luís Stoco"
                ]
            },
            "doutorado": {
                "Poéticas e processos de atuação em artes": [
                    "Prof. Dr. Alex Ferreira Damasceno",
                    "Prof.ª Dr.ª Hosana Celeste Oliveira",
                    "Prof.ª Dr.ª Mayrla Andrade Ferreira dos Santos",
                    "Prof. Dr. Thales Branche Paes de Mendonça",
                    "Prof.ª Dr.ª Valzeli Figueira Sampaio"
                ],
                "Teorias e Interfaces Epistêmicas em Artes": [
                    "Prof.ª Dr.ª Ana Cláudia do Amaral Leão",
                    "Prof. Dr. Francisco Pereira Smith Junior",
                    "Prof.ª Dr.ª Hosana Celeste Oliveira",
                    "Prof.ª Dr.ª Liliam Cristina Barros Cohen",
                    "Prof.ª Dr.ª Maria dos Remédios de Brito"
                ],
                "Memórias, Histórias e Educação em Artes": [
                    "Prof.ª Dr.ª Ana Claudia da Cruz Melo",
                    "Prof. Dr. Áureo Deo de Freitas Júnior",
                    "Prof. Dr. José Denis de Oliveira Bezerra",
                    "Prof.ª Dr.ª Márcia Mariana Bittencourt Brito",
                    "Prof. Dr. Sávio Luís Stoco"
                ]
            }
        };

        // Passar o valor do tipo de inscrição (mestrado ou doutorado) do PHP para o JavaScript
        const nivel = "<?php echo $nivel; ?>"; // Pode ser 'mestrado' ou 'doutorado'

        // Função para atualizar os orientadores de acordo com a linha de pesquisa e o tipo de candidato
        document.getElementById('drpLinha').addEventListener('change', function() {
            var linhaPesquisa = this.value;
            var orientadorSelect = document.getElementById('orientador');

            // Limpa as opções atuais
            orientadorSelect.innerHTML = '<option selected value="0">Selecionar Orientador</option>';

            // Verifica se a linha de pesquisa tem orientadores disponíveis
            if (linhaPesquisa !== '0') {
                // Adiciona as opções de orientadores baseadas na linha de pesquisa e tipo de inscrição
                const orientadores = dadosPesquisa[nivel][linhaPesquisa];

                // Se houver orientadores, adiciona-os ao select
                if (orientadores) {
                    orientadores.forEach(function(orientador) {
                        var option = document.createElement("option");
                        option.value = orientador;
                        option.textContent = orientador;
                        orientadorSelect.appendChild(option);
                    });
                } else {
                    console.log("Nenhum orientador encontrado para essa linha de pesquisa.");
                }
            } else {
                console.log("Nenhuma linha de pesquisa selecionada.");
            }
        });

        function validarFormulario() {
            var txtVisto = document.getElementById("txtVisto").value;
            var dtInicioVigenciaVisto_1 = document.getElementById("dtInicioVigenciaVisto_1").value;
            var dtInicioVigenciaVisto_2 = document.getElementById("dtInicioVigenciaVisto_2").value;
            var dtInicioVigenciaVisto_3 = document.getElementById("dtInicioVigenciaVisto_3").value;
            var dtTerminoVigenciaVisto_1 = document.getElementById("dtTerminoVigenciaVisto_1").value;
            var dtTerminoVigenciaVisto_2 = document.getElementById("dtTerminoVigenciaVisto_2").value;
            var dtTerminoVigenciaVisto_3 = document.getElementById("dtTerminoVigenciaVisto_3").value;

            // Verificando se o campo Visto foi preenchido
            if (txtVisto !== "") {
                // Se o campo Visto foi preenchido, os campos de data devem ser obrigatórios
                if (dtInicioVigenciaVisto_1 === "" || dtInicioVigenciaVisto_2 === "" || dtInicioVigenciaVisto_3 === "" ||
                    dtTerminoVigenciaVisto_1 === "" || dtTerminoVigenciaVisto_2 === "" || dtTerminoVigenciaVisto_3 === "") {
                    alert("Por favor, preencha todas as datas do visto.");
                    return false; // Impede o envio do formulário
                }
            }

            return true; // Permite o envio do formulário se tudo estiver correto
        }

        // Adicionando a função de validação no evento de envio do formulário
        document.querySelector("form").onsubmit = function() {
            return validarFormulario();
        };

        /**
         * Função genérica para validação de arquivos
         */
        function validarArquivo(input, mensagemSelector) {
            const arquivo = input.files[0];
            if (!arquivo) return;

            const maxSize = 2 * 1024 * 1024; // 2MB
            const extensaoValida = 'pdf';
            let mensagem = '';

            if (arquivo.size > maxSize) {
                mensagem = 'O arquivo ultrapassa 2MB';
            } else if (!arquivo.name.toLowerCase().endsWith(extensaoValida)) {
                mensagem = 'O arquivo deve ser um PDF';
            }

            $(mensagemSelector).text(mensagem);
            if (mensagem) input.value = '';
        }

        /**
         * Aplicando validação a múltiplos arquivos
         */
        const arquivos = [
            { id: '#fileAutodeclaracaoDeEtnia', mensagem: '#ETNIA_DOWN' },
            { id: '#fileAutodeclaracaoDeTrans', mensagem: '#TRANS_DOWN' },
            { id: '#fileAnexoCvLattes', mensagem: '#ANEXO_LATTES' },
            { id: '#fileLaudoMedico', mensagem: '#LAUDO_DOWN' },
            { id: '#arquivoIndigena', mensagem: '#ANUENCIA_DOWN' },
            { id: '#fileContraCheque', mensagem: '#CHEQUE_DOWN' },
            { id: '#arquivoDiploma', mensagem: '#DIPLOMA_DOWN' },
            { id: '#arquivoHistorico', mensagem: '#HISTORICO_DOWN' },
            { id: '#arquivoRg', mensagem: '#RGA_DOWN' },
            { id: '#arquivoCpf', mensagem: '#CPFA_DOWN' },
            { id: '#arquivoGru', mensagem: '#GRU_DOWN' },
            { id: '#arquivoConcordancia', mensagem: '#CONCORDANCIA_DOWN' },
            { id: '#arquivoAnuencia', mensagem: '#ANUENCIA_DOWN' },
            { id: '#arquivoProficiencia', mensagem: '#PROFICIENCIA_DOWN' },
            { id: '#arquivoProficiencia2', mensagem: '#PROFICIENCIA2_DOWN' }
        ];

        arquivos.forEach(({ id, mensagem }) => {
            $(id).on('change', function () {
                validarArquivo(this, mensagem);
            });
        });

        /**
         * Exibir campos condicionais
         */
        function toggleCampo(inputId, targetId, showValue) {
            $(inputId).on('change', function () {
                $(targetId).toggle($(this).val() !== showValue);
            });
        }

        toggleCampo('#txtVisto', '#dtVisto', '');
        toggleCampo('#optAtendimentoEspecial', '#litxtEspecial2', '0');
        toggleCampo('#optVinculoEmpregaticio', '#litxtNomeInstituicao', '0');
    });
</script>

<head>
    <body id="main_body" >
        <div id="form_container">
            <div style="float:center;">
                <img src="images/logoPpgArtes.jpg" width="198" height="110" alt="PPGARTES"/>
            </div>
            <h1><a>PSPPGARTES</a></h1>
            <form id="form_cadastrarInscrito" class="appnitro" enctype="multipart/form-data" method="post" action="../processo/processarInscrito.php">
                <div class="form_description">
                    <h2>PSPPGARTES</h2>
                    <p>Formulário de Solicitação de Inscrição - Edital 2025
                    </p>
                    <p><b>Todos os documentos deverão ser enviados em formato PDF com, no máximo, 2Mb. Cada arquivo enviado deverá ser nomeado de forma clara, incluindo o nome do/a candidato/a e o tipo (por exemplo: o PDF contendo o RG de um candidato chamado Fulano de Tal deverá ser designado "RG_Fulano_de_Tal.pdf")". Evitar a utilização de acentuação gráfica ao nomear o arquivo.
                        </b></p>
                </div>
                <div class="row">
                    <h3><br>Informações Pessoais:<br></h3>
                    <div id="liNome" >
                        <label class="description" for="txtNome">Nome Completo:<span class="required">*</span>
                        </label>
                        <div>
                            <input id="txtNome" name="txtNome" class="element text large"  required type="text" maxlength="255"  value=""/> 
                        </div>
                    </div><br>
                    
                    <div id="liNomeSocial" >
                        <label class="description" for="txtNomeSocial">Nome Social:</label>
                        <div>
                            <input id="txtNomeSocial" name="txtNomeSocial" class="element text large"   type="text" maxlength="255"  value=""/> 
                        </div>
                    </div><br>
                    
                    <div id="liNacionalidade" >
                        <label class="description" for="txtNacionalidade">Nacionalidade:<span class="required">*</span></label>
                        <div>
                            <input id="txtNacionalidade" name="txtNacionalidade" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div>
                    </div><br>

                    <div id="liNaturalidade" >
                        <label class="description" for="txtNaturalidade">Naturalidade:<span class="required">*</span></label>
                        <div>
                            <input id="txtNaturalidade" name="txtNaturalidade" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div>
                    </div><br>

                    <div id="lidtNascimento" >
                        <label class="description" for="dtNascimento_1">Data de Nascimento:<span class="required">*</span>
                        </label>
                        <span>
                            <input id="dtNascimento_1" name="dtNascimento_1" class="element text" size="2" maxlength="2" min="1" max="31"  required value="" type="number"> /
                            <label for="dtNascimento_1">DD</label>
                        </span>
                        <span>
                            <input id="dtNascimento_2" name="dtNascimento_2" class="element text" size="2" maxlength="2" min="1" max="12"  required value="" type="number"> /
                            <label for="dtNascimento_2">MM</label>
                        </span>
                        <span>
                            <input id="dtNascimento_3" name="dtNascimento_3" class="element text" size="4" maxlength="4" min="1900" max="2100"   required value="" type="number">
                            <label for="dtNascimento_3">AAAA</label>
                        </span>
                        <span id="calendar_5">
                            <img id="cal_img_5" class="datepicker" src="images/calendar.gif" alt="Pick a date.">
                        </span>
                        <script type="text/javascript">
                            Calendar.setup({
                                inputField: "dtNascimento_3",
                                baseField: "dtNascimento",
                                displayArea: "calendar_5",
                                button: "cal_img_5",
                                ifFormat: "%B %e, %Y",
                                onSelect: selectEuropeDate
                            });
                        </script>
                    </div><br>

                    <div id="liRG" >
                        <label class="description" for="txtNumRG">RG:<span class="required">*</span></label>
                        <div>
                            <input id="txtNumRG" name="txtNumRG" class="element text small"  required type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_6"><small>Informar o número do seu documento de identificação</small></p> 
                    </div><br>

                    <div id="liEmissorRG" >
                        <label class="description" for="txtEmissorRg">Órgão Expeditor:<span class="required">*</span></label>
                        <div>
                            <input id="txtEmissorRg" name="txtEmissorRg" class="element text small"  required type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_7"><small>Informar o Órgão emissor do seu documento de identidade</small></p> 
                    </div><br>

                    <div id="lidtRg" >
                        <label class="description" for="dtRg_1">Data de Emissão:<span class="required">*</span></label>
                        <span>
                            <input id="dtRg_1" name="dtRg_1" class="element text" size="2" maxlength="2" min="1" max="31"  required value="" type="number"> /
                            <label for="dtRg_1">DD</label>
                        </span>
                        <span>
                            <input id="dtRg_2" name="dtRg_2" class="element text" size="2" maxlength="2" min="1" max="12"  required value="" type="number"> /
                            <label for="dtRg_2">MM</label>
                        </span>
                        <span>
                            <input id="dtRg_3" name="dtRg_3" class="element text" size="4" maxlength="4" min="1920" max="2030"   required value="" type="number">
                            <label for="dtRg_3">AAAA</label>
                        </span>
                        <span id="calendar_8">
                            <img id="cal_img_8" class="datepicker" src="images/calendar.gif" alt="Pick a date.">	
                        </span>
                        <script type="text/javascript">
                            Calendar.setup({
                                inputField: "dtRg_3",
                                baseField: "dtRg",
                                displayArea: "calendar_8",
                                button: "cal_img_8",
                                ifFormat: "%B %e, %Y",
                                onSelect: selectEuropeDate
                            });
                        </script>
                        <p class="guidelines" id="guide_8"><small>Informar a data da emissão do seu documento de identificação</small></p> 
                    </div><br>

                    <div id="liArquivoRG" >
                        <label class="description" for="arquivoRg">Anexo do RG (Conforme subitem 3.2.3):<span class="required">*</span></label>
                        <div>
                            <input id="arquivoRg" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="RGA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_25"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p>
                    </div><br>

                    <div id="liCPF" >
                        <label class="description" for="txtCPF">CPF:<span class="required">*</span></label>
                        <div>
                            <input id="txtCPF" name="txtCPF" class="cpf_cnpj"  required type="text" maxlength="11" value=""/> 
                            <p id="CPF_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p> 
                            
                        </div><p class="guidelines" id="guide_10"><small>Digite somente números sem "." ou "-"</small></p> 
                    </div><br>

                    <div id="liArquivoCPF" >
                        <label class="description" for="arquivoCpf">Anexo do CPF (Conforme subitem 3.2.3):<span class="required">*</span></label>
                        <div>
                            <input id="arquivoCpf" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="CPFA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_26"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </div><br>

                    <div id="liVisto" >
                        <label class="description" for="txtVisto">Visto Permanente (preencher apenas se for estrangeiro):</label>
                        <div>
                            <input id="txtVisto" name="txtVisto" class="element text medium"   type="text" maxlength="255" value=""/> 
                        </div>
                    </div><br>

                    <div id ="dtVisto" style="display: none;" >

                        <div id="lidtInicioVigenciaVisto" >
                            <label class="description" for="dtInicioVigenciaVisto_1">Início Período de Vigência:</label>
                            <span>
                                <input id="dtInicioVigenciaVisto_1" name="dtInicioVigenciaVisto_1" class="element text" size="2" maxlength="2" min="1" max="31" value="" type="number"> /
                                <label for="dtInicioVigenciaVisto_1">DD</label>
                            </span>
                            <span>
                                <input id="dtInicioVigenciaVisto_2" name="dtInicioVigenciaVisto_2" class="element text" size="2" maxlength="2" min="1" max="12" value="" type="number"> /
                                <label for="dtInicioVigenciaVisto_2">MM</label>
                            </span>
                            <span>
                                <input id="dtInicioVigenciaVisto_3" name="dtInicioVigenciaVisto_3" class="element text" size="4" maxlength="4" min="1920" max="2050" value="" type="number">
                                <label for="dtInicioVigenciaVisto_3">AAAA</label>
                            </span>
                            <span id="calendar_22">
                                <img id="cal_img_22" class="datepicker" src="images/calendar.gif" alt="Pick a date.">	
                            </span>
                            <script type="text/javascript">
                                Calendar.setup({
                                    inputField: "dtInicioVigenciaVisto_3",
                                    baseField: "dtInicioVigenciaVisto",
                                    displayArea: "calendar_22",
                                    button: "cal_img_22",
                                    ifFormat: "%B %e, %Y",
                                    onSelect: selectEuropeDate
                                });
                            </script>
                        </div><br>
                        <div id="lidtTerminoVigenciaVisto" >
                            <label class="description" for="dtTerminoVigenciaVisto_1">Término Periodo de Vigência:</label>
                            <span>
                                <input id="dtTerminoVigenciaVisto_1" name="dtTerminoVigenciaVisto_1" class="element text" size="2" maxlength="2" min="1" max="31"  value="" type="number"> /
                                <label for="dtTerminoVigenciaVisto_1">DD</label>
                            </span>
                            <span>
                                <input id="dtTerminoVigenciaVisto_2" name="dtTerminoVigenciaVisto_2" class="element text" size="2" maxlength="2" min="1" max="12" value="" type="number"> /
                                <label for="dtTerminoVigenciaVisto_2">MM</label>
                            </span>
                            <span>
                                <input id="dtTerminoVigenciaVisto_3" name="dtTerminoVigenciaVisto_3" class="element text" size="4" maxlength="4" min="1920" max="2050"  value="" type="number">
                                <label for="dtTerminoVigenciaVisto_3">AAAA</label>
                            </span>
                            <span id="calendar_23">
                                <img id="cal_img_23" class="datepicker" src="images/calendar.gif" alt="Pick a date.">	
                            </span>
                            <script type="text/javascript">
                                Calendar.setup({
                                    inputField: "dtTerminoVigenciaVisto_3",
                                    baseField: "dtTerminoVigenciaVisto",
                                    displayArea: "calendar_23",
                                    button: "cal_img_23",
                                    ifFormat: "%B %e, %Y",
                                    onSelect: selectEuropeDate
                                });
                            </script>
                        </div>
                    </div><br>

                    <div id="liEndereço" >
                        <label class="description" for="txtEndereco">Endereço:<span class="required">*</span></label>
                        <div>
                            <textarea id="txtEndereco" name="txtEndereco" required class="element textarea medium"></textarea>
                        </div>
                    </div><br>

                    <div id="liTelefone" >
                        <label class="description" for="txtTelefone">Telefone:</label>
                        <div>
                            <input id="txtTelefone" name="txtTelefone" class="element text medium"  type="text" maxlength="255" value=""/>
                        </div><p class="guidelines" id="guide_11"><small>informar no formato (##) #####-####</small></p>
                    </div><br>

                    <div id="liCelular" >
                        <label class="description" for="txtCelular">Celular:<span class="required">*</span></label>
                        <div>
                            <input id="txtCelular" name="txtCelular" class="element text medium"  required type="text" maxlength="255" value=""/>
                        </div><p class="guidelines" id="guide_12"><small>informar no formato (##) #####-####</small></p>
                    </div><br>

                    <div id="liEmail" >
                        <label class="description" for="txtEmail">E-mail:<span class="required">*</span></label>
                        <div>
                            <input id="txtEmail" name="txtEmail" class="element text medium"  required type="text" maxlength="255" value=""/>
                        </div><p class="guidelines" id="guide_13"><small>Informe um e-mail válido para contato</small></p>
                    </div><br>

                    <div id="libolAtendimentoEspecial" >
                        <label class="description" for="optAtendimentoEspecial">Necessita de Atendimento Especial para a realização da prova?<span class="required">*</span>
                        </label>
                        <div>
                            <select class="element select medium" required value=''  id="optAtendimentoEspecial" name="optAtendimentoEspecial">
                                <option value="Nao" >Não</option>
                                <option value="Sim" >Sim</option>
                            </select>
                    </div><br>

                    <div id="litxtEspecial2" style="display: none;">
                        <label class="description" for="txtEspecial">Especificar atendimento especial:</label>
                        <div>
                            <input id="txtEspecial" name="txtEspecial" class="element text large"  type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_13"><small>Especificar qual o atendimento especial</small></p> 
                    </div>
                
                    <h3><br>Autodeclarações e Documentos:<br></h3>
                    
                    <div id="liAutodeclaraçãoDeEtnia" >
                        <label class="description" for="fileAutodeclaracaoDeEtnia">Autodeclaração de etnia (Item 2.4 - Anexo 06)</label>
                        <div>
                            <input id="fileAutodeclaracaoDeEtnia" name="Anexos[]" class="element file" type="file"/> 
                            <p id="ETNIA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_17"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </div><br>

                    <div id="liAutodeclaraçãoDeTrans" >
                        <label class="description" for="fileAutodeclaracaoDeTrans">Autodeclaração de pessoas trans (Item 2.6 - Anexo 07)</label>
                        <div>
                            <input id="fileAutodeclaracaoDeTrans" name="Anexos[]" class="element file" type="file"/> 
                            <p id="TRANS_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_19"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </div><br>

                    <div id="liLaudoMedico" >
                        <label class="description" for="fileLaudoMedico">Laudo Médico/Relatório Médico (item 2.5 do Edital)</label>
                        <div>
                            <input id="fileLaudoMedico" name="Anexos[]" class="element file" type="file"/> 
                            <p id="LAUDO_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_41"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </div><br>

                    <div id="liArquivoIndigena" >
                        <label class="description" for="arquivoIndigena">Autodeclaração de Identidade indígena e Declaração de Pertencimento Étnico (Item 2.7 do EDITAL)</label>
                        <div>
                            <input id="arquivoIndigena" name="Anexos[]"  class="element file" type="file"/> 
                            <p id="ANUENCIA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_29"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </div><br>

                    <div id="liContraCheque" >
                        <label class="description" for="fileContraCheque">Anexar o último contracheque ou declaração funcional - Vaga PADT (item 2.8 do Edital)</label>
                        <div>
                            <input id="fileContraCheque" name="Anexos[]" class="element file" type="file"/> 
                            <p id="CHEQUE_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_43"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </div>

                    <h3><br>Formação Acadêmica:<br></h3>
                    
                    <div id="liNomeEnsinoSuperior" >
                        <label class="description" for="txtNomeEnsinoSuperior">Instituição de Ensino Superior:<span class="required">*</span></label>
                        <div>
                            <input id="txtNomeEnsinoSuperior" name="txtNomeEnsinoSuperior" class="element text large"  required type="text" maxlength="200" value=""/> 
                        </div><p class="guidelines" id="guide_16"><small>Informe nome por extenso</small></p> 
                    </div><br>

                    <div id="liSiglaEnsinoSuperior" >
                        <label class="description" for="txtSiglaEnsinoSuperior">Sigla da Instituição:<span class="required">*</span></label>
                        <div>
                            <input id="txtSiglaEnsinoSuperior" name="txtSiglaEnsinoSuperior" class="element text small"  required type="text" maxlength="20" value=""/> 
                        </div>
                    </div><br>
                    
                    <div id="liCurso" >
                        <label class="description" for="txtCurso">Curso:<span class="required">*</span></label>
                        <div>
                            <input id="txtCurso" name="txtCurso" class="element text large"  required type="text" maxlength="255" value=""/> 
                        </div>
                    </div><br>

                    <div id="liTitulo" >
                        <label class="description" for="txtTitulo">Título obtido:<span class="required">*</span></label>
                        <div>
                            <input id="txtTitulo" name="txtTitulo" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div>
                    </div><br>

                    <div id="lidtInicioCurso" >
                        <label class="description" for="dtInicioCurso_1">Início do curso:<span class="required">*</span></label>
                        <span>
                            <input id="dtInicioCurso_1" name="dtInicioCurso_1" class="element text" size="2" maxlength="2" min="1" max="31"  required value="" type="number"> /
                            <label for="dtInicioCurso_1">DD</label>
                        </span>
                        <span>
                            <input id="dtInicioCurso_2" name="dtInicioCurso_2" class="element text" size="2" maxlength="2" min="1" max="12"  required value="" type="number"> /
                            <label for="dtInicioCurso_2">MM</label>
                        </span>
                        <span>
                            <input id="dtInicioCurso_3" name="dtInicioCurso_3" class="element text" size="4" maxlength="4" min="1920" max="2030"   required value="" type="number">
                            <label for="dtInicioCurso_3">AAAA</label>
                        </span>
                        <span id="calendar_20">
                            <img id="cal_img_20" class="datepicker" src="images/calendar.gif" alt="Pick a date.">	
                        </span>
                        <script type="text/javascript">
                            Calendar.setup({
                                inputField: "dtInicioCurso_3",
                                baseField: "dtInicioCurso",
                                displayArea: "calendar_20",
                                button: "cal_img_20",
                                ifFormat: "%B %e, %Y",
                                onSelect: selectEuropeDate
                            });
                        </script>
                    </div><br>

                    <div id="lidtTerminoCurso" >
                        <label class="description" for="dtTerminoCurso_1">Término do curso:</label>
                        <span>
                            <input id="dtTerminoCurso_1" name="dtTerminoCurso_1" class="element text" size="2" maxlength="2" min="1" max="31"  required value="" type="number"> /
                            <label for="dtTerminoCurso_1">DD</label>
                        </span>
                        <span>
                            <input id="dtTerminoCurso_2" name="dtTerminoCurso_2" class="element text" size="2" maxlength="2" min="1" max="12"  required value="" type="number"> /
                            <label for="dtTerminoCurso_2">MM</label>
                        </span>
                        <span>
                            <input id="dtTerminoCurso_3" name="dtTerminoCurso_3" class="element text" size="4" maxlength="4" min="1920" max="2030"   required value="" type="number">
                            <label for="dtTerminoCurso_3">AAAA</label>
                        </span>
                        <span id="calendar_21">
                            <img id="cal_img_21" class="datepicker" src="images/calendar.gif" alt="Pick a date.">	
                        </span>
                        <script type="text/javascript">
                            Calendar.setup({
                                inputField: "dtTerminoCurso_3",
                                baseField: "dtTerminoCurso",
                                displayArea: "calendar_21",
                                button: "cal_img_21",
                                ifFormat: "%B %e, %Y",
                                onSelect: selectEuropeDate
                            });
                        </script>
                    </div><br>
                    
                    <div id="liArquivoDiploma" >
                        <label class="description" for="arquivoDiploma">Diploma/Declaração Conclusão/Declaração Concluinte (Conforme subitem 3.2.2 do Edital):<span class="required">*</span></label>
                        <div>
                            <input id="arquivoDiploma" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="DIPLOMA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_23"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </div><br>

                    <div id="liArquivoHistorico" >
                        <label class="description" for="arquivoHistorico">Histórico (Conforme subitem 3.2.2 do Edital):<span class="required">*</span></label>
                        <div>
                            <input id="arquivoHistorico" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="HISTORICO_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_24"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </div>

                    <h3><br>Projeto Proposto:<br></h3>

                    <div id="liTituloProjeto" >
                        <label class="description" for="txtTituloProjeto">Título do Projeto Proposto:<span class="required">*</span></label>
                        <div>
                            <textarea id="txtTituloProjeto" name="txtTituloProjeto" required class="element textarea medium"></textarea> 
                        </div>
                    </div><br>

                    <div id="lioptCampo">
                        <label class="description" for="optCampo">Selecione o Campo de Atuação:<span class="required">*</span></label>
                        <div>
                            <select class="element select medium" required id="optCampo" name="optCampo"> 
                                <option selected value="0">Selecionar campo</option>
                                <option value="1">Teatro</option>
                                <option value="2">Música</option>
                                <option value="3">Dança</option>
                                <option value="4">Artes Visuais</option>
                                <option value="5">Cinema</option>
                            </select>
                        </div>
                    </div><br>

                    <div id="liLinhaPesquisa">
                        <label class="description" for="drpLinha">Selecione sua linha de pesquisa:<span class="required">*</span></label>
                        <div>
                            <select id="drpLinha" required name="optLinhaPesquisa">
                                <option selected value="0">Selecionar Linha</option>
                                <option value="Poéticas e processos de atuação em artes">Poéticas e processos de atuação em artes</option>
                                <option value="Teorias e Interfaces Epistêmicas em Artes">Teorias e Interfaces Epistêmicas em Artes</option>
                                <option value="Memórias, Histórias e Educação em Artes">Memórias, Histórias e Educação em Artes</option>
                            </select>
                        </div>
                        <p class="guidelines" id="guide_39"><small>Informe qual a linha de pesquisa você quer concorrer</small></p>
                    </div><br>

                    <div id="liOrientador">
                        <label class="description" for="orientador">Selecione seu orientador:<span class="required">*</span></label>
                        <div>
                            <select id="orientador" name="orientador">
                                <option selected value="0">Selecionar Orientador</option>
                            </select>
                        </div>
                        <p class="guidelines" id="guide_51"><small>Informe qual orientador pretendido</small></p>
                    </div><br>

                    <script type="text/javascript">
                        // Função para validar o formulário
                        function validarFormulario(event) {
                            var optCampo = document.getElementById("optCampo").value;
                            var drpLinha = document.getElementById("drpLinha").value;
                            var orientador = document.getElementById("orientador").value;

                            // Verificando se o campo de "Campo de Atuação" foi selecionado
                            if (optCampo === "0") {
                                alert("Por favor, selecione um campo de atuação.");
                                event.preventDefault(); // Impede o envio do formulário
                                return false;
                            }

                            // Verificando se a "Linha de Pesquisa" foi selecionada
                            if (drpLinha === "0") {
                                alert("Por favor, selecione uma linha de pesquisa.");
                                event.preventDefault(); // Impede o envio do formulário
                                return false;
                            }

                            // Verificando se o "Orientador" foi selecionado
                            if (orientador === "0") {
                                alert("Por favor, selecione um orientador.");
                                event.preventDefault(); // Impede o envio do formulário
                                return false;
                            }

                            return true; // Permite o envio do formulário se tudo estiver correto
                        }

                        // Adicionando a função de validação no evento de envio do formulário
                        document.querySelector("form").addEventListener("submit", validarFormulario);
                    </script>

                    <h3><br>Documentos Adicionais:<br></h3>
                    <div id="liArquivoGRU" >
                        <label class="description" for="arquivoGru">GRU e Comprovante de Pagamento ou Declaração de isenção (Conforme subitem 3.2.4 - Anexo 2):<span class="required">*</span></label>
                        <div>
                            <input id="arquivoGru" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="GRU_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_27"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </div><br>

                    <div id="liArquivoConcordancia" >
                        <label class="description" for="arquivoConcordancia">Declaração de Concordância (Conforme subitem 3.2.5):<span class="required">*</span></label>
                        <div>
                            <input id="arquivoConcordancia" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="CONCORDANCIA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_28"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </div><br>

                    <div id="liArquivoProficiencia" >
                        <label class="description" for="arquivoProficiencia">Certificado de proficiência em língua estrangeira (Conforme subitem 3.2.6 e item 8.4)</label>
                        <div>
                            <input id="arquivoProficiencia" name="Anexos[]" class="element file" type="file"/> 
                            <p id="PROFICIENCIA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_30"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb, sendo que será aceito apenas 1 certificado para o mestrado e 2 para o doutorado</small></p> 
                    </div>

                    <h3><br>Vínculo Empregatício:<br></h3>

                    <div id="liVinculoEmpregaticio" >
                        <label class="description" for="optVinculoEmpregaticio">Vínculo Empregatício:<span class="required">*</span></label>
                        <div>
                            <select class="element select medium" required id="optVinculoEmpregaticio" name="optVinculoEmpregaticio"> 
                                <option value="Nao" >Não</option>
                                <option value="Sim" >Sim</option>
                            </select>
                        </div> <p class="guidelines" id="guide_31"><small>Informe se você possui ou não vínculo empregatício</small></p> 
                    </div><br>

                    <div id="litxtNomeInstituicao" style="display: none;" >
                        <label class="description" for="txtNomeInstituicao">Instituição / Empresa:</label>
                        <div>
                            <input id="txtNomeInstituicao" name="txtNomeInstituicao" class="element text large" type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_16"><small>Informe nome da Instituição / Empresa que está vinculado atualmente</small></p> 
                    </div>

                    <h3><br>Anexos:<br></h3>

                    <div id="liAnexoCvLattes">
                        <label class="description" for="fileAnexoCvLattes">Anexar currículo lattes (Apenas PDF)<span class="required">*</span></label>
                        <div>
                            <input id="fileAnexoCvLattes" name="Anexos[]" class="element file" type="file"/> 
                            <p id="ANEXO_LATTES" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_15"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </div><br>

                    <div id="liLinkCvLattes" >
                        <label class="description" for="txtLinkCvLattes">Endereço para acessar CV (plataforma lattes CNPq):<span class="required">*</span>
                        </label>
                        <div>
                            <input id="txtLinkCvLattes" name="txtLinkCvLattes" class="element text medium"  required type="text" maxlength="255"  value=""/> 
                        </div><p class="guidelines" id="guide_1"><small>informar o link válido para o seu CV</small></p>
                    </div><br>

                <br><button>Enviar solicitação de inscrição</button>
            </form>
        </div>
        <img id="bottom" src="images/bottom.png" alt="">
    </body>
</html>