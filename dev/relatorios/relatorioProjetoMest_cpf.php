<?php
 include("../principal/seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
 protegePagina(); // Chama a fun��o que protege a p�gina
 //include("funcoes.php");


//importa bibliotecas necess�rias
require ("fpdf.php");
define ("FPDF_PATH", "font");


function remove($valor){
	$itens = array("-", ".", " ");
	$resultado = str_replace($itens, "", $valor);
	return $resultado;
}

function mascara($val, $mask)
{
 $maskared = '';
 $k = 0;
 for($i = 0; $i<=strlen($mask)-1; $i++){
	if($mask[$i] == '#'){
		if(isset($val[$k]))
			$maskared .= $val[$k++];
	}
	else if($mask[$i] == 'X'){
			if(isset($val[$k]))
				$maskared .= "X";
				$k++;
		
	}
	else{
		if(isset($mask[$i]))
			$maskared .= $mask[$i];
	}
 }
 return $maskared;
}

$idProcesso = $_GET['idProcesso'];
$consulta = mysqli_query($_SG['link'], "SELECT p.processo, p.alocacaoAnteprojeto FROM procseletivo p WHERE p.idProcesso = $idProcesso");
$row = mysqli_fetch_row($consulta);
$processo = $row[0];

class PDF extends FPDF
{
 function Header()
 {
      global $pdf, $processo;
//$processo = $_GET['processo']; 
      
      //t�tulo do relat�rio
      $titulo="PROGRAMA DE PÓS-GRADUAÇÃO EM ARTES";
      $pdf->setXY(1.5, 1);
      $pdf->SetFont('arial','b',9);
      $pdf->MultiCell(18, 0.8, $titulo, 0, 'C');
      $pdf->Ln();
      
      $pdf->SetFillColor(0,0,0);
      $pdf->setXY(1.5, 1.7);
      $pdf->Cell(18,0.1,' ',1,1,"C");
      $pdf->setXY(1.5, 1.8);
      $pdf->Cell(18,0.1,' ',1,1,"C", true);
     
      //per�odo da busca
      $periodo= " PROCESSO SELETIVO ".$processo." - AVALIAÇÃO DO PROJETO (MESTRADO)";
      $pdf->setXY(1.5, 2);
      $pdf->SetFont('arial','',10);
      $pdf->MultiCell(18, 0.8, $periodo, 0, 'C');
      $pdf->Ln();

    
     //cabe�alho da tabela
     $pdf->SetFont('arial','B',9);
     $pdf->setXY(1.7,4);
     $pdf->Cell(3,0.6,'CPF do candidato',1,0,"C");
     $pdf->Cell(3,0.6,'Nota 1',1,0,"C");
     $pdf->Cell(3,0.6,'Nota 2',1,0,"C");
     $pdf->Cell(3,0.6,'Média final',1,0,"C");
     $pdf->Cell(3,0.6,'Data',1,0,"C");
     $pdf->Cell(3,0.6,'Assinatura',1,1,"C");

 }
 
  function Footer()
 {
       global $pdf;
       $pdf->setY(24);
       $pdf->SetFont('arial','',8);
       $pdf->Cell(0,10,'Página '.$this->PageNo().'/{nb}',0,0,'R');
 }
}


$pdf = new PDF('P', 'cm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setFont('times', '', 10);
$pdf->SetAutoPageBreak(true , 2.5);



//conex�o com o banco e consultas
include("../principal/dbconnect.inc.php");




//tabela de dados
$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf, c.notaProva1, c.notaProva2, c.notaAnteprojeto1, c.notaAnteprojeto2 FROM candidato c WHERE c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 1 ORDER BY c.numInscricao");
$total_results = mysqli_num_rows($result);

$pdf->SetFont('arial','',9);
while ($row = mysqli_fetch_array($result) ) {


    $nome = $row['nome'];
    $numInscricao = $row['numInscricao'];   
    $cpf = $row['cpf'];

	$cpfLimpo = remove($cpf);
	$cpfFormatado = mascara($cpfLimpo,'###.###.###-##');
    
    $nota1 = $row['notaAnteprojeto1'];
    $nota2 = $row['notaAnteprojeto2'];
    $mediaNota = ($nota1+$nota2)/2; 
    
       $pdf->setX(1.7);
       $pdf->Cell(3,0.6,$cpfFormatado,1,0,"L");
       $pdf->Cell(3,0.6,number_format($nota1, 2, '.', ''),1,0,"C");
       $pdf->Cell(3,0.6,number_format($nota2, 2, '.', ''),1,0,"C");
       $pdf->Cell(3,0.6,number_format($mediaNota, 2, '.', ''),1,0,"C");
       $pdf->Cell(3,0.6,'',1,0,"C");
       $pdf->Cell(3,0.6,'',1,1,"C");       
    
    
}
    
//fun��o para exibir o relat�rio gerado em um arquivo .pdf no navegador
$pdf->Output("relatorioProjetoMest_cpf.pdf","I");


?>




