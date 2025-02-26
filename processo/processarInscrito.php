<?php

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Função para validar dados de entrada
function validar_dados($dados) {
    return htmlspecialchars(strip_tags($dados));
}

// Função para enviar o e-mail
function send_email($nome, $email, $mensagem, $data_envio, $hora_envio) {
    $sender_email = 'psppgartes@pokkins.com';
    $sender_password = 'senha';
    $recipient_email = 'thiago.correa@icen.ufpa.br';
    $subject = 'Inscrição Recebida';

    $body = "
        <html>
        <head>
            <title>Inscrição Recebida</title>
        </head>
        <body>
            <h2>Nova mensagem de contato</h2>
            <p><strong>Nome:</strong> $nome</p>
            <p><strong>E-mail:</strong> $email</p>
            <p><strong>Mensagem:</strong></p>
            <p>$mensagem</p>
            <p><strong>Data de envio:</strong> $data_envio</p>
            <p><strong>Hora de envio:</strong> $hora_envio</p>
        </body>
        </html>
    ";

    $mail = new PHPMailer();

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.titan.email'; // Ajuste o servidor SMTP
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = $sender_email;
        $mail->Password = $sender_password;
        $mail->SMTPSecure = 'tls';

        $mail->setFrom($sender_email);
        $mail->addAddress($recipient_email);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $body;

        if ($mail->send()) {
            echo 'E-mail enviado com sucesso.';
        } else {
            echo 'Erro ao enviar o e-mail: ' . $mail->ErrorInfo;
        }

    } catch (Exception $e) {
        echo 'Erro ao enviar o e-mail: ' . $e->getMessage();
    }
}

// Função para inserir dados no banco de dados
function inserir_no_banco($nome, $email) {
    $conn = new mysqli('psppgartes.ufpa.br', 'psppgartes', 'senha', 'st_psppgartes');

    if ($conn->connect_error) {
        die('Conexão falhou: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO candidato (nome, email) VALUES (?, ?)");
    
    if ($stmt === false) {
        die('Erro ao preparar a consulta: ' . $conn->error);
    }

    $stmt->bind_param('sssiis', $nome, $email);

    if ($stmt->execute()) {
        echo "Dados inseridos com sucesso!";
    } else {
        echo "Erro ao inserir os dados: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Recuperar dados do formulário
$nome = validar_dados($_POST['nome']);
$email = validar_dados($_POST['email']);
$mensagem = validar_dados($_POST['mensagem']);
$data_envio = date('d/m/Y');
$hora_envio = date('H:i:s');

// Inserir dados no banco
inserir_no_banco($nome, $email);

// Enviar e-mail
send_email($nome, $email, $mensagem, $data_envio, $hora_envio);

?>