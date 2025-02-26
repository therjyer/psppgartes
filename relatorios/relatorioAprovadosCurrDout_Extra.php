<?php

include("seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
protegePagina(); // Chama a fun��o que protege a p�gina
//include("funcoes.php");
//importa bibliotecas necess�rias
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
        //t�tulo do relat�rio
        $titulo = "PROGRAMA DE P�S-GRADUA��O EM ARTES";
        $pdf->setXY(1.5, 1);
        $pdf->SetFont('arial', 'b', 9);
        $pdf->MultiCell(18, 0.8, $titulo, 0, 'C');
        $pdf->Ln();

        $pdf->SetFillColor(0, 0, 0);
        $pdf->setXY(1.5, 1.7);
        $pdf->Cell(18, 0.1, ' ', 1, 1, "C");
        $pdf->setXY(1.5, 1.8);
        $pdf->Cell(18, 0.1, ' ', 1, 1, "C", true);

        //per�odo da busca
        $periodo = " PROCESSO SELETIVO " . $processo . " - LISTA DE CLASSIFICADOS NA AN�LISE DE CURR�CULO(DOUTORADO)";
        $pdf->setXY(1.5, 2);
        $pdf->SetFont('arial', '', 10);
        $pdf->MultiCell(18, 0.8, $periodo, 0, 'C');
        $pdf->Ln();
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



//conex�o com o banco e consultas
include("dbconnect.inc.php");

//tODOS

$pdf->SetFont('arial', 'B', 9);
$pdf->setXY(1.7, 3.4);
$pdf->Cell(17.5, 0.6, 'Classifica��o Geral', 1, 1, "C");


//cabe�alho da tabela
$pdf->SetFont('arial', 'B', 9);
$pdf->setXY(1.7, 4);
$pdf->Cell(9.5, 0.6, 'CPF do candidato', 1, 0, "C");
$pdf->Cell(4, 0.6, 'Nota', 1, 0, "C");
$pdf->Cell(4, 0.6, 'Linha de Pesquisa', 1, 1, "C");

//tabela de dados
$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf,c.linhaPesquisa, c.pontuacaoCurriculo FROM candidato c WHERE  c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND (c.pontuacaoCurriculo >=7) AND c.tipoProcesso=2 ORDER BY  c.linhaPesquisa, c.pontuacaoCurriculo DESC");
$total_results = mysqli_num_rows($result);
 
$pdf->SetFont('arial', '', 9);
for ($i = 0; $i < $total_results; $i++) {


    $nome = mysql_result($result, $i, 'nome');
    $linhaPesquisa = mysql_result($result, $i, 'linhaPesquisa');
    $cpf = mysql_result($result, $i, 'cpf');

    $cpfLimpo = remove($cpf);
    $cpfFormatado = mascara($cpfLimpo, '###.XXX.XXX-##');

    $pontuacao = mysql_result($result, $i, 'pontuacaoCurriculo');
    //; $nota2 = mysql_result($result, $i, 'notaProva2');
    // $pontuacao = ($pontuacao + $nota2) / 2;
    $nota = number_format($pontuacao, 2, '.', '');

    if ($linhaPesquisa == "linha1") {
        $linha = "Linha 1";
    } else if ($linhaPesquisa == "linha2") {
        $linha = "Linha 2";
    } else if ($linhaPesquisa == "linha3") {
        $linha = "Linha 3";
    }


    $pdf->setX(1.7);
    $pdf->Cell(9.5, 0.6, $cpfFormatado, 1, 0, "C");
    $pdf->Cell(4, 0.6, $nota, 1, 0, "C");
    $pdf->Cell(4, 0.6, $linha   , 1, 1, "C");
}




$pdf->Ln();
$pdf->Ln();
$info = "13.5 - A divulga��o dos resultados contemplar� apenas o n�mero do CPF dos candidatos aprovados em cada etapa, seguido da nota respectiva.";
$pdf->setX(1.7);
$pdf->SetFont('arial', '', 10);
//$pdf->MultiCell(18, 0.5, $info, 0, 'L');
//$pdf->Ln();
//fun��o para exibir o relat�rio gerado em um arquivo .pdf no navegador
$pdf->Output("relatorioAprovadosCurrDout_Geral.pdf", "I");
?>




