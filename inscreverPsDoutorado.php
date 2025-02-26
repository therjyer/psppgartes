<?php
session_start();
$_SESSION['tipoinscrito'] = '2';

date_default_timezone_set('America/Belem');

// Definir o período de inscrições
$inicioInscricao = new DateTime('2025-02-14');
$fimInscricao = new DateTime('2025-03-13 23:59:59'); // Último segundo do dia 13 de março
$dataAtual = new DateTime("now");

if ($dataAtual >= $inicioInscricao && $dataAtual <= $fimInscricao) {
    header("Location: principal/inscreverPs.php"); // Inscrição permitida
} else {
    header("Location: aviso.html"); // Fora do período de inscrição
}
exit; // Evita execução de código após o redirecionamento
?>