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
            $periodo = " PROCESSO SELETIVO " . $processo . " - CANDIDATOS E NOTAS DO CURRÍCULO (MESTRADO)";
            } else
            {
            $periodo = " PROCESSO SELETIVO " . $processo . " - CANDIDATOS E NOTAS DO CURRÍCULO (DOUTORADO)";
            }


        //$periodo = " PROCESSO SELETIVO " . $processo . " - CANDIDATOS E NOTAS DO PROJETO";


        $pdf->setXY(1.5, 2);
        $pdf->SetFont('arial', '', 10);
        $pdf->MultiCell(18, 0.8, $periodo, 0, 'C');
        $pdf->Ln();


        //cabe�alho da tabela
        $pdf->SetFont('arial', 'B', 9);
        $pdf->setXY(1.7, 4);
        $pdf->Cell(8.5, 0.6, 'Nome do candidato', 1, 0, "C");
        //$pdf->Cell(4, 0.6, 'N� de Inscri��o', 1, 0, "C");
        $pdf->Cell(6, 0.6, 'Nome Avaliador', 1, 0, "C");
        $pdf->Cell(3, 0.6, 'Nota Avaliador', 1, 1, "C");
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
$result = mysqli_query($_SG['link'], "SELECT * FROM `viewnotascurriculo` WHERE PROCESSO = $idProcesso AND tipoProcesso = $idTipoProcesso");
$total_results = mysqli_num_rows($result);

$pdf->SetFont('arial', '', 9);
//for ($i = 0; $i < $total_results; $i++) {
while ($row = mysqli_fetch_array($result) ) {


    $nome = $row['nome'];
    //$numInscricao = mysql_result($result, $i, 'numInscricao');
    $nota1 = $row['pontuacaocurriculo'];
    $avaliador = $row['nomeavaliador'];
   

    $pdf->setX(1.7);
    $pdf->Cell(8.5, 0.6, $nome, 1, 0, "L");

    $pdf->Cell(6, 0.6, $avaliador, 1, 0, "C");
    $pdf->Cell(3, 0.6, number_format($nota1, 2, ',', ' '), 1, 1, "C");
    
}

//fun��o para exibir o relat�rio gerado em um arquivo .pdf no navegador
        if ($idTipoProcesso == "1")
            {
            $pdf->Output("relatorioNotasProjetoMestrado.pdf", "I");
            } else
            {
            $pdf->Output("relatorioNotasProjetoDoutorado.pdf", "I");
            }

?>




