<?php

include("../principal/seguranca.php");
; // Inclui o arquivo com o sistema de seguran�a
protegePagina(); // Chama a fun��o que protege a p�gina
//include("funcoes.php");
//conex�o com o banco e consultas
include("../principal/dbconnect.inc.php");

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
        if ($mask[$i] == '#')
            {
            if (isset($val[$k]))
                $maskared .= $val[$k++];
            } else if ($mask[$i] == 'X')
            {
            if (isset($val[$k]))
                $maskared .= "X";
            $k++;
            } else
            {
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

//$alocacao = mysql_result($consulta, 0, 'alocacaoAnteprojeto');


class PDF extends FPDF {

    function Header() {
        global $pdf, $processo;
        //$processo = $_GET['processo']; 
        //t�tulo do relat�rio
        $titulo = "PROGRAMA DE PÓS-GRADUAÇÃO EM ARTES";
        $pdf->setXY(1, 1);
        $pdf->SetFont('arial', 'b', 9);
        $pdf->MultiCell(28, 0.8, $titulo, 0, 'C');
        $pdf->Ln();

        $pdf->SetFillColor(0, 0, 0);
        $pdf->setXY(1, 1.7);
        $pdf->Cell(28, 0.1, ' ', 1, 1, "C"); //era 26
        $pdf->setXY(1, 1.8);
        $pdf->Cell(28, 0.1, ' ', 1, 1, "C", true);

        //per�odo da busca
        //$periodo= " PROCESSO SELETIVO ".$processo." - RELATÓRIO DE ALOCAÇÃO PARA AVALIAÇÃO DE CURRÍCULO (MESTRADO)";
        $idTipoProcesso = $_GET['idTipo'];
        if ($idTipoProcesso == "1")
            {
            $periodo = " PROCESSO SELETIVO " . $processo . " - RELATÓRIO DE NOTAS ATÉ ETAPA ENTREVISTA (MESTRADO)";
            } else
            {
            $periodo = " PROCESSO SELETIVO " . $processo . " - RELATÓRIO DE NOTAS ATÉ ETAPA ENTREVISTA (DOUTORADO)";
            }


        $pdf->setXY(1, 2);
        $pdf->SetFont('arial', '', 10);
        $pdf->MultiCell(28, 0.8, $periodo, 0, 'C');
        $pdf->Ln();

        //cabe�alho da tabela
        $pdf->SetFont('arial', 'B', 9);
        $pdf->setXY(1, 4);
        $pdf->Cell(8, 0.6, 'Nome do candidato', 1, 0, "C");  //0,5
        $pdf->Cell(4.5, 0.6, 'Orientador 1', 1, 0, "C"); //0,3
        $pdf->Cell(4.5, 0.6, 'Orientador 2', 1, 0, "C");
        $pdf->Cell(1.8, 0.6, 'Linha', 1, 0, "C"); //0,2
        $pdf->Cell(2.1, 0.6, 'Área', 1, 0, "C"); //0,2
        $pdf->Cell(1.8, 0.6, 'Nota Proj', 1, 0, "C"); //0,2
        $pdf->Cell(1.8, 0.6, 'Nota Curr', 1, 0, "C"); //0,2
        $pdf->Cell(1.8, 0.6, 'Nota Entre', 1, 0, "C"); //0,2
        $pdf->Cell(1.8, 0.6, 'Media', 1, 1, "C"); //0,2
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

$idTipoProcesso = $_GET['idTipo'];

//tabela de dados
$result = mysqli_query($_SG['link'], "SELECT * FROM `viewandamentoateentrevista` WHERE processo = $idProcesso and tipoProcesso = $idTipoProcesso order by linhaPesquisa asc, media DESC");
//$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf, c.tipoProcesso, c.notaProva1, c.notaProva2,c.notaEntrevista, c.avaliadorCurriculo1, c.avaliadorCurriculo2 FROM candidato c WHERE c.processo = $idProcesso AND c.estado=1 AND c.estadoHomologacao = 1 AND c.tipoProcesso=1 AND (((c.notaProva1+c.notaProva2)/2)>=7) ORDER BY c.avaliadorCurriculo1, c.nome");
$total_results = mysqli_num_rows($result);
$pdf->SetFont('arial', '', 9);
//for ($i=0;$i<$total_results;$i++){
while ($row = mysqli_fetch_array($result)) {

    $nome = substr($row['nome'],0,42);
    //$cpf = $row['cpf'];
    //$cpfLimpo = remove($cpf);
    //$cpfFormatado = mascara($cpfLimpo, '###.###.###-##');
    
    $nomeProfessor1 = $row['nomeAvaliador1'];
    $nomeProfessor2 = $row['nomeAvaliador2'];
    $linhaPesquisa = $row['linhaPesquisa'];
    $area = $row['areaAtuacao'];
    $nota = number_format($row['notaProjeto'], 2, '.', '');
    $notaCurriculo = number_format($row['pontuacaoCurriculo'], 2, '.', '');
    $notaEntrevista = number_format($row['notaEntrevista'], 2, '.', '');
    $media = number_format($row['media'], 2, '.', '');

    //if ($idProfessor1 == "-") {
    //    $nomeProfessor1 = "-";
    //}    






    $pdf->setX(1);
    $pdf->Cell(8, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(4.5, 0.6, $nomeProfessor1, 1, 0, "C");
    $pdf->Cell(4.5, 0.6, $nomeProfessor2, 1, 0, "C");
    $pdf->Cell(1.8, 0.6, $linhaPesquisa, 1, 0, "C");
    $pdf->Cell(2.1, 0.6, $area, 1, 0, "C");
    $pdf->Cell(1.8, 0.6, $nota, 1, 0, "C");
    $pdf->Cell(1.8, 0.6, $notaCurriculo, 1, 0, "C");
    $pdf->Cell(1.8, 0.6, $notaEntrevista, 1, 0, "C");
    $pdf->Cell(1.8, 0.6, $media, 1, 1, "C");
}

//fun��o para exibir o relat�rio gerado em um arquivo .pdf no navegador
//$pdf->Output("Relatório de Alocação de Projeto(Processo Seletivo ".$processo.").pdf","I");
if ($idTipoProcesso == "1")
    {
    $pdf->Output("relatorioAndamentoAteEntrevista_Mestrado.pdf", "I");
    } else
    {
    $pdf->Output("relatorioAndamentoAteEntrevista_Doutorado.pdf", "I");
    }
?>