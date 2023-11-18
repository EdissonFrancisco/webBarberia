<?php
namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
  public $email;
  public $nombre;
  public $token;
  public function __construct($email, $nombre, $token) {
    $this->email = $email;
    $this->nombre = $nombre;
    $this->token = $token;
  }

  private function configuracionEmail() {
    //crear el objeto de email
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Port = 2525;
    $mail->Username = '59ae1d94d9207b';
    $mail->Password = '0a839c34dd41cb';
    $mail->setFrom('cuentas@appsalon.com');
    $mail->addAddress('cuentas@appsalon.com', 'Appsalon.com');

    $mail->isHTML(TRUE);
    $mail->CharSet = 'UTF-8';

    return $mail;
  }

  public function enviarConfirmacion() {
    //conectar con las credenciales para envio
    $mail = $this->configuracionEmail();
    $mail->Subject = 'Confirmacion de cuenta';

    $contenido = "<html>";
    $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado una cuenta en AppSalon para confirmar 
    tu cuenta ingresa al siguiente enlase </p>";
    $contenido .= "<p>ingresa aqui: <a href='http://localhost:3000/confirmar-cuenta?token=" . $this->token . "'> Confirmar Cuenta </a></p>";
    $contenido .= "<p>si no has sido tu por favor has caso omiso a este mensaje</p>";
    $contenido .= "</html>";

    $mail->Body = $contenido;

    //enviar el email
    $mail->send();

  }
  public function enviarInformacion() {
    $mail = $this->configuracionEmail();
    $mail->Subject = 'Reestablecer Password';

    $contenido = "<html>";
    $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado reestablecer tu Password ingresa al siguiente enlase </p>";
    $contenido .= "<p>ingresa aqui: <a href='http://localhost:3000/recuperar?token=" . $this->token . "'> Reestablecer Cuenta </a></p>";
    $contenido .= "<p>si no has sido tu por favor has caso omiso a este mensaje</p>";
    $contenido .= "</html>";

    $mail->Body = $contenido;

    //enviar el email
    $mail->send();
  }

}