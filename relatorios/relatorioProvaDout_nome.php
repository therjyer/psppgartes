<?php
 include("seguranca.php"); // Inclui o arquivo com o sistema de segurança
 protegePagina(); // Chama a função que protege a página
 //include("funcoes.php");


//importa bibliotecas necessárias
require ("fpdf.php");
define ("FPDF_PATH", "font");

$idProcesso = $_GET['idProcesso'];
$consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
$processo = mysql_result($consulta, 0, 'processo');

class PDF extends FPDF
{
 function Header()
 {
      global $pdf, $processo;
//$processo = $_GET['processo']; 
      
      //título do relatório
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
     
      //período da busca
      $periodo= " PROCESSO SELETIVO ".$processo." - PROVA (DOUTORADO)";
      $pdf->setXY(1.5, 2);
      $pdf->SetFont('arial','',10);
      $pdf->MultiCell(18, 0.8, $periodo, 0, 'C');
      $pdf->Ln();

    
     //cabeçalho da tabela
     $pdf->SetFont('arial','B',9);
     $pdf->setXY(1.7,4);
     $pdf->Cell(9,0.6,'Nome do candidato',1,0,"C");
     $pdf->Cell(3,0.6,'Nota 1',1,0,"C");
     $pdf->Cell(3,0.6,'Nota 2',1,0,"C");
     $pdf->Cell(3,0.6,'Média final',1,1,"C");

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



//conexão com o banco e consultas
include("dbconnect.inc.php");




//tabela de dados
$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf, c.notaProva1, c.notaProva2, c.notaAnteprojeto1, c.notaAnteprojeto2 FROM candidato c WHERE c.processo = $idProcesso AND (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2)>=7) AND c.estado=1 AND c.tipoProcesso=2 ORDER BY c.nome");
$total_results = mysqli_num_rows($result);

$pdf->SetFont('arial','',9);
for ($i=0;$i<$total_results;$i++){


    $nome = mysql_result($result, $i, 'nome');
    $numInscricao = mysql_result($result, $i, 'numInscricao');   
    $cpf = mysql_result($result, $i, 'cpf'); 

    
    $nota1 = mysql_result($result, $i, 'notaProva1');
    $nota2 = mysql_result($result, $i, 'notaProva2');
    $mediaNota = ($nota1+$nota2)/2; 
    
       $pdf->setX(1.7);
       $pdf->Cell(9,0.6,$nome,1,0,"L");
       $pdf->Cell(3,0.6,number_format($nota1, 2, '.', ''),1,0,"C");
       $pdf->Cell(3,0.6,number_format($nota2, 2, '.', ''),1,0,"C");
       $pdf->Cell(3,0.6,number_format($mediaNota, 2, '.', ''),1,1,"C");
    
    
}
    
//função para exibir o relatório gerado em um arquivo .pdf no navegador
$pdf->Output("relatorioAnteprojeto.pdf","I");


?>




