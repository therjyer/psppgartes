<?php

include("../principal/seguranca.php"); // Inclui o arquivo com o sistema de segurança
protegePagina(); // Chama a função que protege a página
//include("funcoes.php");
//conexão com o banco e consultas
include("../principal/dbconnect.inc.php");

//importa bibliotecas necessárias
require ("fpdf.php");
define("FPDF_PATH", "font");

$idProcesso = $_GET['idProcesso'];
$idTipoProcesso = $_GET['idTipo'];
$consulta = mysqli_query($_SG['link'], "SELECT p.processo, p.alocacaoAnteprojeto FROM procseletivo p WHERE p.idProcesso = $idProcesso");
$row = mysqli_fetch_row($consulta);
$processo = $row[0];

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
       

        $idTipoProcesso = $_GET['idTipo'];
        if ($idTipoProcesso == "1")
            {
            $periodo = " PROCESSO SELETIVO " . $processo . " - RESULTADO FINAL (MESTRADO)";
            } else
            {
            $periodo = " PROCESSO SELETIVO " . $processo . " - RESULTADO FINAL (DOUTORADO)";
            }


        $pdf->setXY(1.5, 2);
        $pdf->SetFont('arial', '', 10);
        $pdf->MultiCell(25.7, 0.8, $periodo, 0, 'C');
        //$pdf->Ln();
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



//AMPLA CONCORRÊNCIA  BEGIN ############################################################################

$pdf->SetFont('arial', 'B', 9);
$pdf->setXY(1, 3.4);
$pdf->Cell(27.2, 0.6, 'Ampla Concorrência', 1, 1, "C");

//cabeçalho da tabela
$pdf->SetFont('arial', 'B', 9);
$pdf->setXY(1, 4);
$pdf->Cell(2.2, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(2.0, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(9.0, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Proj Pesquisa ', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Analise Curr', 1, 0, "C");
// $pdf->Cell(2.7,0.6,'Profile (1)',1,0,"C");
// $pdf->Cell(2.7,0.6,'Média',1,0,"C");
$pdf->Cell(2.2, 0.6, 'Entrevista', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Nota Final', 1, 0, "C");
$pdf->Cell(5.2, 0.6, 'Resultado Final', 1, 1, "C");


$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) AS notaFinalCalc, c.colocacao AS colocacao, c.resultadoFinal AS resultadoFinal
FROM candidato c WHERE  c.processo = 46 AND c.estadoEntrevista = 1 AND c.estado=1 AND c.tipoProcesso = $idTipoProcesso AND c.estadoCurriculo = 1 AND c.cotas = 'ac' ORDER BY c.linhaPesquisa, c.colocacao");


while ($row = mysqli_fetch_array($result)) {

    $linha = "Linha  $linhaPesquisa";    
    $nome = $row['nome'];    
    $linhaPesquisa = $row['linhaPesquisa'];
    $idCandidato = $row['idCandidato'];    
    $notaAnteprojeto = $row['notaAnteprojeto'];
    $pontuacaoCurriculo = $row['pontuacaoCurriculo'];
    $notaEntrevista = $row['notaEntrevista'];
    $notaFinal = $row['notaFinalCalc'];
    $resultadoFinal = $row['resultadoFinal'];
    $colocacao = $row['colocacao'];
    
    switch ($resultadoFinal) {
        case "1": 
            $resultado = "Aprovado e classificado";
            break;
        case "2": 
            $resultado = "Aprovado, mas não classificado";
            break;
        case "3": 
            $resultado = "Reprovado";
            break;
        default:
            break;
    }    
 
    $pdf->SetFont('arial', '', 9);
    $pdf->setX(1);
    $pdf->Cell(2.2, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(2.0, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(9.0, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, $resultado, 1, 1, "C");
}
    
    
    
//AMPLA CONCORRÊNCIA  END ############################################################################


//ETNORACIAL  BEGIN ############################################################################

$pdf->Ln();
$pdf->Ln();


$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);

$pdf->Cell(27.2, 0.6, 'ETNORACIAL e/ou PcD', 1, 1, "C");

//cabeçalho da tabela
//$pdf->SetFont('arial', 'B', 9);
//$pdf->setXY(1, 4);
$pdf->Cell(2.2, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(2.0, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(9.0, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Proj Pesquisa ', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Analise Curr', 1, 0, "C");
// $pdf->Cell(2.7,0.6,'Profile (1)',1,0,"C");
// $pdf->Cell(2.7,0.6,'Média',1,0,"C");
$pdf->Cell(2.2, 0.6, 'Entrevista', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Nota Final', 1, 0, "C");
$pdf->Cell(5.2, 0.6, 'Resultado Final', 1, 1, "C");


$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) AS notaFinalCalc, c.colocacao AS colocacao, c.resultadoFinal AS resultadoFinal
FROM candidato c WHERE  c.processo = 46 AND c.estadoEntrevista = 1 AND c.estado=1 AND c.tipoProcesso = $idTipoProcesso AND c.estadoCurriculo = 1 AND c.cotas in ('cc','cct') ORDER BY c.linhaPesquisa, c.colocacao");


while ($row = mysqli_fetch_array($result)) {

    $linha = "Linha  $linhaPesquisa";    
    $nome = $row['nome'];    
    $linhaPesquisa = $row['linhaPesquisa'];
    $idCandidato = $row['idCandidato'];    
    $notaAnteprojeto = $row['notaAnteprojeto'];
    $pontuacaoCurriculo = $row['pontuacaoCurriculo'];
    $notaEntrevista = $row['notaEntrevista'];
    $notaFinal = $row['notaFinalCalc'];
    $resultadoFinal = $row['resultadoFinal'];
    $colocacao = $row['colocacao'];
    
    switch ($resultadoFinal) {
        case "1": 
            $resultado = "Aprovado e classificado";
            break;
        case "2": 
            $resultado = "Aprovado, mas não classificado";
            break;
        case "3": 
            $resultado = "Reprovado";
            break;
        default:
            break;
    }    
 
    $pdf->SetFont('arial', '', 9);
    $pdf->setX(1);
    $pdf->Cell(2.2, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(2.0, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(9.0, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, $resultado, 1, 1, "C");
}

//ETNORRACIAL   END ############################################################################
//

//COTA TRANS BEGIN
############################################################################
//

$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);   //$pdf->setXY(1, 3.4);
$pdf->Cell(27.2, 0.6, 'COTA TRANS', 1, 1, "C");



//cabeçalho da tabela
//$pdf->SetFont('arial', 'B', 9);
//$pdf->setXY(1, 4);
$pdf->Cell(2.2, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(2.0, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(9.0, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Proj Pesquisa ', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Analise Curr', 1, 0, "C");
// $pdf->Cell(2.7,0.6,'Profile (1)',1,0,"C");
// $pdf->Cell(2.7,0.6,'Média',1,0,"C");
$pdf->Cell(2.2, 0.6, 'Entrevista', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Nota Final', 1, 0, "C");
$pdf->Cell(5.2, 0.6, 'Resultado Final', 1, 1, "C");


$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) AS notaFinalCalc, c.colocacao AS colocacao, c.resultadoFinal AS resultadoFinal
FROM candidato c WHERE  c.processo = 46 AND c.estadoEntrevista = 1 AND c.estado=1 AND c.tipoProcesso = $idTipoProcesso AND c.estadoCurriculo = 1 AND c.cotas in ('ct') ORDER BY c.linhaPesquisa, c.colocacao");


while ($row = mysqli_fetch_array($result)) {

    $linha = "Linha  $linhaPesquisa";    
    $nome = $row['nome'];    
    $linhaPesquisa = $row['linhaPesquisa'];
    $idCandidato = $row['idCandidato'];    
    $notaAnteprojeto = $row['notaAnteprojeto'];
    $pontuacaoCurriculo = $row['pontuacaoCurriculo'];
    $notaEntrevista = $row['notaEntrevista'];
    $notaFinal = $row['notaFinalCalc'];
    $resultadoFinal = $row['resultadoFinal'];
    $colocacao = $row['colocacao'];
    
    switch ($resultadoFinal) {
        case "1": 
            $resultado = "Aprovado e classificado";
            break;
        case "2": 
            $resultado = "Aprovado, mas não classificado";
            break;
        case "3": 
            $resultado = "Reprovado";
            break;
        default:
            break;
    }    
 
    $pdf->SetFont('arial', '', 9);
    $pdf->setX(1);
    $pdf->Cell(2.2, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(2.0, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(9.0, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, $resultado, 1, 1, "C");
}


//COTA TRANS END
############################################################################
//

//PADT  BEGIN ############################################################################
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);   //$pdf->setXY(1, 3.4);
$pdf->Cell(27.2, 0.6, 'PADT', 1, 1, "C");



//cabeçalho da tabela
//$pdf->SetFont('arial', 'B', 9);
//$pdf->setXY(1, 4);
$pdf->Cell(2.2, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(2.0, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(9.0, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Proj Pesquisa ', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Analise Curr', 1, 0, "C");
// $pdf->Cell(2.7,0.6,'Profile (1)',1,0,"C");
// $pdf->Cell(2.7,0.6,'Média',1,0,"C");
$pdf->Cell(2.2, 0.6, 'Entrevista', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Nota Final', 1, 0, "C");
$pdf->Cell(5.2, 0.6, 'Resultado Final', 1, 1, "C");


$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) AS notaFinalCalc, c.colocacao AS colocacao, c.resultadoFinal AS resultadoFinal
FROM candidato c WHERE  c.processo = 46 AND c.estadoEntrevista = 1 AND c.estado=1 AND c.tipoProcesso = $idTipoProcesso AND c.estadoCurriculo = 1 AND c.cotas in ('padt') ORDER BY c.linhaPesquisa, c.colocacao");


while ($row = mysqli_fetch_array($result)) {

    $linha = "Linha  $linhaPesquisa";    
    $nome = $row['nome'];    
    $linhaPesquisa = $row['linhaPesquisa'];
    $idCandidato = $row['idCandidato'];    
    $notaAnteprojeto = $row['notaAnteprojeto'];
    $pontuacaoCurriculo = $row['pontuacaoCurriculo'];
    $notaEntrevista = $row['notaEntrevista'];
    $notaFinal = $row['notaFinalCalc'];
    $resultadoFinal = $row['resultadoFinal'];
    $colocacao = $row['colocacao'];
    
    switch ($resultadoFinal) {
        case "1": 
            $resultado = "Aprovado e classificado";
            break;
        case "2": 
            $resultado = "Aprovado, mas não classificado";
            break;
        case "3": 
            $resultado = "Reprovado";
            break;
        default:
            break;
    }    
 
    $pdf->SetFont('arial', '', 9);
    $pdf->setX(1);
    $pdf->Cell(2.2, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(2.0, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(9.0, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, $resultado, 1, 1, "C");
}


//PADT   END ############################################################################






  $pdf->Ln();
  $info = "2.6. Não havendo entre os candidatos dos grupos indicados nos incisos II e III do subitem 2.1 e nos incisos II, III e IV do subitem 2.2, aprovados em número suficiente, para preenchimento das vagas, as vagas remanescentes serão revertidas para os candidatos aprovados em ampla concorrência, de acordo com a classificação, nas respectivas Linhas de Pesquisa.
2.7. Não havendo entre os candidatos a ampla concorrência aprovados em número suficiente, para preenchimento das vagas, as vagas remanescentes serão revertidas para os candidatos aprovados dos grupos indicados nos incisos II e III do subitem 2.1 e nos incisos II, III e IV, do subitem 2.2, de acordo com a classificação, nas respectivas Linhas de Pesquisa

";
  $pdf->setX(1.6);
  $pdf->SetFont('arial', '', 10);
  //$pdf->MultiCell(0, 0.5, $info, 0, 'L');
  $pdf->Ln();
 




//função para exibir o relatório gerado em um arquivo .pdf no navegador
//$pdf->Output("Relatório Geral - MESTRADO (Processo Seletivo " . $processo . ").pdf", "I");

        if ($idTipoProcesso == "1")
            {
            $pdf->Output("Relatório Geral - MESTRADO (Processo Seletivo " . $processo . ").pdf", "I");
            } else
            {
            $pdf->Output("Relatório Geral - DOUTORADOTORADO (Processo Seletivo " . $processo . ").pdf", "I");
            }

?>