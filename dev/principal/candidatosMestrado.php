<?php
include("seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
// include("dbconnect.inc.php");
protegePagina(); // Chama a fun��o que protege a p�gina
?>
<!DOCTYPE html>
<html class="no-js"> <!--<![endif]-->
    <head>

        <?php include "head.html"; ?>
        <?php header("Content-type: text/html; charset=UTF-8"); ?>
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
                    $cand = 1;
                    $ava = 0;
                    $perf = 0;

                    $idProcesso = (isset($_GET['idProcesso'])) ? $_GET['idProcesso'] : '';
                    $consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
                    $row = mysqli_fetch_array($consulta);
                    $processo = $row['processo'];
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
                                                Mestrado - Lista de candidatos inscritos no processo de seleção <?php echo $processo; ?>
                                            </h4>

                                        </div>

                                        <div align="center">
                                            <form class="form-wrapper cf" method="post" action="candidatosMestrado.php?idProcesso=<?php echo $idProcesso; ?>">
                                                <input name="busca" id="busca" type="text" placeholder="Digite o texto da pesquisa..." required>
                                                <button type="submit">Pesquisar</button>
                                            </form>
                                        </div>

                                        <?php
                                        $msg = (isset($_GET['msg'])) ? $_GET['msg'] : '';
                                        if ($msg == 1)
                                            echo "<center><b>Candidato cadastrado com sucesso!</b></center><br />";
                                        else if ($msg == 2)
                                            echo "<center><b>Candidato atualizado com sucesso!</b></center><br />";
                                        else if ($msg == 3)
                                            echo "<center><b>Candidato deletado com sucesso!</b></center><br />";
                                        ?> 


                                        <?php
                                        $result = mysqli_query($_SG['link'], "SELECT p.inscInicio, p.inscFim FROM procseletivo p WHERE p.idProcesso = $idProcesso")
                                                or die(mysql_error());

                                        $row = mysqli_fetch_array($result);
                                        $processo = $row['inscFim'];
                                        $dataAtual = date("Y/m/d");

                                        $inscIni = $processo = $row['inscInicio'];
                                        $timestamp = strtotime($inscIni); // Gera o timestamp de $data_mysql
                                        $inscInicio = date('d/m/Y', $timestamp); // Resultado: 12/03/2009
                                        $iI = date('Y/m/d', $timestamp); // Resultado: 12/03/2009

                                        $inscF = $processo = $row['inscFim'];
                                        $timestamp2 = strtotime($inscF); // Gera o timestamp de $data_mysql
                                        $inscFim = date('d/m/Y', $timestamp2); // Resultado: 12/03/2009
                                        $iF = date('Y/m/d', $timestamp2); // Resultado: 12/03/2009

                                        if ((($dataAtual >= $iI) & ($dataAtual <= $iF)) & (($_SESSION['usuarioTipo'] == 1) || ($_SESSION['usuarioTipo'] == 2)))
                                            {
                                            echo "
                        <p align='right'><a href='cadastrarCandidatoMestrado.php?idProcesso=$idProcesso'  class='botao' > Cadastrar Candidato Mestrado</a>
										</p>
                     ";
                                            }
                                        ?>


                                        <div align='center'>                               <p>
                                                <?php
                                                include("dbconnect.inc.php");
                                                $per_page = 10;
                                                $usuarioID = $_SESSION["usuarioID"];
                                                $busca = (isset($_POST['busca'])) ? $_POST['busca'] : '';

                                                $result = mysqli_query($_SG['link'], "SELECT c.estado, c.idCandidato, c.tipoProcesso, c.numInscricao, c.nome FROM candidato c WHERE  (c.nome like '%$busca%' OR c.cpf like '%$busca%' OR c.numInscricao like '%$busca%') AND c.estado = 1 AND c.processo = '$idProcesso' AND c.tipoProcesso = 1 ORDER BY c.nome")
                                                        or die(mysql_error());

                                                $total_results = mysqli_num_rows($result);

                                                $total_pages = ceil($total_results / $per_page);
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
                                                    $end = $per_page;
                                                    }


                                                echo "<table id='pages'> <tr><td> <ul>";

                                                if (isset($_GET['page']))
                                                    {
                                                    $pg_atual = $_GET['page'];
                                                    } else
                                                    {
                                                    $pg_atual = -1;
                                                    }

                                                for ($i = 1; $i <= $total_pages; $i++) {
                                                    if ($pg_atual > 0)
                                                        {
                                                        if ($i == $pg_atual)
                                                            {
                                                            echo "<li ><a class='current' href='candidatosMestrado.php?page=$i&idProcesso=$idProcesso'> $i </a></li>";
                                                            } else
                                                            {
                                                            echo "<li><a href='candidatosMestrado.php?page=$i&idProcesso=$idProcesso'> $i </a></li>";
                                                            }
                                                        } else
                                                        {
                                                        echo "<li ><a class='current' href='candidatosMestrado.php?page=1&idProcesso=$idProcesso'> 1 </a></li>";
                                                        $pg_atual = 1;
                                                        }
                                                }


                                                echo " </tr></td> </ul></table>";
                                                ?>
                                                <?php if ($total_results != 0)
                                                    {
                                                    ?>                        
                                                <table id="rounded-corner">

                                                    <tr><th scope="col" class="rounded-company">Número Inscrição</th>
                                                        <th scope="col" class="rounded-q1">Tipo</th>
                                                        <th scope="col" class="rounded-q2">Nome</th>
                                                        <th scope="col" class="rounded-q3">Editar</th>
                                                        <th scope="col" class="rounded-q4">Excluir</th></tr>




                                                    <?php
                                                    //for ($i = $start; $i < $end; $i++)
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        // make sure that PHP doesn't try to show results that don't exist
                                                        //if ($i == $total_results) { break; }
                                                        // echo out the contents of each row into a table

                                                        echo "<tr>";
                                                        echo '<td>' . $row['numInscricao'] . '</td>';
                                                        $tipoProcesso = $row['tipoProcesso'];

                                                        if ($tipoProcesso == 1)
                                                            {
                                                            $tipo = "Mestrado";
                                                            } else if ($tipoProcesso == 2)
                                                            {
                                                            $tipo = "Doutorado";
                                                            }
                                                        echo '<td>' . $tipo . '</td>';

                                                        echo '<td>' . $row['nome'] . '</td>';
                                                        ?>
                                                <td><a href="editarCandidato.php?id=<?php echo $row['idCandidato'] ?>&idProcesso=<?php echo $idProcesso; ?>">Editar</a></td><!--<img src="images/edit.png" width="35%" height="40%"></a></td>-->

                                                        <td><a href="javascript:confirmaExclusao('../processo/deletarCandidato.php?id=<?php echo $row['idCandidato'] ?>&idProcesso=<?php echo $idProcesso; ?>')">Excluir</a></td>

                                                        <?php
                                                        echo "</tr>";
                                                    }
                                                    ?>





                                                </table>
                                                <?php
                                                } else
                                                echo "<br /><center>Nenhum candidato cadastrado para esse processo.</center><br />";
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
