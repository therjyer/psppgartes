<!--?php
// put your code here

if (session_status() !== PHP_SESSION_ACTIVE)
    {
    session_start();
    }

if ($_SESSION['tipoinscrito'] == '1')
    {
    $tipoTitulo = "Mestrado";
    } else
    {
    $tipoTitulo = "Doutorado";
    }

$tipoinscrito = $_SESSION['tipoinscrito'];
?-->
<?php
include("seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
// include("dbconnect.inc.php");
protegePagina(); // Chama a fun��o que protege a p�gina
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

    <head>
        <?php include "head.html"; ?>
        <!-- MERGE ENTRE O FORMULÁRIO DE INSCRITOS COM OS TEMPLATES ORIOGINAIS DO PSPPGARTES -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="../css/view.css" media="all">
        <script type="text/javascript" src="../ferramentas/js/view.js"></script>
        <script type="text/javascript" src="../ferramentas/js/calendar.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script src="../ferramentas/js/valida_cpf_cnpj.js"></script>
        <script>

            /*
             * 
             *  VALIDA CEP
             */
            $(function () {
                $("#txtCPF").on("change", function () {
                    //Verificar o tamanho
                    var cpf_cnpj = $(this).val();
                    if (!valida_cpf_cnpj(cpf_cnpj)) {
                        $('#CPF_DOWN').text('CPF inv�lido');
                        this.value = "";
                    } else {
                        $('#CPF_DOWN').text(' ');
                    }

                });

                /*
                 * CONFIGURA DROPDOWN ORIENTADORES
                 */
                //$("#drpLinha").change(function () {
                $("#drpLinha").change(function () {
                    //$("#drpOrientador").load("textdata/" + $(this).val() + ".txt");
                    //var tipoInscrito = <?php echo json_encode($tipoTitulo); ?>;
                    var strCaminho = "../ferramentas/textdata/" + <?php echo json_encode($tipoTitulo); ?> + +$(this).val() + ".txt";
                    //if (tipoInscrito === "Mestrado") 
                    $("#drpOrientador").load(strCaminho);
                    $("#drpOrientadorOpcional").load(strCaminho);
                    /*
                     switch (tipoInscrito) {
                     case 'Mestrado':                        
                     {
                     //$("#drpOrientador").load("../ferramentas/textdata/M" + $(this).val() + ".txt");
                     $("#drpOrientador").load(strCaminho);
                     $("#drpOrientadorOpcional").load(strCaminho);
                     $('#Orientador1l').text("../ferramentas/textdata/M" + $(this).val() + ".txt");
                     $('#Orientador2l').text(strCaminho);
                     } //else
                     case 'Doutorado':
                     {
                     $("#drpOrientador").load("strCaminho);
                     $("#drpOrientadorOpcional").load(strCaminho);
                     $('#Orientador1l').text("../ferramentas/textdata/D" + $(this).val() + ".txt");
                     $('#Orientador2l').text(strCaminho);
                     }
                     
                     }
                     */
                }); // $("#drpLinha").change

                /*
                 
                 
                 $("#json-one").change(function () {
                 
                 var $dropdown = $(this);
                 $.getJSON("../ferramentas/jsondata/data.json", function (data) {
                 
                 var key = $dropdown.val();
                 var vals = [];
                 switch (key) {
                 case '1':
                 vals = data.beverages.split(",");
                 break;
                 case '2':
                 vals = data.beverages.split(",");
                 break;
                 case '3':
                 vals = data.beverages.split(",");
                 break;
                 case '0':
                 //vals = ['Escolha a Linha antes'];
                 vals = data.snacks.split(",");
                 }
                 
                 var $jsontwo = $("#json-two");
                 $jsontwo.empty();
                 $.each(vals, function (index, value) {
                 $jsontwo.append("<option>" + value + "</option>");
                 });
                 });
                 }); //$("#json-one").change
                 */
                /*
                 * ARQUIVO ETNIA
                 */
                $("#fileAutodeclara��oDeEtnia").on("change", function () {
                    //Verificar o tamanho

                    if (this.files[0].size > 2000000) {
                        $('#ETNIA_DOWN').text('O Arquivo ultrassa 2Mb');
                        this.value = "";
                    } else {
                        $('#ETNIA_DOWN').text(' ');
                    }

                    var fileName = this.files[0].name;
                    var fileExt = fileName.substring(fileName.length - 3);
                    if (fileExt !== 'pdf') {
                        $('#ETNIA_DOWN').text('O Arquivo n�o � PDF');
                        this.value = "";

                    } else {
                        $('#ETNIA_DOWN').text(' ');
                    }

                });

                /*
                 * ARQUIVO LAUDO M�DICO
                 */
                $("#fileLaudoMedico").on("change", function () {
                    //Verificar o tamanho

                    if (this.files[0].size > 2000000) {
                        $('#LAUDO_DOWN').text('O Arquivo ultrassa 2Mb');
                        this.value = "";
                    } else {
                        $('#LAUDO_DOWN').text(' ');
                    }

                    var fileName = this.files[0].name;
                    var fileExt = fileName.substring(fileName.length - 3);
                    if (fileExt !== 'pdf') {
                        $('#LAUDO_DOWN').text('O Arquivo n�o � PDF');
                        this.value = "";

                    } else {
                        $('#LAUDO_DOWN').text(' ');
                    }

                });
                /*
                 * ARQUIVO CONTRACHEQUE
                 */
                $("#fileContraCheque").on("change", function () {
                    //Verificar o tamanho

                    if (this.files[0].size > 2000000) {
                        $('#CHEQUE_DOWN').text('O Arquivo ultrassa 2Mb');
                        this.value = "";
                    } else {
                        $('#CHEQUE_DOWN').text(' ');
                    }

                    var fileName = this.files[0].name;
                    var fileExt = fileName.substring(fileName.length - 3);
                    if (fileExt !== 'pdf') {
                        $('#CHEQUE_DOWN').text('O Arquivo n�o � PDF');
                        this.value = "";

                    } else {
                        $('#CHEQUE_DOWN').text(' ');
                    }

                });
                /*
                 * ARQUIVO DIPLOMA
                 */
                $("#arquivoDiploma").on("change", function () {
                    //Verificar o tamanho

                    if (this.files[0].size > 2000000) {
                        $('#DIPLOMA_DOWN').text('O Arquivo ultrassa 2Mb');
                        this.value = "";
                    } else {
                        $('#DIPLOMA_DOWN').text(' ');
                    }

                    var fileName = this.files[0].name;
                    var fileExt = fileName.substring(fileName.length - 3);
                    if (fileExt !== 'pdf') {
                        $('#DIPLOMA_DOWN').text('O Arquivo n�o � PDF');
                        this.value = "";

                    } else {
                        $('#DIPLOMA_DOWN').text(' ');
                    }

                }); //$("#arquivoDiploma").on("change"

                /*
                 * ARQUIVO HIST�RICO
                 */
                $("#arquivoHistorico").on("change", function () {
                    //Verificar o tamanho

                    if (this.files[0].size > 2000000) {
                        $('#HISTORICO_DOWN').text('O Arquivo ultrassa 2Mb');
                        this.value = "";
                    } else {
                        $('#HISTORICO_DOWN').text(' ');
                    }

                    var fileName = this.files[0].name;
                    var fileExt = fileName.substring(fileName.length - 3);
                    if (fileExt !== 'pdf') {
                        $('#HISTORICO_DOWN').text('O Arquivo n�o � PDF');
                        this.value = "";

                    } else {
                        $('#HISTORICO_DOWN').text(' ');
                    }

                }); //$("#arquivoHistorico").on("change"

                /*
                 * ARQUIVO RG
                 */
                $("#arquivoRg").on("change", function () {
                    //Verificar o tamanho

                    if (this.files[0].size > 2000000) {
                        $('#RGA_DOWN').text('O Arquivo ultrassa 2Mb');
                        this.value = "";
                    } else {
                        $('#RGA_DOWN').text(' ');
                    }

                    var fileName = this.files[0].name;
                    var fileExt = fileName.substring(fileName.length - 3);
                    if (fileExt !== 'pdf') {
                        $('#RGA_DOWN').text('O Arquivo n�o � PDF');
                        this.value = "";

                    } else {
                        $('#RGA_DOWN').text(' ');
                    }

                }); //$("#arquivoRg").on("change",

                /*
                 * ARQUIVO CPF
                 */
                $("#arquivoCpf").on("change", function () {
                    //Verificar o tamanho

                    if (this.files[0].size > 2000000) {
                        $('#CPFA_DOWN').text('O Arquivo ultrassa 2Mb');
                        this.value = "";
                    } else {
                        $('#CPFA_DOWN').text(' ');
                    }

                    var fileName = this.files[0].name;
                    var fileExt = fileName.substring(fileName.length - 3);
                    if (fileExt !== 'pdf') {
                        $('#CPFA_DOWN').text('O Arquivo n�o � PDF');
                        this.value = "";

                    } else {
                        $('#CPFA_DOWN').text(' ');
                    }

                }); //$("#arquivoCpf").on("change"

                /*
                 * ARQUIVO GRU
                 */
                $("#arquivoGru").on("change", function () {
                    //Verificar o tamanho

                    if (this.files[0].size > 2000000) {
                        $('#GRU_DOWN').text('O Arquivo ultrassa 2Mb');
                        this.value = "";
                    } else {
                        $('#GRU_DOWN').text(' ');
                    }

                    var fileName = this.files[0].name;
                    var fileExt = fileName.substring(fileName.length - 3);
                    if (fileExt !== 'pdf') {
                        $('#GRU_DOWN').text('O Arquivo n�o � PDF');
                        this.value = "";

                    } else {
                        $('#GRU_DOWN').text(' ');
                    }

                }); //$("#arquivoGru").on("change"

                /*
                 * ARQUIVO CONCOD�NCIA
                 */
                $("#arquivoConcordancia").on("change", function () {
                    //Verificar o tamanho

                    if (this.files[0].size > 2000000) {
                        $('#CONCORDANCIA_DOWN').text('O Arquivo ultrassa 2Mb');
                        this.value = "";
                    } else {
                        $('#CONCORDANCIA_DOWN').text(' ');
                    }

                    var fileName = this.files[0].name;
                    var fileExt = fileName.substring(fileName.length - 3);
                    if (fileExt !== 'pdf') {
                        $('#CONCORDANCIA_DOWN').text('O Arquivo n�o � PDF');
                        this.value = "";

                    } else {
                        $('#CONCORDANCIA_DOWN').text(' ');
                    }

                }); //$("#arquivoConcordancia").on("change"

                /*
                 * ARQUIVO ANU�NCIA
                 */

                $("#arquivoAnuencia").on("change", function () {
                    //Verificar o tamanho

                    if (this.files[0].size > 2000000) {
                        $('#ANUENCIA_DOWN').text('O Arquivo ultrassa 2Mb');
                        this.value = "";
                    } else {
                        $('#ANUENCIA_DOWN').text(' ');
                    }

                    var fileName = this.files[0].name;
                    var fileExt = fileName.substring(fileName.length - 3);
                    if (fileExt !== 'pdf') {
                        $('#ANUENCIA_DOWN').text('O Arquivo n�o � PDF');
                        this.value = "";

                    } else {
                        $('#ANUENCIA_DOWN').text(' ');
                    }

                });//$("#arquivoAnuencia").on("change" 




                /*
                 * ARQUIVO PROFICI�NCIA 1
                 */
                $("#arquivoProficiencia").on("change", function () {
                    //Verificar o tamanho

                    if (this.files[0].size > 2000000) {
                        $('#PROFICIENCIA_DOWN').text('O Arquivo ultrassa 2Mb');
                        this.value = "";
                    } else {
                        $('#PROFICIENCIA_DOWN').text(' ');
                    }

                    var fileName = this.files[0].name;
                    var fileExt = fileName.substring(fileName.length - 3);
                    if (fileExt !== 'pdf') {
                        $('#PROFICIENCIA_DOWN').text('O Arquivo n�o � PDF');
                        this.value = "";

                    } else {
                        $('#PROFICIENCIA_DOWN').text(' ');
                    }

                }); //$("#arquivoProficiencia").on("change"

                /*
                 * ARQUIVO PROFICI�NCIA 2
                 */
                $("#arquivoProficiencia2").on("change", function () {
                    //Verificar o tamanho

                    if (this.files[0].size > 2000000) {
                        $('#PROFICIENCIA2_DOWN').text('O Arquivo ultrassa 2Mb');
                        this.value = "";
                    } else {
                        $('#PROFICIENCIA2_DOWN').text(' ');
                    }

                    var fileName = this.files[0].name;
                    var fileExt = fileName.substring(fileName.length - 3);
                    if (fileExt !== 'pdf') {
                        $('#PROFICIENCIA2_DOWN').text('O Arquivo n�o � PDF');
                        this.value = "";

                    } else {
                        $('#PROFICIENCIA2_DOWN').text(' ');
                    }

                }); //$("#arquivoProficiencia").on("change"

                $("#txtVisto").on("change", function () {
                    //Verificar o tamanho
                    var vistoValor = $(this).val();
                    if (vistoValor === "") {
                        document.getElementById("dtVisto").style.display = "none";
                        document.getElementById("txtEndereco").focus();

                    } else {
                        document.getElementById("dtVisto").style.display = "block";
                        document.getElementById("dtInicioVigenciaVisto_1").focus();
                    }
                }); //$("#txtCPF").on("change"
                $("#optAtendimentoEspecial").on("change", function () {
                    //Verificar o tamanho
                    var atendimentoValor = $(this).val();
                    if (atendimentoValor === "0") {
                        document.getElementById("txtEspecial").style.display = "none";
                        //RETIRADO NO PSPPGARTES 2021
                        //document.getElementById("txtLocaldeProva").focus();

                    } else {
                        document.getElementById("txtEspecial").style.display = "block";
                        document.getElementById("txtEspecial").focus();
                    }
                }); //$("#txtCPF").on("change"
                $("#optVinculoEmpregaticio").on("change", function () {
                    //Verificar o tamanho
                    var atendimentoValor = $(this).val();
                    if (atendimentoValor === "0") {
                        document.getElementById("txtNomeInstituicao").style.display = "none";
                        document.getElementById("txtNomeInstituicao").focus();

                    } else {
                        document.getElementById("txtNomeInstituicao").style.display = "block";
                    }
                }); //$("#optVinculoEmpregaticio").on("change"

            }); // $(function () {
        </script>
    </head>
    <!--body id="main_body" -->
    <body class="bg-fixed bg-1">
        <!--img id="top" src="top.png" alt=""-->
        <div id="main-container">
            <form id="form_editarCandidato" class="appnitro" enctype="multipart/form-data" method="post" action="../processo/atualizarCandidato.php">
                <div class="form_description">
                    <p>Formulário de Solicitação de Inscrição - Edital 2021 ( <?php echo $tipoTitulo; ?> )  
                </div>						
                <ul >

                    <li id="LinkCvLattes" >
                        <label class="description" for="txtLinkCvLattes">Endere�o para acessar CV (plataforma lattes CNPq):<span class="required">
                        </label>
                        <div>
                            <input id="txtLinkCvLattes" name="txtLinkCvLattes" class="element text medium"  required type="text" maxlength="255"  value="http://"/> 
                        </div><p class="guidelines" id="guide_1"><small>informar o link v�lido para o seu CV</small></p> 
                    </li>		
                    <li id="Nome" >
                        <label class="description" for="txtNome">Nome Completo:<span class="required">*</span>
                        </label>
                        <div>
                            <input id="txtNome" name="txtNome" class="element text large"  required type="text" maxlength="255"  value=""/> 
                        </div> 
                    </li>		
                    <li id="Nacionalidade" >
                        <label class="description" for="txtNacionalidade">Nacionalidade</label>
                        <div>
                            <input id="txtNacionalidade" name="txtNacionalidade" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div> 
                    </li>		
                    <li id="Naturalidade" >
                        <label class="description" for="txtNaturalidade">Naturalidade:</label>
                        <div>
                            <input id="txtNaturalidade" name="txtNaturalidade" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div> 
                    </li>		
                    <li id="dtNascimento" >
                        <label class="description" for="dtNascimento">Data de Nascimento
                        </label>
                        <span>
                            <input id="dtNascimento_1" name="dtNascimento_1" class="element text" size="2" maxlength="2" min="1" max="31"  required value="" type="number"> /
                            <label for="dtNascimentoDia">DD</label>
                        </span>
                        <span>
                            <input id="dtNascimento_2" name="dtNascimento_2" class="element text" size="2" maxlength="2" min="1" max="12"  required value="" type="number"> /
                            <label for="dtNascimentoMes">MM</label>
                        </span>
                        <span>
                            <input id="dtNascimento_3" name="dtNascimento_3" class="element text" size="4" maxlength="4" min="1920" max="2021"   required value="" type="number">
                            <label for="dtNascimentoAno">AAAA</label>
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
                    <li id="RG" >
                        <label class="description" for="txtNumRG">RG:</label>
                        <div>
                            <input id="txtNumRG" name="txtNumRG" class="element text small"  required type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_6"><small>Informar o n�mero do seu documento de identifica��o</small></p> 
                    </li>		
                    <li id="EmissorRG" >
                        <label class="description" for="txtEmissorRg">�rg�o Expeditor:</label>
                        <div>
                            <input id="txtEmissorRg" name="txtEmissorRg" class="element text small"  required type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_7"><small>Informar o �rg�o emissor do seu documento de identidade</small></p> 
                    </li>		
                    <li id="dtRg" >
                        <label class="description" for="dtRg">Data de Emiss�o:</label>
                        <span>
                            <input id="dtRg_1" name="dtRg_1" class="element text" size="2" maxlength="2" min="1" max="31"  required value="" type="number"> /
                            <label for="dtRg_1">DD</label>
                        </span>
                        <span>
                            <input id="dtRg_2" name="dtRg_2" class="element text" size="2" maxlength="2" min="1" max="12"  required value="" type="number"> /
                            <label for="dtRg_2">MM</label>
                        </span>
                        <span>
                            <input id="dtRg_3" name="dtRg_3" class="element text" size="4" maxlength="4" min="1920" max="2021"   required value="" type="number">
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
                        <p class="guidelines" id="guide_8"><small>Informar a data da emiss�o do seu documento de identifica��o</small></p> 
                    </li>		
                    <li id="CPF" >
                        <label class="description" for="txtCPF">CPF:</label>
                        <div>
                            <input id="txtCPF" name="txtCPF" class="cpf_cnpj"  required type="text" maxlength="11" value=""/> 
                            <p id="CPF_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p> 
                            <label id="lblValidarCPF" hidden="false" ></label>
                        </div><p class="guidelines" id="guide_10"><small>Digite somente     n�meros sem "." ou "-"</small></p> 
                    </li>		
                    <li id="Visto" >
                        <label class="description" for="txtVisto">Visto Permanente (preencher apenas se for estrangeiro):</label>
                        <div>
                            <input id="txtVisto" name="txtVisto" class="element text medium"   type="text" maxlength="255" value=""/> 
                        </div> 
                    </li>
                    <div id ="dtVisto" style="display: none;" >
                        <li id="dtInicioVigenciaVisto" >
                            <label class="description" for="dtInicioVigenciaVisto">In�cio Per�odo de Vig�ncia:</label>
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
                        <li id="dtTerminoVigenciaVisto" >
                            <label class="description" for="dtTerminoVigenciaVisto">T�rmino Per�odo de Vig�ncia:</label>
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
                    <li id="Endere�o" >
                        <label class="description" for="txtEndereco">Endere�o</label>
                        <div>
                            <textarea id="txtEndereco" name="txtEndereco" required class="element textarea medium"></textarea> 
                        </div> 
                    </li>		
                    <li id="Telefone" >
                        <label class="description" for="txtTelefone">Telefone</label>
                        <div>
                            <input id="txtTelefone" name="txtTelefone" class="element text medium"  type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_11"><small>informar no formato (##) #####-####</small></p> 
                    </li>		
                    <li id="Celular" >
                        <label class="description" for="txtCelular">Celular:</label>
                        <div>
                            <input id="txtCelular" name="txtCelular" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_12"><small>informar no formato (##) #####-####</small></p> 
                    </li>		
                    <li id="Email" >
                        <label class="description" for="txtEmail">E-mail</label>
                        <div>
                            <input id="txtEmail" name="txtEmail" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_13"><small>Informe um e-mail v�lido para contato</small></p> 
                    </li>		
                    <li id="bolAtendimentoEspecial" >
                        <label class="description" for="bolAtendimentoEspecial">Necessita de Atendimento Especial para a realiza��o da prova?
                        </label>
                        <div>
                            <select class="element select medium" required value=''  id="optAtendimentoEspecial" name="optAtendimentoEspecial"> 
                                <option value="" ></option>
                                <option value="0" >N�o</option>
                                <option value="1" >Sim</option>                                
                            </select>
                        </div> 
                    <li id="txtEspecial" style="display: none;">
                        <label class="description" for="txtEspecial">Especificar atendimento especial:</label>
                        <div>
                            <input id="txtEspecial" name="txtEspecial" class="element text large"  type="text" maxlength="255" value=""/> 
                        </div><p class="guidelines" id="guide_13"><small>Especificar qual o atendimkento especial</small></p> 
                    </li>

                    <!--span>
                    <!--input id="bolAtendimentoEspecial_1" name="bolAtendimentoEspecial" class="element checkbox" type="checkbox" value="1" />
                    <label class="choice" for="bolAtendimentoEspecial_1">SIM</label>
                </span--> 
                    </li>
                    <!--
                    RETIRADO NO PSPPGARTES2021		
                    <li id="LocaldeProva" >
                        <label class="description" for="txtLocaldeProva">Op��o de Local/Provas presenciais:</label>
                        <div>
                            <input id="txtLocaldeProva" name="txtLocaldeProva" class="element text medium"  required type="text" maxlength="255" value="PPG BEL�M"/> 
                        </div><p class="guidelines" id="guide_31"><small>N�o preencher caso seja no PPGARTES/UFPA (PPG BEL�M)</small></p> 
                    </li>
                    -->		
                    <li id="Autodeclara��oDeEtnia" >
                        <label class="description" for="fileAutodeclara��oDeEtnia">Autodeclara��o de etnia / pessoas trans (item 2.4 e anexo7)</label>
                        <div>
                            <input id="fileAutodeclara��oDeEtnia" name="Anexos[]" class="element file" type="file"/> 
                            <p id="ETNIA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_15"><small>Obrigat�rio que esteja no formato PDF</small></p> 
                    </li>
                    <li id="LaudoMedico" >
                        <label class="description" for="fileLaudoMedico">Laudo M�dico (item 2.4)</label>
                        <div>
                            <input id="fileLaudoMedico" name="Anexos[]" class="element file" type="file"/> 
                            <p id="LAUDO_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_41"><small>Obrigat�rio que esteja no formato PDF</small></p> 
                    </li>
                    <li id="ContraCheque" >
                        <label class="description" for="fileContraCheque">Anexar o �ltimo contracheque - Vaga PADT (item 2.4)</label>
                        <div>
                            <input id="fileContraCheque" name="Anexos[]" class="element file" type="file"/> 
                            <p id="CHEQUE_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_43"><small>Obrigat�rio que esteja no formato PDF</small></p> 
                    </li>
                    <li id="NomeEnsinoSuperior" >
                        <label class="description" for="txtNomeEnsinoSuperior">Institui��o de Ensino Superior :</label>
                        <div>
                            <input id="txtNomeEnsinoSuperior" name="txtNomeEnsinoSuperior" class="element text large"  required type="text" maxlength="200" value=""/> 
                        </div><p class="guidelines" id="guide_16"><small>Informe nome por extenso</small></p> 
                    </li>
                    <li id="SiglaEnsinoSuperior" >
                        <label class="SiglaEnsinoSuperior" for="txtSiglaEnsinoSuperior">Sigla da Institui��o:</label>
                        <div>
                            <input id="txtSiglaEnsinoSuperior" name="txtSiglaEnsinoSuperior" class="element text small"  required type="text" maxlength="20" value=""/> 
                        </div> 
                    </li>		
                    <li id="Curso" >
                        <label class="description" for="txtCurso">Curso:</label>
                        <div>
                            <input id="txtCurso" name="txtCurso" class="element text large"  required type="text" maxlength="255" value=""/> 
                        </div> 
                    </li>		
                    <li id="Titulo" >
                        <label class="description" for="txtTitulo">T�tulo obtido:</label>
                        <div>
                            <input id="txtTitulo" name="txtTitulo" class="element text medium"  required type="text" maxlength="255" value=""/> 
                        </div> 
                    </li>		
                    <li id="dtInicioCurso" >
                        <label class="description" for="dtInicioCurso">In�cio do curso:</label>
                        <span>
                            <input id="dtInicioCurso_1" name="dtInicioCurso_1" class="element text" size="2" maxlength="2" min="1" max="31"  required value="" type="number"> /
                            <label for="dtInicioCurso_1">DD</label>
                        </span>
                        <span>
                            <input id="dtInicioCurso_2" name="dtInicioCurso_2" class="element text" size="2" maxlength="2" min="1" max="12"  required value="" type="number"> /
                            <label for="dtInicioCurso_2">MM</label>
                        </span>
                        <span>
                            <input id="dtInicioCurso_3" name="dtInicioCurso_3" class="element text" size="4" maxlength="4" min="1920" max="2021"   required value="" type="number">
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
                    <li id="dtTerminoCurso" >
                        <label class="description" for="dtTerminoCurso">T�rmino do curso:</label>
                        <span>
                            <input id="dtTerminoCurso_1" name="dtTerminoCurso_1" class="element text" size="2" maxlength="2" min="1" max="31"  required value="" type="number"> /
                            <label for="dtTerminoCurso_1">DD</label>
                        </span>
                        <span>
                            <input id="dtTerminoCurso_2" name="dtTerminoCurso_2" class="element text" size="2" maxlength="2" min="1" max="12"  required value="" type="number"> /
                            <label for="dtTerminoCurso_2">MM</label>
                        </span>
                        <span>
                            <input id="dtTerminoCurso_3" name="dtTerminoCurso_3" class="element text" size="4" maxlength="4" min="1920" max="2021"   required value="" type="number">
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
                    <li id="TituloProjeto" >
                        <label class="description" for="txtTituloProjeto">T�tulo do Projeto Proposto para o <?php echo $tipoTitulo; ?>:</label>
                        <div>
                            <textarea id="txtTituloProjeto" name="txtTituloProjeto" required class="element textarea medium"></textarea> 
                        </div> 
                    </li>		
                    <li id="optCampo" >
                        <label class="description" for="optCampo">Selecione o Campo de Atua��o</label>
                        <div>
                            <select class="element select medium" required id="optCampo" name="optCampo"> 
                                <option value="" selected="selected"></option>
                                <option value="1" >Teatro</option>
                                <option value="2" >M�sica</option>
                                <option value="3" >Dan�a</option>
                                <option value="4" >Artes Visuais</option>
                                <option value="5" >Cinema</option>
                            </select>
                        </div> 
                    </li>		
                    <li id="optLinhaPesquisa" >
                        <label class="description" for="optLinhaPesquisa">Selecione sua linha de pesquisa</label>
                        <div>
                            <select id="drpLinha" required name="optLinhaPesquisa">
                                <!--select class="element select medium" required id="optLinhaPesquisa" name="optLinhaPesquisa"--> 
                                <option selected value="0">Selecionar Linha</option>
                                <option value="1">Po�ticas e processos de atua��o em artes</option>
                                <option value="2">Teorias e Interfaces Epistemicas em Artes</option>
                                <option value="3">Mem�rias, Hist�rias e Educa��o em Artes</option>
                            </select>
                            <!--select class="element select medium" required id="optLinhaPesquisa" name="optLinhaPesquisa"> 
                                <option value="0" selected="Selecionar Linha"></option>
                                <option value="1" >Linha 01</option>
                                <option value="2" >Linha 02</option>
                                <option value="3" >Linha 03</option>

                            </select-->
                        </div> 
                    </li>		
                    <li  id="Orientador1"  >
                        <label id="Orientador1l" class="description" for="optOrientador1">Orientador(a) (1� Op��o)</label>
                        <div>
                            <!--select id="drpOrientador" name="optOrientador1"--> 
                            <select class="element select medium" required id="drpOrientador" name="optOrientador1"> 
                                <option></option>
                            </select>
                            <!--select class="element select medium" required id="optOrientador1" name="optOrientador1" hidden="True"> 
                                <option value="" selected="selected"></option>
                                <option value="1" >LP1 - Ana Fl�via Mendes Sapucahy</option>
                                <option value="2" >LP1 - Ces�rio Augusto de Alencar</option>
                                <option value="3" >LP1 - Iara Regina da Silva Souza</option>
                                <option value="4" >LP1 - Valzeli Figueira Sampaio</option>
                                <option value="5" >LP1 - Orlando Maneschy</option>

                            </select-->
                        </div> 
                    </li>		
                    <li id="Orientador2" >
                        <label id="Orientador2l" class="description" for="optOrientador2">Orientador(a) (2� Op��o)</label>
                        <div>
                            <select id="drpOrientadorOpcional" name="optOrientador2">
                                <option Value = ""></option>
                            </select>
                            <!--select class="element select medium" required id="optOrientador2" name="optOrientador2" hidden="True"> 
                                <option value="" selected="selected"></option>
                                <option value="1" >LP1 - Ana Fl�via Mendes Sapucahy</option>
                                <option value="2" >LP1 - Ces�rio Augusto de Alencar</option>
                                <option value="3" >LP1 - Iara Regina da Silva Souza</option>
                                <option value="4" >LP1 - Valzeli Figueira Sampaio</option>
                                <option value="5" >LP1 - Orlando Maneschy</option>
                                <option value="6" >LP2 - Ana Cl�udia Le�o</option>

                            </select-->
                        </div> 
                    </li>		
                    <li id="ArquivoDiploma" >
                        <label class="description" for="arquivoDiploma">Diploma (Conforme subitem 3.2.1.1):</label>
                        <div>
                            <input id="arquivoDiploma" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="DIPLOMA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_23"><small>Obrigat�rio que esteja no formato PDF</small></p> 
                    </li>		
                    <li id="ArquivoHistorico" >
                        <label class="description" for="ArquivoHistorico">Hist�rico (Conforme subitem 3.2.1.1) :</label>
                        <div>
                            <input id="arquivoHistorico" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="HISTORICO_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_24"><small>Obrigat�rio que esteja no formato PDF</small></p> 
                    </li>		
                    <li id="ArquivoRG" >
                        <label class="description" for="arquivoRg">RG (Conforme subitem 3.2.1.2):</label>
                        <div>
                            <input id="arquivoRg" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="RGA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_25"><small>Obrigat�rio que esteja no formato PDF</small></p> 
                    </li>		
                    <li id="ArquivoCPF" >
                        <label class="description" for="arquivoCpf">CPF (Conforme subitem 3.2.1.2):</label>
                        <div>
                            <input id="arquivoCpf" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="CPFA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_26"><small>Obrigat�rio que esteja no formato PDF</small></p> 
                    </li>		
                    <li id="ArquivoGRU" >
                        <label class="description" for="arquivoGru">GRU e Comprovante de Pagamento (Conforme subitem 3.2.1.3)</label>
                        <div>
                            <input id="arquivoGru" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="GRU_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_27"><small>Obrigat�rio que esteja no formato PDF</small></p> 
                    </li>		
                    <li id="ArquivoConcordancia" >
                        <label class="description" for="arquivoConcordancia">Declara��o de Concord�ncia (Conforme subitem 3.2.1.4)</label>
                        <div>
                            <input id="arquivoConcordancia" name="Anexos[]" required class="element file" type="file"/> 
                            <p id="CONCORDANCIA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_28"><small>Obrigat�rio que esteja no formato PDF</small></p> 
                    </li>
                    <!--
                    RETIRADO NO PSPPGARTES 2021		
                    <li id="ArquivoAnuencia" >
                        <label class="description" for="arquivoAnuencia">Declara��o de Anu�ncia (Conforme subitem 3.2.1.5)</label>
                        <div>
                            <input id="arquivoAnuencia" name="Anexos[]"  class="element file" type="file"/> 
                            <p id="ANUENCIA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_29"><small>Obrigat�rio que esteja no formato PDF</small></p> 
                    </li>
                    --> 		
                    <li id="ArquivoProficiencia" >
                        <label class="description" for="element_30">Comprovante 1 de profici�ncia em l�ngua estrangeira  (Conforme subitem 3.2.1.6)</label>
                        <div>
                            <input id="arquivoProficiencia" name="Anexos[]" class="element file" type="file"/> 
                            <p id="PROFICIENCIA_DOWN" style="color:red; font-size: 12px; font-weight: bold;"></p>
                        </div> <p class="guidelines" id="guide_30"><small>Obrigat�rio que esteja no formato PDF</small></p> 
                    </li>
                    <?php
                    //Exibe somente para Doutorado

                    if ($tipoinscrito == '2')
                        {
                        echo "<li id='ArquivoProficiencia2' >" .
                        "<label class='description' for='element_30'>Comprovante 2 de profici�ncia em l�ngua estrangeira  (Conforme subitem 3.2.1.6)</label> " .
                        "<div>" .
                        "<input id='arquivoProficiencia2' name='Anexos[]' class='element file' type='file'/>" .
                        "<p id='PROFICIENCIA2_DOWN' style='color:red; font-size: 12px; font-weight: bold;'></p> " .
                        "</div> <p class='guidelines' id='guide_32'><small>Obrigat�rio que esteja no formato PDF</small></p>" .
                        "</li>";
                        }
                    ?>
                    <li id="VinculoEmpregaticio" >
                        <label class="description" for="bolVinculoEmpregaticio">V�nculo Empregat�cio:</label>
                        <div>
                            <select class="element select medium" required id="optVinculoEmpregaticio" name="optVinculoEmpregaticio"> 
                                <option value="" ></option>
                                <option value="0" >N�o</option>
                                <option value="1" >Sim</option>                                
                            </select>
                        </div> <p class="guidelines" id="guide_31"><small>Informar com SIM se possui v�culo empregat�cio</small></p> 
                        <!--span>
                            <input id="element_33_1" name="element_33_1" class="element checkbox" type="checkbox" value="1" />
                            <label class="choice" for="element_33_1">SIM</label>
                        </span--> 
                    </li>
                    <li id="txtNomeInstituicao" style="display: none;">
                        <label class="description" for="txtNomeInstituicao">Institui��o / Empresa:</label>
                        <div>
                            <input id="txtNomeInstituicao" name="txtNomeInstituicao" class="element text large" type="text" maxlength="200" value=""/> 
                        </div><p class="guidelines" id="guide_16"><small>Informe nome da Institui��o / Empresa que est� vinculado atualmente</small></p> 
                    </li>

                    <li class="buttons">
                        <input type="hidden" name="form_id" value="97971" />

                        <input id="saveForm" class="button_text" type="submit" name="submit" value="Enviar solicita��o de inscri��o" />
                    </li>
                </ul>
            </form>	
            <!--div id="footer">
                Generated by <a href="http://www.phpform.org">pForm</a>
            </div-->
        </div>
        <img id="bottom" src="bottom.png" alt="">
    </body>
</html>