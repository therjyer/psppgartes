<?php
//NOME OLD pselecao.php
include("seguranca.php"); // Inclui o arquivo com o sistema de seguran�a

protegePagina(); // Chama a fun��o que protege a p�gina
?>
<!DOCTYPE html>
<html class="no-js"> <!--<![endif]-->
    <head>
        <?php include "head.html"; ?>
    </head>

    <?php include ("header.php"); ?>

    <body class="bg-fixed bg-1">
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
            <![endif]-->
        <div class="main-container">
            <div class="main wrapper clearfix">

                <!-- Main Tab Container -->
                <div id="tab-container">
                    <?php
                    $ind = 0;
                    $user = 0;
                    $cand = 0;
                    $ava = 1;
                    $perf = 0;

                    $idProcesso = (isset($_GET['idProcesso'])) ? $_GET['idProcesso'] : '';
                    //$consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso"); php5
                    $consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
                    $row = mysqli_fetch_array($consulta);

                    $processo = $row['processo'];
                    $nomeProfessor = $_SESSION['usuarioNome'];
                    //$consulta2 = mysqli_query($_SG['link'], "SELECT u.tipo FROM usuarios u WHERE u.nome = '$nomeProfessor' AND u.estado=1"); php5
                    $consulta2 = mysqli_query($_SG['link'], "SELECT u.tipo FROM usuarios u WHERE u.nome = '$nomeProfessor' AND u.estado=1");
                    $row2 = mysqli_fetch_array($consulta2);
                    $idTipo = $row2['tipo'];
                    ?>
                    <!-- Tab List -->
                    <?php include ("menu.php"); ?>
                    <!-- End Tab List -->

                    <div id="tab-data-wrap">

                        <!-- About Tab Data -->
                        <div id="usuarios">
                            <section class="clearfix">
                                <div class="g3">
                                    <div>
                                        <div class="info">
                                            <h4>
                                                <?php
                                                if ($idTipo == 3)
                                                    {
                                                    echo "Processo Seletivo $processo - Professor";
                                                    } else
                                                    {
                                                    echo "Processo Seletivo - Doutorado $processo";
                                                    }
                                                ?>
                                            </h4>
                                        </div>

                                        <?php
                                        //= mysqli_query($_SG['link'], "SELECT p.inscInicio, p.inscFim, p.homologacaoInicio, p.homologacaoFim, p.provaInicio, p.provaFim, p.projetoInicio, p.projetoFim, p.entrevistaInicio, p.entrevistaFim, p.curriculoInicio, p.curriculoFim, p.profileInicio, p.profileFim, p.resultado  FROM procseletivo p WHERE p.idProcesso = $idProcesso") php5
                                        $result = mysqli_query($_SG['link'], "SELECT p.inscInicio, p.inscFim, p.homologacaoInicio, p.homologacaoFim, p.provaInicio, p.provaFim, p.projetoInicio, p.projetoFim, p.entrevistaInicio, p.entrevistaFim, p.curriculoInicio, p.curriculoFim, p.profileInicio, p.profileFim, p.resultado  FROM procseletivo p WHERE p.idProcesso = $idProcesso")
                                                or die(mysql_error());
                                        $row3 = mysqli_fetch_array($result);



                                        $dataAtual = date("Y/m/d");

                                        $inscIni = $row3['inscInicio'];
                                        $timestamp = strtotime($inscIni); // Gera o timestamp de $data_mysql
                                        $inscInicio = date('d/m/Y', $timestamp); // Resultado: 12/03/2009
                                        $iI = date('Y/m/d', $timestamp); // Resultado: 12/03/2009

                                        $inscF = $row3['inscFim'];
                                        $timestamp2 = strtotime($inscF); // Gera o timestamp de $data_mysql
                                        $inscFim = date('d/m/Y', $timestamp2); // Resultado: 12/03/2009
                                        $iF = date('Y/m/d', $timestamp2); // Resultado: 12/03/2009

                                        $homoIni = $row3['homologacaoInicio'];
                                        $timestamp3 = strtotime($homoIni); // Gera o timestamp de $data_mysql
                                        $homologacaoInicio = date('d/m/Y', $timestamp3); // Resultado: 12/03/2009
                                        $hI = date('Y/m/d', $timestamp3); // Resultado: 12/03/2009

                                        $homoFim = $row3['homologacaoFim'];
                                        $timestamp4 = strtotime($homoFim); // Gera o timestamp de $data_mysql
                                        $homologacaoFim = date('d/m/Y', $timestamp4); // Resultado: 12/03/2009
                                        $hF = date('Y/m/d', $timestamp4); // Resultado: 12/03/2009

                                        $provaIni = $row3['provaInicio'];
                                        $timestamp5 = strtotime($provaIni); // Gera o timestamp de $data_mysql
                                        $provaInicio = date('d/m/Y', $timestamp5); // Resultado: 12/03/2009
                                        $pI = date('Y/m/d', $timestamp5); // Resultado: 12/03/2009

                                        $provaF = $row3['provaFim'];
                                        $timestamp6 = strtotime($provaF); // Gera o timestamp de $data_mysql
                                        $provaFim = date('d/m/Y', $timestamp6); // Resultado: 12/03/2009
                                        $pF = date('Y/m/d', $timestamp6); // Resultado: 12/03/2009

                                        $projetoIni = $row3['projetoInicio'];
                                        $timestamp7 = strtotime($projetoIni); // Gera o timestamp de $data_mysql
                                        $projetoInicio = date('d/m/Y', $timestamp7); // Resultado: 12/03/2009
                                        $pjI = date('Y/m/d', $timestamp7); // Resultado: 12/03/2009

                                        $projetoF = $row3['projetoFim'];
                                        $timestamp8 = strtotime($projetoF); // Gera o timestamp de $data_mysql
                                        $projetoFim = date('d/m/Y', $timestamp8); // Resultado: 12/03/2009
                                        $pjF = date('Y/m/d', $timestamp8); // Resultado: 12/03/2009

                                        $entrevistaIni = $row3['entrevistaInicio'];
                                        $timestamp9 = strtotime($entrevistaIni); // Gera o timestamp de $data_mysql
                                        $entrevistaInicio = date('d/m/Y', $timestamp9); // Resultado: 12/03/2009
                                        $eI = date('Y/m/d', $timestamp9); // Resultado: 12/03/2009

                                        $entrevistaF = $row3['entrevistaFim'];
                                        $timestamp10 = strtotime($entrevistaF); // Gera o timestamp de $data_mysql
                                        $entrevistaFim = date('d/m/Y', $timestamp10); // Resultado: 12/03/2009
                                        $eF = date('Y/m/d', $timestamp10); // Resultado: 12/03/2009

                                        $curriculoIni = $row3['curriculoInicio'];
                                        $timestamp11 = strtotime($curriculoIni); // Gera o timestamp de $data_mysql
                                        $curriculoInicio = date('d/m/Y', $timestamp11); // Resultado: 12/03/2009
                                        $cI = date('Y/m/d', $timestamp11); // Resultado: 12/03/2009

                                        $curriculoF = $row3['curriculoFim'];
                                        $timestamp12 = strtotime($curriculoF); // Gera o timestamp de $data_mysql
                                        $curriculoFim = date('d/m/Y', $timestamp12); // Resultado: 12/03/2009
                                        $cF = date('Y/m/d', $timestamp12); // Resultado: 12/03/2009
//

                                        $profileIni = $row3['profileInicio'];
                                        $timestamp13 = strtotime($profileIni); // Gera o timestamp de $data_mysql
                                        $profileInicio = date('d/m/Y', $timestamp13); // Resultado: 12/03/2009
                                        $proI = date('Y/m/d', $timestamp13); // Resultado: 12/03/2009

                                        $profileF = $row3['profileFim'];
                                        $timestamp14 = strtotime($profileF); // Gera o timestamp de $data_mysql
                                        $profileFim = date('d/m/Y', $timestamp14); // Resultado: 12/03/2009
                                        $proF = date('Y/m/d', $timestamp14); // Resultado: 12/03/2009
//
//
                                        $resultado = $row3['resultado'];
                                        $timestamp15 = strtotime($resultado); // Gera o timestamp de $data_mysql
                                        $resultado1 = date('d/m/Y', $timestamp15); // Resultado: 12/03/2009
                                        $resultado2 = date('Y/m/d', $timestamp15); // Resultado: 12/03/2009
//
//estilos
                                        $pendente = 'color: rgba(0,0,0,0.15);';
                                        $andamento = 'font-size: 20px;';
                                        $realizado = 'color: rgba(0,0,0,0.50);';
                                        ?>

                                        <!-- The Timeline -->



                                        <!-- aqui ficava o c�digo original  -->
                                        <!-- NOVO DESIGN  -->
                                        <?php
                                        // display data in table
                                        //echo "<p><b>View All</b> | <a href='view-paginated.php?page=1'>View Paginated</a></p>";
                                        echo "<table border='1' cellpadding='10'>";
                                        echo "<tr><th>Fase</th> <th>Descrição</th> <th>Data/Período</th> <th>Ação</th></tr>";

                                        //Fase 1
                                        echo "<tr>";
                                        echo "<td>1º</td>";
                                        echo "<td>Divulgação Edital do PSPPGARTES 2021</td>";
                                        echo "<td></td>";
//echo "<td>01/02/2021</td>";
                                        echo "<td></td>";
//echo "<td><a href = 'Arquivos/EDITAL DE SELE��O 201 VAGAS POR LINHA.pdf' target = '_blank'>Edital 2019</a></td>";
                                        echo "</tr>";
                                        //Fase 2
                                        echo "<tr>";
                                        echo "<td>2º</td>";
                                        echo "<td>Inscrição no PSPPGARTES/2021</td>";
                                        echo "<td></td>";
                                        //echo "<td>04/02/2021 a 04/03/2021</td>";
                                        if ($idTipo != 3)
                                            {
                                            //echo "<td></td>";
                                            echo "<td><b><a href='cadastrarCandidatoDoutorado.php?idProcesso=$idProcesso'>Cadastro de Candidatos</a></b><br /><a href='../relatorios/relatorioCandidatosInscritosDoutorado.php?idProcesso=$idProcesso' target='_blank'>Relatório de inscritos</a></td>";
                                            }
                                        echo "</tr>";
                                        //Fase 3
                                        echo "<tr>";
                                        echo "<td>3º</td>";
                                        echo "<td>Homologação das inscrições e sua divulgação</td>";
                                        echo "<td>05/04/2021</td>";
                                        //echo "<td></td>";
                                        if ($idTipo != 3)
                                            {
                                            //echo "<td><b><a href='homologacaoDoutorado.php?idProcesso=$idProcesso'>Homologar</a><br /></b></td>";
                                            echo "<td><b><a href='homologacaoDoutorado.php?idProcesso=$idProcesso'>Homologar</a><br /></b><a href = '../relatorios/relatorioHomologacaoDoutoradoPublico.php?idProcesso=$idProcesso' target = '_blank'>Relatório de Homologação</a><br /><a href = '../relatorios/relatorioHomologacaoDoutoradoGeral.php?idProcesso=$idProcesso' target = '_blank'>Relatório Geral de Homologação</a><br /><a href = '../relatorios/relatorioInscricoesHomologadasDoutorado.php?idProcesso=$idProcesso' target = '_blank'>Relatório de Inscrições Homologadas</a><br /><a href = '../relatorios/relatorioHomologadosLinhaDoutorado.php?idProcesso=$idProcesso' target = '_blank'>Relatório de Inscrições Homologadas por Linha</a>";
                                            echo "<br /><a href = '../relatorios/relatorioHomologadosOrientadoresDoutorado.php?idProcesso=$idProcesso' target = '_blank'>Relatório de Orientadores selecionados pelos Inscritos</a></td>";
                                            }
                                        echo "</tr>";
                                        /*

                                          //Fase 4
                                          echo "<tr>";
                                          echo "<td>4º</td>";
                                          echo "<td>Entrega do Projeto de Pesquisa</td>";
                                          echo "<td>26/03/2021 a 27/03/2021</td>";
                                          echo "<td>";
                                          if ($idTipo != 3) {
                                          echo "<a href='relatorioAlocacaoProjetoMest.php?idProcesso=$idProcesso' target = '_blank'>Relat�rio de Professores Alocados</a><br />";
                                          } else {
                                          echo "<a href='avaliarProjeto.php?idProcesso=$idProcesso'>Avaliar Projeto</a><br /></b>";
                                          }
                                          if ($idTipo != 3) {
                                          echo "<a href='relatorioProjetoMest_cpf.php?idProcesso=$idProcesso' target = '_blank'>Relat�rio Extra por CPF</a><br /></b>";
                                          echo "<a href = 'relatorioProjetoMest_nome.php?idProcesso=$idProcesso' target = '_blank'>Relat�rio Extra por Nome</a>";
                                          }
                                          echo "</td></tr>";
                                          //Fase 5
                                          echo "<tr>";
                                          echo "<td>5�</td>";
                                          echo "<td>Divulga��o dos Resultados na avalia��o do Projeto de Pesquisa</td>";
                                          echo "<td>16/04/2021</td>";
                                          if ($idTipo != 3) {
                                          echo "<td><b><a href = 'relatorioAprovadosProjetoMest.php?idProcesso=$idProcesso' target = '_blank'> Relat�rio para Publica��o</a></b><br /><a href = 'relatorioAprovadosProjetoMest_linha.php?idProcesso=$idProcesso' target = '_blank'> Relat�rio Extra por Nome, Linha e Campo</a><br /><a href = 'relatorioAprovadosProjetoMest_nome.php?idProcesso=$idProcesso' target = '_blank'>Relat�rio Extra por Ordem Alfab�tica</a></td>";
                                          }
                                          echo "</tr>";
                                          //Fase 6
                                          echo "<tr>";
                                          echo "<td>6�</td>";
                                          echo "<td>Prova escrita (eliminat�ria e classificat�riaj)</td>";
                                          echo "<td>23/04/2021</td>";
                                          echo "<td>";
                                          if ($idTipo != 3) {
                                          echo "<a href = 'relatorioAlocacaoProvaMest.php?idProcesso=$idProcesso' target = '_blank'> Relat�rio de Aloca��o</a><br />";
                                          echo "<a href='relatorioProvaMest_cpf.php?idProcesso=$idProcesso' target = '_blank'>Relat�rio Extra por CPF</a><br /></b>";
                                          echo "<a href = 'relatorioProvaMest_nome.php?idProcesso=$idProcesso' target = '_blank'>Relat�rio Extra por Nome</a>";
                                          } else {
                                          echo "<b><a href = 'avProva1.php?idProcesso=$idProcesso' target = '_blank'>Lan�ar Nota da Prova</a></b>";
                                          }


                                          echo "</td></tr>";
                                          //Fase 7
                                          echo "<tr>";
                                          echo "<td>7�</td>";
                                          echo "<td>Divulga��o dos Resultados da Prova Escrita.</td>";
                                          echo "<td>08/05/2021</td>";
                                          echo "<td>";
                                          if ($idTipo != 3) {
                                          echo "<b><a href = 'relatorioAprovadosProvaMest.php?idProcesso=$idProcesso' target = '_blank'> Relat�rio para Publica��o</a></b><br />";
                                          echo "<a href = 'relatorioAprovadosProvaMest_linha.php?idProcesso=$idProcesso' target = '_blank'> Relat�rio Extra por Nome, Linha e Campo</a><br />";
                                          echo "<a href = 'relatorioAprovadosProvaMest_nome.php?idProcesso=$idProcesso' target = '_blank'>Relat�rio Extra por Ordem Alfab�tica</a>";
                                          }
                                          echo "</td></tr>";
                                          //Fase 8
                                          echo "<tr>";
                                          echo "<td>8�</td>";
                                          echo "<td>Entrega de documentos para An�lise do Curr�culo...</td>";
                                          echo "<td>13/05/2021 a 14/05/2021</td>";
                                          echo "<td>";
                                          if ($idTipo != 3) {
                                          echo "<a href = 'relatorioAlocacaoCurriculoMest.php?idProcesso=$idProcesso' target = '_blank'> Relat�rio de Professores  Alocados</a><br />";
                                          } else {
                                          echo "<b><a href = 'avCurriculoMestDout.php?idProcesso=$idProcesso' target = '_blank'> Lan�ar Nota Curr�culo</a></b><br />";
                                          }

                                          echo "</td>";
                                          echo "</tr>";
                                          //Fase 9
                                          echo "<tr>";
                                          echo "<td>9�</td>";
                                          echo "<td>Divulga��o dos Resultados da An�lise do Curr�culo.</td>";
                                          echo "<td>29/05/2021</td>";
                                          echo "<td>";
                                          if ($idTipo != 3) {
                                          echo "<b><a href = 'relatorioAprovadosCurrMest.php?idProcesso=$idProcesso' target = '_blank'> Relat�rio para Publica��o</a></b><br />";
                                          echo "<a href = 'relatorioAprovadosCurrMest_linha.php?idProcesso=$idProcesso' target = '_blank'> Relat�rio Extra por Nome, Linha e Campo</a><br />";
                                          echo "<a href = 'relatorioAprovadosCurrMest_nome.php?idProcesso=$idProcesso' target = '_blank'>Relat�rio Extra por Ordem Alfab�tica</a><br />";
                                          echo "<a href = 'relatorioAprovadosCurrMest_Extra.php?idProcesso=$idProcesso' target = '_blank'>Relat�rio Extra de Classifica��o Geral</a>";
                                          }
                                          echo "</td>";
                                          echo "</tr>";
                                          //Fase 10
                                          echo "<tr>";
                                          echo "<td>10�</td>";
                                          echo "<td>Prova oral/Defesa de Projeto</td>";
                                          echo "<td>06/06/2021 a 07/06/2021</td>";
                                          echo "<td>";
                                          if ($idTipo != 3) {
                                          echo "<a href = 'relatorioAlocacaoEntrevistaMest.php?idProcesso=$idProcesso' target = '_blank'> Relat�rio de Professores  Alocados</a><br />";
                                          } else {
                                          echo "<b><a href = 'avEntrevistaMestDout.php?idProcesso=$idProcesso' target = '_blank'> Lan�ar Nota Entrevista</a></b><br />";
                                          }
                                          echo "</td>";
                                          echo "</tr>";
                                          //Fase 11
                                          echo "<tr>";
                                          echo "<td>11�</td>";
                                          echo "<td>Divulga��o dos Resultados da Prova Oral e do Resultado Final.</td>";
                                          echo "<td>12/06/2021</td>";
                                          echo "<td>";
                                          if ($idTipo != 3) {
                                          echo "<b><a href = 'relatorioResultadoFinalMest.php?idProcesso=$idProcesso' target = '_blank'>Relat�rio Final para Publica��o</a></b><br />";
                                          echo "<a href = 'relatorioResultadoFinalAreaMest.php?idProcesso=$idProcesso' target = '_blank'>Relat�rio Extra por �rea</a><br />";
                                          echo "<a href = 'relatorioResultadoFinalMestExtra.php?idProcesso=$idProcesso' target = '_blank'>Relat�rio Extra - Classificados (Bolsas)</a><br />";
                                          }
                                          echo "</td>";
                                          echo "</tr>";
                                         */
                                        ?>


                                    </div>                         


                                </div>



                            </section><!-- content -->
                        </div>
                        <!-- End About Tab Data -->


                    </div>
                </div>
                <!-- End Tab Container -->
                <footer>
<?php include "rodape.html"; ?>
                </footer>
            </div><!-- #main -->
        </div><!-- #main-container -->



    </body>
</html>
