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
                    $cand = 1;
                    $ava = 0;
                    $perf = 0;

                    $idProcesso = $_GET['idProcesso'];
                    $consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
                    $row = mysqli_fetch_row($consulta);
                    $processo = $row[0];
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
                                        <?php
                                        $id = $_GET['id'];
                                        $result = mysqli_query($_SG['link'], "SELECT * FROM `vieweditarcandidatos` WHERE `idCandidato` = $id")
                                                or die(mysql_error());
                                        //$row = mysqli_fetch_row($consulta)
                                        $row = mysqli_fetch_array($result);
                                        ?>


                                        <div id="container">
                                            <div id="contact-form" class="clearfix">
                                                <h1>Dados do Candidato</h1>
                                                <!-- <h2>Descri��o</h2>     -->

                                                <form method="post" action="../processo/atualizarCandidato.php?id=
                                                      <?php echo $id; ?>&idProcesso=<?php echo $idProcesso; ?>">

                                                    <label for="numInscricao">Número de inscrição: <span class="required">*</span></label>
                                                    <input type="text" id="numInscricao" name="numInscricao" value="<?php echo $row['numInscricao']; ?>" placeholder="" required autofocus/>

                                                    <label for="nome">Nome do candidato: <span class="required">*</span></label>
                                                    <input type="text" id="nome" name="nome" value="<?php echo $row['nome']; ?>" placeholder="" required  />

                                                    <label for="email">Email Address: <span class="required">*</span></label>
                                                    <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" placeholder="" required />

                                                    <label for="cpf">CPF <span class="required">*</span></label>
                                                    <input type="text" id="cpf" name="cpf" value="<?php echo $row['cpf']; ?>" placeholder="" required />




                                                    <?php $linhaPesquisa = $row['optLinhaPesquisa']; ?>

                                                    <label for="linhaPesquisa">Linha de pesquisa: </label>
                                                    <select id="linhaPesquisa" name="linhaPesquisa" >
                                                        <?php
                                                        if ($linhaPesquisa == '1')
                                                            {
                                                            echo "   <option value='1'>Poéticas e processos de atuação em artes</option>
                                                            <option value='2'>Teorias e Interfaces Epistemicas em Artes</option>
							  <option value='3'>Memórias, Histórias e Educação em Artes</option>";
                                                            } else if ($linhaPesquisa == '2')
                                                            {
                                                            echo "   <option value='2'>Teorias e Interfaces Epistemicas em Artes</option>
							  <option value='3'>Memórias, Histórias e Educação em Artes</option>
                              <option value='1'>Poéticas e processos de atuação em artes</option>";
                                                            } else if ($linhaPesquisa == '3')
                                                            {
                                                            echo "   <option value='3'>Memórias, Histórias e Educação em Artes</option>
							  <option value='2'>Teorias e Interfaces Epistemicas em Artes</option>
                              <option value='1'>Poéticas e processos de atuação em artes</option>";
                                                            } else
                                                            {
                                                            echo "<option value=''>Selecionar:</option>
                                                                <option value='linha1'>Poéticas e processos de atuação em artes</option>
                                                                <option value='linha2'>Teorias e Interfaces Epistemicas em Artes</option>
                                                                <option value='linha3'>Memórias, Histórias e Educação em Artes</option>";
                                                            }
                                                        ?>

                                                    </select>

                                                    <?php $areaAtuacao = $row['optCampo']; ?>

                                                    <label for="areaAtuacao">Campo de Atuação: </label>
                                                    <select id="areaAtuacao" name="areaAtuacao" >
                                                        <?php
                                                        if ($areaAtuacao == '2')
                                                            {
                                                            echo "<option value='2'>Música</option>
                                                            <option value='1'>Teatro</option>
                                                            <option value='3'>Dança</option>
                                                            <option value='4'>Artes Visuais</option>
                                                            <option value='5'>Cinema</option>
                                                            <option value='0'>Outros</option>";
                                                            } else if ($areaAtuacao == '1')
                                                            {
                                                            echo "<option value='1'>Teatro</option>
                                                            <option value='2'>Música</option>
                                                            <option value='3'>Dança</option>
                                                            <option value='4'>Artes Visuais</option>
                                                            <option value='5'>Cinema</option>
                                                            <option value='0'>Outros</option>";
                                                            } else if ($areaAtuacao == '3')
                                                            {
                                                            echo "   <option value='3'>Dança</option>
                                                            <option value='1'>Teatro</option>
                                                            <option value='2'>Música</option>							  				  
							  <option value='4'>Artes Visuais</option>
							  <option value='5'>Cinema</option>
							  <option value='0'>Outros</option>";
                                                            } else if ($areaAtuacao == '4')
                                                            {
                                                            echo "   <option value='4'>Artes Visuais</option>
                                                            <option value='3'>Dança</option>
                                                            <option value='1'>Teatro</option>
                                                            <option value='2'>Música</option>
                                                            <option value='5'>Cinema</option>							  
                                                            <option value='0'>Outros</option>";
                                                            } else if ($areaAtuacao == '5')
                                                            {
                                                            echo "   <option value='5'>Cinema</option>
                                                            <option value='3'>Dança</option>
                                                            <option value='1'>Teatro</option>
                                                            <option value='2'>Música</option>
                                                            <option value='4'>Artes Visuais</option>							  
                                                            <option value='0'>Outros</option>";
                                                            } else if ($areaAtuacao == '0')
                                                            {
                                                            echo "   <option value='0'>Outros</option>
                                                            <option value='4'>Artes Visuais</option>
                                                            <option value='3'>Dança</option>
                                                            <option value='1'>Teatro</option>
                                                            <option value='5'>Cinema</option>
                                                            <option value='2'>Música</option>";
                                                            } else
                                                            {
                                                            echo "<option value=''>Selecionar:</option>
                                                            <option value='musica'>2</option>
                                                            <option value='teatro'>1</option>
                                                            <option value='danca'>3</option>
                                                            <option value='4'>Artes Visuais</option>
                                                            <option value='5'>Cinema</option>
                                                            <option value='6'>Outros</option>";
                                                            }
                                                        ?>

                                                    </select>				

                                                    <?php $cotas = $row['cotas']; ?>

                                                    <label for="cotas">Grupos:</label>
                                                    <select id="cotas" name="cotas" >
                                                        <?php
                                                        if ($cotas == 'ac')
                                                            {

                                                            echo "<option value = 'ac'>AC - Ampla Concorrência</option>
                                                            <option value = 'er'>ER - Etnoracial</option>";
                                                            } else if ($cotas == 'er')
                                                            {
                                                            echo "<option value = 'er'>ER - Etnoracial</option>
                                                            <option value = 'ac'>AC - Ampla Concorrência</option>";
                                                            } else
                                                            {
                                                            echo "<option value=''>Selecionar:</option><option value = 'ac'>AC - Ampla Concorrência</option>
                                                            <option value = 'er'>ER - Etnoracial</option>";
                                                            }
                                                        ?>
                                                    </select>      
                                                    <!-- 2018
                                                    else if ($cotas == 'padt') {
                                                            echo "<option value = 'padt'>PADT</option>
                                                            <option value = 'ac'>AC - Ampla Concorr�ncia</option>
                                                            <option value = 'er'>ER - Etnoracial</option>
                                                            <option value = 'tc'>TC</option>";
                                                        } else if ($cotas == 'tc') {
                                                            echo "<option value = 'tc'>TC</option>
                                                            <option value = 'padt'>PADT</option>
                                                            <option value = 'ac'>AC - Ampla Concorr�ncia</option>
                                                            <option value = 'er'>ER - Etnoracial</option>
                                                            ";
                                                        }
                                                    
                                                    
                                                    -->



<!-- <label for="message">Message: <span class="required">*</span></label>
<textarea id="message" name="message" placeholder="Your message must be greater than 20 charcters" required data-minlength="20"></textarea> -->

<!--<span id="loading"></span>    -->
                                                    <br/>
                                                    <input type="submit" value="Salvar" id="submit-button" />
                                                    <p id="req-field-desc"><span class="required">*</span> Campo obrigatório</p>
                                                    <!--p id="req-field-desc"><span class="required">**</span> Campo obrigatório. Não selecionar o mesmo professor em mais de um campo.</p-->                
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
