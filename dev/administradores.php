<?php
include("seguranca.php"); // Inclui o arquivo com o sistema de segurança
// include("dbconnect.inc.php");
protegePagina(); // Chama a função que protege a página
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
          <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
          <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
          <script src="js/vendor/jquery.hashchange.min.js"></script>
          <script src="js/vendor/jquery.easytabs.min.js"></script>
          <script src="js/main.js"></script>
          <script src="js/tabela2.js"></script>-->  
        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script>window.html5 || document.write('<script src="js/vendor/html5shiv.js"><\/script>')</script>
            <![endif]-->
    </head>
    <div style="text-align:right;">
        <?php
        if ($_SESSION['usuarioNome'] != "")
            {
            ?>

            <div class="logout" >


                <form id = "logout" name="logout" method="post" action="logout.php">
                    <!--?php echo "Olá <b>".$_SESSION['usuarioNome']."</b>, seja bem vindo."; ?-->
                    <?php echo "<b>[" . $_SESSION['usuarioNome'] . "]</b>"; ?>     
                    <input align="right" class='botao' type="submit" value="Sair" />
                </form>
            </div>

        <?php } ?>      
    </div> 
    <body class="bg-fixed bg-1">
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
            <![endif]-->
        <div class="main-container">
            <div class="main wrapper clearfix">

                <!-- Main Tab Container -->
                <div id="tab-container" class="tab-container">
                    <?php
                    $ind = 0;
                    $user = 1;
                    $cand = 0;
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
                                <div class="g2">
                                    <div>
                                        <div class="info">
                                            <h4>
                                                Lista de administradores cadastrados no sistema
                                            </h4>
                                        </div>
                                        <div align="center">
                                            <form class="form-wrapper cf" method="post" action="administradores.php?processo=<?php echo $processo; ?>">
                                                <input name="busca" id="busca" type="text" placeholder="Digite o texto da pesquisa..." required>
                                                <button type="submit">Pesquisar</button>
                                            </form>
                                        </div>

                                        <?php
                                        $msg = (isset($_GET['msg'])) ? $_GET['msg'] : '';

                                        if ($msg == 1)
                                            {
                                            echo "<center><b>Administrador cadastrado com sucesso!</b></center><br />";
                                            } else if ($msg == 2)
                                            {
                                            echo "<center><b>Administrador atualizado com sucesso!</b></center><br />";
                                            } else if ($msg == 3)
                                            {
                                            echo "<center><b>Administrador deletado com sucesso!</b></center><br />";
                                            }
                                        ?>                    

                                        <p align="right"> <a href="cadastrarUsuario.php?tipo=1" class="botao" >Cadastrar Administrador </a><p>

                                        <p>
                                        <div align='center'>                                   
                                            <?php
                                            include("dbconnect.inc.php");

                                            $per_page = 10;

                                            $usuarioID = $_SESSION["usuarioID"];
                                            $busca = (isset($_POST['busca'])) ? $_POST['busca'] : '';

                                            $result = mysqli_query($_SG['link'], "SELECT SQL_CACHE u.id,u.estado,u.tipo,u.nome,u.email, u.fone FROM usuarios AS u WHERE u.tipo = '1' AND estado = '1' AND (u.nome like '%$busca%' OR u.email like '%$busca%' OR u.fone like '%$busca%') ORDER BY u.email")
                                                    or die(mysqli_error());

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
                                                        echo "<li ><a class='current' href='administradores.php?page=$i'> $i </a></li>";
                                                        } else
                                                        {
                                                        echo "<li><a href='administradores.php?page=$i'> $i </a></li>";
                                                        }
                                                    } else
                                                    {
                                                    echo "<li ><a class='current' href='administradores.php?page=1'> 1 </a></li>";
                                                    $pg_atual = 1;
                                                    }
                                            }


                                            echo " </tr></td> </ul></table>";
                                            ?>



                                            <table id="rounded-corner">
                                                <tr><th scope="col" class="rounded-company">Nome</th><th>E-mail</th><th>Fone</th><th>Editar</th><th>Excluir</th></tr>
                                                
                                                <?php
                                                //grid de administradores
                                                while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) { //for ($i = $start; $i < $end; $i++)

                                                    echo "<tr>";
                                                    echo '<td>' . $row['nome'] . '</td>';
                                                    echo '<td>' . $row['email'] . '</td>';
                                                    echo '<td>' . $row['fone'] . '</td>';
                                                    ?>
                                                    <td><a href="editarUsuario.php?id=<?php echo $row['id'] ?>&tipo=1">Editar</a></td>

                                                    <td><a href="javascript:confirmaExclusao('deletarUsuario.php?id=<?php echo mysql_result($result, $i, 'id') ?>&tipo=1')">Excluir</a></td>


                                                    <?php
                                                    echo "</tr>";
                                                }
                                                ?>






                                            </table>
                                        </div>


                                        </p>

                                    </div>
                                </div>

                                <div class='g1'>
                                    <div class='main-links sidebar'>
                                        <ul>
                                            <li>
                                                <a href='administradores.php'><b>Administradores</b></font></a>
                                            </li>
                                            <li>
                                                <a href='professores.php'>Professores</a>
                                            </li>
                                            <li>
                                                <a href='secretarios.php'>Secretários(as)</a>
                                            </li>                                    

                                        </ul>
                                    </div>
                                </div> 

                            </section><!-- content -->
                        </div>
                        <!-- End About Tab Data -->


                    </div>
                </div>
                <!-- End Tab Container -->
                <footer>
                    <p>
                        Sistema de Avaliação PSPPGARTES
                    </p>
                </footer>
            </div><!-- #main -->
        </div><!-- #main-container -->



    </body>
</html>
