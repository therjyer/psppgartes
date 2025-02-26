<?php
include("seguranca.php"); // Inclui o arquivo com o sistema de segurança
// include("dbconnect.inc.php");
//protegePagina(); // Chama a função que protege a página
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <?php include "head.html"; ?>
        <!--?php header("Content-type: text/html; charset=UTF-8"); ?-->
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

                <div id="tab-container">
                    <?php
                    $ind = 0;
                    $user = 0;
                    $cand = 0;
                    $ava = 1;
                    $perf = 0;

                    /*
                      //antigo
                      $idProcesso = (isset($_GET['idProcesso'])) ? $_GET['idProcesso'] : '';
                      $consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
                      $processo = mysql_result($consulta, 0, 'processo');
                      //fim antigo
                     */

                    //novo aqui
                    $idProcesso = (isset($_GET['idProcesso'])) ? $_GET['idProcesso'] : '';
                    $consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
                    $row = mysqli_fetch_array($consulta);
                    $processo = $row['processo'];
                    // fim novo
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
                                                Selecione um canditado para homologar (Mestrado)
                                            </h4>
                                        </div>
                                        <div align="center">
                                            <form class="form-wrapper cf" method="post" action="homologacaoMestrado.php?idProcesso=<?php echo $idProcesso; ?>">
                                                <input name="busca" id="busca" type="text" placeholder="Digite o texto da pesquisa..." required>
                                                <button type="submit">Pesquisar</button>
                                            </form>
                                        </div>

                                        <?php
                                        $msg = (isset($_GET['msg'])) ? $_GET['msg'] : '';
                                        if ($msg == 1)
                                            echo "<center><b>Alteração realizada com sucesso!</b></center><br />";
                                        else if ($msg == 2)
                                            echo "<center><b>Canditado atualizado com sucesso!</b></center><br />";
                                        else if ($msg == 3)
                                            echo "<center><b>Canditado deletado com sucesso!</b></center><br />";
                                        ?> 



                                        <div align='center'>                               <p>
                                                <?php
                                                include("dbconnect.inc.php");
                                                $per_page = 10;
                                                $usuarioID = $_SESSION["usuarioID"];
                                                $busca = (isset($_POST['busca'])) ? $_POST['busca'] : '';

                                                $result = mysqli_query($_SG['link'], "SELECT c.estado, c.estadoHomologacao, c.idCandidato, c.numInscricao, c.nome, c.cpf FROM candidato c WHERE  (c.nome like '%$busca%' OR c.cpf like '%$busca%' OR c.numInscricao like '%$busca%') AND c.estado = 1 AND c.processo = '$idProcesso' AND c.tipoProcesso = 1 ORDER BY c.nome")
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
                                                            echo "<li ><a class='current' href='homologacaoMestrado.php?page=$i&idProcesso=$idProcesso'> $i </a></li>";
                                                            } else
                                                            {
                                                            echo "<li><a href='homologacaoMestrado.php?page=$i&idProcesso=$idProcesso'> $i </a></li>";
                                                            }
                                                        } else
                                                        {
                                                        echo "<li ><a class='current' href='homologacaoMestrado.php?page=1&idProcesso=$idProcesso'> 1 </a></li>";
                                                        $pg_atual = 1;
                                                        }
                                                }


                                                echo " </tr></td> </ul></table>";
                                                ?>
                                                <?php if ($total_results != 0)
                                                    { ?>                        
                                                <table id="rounded-corner">

                                                    <tr><th scope="col" class="rounded-q1">Estado</th>
                                                        <th scope="col" class="rounded-company">Número Inscrição</th>                
                                                        <th scope="col" class="rounded-q1">Nome</th>
                                                        <th scope="col" class="rounded-q2">Homologar</th>




                                                        <?php
                                                        //for ($i = $start; $i < $end; $i++)
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            // make sure that PHP doesn't try to show results that don't exist
                                                            // antigo if ($i == $total_results) { break; }
                                                            // echo out the contents of each row into a table
                                                            // antigo $estadoHomologacao = mysql_result($result, $i, 'estadoHomologacao');
                                                            $estadoHomologacao = $row['estadoHomologacao'];


                                                            if ($estadoHomologacao == 0)
                                                                {
                                                                $estado = "-";
                                                                } else if ($estadoHomologacao == 1)
                                                                {
                                                                $estado = "<b>Homologado</b>";
                                                                } else
                                                                {
                                                                $estado = "Não Homologado";
                                                                }

                                                            //antigo $idCandidato = mysql_result($result, $i, 'idCandidato');
                                                            $idCandidato = $row['idCandidato'];
                                                            echo "<tr>";
                                                            echo '<td>' . $estado . '</td>';
                                                            //antigo echo '<td>' . mysql_result($result, $i, 'numInscricao') . '</td>';
                                                            echo '<td>' . $row['numInscricao'] . '</td>';
                                                            //antigo echo '<td>' . mysql_result($result, $i, 'nome') . '</td>';
                                                            echo '<td>' . $row['nome'] . '</td>';
                                                            $pg = (isset($_GET['page'])) ? $_GET['page'] : '';
                                                            echo "<td> <a href='homologarMestrado.php?estadoHomologacao=1&idCandidato=$idCandidato&idProcesso=$idProcesso&page=$pg'>Sim</a> / <a href='homologarMestrado.php?estadoHomologacao=2&idCandidato=$idCandidato&idProcesso=$idProcesso&page=$pg'>Não</a></td>";
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
