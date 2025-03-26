<?php
require('fpdf.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o banco de dados
$conn = mysqli_connect('localhost', 'psppgartes', '1RNYK]lTMJapb24s', 'st_psppgartes');
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

$tipoProcesso = isset($_GET['tipo']) ? intval($_GET['tipo']) : 1;
$tituloProcesso = ($tipoProcesso === 1) ? "Mestrado" : "Doutorado";

// Modifiquei a consulta para incluir ORDER BY txtNome
$query = "SELECT txtNome, txtCPF, optCampo, optLinhaPesquisa, txtOrientador1 FROM inscritos2025 WHERE optTipoProcesso = $tipoProcesso ORDER BY txtNome ASC";
$result = mysqli_query($conn, $query);

function removerCaracteresEspeciais($texto) {
    return preg_replace('/[^\w\s]/u', '', mb_strtoupper($texto, 'UTF-8'));
}

function utf8ParaIso($texto) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texto);
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, utf8ParaIso('PROGRAMA DE PÓS-GRADUAÇÃO EM ARTES'), 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, utf8ParaIso("PROCESSO SELETIVO 2025 - LISTA DE CANDIDATOS INSCRITOS ($tituloProcesso)"), 0, 1, 'C');
$pdf->Ln(5);

// Cabeçalho da tabela
$pdf->SetFont('Arial', 'B', 8);
$larguras = [50, 30, 20, 50, 40]; 
$cabecalhos = ['Nome', 'CPF', 'Área', 'Linha de Pesquisa', 'Orientador Escolhido'];

foreach ($cabecalhos as $i => $cab) {
    $pdf->Cell($larguras[$i], 10, utf8ParaIso($cab), 1, 0, 'C');
}
$pdf->Ln();

// Função para calcular a altura da linha
function calculaAlturaLinha($pdf, $textos, $larguras) {
    $alturaMax = 0;
    foreach ($textos as $i => $texto) {
        $numLinhas = $pdf->GetStringWidth($texto) / ($larguras[$i] - 2); // Ajuste para margem interna
        $altura = ceil($numLinhas) * 5; // 5 é a altura padrão da linha
        $alturaMax = max($alturaMax, $altura);
    }
    return max($alturaMax, 5); // Garantir altura mínima
}

$pdf->SetFont('Arial', '', 8);
$alturaLinha = 10; // Altura fixa para duas linhas
$margemRodape = 15; // Margem inferior da página

function formatarCampo($optCampo) {
    switch ($optCampo) {
        case 1: return 'Teatro';
        case 2: return 'Música';
        case 3: return 'Dança';
        case 4: return 'Artes Visuais';
        case 5: return 'Cinema';
        default: return 'Desconhecido';
    }
}

function verificaQuebraPagina($pdf, $alturaLinha, $margemRodape) {
    if ($pdf->GetY() + $alturaLinha > $pdf->GetPageHeight() - $margemRodape) {
        $pdf->AddPage();
    }
}

while ($row = mysqli_fetch_assoc($result)) {
    $dados = [
        utf8ParaIso(removerCaracteresEspeciais($row['txtNome'])),
        $row['txtCPF'],
        utf8ParaIso(formatarCampo($row['optCampo'])), // Formatar a área corretamente
        utf8ParaIso($row['optLinhaPesquisa']),
        utf8ParaIso($row['txtOrientador1'])
    ];

    verificaQuebraPagina($pdf, $alturaLinha, $margemRodape);

    $yInicio = $pdf->GetY();
    $xInicio = $pdf->GetX();

    // Criar células e adicionar bordas corretamente
    foreach ($larguras as $i => $largura) {
        $pdf->Rect($xInicio, $yInicio, $largura, $alturaLinha); // Desenha a borda
        $pdf->SetXY($xInicio, $yInicio);
        $pdf->MultiCell($largura, $alturaLinha / 2, $dados[$i], 0, 'L');
        $xInicio += $largura;
    }

    $pdf->SetY($yInicio + $alturaLinha); // Ajusta para a próxima linha
}

// Salvar o PDF
$nomeArquivo = "Candidatos_Inscritos_2025_" . strtolower($tituloProcesso) . ".pdf";
$nomeArquivo = str_replace(' ', '_', $nomeArquivo);

$caminhoLocal = dirname(__FILE__) . "/gerados/$nomeArquivo";
if (!file_exists(dirname(__FILE__) . "/gerados")) {
    mkdir(dirname(__FILE__) . "/gerados", 0777, true);
}

$pdf->Output('F', $caminhoLocal);

// ENVIO DO ARQUIVO VIA FTP
$ftp_host = "br860.hostgator.com.br";
$ftp_user = "pokkin57";
$ftp_pass = "05[3=g_B1eRRue4(0kt%5Kg5aVRzq";
$ftp_caminho_remoto = "/psppgartes.pokkins.com/pdfs/$nomeArquivo";

$conn_id = ftp_connect($ftp_host);
if (!$conn_id) {
    die("Erro ao conectar ao FTP.");
}
$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
if (!$login_result) {
    die("Erro ao autenticar no FTP.");
}
ftp_pasv($conn_id, true);
if (ftp_put($conn_id, $ftp_caminho_remoto, $caminhoLocal, FTP_BINARY)) {
    echo "Arquivo enviado com sucesso!<br>";
    echo "Baixe aqui: <a href='https://psppgartes.pokkins.com/pdfs/$nomeArquivo' target='_blank'>Download</a>";
} else {
    echo "Erro ao enviar o arquivo via FTP.";
}
ftp_close($conn_id);
mysqli_close($conn);
?>