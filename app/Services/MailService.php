<?php

namespace App\Services;

// Garante que o PHPMailer está disponível
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private $mail;

    public function __construct()
    {
        // Se der erro aqui, o AuthController vai capturar agora (graças ao Throwable)
        $this->mail = new PHPMailer(true);

        // --- CONFIGURAÇÃO SMTP (Preencha com dados reais) ---
        $this->mail->isSMTP();
        $this->mail->Host       = $_ENV['MAIL_HOST'];
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $_ENV['MAIL_USERNAME_PRINC'];
        $this->mail->Password   = $_ENV['MAIL_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // ou ENCRYPTION_STARTTLS
        $this->mail->Port       = 465; // ou 587

        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $this->mail->CharSet = $_ENV['MAIL_CHARSET'] ?? 'UTF-8';
        $this->mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $this->mail->isHTML(true);
    }

    public function send($toEmail, $subject, $body, $toName = '')
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($toEmail, $toName);

            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->AltBody = strip_tags($body);

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Loga o erro real no arquivo de log do servidor (error_log)
            error_log("MailService Error: " . $this->mail->ErrorInfo);
            return false;
        } finally {
            // Limpa os destinatários para o próximo envio, se o objeto for reutilizado.
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            $this->mail->clearBCCs();
        }
    }
}
