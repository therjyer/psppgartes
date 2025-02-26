<?php

include("../principal/seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
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
$idTipoProcesso = $_GET['idTipo'];
$consulta = mysqli_query($_SG['link'], "SELECT p.processo, p.alocacaoAnteprojeto FROM procseletivo p WHERE p.idProcesso = $idProcesso");
$row = mysqli_fetch_row($consulta);
$processo = $row[0];

class PDF extends FPDF {

    function Header() {
        global $pdf, $processo;
//$processo = $_GET['processo']; 
        //t�tulo do relat�rio
        $titulo = "PROGRAMA DE PÓS-GRADUAÇÃO EM ARTES";
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
        $idTipoProcesso = $_GET['idTipo'];
        if ($idTipoProcesso == "1")
            {
            $periodo = " PROCESSO SELETIVO " . $processo . " - LISTA DE APROVADOS NO PROJETO POR LINHA E ÁREA (MESTRADO)";
            } else
            {
            $periodo = " PROCESSO SELETIVO " . $processo . " - LISTA DE APROVADOS NO PROJETO POR LINHA E ÁREA (DOUTORADO)";
            }
        //$periodo = " PROCESSO SELETIVO " . $processo . " - LISTA DE APROVADOS NO PROJETO (DOUTORADO)";
        
        
        
        
        $pdf->setXY(1.5, 2);
        $pdf->SetFont('arial', '', 10);
        $pdf->MultiCell(18, 0.8, $periodo, 0, 'C');
        $pdf->Ln();


        //cabe�alho da tabela
        $pdf->SetFont('arial', 'B', 9);
        $pdf->setXY(1.7, 4);
        $pdf->Cell(11.5, 0.6, 'Nome do candidato', 1, 0, "C");
        //$pdf->Cell(4, 0.6, 'N� de Inscri��o', 1, 0, "C");
        $pdf->Cell(3, 0.6, 'Linha', 1, 0, "C");
        $pdf->Cell(3, 0.6, 'Área', 1, 1, "C");
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
include("../principal/dbconnect.inc.php");




//tabela de dados
//$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf,c.linhaPesquisa, c.areaAtuacao, c.notaAnteprojeto1, c.notaAnteprojeto2 FROM candidato c WHERE c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2)>=7) AND c.tipoProcesso=2 AND c.pontuacaoCurriculo > 0 ORDER BY c.linhaPesquisa, c.areaAtuacao, c.nome");
$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf,c.linhaPesquisa, c.areaAtuacao, c.notaAnteprojeto1, c.notaAnteprojeto2 FROM candidato c WHERE c.processo = $idProcesso AND tipoProcesso = $idTipoProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2)>=7)   ORDER BY c.linhaPesquisa, c.areaAtuacao, c.nome");
$total_results = mysqli_num_rows($result);

$pdf->SetFont('arial', '', 9);
while ($row = mysqli_fetch_array($result)) {
//for ($i = 0; $i < $total_results; $i++) {


    $nome = $row['nome'];
    //$numInscricao = mysql_result($result, $i, 'numInscricao');
    $linhaPesquisa = $row['linhaPesquisa'];
    //$cpf = mysql_result($result, $i, 'cpf');
    $at = $row['areaAtuacao'];
    $area = $row['areaAtuacao'];

    //$cpfLimpo = remove($cpf);
    //$cpfFormatado = mascara($cpfLimpo, '###.XXX.XXX-##');
    //$nota1 = mysql_result($result, $i, 'notaAnteprojeto1');
    //$nota2 = mysql_result($result, $i, 'notaAnteprojeto2');
    //$mediaNota = ($nota1 + $nota2) / 2;
    //$nota = number_format($mediaNota, 2, '.', '');

    if ($linhaPesquisa == "1") {
        $linha = "Linha 1";
    } else if ($linhaPesquisa == "2") {
        $linha = "Linha 2";
    } else if ($linhaPesquisa == "3") {
        $linha = "Linha 3";
    }
    $area='';

if ($at=='1'){
	$area = 'Teatro';
}else if ($at=='2'){
	$area = 'Música';
}else if ($at=='3'){
	$area = 'Dança';
}else if ($at=='4'){
	$area = 'Artes Visuais';
}else if ($at=='5'){
	$area = 'Cinema';        
}else if ($at=='0'){
	$area = 'Outros';
}




    $pdf->setX(1.7);
    $pdf->Cell(11.5, 0.6, $nome, 1, 0, "L");
    //$pdf->Cell(4, 0.6, $numInscricao, 1, 0, "C");
    $pdf->Cell(3, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(3, 0.6, $area, 1, 1, "C");
}

//fun��o para exibir o relat�rio gerado em um arquivo .pdf no navegador
//$pdf->Output("relatorioAprovadosProjeto_linha_area.pdf", "I");
        if ($idTipoProcesso == "1")
            {
            $pdf->Output("relatorioAprovadosProjeto_linha_area_Mestrado.pdf", "I");
            } else
            {
            $pdf->Output("relatorioAprovadosProjeto_linha_area_Doutorado.pdf", "I");
            }

?>




