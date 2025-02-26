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
        if ($idTipoProcesso == "1") {
            $periodo = " PROCESSO SELETIVO " . $processo . " - RESULTADO FINAL (MESTRADO)";
        } else {
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
$pdf->Cell(27.4, 0.6, 'Ampla Concorrência', 1, 1, "C");

//cabeçalho da tabela
$pdf->Cell(1.5, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(1.7, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(7.5, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(4.5, 0.6, 'Orientador', 1, 0, "C");
$pdf->Cell(1.8, 0.6, 'Proj Pesqu', 1, 0, "C");
$pdf->Cell(1.8, 0.6, 'Anali Curr', 1, 0, "C");
// $pdf->Cell(2.7,0.6,'Profile (1)',1,0,"C");
// $pdf->Cell(2.7,0.6,'Média',1,0,"C");
$pdf->Cell(1.8, 0.6, 'Entrev', 1, 0, "C");
$pdf->Cell(1.8, 0.6, 'Nt Final', 1, 0, "C");
$pdf->Cell(5.0, 0.6, 'Resultado Final', 1, 1, "C");

$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, ((((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) / 3 ) AS notaFinalCalc, c.colocacao AS colocacao, c.resultadoFinal AS resultadoFinal, `u1`.`nome` AS `orientador` FROM candidato c left join `usuarios` `u1` on(`c`.`optOrientador1` = `u1`.`id`) WHERE  c.processo = $idProcesso AND c.estadoEntrevista = 1 AND c.estado=1 AND c.tipoProcesso = $idTipoProcesso AND c.estadoCurriculo = 1 AND c.cotas = 'ac' AND c.notaEntrevista >= 7 ORDER BY c.linhaPesquisa, c.colocacao");

while ($row = mysqli_fetch_array($result)) {

    
    $nome = substr($row['nome'],0,40);
    $orientador = $row['orientador'];
    $linhaPesquisa = $row['linhaPesquisa'];
    $idCandidato = $row['idCandidato'];
    $notaAnteprojeto = $row['notaAnteprojeto'];
    $pontuacaoCurriculo = $row['pontuacaoCurriculo'];
    $notaEntrevista = $row['notaEntrevista'];
    $notaFinal = $row['notaFinalCalc'];
    $resultadoFinal = $row['resultadoFinal'];
    $colocacao = $row['colocacao'];
    $linha = "Linha  $linhaPesquisa";
    
    if ($resultadoFinal == 2) { $orientador = "-"; }
    if ($resultadoFinal == 3) { $orientador = "-"; }

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
    $pdf->Cell(1.5, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(1.7, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(7.5, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(4.5, 0.6, $orientador, 1, 0, "L");
    $pdf->Cell(1.8, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(1.8, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(1.8, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(1.8, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    

    $pdf->Cell(5.0, 0.6, $resultado, 1, 1, "C");
}

//AMPLA CONCORRÊNCIA  END ############################################################################
//
//ETNORACIAL  BEGIN ############################################################################

    $pdf->Ln();
    $pdf->Ln();

    $pdf->SetFont('arial', 'B', 9);
    $pdf->setX(1);

    $pdf->Cell(27.4, 0.6, 'ETNORACIAL e/ou PcD e/ou TRANS', 1, 1, "C");

//cabeçalho da tabela
//$pdf->SetFont('arial', 'B', 9);
//$pdf->setXY(1, 4); 
//2.2 , 2.0 , 9.0 , 2.2 , 2.2 , 2.2 , 2.2 , 2.2 , 5.2
//1.5 , 1.7 , 7.5 , 1.8 , 1.8 , 1.8 , 1.8 , 1.8 , 5.0
//0.7 , 0.3 , 1.5 , 0.2 , 0.4 , 0.4 , 0.4 , 0.4 , 0.2
// 2.5 + 2.0 = 4.5
    $pdf->Cell(1.5, 0.6, 'Coloc.', 1, 0, "C");
    $pdf->Cell(1.7, 0.6, 'Linha', 1, 0, "C");
    $pdf->Cell(7.5, 0.6, 'Nome do candidato', 1, 0, "C");
    $pdf->Cell(4.5, 0.6, 'Orientador', 1, 0, "C");
    $pdf->Cell(1.8, 0.6, 'Proj Pesqu', 1, 0, "C");
    $pdf->Cell(1.8, 0.6, 'Anali Curr', 1, 0, "C");
// $pdf->Cell(2.7,0.6,'Profile (1)',1,0,"C");
// $pdf->Cell(2.7,0.6,'Média',1,0,"C");
    $pdf->Cell(1.8, 0.6, 'Entrev', 1, 0, "C");
    $pdf->Cell(1.8, 0.6, 'Nt Final', 1, 0, "C");
    $pdf->Cell(5.0, 0.6, 'Resultado Final', 1, 1, "C");

    // 2023 $result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, ((((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) / 3 ) AS notaFinalCalc, c.colocacao AS colocacao, c.resultadoFinal AS resultadoFinal, `u1`.`nome` AS `orientador` FROM candidato c left join `usuarios` `u1` on(`c`.`optOrientador1` = `u1`.`id`) WHERE  c.processo = $idProcesso AND c.estadoEntrevista = 1 AND c.estado=1 AND c.tipoProcesso = $idTipoProcesso AND c.estadoCurriculo = 1 AND c.cotas in ('er') AND c.notaEntrevista >= 7 ORDER BY c.linhaPesquisa, c.colocacao");  //'cc','cct'
    //2024
    $result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, ((((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) / 3 ) AS notaFinalCalc, c.colocacao AS colocacao, c.resultadoFinal AS resultadoFinal, `u1`.`nome` AS `orientador` FROM candidato c left join `usuarios` `u1` on(`c`.`optOrientador1` = `u1`.`id`) WHERE  c.processo = $idProcesso AND c.estadoEntrevista = 1 AND c.estado=1 AND c.tipoProcesso = $idTipoProcesso AND c.estadoCurriculo = 1 AND c.cotas in ('er','cc','cct','pcd') AND c.notaEntrevista >= 7 ORDER BY c.linhaPesquisa, c.colocacao");  //'cc','cct'


    while ($row = mysqli_fetch_array($result)) {


        $nome = substr($row['nome'],0,35);
        $orientador = $row['orientador'];
        $linhaPesquisa = $row['linhaPesquisa'];
        $idCandidato = $row['idCandidato'];
        $notaAnteprojeto = $row['notaAnteprojeto'];
        $pontuacaoCurriculo = $row['pontuacaoCurriculo'];
        $notaEntrevista = $row['notaEntrevista'];
        $notaFinal = $row['notaFinalCalc'];
        $resultadoFinal = $row['resultadoFinal'];
        $colocacao = $row['colocacao'];
        $linha = "Linha  $linhaPesquisa";
        if ($resultadoFinal == 2) { $orientador = "-"; }
        if ($resultadoFinal == 3) { $orientador = "-"; }

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
        //1.5 , 1.7 , 7.5 , 1.8 , 1.8 , 1.8 , 1.8 , 1.8 , 5.0
        $pdf->SetFont('arial', '', 9);
        $pdf->setX(1);
        $pdf->Cell(1.5, 0.6, $colocacao, 1, 0, "C");
        $pdf->Cell(1.7, 0.6, $linha, 1, 0, "C");
        $pdf->Cell(7.5, 0.6, $nome, 1, 0, "L");
        $pdf->Cell(4.5, 0.6, $orientador, 1, 0, "L");
        $pdf->Cell(1.8, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
        $pdf->Cell(1.8, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");
        $pdf->Cell(1.8, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
        $pdf->Cell(1.8, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
        $pdf->Cell(5.0, 0.6, $resultado, 1, 1, "C");
    }

//ETNORRACIAL   END ############################################################################
//


//PADT
############################################################################
//
//PADT  BEGIN ############################################################################
    $pdf->Ln();
    $pdf->Ln();
    $pdf->SetFont('arial', 'B', 9);
    $pdf->setX(1);   //$pdf->setXY(1, 3.4);
    $pdf->Cell(27.4, 0.6, 'PADT', 1, 1, "C");

//cabeçalho da tabela
//$pdf->SetFont('arial', 'B', 9);
//$pdf->setXY(1, 4);
    $pdf->Cell(1.5, 0.6, 'Coloc.', 1, 0, "C");
    $pdf->Cell(1.7, 0.6, 'Linha', 1, 0, "C");
    $pdf->Cell(7.5, 0.6, 'Nome do candidato', 1, 0, "C");
    $pdf->Cell(4.5, 0.6, 'Orientador', 1, 0, "C");
    $pdf->Cell(1.8, 0.6, 'Proj Pesqu', 1, 0, "C");
    $pdf->Cell(1.8, 0.6, 'Anali Curr', 1, 0, "C");
// $pdf->Cell(2.7,0.6,'Profile (1)',1,0,"C");
// $pdf->Cell(2.7,0.6,'Média',1,0,"C");
    $pdf->Cell(1.8, 0.6, 'Entrev', 1, 0, "C");
    $pdf->Cell(1.8, 0.6, 'Nt Final', 1, 0, "C");
    $pdf->Cell(5.0, 0.6, 'Resultado Final', 1, 1, "C");

    $result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa,  ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, ((((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) / 3 ) AS notaFinalCalc, c.colocacao AS colocacao, c.resultadoFinal AS resultadoFinal, `u1`.`nome` AS `orientador` FROM candidato c left join `usuarios` `u1` on(`c`.`optOrientador1` = `u1`.`id`) WHERE  c.processo = $idProcesso AND c.estadoEntrevista = 1 AND c.estado=1 AND c.tipoProcesso = $idTipoProcesso AND c.estadoCurriculo = 1 AND c.cotas in ('padt') AND c.notaEntrevista >= 7 ORDER BY c.linhaPesquisa, c.colocacao");

    while ($row = mysqli_fetch_array($result)) {

        
        $nome = substr($row['nome'],0,35);
        $orientador = $row['orientador'];
        $linhaPesquisa = $row['linhaPesquisa'];
        $idCandidato = $row['idCandidato'];
        $notaAnteprojeto = $row['notaAnteprojeto'];
        $pontuacaoCurriculo = $row['pontuacaoCurriculo'];
        $notaEntrevista = $row['notaEntrevista'];
        $notaFinal = $row['notaFinalCalc'];
        $resultadoFinal = $row['resultadoFinal'];
        $colocacao = $row['colocacao'];
        $linha = "Linha  $linhaPesquisa";
        if ($resultadoFinal == 2) { $orientador = "-"; }
        if ($resultadoFinal == 3) { $orientador = "-"; }    

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
        $pdf->Cell(1.5, 0.6, $colocacao, 1, 0, "C");
        $pdf->Cell(1.7, 0.6, $linha, 1, 0, "C");
        $pdf->Cell(7.5, 0.6, $nome, 1, 0, "L");
        $pdf->Cell(4.5, 0.6, $orientador, 1, 0, "L");
        $pdf->Cell(1.8, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");

        $pdf->Cell(1.8, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");
        $pdf->Cell(1.8, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
        
        $pdf->Cell(1.8, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");

        $pdf->Cell(5.0, 0.6, $resultado, 1, 1, "C");
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

    if ($idTipoProcesso == "1") {
        $pdf->Output("Relatório Geral - MESTRADO (Processo Seletivo " . $processo . ").pdf", "I");
    } else {
        $pdf->Output("Relatório Geral - DOUTORADOTORADO (Processo Seletivo " . $processo . ").pdf", "I");
    }
?>