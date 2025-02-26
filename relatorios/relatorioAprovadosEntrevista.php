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

include("../principal/dbconnect.inc.php");
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

        //TIPO DE PROCESSO

        $idTipoProcesso = $_GET['idTipo'];
        if ($idTipoProcesso == "1")
            {
            $periodo = " PROCESSO SELETIVO " . $processo . " - LISTA DE CLASSIFICADOS NA ENTREVISTA (MESTRADO)";
            } else
            {
            $periodo = " PROCESSO SELETIVO " . $processo . " - LISTA DE CLASSIFICADOS NA ENTREVISTA (DOUTORADO)";
            }

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


//function


$pdf = new PDF('P', 'cm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('arial', 'B', 9);
$pdf->setXY(1.7, 3.4);
$pdf->Cell(17.5, 0.6, 'Ampla Concorrência', 1, 1, "C");

//cabe�alho da tabela
$pdf->SetFont('arial', 'B', 9);
$pdf->setXY(1.7, 4);
$pdf->Cell(9.5, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(4, 0.6, 'Nota', 1, 0, "C");
$pdf->Cell(4, 0.6, 'Linha de Pesquisa', 1, 1, "C");

$pdf->setFont('times', '', 10);
$pdf->SetAutoPageBreak(true, 2.5);

//conexão com o banco e consultas

//AMPLA COMCORRÊNCIA
    $pdf->SetFont('arial', 'B', 9);
    $pdf->setXY(1.7, 3.4);
    $pdf->Cell(17.5, 0.6, 'Ampla Concorrência', 1, 1, "C");
    $pdf->SetFont('arial', 'B', 9);
    $pdf->setXY(1.7, 4);
    $pdf->Cell(9.5, 0.6, 'Nome do candidato', 1, 0, "C");
    $pdf->Cell(4, 0.6, 'Nota', 1, 0, "C");
    $pdf->Cell(4, 0.6, 'Linha de Pesquisa', 1, 1, "C");
    //$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf,c.linhaPesquisa, c.notaAnteprojeto1, c.notaAnteprojeto2 FROM candidato c WHERE c.cotas = 'ac' AND c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2)>=7) AND c.tipoProcesso= $idTipoProcesso ORDER BY c.linhaPesquisa, c.nome"); //$total_results = mysqli_num_rows($result);
    $result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf,c.linhaPesquisa, c.pontuacaoCurriculo, c.notaEntrevista FROM candidato c WHERE c.cotas = 'ac' AND c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND (c.pontuacaoCurriculo >=7) AND (c.notaEntrevista >=7) AND c.tipoProcesso= $idTipoProcesso ORDER BY  c.linhaPesquisa, c.notaEntrevista DESC");
    $pdf->SetFont('arial', '', 9);
    while ($row = mysqli_fetch_array($result)) {
        $nome = $row['nome'];
        $linhaPesquisa = $row['linhaPesquisa'];   // $cpf = $row['cpf'];   $cpfLimpo = remove($cpf);     $cpfFormatado = mascara($cpfLimpo, '###.XXX.XXX-##');
        //$nota = $row['pontuacaoCurriculo'];
        //$nota2 = $row['notaAnteprojeto2'];
        //$mediaNota = ($nota1 + $nota2) / 2;
        $nota = number_format($row['notaEntrevista'], 2, '.', '');
        if ($linhaPesquisa == "1")
            {
            $linha = "Linha 1";
            } else if ($linhaPesquisa == "2")
            {
            $linha = "Linha 2";
        } else if ($linhaPesquisa == "3")
            {
            $linha = "Linha 3";
        }
        $pdf->setX(1.7);
        $pdf->Cell(9.5, 0.6, $nome, 1, 0, "C");
        $pdf->Cell(4, 0.6, $nota, 1, 0, "C");
        $pdf->Cell(4, 0.6, $linha, 1, 1, "C");
    } //while 

//ETNORAscioal e PcD
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();

       $pdf->SetFont('arial', 'B', 9);
       $pdf->setX(1.7);

        $pdf->Cell(17.5, 0.6, 'ETNORACIAL e/ou PcD e/ou TRANS', 1, 1, "C");
    $pdf->SetFont('arial', 'B', 9);
    $pdf->setX(1.7);
    $pdf->Cell(9.5, 0.6, 'Nome do candidato', 1, 0, "C");
    $pdf->Cell(4, 0.6, 'Nota', 1, 0, "C");
    $pdf->Cell(4, 0.6, 'Linha de Pesquisa', 1, 1, "C");
    //$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf,c.linhaPesquisa, c.notaAnteprojeto1, c.notaAnteprojeto2 FROM candidato c WHERE c.cotas in ('cc','cct') AND c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2)>=7) AND c.tipoProcesso= $idTipoProcesso ORDER BY c.linhaPesquisa, c.nome"); 
    $result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf,c.linhaPesquisa, c.pontuacaoCurriculo, c.notaEntrevista FROM candidato c WHERE c.cotas in ('er','ct') AND c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND (c.pontuacaoCurriculo >=7) AND (c.notaEntrevista >=7) AND c.tipoProcesso=$idTipoProcesso ORDER BY  c.linhaPesquisa, c.pontuacaoCurriculo DESC");
//$total_results = mysqli_num_rows($result);
    $pdf->SetFont('arial', '', 9);
    while ($row = mysqli_fetch_array($result)) {
        $nome = $row['nome'];
        $linhaPesquisa = $row['linhaPesquisa'];   // $cpf = $row['cpf'];   $cpfLimpo = remove($cpf);     $cpfFormatado = mascara($cpfLimpo, '###.XXX.XXX-##');
        //$nota = $row['pontuacaoCurriculo'];
        //$nota2 = $row['notaAnteprojeto2'];
        //$mediaNota = ($nota1 + $nota2) / 2;
        $nota = number_format($row['notaEntrevista'], 2, '.', '');
        if ($linhaPesquisa == "1")
            {
            $linha = "Linha 1";
            } else if ($linhaPesquisa == "2")
            {
            $linha = "Linha 2";
        } else if ($linhaPesquisa == "3")
            {
            $linha = "Linha 3";
        }
        $pdf->setX(1.7);
        $pdf->Cell(9.5, 0.6, $nome, 1, 0, "C");
        $pdf->Cell(4, 0.6, $nota, 1, 0, "C");
        $pdf->Cell(4, 0.6, $linha, 1, 1, "C");
    } //while 

//cota trans
    /*
$pdf->Ln();
$pdf->Ln();


       $pdf->SetFont('arial', 'B', 9);
       $pdf->setX(1.7);

        $pdf->Cell(17.5, 0.6, 'COTA TRANS', 1, 1, "C");
    $pdf->SetFont('arial', 'B', 9);
    $pdf->setX(1.7);
    $pdf->Cell(9.5, 0.6, 'Nome do candidato', 1, 0, "C");
    $pdf->Cell(4, 0.6, 'Nota', 1, 0, "C");
    $pdf->Cell(4, 0.6, 'Linha de Pesquisa', 1, 1, "C");
    //$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf,c.linhaPesquisa, c.notaAnteprojeto1, c.notaAnteprojeto2 FROM candidato c WHERE c.cotas in ('ct') AND c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2)>=7) AND c.tipoProcesso= $idTipoProcesso ORDER BY c.linhaPesquisa, c.nome"); 
    $result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf,c.linhaPesquisa, c.pontuacaoCurriculo, c.notaEntrevista FROM candidato c WHERE c.cotas in ('ct') AND c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND (c.pontuacaoCurriculo >=7) AND (c.notaEntrevista >=7) AND c.tipoProcesso=$idTipoProcesso ORDER BY  c.linhaPesquisa, c.pontuacaoCurriculo DESC");
//$total_results = mysqli_num_rows($result);
    $pdf->SetFont('arial', '', 9);
    while ($row = mysqli_fetch_array($result)) {
        $nome = $row['nome'];
        $linhaPesquisa = $row['linhaPesquisa'];   // $cpf = $row['cpf'];   $cpfLimpo = remove($cpf);     $cpfFormatado = mascara($cpfLimpo, '###.XXX.XXX-##');
        //$nota = $row['pontuacaoCurriculo'];
        //$nota2 = $row['notaAnteprojeto2'];
        //$mediaNota = ($nota1 + $nota2) / 2;
        $nota = number_format($row['notaEntrevista'], 2, '.', '');
        if ($linhaPesquisa == "1")
            {
            $linha = "Linha 1";
            } else if ($linhaPesquisa == "2")
            {
            $linha = "Linha 2";
        } else if ($linhaPesquisa == "3")
            {
            $linha = "Linha 3";
        }
        $pdf->setX(1.7);
        $pdf->Cell(9.5, 0.6, $nome, 1, 0, "C");
        $pdf->Cell(4, 0.6, $nota, 1, 0, "C");
        $pdf->Cell(4, 0.6, $linha, 1, 1, "C");
    } //while    
    */

//PADT
$pdf->Ln();
$pdf->Ln();

       $pdf->SetFont('arial', 'B', 9);
       $pdf->setX(1.7);

        $pdf->Cell(17.5, 0.6, 'PADT', 1, 1, "C");
    $pdf->SetFont('arial', 'B', 9);
    $pdf->setX(1.7);
    $pdf->Cell(9.5, 0.6, 'Nome do candidato', 1, 0, "C");
    $pdf->Cell(4, 0.6, 'Nota', 1, 0, "C");
    $pdf->Cell(4, 0.6, 'Linha de Pesquisa', 1, 1, "C");
    //$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf,c.linhaPesquisa, c.notaAnteprojeto1, c.notaAnteprojeto2 FROM candidato c WHERE c.cotas in ('padt', 'padtcc') AND c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2)>=7) AND c.tipoProcesso= $idTipoProcesso ORDER BY c.linhaPesquisa, c.nome"); 
    $result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf,c.linhaPesquisa, c.pontuacaoCurriculo, c.notaEntrevista FROM candidato c WHERE c.cotas in ('padt', 'padtcc') AND c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND (c.pontuacaoCurriculo >=7) AND (c.notaEntrevista >=7) AND c.tipoProcesso=$idTipoProcesso ORDER BY  c.linhaPesquisa, c.pontuacaoCurriculo DESC");
//$total_results = mysqli_num_rows($result);
    $pdf->SetFont('arial', '', 9);
    while ($row = mysqli_fetch_array($result)) {
        $nome = $row['nome'];
        $linhaPesquisa = $row['linhaPesquisa'];   // $cpf = $row['cpf'];   $cpfLimpo = remove($cpf);     $cpfFormatado = mascara($cpfLimpo, '###.XXX.XXX-##');
        //$nota = $row['pontuacaoCurriculo'];
        //$nota2 = $row['notaAnteprojeto2'];
        //$mediaNota = ($nota1 + $nota2) / 2;
        $nota = number_format($row['notaEntrevista'], 2, '.', '');
        if ($linhaPesquisa == "1")
            {
            $linha = "Linha 1";
            } else if ($linhaPesquisa == "2")
            {
            $linha = "Linha 2";
        } else if ($linhaPesquisa == "3")
            {
            $linha = "Linha 3";
        }
        $pdf->setX(1.7);
        $pdf->Cell(9.5, 0.6, $nome, 1, 0, "C");
        $pdf->Cell(4, 0.6, $nota, 1, 0, "C");
        $pdf->Cell(4, 0.6, $linha, 1, 1, "C");
    } //while 

    
    


//geracota("ac", "Ampla concorrência", $pdf, $_SG['link'], $idProcesso, $idTipoProcesso);
//geracota("padt", "PADT", $pdf, $_SG['link'], $idProcesso, $idTipoProcesso);

$pdf->Ln();
$pdf->Ln();
$info = "13.5 - A divulgação dos resultados contemplará apenas o número do CPF dos candidatos aprovados em cada etapa, seguido da nota respectiva.";
$pdf->setX(1.7);
$pdf->SetFont('arial', '', 10);
//$pdf->MultiCell(18, 0.5, $info, 0, 'L');
//$pdf->Ln();
//fun��o para exibir o relat�rio gerado em um arquivo .pdf no navegador
//$pdf->Output("relatorioAprovadosProjeto.pdf", "I");
        if ($idTipoProcesso == "1")
            {
            $pdf->Output("relatorioAprovadosEntrevista_Mestrado.pdf", "I");
            } else
            {
            $pdf->Output("relatorioAprovadosEntrevista_Doutorado.pdf", "I");
            }

?>




