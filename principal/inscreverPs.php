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
            let cpf_cnpj = $(this).val();
            if (!valida_cpf_cnpj(cpf_cnpj)) {
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
        let dadosPesquisa = {};

        $(document).ready(function () {
            $.getJSON('../ferramentas/jsondata/data.json', function (data) {
                dadosPesquisa = data;
                let nivel = "<?php echo $nivel; ?>"; // Pegando o nível do PHP

                if (nivel && dadosPesquisa[nivel]) {
                    carregarLinhasDePesquisa(nivel);
                }
            }).fail(function () {
                console.error('Erro ao carregar JSON');
            });
        });

        function carregarLinhasDePesquisa(nivel) {
            let $linhaPesquisa = $('#linhaPesquisa').empty().append('<option value="">Selecione a linha de pesquisa</option>');
            
            $.each(dadosPesquisa[nivel], function (linha) {
                $linhaPesquisa.append(`<option value="${linha}">${linha}</option>`);
            });

            $linhaPesquisa.prop('disabled', false); // Habilita a caixa de seleção

            // Se já tiver uma linha de pesquisa selecionada, carregar os orientadores
            $linhaPesquisa.change();
        }

        $('#linhaPesquisa').change(function () {
            let nivel = "<?php echo $nivel; ?>"; 
            let linhaSelecionada = $(this).val();
            let $orientador = $('#orientador').empty().append('<option value="">Selecione o orientador</option>');
            
            if (linhaSelecionada && dadosPesquisa[nivel] && dadosPesquisa[nivel][linhaSelecionada]) {
                $.each(dadosPesquisa[nivel][linhaSelecionada], function (_, orientador) {
                    $orientador.append(`<option value="${orientador}">${orientador}</option>`);
                });
                $orientador.prop('disabled', false); // Habilita a caixa de orientadores
            }
        });

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
        <!--img id="top" src="top.png" alt=""-->
        <div id="form_container">
            <div style="float:center;">
                <img src="images/logoppgartes.jpg" width="198" height="110" alt="PPGARTES"/>
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
                    <div>
                        <label for="inputNome">Primeiro Nome:</label>
                        <input type="text" id="inputNome" name="nome" required maxlength="25" minlength="2">
                        <span></span>
                    </div>

                    <div>
                        <label for="inputEmail">Endereço de Email:</label>
                        <input type="email" id="inputEmail" name="email" required maxlength="50">
                        <span></span>
                    </div>

                    <div>
                        <label for="textAreaMensagem">Mensagem:</label>
                        <textarea name="mensagem" id="textAreaMensagem" required maxlength="100"></textarea>
                        <span></span>
                    </div>

                    <button>Enviar mensagem</button>
                <!--<ul>
                    <li id="liAnexoCvLattes">
                        <label class="description" for="fileAnexoCvLattes">Anexar currículo lattes (Apenas PDF)<span class="required">*</span></label>
                        <div>
                            <input id="fileAnexoCvLattes" name="Anexos[]" class="element file" type="file"/> 
                            <p id="ANEXO_LATTES" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_15"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </li>
                    <li id="liLinkCvLattes" >
                        <label class="description" for="txtLinkCvLattes">Endereço para acessar CV (plataforma lattes CNPq):<span class="required">*</span>
                        </label>
                        <div>
                            <input id="txtLinkCvLattes" name="txtLinkCvLattes" class="element text medium"  required type="text" maxlength="255"  value="http://"/> 
                        </div><p class="guidelines" id="guide_1"><small>informar o link válido para o seu CV</small></p> 
                    </li>
                    <li id="liNome" >
                        <label class="description" for="txtNome">Nome Completo:<span class="required">*</span>
                        </label>
                        <div>
                            <input id="txtNome" name="txtNome" class="element text large"  required type="text" maxlength="255"  value=""/> 
                        </div> 
                    </li>
                    <li id="liNomeSocial" >
                        <label class="description" for="txtNomeSocial">Nome Social:</label>
                        <div>
                            <input id="txtNomeSocial" name="txtNomeSocial" class="element text large"   type="text" maxlength="255"  value=""/> 
                        </div> 
                    </li>
                    <li id="liNacionalidade" >
                        <label class="description" for="txtNacionalidade">Nacionalidade:<span class="required">*</span></label>
                        <div>
                            <input id="txtNacionalidade" name="txtNacionalidade" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div> 
                    </li>		
                    <li id="liNaturalidade" >
                        <label class="description" for="txtNaturalidade">Naturalidade:<span class="required">*</span></label>
                        <div>
                            <input id="txtNaturalidade" name="txtNaturalidade" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div> 
                    </li>		
                    <li id="lidtNascimento" >
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
                            <input id="dtNascimento_3" name="dtNascimento_3" class="element text" size="4" maxlength="4" min="1920" max="2024"   required value="" type="number">
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

                    </li>		
                    <li id="liRG" >
                        <label class="description" for="txtNumRG">RG:<span class="required">*</span></label>
                        <div>
                            <input id="txtNumRG" name="txtNumRG" class="element text small"  required type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_6"><small>Informar o número do seu documento de identificação</small></p> 
                    </li>		
                    <li id="liEmissorRG" >
                        <label class="description" for="txtEmissorRg">Órgão Expeditor:<span class="required">*</span></label>
                        <div>
                            <input id="txtEmissorRg" name="txtEmissorRg" class="element text small"  required type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_7"><small>Informar o Órgão emissor do seu documento de identidade</small></p> 
                    </li>		
                    <li id="lidtRg" >
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
                            <input id="dtRg_3" name="dtRg_3" class="element text" size="4" maxlength="4" min="1920" max="2024"   required value="" type="number">
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
                    </li>		
                    <li id="liCPF" >
                        <label class="description" for="txtCPF">CPF:<span class="required">*</span></label>
                        <div>
                            <input id="txtCPF" name="txtCPF" class="cpf_cnpj"  required type="text" maxlength="11" value=""/> 
                            <p id="CPF_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p> 
                            
                        </div><p class="guidelines" id="guide_10"><small>Digite somente números sem "." ou "-"</small></p> 
                    </li>		
                    <li id="liVisto" >
                        <label class="description" for="txtVisto">Visto Permanente (preencher apenas se for estrangeiro):</label>
                        <div>
                            <input id="txtVisto" name="txtVisto" class="element text medium"   type="text" maxlength="255" value=""/> 
                        </div> 
                    </li>
                    <div id ="dtVisto" style="display: none;" >
                        <li id="lidtInicioVigenciaVisto" >
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

                        </li>		
                        <li id="lidtTerminoVigenciaVisto" >
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

                        </li>
                    </div>
                    <li id="liEndereço" >
                        <label class="description" for="txtEndereco">Endereço:<span class="required">*</span></label>
                        <div>
                            <textarea id="txtEndereco" name="txtEndereco" required class="element textarea medium"></textarea> 
                        </div> 
                    </li>		
                    <li id="liTelefone" >
                        <label class="description" for="txtTelefone">Telefone:</label>
                        <div>
                            <input id="txtTelefone" name="txtTelefone" class="element text medium"  type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_11"><small>informar no formato (##) #####-####</small></p> 
                    </li>		
                    <li id="liCelular" >
                        <label class="description" for="txtCelular">Celular:<span class="required">*</span></label>
                        <div>
                            <input id="txtCelular" name="txtCelular" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_12"><small>informar no formato (##) #####-####</small></p> 
                    </li>		
                    <li id="liEmail" >
                        <label class="description" for="txtEmail">E-mail:<span class="required">*</span></label>
                        <div>
                            <input id="txtEmail" name="txtEmail" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_13"><small>Informe um e-mail válido para contato</small></p> 
                    </li>		
                    <li id="libolAtendimentoEspecial" >
                        <label class="description" for="optAtendimentoEspecial">Necessita de Atendimento Especial para a realização da prova?<span class="required">*</span>
                        </label>
                        <div>
                            <select class="element select medium" required value=''  id="optAtendimentoEspecial" name="optAtendimentoEspecial"> 
                                <option value="" ></option>
                                <option value="0" >Não</option>
                                <option value="1" >Sim</option>                                
                            </select>
                        </div> 
                    <li id="litxtEspecial2" style="display: none;">
                        <label class="description" for="txtEspecial">Especificar atendimento especial:</label>
                        <div>
                            <input id="txtEspecial" name="txtEspecial" class="element text large"  type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_13"><small>Especificar qual o atendimento especial</small></p> 
                    </li>
                    <li id="liAutodeclaraçãoDeEtnia" >
                        <label class="description" for="fileAutodeclaraçãoDeEtnia">Autodeclaração de etnia (Item 2.4 - Anexo 06)</label>
                        <div>
                            <input id="fileAutodeclaraçãoDeEtnia" name="Anexos[]" class="element file" type="file"/> 
                            <p id="ETNIA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_15"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </li>
                    <li id="liAutodeclaraçãoDeTrans" >
                        <label class="description" for="fileAutodeclaraçãoDeTrans">Autodeclaração de pessoas trans (Item 2.6 - Anexo 07)</label>
                        <div>
                            <input id="fileAutodeclaraçãoDeTrans" name="Anexos[]" class="element file" type="file"/> 
                            <p id="TRANS_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_15"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </li>
                    <li id="liLaudoMedico" >
                        <label class="description" for="fileLaudoMedico">Laudo Médico/Relatório Médico (item 2.5 do Edital)</label>
                        <div>
                            <input id="fileLaudoMedico" name="Anexos[]" class="element file" type="file"/> 
                            <p id="LAUDO_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_41"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </li>
                   <li id="liArquivoIndigena" >
                        <label class="description" for="arquivoIndigena">Autodeclaração de Identidade indígena e Declaração de Pertencimento Étnico(Item 2.7 do EDITAL)</label>
                        <div>
                            <input id="arquivoIndigena" name="Anexos[]"  class="element file" type="file"/> 
                            <p id="ANUENCIA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_29"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </li>
                    <li id="liContraCheque" >
                        <label class="description" for="fileContraCheque">Anexar o último contracheque ou declaração funcional - Vaga PADT (item 2.8 do Edital)</label>
                        <div>
                            <input id="fileContraCheque" name="Anexos[]" class="element file" type="file"/> 
                            <p id="CHEQUE_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_43"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </li>
                    <li id="liNomeEnsinoSuperior" >
                        <label class="description" for="txtNomeEnsinoSuperior">Instituição de Ensino Superior:<span class="required">*</span></label>
                        <div>
                            <input id="txtNomeEnsinoSuperior" name="txtNomeEnsinoSuperior" class="element text large"  required type="text" maxlength="200" value=""/> 
                        </div><p class="guidelines" id="guide_16"><small>Informe nome por extenso</small></p> 
                    </li>
                    <li id="liSiglaEnsinoSuperior" >
                        <label class="SiglaEnsinoSuperior" for="txtSiglaEnsinoSuperior">Sigla da Instituição:<span class="required">*</span></label>
                        <div>
                            <input id="txtSiglaEnsinoSuperior" name="txtSiglaEnsinoSuperior" class="element text small"  required type="text" maxlength="20" value=""/> 
                        </div> 
                    </li>		
                    <li id="liCurso" >
                        <label class="description" for="txtCurso">Curso:<span class="required">*</span></label>
                        <div>
                            <input id="txtCurso" name="txtCurso" class="element text large"  required type="text" maxlength="255" value=""/> 
                        </div> 
                    </li>		
                    <li id="liTitulo" >
                        <label class="description" for="txtTitulo">Título obtido:<span class="required">*</span></label>
                        <div>
                            <input id="txtTitulo" name="txtTitulo" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div> 
                    </li>		
                    <li id="lidtInicioCurso" >
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
                            <input id="dtInicioCurso_3" name="dtInicioCurso_3" class="element text" size="4" maxlength="4" min="1920" max="2024"   required value="" type="number">
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

                    </li>		
                    <li id="lidtTerminoCurso" >
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
                            <input id="dtTerminoCurso_3" name="dtTerminoCurso_3" class="element text" size="4" maxlength="4" min="1920" max="2024"   required value="" type="number">
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

                    </li>		
                    <li id="liTituloProjeto" >
                        <label class="description" for="txtTituloProjeto">Título do Projeto Proposto:<span class="required">*</span></label>
                        <div>
                            <textarea id="txtTituloProjeto" name="txtTituloProjeto" required class="element textarea medium"></textarea> 
                        </div> 
                    </li>		
                    <li id="lioptCampo" >
                        <label class="description" for="optCampo">Selecione o Campo de Atuação:<span class="required">*</span></label>
                        <div>
                            <select class="element select medium" required id="optCampo" name="optCampo"> 
                                <option value="" selected="selected"></option>
                                <option value="1" >Teatro</option>
                                <option value="2" >Música</option>
                                <option value="3" >Dança</option>
                                <option value="4" >Artes Visuais</option>
                                <option value="5" >Cinema</option>
                            </select>
                        </div> 
                    </li>
                    <li id="liLinhaPesquisa">
                        <label class="description" for="linhaPesquisa">Selecione a linha de pesquisa:<span class="required">*</span></label>
                        <div>
                            <select id="linhaPesquisa" name="linhaPesquisa">
                                <option value="">Linha de pesquisa</option>
                            </select>
                        </div>
                        <p class="guidelines" id="guide_31"><small>Informe qual a linha de pesquisa você quer concorrer</small></p> 
                    </li>

                    <li id="liOrientador">
                        <label class="description" for="orientador">Selecione o orientador:<span class="required">*</span></label>
                        <div>
                            <select id="orientador" name="orientador">
                                <option value="">Orientador</option>
                            </select>
                        </div>
                        <p class="guidelines" id="guide_31"><small>Informe qual orientador pretendido</small></p> 
                    </li>
                    <li id="liArquivoDiploma" >
                        <label class="description" for="arquivoDiploma">Diploma/Declaração Conclusão/Declaração Concluinte (Conforme subitem 3.2.2 do Edital):<span class="required">*</span></label>
                        <div>
                            <input id="arquivoDiploma" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="DIPLOMA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_23"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </li>		
                    <li id="liArquivoHistorico" >
                        <label class="description" for="arquivoHistorico">Histórico (Conforme subitem 3.2.2 do Edital):<span class="required">*</span></label>
                        <div>
                            <input id="arquivoHistorico" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="HISTORICO_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_24"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </li>		
                    <li id="liArquivoRG" >
                        <label class="description" for="arquivoRg">RG (Conforme subitem 3.2.3):<span class="required">*</span></label>
                        <div>
                            <input id="arquivoRg" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="RGA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_25"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </li>		
                    <li id="liArquivoCPF" >
                        <label class="description" for="arquivoCpf">CPF (Conforme subitem 3.2.3):<span class="required">*</span></label>
                        <div>
                            <input id="arquivoCpf" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="CPFA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_26"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </li>		
                    <li id="liArquivoGRU" >
                        <label class="description" for="arquivoGru">GRU e Comprovante de Pagamento ou Declaração de isenção (Conforme subitem 3.2.4 - Anexo 2):<span class="required">*</span></label>
                        <div>
                            <input id="arquivoGru" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="GRU_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_27"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </li>		
                    <li id="liArquivoConcordancia" >
                        <label class="description" for="arquivoConcordancia">Declaração de Concordância (Conforme subitem 3.2.5):<span class="required">*</span></label>
                        <div>
                            <input id="arquivoConcordancia" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="CONCORDANCIA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_28"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb</small></p> 
                    </li>
                    <li id="liArquivoProficiencia" >
                        <label class="description" for="arquivoProficiencia">Certificado de proficiência em língua estrangeira (Conforme subitem 3.2.6 e item 8.4)</label>
                        <div>
                            <input id="arquivoProficiencia" name="Anexos[]" class="element file" type="file"/> 
                            <p id="PROFICIENCIA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_30"><small>Obrigatório que esteja no formato PDF e tamanho máximo de 2Mb, sendo que será aceito apenas 1 certificado para o mestrado e 2 para o doutorado</small></p> 
                    </li>
                    <li id="liVinculoEmpregaticio" >
                        <label class="description" for="optVinculoEmpregaticio">Vínculo Empregatício:<span class="required">*</span></label>
                        <div>
                            <select class="element select medium" required id="optVinculoEmpregaticio" name="optVinculoEmpregaticio"> 
                                <option value="" ></option>
                                <option value="0" >Não</option>
                                <option value="1" >Sim</option>                                
                            </select>
                        </div> <p class="guidelines" id="guide_31"><small>Informe se você possui ou não vínculo empregatício</small></p> 
                    </li>
                    <li id="litxtNomeInstituicao" style="display: none;">
                        <label class="description" for="txtNomeInstituicao">Instituição / Empresa:</label>
                        <div>
                            <input id="txtNomeInstituicao" name="txtNomeInstituicao" class="element text large" type="text" maxlength="200" value=""/> 
                        </div><p class="guidelines" id="guide_16"><small>Informe nome da Instituição / Empresa que está vinculado atualmente</small></p> 
                    </li>

                    <li class="buttons">
                        <input type="hidden" name="form_id" value="97971" />

                        <input id="saveForm" class="button_text" type="submit" name="submit" value="Enviar solicitação de inscrição" />
                    </li>
                </ul>-->
            </form>
            <!--div id="footer">
                Generated by <a href="http://www.phpform.org">pForm</a>
            </div-->
        </div>
        <img id="bottom" src="images/bottom.png" alt="">
    </body>
</html>