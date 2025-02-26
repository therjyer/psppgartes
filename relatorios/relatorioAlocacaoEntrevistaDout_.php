<?php

include("seguranca.php"); // Inclui o arquivo com o sistema de segurança
protegePagina(); // Chama a função que protege a página
//include("funcoes.php");
//conexão com o banco e consultas
include("dbconnect.inc.php");

//importa bibliotecas necessárias
require ("fpdf.php");
define("FPDF_PATH", "font");

function remove($valor) {
    $itens = array("-", ".", " ");
    $resultado = str_replace($itens, "", $valor);
    return $resultado;
}

function mascara($val, $mask) {
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k]))
                $maskared .= $val[$k++];
        }
        else if ($mask[$i] == 'X') {
            if (isset($val[$k]))
                $maskared .= "X";
            $k++;
        }
        else {
            if (isset($mask[$i]))
                $maskared .= $mask[$i];
        }
    }
    return $maskared;
}

$idProcesso = $_GET['idProcesso'];
$consulta = mysqli_query($_SG['link'], "SELECT p.processo, p.alocacaoAnteprojeto FROM procseletivo p WHERE p.idProcesso = $idProcesso");
$processo = mysql_result($consulta, 0, 'processo');

//$alocacao = mysql_result($consulta, 0, 'alocacaoAnteprojeto');


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
        $pdf->setXY(1.5, 1.7);
        $pdf->Cell(25.7, 0.1, ' ', 1, 1, "C");
        $pdf->setXY(1.5, 1.8);
        $pdf->Cell(25.7, 0.1, ' ', 1, 1, "C", true);

        //período da busca
        $periodo = " PROCESSO SELETIVO " . $processo . " - RELATÓRIO DE ALOCAÇÃO PARA AVALIAÇÃO DE PROVA ORAL (DOUTORADO)";
        $pdf->setXY(1.5, 2);
        $pdf->SetFont('arial', '', 10);
        $pdf->MultiCell(25.7, 0.8, $periodo, 0, 'C');
        $pdf->Ln();


        //cabeçalho da tabela
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
$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf, c.tipoProcesso, c.notaProva1, c.notaProva2, c.pontuacaoCurriculo, c.avaliadorEntrevista1, c.avaliadorEntrevista2 FROM candidato c WHERE c.processo = $idProcesso AND c.estado=1 AND c.estadoHomologacao = 1 AND c.tipoProcesso=2 AND (pontuacaoCurriculo >=7) ORDER BY c.avaliadorEntrevista1, c.nome");
$total_results = mysqli_num_rows($result);
$pdf->SetFont('arial', '', 9);
for ($i = 0; $i < $total_results; $i++) {


    $nome = mysql_result($result, $i, 'nome');

    $cpf = mysql_result($result, $i, 'cpf');

    $cpfLimpo = remove($cpf);
    $cpfFormatado = mascara($cpfLimpo, '###.###.###-##');

    $idProfessor1 = mysql_result($result, $i, 'avaliadorEntrevista1');
    
    if ($idProfessor1 == "-") {
        $nomeProfessor1 = "-";
    } else {

        $consulta3 = mysqli_query($_SG['link'], "SELECT u.nome FROM usuarios u WHERE u.id=$idProfessor1");
        $nomeProfessor1 = mysql_result($consulta3, 0, 'nome');
    }


    //$idProfessor1 = mysql_result($result, $i, 'avaliadorEntrevista1');
    //$consulta3 = mysqli_query($_SG['link'], "SELECT u.nome FROM usuarios u WHERE u.id=$idProfessor1");
    //$nomeProfessor1 = mysql_result($consulta3, 0, 'nome');

    $idProfessor2 = mysql_result($result, $i, 'avaliadorEntrevista2');
        if ($idProfessor2 == "-") {
        $nomeProfessor2 = "-";
    } else {

        $consulta2 = mysqli_query($_SG['link'], "SELECT u.nome FROM usuarios u WHERE u.id=$idProfessor2");
        $nomeProfessor2 = mysql_result($consulta2, 0, 'nome');
    }
    
    
    
    //$idProfessor2 = mysql_result($result, $i, 'avaliadorEntrevista2');
    //$consulta2 = mysqli_query($_SG['link'], "SELECT u.nome FROM usuarios u WHERE u.id=$idProfessor2");
    //$nomeProfessor2 = mysql_result($consulta2, 0, 'nome');

    // $cpf = mysql_result($result, $i, 'cpf'); 




    $pdf->setX(1.7);
    $pdf->Cell(8, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(5, 0.6, $cpfFormatado, 1, 0, "C");
    $pdf->Cell(6.24, 0.6, $nomeProfessor1, 1, 0, "C");
    $pdf->Cell(6.24, 0.6, $nomeProfessor2, 1, 1, "C");
}

//função para exibir o relatório gerado em um arquivo .pdf no navegador
$pdf->Output("Relatório de Alocação de Entrevista(Processo Seletivo " . $processo . ").pdf", "I");
?>