
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <?php header("Content-type: text/html; charset=UTF-8"); ?>
    <head>

        <?php
        set_include_path('/psppgartes');
        include "head.html";
        ?>
    </head>
    <body class="bg-fixed bg-1">
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
            <![endif]-->
        <div class="main-container">
            <div class="main wrapper clearfix">
                <!-- Header Start -->
                <header id="header">
                    <div id="logo">
                        <h2>

                        </h2>
                        <h4>

                        </h4>
                    </div>
                </header>
                <!-- Header End -->
                <!-- Main Tab Container -->
                <div id="tab-container" class="tab-container">

                    <!-- Tab List -->
                    <!--   <ul class='etabs'>
                           
                           
                           <li class='tab' id="tab-about">
                             <a href="index.php"><i class="icon-file-text"></i><span> <font color='#555' size='18'><b>P�gina Inicial </b></font></span></a>
                           </li>
           
                                           <li class='tab'>
                               <a href="arquivos.php"><i class="icon-user"></i><span> Arquivos </span></a>
                           </li>
                                           
                                           <li class='tab'>
                               <a href="inscricao.php"><i class="icon-user"></i><span> Inscri��o </span></a>
                           </li>
                                           
           
                                           
                           <li class='tab'>
                               <a href="login.php"><i class="icon-user"></i><span> Entrar </span></a>
                           </li>
                    <!-- <li class='tab'>
                        <a href="#portfolio"><i class="icon-heart"></i><span> Portfolio</span></a>
                      </li>
                      <li class='tab'>
                        <a href="#contact"><i class="icon-envelope"></i><span> Contact</span></a>
                      </li>   
                  </ul>-->
                    <!-- End Tab List -->

                    <div id="tab-data-wrap">
                        <!-- About Tab Data -->
                        <div id="login">
                            <section class="clearfix">
                                <div class="g3">

                                    <div class="break"></div>
                                    <div class="contact-info">

                                        <div class="g3">



                                            <!--<!DOCTYPE html>
                                            <!--[if lt IE 7 ]> <html lang="en" class="ie6 ielt8"> <![endif]-->
                                            <!--[if IE 7 ]>    <html lang="en" class="ie7 ielt8"> <![endif]-->
                                            <!--[if IE 8 ]>    <html lang="en" class="ie8"> <![endif]-->
                                            <!--[if (gte IE 9)|!(IE)]><!-->
                                            <html lang="en"> <!--<![endif]-->



                                                <div class="container">
                                                    <section id="content">

                                                        <form method="post" action="principal/valida.php">
                                                            <h1>Entrar no Sistema</h1>


                                                            <div>

                                                                <input type="email"  name="usuario" required="" placeholder="E-mail" id="usuario" />
                                                            </div><br/>

                                                            <div>

                                                                <input type="password"  name="senha" required="" placeholder="Senha" id="senha" />
                                                            </div><br/>

                                                            <?php
                                                            $msg = (isset($_GET['msg'])) ? $_GET['msg'] : '';
                                                            if ($msg == 1) 
                                                                {
                                                                echo "<div><font color='#983e3e' size='3'>Usuário ou senha incorretos.</font></div>";
                                                                }
                                                            ?>                      
                                                            <div>
                                                                <input type="submit" value="Entrar" />
                                                                <!--	<a href="#">Lost your password?</a>
                                                                        <a href="#">Register</a> -->
                                                            </div><br/>

                                                        </form><!-- form --> 

                                                    </section><!-- content -->
                                                </div><!-- container --> 



                                        </div>
                                    </div>
                                </div>
                            </section><!-- content -->
                        </div>
                        <!-- End About Tab Data -->

                    </div>
                </div>
                <!-- End Tab Container -->
                <footer>
<?php include "principal/rodape.html"; ?>
                </footer>
            </div><!-- #main -->
        </div><!-- #main-container -->



    </body>
</html>
