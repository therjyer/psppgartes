<!--Exibe o Menu de Guias conforme o tipo do Usu�rio ap�s selecionar o Processo
Ser� alterado para carregamento din�mico de perfis
-->
<?php
$idProcesso = (isset($_GET['idProcesso'])) ? $_GET['idProcesso'] : '';
?>

<ul class='etabs'>                

    <?php
    if ($_SESSION['usuarioTipo'] == 1) {
        //Tipo 1 - Administrador
        echo "            
                         <li class='tab'>
                          <a href='cronogramaMestrado.php?idProcesso=$idProcesso'><i class='icon-user'></i><span> Mestrado </span></a>
                        </li> 
						
                         <li class='tab'>
                          <a href='cronogramaDoutorado.php?idProcesso=$idProcesso'><i class='icon-user'></i><span> Doutorado </span></a>
                        </li>  
						
                         <li class='tab'>
                          <a href='candidatosMestrado.php?idProcesso=$idProcesso'><i class='icon-user'></i><span> Candidatos Mestrado</span></a>
                        </li>   
						
                         <li class='tab'>
                          <a href='candidatosDoutorado.php?idProcesso=$idProcesso'><i class='icon-user'></i><span> Candidatos Doutorado</span></a>
                        </li>						
            ";
    } else if ($_SESSION['usuarioTipo'] == 2) {
        //Tipo 2 - Secret�rio
        echo "
            
                         <li class='tab'>
                          <a href='cronogramaMestrado.php?idProcesso=$idProcesso'><i class='icon-user'></i><span> Mestrado </span></a>
                        </li> 
						
                         <li class='tab'>
                          <a href='cronogramaDoutorado.php?idProcesso=$idProcesso'><i class='icon-user'></i><span> Doutorado </span></a>
                        </li> 
						
                         <li class='tab'>
                          <a href='candidatosMestrado.php?idProcesso=$idProcesso'><i class='icon-user'></i><span> Candidatos Mestrado</span></a>
                        </li> 
						
						<li class='tab'>
                          <a href='candidatosDoutorado.php?idProcesso=$idProcesso'><i class='icon-user'></i><span> Candidatos Doutorado</span></a>
                        </li>

            ";
    } else {
        //Tipo 3 - Professor
        echo "
                          <li class='tab'>
                          <a href='listarProcessos.php?idProcesso=$idProcesso'><i class='icon-user'></i><span> Processo de Seleção </span></a>
                        </li> 


            ";
    }
    ?>




</ul>