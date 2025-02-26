<!-- Chamado de Listar Processos
    Exibe o nome do usu�rio quando em Listar Processos
    Exibo o ano do processo ap�s selecionar processo

-->


<div style="text-align:right;">

    <div style="float:left;">
        <!-- img src="1521748832379-110310236.jpg" width="99" height="55" alt="PPGARTES"/ -->
        <img src="images/ppg_logo_small_1.png" width="99" height="55" alt="PPGARTES"/>
    </div>
    <?php if ($_SESSION['usuarioNome'] != "")
        { ?>

        <div class="logout" style="float:left;" >
            <form id = "logout" name="logout" method="post" action="logout.php">
    <?php echo "<b>[" . $_SESSION['usuarioNome'] . "]</b>"; ?>

                <input align="right" class='botao' type="submit" value="Sair" />
            </form>
        </div>

<?php } ?>      
</div> 
<div style="text-align:right;">
    <?php
    $idProcesso = (isset($_GET['idProcesso'])) ? $_GET['idProcesso'] : '';
    //$consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso"); php5
    $consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
    //$processo = mysqli_result($consulta, 0, 'processo'); php5
    $row = mysqli_fetch_array($consulta);
    $processo = $row['processo'];
    ?>
    <div class="logout" style="float:left;" >
        Processo Seletivo <?php echo $processo; ?><br />  <a style="color:#fff;" href="listarProcessos.php"> <u> Selecionar outro Processo</u></a><br /><br />
    </div>


</div> 
