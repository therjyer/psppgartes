<?php

include("seguranca.php"); // Inclui o arquivo com o sistema de seguran�a
protegePagina(); // Chama a fun��o que protege a p�gina
//include("funcoes.php");
//conex�o com o banco e consultas
include("dbconnect.inc.php");

//importa bibliotecas necess�rias
require ("fpdf.php");
define("FPDF_PATH", "font");

$idProcesso = $_GET['idProcesso'];
$consulta = mysqli_query($_SG['link'], "SELECT p.processo FROM procseletivo p WHERE p.idProcesso = $idProcesso");
$processo = mysql_result($consulta, 0, 'processo');

//$alocacao = mysql_result($consulta, 0, 'alocacaoProva');


class PDF extends FPDF {

    function Header() {
        global $pdf, $processo;
        //$processo = $_GET['processo']; 
        //t�tulo do relat�rio
        $titulo = "PROGRAMA DE P�S-GRADUA��O EM ARTES";
        $pdf->setXY(1.5, 1);
        $pdf->SetFont('arial', 'b', 9);
        $pdf->MultiCell(25.7, 0.8, $titulo, 0, 'C');
        $pdf->Ln();

        $pdf->SetFillColor(0, 0, 0);
        $pdf->setXY(1, 1.7);
        $pdf->Cell(28, 0.1, ' ', 1, 1, "C");
        $pdf->setXY(1, 1.8);
        $pdf->Cell(28, 0.1, ' ', 1, 1, "C", true);

        //per�odo da busca
        $periodo = " PROCESSO SELETIVO " . $processo . " - RESULTADO FINAL (DOUTORADO)";
        $pdf->setXY(1.5, 2);
        $pdf->SetFont('arial', '', 10);
        $pdf->MultiCell(25.7, 0.8, $periodo, 0, 'C');
        $pdf->Ln();
    }

    function Footer() {
        global $pdf;
        $pdf->setY(24);
        $pdf->SetFont('arial', '', 8);
        $pdf->Cell(0, 10, 'P�gina ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }

}

$pdf = new PDF('L', 'cm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setFont('times', '', 10);
$pdf->SetAutoPageBreak(true, 2.5);


/* 2018
$linha1Ac = 4; //3 vagas e recebe mais 1 de er
$linha1PADT = 1; //preenchidos
$linha1Er = 1; // 1 vaga 0 preenchidas - vai para o ac
$linha1Tc = 0;
//LINHA2
$linha2Ac = 10; //5vagas - 5 preenchidas + 3 dosoutros grupos  //depois foi acescentado mais 2 vagas que sobraram
$linha2PADT = 0; //1 vaga 0 preenchida - vai 1 para o ac
$linha2Er = 0;  //1 vaga 0 preenchida- vai 1 para o ac
$linha2Tc = 0; //1 vaga 0 preenchida- vai 1 para o ac
//LINHA3
$linha3Ac = 5; //5vagas - 5 preenchidas
$linha3PADT = 1; //2 vagas - 1 preenchida - 1 para doar para tc
$linha3Er = 2;  //2 vagas - 1 preenchida - 1 sobrando
$linha3Tc = 2;  // 1 vaga + 1 doa��o de padt
*/

//PARA INTERFACEAR COM AS VAGAS PARAMETRIZADAS 2019
//LINHA1
$linha1Ac = 1; 
$linha1PADT = 0; //preenchidos
$linha1Er = 0; // 1 vaga 0 preenchidas - vai para o ac

//LINHA2
$linha2Ac = 4; 
$linha2PADT = 1; //1 vaga 0 preenchida - vai 1 para o ac
$linha2Er = 1;  //1 vaga 0 preenchida- vai 1 para o ac

//LINHA3
$linha3Ac = 4; //5vagas - 5 preenchidas
$linha3PADT = 2; //2 vagas - 1 preenchida - 1 para doar para tc
$linha3Er = 1;  //2 vagas - 1 preenchida - 1 sobrando


//AMPLA CONCORR�NCIA  BEGIN ############################################################################

$pdf->SetFont('arial', 'B', 9);
$pdf->setXY(1, 3.4);
$pdf->Cell(27.2, 0.6, 'Ampla Concorr�ncia', 1, 1, "C");

//cabe�alho da tabela
$pdf->SetFont('arial', 'B', 9);
$pdf->setXY(1, 4);
$pdf->Cell(1, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(2, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(1.5, 0.6, '�rea', 1, 0, "C");
$pdf->Cell(7.2, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Proj Pesquisa ', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Prova Escrita', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'An�lise Curr', 1, 0, "C");
$pdf->Cell(1.7, 0.6, 'Prova Oral', 1, 0, "C");
$pdf->Cell(2, 0.6, 'Nota Final', 1, 0, "C");
$pdf->Cell(5.2, 0.6, 'Resultado Final', 1, 1, "C");


//tabela de dados
//$result = mysqli_query($_SG['link'], "SELECT c.idCandidato, c.nome, c.numInscricao, c.linhaPesquisa, c.cpf, c.notaEntrevista, c.notaProva1, c.notaProva2, c.notaAnteprojeto1, c.notaAnteprojeto2, c.notaEntrevista, c.notaProfile, c.pontuacaoCurriculo, c.notaFinal FROM candidato c WHERE  c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 2 AND c.notaEntrevista>=7 AND c.cotas = 'ac' ORDER BY c.linhaPesquisa, c.notaFinal DESC ");
$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa, c.areaAtuacao,   ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, ((c.notaProva1+c.notaProva2)) AS notaProva , c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, CONVERT((((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) + ((c.notaProva1+c.notaProva2)) + c.pontuacaoCurriculo + c.notaEntrevista), DECIMAL(10,2)) AS notaFinalCalc
FROM candidato c WHERE  c.processo = 45 AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 2 AND (c.pontuacaoCurriculo>=7) AND c.cotas = 'ac' ORDER BY c.linhaPesquisa, notaFinalCalc DESC, notaAnteprojeto DESC");

$total_results = mysqli_num_rows($result);



$pdf->SetFont('arial', '', 9);

$maiorPontuacao = 0;
for ($i = 0; $i < $total_results; $i++) {
    $pontuacao = mysql_result($result, $i, 'pontuacaoCurriculo');
    if ($pontuacao > $maiorPontuacao)
        $maiorPontuacao = $pontuacao;
}

$contL1 = 0;
$contL2 = 0;
$contL3 = 0;




for ($i = 0; $i < $total_results; $i++) {

    //if ($i == 15) {
    // $pdf->setX(1); 
    //  $pdf->Cell(27.2, 0.6, 'Do 9� ao 10� LINHAS 02 OU 03, CONFORME ADENDO PUBLICADO EM 13/06/2018', 1, 1, "C");  
    //}    
    
    $linhaPesquisa = mysql_result($result, $i, 'linhaPesquisa');
    $idCandidato = mysql_result($result, $i, 'idCandidato');
//Notas j� vem calculadas na query

    $notaAnteprojeto = mysql_result($result, $i, 'notaAnteprojeto');
    $notaProva = mysql_result($result, $i, 'notaProva');
    $pontuacaoCurriculo = mysql_result($result, $i, 'pontuacaoCurriculo');
    $notaEntrevista = mysql_result($result, $i, 'notaEntrevista');
    $notaFinal = mysql_result($result, $i, 'notaFinalCalc');
        $areaAtuacao = mysql_result($result, $i, 'areaAtuacao');

    switch ($linhaPesquisa) {
        case "linha1":
            $linha = "Linha 1";
            $contL1++;
            $colocacao = $contL1 . '�';
            if ($notaEntrevista >= 7) {
                if ($contL1 <= $linha1Ac) {
                    $resultado = "Aprovado e classificado";
                } else
                    $resultado = "Aprovado, mas n�o classificado";
            } else {
                $resultado = "Reprovado";
            }
            break;
        case "linha2":
            $linha = "Linha 2";
            $contL2++;
            $colocacao = $contL2 . '�';
            if ($notaEntrevista >= 7) {
                if ($contL2 <= $linha2Ac) {
                    $resultado = "Aprovado e classificado";
                } else
                    $resultado = "Aprovado, mas n�o classificado";
            } else {
                $resultado = "Reprovado";
            }
            break;
        case "linha3":
            $linha = "Linha 3";
            $contL3++;
            $colocacao = $contL3 . '�';
            if ($notaEntrevista >= 7) {
                if ($contL3 <= $linha3Ac) {
                    $resultado = "Aprovado e classificado";
                } else
                    $resultado = "Aprovado, mas n�o classificado";
            } else {
                $resultado = "Reprovado";
            }
            break;

        default:
            break;
    }

    //if ($i >= 15 ) {
    //if ($i <= 16) {
    //    $linha = "Linha 2 ou 3";
    //}
        
    //}
    //NOTAS NOVO ##################################333


    $nome = mysql_result($result, $i, 'nome');
$nome = substr($nome, 0, 41);




    $query = "UPDATE candidato SET notaFinal='$notaFinal' WHERE idCandidato = $idCandidato";
    $exec = mysql_query($query, $conexao);

    $pdf->setX(1);
    $pdf->Cell(1, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(2, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(1.5, 0.6, $areaAtuacao, 1, 0, "C");
    $pdf->Cell(7.2, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaProva, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(1.7, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, $resultado, 1, 1, "C");
}

//AMPLA CONCORR�NCIA  END ############################################################################
/*
//TERMO DE COOPERA��O  BEGIN ############################################################################
$pdf->Ln();
$pdf->Ln();

$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);   //$pdf->setXY(1, 3.4);
$pdf->Cell(27.2, 0.6, 'TERMO DE COOPERA��O-UFPA/UNIFAP/IDAP', 1, 1, "C");

//cabe�alho da tabela
$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);  //$pdf->setXY(1, 4);
$pdf->Cell(1, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(1.5, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(1.5, 0.6, '�rea', 1, 0, "C");
$pdf->Cell(7.2, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Proj Pesquisa ', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Prova Escrita', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'An�lise Curr', 1, 0, "C");
$pdf->Cell(2, 0.6, 'Prova Oral', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Nota Final', 1, 0, "C");
$pdf->Cell(5.2, 0.6, 'Resultado Final', 1, 1, "C");


//tabela de dados
//$result = mysqli_query($_SG['link'], "SELECT c.idCandidato, c.nome, c.numInscricao, c.linhaPesquisa, c.cpf, c.notaEntrevista, c.notaProva1, c.notaProva2, c.notaAnteprojeto1, c.notaAnteprojeto2, c.notaEntrevista, c.notaProfile, c.pontuacaoCurriculo, c.notaFinal FROM candidato c WHERE  c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 2 AND c.notaEntrevista>=7 AND c.cotas = 'ac' ORDER BY c.linhaPesquisa, c.notaFinal DESC ");
$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa, c.areaAtuacao, ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, ((c.notaProva1+c.notaProva2)/2) AS notaProva , c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) +  ((c.notaProva1+c.notaProva2)/2) + c.pontuacaoCurriculo + c.notaEntrevista) AS notaFinalCalc
FROM candidato c WHERE  c.processo = 44 AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 2 AND c.notaEntrevista>=7 AND c.cotas = 'tc' ORDER BY c.linhaPesquisa, notaFinalCalc DESC, notaAnteprojeto DESC");

$total_results = mysqli_num_rows($result);
$pdf->SetFont('arial', '', 9);

$maiorPontuacao = 0;
for ($i = 0; $i < $total_results; $i++) {
    $pontuacao = mysql_result($result, $i, 'pontuacaoCurriculo');
    if ($pontuacao > $maiorPontuacao)
        $maiorPontuacao = $pontuacao;
}

$contL1 = 0;
$contL2 = 0;
$contL3 = 0;


for ($i = 0; $i < $total_results; $i++) {

    $linhaPesquisa = mysql_result($result, $i, 'linhaPesquisa');
    $idCandidato = mysql_result($result, $i, 'idCandidato');

//Notas j� vem calculadas na query

    $notaAnteprojeto = mysql_result($result, $i, 'notaAnteprojeto');
    $notaProva = mysql_result($result, $i, 'notaProva');
    $pontuacaoCurriculo = mysql_result($result, $i, 'pontuacaoCurriculo');
    $notaEntrevista = mysql_result($result, $i, 'notaEntrevista');
    $notaFinal = mysql_result($result, $i, 'notaFinalCalc');
$areaAtuacao = mysql_result($result, $i, 'areaAtuacao');

    switch ($linhaPesquisa) {
        case "linha1":
            $linha = "Linha 1";
            $contL1++;
            $colocacao = $contL1 . '�';
            if ($notaEntrevista >= 7) {
                if ($contL1 <= $linha1Tc) {
                    $resultado = "Aprovado e classificado";
                } else
                    $resultado = "Aprovado, mas n�o classificado";
            } else {
                $resultado = "Reprovado";
            }
            break;
        case "linha2":
            $linha = "Linha 2";
            $contL2++;
            $colocacao = $contL2 . '�';
            if ($notaEntrevista >= 7) {
                if ($contL2 <= $linha2Tc) {
                    $resultado = "Aprovado e classificado";
                } else
                    $resultado = "Aprovado, mas n�o classificado";
            } else {
                $resultado = "Reprovado";
            }
            break;
        case "linha3":
            $linha = "Linha 3";
            $contL3++;
            $colocacao = $contL3 . '�';
            if ($notaEntrevista >= 7) {
                if ($contL3 <= $linha3Tc) {
                    $resultado = "Aprovado e classificado";
                } else
                    $resultado = "Aprovado, mas n�o classificado";
            } else {
                $resultado = "Reprovado";
            }
            break;

        default:
            break;
    }

    //NOTAS NOVO ##################################333


    $nome = mysql_result($result, $i, 'nome');




    $query = "UPDATE candidato SET notaFinal='$notaFinal' WHERE idCandidato = $idCandidato";
    $exec = mysql_query($query, $conexao);



    $pdf->setX(1);
        $pdf->Cell(1, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(1.5, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(1.5, 0.6, $areaAtuacao, 1, 0, "C");
    $pdf->Cell(7.2, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaProva, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, $resultado, 1, 1, "C");
}

//TERMO DE COOPERA��O   END ############################################################################
 */
 //ETNORACIAL  BEGIN ############################################################################
$pdf->Ln();
$pdf->Ln();

$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);   //$pdf->setXY(1, 3.4);
$pdf->Cell(27.2, 0.6, 'ETNORACIAL', 1, 1, "C");

//cabe�alho da tabela
$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);  //$pdf->setXY(1, 4);
$pdf->Cell(1, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(1.5, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(1.5, 0.6, '�rea', 1, 0, "C");
$pdf->Cell(7.2, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Proj Pesquisa ', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Prova Escrita', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'An�lise Curr', 1, 0, "C");
$pdf->Cell(2, 0.6, 'Prova Oral', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Nota Final', 1, 0, "C");
$pdf->Cell(5.2, 0.6, 'Resultado Final', 1, 1, "C");


//tabela de dados
//$result = mysqli_query($_SG['link'], "SELECT c.idCandidato, c.nome, c.numInscricao, c.linhaPesquisa, c.cpf, c.notaEntrevista, c.notaProva1, c.notaProva2, c.notaAnteprojeto1, c.notaAnteprojeto2, c.notaEntrevista, c.notaProfile, c.pontuacaoCurriculo, c.notaFinal FROM candidato c WHERE  c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 2 AND c.notaEntrevista>=7 AND c.cotas = 'ac' ORDER BY c.linhaPesquisa, c.notaFinal DESC ");
$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa, c.areaAtuacao, ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, ((c.notaProva1+c.notaProva2)) AS notaProva , c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, (((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) +  ((c.notaProva1+c.notaProva2)) + c.pontuacaoCurriculo + c.notaEntrevista) AS notaFinalCalc
FROM candidato c WHERE  c.processo = 45 AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 2 AND c.pontuacaoCurriculo>=7 AND c.cotas = 'er' ORDER BY c.linhaPesquisa, notaFinalCalc DESC, notaAnteprojeto DESC");

$total_results = mysqli_num_rows($result);
$pdf->SetFont('arial', '', 9);

$maiorPontuacao = 0;
for ($i = 0; $i < $total_results; $i++) {
    $pontuacao = mysql_result($result, $i, 'pontuacaoCurriculo');
    if ($pontuacao > $maiorPontuacao)
        $maiorPontuacao = $pontuacao;
}

$contL1 = 0;
$contL2 = 0;
$contL3 = 0;


for ($i = 0; $i < $total_results; $i++) {

    $linhaPesquisa = mysql_result($result, $i, 'linhaPesquisa');
    $idCandidato = mysql_result($result, $i, 'idCandidato');

//Notas j� vem calculadas na query

    $notaAnteprojeto = mysql_result($result, $i, 'notaAnteprojeto');
    $notaProva = mysql_result($result, $i, 'notaProva');
    $pontuacaoCurriculo = mysql_result($result, $i, 'pontuacaoCurriculo');
    $notaEntrevista = mysql_result($result, $i, 'notaEntrevista');
    $notaFinal = mysql_result($result, $i, 'notaFinalCalc');
$areaAtuacao = mysql_result($result, $i, 'areaAtuacao');

    switch ($linhaPesquisa) {
        case "linha1":
            $linha = "Linha 1";
            $contL1++;
            $colocacao = $contL1 . '�';
            if ($notaEntrevista >= 7) {
                if ($contL1 <= $linha1Er) {
                    $resultado = "Aprovado e classificado";
                } else
                    $resultado = "Aprovado, mas n�o classificado";
            } else {
                $resultado = "Reprovado";
            }
            break;
        case "linha2":
            $linha = "Linha 2";
            $contL2++;
            $colocacao = $contL2 . '�';
            if ($notaEntrevista >= 7) {
                if ($contL2 <= $linha2Er) {
                    $resultado = "Aprovado e classificado";
                } else
                    $resultado = "Aprovado, mas n�o classificado";
            } else {
                $resultado = "Reprovado";
            }
            break;
        case "linha3":
            $linha = "Linha 3";
            $contL3++;
            $colocacao = $contL3 . '�';
            if ($notaEntrevista >= 7) {
                if ($contL3 <= $linha3Er) {
                    $resultado = "Aprovado e classificado";
                } else
                    $resultado = "Aprovado, mas n�o classificado";
            } else {
                $resultado = "Reprovado";
            }
            break;

        default:
            break;
    }    


    //NOTAS NOVO ##################################333


    $nome = mysql_result($result, $i, 'nome');


    $query = "UPDATE candidato SET notaFinal='$notaFinal' WHERE idCandidato = $idCandidato";
    $exec = mysql_query($query, $conexao);



    $pdf->setX(1);
        $pdf->Cell(1, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(1.5, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(1.5, 0.6, $areaAtuacao, 1, 0, "C");
    $pdf->Cell(7.2, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaProva, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, $resultado, 1, 1, "C");
}

//ETNORRACIAL   END ############################################################################
//PADT  BEGIN ############################################################################
$pdf->Ln();


$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);   //$pdf->setXY(1, 3.4);
$pdf->Cell(27.2, 0.6, 'IES - Amazonia Legal e Regi�o Nordeste', 1, 1, "C");

//cabe�alho da tabela
$pdf->SetFont('arial', 'B', 9);
$pdf->setX(1);  //$pdf->setXY(1, 4);
$pdf->Cell(1, 0.6, 'Coloc.', 1, 0, "C");
$pdf->Cell(1.5, 0.6, 'Linha', 1, 0, "C");
$pdf->Cell(1.5, 0.6, '�rea', 1, 0, "C");
$pdf->Cell(7.2, 0.6, 'Nome do candidato', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Proj Pesquisa ', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Prova Escrita', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'An�lise Curr', 1, 0, "C");
$pdf->Cell(2, 0.6, 'Prova Oral', 1, 0, "C");
$pdf->Cell(2.2, 0.6, 'Nota Final', 1, 0, "C");
$pdf->Cell(5.2, 0.6, 'Resultado Final', 1, 1, "C");


//tabela de dados
//$result = mysqli_query($_SG['link'], "SELECT c.idCandidato, c.nome, c.numInscricao, c.linhaPesquisa, c.cpf, c.notaEntrevista, c.notaProva1, c.notaProva2, c.notaAnteprojeto1, c.notaAnteprojeto2, c.notaEntrevista, c.notaProfile, c.pontuacaoCurriculo, c.notaFinal FROM candidato c WHERE  c.processo = $idProcesso AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 2 AND c.notaEntrevista>=7 AND c.cotas = 'ac' ORDER BY c.linhaPesquisa, c.notaFinal DESC ");
$result = mysqli_query($_SG['link'], "SELECT  c.idCandidato, c.nome, c.linhaPesquisa, c.areaAtuacao,   ((c.notaAnteprojeto1+c.notaAnteprojeto2)/2) AS notaAnteprojeto, ((c.notaProva1+c.notaProva2)) AS notaProva , c.pontuacaoCurriculo, c.notaEntrevista , c.notaFinal, (((c.notaAnteprojeto1+c.notaAnteprojeto2)) +  ((c.notaProva1+c.notaProva2)) + c.pontuacaoCurriculo + c.notaEntrevista) AS notaFinalCalc
FROM candidato c WHERE  c.processo = 45 AND c.estadoHomologacao=1 AND c.estado=1 AND c.tipoProcesso = 2 AND c.pontuacaoCurriculo>=7 AND c.cotas = 'padt' ORDER BY c.linhaPesquisa, notaFinalCalc DESC, notaAnteprojeto DESC");

$total_results = mysqli_num_rows($result);
$pdf->SetFont('arial', '', 9);

$maiorPontuacao = 0;
for ($i = 0; $i < $total_results; $i++) {
    $pontuacao = mysql_result($result, $i, 'pontuacaoCurriculo');
    if ($pontuacao > $maiorPontuacao)
        $maiorPontuacao = $pontuacao;
}

$contL1 = 0;
$contL2 = 0;
$contL3 = 0;


for ($i = 0; $i < $total_results; $i++) {

    $linhaPesquisa = mysql_result($result, $i, 'linhaPesquisa');
    $idCandidato = mysql_result($result, $i, 'idCandidato');

//Notas j� vem calculadas na query

    $notaAnteprojeto = mysql_result($result, $i, 'notaAnteprojeto');
    $notaProva = mysql_result($result, $i, 'notaProva');
    $pontuacaoCurriculo = mysql_result($result, $i, 'pontuacaoCurriculo');
    $notaEntrevista = mysql_result($result, $i, 'notaEntrevista');
    $notaFinal = mysql_result($result, $i, 'notaFinalCalc');
$areaAtuacao = mysql_result($result, $i, 'areaAtuacao');

    switch ($linhaPesquisa) {
        case "linha1":
            $linha = "Linha 1";
            $contL1++;
            $colocacao = $contL1 . '�';
            if ($notaEntrevista >= 7) {
                if ($contL1 <= $linha1PADT) {
                    $resultado = "Aprovado e classificado";
                } else
                    $resultado = "Aprovado, mas n�o classificado";
            } else {
                $resultado = "Reprovado";
            }
            break;
        case "linha2":
            $linha = "Linha 2";
            $contL2++;
            $colocacao = $contL2 . '�';
            if ($notaEntrevista >= 7) {
                if ($contL2 <= $linha2PADT) {
                    $resultado = "Aprovado e classificado";
                } else
                    $resultado = "Aprovado, mas n�o classificado";
            } else {
                $resultado = "Reprovado";
            }
            break;
        case "linha3":
            $linha = "Linha 3";
            $contL3++;
            $colocacao = $contL3 . '�';
            if ($notaEntrevista >= 7) {
                if ($contL3 <= $linha3PADT) {
                    $resultado = "Aprovado e classificado";
                } else
                    $resultado = "Aprovado, mas n�o classificado";
            } else {
                $resultado = "Reprovado";
            }
            break;

        default:
            break;
    } 



    //NOTAS NOVO ##################################333


    $nome = mysql_result($result, $i, 'nome');


    $query = "UPDATE candidato SET notaFinal='$notaFinal' WHERE idCandidato = $idCandidato";
    $exec = mysql_query($query, $conexao);



    $pdf->setX(1);
        $pdf->Cell(1, 0.6, $colocacao, 1, 0, "C");
    $pdf->Cell(1.5, 0.6, $linha, 1, 0, "C");
    $pdf->Cell(1.5, 0.6, $areaAtuacao, 1, 0, "C");
    $pdf->Cell(7.2, 0.6, $nome, 1, 0, "L");
    $pdf->Cell(2.2, 0.6, number_format($notaAnteprojeto, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaProva, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($pontuacaoCurriculo, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2, 0.6, number_format($notaEntrevista, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(2.2, 0.6, number_format($notaFinal, 2, ',', ' '), 1, 0, "C");
    $pdf->Cell(5.2, 0.6, $resultado, 1, 1, "C");
}

//PADT   END ############################################################################



 
  $pdf->Ln();
  $info = "2.6. N�o havendo entre os candidatos dos grupos indicados nos incisos II e III do subitem 2.1 e nos incisos II, III e IV do subitem 2.2, aprovados em n�mero suficiente, para preenchimento das vagas, as vagas remanescentes ser�o revertidas para os candidatos aprovados em ampla concorr�ncia, de acordo com a classifica��o, nas respectivas Linhas de Pesquisa.
2.7. N�o havendo entre os candidatos a ampla concorr�ncia aprovados em n�mero suficiente, para preenchimento das vagas, as vagas remanescentes ser�o revertidas para os candidatos aprovados dos grupos indicados nos incisos II e III do subitem 2.1 e nos incisos II, III e IV, do subitem 2.2, de acordo com a classifica��o, nas respectivas Linhas de Pesquisa";
  $pdf->setX(1.6);
  $pdf->SetFont('arial', '', 10);
  $pdf->MultiCell(0, 0.5, $info, 0, 'L');
  $pdf->Ln();



//fun��o para exibir o relat�rio gerado em um arquivo .pdf no navegador
$pdf->Output("Relat�rio Geral por �rea - DOUTORADO (Processo Seletivo " . $processo . ").pdf", "I");
?>