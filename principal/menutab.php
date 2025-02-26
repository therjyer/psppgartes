<!-- Exibe o Menu de Guias conforme o tipo do Usu�rio ap�s Entrar no sistema ou ao selecionar o Processo
Ser� alterado para carregamento din�mico de perfis
-->
<?php
$processo = (isset($_GET['processo'])) ? $_GET['processo'] : '';
?>
<!-- NOME OLD menu2.php -->
<ul class='etabs'>                

    <?php
    //<?php if ($perf==1) {echo "<font color='#555'><b>Meu Perfil</b></font>";}else{echo"Meu Perfil";}

    if ($_SESSION['usuarioTipo'] == 1) {
        //Tipo 1 - Administrador 
        echo "            
                         <li class='tab'>
                          <a href='listarProcessos.php'><i class='icon-user'></i><span> Processo Seletivo </span></a>
                        </li> 
                        
                         <li class='tab'>
                          <a href='administradores.php'><i class='icon-user'></i><span> Usuários </span></a>
                        </li> 

                         <li class='tab'>
                          <a href='meuPerfil.php'><i class='icon-user'></i><span> Meu Perfil </span></a>
                        </li> 

            ";
    } else if ($_SESSION['usuarioTipo'] == 2) {
        //Tipo 2 - Secret�rio
        echo "
            
                         <li class='tab'>
                          <a href='listarProcessos.php'><i class='icon-user'></i><span> Processo Seletivo </span></a>
                        </li> 

                         <li class='tab'>
                          <a href='professores.php'><i class='icon-user'></i><span> Professores </span></a>
                        </li> 

                         <li class='tab'>
                          <a href='meuPerfil.php'><i class='icon-user'></i><span> Meu Perfil </span></a>
                        </li> 

            ";
    } else {
        //Tipo 3 - Professor
        echo "
                          <li class='tab'>
                          <a href='listarProcessos.php'><i class='icon-user'></i><span> Processo de Seleção </span></a>
                        </li> 


                         <li class='tab'>
                          <a href='meuPerfil.php'><i class='icon-user'></i><span> Meu Perfil </span></a>
                        </li> 

            ";
    }
    ?>
</ul>