<?php

include("seguranca.php"); // Inclui o arquivo com o sistema de segurança
protegePagina(); // Chama a função que protege a página
//include("funcoes.php");
//conexão com o banco e consultas
include("dbconnect.inc.php");

//importa bibliotecas necessárias
require ("fpdf.php");
define("FPDF_PATH", "font");

$idProcesso = $_GET['idProcesso'];
$consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
$processo = mysql_result($consulta, 0, 'processo');

//$alocacao = mysql_result($consulta, 0, 'alocacaoProva');


class PDF extends FPDF {

    function Header() {
        global $pdf, $processo;
        //$processo = $_GET['processo']; 
        //título do relatório
        $titulo = "PROGRAMA DE PÓS-GRADUAÇÃO EM ARTES";
        $pdf->setXY(1.5, 1);
        $pdf->SetFont('arial', 'b', 9);
        $pdf->MultiCell(25.7, 0.8, $titulo, 0, 'C');
        $pdf->Ln();

        $pdf->SetFillColor(0, 0, 0);
        $pdf->setXY(1, 1.7);
        $pdf->Cell(28, 0.1, ' ', 1, 1, "C");
        $pdf->setXY(1, 1.8);
        $pdf->Cell(28, 0.1, ' ', 1, 1, "C", true);

        //período da busca
        $periodo = " PROCESSO SELETIVO " . $processo . " - RESULTADO FINAL (MESTRADO)";
        $pdf->setXY(1.5, 2);
        $pdf->SetFont('arial', '', 10);
        $pdf->MultiCell(25.7, 0.8, $periodo, 0, 'C');
        $pdf->Ln();
    }

    function Footer() {
        global $pdf;
        $pdf->setY(24);
        $pdf->SetFont('arial', '', 8);
        $pdf->Cell(0, 10, 'Página ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }

}

$pdf = new PDF('L', 'cm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setFont('times', '', 10);
$pdf->SetAutoPageBreak(true, 2.5);


//PARA INTERFACEAR COM AS VAGAS PARAMETRIZADAS
//LINHA1
$linha1Ac = 4;
$linha1PADT = 1;
$linha1Er = 2;
$linha1Tc = 0;
//LINHA2
$linha2Ac = 10;
$linha2PADT = 2;
$linha2Er = 1;
$linha2Tc = 0;
//LINHA3
$linha3Ac = 8;
$linha3PADT = 1;
$linha3Er = 1;
$linha3Tc = 0;





//AMPLA CONCORRÊNCIA  BEGIN ############################################################################

$pdf->SetFont('arial', 'B', 9);
$pdf->setXY(1, 3.4);
$pdf->Cell(27.2, 0.6, 'Ampla Concorrência', 1, 1, "C");

//cabeçalho da tabela
$pdf->SetFont('arial', 'B', 9);
$pdf->setXY(1, 4);
$pdf->Cell(1.5, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(1.5, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(8.0, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Proj Pesquisa ', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Prova Escrita', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Análise Curr', 1, 0, "C");
// $pdf->Cell(2.7,0.6,'Profile (1)',1,0,"C");
// $pdf->Cell(2.7,0.6,'Média',1,0,"C");
$pdf->Cell(2.2, 0.6, 'Prova Oral', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Nota Final', 1, 0, "C");
$pdf->Cell(5.2, 0.6, 'Resultado Final', 1, 1, "C");


//tabela de dados
//$result = mysqli_query($_SG['link'], "SELECT c.idCandidato, c.nome, c.numInscricao, c.linhaPesquisa, c.cpf, c.notaEntrevista, c.notaProva1, c.notaProva2, c.notaAnteprojeto1, c.notaAnteprojeto2, c.notaEntrevista, c.notaProfile, c.pontuacaoCurriculo, c.notaFinal FROM candidato c WHERE  c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 1 AND c.notaEntrevista>=7 AND c.cotas = 'ac' ORDER BY c.linhaPesquisa, c.notaFinal DESC ");
$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, ((c.notaProva1+c.notaProva2)/2) AS notaProva , c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) +  ((c.notaProva1+c.notaProva2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) AS notaFinalCalc
FROM candidato c WHERE  c.processo = 44 AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 1 AND c.notaEntrevista>=7 AND c.cotas = 'ac' ORDER BY c.linhaPesquisa, notaFinalCalc DESC, notaAnteprojeto DESC");

$total_results = mysqli_num_rows($result);
$pdf->SetFont('arial', '', 9);

$maiorPontuacao = 0;
for ($i = 0; $i < $total_results; $i++) {
    $pontuacao = mysql_result($result, $i, 'pontuacaoCurriculo');
    if ($pontuacao > $maiorPontuacao)
        $maiorPontuacao = $pontuacao;
}

$contL1 = 0;
$contL2 = 0;
$contL3 = 0;


for ($i = 0; $i < $total_results; $i++) {

    $linhaPesquisa = mysql_result($result, $i, 'linhaPesquisa');
    $idCandidato = mysql_result($result, $i, 'idCandidato');
//Notas já vem calculadas na query

    $notaAnteprojeto = mysql_result($result, $i, 'notaAnteprojeto');
    $notaProva = mysql_result($result, $i, 'notaProva');
    $pontuacaoCurriculo = mysql_result($result, $i, 'pontuacaoCurriculo');
    $notaEntrevista = mysql_result($result, $i, 'notaEntrevista');
    $notaFinal = mysql_result($result, $i, 'notaFinalCalc');


    if ($linhaPesquisa == "linha1") {
        $linha = "Linha 1";
        $contL1++;
        $colocacao = $contL1 . 'º';
        if ($contL1 <= $linha1Ac) {
            $resultado = "Aprovado e classificado";
        } else
            $resultado = "Aprovado, mas não classificado.";
    }else if ($linhaPesquisa == "linha2") {
        $linha = "Linha 2";
        $contL2++;
        $colocacao = $contL2 . 'º';
        if ($contL2 <= $linha2Ac) {
            $resultado = "Aprovado e classificado";
        } else
            $resultado = "Aprovado, mas não classificado.";
    }else if ($linhaPesquisa == "linha3") {
        $linha = "Linha 3";
        $contL3++;
        $colocacao = $contL3 . 'º';
        if ($contL3 <= $linha3Ac) {
            $resultado = "Aprovado e classificado";
        } else
            $resultado = "Aprovado, mas não classificado.";
    }


    //NOTAS NOVO ##################################333


    $nome = mysql_result($result, $i, 'nome');





    $query = "UPDATE candidato SET notaFinal='$notaFinal' WHERE idCandidato = $idCandidato";
    $exec = mysql_query($query, $conexao);

    $pdf->setX(1);
    $pdf->Cell(1.5, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(1.5, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(8.0, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaProva, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");

    $pdf->Cell(2.2, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, $resultado, 1, 1, "C");
}

//AMPLA CONCORRÊNCIA  END ############################################################################


//TERMO DE COOPERAÇÃO  BEGIN ############################################################################
$pdf->Ln();
$pdf->Ln();

$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);   //$pdf->setXY(1, 3.4);
$pdf->Cell(27.2, 0.6, 'TERMO DE COOPERAÇÃO-UFPA/UNIFAP/IDAP', 1, 1, "C");

//cabeçalho da tabela
$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);  //$pdf->setXY(1, 4);
$pdf->Cell(1.5, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(1.5, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(8.0, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Proj Pesquisa ', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Prova Escrita', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Análise Curr', 1, 0, "C");
// $pdf->Cell(2.7,0.6,'Profile (1)',1,0,"C");
// $pdf->Cell(2.7,0.6,'Média',1,0,"C");
$pdf->Cell(2.2, 0.6, 'Prova Oral', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Nota Final', 1, 0, "C");
$pdf->Cell(5.2, 0.6, 'Resultado Final', 1, 1, "C");


//tabela de dados
//$result = mysqli_query($_SG['link'], "SELECT c.idCandidato, c.nome, c.numInscricao, c.linhaPesquisa, c.cpf, c.notaEntrevista, c.notaProva1, c.notaProva2, c.notaAnteprojeto1, c.notaAnteprojeto2, c.notaEntrevista, c.notaProfile, c.pontuacaoCurriculo, c.notaFinal FROM candidato c WHERE  c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 1 AND c.notaEntrevista>=7 AND c.cotas = 'ac' ORDER BY c.linhaPesquisa, c.notaFinal DESC ");
$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, ((c.notaProva1+c.notaProva2)/2) AS notaProva , c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) +  ((c.notaProva1+c.notaProva2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) AS notaFinalCalc
FROM candidato c WHERE  c.processo = 44 AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 1 AND c.notaEntrevista>=7 AND c.cotas = 'tc' ORDER BY c.linhaPesquisa, notaFinalCalc DESC, notaAnteprojeto DESC");

$total_results = mysqli_num_rows($result);
$pdf->SetFont('arial', '', 9);

$maiorPontuacao = 0;
for ($i = 0; $i < $total_results; $i++) {
    $pontuacao = mysql_result($result, $i, 'pontuacaoCurriculo');
    if ($pontuacao > $maiorPontuacao)
        $maiorPontuacao = $pontuacao;
}

$contL1 = 0;
$contL2 = 0;
$contL3 = 0;


for ($i = 0; $i < $total_results; $i++) {

    $linhaPesquisa = mysql_result($result, $i, 'linhaPesquisa');
    $idCandidato = mysql_result($result, $i, 'idCandidato');



    if ($linhaPesquisa == "linha1") {
        $linha = "Linha 1";
        $contL1++;
        $colocacao = $contL1 . 'º';
        if ($contL1 <= $linha1Tc) {
            $resultado = "Aprovado e classificado";
        } else
            $resultado = "Aprovado, mas não classificado.";
    }else if ($linhaPesquisa == "linha2") {
        $linha = "Linha 2";
        $contL2++;
        $colocacao = $contL2 . 'º';
        if ($contL2 <= $linha2Tc) {
            $resultado = "Aprovado e classificado";
        } else
            $resultado = "Aprovado, mas não classificado.";
    }else if ($linhaPesquisa == "linha3") {
        $linha = "Linha 3";
        $contL3++;
        $colocacao = $contL3 . 'º';
        if ($contL3 <= $linha3Tc) {
            $resultado = "Aprovado e classificado";
        } else
            $resultado = "Aprovado, mas não classificado.";
    }


    //NOTAS NOVO ##################################333


    $nome = mysql_result($result, $i, 'nome');

//Notas já vem calculadas na query

    $notaAnteprojeto = mysql_result($result, $i, 'notaAnteprojeto');
    $notaProva = mysql_result($result, $i, 'notaProva');
    $pontuacaoCurriculo = mysql_result($result, $i, 'pontuacaoCurriculo');
    $notaEntrevista = mysql_result($result, $i, 'notaEntrevista');
    $notaFinal = mysql_result($result, $i, 'notaFinalCalc');



    $query = "UPDATE candidato SET notaFinal='$notaFinal' WHERE idCandidato = $idCandidato";
    $exec = mysql_query($query, $conexao);

    
 
    $pdf->setX(1);
    $pdf->Cell(1.5, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(1.5, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(8.0, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaProva, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");

    $pdf->Cell(2.2, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, $resultado, 1, 1, "C");
      
    
}

//TERMO DE COOPERAÇÃO   END ############################################################################



//ETNORACIAL  BEGIN ############################################################################
$pdf->Ln();
$pdf->Ln();

$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);   //$pdf->setXY(1, 3.4);
$pdf->Cell(27.2, 0.6, 'ETNORACIAL-PCD', 1, 1, "C");

//cabeçalho da tabela
$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);  //$pdf->setXY(1, 4);
$pdf->Cell(1.5, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(1.5, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(8.0, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Proj Pesquisa ', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Prova Escrita', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Análise Curr', 1, 0, "C");
// $pdf->Cell(2.7,0.6,'Profile (1)',1,0,"C");
// $pdf->Cell(2.7,0.6,'Média',1,0,"C");
$pdf->Cell(2.2, 0.6, 'Prova Oral', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Nota Final', 1, 0, "C");
$pdf->Cell(5.2, 0.6, 'Resultado Final', 1, 1, "C");


//tabela de dados
//$result = mysqli_query($_SG['link'], "SELECT c.idCandidato, c.nome, c.numInscricao, c.linhaPesquisa, c.cpf, c.notaEntrevista, c.notaProva1, c.notaProva2, c.notaAnteprojeto1, c.notaAnteprojeto2, c.notaEntrevista, c.notaProfile, c.pontuacaoCurriculo, c.notaFinal FROM candidato c WHERE  c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 1 AND c.notaEntrevista>=7 AND c.cotas = 'ac' ORDER BY c.linhaPesquisa, c.notaFinal DESC ");
$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, ((c.notaProva1+c.notaProva2)/2) AS notaProva , c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) +  ((c.notaProva1+c.notaProva2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) AS notaFinalCalc
FROM candidato c WHERE  c.processo = 44 AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 1 AND c.notaEntrevista>=7 AND c.cotas = 'er' ORDER BY c.linhaPesquisa, notaFinalCalc DESC, notaAnteprojeto DESC");

$total_results = mysqli_num_rows($result);
$pdf->SetFont('arial', '', 9);

$maiorPontuacao = 0;
for ($i = 0; $i < $total_results; $i++) {
    $pontuacao = mysql_result($result, $i, 'pontuacaoCurriculo');
    if ($pontuacao > $maiorPontuacao)
        $maiorPontuacao = $pontuacao;
}

$contL1 = 0;
$contL2 = 0;
$contL3 = 0;


for ($i = 0; $i < $total_results; $i++) {

    $linhaPesquisa = mysql_result($result, $i, 'linhaPesquisa');
    $idCandidato = mysql_result($result, $i, 'idCandidato');



    if ($linhaPesquisa == "linha1") {
        $linha = "Linha 1";
        $contL1++;
        $colocacao = $contL1 . 'º';
        if ($contL1 <= $linha1Er) {
            $resultado = "Aprovado e classificado";
        } else
            $resultado = "Aprovado, mas não classificado.";
    }else if ($linhaPesquisa == "linha2") {
        $linha = "Linha 2";
        $contL2++;
        $colocacao = $contL2 . 'º';
        if ($contL2 <= $linha2Er) {
            $resultado = "Aprovado e classificado";
        } else
            $resultado = "Aprovado, mas não classificado.";
    }else if ($linhaPesquisa == "linha3") {
        $linha = "Linha 3";
        $contL3++;
        $colocacao = $contL3 . 'º';
        if ($contL3 <= $linha3Er) {
            $resultado = "Aprovado e classificado";
        } else
            $resultado = "Aprovado, mas não classificado.";
    }


    //NOTAS NOVO ##################################333


    $nome = mysql_result($result, $i, 'nome');

//Notas já vem calculadas na query

    $notaAnteprojeto = mysql_result($result, $i, 'notaAnteprojeto');
    $notaProva = mysql_result($result, $i, 'notaProva');
    $pontuacaoCurriculo = mysql_result($result, $i, 'pontuacaoCurriculo');
    $notaEntrevista = mysql_result($result, $i, 'notaEntrevista');
    $notaFinal = mysql_result($result, $i, 'notaFinalCalc');



    $query = "UPDATE candidato SET notaFinal='$notaFinal' WHERE idCandidato = $idCandidato";
    $exec = mysql_query($query, $conexao);

    
 
    $pdf->setX(1);
    $pdf->Cell(1.5, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(1.5, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(8.0, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaProva, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");

    $pdf->Cell(2.2, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, $resultado, 1, 1, "C");
      
    
}

//ETNORRACIAL   END ############################################################################


//PADT  BEGIN ############################################################################
$pdf->Ln();
$pdf->Ln();

$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);   //$pdf->setXY(1, 3.4);
$pdf->Cell(27.2, 0.6, 'PADT', 1, 1, "C");

//cabeçalho da tabela
$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);  //$pdf->setXY(1, 4);
$pdf->Cell(1.5, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(1.5, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(8.0, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Proj Pesquisa ', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Prova Escrita', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Análise Curr', 1, 0, "C");
// $pdf->Cell(2.7,0.6,'Profile (1)',1,0,"C");
// $pdf->Cell(2.7,0.6,'Média',1,0,"C");
$pdf->Cell(2.2, 0.6, 'Prova Oral', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Nota Final', 1, 0, "C");
$pdf->Cell(5.2, 0.6, 'Resultado Final', 1, 1, "C");


//tabela de dados
//$result = mysqli_query($_SG['link'], "SELECT c.idCandidato, c.nome, c.numInscricao, c.linhaPesquisa, c.cpf, c.notaEntrevista, c.notaProva1, c.notaProva2, c.notaAnteprojeto1, c.notaAnteprojeto2, c.notaEntrevista, c.notaProfile, c.pontuacaoCurriculo, c.notaFinal FROM candidato c WHERE  c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 1 AND c.notaEntrevista>=7 AND c.cotas = 'ac' ORDER BY c.linhaPesquisa, c.notaFinal DESC ");
$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, ((c.notaProva1+c.notaProva2)/2) AS notaProva , c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) +  ((c.notaProva1+c.notaProva2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) AS notaFinalCalc
FROM candidato c WHERE  c.processo = 44 AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 1 AND c.notaEntrevista>=7 AND c.cotas = 'padt' ORDER BY c.linhaPesquisa, notaFinalCalc DESC, notaAnteprojeto DESC");

$total_results = mysqli_num_rows($result);
$pdf->SetFont('arial', '', 9);

$maiorPontuacao = 0;
for ($i = 0; $i < $total_results; $i++) {
    $pontuacao = mysql_result($result, $i, 'pontuacaoCurriculo');
    if ($pontuacao > $maiorPontuacao)
        $maiorPontuacao = $pontuacao;
}

$contL1 = 0;
$contL2 = 0;
$contL3 = 0;


for ($i = 0; $i < $total_results; $i++) {

    $linhaPesquisa = mysql_result($result, $i, 'linhaPesquisa');
    $idCandidato = mysql_result($result, $i, 'idCandidato');



    if ($linhaPesquisa == "linha1") {
        $linha = "Linha 1";
        $contL1++;
        $colocacao = $contL1 . 'º';
        if ($contL1 <= $linha1PADT) {
            $resultado = "Aprovado e classificado";
        } else
            $resultado = "Aprovado, mas não classificado.";
    }else if ($linhaPesquisa == "linha2") {
        $linha = "Linha 2";
        $contL2++;
        $colocacao = $contL2 . 'º';
        if ($contL2 <= $linha2PADT) {
            $resultado = "Aprovado e classificado";
        } else
            $resultado = "Aprovado, mas não classificado.";
    }else if ($linhaPesquisa == "linha3") {
        $linha = "Linha 3";
        $contL3++;
        $colocacao = $contL3 . 'º';
        if ($contL3 <= $linha3PADT) {
            $resultado = "Aprovado e classificado";
        } else
            $resultado = "Aprovado, mas não classificado.";
    }


    //NOTAS NOVO ##################################333


    $nome = mysql_result($result, $i, 'nome');

//Notas já vem calculadas na query

    $notaAnteprojeto = mysql_result($result, $i, 'notaAnteprojeto');
    $notaProva = mysql_result($result, $i, 'notaProva');
    $pontuacaoCurriculo = mysql_result($result, $i, 'pontuacaoCurriculo');
    $notaEntrevista = mysql_result($result, $i, 'notaEntrevista');
    $notaFinal = mysql_result($result, $i, 'notaFinalCalc');



    $query = "UPDATE candidato SET notaFinal='$notaFinal' WHERE idCandidato = $idCandidato";
    $exec = mysql_query($query, $conexao);

    
 
    $pdf->setX(1);
    $pdf->Cell(1.5, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(1.5, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(8.0, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaProva, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");

    $pdf->Cell(2.2, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, $resultado, 1, 1, "C");
      
    
}

//PADT   END ############################################################################



/*

$pdf->Ln();
$info = "Aprovado e Classificado:  Preenche vaga disponível na Linha de Pesquisa, conforme Adendo ao Edital Publicado em 07/04/2016.

Aprovado, mas não classificado: Não Contemplado com vaga disponível na Linha de Pesquisa.";
$pdf->setX(1);
$pdf->SetFont('arial', '', 10);
$pdf->MultiCell(18, 0.5, $info, 0, 'L');
$pdf->Ln();
*/




//função para exibir o relatório gerado em um arquivo .pdf no navegador
$pdf->Output("Relatório Geral (Processo Seletivo " . $processo . ").pdf", "I");
?>