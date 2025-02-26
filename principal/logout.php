<?php
	session_start(); // Inicia a sess�o
	session_destroy(); // Destr�i a sess�o limpando todos os valores salvos
	header("Location: ../"); exit; // Redireciona o visitante
?>