<?php
require('fpdf.php');

// Conexão com o banco de dados
$conn = mysqli_connect('localhost', 'psppgartes', '1RNYK]lTMJapb24s', 'st_psppgartes');
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// Verifica se o tipo de processo foi passado na URL (1 = Mestrado, 2 = Doutorado)
$tipoProcesso = isset($_GET['tipo']) ? intval($_GET['tipo']) : 1;

// Define o título do processo
$tituloProcesso = ($tipoProcesso === 1) ? "Mestrado" : "Doutorado";

// Consulta os inscritos conforme o tipo de processo
$query = "SELECT txtNome, numInscricao, txtCPF FROM inscritos2025 WHERE optTipoProcesso = $tipoProcesso";
$result = mysqli_query($conn, $query);

// Função para remover caracteres especiais e acentos
function removerCaracteresEspeciais($texto) {
    // Transforma o texto em maiúsculas
    $texto = mb_strtoupper($texto, 'UTF-8');  
    
    // Remove acentos, cedilhas, e caracteres especiais
    $texto = preg_replace('/[^\w\s]/u', '', $texto);
    return $texto;
}

// Criação do PDF
$pdf = new FPDF();
$pdf->AddPage();

// Adiciona a codificação UTF-8 (isso ajuda a lidar com caracteres especiais)
$pdf->SetAutoPageBreak(true, 15);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, 'PROGRAMA DE POS-GRADUACAO EM ARTES', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, "PROCESSO SELETIVO 2025 - LISTA DE CANDIDATOS INSCRITOS ($tituloProcesso)", 0, 1, 'C');

$pdf->Ln(5);

// Cabeçalho da tabela
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(80, 10, 'Nome do Candidato', 1, 0, 'C');
$pdf->Cell(50, 10, 'N. de Inscricao', 1, 0, 'C');
$pdf->Cell(50, 10, 'CPF', 1, 1, 'C');

// Adiciona os dados da tabela ao PDF
$pdf->SetFont('Arial', '', 10);
while ($row = mysqli_fetch_assoc($result)) {
    // Remover caracteres especiais e transformar para maiúsculas
    $nome = removerCaracteresEspeciais($row['txtNome']);
    $pdf->Cell(80, 10, utf8_decode($nome), 1, 0, 'L');  // A função utf8_decode() deve ser usada para garantir a codificação correta
    $pdf->Cell(50, 10, $row['numInscricao'], 1, 0, 'C');
    $pdf->Cell(50, 10, $row['txtCPF'], 1, 1, 'C');
}

// Nome do arquivo PDF
$nomeArquivo = "Candidatos_Inscritos_2025_" . strtolower($tituloProcesso) . ".pdf";
$nomeArquivo = str_replace(' ', '_', $nomeArquivo);

// Caminho correto para salvar o arquivo no servidor
$caminhoLocal = dirname(__FILE__) . "/gerados/$nomeArquivo"; 

// Verifica e cria a pasta gerados, se necessário
if (!file_exists(dirname(__FILE__) . "/gerados")) {
    mkdir(dirname(__FILE__) . "/gerados", 0777, true);
}

// Salvar o PDF no servidor
$pdf->Output('F', $caminhoLocal);

// --------------------------
// ENVIO DO ARQUIVO VIA FTP
// --------------------------

$ftp_host = "br860.hostgator.com.br";  // Endereço FTP
$ftp_user = "pokkin57";         // Usuário FTP
$ftp_pass = "05[3=g_B1eRRue4(0kt%5Kg5aVRzq"; // Senha FTP
$ftp_port = 21; // Porta FTP padrão (tente 990 se usar FTPS)
$ftp_caminho_remoto = "/psppgartes.pokkins.com/pdfs/$nomeArquivo";  // Caminho no servidor remoto

// Conectar ao FTP
$conn_id = ftp_connect($ftp_host);
if (!$conn_id) {
    die("Erro ao conectar ao FTP.");
}

// Login no FTP
$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
if (!$login_result) {
    die("Erro ao autenticar no FTP.");
}

// Modo passivo para evitar bloqueios
ftp_pasv($conn_id, true);

// Upload do arquivo
if (ftp_put($conn_id, $ftp_caminho_remoto, $caminhoLocal, FTP_BINARY)) {
    echo "Arquivo enviado com sucesso!<br>";
    echo "Baixe aqui: <a href='https://psppgartes.pokkins.com/pdfs/$nomeArquivo' target='_blank'>Download</a>";
} else {
    echo "Erro ao enviar o arquivo via FTP.";
}

// Fecha a conexão FTP
ftp_close($conn_id);

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>