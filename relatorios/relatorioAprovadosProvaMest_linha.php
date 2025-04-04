<?php
 include("seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
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
$consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
$processo = mysql_result($consulta, 0, 'processo');

class PDF extends FPDF
{
 function Header()
 {
      global $pdf, $processo;
//$processo = $_GET['processo']; 
      
      //t�tulo do relat�rio
      $titulo="PROGRAMA DE P�S-GRADUA��O EM ARTES";
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
      $periodo= " PROCESSO SELETIVO ".$processo." - LISTA DE APROVADOS NA PROVA (MESTRADO)";
      $pdf->setXY(1.5, 2);
      $pdf->SetFont('arial','',10);
      $pdf->MultiCell(18, 0.8, $periodo, 0, 'C');
      $pdf->Ln();

    
     //cabe�alho da tabela
     $pdf->SetFont('arial','B',9);
     $pdf->setXY(1.7,4);
     $pdf->Cell(7.5,0.6,'Nome do candidato',1,0,"C");
     $pdf->Cell(4,0.6,'N� de Inscri��o',1,0,"C");
	 $pdf->Cell(3,0.6,'Linha',1,0,"C");
	 $pdf->Cell(3,0.6,'�rea',1,1,"C");


 }
 
  function Footer()
 {
       global $pdf;
       $hoje = date("d.m.y  -  H:i"); 
       $pdf->setY(24);
       $pdf->SetFont('arial','',8);
       $pdf->Cell(0,10,$hoje,0,0,'R');
 }
}


$pdf = new PDF('P', 'cm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setFont('times', '', 10);
$pdf->SetAutoPageBreak(true , 2.5);



//conex�o com o banco e consultas
include("dbconnect.inc.php");




//tabela de dados
$result = mysqli_query($_SG['link'], "SELECT c.nome, c.numInscricao, c.cpf,c.linhaPesquisa, c.areaAtuacao, c.notaProva1, c.notaProva2 FROM candidato c WHERE c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND (((c.notaProva1+c.notaProva2)/2)>=7) AND c.tipoProcesso=1 ORDER BY c.linhaPesquisa, c.areaAtuacao, c.nome");
$total_results = mysqli_num_rows($result);

$pdf->SetFont('arial','',9);
for ($i=0;$i<$total_results;$i++){


    $nome = mysql_result($result, $i, 'nome');
	$numInscricao = mysql_result($result, $i, 'numInscricao');
    $linhaPesquisa = mysql_result($result, $i, 'linhaPesquisa');
	$cpf = mysql_result($result, $i, 'cpf');	
    $at = mysql_result($result, $i, 'areaAtuacao');
	
	$cpfLimpo = remove($cpf);
	$cpfFormatado = mascara($cpfLimpo,'###.XXX.XXX-##');
	
	$nota1 = mysql_result($result, $i, 'notaProva1');
    $nota2 = mysql_result($result, $i, 'notaProva2');
    $mediaNota = ($nota1+$nota2)/2; 
	$nota = number_format($mediaNota, 1, '.', '');
	
	if ($linhaPesquisa=="linha1"){
		$linha = "Linha 1";
	}else if ($linhaPesquisa=="linha2"){
		$linha = "Linha 2";
	}else if ($linhaPesquisa=="linha3"){
		$linha = "Linha 3";
	}
if ($at=='musica'){
	$area = 'M�sica';
}else if ($at=='teatro'){
	$area = 'Teatro';
}else if ($at=='cinema'){
	$area = 'Cinema';
        
}else if ($at=='danca'){
	$area = 'Dan�a';
}else if ($at=='visuais'){
	$area = 'Artes Visuais';
}else if ($at=='outros'){
	$area = 'Outros';
}
    
   
    
       $pdf->setX(1.7);
       $pdf->Cell(7.5,0.6,$nome,1,0,"L");
       $pdf->Cell(4,0.6,$numInscricao,1,0,"C");
	   $pdf->Cell(3,0.6,$linha,1,0,"C");
	   $pdf->Cell(3,0.6,$area,1,1,"C");


    
    
}
  
//fun��o para exibir o relat�rio gerado em um arquivo .pdf no navegador
$pdf->Output("relatorioAprovadosProjetoMest.pdf","I");


?>




