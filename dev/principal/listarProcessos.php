
<?php
// Inclui o arquivo com o sistema de seguran�a
include("seguranca.php");
// include("dbconnect.inc.php");
protegePagina(); // Chama a fun��o que protege a p�gina
?>

<html class="no-js">

    <head>
        <?php include "head.html"; ?>
        <script type="text/javascript" language="javascript">
            function confirmaExclusao(aURL) {
                if (confirm('Confirma inativação deste Processo?')) {
                    location.href = aURL;
                }
            }
        </script>     
    </head>
    <div style="text-align:right;">
        <div style="float:left;">
            <!--img src="/ppgartes/imagens/1521748832379-110310236.jpg" width="99" height="55" alt="PPGARTES"/-->
            <img src="imagens/ppg_logo_small_1.png" width="99" height="55" alt="PPGARTES"/>
        </div>
        <?php if ($_SESSION['usuarioNome'] != "")
            { ?>

            <div class="logout" >
                <form id = "logout" name="logout" method="post" action="logout.php">
                    <!--?php echo "Bem vindo(a) <b>" . $_SESSION['usuarioNome'] . "</b>"; ?-->
    <?php echo "<b>[" . $_SESSION['usuarioNome'] . "]</b>"; ?>

                    <input align="right" class='botao' type="submit" value="Sair" />
                </form>
            </div>
<?php } ?>      
    </div> 
    <body class="bg-fixed bg-1">

        <div class="main-container">
            <div class="main wrapper clearfix">

                <div class="main wrapper clearfix">

                    <div id="tab-container">
                        <?php
                        $ind = 0;
                        $user = 0;
                        $cand = 1;
                        $ava = 0;
                        $perf = 0;
                        ?>
                        <!-- Tab List -->
<?php include ("menutab.php"); ?>
                        <!-- End Tab List -->

                        <div id="tab-data-wrap">

                            <!-- About Tab Data -->
                            <div id="usuarios">
                                <section class="clearfix">
                                    <div class="g3">
                                        <div>

                                            <?php
                                            $msg = (isset($_GET['msg'])) ? $_GET['msg'] : '';
                                            if ($msg == 1)
                                                echo "<center><b>Processo seletivo cadastrado com sucesso!</b></center><br />";
                                            else if ($msg == 2)
                                                echo "<center><b>Processo seletivo atualizado com sucesso!</b></center><br />";
                                            else if ($msg == 3)
                                                echo "<center><b>Processo seletivo deletado com sucesso!</b></center><br />";
                                            ?>                                 

                                            <p>

                                            <div align="center">

                                                <?php
                                                include("dbconnect.inc.php");
                                                $per_page = 10;
                                                $usuarioID = $_SESSION["usuarioID"];
                                                $busca = (isset($_POST['busca'])) ? $_POST['busca'] : '';

                                                //$result = mysqli_query($_SG['link'], "SELECT p.idProcesso, p.processo, p.estado FROM procseletivo p WHERE p.estado=1 AND novo = 1 ORDER BY p.dataCriacao DESC") php5
                                                $result = mysqli_query($_SG['link'], "SELECT p.idProcesso, p.processo, p.estado FROM procseletivo p WHERE p.estado=1 AND novo = 1 ORDER BY p.dataCriacao DESC")
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
                                                ?>

<?php if ($total_results != 0)
    { ?>

                                                    <!--p align="right"><a href="cadastrarProcesso.php"  class="botao" > Cadastrar Processo</a></p-->

                                                    <table id="rounded-corner">

                                                        <tr><th scope="col" class="rounded-company">Selecione um Processo Seletivo</th><th>Editar</th><th>Excluir</th></tr>



                                                        <!-- ?php
                                                        for ($i = $start; $i < $end; $i++) {
                                                            // make sure that PHP doesn't try to show results that don't exist
                                                            if ($i == $total_results) {
                                                                break;
                                                            }

                                                            // echo out the contents of each row into a table


                                                            //$processo = mysql_result($result, $i, 'processo'); php5
                                                            mysqli_res
                                                            $processo = mysqli_result($result, $i, 'processo');
                                                            $idProcesso = mysqli_result($result, $i, 'idProcesso');


                                                            echo "<tr>";
                                                            echo "<td onclick=\"document.location = 'cronogramaMestrado.php?idProcesso=$idProcesso';\">" . $processo . '</td>';
                                                            ?  -->

                                                        <?php
                                                        while ($row = mysqli_fetch_array($result)) {
                                                            $processo = $row['processo'];
                                                            $idProcesso = $row['idProcesso'];
                                                            echo "<tr>";
                                                            echo "<td onclick=\"document.location = 'cronogramaMestrado.php?idProcesso=$idProcesso';\">" . $processo . '</td>';
                                                            ?>




        <td><!--a href="editarProcesso.php?id=<?php echo $row[idProcesso] ?>">Editar</a--></td><!--<img src="images/edit.png" width="35%" height="40%"></a></td>-->

                                                                            <td><!--a href="javascript:confirmaExclusao('deletarProcesso.php?id=<?php echo $row[idProcesso] ?>')">Excluir</a--></td>

    <?php }
    ?>         





                                                    </table>

                                                    <?php
                                                    } else
                                                    echo "<br /><center>Nenhum Processo Seletivo Cadastrado no sistema. <br/> <a href='cadastrarProcesso.php'><u><b> Clique aqui </b></u></a> para cadastrar.</center><br />";
                                                ?> 

                                                <!-- <ul class="" id="nav">
                                                     <li align="right">
                          
                                                           <input class='submit' type="submit" value="Finalizar" />
                                                      </li>
                                                 </ul>  -->

                                            </div>   


                                            </p>

                                        </div>
                                    </div>

                                    <!--     <div class="g1">
                                             <div class="main-links sidebar">
                                                 <ul>
                                                     <li>
                                                         <a href="alunos.php">Alunos</a>
                                                     </li>
                                                     <li>
                                                         <a href="administradores.php">Administradores</a>
                                                     </li>
                                                     <li>
                                                         <a href="avaliadores.php">Avaliadores</a>
                                                     </li>
                 
                                                 </ul>
                                             </div>
                                         </div> -->

                                </section><!-- content -->
                            </div>
                            <!-- End About Tab Data -->


                        </div>
                    </div>
                    <!-- End Tab Container -->
                    <footer>
<?php include "rodape.html"; ?>
                    </footer>
                </div><!-- #main --

                </div><!-- #main-container -->