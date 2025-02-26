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
</head>
<div style="text-align:right;">
            <?php if($_SESSION['usuarioNome'] != "") { ?>
   
            <div class="logout" >
         
                       
                 <form id = "logout" name="logout" method="post" action="logout.php">
                        <?php echo "Olá <b>".$_SESSION['usuarioNome']."</b>, seja bem vindo."; ?>
                             
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
      <!-- Header Start 
        <header>
            <div align="center">
                <h2>
                    Cordas da Amazônia
                </h2>
                <h4>
                    Sistema de Avaliação
                </h4>
            </div>
        </header>
        Header End -->
        <!-- Main Tab Container -->
        <div id="tab-container">
<?php 
    $ind=0;
    $user=1;
    $cand=0;
    $ava=0;
    $perf=0;

    $processo = (isset($_GET['processo'])) ? $_GET['processo'] : ''; 
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
<?php include("dbconnect.inc.php");
    $id=$_GET['id'];
    $tipo=$_GET['tipo'];
	$result = mysqli_query($_SG['link'], "SELECT a.id,a.tipo, a.estado,a.nome,a.email, a.fone FROM usuarios a WHERE a.id=$id and a.tipo=$tipo")
		or die(mysqli_error());
 ?>

                
	<div id="container">
        <div id="contact-form" class="clearfix">
            <h1>Dados do Usuário</h1>
           <!-- <h2>Descrição</h2>     -->

           <form method="post" action="atualizarUsuario.php?id=<?php echo $id; ?>&tipo=<?php echo $tipo; ?>">
          
                <label for="nome">Nome: <span class="required">*</span></label>
                <input type="text" id="nome" name="nome" value="<?php echo mysql_result($result, 0, 'nome'); ?>" placeholder="" required  autofocus/>
                
                <label for="email">E-mail: <span class="required">*</span></label>
                <input type="email" id="email" name="email" value="<?php echo mysql_result($result, 0, 'email'); ?>" placeholder="" required />
                               
                <label for="fone">Telefone: <span class="required">*</span></label>
                <input type="tel" id="fone" name="fone" value="<?php echo mysql_result($result, 0, 'fone'); ?>" required/>
                

               <!-- <label for="message">Message: <span class="required">*</span></label>
                <textarea id="message" name="message" placeholder="Your message must be greater than 20 charcters" required data-minlength="20"></textarea> -->
                
                <!--<span id="loading"></span>    -->
                <br/>
                <input type="submit" value="Salvar" id="submit-button" />
                <p id="req-field-desc"><span class="required">*</span> Campo obrigatório</p>
  
            </form>

        </div>
                              

</div><!-- container -->

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
    </div><!-- #main -->
</div><!-- #main-container -->



</body>
</html>
