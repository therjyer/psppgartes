-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 05/02/2024 às 16:50
-- Versão do servidor: 10.11.4-MariaDB-1~deb12u1
-- Versão do PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `st_psppgartes`
--

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewalocavalidacurriculo`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewalocavalidacurriculo` (
`nome` varchar(200)
,`cpf` varchar(200)
,`tipoProcesso` varchar(2)
,`nomeAvaliador1` varchar(45)
,`processo` varchar(255)
,`linhaPesquisa` varchar(20)
,`areaAtuacao` varchar(20)
,`notaProjeto` double
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewandamentoatecurriculo`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewandamentoatecurriculo` (
`nome` varchar(200)
,`tipoProcesso` varchar(2)
,`nomeAvaliador1` varchar(45)
,`nomeAvaliador2` varchar(45)
,`processo` varchar(255)
,`linhaPesquisa` varchar(20)
,`areaAtuacao` varchar(20)
,`notaProjeto` double
,`pontuacaoCurriculo` float
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewandamentoateentrevista`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewandamentoateentrevista` (
`nome` varchar(200)
,`tipoProcesso` varchar(2)
,`nomeAvaliador1` varchar(45)
,`nomeAvaliador2` varchar(45)
,`processo` varchar(255)
,`linhaPesquisa` varchar(20)
,`areaAtuacao` varchar(20)
,`notaProjeto` double
,`pontuacaoCurriculo` float
,`notaEntrevista` float
,`media` double
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewCampos`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewCampos` (
`Campo` bigint(255)
,`Quantidade` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewcandidatosoptorientadores`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewcandidatosoptorientadores` (
`nome` varchar(200)
,`numInscricao` varchar(255)
,`tipoProcesso` varchar(2)
,`orientador1` varchar(45)
,`orientador2` varchar(45)
,`processo` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vieweditarcandidatos`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vieweditarcandidatos` (
`idCandidato` bigint(255)
,`numInscricao` varchar(255)
,`nome` varchar(200)
,`email` varchar(75)
,`cpf` varchar(200)
,`optLinhaPesquisa` bigint(255)
,`optCampo` bigint(255)
,`cotas` varchar(200)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewHomologExtra`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewHomologExtra` (
`idCandidato` bigint(255)
,`numInscricao` varchar(255)
,`txtNome` varchar(200)
,`nomeLinha` varchar(20)
,`nomeArea` varchar(20)
,`Orientador1` varchar(45)
,`Orientador2` varchar(45)
,`cotas` varchar(200)
,`optTipoProcesso` varchar(2)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewHtmlInscritos`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewHtmlInscritos` (
`idCandidato` bigint(255)
,`txtCPF` varchar(11)
,`numInscricao` varchar(255)
,`optTipoProcesso` bigint(255)
,`txtNome` varchar(200)
,`txtEmail` varchar(50)
,`txtHtml` text
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewInscritos`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewInscritos` (
`idCandidato` bigint(255)
,`Número de Inscrição` varchar(255)
,`Nome` varchar(200)
,`Data de Cadastro` datetime
,`TIPO DE PROCESSO` varchar(8)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewinstituições`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewinstituições` (
`Instituição` varchar(20)
,`Quantidade` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewnotascurriculo`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewnotascurriculo` (
`numInscricao` varchar(255)
,`nome` varchar(200)
,`nomeavaliador` varchar(45)
,`pontuacaocurriculo` float
,`tipoProcesso` varchar(2)
,`processo` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewnotasentrevista`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewnotasentrevista` (
`numInscricao` varchar(255)
,`nome` varchar(200)
,`nomeavaliador` varchar(45)
,`notaEntrevista` float
,`tipoProcesso` varchar(2)
,`processo` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewnotasprojeto`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewnotasprojeto` (
`numInscricao` varchar(255)
,`nome` varchar(200)
,`notaAnteprojeto1` float
,`notaAnteprojeto2` float
,`tipoProcesso` varchar(2)
,`processo` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vieworientadoresopcao`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vieworientadoresopcao` (
`id` int(11)
,`Orientador` varchar(45)
,`opcao` varchar(8)
,`candidato` varchar(200)
,`inscricao` varchar(255)
,`processo` varchar(255)
,`tipoProcesso` varchar(2)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `viewRepetidos`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `viewRepetidos` (
`numInscricao` varchar(255)
,`txtNome` varchar(200)
,`dtCadastro` datetime
);

-- --------------------------------------------------------

--
-- Estrutura para view `viewalocavalidacurriculo`
--
DROP TABLE IF EXISTS `viewalocavalidacurriculo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewalocavalidacurriculo`  AS SELECT `c`.`nome` AS `nome`, `c`.`cpf` AS `cpf`, `c`.`tipoProcesso` AS `tipoProcesso`, `u`.`nome` AS `nomeAvaliador1`, `c`.`processo` AS `processo`, `l`.`nomeLinha` AS `linhaPesquisa`, `a`.`nomeArea` AS `areaAtuacao`, (`c`.`notaAnteprojeto1` + `c`.`notaAnteprojeto2`) / 2 AS `notaProjeto` FROM (((`candidato` `c` left join `usuarios` `u` on(`c`.`avaliadorCurriculo1` = `u`.`id`)) left join `db_area` `a` on(`c`.`areaAtuacao` = `a`.`idArea`)) left join `db_linha` `l` on(`c`.`linhaPesquisa` = `l`.`idLinha`)) WHERE `c`.`estado` = 1 AND `c`.`estadoHomologacao` = 1 AND (`c`.`notaAnteprojeto1` + `c`.`notaAnteprojeto2`) / 2 >= 7 ORDER BY `u`.`nome` ASC, `l`.`idLinha` ASC, `a`.`idArea` ASC, `c`.`nome` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewandamentoatecurriculo`
--
DROP TABLE IF EXISTS `viewandamentoatecurriculo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewandamentoatecurriculo`  AS SELECT `c`.`nome` AS `nome`, `c`.`tipoProcesso` AS `tipoProcesso`, `u1`.`nome` AS `nomeAvaliador1`, `u2`.`nome` AS `nomeAvaliador2`, `c`.`processo` AS `processo`, `l`.`nomeLinha` AS `linhaPesquisa`, `a`.`nomeArea` AS `areaAtuacao`, (`c`.`notaAnteprojeto1` + `c`.`notaAnteprojeto2`) / 2 AS `notaProjeto`, `c`.`pontuacaoCurriculo` AS `pontuacaoCurriculo` FROM ((((`candidato` `c` left join `usuarios` `u1` on(`c`.`optOrientador1` = `u1`.`id`)) left join `usuarios` `u2` on(`c`.`optOrientador2` = `u2`.`id`)) left join `db_area` `a` on(`c`.`areaAtuacao` = `a`.`idArea`)) left join `db_linha` `l` on(`c`.`linhaPesquisa` = `l`.`idLinha`)) WHERE `c`.`estado` = 1 AND `c`.`estadoHomologacao` = 1 AND (`c`.`notaAnteprojeto1` + `c`.`notaAnteprojeto2`) / 2 >= 7 AND `c`.`pontuacaoCurriculo` >= 7 ORDER BY `c`.`nome` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewandamentoateentrevista`
--
DROP TABLE IF EXISTS `viewandamentoateentrevista`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewandamentoateentrevista`  AS SELECT `c`.`nome` AS `nome`, `c`.`tipoProcesso` AS `tipoProcesso`, `u1`.`nome` AS `nomeAvaliador1`, `u2`.`nome` AS `nomeAvaliador2`, `c`.`processo` AS `processo`, `l`.`nomeLinha` AS `linhaPesquisa`, `a`.`nomeArea` AS `areaAtuacao`, (`c`.`notaAnteprojeto1` + `c`.`notaAnteprojeto2`) / 2 AS `notaProjeto`, `c`.`pontuacaoCurriculo` AS `pontuacaoCurriculo`, `c`.`notaEntrevista` AS `notaEntrevista`, ((`c`.`notaAnteprojeto1` + `c`.`notaAnteprojeto2`) / 2 + `c`.`pontuacaoCurriculo` + `c`.`notaEntrevista`) / 3 AS `media` FROM ((((`candidato` `c` left join `usuarios` `u1` on(`c`.`optOrientador1` = `u1`.`id`)) left join `usuarios` `u2` on(`c`.`optOrientador2` = `u2`.`id`)) left join `db_area` `a` on(`c`.`areaAtuacao` = `a`.`idArea`)) left join `db_linha` `l` on(`c`.`linhaPesquisa` = `l`.`idLinha`)) WHERE `c`.`estado` = 1 AND `c`.`estadoHomologacao` = 1 AND (`c`.`notaAnteprojeto1` + `c`.`notaAnteprojeto2`) / 2 >= 7 AND `c`.`pontuacaoCurriculo` >= 7 AND `c`.`notaEntrevista` >= 7 ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewCampos`
--
DROP TABLE IF EXISTS `viewCampos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewCampos`  AS SELECT `db_inscritos`.`optCampo` AS `Campo`, count(0) AS `Quantidade` FROM `db_inscritos` GROUP BY `db_inscritos`.`optCampo` ORDER BY 1 ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewcandidatosoptorientadores`
--
DROP TABLE IF EXISTS `viewcandidatosoptorientadores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewcandidatosoptorientadores`  AS SELECT `c`.`nome` AS `nome`, `c`.`numInscricao` AS `numInscricao`, `c`.`tipoProcesso` AS `tipoProcesso`, `u1`.`nome` AS `orientador1`, `u2`.`nome` AS `orientador2`, `c`.`processo` AS `processo` FROM ((`candidato` `c` left join `usuarios` `u1` on(`c`.`optOrientador1` = `u1`.`id`)) left join `usuarios` `u2` on(`c`.`optOrientador2` = `u2`.`id`)) WHERE `c`.`estado` = 1 AND `c`.`estadoHomologacao` = 1 ORDER BY `c`.`processo` ASC, `c`.`nome` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `vieweditarcandidatos`
--
DROP TABLE IF EXISTS `vieweditarcandidatos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `vieweditarcandidatos`  AS SELECT `c`.`idCandidato` AS `idCandidato`, `c`.`numInscricao` AS `numInscricao`, `c`.`nome` AS `nome`, `c`.`email` AS `email`, `c`.`cpf` AS `cpf`, `c`.`optLinhaPesquisa` AS `optLinhaPesquisa`, `c`.`optCampo` AS `optCampo`, `c`.`cotas` AS `cotas` FROM `candidato` AS `c` ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewHomologExtra`
--
DROP TABLE IF EXISTS `viewHomologExtra`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewHomologExtra`  AS SELECT `candidato`.`idCandidato` AS `idCandidato`, `candidato`.`numInscricao` AS `numInscricao`, `candidato`.`nome` AS `txtNome`, `linha`.`nomeLinha` AS `nomeLinha`, `AREA`.`nomeArea` AS `nomeArea`, `U1`.`nome` AS `Orientador1`, `U2`.`nome` AS `Orientador2`, `candidato`.`cotas` AS `cotas`, `candidato`.`tipoProcesso` AS `optTipoProcesso` FROM ((((`candidato` left join `usuarios` `U1` on(`U1`.`id` = `candidato`.`optOrientador1`)) left join `usuarios` `U2` on(`U2`.`id` = `candidato`.`optOrientador2`)) left join `db_linha` `linha` on(`linha`.`idLinha` = `candidato`.`linhaPesquisa`)) left join `db_area` `AREA` on(`AREA`.`idArea` = `candidato`.`optCampo`)) WHERE `candidato`.`estado` = 1 ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewHtmlInscritos`
--
DROP TABLE IF EXISTS `viewHtmlInscritos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewHtmlInscritos`  AS SELECT `db_inscritos`.`idCandidato` AS `idCandidato`, `db_inscritos`.`txtCPF` AS `txtCPF`, `db_inscritos`.`numInscricao` AS `numInscricao`, `db_inscritos`.`optTipoProcesso` AS `optTipoProcesso`, `db_inscritos`.`txtNome` AS `txtNome`, `db_inscritos`.`txtEmail` AS `txtEmail`, concat('<table Border = 1>','<tr><td>Tipo</td><td>',if(`db_inscritos`.`optTipoProcesso` = 1,'Mestrado','Doutorado'),'</td><tr/>','<tr><td>Matrícula</td><td>',`db_inscritos`.`numInscricao`,'</td><tr/>','<tr><td>Nome</td><td>',`db_inscritos`.`txtNome`,'</td><tr/>','<tr><td>Cv Lattes</td><td>',`db_inscritos`.`txtLinkCvLattes`,'</td></tr>','<tr><td>Nacionalidade</td><td>',`db_inscritos`.`txtNacionalidade`,'</td></tr>','<tr><td>Naturalidade</td><td>',`db_inscritos`.`txtNaturalidade`,'</td></tr>','<tr><td>Data Nascimento</td><td>',`db_inscritos`.`dtNascimento`,'</td></tr>','<tr><td>NumRG</td><td>',`db_inscritos`.`txtNumRG`,'</td></tr>','<tr><td>EmissorRg</td><td>',`db_inscritos`.`txtEmissorRg`,'</td></tr>','<tr><td>CPF</td><td>',`db_inscritos`.`txtCPF`,'</td></tr>','<tr><td>Visto</td><td>',`db_inscritos`.`txtVisto`,'</td></tr>','<tr><td>Data Início Visto</td><td>',`db_inscritos`.`dtInicioVigenciaVisto`,'</td></tr>','<tr><td>Data Término Visto</td><td>',`db_inscritos`.`dtTerminoVigenciaVisto`,'</td></tr>','<tr><td>Endereco</td><td>',`db_inscritos`.`txtEndereco`,'</td></tr>','<tr><td>Telefone</td><td>',`db_inscritos`.`txtTelefone`,'</td></tr>','<tr><td>Celular</td><td>',`db_inscritos`.`txtCelular`,'</td></tr>','<tr><td>Email</td><td>',`db_inscritos`.`txtEmail`,'</td></tr>','<tr><td>Local de Prova</td><td>',`db_inscritos`.`txtLocaldeProva`,'</td></tr>','<tr><td>Atendimento Especial</td><td>',if(`db_inscritos`.`bolAtendimentoEspecial` = 1,'Sim','Não'),'</td></tr>','<tr><td>Qual Atendimento Especial</td><td>',`db_inscritos`.`txtEspecial`,'</td></tr>','<tr><td>Nome Ensino Superior</td><td>',`db_inscritos`.`txtNomeEnsinoSuperior`,'</td></tr>','<tr><td>Sigla EnsinoSuperior</td><td>',`db_inscritos`.`txtSiglaEnsinoSuperior`,'</td></tr>','<tr><td>Curso</td><td>',`db_inscritos`.`txtCurso`,'</td></tr>','<tr><td>Título</td><td>',`db_inscritos`.`txtTitulo`,'</td></tr>','<tr><td>dt Inicio Curso</td><td>',`db_inscritos`.`dtInicioCurso`,'</td></tr>','<tr><td>dt Termino Curso</td><td>',`db_inscritos`.`dtTerminoCurso`,'</td></tr>','<tr><td>Título do Projeto</td><td>',`db_inscritos`.`txtTituloProjeto`,'</td></tr>','<tr><td>Campo</td><td>',`AREA`.`nomeArea`,'</td></tr>','<tr><td>LinhaPesquisa</td><td>',`linha`.`nomeLinha`,'</td></tr>','<tr><td>Orientador1</td><td>',`U1`.`nome`,'</td></tr>','<tr><td>Orientador2</td><td>',`U2`.`nome`,'</td></tr>','<tr><td>Vinculo Empregatício</td><td>',if(`db_inscritos`.`bolVinculoEmpregaticio` = 1,'Sim','Não'),'</td></tr>','<tr><td>None da Instituição</td><td>',`db_inscritos`.`txtInstituicao`,'</td></tr>','</table><br/>') AS `txtHtml` FROM ((((`db_inscritos` left join `usuarios` `U1` on(`U1`.`id` = `db_inscritos`.`optOrientador1`)) left join `usuarios` `U2` on(`U2`.`id` = `db_inscritos`.`optOrientador2`)) left join `db_linha` `linha` on(`linha`.`idLinha` = `db_inscritos`.`optLinhaPesquisa`)) left join `db_area` `AREA` on(`AREA`.`idArea` = `db_inscritos`.`optCampo`)) ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewInscritos`
--
DROP TABLE IF EXISTS `viewInscritos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewInscritos`  AS SELECT `db_inscritos`.`idCandidato` AS `idCandidato`, `db_inscritos`.`numInscricao` AS `Número de Inscrição`, `db_inscritos`.`txtNome` AS `Nome`, `db_inscritos`.`dtCadastro` AS `Data de Cadastro`, 'MESTRADO' AS `TIPO DE PROCESSO` FROM `db_inscritos` WHERE `db_inscritos`.`optTipoProcesso` = 1 ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewinstituições`
--
DROP TABLE IF EXISTS `viewinstituições`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewinstituições`  AS SELECT `db_inscritos`.`txtSiglaEnsinoSuperior` AS `Instituição`, count(0) AS `Quantidade` FROM `db_inscritos` GROUP BY `db_inscritos`.`txtSiglaEnsinoSuperior` ORDER BY 2 DESC ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewnotascurriculo`
--
DROP TABLE IF EXISTS `viewnotascurriculo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewnotascurriculo`  AS SELECT `candidato`.`numInscricao` AS `numInscricao`, `candidato`.`nome` AS `nome`, `usuarios`.`nome` AS `nomeavaliador`, `candidato`.`pontuacaoCurriculo` AS `pontuacaocurriculo`, `candidato`.`tipoProcesso` AS `tipoProcesso`, `candidato`.`processo` AS `processo` FROM (`candidato` left join `usuarios` on(`candidato`.`avaliadorCurriculo1` = `usuarios`.`id`)) WHERE `candidato`.`estadoHomologacao` = 1 AND `candidato`.`estado` = 1 ORDER BY `candidato`.`nome` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewnotasentrevista`
--
DROP TABLE IF EXISTS `viewnotasentrevista`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewnotasentrevista`  AS SELECT `candidato`.`numInscricao` AS `numInscricao`, `candidato`.`nome` AS `nome`, `usuarios`.`nome` AS `nomeavaliador`, `candidato`.`notaEntrevista` AS `notaEntrevista`, `candidato`.`tipoProcesso` AS `tipoProcesso`, `candidato`.`processo` AS `processo` FROM (`candidato` left join `usuarios` on(`candidato`.`avaliadorCurriculo1` = `usuarios`.`id`)) WHERE `candidato`.`estadoHomologacao` = 1 AND `candidato`.`estado` = 1 ORDER BY `candidato`.`nome` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewnotasprojeto`
--
DROP TABLE IF EXISTS `viewnotasprojeto`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewnotasprojeto`  AS SELECT `candidato`.`numInscricao` AS `numInscricao`, `candidato`.`nome` AS `nome`, `candidato`.`notaAnteprojeto1` AS `notaAnteprojeto1`, `candidato`.`notaAnteprojeto2` AS `notaAnteprojeto2`, `candidato`.`tipoProcesso` AS `tipoProcesso`, `candidato`.`processo` AS `processo` FROM `candidato` WHERE `candidato`.`estadoHomologacao` = 1 AND `candidato`.`estado` = 1 ORDER BY `candidato`.`nome` ASC ;

-- --------------------------------------------------------

--
-- Estrutura para view `vieworientadoresopcao`
--
DROP TABLE IF EXISTS `vieworientadoresopcao`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `vieworientadoresopcao`  AS SELECT `u`.`id` AS `id`, `u`.`nome` AS `Orientador`, '1ª opção' AS `opcao`, `c`.`nome` AS `candidato`, `c`.`numInscricao` AS `inscricao`, `c`.`processo` AS `processo`, `c`.`tipoProcesso` AS `tipoProcesso` FROM (`candidato` `c` left join `usuarios` `u` on(`c`.`optOrientador1` = `u`.`id`)) WHERE `c`.`estadoHomologacao` = 1 ;

-- --------------------------------------------------------

--
-- Estrutura para view `viewRepetidos`
--
DROP TABLE IF EXISTS `viewRepetidos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`psppgartes`@`localhost` SQL SECURITY DEFINER VIEW `viewRepetidos`  AS SELECT `Original`.`numInscricao` AS `numInscricao`, `Original`.`txtNome` AS `txtNome`, `Original`.`dtCadastro` AS `dtCadastro` FROM `db_inscritos` AS `Original` WHERE (select count(0) from `db_inscritos` `Contador` where `Original`.`txtNome` = `Contador`.`txtNome`) > 1 ORDER BY `Original`.`txtNome` ASC, `Original`.`dtCadastro` ASC ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
