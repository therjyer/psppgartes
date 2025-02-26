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

/* 2018
//PARA INTERFACEAR COM AS VAGAS PARAMETRIZADAS
//LINHA1
$linha1Ac = 6;
$linha1PADT = 0; // 1 vaga - 0 preenchida, 1 vai para ac
$linha1Er = 1; // 2 vagas - 1 preenchida - 1 vai para ac
$linha1Tc = 0;
//LINHA2
$linha2Ac = 9; //10 vagas - 8 preenchidas - sobrea 2  - 1 vai para er - sobra  1
$linha2PADT = 2;
$linha2Er = 2; // 1 vaga - 1 preenchida - 1 não classificada, recebe 1 vaga de ac
$linha2Tc = 0;
//LINHA3
$linha3Ac = 8;
$linha3PADT = 1;
$linha3Er = 1;
$linha3Tc = 0;
*/

//PARA INTERFACEAR COM AS VAGAS PARAMETRIZADAS 2019
//LINHA1
$linha1Ac = 4;
$linha1Er = 0; 

//LINHA2
$linha2Ac = 4; //10 vagas - 8 preenchidas - sobrea 2  - 1 vai para er - sobra  1
$linha2Er = 2; // 1 vaga - 1 preenchida - 1 não classificada, recebe 1 vaga de ac

//LINHA3
$linha3Ac = 4;
$linha3Er = 0;




//AMPLA CONCORRÊNCIA  BEGIN ############################################################################

$pdf->SetFont('arial', 'B', 9);
$pdf->setXY(1, 3.4);
$pdf->Cell(27.2, 0.6, 'Todos os Grupos e Linhas', 1, 1, "C");

//cabeçalho da tabela
$pdf->SetFont('arial', 'B', 9);
$pdf->setXY(1, 4);
$pdf->Cell(1.5, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(9.5, 0.6, 'Nome do candidato', 1, 0, "C");
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
$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, ((c.notaProva1+c.notaProva2)) AS notaProva , c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) +  ((c.notaProva1+c.notaProva2)) + c.pontuacaoCurriculo + c.notaEntrevista) AS notaFinalCalc
FROM candidato c WHERE  c.processo = 45 AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 1 AND c.resultadoFinal = 1 ORDER BY notaFinalCalc DESC, notaAnteprojeto DESC, notaProva DESC");

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

$contVagaAcL1 = 0;
$contVagaAcL2 = 0;
$contVagaAcL3 = 0;
$contGeral=0;

for ($i = 0; $i < $total_results; $i++) {

    $linhaPesquisa = mysql_result($result, $i, 'linhaPesquisa');
    $idCandidato = mysql_result($result, $i, 'idCandidato');
//Notas já vem calculadas na query

    $notaAnteprojeto = mysql_result($result, $i, 'notaAnteprojeto');
    $notaProva = mysql_result($result, $i, 'notaProva');
    $pontuacaoCurriculo = mysql_result($result, $i, 'pontuacaoCurriculo');
    $notaEntrevista = mysql_result($result, $i, 'notaEntrevista');
    $notaFinal = mysql_result($result, $i, 'notaFinalCalc');

            $contGeral++;
            $colocacao = $contGeral . 'º';  
    
   
    
    $nome = mysql_result($result, $i, 'nome');
    

    //$query = "UPDATE candidato SET notaFinal='$notaFinal' WHERE idCandidato = $idCandidato";
    //$exec = mysql_query($query, $conexao);

    $pdf->setX(1);
    $pdf->Cell(1.5, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(9.5, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaProva, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");

    $pdf->Cell(2.2, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, "Aprovado e Classificado", 1, 1, "C");
}
    $contSoubrouVagaAcL1 = $linha1Ac  - $contVagaAcL1;
    $contSoubrouVagaAcL2 = $linha2Ac  - $contVagaAcL2;
    $contSoubrouVagaAcL3 = $linha3Ac  - $contVagaAcL3;
    
    
    
//AMPLA CONCORRÊNCIA  END ############################################################################




/*

  $pdf->Ln();
  $pdf->Ln();
  $info = "2.6. Não havendo entre os candidatos dos grupos indicados nos incisos II e III do subitem 2.1 e nos incisos II, III e IV do subitem 2.2, aprovados em número suficiente, para preenchimento das vagas, as vagas remanescentes serão revertidas para os candidatos aprovados em ampla concorrência, de acordo com a classificação, nas respectivas Linhas de Pesquisa.
2.7. Não havendo entre os candidatos a ampla concorrência aprovados em número suficiente, para preenchimento das vagas, as vagas remanescentes serão revertidas para os candidatos aprovados dos grupos indicados nos incisos II e III do subitem 2.1 e nos incisos II, III e IV, do subitem 2.2, de acordo com a classificação, nas respectivas Linhas de Pesquisa";
  $pdf->setX(1.6);
  $pdf->SetFont('arial', '', 10);
  $pdf->MultiCell(0, 0.5, $info, 0, 'L');
  $pdf->Ln();
 
*/



//função para exibir o relatório gerado em um arquivo .pdf no navegador
$pdf->Output("Relatório Geral Classificados - MESTRADO (Processo Seletivo " . $processo . ").pdf", "I");
?>