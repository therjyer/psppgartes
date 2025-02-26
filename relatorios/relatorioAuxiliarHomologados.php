<?php

include("../principal/seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
protegePagina(); // Chama a fun��o que protege a p�gina

include("../principal/dbconnect.inc.php");

//importa bibliotecas necess�rias
require ("fpdf.php");
define("FPDF_PATH", "font");

$idProcesso = $_GET['idProcesso'];
$idTipoProcesso = $_GET['idTipo'];
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

        //tipo de processo
        $idTipoProcesso = $_GET['idTipo'];        
        if ($idTipoProcesso == "1")
            {
            $periodo = " PROCESSO SELETIVO " . $processo . " - RELATÓRIO AUXILIAR DE HOMOLOGADOS (MESTRADO)";
            } else
            {
            $periodo = " PROCESSO SELETIVO " . $processo . " - RELATÓRIO AUXILIAR DE HOMOLOGADOS (DOUTORADO)";
            }



        //per�odo da busca
        //$periodo = " PROCESSO SELETIVO " . $processo . " - RELATÓRIO DE ALOCAÇÃO PARA AVALIAÇÃO DE PROJETO (MESTRADO)";
        $pdf->setXY(1.5, 2);
        $pdf->SetFont('arial', '', 10);
        $pdf->MultiCell(25.7, 0.8, $periodo, 0, 'C');
        $pdf->Ln();


        //cabe�alho da tabela
        $pdf->SetFont('arial', 'B', 9);
        $pdf->setXY(1.7, 4);
        $pdf->Cell(8, 0.6, 'Nome do candidato', 1, 0, "C");
        $pdf->Cell(5, 0.6, 'CPF', 1, 0, "C");
        $pdf->Cell(6.24, 0.6, 'Avaliador 1', 1, 0, "C");
        $pdf->Cell(6.24, 0.6, 'Avaliador 2', 1, 1, "C");
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
$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf, c.tipoProcesso, c.notaProva1, c.notaProva2, c.avaliadorAnteprojeto1, c.avaliadorAnteprojeto2 FROM candidato c WHERE c.processo = $idProcesso AND c.estado=1 AND c.estadoHomologacao = 1 AND c.tipoProcesso= $idTipoProcesso ORDER BY  c.avaliadorAnteprojeto1, c.nome");
$total_results = mysqli_num_rows($result);
$pdf->SetFont('arial', '', 9);
while ($row = mysqli_fetch_array($result) ) {


    $nome = $row['nome'];

    $cpf = $row['cpf'];
    $idProfessor1 = $row['avaliadorAnteprojeto1'];
    if ($idProfessor1 == "") {
        $nomeProfessor1 = "-";
    } else {

        $consultaPofessor1 = mysqli_query($_SG['link'], "SELECT u.nome FROM usuarios u WHERE u.id=$idProfessor1");
        $rowprofessor1 = mysqli_fetch_row($consultaPofessor1);
        $nomeProfessor1 = $rowprofessor1[0];
    }


    $idProfessor2 = $row['avaliadorAnteprojeto2'];

    if ($idProfessor2 == "") {
        $nomeProfessor2 = "-";
    } else {
        $consultaProfessor2 = mysqli_query($_SG['link'], "SELECT u.nome FROM usuarios u WHERE u.id=$idProfessor2");
        $rowProfessor2 = mysqli_fetch_row($consultaProfessor2);
        $nomeProfessor2 = $rowProfessor2[0];
    }
    // $cpf = mysql_result($result, $i, 'cpf'); 




    $pdf->setX(1.7);
    $pdf->Cell(8, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(5, 0.6, $cpf, 1, 0, "C");
    $pdf->Cell(6.24, 0.6, $nomeProfessor1, 1, 0, "C");
    $pdf->Cell(6.24, 0.6, $nomeProfessor2, 1, 1, "C");
}

//fun��o para exibir o relat�rio gerado em um arquivo .pdf no navegador
$pdf->Output("Relatório Auxiliar de Homologados(Processo Seletivo " . $processo . ").pdf", "I");
?>