<!DOCTYPE html>
<html lang="pt-BR" class="no-js">
<head>
    <?php
    header("Content-type: text/html; charset=UTF-8");
    set_include_path('/psppgartes');
    include "head.html";
    ?>
</head>
<body class="bg-fixed bg-1">
    <div class="main-container">
        <div class="main wrapper clearfix">
            <header id="header">
                <div id="logo">
                </div>
            </header>
            <div id="tab-container" class="tab-container">
                <div id="tab-data-wrap">
                    <div id="login">
                        <section class="clearfix">
                            <div class="g3">
                                <div class="break"></div>
                                <div class="contact-info">
                                    <div class="container">
                                        <section id="content">
                                            <form method="post" action="principal/valida.php">
                                                <h1>Entrar no Sistema</h1>
                                                <div>
                                                    <input type="email" name="usuario" required placeholder="E-mail" id="usuario" />
                                                </div>
                                                <br/>
                                                <div>
                                                    <input type="password" name="senha" required placeholder="Senha" id="senha" />
                                                </div>
                                                <br/>
                                                <?php
                                                if (isset($_GET['msg']) && $_GET['msg'] == 1) {
                                                    echo "<div><font color='#983e3e' size='3'>Usuário ou senha incorretos.</font></div>";
                                                }
                                                ?>
                                                <div>
                                                    <input type="submit" value="Entrar" />
                                                </div>
                                                <br/>
                                            </form>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <footer>
                <?php include "principal/rodape.html"; ?>
            </footer>
        </div>
    </div>
</body>
</html>