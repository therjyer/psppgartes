<?php

include("seguranca.php"); // Inclui o arquivo com o sistema de segurança
protegePagina(); // Chama a função que protege a página
//include("funcoes.php");
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
$consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
$processo = mysql_result($consulta, 0, 'processo');

class PDF extends FPDF {

    function Header() {
        global $pdf, $processo;
//$processo = $_GET['processo']; 
        //título do relatório
        $titulo = "PROGRAMA DE POS-GRADUACAO EM ARTES";
        $pdf->setXY(1.5, 1);
        $pdf->SetFont('arial', 'b', 9);
        $pdf->MultiCell(18, 0.8, $titulo, 0, 'C');
        $pdf->Ln();

        $pdf->SetFillColor(0, 0, 0);
        $pdf->setXY(1.5, 1.7);
        $pdf->Cell(18, 0.1, ' ', 1, 1, "C");
        $pdf->setXY(1.5, 1.8);
        $pdf->Cell(18, 0.1, ' ', 1, 1, "C", true);

        //período da busca
        $periodo = " PROCESSO SELETIVO " . $processo . " - CANDITADOS APROVADOS NA PROVA ESCRITA (MESTRADO)";
        $pdf->setXY(1.5, 2);
        $pdf->SetFont('arial', '', 10);
        $pdf->MultiCell(18, 0.8, $periodo, 0, 'C');
        $pdf->Ln();


        //cabeçalho da tabela
        $pdf->SetFont('arial', 'B', 9);
        $pdf->setXY(1.7, 4);
        $pdf->Cell(9.5, 0.6, 'CPF do candidato', 1, 0, "C");
        $pdf->Cell(4, 0.6, 'Nota', 1, 0, "C");
        $pdf->Cell(4, 0.6, 'Linha de Pesquisa', 1, 1, "C");
    }

    function Footer() {
        global $pdf;
        $hoje = date("d.m.y  -  H:i");
        $pdf->setY(24);
        $pdf->SetFont('arial', '', 8);
        $pdf->Cell(0, 10, $hoje, 0, 0, 'R');
    }

}

$pdf = new PDF('P', 'cm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setFont('times', '', 10);
$pdf->SetAutoPageBreak(true, 2.5);



//conexão com o banco e consultas
include("dbconnect.inc.php");




//tabela de dados
$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf, c.linhaPesquisa, c.notaProva1, c.notaProva2 from candidato c WHERE c.processo = $idProcesso AND c.estado=1 AND c.estadoHomologacao = 1 AND c.tipoProcesso=1 ORDER BY c.linhaPesquisa");
$total_results = mysqli_num_rows($result);

$pdf->SetFont('arial', '', 9);
for ($i = 0; $i < $total_results; $i++) {


    $nome = mysql_result($result, $i, 'nome');
    $numInscricao = mysql_result($result, $i, 'numInscricao');
    $linhaPesquisa = mysql_result($result, $i, 'linhaPesquisa');
    $cpf = mysql_result($result, $i, 'cpf');

    $cpfLimpo = remove($cpf);
    $cpfFormatado = mascara($cpfLimpo, '###.XXX.XXX-##');

    $nota1 = mysql_result($result, $i, 'notaProva1');
    $nota2 = mysql_result($result, $i, 'notaProva2');
    $mediaNota = ($nota1 + $nota2) / 2;

    $nota = number_format($mediaNota, 1, '.', '');

    if ($linhaPesquisa == "linha1") {
        $linha = "Linha 1";
    } else if ($linhaPesquisa == "linha2") {
        $linha = "Linha 2";
    } else if ($linhaPesquisa == "linha3") {
        $linha = "Linha 3";
    }

    if ($mediaNota >= 7) {
        $pdf->setX(1.7);
        $pdf->Cell(9.5, 0.6, $cpfFormatado, 1, 0, "C");
        $pdf->Cell(4, 0.6, $nota, 1, 0, "C");
        $pdf->Cell(4, 0.6, $linha, 1, 1, "C");
    }
}

$pdf->Ln();
$info = "13.5 - A divulgacao dos resultados contemplara apenas o numero do CPF dos candidatos aprovados em cada etapa, seguido da nota respectiva.";
$pdf->setX(1.7);
$pdf->SetFont('arial', '', 10);
//$pdf->MultiCell(18, 0.5, $info, 0, 'L');
$pdf->Ln();

//função para exibir o relatório gerado em um arquivo .pdf no navegador
$pdf->Output("Candidatos aprovados na prova escrita(Processo Seletivo " . $processo . ").pdf", "I");
?>




