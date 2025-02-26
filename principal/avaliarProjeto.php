<?php
include("seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
// include("dbconnect.inc.php");
protegePagina(); // Chama a fun��o que protege a p�gina
?>
<!DOCTYPE html>
<html class="no-js"> 
    <head>
        <?php include "head.html"; ?>
        <script type="text/javascript" language="javascript">
            function confirmaExclusao(aURL) {
                if (confirm('Você tem certeza que deseja tornar este usuário inativo?')) {
                    location.href = aURL;
                }
            }
        </script>  

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
                    $consultaidProcesso = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
                    $row = mysqli_fetch_row($consultaidProcesso);
                    $processo = $row[0];
                    // php 5 $processo = mysql_result($consulta, 0, 'processo');

                    $nomeProfessor = $_SESSION['usuarioNome'];

                    $consultaidProfessor = mysqli_query($_SG['link'], "SELECT u.id FROM usuarios u WHERE u.nome = '$nomeProfessor' AND u.estado=1");
                    $rowprofessor = mysqli_fetch_row($consultaidProfessor);
                    $idProfessor = $rowprofessor[0];
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
                                                Selecione um canditado para avaliar - Avaliação do Projeto
                                            </h4>
                                            <div align="center">
                                                <form class="form-wrapper cf" method="post" action="avaliarProjeto.php?idProcesso=<?php echo $idProcesso; ?>">
                                                    <input name="busca" id="busca" type="text" placeholder="Digite o texto da pesquisa ou %..." required>
                                                    <button type="submit">Pesquisar</button>
                                                </form>
                                            </div>                                   
                                        </div>


                                        <?php
                                        $msg = (isset($_GET['msg'])) ? $_GET['msg'] : '';
                                        if ($msg == 1)
                                            {
                                            echo "<center><b>Avaliação realizada com sucesso!</b></center><br />";
                                            } else if ($msg == 2)
                                            {
                                            echo "<center><b>Canditado atualizado com sucesso!</b></center><br />";
                                            } else if ($msg == 3)
                                            {
                                            echo "<center><b>Canditado deletado com sucesso!</b></center><br />";
                                            }
                                        ?> 



                                        <div align='center'>                               <p>
                                                <?php
                                                include("dbconnect.inc.php");
                                                //$per_page = 10;
                                                $usuarioID = $_SESSION["usuarioID"];
                                                $busca = (isset($_POST['busca'])) ? $_POST['busca'] : '';
                                                $nomeUsuario = $_SESSION['usuarioNome'];

                                                $consulta = mysqli_query($_SG['link'], "SELECT u.tipo FROM usuarios u WHERE  u.estado = 1  AND u.nome='$nomeUsuario'")
                                                        or die(mysql_error());
                                                $row = mysqli_fetch_row($consulta);
                                                $tipo = $row[0];
                                                // php5 $tipo = mysql_result($consulta, 0, 'tipo');
                                                $consulta2 = mysqli_query($_SG['link'], "SELECT u.id FROM usuarios u WHERE u.nome = '$nomeUsuario' AND u.estado=1")
                                                        or die(mysql_error());
                                                $row2 = mysqli_fetch_row($consulta2);
                                                $idProfessor = $row2[0];

                                                //listar candidatos do avaliador
                                                $result = mysqli_query($_SG['link'], "SELECT c.estado, c.tipoProcesso, c.estadoAnteprojeto1, c.estadoAnteprojeto2, c.idCandidato, c.cpf, c.numInscricao, c.nome, c.notaAnteprojeto1, c.notaAnteprojeto2, c.avaliadorAnteprojeto1, c.avaliadorAnteprojeto2, c.linhaPesquisa FROM candidato c WHERE  (c.nome like '%$busca%') AND c.estado = 1 AND c.processo = '$idProcesso' AND (avaliadorAnteprojeto1='$idProfessor' OR avaliadorAnteprojeto2='$idProfessor') AND c.estadoHomologacao=1 ORDER BY c.nome")
                                                        or die(mysql_error());
                                                //$row3 = mysqli_fetch_row($result);
                                                //$idProfessor = $row2[0];
                                                $total_results = mysqli_num_rows($result);
                                                //$total_results = mysqli_fetch_array($result);
                                                //tirado junto com a paginação$total_pages = ceil($total_results / $per_page);
                                              
                                                if (isset($_GET['page']) && is_numeric($_GET['page']))
                                                    {
                                                    $show_page = $_GET['page'];

                                                    // make sure the $show_page value is valid
                                                    if ($show_page > 0 && $show_page <= $total_pages)
                                                        {
                                                        $start = ($show_page - 1) * $per_page;
                                                        $end = $start + $per_page;
                                                        } else
                                                        {
                                                        // error - show first set of results
                                                        $start = 0;
                                                        $end = $per_page;
                                                        }
                                                    } else
                                                    {
                                                    // if page isn't set, show first set of results
                                                    $start = 0;
                                                    //tirado junto com a paginação $end = $per_page;
                                                    }


                                                //echo "<table id='pages'> <tr><td> <ul>";

                                                if (isset($_GET['page']))
                                                    {
                                                    $pg_atual = $_GET['page'];
                                                    } else
                                                    {
                                                    $pg_atual = -1;
                                                    }

                                                /*
                                                 * SETAR A LISTA DE PÁGINAS DE NAVEGAÇÃO ( 1 2 3 )
                                                 */
                                                    /*
                                                for ($i = 1; $i <= $total_pages; $i++) {
                                                    if ($pg_atual > 0)
                                                        {
                                                        if ($i == $pg_atual)
                                                            {
                                                            echo "<li ><a class='current' href='avaliarProjeto.php?page=$i&idProcesso=$idProcesso'> $i </a></li>";
                                                            } else
                                                            {
                                                            echo "<li><a href='avaliarProjeto.php?page=$i&idProcesso=$idProcesso'> $i </a></li>";
                                                            }
                                                        } else
                                                        {
                                                        echo "<li ><a class='current' href='avaliarProjeto.php?page=1&idProcesso=$idProcesso'> 1 </a></li>";
                                                        $pg_atual = 1;
                                                        }
                                                }
                                                */
                                                //echo " </tr></td> </ul></table>";
                                                ?>

                                                <?php
                                                /*
                                                 * cABEÇALHO DA TABELA
                                                 */
                                                if ($total_results != 0)
                                                    {
                                                    ?>                        
                                                <table id="rounded-corner">

                                                    <tr><th scope="col" class="rounded-q1">Estado</th>              
                                                        <th scope="col" class="rounded-q1">Nome</th>
                                                        <th scope="col" class="rounded-q1">Tipo</th>
                                                        <th scope="col" class="rounded-q2">Nota Projeto</th></tr>
                                                            <?php
                                                            //for ($i = $start; $i < $end; $i++) {
                                                                while ($row = mysqli_fetch_array($result)) {
                                                                //$row = mysqli_fetch_array($result);
                                                                //LISTAR OS CANDIDATOS
                                                                //php5 for ($i = $start; $i < $end; $i++) {
                                                                // make sure that PHP doesn't try to show results that don't exist
  /* tiradfo junto com paginação
                                                                    if ($i == $total_results)
                                                                    {
                                                                    break;
                                                                    }
*/
                                                                // echo out the contents of each row into a table
                                                                $estadoAnteprojeto1 = $row['estadoAnteprojeto1'];
                                                                $estadoAnteprojeto2 = $row['estadoAnteprojeto2'];

                                                                $avaliadorAnteprojeto1 = $row['avaliadorAnteprojeto1'];
                                                                $avaliadorAnteprojeto2 = $row['avaliadorAnteprojeto2'];

                                                                $linhaPesquisa = $row['linhaPesquisa'];
                                                                $tipoProcesso = $row['tipoProcesso'];

                                                                if ($tipoProcesso == "1")
                                                                    {
                                                                    $tipo = "Mestrado - L" . $linhaPesquisa;
                                                                    } else if ($tipoProcesso == "2")
                                                                    {
                                                                    $tipo = "Doutorado - L" . $linhaPesquisa;
                                                                    }

                                                                if ($idProfessor == $avaliadorAnteprojeto1)
                                                                    {
                                                                    $nota = $row['notaAnteprojeto1'];
                                                                    if ($estadoAnteprojeto1 == 0)
                                                                        {
                                                                        $estado = "Não Avaliado";
                                                                        } else
                                                                        {
                                                                        $estado = "<b>Avaliado</b>";
                                                                        }
                                                                    } else if ($idProfessor == $avaliadorAnteprojeto2)
                                                                    {
                                                                    $nota = $row['notaAnteprojeto2'];
                                                                    if ($estadoAnteprojeto2 == 0)
                                                                        {
                                                                        $estado = "Não Avaliado";
                                                                        } else
                                                                        {
                                                                        $estado = "<b>Avaliado</b>";
                                                                        }
                                                                    }



                                                                $idCandidato = $row['idCandidato'];

                                                                //  if ($mediaNota>7){
                                                                echo "<tr onclick=\"document.location = 'avProjLancarNota.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                                                /*
                                                                  if (($linhaPesquisa == "1") && ($tipoProcesso == "1"))
                                                                  {
                                                                  echo "<tr onclick=\"document.location = 'avProjMestL1.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                                                  } else if (($linhaPesquisa == "2") && ($tipoProcesso == "1"))
                                                                  {
                                                                  echo "<tr onclick=\"document.location = 'avProjMestL2.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                                                  } else if (($linhaPesquisa == "3") && ($tipoProcesso == "1"))
                                                                  {
                                                                  echo "<tr onclick=\"document.location = 'avProjMestL3.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                                                  } else if (($linhaPesquisa == "1") && ($tipoProcesso == "2"))
                                                                  {
                                                                  echo "<tr onclick=\"document.location = 'avProjDoutL1.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                                                  } else if (($linhaPesquisa == "2") && ($tipoProcesso == "2"))
                                                                  {
                                                                  echo "<tr onclick=\"document.location = 'avProjDoutL2.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                                                  } else if (($linhaPesquisa == "3") && ($tipoProcesso == "2"))
                                                                  {
                                                                  echo "<tr onclick=\"document.location = 'avProjDoutL3.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                                                  }
                                                                 */
                                                                //  echo "<tr onclick=\"document.location = 'avAnteprojeto2L1.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                                                echo '<td>' . $estado . '</td>';
                                                                echo '<td>' . $row['nome'] . '</td>';
                                                                echo '<td>' . $tipo . '</td>';
                                                                echo '<td>' . $nota . '</td>';
                                                                echo "</tr>";
                                                                //   }
                                                            }
                                                            ?>





                                                </table>
                                                <?php
                                                } else
                                                {
                                                echo "<br /><center>Nenhum candidato para esta fase.</center><br />";
                                                }
                                            ?>     

                                        </div>                            
                                        </p>

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
