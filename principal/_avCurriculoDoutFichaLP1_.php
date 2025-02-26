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

        <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>  

        <script type="text/javascript">
            function bloqueia1() {

                var p1 = document.getElementById("item1");
                var p2 = document.getElementById("item2");

                if (p1.value != "") {
                    p2.disabled = true;
                }
            }

            function bloqueia2() {

                var p1 = document.getElementById("item1");
                var p2 = document.getElementById("item2");

                if (p2.value != "") {
                    p1.disabled = true;
                }
            }
        </script> 
        <script type="text/javascript">
            $(document).ready(function () {
                //Quando o valor do campo mudar...
                $('.calc').change(function () {
                    var item1 = parseFloat($('#item1').val()) || 0.0;
                    var item2 = parseFloat($('#item2').val()) || 0.0;
                    var item3 = parseFloat($('#item3').val()) || 0.0;
                    var item4 = parseFloat($('#item4').val()) || 0.0;
                    var item5 = parseFloat($('#item5').val()) || 0.0;
                    var item6 = parseFloat($('#item6').val()) || 0.0;
                    var item7 = parseFloat($('#item7').val()) || 0.0;
                    var item8 = parseFloat($('#item8').val()) || 0.0;
                    var item9 = parseFloat($('#item9').val()) || 0.0;

                    //O total � o n�mero de item x o valor de x
                    var total = item1 + item2 + item3 + item4 + item5 + item6 + item7 + item8 + item9;

                    $('#total_val').val(total.toFixed(2));
                });
            });
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
                    $consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
                    $processo = mysql_result($consulta, 0, 'processo');
                    $idCandidato = (isset($_GET['idCandidato'])) ? $_GET['idCandidato'] : '';

                    $nomeProfessor = $_SESSION['usuarioNome'];
                    $consulta2 = mysqli_query($_SG['link'], "SELECT u.id FROM usuarios u WHERE u.nome = '$nomeProfessor' AND u.estado=1");
                    $idProfessor = mysql_result($consulta2, 0, 'id');
                    $linha = "linha2";
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
                                                Ficha de Avalia��o da Prova de T�tulos - Mestrado
                                            </h4>
                                        </div>

                                        <div align="center">

                                            <form  id="avProva" name="avProva" method="post"  action="pontuacaoCurriculo.php?idCandidato=<?php echo $idCandidato; ?>&idProcesso=<?php echo $idProcesso; ?>&idProfessor=<?php echo $idProfessor; ?>&idTipo=1"  >

                                                <table id="rounded-corner">

                                                    <tr>
                                                        <th scope="col" class="rounded-company" width="20">Roteiro</th>
                                                        <th scope="col" class="rounded-q1" width="50">Nota</th>
                                                        <th scope="col" class="rounded-q2" width="30">Nota Alcan�ada</th>
                                    <!-- TODOS -->                        
                                                    <tr>
                                                        <td align="left">1. FORMA��O (Os candidatos ser�o pontuados no subitem 1.1 ou 1.2) </td>
                                                        <td></td>
                                                        <td> </td>                
                                                    </tr>

                                                    <tr>
                                                        <td align="left">1.1- Curso superior na �rea de artes.</td>
                                                        <td>0.00 - 2.00</td>  
                                                        <td><input type="number" id="item1" name="item1" class="calc" min="0" max="7.00" step ="0.01" size=90 onblur="bloqueia1();" required></td>   
                                                    </tr>

                                                    <tr>
                                                        <td align="left">1.2- Curso superior em quaisquer outras �reas (2 pto.) e curso t�cnico (n�vel m�dio) na �rea de artes (1 pto.) ou comprova��o de atua��o, de tr�s anos, na �rea de artes. (1 pto.)</td>
                                                        <td>0.00 - 2.00</td>  
                                                        <td><input type="number" id="item2" name="item2" class="calc" min="0" max="7.00" step ="0.01" size=90 onblur="bloqueia2();" required></td>   
                                                    </tr>

                                                    <tr>
                                                        <td align="left">1.3 - Forma��o continuada: Participa��o como ouvinte em eventos cient�ficos na �rea de artes (cursos de extens�o, semin�rios, congressos e similares). 0,15 pto. por evento de no m�nimo 20 horas. (M�ximo 1 pto.) </td>
                                                        <td>0.00 - 1.00</td>  
                                                        <td><input type="number" id="item3" name="item3" class="calc" min="0" max="1.00" step ="0.01" size=90 required=""></td>   
                                                    </tr>
<!--
<!--
                                                    <!--
                                                    <tr>
                                                        <td> <b>Subtotal 1</b></td>
                                                        <td><b>0.00 - 3.00</b></td>  
                                                        <td><input type="text" id="total_val" name="total_val" class="result" readonly="readonly"></td>   
                                                    </tr>
                                                    -->
                                                    
                                    <!-- LP 1 -->
                                                    <tr>
                                                        <td align="left"><b> 2. ATUA��O NA �REA DAS ARTES (LP1 exclusivamente)</b></td>
                                                        <td></td>  
                                                        <td></td>   
                                                    </tr>			

                                                    <tr>
                                                        <td align="left">2.1- Atua��o na �rea de artes como artista, 
                                                            produtor, t�cnico, e/ou funcion�rio/membro de institui��es culturais 
                                                            p�blicas ou privadas. 0.20 pto. por cada atua��.</td>
                                                        <td>0.00 - 1.00</td>  
                                                        <td><input type="number" id="item4" name="item4" class="calc" min="0" max="1.00" step ="0.01" size=90 required=""></td>   
                                                    </tr>
  
                                                    <tr>
                                                        <td align="left">2.2- Projetos Desenvolvidos- (Pesquisa Art�stica de Produ��o 
                                                            art�stica e ou produ��o t�cnica; conforme definido no item 8.3.3. do edital.</td>
                                                        <td>0.00 - 1.00</td>  
                                                        <td><input type="number" id="item5" name="item5" class="calc" min="0" max="1.00" step ="0.01" size=90 required=""></td>   
                                                    </tr>
                                                    <tr>
                                                        <td align="left">2.3- Premia��o ? 0,20 por Premia��o de projetos art�sticos,
                                                            conforme definido no item 8.3.3.do edital.</td>
                                                        <td>0.00 - 1.00</td>  
                                                        <td><input type="number" id="item6" name="item6" class="calc" min="0" max="1.00" step ="0.01" size=90 required=""></td>   

                                                    <tr>
                                                        <td align="left"><b> 3. PRODU��O ESCRITA</b></td>
                                                        <td></td>  
                                                        <td></td>   
                                                    </tr>
                                                    <tr>
                                                        <td align="left">3.1- Atua��o em eventos cient�fico-culturais na �rea 
                                                            (palestrante, comunicador ou ministrante em cursos de extens�o, congressos e similares). 
                                                            0.20 pto. por cada atua��o. Conforme o Anexo 5.</td>
                                                        <td>0.00 - 1.00</td>  
                                                        <td><input type="number" id="item7" name="item7" class="calc" min="0" max="1.00" step ="0.01" size=90 required=""></td>   
                                                    </tr>
                                                    <tr>
                                                        <td align="left">3.2- Publica��o de livro, cap�tulo, artigo, ensaio, entrevista, tradu��o, 
                                                            edi��o ou organiza��o em ve�culos com ISBN ou ISSN, impresso ou online. 
                                                            0.20 pto. por cada publica��o. Conforme o Anexo 5.</td>
                                                        <td>0.00 - 1.00</td>  
                                                        <td><input type="number" id="item8" name="item8" class="calc" min="0" max="1.00" step ="0.01" size=90 required=""></td>   
                                                    </tr>     
                                                        <td align="left"><b> 4. PORTIFOLIO</b></td>
                                                        <td></td>  
                                                        <td></td>   
                                                    </tr>
                                                    <tr>
                                                        <td align="left">3.1- Portf�lio .</td>
                                                        <td>0.00 - 2.00</td>  
                                                        <td><input type="number" id="item9" name="item9" class="calc" min="0" max="2.00" step ="0.01" size=90 required=""></td>   
                                                    </tr>                                                    
                                    <!-- TOTAL -->
                                                     <tr>
                                                        <td> <b>Total</b></td>
                                                        <td><b>0.00 - 10.00</b></td>  
                                                        <td><input type="text" id="total_val" name="total_val" class="result" readonly="readonly"></td>   
                                                    </tr>
                                                    <tr>
                                                        <td> </td>
                                                        <td></td>  
                                                        <td><input type="submit" value="Finalizar" /></td>   
                                                    </tr>
                                                </table>                                    

                                            </form>






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
                    <?php include "rodape.html"; ?>
                </footer>
            </div><!-- #main -->
        </div><!-- #main-container -->



    </body>
</html>
