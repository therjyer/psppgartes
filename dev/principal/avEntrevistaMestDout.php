<?php
include("seguranca.php"); // Inclui o arquivo com o sistema de segurança
protegePagina(); // Protege a página
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include "head.html"; ?>

    <script type="text/javascript" language="javascript">
        function confirmaExclusao(aURL) {
            if (confirm('Você tem certeza que deseja tornar este usuário inativo?')) {
                location.href = aURL;
            }
        }
    </script>  
</head>

<?php include ("header.php"); ?>

<body class="bg-fixed bg-1">
    <div class="main-container">
        <div class="main wrapper clearfix">
            <!-- Main Tab Container -->
            <div id="tab-container">
                <?php
                $idProcesso = isset($_GET['idProcesso']) ? $_GET['idProcesso'] : '';
                $idProcesso = mysqli_real_escape_string($_SG['link'], $idProcesso); // Evitar SQL Injection
                
                // Consulta para obter o nome do processo
                $consultaProcesso = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = '$idProcesso'");
                $row = mysqli_fetch_row($consultaProcesso);
                $processo = $row[0];
                
                // Pegando o nome do professor logado
                $nomeProfessor = $_SESSION['usuarioNome'];
                $consultaProfessor = mysqli_query($_SG['link'], "SELECT u.id FROM usuarios u WHERE u.nome = '$nomeProfessor' AND u.estado=1");
                $rowprofessor = mysqli_fetch_row($consultaProfessor);
                $idProfessor = $rowprofessor[0];
                ?>

                <!-- Menu -->
                <?php include("menu.php"); ?>

                <div id="tab-data-wrap">
                    <div id="usuarios">
                        <section class="clearfix">
                            <div class="g3">
                                <div class="info">
                                    <h4>Selecione um candidato para avaliar - Entrevista</h4>
                                </div>
                                <div align="center">
                                    <form class="form-wrapper cf" method="post" action="avEntrevista1.php?idProcesso=<?php echo $idProcesso; ?>">
                                        <input name="busca" id="busca" type="text" placeholder="Digite o texto da pesquisa..." required>
                                        <button type="submit">Pesquisar</button>
                                    </form>
                                </div>                                   
                            </div>

                            <?php
                            // Exibindo mensagens
                            $msg = isset($_GET['msg']) ? $_GET['msg'] : '';
                            if ($msg == 1) {
                                echo "<center><b>Avaliação realizada com sucesso!</b></center><br />";
                            } elseif ($msg == 2) {
                                echo "<center><b>Candidato atualizado com sucesso!</b></center><br />";
                            } elseif ($msg == 3) {
                                echo "<center><b>Candidato deletado com sucesso!</b></center><br />";
                            }
                            ?>

                            <div align='center'>
                                <p>
                                    <?php
                                    include("dbconnect.inc.php");
                                    $per_page = 10;
                                    $usuarioID = $_SESSION["usuarioID"];
                                    $busca = isset($_POST['busca']) ? $_POST['busca'] : '';

                                    // Consulta SQL com filtro de busca
                                    $result = mysqli_query($_SG['link'], "
                                        SELECT c.estado, c.estadoEntrevista, c.idCandidato, c.cpf, c.numInscricao, c.nome, 
                                               c.notaProva1, c.notaProva2, c.notaAnteprojeto1, c.notaAnteprojeto2, 
                                               c.avaliadorEntrevista1, c.avaliadorEntrevista2, c.notaEntrevista, c.tipoProcesso 
                                        FROM candidato c 
                                        WHERE (c.cpf LIKE '%$busca%') 
                                          AND (((c.notaAnteprojeto1 + c.notaAnteprojeto2) / 2) >= 7) 
                                          AND (c.optOrientador1 = '$idProfessor') 
                                          AND c.estado = 1 
                                          AND c.processo = '$idProcesso'  
                                          AND c.estadoHomologacao = 1 
                                        ORDER BY c.nome
                                    ") or die(mysqli_error($_SG['link']));

                                    $total_results = mysqli_num_rows($result);
                                    if ($total_results > 0) {
                                    ?>

                                    <table id="rounded-corner">
                                        <tr>
                                            <th scope="col" class="rounded-q1">Estado</th>
                                            <th scope="col" class="rounded-q1">Tipo</th>   
                                            <th scope="col" class="rounded-q1">Nome</th>
                                            <th scope="col" class="rounded-q2">Nota</th>
                                        </tr>

                                        <?php
                                        while ($row = mysqli_fetch_array($result)) {
                                            $estadoEntrevista = ($row['estadoEntrevista'] == 0) ? "Não Avaliado" : "Avaliado";
                                            $tipoProcesso = ($row['tipoProcesso'] == 1) ? "Mestrado" : "Doutorado";
                                            $idCandidato = $row['idCandidato'];
                                            echo "<tr onclick=\"document.location = 'avEntrevistaFicha.php?idCandidato=$idCandidato&idProcesso=$idProcesso';\">";
                                            echo '<td>' . $estadoEntrevista . '</td>';
                                            echo '<td>' . $tipoProcesso . '</td>';
                                            echo '<td>' . $row['nome'] . '</td>';
                                            echo '<td>' . $row['notaEntrevista'] . '</td>';
                                            echo "</tr>";
                                        }
                                        ?>

                                    </table>

                                    <?php
                                    } else {
                                        echo "<br /><center>Nenhum candidato para esta fase.</center><br />";
                                    }
                                    ?>
                                </p>
                            </div>  
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <?php include "rodape.html"; ?>
    </footer>
</body>
</html>