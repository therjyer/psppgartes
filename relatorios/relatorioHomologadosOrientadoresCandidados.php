<?php

include("../principal/seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
protegePagina(); // Chama a fun��o que protege a p�gina
//include("funcoes.php");
//conex�o com o banco e consultas
include("../principal/dbconnect.inc.php");

//importa bibliotecas necess�rias
require ("fpdf.php");
define("FPDF_PATH", "font");

$idProcesso = $_GET['idProcesso'];
$consulta = mysqli_query($_SG['link'], "SELECT p.processo, p.alocacaoAnteprojeto FROM procseletivo p WHERE p.idProcesso = $idProcesso");
$row = mysqli_fetch_row($consulta);
$processo = $row[0];

//$alocacao = mysql_result($consulta, 0, 'alocacaoAnteprojeto');


class PDF extends FPDF {

    function Header() {
        global $pdf, $processo;
        //$processo = $_GET['processo']; 
        //t�tulo do relat�rio
        $titulo = "PROGRAMA DE PÓS-GRADUAÇÃO EM ARTES";
        $pdf->setXY(1.5, 1);
        $pdf->SetFont('arial', 'b', 9);
        $pdf->MultiCell(25.7, 0.8, $titulo, 0, 'C');
        $pdf->Ln();

        $pdf->SetFillColor(0, 0, 0);
        $pdf->setXY(1.5, 1.7);
        $pdf->Cell(25.7, 0.1, ' ', 1, 1, "C");
        $pdf->setXY(1.5, 1.8);
        $pdf->Cell(25.7, 0.1, ' ', 1, 1, "C", true);

        //per�odo da busca
        $periodo = " PROCESSO SELETIVO " . $processo . " - RELATÓRIO DE ORIENTADORES E CANDIDATOS HOMOLOGADOS  (MESTRADO)";
        $pdf->setXY(1.5, 2);
        $pdf->SetFont('arial', '', 10);
        $pdf->MultiCell(25.7, 0.8, $periodo, 0, 'C');
        $pdf->Ln();


        //cabe�alho da tabela
        $pdf->SetFont('arial', 'B', 9);
        $pdf->setXY(1.7, 4);
        $pdf->Cell(8, 0.6, 'Orientador', 1, 0, "C");
        $pdf->Cell(3, 0.6, 'Opção do candidato', 1, 0, "C");
        $pdf->Cell(8.24, 0.6, 'Candidato', 1, 0, "C");
        $pdf->Cell(4.24, 0.6, 'Nº inscrição', 1, 1, "C");
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



//tabela de dados
//$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf, c.tipoProcesso, c.notaProva1, c.notaProva2, c.avaliadorAnteprojeto1, c.avaliadorAnteprojeto2 FROM candidato c WHERE c.processo = $idProcesso AND c.estado=1 AND c.estadoHomologacao = 1 AND c.tipoProcesso=1 ORDER BY c.avaliadorAnteprojeto1, c.nome");
$result = mysqli_query($_SG['link'], "SELECT * FROM `vieworientadoresopcao` WHERE tipoProcesso = 1 AND Processo = $idProcesso");
$total_results = mysqli_num_rows($result);
$pdf->SetFont('arial', '', 9);
while ($row = mysqli_fetch_array($result) ) {


    $nome = $row['Orientador'];

    $numInscricao = $row['opcao'];
    $Professor1 = $row['candidato'];
    $Professor2 = $row['inscricao'];



    $pdf->setX(1.7);
    $pdf->Cell(8, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(3, 0.6, $numInscricao, 1, 0, "C");
    $pdf->Cell(8.24, 0.6, $Professor1, 1, 0, "C");
    $pdf->Cell(4.24, 0.6, $Professor2, 1, 1, "C");
}

//fun��o para exibir o relat�rio gerado em um arquivo .pdf no navegador
$pdf->Output("Relatório de Orientadores selecionados pelos candidados(Processo Seletivo " . $processo . ").pdf", "I");
?>