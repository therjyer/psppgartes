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
                                                Selecione um canditado para avaliar - Avaliação da Prova de Títulos)
                                            </h4>
                                            <div align="center">
                                                <form class="form-wrapper cf" method="post" action="avCurriculo1.php?idProcesso=<?php echo $idProcesso; ?>">
                                                    <input name="busca" id="busca" type="text" placeholder="Digite o texto da pesquisa..." required>
                                                    <button type="submit">Pesquisar</button>
                                                </form>
                                            </div>                                   
                                        </div>


                                        <?php
                                        $msg = (isset($_GET['msg'])) ? $_GET['msg'] : '';
                                        if ($msg == 1)
                                            echo "<center><b>Avaliaç~ão realizada com sucesso!</b></center><br />";
                                        else if ($msg == 2)
                                            echo "<center><b>Canditado atualizado com sucesso!</b></center><br />";
                                        else if ($msg == 3)
                                            echo "<center><b>Canditado deletado com sucesso!</b></center><br />";
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
                                                //$tipo = mysql_result($consulta, 0, 'tipo');

                                                $consulta2 = mysqli_query($_SG['link'], "SELECT u.id FROM usuarios u WHERE u.nome = '$nomeUsuario' AND u.estado=1")
                                                        or die(mysql_error());
                                                $row2 = mysqli_fetch_row($consulta2);
                                                $idProfessor = $row2[0];
//$row2 = mysqli_fetch_row($consulta2);
                                                //$idProfessor = $row2[0];


                                                $result = mysqli_query($_SG['link'], "SELECT c.linhaPesquisa, c.estado, c.tipoProcesso, c.tipoProcesso, c.estadoCurriculo, c.idCandidato, c.cpf, c.numInscricao, c.nome, c.notaProva1, c.notaProva2, c.notaAnteprojeto1, c.notaAnteprojeto2, c.notaEntrevista, c.pontuacaoCurriculo, c.avaliadorCurriculo1, c.avaliadorCurriculo2 FROM candidato c WHERE  (c.cpf like '%$busca%')  AND (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2)>=7) AND (c.avaliadorCurriculo1='$idProfessor' OR c.avaliadorCurriculo2='$idProfessor') AND c.estado = 1 AND c.processo = '$idProcesso'  AND c.estadoHomologacao=1 ORDER BY c.tipoProcesso, c.nome")
                                                        or die(mysql_error()); //AND (avaliadorAnteprojeto1='$idProfessor')

                                                $total_results = mysqli_num_rows($result);

                                                //$total_pages = ceil($total_results / $per_page);
                                                /*
                                                  if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                                                  $show_page = $_GET['page'];

                                                  // make sure the $show_page value is valid
                                                  if ($show_page > 0 && $show_page <= $total_pages) {
                                                  $start = ($show_page - 1) * $per_page;
                                                  $end = $start + $per_page;
                                                  } else {
                                                  // error - show first set of results
                                                  $start = 0;
                                                  $end = $per_page;
                                                  }
                                                  } else {
                                                  // if page isn't set, show first set of results
                                                  $start = 0;
                                                  $end = $per_page;
                                                  }


                                                  //echo "<table id='pages'> <tr><td> <ul>";

                                                  if (isset($_GET['page'])) {
                                                  $pg_atual = $_GET['page'];
                                                  } else {
                                                  $pg_atual = -1;
                                                  }


                                                  for ($i = 1; $i <= $total_pages; $i++) {
                                                  if ($pg_atual > 0) {
                                                  if ($i == $pg_atual) {
                                                  echo "<li ><a class='current' href='avCurriculoMestDout.php?page=$i&idProcesso=$idProcesso'> $i </a></li>";
                                                  } else {
                                                  echo "<li><a href='avCurriculoMestDout.php?page=$i&idProcesso=$idProcesso'> $i </a></li>";
                                                  }
                                                  } else {
                                                  echo "<li ><a class='current' href='avCurriculoMestDout.php?page=1&idProcesso=$idProcesso'> 1 </a></li>";
                                                  $pg_atual = 1;
                                                  }
                                                  }


                                                  echo " </tr></td> </ul></table>";

                                                 */
                                                ?>
                                                <?php if ($total_results != 0)
                                                    { ?>                        
                                                <table id="rounded-corner">

                                                    <tr><th scope="col" class="rounded-q1">Estado</th>   
                                                        <th scope="col" class="rounded-q1">Tipo</th>
                                                        <th scope="col" class="rounded-q1">Nome</th>
                                                        <th scope="col" class="rounded-q2">Pontuação</th>




                                                        <?php
                                                        //for ($i = $start; $i < $end; $i++) {
                                                        while ($row = mysqli_fetch_array($result)) {
                                                          
                                                            // echo out the contents of each row into a table
                                                            $estadoCurriculo = $row['estadoCurriculo'];
                                                            
                                                            $pontuacao = $row['pontuacaoCurriculo'];
                                                            $tipoProcesso = $row['tipoProcesso'];

                                                            if ($estadoCurriculo == 0)
                                                                {
                                                                $estado = "Não Avaliado";
                                                                } else
                                                                {
                                                                $estado = "<b>Avaliado</b>";
                                                                }

                                                            $linhaPesquisa = $row['linhaPesquisa'];

                                                            if ($tipoProcesso == "1")
                                                                {
                                                                $tipo = "Mestrado - L" . $linhaPesquisa;
                                                                } else if ($tipoProcesso == "2")
                                                                {
                                                                $tipo = "Doutorado - L" . $linhaPesquisa;
                                                                }




                                                            $idCandidato = $row['idCandidato'];

                                                            if ($tipoProcesso == 1)
                                                                {
                                                                if ($linhaPesquisa == "1")
                                                                    {
                                                                    echo "<tr onclick=\"document.location = 'avCurriculoMestFichaLP1.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                                                    } else
                                                                    {
                                                                    echo "<tr onclick=\"document.location = 'avCurriculoMestFichaLP23.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                                                    }
                                                                } else if ($tipoProcesso == 2)
                                                                {
                                                                if ($linhaPesquisa == "1")
                                                                    {
                                                                    echo "<tr onclick=\"document.location = 'avCurriculoDoutFichaLP1.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                                                    } else
                                                                    {
                                                                    echo "<tr onclick=\"document.location = 'avCurriculoDoutFichaLP23.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                                                    }
                                                                }

                                                            echo '<td>' . $estado . '</td>';
                                                            echo '<td>' . $tipo . '</td>';
                                                            echo '<td>' . $row['nome'] . '</td>';
                                                            echo '<td>' . $pontuacao . '</td>';
                                                            echo "</tr>";
                                                        }
                                                        ?>





                                                </table>
    <?php
    } else
    echo "<br /><center>Nenhum candidato para esta fase.</center><br />";
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
