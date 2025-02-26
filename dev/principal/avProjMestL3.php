<?php
include("seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
// include("dbconnect.inc.php");
protegePagina(); // Chama a fun��o que protege a p�gina
?>
<!DOCTYPE html>
<html class="no-js"> <!--<![endif]-->
    <head>
        <?php include "head.html"; ?>

        <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>   
        <script type="text/javascript">
            $(document).ready(function () {
                //Quando o valor do campo mudar...
                $('.calc').change(function () {
                    var item1 = parseFloat($('#item1').val()) || 0.0;
                    var item2 = parseFloat($('#item2').val()) || 0.0;
                    var item3 = parseFloat($('#item3').val()) || 0.0;
                    //var item4 = parseFloat($('#item4').val()) || 0.0;


                    //O total � o n�mero de refei��es x o valor da refei��o
                    var total = item1 + item2 + item3; // + item4;

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
                    $consultaidProcesso = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
                    $row = mysqli_fetch_row($consultaidProcesso);
                    $processo = $row[0];
                    $idCandidato = (isset($_GET['idCandidato'])) ? $_GET['idCandidato'] : '';
                    $nomeProfessor = $_SESSION['usuarioNome'];
                    $consultaidProfessor = mysqli_query($_SG['link'], "SELECT u.id FROM usuarios u WHERE u.nome = '$nomeProfessor' AND u.estado=1");
                    $rowprofessor = mysqli_fetch_row($consultaidProfessor);
                    $idProfessor = $rowprofessor[0];                 
                    $consultaLinha = mysqli_query($_SG['link'], "SELECT c.linhaPesquisa FROM candidato c WHERE c.idCandidato = '$idCandidato'");
                    $rowLinha = mysqli_fetch_row($consultaLinha);
                    $linhaPesquisa = $rowLinha[0];
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
                                                Ficha 3.1.2 - FICHA DE ANÁLISE DO CURRÍCULO/PORTFÓLIO/MEMORIAL/PUBLICAÇÕES - Mestrado - LP3
                                            </h4>
                                        </div>

                                        <div align="center">

                                            <form  id="avProva" name="avProva" method="post"  action="../processo/notaProjeto.php?idCandidato=<?php echo $idCandidato; ?>&idProcesso=<?php echo $idProcesso; ?>&idProfessor=<?php echo $idProfessor; ?>"  >

                                                <table id="rounded-corner">

                                                    <tr>
                                                        <th scope="col" class="rounded-company" width="20">Roteiro</th>
                                                        <th scope="col" class="rounded-q1" width="50">Nota</th>
                                                        <th scope="col" class="rounded-q2" width="30">Nota Alcançada</th>
                                                    <tr>
                                                        <td align="left">1. Formação. </td>
                                                        <td>0.00 - 3.00</td>
                                                        <td> <input type="number" id="item1" name="item1" class="calc" min="0.00" max="3.00" step ="0.01" size=200 required=""></td>                
                                                    </tr>

                                                    <tr>
                                                        <td align="left">2. Atuação na área das Artes.</td>
                                                        <td>0.00 - 5.00</td>
                                                        <td> <input type="number" id="item2" name="item2" class="calc" min="0.00" max="5.00" step ="0.01" size=200 required=""></td>                
                                                    </tr>            

                                                    <tr>
                                                        <td align="left"> 3. Memorial ou Portfólio.</td>
                                                        <td>0.00 - 2.00</td>  
                                                        <td><input type="number" id="item3" name="item3" class="calc" min="0.00" max="2.00" step ="0.01" size=90 required=""></td>   
                                                    </tr>

                                                    <!-- não tem em 20/21  tr>
                                                        <td align="left">4. As citações e referências bibliográficas deverão seguir as normas da ABNT.</td>
                                                        <td>0.00 - 1.00</td>  
                                                        <td><input type="number" id="item4" name="item4" class="calc" min="0.00" max="1.00" step ="0.01" size=90 required=""></td>   
                                                    </tr-->


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
