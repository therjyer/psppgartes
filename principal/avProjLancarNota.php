<?php
declare(strict_types=1);
error_reporting(-1); // maximum errors
ini_set('display_errors', '1');
include("seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
// include("dbconnect.inc.php");
protegePagina(); // Chama a fun��o que protege a p�gina
?>
<!DOCTYPE html>
<html class="no-js"> <!--<![endif]-->
    <head>
        <?php include "head.html"; ?>
        <!--script type="text/javascript" language="javascript"-->
        <script
  src="https://code.jquery.com/jquery-1.7.1.min.js"
  integrity="sha256-iBcUE/x23aI6syuqF7EeT/+JFBxjPs5zeFJEXxumwb0="
  crossorigin="anonymous"></script>
        <!--script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script-->   
        <script type="text/javascript">
            $(document).ready(function () {
                //Quando o valor do campo mudar...
                $('.calc').change(function () {
                    //Edital 2024            
                    var item11 = parseFloat($('#item11').val()) || 0.0;
                    var item12 = parseFloat($('#item12').val()) || 0.0;
                    var item21 = parseFloat($('#item21').val()) || 0.0;
                    var item22 = parseFloat($('#item22').val()) || 0.0;
                    var item31 = parseFloat($('#item31').val()) || 0.0;
                    var item32 = parseFloat($('#item32').val()) || 0.0;
                    var item40 = parseFloat($('#item40').val()) || 0.0;
                    


                    //O total � o n�mero de refei��es x o valor da refei��o
                    var total = item11 + item12 + item21 + item22 + item31 +item32 + item40;

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
                                                Ficha 1 - FICHA DE AVALIAÇÃO DO ANTEPROJETO DE PESQUISA
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
                                                        <td align="left">1.1 Relação do Projeto com a atuação acadêmica, artística e cultural dos orientadores disponíveis.</td>
                                                        <td>0.00 - 1.50</td>
                                                        <td> <input type="number" id="item11" name="item11" class="calc" min="0.00" max="1.50" step ="0.01" size=200 required=""></td>                
                                                    </tr>
                                                    <tr>
                                                        <td align="left">1.2 Relação do Projeto com a linha de pesquisa do Programa escolhida.</td>
                                                        <td>0.00 - 1.50</td>
                                                        <td> <input type="number" id="item12" name="item12" class="calc" min="0.00" max="1.50" step ="0.01" size=200 required=""></td>                
                                                    </tr>

                                                    <tr>
                                                        <td align="left">2.1 Aspectos teóricos e conceituais do Projeto proposto em consonância com a linha de pesquisa do Programa
escolhida.</td>
                                                        <td>0.00 - 1.50</td>
                                                        <td> <input type="number" id="item21" name="item21" class="calc" min="0.00" max="1.50" step ="0.01" size=200 required=""></td>                
                                                    </tr>            
                                                    <tr>
                                                        <td align="left">2.2 Aspectos práticos e metodológicos do Projeto, em consonância com a linha de pesquisa do Programa
escolhida.</td>
                                                        <td>0.00 - 1.50</td>
                                                        <td> <input type="number" id="item22" name="item22" class="calc" min="0.00" max="1.50" step ="0.01" size=200 required=""></td>                
                                                    </tr> 
                                                    <tr>
                                                        <td align="left"> 3.1 Exequibilidade da metodologia da pesquisa em relação ao Cronograma predeterminado para sua execução: Mestrado – 24 meses; Doutorado – 48 meses.</td>
                                                        <td>0.00 - 3.00</td>  
                                                        <td><input type="number" id="item31" name="item31" class="calc" min="0.00" max="1.50" step ="0.01" size=90 required=""></td>   
                                                    </tr>
                                                    <tr>
                                                        <td align="left"> 3.2 Exequibilidade dos objetivos pretendidos pela pesquisa em relação ao Cronograma predeterminado para Sua execução: Mestrado – 24 meses; Doutorado – 48 meses.</td>
                                                        <td>0.00 - 3.00</td>  
                                                        <td><input type="number" id="item32" name="item32" class="calc" min="0.00" max="1.50" step ="0.01" size=90 required=""></td>   
                                                    </tr>

                                                    <tr>
                                                        <td align="left">4. Adequação textual do Anteprojeto segundo as atuais normas da ABNT.</td>
                                                        <td>0.00 - 1.00</td>  
                                                        <td><input type="number" id="item40" name="item40" class="calc" min="0.00" max="1.00" step ="0.01" size=90 required=""></td>   
                                                    </tr>


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
